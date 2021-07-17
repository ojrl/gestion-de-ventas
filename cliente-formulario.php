<?php

include_once "config.php";
include_once "entidades/cliente.php";
include_once "entidades/provincia.php";
include_once "entidades/localidad.php";

$pag = "Registro de cliente";
$cliente = new Cliente();
$provincia = new Provincia();
$aProvincias = $provincia->obtenerTodas();

if(isset($_GET["id"]) && $_GET["id"] > 0) {
    $cliente->idcliente = $_GET["id"];
    $cliente->obtenerPorId();
}

if($_POST) {
    if(isset($_POST["btnGuardar"])) {
        $cliente->cargarFormulario($_REQUEST);
        if(isset($_GET["id"]) && $_GET["id"] > 0) {
            $cliente->actualizar();
            header("Location: clientes.php");
        } else {
            $mensaje = "¡Se ha registrado el cliente exitosamente!";
            $cliente->insertar();
        }
    } else if(isset($_POST["btnBorrar"])) {
        if(isset($_GET["id"]) && $_GET["id"] > 0) {
            $cliente->eliminar();
            header("Location: clientes.php");
        }
    }
} 

if(isset($_GET["do"]) && $_GET["do"] == "buscarLocalidad" && isset($_GET["idProvincia"]) && $_GET["idProvincia"] > 0) {
    $idProvincia = $_GET["idProvincia"];
    $localidad = new Localidad();
    $aLocalidades = $localidad->obtenerPorProvincia($idProvincia);
    echo json_encode($aLocalidades);
    exit;
}

include_once "header.php"; 

?>
    <!-- Inicio del container-fluid -->
    <div class="container-fluid">
        <!-- Cabecera principal -->
        <h1 class="h3 mb-4 text-gray-800">Cliente</h1>
        <div class="row">
            <div class="col-12 mb-3">
                <a href="clientes.php" class="btn btn-primary mr-2"><i class="fas fa-clipboard-list"></i> Listado</a>
                <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar"><i class="fas fa-save"></i> Guardar</button>
                <?php if(isset($_GET["id"]) && $_GET["id"] > 0): ?>
                    <button type="submit" class="btn btn-danger" id="btnBorrar" name="btnBorrar"><i class="fas fa-trash-alt"></i> Borrar</button>
                <?php else: ?>   
                    <a href="cliente-formulario.php" class="btn btn-danger mr-2"><i class="fas fa-trash-alt"></i> Limpiar</a>
                <?php endif;?>
                <?php if(isset($mensaje)): ?>
                    <small class="alert alert-success" role="alert"><?php echo $mensaje; ?></small>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6 form-group">
                <label for="txtNombre">Nombre:</label>
                <input type="text" required class="form-control" name="txtNombre" id="txtNombre" value="<?php echo $cliente->nombre; ?>">
            </div>
            <div class="col-6 form-group">
                <label for="txtCuit">CUIT:</label>
                <input type="text" required class="form-control" name="txtCuit" id="txtCuit" value="<?php echo $cliente->cuit; ?>" maxlength="11">
            </div>
            <div class="col-6 form-group">
                <label for="txtCorreo">Correo:</label>
                <input type="" class="form-control" name="txtCorreo" id="txtCorreo" required value="<?php echo $cliente->correo; ?>">
            </div>
            <div class="col-6 form-group">
                <label for="txtTelefono">Teléfono:</label>
                <input type="number" class="form-control" name="txtTelefono" id="txtTelefono" value="<?php echo $cliente->telefono; ?>">
            </div>
            <div class="col-6 form-group">
                <label for="txtFechaNac" class="d-block">Fecha de nacimiento:</label>
                <select class="form-control d-inline"  name="txtDiaNac" id="txtDiaNac" style="width: 80px">
                    <option selected="" disabled>D</option>
                    <?php for($i = 1; $i <= 31; $i++): ?>
                        <?php if($cliente->fecha_nac != "" && $i == date_format(date_create($cliente->fecha_nac), "d")): ?>
                            <option value="<?php echo $i; ?>" selected><?php echo $i; ?></option>
                        <?php else: ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endif; ?>
                    <?php endfor; ?>
                </select>
                <select class="form-control d-inline"  name="txtMesNac" id="txtMesNac" style="width: 80px">
                    <option selected="" disabled>M</option>
                    <?php for($i = 1; $i <= 12; $i++): ?>
                        <?php if($cliente->fecha_nac != "" && $i == date_format(date_create($cliente->fecha_nac), "m")): ?>
                            <option value="<?php echo $i; ?>" selected><?php echo $i; ?></option>
                        <?php else: ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endif; ?>
                    <?php endfor; ?>
                </select>
                <select class="form-control d-inline"  name="txtAnioNac" id="txtAnioNac" style="width: 100px">
                    <option selected="" disabled>Y</option>
                    <?php for($i = date("Y") - 125; $i <= date("Y"); $i++): ?>
                        <?php if($cliente->fecha_nac != "" && $i == date_format(date_create($cliente->fecha_nac), "Y")): ?>
                            <option value="<?php echo $i; ?>" selected><?php echo $i; ?></option>
                        <?php else: ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endif; ?>
                    <?php endfor; ?> ?>
                </select>
            </div>
        </div>

        <!-- Domicilios -->
        <div class="row">
            <div class="col-12">  
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-table pull-start"></i> Domicilios
                        <div class="pull-end">
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalDomicilio">Agregar</button>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <table id="grilla" class="display" style="width:98%">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Provincia</th>
                                <th>Localidad</th>
                                <th>Dirección</th>
                            </tr>
                        </thead>
                    </table> 
                </div>
            </div>          
        </div>
        <!-- Fin de domicilios -->

        <!-- Formulario de domicilios -->
        <div class="modal fade" id="modalDomicilio" tabindex="-1" role="dialog" aria-labelledby="modalDomicilioLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDomicilioLabel">Domicilio</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 form-group">
                                <label for="lstTipo">Tipo:</label>
                                <select name="lstTipo" id="lstTipo" class="form-control">
                                    <option value="" disabled selected>Seleccionar</option>
                                    <option value="1">Personal</option>
                                    <option value="2">Laboral</option>
                                    <option value="3">Comercial</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 form-group">
                                <label for="lstProvincia">Provincia:</label>
                                <select name="lstProvincia" id="lstProvincia" class="form-control">
                                    <option value="" disabled selected>Seleccionar</option>
                                    <?php foreach($aProvincias as $provincia): ?>
                                        <option value="<?php echo $provincia->idprovincia; ?>"><?php echo $provincia->nombre; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 form-group">
                                <label for="lstLocalidad">Localidad:</label>
                                <select name="lstLocalidad" id="lstLocalidad" class="form-control">
                                    <option value="" disabled selected>Seleccionar</option>
                                    <?php foreach($aLocalidades as $localidad): ?>
                                        <option value="<?php echo $localidad->idlocalidad; ?>"><?php echo $localidad->nombre; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 form-group">
                                <label for="txtDireccion">Dirección:</label>
                                <input type="text" name="" id="txtDireccion" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="fAgregarDomicilio()">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin de Formulario de domicilios -->
    
    </div>  
    <!-- Fin del container-fluid -->
