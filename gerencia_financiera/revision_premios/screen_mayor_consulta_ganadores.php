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
	<style type="text/css" media="print"> 
	@page {    size:  portrait;  } 
	 th, td { padding-bottom: 0px;   border-spacing: 0; font-family: Arial; font-size: 09pt; }   
	</style> 
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
	@media print    
	{
	    #no_print { display: none; }          
	}  
	</style>
	<script type="text/javascript">
	$(document).ready(function ()
	{
		$("#txtid").mask("9999-9999-99999", { placeholder: "____-____-____ " });
	}); 

		 $(".div_wait").fadeIn("fast");  
	</script> 


<form method="post">
<div id='div_wait'></div>
<ul class="nav nav-tabs" id="no_print">
<li class="nav-item">    
    <a style="background-color:#ededed;" class="nav-link active" href="#">Consulta de Ganadores de Lotería Mayor</a>
  </li>
  <li class="nav-item">
     <a class="nav-link" href="./screen_menor_consulta_ganadores.php" >Consulta de Ganadores de Lotería Menor</a>
  </li>
</ul> 
<div id="no_print_fr" class="page">
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Consulta de Ganadores de Lotería Mayor </h3> <br></section>
<section id="no_print">
	<div class="row mt-3 mb-3">
		<div class="col-sm-12">
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#basicExampleModal" style="margin-left: 10px;">
				  Ingresar un Nuevo Registro Previo al sorteo 1211
				</button>
		</div>
	</div>
    <div class="card">
                  <div class="card-header"><h5 class="mb-0">Consulta por Número de Identidad</h5></div>
                  <div class="card-body"  align="center">
                  	<div class="row">
                  		<div class="col-sm-4"  style="padding-right:4px;padding-bottom:3px;padding-left:3px;padding-top:3px;">
                  		 <div class="row">
                  		 	<div class="col-sm-8">
                  		 		  <input type="text" id="txtid" name="txtid" class="form-control" style="width:100%" placeholder="Identidad">	
                  		 	</div>
                  		 	<div class="col-sm-4">
                  		 		<button class="btn btn-success active btn-md" type="submit" name="consulta_id" value = "consulta_id" style="margin-left: 10px;">CONSULTAR <i class="fas fa-search" style="font-size:15px; margin-left: 10px;"></i></button>	
                  		 	</div>
                  		 </div> 
                  		</div>
                  		<div class="col-sm-8">
                  			<div class="card">
                  				<div class="card-body"> 
                  					<?php if (isset($_POST['consulta_id'])): 
                  						$identidad=$_POST['txtid'];
                  						$query_ganador=mysqli_query($conn, "SELECT b.transactionwinnername, a.transactioncode, b.transactionagencyname, b.transactionusername, a.sorteo, a.numero, a.decimos, a.totalpayment total, a.imptopayment impto, a.netopayment neto FROM mayor_pagos_detalle a, mayor_pagos_recibos b WHERE a.transactioncode=b.transactioncode and b.transactionwinnerid='$identidad' and a.transactionstate = 1 order by sorteo desc ");
                  						?>
                  					<div class="table-responsive">
                  					<table class="table table-sm table-hover table-bordered">
                  						<thead>                  							
                  						<tr ><th>ID</th>
                  							 <th>Nombre</th>
                  							 <th>Factura</th>
                  							 <th>Agencia</th>
                  							 <th>Cajero</th>
                  							 <th>Sorteo</th>
                  							 <th>Número</th>
                  							 <th>Décimos</th>
                  							 <th>Total</th>
                  							 <th>Impto</th>
                  							 <th>Neto</th> 
                  						</tr>
                  						</thead>
                  						<tbody>
                  							<?php  
                  							if ($query_ganador) {
                  								$total_decimos=0;	$total_total=0;  $total_impto=0; $total_neto=0;
                  								while ($row_info=mysqli_fetch_array($query_ganador)) 
                  								{
                  								   echo "<tr ><td>".$identidad."</td>
                  								   			  <td>".$row_info['transactionwinnername']."</td>
                  								   			  <td>".$row_info['transactioncode']."</td>
                  								   			  <td>".$row_info['transactionagencyname']."</td>
                  								   			  <td>".$row_info['transactionusername']."</td>	
                  								   			  <td>".$row_info['sorteo']."</td>	
                  								   			  <td>".$row_info['numero']."</td>
                  								   			  <td align='center'>".$row_info['decimos']."</td>
                  								   			  <td align='right'>".$row_info['total']."</td>
                  								   			  <td align='right'>".$row_info['impto']."</td>
                  								   			  <td align='right'>".$row_info['neto']."</td>
                  								   		</tr>";
                  								   		$total_decimos=$total_decimos+$row_info['decimos'];
                  								   		$total_total=$total_total+$row_info['total'];
                  								   		$total_impto=$total_impto+$row_info['impto'];
                  								   		$total_neto=$total_neto+$row_info['neto'] ;               								   		
                  								}
                  								echo "<tr class='table-success' ><td colspan='7'> Totales </td>
                  										   <td align='center'>".$total_decimos."</td>
                  										   <td align='right'>".number_format($total_total,2)."</td>
                  										   <td align='right'>".number_format($total_impto,2)."</td>
                  										   <td align='right'>".number_format($total_neto,2)."</td>                  										   
                  									  </tr>";
                  							}
                  							else
                  							{
                  								echo "No hay registros de esta persona";
                  							}
                  							?>                  						
                  						</tbody>
                  					</table>
                  					</div>
                  					<?php endif ?>
                  				</div>
                  			</div>
                  		</div>
                  	</div>                
                </div>
              </div>
 </section>
