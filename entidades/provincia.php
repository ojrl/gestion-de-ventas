<?php

class Provincia{
    private $idprovincia;
    private $nombre;

    public function __get($propiedad) {
        return $this->$propiedad;
    }

    public function __set($propiedad, $valor) {
        $this->$propiedad = $valor;
        return $this;
    }

    public function obtenerTodas(){
        $aProvincias = array();
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT 
            idprovincia, 
            nombre 
            FROM provincias 
            ORDER BY nombre ASC";
        if($resultado = $mysqli->query($sql)){
            while($fila = $resultado->fetch_assoc()) {
                $obj = new Provincia();
                $obj->idprovincia = $fila["idprovincia"];
                $obj->nombre = $fila["nombre"];
                $aProvincias[] = $obj;
            }            
            $mysqli->close();
            return $aProvincias;
        } else {
            printf("Error en query %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

}

?>