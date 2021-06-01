<?php

include_once "config.php";
include_once "entidades/tipoproducto.php";

$pag = "Listado de tipos de productos";
$tipoProducto = new TipoProducto();
$aTiposProductos = $tipoProducto->obtenerTodos();

include_once "header.php"; 

?>

    <!-- Inicio del container-fluid -->
    <div class="container-fluid">
        <!-- Cabecera principal de la página -->
        <h1 class="h3 mb-4 text-gray-800">Listado de tipos de productos</h1>
        <div class="row">
            <div class="col-12 mb-3">
                <a href="tipoproducto-formulario.php" class="btn btn-primary mr-2"><i class="fas fa-plus"></i> Nuevo</a>
            </div>
            <div class="col-12 mb-3">
                <table class="table table-hover border">
                    <tr class="table-primary">
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                    <?php foreach($aTiposProductos as $tipo): ?>
                    <tr>
                        <td><?php echo $tipo->nombre; ?></td>
                        <td style="width: 110px;">
                        <a href="tipoproducto-formulario.php?id=<?php echo $tipo->idtipoproducto; ?>"><i class="fas fa-search"></i></a>   
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