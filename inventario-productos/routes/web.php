<?php

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventarioPermisoController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('productos', ProductoController::class);
    Route::get('/productos/export/{format}', [ProductoController::class, 'export'])
        ->name('productos.export');
    Route::get('/permisos', [InventarioPermisoController::class, 'index'])->name('permisos.index');
    Route::post('/permisos', [InventarioPermisoController::class, 'store'])->name('permisos.store');
    Route::put('/permisos/{permiso}', [InventarioPermisoController::class, 'update'])->name('permisos.update');
    Route::delete('/permisos/{permiso}', [InventarioPermisoController::class, 'destroy'])->name('permisos.destroy');
});

require __DIR__ . '/auth.php';
