<?php

include_once "config.php";
include_once "entidades/usuario.php";

$pag = "Listado de usuarios";
$usuario = new Usuario();
$aUsuarios = $usuario->obtenerTodos();

include_once "header.php";

?>

  <!-- Inicio del container-fluid -->
  <div class="container-fluid">
    <!-- Cabecera principal -->
    <h1 class="h3 mb-4 text-gray-800">Listado de usuarios</h1>
    <div class="row">
      <div class="col-12 mb-3">
        <a href="usuario-formulario.php" class="btn btn-primary mr-2"><i class="fas fa-plus"></i> Nuevo</a>
      </div>
      <div class="col-12 mb-3">
        <table class="table table-hover border">
          <tr class="table-primary">
            <th>Usuario</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Correo</th>
            <th>Acciones</th>
          </tr>
          <?php foreach ($aUsuarios as $usuario): ?>
          <tr>
            <td><?php echo $usuario->usuario; ?></td>
            <td><?php echo $usuario->nombre; ?></td>
            <td><?php echo $usuario->apellido; ?></td>
            <td><?php echo $usuario->correo; ?></td>
            <td style="width: 110px;">
              <a href="usuario-formulario.php?id=<?php echo $usuario->idusuario; ?>"><i class="fas fa-search"></i></a>   
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