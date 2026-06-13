@extends('layouts.app')

@section('title', 'TitipKata')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center px-4 py-12">

    <!-- Floating decorations -->
    <div class="fixed top-12 left-8 opacity-30 text-4xl select-none pointer-events-none">✦</div>
    <div class="fixed top-32 right-12 opacity-20 text-2xl select-none pointer-events-none">◆</div>
    <div class="fixed bottom-20 left-16 opacity-20 text-3xl select-none pointer-events-none">●</div>
    <div class="fixed bottom-32 right-8 opacity-30 text-2xl select-none pointer-events-none">✦</div>

    <div class="w-full max-w-md">

        <!-- Logo -->
        <div class="text-center mb-10">
            <div class="inline-block mb-4">
                <div class="relative inline-block">
                    <h1 class="text-6xl font-black logo-text tracking-tight" style="text-shadow: 4px 4px 0 #1A1A1A; -webkit-text-stroke: 2px #1A1A1A; color: #FDF6E3; paint-order: stroke fill;">TitipKata</h1>
                </div>
            </div>
            <p class="text-lg font-semibold" style="color:#3A3A3A;">Tempat menitipkan cerita, pesan, dan perasaan.</p>
        </div>

        <!-- Main Card -->
        <div class="card card-hero mb-6">
            <div class="text-center mb-6">
                <div class="inline-flex items-center gap-2 mb-3">
                    <span class="text-2xl">🔐</span>
                    <h2 class="text-xl font-black">Masuk ke Room</h2>
                </div>
                <p class="text-sm" style="color:#6B6B6B; line-height:1.6;">
                    Website ini tidak tersedia secara publik.<br>
                    Masukkan kode room untuk melanjutkan.
                </p>
            </div>

            <form action="{{ route('room.join') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Kode Room</label>
                    <input
                        type="text"
                        name="room_code"
                        class="input text-center text-xl font-bold tracking-widest uppercase"
                        placeholder="abc123"
                        value="{{ old('room_code') }}"
                        autocomplete="off"
                        style="letter-spacing: 0.2em;"
                    >
                    @error('room_code')
                        <p class="mt-2 text-sm font-bold" style="color:#FF7A6B;">⚠ {{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-full btn-lg">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Masuk Room
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center">
            <p class="text-xs" style="color:#6B6B6B;">
                TitipKata &copy; {{ date('Y') }} — titipkata.my.id
            </p>
            <a href="{{ route('admin.login') }}" class="text-xs font-semibold mt-1 inline-block" style="color:#6B6B6B; text-decoration:underline;">Admin Login</a>
        </div>
    </div>
</div>

<style>
.logo-text {
    background: linear-gradient(135deg, #FDF6E3 0%, #FFFDF7 100%);
    -webkit-background-clip: text;
    background-clip: text;
}
</style>
@endsection
