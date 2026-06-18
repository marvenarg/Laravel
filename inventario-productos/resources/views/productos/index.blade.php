<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    Inventario de productos
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Gestioná tus productos, stock y exportaciones.
                </p>

                @if(request('owner') && request('owner') !== 'todos')
                    @php
                        $inventarioSeleccionado = $inventarios->firstWhere('id', (int) request('owner'));
                    @endphp

                    @if($inventarioSeleccionado)
                        <p class="mt-2 text-sm text-blue-600 dark:text-blue-400">
                            Mostrando: {{ $inventarioSeleccionado->id === auth()->id() ? 'Mi inventario' : 'Inventario de ' . $inventarioSeleccionado->name }}
                        </p>
                    @endif
                @elseif(request('owner', 'todos') === 'todos')
                    <p class="mt-2 text-sm text-blue-600 dark:text-blue-400">
                        Mostrando: Todos los inventarios accesibles
                    </p>
                @endif
            </div>

            <a href="{{ route('productos.create') }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                Nuevo producto
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('mensaje'))
                <div class="mb-5 p-4 rounded-xl bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100">
                    {{ session('mensaje') }}
                </div>
            @endif

            <div class="mb-6 bg-white dark:bg-gray-800 rounded-2xl shadow p-4">
                <form method="GET" action="{{ route('productos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="owner" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Inventario
                        </label>
                        <select
                            name="owner"
                            id="owner"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option value="todos" {{ request('owner', 'todos') === 'todos' ? 'selected' : '' }}>
                                Todos los inventarios accesibles
                            </option>

                            @foreach($inventarios as $inventario)
                                <option value="{{ $inventario->id }}" {{ (string) request('owner') === (string) $inventario->id ? 'selected' : '' }}>
                                    {{ $inventario->id === auth()->id() ? 'Mi inventario' : 'Inventario de ' . $inventario->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="buscar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Buscar por nombre
                        </label>
                        <input
                            type="text"
                            name="buscar"
                            id="buscar"
                            value="{{ request('buscar') }}"
                            placeholder="Ej: Mouse Logitech"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >
                    </div>

                    <div>
                        <label for="orden" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Ordenar por
                        </label>
                        <select
                            name="orden"
                            id="orden"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option value="recientes" {{ request('orden', 'recientes') == 'recientes' ? 'selected' : '' }}>Más recientes</option>
                            <option value="antiguos" {{ request('orden') == 'antiguos' ? 'selected' : '' }}>Más antiguos</option>
                            <option value="nombre_asc" {{ request('orden') == 'nombre_asc' ? 'selected' : '' }}>Nombre A-Z</option>
                            <option value="nombre_desc" {{ request('orden') == 'nombre_desc' ? 'selected' : '' }}>Nombre Z-A</option>
                        </select>
                    </div>
                    <!--
                    <div class="md:col-span-4 flex flex-wrap gap-3">
                        <button
                            type="submit"
                            class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                            Aplicar
                        </button>

                        <a href="{{ route('productos.index') }}"
                            class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                            Limpiar
                        </a>
                    </div>
                    -->                    
                    <div class="md:col-span-4 flex flex-wrap items-end gap-3">
                        <button type="submit"
                                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                                Aplicar
                        </button>

                        <a href="{{ route('productos.index') }}"
                                class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-gray-300 text-gray-800 hover:bg-gray-400 transition dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600">
                                Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <div class="mb-6 bg-white dark:bg-gray-800 rounded-2xl shadow p-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">
                    Exportar productos
                </h3>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('productos.export', array_merge(['format' => 'html'], request()->only(['owner', 'buscar', 'orden']))) }}"
                       class="px-4 py-2 rounded-lg bg-slate-600 text-white hover:bg-slate-700 transition">
                        Exportar HTML
                    </a>

                    <a href="{{ route('productos.export', array_merge(['format' => 'csv'], request()->only(['owner', 'buscar', 'orden']))) }}"
                       class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition">
                        Exportar CSV
                    </a>

                    <a href="{{ route('productos.export', array_merge(['format' => 'xlsx'], request()->only(['owner', 'buscar', 'orden']))) }}"
                       class="px-4 py-2 rounded-lg bg-green-700 text-white hover:bg-green-800 transition">
                        Exportar Excel
                    </a>

                    <a href="{{ route('productos.export', array_merge(['format' => 'ods'], request()->only(['owner', 'buscar', 'orden']))) }}"
                       class="px-4 py-2 rounded-lg bg-blue-700 text-white hover:bg-blue-800 transition">
                        Exportar ODS
                    </a>
                </div>
            </div>

            @if($productos->isEmpty())
                <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 p-10 text-center bg-white dark:bg-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        No hay productos cargados
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">
                        Comenzá agregando tu primer producto.
                    </p>
                    <a href="{{ route('productos.create') }}"
                       class="inline-block mt-4 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                        Nuevo producto
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($productos as $producto)
                        @php
                            $rolSobreInventario = auth()->id() === $producto->user_id
                                ? 'owner'
                                : \App\Models\InventarioPermiso::where('owner_user_id', $producto->user_id)
                                    ->where('invited_user_id', auth()->id())
                                    ->value('rol');

                            $stockClass = $producto->stock <= 0
                                ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200'
                                : ($producto->stock <= 5
                                    ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200'
                                    : 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200');
                        @endphp

                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $producto->nombre }}
                                    </h3>

                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Propietario: {{ $producto->owner?->name ?? 'Sin registro' }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Última edición: {{ $producto->updatedByUser?->name ?? 'Sin registro' }}
                                    </p>

                                    @if($producto->descripcion)
                                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $producto->descripcion }}
                                        </p>
                                    @endif
                                </div>

                                <span class="px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap {{ $stockClass }}">
                                    Stock: {{ $producto->stock }}
                                </span>
                            </div>

                            <div class="mt-4">
                                <span class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                                    ${{ number_format($producto->precio, 2, ',', '.') }}
                                </span>
                            </div>

                            <div class="mt-5 flex flex-wrap gap-2">
                                <a href="{{ route('productos.show', $producto) }}"
                                   class="px-3 py-2 rounded-lg bg-sky-600 text-white text-sm hover:bg-sky-700 transition">
                                    Ver
                                </a>

                                @if(in_array($rolSobreInventario, ['owner', 'editor', 'admin']))
                                    <a href="{{ route('productos.edit', $producto) }}"
                                       class="px-3 py-2 rounded-lg bg-amber-500 text-white text-sm hover:bg-amber-600 transition">
                                        Editar
                                    </a>
                                @endif

                                @if(in_array($rolSobreInventario, ['owner', 'admin']))
                                    <form action="{{ route('productos.destroy', $producto) }}" method="POST"
                                          onsubmit="return confirm('¿Seguro que querés eliminar este producto?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-2 rounded-lg bg-red-600 text-white text-sm hover:bg-red-700 transition">
                                            Eliminar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>