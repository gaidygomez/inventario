<?php
ob_start();

require("../conexion.php");

if (empty($_GET['id']) && empty($_GET['sucursal'])) {
    header("Location: permisos.php");
} else {
    $id = $_GET['id'];
    
    $query_delete = mysqli_query($conexion, "DELETE FROM producto WHERE codproducto = $id");

    $query_sucursal = mysqli_query($conexion, "DELETE FROM producto_sucursales WHERE producto_id = $id");

    mysqli_close($conexion);

    header("Location: productos.php");
}

ob_end_flush();