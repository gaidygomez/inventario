<?php
require_once "../../conexion.php";

$datos = [];
$start_date = $_GET['startDate'] == '' ? new DateTime() : new DateTime($_GET['startDate']);
$end_date = $_GET['endDate'] == '' ? new DateTime() : new DateTime($_GET['endDate']);

if ($_GET['user'] == '' && $_GET['startDate'] == '' && $_GET['endDate'] == '') {
	$start = $start_date->format('Y-m-d');
	$end = $end_date->format('Y-m-d');

	$datos = allInvoices($conexion, $start, $end);

	echo json_encode($datos);
} elseif ($_GET['user'] != '' && $_GET['startDate'] == '' && $_GET['endDate'] == '') {
	echo json_encode('Datos de hoy del usuario específico');
} else {
	echo json_encode('Datos Especificos');
}

function allInvoices($conexion, $start, $end) {

	$query = "SELECT v.*, u.nombre as usuario, u.correo, c.nom, c.nombre as cliente, c.telefono 
		FROM inventario.ventas AS v 
			INNER JOIN inventario.usuario AS u 
				ON v.id_usuario = u.idusuario
			INNER JOIN inventario.cliente as c
				ON v.id_cliente = c.idcliente 
		WHERE DATE(fecha) BETWEEN ? AND ?";
	
	$stmt = mysqli_prepare($conexion ,$query);

	mysqli_stmt_bind_param($stmt, 'ss', $start, $end);

	mysqli_stmt_execute($stmt);

	$datos = mysqli_stmt_get_result($stmt);

	$facturas = mysqli_fetch_all($datos);

	mysqli_close($conexion);

	$facturas = array_map(function($factura) {
		return [
			'factura' => $factura[0],
			'total' => $factura[2],
			'fecha' => $factura[4],
			'usuario' => $factura[5],
			'usuario_correo' => $factura[6],
			'cliente_nombre' => $factura[7],
			'cliente_dni' => $factura[8],
			'cliente_telefono' => $factura[9] 
		];
	}, $facturas);

	return [ 'facturas' => $facturas ];
}

function allSumInvoices($conexion, $start, $end) {
	$query = "SELECT SUM(v.total) 
	FROM inventario.ventas AS v 
		INNER JOIN inventario.usuario AS u 
			ON v.id_usuario = u.idusuario
		INNER JOIN inventario.cliente as c
			ON v.id_cliente = c.idcliente 
	WHERE DATE(fecha) BETWEEN ? AND ?
	GROUP BY v.id_usuario";
} 