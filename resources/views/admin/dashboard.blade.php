<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>




{{-- <x-app-layout>
    <div class="d-flex justify-content-between p-3 bg-black">
        <div>
            <p class="text-warning">Laravel Cashier (Stripe)</p>
        </div>
        <div>
            <span class="text-white">{{ Auth::user()->name }}</span>
            <span class="px-3">|</span>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <input type="submit" value="Logout" class="text-white p-2 bg-blue-600 rounded cursor-pointer">
            </form>
        </div>
    </div>

    <div class="text-center mt-5">
        <p class="display-6 text-white">Dashboard!</p>

    </div>

    <hr>

    <div class="text-center">
        <p class="text-white">Subsctiption termina em <strong>{{ $subscription_end }}</strong></p>
        <a href="{{ route('products.index', ['slug' => $user->slug]) }}"
            class="inline-block bg-gray-600 p-2 text-white rounded">Acessar página da loja</a>
        <a href="{{ route('products.create', ['slug' => $user->slug]) }}"
            class="inline-block bg-blue-700 p-2 text-white rounded">Criar um produto</a>
        <a href="{{ route('admin.products', ['slug' => $user->slug]) }}"
            class="bg-green-700 inline-block p-2 text-white rounded">Lista de Produtos</a>
        <a href="{{ route('pedidos.index', ['slug' => $user->slug]) }}"
            class="bg-red-700 inline-block p-2 text-white rounded">Lista de pedidos</a>
        <a href="{{ route('billing', ['slug' => $user->slug]) }}"
            class="bg-purple-700 inline-block p-2 text-white rounded">Opções de assinatura</a>
    </div>

    <hr>

    @foreach ($invoices as $invoice)
        <div class="text-center mb-2">
            @if ($invoice->status == 'open')
                <a href="{{ $invoice->hosted_invoice_url }}"
                    class="p-2 bg-yellow-500 rounded inline-block">
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
</x-app-layout> --}}