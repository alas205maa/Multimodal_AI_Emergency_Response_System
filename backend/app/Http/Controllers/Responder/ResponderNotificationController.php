<?php

namespace App\Http\Controllers\Responder;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ResponderNotificationController extends Controller
{
    /**
     * Display responder notifications.
     */
    public function index()
    {
        $notifications = collect([
            (object) [
                'id' => 101,
                'category' => 'Critical Emergency Alert',
                'title' => 'Critical Structure Fire Escalation',
                'message' => 'Emergency #1042 escalated to Critical. AI fusion detected active fire plume and confirmed human entrapment threat.',
                'emergency_id' => 1042,
                'is_read' => false,
                'time_ago' => '12 mins ago',
                'timestamp' => Carbon::now()->subMinutes(12)->format('M d, Y - H:i:s'),
            ],
            (object) [
                'id' => 102,
                'category' => 'New Emergency Report',
                'title' => 'Multi-Vehicle Collision Reported',
                'message' => 'New report #1041 submitted at King Fahd Rd & Tahlia St. Fuel leak flagged by citizen.',
                'emergency_id' => 1041,
                'is_read' => false,
                'time_ago' => '34 mins ago',
                'timestamp' => Carbon::now()->subMinutes(34)->format('M d, Y - H:i:s'),
            ],
            (object) [
                'id' => 103,
                'category' => 'Emergency Status Update',
                'title' => 'Unit Dispatched to Incident #1040',
                'message' => 'Civil Defense Water Rescue Unit #8 acknowledged dispatch and is en route (ETA 8 mins).',
                'emergency_id' => 1040,
                'is_read' => true,
                'time_ago' => '1 hour ago',
                'timestamp' => Carbon::now()->subHours(1)->format('M d, Y - H:i:s'),
            ],
            (object) [
                'id' => 104,
                'category' => 'System Notification',
                'title' => 'Geospatial Radar Mesh Online',
                'message' => 'Regional emergency grid sync completed. All station telemetries operating normally.',
                'emergency_id' => null,
                'is_read' => true,
                'time_ago' => '3 hours ago',
                'timestamp' => Carbon::now()->subHours(3)->format('M d, Y - H:i:s'),
            ],
        ]);

        return view('responder.notifications', [
            'notifications' => $notifications,
        ]);
    }
}
