<x-app-layout>
    <div class="flex flex-col items-center justify-center max-w-screen mx-auto text-center">
        <p class="text-bold font-2xl text-white bg-red-600 rounded p-2 mt-2">ATENCAO<p>
        <p class=" dark:text-white">Voce ira ser redirecionado para o Whatsapp do vendedor com uma mensagem pronta contendo as informações do pedido.<br>Por favor, envie a mensagem</p>
    <a href="{{ $url }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
        Finalizar Pedido no WhatsApp
    </a>
</div>
</x-app-layout>