<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="clean">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-[var(--text-base)] antialiased">
    <main class="min-h-screen p-2 w-full flex flex-col items-center justify-center bg-[var(--bg-page)]">
        <div
            class="max-w-2xl w-full sm:mx-auto mx-2 px-4 sm:px-6 lg:px-8 py-8 bg-[var(--bg-card)] border-1 border-[var(--color-primary)] shadow-2xl overflow-hidden rounded-lg">
            {{ $slot }}
        </div>
    </main>
</body>

</html>