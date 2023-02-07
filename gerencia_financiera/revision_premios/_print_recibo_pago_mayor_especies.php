<?php

require("../../conexion.php");
include('../../assets/mpdf/mpdf.php');

date_default_timezone_set("America/Tegucigalpa");
session_start();
if (isset($_GET['c'])) {	$cod_factura = $_GET['c']; } else { 	$cod_factura = $_SESSION['cod_impresion'];	}


 
$factura =  mysqli_query($conn, "SELECT * FROM mayor_pagos_recibos WHERE  transactioncode = $cod_factura ");
$ob_factura = mysqli_fetch_object($factura);
$fecha_venta = $ob_factura->transactiondate;
$identidad = $ob_factura->transactionwinnerid;
$total = $ob_factura->totalpayment;
$impto = $ob_factura->imptopayment;
$neto = $ob_factura->netopayment;
$codcore = $ob_factura->transactioncore;
$id_seccional = $ob_factura->transactionagency;
$identidad=$ob_factura->transactionwinnerid;
$nombre=$ob_factura->transactionwinnername;


$info_seccional = mysqli_query($conn, "SELECT nombre FROM seccionales WHERE cod_seccional = '$id_seccional' ");
$ob_seccional = mysqli_fetch_object($info_seccional);
$seccional = $ob_seccional->nombre;

$detalle_factura= mysqli_query($conn, "SELECT sorteo, numero, decimos, netopayment, totalpayment FROM mayor_pagos_detalle WHERE transactioncode=$cod_factura; ");

$mpdf = new mPDF('', array(70,400));

$mpdf->WriteHTML('

<style type="text/css">

.font-style {
	font-family: "Arial Black", "Arial Bold", Gadget, sans-serif;
	color: #000000;
	font-size: 18;
	padding: 5;
	font-weight: bold;
}

@page  {
//  size:8in 8in; 
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
	font-size: 17;
	padding: 5;
	font-weight: bold;

}

</style>


<div  class="Section1"> 
<div >
<b>

PANI<br>
Lotería Mayor<br>
Pago de Premios<br><br>

 Recibo #:'.$cod_factura.'<br>
 Fecha:'.$fecha_venta.'<br>
<br>
Agencia:'.$seccional.'<br>
Pagado a: <br>'.$nombre.'<br>
Identidad:'.$identidad .'<br><br>
Detalle:
<br>

<table align="center" class="Section1">
<thead>
  <tr>
	<td style="text-align: center;">Sorteo</td>
	<td style="text-align: center;">Num.</td>
	<td style="text-align: center;">Dec.</td>
	<td style="text-align: center;">Descr.</td>
  </tr>
</thead>
<tbody>');

		while ($row_detalle= mysqli_fetch_array($detalle_factura))
		{  
			$_sorteo=$row_detalle['sorteo'];
			$_numero=$row_detalle['numero'];

			$query_detalle_premio= mysqli_query($conn, "SELECT desc_premio descripcion_respaldo FROM sorteos_mayores_premios WHERE sorteos_mayores_id=$_sorteo and numero_premiado_mayor=$_numero");
			if (!mysqli_num_rows($query_detalle_premio)>0) 
			{
				echo mysqli_error();
			}
			while ($row_especie=mysqli_fetch_array($query_detalle_premio)) 
			{
			  $detalle=$row_especie['descripcion_respaldo'];
			}

		$mpdf->	WriteHTML('<tr>
		   <td style=" text-align: center;">'.$_sorteo.'</td>
		   <td style=" text-align: center;">'.$_numero.'</td>
		   <td style=" text-align: center;">'.$row_detalle['decimos'].'</td>
		   <td style=" text-align: right;">'.$detalle.'</td>
		   </tr>');
		}


		 $mpdf->WriteHTML(' 	
		 </tbody>
		 </table>
		 <br><br>

		<p align="center" class="Section1">______________________________
		<br>
		Firma del cliente
		<br>
		<br>
		Nota: Este recibo es valido sin<br>
		la firma y sello del cajero
		<p>


		</b>

		</div>
		</div>');

$mpdf->Output();

?>
