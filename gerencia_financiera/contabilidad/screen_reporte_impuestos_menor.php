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
        <a class="nav-link" href="screen_reporte_impuestos_mayor.php">Premios de Lotería Mayor > 30,000 no Cobrados (Caducos)</a>       
      </li>
      <li class="nav-item">
        <a style="background-color:#ededed;" class="nav-link active" href="#">Premios de Lotería Menor > 30,000 no Cobrados (Caducos)</a>
      </li>
  </ul> 
</section>
</div>
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Premios de Lotería Menor > 30,000 no Cobrados (Caducos)</h3></section>

    <script type="text/javascript">
       $(".div_wait").fadeIn("fast");  
    </script>

<section>
<?php 

    $query_premios=mysqli_query($conn, "SELECT sorteo, numero, serie, totalpayment, imptopayment, netopayment FROM archivo_pagos_menor  WHERE totalpayment>=30000 ORDER BY sorteo ASC, totalpayment DESC;");

    $status='';
    echo "<div id='tabla' class='table-responsive'>";
    echo 	 "<table class='table table-bordered table-hover table-sm' ><thead><tr>
                <td></td>
    						<td align='center' class='font-weight-bold'>Fecha Sorteo</td>
                <td align='center' class='font-weight-bold'>Sorteo</td>
    						<td align='center' class='font-weight-bold'>Número Favorecido</td>
    						<td align='center' class='font-weight-bold'>Serie</td>
    						<td align='center' class='font-weight-bold'>Total No Cobrado</td>
    						<td align='center' class='font-weight-bold'>Impto No Cobrado</td></thead><tbody>";

$acum_total=0;  $acum_impto=0;  $acum_neto=0;   $contador=1;
    while ($row_premios = mysqli_fetch_array($query_premios)) 
    {
        $sorteo 		    = $row_premios['sorteo'];
        $numero 		    = $row_premios['numero'];
        $serie 			    = $row_premios['serie'];
        $totalpayment   = $row_premios['totalpayment'];
        $imptopayment 	= $row_premios['imptopayment'];
        $netopayment 	  = $row_premios['netopayment'];

        $query_fecha_sorteo   = mysqli_query($conn, "SELECT fecha_sorteo FROM sorteos_menores WHERE id=$sorteo;");
        $ob_fecha_sorteo      = mysqli_fetch_object($query_fecha_sorteo);
        $fecha_sorteo         = $ob_fecha_sorteo->fecha_sorteo;

        $query_pagado= mysqli_query($conn, "SELECT * FROM menor_pagos_detalle WHERE sorteo=$sorteo and numero=$numero and serie=$serie and transactionstate=1");
        if (!mysqli_num_rows($query_pagado)>0) 
        {
          $acum_total = $acum_total + $totalpayment;
          $acum_impto = $acum_impto + $imptopayment;
          $acum_neto  = $acum_neto  + $netopayment;

          echo "<tr><td align='center'>".$contador."</td>
                    <td align='center'>".$fecha_sorteo."</td>
          		      <td align='center'>".$sorteo."</td>
          		      <td align='center'>".$numero."</td>
          		      <td align='center'>".$serie."</td>
          		      <td align='right'>".number_format($totalpayment,2)."</td>
          		      <td align='right'>".number_format($imptopayment,2)."</td>
          		      <!-- td align='right'>".number_format($netopayment,2)."</td --></tr>";
                    $contador++;
        }
    }

    echo "<tr class='table-success'><td colspan='5' align='center'> Total </td>
              <td align='right'>".number_format($acum_total,2)."</td>
              <td align='right'>".number_format($acum_impto,2)."</td>
              <!-- td align='right'>".number_format($acum_neto,2)."</td -->            
          </tr>";
    echo "</tbody></table></div>";

  ?>
  </section>
</form>
 	 <script type="text/javascript">
       $(".div_wait").fadeOut("fast");  
    </script>


