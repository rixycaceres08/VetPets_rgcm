-- ============================================================
-- Base de datos: vetpets_db (VERSIÓN COMPLETA Y DEFINITIVA)
-- Sistema: VetPets Rixy Cáceres - Dashboard Administrativo
-- Autora: Rixy Gisselle Cáceres Moncada
--
-- ⚠️ ADVERTENCIA: Este script BORRA las tablas existentes de
-- vetpets_db y las vuelve a crear desde cero con la estructura
-- final correcta. Si tienes datos importantes guardados, haz un
-- respaldo antes (phpMyAdmin → Exportar) porque se perderán.
--
-- Úsalo para dejar la base de datos 100% correcta de una vez,
-- sin depender de migraciones anteriores.
-- ============================================================

CREATE DATABASE IF NOT EXISTS vetpets_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_spanish_ci;

USE vetpets_db;

-- ------------------------------------------------------------
-- Elimina las tablas existentes (en orden seguro por las
-- llaves foráneas: primero las tablas "hijas", al final las
-- tablas "padre").
-- ------------------------------------------------------------
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS cirugias;
DROP TABLE IF EXISTS vacunas;
DROP TABLE IF EXISTS facturas;
DROP TABLE IF EXISTS pacientes;
DROP TABLE IF EXISTS propietarios;
DROP TABLE IF EXISTS farmacia;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS clinica_config;

SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------------------
-- Tabla: propietarios
-- ------------------------------------------------------------
CREATE TABLE propietarios (
    id_propietario INT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(20) NOT NULL UNIQUE,
    nombre_completo VARCHAR(120) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(120),
    direccion VARCHAR(200),
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabla: pacientes (mascotas) — estructura final completa
-- ------------------------------------------------------------
CREATE TABLE pacientes (
    id_paciente INT AUTO_INCREMENT PRIMARY KEY,
    nombre_mascota VARCHAR(100) NOT NULL,
    especie VARCHAR(50) NOT NULL,
    raza VARCHAR(80),
    edad VARCHAR(50),
    peso DECIMAL(5,2),
    color_pelaje VARCHAR(80),
    fecha_ingreso DATE,
    estado ENUM('Activo','Inactivo','Cancelado') NOT NULL DEFAULT 'Activo',
    alergias TEXT,
    observaciones_generales TEXT,
    dni_propietario VARCHAR(20) NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_paciente_propietario
        FOREIGN KEY (dni_propietario) REFERENCES propietarios(dni)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabla: cirugias
-- ------------------------------------------------------------
CREATE TABLE cirugias (
    id_cirugia INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT NOT NULL,
    tipo_cirugia VARCHAR(120),
    fecha_programada DATETIME,
    estado ENUM('Programada','Realizada','Cancelada') DEFAULT 'Programada',
    seguimiento_postoperatorio TEXT,
    CONSTRAINT fk_cirugia_paciente
        FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabla: vacunas
-- ------------------------------------------------------------
CREATE TABLE vacunas (
    id_vacuna INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT NOT NULL,
    nombre_vacuna VARCHAR(100) NOT NULL,
    fecha_aplicacion DATE,
    proxima_dosis DATE,
    CONSTRAINT fk_vacuna_paciente
        FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabla: farmacia (inventario de medicamentos)
-- ------------------------------------------------------------
CREATE TABLE farmacia (
    id_medicamento INT AUTO_INCREMENT PRIMARY KEY,
    nombre_medicamento VARCHAR(120) NOT NULL,
    cantidad_stock INT DEFAULT 0,
    fecha_vencimiento DATE,
    fecha_ingreso DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabla: facturas
-- ------------------------------------------------------------
CREATE TABLE facturas (
    id_factura INT AUTO_INCREMENT PRIMARY KEY,
    dni_propietario VARCHAR(20) NOT NULL,
    monto_total DECIMAL(10,2) NOT NULL,
    estado_pago ENUM('Pagada','Pendiente') DEFAULT 'Pendiente',
    fecha_emision DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_factura_propietario
        FOREIGN KEY (dni_propietario) REFERENCES propietarios(dni)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabla: usuarios (usuarios del sistema / administradores)
-- ------------------------------------------------------------
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(80) NOT NULL,
    correo VARCHAR(120) NOT NULL UNIQUE,
    contrasena_hash VARCHAR(255) NOT NULL,
    rol ENUM('Administrador','Veterinario','Recepcion') DEFAULT 'Recepcion',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabla: clinica_config (Datos de la Clínica)
-- ------------------------------------------------------------
CREATE TABLE clinica_config (
    id_config INT AUTO_INCREMENT PRIMARY KEY,
    nombre_clinica VARCHAR(120) NOT NULL,
    direccion VARCHAR(200),
    telefono VARCHAR(20),
    correo VARCHAR(120),
    horario_atencion VARCHAR(150)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Datos iniciales
-- ------------------------------------------------------------
INSERT INTO clinica_config (nombre_clinica, direccion, telefono, correo, horario_atencion)
VALUES ('VetPets Rixy Cáceres', 'Tegucigalpa, Honduras', '0000-0000',
        'contacto@vetpets.com', 'Lunes a sábado, 8:00am - 6:00pm');

-- Usuario administrador de ejemplo
-- (la contraseña real debe generarse con password_hash() en PHP,
--  el hash de abajo es solo un marcador de posición)
INSERT INTO usuarios (nombre_usuario, correo, contrasena_hash, rol)
VALUES ('Rixy Cáceres', 'rixy.caceres@vetpets.com', '$2y$10$ejemploHashReemplazar', 'Administrador');

-- ------------------------------------------------------------
-- Verificación final: confirma que la tabla pacientes quedó
-- con todas las columnas correctas.
-- ------------------------------------------------------------
DESCRIBE pacientes;
