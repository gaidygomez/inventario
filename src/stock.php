<?php include_once "includes/header.php";
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "nueva_venta";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$sucursals = mysqli_fetch_all(mysqli_query($conexion, "SELECT idsucursal, direccion, sucursal FROM sucursales"));
$products = mysqli_fetch_all(mysqli_query($conexion, "SELECT codproducto, codigo, descripcion FROM producto"));
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
?>
<div class="row">
    <div class="col-lg-12">
      <!--  <div class="form-group">
            <h4 class="text-center">Añadir nuevo stock</h4>
        </div>-->
        <!--<div class="card">
            <div class="card-body">
                <form method="post">
                    <div class="row">
                        <div class="col-lg-4">
                            <div>
                                <input type="hidden" id="idcliente" name="idcliente" required>
                                <label>Nombre</label>
                                <input type="text" name="nom_cliente" id="nom_cliente" class="form-control" placeholder="Ingrese nombre del cliente" required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="number" name="tel_cliente" id="tel_cliente" class="form-control" disabled required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Dirreción</label>
                                <input type="text" name="dir_cliente" id="dir_cliente" class="form-control" disabled required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>-->
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                Añadir stock
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><i class="fas fa-user"></i> VENDEDOR</label>
                            <p style="font-size: 16px; text-transform: uppercase; color: red;"><?php echo $_SESSION['nombre']; ?></p>
                        </div>
                    </div>
                    <div class="col-lg-6">
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
                        <div class="card">
                            <div class="card-header">
                                seleccione producto
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <select name="producto" id="producto" class="custom-select">
                                        <option value=""> </option>
                                        <?php foreach ($products as $key => $product): ?>
                                            <option value="<?= $product[0] ?>"> <?= $product[2] ?> </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <!-- <input id="producto" class="form-control" type="text" name="producto" placeholder="Ingresa el código o nombre"> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" id="tblDetalle">
                <thead class="thead-dark">
                    <tr>
                        <th>Id</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody id="detalle_venta">

                </tbody>
                <tfoot>
                    <tr class="font-weight-bold">
                        <td colspan=3>Descripción</td>
                        <td id="total"></td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>
    <div class="col-md-6">
        <a href="#" class="btn btn-primary" id="btn_generar"><i class="fas fa-save"></i> Añadir stock a sucursal</a>
    </div>

</div>
<?php include_once "includes/footer.php"; ?>