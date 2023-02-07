<?php 
require('../../template/header.php'); 
$name_revisor="";
if (isset($_GET['usuario_id'])) 
{

$usuario_id=$_GET['usuario_id'];
$s_year =$_GET['year'];

$query_usuario=mysqli_query($conn, "SELECT nombre_completo FROM pani_usuarios where id=$usuario_id");
	while ($row_u=mysqli_fetch_array($query_usuario)) 
	{
		$name_revisor=$row_u['nombre_completo'];	
	}
}	
else
{
$usuario_id=$_SESSION['id_usuario'];
$name_revisor= "asignada a: <br>".$_SESSION['nombre_usuario'];	
}





$_remesa=$_GET['remesa']; 

echo '<table align="center">
         <tr><td  width="20%"></td>
               <td width="60%" style="font-family: Arial; font-size: 18pt;"></td>
               <td width="20%"></td>
        </tr> 
        <tr>
            <td  colspan="3"> . </td>
        </tr>
         <tr><td width="20%"></td>
           <td  width="60%"> <div align="center"><label style=" font-family: Arial; font-size:14pt;" >Patronato Nacional de la Infancia PANI <br> Departamento de Revision de Premios<br>Bitacora de Revision , Remesa No.   '. $_remesa .'  '.$name_revisor.'  </label></div></td>
           <td width="20%"></td>
        </tr>      
     </table><br>';

 ?>
    	       	<div class="table-responsive">
    	       		<table  class="table table-hover table-sm table-bordered">
    	       			<thead><tr align="center">
    	       						<th colspan='3'> Información</th> 
		    	       				<th class='table-info'      colspan="2">Recibido</th>
		    	       				<th class='table-success'   colspan="2">Revisado</th>
		    	       				<th class='table-danger'    colspan="2">Debitos</th>
		    	       				<th colspan="2">Pendiente</th> 
		    	       			</tr> 
		    	       			<tr><th>Fecha de pago</th>
		    	       				<th>Agencia</th> 
		    	       				<th>Revisor</th> 
		    	       				<th class='table-info'     >Cant.</th>
		    	       				<th class='table-info'     >Neto</th> 
		    	       				<th class='table-success'  >Cant.</th>
		    	       				<th class='table-success'  >Neto</th> 
		    	       				<th class='table-danger'   >Cant.</th>
		    	       				<th class='table-danger'   >Neto</th> 
		    	       				<th class=''   >Cant.</th>
		    	       				<th class=''   >Neto</th>  
		    	       			</tr>   	       			
    	       			</thead>
    	       			<tbody>    	       		 
			    	    <?php	
						$fecha_pago='';
						$agencia_code='';
			    	  
			    	    if ($usuario_id=='1000') 
			    	    {			    	    	
			    	     $query_pagos_fecha=mysqli_query($conn, "SELECT date(a.transactiondate)  fecha_pago, b.transactionagency agencia_code, b.transactionagencyname agencia_name, usuario_revision, usuario_revision_name, sum(decimos) recepcionado, sum(a.netopayment) neto_recepcionado
			    	       										 FROM mayor_pagos_detalle a INNER JOIN mayor_pagos_recibos b ON a.transactioncode=b.transactioncode 
			    	       										 WHERE a.remesa=$_remesa AND ano_remesa = '$s_year'  and  a.transactionstate in (1) group by date(a.transactiondate), b.transactionagency order by date(a.transactiondate) asc ");	

		    	     
			    	    }
			    	    else
			    	    {
			    	     $query_pagos_fecha=mysqli_query($conn, "SELECT date(a.transactiondate)  fecha_pago, b.transactionagency agencia_code, b.transactionagencyname agencia_name, usuario_revision, usuario_revision_name, sum(decimos) recepcionado, sum(a.netopayment) neto_recepcionado
			    	       										 FROM mayor_pagos_detalle a INNER JOIN mayor_pagos_recibos b ON a.transactioncode=b.transactioncode 
			    	       										 WHERE a.remesa=$_remesa AND ano_remesa = '$s_year'  and a.transactionstate in (1) and usuario_revision=$usuario_id group by date(a.transactiondate), b.transactionagency order by date(a.transactiondate) asc ");	
 
			    	    }

			    	     	$cantidad_recibida=0;      			$neto_recibido    =0;	   	    $acumulado_cantidad_recibida=0;						$acumulado_neto_recibido=0;
		    	       		$cantidad_revisada=0;      			$neto_revisado    =0;			$acumulado_cantidad_revisada=0;	       				$acumulado_neto_revisado=0;				    	       		
		    	       		$cantidad_debitada=0;      		    $neto_debitado    =0;			$acumulado_cantidad_debitada=0;						$acumulado_neto_debitado=0;
		    	       		$cantidad_caducada=0;      		    $neto_caducado    =0;			$acumulado_cantidad_caducada=0;						$acumulado_neto_caducado=0;
		    	       		$class='';		    	       		$dias_vencimiento =0;           $fecha1='';                                         $fecha2='';
		    	       		$cantidad_pendiente=0;      		$neto_pendiente=0;				$acumulado_cantidad_pendiente=0;					$acumulado_neto_pendiente=0;
		    	       		$cantidad_ajustes=0;                      $neto_ajustes= 0;

						
		    	       if ($query_pagos_fecha) 
		    	       {			    	       	 		    	       	
		    	       			while ($array_pagos_fecha=mysqli_fetch_array($query_pagos_fecha)) 
			    	       		{ 
			    	       		   $fecha_pago=$array_pagos_fecha['fecha_pago'];    							
			    	       		   $agencia_code=$array_pagos_fecha['agencia_code'];    
			    	       		   $agencia_name=$array_pagos_fecha['agencia_name'];				    	       		   								 
								   $cantidad_recibida=$array_pagos_fecha['recepcionado']; 	                    
								   $neto_recibido=$array_pagos_fecha['neto_recepcionado'];
								   $usuario_revision_code=$array_pagos_fecha['usuario_revision'];	 
								   $usuario_revision_name=$array_pagos_fecha['usuario_revision_name'];

									if ($usuario_id=='1000') 
						    	    {			    	    	

						    	       $query_revisado_txt      = "SELECT sum(a.decimos) revisado,  sum(a.netopayment) neto_revisado  
																					FROM mayor_pagos_detalle a INNER JOIN mayor_pagos_recibos b ON a.transactioncode=b.transactioncode 
																					WHERE a.transactionstate=1 and estado_revision in (1,2) and date(a.transactiondate) = '$fecha_pago' and b.transactionagency=$agencia_code and 
																					remesa=$_remesa  group by date(a.transactiondate), b.transactionagency order by date(a.transactiondate) desc;";

									   $query_debitado_txt      = "SELECT sum(b.decimos_nota) debitado, sum(b.neto_nota)  neto_debitado
																					FROM mayor_pagos_detalle a INNER JOIN rp_notas_credito_debito_mayor b ON a.id=b.id_detalle
																					WHERE b.ano_remesa = '$s_year'   and b.remesa=$_remesa  and date(a.transactiondate)='$fecha_pago' and b.agencia=$agencia_code";

										$query_faltante_txt     = "SELECT sum(decimos_nota) faltante, sum(neto_nota) neto_faltante
																					 FROM rp_notas_credito_debito_mayor 
																					 WHERE id_detalle = null and remesa=$_remesa and ano_remesa = '$s_year' and date(transactiondate)='$fecha_pago' and agencia=$agencia_code;";			    	     
						    	    }
						    	    else
						    	    {		    	 

						    	       $query_revisado_txt      = "SELECT sum(a.decimos) revisado,  sum(a.netopayment) neto_revisado  
																					FROM mayor_pagos_detalle a INNER JOIN mayor_pagos_recibos b ON a.transactioncode=b.transactioncode 
																					WHERE a.transactionstate=1 and estado_revision in (1,2) and date(a.transactiondate) = '$fecha_pago' and b.transactionagency=$agencia_code and 
																					remesa=$_remesa and usuario_revision=$usuario_id group by date(a.transactiondate), b.transactionagency order by date(a.transactiondate) desc;";

									   $query_debitado_txt      = "SELECT sum(b.decimos_nota) debitado, sum(b.neto_nota)  neto_debitado
																					FROM mayor_pagos_detalle a INNER JOIN rp_notas_credito_debito_mayor b ON a.id=b.id_detalle
																					WHERE b.ano_remesa = '$s_year' and b.remesa=$_remesa  and date(a.transactiondate)='$fecha_pago' and b.agencia=$agencia_code and usuario=$usuario_id";

										$query_faltante_txt     = "SELECT sum(decimos_nota) faltante, sum(neto_nota) neto_faltante
																					 FROM rp_notas_credito_debito_mayor 
																					 WHERE id_detalle = null and remesa=$_remesa and ano_remesa = '$s_year' and date(transactiondate)='$fecha_pago' and agencia=$agencia_code and usuario=$usuario_id;";
						    	    }
				    			    	       		   

								   $query_revisado=mysqli_query($conn, $query_revisado_txt );
								  
								   if (mysqli_num_rows($query_revisado)>0) 
								   {	
								   		 while ($row_revisado= mysqli_fetch_array($query_revisado)) 
										 {
											$cantidad_revisada=$row_revisado['revisado']; 				            
											$neto_revisado=$row_revisado['neto_revisado'];								    
										 }
								   }
								   else
								   {
								   		$cantidad_revisada=0; 				            
										$neto_revisado=0;								    
								   }

								   $query_debitado=mysqli_query($conn, $query_debitado_txt );
								  
								   if (mysqli_num_rows($query_debitado)>0) 
								   {	
								   		 while ($row_debitado= mysqli_fetch_array($query_debitado)) 
										 {
											 $cantidad_debitada  = $row_debitado['debitado']; 							
											 $neto_debitado	     = $row_debitado['neto_debitado'];							    
										 }

										 $cantidad_revisada = $cantidad_revisada - $cantidad_debitada;
										 $neto_revisado     = $neto_revisado 	 - $neto_debitado;
								   }
								   else
								   {
								   		$cantidad_debitada=0; 				            
										$neto_debitado=0;								    
								   }



								    $query_faltante=mysqli_query($conn, $query_faltante_txt );
								  
								   if (mysqli_num_rows($query_faltante)>0) 
								   {	
								   		 while ($row_faltante= mysqli_fetch_array($query_faltante)) 
										 {
											 $cantidad_faltante  = $row_faltante['faltante']; 							
											 $neto_faltante	     = $row_faltante['neto_faltante'];							    
										 }

										 $cantidad_revisada = $cantidad_revisada - $cantidad_faltante;
										 $neto_revisado     = $neto_revisado 	 - $neto_faltante;

										 $cantidad_debitada = $cantidad_debitada + $cantidad_faltante; 				            
										 $neto_debitado     = $neto_debitado     + $neto_faltante;	
								   }
								   else
								   {
								   		$cantidad_faltante=0; 				            
										$neto_faltante=0;								    
								   }
						  
								   $cantidad_pendiente=$cantidad_recibida-$cantidad_revisada-$cantidad_debitada;
								   $neto_pendiente=$neto_recibido-$neto_revisado-$neto_debitado; 

								   $acumulado_cantidad_recibida  = $acumulado_cantidad_recibida + $cantidad_recibida;								 $acumulado_neto_recibido  = $acumulado_neto_recibido  + $neto_recibido;		
								   $acumulado_cantidad_revisada  = $acumulado_cantidad_revisada + $cantidad_revisada;		   						 $acumulado_neto_revisado  = $acumulado_neto_revisado  + $neto_revisado;
								   $acumulado_cantidad_debitada  = $acumulado_cantidad_debitada + $cantidad_debitada;								 $acumulado_neto_debitado  = $acumulado_neto_debitado  + $neto_debitado;	
								   $acumulado_cantidad_pendiente = $acumulado_cantidad_pendiente + $cantidad_pendiente;								 $acumulado_neto_pendiente = $acumulado_neto_pendiente + $neto_pendiente;	 
								 					    	       
					    	       echo "<tr><td align='center'>".$fecha_pago."</td>
					    	       			 <td align='left'>".$agencia_code." -- ".$agencia_name."  </td>  
					    	       			 <td align='center'>".$usuario_revision_code." -- ".$usuario_revision_name."  </td> 
					    	       			 <td align='center'    class='table-info' >".$cantidad_recibida."</td> 
					    	       			 <td align='right'     class='table-info' >".number_format($neto_recibido,2)."</td>
					    	       			 <td align='center'    class='table-success'   >".$cantidad_revisada."</td>
					    	       			 <td align='right'     class='table-success'   >".number_format($neto_revisado,2)."</td>					    	       			
					    	       			 <td align='center'    class='table-danger'    >".$cantidad_debitada."</td>
					    	       			 <td align='right'     class='table-danger'    >".number_format($neto_debitado,2)."</td>					    	       			 
					    	       			 <td align='center'    >".$cantidad_pendiente."</td>
					    	       			 <td align='right'     >".number_format($neto_pendiente,2)."</td>					    	       			 
					    	       		</tr>";
			    	       		}			    	       		
			    	       		echo "<tr class=''><td colspan='17'></td></tr>";
			    	       		echo "<tr class='table-success'><td align='center' colspan='3'><strong> Totales </strong></td>
									    	       				<td align='center'><strong>".number_format($acumulado_cantidad_recibida)."</strong></td>
									    	       				<td align='right'><strong>".number_format($acumulado_neto_recibido,2)."</strong></td>
									    	       				<td align='center'><strong>".number_format($acumulado_cantidad_revisada)."</strong></td>
									    	       				<td align='right'><strong>".number_format($acumulado_neto_revisado,2)."</strong></td>
									    	       				<td align='center'><strong>".number_format($acumulado_cantidad_debitada)."</strong></td>
									    	       				<td align='right'><strong>".number_format($acumulado_neto_debitado,2)."</strong></td> 
									    	       				<td align='center'><strong>".number_format($acumulado_cantidad_pendiente)."</strong></td>
									    	       				<td align='right'><strong>".number_format($acumulado_neto_pendiente,2)."</strong></td>
									    	       				
			    	       			   </tr>";											    	       
			    	        }
			    	    	
    	       			echo "</tbody>
    	       		</table> ";


		    	       		
			    	    ?>	
			    	       			</tbody>
    	       			 
    	       		</table>    	       			
    	       		</div>
<script type="text/javascript">
document.title="Revision de Premios";
window.print(); 
setTimeout(window.close, 1000);
</script>
 
