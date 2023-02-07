
<?php 
require('../../template/header.php'); 
$user_name=$_SESSION['nombre'];

$_remesa=$_GET['remesa']; 
$s_year=$_GET['year']; 

echo '<table align="center" style="font-family: Arial; font-size: 18pt; margin-top:-25px">
			<tr><td  width="20%"></td>
				<td width="60%" style="font-family: Arial; font-size: 18pt;"></td>
				<td width="20%"></td>
		</tr> 
		<tr><td colspan="3"> . </td></tr>
		<tr><td width="20%"></td>
			<td  width="60%"> <div align="center"><label style=" font-family: Arial; font-size:14pt;" >Patronato Nacional de la Infancia PANI <br> Departamento de Revision de Premios<br>Liquidación de Remesa No.  '. $_remesa .'  </label></div></td>
			<td width="20%"></td>
		</tr>      
		</table><br>';

$cantidad_acumulado_final=0;   $neto_acumulado_final=0;   $contador_sorteo=0; 
$query_sorteos= mysqli_query($conn, "SELECT  a.sorteo, b.fecha_sorteo FROM menor_pagos_detalle a , sorteos_menores b  where a.sorteo=b.id and  a.estado_revision=1 and a.remesa=$_remesa and  ano_remesa and  ano_remesa = $s_year group by  sorteo;");
$query_sorteos2=mysqli_query($conn, "SELECT  a.sorteo, b.fecha_sorteo FROM menor_pagos_detalle a , sorteos_menores b  where a.sorteo=b.id and  a.estado_revision=1 and a.remesa=$_remesa and  ano_remesa and  ano_remesa = $s_year group by  sorteo;");
$query_sorteos3=mysqli_query($conn, "SELECT  a.sorteo, b.fecha_sorteo FROM menor_pagos_detalle a , sorteos_menores b  where a.sorteo=b.id and  a.estado_revision=1 and a.remesa=$_remesa and  ano_remesa and  ano_remesa = $s_year group by  sorteo;");		 

?>

