<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

</head>

<body class="h-screen w-screen flex items-center justify-center :bg-slate-950 bg-gray-200  p-3">
    <main class="w-72 m-auto sm:w-96">
        <div class="text-center">
            <h1 class="font-bold text-2xl :text-white">Login</h1>
        </div>
        <br>
        <section class="space-y-4 bg-white :bg-gray-900 rounded-2xl w-full h-full shadow-lg px-8 py-10">
            <form action="{{ route('login') }}" method="post">
                @csrf
                <div class="flex flex-col">
                    <label for="email" class="ml-1 :text-white">Email</label>
                    <input type="text" name="email"
                        class=":text-white border-2 border-gray-500 rounded-2xl py-2 px-2 :bg-slate-800">
                    @error('email')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col">
                    <label for="senha" class="ml-1 :text-white">Senha</label>
                    <input type="password" name="password"
                        class="border-2 :text-white border-gray-500 rounded-2xl py-2 px-2 :bg-slate-800">
                    @error('password')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-2 flex-col">
                    <p>
                        <a href="{{route('register')}}"
                            class=":text-white :hover:text-blue-300 hover:text-blue-600 transition-all duration-100 ease-in-out">
                            Registrar
                        </a>
                        <a href="{{route('home')}}"
                            class=":text-white :hover:text-blue-300 hover:text-blue-600 transition-all duration-100 ease-in-out">
                            Página inicial
                        </a>
                    </p>
                    <button
                        class="cursor-pointer text-white bg-blue-500 w-full rounded-2xl px-4 py-2 hover:bg-blue-600 
                    transition-all duration-300 ease mt-2">Login
                    </button>
                </div>
            </form>
        </section>
    </main>

    <script>
        function claro() {
            document.documentElement.classList.remove('')
        }

        function escuro() {
            document.documentElement.classList.add('')
        }
    </script>
</body>

</html>
