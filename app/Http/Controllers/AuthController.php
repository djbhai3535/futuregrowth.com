<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showRegistrationForm(Request $request)
    {
        $ref = $request->query('ref');
        return view('auth.register', compact('ref'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'referral_code' => 'nullable|string|exists:users,referral_code'
        ]);

        if (setting('enable_recaptcha', false)) {
            $recaptchaResponse = $request->input('g-recaptcha-response');
            $secret = setting('recaptcha_secret_key');
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $recaptchaResponse,
                'remoteip' => $request->ip()
            ]);
            if (!$response->json('success')) {
                return back()->withErrors(['email' => 'reCAPTCHA verification failed. Please try again.'])->withInput();
            }
        }

        $referrer = null;
        if ($request->referral_code) {
            $referrer = User::where('referral_code', $request->referral_code)->first();
        }

        $code = rand(100000, 999999);
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'referral_code' => Str::random(10),
            'referred_by' => $referrer ? $referrer->id : null,
            'status' => 'active',
            'verification_code' => $code,
            'verification_code_expires_at' => now()->addMinutes(30)
        ]);

        // Create Wallet
        $wallet = Wallet::create(['user_id' => $user->id]);

        // Preserve session and authentication state immediately
        Auth::login($user);

        // Check if email verification is enabled
        if (setting('enable_email_verification', true)) {
            try {
                Mail::to($user->email)->send(new \App\Mail\VerificationMail($code));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send verification email: ' . $e->getMessage(), ['exception' => $e]);
            }

            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'Registered account - Verification code sent',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->route('verification.notice');
        } else {
            // Auto verify
            $user->email_verified_at = now();
            $user->verification_code = null;
            $user->verification_code_expires_at = null;
            $user->save();

            // Direct credit bonus
            $bonusAmount = Setting::getVal('signup_bonus', 7);
            if ($bonusAmount > 0) {
                $wallet->bonus_balance = $bonusAmount;
                $wallet->save();
                
                \App\Models\Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'bonus',
                    'amount' => $bonusAmount,
                    'wallet_type' => 'bonus_balance',
                    'status' => 'completed',
                    'description' => 'Free Sign-up Bonus',
                ]);
            }

            Auth::login($user);

            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'Registered account (Auto-verified)',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->route('dashboard');
        }
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (setting('enable_recaptcha', false)) {
            $recaptchaResponse = $request->input('g-recaptcha-response');
            $secret = setting('recaptcha_secret_key');
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $recaptchaResponse,
                'remoteip' => $request->ip()
            ]);
            if (!$response->json('success')) {
                return back()->withErrors(['email' => 'reCAPTCHA verification failed. Please try again.'])->withInput();
            }
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->status === 'banned') {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been banned.']);
            }

            // Check if user is admin (Temporarily bypass 2FA until SMTP is configured)
            if ($user->is_admin) {
                session(['admin_2fa_verified' => true]);
                return redirect()->route('admin.dashboard');
            }

            // Check if email verification is completed
            if (setting('enable_email_verification', true) && !$user->email_verified_at) {
                $userId = $user->id;
                Auth::logout();
                
                // Regenerate verification code if expired
                if (!$user->verification_code || now()->gt($user->verification_code_expires_at)) {
                    $code = rand(100000, 999999);
                    $user->verification_code = $code;
                    $user->verification_code_expires_at = now()->addMinutes(30);
                    $user->save();
                    
                    try {
                        Mail::to($user->email)->send(new \App\Mail\VerificationMail($code));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Failed to send verification email: ' . $e->getMessage());
                    }
                }
                
                return redirect()->route('verification.notice')->with('user_id', $userId);
            }

            $request->session()->regenerate();
            
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Logged in',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Logged out',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showVerificationNotice(Request $request)
    {
        $userId = Auth::id() ?? session('user_id') ?? $request->query('user_id');
        if (!$userId) {
            return redirect()->route('login');
        }
        return view('auth.verify-email', compact('userId'));
    }

    public function verifyEmail(Request $request)
    {
        $userId = Auth::id() ?? $request->user_id;
        if ($userId) {
            $request->merge(['user_id' => $userId]);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'code' => 'required|string|size:6'
        ]);

        if (setting('enable_recaptcha', false)) {
            $recaptchaResponse = $request->input('g-recaptcha-response');
            $secret = setting('recaptcha_secret_key');
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $recaptchaResponse,
                'remoteip' => $request->ip()
            ]);
            if (!$response->json('success')) {
                return back()->withErrors(['code' => 'reCAPTCHA verification failed. Please try again.'])->withInput();
            }
        }

        $user = User::findOrFail($request->user_id);

        if ($user->verification_code !== $request->code || now()->gt($user->verification_code_expires_at)) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->verification_code_expires_at = null;
        $user->save();

        // Award Signup Bonus
        $wallet = $user->wallet;
        $bonusAmount = Setting::getVal('signup_bonus', 7);
        if ($bonusAmount > 0 && !$user->transactions()->where('type', 'bonus')->exists()) {
            $wallet->bonus_balance = $bonusAmount;
            $wallet->save();

            \App\Models\Transaction::create([
                'user_id' => $user->id,
                'type' => 'bonus',
                'amount' => $bonusAmount,
                'wallet_type' => 'bonus_balance',
                'status' => 'completed',
                'description' => 'Free Sign-up Bonus credited after Verification',
            ]);
        }

        Auth::login($user);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Email verified and logged in',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('dashboard')->with('success', 'Email verified successfully! Welcome to FutureGrowth.');
    }

    public function resendVerificationCode(Request $request)
    {
        $userId = Auth::id() ?? $request->user_id;
        if ($userId) {
            $request->merge(['user_id' => $userId]);
        }

        $request->validate(['user_id' => 'required|exists:users,id']);
        $user = User::findOrFail($userId);

        if ($user->email_verified_at) {
            return redirect()->route('login')->with('success', 'Email already verified.');
        }

        $code = rand(100000, 999999);
        $user->verification_code = $code;
        $user->verification_code_expires_at = now()->addMinutes(30);
        $user->save();

        try {
            Mail::to($user->email)->send(new \App\Mail\VerificationMail($code));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to resend verification email: ' . $e->getMessage(), ['exception' => $e]);
        }

        return back()->with('success', 'Verification code resent successfully. Please check your inbox.');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        if (setting('enable_recaptcha', false)) {
            $recaptchaResponse = $request->input('g-recaptcha-response');
            $secret = setting('recaptcha_secret_key');
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $recaptchaResponse,
                'remoteip' => $request->ip()
            ]);
            if (!$response->json('success')) {
                return back()->withErrors(['email' => 'reCAPTCHA verification failed. Please try again.'])->withInput();
            }
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $code = rand(100000, 999999);
        
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => $code,
                'created_at' => now()
            ]
        );

        try {
            Mail::to($user->email)->send(new \App\Mail\ResetPasswordMail($code));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send reset code: ' . $e->getMessage(), ['exception' => $e]);
        }

        return redirect()->route('password.reset', ['email' => $user->email])->with('success', 'Reset verification code sent to your email.');
    }

    public function showResetPasswordForm(Request $request)
    {
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (setting('enable_recaptcha', false)) {
            $recaptchaResponse = $request->input('g-recaptcha-response');
            $secret = setting('recaptcha_secret_key');
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $recaptchaResponse,
                'remoteip' => $request->ip()
            ]);
            if (!$response->json('success')) {
                return back()->withErrors(['code' => 'reCAPTCHA verification failed. Please try again.'])->withInput();
            }
        }

        $tokenRecord = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->code)
            ->first();

        if (!$tokenRecord || Carbon::parse($tokenRecord->created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->password = Hash::make($request->password);
        $user->save();

        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Reset password successfully',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('login')->with('success', 'Password reset successfully! Please login with your new password.');
    }

    public function showAdmin2FAForm()
    {
        if (!session()->has('admin_2fa_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.admin-2fa');
    }

    public function verifyAdmin2FA(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);
        
        if (!session()->has('admin_2fa_user_id')) {
            return redirect()->route('login');
        }

        if (setting('enable_recaptcha', false)) {
            $recaptchaResponse = $request->input('g-recaptcha-response');
            $secret = setting('recaptcha_secret_key');
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $recaptchaResponse,
                'remoteip' => $request->ip()
            ]);
            if (!$response->json('success')) {
                return back()->withErrors(['code' => 'reCAPTCHA verification failed. Please try again.'])->withInput();
            }
        }

        $user = User::findOrFail(session('admin_2fa_user_id'));

        if ($user->two_factor_code !== $request->code || now()->gt($user->two_factor_expires_at)) {
            return back()->withErrors(['code' => 'Invalid or expired 2FA code.']);
        }

        $user->two_factor_code = null;
        $user->two_factor_expires_at = null;
        $user->save();

        // Perform login manually since they were temporarily logged out
        Auth::login($user);

        session(['admin_2fa_verified' => true]);
        session()->forget('admin_2fa_user_id');

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Completed admin 2FA verification',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $adminSecret = 'admin-fg-secure';
        try {
            $adminSecret = setting('admin_secret_path', 'admin-fg-secure');
        } catch (\Exception $e) {}

        return redirect()->intended($adminSecret);
    }
}
