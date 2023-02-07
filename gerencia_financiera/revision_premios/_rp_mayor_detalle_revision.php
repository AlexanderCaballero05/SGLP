<?php 
require('../../template/header.php'); 
$usuario_id=isset($_GET['id_usuario']); 
$nombre_usuario=$_SESSION['nombre']; 
 if (!isset($usuario_id))  
 {
  $usuario_id=$_SESSION['id_usuario']; 
 }

$fecha_pago=$_GET['fecha'];
$cod_agencia=$_GET['cod_agencia'];

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
<script type="text/javascript"> 
 



</script>
<form method="post">
<div id="div_wait" class="div_wait">  </div> 
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><br><h3>Detalle de Revisión de Billetes Premiados de Lotería Mayor de fecha <?php echo $fecha_pago; ?> en la agencia <?php echo "MERCADO EL RAPIDO"; ?> </h3> <br></section>
 <hr>
 <section>
   <div class="table-responsive">
     <table class="table table-bordered table-hover">
        <thead><tr><th>No.</th>
                   <th>Sorteo</th>
                   <th>Número</th>
                   <th>Registro</th>
                   <th>Décimos</th>
                   <th>Total</th>
                   <th>Impto</th>
                   <th>Neto</th>
                   <th>Fecha de Revisión</th>
              </tr>          
        </thead>
        <tbody>
          <?php 
            $query_pagos= mysqli_query($conn, "SELECT sorteo, numero, registro, decimos, a.totalpayment, a.imptopayment, a.netopayment, estado_revision, fecha_revision
                                               FROM mayor_pagos_detalle a INNER JOIN mayor_pagos_recibos b ON a.transactioncode=b.transactioncode 
                                               WHERE a.transactionstate=1 and date(a.transactiondate) between '$fecha_pago' and '$fecha_pago' and b.transactionagency=$cod_agencia order by netopayment desc;");

            $no=1;    
            $flag=0;            
            $txt_estado='';
            $decimos_acumulado = 0;
            $total_acumulado = 0;            
            $impto_acumulado = 0;            
            $neto_acumulado  = 0;
            while ($row_pagos=mysqli_fetch_array($query_pagos)) 
            {
               $sorteo            = $row_pagos['sorteo'];
               $numero            = $row_pagos['numero'];
               $registro          = $row_pagos['registro'];
               $decimos           = $row_pagos['decimos'];
               $totalpayment      = $row_pagos['totalpayment'];
               $imptopayment      = $row_pagos['imptopayment'];
               $netopayment       = $row_pagos['netopayment'];
               $estado_revision   = $row_pagos['estado_revision'];
               $fecha_revision    = $row_pagos['fecha_revision'];

               $decimos_acumulado = $decimos_acumulado + $decimos;
               $total_acumulado   = $total_acumulado   + $totalpayment;
               $impto_acumulado   = $impto_acumulado   + $imptopayment;
               $neto_acumulado    = $neto_acumulado    + $netopayment;



               echo "<tr><td align='center'>".$no."</td>
                         <td align='center'>".$sorteo."</td>
                         <td align='center'>".$numero."</td>
                         <td align='center'>".$registro."</td>
                         <td align='center'>".$decimos."</td>
                         <td align='right'>".number_format($totalpayment,2)."</td>
                         <td align='right'>".number_format($imptopayment,2)."</td>
                         <td align='right'>".number_format($netopayment,2)."</td>                         
                         <td align='center'>".$fecha_revision."</td>                         
                     </tr>";
                  $no++;
            }

            echo "<tr><td colspan='4'></td>
                      <td align='center'>".$decimos_acumulado."</td>
                      <td align='right'>".number_format($total_acumulado,2)."</td>
                      <td align='right'>".number_format($impto_acumulado,2)."</td>                         
                      <td align='right'>".number_format($neto_acumulado,2)."</td>                      
                      <td></td>                      
                  <tr>";

           ?>          
        </tbody>
     </table>
   </div>
 </section>


</form>
<script type="text/javascript">
       $(".div_wait").fadeOut("fast");  
</script>


