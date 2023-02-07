<?php 
require("../../conexion.php");
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=facturas_mayor.xls");
$sorteo=$_GET['sorteo'];


$query_detalle = mysqli_query($conn, "SELECT detalle_venta, tipo_pago, estado, sorteo, numero, registro, total , impto, neto  FROM archivo_pagos_mayor where sorteo = $sorteo;");

if (mysqli_num_rows($query_detalle)>0) 
{
	echo "<table class='table'>
			<thead>
				<tr><th>No.</th>
					<th>Detalle Venta</th>
					<th>Tipo Pago</th>
					<th>Estado</th>
					<th>Sorteo</th>
					<th>Numero</th>					
					<th>Registro</th>
					<th>Total</th>
					<th>Impto</th>
					<th>Neto</th></tr></thead><tbody>";
	$contador=1;
	while ( $row= mysqli_fetch_array($query_detalle)) 
	{
	 	$detalle_venta                  = $row['detalle_venta'];
	 	$tipo_pago                		= $row['tipo_pago'];
	 	$estado                 		= $row['estado']; 
	 	$sorteo                			= $row['sorteo'];
	 	$numero                 		= $row['numero'];
	 	$Registro                       = $row['registro'];
	 	$totalpayment                   = $row['total'];
	 	$imptopayment          		    = $row['impto'];
	 	$netopayment                    = $row['neto'];

	 	echo "<tr><td>".$contador."</td>
	 			  <td>".$fecha."</td>
	 			  <td>".$agencia."</td>
	 			  <td>".$cajero."</td>
	 			  <td>".$fecha_recepcion."</td>
	 			  <td>".$remesa."</td>
	 			  <td>".$sorteo."</td>
	 			  <td>".$numero."</td>
	 			  <td>".$decimos."</td>
	 			  <td>".$totalpayment."</td>
	 			  <td>".$imptopayment."</td>
	 			  <td>".$netopayment."</td>
	 		  </tr>";
	 	$contador++;
	}

	echo "</tbody></table>";
}
else
{

}
 
 ?>