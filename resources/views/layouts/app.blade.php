<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TitipKata') — Tempat menitipkan cerita, pesan, dan perasaan.</title>
    <meta name="description" content="@yield('description', 'TitipKata — Tempat menitipkan cerita, pesan, dan perasaan.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        heading: ['Space Grotesk', 'sans-serif'],
                        body: ['Space Grotesk', 'sans-serif'],
                    },
                    colors: {
                        cream: '#FDF6E3',
                        'cream-light': '#FFFDF7',
                        primary: '#1A1A1A',
                        secondary: '#3A3A3A',
                        muted: '#6B6B6B',
                        coral: '#FF7A6B',
                        yellow: '#FFE066',
                        blue: '#A8D0F0',
                        pink: '#FFB5D8',
                        green: '#25D366',
                    }
                }
            }
        }
    </script>

    <style>
        * { font-family: 'Space Grotesk', sans-serif; box-sizing: border-box; }
        body { background-color: #FDF6E3; color: #1A1A1A; }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            font-weight: 700;
            font-size: 14px;
            border: 2px solid #1A1A1A;
            border-radius: 12px;
            box-shadow: 3px 3px 0px 0px #1A1A1A;
            cursor: pointer;
            transition: all 0.1s ease;
            text-decoration: none;
            background: #FFFDF7;
            color: #1A1A1A;
        }
        .btn:hover {
            transform: translate(-1px, -1px);
            box-shadow: 4px 4px 0px 0px #1A1A1A;
        }
        .btn:active {
            transform: translate(2px, 2px);
            box-shadow: 1px 1px 0px 0px #1A1A1A;
        }
        .btn-primary {
            background: #1A1A1A;
            color: #FDF6E3;
        }
        .btn-coral { background: #FF7A6B; }
        .btn-yellow { background: #FFE066; }
        .btn-green { background: #25D366; color: #fff; }
        .btn-blue { background: #A8D0F0; }
        .btn-pink { background: #FFB5D8; }
        .btn-sm { padding: 6px 14px; font-size: 12px; border-radius: 8px; }
        .btn-lg { padding: 14px 28px; font-size: 16px; border-radius: 14px; box-shadow: 4px 4px 0px 0px #1A1A1A; }

        .card {
            background: #FFFDF7;
            border: 2px solid #1A1A1A;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 4px 4px 0px 0px #1A1A1A;
        }
        .card-hero {
            box-shadow: 6px 6px 0px 0px #1A1A1A;
        }

        .input {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #1A1A1A;
            border-radius: 10px;
            background: #FFFDF7;
            font-size: 15px;
            font-family: 'Space Grotesk', sans-serif;
            outline: none;
            transition: box-shadow 0.15s;
        }
        .input:focus { box-shadow: 3px 3px 0px 0px #1A1A1A; }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 999px;
            border: 2px solid #1A1A1A;
            font-size: 12px;
            font-weight: 700;
        }

        .status-pending { background: #FFE066; }
        .status-approved { background: #25D366; color: white; }
        .status-rejected { background: #FF7A6B; }
        .status-posted { background: #A8D0F0; }

        .logo-text {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 800;
            letter-spacing: -1px;
        }

        textarea.input { resize: vertical; min-height: 150px; }

        @media (max-width: 640px) {
            .card { padding: 16px; }
        }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen">

    @yield('content')

    @stack('scripts')
</body>
</html>
