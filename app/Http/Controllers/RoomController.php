<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Submission;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function show(string $roomCode)
    {
        $room = Room::where('room_code', $roomCode)->firstOrFail();
        return view('rooms.show', compact('room'));
    }

    public function submit(Request $request, string $roomCode)
    {
        $room = Room::where('room_code', $roomCode)->firstOrFail();

        if ($room->isExpired()) {
            return back()->withErrors(['content' => 'Room ini sudah kadaluarsa.']);
        }

        $rules = [
            'content' => 'required|string|max:5000',
        ];

        if (!$room->allow_anonymous) {
            $rules['name'] = 'required|string|max:100';
        } else {
            $rules['name'] = 'nullable|string|max:100';
        }

        $validated = $request->validate($rules);

        Submission::create([
            'room_id' => $room->id,
            'name' => $validated['name'] ?? null,
            'content' => strip_tags($validated['content']),
            'status' => 'pending',
        ]);

        return back()->with('success', true);
    }
}