<br><hr>
<section id="no_print">
    <div class="card">
               <div class="card-header"><h5 class="mb-0">Consulta por Sorteo</h5></div>
               <div class="card-body"  align="center">
                  	<div class="row">
                  	<div class="col-sm-4"  style="padding-right:4px;padding-bottom:3px;padding-left:3px;padding-top:3px;"> 
                  		<div class="row">
                  			<div class="col-sm-8">
                  				<select  id="sorteo_inicial" name="sorteo_inicial" class="form-control">
					                <option value=""> Seleccione Uno ... </option>
					            	<?php 
					            		$result_query_sorteo= mysqli_query($conn, "(SELECT id, fecha_sorteo ,ADDDATE(fecha_sorteo, INTERVAL 45 DAY) vencimiento, (45 - DATEDIFF(CURRENT_DATE, fecha_sorteo)) dias FROM sorteos_mayores order by id desc) UNION (SELECT sorteo as id, null as 'fecha_sorteo' , null as 'vecimiento' , null as 'dias' FROM rp_registro_ganadores_historico_mayor) order by id asc");
										if (mysqli_num_rows($result_query_sorteo)>0 ) 
										{
											while($row_sorteos=mysqli_fetch_array($result_query_sorteo,MYSQLI_ASSOC))  
										    {
												echo "<option value = '".$row_sorteos['id']."'>No.".$row_sorteos['id']." | Fecha ".$row_sorteos['fecha_sorteo']." | Vence ".$row_sorteos['vencimiento']." | - ".$row_sorteos['dias']." días</option>" ;	
											}
										}
										else
										{
											echo mysqli_error();
										}	
					            	 ?>   
						            </select>
                  			</div>
                  			<div class="col-sm-4">
                  				<button class="btn btn-success active btn-md" type="submit" name="consulta_sorteo" value = "consulta_sorteo">CONSULTAR <i class="fas fa-search" style="font-size:15px;"></i></button>		
                  			</div>	
                  		</div>
                  	</div>
                  	<div class="col-sm-8">
                  	<div class="card">
                  	<div class="card-body"> 
                  					<?php if (isset($_POST['consulta_sorteo'])): 

                  						$sorteo=$_POST['sorteo_inicial'];
                  						$query_ganador=mysqli_query($conn, "(SELECT b.transactionwinnername, b.transactionwinnerid, a.transactioncode, b.transactionagencyname, b.transactionusername, a.sorteo, a.numero, a.decimos, a.totalpayment total, a.imptopayment impto, a.netopayment neto FROM mayor_pagos_detalle a, mayor_pagos_recibos b WHERE a.transactioncode=b.transactioncode and a.sorteo=$sorteo and a.transactionstate = 1 order by a.netopayment desc) UNION ( SELECT nombre_completo as transactionwinnername, identidad as transactionwinnerid, factura as transactioncode, agencia as transactionagencyname, cajero as transactionusername, sorteo, numero, serie, total, impto, neto from rp_registro_ganadores_historico_menor where sorteo = $sorteo )");
                  					?>
                  					<div class="table-responsive">
                  					<table id="table_id1" class="table table-sm table-hover  table-bordered" width="100%" >
                  						<thead>                  							
                  						<tr ><th>ID</th>
                  							 <th>Nombre</th>
                  							 <th>Factura</th>
                  							 <th>Agencia</th>
                  							 <th>Cajero</th>
                  							 <th>Sorteo</th>
                  							 <th>Número</th>
                  							 <th>Décimos</th>
                  							 <th>Total</th>
                  							 <th>Impto</th>
                  							 <th>Neto</th> 
                  						</tr>
                  						</thead>
                  						<tbody>
                  							<?php  
                  							if ($query_ganador) 
                  							{
                  								$total_decimos=0;	$total_total=0;  $total_impto=0; $total_neto=0;
                  								while ($row_info=mysqli_fetch_array($query_ganador)) 
                  								{
                  								   echo "<tr ><td>".$row_info['transactionwinnerid']."</td>                  								   			  
                  								   			  <td>".$row_info['transactionwinnername']."</td>
                  								   			  <td>".$row_info['transactioncode']."</td>
                  								   			  <td>".$row_info['transactionagencyname']."</td>
                  								   			  <td>".$row_info['transactionusername']."</td>	
                  								   			  <td>".$row_info['sorteo']."</td>	
                  								   			  <td>".$row_info['numero']."</td>
                  								   			  <td align='center'>".$row_info['decimos']."</td>
                  								   			  <td align='right'>".$row_info['total']."</td>
                  								   			  <td align='right'>".$row_info['impto']."</td>
                  								   			  <td align='right'>".$row_info['neto']."</td>
                  								   		</tr>";
                  								   		$total_decimos=$total_decimos+$row_info['decimos'];
                  								   		$total_total=$total_total+$row_info['total'];
                  								   		$total_impto=$total_impto+$row_info['impto'];
                  								   		$total_neto=$total_neto+$row_info['neto'] ;               								   		
                  								}
                  								echo "</tbody>
                  								<tfoot>
                  									 <tr class='table-success' ><td colspan='7'> Totales </td>
                  										   <td align='center'>".$total_decimos."</td>
                  										   <td align='right'>".number_format($total_total,2)."</td>
                  										   <td align='right'>".number_format($total_impto,2)."</td>
                  										   <td align='right'>".number_format($total_neto,2)."</td>                  										   
                  									  </tr> 
                  								</tfoot>";
                  							
                  							}
                  							else
                  							{
                  								echo "No hay registros de esta persona";
                  							}
                  							?>                  						
                  						</tbody>
                  					</table>
                  					</div>
                  					<?php 
                  						echo '<a role="button" class="btn btn-success btn-lg active" target="_blank" href="_EXCEL_mayor_ganadores.php?sorteo='.$sorteo.'">  Exportar a Excel  <i class="far fa-file-excel"></i> </a>';
                  					 ?>
                  					
                  					<?php endif ?>
                  	</div>
                  	</div>
                  	</div>
                  	</div>                
            </div>
        </div>
 	</section>

 	<?php 
