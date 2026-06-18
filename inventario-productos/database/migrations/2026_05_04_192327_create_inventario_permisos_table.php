<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventario_permisos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('invited_user_id')->constrained('users')->onDelete('cascade');
            $table->string('rol'); // lectura, editor, admin
            $table->timestamps();

            $table->unique(['owner_user_id', 'invited_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_permisos');
    }
};
