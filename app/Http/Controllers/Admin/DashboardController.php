<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Submission;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_rooms' => Room::count(),
            'total_submissions' => Submission::count(),
            'pending' => Submission::where('status', 'pending')->count(),
            'approved' => Submission::where('status', 'approved')->count(),
            'posted' => Submission::where('status', 'posted')->count(),
        ];

        $recentSubmissions = Submission::with('room')
            ->latest()
            ->take(10)
            ->get();

        $rooms = Room::withCount(['submissions', 'pendingSubmissions'])
            ->latest()
            ->get();

        return view('admin.dashboard', compact('stats', 'recentSubmissions', 'rooms'));
    }
}
