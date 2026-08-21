<?php

namespace App\Services;

use App\Models\Vendor;
use App\Models\Inquiry;
use App\Models\Review;
use App\Models\TagType;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SKAgarwal\GoogleApi\PlacesNew\GooglePlaces;

class VendorService {



    public function getVendorsByRank($type, $filters = null){
        return Vendor::when(
            !empty($type),
            fn ($query) => $query->when(
                is_array($type),
                fn ($q) => $q->whereIn('type', $type),
                fn ($q) => $q->where('type', $type)
            )
        )->where("visible", 1)->withTags($filters)->with('events')->orderByRank();
    }

    public function getVendorByID($id){
        return Vendor::where('id', $id)->first();
    }

    public function getVendorByUUID($uuid){
        return Vendor::where('uuid', $uuid)->first();
    }

    public function uuidToID($uuid){
        if($uuid == null){
            return null;
        }
        return Vendor::where('uuid', $uuid)->value('id');
    }

    public function inquiriesForType($type){
        return Inquiry::where('vendor_type', $type)->where('requestable', 1);
    }

    public function refreshEarnedBadges(Vendor $vendor){
        try {
            //TODO: optimize
            $earnedBadges = [];
            //badge 1: trending
            if ($vendor->trendingBadge()) {
                array_push($earnedBadges, 1);
            }
            if ($vendor->earlyAdopterBadge()) {
                array_push($earnedBadges, 2);
            }
            if ($vendor->fastResponderBadge()) {
                array_push($earnedBadges, 3);
            }
            if ($vendor->communityBuilderBadge()) {
                array_push($earnedBadges, 4);
            }
            $vendor->badges = json_encode($earnedBadges, true);
            $vendor->save();
        } catch (\Throwable $e) {
            Log::warning('refreshEarnedBadges failed', [
                'vendor_id' => $vendor->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function refreshReviews(Vendor $vendor, $reviews = null){
        if($reviews == null){
            return;
        }
        //remove old reviews so duplicates are not added
        Review::where('vendor_id', $vendor->id)->delete();

        $parsed_reviews = [];
        foreach($reviews as $review){
            $parsed_review = [
                'vendor_id' => $vendor->id,
                'rating' => $review['rating'],
                'body' => $review['originalText']['text'] ?? '',
                'author' => $review['authorAttribution']['displayName'] ?? '',
                'author_photo' => $review['authorAttribution']['photoUri'] ?? null,
                'date' => $review['publishTime'] ?? null
            ];
            array_push($parsed_reviews, $parsed_review);
        }
        if(count($parsed_reviews) > 0){
            Review::insert($parsed_reviews);
        }
    }

    /**
     * Re-fetch a vendor's Google rating, review count, and reviews from the
     * Places API and persist them. Requires $vendor->google_place_id to
     * already be set (via the profile "link Google place" flow). Shared by
     * that flow and the vendors:refresh-google-reviews scheduled command, so
     * linked vendors' Google data doesn't go stale between manual re-links.
     */
    public function syncGooglePlaceData(Vendor $vendor): bool
    {
        if (empty($vendor->google_place_id)) {
            return false;
        }

        $profile = $vendor->profile;
        if (! $profile) {
            return false;
        }

        try {
            $fields = ["reviews", "rating", "userRatingCount", "googleMapsUri", "photos"];
            $response = GooglePlaces::make()->placeDetails($vendor->google_place_id, $fields)->array();
        } catch (\Throwable $e) {
            Log::warning('syncGooglePlaceData: Google Places request failed', [
                'vendor_id' => $vendor->id,
                'message' => $e->getMessage(),
            ]);
            return false;
        }

        if (! isset($response['rating'])) {
            return false;
        }

        $profile->google_review_score = $response['rating'];
        $profile->google_reviews_count = $response['userRatingCount'] ?? 0;
        $profile->google_place_link = $response['googleMapsUri'] ?? $profile->google_place_link;
        $profile->save();

        $this->refreshReviews($vendor, $response['reviews'] ?? null);
        $this->syncPlacePhoto($vendor, $profile, $response['photos'] ?? null);

        return true;
    }

    /**
     * Downloads the business's first Google photo (if any) and caches it in
     * local storage alongside portfolio images, so the storefront doesn't
     * depend on Google's short-lived signed photo URLs or spend an API call
     * on every page view. Failures here shouldn't fail the whole sync since
     * rating/review data already saved successfully by the time this runs.
     */
    private function syncPlacePhoto(Vendor $vendor, $profile, ?array $photos): void
    {
        $photoName = $photos[0]['name'] ?? null;
        if (! $photoName) {
            return;
        }

        try {
            $media = GooglePlaces::make()->placePhoto($photoName, maxWidthPx: 800)->array();
            $photoUri = $media['photoUri'] ?? null;
            if (! $photoUri) {
                return;
            }

            $bytes = Http::timeout(10)->get($photoUri)->body();
            $filename = 'google-' . Str::random(40) . '.jpg';
            Storage::disk('public')->put('images/' . $filename, $bytes);

            $previous = $profile->google_photo;
            $profile->google_photo = $filename;
            $profile->save();

            if ($previous && $previous !== $filename) {
                Storage::disk('public')->delete('images/' . $previous);
            }
        } catch (\Throwable $e) {
            Log::warning('syncGooglePlaceData: photo sync failed', [
                'vendor_id' => $vendor->id,
                'message' => $e->getMessage(),
            ]);
        }
    }
}