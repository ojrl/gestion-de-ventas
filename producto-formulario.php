<?php

include_once "config.php";
include_once "entidades/producto.php";
include_once "entidades/tipoproducto.php";

$pag = "Registro de producto";
$producto = new Producto();
$tipoProducto = new TipoProducto();
$aTiposProductos = $tipoProducto->obtenerTodos();

if(isset($_GET["id"]) && $_GET["id"] > 0) {
    $producto->idproducto = $_GET["id"];
    $producto->obtenerPorId();
}

if($_POST) {
    if(isset($_POST["btnGuardar"])) {
        if(isset($_GET["id"]) && $_GET["id"] > 0) {
            //Actualiza la imagen de un producto existente
            if($_FILES["txtImagen"]["error"] === UPLOAD_ERR_OK && file_exists("files/" . $producto->imagen)) {
                unlink("files/" . $producto->imagen);
            }
            //Actualiza un producto existente
            $producto->cargarFormulario($_REQUEST);
            $producto->actualizar();
            header("Location: productos.php");
        } else {
            //Registra un nuevo producto
            $producto->cargarFormulario($_REQUEST);
            $mensaje = "¡Se ha registrado el producto exitosamente!";
            $producto->insertar();
        }
    } else if(isset($_POST["btnBorrar"])) {
        if(isset($_GET["id"]) && $_GET["id"] > 0) {
            if(file_exists("files/" . $producto->imagen)) {
                unlink("files/" . $producto->imagen);
            }
            //Elimina un producto existente
            $producto->eliminar();
            header("Location: productos.php");
        }
    }
} 

include_once "header.php";

?>
    <!-- Inicio del container-fluid -->
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Producto</h1>
        <div class="row">
            <div class="col-12 mb-3">
                <a href="productos.php" class="btn btn-primary mr-2"><i class="fas fa-clipboard-list"></i> Listado</a>
                <button type="submit" id="btnGuardar" name="btnGuardar" class="btn btn-success mr-2"><i class="fas fa-save"></i> Guardar</button>
                <?php if(isset($_GET["id"]) && $_GET["id"] > 0): ?>
                    <?php echo "<button type='submit' class='btn btn-danger' id='btnBorrar' name='btnBorrar'><i class='fas fa-trash-alt'></i> Borrar</button>"; ?>
                <?php else: ?>
                    <a href="producto-formulario.php" class="btn btn-danger mr-2"><i class="fas fa-trash-alt"></i> Limpiar</a>
                <?php endif;?>
                <?php if(isset($mensaje)): ?>
                    <small class="alert alert-success" role="alert"><?php echo $mensaje; ?></small>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6 form-group">
                <label for="lstTipoProducto" class="d-block">Tipo de producto:</label>
                <select id="lstTipoProducto" name="lstTipoProducto" class="form-control selectpicker d-inline" data-live-search="true" required>
                    <option selected disabled></option>
                    <?php foreach($aTiposProductos as $tipoProducto): ?>
                        <?php if($producto->idproducto != "" && $producto->fk_idtipoproducto == $tipoProducto->idtipoproducto):?>
                            <option value="<?php echo $producto->fk_idtipoproducto; ?>" selected><?php echo $tipoProducto->nombre; ?></option>
                        <?php else: ?>
                            <option value="<?php echo $tipoProducto->idtipoproducto; ?>"><?php echo $tipoProducto->nombre; ?></option>               
                        <?php endif; ?>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="col-6 form-group">
                <label for="txtNombre">Nombre:</label>
                <input type="text" id="txtNombre" name="txtNombre" value="<?php echo $producto->nombre; ?>" class="form-control" required>
            </div>
            <div class="col-6 form-group">
                <label for="txtCantidad">Cantidad:</label>
                <input type="number" id="txtCantidad" name="txtCantidad" value="<?php echo $producto->cantidad; ?>"class="form-control" required>
            </div>
            <div class="col-6 form-group">
                <label for="txtPrecio">Precio:</label>
                <input type="text" id="txtPrecio" name="txtPrecio" value="<?php echo $producto->precio; ?>" class="form-control" placeholder="0" required>
            </div>
            <div class="col-12 form-group">
                <label for="txtDescripcion">Descripción:</label>
                <textarea id="txtDescripcion" name="txtDescripcion" class="form-control"><?php echo $producto->descripcion; ?></textarea>
            </div>
            <div class="col-6 form-group">
                <label for="txtImagen">Imagen:</label>
                <input type="file" id="txtImagen" name="txtImagen" class="form-control">
            </div>
        </div>
        <!-- Fin del row -->
    </div>
    <!-- Fin del container-fluid -->
</div>
<!-- Fin del contenido principal -->

<?php include_once "footer.php"; ?>