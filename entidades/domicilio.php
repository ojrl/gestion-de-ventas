<?php

class Domicilio {
    private $iddomicilio;
    private $fk_idtipodomicilio;
    private $fk_idprovincia;
    private $fk_idlocalidad;
    private $direccion;
    private $fk_idcliente;

    public function __construct() {
    }

    public function __get($propiedad) {
        return $this->$propiedad;
    }

    public function __set($propiedad, $valor) {
        $this->$propiedad = $valor;
    }

    public function insertar() {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "INSERT INTO domicilios (
            fk_idtipodomicilio,
            fk_idprovincia,
            fk_idlocalidad,
            direccion,
            fk_idcliente
        ) VALUES ("
            . $this->fk_idtipodomicilio . ", '"
            . $this->fk_idprovincia . "', "
            . $this->fk_idlocalidad . ", "
            . $this->direccion . ", '"
            . $this->fk_idcliente . "')";
        if($mysqli->query($sql)) {
            $this->iddomicilio = $mysqli->insert_id;
        } else {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function eliminarPorCliente($idCliente){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "DELETE FROM domicilios WHERE fk_idcliente = " . $idCliente;
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorCliente($idCliente){
        $aDomicilios = null;
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT 
            iddomicilio,
            fk_idtipodomicilio,
            fk_idprovincia,
            fk_idlocalidad,
            direccion,
            fk_idcliente
            FROM domicilios 
            WHERE fk_idcliente = $idCliente
            ORDER BY iddomicilio ASC";
        if($resultado = $mysqli->query($sql)) {
            while($fila = $resultado->fetch_assoc()) {
                $aDomicilios[] = array(
                    "iddomicilio" => $fila["iddomicilio"],
                    "fk_idtipodomicilio" => $fila["fk_idtipodomicilio"],
                    "fk_idprovincia" => $fila["fk_idprovincia"],
                    "fk_idlocalidad" => $fila["fk_idlocalidad"],
                    "direccion" => $fila["direccion"],
                    "fk_idcliente" => $fila["fk_idcliente"]
                );
            }            
            $mysqli->close();
            return $aDomicilios;
        } else {
            printf("Error en query %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerTodos() {
        $aDomicilios = array();
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT
            iddomicilio,
            fk_idtipodomicilio,
            fk_idprovincia,
            fk_idlocalidad,
            direccion,
            fk_idcliente
        FROM domicilios
        ORDER BY iddomicilio ASC";
        if($resultado = $mysqli->query($sql)) {
            while($fila = $resultado->fetch_assoc()) {
                $obj = new Domicilio();
                $obj->iddomicilio = $fila["iddomicilio"];
                $obj->fk_idtipodomicilio = $fila["fk_idtipodomicilio"];
                $obj->fk_idprovincia = $fila["fk_idprovincia"];
                $obj->fk_idlocalidad = $fila["fk_idlocalidad"];
                $obj->direccion = $fila["direccion"];
                $obj->fk_idcliente = $fila["fk_idcliente"];
                $aDomicilios[] = $obj;
            }
            $mysqli->close();
            return $aDomicilios;
        } else {
            printf("Error en query %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }
}

?>