<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla de préstamos de herramientas.
     * Cada préstamo está asociado a una herramienta (clave foránea herramienta_id).
     */
    public function up(): void
    {
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            // Relación: no se permite borrar una herramienta que tenga préstamos registrados.
            $table->foreignId('herramienta_id')->constrained('herramientas')->restrictOnDelete();
            $table->string('nombre_responsable', 120);
            $table->date('fecha_prestamo');
            $table->date('fecha_devolucion')->nullable();
            $table->unsignedInteger('cantidad_prestada');
            $table->enum('estado', ['prestado', 'devuelto', 'vencido'])->default('prestado');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
