@extends('layouts.app')

@section('title', 'Admin Login — TitipKata')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="text-4xl font-black logo-text" style="text-decoration:none; color:#1A1A1A;">TitipKata</a>
            <p class="text-sm font-semibold mt-1" style="color:#6B6B6B;">Admin Panel</p>
        </div>

        <div class="card card-hero">
            <h1 class="text-2xl font-black mb-6 flex items-center gap-2">
                🔑 Login Admin
            </h1>

            @if($errors->any())
            <div class="mb-4 p-3 rounded-xl border-2 border-primary" style="background:#FF7A6B;">
                @foreach($errors->all() as $error)
                    <p class="text-sm font-bold">{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" class="input" placeholder="admin@titipkata.my.id" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-bold mb-2">Password</label>
                    <input type="password" name="password" class="input" placeholder="••••••••" required>
                </div>

                <div class="flex items-center gap-2 mb-5">
                    <input type="checkbox" name="remember" id="remember" style="width:18px;height:18px;border:2px solid #1A1A1A;border-radius:4px;">
                    <label for="remember" class="text-sm font-semibold">Ingat saya</label>
                </div>

                <button type="submit" class="btn btn-primary w-full btn-lg">
                    Masuk sebagai Admin
                </button>
            </form>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="text-sm font-semibold underline" style="color:#6B6B6B;">← Kembali ke beranda</a>
        </div>
    </div>
</div>
@endsection
