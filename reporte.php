<?php

$fecha = date("Y-m-d");
header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename=reporte-$fecha.csv");

include_once "config.php";
include_once "entidades/venta.php";

$pag = "Reporte de ventas";
$venta = new Venta();
$aVentas = $venta->obtenerTodas();
$fp = fopen('php://output', 'w');
fputs($fp, $bom = ( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
$aTitulos = array("Fecha", "Cliente", "Producto", "Cantidad", "Total");
fputcsv($fp, $aTitulos, ";");
foreach($aVentas as $venta) {
	$aDatos = array(
		$venta->fecha, 
		$venta->cliente, 
		$venta->producto,
		$venta->cantidad,
		$venta->total
	);
   	fputcsv($fp, $aDatos, ";");
}
fclose($fp);

?>