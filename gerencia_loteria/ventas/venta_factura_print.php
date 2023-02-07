<?php
include('./mpdf/mpdf.php');
require("./conexion.php");

date_default_timezone_set("America/Tegucigalpa");

$cod_factura = $_GET['c'];

if (isset($_GET['c'])) {
$cod_factura = $_GET['c'];
$reimpresion = date("Y-m-d h:m:s");

}else{
$cod_factura = $_SESSION['cod_impresion'];
}


$factura =  mysql_query("SELECT  a.fecha_venta, a.id_sorteo, a.identidad_comprador, a.nombre_comprador, a.total_neto, a.total_bruto , a.descuento, a.id_seccional, b.nombre as nombre_agencia, c.descripcion_producto FROM transaccional_ventas as a INNER JOIN fvp_seccionales as b INNER JOIN mto_productos as c ON a.cod_producto = c.cod_producto AND a.id_seccional = b.id WHERE cod_factura = $cod_factura ");

if ($factura ===  false) {
echo mysql_error();
}

$ob_factura   = mysql_fetch_object($factura);
$id_sorteo    = $ob_factura->id_sorteo;
$fecha_venta  = $ob_factura->fecha_venta;
$identidad 	  = $ob_factura->identidad_comprador;
$nombre 	  = $ob_factura->nombre_comprador;
$total 	 	  = $ob_factura->total_neto;
$descuento 	  = $ob_factura->descuento;
$agencia 	  = $ob_factura->nombre_agencia;
$descripcion_producto = $ob_factura->descripcion_producto;


if (isset($reimpresion)) {

$fecha_venta = $fecha_venta."<br>"."Reimpresion: ".$reimpresion;

}

$mpdf = new mPDF('', array(70,400));


$mpdf->WriteHTML('


<style type="text/css">

@page  {
    margin: 0mm 0mm 0mm 0mm;
}

@media print
{
}

.Section1{
width: 100%;
text-align: center;
	font-family: "Arial Black", "Arial Bold", Gadget, sans-serif;
	color: #000000;
	font-size: 14;
	padding: 5;
	font-weight: bold;
}

</style>

<div class="Section1">
<div >
<b>
PATRONATO NACIONAL DE LA INFANCIA<br>

<br>
<b>FACTURA #: '.$cod_factura.'</b><br>
Fecha de Transaccion:  '.$fecha_venta.'<br>
No. Sorteo: '.$id_sorteo.'<br>
Cliente: '.$nombre.'<br>
<br>
Concepto:
Pago De '.$descripcion_producto.'<br>
<br>

<table width= "100%">
<tr>
<td>Monto Factura: </td>
<td align = "right">'.number_format($total, 2, '.', ',').' Lps.</td>
</tr>
</table>

<br>
<br>
<hr>
<br>
Firma del cliente
<br>
<br>

</b>

</div>
</div>


');

$mpdf->Output();

?>
