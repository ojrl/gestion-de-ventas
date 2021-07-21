<?php

include_once "config.php";
include_once "entidades/usuario.php";

$pag = "Registro de usuario";
$usuario = new Usuario();

if(isset($_GET["id"]) && $_GET["id"] > 0) {
    $usuario->idusuario = $_GET["id"];
    $usuario->obtenerPorId();
}

if($_POST) {
    if(isset($_POST["btnGuardar"])) {
        $usuario->cargarFormulario($_REQUEST);
        if(isset($_GET["id"]) && $_GET["id"] > 0) {
            $usuario->actualizar();
            header("Location: usuarios.php");
        } else {
            if($_REQUEST["txtClave"] === $_REQUEST["txtVerificarClave"]) {
                $usuario->insertar();
                header("Location: usuarios.php");
            } else {
                $mensajeFalse = "¡Las contraseñas deben ser las mismas!";
            }
        }
    } else if(isset($_POST["btnBorrar"])) {
        if(isset($_GET["id"]) && $_GET["id"] > 0) {
            $usuario->eliminar();
            header("Location: usuarios.php");
        }
    }
}

include_once "header.php";

?>

    <!-- Inicio del container-fluid -->
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Usuario</h1>
        <div class="row">
            <div class="col-12 mb-3">
                <a href="usuarios.php" class="btn btn-primary mr-2"><i class="fas fa-clipboard-list"></i> Listado</a>
                <button type="submit" id="btnGuardar" name="btnGuardar" class="btn btn-success mr-2"><i class="fas fa-save"></i> Guardar</button>
                <?php if(isset($_GET["id"]) && $_GET["id"] > 0): ?>
                    <?php echo "<button type='submit' class='btn btn-danger' id='btnBorrar' name='btnBorrar'><i class='fas fa-trash-alt'></i> Borrar</button>"; ?>
                <?php else: ?>
                    <a href="usuario-formulario.php" class="btn btn-danger mr-2"><i class="fas fa-trash-alt"></i> Limpiar</a>
                <?php endif;?>
                <?php if(isset($mensaje)): ?>
                    <small class="alert alert-success" role="alert"><?php echo $mensaje; ?></small>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6 form-group">
                <label for="txtNombre">Nombre:</label>
                <input type="text" id="txtNombre" name="txtNombre" value="<?php echo $usuario->nombre; ?>" class="form-control" required>
            </div>
            <div class="col-6 form-group">
                <label for="txtApellido">Apellido:</label>
                <input type="text" id="txtApellido" name="txtApellido" value="<?php echo $usuario->apellido; ?>" class="form-control" required>
            </div>
            <div class="col-6 form-group">
                <label for="txtUsuario">Usuario:</label>
                <input type="text" id="txtUsuario" name="txtUsuario" class="form-control" value="<?php echo $usuario->usuario; ?>" required>
            </div>
            <div class="col-6 form-group">
                <label for="txtCorreo">Correo:</label>
                <input type="text" id="txtCorreo" name="txtCorreo" class="form-control" value="<?php echo $usuario->correo; ?>" required>
            </div>

            <?php if(isset($_GET["id"]) && $_GET["id"] > 0): ?>
                <div class="col-12 form-group">
                    <label for="txtContrasena">Clave:</label>
                    <input type="text" id="txtContrasena" name="txtContrasena" value="<?php echo $usuario->clave; ?>" class="form-control" disabled>
                    <input type="password" id="txtClave" name="txtClave" class="form-control" value="<?php echo $usuario->clave; ?>" hidden required>
                </div>
            <?php else: ?>
                <div class="col-6 form-group">
                    <label for="txtClave">Clave:</label>
                    <input type="password" id="txtClave" name="txtClave" class="form-control" value="" required>
                    <?php if(isset($mensajeFalse)): ?>
                        <br><small class="alert alert-danger my-3" role="alert"><?php echo $mensajeFalse; ?></small>
                    <?php endif; ?>
                </div>
                <div class="col-6 form-group">
                    <label for="txtVerificarClave">Verificar Clave:</label>
                    <input type="password" id="txtVerificarClave" name="txtVerificarClave" class="form-control" value="" required>
                    <?php if(isset($mensajeFalse)): ?>
                        <br><small class="alert alert-danger my-3" role="alert"><?php echo $mensajeFalse; ?></small>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <!-- Fin del row -->
    </div>
    <!-- Fin del container-fluid -->
</div>
<!-- Fin del contenido principal -->

<?php include_once "footer.php"; ?>