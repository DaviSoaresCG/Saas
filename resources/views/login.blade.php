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

                <div class="text-center mb-3">
                    <a href="{{ route('login.submit', ['id' => 1]) }}" class="btn btn-lg px-5 btn-info">Login user 1</a>
                </div>

                <div class="text-center mb-3">
                    <a href="{{ route('login.submit', ['id' => 2]) }}" class="btn btn-lg px-5 btn-info">Login user 2</a>
                </div>

                <div class="text-center mb-3">
                    <a href=" {{route('login.submit', ['id' => 3]) }} " class="btn btn-lg px-5 btn-info">Login user 3</a>
                </div>

            </div>
        </div>
    </div>

</body>

</html>
