<x-store-layout page-title='Carrinho'>
    <div class="mb-6 flex flex-wrap items-center gap-2">
        <button type="button"
            onclick="(function(){try{var r=document.referrer;if(r&&new URL(r).hostname===location.hostname){history.back();return;}}catch(e){} window.location.href='{{ route('products.index') }}';})();"
            class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-[var(--color-primary)] px-3 py-2 text-sm font-semibold text-[var(--text-on-primary)] transition-colors">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Voltar
        </button>
    </div>
    <div class="container mx-auto dark:text-white">
        
        
        <h1 class="text-3xl font-bold text-[var(--text-base)] mb-8">Meu Carrinho</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-3">
                @foreach ($cart as $id => $item)

                <div class="bg-[var(--bg-card)] border-2 border-[var(--color-primary)]/40 rounded-2xl shadow-lg p-6 transition-colors duration-200">
                        <div class="product-item flex flex-col md:flex-row gap-6 items-center  pb-6 "
                            data-price="{{ $item['value'] }}" data-product-id="{{ $id }}">

                            <div class="w-32 h-32 md:w-28 md:h-28 flex-shrink-0">
                                <img class="w-full h-full object-cover rounded-lg shadow-md"
                                    src="{{ asset('storage/' . $item['path']) }}" alt="{{ $item['name'] }}">
                            </div>

                            <div class="flex-1 w-full md:w-auto">
                                <h3 class="text-xl font-semibold text-[var(--text-base)]">{{ $item['name'] }}</h3>
                            </div>

                            <div class="flex items-center gap-3">
                                <button data-action="decrement" data-product-id="{{ $id }}"
                                    class="flex cursor-pointer btn-update-cart bg-red-600 hover:bg-red-700 px-2 rounded font-bold text-white transition-colors">
                                    -
                                </button>

                                <span class="item-quantity text-lg font-semibold text-[var(--text-base)]"
                                    id="quantity-{{ $id }}"> {{ $item['quantity'] }} </span>

                                <button data-action="increment" data-product-id="{{ $id }}"
                                    class="flex cursor-pointer font-bold btn-update-cart bg-blue-600 hover:bg-blue-700 px-2 rounded text-white transition-colors">
                                    +
                                </button>
                            </div>

                            <div class="flex flex-col items-end">
                                <p class="text-[var(--text-base)] font-bold">
                                    Valor Unitário: {{ number_format($item['value'], 2, ',', '.') }}
                                </p>
                                <span class="item-subtotal text-xl font-bold text-[var(--text-base)] mb-2"
                                    id="item-subtotal-{{ $id }}">
                                    R$ {{ number_format($item['value'] * $item['quantity'], 2, ',', '.') }}
                                </span>
                                <button data-action="remove" data-product-id="{{ $id }}"
                                    class="btn-remove-item p-1 rounded cursor-pointer text-white bg-red-600 transition-colors text-sm font-medium flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                      </svg>
                                      
                                </button>
                            </div>
                        </div>
                </div>
                @endforeach

            </div>

            <div class="lg:col-span-1">
                <div class="bg-[var(--bg-card)] border-2 border-[var(--color-primary)]/40 rounded-lg shadow-lg p-6 lg:sticky lg:top-8 transition-colors duration-200">
                    <h2 class="text-2xl font-bold text-[var(--text-base)] border-b border-[var(--color-primary)] pb-4 mb-6">Resumo do Pedido</h2>

                    <div class="mt-6 pt-6">
                        <div class="flex justify-between text-[var(--text-base)] text-xl font-bold">
                            <span>Total</span>
                            <span id="summary-total">
                                R$ {{ number_format($total, 2, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    <form action="{{ route('order.finished') }}" method="get">
                        @csrf
                        <input type="number" hidden id="summary-total" value="">
                        <button
                            class="mt-8 w-full cursor-pointer  bg-[var(--color-primary)] text-[var(--text-on-primary)] text-lg font-semibold py-3 rounded-lg shadow-md">
                            Finalizar pedido
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {

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
                    const response = await fetch("{{ route('cart.update') }}", {
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
                        document.getElementById('summary-total').innerText = 'R$ ' + data.new_total;

                        if (itemElement) {
                            if (quantity <= 0) {
                                itemElement.parentElement.style.transition = 'opacity 0.3s ease-out';
                                itemElement.parentElement.style.opacity = '0';
                                setTimeout(() => itemElement.parentElement.remove(), 300);
                            } else {
                                const itemSubtotalEl = document.getElementById('item-subtotal-' + productId);
                                if (itemSubtotalEl) itemSubtotalEl.innerText = 'R$ ' + data.item_subtotal;
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