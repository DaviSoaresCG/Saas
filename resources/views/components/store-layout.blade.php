@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
   
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


<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-[var(--bg-page)] font-sans antialiased ">
        <header class="sticky top-0 z-20 bg-[var(--bg-page)] backdrop-blur-md">
            <div class="mx-auto h-16 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[var(--color-primary)] shrink-0">
                        <i data-lucide="shopping-bag" class="h-5 w-5 text-[var(--text-on-primary)]"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-[var(--text-base)] uppercase tracking-wide truncate">{{ $storeName }}</p>
                        @if ($pageTitle)
                            <p class="text-sm font-bold text-[var(--text-base)] truncate">{{ $pageTitle }}</p>
                        @else
                            <p class="text-sm font-bold text-[var(--text-base)] truncate">Catálogo</p>
                        @endif
                    </div>
                </div>
                @if(!Route::is('variant.*'))
                <nav class="flex items-center gap-2 sm:gap-3 shrink-0">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-[var(--text-on-primary)] bg-[var(--color-primary)] transition-colors shadow-lg shadow-[var(--color-primary)]/20">
                        <i data-lucide="layout-grid" class="h-4 w-4"></i>
                        <span class="hidden sm:inline">Dashboard</span>
                    </a>
                @endif
                    <a href="{{ Route::is('variant.*') ? route('variant.cart.index', ['slug' => $slug]) : route('cart.index') }}"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-[var(--color-primary)] px-3 py-2 text-sm text-[var(--text-on-primary)] shadow-lg shadow-[var(--color-primary)]/20 font-medium transition-colors">
                        <i data-lucide="shopping-cart" class="h-4 w-4"></i>
                        <span class="hidden sm:inline">Carrinho</span>
                    </a>
                </nav>
            </div>
        </header>
            
        {{ $slot }}
        
    </div>

    @if (!empty($modalCarrinho))
        <!-- Modal Adicionar Produto ao Carrinho -->
        <div id="add-to-cart-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 transition-all duration-300">
            <div class="relative w-full max-w-md bg-[var(--bg-card)] border-2 border-[var(--color-primary)]/40 rounded-2xl p-6 sm:p-8 shadow-2xl text-[var(--text-base)]">
                
                <button type="button" id="close-add-modal-btn" class="absolute top-4 right-4 text-[var(--text-muted)] hover:text-[var(--text-base)] transition-colors cursor-pointer">
                    <i data-lucide="x" class="h-6 w-6"></i>
                </button>

                <div class="mb-5 text-center">
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[var(--color-primary)]/10 text-[var(--color-primary)] mb-2.5">
                        <i data-lucide="shopping-bag" class="h-6 w-6"></i>
                    </div>
                    <h3 id="add-modal-product-name" class="text-xl font-bold text-[var(--text-base)]">Adicionar ao Carrinho</h3>
                    <p id="add-modal-product-price" class="text-base font-extrabold text-emerald-600 mt-1"></p>
                </div>

                <form id="add-to-cart-modal-form" action="" method="POST" class="space-y-4">
                    @csrf
                    <div id="add-modal-attributes-container" class="hidden"></div>

                    <div>
                        <label for="add-modal-quantity" class="block text-sm font-semibold text-[var(--text-base)] mb-1.5">
                            Quantidade <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <button type="button" id="btn-modal-qty-minus" class="flex h-11 w-11 cursor-pointer items-center justify-center rounded-xl bg-[var(--color-primary)]/15 hover:bg-[var(--color-primary)]/25 text-[var(--text-base)] font-bold text-lg transition-colors">-</button>
                            <input type="number" id="add-modal-quantity" name="quantity" value="1" min="1" required
                                class="w-full text-center px-4 py-2.5 rounded-xl bg-[var(--bg-card)] border border-[var(--color-primary)]/30 text-[var(--text-base)] font-bold text-lg focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)] transition-all">
                            <button type="button" id="btn-modal-qty-plus" class="flex h-11 w-11 cursor-pointer items-center justify-center rounded-xl bg-[var(--color-primary)]/15 hover:bg-[var(--color-primary)]/25 text-[var(--text-base)] font-bold text-lg transition-colors">+</button>
                        </div>
                    </div>

                    <div>
                        <label for="add-modal-observacao" class="block text-sm font-semibold text-[var(--text-base)] mb-1.5">
                            Observação / Variação <span class="text-xs font-normal text-[var(--text-muted)]">(Opcional)</span>
                        </label>
                        <textarea id="add-modal-observacao" name="observacao" rows="3"
                            placeholder="Ex: Tamanho G, sem cebola, cor preta..."
                            class="w-full px-4 py-3 rounded-xl bg-[var(--bg-card)] border border-[var(--color-primary)]/30 text-[var(--text-base)] placeholder-[var(--text-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)] transition-all text-sm"></textarea>
                    </div>

                    <div class="pt-2 flex flex-col gap-2.5">
                        <button type="submit"
                            class="w-full cursor-pointer bg-[var(--color-primary)] hover:opacity-90 text-[var(--text-on-primary)] font-bold py-3.5 rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 text-base">
                            <i data-lucide="shopping-cart" class="h-5 w-5"></i>
                            <span>Confirmar e Adicionar</span>
                        </button>
                        <button type="button" id="cancel-add-modal-btn"
                            class="w-full cursor-pointer bg-transparent hover:bg-gray-500/10 text-[var(--text-muted)] font-semibold py-2 rounded-xl transition-all text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const addToCartModal = document.getElementById('add-to-cart-modal');
                const closeAddModalBtn = document.getElementById('close-add-modal-btn');
                const cancelAddModalBtn = document.getElementById('cancel-add-modal-btn');
                const modalForm = document.getElementById('add-to-cart-modal-form');
                const modalProdName = document.getElementById('add-modal-product-name');
                const modalProdPrice = document.getElementById('add-modal-product-price');
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

                function hideModal() {
                    if (addToCartModal) addToCartModal.classList.add('hidden');
                }

                if (closeAddModalBtn) closeAddModalBtn.addEventListener('click', hideModal);
                if (cancelAddModalBtn) cancelAddModalBtn.addEventListener('click', hideModal);
                if (addToCartModal) {
                    addToCartModal.addEventListener('click', function(e) {
                        if (e.target === addToCartModal) hideModal();
                    });
                }

                document.querySelectorAll('form[action*="/cart/add/"]').forEach(function(form) {
                    form.addEventListener('submit', function(e) {
                        if (form === modalForm) {
                            return;
                        }
                        e.preventDefault();

                        const actionUrl = form.getAttribute('action');
                        const card = form.closest('.group') || form.closest('.p-6') || form.parentElement;
                        const productName = card ? (card.querySelector('h1, h3, .font-bold')?.innerText || 'Produto') : 'Produto';
                        const productPrice = card ? (card.querySelector('.tabular-nums, .text-emerald-600')?.innerText || '') : '';

                        modalForm.setAttribute('action', actionUrl);
                        if (modalProdName) modalProdName.innerText = productName;
                        if (modalProdPrice) modalProdPrice.innerText = productPrice;
                        if (modalQtyInput) modalQtyInput.value = '1';
                        document.getElementById('add-modal-observacao').value = '';

                        if (addToCartModal) addToCartModal.classList.remove('hidden');
                    });
                });
            });
        </script>
    @endif
</x-app-layout>
