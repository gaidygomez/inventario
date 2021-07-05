<?php
require_once "../../conexion.php";

$datos = [];
$start_date = $_GET['startDate'] == '' ? date('Y-m-d') : new DateTime($_GET['startDate']);
$end_date = $_GET['endDate'] == '' ? date('Y-m-d') : new DateTime($_GET['endDate']);

if ($_GET['user'] == '' && $_GET['startDate'] == '' && $_GET['endDate'] == '') {
	$query = "SELECT v.* FROM ventas AS v INNER JOIN usuario AS u ON v.id_usuario = u.idusuario 
		WHERE DATE(fecha) BETWEEN $start_date AND $end_date";
	
	$facturas = mysqli_fetch_all(mysqli_query($conexion, $query));

	foreach ($facturas as $key => $factura) {
		$datos[] = [
			'factura' => $factura[0],
			'total' => $factura[2],
			'fecha' => $factura[4]
		];
	}

	echo json_encode(['success' => [ 'facturas' => $datos ]]);

	/*$queryTotal = "SELECT SUM(v.total) FROM ventas AS v INNER JOIN usuario AS u ON v.id_usuario = u.idusuario 
		WHERE DATE(fecha) BETWEEN $start AND $end GROUP BY v.id_usuario";



	$total = mysqli_fetch_all(mysqli_query($conexion, $queryTotal));

	echo json_encode(['success' => [ 'facturas' => $facturas, 'total' => $total ]]);

	mysqli_close($conexion);*/

} elseif ($_GET['user'] != '' && $_GET['startDate'] == '' && $_GET['endDate'] == '') {
	echo json_encode('Datos de hoy del usuario específico');
} else {
	echo json_encode('Datos Especificos');
}
