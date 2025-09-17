<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Cashier</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
</head>

<body>
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-4 col-sm-8">

                <p class="display-6 text-center my-5">Laravel Cashier (Stripe)</p>

                <div class="card p-4 text-center">
                    <p class="display-6 text-success">Muito obrigado pela sua subscrição!</p>
                    <p>Já pode avançar para a dashboard!</p>
                    <div class="text-center">
                        <a href="{{ Route('dashboard', ['slug' => $slug]) }}" class="btn btn-primary px-5 mt-3">Dashboard</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>