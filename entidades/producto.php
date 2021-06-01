<?php

class Producto {
    private $idproducto;
    private $fk_idtipoproducto;
    private $nombre;
    private $cantidad;
    private $precio;
    private $descripcion;
    private $imagen;

    public function __construct() {
    }

    public function __get($propiedad) {
        return $this->$propiedad;
    }

    public function __set($propiedad, $valor) {
        $this->$propiedad = $valor;
    }

    public function cargarFormulario($request) {
        $this->idproducto = isset($request["id"]) ? $request["id"] : "";
        $this->fk_idtipoproducto = isset($request["lstTipoProducto"]) ? $request["lstTipoProducto"] : "";
        $this->nombre = isset($request["txtNombre"]) ? $request["txtNombre"] : "";
        $this->cantidad = isset($request["txtCantidad"]) ? $request["txtCantidad"] : "";
        $this->precio = isset($request["txtPrecio"]) ? $request["txtPrecio"] : "";
        $this->descripcion = isset($request["txtDescripcion"]) ? $request["txtDescripcion"] : "";
        if($_FILES["txtImagen"]["error"] === UPLOAD_ERR_OK) {
            $nombreAleatorio = date("YmdHims");
            $nombreOriginal = $_FILES["txtImagen"]["name"];
            $nombreTemporal = $_FILES["txtImagen"]["tmp_name"];
            $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
            $nombreArchivo = "$nombreAleatorio.$extension";
            move_uploaded_file($nombreTemporal, "files/$nombreArchivo");
            $this->imagen = $nombreArchivo;
        }
    }

    public function insertar() {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "INSERT INTO productos (
            fk_idtipoproducto,
            nombre,
            cantidad,
            precio,
            descripcion,
            imagen
        ) VALUES ("
            . $this->fk_idtipoproducto . ", '"
            . $this->nombre . "', "
            . $this->cantidad . ", "
            . $this->precio . ", '"
            . $this->descripcion . "', '"
            . $this->imagen . "')";
        if($mysqli->query($sql)) {
            $this->idproducto = $mysqli->insert_id;
        } else {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function actualizar() {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "UPDATE productos SET "
            . "fk_idtipoproducto = " . $this->fk_idtipoproducto . ", "
            . "nombre = '" . $this->nombre . "', "
            . "cantidad = " . $this->cantidad . ", "
            . "precio = " . $this->precio . ", "
            . "descripcion = '" . $this->descripcion . "', "
            . "imagen =  '" . $this->imagen . "' "
            . "WHERE idproducto = " . $this->idproducto;
        if($mysqli->query($sql)) {
            echo "<script>alert('¡Se ha actualizado los datos del producto exitosamente!')</script>";
        } else {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function eliminar() {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "DELETE FROM productos WHERE idproducto = " . $this->idproducto;
        if($mysqli->query($sql)) {
            echo "<script>alert('¡Se ha eliminado el registro del producto exitosamente!')</script>";
        } else {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorId() {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT
            idproducto, 
            fk_idtipoproducto,
            nombre,
            cantidad,
            precio,
            descripcion,
            imagen
        FROM productos
        WHERE idproducto = " . $this->idproducto;
        if($resultado = $mysqli->query($sql)) {
            if($fila = $resultado->fetch_assoc()) {
                $this->idproducto = $fila["idproducto"];
                $this->fk_idtipoproducto = $fila["fk_idtipoproducto"];
                $this->nombre = $fila["nombre"];
                $this->cantidad = $fila["cantidad"];
                $this->precio = $fila["precio"];
                $this->descripcion = $fila["descripcion"];
                $this->imagen = $fila["imagen"];
            }  
        } else {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerTodos() {
        $aProductos = array();
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT
            A.idproducto,
            A.fk_idtipoproducto,
            B.nombre AS tipo,
            A.nombre,
            A.cantidad,
            A.precio,
            A.descripcion,
            A.imagen
	    FROM productos A
        INNER JOIN tipos_productos B ON A.fk_idtipoproducto = B.idtipoproducto
	    ORDER BY A.nombre ASC";
        if($resultado = $mysqli->query($sql)){
            while($fila = $resultado->fetch_assoc()) {
                $obj = new Producto();
                $obj->idproducto = $fila["idproducto"];
                $obj->fk_idtipoproducto = $fila["fk_idtipoproducto"];
                $obj->tipo = $fila["tipo"];
                $obj->nombre = $fila["nombre"];
                $obj->cantidad = $fila["cantidad"];
                $obj->precio = $fila["precio"];
                $obj->descripcion = $fila["descripcion"];
                $obj->imagen = $fila["imagen"];
                $aProductos[] = $obj;
            }
            $mysqli->close();
            return $aProductos;
        } else {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }
}

?>