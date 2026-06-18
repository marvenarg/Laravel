<x-app-layout>
    @php
        $rolSobreInventario = auth()->id() === $producto->user_id
            ? 'owner'
            : \App\Models\InventarioPermiso::where('owner_user_id', $producto->user_id)
                ->where('invited_user_id', auth()->id())
                ->value('rol');
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    Detalle del producto
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Consultá la información completa del producto.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('productos.index') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                    Volver
                </a>

                @if(in_array($rolSobreInventario, ['owner', 'editor', 'admin']))
                    <a href="{{ route('productos.edit', $producto) }}"
                       class="inline-flex items-center px-4 py-2 rounded-lg bg-amber-500 text-white hover:bg-amber-600 transition">
                        Editar
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $stockClass = $producto->stock <= 0
                    ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200'
                    : ($producto->stock <= 5
                        ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200'
                        : 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200');
            @endphp

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow border border-gray-200 dark:border-gray-700">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $producto->nombre }}
                        </h3>

                        @if($producto->descripcion)
                            <p class="mt-3 text-gray-600 dark:text-gray-400">
                                {{ $producto->descripcion }}
                            </p>
                        @endif
                    </div>

                    <span class="px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap {{ $stockClass }}">
                        Stock: {{ $producto->stock }}
                    </span>
                </div>

                <div class="mt-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Precio</p>
                    <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                        ${{ number_format($producto->precio, 2, ',', '.') }}
                    </p>
                </div>

                <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4 text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <p>
                        <span class="font-medium">Propietario:</span>
                        {{ $producto->owner?->name ?? 'Sin registro' }}
                    </p>

                    <p>
                        <span class="font-medium">Última edición por:</span>
                        {{ $producto->updatedByUser?->name ?? 'Sin registro' }}
                    </p>
                </div>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('productos.index') }}"
                       class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                        Volver al listado
                    </a>

                    @if(in_array($rolSobreInventario, ['owner', 'editor', 'admin']))
                        <a href="{{ route('productos.edit', $producto) }}"
                           class="px-4 py-2 rounded-lg bg-amber-500 text-white hover:bg-amber-600 transition">
                            Editar producto
                        </a>
                    @endif

                    @if(in_array($rolSobreInventario, ['owner', 'admin']))
                        <form action="{{ route('productos.destroy', $producto) }}" method="POST"
                              onsubmit="return confirm('¿Seguro que querés eliminar este producto?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                                Eliminar
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>