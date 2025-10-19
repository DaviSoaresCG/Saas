<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Bem vindo {{$user->name}}</h1>
    <p>Ao acessar o seu dashboard, terá que logar com a sua conta</p>
    <a href="{{ route('dashboard', ['slug' => $user->slug]) }}">Clique aqui para acessar seu dashboard</a>
</body>
</html>