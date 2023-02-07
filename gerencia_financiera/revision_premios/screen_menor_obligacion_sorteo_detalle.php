
<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['usuario'] ;
$sorteo=$_GET['sorteo'];


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
 <style type="text/css" media="print"> 
 @page  
 {    
    size: A4; 
    landscape;  
 } 
 
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

@media print {
  #no_print 
  { 
    display: none; 
  }   
}

</style>
<form method="post" id="_revision_premios"  class="" name="_revision_premios">
<div class="container-fluid">
<div id='div_wait'></div>
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Detalle de Obligacion de Pagos de Lotería Menor del Sorteo # <?php echo $sorteo; ?>  </h3>   <br></section>
  <div class="table-responsive">
                <table  class="table table-hover table-sm table-bordered">
                   <thead><tr><th>Descripción</th> 
                              <th>Cant.</th>
                              <th>Neto</th> 
                          </tr>                 
                  </thead>
                  <tbody>  
                  <?php    
               
                   $acumulado_final_cantidad = 0;
                   $acumulado_final_neto     = 0;



                   $query_dr= mysqli_query($conn, "SELECT numero,  totalpayment, count(*)  conteo , sum(netopayment) netopayment FROM archivo_pagos_menor WHERE sorteo = $sorteo AND tipo_premio in('PD', 'PR', 'PM' ) GROUP BY numero order by netopayment DESC" );
                   $query_pc= mysqli_query($conn, "SELECT numero,  serie ,totalpayment, 1 as conteo,   netopayment FROM archivo_pagos_menor WHERE sorteo = $sorteo AND tipo_premio in('PC')       order by netopayment desc ;" );
                   $query_ps= mysqli_query($conn, "SELECT serie,    totalpayment,  count(*)  conteo , sum(netopayment) netopayment FROM archivo_pagos_menor WHERE sorteo = $sorteo AND tipo_premio in('PS')       GROUP BY serie order by netopayment desc;" );

                   $acum_cantidad_dr  = 0;
                   $acum_neto_dr      = 0;
                   while ($row_dr=mysqli_fetch_array($query_dr)) {
                      $numero         = $row_dr['numero'];
                      $valor_pagar    = $row_dr['totalpayment'];
                      $conteo_dr      = $row_dr['conteo'];
                      $neto_dr        = $row_dr['netopayment'];

                      $acum_cantidad_dr  = $acum_cantidad_dr  + $conteo_dr;
                      $acum_neto_dr      = $acum_neto_dr      + $neto_dr;

                      $txtnumero= "Numero ".$numero." con premio de L. ".number_format($valor_pagar,2);

                      echo "<tr><td>".$txtnumero."</td>
                                <td  align='center'>".$conteo_dr."</td>
                                 <td align='right'>".number_format($neto_dr,2)."</td>
                            </tr>";                   
                   }


                   $acum_cantidad_dc  = 0;
                   $acum_neto_dc      = 0;
                   
                   while ($row_dc=mysqli_fetch_array($query_pc)) {
                      $numero_dc         = $row_dc['numero'];
                      $serie_dc          = $row_dc['serie'];
                      $valor_pagar       = $row_dc['totalpayment'];
                      $conteo_dc         = $row_dc['conteo'];
                      $neto_dc           = $row_dc['netopayment'];

                      $txt= "Numero ".$numero_dc." y serie ".$serie_dc ." con premio de L. ".number_format($valor_pagar,2);

                      $acum_cantidad_dc  = $acum_cantidad_dc  + $conteo_dc;
                      $acum_neto_dc      = $acum_neto_dc      + $neto_dc;

                      echo "<tr><td>".$txt."</td>
                                <td  align='center'>".$conteo_dc."</td>
                                 <td align='right'>".number_format($neto_dc,2)."</td>
                            </tr>";                   
                   }

                   $acum_cantidad_ps  = 0;
                   $acum_neto_ps      = 0;
                   while ($row_ps=mysqli_fetch_array($query_ps)) {                      
                      $txt_serie_ps      = $row_ps['serie'];
                      $valor_pagar       = $row_ps['totalpayment'];
                      $conteo_ps         = $row_ps['conteo'];
                      $neto_ps           = $row_ps['netopayment'];

                      $txt= "Serie ".$txt_serie_ps." con premio de L. ".number_format($valor_pagar,2);

                      $acum_cantidad_ps  = $acum_cantidad_ps  + $conteo_ps;
                      $acum_neto_ps      = $acum_neto_ps      + $neto_ps;

                      echo "<tr><td>".$txt."</td>
                                <td align='center'>".$conteo_ps."</td>
                                <td align='right'>".number_format($neto_ps,2)."</td>
                            </tr>";                   
                   }

                   $acumulado_final_cantidad= $acum_cantidad_dr + $acum_cantidad_dc + $acum_cantidad_ps ;
                   $acumulado_final_neto= $acum_neto_dr + $acum_neto_dc + $acum_neto_ps ;

                    echo "<tr><td></td>
                                <td align='center'>".$acumulado_final_cantidad."</td>
                                <td align='right'>".number_format($acumulado_final_neto,2)."</td>
                            </tr>";  
                  ?>
                  </tbody>
                </table>                  
                </div><br><br>
                <script type="text/javascript">
             $(".div_wait").fadeOut("fast");  
          </script>
 </section>
 <section id="no_print">
    <div align="center">
      <button class="btn btn-danger btn-lg"  onclick='window.print();' type="button" id="no_print"> <i class="fas fa-print"></i> Imprimir </button>    
    </div>
 </section>
</div>
</form> 
<script type="text/javascript">
  $(".div_wait").fadeOut("fast");  
</script>

