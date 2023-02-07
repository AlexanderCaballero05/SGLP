<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['usuario'] ;

    function diferenciaDias($inicio, $fin)
    {
        $inicio = strtotime($inicio);
        $fin = strtotime($fin);
        $dif = $fin - $inicio;
        $diasFalt = (( ( $dif / 60 ) / 60 ) / 24);
        return ceil($diasFalt);
    }

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
</style>
<form method="post" id="_revision_premios"  class="" name="_revision_premios">
<div id='div_wait'></div>
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Monitoreo y Recepción de Lotería Mayor (Diario)</h3> <br></section>
<section>
<a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS  <i class="far fa-hand-point-down fa-lg"></i></a>
    <div class="collapse " id="collapse1" align="center">
      <div class="card">
        <div class="card card-body" id="non-printable" >
          <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
            <div class = "input-group-prepend"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i> &nbsp;  Fecha Inicial: </span></div>
            <input type='date' id ="fecha_i"   name = "fecha_inicial" class="form-control" id ="dt1">
            <div class = "input-group-prepend" style="margin-left: 10px;"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i>  &nbsp;   Fecha Final: </span></div>
            <input type='date' id ="fecha_f"   name = "fecha_final" class="form-control" id ="dt2">           
            <button type="submit" name="seleccionar" style="margin-left: 10px;" class="btn btn-primary" value = "Seleccionar">  Seleccionar &nbsp;<i class="fas fa-search fa-lg"></i></button>
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
    	 $_fecha_inicial=$_POST['fecha_inicial'];  $_fecha_inicial = date("Y-m-d", strtotime($_fecha_inicial)); 
    	 $_fecha_final=$_POST['fecha_final'];  	   $_fecha_final   = date("Y-m-d", strtotime($_fecha_final)); 
 
    	 ?>
    	       	<div class="table-responsive">
    	       		<table  class="table table-hover table-sm table-bordered">
    	       			<thead><tr align="center">
    	       						<th></th>
		    	       				<th colspan="2">Pagado</th>    	       				
		    	       				<th class='table-info'      colspan="3">Recepcionado</th>
		    	       				<th class='table-info'      colspan="3">Pendiente Recepcionar</th>
		    	       				<th class='table-success'   colspan="2">Revisado</th>
		    	       				<th class='table-danger'    colspan="2">Debitos</th>
		    	       				<th class='table-secondary'></th>
		    	       				<th colspan="3">Pendiente de Revisar</th>
		    	       				<th></th>
		    	       			</tr> 
		    	       			<tr><th></th>
		    	       				<th>Dec.</th>
		    	       				<th>Neto</th>
		    	       				<th class='table-info'     >Dec.</th>
		    	       				<th class='table-info'     >Neto</th>
		    	       				<th class='table-info'     ></th>
		    	       				<th class='table-info'     >Dec.</th>
		    	       				<th class='table-info'     >Neto</th>
		    	       				<th class='table-info'     ></th>
		    	       				<th class='table-success'  >Dec.</th>
		    	       				<th class='table-success'  >Neto</th>
		    	       				<th class='table-danger'   >Dec.</th>
		    	       				<th class='table-danger'   >Neto</th>
		    	       				<th class='table-secondary'></th>
		    	       				<th>Dec.</th>
		    	       				<th>Neto</th>
		    	       				<th>Dias</th>
		    	       				<th>Dias</th>
		    	       			</tr>   	       			
    	       			</thead>
    	       			<tbody>    	       		 
			    	    <?php			    	     
			    	     $query_pagos_fecha=mysqli_query($conn, "SELECT date(a.transactiondate)  fecha_pago, sum(decimos) cantidad_pagada, sum(a.netopayment) neto_pagado ,
			    	       										 (  sum( case when (fecha_recepcion_banco is not null ) then a.decimos else 0 end) )'recepcionado',
			    	       										 (  sum( case when (fecha_recepcion_banco is not null ) then a.netopayment else 0 end) ) 'neto_recepcionado',
			    	       										 (  sum( case when (estado_revision =1 ) then a.decimos else 0 end) )revisado,
			    	       										 (  sum( case when (estado_revision =1 ) then a.netopayment else 0 end) ) 'neto_revisado',
			    	       										 (  sum( case when (estado_revision =2 ) then a.decimos  else 0 end) )debitado,
			    	       										 (  sum( case when (estado_revision =2 ) then a.netopayment else 0 end) ) 'neto_debitado'
			    	       										 FROM mayor_pagos_detalle a INNER JOIN mayor_pagos_recibos b ON a.transactioncode=b.transactioncode 
			    	       										 WHERE date(a.transactiondate) between '$_fecha_inicial' AND '$_fecha_final' AND a.transactionstate in (1) 
			    	       										 GROUP BY date(a.transactiondate) ORDER BY date(a.transactiondate) ASC");			    	       
			    	       if ($query_pagos_fecha) 
			    	       {
			    	       		$cantidad_pagada=0;       			$neto_pagado=0;					$acumulado_cantidad_pagada=0;    					$acumulado_neto_pagado=0;
			    	       		$cantidad_recibida=0;      			$neto_recibido=0;				$acumulado_cantidad_recibida=0;						$acumulado_neto_recibido=0;
			    	       		$cantidad_recibida_pendiente=0;     $neto_recibido_pendiente=0;     $acumulado_cantidad_recibida_pendiente=0;			$acumulado_neto_recibido_pendiente=0;
			    	       		$cantidad_revisada=0;      			$neto_revisado=0;			    $acumulado_cantidad_revisada=0;	       				$acumulado_neto_revisado=0;		
			    	       		$cantidad_revisada_pendiente=0;     $neto_revisado_pendiente=0;		$acumulado_cantidad_revisada_pendiente=0;			$acumulado_neto_revisado_pendiente=0;			    	       		
			    	       		$cantidad_debitada=0;      		    $neto_debitado=0;				$acumulado_cantidad_debitada=0;						$acumulado_neto_debitado=0;
			    	       		$cantidad_debitada_parcial=0;        $neto_debitado_parcial=0;
			    	       		$cantidad_caducada=0;      		    $neto_caducado=0;				$acumulado_cantidad_caducada=0;						$acumulado_neto_caducado=0;
			    	       		$class='';
			    	       		$dias_vencimiento=0;                $fecha1=''; $fecha2='';


			    	       		while ($array_pagos_fecha=mysqli_fetch_array($query_pagos_fecha)) 
			    	       		{
			    	       		   $fecha_pago=$array_pagos_fecha['fecha_pago'];			    	       			 
			    	       		   $fecha_pago_screen= date("d-m-Y", strtotime($fecha_pago));
			    	       		   $fecha_actual=date("Y-m-d");
			    	       		   $fecha1 = strtotime($fecha_actual);  $fecha2 = strtotime($fecha_pago);
								   $res = $fecha1 - $fecha2;
								   $dias_vencimiento = date('d', $res);	
								   $cantidad_pagada=$array_pagos_fecha['cantidad_pagada'];      	            $neto_pagado=$array_pagos_fecha['neto_pagado'];
								   $cantidad_recibida=$array_pagos_fecha['recepcionado']; 	                    $neto_recibido=$array_pagos_fecha['neto_recepcionado'];
								   $cantidad_recibida_pendiente=$cantidad_pagada-$cantidad_recibida;            $neto_recibido_pendiente=$neto_pagado-$neto_recibido;
								   $cantidad_revisada=$array_pagos_fecha['revisado']; 				            $neto_revisado=$array_pagos_fecha['neto_revisado'];
								   $cantidad_debitada=$array_pagos_fecha['debitado']; 							$neto_debitado=$array_pagos_fecha['neto_debitado'];
								   //echo "eee".$fecha_pago;

								   $query_debito_parcial=mysqli_query($conn, "SELECT decimos_nota, neto_nota FROM rp_notas_credito_debito_mayor WHERE date(transactiondate)='$fecha_pago'");
								   if (mysqli_num_rows($query_debito_parcial)>0) {

								   	 while ($array_debitos_parcial=mysqli_fetch_array($query_debito_parcial)) {
								   	 	 $cantidad_debitada_parcial=$array_debitos_parcial['decimos_nota'];
								   	 	 $neto_debitado_parcial=$array_debitos_parcial['neto_nota'];
								   	 }
								   	 $cantidad_debitada=$cantidad_debitada+$cantidad_debitada_parcial;
								   	 $neto_debitado=$neto_debitado+$neto_debitado_parcial;
								   	 $cantidad_revisada= $cantidad_revisada-$cantidad_debitada_parcial; 				            
								   	 $neto_revisado= $neto_revisado-$neto_debitado_parcial;
								   	 unset($query_debito_parcial);
								   }

								   $cantidad_caducada=$cantidad_recibida-$cantidad_revisada-$cantidad_debitada;
								   $neto_caducado=$neto_recibido-$neto_revisado-$neto_debitado;
								   $dias_vencidos=diferenciaDias($fecha_pago, $fecha_actual);

								   $acumulado_cantidad_pagada=$acumulado_cantidad_pagada+$cantidad_pagada;  									 $acumulado_neto_pagado=$acumulado_neto_pagado+$neto_pagado;
								   $acumulado_cantidad_recibida=$acumulado_cantidad_recibida+$cantidad_recibida;								 $acumulado_neto_recibido=$acumulado_neto_recibido+$neto_recibido;	
								   $acumulado_cantidad_recibida_pendiente=$acumulado_cantidad_recibida_pendiente+$cantidad_recibida_pendiente;	 $acumulado_neto_recibido_pendiente=$acumulado_neto_recibido_pendiente+$neto_recibido_pendiente;		
								   $acumulado_cantidad_revisada=$acumulado_cantidad_revisada+$cantidad_revisada;		   						 $acumulado_neto_revisado=$acumulado_neto_revisado+$neto_revisado;
								   $acumulado_cantidad_revisada_pendiente=$acumulado_cantidad_revisada_pendiente+$cantidad_revisada_pendiente;	 $acumulado_neto_revisado_pendiente=$acumulado_neto_revisado_pendiente+$neto_revisado_pendiente;
								   $acumulado_cantidad_debitada=$acumulado_cantidad_debitada+$cantidad_debitada;								 $acumulado_neto_debitado=$acumulado_neto_debitado+$neto_debitado;	
								   $acumulado_cantidad_caducada=$acumulado_cantidad_caducada+$cantidad_caducada;								 $acumulado_neto_caducado=$acumulado_neto_caducado+$neto_caducado;	 

								    
								   if ($dias_vencidos>60) { $class='text-danger'; } else {  $class=''; }
					    	       
					    	       echo "<tr><td align='center'>".$fecha_pago_screen."</td> 
					    	       			 <td align='center'>".$cantidad_pagada."</td>
					    	       			 <td align='right'>".number_format($neto_pagado,2)."</td>
					    	       			 <td align='center'    class='table-info' >".$cantidad_recibida."</td>
					    	       			 <td align='right'     class='table-info' >".number_format($neto_recibido,2)."</td>
					    	       			 <td align='center'    class='table-info' >
					    	       			 	<a role='button'   class='btn btn-primary btn-sm btn-outline' href='./_rp_mayor_recepcion_remesa.php?fecha_pago=".$fecha_pago."' target='blank'>  Ir <i class='fas fa-eye'></i></a>
					    	       			 </td>
					    	       			 <td align='center'    class='table-info'>".$cantidad_recibida_pendiente."</td>
					    	       			 <td align='right'     class='table-info'>".number_format($neto_recibido_pendiente,2)."</td>
					    	       			 <td align='center'    class='table-info'>
					    	       			 	 <a role='button'  class='btn btn-primary btn-sm btn-outline' href='./_rp_mayor_recepcion_remesa.php?fecha_pago=".$fecha_pago."' target='blank'>  Ir <i class='far fa-share-square'></i></a>
					    	       			 </td>
					    	       			 <td align='center'    class='table-success'   >".$cantidad_revisada."</td>
					    	       			 <td align='right'     class='table-success'   >".number_format($neto_revisado,2)."</td>					    	       			
					    	       			 <td align='center'    class='table-danger'    >".$cantidad_debitada."</td>
					    	       			 <td align='right'     class='table-danger'    >".number_format($neto_debitado,2)."</td>
					    	       			 <td align='center'    class='table-secondary' >
					    	       			 	<a role='button'   class='btn btn-secondary btn-sm btn-outline' href='./screen_mayor_notas_fecha_detalle.php?fecha=".$fecha_pago."' target='blank'>   <i class='fas fa-eye'></i></a>	
					    	       			 </td>
					    	       			 <td align='center'    class='".$class."'>".$cantidad_caducada."</td>
					    	       			 <td align='right'     class='".$class."'>".number_format($neto_caducado,2)."</td>					    	       			 
					    	       			 <td align='center'    class='".$class."'>".$dias_vencidos."</td>
					    	       			 <td align='center'    class='".$class."'>
					    	       			 <a role='button'   class='btn btn-secondary btn-sm btn-outline' href='./screen_mayor_pendiente_revision_detalle.php?fecha=".$fecha_pago."' target='blank'>  <i class='fas fa-eye'></i></a>	
					    	       			 </td>
					    	       		</tr>";
			    	       		}			    	       		
			    	       		echo "<tr class=''><td colspan='17'></td></tr>";
			    	       		echo "<tr class='table-success'><td align='center'><strong> Totales </strong></td>
									    	       				<td align='center'><strong>".number_format($acumulado_cantidad_pagada)."</strong></td>
									    	       				<td align='right'><strong>".number_format($acumulado_neto_pagado,2)."</strong></td>
									    	       				<td align='center'><strong>".number_format($acumulado_cantidad_recibida)."</strong></td>
									    	       				<td align='right'><strong>".number_format($acumulado_neto_recibido,2)."</strong></td>
									    	       				<td align='center'><strong></strong></td>
									    	       				<td align='center'><strong>".number_format($acumulado_cantidad_recibida_pendiente)."</strong></td>
									    	       				<td align='right'><strong>".number_format($acumulado_neto_recibido_pendiente,2)."</strong></td>
									    	       				<td align='center'><strong></strong></td>
									    	       				<td align='center'><strong>".number_format($acumulado_cantidad_revisada)."</strong></td>
									    	       				<td align='right'><strong>".number_format($acumulado_neto_revisado,2)."</strong></td>
									    	       				<td align='center'><strong>".number_format($acumulado_cantidad_debitada)."</strong></td>
									    	       				<td align='right'><strong>".number_format($acumulado_neto_debitado,2)."</strong></td> 
									    	       				<td align='center'><strong></strong></td>
									    	       				<td align='center'><strong>".number_format($acumulado_cantidad_caducada)."</strong></td>
									    	       				<td align='right'><strong>".number_format($acumulado_neto_caducado,2)."</strong></td>
									    	       				<td align='center'><strong></strong></td>
									    	       				<td align='center'><strong></strong></td>
			    	       			   </tr>";											    	       
			    	        }	
			    	    ?>	
    	       			</tbody>
    	       		</table>    	       			
    	       		</div>
    	       		<script type="text/javascript">
			 			 $(".div_wait").fadeOut("fast");  
			 		</script>
    	    <?php

    }	
  ?>
 	
 </section>
<script type="text/javascript">
	$(".div_wait").fadeOut("fast");  
</script>