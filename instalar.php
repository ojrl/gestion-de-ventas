<?php

//Posible página adicional

include_once "config.php";
include_once "entidades/usuario.php";

$usuario = new Usuario();
$usuario->usuario = "admin";
$usuario->clave = $usuario->encriptarClave("admin123");
$usuario->nombre = "Orlando";
$usuario->apellido = "Ramos";
$usuario->correo = "ojrl93@gmail.com";
$usuario->insertar();
echo "Usuario insertado.";

?>