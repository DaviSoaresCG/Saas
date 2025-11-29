<x-app-layout>

    <div class="container mx-auto max-w-7xl p-4 md:p-8">
        <h1 class="text-3xl font-bold text-white mb-8">Meu Carrinho</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-3">
                @foreach ($cart as $id => $item)

                <div class="bg-gray-800 rounded-lg shadow-lg p-6">

                    {{-- 
  MUDANÇA 1: O loop deve ser @foreach ($cart as $id => $item) 
  para funcionar com a estrutura de sessão que recomendamos.
--}}
                        {{-- 
      MUDANÇA 2: Adicionado 'data-product-id' ao 'div' principal 
      para podermos remover o item inteiro do DOM.
    --}}
                        <div class="product-item flex flex-col md:flex-row gap-6 items-center border-b pb-6"
                            data-price="{{ $item['value'] }}" data-product-id="{{ $id }}">

                            <div class="w-32 h-32 md:w-28 md:h-28 flex-shrink-0">
                                {{-- MUDANÇA 3: Acessando como array ($item['path']) --}}
                                <img class="w-full h-full object-cover rounded-lg shadow-md"
                                    src="{{ asset('storage/' . $item['path']) }}" alt="{{ $item['name'] }}">
                            </div>

                            <div class="flex-1 w-full md:w-auto">
                                <h3 class="text-xl font-semibold text-white">{{ $item['name'] }}</h3>
                            </div>

                            <div class="flex items-center gap-3">
                                {{-- MUDANÇA 4: O 'data-product-id' agora é '$id' --}}
                                <button data-action="decrement" data-product-id="{{ $id }}"
                                    class="flex btn-update-cart bg-red-600 hover:bg-red-700 px-2 rounded text-white">
                                    -
                                </button>

                                <span class="item-quantity text-lg font-semibold text-white"
                                    id="quantity-{{ $id }}"> {{ $item['quantity'] }} </span>

                                <button data-action="increment" data-product-id="{{ $id }}"
                                    class="flex btn-update-cart bg-blue-600 hover:bg-blue-700 px-2 rounded text-white">
                                    +
                                </button>
                            </div>

                            <div class="flex flex-col items-end">
                                {{-- 
              MUDANÇA 5: O subtotal deve ser calculado dinamicamente
              e ter um 'id' para o JS poder atualizá-lo.
                                
            --}}    
                                <p class="text-white font-bold">
                                    Valor Unitário: {{ number_format($item['value'], 2, ',', '.') }}
                                </p>
                                <span class="item-subtotal text-xl font-bold text-white mb-2"
                                    id="item-subtotal-{{ $id }}">
                                    R$ {{ number_format($item['value'] * $item['quantity'], 2, ',', '.') }}
                                </span>

                                {{-- 
              MUDANÇA 6: Adicionado 'data-product-id' e uma classe 'btn-remove-item'
              para o botão de remover.
            --}}
                                <button data-action="remove" data-product-id="{{ $id }}"
                                    class="btn-remove-item text-red-500 hover:text-red-700 transition-colors text-sm font-medium flex items-center gap-1">
                                    Remover
                                </button>
                            </div>
                        </div>
                </div>
                @endforeach

            </div>

            <div class="lg:col-span-1">
                <div class="bg-gray-800 rounded-lg shadow-lg p-6 lg:sticky lg:top-8">
                    <h2 class="text-2xl font-bold text-white border-b pb-4 mb-6">Resumo do Pedido</h2>

                    <div class="mt-6 pt-6">
                        <div class="flex justify-between text-white text-xl font-bold">
                            <span>Total</span>
                            <span id="summary-total">
                                R$ {{ number_format($total, 2, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    <form action="{{ route('pedido.finalizar', ['slug' => $slug]) }}" method="get">
                        @csrf
                        <input type="number" hidden id="summary-total" value="">
                        <button
                            class="mt-8 w-full bg-blue-600 text-white text-lg font-semibold py-3 rounded-lg hover:bg-blue-700 transition-colors">
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
            // Listener para botões +/- (O que você já tinha)
            document.querySelectorAll('.btn-update-cart').forEach(button => {
                button.addEventListener('click', function() {
                    console.log("deu certo");
                    const productId = this.dataset.productId;
                    const action = this.dataset.action;
                    const quantityElement = document.getElementById('quantity-' + productId);
                    let currentQuantity = parseInt(quantityElement.innerText);

                    if (action === 'increment') {
                        currentQuantity++;
                    } else if (action === 'decrement') {
                        currentQuantity--;
                        // Não deixa a quantidade ser menor que 1 (use "remover" para isso)
                        if (currentQuantity < 1) {
                            currentQuantity = 1;
                            // Ou, se quiser que 0 remova:
                            // if (currentQuantity < 0) currentQuantity = 0;
                        }
                    }

                    // Atualiza visualmente ANTES de enviar (Otimista)
                    // Se der erro, a resposta do AJAX deve reverter isso
                    quantityElement.innerText = currentQuantity;

                    updateCartOnServer(productId, currentQuantity);
                });
            });

            // NOVO: Listener para botões "Remover"
            document.querySelectorAll('.btn-remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.dataset.productId;

                    // Enviar quantidade 0 para o servidor significa "remover"
                    updateCartOnServer(productId, 0);
                });
            });

            /**
             * Função que envia os dados para o servidor via AJAX (fetch)
             */
            async function updateCartOnServer(productId, quantity) {

                // Pega o elemento principal do item
                const itemElement = document.querySelector(`.product-item[data-product-id="${productId}"]`);

                try {
                    const response = await fetch("{{ route('cart.update', ['slug' => $slug]) }}", {
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

                    if (!response.ok) {
                        throw new Error('Erro na resposta do servidor.');
                    }

                    const data = await response.json();

                    if (data.success) {
                        console.log(data.message);

                        // --- ATUALIZAÇÕES DO DOM ---

                        // 1. Atualiza o Resumo do Pedido (Subtotal e Total Geral)
                        document.getElementById('summary-total').innerText = 'R$ ' + data.new_total;

                        if (itemElement) {
                            if (quantity <= 0) {
                                // 2a. Remove o item do DOM com um "fade out"
                                itemElement.style.transition = 'opacity 0.3s ease-out';
                                itemElement.style.opacity = '0';
                                setTimeout(() => itemElement.remove(), 300);
                            } else {
                                // 2b. Atualiza o subtotal do item específico
                                const itemSubtotalEl = document.getElementById('item-subtotal-' + productId);
                                if (itemSubtotalEl) {
                                    itemSubtotalEl.innerText = 'R$ ' + data.item_subtotal;
                                }
                                // Confirma a quantidade (caso a lógica do servidor mude, ex: estoque)
                                const quantityEl = document.getElementById('quantity-' + productId);
                                if (quantityEl) {
                                    quantityEl.innerText = data.quantity;
                                }
                            }
                        }

                    } else {
                        console.error('Falha ao atualizar o carrinho (resposta do servidor).');
                        // Aqui você reverteria a mudança visual, ex: recarregando a página
                        location.reload();
                    }

                } catch (error) {
                    console.error(error);
                    // Reverte a mudança visual em caso de erro de rede
                }
            }
        });
    </script>
</x-app-layout>
