# Sistema de Gestión de Usuarios con Roles

Proyecto desarrollado en PHP con Programación Orientada a Objetos, PDO, MySQL y patrón MVC.

## Requisitos

- XAMPP, WAMP o servidor local equivalente
- PHP 8.0 o superior
- MySQL / MariaDB
- Navegador web

## Instalación

1. Copia la carpeta `gestion-usuarios` dentro de `htdocs` si usas XAMPP.
2. Abre phpMyAdmin.
3. Importa el archivo:

```text
database/database.sql
```

4. Revisa la conexión en:

```text
config/Database.php
```

Por defecto está configurado así:

```php
host: localhost
database: usuarios_mvc
usuario: root
contraseña: vacía
```

5. Ingresa desde el navegador:

```text
http://localhost/gestion-usuarios/public/
```

## Credenciales de acceso

- Admin: `admin@sistema.com` / `password`
- Usuario: `usuario@test.com` / `password`

## Características

- Login con email y contraseña
- Logout
- Sesiones PHP
- Registro de usuarios
- CRUD de usuarios para administrador
- Edición de perfil propio para usuario normal
- Cambio de contraseña
- Roles `admin` y `user`
- Actualización de último acceso
- Registro de fecha de creación y actualización
- Validación de email único
- Contraseñas protegidas con `password_hash()` y `password_verify()`
- Consultas seguras con Prepared Statements
- Salidas protegidas con `htmlspecialchars()`
- Filtros por nombre, email y rol en el listado de usuarios
- Diseño responsive con CSS

## Estructura MVC

```text
gestion-usuarios/
├── config/
│   └── Database.php
├── models/
│   ├── Usuario.php
│   └── Auth.php
├── controllers/
│   ├── UsuarioController.php
│   └── AuthController.php
├── views/
│   ├── layout/
│   ├── auth/
│   └── usuarios/
├── middleware/
│   └── AuthMiddleware.php
├── public/
│   └── index.php
├── assets/
│   └── css/
│       └── style.css
├── database/
│   └── database.sql
├── helpers.php
└── README.md
```

## Explicación de arquitectura

### Modelo

Los modelos contienen la lógica de negocio y las consultas a la base de datos.

- `Usuario.php`: gestiona CRUD, validaciones, email único, cambio de contraseña y último acceso.
- `Auth.php`: gestiona login, logout, usuario actual y verificación de roles.

### Vista

Las vistas contienen la interfaz del sistema.

- `views/auth/login.php`
- `views/auth/register.php`
- `views/usuarios/index.php`
- `views/usuarios/edit.php`
- `views/usuarios/perfil.php`
- `views/layout/header.php`
- `views/layout/footer.php`

### Controlador

Los controladores reciben la petición, llaman a los modelos y cargan las vistas.

- `AuthController.php`: login, logout y registro.
- `UsuarioController.php`: listado, edición, actualización, eliminación, perfil y cambio de contraseña.

### Middleware

`AuthMiddleware.php` protege rutas privadas y valida si el usuario tiene rol administrador.

## Capturas de pantalla

Agregar aquí las capturas del sistema funcionando:

- Login
- Registro
- Listado de usuarios como admin
- Edición de usuario
- Perfil del usuario
- Cambio de contraseña

## Observación

La eliminación de usuarios se implementó como desactivación lógica, cambiando el campo `activo` a `0`. Esto evita borrar datos importantes definitivamente.
