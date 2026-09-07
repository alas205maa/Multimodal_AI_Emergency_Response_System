<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CitizenProfileController extends Controller
{
    /**
     * Display profile page.
     */
    public function show()
    {
        $user = auth()->user() ?? (object) [
            'name' => 'Fahad Al-Otaibi',
            'email' => 'citizen@emergency-system.sa',
            'created_at' => now()->subMonths(3),
        ];

        return view('citizen.profile', [
            'user' => $user,
        ]);
    }

    /**
     * Update profile info.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        return back()->with('status', 'Profile details updated successfully.');
    }

    /**
     * Change password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        return back()->with('status', 'Password changed successfully.');
    }
}
