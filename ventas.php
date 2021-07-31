<?php

include_once "config.php";
include_once "entidades/venta.php";

$pag = "Listado de ventas";
$venta = new Venta();
$aVentas = $venta->obtenerTodas();

if(isset($_GET["msj"])) {
  if($_GET["msj"] == "insertar") {
    $mensaje = "¡Se ha insertado el registro de la venta exitosamente!";
  } elseif($_GET["msj"] == "actualizar") {
    $mensaje = "¡Se ha actualizado el registro de la venta exitosamente!";
  } elseif($_GET["msj"] == "eliminar") {
    $mensaje = "¡Se ha eliminado el registro de la venta exitosamente!";
  }
}

include_once "header.php";

?>

  <!-- Inicio del container-fluid -->
  <div class="container-fluid">
    <!-- Cabecera principal -->
    <h1 class="h3 mb-4 text-gray-800">Listado de ventas</h1>
    <div class="row">
      <div class="col-12 mb-3">
        <a href="venta-formulario.php" class="btn btn-primary mr-2"><i class="fas fa-plus"></i> Nueva</a>
        <?php if(isset($mensaje)): ?>
          <small class="alert alert-success" role="alert"><?php echo $mensaje; ?></small>
        <?php endif; ?>
      </div>
      <div class="col-12 mb-3">
        <table class="table table-hover table-striped border">
          <tr class="table-primary">
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Producto</th>
            <th>Precio unitario</th>
            <th>Cantidad</th>
            <th>Total</th>
            <th>Acciones</th>
          </tr>
          <?php foreach($aVentas as $venta): ?>
          <tr>
            <td><?php echo date_format(date_create($venta->fecha), "d/m/Y H:i"); ?></td>
            <td><?php echo $venta->cliente; ?></td>
            <td><?php echo $venta->producto; ?></td>
            <td><?php echo number_format($venta->preciounitario, 2, ",", "."); ?></td>
            <td><?php echo $venta->cantidad; ?></td>
            <td><?php echo number_format($venta->total, 2, ",", "."); ?></td>
            <td style="width: 110px;">
              <a href="venta-formulario.php?id=<?php echo $venta->idventa; ?>"><i class="fas fa-search"></i></a>   
            </td>
          </tr>
          <?php endforeach; ?>
        </table>
      </div>
    </div>
    <!-- Fin del row -->
  </div>
  <!-- Fin del container-fluid -->
</div>
<!-- Fin del contenido principal -->

<?php include_once "footer.php"; ?>