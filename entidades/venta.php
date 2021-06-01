<?php

class Venta {
    private $idventa;
    private $fecha;
    private $fk_idcliente;
    private $fk_idproducto;
    private $cantidad;
    private $preciounitario;
    private $total;

    public function __construct() {
    }

    public function __get($propiedad) {
        return $this->$propiedad;
    }

    public function __set($propiedad, $valor) {
        $this->$propiedad = $valor;
    }

    public function cargarFormulario($request) {
        $this->idventa = isset($request["id"]) ? $request["id"] : "";
        if(isset($request["lstDia"]) && isset($request["lstMes"]) && isset($request["lstAnio"]) && isset($request["txtHora"])) {
            $this->fecha = $request["lstAnio"] . "-" .  $request["lstMes"] . "-" .  $request["lstDia"] . " " . $request["txtHora"];
        }
        $this->fk_idcliente = isset($request["lstCliente"]) ? $request["lstCliente"] : "";
        $this->fk_idproducto = isset($request["lstProducto"]) ? $request["lstProducto"] : "";
        $this->cantidad = isset($request["txtCantidad"]) ? $request["txtCantidad"] : "";
        $this->preciounitario = isset($request["txtPrecio"]) ? $request["txtPrecio"] : "";
        $this->total = isset($request["txtTotal"]) ? $request["txtTotal"] : "";
    }

    public function insertar() {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "INSERT INTO ventas (
            fecha,
            fk_idcliente,
            fk_idproducto,
            cantidad,
            preciounitario,
            total
            ) VALUES ('"
            . $this->fecha . "', "
            . $this->fk_idcliente . ", "
            . $this->fk_idproducto . ", "
            . $this->cantidad . ", "
            . $this->preciounitario . ", "
            . $this->total . ");";
        if($mysqli->query($sql)) {
            $this->idventa = $mysqli->insert_id;
        } else {
            printf("Error en query %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function actualizar() {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "UPDATE ventas SET "
            . "fecha = '" . $this->fecha . "', "
            . "fk_idcliente = " . $this->fk_idcliente . ", "
            . "fk_idproducto = " . $this->fk_idproducto . ", "
            . "cantidad = " . $this->cantidad . ", "
            . "preciounitario = " . $this->preciounitario . ", "
            . "total = " . $this->total . " "
            . "WHERE idventa = " . $this->idventa;
        if(!$mysqli->query($sql)) {
            printf("Error en query %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function eliminar() {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "DELETE FROM ventas WHERE idventa = " . $this->idventa;
        if(!$mysqli->query($sql)){
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorId() {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT 
            idventa,
            fecha,
            fk_idcliente,
            fk_idproducto,
            cantidad,
            preciounitario,
            total
            FROM ventas
            WHERE idventa = " . $this->idventa;
        if($resultado = $mysqli->query($sql)) {
            if($fila = $resultado->fetch_assoc()) {
                $this->idventa = $fila["idventa"];
                $this->fecha = $fila["fecha"];
                $this->fk_idcliente = $fila["fk_idcliente"];
                $this->fk_idproducto = $fila["fk_idproducto"];
                $this->cantidad = $fila["cantidad"];
                $this->preciounitario = $fila["preciounitario"];
                $this->total = $fila["total"];
            }  
        } else {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerTodas() {
        $aVentas = array();
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT 
            A.idventa,
            A.fecha,
            A.fk_idcliente,
            B.nombre AS cliente,
            A.fk_idproducto,
            C.nombre AS producto,
            A.cantidad,
            A.preciounitario,
            A.total
            FROM ventas A
            INNER JOIN clientes B ON A.fk_idcliente = B.idcliente
            INNER JOIN productos C ON A.fk_idproducto = C.idproducto
            ORDER BY A.idventa ASC";
        if($resultado = $mysqli->query($sql)) {
            while($fila = $resultado->fetch_assoc()) {
                $obj = new Venta();
                $obj->idventa = $fila["idventa"];
                $obj->fecha = $fila["fecha"];
                $obj->fk_idcliente = $fila["fk_idcliente"];
                $obj->cliente = $fila["cliente"];
                $obj->fk_idproducto = $fila["fk_idproducto"];
                $obj->producto = $fila["producto"];
                $obj->cantidad = $fila["cantidad"];
                $obj->preciounitario = $fila["preciounitario"];
                $obj->total = $fila["total"];
                $aVentas[] = $obj;
            }
            $mysqli->close();
            return $aVentas;
        } else {
            printf("Error en query %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerFacturacionMensual($mes) {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT SUM(total) AS facturacion FROM ventas WHERE MONTH(fecha) = $mes";
        if($resultado = $mysqli->query($sql)) {
            if($fila = $resultado->fetch_assoc()) {
                $facturacionMensual = $fila["facturacion"];
                $mysqli->close();
                return $facturacionMensual;
            }
        } else {
            printf("Error en query %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerFacturacionAnual($anio) {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT SUM(total) AS facturacion FROM ventas WHERE YEAR(fecha) = $anio";
        if($resultado = $mysqli->query($sql)) {
            if($fila = $resultado->fetch_assoc()) {
                $facturacionAnual = $fila["facturacion"];
                $mysqli->close();
                return $facturacionAnual;
            }
        } else {
            printf("Error en query %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }
}

?>