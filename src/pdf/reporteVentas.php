<?php
require_once "../../conexion.php";
require_once "./reportes/ventas.php";

$datos = [];
$start_date = $_GET['startDate'] == '' ? new DateTime() : new DateTime($_GET['startDate']);
$end_date = $_GET['endDate'] == '' ? new DateTime() : new DateTime($_GET['endDate']);
$start = $start_date->format('Y-m-d');
$end = $end_date->format('Y-m-d');
$state = mysqli_query($conexion, "SELECT * FROM configuracion");
$config = mysqli_fetch_assoc($state);

if ($_GET['user'] == '' && $_GET['startDate'] == '' && $_GET['endDate'] == '') {

	$datos = allInvoices($conexion, $start, $end);

	reporteVentas($datos, $config, allSumInvoices($conexion, $start, $end));
	
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
	$query = "SELECT SUM(v.total) as total_vendido, v.id_usuario 
	FROM inventario.ventas AS v 
		INNER JOIN inventario.usuario AS u 
			ON v.id_usuario = u.idusuario
		INNER JOIN inventario.cliente as c
			ON v.id_cliente = c.idcliente 
	WHERE DATE(fecha) BETWEEN ? AND ?
	GROUP BY v.id_usuario";

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
	$query = "SELECT SUM(v.total) as total 
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

	$total = mysqli_fetch_assoc($datos);

	return $total['total'];

}