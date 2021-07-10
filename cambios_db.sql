-- inventario.producto_sucursales definition

CREATE TABLE `producto_sucursales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `producto_id` bigint(20) unsigned NOT NULL,
  `sucursal_id` bigint(20) unsigned NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha_añadido` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizado` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE inventario.producto DROP COLUMN existencia;
