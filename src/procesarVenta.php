<?php
require_once "../conexion.php";
session_start();


if ($_POST['user'] != '' && $_POST['sucursal'] != '') {

	$user = $_SESSION['idUser'];
	$cliente = $_POST['user'];
	$productos = $_POST['productos'];
	$cantidades = $_POST['cantidades'];
	$total = $_POST['total'];
	$precios = $_POST['precios'];
	$sucursal = $_POST['sucursal'];

	$venta = insertVenta($cliente, $total, $user, $conexion);

	$detalles = insertDetalleVenta($productos, $venta, $cantidades, $precios, $conexion);

	$actualizar = updateStock($productos, $sucursal, $cantidades, $conexion);

	$borrar = deleteDetalles($user, $conexion);

	mysqli_close($conexion);
	
	if ($detalles && $actualizar && $borrar) {

		echo json_encode(['success' => 'La venta ha sido procesada.', 'cliente' => $cliente, 'venta' => $venta]);

	} else {

		header('Content-type: application/json', true, 500);

		echo json_encode(['error' => 'Un error ha ocurrido al tratar de almacenar la venta']);

	}
} else {
	header('Content-type: application/json', true, 422);

	echo json_encode(['error' => 'El Usuario y la Sucursal son Requeridas']);
}

function insertVenta($cliente, $total, $user, $conexion) 
{
	$query = "INSERT INTO ventas(id_cliente, total, id_usuario) VALUES($cliente, $total, $user)";
	
	mysqli_query($conexion, $query);

	$venta = mysqli_insert_id($conexion);

	return $venta;
}

function insertDetalleVenta($productos, $venta, $cantidades, $precios, $conexion) 
{
	foreach ($productos as $key => $producto) {
		$query = "INSERT INTO detalle_venta (id_producto, id_venta, cantidad, precio) VALUES ($producto, $venta, $cantidades[$key], $precios[$key])";

		$detalle = mysqli_query($conexion, $query);
	}

	return $detalle;
}

function updateStock($productos, $sucursal,$cantidades, $conexion)
{
	foreach ($productos as $key => $producto) {
		$query = "UPDATE producto_sucursales SET cantidad = cantidad - $cantidades[$key] WHERE producto_id = $producto AND sucursal_id = $sucursal";

		$state = mysqli_query($conexion, $query);
	}

	return $state;
}

function deleteDetalles($user, $conexion)
{
	$query = "DELETE FROM detalle_temp WHERE id_usuario = $user";

	$state = mysqli_query($conexion, $query);

	return $state;
}