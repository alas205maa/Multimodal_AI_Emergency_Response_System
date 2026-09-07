<?php

namespace App\Http\Controllers\Responder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResponderProfileController extends Controller
{
    /**
     * Display responder profile details.
     */
    public function show()
    {
        $responder = (object) [
            'name' => 'Capt. Tariq Al-Mansoor',
            'badge_id' => 'EMR-8842',
            'role' => 'Lead Incident Commander',
            'department' => 'Central Command & Civil Defense Coordination',
            'email' => 'tariq.mansoor@emergency-response.gov.sa',
            'phone' => '+966 50 123 4567',
            'station' => 'Station #01 - Regional HQ',
        ];

        return view('responder.profile', [
            'responder' => $responder,
        ]);
    }

    /**
     * Update profile details.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['required'],
        ]);

        return back()->with('status', 'Commander profile updated successfully.');
    }

    /**
     * Change responder password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        return back()->with('status', 'Credentials updated successfully.');
    }
}
