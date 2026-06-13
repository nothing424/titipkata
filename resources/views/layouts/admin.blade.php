<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — TitipKata</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { heading: ['Space Grotesk', 'sans-serif'], body: ['Space Grotesk', 'sans-serif'] },
                    colors: {
                        cream: '#FDF6E3', 'cream-light': '#FFFDF7', primary: '#1A1A1A',
                        coral: '#FF7A6B', yellow: '#FFE066', blue: '#A8D0F0', pink: '#FFB5D8', green: '#25D366',
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Space Grotesk', sans-serif; box-sizing: border-box; }
        body { background-color: #FDF6E3; color: #1A1A1A; }
        .btn { display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:8px 18px;font-weight:700;font-size:13px;border:2px solid #1A1A1A;border-radius:10px;box-shadow:3px 3px 0px 0px #1A1A1A;cursor:pointer;transition:all 0.1s;text-decoration:none;background:#FFFDF7;color:#1A1A1A; }
        .btn:hover { transform:translate(-1px,-1px);box-shadow:4px 4px 0px 0px #1A1A1A; }
        .btn:active { transform:translate(2px,2px);box-shadow:1px 1px 0px 0px #1A1A1A; }
        .btn-primary { background:#1A1A1A;color:#FDF6E3; }
        .btn-coral { background:#FF7A6B; }
        .btn-yellow { background:#FFE066; }
        .btn-green { background:#25D366;color:#fff; }
        .btn-blue { background:#A8D0F0; }
        .btn-sm { padding:5px 12px;font-size:11px;border-radius:8px; }
        .card { background:#FFFDF7;border:2px solid #1A1A1A;border-radius:16px;padding:20px;box-shadow:4px 4px 0px 0px #1A1A1A; }
        .input { width:100%;padding:9px 13px;border:2px solid #1A1A1A;border-radius:10px;background:#FFFDF7;font-size:14px;outline:none;transition:box-shadow 0.15s; }
        .input:focus { box-shadow:3px 3px 0px 0px #1A1A1A; }
        .badge { display:inline-flex;align-items:center;padding:3px 10px;border-radius:999px;border:2px solid #1A1A1A;font-size:11px;font-weight:700; }
        .status-pending { background:#FFE066; }
        .status-approved { background:#25D366;color:white; }
        .status-rejected { background:#FF7A6B; }
        .status-posted { background:#A8D0F0; }
        .sidebar-link { display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;font-weight:600;font-size:14px;color:#1A1A1A;text-decoration:none;transition:background 0.15s; }
        .sidebar-link:hover, .sidebar-link.active { background:#FFE066;border:2px solid #1A1A1A;box-shadow:2px 2px 0 #1A1A1A; }
        select.input { appearance:auto; }
        textarea.input { resize:vertical; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen">
<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 border-r-2 border-primary bg-cream-light flex flex-col" style="min-height:100vh;">
        <div class="p-5 border-b-2 border-primary">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <span class="text-xl font-black logo-text">TitipKata</span>
                <span class="badge" style="background:#FFE066;font-size:10px;">Admin</span>
            </a>
        </div>

        <nav class="flex-1 p-4 flex flex-col gap-1">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.rooms.index') }}" class="sidebar-link {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Room
            </a>
            <a href="{{ route('admin.rooms.create') }}" class="sidebar-link {{ request()->routeIs('admin.rooms.create') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                Buat Room
            </a>
        </nav>

        <div class="p-4 border-t-2 border-primary">
            <div class="text-xs text-gray-500 mb-2">Login sebagai</div>
            <div class="font-bold text-sm">{{ auth('admin')->user()->name }}</div>
            <form action="{{ route('admin.logout') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-coral w-full text-xs" style="padding:6px 12px;">Logout</button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-auto">
        @if(session('success'))
        <div class="mx-6 mt-6 p-4 rounded-xl border-2 border-primary font-bold" style="background:#25D366;color:white;box-shadow:3px 3px 0 #1A1A1A;">
            ✓ {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mx-6 mt-6 p-4 rounded-xl border-2 border-primary" style="background:#FF7A6B;box-shadow:3px 3px 0 #1A1A1A;">
            <div class="font-bold">Ada kesalahan:</div>
            @foreach($errors->all() as $error)
                <div class="text-sm">• {{ $error }}</div>
            @endforeach
        </div>
        @endif

        @yield('content')
    </main>
</div>
@stack('scripts')
</body>
</html>
