<?php 
//require("../../conexion.php");



function sorteos_mayores()
{
	$conn = mysqli_connect('192.168.15.248:3306', 'SVR_APP', 'softlotpani**', 'pani') or die('No se pudo conectar: ' . mysqli_error());

		$result_query_sorteo= mysqli_query($conn, "SELECT a.id, fecha_sorteo ,ADDDATE(fecha_sorteo, INTERVAL 45 DAY) vencimiento, (45 - DATEDIFF(CURRENT_DATE, fecha_sorteo)) dias FROM sorteos_mayores a, archivo_pagos_mayor b where a.id=b.sorteo and fecha_sorteo <= CURRENT_DATE and a.id>1210  group by a.id order by a.id desc");

	

						if (mysqli_num_rows($result_query_sorteo)>0 ) 
						{
							while($row_sorteos=mysqli_fetch_array($result_query_sorteo,MYSQLI_ASSOC))  
							{
								echo "<option value = '".$row_sorteos['id']."'>No.".$row_sorteos['id']." | Fecha ".$row_sorteos['fecha_sorteo']." | Vence ".$row_sorteos['vencimiento']." |  ".$row_sorteos['dias']." días</option>" ;	
							}
						}
						else
						{
							echo mysqli_error();
						}		
}


function sorteos_menores()
{
	$conn = mysqli_connect('192.168.15.248:3306', 'SVR_APP', 'softlotpani**', 'pani') or die('No se pudo conectar: ' . mysqli_error());

		$result_query_sorteo_menor= mysqli_query($conn, "SELECT a.id, fecha_sorteo ,ADDDATE(fecha_sorteo, INTERVAL 45 DAY) vencimiento, (45 - DATEDIFF(CURRENT_DATE, fecha_sorteo)) dias FROM sorteos_menores a, archivo_pagos_menor b where a.id=b.sorteo and fecha_sorteo <= CURRENT_DATE and a.id>3210  group by a.id order by a.id desc");

						if (mysqli_num_rows($result_query_sorteo_menor)>0 ) 
						{
							while($row_sorteos_menores=mysqli_fetch_array($result_query_sorteo_menor,MYSQLI_ASSOC))  
							{
								echo "<option value = '".$row_sorteos_menores['id']."'>No.".$row_sorteos_menores['id']." | Fecha ".$row_sorteos_menores['fecha_sorteo']." | Vence ".$row_sorteos_menores['vencimiento']." |  ".$row_sorteos_menores['dias']." días</option>" ;	
							}
						}
						else
						{
							echo mysqli_error();
						}		
}


//print_r(sorteos_mayores());

 ?>