<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['id_usuario'];
$usuario_name=$_SESSION['nombre_usuario'];


?>
<script type="text/javascript">
  $(".div_wait").fadeIn("fast");  



 </script>
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

.modal-lg {
    max-width: 80%;
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
 }

</style>


<form method="post" id="_revision_premios" name="_revision_premios">
<div class="container-fluid">
<ul class="nav nav-tabs" id="no_print">
<li class="nav-item">
    <a class="nav-link" href="./screen_mayor_reporte_impuestos.php" >Impuestos de Lotería Mayor</a>
  </li>
  <li class="nav-item">
    <a style="background-color:#ededed;" class="nav-link active" href="#" >Impuestos de Lotería Menor</a>
  </li>
</ul> 
<div id="no_print_fr">
<div id="div_wait" class="div_wait">  </div>
<section id="no_print" style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Reporte de Impuestos de Loteria Menor</h3><br></section>
<section>
<a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS  <i class="far fa-hand-point-down fa-lg"></i></a>
    <div class="collapse " id="collapse1" align="center">
      <div class="card">
        <div class="card card-body" id="non-printable" >
          <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
            <div class = "input-group-prepend"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i> &nbsp;  Remesa: </span></div>
                <input type="month"  class="form-control" id="mes" name="mes"  min="2019-01" value="">     
            <button id="buttonConsulta" name="seleccionar" type="submit" class="Consulta btn btn-primary">BUSQUEDA DE BILLETES PAGADOS</button>
          </div>
        </div>
      </div>
    </div> 
    <input type="hidden" name="slctrevisor" value="<?php echo $usuario_id  ?> ">
 </section>
</div>
 <hr>

