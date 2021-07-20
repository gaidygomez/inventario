<?php

include_once "../conexion.php";
session_start();

if ($_GET['sucursal'] != '' && $_GET['producto'] != '') {
	$query = "SELECT cantidad FROM producto_sucursales WHERE sucursal_id = ? AND producto_id = ?";

	$stmt = mysqli_prepare($conexion, $query);

	mysqli_stmt_bind_param($stmt, 'ii', $_GET['sucursal'], $_GET['producto']);

	mysqli_stmt_execute($stmt);

	$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

	if ($data['cantidad'] > 0) {
		echo json_encode(['success' => 'Hay Productos']);
	} else {
		header('Content-type: application/json', false, 422);

		echo json_encode(['error' => 'La Sucursal No Posee Productos']);
	}
	
}