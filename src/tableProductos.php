<?php

require_once "../conexion.php";

if ($_GET['sucursal'] !== '') {

	$query = "SELECT p.* FROM producto AS p 
		INNER JOIN producto_sucursales AS ps 
			ON ps.producto_id = p.codproducto 
		WHERE ps.sucursal_id = ?";

	$stmt = mysqli_prepare($conexion, $query);

	mysqli_stmt_bind_param($stmt, 'i', $_GET['sucursal']);

	mysqli_stmt_execute($stmt);

	$productos = array_map(function ($producto) {
		return [
			'id' => $producto[0],
			'codigo' => $producto[1],
			'descripcion' => $producto[2],
			'compra' => $producto[3],
			'precio' => $producto[4],
			'sabor' => $producto[5],
			'estado' => $producto[7]
		];
	}, mysqli_fetch_all(mysqli_stmt_get_result($stmt)));

	echo json_encode(['data' => $productos]);
}