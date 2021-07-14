<?php

require_once "../conexion.php";
session_start();

$user = $_SESSION['idUser'];

if (isset($_POST['id']) && isset($_POST['qty'])) {
    $qty = "UPDATE detalle_temp SET cantidad = ? WHERE id = ?";

    $stmt = mysqli_prepare($conexion, $qty);

    mysqli_stmt_bind_param($stmt, 'ii', $_POST['qty'], $_POST['id']);

    echo json_encode(mysqli_stmt_execute($stmt));

} elseif (isset($_POST['id']) && isset($_POST['price'])) {
    $price = "UPDATE detalle_temp SET precio_venta = ?, total = precio_venta * cantidad WHERE id = ?";

    $stmt = mysqli_prepare($conexion, $price);

    mysqli_stmt_bind_param($stmt, 'ii', $_POST['price'], $_POST['id']);

    echo json_encode(mysqli_stmt_execute($stmt));

} else {
    header('Content-type: application/json', false, 422);

    echo json_encode(['error' => 'Faltan argumentos para realizar la actualización']);
}