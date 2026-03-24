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

                <p class="display-6 text-center my-5">Laravel Cashier (Stripe) {{auth()->user()->id}}</p>

                <div class="card p-4 text-center">
                    <p class="display-6 text-success">Aguarde enquanto estamos processando sua inscrição!</p>
                    <p>Isso pode levar alguns segundos</p>
                    <div class="text-center">
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- Garanta que o Vite está carregando o JS. O Echo vive dentro do app.js --}}
    @vite(['resources/js/app.js'])

    <script>
        // 1. Passamos o ID do usuário logado para o JavaScript
        // Isso é necessário para saber QUAL canal privado escutar
        const currentUserId = {{ auth()->id() }};
    </script>

    <script type="module">
        // Espera o Echo carregar
        // "Echo" é configurado automaticamente no resources/js/bootstrap.js

        console.log('Iniciando escuta do WebSocket...');

        Echo.private(`App.Models.User.${currentUserId}`)
            .listen('InscricaoConfirmada', (e) => {

                console.log('⚡ Evento recebido via WebSocket!', e);

                // Feedback visual antes de redirecionar (opcional)
                alert('Pagamento confirmado! Redirecionando...');

                // Redireciona para a página de sucesso
                window.location.href = "{{ route('subscription.success') }}";
            });
    </script>
</body>

</html>
