<?php

require_once "../conexion.php";
session_start();

$user = $_SESSION['idUser'];

if (isset($_POST['id']) && $_POST['qty'] != '') {

    $data = evaluarCantidad($conexion, $_POST['producto'], $_POST['sucursal']);
    
    if ($data['cantidad'] < $_POST['qty']) {
        header('Content-type: application/json', false, 400);

        echo json_encode(['error' => 'La cantidad excede la existencia']);
    } else {    
        $qty = "UPDATE detalle_temp SET cantidad = ? WHERE id = ?";

        $stmt = mysqli_prepare($conexion, $qty);

        mysqli_stmt_bind_param($stmt, 'ii', $_POST['qty'], $_POST['id']);

        echo json_encode(mysqli_stmt_execute($stmt));
    }

} elseif (isset($_POST['id']) && $_POST['price'] != '') {
    $price = "UPDATE detalle_temp SET precio_venta = ?, total = precio_venta * cantidad WHERE id = ?";

    $stmt = mysqli_prepare($conexion, $price);

    mysqli_stmt_bind_param($stmt, 'ii', $_POST['price'], $_POST['id']);

    echo json_encode(mysqli_stmt_execute($stmt));

} else {
    header('Content-type: application/json', false, 422);

    echo json_encode(['error' => 'Faltan argumentos para realizar la actualización']);
}

function evaluarCantidad($conexion, $id, $sucursal)
{
    $query = "SELECT cantidad FROM producto_sucursales WHERE producto_id = ? AND sucursal_id = ? LIMIT 1";

    $stmt = mysqli_prepare($conexion, $query);

    mysqli_stmt_bind_param($stmt, 'ii', $id, $sucursal);

    mysqli_stmt_execute($stmt);

    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}