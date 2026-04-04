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

<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-slate-900 text-slate-100 font-sans antialiased selection:bg-blue-600 selection:text-white">
        <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-blue-600/10 blur-[90px]"></div>
            <div class="absolute bottom-0 -left-24 h-72 w-72 rounded-full bg-indigo-600/10 blur-[90px]"></div>
        </div>

        <header class="sticky top-0 z-20 border-b border-slate-800 bg-slate-900/90 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600 shrink-0">
                        <i data-lucide="shopping-bag" class="h-5 w-5 text-white"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-blue-400 uppercase tracking-wide truncate">{{ $storeName }}</p>
                        @if ($pageTitle)
                            <p class="text-sm font-bold text-white truncate">{{ $pageTitle }}</p>
                        @else
                            <p class="text-sm font-bold text-white truncate">Catálogo</p>
                        @endif
                    </div>
                </div>
                <nav class="flex items-center gap-2 sm:gap-3 shrink-0">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">
                        <i data-lucide="layout-grid" class="h-4 w-4"></i>
                        <span class="hidden sm:inline">Dashboard</span>
                    </a>
                    <a href="{{ route('cart.index') }}"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 px-3 py-2 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition-colors">
                        <i data-lucide="shopping-cart" class="h-4 w-4"></i>
                        <span class="hidden sm:inline">Carrinho</span>
                    </a>
                </nav>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{ $slot }}
        </main>
    </div>
</x-app-layout>
