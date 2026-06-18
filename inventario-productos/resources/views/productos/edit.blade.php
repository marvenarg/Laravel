<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
            Editar producto
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('productos.update', $producto) }}" method="POST"
                  class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">
                @csrf
                @method('PUT')

                <div class="mb-5">
                    <label class="block mb-2 text-sm font-medium text-gray-800 dark:text-gray-200">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                    @error('nombre')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-sm font-medium text-gray-800 dark:text-gray-200">Descripción</label>
                    <textarea name="descripcion" rows="4"
                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">{{ old('descripcion', $producto->descripcion) }}</textarea>
                    @error('descripcion')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800 dark:text-gray-200">Precio</label>
                        <input type="number" step="0.01" name="precio" value="{{ old('precio', $producto->precio) }}"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                        @error('precio')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800 dark:text-gray-200">Stock</label>
                        <input type="number" name="stock" value="{{ old('stock', $producto->stock) }}"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                        @error('stock')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-6">
                    <button type="submit"
                            class="px-5 py-2.5 rounded-lg bg-amber-500 text-white font-medium hover:bg-amber-600 transition">
                        Actualizar
                    </button>

                    <a href="{{ route('productos.index') }}"
                       class="px-5 py-2.5 rounded-lg bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>