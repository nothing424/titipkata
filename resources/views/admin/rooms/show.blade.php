@extends('layouts.admin')

@section('title', $room->title)

@section('content')
<div class="p-6">

    <!-- Header -->
    <div class="flex items-start justify-between flex-wrap gap-3 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-sm">← Kembali</a>
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="badge" style="background:{{ $room->color_badge }};">{{ $room->category ?? 'Umum' }}</span>
                    @if($room->isExpired())<span class="badge status-rejected">Berakhir</span>@endif
                    @if($room->is_public)<span class="badge" style="background:#A8D0F0;">Publik</span>@else<span class="badge" style="background:#FFE066;">Privat</span>@endif
                </div>
                <h1 class="text-2xl font-black">{{ $room->title }}</h1>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.rooms.edit', $room->room_code) }}" class="btn btn-yellow">Edit Room</a>
            <a href="{{ $room->getParticipantUrl() }}" target="_blank" class="btn">🔗 Buka Room</a>
        </div>
    </div>

    <!-- Room Info + Links -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <div class="card lg:col-span-2" style="box-shadow:3px 3px 0 #1A1A1A;">
            <h3 class="font-black mb-3">Informasi Room</h3>
            @if($room->description)
                <p class="text-sm mb-3" style="color:#3A3A3A;line-height:1.6;">{{ $room->description }}</p>
            @endif
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <div class="font-bold text-xs mb-1" style="color:#6B6B6B;">KODE ROOM</div>
                    <div class="font-black text-xl font-mono">{{ $room->room_code }}</div>
                </div>
                @if($room->expires_at)
                <div>
                    <div class="font-bold text-xs mb-1" style="color:#6B6B6B;">BERAKHIR</div>
                    <div class="font-bold">{{ $room->expires_at->format('d M Y, H:i') }}</div>
                </div>
                @endif
                <div>
                    <div class="font-bold text-xs mb-1" style="color:#6B6B6B;">TOTAL KIRIMAN</div>
                    <div class="font-black text-xl">{{ $submissions->total() }}</div>
                </div>
                <div>
                    <div class="font-bold text-xs mb-1" style="color:#6B6B6B;">DIBUAT</div>
                    <div class="font-bold">{{ $room->created_at->format('d M Y') }}</div>
                </div>
            </div>
        </div>

        <div class="card" style="box-shadow:3px 3px 0 #1A1A1A;">
            <h3 class="font-black mb-3">Link & QR</h3>
            <div class="mb-3">
                <div class="font-bold text-xs mb-1" style="color:#6B6B6B;">LINK PESERTA</div>
                <div class="flex gap-2 items-center">
                    <input type="text" class="input text-xs" value="{{ $room->getParticipantUrl() }}" id="participant-url" readonly style="font-size:11px;padding:6px 10px;">
                    <button onclick="copyText('participant-url')" class="btn btn-sm btn-blue">Salin</button>
                </div>
            </div>
            <div class="mb-3">
                <div class="font-bold text-xs mb-1" style="color:#6B6B6B;">KODE ROOM</div>
                <div class="flex gap-2 items-center">
                    <input type="text" class="input text-center font-mono font-black" value="{{ $room->room_code }}" id="room-code-input" readonly>
                    <button onclick="copyText('room-code-input')" class="btn btn-sm btn-yellow">Salin</button>
                </div>
            </div>
            <!-- QR Code via Google Chart API -->
            <div class="text-center mt-3">
                <img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl={{ urlencode($room->getParticipantUrl()) }}&choe=UTF-8" alt="QR Code" class="mx-auto border-2 border-primary rounded-lg">
                <p class="text-xs font-bold mt-1" style="color:#6B6B6B;">QR Code Room</p>
            </div>
        </div>
    </div>

    <!-- Submissions -->
    <div class="card" style="box-shadow:3px 3px 0 #1A1A1A;">
        <div class="flex items-center justify-between flex-wrap gap-3 mb-4">
            <h2 class="text-lg font-black">Kiriman ({{ $submissions->total() }})</h2>

            <!-- Filter -->
            <form action="{{ route('admin.rooms.show', $room->room_code) }}" method="GET" class="flex gap-2 flex-wrap">
                <input type="text" name="search" placeholder="Cari..." class="input" style="width:180px;padding:7px 12px;font-size:13px;" value="{{ request('search') }}">
                <select name="status" class="input" style="width:140px;padding:7px 12px;font-size:13px;">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    <option value="posted" {{ request('status') == 'posted' ? 'selected' : '' }}>Diposting</option>
                </select>
                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                @if(request()->hasAny(['search','status']))
                    <a href="{{ route('admin.rooms.show', $room->room_code) }}" class="btn btn-sm">Reset</a>
                @endif
            </form>
        </div>

        <div class="flex flex-col gap-3">
            @forelse($submissions as $sub)
            <div class="border-2 border-primary rounded-xl p-4" style="background:#FFFDF7; box-shadow:2px 2px 0 #1A1A1A;" id="sub-{{ $sub->id }}">
                <div class="flex items-start justify-between gap-3 flex-wrap">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                            <span class="badge status-{{ $sub->status }}" style="font-size:11px;">{{ $sub->getStatusLabel() }}</span>
                            <span class="font-black text-sm">{{ $sub->getDisplayName() }}</span>
                            <span class="text-xs" style="color:#6B6B6B;">{{ $sub->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm" style="line-height:1.7; color:#1A1A1A; white-space:pre-line;">{{ $sub->content }}</p>
                    </div>

                    <div class="flex flex-col gap-1 flex-shrink-0">
                        <!-- Status Actions -->
                        @if($sub->status !== 'approved')
                        <button onclick="updateStatus({{ $sub->id }}, 'approved')" class="btn btn-sm btn-green">✓ Setujui</button>
                        @endif
                        @if($sub->status !== 'rejected')
                        <button onclick="updateStatus({{ $sub->id }}, 'rejected')" class="btn btn-sm btn-coral">✗ Tolak</button>
                        @endif
                        @if($sub->status === 'approved')
                        <button onclick="updateStatus({{ $sub->id }}, 'posted')" class="btn btn-sm btn-blue">📱 Posted</button>
                        @endif

                        <!-- Tools -->
                        <button onclick="copyContent({{ $sub->id }})" class="btn btn-sm btn-yellow">📋 Salin</button>
                        <a href="{{ route('admin.submissions.quote', $sub->id) }}" target="_blank" class="btn btn-sm btn-pink">🎨 Quote</a>

                        <!-- Delete -->
                        <form action="{{ route('admin.submissions.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('Hapus kiriman ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="background:#fee2e2;width:100%;">🗑 Hapus</button>
                        </form>
                    </div>
                </div>

                <!-- Hidden content for copy -->
                <textarea class="sr-only" id="content-{{ $sub->id }}">{{ $sub->content }}</textarea>
            </div>
            @empty
            <div class="text-center py-10" style="color:#6B6B6B;">
                <div class="text-3xl mb-2">📭</div>
                <p class="font-bold">Belum ada kiriman.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-4">{{ $submissions->links() }}</div>
    </div>
</div>

@push('scripts')
<script>
function copyText(id) {
    const el = document.getElementById(id);
    el.select();
    document.execCommand('copy');
    alert('Tersalin!');
}

function copyContent(id) {
    const el = document.getElementById('content-' + id);
    el.select();
    document.execCommand('copy');
    alert('Teks berhasil disalin!');
}

async function updateStatus(id, status) {
    const labels = {approved:'Disetujui', rejected:'Ditolak', posted:'Diposting', pending:'Pending'};
    if (!confirm('Ubah status menjadi ' + labels[status] + '?')) return;

    const resp = await fetch(`/admin/submissions/${id}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
        },
        body: JSON.stringify({ status }),
    });

    if (resp.ok) {
        location.reload();
    } else {
        alert('Gagal memperbarui status.');
    }
}
</script>
@endpush
@endsection
