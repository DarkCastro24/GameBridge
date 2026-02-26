# GameBridge – Tienda en linea de Hardware

GameBridge es una tienda en línea funcional desarrollada en PHP puro, orientada a la gestión y venta de productos mediante un sitio público y un panel administrativo (dashboard).  
Este proyecto es un sistema operativo para entornos locales de desarrollo.

Incluye funcionalidades como:
- Gestión de usuarios (Root y roles)
- Seguridad de autenticación y control de acceso
- Catálogo de productos
- Gestión de clientes
- Facturación y detalle de pedidos
- Valoraciones de productos
- Historial de acceso de usuarios
- Panel administrativo y sitio público

---

## Autor

Diego Eduardo Castro Quintanilla

## Tecnologías utilizadas

El proyecto fue desarrollado utilizando las siguientes herramientas y tecnologías:

- XAMPP v8.0.0  
  - PHP 8.0.0  
  - Apache 2.4.46  
- PostgreSQL 15 con pgAdmin 4
- JavaScript Vanilla
- HTML5 y CSS3
- Materialize CSS 

---

## Requisitos previos

Antes de ejecutar el proyecto, asegúrese de tener instalado:

- XAMPP (con Apache y PHP 8.x)
- PostgreSQL 15
- pgAdmin 4
- Navegador web Google, Opera o Mozilla Firefox

---

## Instalación y configuración

### Paso 1. Crear la base de datos

1. Abrir pgAdmin 4.
2. Crear una base de datos con el nombre:

```
GameBridge
```

3. Importar el archivo `database.sql` incluido en el proyecto para crear la estructura completa de la base de datos.

---

### Paso 2. Configurar credenciales de la base de datos

Editar el archivo ubicado en:

```
app/helpers/database.php
```

Configurar las credenciales de conexión según el entorno local:

```
Servidor: localhost (127.0.0.1)
Usuario: postgres
Contraseña: 2002
Puerto: 5432
Base de datos: GameBridge
```

Asegúrese de que estos datos coincidan con su configuración local de PostgreSQL.

---

### Paso 3. Habilitar PostgreSQL en PHP (XAMPP)

Para que PHP pueda conectarse correctamente a PostgreSQL:

1. Ir a la ruta:
```
C:\xampp\php
```

2. Abrir el archivo:
```
php.ini
```

3. Buscar la línea:
```
;extension=pdo_pgsql
```

4. Eliminar el punto y coma (`;`) al inicio de la línea:
```
extension=pdo_pgsql
```

5. Guardar los cambios.
6. Reiniciar Apache desde el panel de control de XAMPP.

---

### Paso 4. Acceso al sistema

Una vez configurado el entorno, acceder desde el navegador:

#### Sitio privado (Dashboard)

Al ingresar por primera vez, el sistema solicitará la creación del usuario Root.

```
http://localhost/GameBridge/views/dashboard/
```

#### Sitio público (Tienda)

```
http://localhost/GameBridge/views/public/
```

---

## Consideraciones importantes

- El sistema controla que solo exista un usuario Root inicial.
- La seguridad de contraseñas utiliza hashing mediante `password_hash` y validación con `password_verify`.
  
---

## Variables de entorno

Este proyecto ahora soporta variables de entorno para credenciales sensibles.

- Copie el archivo `.env.example` a `.env` en la raíz del proyecto.
- Actualice los valores (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`, `DB_PORT`, `MAIL_FROM_NAME`, `MAIL_FROM_ADDRESS`).
- Asegúrese de que `.env` no se suba al repositorio (ya está incluido en `.gitignore`).

Nota: Para carga automática más avanzada puede instalar `vlucas/phpdotenv` y reemplazar el cargador simple incluido.


