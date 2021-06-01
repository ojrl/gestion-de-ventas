<?php

include_once "config.php";
include_once "entidades/producto.php";
include_once "entidades/tipoproducto.php";

$pag = "Registro de tipo de producto";
$tipoProducto = new TipoProducto;
$aTiposProductos = $tipoProducto->obtenerTodos();

if(isset($_GET["id"]) && $_GET["id"] > 0) {
    $tipoProducto->idtipoproducto = $_GET["id"];
    $tipoProducto->obtenerPorId();
}

if($_POST) {
    if(isset($_POST["btnGuardar"])) {
        $tipoProducto->cargarFormulario($_REQUEST);
        if(isset($_GET["id"]) && $_GET["id"] > 0) {
            $tipoProducto->actualizar();
            header("Location: tipos_productos.php");
        } else {
            $tipoProducto->insertar();
            $mensaje = "¡Se ha registrado el tipo de producto exitosamente!";
        }
    } else if(isset($_POST["btnBorrar"])) {
        if(isset($_GET["id"]) && $_GET["id"] > 0) {
            $tipoProducto->eliminar();
            header("Location: tipos_productos.php");
        }
    }
}

include_once "header.php";

?>
    <!-- Inicio del container-fluid -->
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Tipo de Producto</h1>
        <div class="row">
            <div class="col-12 mb-3">
                <a href="tipos_productos.php" class="btn btn-primary mr-2"><i class="fas fa-clipboard-list"></i> Listado</a>
                <button type="submit" id="btnGuardar" name="btnGuardar" class="btn btn-success mr-2"><i class="fas fa-save"></i> Guardar</button>
                <?php if(isset($_GET["id"]) && $_GET["id"] > 0): ?>
                    <?php echo "<button type='submit' class='btn btn-danger' id='btnBorrar' name='btnBorrar'><i class='fas fa-trash-alt'></i> Borrar</button>"; ?>
                <?php else:?>    
                    <a href="tipoproducto-formulario.php" class="btn btn-danger mr-2"><i class="fas fa-trash-alt"></i> Limpiar</a>
                <?php endif;?>
                <?php if(isset($mensaje)): ?>
                    <small class="alert alert-success" role="alert"><?php echo $mensaje; ?></small>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12 form-group">
                <label for="txtNombre">Nombre:</label>
                <input type="text" id="txtNombre" name="txtNombre" value="<?php echo $tipoProducto->nombre;?>" class="form-control" required>
            </div>
        </div>
        <!-- Fin del row -->
    </div>
    <!-- Fin del container-fluid -->
</div>
<!-- Fin del contenido principal -->

<?php include_once "footer.php"; ?>