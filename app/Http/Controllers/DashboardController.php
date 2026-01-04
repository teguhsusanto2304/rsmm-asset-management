<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    /**
     * Show dashboard with dummy data
     */
    public function index()
    {
        // Statistics - dummy data
        $stats = [
            'total_assets' => 1240,
            'mutated_assets' => 86,
            'damaged_assets' => 15,
            'in_handling' => 8,
        ];

        // Latest Mutation Requests - dummy data
        $latestMutations = collect([
            (object)[
                'id' => 1,
                'request_id' => 'REQ-001',
                'asset' => (object)['name' => 'Laptop Dell XPS 15'],
                'fromLocation' => (object)['name' => 'Gudang A'],
                'toLocation' => (object)['name' => 'Kantor Pemasaran'],
                'status' => 'pending',
                'status_badge' => '<span class="inline-flex items-center justify-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800">Pending</span>'
            ],
            (object)[
                'id' => 2,
                'request_id' => 'REQ-002',
                'asset' => (object)['name' => 'Proyektor Epson'],
                'fromLocation' => (object)['name' => 'Ruang Rapat B'],
                'toLocation' => (object)['name' => 'Ruang Rapat C'],
                'status' => 'approved',
                'status_badge' => '<span class="inline-flex items-center justify-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">Approved</span>'
            ],
            (object)[
                'id' => 3,
                'request_id' => 'REQ-003',
                'asset' => (object)['name' => 'Kursi Ergonomis'],
                'fromLocation' => (object)['name' => 'Gudang B'],
                'toLocation' => (object)['name' => 'Kantor HRD'],
                'status' => 'rejected',
                'status_badge' => '<span class="inline-flex items-center justify-center rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800">Rejected</span>'
            ],
        ]);

        // Latest Damage Reports - dummy data
        $latestDamages = collect([
            (object)[
                'id' => 1,
                'report_id' => 'DMG-001',
                'asset' => (object)['name' => 'Monitor Samsung 27"'],
                'reporter' => (object)['name' => 'John Doe'],
                'status' => 'in_progress',
                'status_badge' => '<span class="inline-flex items-center justify-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800">In Progress</span>'
            ],
            (object)[
                'id' => 2,
                'report_id' => 'DMG-002',
                'asset' => (object)['name' => 'Meja Kerja Staff'],
                'reporter' => (object)['name' => 'Jane Smith'],
                'status' => 'resolved',
                'status_badge' => '<span class="inline-flex items-center justify-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">Resolved</span>'
            ],
            (object)[
                'id' => 3,
                'report_id' => 'DMG-003',
                'asset' => (object)['name' => 'Printer HP LaserJet'],
                'reporter' => (object)['name' => 'Mike Johnson'],
                'status' => 'new_report',
                'status_badge' => '<span class="inline-flex items-center justify-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-800">New Report</span>'
            ],
        ]);

        // Get authenticated user

        return view('admin.dashboard', compact('stats', 'latestMutations', 'latestDamages'));
    }

    /**
     * Get dashboard data (API endpoint for AJAX)
     */
    public function getData()
    {
        $stats = [
            'total_assets' => 1240,
            'mutated_assets' => 86,
            'damaged_assets' => 15,
            'in_handling' => 8,
        ];

        return response()->json($stats);
    }

    /**
     * Get mutation requests data
     */
    public function getMutationRequests()
    {
        $mutations = collect([
            (object)[
                'id' => 1,
                'request_id' => 'REQ-001',
                'asset_name' => 'Laptop Dell XPS 15',
                'from_location' => 'Gudang A',
                'to_location' => 'Kantor Pemasaran',
                'status' => 'pending'
            ],
            (object)[
                'id' => 2,
                'request_id' => 'REQ-002',
                'asset_name' => 'Proyektor Epson',
                'from_location' => 'Ruang Rapat B',
                'to_location' => 'Ruang Rapat C',
                'status' => 'approved'
            ],
            (object)[
                'id' => 3,
                'request_id' => 'REQ-003',
                'asset_name' => 'Kursi Ergonomis',
                'from_location' => 'Gudang B',
                'to_location' => 'Kantor HRD',
                'status' => 'rejected'
            ],
            (object)[
                'id' => 4,
                'request_id' => 'REQ-004',
                'asset_name' => 'Monitor LG 24 Inch',
                'from_location' => 'Kantor Pusat',
                'to_location' => 'Kantor Cabang',
                'status' => 'pending'
            ],
            (object)[
                'id' => 5,
                'request_id' => 'REQ-005',
                'asset_name' => 'Keyboard Mechanical RGB',
                'from_location' => 'Gudang A',
                'to_location' => 'Kantor IT',
                'status' => 'approved'
            ],
        ]);

        return response()->json($mutations);
    }

    /**
     * Get damage reports data
     */
    public function getDamageReports()
    {
        $damages = collect([
            (object)[
                'id' => 1,
                'report_id' => 'DMG-001',
                'asset_name' => 'Monitor Samsung 27"',
                'reporter_name' => 'John Doe',
                'status' => 'in_progress',
                'created_at' => '2025-11-28'
            ],
            (object)[
                'id' => 2,
                'report_id' => 'DMG-002',
                'asset_name' => 'Meja Kerja Staff',
                'reporter_name' => 'Jane Smith',
                'status' => 'resolved',
                'created_at' => '2025-11-27'
            ],
            (object)[
                'id' => 3,
                'report_id' => 'DMG-003',
                'asset_name' => 'Printer HP LaserJet',
                'reporter_name' => 'Mike Johnson',
                'status' => 'new_report',
                'created_at' => '2025-12-01'
            ],
            (object)[
                'id' => 4,
                'report_id' => 'DMG-004',
                'asset_name' => 'CPU Desktop Intel',
                'reporter_name' => 'Sarah Williams',
                'status' => 'in_progress',
                'created_at' => '2025-11-30'
            ],
            (object)[
                'id' => 5,
                'report_id' => 'DMG-005',
                'asset_name' => 'Mouse Wireless Logitech',
                'reporter_name' => 'David Brown',
                'status' => 'resolved',
                'created_at' => '2025-11-25'
            ],
        ]);

        return response()->json($damages);
    }
}