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
            'user' => $request->user()
        ]);
    }

    public function vendorEdit(Request $request): View
    {
        $vendor = $request->user()->load('tags');
        return view('profile.vendor_edit', [
            'user' => $vendor,
            'user_type' => VendorTypes::where('id', $request->user()->type)->first(),
            'page' => 'edit_profile',
            'tag_types' => TagType::where('vendor_type_id', $vendor->type)->where('hidden', 0)->get(),
            'profile' => $vendor->profile,
        ]);
    }

    public function userEdit(Request $request): View
    {
        $user = $request->user();
        $user->questions = json_decode($user->questions);
        return view('profile.user_edit', [
            'user' => $request->user(),
            'searching_for' => $user->getRequestedVendorTypes(),
            'vendor_types' => VendorTypes::all(),
            'page' => 'edit_profile'
        ]);
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
            'connections' => $vendor->connections,
            'profile' => $vendor->profile,
            'related_vendors' => $related_vendors,
            'page' => 'storefront',
            'wedding_date_available' => null,
            'endorsements' => $endorsements
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
        if($vendor){
            return view('profile.vendor', $data);
        } else {
            return view('404');
        }
    }

    public function client($id): View
    {
        $client = User::where('uuid', $id)->first();
        if($client){
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
        $request->user()->fill($request->except(['vt','q1','q2','q3','q4']));
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        $request->user()->questions = $answers;
        $request->user()->save();

        return Redirect::route('user.profile.edit')->with('status', 'profile-updated');
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

        //edit availability
        $unavailableDates = explode(", ", $request->input('availability'));
        $deletionDates = $vendor->meetings()->whereNotIn('date', $unavailableDates)->delete();
        $newDates = [];
        foreach($unavailableDates as $date){
            array_push($newDates, ['vendor' => $vendor->id, "date" => Carbon::parse($date)->format('Y-m-d'), 'type' => "manual", 'approved' => 1]);
        }
        Meeting::upsert($newDates, uniqueBy: ['id']);

        //edit tags
        $tags = $request->input('tag', []);
        foreach(array_keys($tags) as $tagName){
            $vendor->updateTag($tagName, $tags[$tagName]);
        }

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

        $fields = ["reviews","rating","userRatingCount","googleMapsUri"];
        
        $placeID = $request->place_id;

        $response = GooglePlaces::make()->placeDetails($placeID, $fields);

        try{
            $response = $response->array();
            
            $user->google_place_id = $request->place_id;
            $user->save();
            
            $profile = $user->profile;
            $profile->google_review_score = $response["rating"];
            $profile->google_reviews_count = $response["userRatingCount"];
            $profile->google_place_link = $response["googleMapsUri"];
            $profile->save();
        } catch(\Exception $e){
            return "Unable to link place";
        }
        
        $this->vendorService->refreshReviews($user, $response["reviews"]);
        
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
}
