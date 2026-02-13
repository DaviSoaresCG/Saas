<x-app-layout>
    <div class="container mx-auto max-w-7xl p-4 md:p-8 dark:text-white">
        
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Meu Carrinho</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-3">
                @foreach ($cart as $id => $item)

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 transition-colors duration-200">
                        <div class="product-item flex flex-col md:flex-row gap-6 items-center border-b border-gray-200 dark:border-gray-700 pb-6 last:border-0"
                            data-price="{{ $item['value'] }}" data-product-id="{{ $id }}">

                            <div class="w-32 h-32 md:w-28 md:h-28 flex-shrink-0">
                                <img class="w-full h-full object-cover rounded-lg shadow-md"
                                    src="{{ asset('storage/' . $item['path']) }}" alt="{{ $item['name'] }}">
                            </div>

                            <div class="flex-1 w-full md:w-auto">
                                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $item['name'] }}</h3>
                            </div>

                            <div class="flex items-center gap-3">
                                <button data-action="decrement" data-product-id="{{ $id }}"
                                    class="flex btn-update-cart bg-red-600 hover:bg-red-700 px-2 rounded text-white transition-colors">
                                    -
                                </button>

                                <span class="item-quantity text-lg font-semibold text-gray-800 dark:text-white"
                                    id="quantity-{{ $id }}"> {{ $item['quantity'] }} </span>

                                <button data-action="increment" data-product-id="{{ $id }}"
                                    class="flex btn-update-cart bg-blue-600 hover:bg-blue-700 px-2 rounded text-white transition-colors">
                                    +
                                </button>
                            </div>

                            <div class="flex flex-col items-end">
                                <p class="text-gray-600 dark:text-gray-300 font-bold">
                                    Valor Unitário: {{ number_format($item['value'], 2, ',', '.') }}
                                </p>
                                <span class="item-subtotal text-xl font-bold text-gray-900 dark:text-white mb-2"
                                    id="item-subtotal-{{ $id }}">
                                    R$ {{ number_format($item['value'] * $item['quantity'], 2, ',', '.') }}
                                </span>
                                <button data-action="remove" data-product-id="{{ $id }}"
                                    class="btn-remove-item text-red-500 hover:text-red-700 dark:hover:text-red-400 transition-colors text-sm font-medium flex items-center gap-1">
                                    Remover
                                </button>
                            </div>
                        </div>
                </div>
                @endforeach

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 lg:sticky lg:top-8 transition-colors duration-200">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">Resumo do Pedido</h2>

                    <div class="mt-6 pt-6">
                        <div class="flex justify-between text-gray-900 dark:text-white text-xl font-bold">
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
                            class="mt-8 w-full bg-blue-600 text-white text-lg font-semibold py-3 rounded-lg hover:bg-blue-700 transition-colors shadow-md">
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
</x-app-layout>