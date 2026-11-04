<?php

namespace Database\Seeders;

use App\Models\Herramienta;
use App\Models\Prestamo;
use Illuminate\Database\Seeder;

/**
 * Datos de prueba para demostrar el funcionamiento del sistema.
 * Ejecutar con: php artisan migrate --seed  (o php artisan db:seed)
 *
 * La "cantidad disponible" de cada herramienta ya tiene descontadas
 * las unidades de los préstamos que siguen activos (prestado o vencido).
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // código, nombre, tipo, marca, unidades en el taller, estado
        $datos = [
            ['HR-001', 'Taladro percutor 1/2"', 'Eléctrica', 'Bosch', 4, 'activa'],
            ['HR-002', 'Pulidora angular 4 1/2"', 'Eléctrica', 'DeWalt', 3, 'activa'],
            ['HR-003', 'Juego de llaves combinadas', 'Manual', 'Stanley', 6, 'activa'],
            ['HR-004', 'Destornillador de impacto', 'Eléctrica', 'Makita', 2, 'activa'],
            ['HR-005', 'Martillo de uña 16 oz', 'Manual', 'Truper', 10, 'activa'],
            ['HR-006', 'Pistola de impacto neumática', 'Neumática', 'Ingersoll Rand', 1, 'activa'],
            ['HR-007', 'Calibrador pie de rey', 'Medición', 'Mitutoyo', 5, 'activa'],
            ['HR-008', 'Multímetro digital', 'Medición', 'Fluke', 3, 'activa'],
            ['HR-009', 'Sierra circular 7 1/4"', 'Corte', 'Black+Decker', 0, 'en_reparacion'],
            ['HR-010', 'Equipo de soldadura MIG', 'Soldadura', 'Lincoln Electric', 1, 'activa'],
            ['HR-011', 'Compresor de aire 50 L', 'Neumática', 'Truper', 0, 'fuera_de_servicio'],
        ];

        $h = [];
        foreach ($datos as [$codigo, $nombre, $tipo, $marca, $cantidad, $estado]) {
            $h[$codigo] = Herramienta::create([
                'codigo' => $codigo,
                'nombre' => $nombre,
                'tipo' => $tipo,
                'marca' => $marca,
                'cantidad_disponible' => $cantidad,
                'estado' => $estado,
            ]);
        }

        // herramienta, responsable, fecha préstamo, fecha devolución, cantidad, estado
        $prestamos = [
            ['HR-001', 'Carlos Ramírez', '2026-10-28', '2026-11-08', 1, 'prestado'],
            ['HR-002', 'Laura Gómez', '2026-10-30', '2026-11-06', 1, 'prestado'],
            ['HR-003', 'Andrés Restrepo', '2026-10-15', '2026-10-20', 2, 'devuelto'],
            ['HR-004', 'Juliana Ospina', '2026-10-10', '2026-10-25', 1, 'vencido'],
            ['HR-005', 'Mateo Cardona', '2026-11-02', '2026-11-09', 2, 'prestado'],
            ['HR-006', 'Valentina Arango', '2026-09-22', '2026-09-29', 1, 'devuelto'],
            ['HR-007', 'Santiago Mejía', '2026-11-03', null, 1, 'prestado'],
            ['HR-008', 'Daniela Henao', '2026-10-05', '2026-10-12', 1, 'devuelto'],
            ['HR-010', 'Felipe Zapata', '2026-10-20', '2026-10-30', 1, 'vencido'],
            ['HR-001', 'Sofía Vélez', '2026-09-15', '2026-09-18', 2, 'devuelto'],
        ];

        foreach ($prestamos as [$codigo, $responsable, $fecha, $devolucion, $cantidad, $estado]) {
            Prestamo::create([
                'herramienta_id' => $h[$codigo]->id,
                'nombre_responsable' => $responsable,
                'fecha_prestamo' => $fecha,
                'fecha_devolucion' => $devolucion,
                'cantidad_prestada' => $cantidad,
                'estado' => $estado,
            ]);
        }
    }
}
