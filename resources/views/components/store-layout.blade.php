@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .font-serif {
            font-family: 'Instrument Serif', Georgia, serif;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    </script>

@endpush
@props(['slug'])


<x-app-layout :title="$tabTitle ?? $title ?? null" :logo-url="$storeLogo ?? $logoUrl ?? null">
    <div class="min-h-screen bg-[var(--bg-page)] font-sans antialiased flex flex-col">
        <header class="sticky top-0 z-20 w-full bg-[var(--bg-page)]/90 backdrop-blur-xl border-b border-[var(--text-base)]/10 transition-all">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-3.5 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    @php
                        $displayLogo = $storeLogo ?? $logoUrl ?? null;
                    @endphp
                    @if (!empty($displayLogo))
                        <div class="flex h-16 w-16 sm:h-18 sm:w-18 items-center justify-center rounded-2xl overflow-hidden shrink-0 border border-[var(--text-base)]/10 bg-[var(--bg-card)] shadow-sm">
                            <img src="{{ $displayLogo }}" alt="{{ $storeName }}" class="h-full w-full object-cover">
                        </div>
                    @else
                        <div class="flex h-11 w-11 sm:h-12 sm:w-12 items-center justify-center rounded-2xl bg-[var(--color-primary)] text-[var(--text-on-primary)] shrink-0 shadow-sm">
                            <i data-lucide="shopping-bag" class="h-5 w-5 sm:h-6 sm:w-6"></i>
                        </div>
                    @endif
                    <div class="min-w-0 flex flex-col justify-center">
                        <p class="font-serif text-lg sm:text-2xl font-normal text-[var(--text-base)] tracking-tight truncate leading-tight">
                            {{ $storeName }}
                        </p>
                        <p class="text-xs sm:text-sm font-medium text-[var(--text-muted)] tracking-wide truncate">
                            {{ $pageTitle ?: 'Catálogo' }}
                        </p>
                    </div>
                </div>
                <nav class="flex items-center gap-2 sm:gap-3 shrink-0">
                    @if(!Route::is('variant.*'))
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center gap-1.5 rounded-full border border-[var(--text-base)]/15 bg-[var(--bg-card)] px-3.5 py-2 text-xs sm:text-sm font-medium text-[var(--text-base)] hover:bg-[var(--text-base)]/5 shadow-sm transition-all duration-200">
                            <i data-lucide="layout-dashboard" class="h-4 w-4"></i>
                            <span class="hidden sm:inline">Dashboard</span>
                        </a>
                    @endif
                    <a href="{{ Route::is('variant.*') ? route('variant.cart.index', ['slug' => $slug]) : route('cart.index') }}"
                        class="inline-flex items-center gap-2 rounded-full bg-[var(--color-primary)] px-4 py-2 text-xs sm:text-sm font-semibold text-[var(--text-on-primary)] shadow-sm hover:opacity-95 active:scale-[0.98] transition-all duration-200">
                        <i data-lucide="shopping-cart" class="h-4 w-4"></i>
                        <span class="hidden sm:inline font-medium">Carrinho</span>
                        <span id="cart-badge"
                            class="{{ ($cartCount ?? 0) > 0 ? '' : 'hidden ' }}inline-flex h-5 min-w-[20px] items-center justify-center rounded-full bg-red-600 px-1.5 text-[10px] font-bold text-white shadow-sm leading-none">
                            {{ ($cartCount ?? 0) > 99 ? '99+' : ($cartCount ?? 0) }}
                        </span>
                    </a>
                </nav>
            </div>
            @if(!empty($catalogoName) && ($mostrarNome ?? true))
                <div class="w-full border-t border-[var(--text-base)]/10 bg-[var(--bg-card)]/50 backdrop-blur-md py-2 px-4 text-center">
                    <span class="inline-flex items-center gap-2 rounded-full border border-[var(--text-base)]/10 bg-[var(--bg-card)] px-3.5 py-1 text-xs sm:text-sm font-medium text-[var(--text-base)] shadow-sm">
                        <i data-lucide="tag" class="h-3.5 w-3.5 text-[var(--text-muted)]"></i>
                        <span>{{ $catalogoName }}</span>
                    </span>
                </div>
            @endif
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 w-full flex-1">
            {{ $slot }}
        </main>
    </div>

    @if (!empty($modalCarrinho))
        <!-- Modal Adicionar Produto ao Carrinho -->
        <div id="add-to-cart-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-md p-4 opacity-0 transition-opacity duration-300 ease-out">
            <div id="add-to-cart-modal-content" class="relative w-full max-w-md bg-[var(--bg-card)] border border-[var(--text-base)]/10 rounded-3xl p-6 sm:p-8 shadow-2xl text-[var(--text-base)] transform scale-95 opacity-0 transition-all duration-300 ease-out">

                <button type="button" id="close-add-modal-btn" class="absolute top-4 right-4 flex h-9 w-9 items-center justify-center rounded-full text-[var(--text-muted)] hover:text-[var(--text-base)] hover:bg-[var(--text-base)]/5 transition-colors cursor-pointer" aria-label="Fechar modal">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>

                <div class="mb-5 text-center">
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[var(--color-primary)]/10 text-[var(--color-primary)] mb-3">
                        <i data-lucide="shopping-bag" class="h-6 w-6"></i>
                    </div>
                    <h3 id="add-modal-product-name" class="text-lg sm:text-xl font-bold text-[var(--text-base)] tracking-tight">Adicionar ao Carrinho</h3>
                    <p id="add-modal-product-price" class="mt-1 font-serif text-2xl sm:text-3xl font-normal text-[var(--text-base)]"></p>

                    <div id="add-modal-erp-info" class="hidden my-3 flex items-center justify-center gap-2 text-xs text-[var(--text-muted)]">
                        <span id="add-modal-product-sku" class="rounded-lg bg-[var(--text-base)]/5 px-2.5 py-1 font-mono text-[11px]"></span>
                        <span id="add-modal-product-peso" class="rounded-lg bg-[var(--text-base)]/5 px-2.5 py-1 text-[11px]"></span>
                    </div>
                </div>

                <form id="add-to-cart-modal-form" action="" method="POST" class="space-y-4">
                    @csrf
                    <div id="add-modal-attributes-container" class="hidden"></div>

                    <div>
                        <label for="add-modal-quantity" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                            Quantidade <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <button type="button" id="btn-modal-qty-minus" class="flex h-11 w-11 cursor-pointer items-center justify-center rounded-xl border border-[var(--text-base)]/15 bg-[var(--bg-card)] hover:bg-[var(--text-base)]/5 active:scale-95 text-[var(--text-base)] font-bold text-lg transition-all shadow-sm">-</button>
                            <input type="number" id="add-modal-quantity" name="quantity" value="1" min="1" required
                                class="w-full text-center px-4 py-2.5 rounded-xl bg-[var(--bg-card)] border border-[var(--text-base)]/15 text-[var(--text-base)] font-bold text-lg focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/15 focus:border-[var(--color-primary)] transition-all">
                            <button type="button" id="btn-modal-qty-plus" class="flex h-11 w-11 cursor-pointer items-center justify-center rounded-xl border border-[var(--text-base)]/15 bg-[var(--bg-card)] hover:bg-[var(--text-base)]/5 active:scale-95 text-[var(--text-base)] font-bold text-lg transition-all shadow-sm">+</button>
                        </div>
                    </div>

                    <div>
                        <label for="add-modal-observacao" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                            Observação / Variação <span class="text-[11px] font-normal lowercase text-[var(--text-muted)]/80">(opcional)</span>
                        </label>
                        <textarea id="add-modal-observacao" name="observacao" rows="3"
                            placeholder="Ex: Tamanho G, sem cebola, cor preta..."
                            class="w-full px-4 py-3 rounded-2xl bg-[var(--bg-card)] border border-[var(--text-base)]/15 text-[var(--text-base)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/15 focus:border-[var(--color-primary)] transition-all text-sm resize-none"></textarea>
                    </div>

                    <div class="pt-2 flex flex-col gap-2.5">
                        <button type="submit"
                            class="w-full cursor-pointer bg-[var(--color-primary)] hover:opacity-95 active:scale-[0.98] text-[var(--text-on-primary)] font-semibold py-3.5 rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 text-sm sm:text-base">
                            <i data-lucide="shopping-cart" class="h-4 w-4 sm:h-5 sm:w-5"></i>
                            <span>Confirmar e Adicionar</span>
                        </button>
                        <button type="button" id="cancel-add-modal-btn"
                            class="w-full cursor-pointer rounded-xl border border-transparent py-2.5 text-center text-xs sm:text-sm font-medium text-[var(--text-muted)] hover:text-[var(--text-base)] hover:bg-[var(--text-base)]/5 transition-all">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const addToCartModal = document.getElementById('add-to-cart-modal');
                const modalContent = document.getElementById('add-to-cart-modal-content');
                const closeAddModalBtn = document.getElementById('close-add-modal-btn');
                const cancelAddModalBtn = document.getElementById('cancel-add-modal-btn');
                const modalForm = document.getElementById('add-to-cart-modal-form');
                const modalProdName = document.getElementById('add-modal-product-name');
                const modalProdPrice = document.getElementById('add-modal-product-price');
                const modalProdSku = document.getElementById('add-modal-product-sku');
                const modalProdPeso = document.getElementById('add-modal-product-peso');
                const modalErpInfo = document.getElementById('add-modal-erp-info');
                const modalQtyInput = document.getElementById('add-modal-quantity');
                const btnMinus = document.getElementById('btn-modal-qty-minus');
                const btnPlus = document.getElementById('btn-modal-qty-plus');
                const attrContainer = document.getElementById('add-modal-attributes-container');

                if (btnMinus && modalQtyInput) {
                    btnMinus.addEventListener('click', function() {
                        let q = parseInt(modalQtyInput.value) || 1;
                        if (q > 1) modalQtyInput.value = q - 1;
                    });
                }
                if (btnPlus && modalQtyInput) {
                    btnPlus.addEventListener('click', function() {
                        let q = parseInt(modalQtyInput.value) || 1;
                        modalQtyInput.value = q + 1;
                    });
                }

                function showModal() {
                    if (!addToCartModal) return;
                    addToCartModal.classList.remove('hidden');
                    addToCartModal.classList.add('flex');
                    requestAnimationFrame(() => {
                        requestAnimationFrame(() => {
                            addToCartModal.classList.remove('opacity-0');
                            addToCartModal.classList.add('opacity-100');
                            if (modalContent) {
                                modalContent.classList.remove('scale-95', 'opacity-0');
                                modalContent.classList.add('scale-100', 'opacity-100');
                            }
                        });
                    });
                }

                function hideModal() {
                    if (!addToCartModal) return;
                    addToCartModal.classList.remove('opacity-100');
                    addToCartModal.classList.add('opacity-0');
                    if (modalContent) {
                        modalContent.classList.remove('scale-100', 'opacity-100');
                        modalContent.classList.add('scale-95', 'opacity-0');
                    }
                    setTimeout(() => {
                        addToCartModal.classList.remove('flex');
                        addToCartModal.classList.add('hidden');
                    }, 300);
                }

                if (closeAddModalBtn) closeAddModalBtn.addEventListener('click', hideModal);
                if (cancelAddModalBtn) cancelAddModalBtn.addEventListener('click', hideModal);
                if (addToCartModal) {
                    addToCartModal.addEventListener('click', function(e) {
                        if (e.target === addToCartModal) hideModal();
                    });
                }

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && addToCartModal && !addToCartModal.classList.contains('hidden')) {
                        hideModal();
                    }
                });

                document.querySelectorAll('form[action*="/cart/add/"]').forEach(function(form) {
                    form.addEventListener('submit', function(e) {
                        if (form === modalForm) {
                            return;
                        }
                        e.preventDefault();

                        const actionUrl = form.getAttribute('action');
                        const card = form.closest('.group') || form.closest('.p-6') || form.parentElement;
                        const productName = card ? (card.querySelector('h1, h3, .font-bold')?.innerText || 'Produto') : 'Produto';
                        const productPrice = card ? (card.querySelector('.tabular-nums, .text-emerald-600, .text-amber-500')?.innerText || '') : '';
                        const sku = form.dataset.sku || card?.dataset?.sku || '';
                        const peso = form.dataset.peso || card?.dataset?.peso || '';

                        modalForm.setAttribute('action', actionUrl);
                        if (modalProdName) modalProdName.innerText = productName;
                        if (modalProdPrice) {
                            modalProdPrice.innerText = productPrice;
                            if (productPrice.includes('Sob Consulta')) {
                                modalProdPrice.className = 'mt-2 inline-flex items-center rounded-full bg-[var(--color-primary)]/10 px-3.5 py-1 text-xs font-semibold text-[var(--color-primary)] border border-[var(--color-primary)]/20';
                            } else {
                                modalProdPrice.className = 'mt-1 font-serif text-2xl sm:text-3xl font-normal text-[var(--text-base)]';
                            }
                        }

                        if (modalErpInfo) {
                            if (sku || peso) {
                                if (modalProdSku) modalProdSku.innerText = sku ? `SKU: ${sku}` : '';
                                if (modalProdPeso) modalProdPeso.innerText = peso ? `Peso: ${peso}` : '';
                                modalErpInfo.classList.remove('hidden');
                            } else {
                                modalErpInfo.classList.add('hidden');
                            }
                        }

                        if (modalQtyInput) modalQtyInput.value = '1';
                        document.getElementById('add-modal-observacao').value = '';

                        showModal();
                    });
                });
            });
        </script>
    @endif
</x-app-layout>
