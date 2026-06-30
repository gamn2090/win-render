<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\Inquiry;
use App\Models\Vendor;
use App\Models\Pairing;
use App\Models\Referral;
use App\Models\VendorTypes;
use App\Models\Event;
use App\Services\HubspotService;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Jobs\SendEmail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {   
        if(Auth::guard('web')->check()){
            return redirect(RouteServiceProvider::HOME);
        } else if(Auth::guard('vendor')->check()){
            return redirect(RouteServiceProvider::VENDOR_HOME);
        }
        $data = [];
        $data["types"] = VendorTypes::orderBy('priority', 'asc')->get();
        return view('auth.register_user', ["data" => $data, 'event' => $request->event]);
    }

    public function dashboard(Request $request){
        //TODO: optimize vendor/pairing loading
        $user = $request->user();
        $first_login = $request->session()->pull('first-login', false);

        $data = [
            'pairings' => collect($user->vendorsWithStatus()),
            'page' => 'dashboard',
            'recentConversations' => $user->recentConversations(3),
            'requestedVendorTypes' => $user->requestedVendorTypes()->get(),
            'favoritedVendors' => $user->favoritedVendors()->get(),
            'first_login' => $first_login,
            'profile' => $user->profile
        ];
        return view('dashboard', $data);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->email = strtolower($request->email);
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $event = Event::where("join_link", $request->event)->first();
        if($event){
            $request->event = $event->id;
        } else {
            $request->event = null;
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'fiance_first_name' => $request->fiance_first_name,
            'fiance_last_name' => $request->fiance_last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'wedding_date' => $request->wedding_date,
            'wedding_location' => $request->wedding_location,
            'bio' => $request->bio,
            'questions' => $request->questions,
            'booking_date' => $request->booking_date,
            'ref_source' => 99,
            'phone' => $request->phone,
            'event' => $request->event,
        ]);
        //if user is referred, create the pairing
        if($request->ref_by != null){
            //$user->ref_by = intval($request->ref_by);
            $user->in_network = 1;
            $user->save();
            $vendor = Vendor::where('id', intval($request->ref_by))->first();
            if($vendor){
                Pairing::create([
                    'vendor_id' => $vendor->id,
                    'vendor_type' => $vendor->type,
                    'status' => 3,
                    'client_id' => $user->id,
                    'main_connection' => true,
                    'discount_eligible' => false,
                    'approved' => true
                ]);
            }
        }

        $reqestable = $request->can_request;
        if($request->vt != null){
            foreach($request->vt as $type){
                $inq = Inquiry::create([
                    "vendor_type" => $type,
                    "user_id" => $user->id,
                    "requestable" => $reqestable
                ]);
            }
        }

        event(new Registered($user));

        HubspotService::createCouple($user);

        $profile = Profile::create(['type' => 'client', 'belongs_to' => $user->id]);

        SendEmail::dispatch(\Config::get('hubspot.emails.couple_registration_client'), $user->email, [
            'couple_name' => $user->first_name . ' & ' . $user->fiance_first_name
        ]);

        Auth::guard("web")->login($user);
        $request->session()->put('first-login', true);

        return redirect(RouteServiceProvider::HOME);
    }


    public function createWithReferral(Request $request, $referer){
        $ref_name = urldecode($referer);
        $ref_by = Vendor::where('business_name', $ref_name)->first();
        if(!$ref_by){
            return redirect(RouteServiceProvider::WELCOME);
        }
        $data = [];
        $data["types"] = VendorTypes::orderBy('priority', 'asc')->get();
        return view('auth.register_user', [
            'ref_id' => $ref_by->id,
            'data' => $data,
            'event' => $request->event
        ]);
    }

    /**
     * Register a user through their vendor's referral link
     */
    public function registerReferral(Request $request, $pairingId){
        $referral = Referral::where('uuid', $pairingId)->first();
        if(!$referral){
            return view('404');
        }
        $data = [];
        $data["types"] = VendorTypes::orderBy('priority', 'asc')->get();
        return view('auth.register_user', [
            'ref_by' => $referral->ref_by,
            'data' => $data,
            'event' => $request->event
        ]);
    }

    public function finishReferralSetup(Request $request){
        $user = User::where('id', $request->id)->first();
        if(!$user){
            return;
        }
        $user->password = Hash::make($request->password);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->fiance_first_name = $request->fiance_first_name;
        $user->fiance_last_name = $request->fiance_last_name;
        $user->wedding_date = $request->wedding_date;
        $user->wedding_location = $request->wedding_location;
        $user->in_network = true;
        $user->bio = $request->bio;
        $user->booking_date = $request->booking_date;
        $user->questions = $request->questions;
        $user->save();
        $reqestable = boolval($request->can_request);
        foreach($request->vt as $type){
            $inq = Inquiry::create([
                "vendor_type" => $type,
                "user_id" => $user->id,
                "requestable" => $reqestable
            ]);
        }
        $pairing = Pairing::where('client_id', $user->id)->where('main_connection', true)->first();
        if($pairing){
            $pairing->approved = true;
            $pairing->save();
        }

        Auth::guard("web")->login($user);

        HubspotService::createCouple($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function uploadProfileImage(Request $request){
        $user = $request->user();
        if(!$user){
            return 'unauthenticated';
        }
        if($request->hasFile('image')){
            $filename = $request->image->hashName();
            $request->image->storeAs('images',$filename,'public');
            //remove old image if it exists
            if($user->image != null && $user->image != "user.jpg"){
                unlink(storage_path('app/public/images/' . $user->image));
            }
            $user->image = $filename;
            $user->save();
            return 'saved image';
        }
        return 'no image';
    }
}