if ($_SERVER["REQUEST_METHOD"] === "POST") 
	{ 
		if (isset($_POST['add_nuevo'])) {
		   $_id_nuevo			=	$_POST['id_nuevo'];
		   $_nombre_nuevo		=	$_POST['nombre_nuevo'];
		   $_sorteo_nuevo		=	$_POST['sorteo_nuevo'];
		   $_numero_nuevo		=	$_POST['numero_nuevo'];
		   $_serie_nuevo		=	$_POST['decimos_nuevo'];
		   $_total_nuevo		=	$_POST['total_nuevo'];
		   $_impto_nuevo		=	$_POST['impto_nuevo'];
		   $_neto_nuevo			=	$_POST['neto_nuevo'];
		   $_comentario_nuevo	=	$_POST['comentario_nuevo'];		  

		   //echo $_id_nuevo." -- ".$_nombre_nuevo." -- ".$_sorteo_nuevo." -- ".$_numero_nuevo." -- ".$_serie_nuevo." -- ".$_total_nuevo." -- ".$_impto_nuevo." -- ".$_neto_nuevo	." -- ".$_comentario_nuevo;

		    $query_insert_nuevo_ganador= mysqli_query($conn, "INSERT INTO rp_registro_ganadores_historico_mayor(identidad, nombre_completo, sorteo, numero, decimos, total, impto, neto, comentario, register_user) VALUES ('$_id_nuevo', '$_nombre_nuevo', '$_sorteo_nuevo', '$_numero_nuevo', '$_serie_nuevo', '$_total_nuevo', '$_impto_nuevo', '$_neto_nuevo',  '$_comentario_nuevo', '$usuario_name' )");

		    if ($query_insert_nuevo_ganador) {
		    	echo "todo bien";
		    	 ?>
		            <script type="text/javascript"> 
		                 swal({
		                      title: "",
		                        text: "Registro Agregado Exitosamente!.",
		                        type: "success" 
		                      })   .then(function(result){
		                           window.location.replace("./screen_mayor_consulta_ganadores.php" );                        
		                     });
		            </script>
            	<?php 
		    }else {
		    	echo mysqli_error($conn);
		    }


		}

	}
 ?>
 </form>
 <script type="text/javascript">
 	$(".div_wait").fadeOut("fast");  
 </script>