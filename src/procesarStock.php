<?php
require_once "../conexion.php";

if (! isset($_POST['data'])) {
	header('Content-type: application/json', false, 422);

	echo json_encode(['error' => 'No se han enviado Productos']);
} else {
	$productos = json_decode($_POST['data']);

	$status = createOrUpdate($conexion, $productos);

	if ($status) {
		echo json_encode(['success' => 'Los Productos han sido Añadidos']);
	} else {
		header('Content-type: application/json', false, 500);

		echo json_encode(['error' => 'Un error ha ocurrido']);
	}
}

function createOrUpdate($conexion, $productos) {
	$exists = "SELECT * FROM producto_sucursales WHERE producto_id = ? AND sucursal_id = ?";
	
	$update = "UPDATE producto_sucursales SET cantidad = cantidad + ? WHERE producto_id = ? AND sucursal_id = ? ";

	$crear = "INSERT INTO producto_sucursales(producto_id, sucursal_id, cantidad) VALUES (?, ?, ?)";

	$stmt = mysqli_prepare($conexion, $exists);

	$stmtUpdate = mysqli_prepare($conexion, $update);

	$stmtCreate = mysqli_prepare($conexion, $crear);

	foreach ($productos as $key => $producto) {
		mysqli_stmt_bind_param($stmt, 'ii', $producto->idproducto, $producto->idsucursal);
		
		mysqli_stmt_execute($stmt);

		$status = mysqli_stmt_fetch($stmt);

		mysqli_stmt_free_result($stmt);

		if ($status) {
			mysqli_stmt_bind_param($stmtUpdate , 'iii', $producto->qty, $producto->idproducto, $producto->idsucursal);

			$state = mysqli_stmt_execute($stmtUpdate);

			mysqli_stmt_free_result($stmtUpdate);
		} else {
			mysqli_stmt_bind_param($stmtCreate, 'iii', $producto->idproducto, $producto->idsucursal, $producto->qty);

			$state = mysqli_stmt_execute($stmtCreate);

			mysqli_stmt_free_result($stmtCreate);
		}
		
	}

	return $state;
}
