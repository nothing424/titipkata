@extends('layouts.app')

@section('title', $room->title . ' — TitipKata')
@section('description', $room->description)

@section('content')
<div class="min-h-screen px-4 py-10">
    <div class="max-w-xl mx-auto">

        <!-- Back -->
        <div class="mb-6">
            <a href="{{ route('home') }}" class="btn btn-sm">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                Beranda
            </a>
        </div>

        @if(session('success'))
        <!-- Success State -->
        <div class="card card-hero text-center py-10" style="background:#25D366; border-color:#1A1A1A;">
            <div class="text-5xl mb-4">🎉</div>
            <h2 class="text-2xl font-black text-white mb-2">Terkirim!</h2>
            <p class="font-semibold text-white mb-6" style="opacity:0.9;">Ceritamu berhasil dikirim dan sedang menunggu review.</p>
            <a href="{{ route('room.show', $room->room_code) }}" class="btn btn-primary">Kirim lagi</a>
        </div>

        @elseif($room->isExpired())
        <!-- Expired State -->
        <div class="card text-center py-10">
            <div class="text-5xl mb-4">⏰</div>
            <h2 class="text-2xl font-black mb-2">Room Telah Berakhir</h2>
            <p style="color:#6B6B6B;">Room ini sudah tidak menerima kiriman baru.</p>
        </div>

        @else
        <!-- Room Info -->
        <div class="card card-hero mb-6" style="background:#FFFDF7;">
            <div class="flex items-start gap-3 mb-4">
                <span class="badge" style="background:{{ $room->color_badge }}; font-size:12px; padding: 4px 14px;">
                    {{ $room->category ?? 'Cerita' }}
                </span>
                @if($room->expires_at && !$room->isExpired())
                    <span class="badge" style="background:#FFE066; font-size:11px;" id="countdown-badge">
                        ⏳ <span id="countdown">...</span>
                    </span>
                @endif
            </div>

            <h1 class="text-3xl font-black mb-2 leading-tight">{{ $room->title }}</h1>

            @if($room->description)
            <p class="font-medium" style="color:#3A3A3A; line-height:1.6;">{{ $room->description }}</p>
            @endif
        </div>

        <!-- Submission Form -->
        <div class="card">
            <h2 class="text-xl font-black mb-5">
                ✍️ Titipkan Ceritamu
            </h2>

            @if($errors->any())
            <div class="mb-4 p-3 rounded-lg border-2 border-primary" style="background:#FF7A6B;">
                @foreach($errors->all() as $error)
                    <p class="text-sm font-bold">• {{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form action="{{ route('room.submit', $room->room_code) }}" method="POST">
                @csrf

                <!-- Name Field -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">
                        Namamu
                        @if($room->allow_anonymous)
                            <span class="font-normal" style="color:#6B6B6B;">(opsional — kosongkan untuk anonim)</span>
                        @else
                            <span style="color:#FF7A6B;">*wajib</span>
                        @endif
                    </label>
                    <input
                        type="text"
                        name="name"
                        class="input"
                        placeholder="{{ $room->allow_anonymous ? 'Anonim' : 'Masukkan namamu...' }}"
                        value="{{ old('name') }}"
                        {{ !$room->allow_anonymous ? 'required' : '' }}
                        maxlength="100"
                    >
                </div>

                <!-- Content Field -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">
                        Ceritamu <span style="color:#FF7A6B;">*wajib</span>
                    </label>
                    <textarea
                        name="content"
                        class="input"
                        placeholder="Tulis cerita, pesan, pengalaman, atau apapun yang ingin kamu titipkan..."
                        rows="7"
                        maxlength="5000"
                        id="content-field"
                        required
                    >{{ old('content') }}</textarea>
                    <div class="flex justify-end mt-1">
                        <span class="text-xs font-semibold" style="color:#6B6B6B;">
                            <span id="char-count">0</span>/5000
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-full btn-lg">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    Kirim Cerita
                </button>

                <p class="text-center text-xs mt-3" style="color:#6B6B6B;">
                    Ceritamu akan dimoderasi sebelum dipublikasikan.
                </p>
            </form>
        </div>

        @endif

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-xs font-semibold" style="color:#6B6B6B;">
                TitipKata — titipkata.my.id
            </p>
        </div>

    </div>
</div>

@push('scripts')
<script>
    // Char counter
    const textarea = document.getElementById('content-field');
    const counter = document.getElementById('char-count');
    if (textarea && counter) {
        counter.textContent = textarea.value.length;
        textarea.addEventListener('input', function() {
            counter.textContent = this.value.length;
        });
    }

    // Countdown timer
    @if($room->expires_at && !$room->isExpired())
    const expiresAt = new Date('{{ $room->expires_at->toISOString() }}');
    function updateCountdown() {
        const now = new Date();
        const diff = expiresAt - now;
        if (diff <= 0) {
            document.getElementById('countdown').textContent = 'Berakhir';
            return;
        }
        const days = Math.floor(diff / 86400000);
        const hours = Math.floor((diff % 86400000) / 3600000);
        const mins = Math.floor((diff % 3600000) / 60000);
        let text = '';
        if (days > 0) text += days + 'h ';
        if (hours > 0) text += hours + 'j ';
        text += mins + 'm lagi';
        document.getElementById('countdown').textContent = text;
    }
    updateCountdown();
    setInterval(updateCountdown, 60000);
    @endif
</script>
@endpush

@endsection
