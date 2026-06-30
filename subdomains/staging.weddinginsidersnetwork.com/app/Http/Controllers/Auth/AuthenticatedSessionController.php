<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
    public function store(LoginRequest $request)
    {
        $request->email = strtolower($request->email);
        $request->authenticate();

        $request->session()->regenerate();

        return json_encode(["status" => true]);
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
    public function vendorStore(LoginRequest $request)
    {
        $request->email = strtolower($request->email);
        $request->vendorAuthenticate();

        $request->session()->regenerate();

        return json_encode(["status" => true]);
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
}
