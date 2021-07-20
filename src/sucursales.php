<?php 
ob_start();

include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "sucursales"; //añadir permiso a sucursal
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['sucursal']) || empty($_POST['direccion']) ||  empty($_POST['contacto_persona'])) {
        $alert = '<div class="alert alert-danger" role="alert">
                                    Todo los campos son obligatorio
                                </div>';
    } else {
        $sucursal = $_POST['sucursal'];
        $direccion = $_POST['direccion'];
        $contacto_persona = $_POST['contacto_persona'];
        

        $result = 0;
        $query = mysqli_query($conexion, "SELECT * FROM sucursales WHERE sucursal = '$sucursal'");
        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $alert = '<div class="alert alert-danger" role="alert">
                                    ya existe
                                </div>';
        } else {
            $query_insert = mysqli_query($conexion, "INSERT INTO sucursales(sucursal,direccion,contacto_persona) values ('$sucursal','$direccion','$contacto_persona')");
            if ($query_insert) {
                $alert = '<div class="alert alert-success" role="alert">
                                    sucursal registrado
                                </div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
                                    Error al registrar
                            </div>';
            }
        }
    }
    mysqli_close($conexion);
}
?>
<button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nueva_sucursal"><i class="fas fa-user-plus"></i> Nuevo sucursal </button>
<?php echo isset($alert) ? $alert : ''; ?>
<div class="table-responsive">
    <table class="table table-striped table-bordered" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Sucursal</th>
                <th>Direccion</th>
                <th>Contacto</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT * FROM sucursales");  //bien
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
                    if ($data['estado'] == 1) {
                        $estado = '<span class="badge badge-pill badge-success">Activo</span>';
                    } else {
                        $estado = '<span class="badge badge-pill badge-danger">Inactivo</span>';
                    }
            ?>
                    <tr>
                        <td><?php echo $data['idsucursal']; ?></td>
                        <td><?php echo $data['sucursal']; ?></td>
                        <td><?php echo $data['direccion']; ?></td>
                        <td><?php echo $data['contacto_persona']; ?></td>
                        <td><?php echo $estado; ?></td>
                        <td>
                            <?php if ($data['estado'] == 1) { ?>
                                <a href="editar_sucursal.php?id=<?php echo $data['idsucursal']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
                                <form action="elimina_sucursal.php?id=<?php echo $data['idsucursal']; ?>" method="post" class="confirmar d-inline">
                                    <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>

    </table>
</div>
<div id="nueva_sucursal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nueva Sucursal</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="nombre">Sucursal</label>
                        <input type="text" placeholder="Ingrese sucursal" name="sucursal" id="sucursal" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Direccion</label>
                        <input type="text" placeholder="Ingrese direccion sucursal" name="direccion" id="direccion" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="number" placeholder="Ingrese Teléfono" name="contacto_persona" id="contacto_persona" class="form-control">
                    </div>
                    <input type="submit" value="Guardar Sucursal" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ob_end_flush(); ?>


