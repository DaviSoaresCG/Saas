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
                    <p class="display-6 text-success">Aguarde enquanto estamos processando sua inscrição!</p>
                    <p>Isso pode levar alguns segundos</p>
                    <div class="text-center">
                        
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        setInterval(function() {
            fetch("{{ route('api.subscription.status') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.subscribed) {
                        window.location.href = "{{ route('subscription.success') }}";
                    }
                });
        }, 3000); // verifica a cada 3 segundos
    </script>
</body>
</html>