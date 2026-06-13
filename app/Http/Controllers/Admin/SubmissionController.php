<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function index(Request $request, string $roomCode)
    {
        $room = Room::where('room_code', $roomCode)->firstOrFail();

        $query = $room->submissions()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $submissions = $query->paginate(20)->withQueryString();

        return view('admin.submissions.index', compact('room', 'submissions'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $submission = Submission::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,approved,rejected,posted',
        ]);

        $submission->update(['status' => $request->status]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'status' => $submission->status]);
        }

        return back()->with('success', 'Status berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $submission = Submission::findOrFail($id);
        $roomCode = $submission->room->room_code;
        $submission->delete();

        return back()->with('success', 'Kiriman berhasil dihapus.');
    }

    public function generateQuoteImage(int $id)
    {
        $submission = Submission::with('room')->findOrFail($id);
        return view('admin.submissions.quote-image', compact('submission'));
    }
}
