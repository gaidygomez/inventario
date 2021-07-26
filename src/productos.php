<?php 
ob_start();

include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "productos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);

$sucursales = mysqli_fetch_all(mysqli_query($conexion, "SELECT idsucursal, sucursal FROM sucursales"));

if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
    if (!empty($_POST)) {
        $alert = "";
        if (empty($_POST['codigo']) || empty($_POST['producto']) || empty($_POST['precio']) || $_POST['precio'] <  0 || empty($_POST['preciocom']) || $_POST['preciocom'] <  0 || empty($_POST['sabor'])) {
            $alert = '<div class="alert alert-danger" role="alert">
                Todo los campos son obligatorios
              </div>';
        } else {
            $codigo = $_POST['codigo'];
            $producto = $_POST['producto'];
            $preciocom = $_POST['preciocom'];
            $precio = $_POST['precio'];
            $sabor = $_POST['sabor'];
            $usuario_id = $_SESSION['idUser'];
            $query = mysqli_query($conexion, "SELECT * FROM producto WHERE codigo = '$codigo'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning" role="alert">
                        El c車digo ya existe
                    </div>';
            } else {
                $query_insert = mysqli_query($conexion, "INSERT INTO producto(codigo,descripcion,preciocom,sabor,precio,usuario_id) values ('$codigo', '$producto', '$preciocom','$sabor', '$precio', '$usuario_id')");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success" role="alert">
                Producto Registrado
              </div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar el producto
              </div>';
                }
            }
        }
    }
    ?>

    <div class="d-flex justify-content-between my-3">
        <div class="my-auto">
             <button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_producto"><i class="fas fa-plus"></i></button>
             <?php echo isset($alert) ? $alert : ''; ?>
        </div>

     <div class="form-group">
        <label for="">Sucursales</label>
         <select class="custom-select" name="sucursal" id="select-products">
            <option value=""></option>
            <?php foreach($sucursales as $key => $sucursal): ?>
                <option value="<?= $sucursal[0]; ?>"> <?= $sucursal[1]; ?> </option>
            <?php endforeach; ?>
         </select>
     </div>
    </div>

    <div class="mb-3" id="reportSucursal" style="display: none;">
        <div>
            <button class="btn btn-danger" type="button">
                <i class="far fa-file-pdf"></i>
                <span class="mx-1">Sucursal</span>
            </button>
        </div>
    </div>
 <div class="table-responsive">
 
     <table class="table table-striped table-bordered" id="table-productos">
         <thead class="thead-dark">
             <tr>
                 <th>#</th>
                 <th>Código</th>
                 <th>Producto</th>
                 <th>Precio comp.</th>
                 <th>Precio</th>
                 <th>Sabor</th>
                 <th>Estado</th>
                 <th style="width: 250px;"></th>
             </tr>
         </thead>
         <tbody>
            <td colspan="8" class="text-center" id="info-table">
                Escoja una sucursal
            </td>
         </tbody>

     </table>
 </div>
 
 <div id="nuevo_producto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
     <div class="modal-dialog" role="document">
     
         <div class="modal-content">
             <div class="modal-header bg-primary text-white">
                 <h5 class="modal-title" id="my-modal-title">Nuevo Producto</h5>
                 
                 <button class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
                 
             </div>
             <div class="modal-body">
                 <form action="" method="post" autocomplete="off">
                     <?php echo isset($alert) ? $alert : ''; ?>
                     <div class="form-group">
                         <label for="codigo">Código de Barras</label>
                         <input type="text" placeholder="Ingrese código de barras" name="codigo" id="codigo" class="form-control">
                     </div>
                     <div class="form-group">
                         <label for="producto">Producto</label>
                         <input type="text" placeholder="Ingrese nombre del producto" name="producto" id="producto" class="form-control">
                     </div>
                     <div class="form-group">
                         <label for="producto">Sabor</label>
                         <input type="text" placeholder="Ingrese el sabor del producto" name="sabor" id="sabor" class="form-control">
                     </div>

                     <div class="form-group">
                         <label for="precio">Precio de compra</label>
                         <input type="text" placeholder="Ingrese precio" class="form-control" name="preciocom" id="preciocom">
                     </div>
                     <div class="form-group">
                         <label for="precio">Precio venta</label>
                         <input type="text" placeholder="Ingrese precio" class="form-control" name="precio" id="precio">
                     </div>
                     
                     <input type="submit" value="Guardar Producto" class="btn btn-primary">
                 </form>
             </div>
         </div>
     </div>
 </div>

 <?php include_once "includes/footer.php"; ob_end_flush(); ?>