<?php

include_once "config.php";
include_once "entidades/producto.php";

$pag = "Listado de productos";
$producto = new Producto();
$aProductos = $producto->obtenerTodos();

include_once "header.php"; 

?>

  <!-- Inicio del container-fluid -->
  <div class="container-fluid">
    <!-- Cabecera principal -->
    <h1 class="h3 mb-4 text-gray-800">Listado de productos</h1>
    <div class="row">
      <div class="col-12 mb-3">
        <a href="producto-formulario.php" class="btn btn-primary mr-2"><i class="fas fa-plus"></i> Nuevo</a>
      </div>
      <div class="col-12 mb-3">
        <table class="table table-hover border">
          <tr class="table-primary">
            <th>Foto</th>
            <th>Nombre</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Acciones</th>
          </tr>
          <?php foreach($aProductos as $producto): ?>
          <tr>
            <td style="width: 120px;"><img src="files/<?php echo $producto->imagen; ?>" alt="" class="img-thumbnail"></td>
            <td><?php echo $producto->nombre; ?></td>
            <td class="text-right"><?php echo number_format($producto->cantidad, 0, ",", "."); ?></td>
            <td class="text-right"><?php echo number_format($producto->precio, 2, ",", "."); ?></td>
            <td style="width: 120px;">
              <a href="producto-formulario.php?id=<?php echo $producto->idproducto; ?>"><i class="fas fa-search"></i></a>   
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