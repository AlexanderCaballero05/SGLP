<?php 
require('../../template/header.php'); 
/*require("../../conexion.php");
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=impuestos_menor.xls");*/

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


@media print {
        #no_print { display: none; }  
        #printOnly {
       display : block;
    }       
    #tabla{
        width:98%
    }
 }
@media screen {
        #printOnly { display: none; } 
    }       
 }
</style>


<style type="text/css" media="print">

@page {    size:  landscape;  } 
th, td { padding-bottom: 0px;   border-spacing: 0; font-family: Arial; font-size: 09pt; } 
  
</style> 

<form method="post" id="_revision_premios"  class="" name="_revision_premios">
<div id='div_wait'></div>
<div id="no_print_fr">
<section>
  <ul class="nav nav-tabs" id="no_print">
     <li class="nav-item">
        <a style="background-color:#ededed;" class="nav-link active" href="#" onclick='$(".div_wait").fadeIn("fast"); '>Premios de Lotería Mayor > 30,000 no Cobrados (Caducos)</a>       
      </li>
      <li class="nav-item">
        <a class="nav-link" href="screen_reporte_impuestos_menor.php">Premios de Lotería Menor > 30,000 no Cobrados (Caducos)</a>
      </li>
  </ul> 
</section>
</div>
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Premios de Lotería Mayor > 30,000 no Cobrados (Caducos)</h3> <br></section>

    <script type="text/javascript">
       $(".div_wait").fadeIn("fast");  
    </script>

<section>
<?php 

    $query_premios=mysqli_query($conn, "SELECT sorteo, numero, 10 as decimos ,total totalpayment, impto imptopayment, neto netopayment FROM archivo_pagos_mayor WHERE impto>0 and tipo_pago<>'E' ORDER BY sorteo ASC, total DESC;");

    $status='';
    echo "<div id='tabla' class='table-responsive' >";
    echo "<table class='table table-bordered table-hover table-sm' ><thead><tr>
    						<td align='center' class='font-weight-bold'>Fecha de Sorteo</td>
                            <td align='center' class='font-weight-bold'>Sorteo</td>
    						<td align='center' class='font-weight-bold'>Número Favorecido</td>
    						<td align='center' class='font-weight-bold'>Décimos no Cobrados</td>
    						<td align='center' class='font-weight-bold'>Total premio no Cobrado</td>
    						<td align='center' class='font-weight-bold'>Impto</td>
                            <!-- th>impto_pagado</th -->
                            <!-- th>Impto pendiente</th -->
    						<!-- td align='center' class='font-weight-bold'>Neto</td--></tr></thead><tbody>";

    $acum_decimos=0;            $acum_total=0;            $acum_impto=0;            $acum_neto=0;
    $decimos_pendientes =   0;  
    $total_pendiente    =   0;
    $impto_pendiente    =   0;
    $neto_pendiente     =   0;   

    while ($row_premios = mysqli_fetch_array($query_premios)) 
    {
        $sorteo 		= $row_premios['sorteo'];
        $numero 		= $row_premios['numero'];
        $decimos 		= $row_premios['decimos'];
        $totalpayment   = $row_premios['totalpayment'];
        $imptopayment 	= $row_premios['imptopayment'];
        $netopayment 	= $row_premios['netopayment'];

        $query_fecha_sorteo   = mysqli_query($conn, "SELECT fecha_sorteo FROM sorteos_mayores WHERE id=$sorteo;");
        $ob_fecha_sorteo      = mysqli_fetch_object($query_fecha_sorteo);
        $fecha_sorteo         = $ob_fecha_sorteo->fecha_sorteo;



        $query_pagado= mysqli_query($conn, "SELECT numero, sum(a.decimos) decimos_pagados, sum(a.totalpayment) total_pagado,  sum(a.imptopayment) impto_pagado, sum(a.netopayment) neto_pagado 
										    FROM mayor_pagos_detalle a, mayor_pagos_recibos b 
											WHERE a.transactioncode=b.transactioncode  and sorteo=$sorteo and numero=$numero  and a.transactionstate in (1,3)  and tipo_premio<>'E' ;");

        $ob_pagado      = mysqli_fetch_object($query_pagado);

        if (isset($ob_pagado)) 
        {       
                $decimos_pagados        = $ob_pagado->decimos_pagados;
                $total_pagado           = $ob_pagado->total_pagado;
                $impto_pagado           = $ob_pagado->impto_pagado;
                $neto_pagado            = $ob_pagado->neto_pagado;  
        }
        else
        {
            $decimos_pagados = 0;
            $total_pagado    = 0;
            $impto_pagado    = 0;
            $neto_pagado     = 0;
        }
        
        			$decimos_pendientes 	=	$decimos 		  -  $decimos_pagados;
        			$total_pendiente		=	$totalpayment     -	 $total_pagado;
        			$impto_pendiente		=	$imptopayment     -  $impto_pagado;
        			$neto_pendiente			=	$netopayment	  -  $neto_pagado;

        		if ($impto_pendiente>0 ) 
        		{
                        $acum_decimos   =   $acum_decimos+$decimos_pendientes;
                        $acum_total     =   $acum_total+$total_pendiente;
                        $acum_impto     =   $acum_impto+$impto_pendiente;
                        $acum_neto      =   $acum_neto+$neto_pendiente;

        			   echo "<tr><td align='center'>".$fecha_sorteo."</td>
		          		         <td align='center'>".$sorteo."</td>
                                 <td align='center'>".$numero."</td>
		          		         <td align='center'>".$decimos_pendientes."</td>
		          		         <td align='right'>".number_format($total_pendiente,2)."</td>
                                 <!-- td align='right'>".number_format($imptopayment,2)."</td -->
                                 <!-- td align='right'>".number_format($impto_pagado,2)."</td -->
		          		         <td align='right'>".number_format($impto_pendiente,2)."</td>
		          		         <!-- td align='right'>".number_format($neto_pendiente,2)."</td --></tr>";		          		      
        		}		         
    }
      echo "<tr class='table-success'><td colspan='3' align='center'> Total </td>
                                      <td align='center'>".$acum_decimos."</td>
                                      <td align='right'>".number_format($acum_total,2)."</td>
                                      <td align='right'>".number_format($acum_impto,2)."</td>
                                      <!-- td align='right'>".number_format($acum_neto,2)."</td --></tr>";

    echo "</tbody></table></div>";

  ?>
 	  </section>
</form>
     <script type="text/javascript">
       $(".div_wait").fadeOut("fast");  
    </script>

