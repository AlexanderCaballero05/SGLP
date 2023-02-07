<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['usuario'] ;
$fecha=$_GET['fecha'];



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
 @page {    size: a4; landscape;  }
 
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
<form method="post" id="_revision_premios"  class="" name="_revision_premios">
<div class="container-fluid">
<div id='div_wait'></div>
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Detalle de Pagos Pendientes de Revision de Lotería Menor de Fecha <?php echo $fecha; ?>  </h3>   <br></section>
 <?php    
    $cantidad_acumulado_final=0;  $neto_acumulado_final=0;      
        
         echo  "<table width='96%'  id='tableinfo' align='center' class='table table-hover table-sm table-bordered'>         
                <thead><th>No.</th>
                       <th>Agencia</th>
                       <th>Cajero</th>
                       <th>Sorteo</th>
                       <th>Numero</th>
                       <th>Serie</th>
                       <th>Total</th>
                       <th>Impto</th>
                       <th>Neto</th>
                       <th>Fecha Recepcion</th>
                       <th>Remesa</th>
                       <th>Usuario</th>
                </thead><tbody>";
         
      
          $query_numeros=mysqli_query($conn, "SELECT b.transactionagency,  b.transactionagencyname, b.transactionusername, a.sorteo, a.numero, a.serie, a.registro, a.principal, a.impto, a.neto,  a.fecha_recepcion_banco, a.remesa, c.usuario_revision, d.nombre_completo
                                              FROM menor_pagos_detalle a , menor_pagos_recibos b, rp_asignacion_agencias_revisor_menor c, pani_usuarios d
                                              WHERE 
                                              a.transactioncode=b.transactioncode and 
                                              b.transactionagency=c.transactionagency and 
                                              a.remesa=c.remesa  and 
                                              date(a.transactiondate)= date(c.transactiondate)  and 
                                              date(a.transactiondate)='$fecha' and 
                                              a.transactionstate=1 and 
                                              (a.estado_revision = 0 or a.estado_revision is null ) and 
                                              a.ano_remesa = '2019' and 
                                              c.usuario_revision=d.id");

        $total_acumulado=0; $impto_acumulado=0; $neto_acumulado=0; $contador=1;
        while ($row_numeros=mysqli_fetch_array($query_numeros)) 
        {
              $agencia                     = $row_numeros['transactionagency']."--".$row_numeros['transactionagencyname'];
              $transactionusername         = $row_numeros['transactionusername'];
              $sorteo                      = $row_numeros['sorteo'];
              $numero                      = $row_numeros['numero'];
              $serie                       = $row_numeros['serie'];
              $registro                    = $row_numeros['registro'];
              $principal                   = $row_numeros['principal'];
              $impto                       = $row_numeros['impto'];
              $neto                        = $row_numeros['neto'];
              $fecha_recepcion_banco       = $row_numeros['fecha_recepcion_banco'];
              $remesa                      = $row_numeros['remesa'];
              $nombre_completo             = $row_numeros['nombre_completo'];
              

              $total_acumulado=$total_acumulado+$principal; $impto_acumulado=$impto_acumulado+$impto; $neto_acumulado= $neto_acumulado + $neto;

                  echo "<tr><td align='center'>".$contador."</td>
                            <td align='center'>".$agencia."</td>  
                            <td align='center'>".$transactionusername."</td>  
                            <td align='center'>".$sorteo."</td>  
                            <td align='center'>".$numero."</td>  
                            <td align='center'>".$serie."</td>                                
                            <td align='right'>".number_format($principal,2)."</td>  
                            <td align='right'>".number_format($impto,2)."</td>  
                            <td align='right'>  ".number_format($neto,2)."</td>  
                            <td align='center'>".$fecha_recepcion_banco."</td>  
                            <td align='center'>  ".$remesa."</td> 
                            <td align='center'>".$nombre_completo."</td></tr>";
                  $contador++;      
          }     
                   
     
        echo "</tbody>
                      <tr><td colspan='6'></td>
                          <td align='right'>".number_format($total_acumulado, 2)."</td>
                          <td align='right'>".number_format($impto_acumulado, 2)."</td>
                          <td align='right'>".number_format($neto_acumulado, 2)."</td>                          
                          <td colspan='3'></td>                          
                      </tr>
        </table>";

  ?>
                



   
 </section>
 <section id="no_print">
    <div align="center">
    <button class="btn btn-danger btn-lg"  onclick='window.print();' type="button id="no_print""> <i class="fas fa-print"></i> Imprimir </button>
    </div>
 </section>

</div>
</form>



 
<script type="text/javascript">
  $(".div_wait").fadeOut("fast");  
</script>

