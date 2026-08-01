<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\VendorTypes;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Meeting;
use App\Models\Review;
use App\Models\Tag;
use Illuminate\Support\Facades\RateLimiter;
use SKAgarwal\GoogleApi\PlacesNew\GooglePlaces;
use App\Services\VendorService;
use Illuminate\Support\Str;
use App\Models\Profile;
use App\Models\Inquiry;
use App\Models\TagType;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function __construct(
        protected VendorService $vendorService,
    ) {}
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'page' => 'edit_profile',
        ]);
    }

    public function accountSettings(Request $request): View
    {
        return view('couple.account_settings', [
            'user' => $request->user(),
        ]);
    }

    public function vendorAccountSettings(Request $request): View
    {
        return view('vendor.account_settings', [
            'user' => Auth::guard('vendor')->user(),
        ]);
    }

    public function vendorEdit(Request $request): View
    {
        $vendor = $request->user()->load('tags');
        $vendor->ensureUuid();
        $vendor->ensureVendorProfile();

        $locationParts = array_map('trim', explode(',', trim((string) $vendor->location), 2));
        $businessCity = $locationParts[0] ?? '';
        $businessState = $locationParts[1] ?? '';

        return view('profile.vendor_edit', [
            'user' => $vendor,
            'user_type' => VendorTypes::where('id', $request->user()->type)->first(),
            'page' => 'edit_profile',
            'tag_types' => TagType::where('vendor_type_id', $vendor->type)->where('hidden', 0)->get(),
            'profile' => $vendor->profile,
            'businessCity' => $businessCity,
            'businessState' => $businessState,
        ]);
    }

    public function userEdit(Request $request): View
    {
        $user = $request->user();
        $decoded = json_decode($user->questions ?? 'null', true);
        if (! is_array($decoded)) {
            $decoded = [];
        }
        $user->questions = array_pad(array_values($decoded), 4, '');

        // "Wedding Location" (the couple's own city/state, set at registration)
        // and "Wedding Venue" (just the venue's name, if they have one) are two
        // separate concepts — Wedding Location always comes straight from the
        // user's own wedding_location column, never from bio-parsing, so it
        // can't get blanked out just because no venue name was ever entered.
        $venueName = '';
        $bioText = $user->bio ?? '';

        if (preg_match('/Wedding venue:\s*([^\n]+)/i', $bioText, $venueMatch)) {
            $venueName = trim($venueMatch[1]);
            $bioText = trim(preg_replace('/Wedding venue:.*$/im', '', $bioText));
        }

        $locationParts = array_map('trim', explode(',', trim((string) $user->wedding_location), 2));
        $venueCity = $locationParts[0] ?? '';
        $venueState = $locationParts[1] ?? '';

        return view('couple.edit_profile', [
            'user' => $user,
            'searching_for' => $user->getRequestedVendorTypes(),
            'vendor_types' => VendorTypes::orderBy('priority', 'asc')->get(),
            'venueName' => $venueName,
            'venueCity' => $venueCity,
            'venueState' => $venueState,
            'bioText' => $bioText,
            'page' => 'edit_profile',
        ]);
    }

    public function vendorStorefront(Request $request)
    {
        $vendor = $request->user();
        $vendor->ensureUuid();

        return $this->vendor($vendor->uuid);
    }

    public function vendor($id)
    {
        if(!Auth::guard("web")->check() && !Auth::guard("vendor")->check()){
            return redirect('/');
        }
        $vendor = Vendor::where('uuid', $id)->first();
        if(!$vendor){
            return view('404');
        }
        $vendor->ensureVendorProfile();
        $related_vendors = null;
        //endorsements parse
        $endorsements = $vendor->topEndorsements();
        $endorsements = $endorsements->map(function($endorsement){
            $endorsementName = "";
            switch($endorsement->type){
              case 1:
                  $endorsementName = 'Responsive';
                  break;
              case 2:
                $endorsementName = 'Professional';
                  break;
              case 3:
                $endorsementName = 'Communicative';
                  break;
              case 4:
                $endorsementName = 'Creative';
                  break;
              case 5:
                $endorsementName = 'Resourceful';
                  break;
              case 6:
                $endorsementName = 'Personable';
                  break;
              default:
                $endorsementName = 'Unknown';
                  break;
            }
            $endorsement->typeNum = $endorsement->type;
            $endorsement->type = $endorsementName;
            return $endorsement;
        });

        $data = [
            'vendor' => $vendor,
            'vendor_types' => VendorTypes::all(),
            'vendor_type' => VendorTypes::where('id', $vendor->type)->first(),
            'connections' => $vendor->connections()->get(),
            'profile' => $vendor->profile,
            'related_vendors' => $related_vendors,
            'page' => 'storefront',
            'wedding_date_available' => null,
            'endorsements' => $endorsements,
            'rank' => Vendor::where('type', $vendor->type)->where('score', '>', $vendor->score ?? 0)->count() + 1,
            'vendorBusyDates' => $vendor->busyDates(),
        ];

        if(Auth::guard('web')->check()){
           $user = Auth::guard('web')->user();
           $types = $user->getRequestedVendorTypes();
           $related_vendors = [];
           foreach($types as $type){
                array_push($related_vendors, $type);
           }
           $vendor->addView();
           $data["wedding_date_available"] = $vendor->isDateAvailable($user->wedding_date);
           $data["related_vendors"] = $related_vendors;
        }
        if(Auth::guard('web')->check()){
            return view('couple.storefront', $data);
        }

        if($vendor){
            return view('vendor.storefront', $data);
        } else {
            return view('404');
        }
    }

    public function client($id): View|\Illuminate\Http\RedirectResponse
    {
        $client = User::where('uuid', $id)->first();
        if($client){
            if (Auth::guard('vendor')->check()) {
                return redirect()->route('vendor.couple.profile', ['id' => $id]);
            }
            return view('profile.client', [
                'client' => $client,
                'vendor_types' => VendorTypes::all(),
                'searching_for' => $client->getRequestedVendorTypes(),
                'booked_vendors' => $client->bookedVendors(),
                'profile' => $client->profile,
                'page' => 'client_storefront'
            ]);
        } else {
            return view('404');
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $vTypes = $request->input('vt');
        if($vTypes == null){
            $vTypes = [];
        }
        $allVendorTypes = VendorTypes::all();
        foreach($allVendorTypes as $type){
            $inquiry = Inquiry::where('user_id', $request->user()->id)->where('vendor_type', $type->id)->first();
            if(in_array($type->id, $vTypes)){
                if(!$inquiry){
                    Inquiry::create([
                        "vendor_type" => $type->id,
                        "user_id" => $request->user()->id
                    ]);
                }
            }
            else{
                if($inquiry){
                    $inquiry->delete();
                }
            }
        }
        $answers = [$request->q1,$request->q2,$request->q3,$request->q4];

        // Wedding Location (the couple's own city/state) and Wedding Venue
        // (just a name) are independent — saving one must never blank out or
        // overwrite the other.
        $venueName = trim((string) $request->input('venue_name'));
        $venueCity = trim((string) $request->input('venue_city'));
        $venueState = trim((string) $request->input('venue_state'));
        $bioText = trim((string) $request->input('bio'));
        $weddingLocation = trim(implode(', ', array_filter([$venueCity, $venueState])));

        $bio = $bioText;
        if ($venueName !== '') {
            $bio = trim('Wedding venue: ' . $venueName . ($bioText !== '' ? "\n" . $bioText : ''));
        }

        $request->user()->fill($request->except(['vt', 'q1', 'q2', 'q3', 'q4', 'venue_name', 'venue_city', 'venue_state', 'bio']));
        $request->user()->wedding_location = $weddingLocation;
        $request->user()->bio = $bio;
        $request->user()->allow_vendor_contact = $request->boolean('allow_vendor_contact');
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        $request->user()->questions = $answers;
        $request->user()->save();

        return Redirect::route('client.my_profile')
            ->with('status', 'profile-updated')
            ->with('toast', ['message' => 'Changes saved successfully', 'type' => 'success']);
    }

    //update client's our wedding day notes
    public function updateOurWeddingDay(Request $request){
        $request->validate([
            'notes' => ['required', 'string', 'max:2048'],
        ]);
        $profile = $request->user()->profile();
        $profile->notes = $request->notes;
        $profile->save();
        return ["status" => "success"];
    }

    /**
     * Update the user's profile information.
     */
    public function updateVendor(ProfileUpdateRequest $request): RedirectResponse
    {
        $profile = $request->user()->profile;
        $vendor = $request->user();

        $profile->bio = $request->bio;
        $profile->business_link = $request->business_link;
        $profile->instagram_link = array_pad(explode("instagram.com", $request->instagram_link), 2, "")[1];
        $profile->facebook_link = array_pad(explode("facebook.com", $request->facebook_link), 2, "")[1];
        $profile->linkedin_link = array_pad(explode("linkedin.com", $request->linkedin_link), 2, "")[1];
        $profile->save();

        $this->syncVendorAvailability($vendor, $request->input('availability'));

        //edit tags
        $tags = $request->input('tag', []);
        foreach(array_keys($tags) as $tagName){
            $vendor->updateTag($tagName, $tags[$tagName]);
        }

        $businessCity = trim((string) $request->input('business_city'));
        $businessState = trim((string) $request->input('business_state'));
        $vendor->location = trim(implode(', ', array_filter([$businessCity, $businessState])));

        $vendor->fill($request->only(['first_name', 'last_name', 'business_name', 'service_radius', 'discount', 'avg_price']));
        $vendor->save();
        return Redirect::route('profile.vendoredit')->with('status', 'profile-updated');
    }

    public function updateVendorGooglePlace(Request $request)
    {
        $request->user()->google_place_id = $request->google_place_id;
        $request->user()->save();
        $profile = $request->user()->profile;
        $profile->google_review_score = $request->rating;
        $profile->save();
        return $request->google_place_id;
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Delete the vendor's account.
     */
    public function vendorDestroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password:vendor'],
        ]);

        $vendor = Auth::guard('vendor')->user();

        Auth::guard('vendor')->logout();

        $vendor->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function viewAllProfiles(){
        $profiles = Vendor::all();
        return view('profile.all', [
            'vendors' => $profiles
        ]);
    }

    public function uploadPortfolioImage(Request $request){
        $user = $request->user();
        if(!$user){
            return 'unauthenticated';
        }
        $profile = $request->user()->profile;
        $profileImages = json_decode($profile->portfolio_images);
        $fileNames = [];
        foreach($request->all() as $k => $file){
            $filename = Str::random(40) . '.jpg';
            $file->storeAs('images',$filename,'public');
            array_push($profileImages, $filename);
            array_push($fileNames, $filename);
        }
        $profile->portfolio_images = json_encode($profileImages);
        $profile->save();
        return $fileNames;
    }

    public function deletePortfolioImage(Request $request){
        $image_name = $request->image_name;
        $user = $request->user();
        if(!$user){
            return 'unauthenticated';
        }
        $request->user()->profile->removeImage($image_name);
        return 200;
    }

    public function setPortfolioCoverImage(Request $request){
        $image_name = $request->image_name;
        $user = $request->user();
        if(!$user){
            return 'unauthenticated';
        }
        $request->user()->profile->setCoverImage($image_name);
        return ["status" => true];
    }

    public function findGooglePlace(Request $request){
        $request->validate([
            'search' => ['required', 'string', 'max:255'],
        ]);
        $user = $request->user();

        if(RateLimiter::tooManyAttempts('find-place:'.$user->id, $perMinute = 3)){
            return 'Too many attempts!';
        }

        $fields = ["places.displayName","places.id","places.formattedAddress","places.googleMapsUri"];
        
        $textQuery = $request->search . " USA";

        $params = [
            "includePureServiceAreaBusinesses" => true,
            "maxResultCount" => 1
        ];

        $response = GooglePlaces::make()->textSearch($textQuery, $fields, $params);
        
        RateLimiter::increment('find-place:'.$user->id);

        return $response->array();
    }

    public function linkGooglePlace(Request $request){
        $request->validate([
            'place_id' => ['required', 'string', 'max:255'],
        ]);
        $user = $request->user();

        if(RateLimiter::tooManyAttempts('link-place:'.$user->id, $perDay = 1)){
            return 'Too many attempts!';
        }

        $user->google_place_id = $request->place_id;
        $user->save();

        if (! $this->vendorService->syncGooglePlaceData($user)) {
            return "Unable to link place";
        }

        RateLimiter::increment('link-place:'.$user->id);

        return;
    }

    public function unlinkGooglePlace(Request $request){
        $user = $request->user();

        Review::where('vendor_id', $user->id)->delete();
        $user->google_place_id = null;
        $user->save();
        $profile = $user->profile;
        $profile->google_review_score = null;
        $profile->google_reviews_count = null;
        $profile->google_place_link = null;
        $profile->save();

        return;
    }

    /**
     * @return list<string> Y-m-d dates
     */
    private function parseAvailabilityDates(?string $raw): array
    {
        if ($raw === null || trim($raw) === '') {
            return [];
        }

        return collect(preg_split('/\s*,\s*/', $raw))
            ->map(static fn (string $date) => trim($date))
            ->filter(static fn (string $date) => $date !== '')
            ->map(static fn (string $date) => Carbon::parse($date)->format('Y-m-d'))
            ->unique()
            ->values()
            ->all();
    }

    private function syncVendorAvailability(Vendor $vendor, ?string $availabilityRaw): void
    {
        $unavailableDates = $this->parseAvailabilityDates($availabilityRaw);

        // Only ever touch this vendor's own manually-blocked ("unavailable")
        // entries here — real client meetings (consultations, weddings) live
        // in the same table under other `type` values and must never be
        // deleted just because they don't appear in this save's date list.
        $vendor->meetings()->where('type', 'manual')->delete();

        if ($unavailableDates === []) {
            return;
        }

        $newDates = array_map(static fn (string $date) => [
            'vendor' => $vendor->id,
            'date' => $date,
            'type' => 'manual',
            'approved' => 1,
        ], $unavailableDates);

        Meeting::insert($newDates);
    }
}
