<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\ActivityLog;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return view('dashboard.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
        ];

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $updateData['avatar'] = $path;
        }

        $user->update($updateData);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Updated profile information' . ($request->hasFile('avatar') ? ' and uploaded new avatar' : ''),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function settings()
    {
        return view('dashboard.settings');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided current password does not match our records.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Changed password',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}
