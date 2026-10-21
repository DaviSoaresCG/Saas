<x-admin-layout active="layout" title="Disposição dos Cards de Produto (Mobile)" subtitle="Defina se seus produtos aparecem em 1 coluna (feed vertical) ou 2 colunas (grade compacta) em smartphones.">

    @if (session('success'))
        <div class="mb-6 rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-5 py-4 text-sm font-semibold text-emerald-400 flex items-center gap-3 shadow-lg shadow-emerald-500/5">
            <i data-lucide="check-circle" class="h-5 w-5 shrink-0 text-emerald-400"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-500/40 bg-red-500/10 px-5 py-4 text-sm font-semibold text-red-400 flex items-center gap-3 shadow-lg shadow-red-500/5">
            <i data-lucide="alert-circle" class="h-5 w-5 shrink-0 text-red-400"></i>
            <div>
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <div x-data="{
        selectedColunas: {{ (int) old('colunas_mobile', $colunas_mobile ?? 1) }},
        animating: false,
        setColunas(val) {
            if (this.selectedColunas === val) return;
            this.animating = true;
            setTimeout(() => {
                this.selectedColunas = val;
                this.animating = false;
            }, 150);
        }
    }" class="space-y-8">

        {{-- Top Header Banner --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-2 border-b border-[var(--color-primary)]/15">
            <div class="flex flex-col gap-1.5 max-w-2xl">
                <div class="flex items-center gap-3">
                    <span class="text-xl sm:text-2xl font-black text-[var(--text-base)] tracking-tight">Experiência Visual Mobile</span>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[var(--color-primary)]/10 text-[var(--color-primary)] text-xs font-bold border border-[var(--color-primary)]/20 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-[var(--color-primary)] animate-pulse"></span>
                        <span>Pré-visualização Interativa</span>
                    </div>
                </div>
                <p class="text-xs sm:text-sm text-[var(--text-muted)]">
                    Escolha a melhor apresentação para os clientes que acessam sua loja via smartphone. Alterne entre as opções abaixo para ver a transformação ao vivo no simulador.
                </p>
            </div>

            <div class="flex items-center gap-3 self-start lg:self-auto">
                <form action="{{ route('layout.update', ['slug' => auth()->user()->slug]) }}" method="POST" id="form-layout-mobile">
                    @csrf
                    <input type="hidden" name="colunas_mobile" :value="selectedColunas">
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl cursor-pointer bg-[var(--color-primary)] hover:opacity-90 px-6 py-3 text-sm font-bold text-[var(--text-on-primary)] shadow-xl shadow-[var(--color-primary)]/25 transition-all active:scale-95">
                        <i data-lucide="save" class="h-4 w-4"></i>
                        <span>Salvar Alterações</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Dual Column Workspace (Config Controls on Left, Device Sandbox on Right) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Left Panel: Configuration Core (7 cols) --}}
            <div class="lg:col-span-7 flex flex-col gap-6">

                {{-- Selector Options Card --}}
                <div class="rounded-3xl border border-[var(--color-primary)]/20 bg-[var(--bg-card)] p-6 sm:p-7 shadow-2xl shadow-black/5 flex flex-col gap-6">
                    <div class="flex items-center justify-between pb-2 border-b border-[var(--color-primary)]/10">
                        <div class="flex items-center gap-2.5">
                            <div class="p-2.5 rounded-xl bg-[var(--color-primary)]/10 text-[var(--color-primary)]">
                                <i data-lucide="layout-grid" class="h-5 w-5"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-[var(--text-base)]">Modo de Exibição dos Produtos</h2>
                                <p class="text-xs text-[var(--text-muted)]">Aplicado especificamente em telas de celulares</p>
                            </div>
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-[var(--color-primary)] px-2.5 py-1 bg-[var(--color-primary)]/10 rounded-lg">
                            Responsivo
                        </span>
                    </div>

                    {{-- Radio Cards Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Option 1: 1 Coluna Feed --}}
                        <div @click="setColunas(1)"
                            :class="selectedColunas === 1
                                ? 'border-[var(--color-primary)] bg-[var(--color-primary)]/10 shadow-lg shadow-[var(--color-primary)]/10 ring-2 ring-[var(--color-primary)]/30'
                                : 'border-gray-200 dark:border-white/10 bg-[var(--bg-card)]/50 hover:border-[var(--color-primary)]/40'"
                            class="cursor-pointer group relative p-5 rounded-2xl border-2 transition-all duration-200 flex flex-col justify-between gap-4 select-none">

                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    {{-- Wireframe Icon: 1 Coluna --}}
                                    <div class="w-11 h-11 rounded-xl bg-[var(--bg-card)] border border-[var(--color-primary)]/20 flex flex-col items-center justify-center gap-1.5 p-2 shadow-sm group-hover:scale-105 transition-transform">
                                        <div class="w-full h-3 rounded-md bg-[var(--color-primary)]"></div>
                                        <div class="w-full h-3 rounded-md bg-[var(--color-primary)]/40"></div>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-bold text-[var(--text-base)]">1 Coluna</h3>
                                        <span class="text-xs text-[var(--text-muted)]">Lista Ampla • Feed Reto</span>
                                    </div>
                                </div>

                                {{-- Radio Checkmark Indicator --}}
                                <div :class="selectedColunas === 1 ? 'bg-[var(--color-primary)] text-[var(--text-on-primary)]' : 'bg-gray-200 dark:bg-white/10 text-transparent'"
                                    class="w-5 h-5 rounded-full flex items-center justify-center transition-all shadow-sm">
                                    <i data-lucide="check" class="h-3 w-3"></i>
                                </div>
                            </div>

                            <p class="text-xs text-[var(--text-muted)] leading-relaxed">
                                Cards grandes e imersivos em linha única reta. Ideal para destacar fotos detalhadas, títulos descritivos e visualização ampla dos produtos.
                            </p>

                            <div class="pt-2 border-t border-[var(--color-primary)]/10">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[var(--color-primary)]/10 text-[var(--color-primary)] text-[11px] font-semibold">
                                    <i data-lucide="sparkles" class="h-3.5 w-3.5"></i>
                                    Ideal para Moda e Produtos de Destaque
                                </span>
                            </div>
                        </div>

                        {{-- Option 2: 2 Colunas Grid --}}
                        <div @click="setColunas(2)"
                            :class="selectedColunas === 2
                                ? 'border-[var(--color-primary)] bg-[var(--color-primary)]/10 shadow-lg shadow-[var(--color-primary)]/10 ring-2 ring-[var(--color-primary)]/30'
                                : 'border-gray-200 dark:border-white/10 bg-[var(--bg-card)]/50 hover:border-[var(--color-primary)]/40'"
                            class="cursor-pointer group relative p-5 rounded-2xl border-2 transition-all duration-200 flex flex-col justify-between gap-4 select-none">

                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    {{-- Wireframe Icon: 2 Colunas --}}
                                    <div class="w-11 h-11 rounded-xl bg-[var(--bg-card)] border border-[var(--color-primary)]/20 grid grid-cols-2 gap-1.5 p-2 shadow-sm group-hover:scale-105 transition-transform">
                                        <div class="w-full h-3 rounded-md bg-[var(--color-primary)]"></div>
                                        <div class="w-full h-3 rounded-md bg-[var(--color-primary)]"></div>
                                        <div class="w-full h-3 rounded-md bg-[var(--color-primary)]/40"></div>
                                        <div class="w-full h-3 rounded-md bg-[var(--color-primary)]/40"></div>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-bold text-[var(--text-base)]">2 Colunas</h3>
                                        <span class="text-xs text-[var(--text-muted)]">Grade Compacta • Grid Duplo</span>
                                    </div>
                                </div>

                                {{-- Radio Checkmark Indicator --}}
                                <div :class="selectedColunas === 2 ? 'bg-[var(--color-primary)] text-[var(--text-on-primary)]' : 'bg-gray-200 dark:bg-white/10 text-transparent'"
                                    class="w-5 h-5 rounded-full flex items-center justify-center transition-all shadow-sm">
                                    <i data-lucide="check" class="h-3 w-3"></i>
                                </div>
                            </div>

                            <p class="text-xs text-[var(--text-muted)] leading-relaxed">
                                Dois produtos por linha na mesma horizontal. Maximiza a quantidade de itens visíveis na tela inicial sem necessidade de muito scroll.
                            </p>

                            <div class="pt-2 border-t border-[var(--color-primary)]/10">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[var(--color-primary)]/10 text-[var(--color-primary)] text-[11px] font-semibold">
                                    <i data-lucide="zap" class="h-3.5 w-3.5"></i>
                                    Ideal para Catálogos Densos e Variedade
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- UX Insight Callout --}}
                <div class="rounded-2xl border border-[var(--color-primary)]/20 bg-[var(--bg-card)] p-5 shadow-sm flex items-start gap-3.5">
                    <div class="w-9 h-9 rounded-xl bg-[var(--color-primary)]/10 text-[var(--color-primary)] flex items-center justify-center shrink-0">
                        <i data-lucide="lightbulb" class="h-5 w-5"></i>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-xs font-bold text-[var(--text-base)] uppercase tracking-wider">Dica de Conversão</span>
                        <p class="text-xs text-[var(--text-muted)] leading-relaxed">
                            A disposição em <strong>2 Colunas</strong> permite aos visitantes escanearem um número maior de opções de produtos de uma só vez, estimulando compras rápidas. Já o layout de <strong>1 Coluna</strong> valoriza fotografias elaboradas e detalhes de itens com tíquete médio mais elevado.
                        </p>
                    </div>
                </div>

                {{-- Metrics Comparison Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="rounded-2xl border border-[var(--color-primary)]/15 bg-[var(--bg-card)] p-4 shadow-sm flex flex-col gap-1">
                        <span class="text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wider">Produtos por Dobra</span>
                        <span class="text-xl font-black text-[var(--color-primary)]" x-text="selectedColunas === 1 ? '2 a 3 produtos' : '4 a 6 produtos'"></span>
                        <span class="text-[11px] text-[var(--text-muted)]">sem rolagem de tela</span>
                    </div>

                    <div class="rounded-2xl border border-[var(--color-primary)]/15 bg-[var(--bg-card)] p-4 shadow-sm flex flex-col gap-1">
                        <span class="text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wider">Tamanho da Imagem</span>
                        <span class="text-xl font-black text-[var(--text-base)]" x-text="selectedColunas === 1 ? 'Feed Amplo' : 'Quadrado Compacto'"></span>
                        <span class="text-[11px] text-[var(--text-muted)]">otimizado para mobile</span>
                    </div>

                    <div class="rounded-2xl border border-[var(--color-primary)]/15 bg-[var(--bg-card)] p-4 shadow-sm flex flex-col gap-1">
                        <span class="text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wider">Experiência</span>
                        <span class="text-xl font-black text-emerald-500">Fluida</span>
                        <span class="text-[11px] text-[var(--text-muted)]">100% responsiva</span>
                    </div>
                </div>

            </div>

            {{-- Right Panel: Device Sandbox (5 cols) --}}
            <div class="lg:col-span-5 flex flex-col items-center justify-start sticky top-24">

                {{-- Viewport Switcher Toolbar --}}
                <div class="w-full max-w-[360px] mb-3 flex items-center justify-between px-3.5 py-2.5 bg-[var(--bg-card)] border border-[var(--color-primary)]/20 rounded-2xl shadow-sm">
                    <div class="flex items-center gap-2">
                        <i data-lucide="smartphone" class="h-4 w-4 text-[var(--color-primary)]"></i>
                        <span class="text-xs font-bold text-[var(--text-base)]">Visualização Mobile</span>
                    </div>

                    <div class="flex items-center gap-1 bg-[var(--bg-page)] p-1 rounded-xl border border-[var(--color-primary)]/10">
                        <button type="button" @click="setColunas(1)"
                            :class="selectedColunas === 1 ? 'bg-[var(--color-primary)] text-[var(--text-on-primary)] font-bold shadow-sm' : 'text-[var(--text-muted)] hover:text-[var(--text-base)]'"
                            class="px-2.5 py-1 rounded-lg text-xs transition-all flex items-center gap-1 cursor-pointer">
                            <i data-lucide="rows" class="h-3.5 w-3.5"></i>
                            <span>1 Col</span>
                        </button>
                        <button type="button" @click="setColunas(2)"
                            :class="selectedColunas === 2 ? 'bg-[var(--color-primary)] text-[var(--text-on-primary)] font-bold shadow-sm' : 'text-[var(--text-muted)] hover:text-[var(--text-base)]'"
                            class="px-2.5 py-1 rounded-lg text-xs transition-all flex items-center gap-1 cursor-pointer">
                            <i data-lucide="grid-2x2" class="h-3.5 w-3.5"></i>
                            <span>2 Col</span>
                        </button>
                    </div>
                </div>

                {{-- Realistic Smartphone Hardware Frame --}}
                <div class="relative w-[340px] sm:w-[360px] h-[720px] bg-neutral-900 dark:bg-black rounded-[48px] p-3 shadow-2xl ring-1 ring-white/20 flex flex-col overflow-hidden">

                    {{-- Inner Rim Highlight --}}
                    <div class="absolute inset-0 rounded-[48px] pointer-events-none ring-1 ring-inset ring-white/10"></div>

                    {{-- Phone Inner Screen Display --}}
                    <div class="w-full h-full bg-[var(--bg-page)] rounded-[38px] flex flex-col overflow-hidden relative shadow-inner text-[var(--text-base)]">

                        {{-- Dynamic Island & Sensor Bar --}}
                        <div class="w-full h-10 bg-[var(--bg-page)] flex items-center justify-between px-6 pt-1 select-none shrink-0 z-30">
                            <span class="text-[11px] font-bold text-[var(--text-base)]">9:41</span>
                            {{-- Dynamic Island Pill --}}
                            <div class="w-20 h-4 bg-black rounded-full flex items-center justify-center gap-1.5 shadow-sm">
                                <div class="w-2 h-2 rounded-full bg-neutral-800"></div>
                                <div class="w-1.5 h-1.5 rounded-full bg-blue-500/40"></div>
                            </div>
                            <div class="flex items-center gap-1.5 text-[var(--text-base)] text-[10px]">
                                <i data-lucide="wifi" class="h-3 w-3"></i>
                                <i data-lucide="battery" class="h-3 w-3"></i>
                            </div>
                        </div>

                        {{-- Mobile App Storefront Header --}}
                        <div class="px-4 py-2.5 bg-[var(--bg-card)] border-b border-[var(--color-primary)]/10 flex items-center justify-between shadow-xs shrink-0 z-20">
                            <div class="flex items-center gap-2">
                                <i data-lucide="menu" class="h-4 w-4 text-[var(--text-base)]"></i>
                                <span class="font-extrabold text-xs text-[var(--color-primary)] tracking-tight uppercase truncate max-w-[140px]">
                                    {{ auth()->user()->store_name ?? 'ZapCatálogo' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="search" class="h-3.5 w-3.5 text-[var(--text-muted)]"></i>
                                <div class="relative flex items-center justify-center">
                                    <i data-lucide="shopping-cart" class="h-3.5 w-3.5 text-[var(--color-primary)]"></i>
                                    <span class="absolute -top-1.5 -right-2 h-3.5 w-3.5 bg-[var(--color-primary)] text-[var(--text-on-primary)] rounded-full text-[8px] font-bold flex items-center justify-center">2</span>
                                </div>
                            </div>
                        </div>

                        {{-- Scrollable Canvas Inside Smartphone Screen --}}
                        <div class="flex-1 overflow-y-auto px-3.5 py-3 flex flex-col gap-3 select-none scrollbar-none">

                            {{-- Mini Promo Banner --}}
                            <div class="w-full rounded-xl bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-primary)]/80 p-2.5 text-[var(--text-on-primary)] flex items-center justify-between shadow-sm">
                                <div class="flex flex-col">
                                    <span class="text-[9px] uppercase tracking-wider font-semibold opacity-90">Novidades</span>
                                    <span class="text-xs font-bold leading-tight">Coleção Especial de Ofertas</span>
                                </div>
                                <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
                            </div>

                            {{-- Category Title --}}
                            <div class="flex items-center justify-between pt-0.5">
                                <span class="text-xs font-bold text-[var(--text-base)]">Mais Vendidos</span>
                                <span class="text-[10px] text-[var(--color-primary)] font-semibold flex items-center gap-0.5">
                                    Ver todos <i data-lucide="chevron-right" class="h-2.5 w-2.5"></i>
                                </span>
                            </div>

                            {{-- DYNAMIC PRODUCT CONTAINER (Animated transition between 1-col & 2-cols) --}}
                            <div :class="{
                                    'flex flex-col gap-3': selectedColunas === 1,
                                    'grid grid-cols-2 gap-2': selectedColunas === 2,
                                    'opacity-40 scale-[0.98] transition-all duration-150': animating,
                                    'opacity-100 scale-100 transition-all duration-200': !animating
                                }">

                                {{-- Product Card 1 --}}
                                <div class="rounded-2xl border border-[var(--color-primary)]/15 bg-[var(--bg-card)] overflow-hidden shadow-xs flex flex-col transition-all duration-300">
                                    <div :class="selectedColunas === 1 ? 'h-36' : 'h-24'"
                                        class="w-full bg-[var(--color-primary)]/10 flex items-center justify-center relative overflow-hidden transition-all duration-300">
                                        <i data-lucide="package" class="h-8 w-8 text-[var(--color-primary)]/60"></i>
                                    </div>
                                    <div class="p-2.5 flex flex-col justify-between flex-1 gap-1.5">
                                        <div>
                                            <h4 class="font-bold text-[var(--text-base)] leading-tight truncate"
                                                :class="selectedColunas === 1 ? 'text-xs' : 'text-[11px]'">
                                                Tênis Esportivo Runner
                                            </h4>
                                            <p x-show="selectedColunas === 1" class="text-[10px] text-[var(--text-muted)] line-clamp-1 mt-0.5">
                                                Amortecimento de alta resposta e leveza.
                                            </p>
                                        </div>
                                        <div class="pt-1">
                                            <span x-show="selectedColunas === 1" class="text-[9px] text-[var(--text-muted)] line-through block">R$ 299,00</span>
                                            <span class="font-extrabold text-[var(--color-primary)]"
                                                :class="selectedColunas === 1 ? 'text-xs' : 'text-[11px]'">
                                                R$ 239,90
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Product Card 2 --}}
                                <div class="rounded-2xl border border-[var(--color-primary)]/15 bg-[var(--bg-card)] overflow-hidden shadow-xs flex flex-col transition-all duration-300">
                                    <div :class="selectedColunas === 1 ? 'h-36' : 'h-24'"
                                        class="w-full bg-[var(--color-primary)]/10 flex items-center justify-center relative overflow-hidden transition-all duration-300">
                                        <i data-lucide="watch" class="h-8 w-8 text-[var(--color-primary)]/60"></i>
                                    </div>
                                    <div class="p-2.5 flex flex-col justify-between flex-1 gap-1.5">
                                        <div>
                                            <h4 class="font-bold text-[var(--text-base)] leading-tight truncate"
                                                :class="selectedColunas === 1 ? 'text-xs' : 'text-[11px]'">
                                                Smartwatch Pulse Pro
                                            </h4>
                                            <p x-show="selectedColunas === 1" class="text-[10px] text-[var(--text-muted)] line-clamp-1 mt-0.5">
                                                Monitoramento cardíaco e bateria 10 dias.
                                            </p>
                                        </div>
                                        <div class="pt-1">
                                            <span class="font-extrabold text-[var(--color-primary)]"
                                                :class="selectedColunas === 1 ? 'text-xs' : 'text-[11px]'">
                                                R$ 489,00
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Product Card 3 --}}
                                <div class="rounded-2xl border border-[var(--color-primary)]/15 bg-[var(--bg-card)] overflow-hidden shadow-xs flex flex-col transition-all duration-300">
                                    <div :class="selectedColunas === 1 ? 'h-36' : 'h-24'"
                                        class="w-full bg-[var(--color-primary)]/10 flex items-center justify-center relative overflow-hidden transition-all duration-300">
                                        <i data-lucide="headphones" class="h-8 w-8 text-[var(--color-primary)]/60"></i>
                                    </div>
                                    <div class="p-2.5 flex flex-col justify-between flex-1 gap-1.5">
                                        <div>
                                            <h4 class="font-bold text-[var(--text-base)] leading-tight truncate"
                                                :class="selectedColunas === 1 ? 'text-xs' : 'text-[11px]'">
                                                Headphone Wireless ANC
                                            </h4>
                                            <p x-show="selectedColunas === 1" class="text-[10px] text-[var(--text-muted)] line-clamp-1 mt-0.5">
                                                Cancelamento ativo de ruído estéreo.
                                            </p>
                                        </div>
                                        <div class="pt-1">
                                            <span class="font-extrabold text-[var(--color-primary)]"
                                                :class="selectedColunas === 1 ? 'text-xs' : 'text-[11px]'">
                                                R$ 349,00
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Product Card 4 --}}
                                <div class="rounded-2xl border border-[var(--color-primary)]/15 bg-[var(--bg-card)] overflow-hidden shadow-xs flex flex-col transition-all duration-300">
                                    <div :class="selectedColunas === 1 ? 'h-36' : 'h-24'"
                                        class="w-full bg-[var(--color-primary)]/10 flex items-center justify-center relative overflow-hidden transition-all duration-300">
                                        <i data-lucide="briefcase" class="h-8 w-8 text-[var(--color-primary)]/60"></i>
                                    </div>
                                    <div class="p-2.5 flex flex-col justify-between flex-1 gap-1.5">
                                        <div>
                                            <h4 class="font-bold text-[var(--text-base)] leading-tight truncate"
                                                :class="selectedColunas === 1 ? 'text-xs' : 'text-[11px]'">
                                                Mochila Urbana Impermeável
                                            </h4>
                                            <p x-show="selectedColunas === 1" class="text-[10px] text-[var(--text-muted)] line-clamp-1 mt-0.5">
                                                Compartimento acolchoado para notebook.
                                            </p>
                                        </div>
                                        <div class="pt-1">
                                            <span class="font-extrabold text-[var(--color-primary)]"
                                                :class="selectedColunas === 1 ? 'text-xs' : 'text-[11px]'">
                                                R$ 199,90
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- Footer End Marker --}}
                            <div class="pt-2 pb-4 text-center">
                                <span class="text-[9px] text-[var(--text-muted)] uppercase tracking-wider">Fim da lista de produtos</span>
                            </div>

                        </div>

                        {{-- Home Indicator Bar --}}
                        <div class="w-full h-4 flex items-center justify-center bg-[var(--bg-page)] pb-1 shrink-0">
                            <div class="w-28 h-1 bg-black/20 dark:bg-white/20 rounded-full"></div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

</x-admin-layout>
