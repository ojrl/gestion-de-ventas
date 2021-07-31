<?php

class Cliente {
    private $idcliente;
    private $nombre;
    private $cuit;
    private $telefono;
    private $correo;
    private $fecha_nac;

    public function __construct() {
    }

    public function __get($propiedad) {
        return $this->$propiedad;
    }

    public function __set($propiedad, $valor) {
        $this->$propiedad = $valor;
    }

    public function cargarFormulario($request) {
        $this->idcliente = isset($request["id"]) ? $request["id"] : "";
        $this->nombre = isset($request["txtNombre"]) ? $request["txtNombre"] : "";
        $this->cuit = isset($request["txtCuit"]) ? $request["txtCuit"] : "";
        $this->telefono = isset($request["txtTelefono"]) ? $request["txtTelefono"] : "";
        $this->correo = isset($request["txtCorreo"]) ? $request["txtCorreo"] : "";
        if(isset($request["txtAnioNac"]) && isset($request["txtMesNac"]) && isset($request["txtDiaNac"])) {
            $this->fecha_nac = $request["txtAnioNac"] . "-" .  $request["txtMesNac"] . "-" .  $request["txtDiaNac"];
        }
    }

    public function insertar() {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "INSERT INTO clientes (
            nombre, 
            cuit, 
            telefono, 
            correo, 
            fecha_nac
        ) VALUES (
            '" . $this->nombre . "', 
            '" . $this->cuit . "', 
            '" . $this->telefono . "', 
            '" . $this->correo . "', 
            '" . $this->fecha_nac . "'
        );";
        if($mysqli->query($sql)) {
            $this->idcliente = $mysqli->insert_id;
        } else {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function actualizar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "UPDATE clientes SET
            nombre = '" . $this->nombre . "',
            cuit = '" . $this->cuit . "',
            telefono = '" . $this->telefono . "',
            correo = '" . $this->correo . "',
            fecha_nac =  '" . $this->fecha_nac . "'
            WHERE idcliente = " . $this->idcliente;
        if($mysqli->query($sql)) {
            echo "<script>alert('¡Se han actualizado los datos del cliente exitosamente!')</script>";
        } else {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function eliminar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "DELETE FROM clientes WHERE idcliente = " . $this->idcliente;
        if($mysqli->query($sql)) {
            echo "<script>alert('¡Se ha eliminado el registro del cliente exitosamente!')</script>";
        } else {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorId(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT 
            idcliente, 
            nombre, 
            cuit, 
            telefono, 
            correo, 
            fecha_nac 
        FROM clientes 
        WHERE idcliente = " . $this->idcliente;
        if($resultado = $mysqli->query($sql)) {
            if($fila = $resultado->fetch_assoc()) {
                $this->idcliente = $fila["idcliente"];
                $this->nombre = $fila["nombre"];
                $this->cuit = $fila["cuit"];
                $this->telefono = $fila["telefono"];
                $this->correo = $fila["correo"];
                $this->fecha_nac = $fila["fecha_nac"];
            } 
        } else {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

  public function obtenerTodos() {
        $aClientes = array();
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT
            idcliente,
            cuit,
            nombre,
            telefono,
            correo,
            fecha_nac
	    FROM clientes
	    ORDER BY nombre ASC";
        if($resultado = $mysqli->query($sql)) {
            while($fila = $resultado->fetch_assoc()) {
                $obj = new Cliente();
                $obj->idcliente = $fila["idcliente"];
                $obj->nombre = $fila["nombre"];
                $obj->cuit = $fila["cuit"];
                $obj->telefono = $fila["telefono"];
                $obj->correo = $fila["correo"];
                $obj->fecha_nac = $fila["fecha_nac"];
                $aClientes[] = $obj;
            }
            $mysqli->close();
            return $aClientes;
        } else {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }
}

?>