<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    Sua assinatura termina em <strong>{{ $subscription_end }}</strong>
                    <div class="text-left space-y-2">
                        <a href="#"
                            class="inline-block bg-gray-600 p-2 text-white rounded">Personalizar Loja</a>
                        <a href="{{ route('products.create') }}"
                            class="inline-block bg-blue-700 p-2 text-white rounded">Criar um produto</a>
                        <a href="{{ route('billing') }}"
                            class="dark:bg-white bg-yellow-500  inline-block dark:text-gray-900 p-2 rounded">Opções de assinatura</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($invoices as $invoice)
        <div class="text-center mb-2">
            <p class="text-2xl font-bold dark:text-white">Faturas</p>
            @if ($invoice->status == 'open')
                <a href="{{ $invoice->hosted_invoice_url }}" class="p-2 bg-yellow-500 rounded inline-block">
                    Fatura Aberta {{ $invoice->date()->format('d/m/y') }} - {{ $invoice->total() }}
                </a>
            @endif
            @if ($invoice->status == 'paid')
                <a href="{{ Route('invoice.download', ['id' => $invoice->id]) }}"
                    class="p-2 bg-green-600 text-white rounded inline-block">
                    Fatura Paga - {{ $invoice->date()->format('d/m/y') }} - {{ $invoice->total() }}
                </a>
            @endif
        </div>
    @endforeach
</x-app-layout>


