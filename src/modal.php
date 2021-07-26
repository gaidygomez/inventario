<?php
require_once('../conexion.php');

$users = mysqli_fetch_all(mysqli_query($conexion, "SELECT idusuario, nombre, correo, usuario FROM usuario WHERE estado = 1"));

?>
<!-- Modal -->
<div class="modal fade" id="reporteVentas" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Reporte de Ventas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formVentas">
          <div class="row mb-2">
            <div class="col-md-6">
              <label for="">Fecha Desde</label>
              <input name="start_date" id="start_date" type="text" class="form-control" placeholder="Fecha Desde">
            </div>
            <div class="col-md-6">
              <label for="">Fecha Hasta</label>
              <input name="end_date" id="end_date" type="text" class="form-control" placeholder="Fecha Hasta">
            </div>
          </div>
          <div class="form-group">
            <label for="">Usuario</label>
            <select name="user" id="user" class="custom-select">
              <option value="" selected>Todos los Usuarios</option>
              <?php foreach ($users as $key => $user): ?>
                <option value="<?= $user[0] ?>"> <?= $user[3] ?> </option>
              <?php endforeach; ?>
            </select>
          </div>  
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="pdf" class="btn btn-danger"><i class="far fa-file-pdf"></i> PDF</button>
        <!-- <button type="button" id="excel" class="btn btn-success"><i class="far fa-file-excel"></i> Excel</button> -->
      </div>
    </div>
  </div>
</div>