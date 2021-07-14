<?php

require_once "../conexion.php";
session_start();

$user = $_SESSION['idUser'];

if (isset($_POST['id']) && isset($_POST['qty'])) {
    echo json_encode($user);
} else {
    header('Content-type: application/json', false, 422);

    echo json_encode(['error' => 'Faltan argumentos para realizar la actualización']);
}