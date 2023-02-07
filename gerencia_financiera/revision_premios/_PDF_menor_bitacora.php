<div style="font-family: Arial; font-size: 18pt;">
<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['id_usuario'];
$name_revisor=$_SESSION['nombre_usuario'];
$_remesa=$_GET['remesa']; 
$_year=$_GET['year']; 

echo '<table align="center" style="font-family: Arial; font-size: 18pt;">
         <tr><td  width="20%"></td>
               <td width="60%" style="font-family: Arial; font-size: 18pt;"></td>
               <td width="20%"></td>
        </tr> 
        <tr>
            <td  colspan="3"> . </td>
        </tr>
        <tr><td width="20%"></td>
           <td  width="60%"> <div align="center"><label style=" font-family: Arial; font-size:14pt;" >Patronato Nacional de la Infancia PANI <br> Departamento de Revision de Premios<br>Bitacora de Revision , Remesa No.   '. $_remesa .' asignada a: <br> '.$name_revisor.'  </label></div></td>
           <td width="20%"></td>
        </tr>      
     </table><br>';

 ?>    	       	<div class="table-responsive">
    	       		<table  class="table table-hover table-sm table-bordered">
    	       			<thead><tr align="center">
    	       						<th colspan='2'> Información</th> 
		    	       				<th colspan="2">Recibido</th>
		    	       				<th colspan="2">Revisado</th>
		    	       				<th colspan="2">Debitos</th>
		    	       				<th colspan="2">Pendiente</th>
		    	       			</tr> 
		    	       			<tr><th>Fecha de pago</th>
		    	       				<th>Agencia</th> 
		    	       				<th>Cant.</th>
		    	       				<th>Neto</th> 
		    	       				<th>Cant.</th>
		    	       				<th>Neto</th> 
		    	       				<th>Cant.</th>
		    	       				<th>Neto</th> 
		    	       				<th>Cant.</th>
		    	       				<th>Neto</th> 
		    	       			</tr>   	       			
    	       			</thead>
    	       			<tbody>    	       		 
			    	    <?php			    	     
			    	     $query_pagos_fecha=mysqli_query($conn, "SELECT date(a.transactiondate)  fecha_pago, b.transactionagency agencia_code, b.transactionagencyname agencia_name, count(numero) cantidad_pagada, sum(neto) neto_pagado ,
			    	       										 (count( case when (fecha_recepcion_banco is not null ) then 'recepcionado' end) )recepcionado,
			    	       										 (  sum( case when (fecha_recepcion_banco is not null ) then neto else 0 end) ) 'neto_recepcionado',
			    	       										 (count( case when (estado_revision =1 ) then 'revisado' end) )revisado,
			    	       										 (  sum( case when (estado_revision =1 ) then neto else 0 end) ) 'neto_revisado',
			    	       										 (count( case when (estado_revision =2 ) then 'revisado' end) )debitado,
			    	       										 (  sum( case when (estado_revision =2 ) then neto else 0 end) ) 'neto_debitado'
			    	       										 FROM menor_pagos_detalle a INNER JOIN menor_pagos_recibos b ON a.transactioncode=b.transactioncode 
			    	       										 WHERE a.remesa=$_remesa AND ano_remesa = '$_year' and a.transactionstate in (1) and usuario_revision=$usuario_id and date(a.transactiondate)>='2021-10-30' group by date(a.transactiondate), b.transactionagency order by date(a.transactiondate) asc ");			    	       
			    	       if ($query_pagos_fecha) 
			    	       {
			    	       	 
			    	       		$cantidad_recibida=0;      			$neto_recibido=0;				$acumulado_cantidad_recibida=0;						$acumulado_neto_recibido=0;
			    	       		$cantidad_revisada=0;      			$neto_revisado=0;			    $acumulado_cantidad_revisada=0;	       				$acumulado_neto_revisado=0;				    	       		
			    	       		$cantidad_debitada=0;      		    $neto_debitado=0;				$acumulado_cantidad_debitada=0;						$acumulado_neto_debitado=0;
			    	       		$cantidad_caducada=0;      		    $neto_caducado=0;				$acumulado_cantidad_caducada=0;						$acumulado_neto_caducado=0;
			    	       		$class='';
			    	       		$dias_vencimiento=0;                $fecha1=''; $fecha2='';

			    	       		while ($array_pagos_fecha=mysqli_fetch_array($query_pagos_fecha)) 
			    	       		{
			    	       		   $fecha_pago=$array_pagos_fecha['fecha_pago'];    							$agencia_code=$array_pagos_fecha['agencia_code'];    $agencia_name=$array_pagos_fecha['agencia_name'];			    	       		   
								 
								   $cantidad_recibida=$array_pagos_fecha['recepcionado']; 	                    $neto_recibido=$array_pagos_fecha['neto_recepcionado'];
								   $cantidad_revisada=$array_pagos_fecha['revisado']; 				            $neto_revisado=$array_pagos_fecha['neto_revisado'];
								   $cantidad_debitada=$array_pagos_fecha['debitado']; 							$neto_devitaado=$array_pagos_fecha['neto_debitado'];

								   $cantidad_caducada=$cantidad_recibida-$cantidad_revisada-$cantidad_debitada;
								   $neto_caducado=$neto_recibido-$neto_revisado-$neto_debitado; 

								    
								   $acumulado_cantidad_recibida=$acumulado_cantidad_recibida+$cantidad_recibida;								 $acumulado_neto_recibido=$acumulado_neto_recibido+$neto_recibido;		
								   $acumulado_cantidad_revisada=$acumulado_cantidad_revisada+$cantidad_revisada;		   						 $acumulado_neto_revisado=$acumulado_neto_revisado+$neto_revisado;
								   $acumulado_cantidad_debitada=$acumulado_cantidad_debitada+$cantidad_debitada;								 $acumulado_neto_debitado=$acumulado_neto_debitado+$neto_debitado;	
								   $acumulado_cantidad_caducada=$acumulado_cantidad_caducada+$cantidad_caducada;								 $acumulado_neto_caducado=$acumulado_neto_caducado+$neto_caducado;	 

								    
								 					    	       
					    	       echo "<tr><td align='center'>".$fecha_pago."</td>
					    	       			 <td align='left'>".$agencia_code." -- ".$agencia_name."  </td>  
					    	       			 <td align='center'>".$cantidad_recibida."</td> 
					    	       			 <td align='right' >".number_format($neto_recibido,2)."</td>
					    	       			 <td align='center'>".$cantidad_revisada."</td>
					    	       			 <td align='right' >".number_format($neto_revisado,2)."</td>					    	       			
					    	       			 <td align='center'>".$cantidad_debitada."</td>
					    	       			 <td align='right' >".number_format($neto_debitado,2)."</td>					    	       			 
					    	       			 <td align='center'>".$cantidad_caducada."</td>
					    	       			 <td align='right' >".number_format($neto_caducado,2)."</td> 
					    	       		</tr>";
			    	       		}			    	       		
			    	       		echo "<tr class=''><td colspan='17'></td></tr>";
			    	       		echo "<tr class='table-success'><td align='center' colspan='2'><strong> Totales </strong></td>
									    	       				<td align='center'><strong>".number_format($acumulado_cantidad_recibida)."</strong></td>
									    	       				<td align='right'><strong>".number_format($acumulado_neto_recibido,2)."</strong></td>
									    	       				<td align='center'><strong>".number_format($acumulado_cantidad_revisada)."</strong></td>
									    	       				<td align='right'><strong>".number_format($acumulado_neto_revisado,2)."</strong></td>
									    	       				<td align='center'><strong>".number_format($acumulado_cantidad_debitada)."</strong></td>
									    	       				<td align='right'><strong>".number_format($acumulado_neto_debitado,2)."</strong></td> 
									    	       				<td align='center'><strong>".number_format($acumulado_cantidad_caducada)."</strong></td>
									    	       				<td align='right'><strong>".number_format($acumulado_neto_caducado,2)."</strong></td> 
			    	       			   </tr>";											    	       
			    	        }
			    	       
			    	     
			    	    ?>	
    	       			</tbody>
    	       		</table>    	       			
    	       		</div>

</div>
<script type="text/javascript">
document.title="Revision de Premios";
window.print(); 
setTimeout(window.close, 1000);
</script>
