
-- Create the database
CREATE DATABASE IF NOT EXISTS `contador`;
USE `contador`;

-- Table: usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table: categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(255) NOT NULL,
  `precio` DECIMAL(10, 2) NOT NULL,
  `borrado_logico` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table: espacios_trabajo
CREATE TABLE IF NOT EXISTS `espacios_trabajo` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(255) NOT NULL,
  `descripcion` TEXT,
  `estado` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  `color_fondo` VARCHAR(7) DEFAULT '#FFFFFF',
  `categoria_id` INT,
  `imagen` BLOB,
  `borrado_logico` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`categoria_id`) REFERENCES `categorias`(`id`) ON DELETE SET NULL
);

-- Table: historial_alquiler
CREATE TABLE IF NOT EXISTS `historial_alquiler` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `espacio_id` INT NOT NULL,
  `usuario_id` INT NOT NULL,
  `fecha_inicio` DATETIME NOT NULL,
  `fecha_fin` DATETIME,
  `tiempo_uso` DECIMAL(10, 2) NOT NULL,
  `precio` DECIMAL(10, 2),
  `fecha_alquiler` DATE NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`espacio_id`) REFERENCES `espacios_trabajo`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
);
