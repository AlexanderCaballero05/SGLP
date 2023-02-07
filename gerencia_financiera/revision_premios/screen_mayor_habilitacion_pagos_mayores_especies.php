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

$remesa=1;
$remesa_titulo=str_pad($remesa, 3, "0", STR_PAD_LEFT);
$remesa_titulo= "No. ".$remesa_titulo." - ".date("Y");


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
@media print    {
        #no_print { display: none; }          

    }

</style>
<form method="post" >
<div id='div_wait'></div>
<div id="no_print_fr" class="page container-fluid">
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><br><h3>Interfaz de habilitación de pagos con montos mayores a L. 100,000 y premios en especies </h3> <br></section>

<section id="no_print">
<a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS  <i class="far fa-hand-point-down fa-lg"></i></a>
    <div class="collapse " id="collapse1" align="center">
      <div class="card">
        <div class="card card-body" id="non-printable" >
          <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
            <div class = "input-group-prepend"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i> &nbsp;  Sorteo : </span></div>
            <select  id="sorteo_inicial" name="sorteo_inicial" class="form-control">
            	<option value=""> Seleccione Uno ... </option>
            	<?php 
            		$result_query_sorteo= mysqli_query($conn, "SELECT a.id, fecha_sorteo ,ADDDATE(fecha_sorteo, INTERVAL 45 DAY) vencimiento, (45 - DATEDIFF(CURRENT_DATE, fecha_sorteo)) dias FROM sorteos_mayores a, archivo_pagos_mayor b where a.id=b.sorteo and a.id> 1210 group by sorteo order by id desc");

						if (mysqli_num_rows($result_query_sorteo)>0 ) 
						{
							while($row_sorteos=mysqli_fetch_array($result_query_sorteo,MYSQLI_ASSOC))  
							{
								echo "<option value = '".$row_sorteos['id']."'>No.".$row_sorteos['id']." | Fecha ".$row_sorteos['fecha_sorteo']." | Vence ".$row_sorteos['vencimiento']." | - ".$row_sorteos['dias']." días</option>" ;	
							}
						}
						else { echo mysqli_error(); }	
            	 ?>            	
            </select>                            
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
  //echo "este es el sorteo : ". $sorteo_inicial ;  
 
			    	    $query_info=mysqli_query($conn, "(SELECT a.id, a.sorteo, a.detalle_venta,  a.numero, a.decimo, a.registro, a.total, a.impto , a.neto, 'EFECTIVO' AS desc_premio ,  'EFECTIVO' AS tipo_premio, estado_especies FROM  archivo_pagos_mayor a 
                         WHERE   a.sorteo =$sorteo_inicial and a.tipo_pago = ('U') and a.neto>=100000)
                         UNION
                        (SELECT a.id, a.sorteo, a.detalle_venta,  a.numero, a.decimo, a.registro, a.total, a.impto , a.neto, b.desc_premio, tipo_premio , estado_especies FROM  archivo_pagos_mayor a, sorteos_mayores_premios b 
                        WHERE a.sorteo=b.sorteos_mayores_id  and a.numero=b.numero_premiado_mayor and a.sorteo =$sorteo_inicial and a.tipo_pago = ('E'));");		
							
			    	    if ($query_info) 
			    	    {
                    if (mysqli_num_rows($query_info)>0)
                     {
                        
                      echo '<div class="table-responsive" id="tabla">
                            <table id="tabla"  class="table table-hover table-sm table-bordered">
                              <thead><tr align="center">
                                          <th>No.</th>
                                          <th>Detalle de Venta</th>
                                          <th> Sorteo </th>
                                          <th> Número</th>
                                          <th> Décimo </th>
                                          <th>Registro</th>
                                          <th>Total</th>
                                          <th>Impto</th>                    
                                          <th>Neto</th>
                                          <th>Descripcion</th>
                                          <th  id="non-printable" ></th>
                                    </tr>                                 
                                    </thead><tbody>';                     

            			    	    	  $contador=1;  $object=''; $total_acumulado=0; $impto_acumulado=0; $neto_acumulado=0;
                			    	    while ( $row_info= mysqli_fetch_array($query_info)) 
                							  {
                                    $estado_especies=$row_info['estado_especies'];
                                    if ($estado_especies==2) 
                                    {
                                       $object ="<button type='submit' name='update_status' class='btn btn-primary active text-white' value='".$row_info['id']."'>Liberar Pago </button>";
                                    }
                                    else
                                    {
                                      $object = "<button type='submit' name='update_status' disabled='true' class='btn btn-success active text-white' value='".$row_info['id']."'> Pago Liberado </button>" ;
                                    }

                                    $total_acumulado = $total_acumulado + $row_info['total']; 
                                    $impto_acumulado = $impto_acumulado + $row_info['impto'];
                                    $neto_acumulado  = $neto_acumulado  + $row_info['neto'];

            					    	      echo "<tr><td align='center'>".$contador."</td>
            					    	    	  			  <td align='left'>".$row_info['detalle_venta']."</td>
            					    	    	  			  <td align='center'>".$sorteo_inicial."</td>
            					    	    	  			  <td align='center'>".$row_info['numero']."</td>
            					    	    	  			  <td align='center'>".$row_info['decimo']."</td>
            					    	    	  			  <td align='center'>".$row_info['registro']."</td>
            					    	    	  			  <td align='right'>".number_format($row_info['total'],2)."</td>
            					    	    	  			  <td align='right'>".number_format($row_info['impto'],2)."</td>
            					    	    	  			  <td align='right'>".number_format($row_info['neto'],2)."</td>
                                            <td>".$row_info['desc_premio']."</td>                                            
            					    	    	  			  <td id='non-printable' align='center'>".$object."</td>
            					    	    	  		</tr>";  
                                        $contador ++;	     
                               }   
                                echo "<tr><td colspan='10'> -- </td></tr>
                                      <tr><td colspan='6' align='center'> Totales </td>
                                          <td align='right'>".number_format($total_acumulado,2)."</td>
                                          <td align='right'>".number_format($impto_acumulado,2)."</td>
                                          <td align='right'>".number_format($neto_acumulado,2)."</td>
                                          <td></td>
                                          <td></td>
                                      </tr>";                      
      			    	    }
      			    	    else
      			    	    {
      			    	    	echo   "<div class='alert alert-danger'> No existes Pagos pendientes para liberacion</div>"; 
      			    	    }
                }
										    	  
    	       	echo "</tbody></table></div></section>

					 <!-- section id='no_print'>
					 		<div align='center'>
					 				<button class='btn btn-danger btn-lg'  onclick='window.print();' type='button' id='no_print'> <i class='fas fa-print'></i> Imprimir </button>
					 		</div>
					 </section -->"; 	       
    	      
    	       	

    }	

    if (isset($_POST['update_status'])) 
    {
       $id_button=$_POST['update_status'];
      // echo "Este es el id ". $id_button;
       if (mysqli_query($conn, "UPDATE archivo_pagos_mayor SET estado_especies=1 WHERE id=$id_button")) 
       {
         echo "<div class='alert alert-success'> El Archivo ha sido actualizado con exito, realizar nuevamente la consulta ".$id_button."</div>";
       }
       else
       {
       	echo "<div class='alert alert-danger'> Ha ocurrido un error, intentar nuevamente</div>";
       }

    }
  ?>
 	

</div>
 </form>
<script type="text/javascript">
	$(".div_wait").fadeOut("fast"); 


</script>