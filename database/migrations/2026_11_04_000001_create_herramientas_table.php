<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla de herramientas del taller.
     */
    public function up(): void
    {
        Schema::create('herramientas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 30)->unique();
            $table->string('nombre', 100);
            $table->string('tipo', 60);
            $table->string('marca', 60);
            $table->unsignedInteger('cantidad_disponible')->default(0);
            $table->enum('estado', ['activa', 'en_reparacion', 'fuera_de_servicio'])->default('activa');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('herramientas');
    }
};
