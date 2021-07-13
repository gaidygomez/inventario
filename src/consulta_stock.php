<?php
include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "productos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$sucursals = mysqli_fetch_all(mysqli_query($conexion, "SELECT idsucursal, direccion, sucursal FROM sucursales"));
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
  header("Location: permisos.php");
}
if (!empty($_POST)) {
  $alert = "";
  if (empty($_POST['codigo']) || empty($_POST['producto']) || empty($_POST['precio'])) {
    $alert = '<div class="alert alert-primary" role="alert">
              Todo los campos son requeridos
            </div>';
  } else {
    $codproducto = $_GET['id'];
    $codigo = $_POST['codigo'];
    $producto = $_POST['producto'];
    $precio = $_POST['precio'];
    $query_update = mysqli_query($conexion, "UPDATE producto SET codigo = '$codigo', descripcion = '$producto', precio= $precio WHERE codproducto = $codproducto");
    if ($query_update) {
      $alert = '<div class="alert alert-primary" role="alert">
              Producto Modificado
            </div>';
    } else {
      $alert = '<div class="alert alert-primary" role="alert">
                Error al Modificar
              </div>';
    }
  }
}

// Validar producto

if (empty($_REQUEST['id'])) {
  header("Location: productos.php");
} else {
  $id_producto = $_REQUEST['id'];
  if (!is_numeric($id_producto)) {
    header("Location: productos.php");
  }
  $query_producto = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $id_producto");
  $result_producto = mysqli_num_rows($query_producto);

  if ($result_producto > 0) {
    $data_producto = mysqli_fetch_assoc($query_producto);
  } else {
    header("Location: productos.php");
  }
}
?>
<div class="row">
  <div class="col-lg-6 m-auto">

    <div class="card">
      <div class="card-header bg-primary text-white">
        <?php echo $data_producto['descripcion']; ?>
      </div>
      <div class="card-body">
        <form action="" method="post">
          <?php echo isset($alert) ? $alert : ''; ?>
          <div class="card">
                <div class="card-header">
                    Seleccione Sucursal
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <select name="sucursal" id="sucursal" class="custom-select">
                            <option value=""> </option>
                            <?php foreach ($sucursals as $key => $sucursl): ?>
                                <option value="<?= $sucursl[0] ?>"> <?= $sucursl[2] ?> </option>
                            <?php endforeach; ?>
                        </select>
                        <!-- <input id="producto" class="form-control" type="text" name="producto" placeholder="Ingresa el código o nombre"> -->
                    </div>
                </div>
                
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label>cantidad</label>
                    <input type="text" name="" id="" class="form-control" disabled required>
                </div>
            </div>
         
          <input type="submit" value="Actualizar Producto" class="btn btn-primary">
          <a href="productos.php" class="btn btn-danger">Atras</a>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once "includes/footer.php"; ?>