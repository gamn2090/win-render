<?php

namespace App\Http\Requests\Auth;

use App\Models\Admin;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->loginAndResolveRole();
    }

    /**
     * Log in as couple (web) or vendor based on which account owns the email.
     *
     * @return 'couple'|'vendor'
     *
     * @throws ValidationException
     */
    public function loginAndResolveRole(): string
    {
        $email = strtolower((string) $this->input('email'));
        $credentials = ['email' => $email, 'password' => $this->input('password')];
        $remember = $this->boolean('remember');

        // Admin emails are guaranteed (by validation at creation time, see
        // AdminController::createAdmin) never to collide with a vendor or
        // couple email, so checking this first is unambiguous.
        if (Admin::where('email', $email)->exists()) {
            if (! Auth::guard('admin')->attempt($credentials, $remember)) {
                throw ValidationException::withMessages([
                    'email' => trans('auth.failed'),
                ]);
            }
            Auth::guard('web')->logout();
            Auth::guard('vendor')->logout();

            return 'admin';
        }

        $coupleExists = User::where('email', $email)->exists();
        $vendorExists = Vendor::where('email', $email)->exists();

        if ($coupleExists && $vendorExists) {
            throw ValidationException::withMessages([
                'email' => 'This email is associated with multiple account types. Please contact support.',
            ]);
        }

        if (! $coupleExists && ! $vendorExists) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        if ($coupleExists) {
            if (! Auth::guard('web')->attempt($credentials, $remember)) {
                throw ValidationException::withMessages([
                    'email' => trans('auth.failed'),
                ]);
            }
            Auth::guard('vendor')->logout();

            return 'couple';
        }

        if (! Auth::guard('vendor')->attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
        Auth::guard('web')->logout();

        return 'vendor';
    }

    /**
     * @return 'vendor'|'admin'
     */
    public function vendorAuthenticate(): string
    {
        //$this->ensureIsNotRateLimited();

        $email = strtolower((string) $this->input('email'));

        if (Admin::where('email', $email)->exists()) {
            if (! Auth::guard('admin')->attempt(['email' => $email, 'password' => $this->input('password')], $this->boolean('remember'))) {
                throw ValidationException::withMessages([
                    'email' => trans('auth.failed'),
                ]);
            }

            return 'admin';
        }

        if (! Auth::guard('vendor')->attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            //RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        //RateLimiter::clear($this->throttleKey());

        return 'vendor';
    }

    public function adminAuthenticate(): void
    {
        //$this->ensureIsNotRateLimited();

        if (! Auth::guard('admin')->attempt($this->only('username', 'password'))) {
            //RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        //RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}
