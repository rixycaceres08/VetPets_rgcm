-- ============================================================
-- migracion_stock_farmacia.sql
-- Renombra la columna "cantidad_stock" a "stock" en la tabla
-- farmacia, para unificar el nombre del campo en todo el sistema.
-- Ejecuta este script UNA SOLA VEZ sobre tu base vetpets_db.
-- ============================================================

USE vetpets_db;

ALTER TABLE farmacia
    CHANGE COLUMN cantidad_stock stock INT DEFAULT 0;
