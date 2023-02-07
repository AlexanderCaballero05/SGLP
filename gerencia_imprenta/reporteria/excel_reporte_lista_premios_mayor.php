<?php 
require("../../conexion.php");
$sorteo=$_GET['sorteo'];

//header("Content-type: text/csv");
//header("Content-Disposition: attachment; filename=file.csv");

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=listaPremios ".$sorteo.".xls");

$c_max_terminacion = mysqli_query($conn, "SELECT * FROM sorteos_mayores_premios WHERE premios_mayores_id = 12 AND sorteos_mayores_id = '$sorteo' LIMIT 1 ");
$ob_max_terminacion = mysqli_fetch_object($c_max_terminacion);
$max_terminacion = $ob_max_terminacion->monto;

$premios = mysqli_query($conn, "SELECT *, LPAD( numero, 5, '0') as numero_formatted FROM archivo_pagos_mayor WHERE sorteo = $sorteo AND tipo_pago = 'T' AND total <= '$max_terminacion' ORDER BY numero ASC ");

if (mysqli_num_rows($premios)>0) 

{
	echo "<table class='table'>
			<thead>
                    <tr>
                    <th>Billete</th>
					<th>Total a Pagar</th>
                    </tr>
                    </thead><tbody>";
	$contador=1;
	while ( $row= mysqli_fetch_array($premios)) 
	{

	 	echo "<tr><td>".$row['numero_formatted']."</td>
	 			  <td>".$row['total']."</td>
	 		  </tr>";
	 	$contador++;
	}

	echo "</tbody></table>";
}
 
?>