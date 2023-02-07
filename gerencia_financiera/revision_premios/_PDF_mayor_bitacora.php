<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['id_usuario'];
$name_revisor=$_SESSION['nombre_usuario'];
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
           <td  width="60%"> <div align="center"><label style=" font-family: Arial; font-size:14pt;" >Patronato Nacional de la Infancia PANI <br> Departamento de Revision de Premios<br>Bitacora de Revision , Remesa No.   '. $_remesa .' asignada a: <br> '.$name_revisor.'  </label></div></td>
           <td width="20%"></td>
        </tr>      
     </table><br>';

 ?>
    	       	<div class="table-responsive">
    	       		<table  class="table table-hover table-sm table-bordered">
    	       			<thead><tr align="center">
    	       						<th colspan='2'> Información</th> 
		    	       				<th class='table-info'      colspan="2">Recibido</th>
		    	       				<th class='table-success'   colspan="2">Revisado</th>
		    	       				<th class='table-danger'    colspan="2">Debitos</th>
		    	       				<th colspan="2">Pendiente</th>
		    	       				<th></th>
		    	       			</tr> 
		    	       			<tr><th>Fecha de pago</th>
		    	       				<th>Agencia</th> 
		    	       				<th class='table-info'     >Cant.</th>
		    	       				<th class='table-info'     >Neto</th> 
		    	       				<th class='table-success'  >Cant.</th>
		    	       				<th class='table-success'  >Neto</th> 
		    	       				<th class='table-danger'   >Cant.</th>
		    	       				<th class='table-danger'   >Neto</th> 
		    	       				<th class=''   >Cant.</th>
		    	       				<th class=''   >Neto</th> 
		    	       				<th class=''></th> 
		    	       			</tr>   	       			
    	       			</thead>
    	       			<tbody>    	       		 
			    	    <?php			    	     
			    	     $query_pagos_fecha=mysqli_query($conn, "SELECT date(a.transactiondate)  fecha_pago, b.transactionagency agencia_code, b.transactionagencyname agencia_name, sum(decimos) cantidad_pagada, sum(a.netopayment) neto_pagado ,
			    	       										 (sum( case when (fecha_recepcion_banco is not null ) then decimos end) )recepcionado,
			    	       										 (  sum( case when (fecha_recepcion_banco is not null ) then a.netopayment else 0 end) ) 'neto_recepcionado',
			    	       										 (sum( case when (estado_revision =1 ) then decimos end) )revisado,
			    	       										 (  sum( case when (estado_revision =1 ) then a.netopayment else 0 end) ) 'neto_revisado',
			    	       										 (sum( case when (estado_revision =2 ) then decimos end) )debitado,
			    	       										 (  sum( case when (estado_revision =2 ) then a.netopayment else 0 end) ) 'neto_debitado'
			    	       										 FROM mayor_pagos_detalle a INNER JOIN mayor_pagos_recibos b ON a.transactioncode=b.transactioncode 
			    	       										 WHERE a.remesa=$_remesa AND ano_remesa='2019' and a.transactionstate in (1) and usuario_revision=$usuario_id group by date(a.transactiondate), b.transactionagency order by date(a.transactiondate) asc ");			    	       
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
			    	       		   $fecha_pago=$array_pagos_fecha['fecha_pago'];    $agencia_code=$array_pagos_fecha['agencia_code'];    $agencia_name=$array_pagos_fecha['agencia_name'];				    	       		   
								 
								   $cantidad_recibida=$array_pagos_fecha['recepcionado']; 	                    $neto_recibido=$array_pagos_fecha['neto_recepcionado'];
								   $cantidad_revisada=$array_pagos_fecha['revisado']; 				            $neto_revisado=$array_pagos_fecha['neto_revisado'];
								   $cantidad_debitada=$array_pagos_fecha['debitado']; 							$neto_devitaado=$array_pagos_fecha['neto_debitado'];

								   $cantidad_caducada=$cantidad_recibida-$cantidad_revisada-$cantidad_debitada;
								   $neto_caducado=$neto_recibido-$neto_revisado-$neto_debitado; 

								      if ($_remesa==20 and $usuario_id==54 and $agencia_code ==12 and $fecha_pago =='2019-02-22') 
								    {
								    	echo "string";
								        $cantidad_debitada=3;
								        $neto_debitado=30;

								        $cantidad_revisada=$cantidad_revisada-$cantidad_debitada;
								        $neto_revisado=$neto_revisado-$neto_debitado;
								    }


								       if ($_remesa==29 and $usuario_revision_code==59 and $agencia_code ==9 and $fecha_pago =='2019-03-15') 
								    {
								    	//echo "string";
								        $cantidad_debitada=5;
								        $neto_debitado=50;

								        $cantidad_revisada=$cantidad_revisada-$cantidad_debitada;
								        $neto_revisado=$neto_revisado-$neto_debitado;
								    }
			    	       		   

								   $acumulado_cantidad_recibida=$acumulado_cantidad_recibida+$cantidad_recibida;								 $acumulado_neto_recibido=$acumulado_neto_recibido+$neto_recibido;		
								   $acumulado_cantidad_revisada=$acumulado_cantidad_revisada+$cantidad_revisada;		   						 $acumulado_neto_revisado=$acumulado_neto_revisado+$neto_revisado;
								   $acumulado_cantidad_debitada=$acumulado_cantidad_debitada+$cantidad_debitada;								 $acumulado_neto_debitado=$acumulado_neto_debitado+$neto_debitado;	
								   $acumulado_cantidad_caducada=$acumulado_cantidad_caducada+$cantidad_caducada;								 $acumulado_neto_caducado=$acumulado_neto_caducado+$neto_caducado;	 

								    
								 					    	       
					    	       echo "<tr><td align='center'>".$fecha_pago."</td>
					    	       			 <td align='center'>".$agencia_code." -- ".$agencia_name."  </td>  
					    	       			 <td align='center'    class='table-info' >".$cantidad_recibida."</td> 
					    	       			 <td align='right'     class='table-info' >".number_format($neto_recibido,2)."</td>
					    	       			
					    	       			
					    	       			 <td align='center'    class='table-success'   >".$cantidad_revisada."</td>
					    	       			 <td align='right'     class='table-success'   >".number_format($neto_revisado,2)."</td>					    	       			
					    	       			 <td align='center'    class='table-danger'    >".$cantidad_debitada."</td>
					    	       			 <td align='right'     class='table-danger'    >".number_format($neto_debitado,2)."</td>					    	       			 
					    	       			 <td align='center'    >".$cantidad_caducada."</td>
					    	       			 <td align='right'     >".number_format($neto_caducado,2)."</td>
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
<script type="text/javascript">
document.title="Revision de Premios";
window.print(); 
setTimeout(window.close, 1000);
</script>
 