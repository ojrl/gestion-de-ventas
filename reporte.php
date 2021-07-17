<?php

include_once "config.php";
include_once "entidades/venta.php";

$fecha = date("Y-m-d");
header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename=reporte-$fecha.csv");

$pag = "Reporte de ventas";
$venta = new Venta();
$aVentas = $venta->obtenerTodas();
$file = fopen('php://output', 'w');
fputs($file, $bom = ( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
$aTitulos = array("Fecha", "Cliente", "Producto", "Cantidad", "Total");
fputcsv($file, $aTitulos, ";");
foreach($aVentas as $venta) {
	$aDatos = array(
		$venta->fecha, 
		$venta->cliente, 
		$venta->producto,
		$venta->cantidad,
		$venta->total
	);
   	fputcsv($file, $aDatos, ";");
}
fclose($file);

?>