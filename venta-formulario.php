<?php

include_once "config.php";
include_once "entidades/cliente.php";
include_once "entidades/producto.php";
include_once "entidades/venta.php";

$pag = "Registro de venta";
$venta = new Venta();
$aVentas = $venta->obtenerTodas();
$cliente = new Cliente();
$aClientes = $cliente->obtenerTodos();
$producto = new Producto();
$aProductos = $producto->obtenerTodos();

if(isset($_GET["id"]) && $_GET["id"] > 0) {
    $venta->idventa = $_GET["id"];
    $venta->obtenerPorId();
}

if(isset($_GET["do"]) && $_GET["do"] == "buscarProducto") {
    $producto = new Producto();
    $producto->idproducto = $_GET["idProducto"];
    $producto->obtenerPorId();
    $resultado["precio"] = $producto->precio;
    echo json_encode($resultado); 
    exit;
}

if($_POST) {
    if(isset($_POST["btnGuardar"])) {
        $venta->cargarFormulario($_REQUEST);
        if(isset($_GET["id"]) && $_GET["id"] > 0) {
            $venta->actualizar();
            header("Location: ventas.php");
        } else {
            $venta->insertar();
            $mensaje = "¡Se ha registrado la venta exitosamente!";
        }
    } else if(isset($_POST["btnBorrar"])) {
        if(isset($_GET["id"]) && $_GET["id"] > 0) {
            $venta->eliminar();
            header("Location: ventas.php");
        }
    }
}

include_once "header.php";

?>
<script>

window.onload = function() {

    $("#lstProducto").change(function(){
        let producto = $("#lstProducto option:selected").val();
        $.ajax({
            type: "GET",
            url: "venta-formulario.php?do=buscarProducto",
            data: { idProducto: producto },
            async: true,
            dataType: "json",
            success: function (respuesta) {
                strResultado = Intl.NumberFormat("es-AR", {style: 'currency', currency: 'ARS'}).format(respuesta.precio);
                $("#txtPrecioMoneda").val(strResultado);
                $("#txtPrecio").val(respuesta.precio);
            }
        });
    });

    document.getElementById("txtCantidad").onchange = function() {
        let total = $("#txtPrecio").val() * $("#txtCantidad").val();
        let totalMoneda = Intl.NumberFormat("es-AR", {style: 'currency', currency: 'ARS'}).format(total);
        $("#txtTotalMoneda").val(totalMoneda);
        $("#txtTotal").val(total);
    }
}

