<?php
ob_start();

require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "usuarios";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $sucursal = $_GET['sucursal'];
    
    $query_delete = mysqli_query($conexion, "DELETE FROM producto WHERE codproducto = $id");

    $query_sucursal = mysqli_query($conexion, "DELETE FROM producto_sucursales WHERE producto_id = $id AND sucursal_id = $sucursal");

    mysqli_close($conexion);

    header("Location: productos.php");
}

ob_end_flush();