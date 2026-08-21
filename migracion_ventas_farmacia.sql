-- ============================================================
-- migracion_ventas_farmacia.sql
-- Agrega la tabla "ventas_farmacia" para registrar cada venta
-- de medicamento y poder llevar historial de existencias.
-- Ejecuta este script UNA SOLA VEZ sobre tu base vetpets_db.
-- ============================================================

USE vetpets_db;

CREATE TABLE IF NOT EXISTS ventas_farmacia (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    id_medicamento INT NOT NULL,
    cantidad_vendida INT NOT NULL,
    fecha_venta DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_venta_medicamento
        FOREIGN KEY (id_medicamento) REFERENCES farmacia(id_medicamento)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;
