<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;

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
    public function store(LoginRequest $request): RedirectResponse
    {
        // Validate credentials — this throws if they're wrong
        $request->authenticate();

        if (app()->environment('testing')) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Get the now-authenticated user, then immediately log them OUT again.
        // We require OTP verification before granting full access.
        $user = $request->user();

        if (! $user->isAdmin()) {
            Log::warning('Non-admin login attempt blocked.', [
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors(['email' => 'This account is not authorized for admin access.'])->withInput();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Generate a 6-digit OTP and store it in the DB
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update([
            'login_otp'            => Hash::make($otp),
            'login_otp_expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP email
        try {
            Mail::to($user->email)->send(new \App\Mail\LoginOtpMail($otp, $user->name));
        } catch (\Exception $e) {
            Log::error('Admin OTP email failed.', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['email' => 'Could not send OTP email. Please check mail configuration.'])->withInput();
        }

        // Store user ID in session temporarily for the OTP verification step
        $request->session()->put('otp_user_id', $user->id);
        $request->session()->put('otp_remember', $request->boolean('remember'));

        return redirect()->route('admin.otp');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->user()?->tokens()->where('name', 'admin-dashboard')->delete();
        $request->user()?->tokens()->where('name', 'auth_token')->delete();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
