<?php

class Usuario {
    private $idusuario;
    private $usuario;
    private $clave;
    private $nombre;
    private $apellido;
    private $correo;

    public function __construct(){
    }

    public function __get($propiedad){
        return $this->$propiedad;
    }

    public function __set($propiedad, $valor){
        $this->$propiedad = $valor;
        return $this;
    }

    public function cargarFormulario($request){
        $this->idusuario = isset($request["id"]) ? $request["id"] : "";
        $this->usuario = isset($request["txtUsuario"]) ? $request["txtUsuario"] : "";
        $this->clave = isset($request["txtClave"]) ? $request["txtClave"] : "";
        $this->nombre = isset($request["txtNombre"]) ? $request["txtNombre"] : "";
        $this->apellido = isset($request["txtApellido"]) ? $request["txtApellido"] : "";
        $this->correo = isset($request["txtCorreo"]) ? $request["txtCorreo"] : "";
    }

    public function insertar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "INSERT INTO usuarios (
            usuario, 
            clave, 
            nombre,
            apellido, 
            correo
            ) VALUES ('"
            . $this->usuario . "', '"
            . $this->clave . "', '"
            . $this->nombre . "', '"
            . $this->apellido . "', '"
            . $this->correo . "');";
        if ($mysqli->query($sql)) {
            $this->idusuario = $mysqli->insert_id;
        } else {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function actualizar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "UPDATE usuarios SET "
            . "usuario = '" . $this->usuario . "', '"
            . "clave = '" . $this->clave . "', '"
            . "nombre = '" . $this->nombre . "', '"
            . "apellido = '" . $this->apellido . "', '"
            . "correo = '" . $this->correo . "' "
            . "WHERE idusuario = " . $this->idusuario;
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function eliminar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "DELETE FROM usuarios WHERE idusuario = " . $this->idusuario;
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorId(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT 
            idusuario, 
            usuario, 
            clave,
            nombre,
            apellido, 
            correo
            FROM usuarios 
            WHERE idusuario = " . $this->idusuario;
        if ($resultado = $mysqli->query($sql)) {
            if($fila = $resultado->fetch_assoc()){
                $this->idusuario = $fila["idusuario"];
                $this->usuario = $fila["usuario"];
                $this->clave = $fila["clave"];
                $this->nombre = $fila["nombre"];
                $this->apellido = $fila["apellido"];
                $this->correo = $fila["correo"];
            }
        } else {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorUsuario($usuario){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT 
            idusuario, 
            usuario, 
            clave,
            nombre,
            apellido, 
            correo
            FROM usuarios 
            WHERE usuario = '$usuario'";
        if ($resultado = $mysqli->query($sql)) {
            if($fila = $resultado->fetch_assoc()){
                $this->idusuario = $fila["idusuario"];
                $this->usuario = $fila["usuario"];
                $this->clave = $fila["clave"];
                $this->nombre = $fila["nombre"];
                $this->apellido = $fila["apellido"];
                $this->correo = $fila["correo"];
            }
        } else {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerTodos(){
        $aUsuarios = array();
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT 
            idusuario, 
            usuario, 
            clave, 
            nombre, 
            apellido, 
            correo 
            FROM usuarios";
        if ($resultado = $mysqli->query($sql)) {
            while($fila = $resultado->fetch_assoc()){
                $obj = new Usuario();
                $obj->idusuario = $fila["idusuario"];
                $obj->usuario = $fila["usuario"];
                $obj->clave = $fila["clave"];
                $obj->nombre = $fila["nombre"];
                $obj->apellido = $fila["apellido"];
                $obj->correo = $fila["correo"];
                $aUsuarios[] = $obj;
            }
            $mysqli->close();
            return $aUsuarios;
        } else {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function encriptarClave($clave){
        $claveEncriptada = password_hash($clave, PASSWORD_DEFAULT);
        return $claveEncriptada;
    }

    public function verificarClave($claveIngresada, $claveEnBBDD){
        return password_verify($claveIngresada, $claveEnBBDD);
    }

}


?>