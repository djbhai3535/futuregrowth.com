<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

        $referrer = null;
        if ($request->referral_code) {
            $referrer = User::where('referral_code', $request->referral_code)->first();
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'referral_code' => Str::random(10),
            'referred_by' => $referrer ? $referrer->id : null,
            'status' => 'active'
        ]);

        // Create Wallet
        $wallet = Wallet::create(['user_id' => $user->id]);

        // Check for Free Sign-up Bonus
        $bonusAmount = Setting::getVal('signup_bonus_amount', 10);
        $maxBonusUsers = Setting::getVal('max_bonus_users', 1000);
        $currentUsers = User::count();

        if ($bonusAmount > 0 && $currentUsers <= $maxBonusUsers) {
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
            'action' => 'Registered account',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('dashboard');
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

        if (Auth::attempt($credentials)) {
            if (Auth::user()->status === 'banned') {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been banned.']);
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
}
