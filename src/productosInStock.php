<?php

require_once '../conexion.php';

if ($_POST['sucursal'] != '' && isset($_POST['producto'])) {
	$query = "SELECT IFNULL(SUM(ps.cantidad), 0) as total 
	FROM producto p INNER JOIN producto_sucursales ps 
		ON ps.producto_id = p.codproducto 
	WHERE ps.sucursal_id = ?
	AND ps.producto_id = ?";

	$stmt = mysqli_prepare($conexion, $query);

	mysqli_stmt_bind_param($stmt, 'ii', $_POST['sucursal'], $_POST['producto']);

	mysqli_stmt_execute($stmt);

	echo json_encode(mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)));

} else {
	header('Content-type: application/json', false, 422);

	echo json_encode(['error' => 'La Sucursal es Requerida.']);
}
