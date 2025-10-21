@extends('layouts.app')
@section('content')
    <section>
        @if (session('success'))
            <div class="p-2 bg-green-600 text-white rounded">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        <h1>Para finalizar o registro, clique no link de verificação que enviamos para o seu email</h1>
        <h2>Não chegou o email? Verifique a caixa de span ou aguarde mais um pouco</h2>
        <form action="{{ route('verification.send') }}" method="post">
            @csrf
            <button type="submit">Enviar novamente</button>
        </form>
    </section>
@endsection