</script>
    <!-- Inicio del container-fluid -->
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Venta</h1>
        <div class="row">
            <div class="col-12 mb-3">
                <a href="ventas.php" class="btn btn-primary mr-2"><i class="fas fa-clipboard-list"></i> Listado</a>
                <button type="submit" id="btnGuardar" name="btnGuardar" class="btn btn-success mr-2"><i class="fas fa-save"></i> Guardar</button>
                <?php if(isset($_GET["id"]) && $_GET["id"] > 0): ?>
                    <?php echo "<button type='submit' class='btn btn-danger' id='btnBorrar' name='btnBorrar'><i class='fas fa-trash-alt'></i> Borrar</button>"; ?>
                <?php else: ?>
                    <a href="venta-formulario.php" class="btn btn-danger mr-2"><i class="fas fa-trash-alt"></i> Limpiar</a>
                <?php endif;?>
                <?php if(isset($mensaje)): ?>
                    <small class="alert alert-success" role="alert"><?php echo $mensaje; ?></small>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12 form-group">
                <label for="lstFecha" class="d-block">Fecha y Hora:</label>
                <select id="lstDia" name="lstDia" class="col-1 form-control d-inline" required>
                    <option selected disabled>D</option>
                    <?php for($i = 1; $i <= 31; $i++): ?>
                        <?php if($i == date_format(date_create($venta->fecha), "d")): ?>
                            <option value="<?php echo $i;?>" selected><?php echo $i;?></option>
                        <?php else: ?>
                            <option value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php endif; ?>                  
                    <?php endfor;?>
                </select>
                <select id="lstMes" name="lstMes" class="col-1 form-control d-inline" required>
                    <option selected disabled>M</option>
                    <?php for($i = 1; $i <= 12; $i++): ?>
                        <?php if($i == date_format(date_create($venta->fecha), "m")): ?>
                            <option value="<?php echo $i;?>" selected><?php echo $i;?></option>
                        <?php else: ?>
                            <option value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php endif; ?>                      
                    <?php endfor;?>
                </select>
                <select id="lstAnio" name="lstAnio" class="col-1 form-control d-inline" required>
                    <option selected disabled>Y</option>
                    <?php for($i = date("Y") - 125; $i <= date("Y"); $i++): ?>
                        <?php if($i == date_format(date_create($venta->fecha), "Y")): ?>
                            <option value="<?php echo $i;?>" selected><?php echo $i;?></option>
                        <?php else: ?>
                            <option value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php endif; ?>                     
                    <?php endfor;?>
                </select>
                <input type="time" id="txtHora" name="txtHora" value="<?php echo date_format(date_create($venta->fecha), "H:i"); ?>" class="col-2 form-control d-inline" required>
            </div>
            <div class="col-6 form-group">
                <label for="lstCliente" class="d-block">Cliente:</label>
                <select id="lstCliente" name="lstCliente" class="form-control selectpicker d-inline" data-live-search="true" required>
                    <option selected disabled></option>
                    <?php foreach($aClientes as $cliente): ?>
                        <?php if($venta->idventa != "" && $venta->fk_idcliente == $cliente->idcliente): ?>
                            <option value="<?php echo $cliente->idcliente;?>" selected><?php echo $cliente->nombre;?></option>                 
                        <?php else: ?>
                            <option value="<?php echo $cliente->idcliente;?>"><?php echo $cliente->nombre;?></option>                 
                        <?php endif; ?>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="col-6 form-group">
                <label for="lstProducto" class="d-block">Producto:</label>
                <select id="lstProducto" name="lstProducto" class="form-control selectpicker d-inline" data-live-search="true" required>
                    <option selected disabled></option>
                    <?php foreach($aProductos as $producto): ?>
                        <?php if($venta->idventa != "" && $venta->fk_idproducto == $producto->idproducto): ?>
                            <option value="<?php echo $producto->idproducto; ?>" selected><?php echo $producto->idproducto . ". " . $producto->nombre;?></option>                  
                        <?php else: ?>
                            <option value="<?php echo $producto->idproducto; ?>"><?php echo $producto->nombre;?></option>                  
                        <?php endif; ?>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="col-6 form-group">
                <label for="txtPrecioMoneda">Precio unitario:</label>
                <input type="text" id="txtPrecioMoneda" name="txtPrecioMoneda" value="<?php echo $venta->preciounitario; ?>" class="form-control" disabled>
                <input type="text" id="txtPrecio" name="txtPrecio" value="<?php echo $venta->preciounitario; ?>" class="form-control" hidden required>
            </div>
            <div class="col-6 form-group">
                <label for="txtCantidad">Cantidad:</label>
                <input type="number" id="txtCantidad" name="txtCantidad" class="form-control" value="<?php echo $venta->cantidad; ?>" required>
            </div>
            <div class="col-6 form-group">
                <label for="txtTotalMoneda">Total:</label>
                <input type="text" id="txtTotalMoneda" name="txtTotalMoneda" value="<?php echo $venta->total; ?>" class="form-control" disabled>
                <input type="text" id="txtTotal" name="txtTotal" value="<?php echo $venta->total; ?>" class="form-control" hidden required>
            </div>
        </div>
        <!-- Fin del row -->
    </div>
    <!-- Fin del container-fluid -->
</div>
<!-- Fin del contenido principal -->

<?php include_once "footer.php"; ?>