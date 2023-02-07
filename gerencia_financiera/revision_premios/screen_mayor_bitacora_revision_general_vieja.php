<?php 
require('../../template/header.php'); 


    function diferenciaDias($inicio, $fin)
    {
        $inicio = strtotime($inicio);
        $fin = strtotime($fin);
        $dif = $fin - $inicio;
        $diasFalt = (( ( $dif / 60 ) / 60 ) / 24);
        return ceil($diasFalt);
    }

$remesa=1;
$remesa_titulo=str_pad($remesa, 3, "0", STR_PAD_LEFT);
$remesa_titulo= "No. ".$remesa_titulo." - ".date("Y");

?>
<script type="text/javascript">
 			 $(".div_wait").fadeIn("fast");  
 		</script>

<style> 		
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
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Cuadre de Revision de Lotería Mayor </h3> <br></section>

<section>
<a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS  <i class="far fa-hand-point-down fa-lg"></i></a>
    <div class="collapse " id="collapse1" align="center">
      <div class="card">
        <div class="card card-body" id="non-printable" >
          <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
            <div class = "input-group-prepend"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i> &nbsp;  Remesa: </span></div>
             <select class="form-control" id="remesa" name="remesa" required="true" >
             <option> Seleccion uno ...</option>
             	<?php 
             	$query_remesas=mysqli_query($conn, "SELECT remesa FROM mayor_pagos_detalle WHERE ano_remesa='2019' GROUP BY remesa order by remesa desc");
             	while ($row_remesa=mysqli_fetch_array($query_remesas)) 
             	{
             	   $rem=$row_remesa['remesa'];
             	  echo "<option value='".$rem."'>  ".$row_remesa['remesa']." </option>";
             	}
             	 ?>
             </select>
             <div class = "input-group-prepend" style="margin-left: 10px;"><span  class="input-group-text"><i class="fas fa-users"></i> &nbsp;  Revisor: </span></div>
             <select class="form-control" id="id_usuario" name="id_usuario"  required="true">
             <option value="1000"> Todos ... </option>
             	<?php 
             	$query_usuarios=mysqli_query($conn, "SELECT usuario_revision, usuario_revision_name FROM mayor_pagos_detalle WHERE ano_remesa='2019'  GROUP BY usuario_revision_name order by remesa desc");
             	while ($row_usuarios=mysqli_fetch_array($query_usuarios)) 
             	{
             	   $rem_usu=$row_usuarios['usuario_revision'];
             	  echo "<option value='".$rem_usu."'>  ".$rem_usu."--".$row_usuarios['usuario_revision_name']." </option>";
             	}
             	 ?>
             </select>
             <button  style="margin-left: 10px;" id="seleccionar" name="seleccionar" type="submit" class="Consulta btn btn-primary">BUSQUEDA DE BILLETES REVISADOS</button>
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
    	//echo  $_POST['id_usuario']."--",$_POST['remesa'];
    	if (!empty($_POST['id_usuario']) or !empty($_POST['remesa']) ) 
    	{		
			$usuario_id=$_POST['id_usuario'];
			$_remesa=$_POST['remesa'];  		 
 		?>
 		<script type="text/javascript">
 			 $(".div_wait").fadeIn("fast");   			 
 		</script> 		
 		<div class="alert alert-dark" role="alert" align="center">  <h3> REMESA  NO. <?php echo $_remesa; ?>  </h3>
 		<?php     	 
 echo "<div align='right'><a href='./_PDF_mayor_bitacora_general.php?remesa=".$_remesa."&usuario_id=".$usuario_id."'  target='_blank' class='btn btn-warning btn-lg' role='button'> <i class='fa fa-print'></i> &nbsp; Imprimir</a></div></div>";
    	 ?>
    	       	<div class="table-responsive">
    	       		<table  class="table table-hover table-sm table-bordered">
    	       			<thead><tr align="center">
    	       						<th colspan='3'> Información</th> 
		    	       				<th class='table-info'      colspan="2">Recibido</th>
		    	       				<th class='table-success'   colspan="2">Revisado</th>
		    	       				<th class='table-danger'    colspan="2">Debitos</th>
		    	       				<th colspan="2">Pendiente</th>
		    	       				<th colspan="2"></th>
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
		    	       				<th class=''></th> 
		    	       				<th class=''></th>
		    	       			</tr>   	       			
    	       			</thead>
    	       			<tbody>    	       		 
			    	    <?php	

			    	    if ($usuario_id=='1000') 
			    	    {
			    	    	
			    	     $query_pagos_fecha=mysqli_query($conn, "SELECT date(a.transactiondate)  fecha_pago, b.transactionagency agencia_code, b.transactionagencyname agencia_name, usuario_revision, usuario_revision_name, sum(decimos) cantidad_pagada, sum(a.netopayment) neto_pagado
			    	       										 FROM mayor_pagos_detalle a INNER JOIN mayor_pagos_recibos b ON a.transactioncode=b.transactioncode 
			    	       										 WHERE a.remesa=$_remesa AND ano_remesa='2019' and a.transactionstate in (1) group by date(a.transactiondate), b.transactionagency order by date(a.transactiondate) asc ");	
			    	    }
			    	    else
			    	    {
			    	     $query_pagos_fecha=mysqli_query($conn, "SELECT date(a.transactiondate)  fecha_pago, b.transactionagency agencia_code, b.transactionagencyname agencia_name, usuario_revision, usuario_revision_name, sum(decimos) cantidad_pagada, sum(a.netopayment) neto_pagado
			    	       										 FROM mayor_pagos_detalle a INNER JOIN mayor_pagos_recibos b ON a.transactioncode=b.transactioncode 
			    	       										 WHERE a.remesa=$_remesa AND ano_remesa='2019' and a.transactionstate in (1) and usuario_revision=$usuario_id group by date(a.transactiondate), b.transactionagency order by date(a.transactiondate) asc ");		
			    	    }

		    	       if ($query_pagos_fecha) 
		    	       {			    	       	 
		    	       		$cantidad_recibida=0;      			$neto_recibido    =0;	   	    $acumulado_cantidad_recibida=0;						$acumulado_neto_recibido=0;
		    	       		$cantidad_revisada=0;      			$neto_revisado    =0;			$acumulado_cantidad_revisada=0;	       				$acumulado_neto_revisado=0;				    	       		
		    	       		$cantidad_debitada=0;      		    $neto_debitado    =0;			$acumulado_cantidad_debitada=0;						$acumulado_neto_debitado=0;
		    	       		$cantidad_caducada=0;      		    $neto_caducado    =0;			$acumulado_cantidad_caducada=0;						$acumulado_neto_caducado=0;
		    	       		$class='';		    	       		$dias_vencimiento =0;           $fecha1='';                                         $fecha2='';


		    	       			$cantidad_ajustes=0;                      $neto_ajustes= 0;
		    	       			  

			    	       		while ($array_pagos_fecha=mysqli_fetch_array($query_pagos_fecha)) 
			    	       		{
			    	       		   $fecha_pago=$array_pagos_fecha['fecha_pago']; $agencia_code=$array_pagos_fecha['agencia_code']; $agencia_name=$array_pagos_fecha['agencia_name']; $usuario_revision_code=$array_pagos_fecha['usuario_revision'];	 $usuario_revision_name=$array_pagos_fecha['usuario_revision_name'];				    			    	       		   
								 

								 $cantidad_debitada=0;
								        $neto_debitado=0;
			    	       		   $query_revisado= mysqli_query($conn, "SELECT sum(decimos) recepcionado, sum(a.netopayment) neto_recepcionado FROM mayor_pagos_detalle a, mayor_pagos_recibos b WHERE a.transactioncode=b.transactioncode and a.fecha_recepcion_banco is not null and a.remesa=$_remesa and ano_remesa='2019' and date(a.transactiondate)='$fecha_pago' and b.transactionagency=$agencia_code");
			    	       		   while ($row_recibido=mysqli_fetch_array($query_revisado)) 
			    	       		   {
			    	       		     $cantidad_recibida=$row_recibido['recepcionado']; 	               $neto_recibido=$row_recibido['neto_recepcionado'];
			    	       		   }



  								   $query_pagado= mysqli_query($conn, "SELECT sum(decimos) revisado, sum(a.netopayment) neto_revisado FROM mayor_pagos_detalle a, mayor_pagos_recibos b WHERE a.transactioncode=b.transactioncode and a.estado_revision=1  and a.remesa=$_remesa and ano_remesa='2019' and date(a.transactiondate)='$fecha_pago' and b.transactionagency=$agencia_code");
			    	       		   while ($row_pagado=mysqli_fetch_array($query_pagado)) 
			    	       		   {
			    	       		     $cantidad_revisada=$row_pagado['revisado']; 	                    $neto_revisado=$row_pagado['neto_revisado'];
			    	       		   }	

 
			    	       		   $query_debitado= mysqli_query($conn, "SELECT COALESCE(sum(decimos),0) debitado, COALESCE(sum(neto),0) neto_debitado  FROM rp_notas_credito_debito_mayor WHERE ano_remesa='2019' and remesa=$_remesa and date(transactiondate)='$fecha_pago' and transactionagency=$agencia_code");
			    	       		   while ($row_debitado=mysqli_fetch_array($query_pagado)) 
			    	       		   {
			    	       		     $cantidad_debitada=$row_debitado['debitado']; 						$neto_debitado=$row_debitado['neto_debitado'];
			    	       		   }

			    	       		     if ($_remesa==20 and $usuario_revision_code==54 and $agencia_code ==12 and $fecha_pago =='2019-02-22') 
								    {
								    	//echo "string";
								        $cantidad_debitada=3;
								        $neto_debitado=30;

								        $cantidad_revisada=$cantidad_revisada-$cantidad_debitada;
								        $neto_revisado=$neto_revisado-$neto_debitado;
								    }

								     if ($_remesa==23 and $usuario_revision_code==38 and $agencia_code ==32 and $fecha_pago =='2019-03-07') 
								    {
								    	//echo "string";
								        $cantidad_debitada=5;
								        $neto_debitado=75;

								        $cantidad_revisada=$cantidad_revisada-$cantidad_debitada;
								        $neto_revisado=$neto_revisado-$neto_debitado;
								    }

								   

								     if ($_remesa==29 and $usuario_revision_code==59 and $agencia_code ==9 and $fecha_pago =='2019-03-14') 
								    {
								    	
;
								        $cantidad_debitada=5;
								        $neto_debitado=50;

								        $cantidad_revisada=$cantidad_revisada-$cantidad_debitada;
								        $neto_revisado=$neto_revisado-$neto_debitado;
								    }
			    	       		   
			    	       		   
			    	       		   $query_faltantes=mysqli_query($conn, "SELECT COALESCE(sum(decimos),0) faltante, COALESCE(sum(neto),0) neto_faltante FROM rp_notas_credito_debito_mayor WHERE ano_remesa='2019' and remesa=$_remesa and date(transactiondate)='$fecha_pago' and transactionagency=$agencia_code");

			    	       		

			    	       		   while ($row_faltante=mysqli_fetch_array($query_faltantes)) 
			    	       		   {
			    	       		     $cantidad_faltante=$row_faltante['faltante']; 					$neto_faltante=$row_faltante['neto_faltante'];
			    	       		   }

			    	       		   $cantidad_ajustes=$cantidad_debitada+$cantidad_faltante;                      $neto_ajustes= $neto_debitado+$neto_faltante;

			    	       		  
								   

								   $cantidad_caducada=$cantidad_recibida-$cantidad_revisada-$cantidad_debitada;
								   $neto_caducado=$neto_recibido-$neto_revisado-$neto_debitado; 								    
								   $acumulado_cantidad_recibida=$acumulado_cantidad_recibida+$cantidad_recibida;								 $acumulado_neto_recibido=$acumulado_neto_recibido+$neto_recibido;		
								   $acumulado_cantidad_revisada=$acumulado_cantidad_revisada+$cantidad_revisada;		   						 $acumulado_neto_revisado=$acumulado_neto_revisado+$neto_revisado;
								   $acumulado_cantidad_debitada=$acumulado_cantidad_debitada+$cantidad_ajustes;								 	 $acumulado_neto_debitado=$acumulado_neto_debitado+$neto_ajustes;	
								   $acumulado_cantidad_caducada=$acumulado_cantidad_caducada+$cantidad_caducada;								 $acumulado_neto_caducado=$acumulado_neto_caducado+$neto_caducado;	 

								    								 					    	       
					    	       echo "<tr><td align='center'>".$fecha_pago."</td>
					    	       			 <td align='center'>".$agencia_code." -- ".$agencia_name."  </td>  
					    	       			 <td align='center'>".$usuario_revision_code." -- ".$usuario_revision_name."  </td>  
					    	       			 <td align='center'    class='table-info' >".$cantidad_recibida."</td> 
					    	       			 <td align='right'     class='table-info' >".number_format($neto_recibido,2)."</td>
					    	       			 <td align='center'    class='table-success'   >".$cantidad_revisada."</td>
					    	       			 <td align='right'     class='table-success'   >".number_format($neto_revisado,2)."</td>					    	       			
					    	       			 <td align='center'    class='table-danger'    >".$cantidad_ajustes."</td>
					    	       			 <td align='right'     class='table-danger'    >".number_format($neto_ajustes,2)."</td>					    	       			 
					    	       			 <td align='center'    >".$cantidad_caducada."</td>
					    	       			 <td align='right'     >".number_format($neto_caducado,2)."</td>
					    	       			 <td align='center'    class=''></td>
					    	       			 <td align='center'    class=''>
					    	       			 	<a href='_mayor_bitacora_detalle.php?fecha=".$fecha_pago."&agencia_code=".$agencia_code."&agencia_name=".$agencia_name."' target='_blank' class='btn btn-info' role='button'> <i class='fas fa-eye'></i> &nbsp;  Ver Detalle</a>
					    	       			 </td>
					    	       		</tr>";
			    	       		}			    	       		
			    	       		echo "<tr class=''><td colspan='11'></td></tr>";
			    	       		echo "<tr class='table-success'><td align='center' colspan='3'><strong> Totales </strong></td>
									    	       				<td align='center'><strong>".number_format($acumulado_cantidad_recibida)."</strong></td>
									    	       				<td align='right'><strong>".number_format($acumulado_neto_recibido,2)."</strong></td>
									    	       				<td align='center'><strong>".number_format($acumulado_cantidad_revisada)."</strong></td>
									    	       				<td align='right'><strong>".number_format($acumulado_neto_revisado,2)."</strong></td>
									    	       				<td align='center'><strong>".number_format($acumulado_cantidad_debitada)."</strong></td>
									    	       				<td align='right'><strong>".number_format($acumulado_neto_debitado,2)."</strong></td> 
									    	       				<td align='center'><strong>".number_format($acumulado_cantidad_caducada)."</strong></td>
									    	       				<td align='right'><strong>".number_format($acumulado_neto_caducado,2)."</strong></td>
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
    	    else
    	    {
    	    	echo "<div class='alert alert-danger'> Debe seleccionar los parametros </div>";
    	    }
    }	
  ?>
 	
 </section>
<script type="text/javascript">
	$(".div_wait").fadeOut("fast");  
</script>
