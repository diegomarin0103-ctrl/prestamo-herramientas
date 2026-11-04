# Sistema de préstamos de herramientas

Aplicación web en **Laravel 12** y **MySQL** para que un taller registre sus herramientas y controle cuáles se encuentran prestadas, a quién y en qué cantidad.

Evaluación Laravel – Grupo 1, Ejercicio 2.

## Módulos (CRUD)

| Módulo | Campos | Operaciones |
|---|---|---|
| **Herramientas** | código, nombre, tipo de herramienta, marca, cantidad disponible, estado (activa, en reparación, fuera de servicio) | crear, consultar (tabla con unidades disponibles y prestadas), ver detalle con historial de préstamos, editar, eliminar con confirmación |
| **Préstamos** | herramienta, nombre del responsable, fecha de préstamo, fecha de devolución, cantidad prestada, estado del préstamo (prestado, devuelto, vencido) | crear, consultar (tabla con búsqueda y filtros por herramienta y estado), ver detalle, editar, eliminar con confirmación |

**Relación:** cada préstamo está asociado a una herramienta (`prestamos.herramienta_id` → `herramientas.id`, clave foránea). Una herramienta con préstamos registrados no se puede eliminar: el sistema muestra un mensaje de error y la base de datos también lo impide (`ON DELETE RESTRICT`).

### Control de inventario

- Al registrar un préstamo **prestado** o **vencido**, la cantidad prestada se descuenta de la cantidad disponible de la herramienta.
- Al cambiar el préstamo a **devuelto** o eliminarlo, las unidades regresan a la herramienta.
- Al editar la cantidad o la herramienta de un préstamo, el inventario se ajusta automáticamente.
- No se puede prestar más unidades de las disponibles, ni prestar una herramienta en reparación o fuera de servicio.
- Las operaciones se hacen dentro de transacciones de base de datos: si algo falla, no queda nada a medias.

### Validaciones

- Los campos marcados con `*` son obligatorios.
- El código de la herramienta no se puede repetir.
- Cantidades: números enteros (disponible ≥ 0, prestada ≥ 1).
- Fechas válidas; la fecha de préstamo no puede ser futura y la de devolución no puede ser anterior a la de préstamo. Es obligatoria cuando el préstamo está devuelto.
- Estado y herramienta solo aceptan valores existentes.
- Los mensajes de validación, éxito y error se muestran en español.

## Tecnologías

- PHP 8.2 o superior
- Laravel 12
- MySQL / MariaDB (XAMPP)
- Bootstrap 5.3 y Bootstrap Icons (por CDN, no requiere `npm`)
- PHPUnit para las pruebas y GitHub Actions para ejecutarlas en cada push

## Instalación

Requisitos: PHP 8.2+, Composer, Git y MySQL (por ejemplo, XAMPP con MySQL encendido).

```bash
# 1. Clonar el repositorio
git clone https://github.com/<usuario>/prestamo-herramientas.git
cd prestamo-herramientas

# 2. Instalar dependencias de PHP
composer install

# 3. Crear el archivo de entorno y la llave de la aplicación
cp .env.example .env        # en Windows (cmd): copy .env.example .env
php artisan key:generate
```

4. Crear la base de datos vacía `prestamo_herramientas` en phpMyAdmin (cotejamiento `utf8mb4_unicode_ci`) o con:

```sql
CREATE DATABASE prestamo_herramientas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

5. Revisar en `.env` los datos de conexión (los de XAMPP vienen por defecto):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=prestamo_herramientas
DB_USERNAME=root
DB_PASSWORD=
```

6. Crear las tablas, cargar los datos de prueba y arrancar el servidor:

```bash
php artisan migrate --seed
php artisan serve
```

Abrir <http://127.0.0.1:8000>.

Para volver a dejar la base de datos con los datos de prueba originales: `php artisan migrate:fresh --seed`.

## Datos de prueba

El seeder (`database/seeders/DatabaseSeeder.php`) registra 11 herramientas (taladros, pulidoras, llaves, equipos de medición y soldadura, entre otras) y 10 préstamos en los tres estados posibles.

## Pruebas

```bash
php artisan test
```

Las pruebas (`tests/Feature`) cubren las cuatro operaciones de cada CRUD, las validaciones, el descuento y la devolución de unidades y la regla de no eliminar herramientas con préstamos. En local usan SQLite en memoria; en GitHub Actions (`.github/workflows/tests.yml`) se ejecutan contra un servicio de **MySQL 8** en cada push y pull request a `main` y `develop`.

## Estructura principal

```
app/Http/Controllers/HerramientaController.php
app/Http/Controllers/PrestamoController.php   # incluye el control de inventario
app/Http/Requests/HerramientaRequest.php      # reglas de validación
app/Http/Requests/PrestamoRequest.php
app/Models/Herramienta.php                    # hasMany prestamos
app/Models/Prestamo.php                       # belongsTo herramienta
database/migrations/                          # tablas herramientas y prestamos
database/seeders/DatabaseSeeder.php           # datos de prueba
resources/views/layouts/                      # plantilla Bootstrap, menú y alertas
resources/views/herramientas/                 # vistas del CRUD de herramientas
resources/views/prestamos/                    # vistas del CRUD de préstamos
routes/web.php                                # Route::resource de ambos módulos
tests/Feature/                                # pruebas automatizadas
```

## Flujo de trabajo con Git

- `main`: versión estable.
- `develop`: integración de los avances.
- Los cambios se hacen en `develop` (o en ramas `feature/...`) y se integran a `main` mediante Pull Requests.
- El archivo `.env`, las contraseñas y la carpeta `vendor` no se suben al repositorio (ver `.gitignore`).
