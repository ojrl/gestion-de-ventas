<?php

include_once "config.php";
include_once "entidades/cliente.php";

$pag = "Listado de clientes";
$cliente = new Cliente();
$aClientes = $cliente->obtenerTodos();

include_once "header.php";

?>

  <!-- Inicio del container-fluid -->
  <div class="container-fluid">
    <!-- Cabecera principal -->
    <h1 class="h3 mb-4 text-gray-800">Listado de clientes</h1>
    <div class="row">
      <div class="col-12 mb-3">
        <a href="cliente-formulario.php" class="btn btn-primary mr-2"><i class="fas fa-plus"></i> Nuevo</a>
      </div>
      <div class="col-12 mb-3">
        <table class="table table-hover border">
          <tr class="table-primary">
            <th>CUIT</th>
            <th>Nombre</th>
            <th>Fecha nac.</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Acciones</th>
          </tr>
          <?php foreach ($aClientes as $cliente): ?>
          <tr>
            <td><?php echo $cliente->cuit; ?></td>
            <td><?php echo $cliente->nombre; ?></td>
            <td><?php echo date_format(date_create($cliente->fecha_nac), "d/m/Y"); ?></td>
            <td><?php echo $cliente->telefono; ?></td>
            <td><?php echo $cliente->correo; ?></td>
            <td style="width: 110px;">
              <a href="cliente-formulario.php?id=<?php echo $cliente->idcliente; ?>"><i class="fas fa-search"></i></a>   
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