</div>
<!-- Fin del contenido principal -->

<script>

$(document).ready( function () {
    let idCliente = '<?php echo isset($cliente) && $cliente->idcliente > 0? $cliente->idcliente : 0 ?>';

    let dataTable = $('#grilla').DataTable({
        "processing": true,
        "serverSide": false,
        "bFilter": false,
        "bInfo": true,
        "bSearchable": false,
        "paging": false,
        "pageLength": 25,
        "order": [[ 0, "asc" ]],
        "ajax": "cliente-formulario.php?do=cargarGrilla&idCliente=" + idCliente
    });

    $('#lstProvincia').change(function() {
        let provincia = $("#lstProvincia option:selected").val();
        $.ajax({
            type: "GET",
            url: "cliente-formulario.php?do=buscarLocalidad",
            data: { idProvincia: provincia },
            async: true,
            dataType: "json",
            success: function (aResultado) {
                $("#lstLocalidad option").remove();
                for(let i = 0; i < aResultado.length; i++) {
                    $("<option>", {
                        value: aResultado[i]["idlocalidad"],
                        text: aResultado[i]["nombre"]
                    }).appendTo("#lstLocalidad");
                }
                $("#lstLocalidad").prop("selectedIndex", "-1");
            }
        });
    });

/*     onchange="fBuscarLocalidad(); */

});

/* 
function fBuscarLocalidad(){
    idProvincia = $("#lstProvincia option:selected").val();
    $.ajax({
        type: "GET",
        url: "cliente-formulario.php?do=buscarLocalidad",
        data: { id:idProvincia },
        async: true,
        dataType: "json",
        success: function (respuesta) {
            let opciones = "<option value='0' disabled selected>Seleccionar</option>";
            const resultado = respuesta.reduce(function(acumulador, valor){
                const {nombre,idlocalidad} = valor;
                return acumulador + `<option value="${idlocalidad}">${nombre}</option>`;
            }, opciones);
            $("#lstLocalidad").empty().append(resultado);
        }
    });
} 
*/

function fAgregarDomicilio(){
    var grilla = $('#grilla').DataTable();
    grilla.row.add([
        $("#lstTipo option:selected").text() + "<input type='hidden' name='txtTipo[]' value='"+ $("#lstTipo option:selected").val() +"'>",
        $("#lstProvincia option:selected").text() + "<input type='hidden' name='txtProvincia[]' value='"+ $("#lstProvincia option:selected").val() +"'>",
        $("#lstLocalidad option:selected").text() + "<input type='hidden' name='txtLocalidad[]' value='"+ $("#lstLocalidad option:selected").val() +"'>",
        $("#txtDireccion").val() + "<input type='hidden' name='txtDomicilio[]' value='"+$("#txtDireccion").val()+"'>"
    ]).draw();
    $('#modalDomicilio').modal('toggle');
    limpiarFormulario();
}

function limpiarFormulario(){
    $("#lstTipo").val("");
    $("#lstProvincia").val("");
    $("#lstLocalidad").val("");
    $("#txtDireccion").val("");
}

</script>

<?php include_once "footer.php"; ?>