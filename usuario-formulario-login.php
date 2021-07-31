<?php

include_once "config.php";
include_once "entidades/usuario.php";

$usuario = new Usuario();
if($_POST) {
  if($_REQUEST["txtClave"] === $_REQUEST["txtVerificarClave"]) {
    $usuario->cargarFormulario($_REQUEST);
    $usuario->insertar();
    $mensaje = "¡Se ha registrado el usuario exitosamente, ya puedes <a href='login.php'>iniciar session!</a>";
  } else {
    $error = "¡Las contraseñas deben ser las mismas!";
  }

}

?>

<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SB Admin 2 - Register</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">
  <div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
          <div class="col-lg-7">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">¡Crea una cuenta!</h1>
              </div>
              <form action="" method="POST" class="user">
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control form-control-user" id="txtNombre" name="txtNombre" placeholder="Nombre" required>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control form-control-user" id="txtApellido" name="txtApellido" placeholder="Apellido">
                  </div>
                </div>
                <div class="form-group">
                  <input type="email" class="form-control form-control-user" id="txtCorreo" name="txtCorreo" placeholder="Correo" required>
                </div>
                <div class="form-group">
                  <input type="txt" class="form-control form-control-user" id="txtUsuario" name="txtUsuario" placeholder="Usuario" required>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="password" class="form-control form-control-user" id="txtClave" name="txtClave" placeholder="Contraseña" required>
                  </div>
                  <div class="col-sm-6">
                    <input type="password" class="form-control form-control-user" id="txtVerificarClave" name="txtVerificarClave" placeholder="Repetir Contraseña" required>
                  </div>
                  <?php if(isset($mensaje)): ?>
                    <small class="col-sm-10 offset-1 alert alert-success mt-2 text-center" role="alert"><?php echo $mensaje; ?></small>
                  <?php endif; ?>
                  <?php if(isset($error)): ?>
                    <small class="col-sm-8 offset-2 alert alert-danger mt-2 text-center" role="alert"><?php echo $error; ?></small>
                  <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block">Registrar cuenta</button>
                <hr>
                <a href="index.php" class="btn btn-google btn-user btn-block">
                  <i class="fab fa-google fa-fw"></i> Registrarse con Google
                </a>
                <a href="index.php" class="btn btn-facebook btn-user btn-block">
                  <i class="fab fa-facebook-f fa-fw"></i> Registrarse con Facebook
                </a>
              </form>
              <hr>
              <div class="text-center">
                <a class="small" href="login.php">¿Ya tienes una cuenta? ¡Inicia session!</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
