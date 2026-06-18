# Inventario de Productos

## Descripción breve

Aplicación web desarrollada con **Laravel 12**, **Laravel Breeze** y **Blade** para gestionar un inventario de productos asociados a usuarios autenticados.

El sistema permite:

- registro de usuarios
- inicio y cierre de sesión
- CRUD de productos
- validación de formularios
- relación **uno a muchos** entre usuario y productos
- búsqueda y ordenamiento de productos
- exportación de productos en distintos formatos
- inventarios compartidos entre usuarios
- permisos por roles sobre inventarios compartidos
- auditoría básica de propietario y último editor
- pruebas automatizadas con PHPUnit

La entidad elegida es **Inventario de productos**, con los campos:

- `nombre`
- `descripcion`
- `precio`
- `stock`

---

## Requisitos de instalación

### Con Laravel Sail

- Docker
- Docker Compose
- Git
- Navegador web

### Sin Sail (XAMPP o entorno local)

- PHP 8.2 o superior
- Composer
- Node.js y npm
- MySQL / MariaDB
- XAMPP, Laragon o similar
- Git

---

## Ejecución en entorno local

### Opción 1: con Laravel Sail

#### 1. Ingresar al proyecto

```bash
cd inventario-productos
```

#### 2. Instalar dependencias

```bash
./vendor/bin/sail composer install
./vendor/bin/sail npm install
```

#### 3. Copiar entorno y generar clave

```bash
cp .env.example .env
./vendor/bin/sail artisan key:generate
```

#### 4. Levantar contenedores

```bash
./vendor/bin/sail up -d
```

#### 5. Compilar assets frontend

```bash
./vendor/bin/sail npm run build
```

#### 6. Ejecutar migraciones y seeders

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

#### 7. Acceder al sistema

```text
http://localhost:8080
```

#### Nota

Para desarrollo local también puede usarse:

```bash
./vendor/bin/sail npm run dev
```

pero para pruebas finales y acceso desde otros dispositivos se recomienda:

```bash
./vendor/bin/sail npm run build
```

---

### Opción 2: sin Sail (XAMPP / local)

#### 1. Ingresar al proyecto

```bash
cd inventario-productos
```

#### 2. Instalar dependencias

```bash
composer install
npm install
```

#### 3. Copiar entorno y generar clave

```bash
cp .env.example .env
php artisan key:generate
```

#### 4. Compilar assets frontend

```bash
npm run build
```

#### 5. Ejecutar migraciones y seeders

```bash
php artisan migrate:fresh --seed
```

#### 6. Iniciar servidor Laravel

```bash
php artisan serve
```

#### 7. Acceder al sistema

```text
http://127.0.0.1:8000
```

#### Nota

Para desarrollo local también puede usarse:

```bash
npm run dev
```

pero para una ejecución más estable se recomienda:

```bash
npm run build
```

---

## Usuario de prueba

El proyecto crea un usuario de prueba mediante seeder:

```text
Email: test@example.com
Password: password
```

---

## Permisos e inventarios compartidos

Cada producto pertenece a un usuario propietario (**owner**). Además, un inventario puede compartirse con otros usuarios mediante permisos.

### Roles disponibles

- **Lectura**: puede ver y exportar productos.
- **Editor**: puede ver y editar productos existentes.
- **Admin**: puede ver, crear, editar y eliminar productos en el inventario compartido.
- **Owner**: tiene control total sobre su inventario y además puede gestionar los permisos de otros usuarios.

### Comportamiento general

- un usuario puede ver su propio inventario y los inventarios compartidos con él
- el sistema permite seleccionar qué inventario visualizar
- los permisos determinan qué acciones están disponibles sobre cada inventario

---

## Exportación de productos

El sistema permite exportar el listado de productos en los siguientes formatos:

- HTML
- CSV
- Excel (`.xlsx`)
- ODS

La exportación respeta los filtros aplicados en pantalla, como inventario seleccionado, búsqueda y ordenamiento.

---

## Auditoría básica

Cada producto registra:

- el usuario propietario del inventario
- el último usuario que editó el producto

Esto permite identificar el owner del producto y la última modificación realizada.

---

## Pruebas creadas

Se implementaron dos pruebas automatizadas con PHPUnit.

### Archivo de pruebas

```text
tests/Feature/ProductoTest.php
```

### Pruebas incluidas

- un usuario autenticado puede ver su listado de productos
- un usuario autenticado puede crear un producto

### Ejecutar pruebas con Sail

```bash
./vendor/bin/sail artisan test
```

### Ejecutar pruebas sin Sail

```bash
php artisan test
```

---

## Autor

Marcelo Ventura
