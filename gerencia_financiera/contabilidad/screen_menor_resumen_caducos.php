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

<!-- section>
	<ul class="nav nav-tabs" id="no_print">
		 <li class="nav-item">
		    <a class="nav-link" href="./screen_mayor_conciliacion_sorteo.php">Pagos de Premios de Lotería Mayor</a>		    
		  </li>
		  <li class="nav-item">
		    <a style="background-color:#ededed;" class="nav-link active" href="#">Pagos de Premios de Lotería Menor</a>
		  </li>
	</ul>	
</section  -->

<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Caducos por tipo de Premio </h3> <br></section>

<section>
<a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS  <i class="far fa-hand-point-down fa-lg"></i></a>
    <div class="collapse " id="collapse1" align="center">
      <div class="card">
        <div class="card card-body" id="non-printable" >
          <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
            <div class = "input-group-prepend"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i> &nbsp;  Sorteo Inicial: </span></div>
            <select  id="sorteo_inicial" name="sorteo_inicial" class="form-control" required="true">
            	<option value=""> Seleccione Uno ... </option>
            	<?php 
            		$result_query_sorteo= mysqli_query($conn, "SELECT id, fecha_sorteo ,ADDDATE(fecha_sorteo, INTERVAL 45 DAY) vencimiento, (45 - DATEDIFF(CURRENT_DATE, fecha_sorteo)) dias FROM sorteos_menores where id> 3228 order by id desc");

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
            <!--div class = "input-group-prepend"  style="margin-left: 10px;"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i> &nbsp;  Sorteo Final: </span></div>
            <select  id="sorteo_final" name="sorteo_final" class="form-control" required="true">
            	<option value=""> Seleccione Uno ... </option>
            	<?php 
            		$result_query_sorteo_final= mysqli_query($conn,"SELECT id, fecha_sorteo ,ADDDATE(fecha_sorteo, INTERVAL 45 DAY) vencimiento, (45 - DATEDIFF(CURRENT_DATE, fecha_sorteo)) dias FROM sorteos_menores where id> 3228 order by id desc");

						if (mysqli_num_rows($result_query_sorteo_final)>0 ) 
						{
							while($row_sorteos_final=mysqli_fetch_array($result_query_sorteo_final,MYSQLI_ASSOC))  
							{
								echo "<option value = '".$row_sorteos_final['id']."'>No.".$row_sorteos_final['id']." | Fecha ".$row_sorteos_final['fecha_sorteo']." | Vence ".$row_sorteos_final['vencimiento']." | - ".$row_sorteos_final['dias']." días</option>" ;	
							}
						}
						else
						{
							echo mysqli_error();
						}
            	 ?>            	
            </select -->                  
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
    	 $sorteo_inicial=$_POST['sorteo_inicial'];

    	 $query_pagos_total=mysqli_query($conn, "SELECT  numero numero_total, serie serie_total , tipo_premio, netopayment  FROM archivo_pagos_menor WHERE sorteo=$sorteo_inicial order by numero, serie desc;");

    	 if ($query_pagos_total) 
    	 {
    	 	$contador_ps=0; $contador_pr=0; $contador_pc=0;  $contador_pd=0;  $contador_pm=0;  $neto_ps=0; $neto_pr=0;  $neto_pc=0;  $neto_pd=0; $neto_pm=0; $contador_total=0; $neto_total=0;

    	 	while ($row_premios_total = mysqli_fetch_array($query_pagos_total)) 
    	 	{

    	 		?>
		 		<script type="text/javascript">
		 			 $(".div_wait").fadeIn("fast");  
		 		</script>
		 		<?php 
    	 		 
    	 		 $numero_total			=	$row_premios_total['numero_total'];
    	 		 $serie_total			=	$row_premios_total['serie_total'];
    	 		 $tipo_premio			=	$row_premios_total['tipo_premio'];
    	 		 $netopayment			=	$row_premios_total['netopayment'];

    	 		 $query_caducos= mysqli_query($conn, "SELECT * FROM menor_pagos_detalle where sorteo=$sorteo_inicial and numero=$numero_total and serie=$serie_total;");
    	 		 if (!mysqli_num_rows($query_caducos)>0) 
    	 		 {
    	 		 //	echo "<br>".$numero_total." -- ".$serie_total." -- ".$tipo_premio." -- ".$netopayment;

    	 		  		if ($tipo_premio == 'PS') 
    	 		  		{
    	 		  		   $contador_ps ++;
    	 		  		   $neto_ps = $neto_ps+$netopayment;
    	 		  		}
    	 		  		else if ($tipo_premio == 'PR') {
    	 		  		   $contador_pr ++;
    	 		  		   $neto_pr = $neto_pr+$netopayment;
    	 		  		}
    	 		  		else if ($tipo_premio == 'PD') {
    	 		  		   $contador_pd ++;
    	 		  		   $neto_pd = $neto_pd+$netopayment;
    	 		  		}
    	 		  		else if ($tipo_premio  == 'PC') {
    	 		  		   $contador_pc ++;
    	 		  		   $neto_pc = $neto_pc+$netopayment;
    	 		  		}
    	 		  		else if ($tipo_premio == 'PM') {
    	 		  		   $contador_pm ++;
    	 		  		   $neto_pm = $neto_pm+$netopayment;
    	 		  		}
    	 		 }
    		}

    			$contador_total	=	$contador_pd+$contador_pr+$contador_ps+$contador_pm;
		 		$neto_total		=	$neto_pd+$neto_pr+$neto_ps+$neto_pm;
		    	 
		    	 echo '<div class="table-responsive">
		    	       		<table  class="table table-hover table-sm table-bordered">
		    	       			<thead><tr><th>Sorteo</th>		    	       				
				    	       				<th>Tipo de Premio</th>
				    	       				<th>Cant.</th>
				    	       				<th>Neto</th>		    	       				
				    	       			</tr>   	       			
		    	       			</thead>
		    	       			<tbody>';
					    	  		 
					    	 			echo "<tr class='table-info'><td align='center'>".$sorteo_inicial."</td>
					    	 					  <td align='center'>Premio de Derecho</td>	
					    	 					  <td align='center'>".$contador_pd."</td>
					    	 					  <td align='right'>".number_format($neto_pd,2)."</td>
					    	 				</tr>
					    	 				<tr class='table-warning'><td align='center'>".$sorteo_inicial."</td>
					    	 					  <td align='center'>Premio de Reves</td>	
					    	 					  <td align='center'>".$contador_pr."</td>
					    	 					  <td align='right'>".number_format($neto_pr,2)."</td>
					    	 				</tr>
					    	 				<tr class='table-success'><td  align='center'>".$sorteo_inicial."</td>
					    	 					  <td align='center'>Premio de Serie</td>	
					    	 					  <td align='center'>".$contador_ps."</td>
					    	 					  <td align='right'>".number_format($neto_ps,2)."</td>
					    	 				</tr>
					    	 				<tr class='table-secondary'>
					    	 					  <td  align='center'>".$sorteo_inicial."</td>
					    	 					  <td align='center'>Premio de igual numero</td>	
					    	 					  <td align='center'>".$contador_pm."</td>
					    	 					  <td align='right'>".number_format($neto_pm,2)."</td>
					    	 				</tr>	
					    	 				<tr class='table-secondary'>
					    	 					  <td  align='center'></td>
					    	 					  <td align='center'></td>	
					    	 					  <td align='center'>".$contador_total."</td>
					    	 					  <td align='right'>".number_format($neto_total,2)."</td>
					    	 				</tr>	
					    	 	</tbody>
		    	       		</table>    	       			
		    	       	</div>";
    	 }    	 
 
 	
 
    	       	 } 	  
    	       	 else
    	       	{
    	       		echo "<div class='alert alert-danger'> El sorteo final debe ser mayor al sorteo final</div>";
    	       	}
    	        ?>
    	       		<script type="text/javascript">
			 			 $(".div_wait").fadeOut("fast");  
			 		</script>
    	    <?php

 
  ?>
 	
 </section>
<script type="text/javascript">
	$(".div_wait").fadeOut("fast");  
</script>