<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    Permisos de inventario
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Compartí tu inventario con otros usuarios.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('mensaje'))
                <div class="mb-5 p-4 rounded-xl bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100">
                    {{ session('mensaje') }}
                </div>
            @endif

            <div class="mb-6 bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Agregar o actualizar permiso
                </h3>

                <form action="{{ route('permisos.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Usuario</label>
                        <select name="invited_user_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}">{{ $usuario->name }} ({{ $usuario->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rol</label>
                        <select name="rol" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                            <option value="lectura">Lectura</option>
                            <option value="editor">Editor</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <!-- 
                    <div class="flex items-end">
                        <button type="submit"
                                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                            Guardar permiso
                        </button>
                    </div>
                    -->
                    <div>
                        <label class="block text-sm font-medium text-transparent mb-2">
                            Acción
                        </label>

                        <button type="submit"
                                class="inline-flex items-center justify-center h-11 px-4 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition whitespace-nowrap">
                            Guardar permiso
                        </button>
                    </div> 
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Usuarios con acceso
                </h3>

                @if($permisos->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">Todavía no compartiste tu inventario con nadie.</p>
                @else
                    <div class="space-y-4">
                        @foreach($permisos as $permiso)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $permiso->invitedUser->name }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $permiso->invitedUser->email }}
                                    </p>
                                </div>

                                <div class="flex flex-wrap gap-2 items-center">
                                    <form action="{{ route('permisos.update', $permiso) }}" method="POST" class="flex gap-2">
                                        @csrf
                                        @method('PUT')

                                        <select name="rol" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                                            <option value="lectura" {{ $permiso->rol === 'lectura' ? 'selected' : '' }}>Lectura</option>
                                            <option value="editor" {{ $permiso->rol === 'editor' ? 'selected' : '' }}>Editor</option>
                                            <option value="admin" {{ $permiso->rol === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>

                                        <button type="submit"
                                                class="px-3 py-2 rounded-lg bg-amber-500 text-white hover:bg-amber-600 transition">
                                            Actualizar
                                        </button>
                                    </form>

                                    <form action="{{ route('permisos.destroy', $permiso) }}" method="POST"
                                          onsubmit="return confirm('¿Seguro que querés quitar este permiso?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="px-3 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                                            Quitar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>