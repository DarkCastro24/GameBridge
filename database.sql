CREATE DATABASE Gamebridge;

-- CATEGORIAS
CREATE TABLE IF NOT EXISTS public.categorias (
  idcategoria  SERIAL PRIMARY KEY,
  categoria    VARCHAR(30) NULL,
  descripcion  VARCHAR(150) NOT NULL,
  imagen       VARCHAR(100) NULL
);

-- MARCAS
CREATE TABLE IF NOT EXISTS public.marcas (
  idmarca  SERIAL PRIMARY KEY,
  marca    VARCHAR(40) NULL
);

-- TIPO USUARIOS
CREATE TABLE IF NOT EXISTS public.tipousuarios (
  idtipo       SERIAL PRIMARY KEY,
  tipousuario  VARCHAR(25) NOT NULL
);

-- ESTADO USUARIOS
CREATE TABLE IF NOT EXISTS public.estadousuarios (
  idestado  INTEGER PRIMARY KEY,
  estado    VARCHAR(25) NOT NULL
);

-- ESTADO FACTURA
CREATE TABLE IF NOT EXISTS public.estadofactura (
  idestado      SERIAL PRIMARY KEY,
  estadofactura VARCHAR(25) NOT NULL
);

-- CLIENTES
CREATE TABLE IF NOT EXISTS public.clientes (
  idcliente          SERIAL PRIMARY KEY,
  nombres            VARCHAR(40) NOT NULL,
  apellidos          VARCHAR(40) NOT NULL,
  dui                CHAR(10) NOT NULL,
  correo_electronico VARCHAR(50) NOT NULL,
  clave              VARCHAR(200) NOT NULL,
  fecharegistro      TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT now(),
  estado             BOOLEAN NOT NULL DEFAULT true
);

-- Validacion para evitar correos y duis duplicados
CREATE UNIQUE INDEX IF NOT EXISTS uq_clientes_correo ON public.clientes (correo_electronico);
CREATE UNIQUE INDEX IF NOT EXISTS uq_clientes_dui    ON public.clientes (dui);

