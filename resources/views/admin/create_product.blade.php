<x-app-layout>
    <nav class="text-sm text-gray-300 mb-4 ml-2">
        <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
        <span class="mx-2">/</span>
        <span class="text-white">Criar produto</span>
    </nav>
    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Add a new product</h2>
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <div class="sm:col-span-2">
                        <label for="name"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Type product name" required="">
                    </div>
                    <div class="w-full">
                        <label for="description" value="{{ old('description') }}"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descrição</label>
                        <input type="text" name="description" id="description" value="{{ old('description') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Descrição" required="">
                    </div>
                    <div class="w-full">
                        <x-input-label for="value" :value="__('Valor R$: 0,00')" />

                        <x-text-input id="value" class="block mt-2 w-full" type="tel" name="value" old="{{ old('value') }}"
                            :value="old('value')" required placeholder="R$: 0,00" autocomplete="value" x-data
                            x-mask:dynamic="$money($input, ',', '.')" />
                        <x-input-error :messages="$errors->get('value')" class="mt-2" />
                    </div>
                    <div class="w-full flex flex-col">
                        <x-input-label for="image" :value="__('Imagem do produto(JPG, JPEG, PNG...)')" />
                        <input type="file" accept="image/png, image/jpeg, image/webp, image/jpg" id="image"
                            name="image" class="rounded-lg p-2.5 w-full text-sm bg-gray-700 text-white">
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />

                    </div>
                </div>
                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800">
                    Adicionar produto
                </button>
            </form>
        </div>
    </section>
</x-app-layout>
