<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::withCount(['submissions', 'pendingSubmissions'])->latest()->paginate(20);
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
            'allow_anonymous' => 'boolean',
            'category' => 'nullable|string|max:100',
            'color_badge' => 'nullable|string|max:7',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $validated['room_code'] = $this->generateUniqueCode();
        $validated['is_public'] = $request->boolean('is_public');
        $validated['allow_anonymous'] = $request->boolean('allow_anonymous');

        $room = Room::create($validated);

        return redirect()->route('admin.rooms.show', $room->room_code)
            ->with('success', 'Room berhasil dibuat!');
    }

    public function show(string $roomCode)
    {
        $room = Room::where('room_code', $roomCode)->firstOrFail();
        $submissions = $room->submissions()->latest()->paginate(20);
        return view('admin.rooms.show', compact('room', 'submissions'));
    }

    public function edit(string $roomCode)
    {
        $room = Room::where('room_code', $roomCode)->firstOrFail();
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, string $roomCode)
    {
        $room = Room::where('room_code', $roomCode)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
            'allow_anonymous' => 'boolean',
            'category' => 'nullable|string|max:100',
            'color_badge' => 'nullable|string|max:7',
            'expires_at' => 'nullable|date',
        ]);

        $validated['is_public'] = $request->boolean('is_public');
        $validated['allow_anonymous'] = $request->boolean('allow_anonymous');

        $room->update($validated);

        return redirect()->route('admin.rooms.show', $room->room_code)
            ->with('success', 'Room berhasil diperbarui!');
    }

    public function destroy(string $roomCode)
    {
        $room = Room::where('room_code', $roomCode)->firstOrFail();
        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', 'Room berhasil dihapus.');
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = Str::lower(Str::random(6));
        } while (Room::where('room_code', $code)->exists());

        return $code;
    }
}
