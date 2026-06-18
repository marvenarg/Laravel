<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_autenticado_puede_ver_su_listado_de_productos(): void
    {
        $user = User::factory()->create();

        Producto::create([
            'user_id' => $user->id,
            'nombre' => 'Producto de prueba',
            'descripcion' => 'Descripción',
            'precio' => 1000,
            'stock' => 10,
        ]);

        $response = $this->actingAs($user)->get('/productos');

        $response->assertStatus(200);
        $response->assertSee('Producto de prueba');
    }

    public function test_usuario_autenticado_puede_crear_un_producto(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/productos', [
            'nombre' => 'Nuevo producto',
            'descripcion' => 'Descripción test',
            'precio' => 2500,
            'stock' => 20,
        ]);

        $response->assertRedirect('/productos');

        $this->assertDatabaseHas('productos', [
            'nombre' => 'Nuevo producto',
            'user_id' => $user->id,
        ]);
    }
}
