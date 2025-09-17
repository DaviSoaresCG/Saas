<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

</head>

<body class="h-screen w-screen flex items-center justify-center dark:bg-slate-950 bg-gray-200  p-3">
    <main class="w-72 m-auto sm:w-96">
        <div class="text-center">
            <h1 class="font-bold text-2xl dark:text-white">Login</h1>
        </div>
        <br>
        <section class="space-y-4 bg-white dark:bg-gray-900 rounded-2xl w-full h-full shadow-lg px-8 py-10">
            <form action="{{ route('login') }}" method="post">
                @csrf
                <div class="flex flex-col">
                    <label for="email" class="ml-1 dark:text-white">Email</label>
                    <input type="text" name="email"
                        class="dark:text-white border-2 border-gray-500 rounded-2xl py-2 px-2 dark:bg-slate-800">
                </div>
                <div class="flex flex-col">
                    <label for="senha" class="ml-1 dark:text-white">Senha</label>
                    <input type="text" name="password"
                        class="border-2 dark:text-white border-gray-500 rounded-2xl py-2 px-2 dark:bg-slate-800">
                </div>
                <div class="flex gap-2 flex-col">
                    <p>
                        <a href="#"
                            class="dark:text-white dark:hover:text-blue-300 hover:text-blue-600 transition-all duration-100 ease-in-out">
                            Esqueci minha senha
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
            document.documentElement.classList.remove('dark')
        }

        function escuro() {
            document.documentElement.classList.add('dark')
        }
    </script>
</body>

</html>