-- PRODUCTOS (depende de categorias y marcas)
CREATE TABLE IF NOT EXISTS public.productos (
  idproducto   SERIAL PRIMARY KEY,
  categoria    INTEGER NOT NULL,
  marca        INTEGER NOT NULL,
  producto     VARCHAR(75) NOT NULL,
  precio       NUMERIC NOT NULL,
  descripcion  VARCHAR(200) NOT NULL,
  imagen       VARCHAR(100) NULL,
  cantidad     INTEGER NULL,
  estado       BOOLEAN NOT NULL DEFAULT true,
  CONSTRAINT fk_productos_categoria FOREIGN KEY (categoria)
    REFERENCES public.categorias (idcategoria)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_productos_marca FOREIGN KEY (marca)
    REFERENCES public.marcas (idmarca)
    ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE INDEX IF NOT EXISTS ix_productos_categoria ON public.productos (categoria);
CREATE INDEX IF NOT EXISTS ix_productos_marca     ON public.productos (marca);

-- USUARIOS 
CREATE TABLE IF NOT EXISTS public.usuarios (
  idusuario         SERIAL PRIMARY KEY,
  tipo              INTEGER NOT NULL,
  usuario           VARCHAR(35) NOT NULL,
  clave             VARCHAR(60) NOT NULL,
  correo_electronico VARCHAR(60) NOT NULL,
  fecharegistro     TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT now(),
  estado            INTEGER NOT NULL DEFAULT 1,  -- 1 Activo, 2 Bloqueado 
  telefono          CHAR(9) NOT NULL,
  dui               CHAR(10) NOT NULL,
  intentos          INTEGER NOT NULL DEFAULT 0,
  fechaclave        TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT now(),
  CONSTRAINT fk_usuarios_tipo FOREIGN KEY (tipo)
    REFERENCES public.tipousuarios (idtipo)
    ON UPDATE CASCADE ON DELETE RESTRICT,

  CONSTRAINT fk_usuarios_estado FOREIGN KEY (estado)
    REFERENCES public.estadousuarios (idestado)
    ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE UNIQUE INDEX IF NOT EXISTS uq_usuarios_usuario ON public.usuarios (usuario);
CREATE UNIQUE INDEX IF NOT EXISTS uq_usuarios_correo  ON public.usuarios (correo_electronico);
CREATE UNIQUE INDEX IF NOT EXISTS uq_usuarios_dui     ON public.usuarios (dui);

-- DIRECCIONES
CREATE TABLE IF NOT EXISTS public.direcciones (
  iddireccion   SERIAL PRIMARY KEY,
  cliente       INTEGER NOT NULL,
  direccion     VARCHAR(150) NOT NULL,
  codigo_postal CHAR(4) NOT NULL,
  telefono_fijo CHAR(9) NULL,
  CONSTRAINT fk_direcciones_cliente FOREIGN KEY (cliente)
    REFERENCES public.clientes (idcliente)
    ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS ix_direcciones_cliente ON public.direcciones (cliente);

-- FACTURAS
CREATE TABLE IF NOT EXISTS public.facturas (
  idfactura SERIAL PRIMARY KEY,
  cliente   INTEGER NOT NULL,
  estado    INTEGER NOT NULL,
  fecha     DATE NOT NULL DEFAULT CURRENT_DATE,
  CONSTRAINT fk_facturas_cliente FOREIGN KEY (cliente)
    REFERENCES public.clientes (idcliente)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_facturas_estado FOREIGN KEY (estado)
    REFERENCES public.estadofactura (idestado)
    ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE INDEX IF NOT EXISTS ix_facturas_cliente ON public.facturas (cliente);
CREATE INDEX IF NOT EXISTS ix_facturas_estado  ON public.facturas (estado);

-- DETALLEPEDIDOS
CREATE TABLE IF NOT EXISTS public.detallepedidos (
  iddetallefactura SERIAL PRIMARY KEY,
  pedido           INTEGER NOT NULL,
  producto         INTEGER NOT NULL,
  preciounitario   NUMERIC NOT NULL,
  cantidad         INTEGER NOT NULL,
  CONSTRAINT fk_detalle_pedido FOREIGN KEY (pedido)
    REFERENCES public.facturas (idfactura)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_detalle_producto FOREIGN KEY (producto)
    REFERENCES public.productos (idproducto)
    ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE INDEX IF NOT EXISTS ix_detalle_pedido   ON public.detallepedidos (pedido);
CREATE INDEX IF NOT EXISTS ix_detalle_producto ON public.detallepedidos (producto);

-- VALORACIONES
CREATE TABLE IF NOT EXISTS public.valoraciones (
  id_valoracion         SERIAL PRIMARY KEY,
  id_detalle            INTEGER NOT NULL,
  calificacion_producto INTEGER NULL,
  comentario_producto   VARCHAR(250) NULL,
  fecha_comentario      TIMESTAMP WITHOUT TIME ZONE NULL DEFAULT now(),
  estado_comentario     BOOLEAN NULL DEFAULT true,
  CONSTRAINT fk_valoraciones_detalle FOREIGN KEY (id_detalle)
    REFERENCES public.detallepedidos (iddetallefactura)
    ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS ix_valoraciones_detalle ON public.valoraciones (id_detalle);

-- HISTORIALUSUARIO 
CREATE TABLE IF NOT EXISTS public.historialusuario (
  idhistorial SERIAL PRIMARY KEY,
  usuario     INTEGER NOT NULL,
  sistema     VARCHAR(150) NOT NULL,
  hora        TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT now(),
  CONSTRAINT fk_historial_usuario FOREIGN KEY (usuario)
    REFERENCES public.usuarios (idusuario)
    ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS ix_historial_usuario ON public.historialusuario (usuario);
CREATE INDEX IF NOT EXISTS ix_historial_sistema ON public.historialusuario (sistema);


/* INSERTS NECESARIOS PARA EL FUNCIONAMIENTO DEL SISTEMA */

-- Tipos de usuario 
INSERT INTO public.tipousuarios (idtipo, tipousuario)
VALUES (1,'Root'), (2,'Administrador');

-- Estados de usuario 
INSERT INTO public.estadousuarios (idestado, estado)
VALUES (1,'Activo'), (2,'Bloqueado');

-- Estados de factura 
INSERT INTO public.estadofactura (idestado, estadofactura)
VALUES (1,'Pendiente'), (2,'Pagada'), (3,'Anulada');

-- Marcas base
INSERT INTO marcas (marca) VALUES
('Intel'),
('AMD'),
('Apple Silicon'),
('Qualcomm'),
('IBM'),
('VIA Technologies'),
('MediaTek'),
('Samsung'),
('NVIDIA'),
('ARM');

-- Corrigiendo incompatiblidad de productos
CREATE TABLE IF NOT EXISTS public.estadoproductos (
  idestado integer PRIMARY KEY,
  estado   varchar(25) NOT NULL
);

INSERT INTO public.estadoproductos (idestado, estado)
VALUES (1, 'Activo'), (2, 'Inactivo');

ALTER TABLE public.productos
ALTER COLUMN estado DROP DEFAULT;

ALTER TABLE public.productos
ALTER COLUMN estado TYPE integer
USING CASE WHEN estado = true THEN 1 ELSE 2 END;

ALTER TABLE public.productos
ALTER COLUMN estado SET DEFAULT 1;

ALTER TABLE public.productos
ADD CONSTRAINT fk_productos_estado
FOREIGN KEY (estado)
REFERENCES public.estadoproductos (idestado)
ON UPDATE CASCADE
ON DELETE RESTRICT;

ALTER TABLE public.productos
ALTER COLUMN cantidad SET DEFAULT 1;

-- CORREGIR SECCIONES EN CATEGORIAS DE PRODUCTOS
CREATE TABLE IF NOT EXISTS public.secciones (
  idseccion SERIAL PRIMARY KEY,
  seccion   VARCHAR(40) NOT NULL
);

-- Recomendado: evitar duplicados de nombre
CREATE UNIQUE INDEX IF NOT EXISTS uq_secciones_seccion ON public.secciones (seccion);

ALTER TABLE public.categorias
ADD COLUMN IF NOT EXISTS seccion integer;

INSERT INTO public.secciones (idseccion, seccion)
VALUES
  (1, 'Hardware'),
  (2, 'Perifericos'),
  (3, 'Accesorios');

UPDATE public.categorias
SET seccion = 1
WHERE seccion IS NULL;

ALTER TABLE public.categorias
ALTER COLUMN seccion SET NOT NULL;

ALTER TABLE public.categorias
ADD CONSTRAINT fk_categorias_secciones
FOREIGN KEY (seccion)
REFERENCES public.secciones (idseccion)
ON UPDATE CASCADE
ON DELETE RESTRICT;


-- AGREGAR CAMPOS PARA ACTIVAR LAS FUNCIONES DE CORREO ELECTRONICO 
ALTER TABLE public.usuarios
ADD COLUMN IF NOT EXISTS codigo_recu integer NULL;

ALTER TABLE public.clientes
ADD COLUMN IF NOT EXISTS codigo_recu integer NULL;

-- ESTADO CLIENTES (espejo de estadousuarios, independiente para clientes)
CREATE TABLE IF NOT EXISTS public.estadocliente (
  idestado  INTEGER PRIMARY KEY,
  estado    VARCHAR(25) NOT NULL
);

INSERT INTO public.estadocliente (idestado, estado)
VALUES (1, 'Activo'), (2, 'Bloqueado')
ON CONFLICT (idestado) DO NOTHING;

-- Ajustar columna estado de clientes: de BOOLEAN a INTEGER con FK
ALTER TABLE public.clientes
ALTER COLUMN estado DROP DEFAULT;

ALTER TABLE public.clientes
ALTER COLUMN estado TYPE integer
USING CASE WHEN estado = true THEN 1 ELSE 2 END;

ALTER TABLE public.clientes
ALTER COLUMN estado SET DEFAULT 1;

ALTER TABLE public.clientes
ADD CONSTRAINT fk_clientes_estado
FOREIGN KEY (estado)
REFERENCES public.estadocliente (idestado)
ON UPDATE CASCADE
ON DELETE RESTRICT;

-- Agregar columna fechaclave si no existe (para control de expiración de contraseña)
ALTER TABLE public.clientes
ADD COLUMN IF NOT EXISTS fechaclave TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT now();

-- Agregar columna intentos si no existe (para bloqueo por intentos fallidos)
ALTER TABLE public.clientes
ADD COLUMN IF NOT EXISTS intentos INTEGER NOT NULL DEFAULT 0;

-- Agregar columna codigo_recu si no existe (para recuperación de contraseña por correo)
ALTER TABLE public.clientes
ADD COLUMN IF NOT EXISTS codigo_recu INTEGER NULL;

-- ============================================================
--  HISTORIAL CLIENTE 
--  Espejo de historialusuario pero para clientes
-- ============================================================
CREATE TABLE IF NOT EXISTS public.historialcliente (
  idhistorial SERIAL PRIMARY KEY,
  cliente     INTEGER NOT NULL,
  sistema     VARCHAR(150) NOT NULL,
  hora        TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT now(),
  CONSTRAINT fk_historialcliente_cliente FOREIGN KEY (cliente)
    REFERENCES public.clientes (idcliente)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS ix_historialcliente_cliente ON public.historialcliente (cliente);
CREATE INDEX IF NOT EXISTS ix_historialcliente_sistema ON public.historialcliente (sistema);

-- ============================================================
--  VERIFICACIÓN FINAL
-- ============================================================
SELECT 'estadocliente'    AS tabla, COUNT(*) AS registros FROM public.estadocliente
UNION ALL
SELECT 'historialcliente' AS tabla, COUNT(*) AS registros FROM public.historialcliente;

-- Funcion para obtener los días para la consulta SELECT diasClave(?)
CREATE OR REPLACE FUNCTION public.diasclave(fecha TIMESTAMP)
RETURNS INTEGER
LANGUAGE sql
AS $$
  SELECT COALESCE(DATE_PART('day', now() - fecha)::int, 999999);
$$;

SELECT * FROM usuarios

SELECT * FROM categorias

SELECT idProducto as id,c.categoria,e.estado,m.marca,producto,precio,p.descripcion, p.imagen 
        FROM productos p
        INNER JOIN categorias c ON c.idCategoria = p.Categoria
        INNER JOIN estadoProductos e ON e.idEstado = p.estado
        INNER JOIN marcas m ON m.idMarca = p.marca
        order by c.categoria

SELECT idcategoria AS id, categoria, descripcion, imagen
                FROM categorias
                ORDER BY categoria

CREATE DATABASE Gamebridge;

-- CATEGORIAS
CREATE TABLE IF NOT EXISTS public.categorias (
  idcategoria  SERIAL PRIMARY KEY,
  categoria    VARCHAR(30) NULL,
  descripcion  VARCHAR(150) NOT NULL,
  imagen       VARCHAR(100) NULL
);

-- MARCAS
CREATE TABLE IF NOT EXISTS public.marcas (
  idmarca  SERIAL PRIMARY KEY,
  marca    VARCHAR(40) NULL
);

-- TIPO USUARIOS
CREATE TABLE IF NOT EXISTS public.tipousuarios (
  idtipo       SERIAL PRIMARY KEY,
  tipousuario  VARCHAR(25) NOT NULL
);

-- ESTADO USUARIOS
CREATE TABLE IF NOT EXISTS public.estadousuarios (
  idestado  INTEGER PRIMARY KEY,
  estado    VARCHAR(25) NOT NULL
);

-- ESTADO FACTURA
CREATE TABLE IF NOT EXISTS public.estadofactura (
  idestado      SERIAL PRIMARY KEY,
  estadofactura VARCHAR(25) NOT NULL
);

-- CLIENTES
CREATE TABLE IF NOT EXISTS public.clientes (
  idcliente          SERIAL PRIMARY KEY,
  nombres            VARCHAR(40) NOT NULL,
  apellidos          VARCHAR(40) NOT NULL,
  dui                CHAR(10) NOT NULL,
  correo_electronico VARCHAR(50) NOT NULL,
  clave              VARCHAR(200) NOT NULL,
  fecharegistro      TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT now(),
  estado             BOOLEAN NOT NULL DEFAULT true
);

-- Validacion para evitar correos y duis duplicados
CREATE UNIQUE INDEX IF NOT EXISTS uq_clientes_correo ON public.clientes (correo_electronico);
CREATE UNIQUE INDEX IF NOT EXISTS uq_clientes_dui    ON public.clientes (dui);

-- PRODUCTOS (depende de categorias y marcas)
CREATE TABLE IF NOT EXISTS public.productos (
  idproducto   SERIAL PRIMARY KEY,
  categoria    INTEGER NOT NULL,
  marca        INTEGER NOT NULL,
  producto     VARCHAR(75) NOT NULL,
  precio       NUMERIC NOT NULL,
  descripcion  VARCHAR(200) NOT NULL,
  imagen       VARCHAR(100) NULL,
  cantidad     INTEGER NULL,
  estado       BOOLEAN NOT NULL DEFAULT true,
  CONSTRAINT fk_productos_categoria FOREIGN KEY (categoria)
    REFERENCES public.categorias (idcategoria)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_productos_marca FOREIGN KEY (marca)
    REFERENCES public.marcas (idmarca)
    ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE INDEX IF NOT EXISTS ix_productos_categoria ON public.productos (categoria);
CREATE INDEX IF NOT EXISTS ix_productos_marca     ON public.productos (marca);

-- USUARIOS 
CREATE TABLE IF NOT EXISTS public.usuarios (
  idusuario         SERIAL PRIMARY KEY,
  tipo              INTEGER NOT NULL,
  usuario           VARCHAR(35) NOT NULL,
  clave             VARCHAR(60) NOT NULL,
  correo_electronico VARCHAR(60) NOT NULL,
  fecharegistro     TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT now(),
  estado            INTEGER NOT NULL DEFAULT 1,  -- 1 Activo, 2 Bloqueado 
  telefono          CHAR(9) NOT NULL,
  dui               CHAR(10) NOT NULL,
  intentos          INTEGER NOT NULL DEFAULT 0,
  fechaclave        TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT now(),
  CONSTRAINT fk_usuarios_tipo FOREIGN KEY (tipo)
    REFERENCES public.tipousuarios (idtipo)
    ON UPDATE CASCADE ON DELETE RESTRICT,

  CONSTRAINT fk_usuarios_estado FOREIGN KEY (estado)
    REFERENCES public.estadousuarios (idestado)
    ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE UNIQUE INDEX IF NOT EXISTS uq_usuarios_usuario ON public.usuarios (usuario);
CREATE UNIQUE INDEX IF NOT EXISTS uq_usuarios_correo  ON public.usuarios (correo_electronico);
CREATE UNIQUE INDEX IF NOT EXISTS uq_usuarios_dui     ON public.usuarios (dui);

-- DIRECCIONES
CREATE TABLE IF NOT EXISTS public.direcciones (
  iddireccion   SERIAL PRIMARY KEY,
  cliente       INTEGER NOT NULL,
  direccion     VARCHAR(150) NOT NULL,
  codigo_postal CHAR(4) NOT NULL,
  telefono_fijo CHAR(9) NULL,
  CONSTRAINT fk_direcciones_cliente FOREIGN KEY (cliente)
    REFERENCES public.clientes (idcliente)
    ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS ix_direcciones_cliente ON public.direcciones (cliente);

-- FACTURAS
CREATE TABLE IF NOT EXISTS public.facturas (
  idfactura SERIAL PRIMARY KEY,
  cliente   INTEGER NOT NULL,
  estado    INTEGER NOT NULL,
  fecha     DATE NOT NULL DEFAULT CURRENT_DATE,
  CONSTRAINT fk_facturas_cliente FOREIGN KEY (cliente)
    REFERENCES public.clientes (idcliente)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_facturas_estado FOREIGN KEY (estado)
    REFERENCES public.estadofactura (idestado)
    ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE INDEX IF NOT EXISTS ix_facturas_cliente ON public.facturas (cliente);
CREATE INDEX IF NOT EXISTS ix_facturas_estado  ON public.facturas (estado);

-- DETALLEPEDIDOS
CREATE TABLE IF NOT EXISTS public.detallepedidos (
  iddetallefactura SERIAL PRIMARY KEY,
  pedido           INTEGER NOT NULL,
  producto         INTEGER NOT NULL,
  preciounitario   NUMERIC NOT NULL,
  cantidad         INTEGER NOT NULL,
  CONSTRAINT fk_detalle_pedido FOREIGN KEY (pedido)
    REFERENCES public.facturas (idfactura)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_detalle_producto FOREIGN KEY (producto)
    REFERENCES public.productos (idproducto)
    ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE INDEX IF NOT EXISTS ix_detalle_pedido   ON public.detallepedidos (pedido);
CREATE INDEX IF NOT EXISTS ix_detalle_producto ON public.detallepedidos (producto);

-- VALORACIONES
CREATE TABLE IF NOT EXISTS public.valoraciones (
  id_valoracion         SERIAL PRIMARY KEY,
  id_detalle            INTEGER NOT NULL,
  calificacion_producto INTEGER NULL,
  comentario_producto   VARCHAR(250) NULL,
  fecha_comentario      TIMESTAMP WITHOUT TIME ZONE NULL DEFAULT now(),
  estado_comentario     BOOLEAN NULL DEFAULT true,
  CONSTRAINT fk_valoraciones_detalle FOREIGN KEY (id_detalle)
    REFERENCES public.detallepedidos (iddetallefactura)
    ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS ix_valoraciones_detalle ON public.valoraciones (id_detalle);

-- HISTORIALUSUARIO 
CREATE TABLE IF NOT EXISTS public.historialusuario (
  idhistorial SERIAL PRIMARY KEY,
  usuario     INTEGER NOT NULL,
  sistema     VARCHAR(150) NOT NULL,
  hora        TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT now(),
  CONSTRAINT fk_historial_usuario FOREIGN KEY (usuario)
    REFERENCES public.usuarios (idusuario)
    ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS ix_historial_usuario ON public.historialusuario (usuario);
CREATE INDEX IF NOT EXISTS ix_historial_sistema ON public.historialusuario (sistema);


/* INSERTS NECESARIOS PARA EL FUNCIONAMIENTO DEL SISTEMA */

-- Tipos de usuario 
INSERT INTO public.tipousuarios (idtipo, tipousuario)
VALUES (1,'Root'), (2,'Administrador');

-- Estados de usuario 
INSERT INTO public.estadousuarios (idestado, estado)
VALUES (1,'Activo'), (2,'Bloqueado');

-- Estados de factura 
INSERT INTO public.estadofactura (idestado, estadofactura)
VALUES (1,'Pendiente'), (2,'Pagada'), (3,'Anulada');

-- Marcas base
INSERT INTO marcas (marca) VALUES
('Intel'),
('AMD'),
('Apple Silicon'),
('Qualcomm'),
('IBM'),
('VIA Technologies'),
('MediaTek'),
('Samsung'),
('NVIDIA'),
('ARM');

-- Corrigiendo incompatiblidad de productos
CREATE TABLE IF NOT EXISTS public.estadoproductos (
  idestado integer PRIMARY KEY,
  estado   varchar(25) NOT NULL
);

INSERT INTO public.estadoproductos (idestado, estado)
VALUES (1, 'Activo'), (2, 'Inactivo');

ALTER TABLE public.productos
ALTER COLUMN estado DROP DEFAULT;

ALTER TABLE public.productos
ALTER COLUMN estado TYPE integer
USING CASE WHEN estado = true THEN 1 ELSE 2 END;

ALTER TABLE public.productos
ALTER COLUMN estado SET DEFAULT 1;

ALTER TABLE public.productos
ADD CONSTRAINT fk_productos_estado
FOREIGN KEY (estado)
REFERENCES public.estadoproductos (idestado)
ON UPDATE CASCADE
ON DELETE RESTRICT;

ALTER TABLE public.productos
ALTER COLUMN cantidad SET DEFAULT 1;

-- CORREGIR SECCIONES EN CATEGORIAS DE PRODUCTOS
CREATE TABLE IF NOT EXISTS public.secciones (
  idseccion SERIAL PRIMARY KEY,
  seccion   VARCHAR(40) NOT NULL
);

-- Recomendado: evitar duplicados de nombre
CREATE UNIQUE INDEX IF NOT EXISTS uq_secciones_seccion ON public.secciones (seccion);

ALTER TABLE public.categorias
ADD COLUMN IF NOT EXISTS seccion integer;

INSERT INTO public.secciones (idseccion, seccion)
VALUES
  (1, 'Hardware'),
  (2, 'Perifericos'),
  (3, 'Accesorios');

UPDATE public.categorias
SET seccion = 1
WHERE seccion IS NULL;

ALTER TABLE public.categorias
ALTER COLUMN seccion SET NOT NULL;

ALTER TABLE public.categorias
ADD CONSTRAINT fk_categorias_secciones
FOREIGN KEY (seccion)
REFERENCES public.secciones (idseccion)
ON UPDATE CASCADE
ON DELETE RESTRICT;


-- AGREGAR CAMPOS PARA ACTIVAR LAS FUNCIONES DE CORREO ELECTRONICO 
ALTER TABLE public.usuarios
ADD COLUMN IF NOT EXISTS codigo_recu integer NULL;

ALTER TABLE public.clientes
ADD COLUMN IF NOT EXISTS codigo_recu integer NULL;

-- ESTADO CLIENTES (espejo de estadousuarios, independiente para clientes)
CREATE TABLE IF NOT EXISTS public.estadocliente (
  idestado  INTEGER PRIMARY KEY,
  estado    VARCHAR(25) NOT NULL
);

INSERT INTO public.estadocliente (idestado, estado)
VALUES (1, 'Activo'), (2, 'Bloqueado')
ON CONFLICT (idestado) DO NOTHING;

-- Ajustar columna estado de clientes: de BOOLEAN a INTEGER con FK
ALTER TABLE public.clientes
ALTER COLUMN estado DROP DEFAULT;

ALTER TABLE public.clientes
ALTER COLUMN estado TYPE integer
USING CASE WHEN estado = true THEN 1 ELSE 2 END;

ALTER TABLE public.clientes
ALTER COLUMN estado SET DEFAULT 1;

ALTER TABLE public.clientes
ADD CONSTRAINT fk_clientes_estado
FOREIGN KEY (estado)
REFERENCES public.estadocliente (idestado)
ON UPDATE CASCADE
ON DELETE RESTRICT;

-- Agregar columna fechaclave si no existe (para control de expiración de contraseña)
ALTER TABLE public.clientes
ADD COLUMN IF NOT EXISTS fechaclave TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT now();

-- Agregar columna intentos si no existe (para bloqueo por intentos fallidos)
ALTER TABLE public.clientes
ADD COLUMN IF NOT EXISTS intentos INTEGER NOT NULL DEFAULT 0;

-- Agregar columna codigo_recu si no existe (para recuperación de contraseña por correo)
ALTER TABLE public.clientes
ADD COLUMN IF NOT EXISTS codigo_recu INTEGER NULL;

-- ============================================================
--  HISTORIAL CLIENTE 
--  Espejo de historialusuario pero para clientes
-- ============================================================
CREATE TABLE IF NOT EXISTS public.historialcliente (
  idhistorial SERIAL PRIMARY KEY,
  cliente     INTEGER NOT NULL,
  sistema     VARCHAR(150) NOT NULL,
  hora        TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT now(),
  CONSTRAINT fk_historialcliente_cliente FOREIGN KEY (cliente)
    REFERENCES public.clientes (idcliente)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS ix_historialcliente_cliente ON public.historialcliente (cliente);
CREATE INDEX IF NOT EXISTS ix_historialcliente_sistema ON public.historialcliente (sistema);

-- ============================================================
--  VERIFICACIÓN FINAL
-- ============================================================
SELECT 'estadocliente'    AS tabla, COUNT(*) AS registros FROM public.estadocliente
UNION ALL
SELECT 'historialcliente' AS tabla, COUNT(*) AS registros FROM public.historialcliente;

-- Funcion para obtener los días para la consulta SELECT diasClave(?)
CREATE OR REPLACE FUNCTION public.diasclave(fecha TIMESTAMP)
RETURNS INTEGER
LANGUAGE sql
AS $$
  SELECT COALESCE(DATE_PART('day', now() - fecha)::int, 999999);
$$;

SELECT * FROM usuarios

SELECT * FROM categorias

SELECT idProducto as id,c.categoria,e.estado,m.marca,producto,precio,p.descripcion, p.imagen 
        FROM productos p
        INNER JOIN categorias c ON c.idCategoria = p.Categoria
        INNER JOIN estadoProductos e ON e.idEstado = p.estado
        INNER JOIN marcas m ON m.idMarca = p.marca
        order by c.categoria

SELECT idcategoria AS id, categoria, descripcion, imagen
                FROM categorias
                ORDER BY categoria

DROP INDEX IF EXISTS public.uq_clientes_dui;