<?php

class TipoProducto {
    private $idtipoproducto;
    private $nombre;

    public function __construct() {
    }

    public function __get($propiedad) {
        return $this->$propiedad;
    }

    public function __set($propiedad, $valor) {
        $this->$propiedad = $valor;
    }

    public function cargarFormulario($request) {
        $this->idtipoproducto = isset($request["id"]) ? $request["id"] : "";
        $this->nombre = isset($request["txtNombre"]) ? $request["txtNombre"] : "";
    }

    public function insertar() {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "INSERT INTO tipos_productos (
            nombre
        ) VALUES (
            '" . $this->nombre . "'
        )";
        if($mysqli->query($sql)) {
            $this->idtipoproducto = $mysqli->insert_id;
        } else {
            printf("Error en query %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function actualizar() {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "UPDATE tipos_productos SET
            nombre = '" . $this->nombre . "'
        WHERE idtipoproducto = " . $this->idtipoproducto;
        if(!$mysqli->query($sql)){
            printf("Error en query %s\n", $mysqli->error . " " . $sql);
        } 
        $mysqli->close();
    }

    public function eliminar() {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "DELETE FROM tipos_productos WHERE idtipoproducto = " . $this->idtipoproducto;
        if(!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorId() {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT
            idtipoproducto,
            nombre
        FROM tipos_productos
        WHERE idtipoproducto = " . $this->idtipoproducto;
        if($resultado = $mysqli->query($sql)) {
            if($fila = $resultado->fetch_assoc()) {
                $this->idtipoproducto = $fila["idtipoproducto"];
                $this->nombre = $fila["nombre"];
            }
        } else {
            printf("Error en query %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerTodos() {
        $aTiposProductos = array();
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT
            idtipoproducto,
            nombre
        FROM tipos_productos
        ORDER BY idtipoproducto ASC";
        if($resultado = $mysqli->query($sql)) {
            while($fila = $resultado->fetch_assoc()) {
                $obj = new TipoProducto();
                $obj->idtipoproducto = $fila["idtipoproducto"];
                $obj->nombre = $fila["nombre"];
                $aTiposProductos[] = $obj;
            }
            $mysqli->close();
            return $aTiposProductos;
        } else {
            printf("Error en query %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }
}

?>