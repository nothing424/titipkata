@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black">Dashboard</h1>
            <p class="text-sm" style="color:#6B6B6B;">Selamat datang, {{ auth('admin')->user()->name }}!</p>
        </div>
        <a href="{{ route('admin.rooms.create') }}" class="btn btn-yellow">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            Buat Room
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-8">
        <div class="card text-center" style="box-shadow:3px 3px 0 #1A1A1A;">
            <div class="text-3xl font-black mb-1">{{ $stats['total_rooms'] }}</div>
            <div class="text-xs font-bold" style="color:#6B6B6B;">Total Room</div>
        </div>
        <div class="card text-center" style="box-shadow:3px 3px 0 #1A1A1A;">
            <div class="text-3xl font-black mb-1">{{ $stats['total_submissions'] }}</div>
            <div class="text-xs font-bold" style="color:#6B6B6B;">Total Kiriman</div>
        </div>
        <div class="card text-center" style="background:#FFE066;box-shadow:3px 3px 0 #1A1A1A;">
            <div class="text-3xl font-black mb-1">{{ $stats['pending'] }}</div>
            <div class="text-xs font-bold">Pending</div>
        </div>
        <div class="card text-center" style="background:#25D366;color:white;box-shadow:3px 3px 0 #1A1A1A;">
            <div class="text-3xl font-black mb-1">{{ $stats['approved'] }}</div>
            <div class="text-xs font-bold">Disetujui</div>
        </div>
        <div class="card text-center" style="background:#A8D0F0;box-shadow:3px 3px 0 #1A1A1A;">
            <div class="text-3xl font-black mb-1">{{ $stats['posted'] }}</div>
            <div class="text-xs font-bold">Diposting</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Room List -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-black">Semua Room</h2>
                <a href="{{ route('admin.rooms.index') }}" class="btn btn-sm">Lihat semua</a>
            </div>

            <div class="flex flex-col gap-3">
                @forelse($rooms as $room)
                <div class="card" style="padding:14px;box-shadow:3px 3px 0 #1A1A1A;">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="badge" style="background:{{ $room->color_badge }};font-size:10px;padding:2px 8px;">{{ $room->category ?? 'Umum' }}</span>
                                @if($room->isExpired())<span class="badge status-rejected" style="font-size:10px;padding:2px 8px;">Berakhir</span>@endif
                                @if($room->pending_submissions_count > 0)
                                    <span class="badge status-pending" style="font-size:10px;padding:2px 8px;">{{ $room->pending_submissions_count }} pending</span>
                                @endif
                            </div>
                            <h3 class="font-black text-sm">{{ $room->title }}</h3>
                            <p class="text-xs font-mono" style="color:#6B6B6B;">{{ $room->room_code }}</p>
                        </div>
                        <div class="flex gap-1 ml-2">
                            <a href="{{ route('admin.rooms.show', $room->room_code) }}" class="btn btn-sm btn-blue" style="padding:4px 10px;">Kelola</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="card text-center py-6" style="color:#6B6B6B;box-shadow:3px 3px 0 #1A1A1A;">
                    <p class="font-bold text-sm">Belum ada room.</p>
                    <a href="{{ route('admin.rooms.create') }}" class="btn btn-yellow btn-sm mt-2">Buat Room Pertama</a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Activity -->
        <div>
            <h2 class="text-lg font-black mb-3">Kiriman Terbaru</h2>
            <div class="flex flex-col gap-2">
                @forelse($recentSubmissions as $sub)
                <div class="card" style="padding:12px;box-shadow:2px 2px 0 #1A1A1A;">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="badge status-{{ $sub->status }}" style="font-size:10px;padding:2px 8px;">{{ $sub->getStatusLabel() }}</span>
                                <span class="text-xs font-bold" style="color:#6B6B6B;">{{ $sub->room->title }}</span>
                            </div>
                            <p class="text-sm font-medium truncate">{{ $sub->getDisplayName() }}: {{ Str::limit($sub->content, 60) }}</p>
                            <p class="text-xs" style="color:#6B6B6B;">{{ $sub->created_at->diffForHumans() }}</p>
                        </div>
                        <a href="{{ route('admin.rooms.show', $sub->room->room_code) }}" class="btn btn-sm" style="padding:4px 8px;flex-shrink:0;">→</a>
                    </div>
                </div>
                @empty
                <div class="card text-center py-6" style="color:#6B6B6B;box-shadow:2px 2px 0 #1A1A1A;">
                    <p class="font-bold text-sm">Belum ada kiriman.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
