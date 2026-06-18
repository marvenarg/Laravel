<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                    Bienvenido al sistema de inventario
                </h3>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Desde acá podés administrar tus productos y exportar el inventario.
                </p>
                <a href="{{ route('productos.index') }}"
                   class="inline-block mt-4 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                    Ir al inventario de productos
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total productos</p>
                    <h3 class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                        {{ auth()->user()->productos()->count() }}
                    </h3>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Stock bajo</p>
                    <h3 class="mt-2 text-3xl font-bold text-yellow-500">
                        {{ auth()->user()->productos()->where('stock', '<=', 5)->count() }}
                    </h3>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Valor del inventario</p>
                    <h3 class="mt-2 text-3xl font-bold text-emerald-500">
                        ${{ number_format(auth()->user()->productos()->sum(\Illuminate\Support\Facades\DB::raw('precio * stock')), 2, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>