<div class="table-responsive">
<table  class="table table-hover table-sm table-bordered">
		<thead><tr><th>Sorteo</th>
				<th>Fecha Sorteo</th> 
				<th>Descripción</th> 
				<th>Cant.</th>
				<th>Sub - Total</th> 
				<th>Total Revisado</th>
				<th>Total Banco</th> 
			</tr>   	       			
	</thead>
	<tbody>  
	<?php

	$query_entregado=mysqli_query($conn, "SELECT  COUNT(*) cantidad, SUM(a.neto) total_neto
						FROM menor_pagos_detalle a, menor_pagos_recibos b 
						WHERE  a.transactioncode=b.transactioncode and  a.remesa=$_remesa and a.transactionstate in (1,3) and  ano_remesa = $s_year and  ( a.estado_revision in (0,1,2) or a.estado_revision is null ) GROUP BY remesa=$_remesa");


	if (mysqli_num_rows($query_entregado)>0) 
	{
		while ( $_rowentregado=mysqli_fetch_array($query_entregado) ) 
		{ 
			$_cantidad_entregada_total=$_rowentregado['cantidad']; $_monto_entregado_total=$_rowentregado['total_neto']; 

			echo "<tr class='table-info'><td colspan='3'></td><td align='center' ><label> ".number_format($_cantidad_entregada_total)."</label></td><td colspan='2'></td><td align='right' ><label>".number_format($_monto_entregado_total,2,'.',',')."</label></td></tr>";
		}
	}


		while ($row_sorteo=mysqli_fetch_array($query_sorteos)) 
		{     
				$sorteo=$row_sorteo['sorteo']; $fecha_sorteo=$row_sorteo['fecha_sorteo'];
				$query = mysqli_query($conn, "SELECT numero_premiado_menor FROM sorteos_menores_premios where sorteos_menores_id=$sorteo and premios_menores_id in(1,3);");
				if ($query==false) { echo mysqli_error($conn); }

				while($row=mysqli_fetch_array($query))  {   $array_numeros[] = $row['numero_premiado_menor']; }
				
				$query_series = mysqli_query($conn, "SELECT numero_premiado_menor FROM sorteos_menores_premios where sorteos_menores_id=$sorteo and (premios_menores_id =2 or premios_menores_id >3);");

						if ($query_series==false) { echo mysqli_error($conn); }
						while($row_series=mysqli_fetch_array($query_series))  { $array_series[] = $row_series['numero_premiado_menor']; }
				
							$query_numeros=mysqli_query($conn, "(SELECT a.numero numero, a.neto valor, COUNT(*) cantidad, SUM(a.neto) total_neto, 'a' as 'orden'
							FROM menor_pagos_detalle a, menor_pagos_recibos b 
							WHERE 
							a.transactioncode=b.transactioncode and  a.remesa=$_remesa and a.transactionstate in (1,3) and  a.estado_revision in (1,1)   and  ano_remesa = $s_year and
							a.sorteo=$sorteo and a.serie not in( ".implode(',',$array_series)." )  and  a.numero in( ".implode(',',$array_numeros)." )
							GROUP BY a.numero  
							)UNION
							(SELECT a.serie serie,  a.neto valor, COUNT(a.serie) cantidad, SUM(a.neto) total_neto, 'b' as 'orden'
							FROM menor_pagos_detalle a, menor_pagos_recibos b 
							WHERE 
							a.transactioncode=b.transactioncode and a.transactionstate in (1,3) and  a.remesa=$_remesa and  a.estado_revision in (1,1)  and  ano_remesa = $s_year and
							a.sorteo=$sorteo  and  a.serie in( ".implode(',',$array_series)." )  
							GROUP BY a.serie , valor   ) order by orden , valor desc ;");

						if ($query_numeros==false){ echo mysqli_error($conn); }

							$total_acumulado=0; $impto_acumulado=0;  $neto_acumulado=0;  $cantidad_acumulado=0;  $contador=1;                            
							while ($row_numeros=mysqli_fetch_array($query_numeros)) 
							{ 				                                      

									if (  in_array( $row_numeros['numero'],  $array_numeros)  )  { $palabra='Numero';  } else {  $palabra='Serie'; }				                                    

									$cantidad_acumulado=$cantidad_acumulado+$row_numeros['cantidad'];    $neto_acumulado=$neto_acumulado+$row_numeros['total_neto'];

									echo "<tr><td align='center'>".$sorteo."</td>  <td align='center'>".$fecha_sorteo."</td> 
											<td align='center'>".$palabra."   ".$row_numeros['numero']." por L. ".$row_numeros['valor']."</td>  
											<td align='center'>".$row_numeros['cantidad']."</td>    
											<td align='right'>".number_format($row_numeros['total_neto'],2,'.',',')."</td><td align='center'></td>
											<td align='right'></td></tr>";
									$contador++;      
							}
									echo "<tr><td colspan='3' align='center'><label>Total</label></td>
											<td align='center'><label>".number_format($cantidad_acumulado)."</label></td>
											<td align='center'></td>
											<td align='right'><label>".number_format($neto_acumulado,2,'.',',')."</label></td>
											<td align='right'></td></tr>";
									$cantidad_acumulado_final=$cantidad_acumulado_final+$cantidad_acumulado;   $neto_acumulado_final=$neto_acumulado_final+$neto_acumulado;
									unset($array_series);  unset($array_numeros);
		}

			////// SECCION DE LAS NOtas de DEBITO
			unset($sorteo); unset($fecha_sorteo);
			if (mysqli_num_rows($query_sorteos2)>0) 
			{
				echo "<tr><td colspan='6' align='center'><label> - Notas de debito  -- </label></td></tr>";
				$contador_notas=0; $neto_acumulado_notas=0;
				while ($row_sorteo2=mysqli_fetch_array($query_sorteos2) )
				{
					$sorteo=$row_sorteo2['sorteo']; $fecha_sorteo=$row_sorteo2['fecha_sorteo'];
					$query_notas= mysqli_query($conn, "SELECT numero, serie, neto, incidencia, tipo_documento FROM rp_notas_credito_debito_menor where remesa=$_remesa and sorteo=$sorteo "); 
					if (mysqli_num_rows($query_notas)>0) 
					{ 
						
						while ($_row_notas=mysqli_fetch_array($query_notas)) 
						{
							$numero=$_row_notas['numero'];   $serie=$_row_notas['serie'];   $neto=$_row_notas['neto'];  $incidencia= $_row_notas['incidencia']; $tipo_documento=$_row_notas['tipo_documento'];
							
							if ($incidencia<>4) {
							if ($tipo_documento<>2) 
							{
								$neto_acumulado_notas=$neto_acumulado_notas+$neto;
							}
							}

							echo "<tr><td align='center'>".$sorteo."</td>  <td align='center'>".$fecha_sorteo."</td> 
									<td align='center'> Billete número ".$numero.", seríe ".$serie."</td>  
									<td align='center'> 1 </td>    
									<td align='right'>".number_format($neto,2,'.',',')."</td><td align='center'></td>
									<td align='right'></td></tr>"; 
							
							if ($incidencia<>4) {
							if ($tipo_documento<>2) 
							{
								$contador_notas ++;
							}
							}
						}				                                         
					} 
				}
				echo "<tr><td colspan='3' align='center'><label>Total</label></td>
												<td align='center'><label>".number_format($contador_notas)."</label></td>
												<td align='center'></td>
												<td align='right'><label></label></td>
												<td align='right'><label>".number_format($neto_acumulado_notas,2,'.',',')."</label></td></tr>";
			}  


			//// seccion del total

unset($sorteo); unset($fecha_sorteo);
if (mysqli_num_rows($query_sorteos3)>0) 
{
	
while ($row_sorteo3=mysqli_fetch_array($query_sorteos3) )
{
			$sorteo=$row_sorteo3['sorteo']; $fecha_sorteo=$row_sorteo3['fecha_sorteo']; $neto_acumulado_faltante=0;   $contador_faltante=0;
			$query_faltantes= mysqli_query($conn, "SELECT numero, serie, netopayment neto, registertype FROM rp_faltantes_sobrantes_menor where remesa=$_remesa and sorteo=$sorteo"); 
			if (mysqli_num_rows($query_faltantes)>0) 
			{ 
					echo " <tr><td colspan='6' align='center'><label> - Faltantes y Sobrantes  -- </label></td></tr>";
					$contador_faltante=0;
					while ($_row_notas3=mysqli_fetch_array($query_faltantes)) 
					{
									$numero=$_row_notas3['numero'];  $serie=$_row_notas3['serie'];  $neto=$_row_notas3['neto']; $tipo=$_row_notas3['registertype'];
									echo "<tr><td align='center'>".$sorteo."</td>  <td align='center'>".$fecha_sorteo."</td> 
												<td align='center'> ".$tipo." Billete número ".$numero.", seríe ".$serie."</td>  
												<td align='center'> 1 </td>    
												<td align='right'>".number_format($neto,2,'.',',')."</td><td align='center'></td></tr>"; 
									$neto_acumulado_faltante=$neto_acumulado_faltante+$neto;   $contador_faltante ++;  
					}
					
									echo "<tr><td colspan='3' align='center'><label>Total</label></td>
												<td align='center'><label>".number_format($contador_faltante)."</label></td>
												<td align='center'></td>
												<td align='right'></td>
												<td align='right'><label>".number_format($neto_acumulado_faltante,2,'.',',')."</label></td></tr>";
					//if ($tipo=='Sobrante') {  $contador_faltante=0;    $neto_acumulado_faltante=0;         }
					

					$contador_faltante=0;    $neto_acumulado_faltante=0;                 
			}    
			else {   echo mysqli_error($conn);  }
}
}

$cantidad_acumulado_final   = $_cantidad_entregada_total -$contador_faltante-$contador_notas;
$neto_acumulado_final_total = $_monto_entregado_total    -$neto_acumulado_faltante-$neto_acumulado_notas;

echo "<tr><td colspan='7' align='center'><label> - Ultima Linea  -- </label></td></tr>
		<tr class='table-success'><td colspan='3' align='center'><label>Total General</label></td>
			<td align='center'><label>".number_format($cantidad_acumulado_final)."</label></td>
			<td align='center'></td>
			<td align='right'><label>".number_format($neto_acumulado_final_total,2,'.',',')."</label></td>
			<td align='right'><label>".number_format($neto_acumulado_final_total,2,'.',',')."</label></td>
		</tr>";                                                          
?> 






	

	</tbody>
</table>    	       			
</div>

<?php 
echo "<br><br><br> <p align='center'>_______________________________________</p><p align='center'>".$user_name."</p><p align='center'>Jefatura de Revisión de Premios</p><div>";
?>

</div>
<script type="text/javascript"> 
window.print(); 
setTimeout(window.close, 1000);
</script>
