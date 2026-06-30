<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Profile;
use App\Models\Event;
use App\Models\VendorTypes;
use App\Models\Referral;
use App\Models\VendorRanking;
use App\Services\HubspotService;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Http;
use App\Jobs\SendEmail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RegisteredVendorController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        if(Auth::guard('web')->check()){
            return redirect(RouteServiceProvider::HOME);
        } else if(Auth::guard('vendor')->check()){
            return redirect(RouteServiceProvider::VENDOR_HOME);
        }
        return view('auth.register_vendor', ['vendor_types' => VendorTypes::orderBy('priority', 'asc')->get()]);
    }

    public function createSolo(Request $request)
    {
        if(Auth::guard('web')->check()){
            return redirect(RouteServiceProvider::HOME);
        } else if(Auth::guard('vendor')->check()){
            return redirect(RouteServiceProvider::VENDOR_HOME);
        }
        return view('auth.register_vendor_form', ['vendor_types' => VendorTypes::orderBy('priority', 'asc')->get(), 'event' => $request->event]);
    }

    public function createWithRef(Request $request, $id)
    {
        $id = intval($id);
        $ref = Referral::where('id', $id)->first();
        if(Auth::guard('web')->check()){
            return redirect(RouteServiceProvider::HOME);
        } else if(Auth::guard('vendor')->check()){
            return redirect(RouteServiceProvider::VENDOR_HOME);
        } 
        return view('auth.register_vendor_form', ['vendor_types' => VendorTypes::orderBy('priority', 'asc')->get(), 'event' => $request->event, 'ref_id' => $ref->ref_by, 'ref_model' => $ref, "ref_user" => json_decode($ref->data)]);
    }

    public function createWithReferral(Request $request,$referer)
    {
        $ref_name = urldecode($referer);
        $ref_by = Vendor::where('business_name', $ref_name)->first();
        if(!$ref_by){
            return redirect(RouteServiceProvider::WELCOME);
        }
        return view('auth.register_vendor_form', ['vendor_types' => VendorTypes::orderBy('priority', 'asc')->get(), 'ref_id' => $ref_by->id, 'event' => $request->event]);
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
            'last_name' => ['required', 'string', 'max:32'],
            'first_name' => ['required', 'string', 'max:32'],
            'business_name' => ['required', 'string', 'max:64'],
            'email' => ['required', 'string', 'email', 'max:64', 'unique:'.Vendor::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            //'service_radius' => ['required', 'integer', 'min:0', 'lt: 3000'],
            'location' => ['required', 'string', 'max:32'],
            'offered_discount' => ['required'],
            'captcha_response' => ['required'],
            'role' => ['required', 'min:1', 'max:21']
        ]);

        //captcha validation
        $response = Http::post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            //TODO: move to env
            'secret' => '0x4AAAAAAA50lRHU0nFon9yXY-Y9zXXkcxE',
            'response' => $request->captcha_response,
        ]);
        if(!$response->ok()){
            return back()->withErrors(['captcha' => $response->json()]);
        }
        if($response->json()['success'] == false){
            return back()->withErrors(['captcha' => 'Captcha validation failed. Please try again.']);
        }
        

        $user = Vendor::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'type' => $request->role,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'discount' => $request->offered_discount,
            'avg_price' => $request->avg_price,
            'business_name' => $request->business_name,
            'location' => $request->location,
            'service_radius' => $request->service_radius
        ]);

        if($request->ref_by != null){
            $user->ref_by = intval($request->ref_by);
            $user->save();
        }
        $event = Event::where("join_link", $request->event)->first();
        if($request->event != null && $event != null){
            $user->updateTag("Event", ["Event" => $event->id]);
        }

        $profile = Profile::create([
            'belongs_to' => $user->id,
            'business_link' => $request->business_website,
            'type' => "vendor",
            'bio' => $request->bio
        ]);

        $rankings = VendorRanking::create([
            'vendor_id' => $user->id
        ]);

        if (!App::environment(['local', 'staging'])) {
            HubspotService::createVendor($user, $profile);
        }

        //queue intro emails
        SendEmail::dispatch(\Config::get('hubspot.emails.vendor_registration'), $user->email, [
            'vendor_name' => $user->business_name
        ]);
        SendEmail::dispatch(\Config::get('hubspot.emails.24h_follow_up'), $user->email, [
            'vendor_name' => $user->business_name
        ])->delay(now()->addHours(24));
        SendEmail::dispatch(\Config::get('hubspot.emails.48h_follow_up'), $user->email, [
            'vendor_name' => $user->business_name
        ])->delay(now()->addHours(48));

        event(new Registered($user));

        Auth::guard("vendor")->login($user);
        $request->session()->put('first-login', true);

        return redirect(RouteServiceProvider::VENDOR_HOME);
    }

    public function uploadProfileImage(Request $request){
        $user = Vendor::where('id', Auth::guard('vendor')->user()->id)->first();
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
            $user->Save();
            return 'saved image';
        }
        return 'no image';
    }
}
