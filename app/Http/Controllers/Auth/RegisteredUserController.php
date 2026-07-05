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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Support\ProfileImageStorage;
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
            'upcomingMeetings' => $user->upcomingMeetings()->take(5)->get(),
            'first_login' => $first_login,
            'profile' => $user->profile
        ];
        return view('couple.dashboard', $data);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->merge([
            'email' => strtolower((string) $request->input('email')),
        ]);

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'fiance_first_name' => ['nullable', 'string', 'max:255'],
            'fiance_last_name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (Vendor::where('email', strtolower((string) $value))->exists()) {
                        $fail('This email is already registered as a vendor. Sign in as a vendor or use a different email.');
                    }
                },
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $event = Event::where("join_link", $request->event)->first();
        if($event){
            $request->event = $event->id;
        } else {
            $request->event = null;
        }

        $bio = $request->bio;
        if ($request->filled('wedding_venue')) {
            $venueLine = 'Wedding venue: '.$request->wedding_venue;
            if ($request->filled('wedding_venue_location')) {
                $venueLine .= ', '.$request->wedding_venue_location;
            }
            $bio = $bio ? $bio."\n".$venueLine : $venueLine;
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
            'bio' => $bio,
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

        if (HubspotService::integrationsEnabled()) {
            HubspotService::createCouple($user);
        }

        $profile = Profile::create(['type' => 'client', 'belongs_to' => $user->id]);

        if (HubspotService::integrationsEnabled()) {
            SendEmail::dispatch(\Config::get('hubspot.emails.couple_registration_client'), $user->email, [
                'couple_name' => $user->first_name . ' & ' . $user->fiance_first_name
            ]);
        }

        Auth::guard('vendor')->logout();
        Auth::guard('web')->login($user);
        $request->session()->regenerate();
        $request->session()->put('first-login', true);
        $request->session()->put('account_role', 'couple');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => true,
                'role' => 'couple',
                'redirect' => RouteServiceProvider::HOME,
            ]);
        }

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

        if (HubspotService::integrationsEnabled()) {
            HubspotService::createCouple($user);
        }

        return redirect(RouteServiceProvider::HOME);
    }

    public function uploadProfileImage(Request $request): JsonResponse
    {
        $user = Auth::guard('web')->user();
        if (! $user) {
            return response()->json(['message' => 'unauthenticated'], 401);
        }

        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
        ]);

        $file = $request->file('image');
        $previousImage = $user->image;
        $filename = ProfileImageStorage::store($file);

        ProfileImageStorage::deleteIfExists($previousImage);

        $user->image = $filename;
        $user->save();

        return response()->json([
            'message' => 'saved image',
            'filename' => $filename,
            'image_url' => ProfileImageStorage::url($filename),
        ]);
    }
}
