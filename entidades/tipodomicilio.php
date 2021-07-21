<?php

class TipoDomicilio {
    private $idtipodomicilio;
    private $nombre;

    public function __construct() {
    }

    public function __get($propiedad) {
        return $this->$propiedad;
    }

    public function __set($propiedad, $valor) {
        $this->$propiedad = $valor;
    }

    public function obtenerPorId() {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT
            idtipodomicilio,
            nombre
        FROM tipos_domicilios
        WHERE idtipodomicilio = " . $this->idtipodomicilio;
        if($resultado = $mysqli->query($sql)) {
            if($fila = $resultado->fetch_assoc()) {
                $this->idtipodomicilio = $fila["idtipodomicilio"];
                $this->nombre = $fila["nombre"];
            }
        } else {
            printf("Error en query %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerTodos() {
        $aTiposDomicilios = array();
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT
            idtipodomicilio,
            nombre
        FROM tipos_domicilios
        ORDER BY idtipodomicilio ASC";
        if($resultado = $mysqli->query($sql)) {
            while($fila = $resultado->fetch_assoc()) {
                $obj = new TipoDomicilio();
                $obj->idtipodomicilio = $fila["idtipodomicilio"];
                $obj->nombre = $fila["nombre"];
                $aTiposDomicilios[] = $obj;
            }
            $mysqli->close();
            return $aTiposDomicilios;
        } else {
            printf("Error en query %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

}

?>