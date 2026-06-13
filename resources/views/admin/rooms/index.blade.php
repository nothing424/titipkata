@extends('layouts.admin')

@section('title', 'Daftar Room')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black">Daftar Room</h1>
            <p class="text-sm" style="color:#6B6B6B;">{{ $rooms->total() }} room terdaftar</p>
        </div>
        <a href="{{ route('admin.rooms.create') }}" class="btn btn-yellow">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            Buat Room Baru
        </a>
    </div>

    <div class="flex flex-col gap-3">
        @forelse($rooms as $room)
        <div class="card" style="box-shadow:3px 3px 0 #1A1A1A;">
            <div class="flex items-start justify-between flex-wrap gap-3">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <div class="w-4 h-4 rounded-full border-2 border-primary" style="background:{{ $room->color_badge }};"></div>
                        <span class="badge" style="background:{{ $room->color_badge }};font-size:11px;">{{ $room->category ?? 'Umum' }}</span>
                        @if($room->is_public)<span class="badge" style="background:#A8D0F0;font-size:11px;">Publik</span>@else<span class="badge" style="background:#FFE066;font-size:11px;">Privat</span>@endif
                        @if($room->isExpired())<span class="badge status-rejected" style="font-size:11px;">Berakhir</span>@endif
                    </div>
                    <h3 class="font-black text-lg">{{ $room->title }}</h3>
                    @if($room->description)
                        <p class="text-sm mb-2" style="color:#3A3A3A;">{{ Str::limit($room->description, 100) }}</p>
                    @endif
                    <div class="flex flex-wrap gap-3 text-xs font-semibold" style="color:#6B6B6B;">
                        <span>Kode: <strong class="font-mono text-primary">{{ $room->room_code }}</strong></span>
                        <span>{{ $room->submissions_count }} kiriman</span>
                        @if($room->pending_submissions_count > 0)
                            <span class="font-bold" style="color:#FF7A6B;">{{ $room->pending_submissions_count }} pending</span>
                        @endif
                        @if($room->expires_at)
                            <span>Berakhir: {{ $room->expires_at->format('d M Y') }}</span>
                        @endif
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ $room->getParticipantUrl() }}" target="_blank" class="btn btn-sm">🔗 Link</a>
                    <a href="{{ route('admin.rooms.show', $room->room_code) }}" class="btn btn-sm btn-blue">Kelola</a>
                    <a href="{{ route('admin.rooms.edit', $room->room_code) }}" class="btn btn-sm btn-yellow">Edit</a>
                    <form action="{{ route('admin.rooms.destroy', $room->room_code) }}" method="POST" onsubmit="return confirm('Hapus room ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-coral">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="card text-center py-12" style="color:#6B6B6B;">
            <div class="text-4xl mb-3">📭</div>
            <p class="font-black text-lg">Belum ada room.</p>
            <a href="{{ route('admin.rooms.create') }}" class="btn btn-yellow mt-4">Buat Room Pertama</a>
        </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $rooms->links() }}</div>
</div>
@endsection
