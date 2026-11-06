-- ==========================================================
-- Base de Datos: Sistema de Seguimiento de Reparaciones
-- Proyecto 4: Servicio Técnico de Computadores y Dispositivos
-- ==========================================================

CREATE DATABASE IF NOT EXISTS `taller_reparaciones`
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `taller_reparaciones`;

-- ----------------------------------------------------------
-- 1. Tabla de Usuarios (Autenticación y Roles)
-- ----------------------------------------------------------
DROP TABLE IF EXISTS `servicios`;
DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `rol` ENUM('admin', 'usuario') NOT NULL DEFAULT 'usuario',
  `creado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 2. Tabla de Servicios / Reparaciones
-- ----------------------------------------------------------
CREATE TABLE `servicios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `numero_servicio` VARCHAR(20) NOT NULL UNIQUE,
  `cliente_nombre` VARCHAR(100) NOT NULL,
  `cliente_telefono` VARCHAR(20) NOT NULL,
  `cliente_email` VARCHAR(100) NULL,
  `tipo_dispositivo` VARCHAR(50) NOT NULL,
  `marca` VARCHAR(50) NOT NULL,
  `modelo` VARCHAR(50) NULL,
  `descripcion_dano` TEXT NOT NULL,
  `diagnostico` TEXT NULL,
  `estado` ENUM('Recibido', 'En diagnóstico', 'En reparación', 'Cerrado') NOT NULL DEFAULT 'Recibido',
  `costo_estimado` DECIMAL(10,2) NULL DEFAULT 0.00,
  `tecnico_asignado` VARCHAR(100) NULL,
  `fecha_ingreso` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fecha_cierre` DATETIME NULL,
  INDEX `idx_numero_servicio` (`numero_servicio`),
  INDEX `idx_estado` (`estado`),
  INDEX `idx_cliente` (`cliente_nombre`),
  CONSTRAINT `chk_estado_valido` CHECK (`estado` IN ('Recibido', 'En diagnóstico', 'En reparación', 'Cerrado')),
  CONSTRAINT `chk_cierre_con_diagnostico` CHECK (`estado` <> 'Cerrado' OR (`diagnostico` IS NOT NULL AND CHAR_LENGTH(TRIM(`diagnostico`)) > 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Inserción de Usuarios por Defecto
-- Passwords: 
-- admin -> admin123
-- usuario -> user123
-- ----------------------------------------------------------
INSERT INTO `usuarios` (`usuario`, `password`, `nombre`, `rol`) VALUES
('admin', '$2y$10$8rSM2DCgAvddqidaIan5/.icLwTxrbrv2ehynIAiuHch/9GdOx4/S', 'Administrador Principal', 'admin'),
('usuario', '$2y$10$aYrsaK38qoe2ZApoi1vdNOMu5pJ2vRTSAxd4IkmAGMDalw2.fNDdS', 'Técnico de Soporte', 'usuario');

-- ----------------------------------------------------------
-- Inserción de Datos Iniciales de Prueba
-- ----------------------------------------------------------
INSERT INTO `servicios` (
  `numero_servicio`, `cliente_nombre`, `cliente_telefono`, `cliente_email`, 
  `tipo_dispositivo`, `marca`, `modelo`, `descripcion_dano`, 
  `diagnostico`, `estado`, `costo_estimado`, `tecnico_asignado`, 
  `fecha_ingreso`, `fecha_cierre`
) VALUES
('SRV-2026-0001', 'Carlos Mendoza', '3104567890', 'carlos.mendoza@email.com', 
 'Portátil / Laptop', 'Lenovo', 'ThinkPad T480', 'El equipo se apaga repentinamente tras 15 minutos de uso y recalienta en la base.', 
 'Sistema de refrigeración obstruido por polvo y pasta térmica solidificada. Ventilador requiere lubricación.', 'En diagnóstico', 85000.00, 'Técnico de Soporte', 
 '2026-10-25 09:30:00', NULL),

('SRV-2026-0002', 'María Camila López', '3159876543', 'mcamila.lopez@email.com', 
 'Computador de escritorio', 'Dell', 'OptiPlex 7070', 'No da video ni emite pitidos al encender. El botón de encendido parpadea en naranja.', 
 'Falla en la fuente de poder de 260W y banco de memoria RAM sulfatado. Se reemplazó la fuente y se limpiaron los contactos.', 'En reparación', 160000.00, 'Técnico de Soporte', 
 '2026-10-27 14:15:00', NULL),

('SRV-2026-0003', 'Andrés Felipe Roa', '3001234567', 'andres.roa@email.com', 
 'Smartphone', 'Samsung', 'Galaxy S21', 'Pantalla quebrada tras caída. El táctil responde intermitentemente en la zona superior.', 
 NULL, 'Recibido', 220000.00, 'Sin asignar', 
 '2026-11-01 11:00:00', NULL),

('SRV-2026-0004', 'Diana Patricia Silva', '3206549870', 'diana.silva@email.com', 
 'Tablet', 'Apple', 'iPad Air 4', 'No retiene la carga eléctrica. Se apaga al desconectar el cable de alimentación.', 
 'Batería degradada al 42% de capacidad original con ciclos excedidos. Se procedió al cambio exitoso de módulo de batería original y pruebas de estrés pasadas.', 'Cerrado', 280000.00, 'Administrador Principal', 
 '2026-10-20 10:00:00', '2026-10-24 16:45:00'),

('SRV-2026-0005', 'Registro de Prueba Test', '3000000000', 'test@prueba.com', 
 'Consola de videojuegos', 'Sony', 'PlayStation 5', 'Registro temporal creado para probar la función de eliminación del sistema.', 
 NULL, 'Recibido', 0.00, 'Sin asignar', 
 '2026-11-02 08:00:00', NULL);
