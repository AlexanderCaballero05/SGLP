<?php 
require("../../conexion.php");
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=facturas_mayor.xls");
$sorteo=$_GET['sorteo'];


$query_detalle = mysqli_query($conn, "SELECT a.transactiondate, b.transactionagencyname, b.transactionusername, a.fecha_recepcion_banco, a.remesa, a.sorteo, a.numero, a.decimos, a.totalpayment, a.imptopayment, a.netopayment
									  FROM mayor_pagos_detalle a, mayor_pagos_recibos b 
									  WHERE 
									  a.transactioncode = b.transactioncode and 
									  a.transactionstate in (1,3)  		    and 
									  a.sorteo = $sorteo;");

if (mysqli_num_rows($query_detalle)>0) 
{
	echo "<table class='table'>
			<thead>
				<tr><th>No.</th>
					<th>Fecha de Pago</th>
					<th>Agencia</th>
					<th>Cajero</th>
					<th>Fecha Recepcion</th>
					<th>Remesa</th>
					<th>Sorteo</th>
					<th>Numero</th>
					<th>Decimos</th>
					<th>Total</th>
					<th>Impto</th>
					<th>Neto</th></tr></thead><tbody>";
	$contador=1;
	while ( $row= mysqli_fetch_array($query_detalle)) 
	{
	 	$fecha                  = $row['transactiondate'];
	 	$agencia                = $row['transactionagencyname'];
	 	$cajero                 = $row['transactionusername'];
	 	$fecha_recepcion        = $row['fecha_recepcion_banco'];
	 	$remesa                 = $row['remesa'];
	 	$sorteo                 = $row['sorteo'];
	 	$numero                 = $row['numero'];
	 	$decimos                = $row['decimos'];
	 	$totalpayment           = $row['totalpayment'];
	 	$imptopayment           = $row['imptopayment'];
	 	$netopayment            = $row['netopayment'];

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