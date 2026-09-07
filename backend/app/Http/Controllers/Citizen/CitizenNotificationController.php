<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CitizenNotificationController extends Controller
{
    /**
     * Display citizen notifications.
     */
    public function index()
    {
        $notifications = collect([
            (object) [
                'id' => 1,
                'title' => 'Emergency Status Updated',
                'message' => 'Emergency #1042 status changed to "In Progress". Response teams are on scene.',
                'type' => 'Emergency Status Updated',
                'time_ago' => '8 mins ago',
                'timestamp' => Carbon::now()->subMinutes(8)->format('M d, Y - H:i'),
                'is_read' => false,
                'report_id' => 1042,
            ],
            (object) [
                'id' => 2,
                'title' => 'Emergency Assigned',
                'message' => 'Your report #1041 has been assigned to Civil Defense Unit #14 and EMS Paramedics.',
                'type' => 'Emergency Assigned',
                'time_ago' => '30 mins ago',
                'timestamp' => Carbon::now()->subMinutes(30)->format('M d, Y - H:i'),
                'is_read' => false,
                'report_id' => 1041,
            ],
            (object) [
                'id' => 3,
                'title' => 'Analysis Complete',
                'message' => 'Multimodal AI processing concluded for Report #1041. Risk level classified as High.',
                'type' => 'Analysis Complete',
                'time_ago' => '32 mins ago',
                'timestamp' => Carbon::now()->subMinutes(32)->format('M d, Y - H:i'),
                'is_read' => true,
                'report_id' => 1041,
            ],
            (object) [
                'id' => 4,
                'title' => 'Report Received',
                'message' => 'Emergency Report #1042 was received by Central Operations. Triage in progress.',
                'type' => 'Report Received',
                'time_ago' => '12 mins ago',
                'timestamp' => Carbon::now()->subMinutes(12)->format('M d, Y - H:i'),
                'is_read' => true,
                'report_id' => 1042,
            ],
            (object) [
                'id' => 5,
                'title' => 'Emergency Resolved',
                'message' => 'Emergency Report #1038 has been safely concluded by attending paramedic teams.',
                'type' => 'Emergency Resolved',
                'time_ago' => '4 hours ago',
                'timestamp' => Carbon::now()->subHours(4)->format('M d, Y - H:i'),
                'is_read' => true,
                'report_id' => 1038,
            ],
        ]);

        return view('citizen.notifications', [
            'notifications' => $notifications,
        ]);
    }
}
