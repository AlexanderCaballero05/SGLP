<?php 
require('../../template/header.php'); 
$usuario_id = $_SESSION['id_usuario'];

    function diferenciaDias($inicio, $fin)
    {
        $inicio = strtotime($inicio);
        $fin = strtotime($fin);
        $dif = $fin - $inicio;
        $diasFalt = (( ( $dif / 60 ) / 60 ) / 24);
        return ceil($diasFalt);
    }

//$remesa=1;


?>
<script type="text/javascript">
     $(".div_wait").fadeIn("fast");  
</script>
<style type="text/css">
.div_wait 
{
  display: none;
  position: fixed;
  left: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  z-index: 9999;
  background-color: black;
  opacity:0.5;
  background: url(../../template/images/wait.gif) center no-repeat #fff;
}

@media print {
        #no_print { display: none; }  
        #printOnly {
       display : block;
    }       
 }
@media screen {
        #printOnly { display: none; } 
}       

</style>
<form method="post" id="_revision_premios"  class="" name="_revision_premios">
<div id='div_wait'></div>
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Bitacora de Revision de Lotería Mayor </h3> <br></section>

<section id="no_print">
<a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS  <i class="far fa-hand-point-down fa-lg"></i></a>
    <div class="collapse " id="collapse1" align="center">
      <div class="card">
        <div class="card card-body" id="non-printable" >
          <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
            <div class = "input-group-prepend"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i> &nbsp;  Remesa: </span></div>
             <select class="form-control" id="remesa" name="remesa" >
             	<?php 

				$query_remesas=mysqli_query($conn, "SELECT remesa, ano_remesa FROM mayor_pagos_detalle WHERE ano_remesa >= 2021 GROUP BY CONCAT(remesa, ano_remesa)  order by ano_remesa DESC, remesa desc");
				while ($row_remesa=mysqli_fetch_array($query_remesas)) 
				{
				$rem=$row_remesa['remesa'];
				echo "<option value='".$rem."/".$row_remesa['ano_remesa']."'> Remesa: ".$row_remesa['remesa']." | Año: ".$row_remesa['ano_remesa']." </option>";
				}

				?>
             </select>
             <button id="seleccionar" name="seleccionar" type="submit" class="Consulta btn btn-primary">BUSQUEDA DE BILLETES REVISADOS</button>
          </div>

        </div>
      </div>
    </div> 
 </section>
 <hr>
 <section>
 <?php     
    if (isset($_POST['seleccionar']))
    { 
 		?>
 		<script type="text/javascript">
 			 $(".div_wait").fadeIn("fast");  
 		</script>
 		<?php 



		 $parametros = explode('/',$_POST['remesa']);

		 $_remesa = $parametros[0];
		 $s_year = $parametros[1];



    	 $remesa_titulo=str_pad($_remesa, 3, "0", STR_PAD_LEFT);
		 $remesa_titulo= "No. ".$remesa_titulo." - ".date("Y"); 
    	
    	 echo $remesa_titulo;

 		 echo "<div align='right' id='no_print'><a class='btn btn-warning btn-lg' role='button' id='no_print' onclick='window.print()' > <i class='fa fa-print'></i> &nbsp; Imprimir</a></div><br>";
   
			    	
			    				    	     $query_pagos_fecha=mysqli_query($conn, "SELECT date(a.transactiondate)  fecha_pago, b.transactionagency agencia_code, b.transactionagencyname agencia_name, sum(decimos) recepcionado, sum(a.netopayment) neto_recepcionado
																					FROM mayor_pagos_detalle a 
																					INNER JOIN mayor_pagos_recibos b ON a.transactioncode=b.transactioncode	
																					INNER JOIN rp_asignacion_agencias_revisor_mayor c ON b.transactionagency=c.transactionagency	
																					WHERE a.remesa=$_remesa and
																					date(a.transactiondate) = date(c.transactiondate) and 
																					a.ano_remesa = $s_year and date(a.transactiondate)>= '2021-10-25' and
																					a.transactionstate in (1) and 
																					c.usuario_revision='$usuario_id' and 
																					fecha_recepcion_banco is not null  group by date(a.transactiondate), b.transactionagency order by date(a.transactiondate) asc");	



			    				    	  
			    	       if (mysqli_num_rows($query_pagos_fecha) >0 )
			    	       {
			    	      		?>
							    <div class="table-responsive">
							    	<table  class="table table-hover table-sm table-bordered">
							    	    <thead><tr align="center">
							    	       			<th colspan='2'> Información</th> 
									    	       	<th class='table-info'      colspan="2">Recibido</th>
									    	       	<th class='table-success'   colspan="2">Revisado</th>
									    	       	<th class='table-danger'    colspan="2">Debitos</th>
									    	       	<th colspan="2">Pendiente</th>
									    	       	<th id="no_print"></th>
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
									    	       	<th class='' id="no_print"></th> 
									    	    </tr>   	       			
							    	    </thead>
							    	    <tbody>    	       		 
							    <?php	

			    	       		$cantidad_recibida=0;    			$neto_recibido=0;				$acumulado_cantidad_recibida=0;						$acumulado_neto_recibido=0;
			    	       		$cantidad_revisada=0;      			$neto_revisado=0;			    $acumulado_cantidad_revisada=0;	       				$acumulado_neto_revisado=0;				    	       		
			    	       		$cantidad_debitada=0;      		    $neto_debitado=0;				$acumulado_cantidad_debitada=0;						$acumulado_neto_debitado=0;
			    	       		$cantidad_pendiente=0;      		$neto_pendiente=0;				$acumulado_cantidad_pendiente=0;					$acumulado_neto_pendiente=0;
			    	      		$class=''; $fecha1=''; $fecha2='';


			    	       		while ($array_pagos_fecha=mysqli_fetch_array($query_pagos_fecha)) 
			    	       		{
			    	       		   $fecha_pago=$array_pagos_fecha['fecha_pago'];    							
			    	       		   $agencia_code=$array_pagos_fecha['agencia_code'];    
			    	       		   $agencia_name=$array_pagos_fecha['agencia_name'];				    	       		   								 
								   $cantidad_recibida=$array_pagos_fecha['recepcionado']; 	                    
								   $neto_recibido=$array_pagos_fecha['neto_recepcionado'];

								   $query_revisado=mysqli_query($conn, "SELECT sum(a.decimos) revisado,  sum(a.netopayment) neto_revisado  
																		FROM mayor_pagos_detalle a INNER JOIN mayor_pagos_recibos b ON a.transactioncode=b.transactioncode 
																		WHERE a.transactionstate=1 and 
																		estado_revision in (1,2) and 
																		date(a.transactiondate) = '$fecha_pago' and 
																		b.transactionagency=$agencia_code and 
																		remesa=$_remesa and date(a.transactiondate) >='2019-01-01' and 
																		usuario_revision=$usuario_id group by date(a.transactiondate), b.transactionagency order by date(a.transactiondate) desc;");
								  
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

								   $query_debitado=mysqli_query($conn, "SELECT sum(b.decimos_nota) debitado, sum(b.neto_nota)  neto_debitado
																		FROM mayor_pagos_detalle a INNER JOIN rp_notas_credito_debito_mayor b ON a.id=b.id_detalle
																		WHERE b.ano_remesa = $s_year and b.remesa=$_remesa  and date(a.transactiondate)='$fecha_pago' and b.agencia=$agencia_code and usuario=$usuario_id");
								  
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



								    $query_faltante=mysqli_query($conn, "SELECT sum(decimos_nota) faltante, sum(neto_nota) neto_faltante
																		 FROM rp_notas_credito_debito_mayor 
																		 WHERE id_detalle = null and remesa=$_remesa and ano_remesa = $s_year and date(transactiondate)='$fecha_pago' and agencia=$agencia_code and usuario=$usuario_id;");
								  
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
					    	       			 <td align='center'    class='table-info' >".$cantidad_recibida."</td> 
					    	       			 <td align='right'     class='table-info' >".number_format($neto_recibido,2)."</td>
					    	       			 <td align='center'    class='table-success'   >".$cantidad_revisada."</td>
					    	       			 <td align='right'     class='table-success'   >".number_format($neto_revisado,2)."</td>					    	       			
					    	       			 <td align='center'    class='table-danger'    >".$cantidad_debitada."</td>
					    	       			 <td align='right'     class='table-danger'    >".number_format($neto_debitado,2)."</td>					    	       			 
					    	       			 <td align='center'    >".$cantidad_pendiente."</td>
					    	       			 <td align='right'     >".number_format($neto_pendiente,2)."</td>
					    	       			 <td align='center'    class='' id='no_print'>
					    	       			 	<a href='_rp_mayor_detalle_revision.php?fecha=".$fecha_pago."&cod_agencia=".$agencia_code."' target='blank' class='btn btn-info' role='button'>Ver Detalle</a>
					    	       			  </td>
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
									    	       				<td align='center'><strong>".number_format($acumulado_cantidad_pendiente)."</strong></td>
									    	       				<td align='right'><strong>".number_format($acumulado_neto_pendiente,2)."</strong></td>
									    	       				<td align='center' id='no_print'><strong></strong></td>
			    	       			   </tr>";											    	       
			    	        }
			    	    	
    	       			echo "</tbody>
    	       		</table>    	       			
    	       		</div>";
    }	
  ?>
 	
 </section>
<script type="text/javascript">
	$(".div_wait").fadeOut("fast");  
</script>
