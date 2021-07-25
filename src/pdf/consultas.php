<?php

require_once "../../conexion.php";

function allInvoices($conexion, $start, $end) {

	$query = queryAllInvoices();
	
	$stmt = mysqli_prepare($conexion ,$query);

	mysqli_stmt_bind_param($stmt, 'ss', $start, $end);

	mysqli_stmt_execute($stmt);

	$datos = mysqli_stmt_get_result($stmt);

	$facturas = mysqli_fetch_all($datos);

	$total_ventas = allSumInvoicesForUser($conexion, $start, $end);

	foreach ($facturas as $key => $factura) {
		$data[] = [
			'factura' => $factura[0],
			'total' => $factura[2],
			'fecha' => $factura[4],
			'id_usuario' => $factura[3],
			'usuario' => $factura[5],
			'usuario_correo' => $factura[6],
			'cliente_nombre' => $factura[7],
			'cliente_dni' => $factura[8],
			'cliente_telefono' => $factura[9],
			'total_vendido' => $total_ventas
		];
	}
	
	return $data;
}

function allSumInvoicesForUser($conexion, $start, $end) {
	$query = queryAllSumForUser()." GROUP BY v.id_usuario";

	$stmt = mysqli_prepare($conexion ,$query);

	mysqli_stmt_bind_param($stmt, 'ss', $start, $end);

	mysqli_stmt_execute($stmt);

	$datos = mysqli_stmt_get_result($stmt);

	$totales = mysqli_fetch_all($datos);

	return array_map(function ($data) {
		return [
			'id' => $data[1],
			'total' => $data[0]
		];
	}, $totales);
}

function allSumInvoices($conexion, $start, $end) {
	$query = allSum();

	$stmt = mysqli_prepare($conexion ,$query);

	mysqli_stmt_bind_param($stmt, 'ss', $start, $end);

	mysqli_stmt_execute($stmt);

	$datos = mysqli_stmt_get_result($stmt);

	$total = mysqli_fetch_assoc($datos);

	return $total['total'];

}

function queryAllInvoices() {
	$query = "SELECT v.*, u.nombre as usuario, u.correo, c.nom, c.nombre as cliente, c.telefono 
	FROM ventas AS v 
		INNER JOIN usuario AS u 
			ON v.id_usuario = u.idusuario
		INNER JOIN cliente as c
			ON v.id_cliente = c.idcliente 
	WHERE DATE(fecha) BETWEEN ? AND ?";

	return $query;
}

function queryAllSumForUser() {
	$query = "SELECT SUM(v.total) as total_vendido, v.id_usuario 
	FROM ventas AS v 
		INNER JOIN usuario AS u 
			ON v.id_usuario = u.idusuario
		INNER JOIN cliente as c
			ON v.id_cliente = c.idcliente 
	WHERE DATE(fecha) BETWEEN ? AND ?";

	return $query;
}

function allSum() {
	$query = "SELECT SUM(v.total) as total 
	FROM ventas AS v 
		INNER JOIN usuario AS u 
			ON v.id_usuario = u.idusuario
		INNER JOIN cliente as c
			ON v.id_cliente = c.idcliente 
	WHERE DATE(fecha) BETWEEN ? AND ?";

	return $query;
}

function allInvoicesOfUser() {
	$query = queryAllInvoices()." AND u.idusuario = ?";

	return $query;
}

function invoicesOfUser($conexion, $start, $end, $user) {
	$query = allInvoicesOfUser();

	$stmt = mysqli_prepare($conexion ,$query);

	mysqli_stmt_bind_param($stmt, 'ssi', $start, $end, $user);

	mysqli_stmt_execute($stmt);

	$datos = mysqli_stmt_get_result($stmt);

	$facturas = mysqli_fetch_all($datos);

	foreach ($facturas as $key => $factura) {
		$data[] = [
			'factura' => $factura[0],
			'total' => $factura[2],
			'fecha' => $factura[4],
			'id_usuario' => $factura[3],
			'usuario' => $factura[5],
			'usuario_correo' => $factura[6],
			'cliente_nombre' => $factura[7],
			'cliente_dni' => $factura[8],
			'cliente_telefono' => $factura[9],
		];
	}
	
	return $data;
}

function querySumOfUser() {
	$query = allSum()." AND u.idusuario = ?";

	return $query;
}

function allSumOfUser($conexion, $start, $end, $user) {
	$query = querySumOfUser();

	$stmt = mysqli_prepare($conexion ,$query);

	mysqli_stmt_bind_param($stmt, 'ssi', $start, $end, $user);

	mysqli_stmt_execute($stmt);

	$datos = mysqli_stmt_get_result($stmt);

	$total = mysqli_fetch_assoc($datos);

	return $total['total'];
}