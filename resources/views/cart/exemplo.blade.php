<x-app-layout>

    <div class="container mx-auto max-w-7xl p-4 md:p-8">
        <h1 class="text-3xl font-bold text-white mb-8">Meu Carrinho</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2">
                <div class="bg-gray-800 rounded-lg shadow-lg p-6 space-y-6">

                    <div class="product-item flex flex-col md:flex-row gap-6 items-center border-b pb-6"
                        data-price="18.50">

                        <div class="w-32 h-32 md:w-28 md:h-28 flex-shrink-0">
                            <img class="w-full h-full object-cover rounded-lg shadow-md"
                                src="https://readymadeui.com/images/sunscreen-img-1.webp" alt="Produto 1">
                        </div>

                        <div class="flex-1 w-full md:w-auto">
                            <h3 class="text-xl font-semibold text-white">Protetor Solar FPS 50</h3>
                        </div>

                        <div class="flex items-center gap-3">
                            <button data-action="decrement"
                                class="flex items-center justify-center w-8 h-8 bg-red-600 rounded-full text-white hover:bg-red-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                                    </path>
                                </svg>
                            </button>

                            <span class="item-quantity text-lg font-semibold text-white">1</span>

                            <button data-action="increment"
                                class="flex items-center justify-center w-8 h-8 bg-blue-600 rounded-full text-white hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="flex flex-col items-end">
                            <span class="item-subtotal text-xl font-bold text-white mb-2">$18.50</span>

                            <button data-action="remove"
                                class="text-red-500 hover:text-red-700 transition-colors text-sm font-medium flex items-center gap-1">
                                Remover
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-gray-800 rounded-lg shadow-lg p-6 lg:sticky lg:top-8">
                    <h2 class="text-2xl font-bold text-white border-b pb-4 mb-6">Resumo do Pedido</h2>

                    <div class="space-y-4">
                        <div class="flex justify-between text-white">
                            <span>Subtotal</span>
                            <span class="font-medium" id="summary-subtotal">$36.50</span>
                        </div>
                    </div>

                    <div class="border-t mt-6 pt-6">
                        <div class="flex justify-between text-white text-xl font-bold">
                            <span>Total</span>
                            <span id="summary-total">$42.50</span>
                        </div>
                    </div>
                    <form action="#" method="post">
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
        // Espera o documento carregar antes de rodar o script
        document.addEventListener("DOMContentLoaded", () => {

            // Seleciona a área que contém TODOS os produtos
            // Usamos isso para "Event Delegation" - é mais eficiente
            const cartContainer = document.querySelector(".lg\\:col-span-2");

            // Seleciona os campos do resumo que vamos atualizar
            const summarySubtotal = document.getElementById("summary-subtotal");
            const summaryTotal = document.getElementById("summary-total");

            // Taxas fixas (exemplo)
            const shippingFee = 4.00;
            const taxFee = 2.00;

            // --- O "CÉREBRO": A FUNÇÃO QUE RECALCULA TUDO ---
            function updateCartTotal() {
                // Pega TODOS os 'product-item' que estão no carrinho
                const productItems = document.querySelectorAll(".product-item");

                let subtotal = 0;

                // Loop por cada item do carrinho
                productItems.forEach(item => {
                    // 1. Pega os valores do item
                    const price = parseFloat(item.dataset.price); // Pega o 'data-price' (ex: 18.50)
                    const quantityElement = item.querySelector(
                        ".item-quantity"); // Pega o <span> da quantidade
                    const quantity = parseInt(quantityElement
                        .textContent); // Pega o texto (ex: "1") e transforma em número

                    // 2. Calcula o subtotal *deste item*
                    const itemSubtotal = price * quantity;

                    // 3. Atualiza o subtotal *deste item* na tela
                    const itemSubtotalElement = item.querySelector(".item-subtotal");
                    itemSubtotalElement.textContent = "$" + itemSubtotal.toFixed(2); // Formata para $0.00

                    // 4. Adiciona o subtotal deste item ao total geral
                    subtotal += itemSubtotal;
                });

                // --- Atualiza o Resumo do Pedido ---
                const total = subtotal;

                summarySubtotal.textContent = "$" + subtotal.toFixed(2);
                summaryTotal.textContent = "$" + total.toFixed(2);
            }

            // --- OS "GATILHOS": OUVINTE DE CLIQUES ---
            cartContainer.addEventListener("click", (event) => {
                // Acha o botão que foi clicado, mesmo que o clique tenha sido no ícone (SVG)
                const button = event.target.closest("button");

                // Se não clicou em um botão, não faz nada
                if (!button) return;

                // Pega a ação do botão (ex: 'increment', 'decrement', 'remove')
                const action = button.dataset.action;

                // Pega o 'product-item' pai deste botão
                const productItem = button.closest(".product-item");

                // Pega o <span> da quantidade deste item
                const quantityElement = productItem.querySelector(".item-quantity");
                let quantity = parseInt(quantityElement.textContent);

                // Executa a ação
                if (action === "increment") {
                    quantity++;
                    quantityElement.textContent = quantity;
                }

                if (action === "decrement") {
                    if (quantity > 1) { // Não deixa a quantidade ser menor que 1
                        quantity--;
                        quantityElement.textContent = quantity;
                    }
                }

                if (action === "remove") {
                    productItem.remove(); // Remove o item do HTML
                }

                // **A MÁGICA ACONTECE AQUI**
                // Depois de qualquer ação, recalcula o carrinho inteiro
                updateCartTotal();
            });

            // Roda a função uma vez no início para garantir que os totais estejam corretos
            updateCartTotal();
        });
    </script>
</x-app-layout>
