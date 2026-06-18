<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (! $user) {
            return;
        }

        Producto::create([
            'user_id' => $user->id,
            'nombre' => 'Teclado mecánico Redragon Kumara',
            'descripcion' => 'Teclado mecánico compacto con switches red.',
            'precio' => 85000.00,
            'stock' => 10,
        ]);

        Producto::create([
            'user_id' => $user->id,
            'nombre' => 'Mouse Logitech G203',
            'descripcion' => 'Mouse gamer con sensor óptico y RGB.',
            'precio' => 42000.00,
            'stock' => 15,
        ]);

        Producto::create([
            'user_id' => $user->id,
            'nombre' => 'Monitor Samsung 24 pulgadas',
            'descripcion' => 'Monitor Full HD de 24 pulgadas para oficina o gaming liviano.',
            'precio' => 215000.00,
            'stock' => 5,
        ]);

        Producto::create([
            'user_id' => $user->id,
            'nombre' => 'Auriculares HyperX Cloud Stinger',
            'descripcion' => 'Auriculares con micrófono para juegos y videollamadas.',
            'precio' => 98000.00,
            'stock' => 8,
        ]);

        Producto::create([
            'user_id' => $user->id,
            'nombre' => 'Webcam Logitech C270',
            'descripcion' => 'Webcam HD ideal para videollamadas y clases virtuales.',
            'precio' => 56000.00,
            'stock' => 12,
        ]);
    }
}
