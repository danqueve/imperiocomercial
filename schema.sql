CREATE DATABASE IF NOT EXISTS crm_ventas;
USE crm_ventas;

-- Tabla de Usuarios (Vendedores, Supervisores, SuperUsuario)
CREATE TABLE `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(255) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `dni` VARCHAR(20) UNIQUE NOT NULL,
  `apellido` VARCHAR(100) NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `celular` VARCHAR(50) NOT NULL,
  `domicilio` VARCHAR(255) NOT NULL,
  `localidad` VARCHAR(100) NOT NULL,
  `rol` ENUM('vendedor', 'supervisor', 'superusuario') NOT NULL DEFAULT 'vendedor',
  `fecha_registro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de Formularios de Venta
CREATE TABLE `formularios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `vendedor_id` INT NOT NULL,
  `cliente_dni` VARCHAR(20) NOT NULL,
  `cliente_apellido_nombre` VARCHAR(255) NOT NULL,
  `cliente_domicilio` VARCHAR(255) NOT NULL,
  `cliente_localidad` VARCHAR(100) NOT NULL,
  `cliente_barrio` VARCHAR(100) DEFAULT NULL,
  `cliente_whatsapp` VARCHAR(50) NOT NULL,
  `cliente_celular_llamada` VARCHAR(50) DEFAULT NULL,
  `cliente_tipo_empleo` VARCHAR(100) DEFAULT NULL,
  `cliente_domicilio_trabajo` VARCHAR(255) DEFAULT NULL,
  `cliente_de_que_trabaja` VARCHAR(255) DEFAULT NULL,
  `cliente_nombre_trabajo` VARCHAR(255) DEFAULT NULL,
  `articulo_detalles` TEXT DEFAULT NULL,
  `estado` ENUM('en revision', 'aprobado', 'rechazado') NOT NULL DEFAULT 'en revision',
  `motivo_rechazo` TEXT DEFAULT NULL,
  `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion_estado` DATETIME DEFAULT NULL,
  `supervisor_id_accion` INT DEFAULT NULL,
  FOREIGN KEY (`vendedor_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`supervisor_id_accion`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar el SuperUsuario inicial
-- Usuario: danqueve, Clave: Vera0803
-- La contraseña está 'hasheada' con password_hash() de PHP.
INSERT INTO `usuarios` (`email`, `password`, `dni`, `apellido`, `nombre`, `celular`, `domicilio`, `localidad`, `rol`)
VALUES ('danqueve', '$2y$10$8vSSgQ5yT9k3tSjRz.P3v.eEwT9Q2bE4YdJ5kL9wX7sF8oG1iO3mC', '11111111', 'Quevedo', 'Daniel', '381000000', 'Dirección de Admin', 'Tucumán', 'superusuario');

