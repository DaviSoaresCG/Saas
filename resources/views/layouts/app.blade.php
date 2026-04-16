<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme='sunset'>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @stack('styles')
    @if ($adminShell ?? false)
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    @endif

</head>
@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            dark: '#0f172a',
                            card: '#1e293b',
                            accent: '#2563eb',
                            accentHover: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
@endpush

@push('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    </script>
@endpush


<body class="font-sans antialiased bg-[var(--bg-page)]">
    @if ($adminShell ?? false)
        <div class="min-h-screen bg-slate-950 text-slate-100" x-data="{ adminMenuOpen: false }"
            @keydown.escape.window="adminMenuOpen = false"
            @resize.window="if (window.innerWidth >= 1024) adminMenuOpen = false">
            @include('layouts.partials.admin-topbar')
            <main>
                {{ $slot }}
            </main>
        </div>
    @else
        <div class="min-h-screen">
            @isset($header)
                <header class="bg-[var(--bg-page)] shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {{ $slot }}
            </main>
        </div>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    @stack('scripts')

</body>

</html>
