<?php

namespace App\Http\Controllers\Responder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResponderSettingsController extends Controller
{
    /**
     * Display operational settings.
     */
    public function index()
    {
        $settings = (object) [
            'audio_alerts' => true,
            'critical_flash' => false,
            'auto_refresh_interval' => 15,
            'map_style' => 'high-contrast',
            'theme' => 'light-calm',
            'sms_escalations' => true,
        ];

        return view('responder.settings', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        return back()->with('status', 'Operational preferences saved successfully.');
    }
}
