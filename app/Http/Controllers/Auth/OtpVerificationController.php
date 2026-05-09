<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;

class OtpVerificationController extends Controller
{
    /**
     * Show the OTP verification form.
     */
    public function create(Request $request): View|RedirectResponse
    {
        if (!$request->session()->has('otp_user_id')) {
            return redirect()->route('admin.login');
        }

        return view('auth.verify-otp');
    }

    /**
     * Handle OTP verification.
     */
    public function store(Request $request): RedirectResponse
    {
        if (!$request->session()->has('otp_user_id')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $userId = $request->session()->get('otp_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('admin.login')->withErrors(['otp' => 'Session expired. Please log in again.']);
        }

        // Check expiry
        if (!$user->login_otp_expires_at || now()->isAfter($user->login_otp_expires_at)) {
            $user->update(['login_otp' => null, 'login_otp_expires_at' => null]);
            $request->session()->forget('otp_user_id');
            return redirect()->route('admin.login')->withErrors(['otp' => 'OTP has expired. Please log in again.']);
        }

        // Check OTP value
        if (! Hash::check((string) $request->otp, (string) $user->login_otp)) {
            return back()->withErrors(['otp' => 'Invalid OTP code. Please try again.']);
        }

        // OTP is valid — clear it and log in
        $user->update(['login_otp' => null, 'login_otp_expires_at' => null]);
        $request->session()->forget('otp_user_id');

        Auth::login($user, $request->session()->get('otp_remember', false));
        $request->session()->forget('otp_remember');
        $request->session()->regenerate();

        // Issue API token for the dashboard
        $user->tokens()->delete();
        $apiToken = $user->createToken('admin-dashboard')->plainTextToken;
        $request->session()->put('admission_api_token', $apiToken);

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }

    /**
     * Resend OTP to the user.
     */
    public function resend(Request $request): RedirectResponse
    {
        if (!$request->session()->has('otp_user_id')) {
            return redirect()->route('admin.login');
        }

        $userId = $request->session()->get('otp_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('admin.login');
        }

        // Generate new OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update([
            'login_otp' => Hash::make($otp),
            'login_otp_expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($user->email)->send(new \App\Mail\LoginOtpMail($otp, $user->name));
        } catch (\Exception $e) {
            return back()->withErrors(['otp' => 'Failed to resend OTP. Please try again.']);
        }

        return back()->with('resent', 'A new OTP has been sent to your email.');
    }
}
