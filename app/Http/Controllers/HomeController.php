<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $publicMode = setting('public_mode', false);

        if ($publicMode) {
            $rooms = Room::where('is_public', true)
                ->where(function($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->latest()
                ->paginate(12);
            return view('home.public', compact('rooms'));
        }

        return view('home.private');
    }

    public function joinRoom(Request $request)
    {
        $request->validate([
            'room_code' => 'required|string|max:20',
        ]);

        $room = Room::where('room_code', strtolower(trim($request->room_code)))->first();

        if (!$room) {
            return back()->withErrors(['room_code' => 'Kode room tidak ditemukan.'])->withInput();
        }

        return redirect()->route('room.show', $room->room_code);
    }
}
