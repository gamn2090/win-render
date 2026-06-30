<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $role = $request->loginAndResolveRole();

        $request->session()->regenerate();

        return response()->json([
            'status' => true,
            'role' => $role,
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Destroy an authenticated session (Vendor).
     */
    public function destroyVendor(Request $request): RedirectResponse
    {
        Auth::guard('vendor')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function vendorCreate(): View
    {
        return view('auth.login_vendor');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function vendorStore(LoginRequest $request): JsonResponse
    {
        $request->merge(['email' => strtolower((string) $request->input('email'))]);
        $request->vendorAuthenticate();
        Auth::guard('web')->logout();

        $request->session()->regenerate();

        return response()->json([
            'status' => true,
            'role' => 'vendor',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function vendorDestroy(Request $request): RedirectResponse
    {
        Auth::guard('vendor')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function googleRedirect(Request $request): RedirectResponse
    {
        $role = $request->query('role', 'couple');
        if (! in_array($role, ['couple', 'vendor'], true)) {
            $role = 'couple';
        }

        $request->session()->put('google_oauth_role', $role);

        return Socialite::driver('google')->redirect();
    }

    public function googleCallback(): RedirectResponse
    {
        $role = request()->session()->pull('google_oauth_role', 'couple');
        if (! in_array($role, ['couple', 'vendor'], true)) {
            $role = 'couple';
        }

        $googleUser = Socialite::driver('google')->user();
        $email = strtolower((string) $googleUser->getEmail());

        if ($email === '') {
            return redirect('/')->with('status', 'We could not get your email from Google. Please try again.');
        }

        $fullName = trim((string) ($googleUser->getName() ?? ''));
        $nameParts = preg_split('/\s+/', $fullName) ?: [];
        $firstName = $nameParts[0] ?? 'Google';
        $lastName = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : 'User';

        if ($role === 'vendor') {
            return $this->handleGoogleVendor($email, $firstName, $lastName);
        }

        return $this->handleGoogleCouple($email, $firstName, $lastName);
    }

    protected function handleGoogleCouple(string $email, string $firstName, string $lastName): RedirectResponse
    {
        if (Vendor::where('email', $email)->exists()) {
            return redirect('/')->with(
                'status',
                'This email is registered as a vendor. Use "Sign Up as a Vendor" or sign in with the vendor account flow.'
            );
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => Hash::make(Str::random(40)),
                'email_verified_at' => now(),
            ]);
        } elseif (! $user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
        }

        $user->profile;

        Auth::guard('vendor')->logout();
        Auth::guard('web')->login($user, true);
        request()->session()->regenerate();
        request()->session()->put('account_role', 'couple');

        return redirect(RouteServiceProvider::HOME);
    }

    protected function handleGoogleVendor(string $email, string $firstName, string $lastName): RedirectResponse
    {
        if (User::where('email', $email)->exists()) {
            return redirect('/')->with(
                'status',
                'This email is registered as a couple. Use "Sign Up as a Couple" or sign in with Google as a couple.'
            );
        }

        $vendor = Vendor::where('email', $email)->first();

        if ($vendor) {
            Auth::guard('web')->logout();
            Auth::guard('vendor')->login($vendor, true);
            request()->session()->regenerate();
            request()->session()->put('account_role', 'vendor');

            return redirect(RouteServiceProvider::VENDOR_HOME);
        }

        request()->session()->put('google_vendor_prefill', [
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);

        return redirect()
            ->route('vendor.register.form')
            ->with('status', 'Complete your vendor registration (business details) to activate your account.');
    }
}
