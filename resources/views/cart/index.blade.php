<x-store-layout page-title='Carrinho'>
    <div class="mb-6 sm:mb-8 flex flex-wrap items-center gap-3">
        <button type="button"
            onclick="(function(){try{var r=document.referrer;if(r&&new URL(r).hostname===location.hostname){history.back();return;}}catch(e){} window.location.href='{{ tenant_route('products.index') }}';})();"
            class="inline-flex cursor-pointer items-center gap-2 rounded-full border border-[var(--text-base)]/15 bg-[var(--bg-card)] px-4 py-2 text-sm font-medium text-[var(--text-base)] shadow-sm transition-all hover:bg-[var(--text-base)]/5 active:scale-95">
            <i data-lucide="arrow-left" class="h-4 w-4 stroke-[2]"></i>
            <span>Voltar às compras</span>
        </button>
    </div>

    <div class="container mx-auto">
        <div class="mb-8 sm:mb-10 flex flex-col gap-1">
            <h1 class="font-serif text-3xl sm:text-4xl text-[var(--text-base)] tracking-tight">Meu Carrinho</h1>
            <p class="text-sm sm:text-base text-[var(--text-muted)]">Revise os produtos selecionados antes de concluir seu pedido</p>
        </div>

        @if (empty($cart))
            <div class="flex flex-col items-center justify-center rounded-3xl border border-[var(--text-base)]/10 bg-[var(--bg-card)] p-8 sm:p-14 text-center shadow-sm">
                <div class="mb-5 flex h-20 w-20 items-center justify-center rounded-2xl bg-[var(--text-base)]/5 text-[var(--text-base)] ring-1 ring-[var(--text-base)]/10">
                    <i data-lucide="shopping-bag" class="h-10 w-10 stroke-[1.5]"></i>
                </div>
                <h2 class="font-serif text-2xl sm:text-3xl text-[var(--text-base)] tracking-tight">Seu carrinho está vazio</h2>
                <p class="mt-2 max-w-md text-sm sm:text-base text-[var(--text-muted)]">
                    Você ainda não adicionou nenhum produto ao seu carrinho. Explore nosso catálogo e encontre itens exclusivos para você.
                </p>
                <div class="mt-8">
                    <a href="{{ tenant_route('products.index') }}"
                        class="inline-flex items-center gap-2.5 rounded-full bg-[var(--color-primary)] px-6 py-3 text-sm sm:text-base font-medium text-[var(--text-on-primary)] shadow-sm transition-all hover:opacity-95 active:scale-[0.98]">
                        <i data-lucide="compass" class="h-4 w-4 stroke-[2]"></i>
                        <span>Explorar produtos</span>
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-4">
                @foreach ($cart as $id => $item)
                    <div class="product-card rounded-2xl border border-[var(--text-base)]/10 bg-[var(--bg-card)] p-4 sm:p-5 shadow-sm transition-all duration-200 hover:border-[var(--text-base)]/20">
                        <div class="product-item flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-5"
                            data-price="{{ $item['value'] }}" data-product-id="{{ $id }}">

                            <!-- Imagem do Produto -->
                            <div class="relative h-24 w-24 sm:h-28 sm:w-28 flex-shrink-0 overflow-hidden rounded-xl border border-[var(--text-base)]/10 bg-[var(--bg-page)]">
                                @if (!empty($item['path']))
                                    <img class="h-full w-full object-cover transition-transform duration-300 hover:scale-105"
                                        src="{{ str_starts_with($item['path'], 'http') ? $item['path'] : asset('storage/' . $item['path']) }}"
                                        alt="{{ $item['name'] }}">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-[var(--text-muted)] bg-[var(--text-base)]/5">
                                        <i data-lucide="package" class="h-8 w-8 stroke-[1.5]"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Dados do Produto: Título, Atributos e Observações -->
                            <div class="flex-1 min-w-0 w-full sm:w-auto">
                                <h3 class="text-base sm:text-lg font-semibold text-[var(--text-base)] leading-snug">
                                    {{ $item['name'] }}
                                </h3>

                                @if (!empty($item['atributos']))
                                    <div class="mt-2 flex flex-wrap gap-1.5">
                                        @foreach ($item['atributos'] as $atributo)
                                            <span class="inline-flex items-center rounded-full border border-[var(--text-base)]/10 bg-[var(--text-base)]/5 px-2.5 py-0.5 text-xs font-medium text-[var(--text-muted)]">
                                                {{ $atributo }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                @if (!empty($item['observacao']))
                                    <div class="mt-2">
                                        <span class="inline-flex items-center gap-1 rounded-full border border-[var(--text-base)]/10 bg-[var(--text-base)]/5 px-2.5 py-0.5 text-xs font-medium text-[var(--text-muted)]">
                                            <span class="font-semibold text-[var(--text-base)]">Obs:</span>
                                            <span>{{ $item['observacao'] }}</span>
                                        </span>
                                    </div>
                                @endif

                                @if (!session('sob_consulta'))
                                    <p class="mt-1.5 text-xs sm:text-sm text-[var(--text-muted)] font-normal">
                                        Unitário: <span class="font-serif font-medium text-[var(--text-base)] text-sm sm:text-base">R$ {{ number_format($item['value'], 2, ',', '.') }}</span>
                                    </p>
                                @endif
                            </div>

                            <!-- Controles Táteis de Quantidade, Subtotal e Remoção -->
                            <div class="flex w-full sm:w-auto items-center justify-between sm:justify-end gap-3 sm:gap-5 pt-3 sm:pt-0 border-t sm:border-t-0 border-[var(--text-base)]/10">
                                <!-- Seletores Táteis de Quantidade -->
                                <div class="inline-flex items-center gap-1.5 rounded-2xl border border-[var(--text-base)]/10 bg-[var(--bg-page)] p-1 shadow-2xs">
                                    <button type="button" data-action="decrement" data-product-id="{{ $id }}"
                                        aria-label="Diminuir quantidade"
                                        class="btn-update-cart flex h-8 w-8 items-center justify-center rounded-xl border border-[var(--text-base)]/15 bg-[var(--bg-card)] text-sm font-semibold text-[var(--text-base)] shadow-2xs transition-all hover:bg-[var(--text-base)]/5 active:scale-95 cursor-pointer">
                                        <i data-lucide="minus" class="h-3.5 w-3.5 stroke-[2] pointer-events-none"></i>
                                    </button>

                                    <span class="item-quantity min-w-[2rem] text-center text-sm sm:text-base font-semibold text-[var(--text-base)]"
                                        id="quantity-{{ $id }}">{{ $item['quantity'] }}</span>

                                    <button type="button" data-action="increment" data-product-id="{{ $id }}"
                                        aria-label="Aumentar quantidade"
                                        class="btn-update-cart flex h-8 w-8 items-center justify-center rounded-xl border border-[var(--text-base)]/15 bg-[var(--bg-card)] text-sm font-semibold text-[var(--text-base)] shadow-2xs transition-all hover:bg-[var(--text-base)]/5 active:scale-95 cursor-pointer">
                                        <i data-lucide="plus" class="h-3.5 w-3.5 stroke-[2] pointer-events-none"></i>
                                    </button>
                                </div>

                                <!-- Subtotal ou Badge Sob Consulta -->
                                <div class="flex flex-col items-end min-w-[5.5rem]">
                                    @if (session('sob_consulta'))
                                        <span class="inline-flex items-center rounded-full border border-amber-500/20 bg-amber-500/10 px-2.5 py-0.5 text-xs font-semibold text-amber-600 dark:text-amber-400">
                                            Sob Consulta
                                        </span>
                                    @else
                                        <span class="item-subtotal font-serif text-lg sm:text-xl font-medium text-[var(--text-base)] tracking-tight"
                                            id="item-subtotal-{{ $id }}">
                                            R$ {{ number_format($item['value'] * $item['quantity'], 2, ',', '.') }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Botão de Exclusão -->
                                <button type="button" data-action="remove" data-product-id="{{ $id }}"
                                    title="Remover item"
                                    aria-label="Remover item do carrinho"
                                    class="btn-remove-item flex h-9 w-9 items-center justify-center rounded-xl border border-[var(--text-base)]/10 text-[var(--text-muted)] transition-all hover:border-red-500/30 hover:bg-red-500/10 hover:text-red-500 active:scale-95 cursor-pointer">
                                    <i data-lucide="trash-2" class="h-4 w-4 stroke-[1.75] pointer-events-none"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="lg:col-span-1">
                <div class="rounded-3xl border border-[var(--text-base)]/10 bg-[var(--bg-card)] p-6 sm:p-7 shadow-sm lg:sticky lg:top-8 transition-all">
                    <h2 class="font-serif text-2xl sm:text-3xl text-[var(--text-base)] tracking-tight border-b border-[var(--text-base)]/10 pb-4">
                        Resumo do Pedido
                    </h2>

                    <div class="mt-6 pt-2">
                        <div class="flex items-baseline justify-between text-[var(--text-base)]">
                            <span class="text-base text-[var(--text-muted)] font-medium">Total</span>
                            <span id="summary-total" class="font-serif text-2xl sm:text-3xl font-medium text-[var(--text-base)] tracking-tight">
                                @if (session('sob_consulta'))
                                    <span class="inline-flex items-center rounded-full border border-amber-500/20 bg-amber-500/10 px-3 py-1 text-sm font-semibold text-amber-600 dark:text-amber-400">
                                        Sob Consulta
                                    </span>
                                @else
                                    R$ {{ number_format($total, 2, ',', '.') }}
                                @endif
                            </span>
                        </div>
                    </div>

                    <button type="button" id="open-checkout-modal-btn"
                        class="mt-6 sm:mt-8 w-full inline-flex items-center justify-center gap-2 rounded-full bg-[var(--color-primary)] text-[var(--text-on-primary)] px-6 py-3.5 text-base font-semibold shadow-sm transition-all hover:opacity-95 active:scale-[0.98] cursor-pointer">
                        <span>Finalizar pedido</span>
                        <i data-lucide="arrow-right" class="h-4 w-4 stroke-[2] pointer-events-none"></i>
                    </button>
                </div>
            </div>

        </div>
        @endif
    </div>

    <!-- Modal de Finalização / Dados do Cliente -->
    <div id="checkout-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-md p-4 transition-all duration-300">
        <div class="relative w-full max-w-md rounded-3xl border border-[var(--text-base)]/10 bg-[var(--bg-card)] p-6 sm:p-8 shadow-2xl text-[var(--text-base)]">
            
            <button type="button" id="close-modal-btn"
                aria-label="Fechar modal"
                class="absolute top-5 right-5 flex h-9 w-9 items-center justify-center rounded-full text-[var(--text-muted)] transition-colors hover:bg-[var(--text-base)]/5 hover:text-[var(--text-base)] cursor-pointer">
                <i data-lucide="x" class="h-5 w-5 stroke-[2] pointer-events-none"></i>
            </button>

            <div class="mb-6 text-center">
                <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--text-base)]/5 text-[var(--text-base)] ring-1 ring-[var(--text-base)]/10 mb-3">
                    <i data-lucide="user-check" class="h-7 w-7 stroke-[1.75] pointer-events-none"></i>
                </div>
                <h3 class="font-serif text-2xl sm:text-3xl font-medium text-[var(--text-base)] tracking-tight">Identificação do Cliente</h3>
                <p class="text-sm text-[var(--text-muted)] mt-1 font-normal">Preencha seus dados para concluirmos o seu pedido.</p>
            </div>

            <form id="checkout-form" action="{{ tenant_route('order.finished') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="cliente_nome" class="block text-xs sm:text-sm font-semibold text-[var(--text-base)] mb-1.5">
                        Nome completo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="cliente_nome" name="cliente_nome" required
                        placeholder="Digite seu nome completo"
                        class="w-full px-4 py-3 rounded-xl border border-[var(--text-base)]/15 bg-[var(--bg-page)] text-[var(--text-base)] placeholder-[var(--text-muted)]/70 focus:outline-none focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary)]/15 transition-all text-sm sm:text-base">
                </div>

                <div>
                    <label for="cliente_phone" class="block text-xs sm:text-sm font-semibold text-[var(--text-base)] mb-1.5">
                        Celular / WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" id="cliente_phone" name="cliente_phone" required
                        placeholder="(00) 90000-0000"
                        class="w-full px-4 py-3 rounded-xl border border-[var(--text-base)]/15 bg-[var(--bg-page)] text-[var(--text-base)] placeholder-[var(--text-muted)]/70 focus:outline-none focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary)]/15 transition-all text-sm sm:text-base" x-data x-mask="(99) 99999-9999">
                </div>

                <div class="pt-2 flex flex-col gap-2.5">
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-full bg-[var(--color-primary)] py-3.5 px-6 font-semibold text-[var(--text-on-primary)] shadow-sm transition-all hover:opacity-95 active:scale-[0.98] cursor-pointer text-sm sm:text-base">
                        <span>Salvar e Enviar Pedido</span>
                        <i data-lucide="arrow-right" class="h-4 w-4 stroke-[2] pointer-events-none"></i>
                    </button>
                    <button type="button" id="cancel-modal-btn"
                        class="w-full inline-flex items-center justify-center rounded-full bg-transparent py-2.5 px-6 font-medium text-[var(--text-muted)] transition-all hover:bg-[var(--text-base)]/5 hover:text-[var(--text-base)] cursor-pointer text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const checkoutModal = document.getElementById('checkout-modal');
            const openModalBtn = document.getElementById('open-checkout-modal-btn');
            const closeModalBtn = document.getElementById('close-modal-btn');
            const cancelModalBtn = document.getElementById('cancel-modal-btn');

            if (openModalBtn && checkoutModal) {
                openModalBtn.addEventListener('click', function() {
                    checkoutModal.classList.remove('hidden');
                });

                if (closeModalBtn) {
                    closeModalBtn.addEventListener('click', function() {
                        checkoutModal.classList.add('hidden');
                    });
                }

                if (cancelModalBtn) {
                    cancelModalBtn.addEventListener('click', function() {
                        checkoutModal.classList.add('hidden');
                    });
                }

                checkoutModal.addEventListener('click', function(e) {
                    if (e.target === checkoutModal) {
                        checkoutModal.classList.add('hidden');
                    }
                });
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            document.querySelectorAll('.btn-update-cart').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.dataset.productId;
                    const action = this.dataset.action;
                    const quantityElement = document.getElementById('quantity-' + productId);
                    let currentQuantity = parseInt(quantityElement.innerText);

                    if (action === 'increment') {
                        currentQuantity++;
                    } else if (action === 'decrement') {
                        currentQuantity--;
                        if (currentQuantity < 1) {
                            currentQuantity = 1;
                        }
                    }

                    quantityElement.innerText = currentQuantity;
                    updateCartOnServer(productId, currentQuantity);
                });
            });

            document.querySelectorAll('.btn-remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.dataset.productId;
                    updateCartOnServer(productId, 0);
                });
            });

            async function updateCartOnServer(productId, quantity) {
                const itemElement = document.querySelector(`.product-item[data-product-id="${productId}"]`);

                try {
                    const response = await fetch("{{ tenant_route('cart.update') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: quantity
                        })
                    });

                    if (!response.ok) throw new Error('Erro na resposta do servidor.');
                    const data = await response.json();

                    if (data.success) {
                        const count = typeof data.cartCounter !== 'undefined' ? (parseInt(data.cartCounter, 10) || 0) : null;
                        const isSobConsulta = {{ session('sob_consulta') ? 'true' : 'false' }};
                        if (!isSobConsulta) {
                            const summaryTotalEl = document.getElementById('summary-total');
                            if (summaryTotalEl) summaryTotalEl.innerText = 'R$ ' + data.new_total;
                        }

                        const cartBadge = document.getElementById('cart-badge');
                        if (cartBadge && count !== null) {
                            if (count > 0) {
                                cartBadge.innerText = count > 99 ? '99+' : count;
                                cartBadge.classList.remove('hidden');
                            } else {
                                cartBadge.innerText = '0';
                                cartBadge.classList.add('hidden');
                            }
                        }

                        if (itemElement) {
                            if (quantity <= 0) {
                                const cardElement = itemElement.closest('.product-card') || itemElement.parentElement;
                                cardElement.style.transition = 'all 0.3s ease-out';
                                cardElement.style.opacity = '0';
                                cardElement.style.transform = 'scale(0.96)';
                                setTimeout(() => {
                                    cardElement.remove();
                                    if (count === 0) {
                                        location.reload();
                                    }
                                }, 300);
                            } else {
                                const itemSubtotalEl = document.getElementById('item-subtotal-' + productId);
                                if (itemSubtotalEl && !isSobConsulta) itemSubtotalEl.innerText = 'R$ ' + data.item_subtotal;
                                const quantityEl = document.getElementById('quantity-' + productId);
                                if (quantityEl) quantityEl.innerText = data.quantity;
                            }
                        }
                    } else {
                        location.reload();
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        });
    </script>
</x-store-layout>