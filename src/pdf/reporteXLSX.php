<?php

require_once "../../conexion.php";
require_once "./reportes/ventasExcel.php";
require_once "./consultas.php";

$datos = [];
$user = $_GET['user'];
$start_date = $_GET['startDate'] == '' ? new DateTime() : new DateTime($_GET['startDate']);
$end_date = $_GET['endDate'] == '' ? new DateTime() : new DateTime($_GET['endDate']);
$start = $start_date->format('Y-m-d');
$end = $end_date->format('Y-m-d');
$state = mysqli_query($conexion, "SELECT * FROM configuracion");
$config = mysqli_fetch_assoc($state);

if ($_GET['user'] == '') {
    $datos = allInvoices($conexion, $start, $end);

    echo json_encode($datos);
    //reporteVentas($datos, $config, allSumInvoices($conexion, $start, $end));
	
} elseif ($_GET['user'] != '') {

	$datos = invoicesOfUser($conexion, $start, $end, $user);

    echo json_encode($datos);
	//reporteVentas($datos, $config, allSumOfUser($conexion, $start, $end, $user));
}