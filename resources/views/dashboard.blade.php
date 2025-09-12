<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Cashier</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
</head>

<body>

    <div class="d-flex justify-content-between p-3 bg-black">
        <div>
            <p class="text-warning">Laravel Cashier (Stripe)</p>
        </div>
        <div>
            <span>{{ Auth::user()->name }}</span>
            <span class="px-3">|</span>
            <a href="{{ Route('logout') }}">Logout</a>
        </div>
    </div>

    <div class="text-center mt-5">
        <p class="display-6">Dashboard!</p>

    </div>

    <hr>

    <div class="text-center">
        <p>Subsctiption termina em <strong>{{$subscription_end}}</strong></p>
    </div>

    <hr>

    @foreach($invoices as $invoice)
        <div class="text-center">
            <a href="{{ Route('invoice.download', ['id' => $invoice->id]) }}" 
                class="btn btn-primary">
                Gerar Nota Fiscal
            </a>
        </div>
    @endforeach

</body>

</html>
