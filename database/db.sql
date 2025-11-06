-- BASE DE DATOS DE LA GESTION DE INVENTARIO

CREATE DATABASE IF NOT EXISTS inventario_scaleup;

USE inventario_scaleup;

-- TABLA DE USUARIOS
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(100) NOT NULL,
    rol ENUM('admin', 'trabajador') NOT NULL DEFAULT 'trabajador',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    email_verificado TINYINT(1) DEFAULT 0,
    codigo_verificacion VARCHAR(6) DEFAULT NULL,
    codigo_expiracion TIMESTAMP NULL
);

-- TABLA DE CATEGORIAS
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- TABLA DE PRODUCTOS
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    categoria_id INT NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    precio DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- llave foranea
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)

);

-- TABLA DE MOVIMIENTOS
CREATE TABLE IF NOT EXISTS movimientos (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    producto_id INT NOT NULL,
    tipo_movimiento ENUM('entrada', 'salida', 'ajuste') NOT NULL,
    cantidad INT NOT NULL,
    precio_unidad DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    precio_total DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    usuario_id INT NOT NULL,
    referencia VARCHAR(100) NULL,
    notas TEXT,
    fecha_movimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Llaves foraneas 
    FOREIGN  KEY (producto_id) REFERENCES productos(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)

 );    

-- VISTAS

-- Vista para obtener datos de movimientos
CREATE VIEW vista_datos_movimientos AS
SELECT 
    m.id,
    m.tipo_movimiento,
    m.cantidad,
    m.precio_unidad,
    m.precio_total,
    m.referencia,
    m.notas,
    m.fecha_movimiento,
    p.id as producto_id,
    p.nombre as producto_nombre,
    c.nombre as categoria_nombre,
    u.nombre as nombre_usuario,
    u.email as email_usuario
FROM movimientos m
LEFT JOIN productos p ON m.producto_id = p.id
LEFT JOIN categorias c ON p.categoria_id = c.id
LEFT JOIN usuarios u ON m.usuario_id = u.id
ORDER BY m.fecha_movimiento DESC;

-- Vista para resumen de movimientos por producto
CREATE VIEW vista_resumen_movimientos_producto AS
SELECT 
    p.id as producto_id,
    p.nombre as producto_nombre,
    p.stock as stock_actual,
    COALESCE(SUM(CASE WHEN m.tipo_movimiento = 'entrada' THEN m.cantidad ELSE 0 END), 0) as total_entradas,
    COALESCE(SUM(CASE WHEN m.tipo_movimiento = 'salida' THEN m.cantidad ELSE 0 END), 0) as total_salidas,
    COALESCE(SUM(CASE WHEN m.tipo_movimiento = 'ajuste' THEN m.cantidad ELSE 0 END), 0) as total_ajustes,
    COALESCE(SUM(CASE WHEN m.tipo_movimiento = 'entrada' THEN m.precio_total ELSE 0 END), 0) as valor_entradas,
    COALESCE(SUM(CASE WHEN m.tipo_movimiento = 'salida' THEN m.precio_total ELSE 0 END), 0) as valor_salidas,
    COUNT(m.id) as total_movimientos,
    MAX(m.fecha_movimiento) as ultimo_movimiento
FROM productos p
LEFT JOIN movimientos m ON p.id = m.producto_id
GROUP BY p.id, p.nombre, p.stock;


-- TRIGGERS

-- Trigger para validar stock no negativo en los productos
DELIMITER //
CREATE TRIGGER antes_de_actualizar_stock
BEFORE UPDATE ON productos
FOR EACH ROW
BEGIN
    IF NEW.stock < 0 THEN  
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El stock no puede ser negativo';
    END IF;

    IF NEW.precio < 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El precio no puede ser negativo';
    END IF;
DELIMITER ;

-- Trigger para actualizar el stock cuando se inserta un nuevo movimiento
DELIMITER //
CREATE TRIGGER despues_de_insertar_movimiento
AFTER INSERT ON movimientos
FOR EACH ROW
BEGIN  
    IF NEW.tipo_movimiento = 'entrada' THEN
        UPDATE productos
        SET stock = stock + NEW.cantidad, updated_at = CURRENT_TIMESTAMP
        WHERE id = New.producto_id;
    ELSEIF NEW.tipo_movimiento = 'salida' THEN
        UPDATE productos 
        SET stock = stock - NEW.cantidad, updated_at = CURRENT_TIMESTAMP
        WHERE id = New.producto_id;
    ELSEIF NEW.tipo_movimiento = 'ajuste' THEN
        UPDATE productos
        SET stock = stock + NEW.cantidad, updated_at = CURRENT_TIMESTAMP
        WHERE id = NEW.producto_id;
    END IF;
END //
DELIMITER ;



