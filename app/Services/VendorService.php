<?php

namespace App\Services;

use App\Models\Vendor;
use App\Models\Inquiry;
use App\Models\Review;
use App\Models\TagType;
use Illuminate\Support\Facades\Log;

class VendorService {



    public function getVendorsByRank($type, $filters = null){
        return Vendor::when(
            is_array($type),
            fn ($query) => $query->whereIn('type', $type),
            fn ($query) => $query->where('type', $type)
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
            //TODO: get reviews refresh from google
            return;
        }
        //remove old reviews so duplicates are not added
        Review::where('vendor_id', $vendor->id)->delete();
        
        $parsed_reviews = [];
        foreach($reviews as $review){
            $parsed_review = [
                'vendor_id' => $vendor->id,
                'rating' => $review['rating'],
                'body' => $review['originalText']['text'],
                'author' => $review['authorAttribution']['displayName'],
                'author_photo' => $review['authorAttribution']['photoUri'],
                'date' => $review['publishTime']
            ];
            array_push($parsed_reviews, $parsed_review);
        }
        Review::upsert($parsed_reviews, uniqueBy: ['id', 'destination'], update: []);
    }
}