<?php 
if (isset($_POST['seleccionar'])) 
{ 
  setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
  setlocale(LC_ALL, "es_ES");

  $mes=$_POST['mes'];
  $mes_txt = date('d-m-Y', strtotime($_POST['mes']));
  $año_txt = date('Y', strtotime($_POST['mes']));   
  $monthName = date("F", strtotime( $mes_txt )); 
  $monthNum  = date('m', strtotime($_POST['mes'])); 
  $dateObj   = DateTime::createFromFormat('!m', $monthNum);
  $monthName = strftime('%B', $dateObj->getTimestamp());

  $total_pagar_final=0;
  echo "<div align='center' class='alert alert-success' style='font-size:16pt;' ><strong> Patronato Nacional de la Infancia PANI <br> Departamento de Revisión de Premios <br> Reporte de Impuesto de Lotería Menor del Mes de ".$monthName." de ".$año_txt." </strong></div>";
  $query_sorteos_mes=mysqli_query($conn, "SELECT b.id, b.fecha_sorteo FROM sorteos_menores b WHERE  date_format(b.fecha_sorteo , '%Y-%m')='$mes' GROUP BY b.id ;");

  ?>
   <table width="100%"  id="table" align="center" class="table table-hover table-bordered table-sm">
          <thead align='center'> 
            <tr><td>Sorteo</td> 
            	<td>Fecha de Sorteo</td> 
           		<td>Número</td> 
           		<td>Serie</td>
           		<td>Total</td>
           		<td>Impto</td>
           		<td>Neto</td>
           		<td>Estado</td>
           		<td>Neto a Pagar</td></tr>
         </thead>
         <tbody>
  <?php 

  while ( $_row_sorteos=mysqli_fetch_array($query_sorteos_mes)) 
    {
       $_sorteo=$_row_sorteos['id'];  $_fecha_sorteo=$_row_sorteos['fecha_sorteo'];  $acumulado_cantidad=0;  $acumulado_total=0;  $acumulado_impto=0;  $acumulado_neto=0;  $acumulado_impto_pagar=0;   $sumatoria_Serie=0;
      $query_numeros_ganadores=mysqli_query($conn, "SELECT a.numero_premiado_menor, a.monto pago_premio FROM sorteos_menores_premios a  WHERE  a.sorteos_menores_id= '$_sorteo' AND a.premios_menores_id  in(1) ;");
	  while ($_numeros_ganadores=mysqli_fetch_array($query_numeros_ganadores))
      {
         $_numero_ganador_derecho=$_numeros_ganadores['numero_premiado_menor'];
         $_monto_ganador_derecho=$_numeros_ganadores['pago_premio'];

         if ($_numero_ganador_derecho==00 or $_numero_ganador_derecho==11 or $_numero_ganador_derecho==22 or $_numero_ganador_derecho==33 or $_numero_ganador_derecho==44 or $_numero_ganador_derecho==55 or $_numero_ganador_derecho==66 or $_numero_ganador_derecho==77 or $_numero_ganador_derecho==88 or $_numero_ganador_derecho==99) 
         {
           $_monto_ganador_derecho=1100;
         }

         $query_series_ganadores=mysqli_query($conn, "SELECT numero_premiado_menor, monto pago_premio FROM sorteos_menores_premios  WHERE  sorteos_menores_id=$_sorteo AND premios_menores_id = 2; ");
           $estado='';
                while ($_series_ganadores=mysqli_fetch_array($query_series_ganadores))
                {
                  $_numero_serie_ganador_derecho=$_series_ganadores['numero_premiado_menor'];
                  $_monto_serie_ganador_derecho=$_series_ganadores['pago_premio'];
                  $suma_pago= $_monto_ganador_derecho+$_monto_serie_ganador_derecho;

                  if ($suma_pago>30000) 
                  {
                    $impto=$suma_pago*0.10;     
                  }
                  else
                  {
                    $impto=0;
                  }

                  $neto=$suma_pago-$impto;
                  $query_pagos_2=mysqli_query($conn, "SELECT * FROM ventas_distribuidor_menor WHERE sorteo=$_sorteo and numero=$_numero_ganador_derecho and serie=$_numero_serie_ganador_derecho ");
                  if ($query_pagos_2)
                  {
                    if (mysqli_num_rows($query_pagos_2)>0) 
                    {
                    $estado='Vendido';
                    $neto_pagar=$impto;
                    }
                    else
                    {
                    $estado='No Vendido';
                    $neto_pagar=0;
                    }
                  }
                    echo " <tr><td align='center'>".$_sorteo."</td> 
                    		   <td align='center'>".$_fecha_sorteo."</td> 
	                           <td align='center'> ".$_numero_ganador_derecho." </td>
	                           <td align='center'>".$_numero_serie_ganador_derecho."</td>
	                           <td align='right'>".number_format($suma_pago,2,'.',',')."</td>
	                           <td align='right'>".number_format($impto,2,'.',',')."</td>
	                           <td align='right'>".number_format($neto,2,'.',',')."</td>
	                           <td align='right'>".$estado."</td>
	                           <td align='right'>".number_format($neto_pagar,2,'.',',')."</td></tr>";
                }
      }

      $query_numeros_ganadores=mysqli_query($conn, "SELECT a.numero_premiado_menor, b.pago_premio FROM sorteos_menores_premios a, premios_menores b WHERE a.premios_menores_id=b.id and a.sorteos_menores_id=$_sorteo AND  b.tipo_serie='REVES' and b.clasificacion='NUMERO';");

while ($_numeros_ganadores=mysqli_fetch_array($query_numeros_ganadores))
      {
         $_numero_ganador_derecho=$_numeros_ganadores['numero_premiado_menor'];
         $_monto_ganador_derecho=$_numeros_ganadores['pago_premio'];
          if ($_numero_ganador_derecho==00 or $_numero_ganador_derecho==11 or $_numero_ganador_derecho==22 or $_numero_ganador_derecho==33 or $_numero_ganador_derecho==44 or $_numero_ganador_derecho==55 or $_numero_ganador_derecho==66 or $_numero_ganador_derecho==77 or $_numero_ganador_derecho==88 or $_numero_ganador_derecho==99) {
           $_monto_ganador_derecho=1100;
         }

         $query_series_ganadores=mysqli_query($conn, "SELECT a.numero_premiado_menor, b.pago_premio FROM sorteos_menores_premios a, premios_menores b WHERE a.premios_menores_id=b.id and a.sorteos_menores_id=$_sorteo  AND  b.tipo_serie='REVES' and b.clasificacion='SERIE';");

         while ($_series_ganadores=mysqli_fetch_array($query_series_ganadores))
                {
                  $_numero_serie_ganador_derecho=$_series_ganadores['numero_premiado_menor'];
                  $_monto_serie_ganador_derecho=$_series_ganadores['pago_premio'];
                  $suma_pago_serie= $_monto_ganador_derecho+$_monto_serie_ganador_derecho;

                  if ($suma_pago_serie>30000) 
                  {
                    $impto_serie=$suma_pago_serie*0.10;     
                  }
                  else
                  {
                    $impto_serie=0;
                  }

 				          $query_pagos=mysqli_query($conn, "SELECT * FROM ventas_distribuidor_menor WHERE sorteo=$_sorteo and numero=$_numero_ganador_derecho and serie=$_numero_serie_ganador_derecho ");
         				  if ($query_pagos)
         				  {
                    if (mysqli_num_rows($query_pagos)>0) 
                    {
                        $estado='Vendido';
                        $neto_pagar_serie=$impto_serie;
                    }
                    else
                    {
                    $estado='No Vendido';
                    $neto_pagar_serie=0;
                    }                     
                  }
                  

                  $neto_serie=$suma_pago_serie-$impto_serie;
                    echo "<tr> <td align='center'>".$_sorteo."</td>
                    		   <td align='center'>".$_fecha_sorteo."</td>  
	                           <td align='center'> ".$_numero_ganador_derecho." </td>
	                           <td align='center'>".$_numero_serie_ganador_derecho."</td>
	                           <td align='right'>".number_format($suma_pago_serie,2,'.',',')."</td>
	                           <td align='right'>".number_format($impto_serie,2,'.',',')."</td>
	                           <td align='right'>".number_format($neto_serie,2,'.',',')."</td>
	                           <td align='right'>".$estado."</td>
	                           <td align='right'>".number_format($neto_pagar_serie,2,'.',',')."</td></tr>";
                           $sumatoria_Serie=$sumatoria_Serie+$neto_pagar_serie;
                }

                $neto_pagar_acumulado=$neto_pagar+$sumatoria_Serie;

                        echo "<tr><td align='center' colspan='8'> Total a Pagar </td>
                           		  <td align='right'>".number_format($neto_pagar_acumulado,2,'.',',')."</td></tr>";
                          $total_pagar_final=$total_pagar_final+$neto_pagar_acumulado;
      }

 
}
    echo "<tr><td align='center' colspan='9'> -- </td></tr><tr><td align='center' colspan='9'> -- </td></tr>
          <tr><td align='center' colspan='8'> Total a Pagar de impuestos generados en el perido de ".$monthName." de ".$año_txt." </td>
                           <td align='right'>".number_format($total_pagar_final,2,'.',',')."</td></tr>"; 

	?>
		   </tbody>
	   </table>
	</div>
          


</div>
   <section id="no_print">
	<div align="center">
		<button class="btn btn-danger btn-lg"  onclick='window.print();' type="button" id="no_print"> <i class="fas fa-print"></i> Imprimir </button>
	</div>
  </section>
  <div align="center" id="printOnly">
    <br><br><br><br> <p id="printOnly"> __________________________________ <br> Firma y Sello  <br> <?php echo $usuario_name; ?> </p>
 </div>
    <?php 
}
  ?>
      
</form>



<script type="text/javascript">
	$(".div_wait").fadeOut("fast");  
</script>
