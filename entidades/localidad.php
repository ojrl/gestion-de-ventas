<?php

class Localidad{
    private $idlocalidad;
    private $nombre;
    private $fk_idprovincia;
    private $cod_postal;

    public function __get($propiedad) {
        return $this->$propiedad;
    }

    public function __set($propiedad, $valor) {
        $this->$propiedad = $valor;
    }

    public function obtenerPorId() {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT 
            idlocalidad,
            nombre, 
            cod_postal,
            fk_idprovincia
            FROM localidades
            WHERE fk_idprovincia = " . $this->idlocalidad;
        if($resultado = $mysqli->query($sql)) {
            if($fila = $resultado->fetch_assoc()) {
                $this->idlocalidad = $fila["idlocalidad"];
                $this->nombre = $fila["nombre"];
                $this->cod_postal = $fila["cod_postal"];
                $this->fk_idprovincia = $fila["fk_idprovincia"];
            }
        } else {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorProvincia($idProvincia){
        $aLocalidades = null;
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT 
            idlocalidad,
            nombre, 
            cod_postal,
            fk_idprovincia
            FROM localidades 
            WHERE fk_idprovincia = $idProvincia
            ORDER BY nombre ASC";
        if($resultado = $mysqli->query($sql)) {
            while($fila = $resultado->fetch_assoc()) {
                $aLocalidades[] = array(
                    "idlocalidad" => $fila["idlocalidad"],
                    "nombre" => $fila["nombre"],
                    "cod_postal" => $fila["cod_postal"],
                    "fk_idprovincia" => $fila["fk_idprovincia"]
                );
            }            
            $mysqli->close();
            return $aLocalidades;
        } else {
            printf("Error en query %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerTodas() {
        $aLocalidades = array();
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT 
            idlocalidad,
            nombre, 
            cod_postal,
            fk_idprovincia
            FROM localidades 
            ORDER BY nombre ASC";
        if($resultado = $mysqli->query($sql)){
            while($fila = $resultado->fetch_assoc()) {
                $obj = new Localidad();
                $obj->idlocalidad = $fila["idlocalidad"];
                $obj->nombre = $fila["nombre"];
                $obj->cod_postal = $fila["cod_postal"];
                $obj->fk_idprovincia = $fila["fk_idprovincia"];
                $aLocalidades[] = $obj;
            }            
            $mysqli->close();
            return $aLocalidades;
        } else {
            printf("Error en query %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }



}

?>