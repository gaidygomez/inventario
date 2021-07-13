<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "sucursales";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: sucursales.php");
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['sucursal']) || empty($_POST['direccion']) || empty($_POST['contacto'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todo los campos son requeridos</div>';
    } else {
        $idsucursal = $_POST['id'];
        $sucursal = $_POST['sucursal'];
        $direccion = $_POST['direccion'];
        $contacto = $_POST['contacto'];
            $sql_update = mysqli_query($conexion, "UPDATE sucursales SET sucursal = '$sucursal' , contacto_persona = '$contacto', direccion = '$direccion' WHERE idsucursal = $idsucursal");

            if ($sql_update) {
                $alert = '<div class="alert alert-success" role="alert">Cliente Actualizado correctamente</div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">Error al Actualizar el Cliente</div>';
            }
    }
}
// Mostrar Datos

if (empty($_REQUEST['id'])) {
    header("Location: clientes.php");
}
$idsucursal = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM sucursales WHERE idsucursal = $idsucursal");
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header("Location: clientes.php");
} else {
    if ($data = mysqli_fetch_array($sql)) {
        $idsucursal = $data['idsucursal'];
        $sucursal = $data['sucursal'];
        $contacto = $data['contacto_persona'];
        $direccion = $data['direccion'];
    }
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Sucursal
                </div>
                <div class="card-body">
                    <form class="" action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>
                        <input type="hidden" name="id" value="<?php echo $idsucursal; ?>">
                        <div class="form-group">
                            <label for="nombre">Sucursal</label>
                            <input type="text" placeholder="Ingrese Nombre" name="sucursal" class="form-control" id="nombre_sucursal" value="<?php echo $sucursal; ?>">
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="number" placeholder="Ingrese Teléfono" name="contacto" class="form-control" id="contacto" value="<?php echo $contacto; ?>">
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" placeholder="Ingrese Direccion" name="direccion" class="form-control" id="direccion" value="<?php echo $direccion; ?>">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Editar Sucursal</button>
                        <a href="sucursales.php" class="btn btn-danger">Atras</a>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>