@extends('layouts.app')

@section('title', 'TitipKata — Cerita & Pesan')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="border-b-2 border-primary" style="background:#FFFDF7;">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black logo-text">TitipKata</h1>
                <p class="text-xs" style="color:#6B6B6B;">Tempat menitipkan cerita, pesan, dan perasaan.</p>
            </div>
            <form action="{{ route('room.join') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="room_code" class="input" placeholder="Kode room..." style="width:160px;" required>
                <button type="submit" class="btn btn-primary">Masuk</button>
            </form>
        </div>
    </header>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Hero -->
        <div class="card card-hero text-center mb-10 py-12" style="background:#FFE066;">
            <h2 class="text-4xl font-black mb-2">Titipkan Ceritamu</h2>
            <p class="text-lg font-semibold" style="color:#3A3A3A;">Bergabung dengan room dan bagikan kisahmu.</p>
        </div>

        <!-- Rooms Grid -->
        <h3 class="text-2xl font-black mb-4">Room Terbuka</h3>
        @if($rooms->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            @foreach($rooms as $room)
            <a href="{{ route('room.show', $room->room_code) }}" class="card hover:translate-x-[-2px] hover:translate-y-[-2px] transition-transform" style="box-shadow:4px 4px 0 #1A1A1A; text-decoration:none; color:inherit;">
                <div class="flex items-start justify-between mb-3">
                    <span class="badge" style="background:{{ $room->color_badge }};">{{ $room->category ?? 'Umum' }}</span>
                    @if($room->isExpired())
                        <span class="badge status-rejected">Berakhir</span>
                    @endif
                </div>
                <h4 class="font-black text-lg mb-1">{{ $room->title }}</h4>
                <p class="text-sm mb-3" style="color:#6B6B6B;">{{ Str::limit($room->description, 80) }}</p>
                <div class="flex items-center justify-between text-xs font-semibold" style="color:#6B6B6B;">
                    <span>Kode: <strong style="color:#1A1A1A;">{{ $room->room_code }}</strong></span>
                    @if($room->expires_at)
                        <span>Berakhir {{ $room->expires_at->diffForHumans() }}</span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        {{ $rooms->links() }}
        @else
        <div class="card text-center py-12" style="color:#6B6B6B;">
            <div class="text-4xl mb-3">📭</div>
            <p class="font-bold">Belum ada room publik.</p>
        </div>
        @endif
    </div>

    <footer class="border-t-2 border-primary text-center py-6 text-xs" style="color:#6B6B6B;">
        TitipKata &copy; {{ date('Y') }} — titipkata.my.id
        <a href="{{ route('admin.login') }}" class="ml-4 underline">Admin</a>
    </footer>
</div>
@endsection
