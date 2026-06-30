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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use App\Models\User;
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
        return view('auth.register_vendor', ['vendor_types' => VendorTypes::ordered()->get()]);
    }

    public function createSolo(Request $request)
    {
        if(Auth::guard('web')->check()){
            return redirect(RouteServiceProvider::HOME);
        } else if(Auth::guard('vendor')->check()){
            return redirect(RouteServiceProvider::VENDOR_HOME);
        }
        return view('auth.register_vendor_form', [
            'vendor_types' => VendorTypes::ordered()->get(),
            'event' => $request->event,
            'google_prefill' => $request->session()->pull('google_vendor_prefill'),
        ]);
    }

    public function createWithRef(Request $request, $id)
    {
        $id = intval($id);
        $ref = Referral::where('id', $id)->first();
        if (! $ref) {
            return redirect()->route('vendor.register.form');
        }
        if(Auth::guard('web')->check()){
            return redirect(RouteServiceProvider::HOME);
        } else if(Auth::guard('vendor')->check()){
            return redirect(RouteServiceProvider::VENDOR_HOME);
        } 
        return view('auth.register_vendor_form', ['vendor_types' => VendorTypes::ordered()->get(), 'event' => $request->event, 'ref_id' => $ref->ref_by, 'ref_model' => $ref, "ref_user" => json_decode($ref->data)]);
    }

    public function createWithReferral(Request $request,$referer)
    {
        $ref_name = urldecode($referer);
        $ref_by = Vendor::where('business_name', $ref_name)->first();
        if(!$ref_by){
            return redirect(RouteServiceProvider::WELCOME);
        }
        return view('auth.register_vendor_form', ['vendor_types' => VendorTypes::ordered()->get(), 'ref_id' => $ref_by->id, 'event' => $request->event]);
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
            'avg_price' => $request->filled('avg_price') ? (int) $request->input('avg_price') : null,
            'ref_by' => $request->filled('ref_by') ? (int) $request->input('ref_by') : null,
            'event' => $request->filled('event') ? $request->input('event') : null,
            'role' => $request->filled('role') ? (int) $request->input('role') : null,
            'offered_discount' => is_numeric($request->input('offered_discount'))
                ? (int) $request->input('offered_discount')
                : $request->input('offered_discount'),
        ]);

        $request->validate([
            'last_name' => ['required', 'string', 'max:32'],
            'first_name' => ['required', 'string', 'max:32'],
            'business_name' => ['required', 'string', 'max:64'],
            'email' => [
                'required',
                'string',
                'email',
                'max:64',
                Rule::unique('vendors', 'email'),
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (User::where('email', strtolower((string) $value))->exists()) {
                        $fail('This email is already registered as a couple. Sign in as a couple or use a different email.');
                    }
                },
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'location' => ['required', 'string', 'max:128'],
            'offered_discount' => ['required', 'integer', 'in:0,50,75,100,150,200,250'],
            'avg_price' => ['nullable', 'integer', 'between:1,7'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'captcha_response' => ['nullable', 'string'],
            'role' => ['required', 'integer', 'exists:vendor_types,id'],
            'ref_by' => ['nullable', 'integer'],
            'event' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->filled('captcha_response')) {
            $response = Http::post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => '0x4AAAAAAA50lRHU0nFon9yXY-Y9zXXkcxE',
                'response' => $request->captcha_response,
            ]);
            if (!$response->ok()) {
                return back()->withErrors(['captcha' => $response->json()]);
            }
            if ($response->json()['success'] == false) {
                return back()->withErrors(['captcha' => 'Captcha validation failed. Please try again.']);
            }
        }

        $vendorData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'type' => (int) $request->role,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'discount' => (int) $request->offered_discount,
            'business_name' => $request->business_name,
            'location' => $request->location,
            'service_radius' => $request->service_radius,
        ];

        if (Schema::hasColumn('vendors', 'avg_price')) {
            $vendorData['avg_price'] = $request->filled('avg_price') ? (int) $request->avg_price : null;
        }

        $user = Vendor::create($vendorData);

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

        if (Schema::hasTable('vendor_rankings')) {
            VendorRanking::firstOrCreate(['vendor_id' => $user->id]);
        }

        if (HubspotService::integrationsEnabled()) {
            HubspotService::createVendor($user, $profile);
        }

        if (HubspotService::integrationsEnabled()) {
            SendEmail::dispatch(\Config::get('hubspot.emails.vendor_registration'), $user->email, [
                'vendor_name' => $user->business_name,
            ]);
            SendEmail::dispatch(\Config::get('hubspot.emails.24h_follow_up'), $user->email, [
                'vendor_name' => $user->business_name,
            ])->delay(now()->addHours(24));
            SendEmail::dispatch(\Config::get('hubspot.emails.48h_follow_up'), $user->email, [
                'vendor_name' => $user->business_name,
            ])->delay(now()->addHours(48));
        }

        event(new Registered($user));

        Auth::guard('web')->logout();
        Auth::guard('vendor')->login($user);
        $request->session()->regenerate();
        $request->session()->put('first-login', true);
        $request->session()->put('account_role', 'vendor');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => true,
                'role' => 'vendor',
                'redirect' => RouteServiceProvider::VENDOR_HOME,
            ]);
        }

        return redirect(RouteServiceProvider::VENDOR_HOME);
    }

    public function uploadProfileImage(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::guard('vendor')->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
        ]);

        $previousImage = $user->image;
        $filename = \App\Support\ProfileImageStorage::store($validated['image']);
        \App\Support\ProfileImageStorage::deleteIfExists($previousImage);

        $user->image = $filename;
        $user->save();

        return response()->json([
            'message' => 'Profile photo saved.',
            'filename' => $filename,
            'image_url' => \App\Support\ProfileImageStorage::url($filename),
        ]);
    }
}
