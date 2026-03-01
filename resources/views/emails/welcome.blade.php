<h1>Ola {{$user->name}}!</h1>
<p>Obrigado por escolher a gente! Já está tudo certo, você pode acessar a dashboard clicando no link abaixo</p>
<p>Ao acessar o seu dashboard, terá que logar com a sua conta</p>

<a href="{{ route('dashboard', ['slug' => $user->slug]) }}" class="p-2 bg-blue-600 text-white">Dashboard</a>