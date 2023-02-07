<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['id_usuario'];
$fecha_pago=$_GET['fecha'];
$agencia_code=$_GET['agencia_code'];
$agencia_name=$_GET['agencia_name'];

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
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><br><h3>Detalle de Bitacora de Revision de Lotería Mayor Agencia  : <?php echo $agencia_code."-".$agencia_name; ?> de fecha <?php echo $fecha_pago; ?> </h3> <br></section> 
<section>  <hr>
  <div class="table-responsive">
     <table class="table table-sm table-bordered table-hover" id="">
        <tr><th>No.</th>
            <th>Remesa</th>
            <th>Revisor</th>            
            <th>Sorteo</th>
            <th>Número</th>
            <th>Serie</th>
            <th>Registro</th>
            <th>Total</th>
            <th>Impto</th>
            <th>Neto</th>
            <th>Estado de Revision</th>
        </tr>
        <tbody>
        <?php 
              $query_billetes= mysqli_query($conn, "SELECT  a.remesa, a.usuario_revision_name, a.sorteo, a.numero, a.decimos, a.registro, a.totalpayment, a.imptopayment, a.netopayment , a.estado_revision FROM mayor_pagos_detalle a, mayor_pagos_recibos b WHERE a.transactioncode=b.transactioncode and a.transactionstate=1 and date(a.transactiondate) ='$fecha_pago' and b.transactionagency= $agencia_code");
              
              if ($query_billetes) 
              { 
                  $estado_revision_txt="";
                  $no=1;
                  $total_acumulado =  0;
                  $impto_acumulado =  0;
                  $neto_acumulado  =  0;
                  while ($row_info_billetes=mysqli_fetch_array($query_billetes) ) 
                  {
                    $remesa=$row_info_billetes['remesa'];
                    $sorteo=$row_info_billetes['sorteo'];
                    $numero=$row_info_billetes['numero'];
                    $decimos=$row_info_billetes['decimos'];
                    $registro=$row_info_billetes['registro'];
                    $totalpayment=$row_info_billetes['totalpayment'];
                    $imptopayment=$row_info_billetes['imptopayment'];
                    $netopayment=$row_info_billetes['netopayment'];
                    $estado_revision=$row_info_billetes['estado_revision'];
                    $usuario_revision_name=$row_info_billetes['usuario_revision_name'];

                    if ($estado_revision==1) 
                    {
                       $estado_revision_txt="Revisado";
                    }
                    else if ($estado_revision==2) 
                    {
                       $estado_revision_txt="Observacion";
                    }
                    else if (empty($estado_revision)) 
                    {
                       $estado_revision_txt="No revisado";
                    }
                    $total_acumulado =  $total_acumulado+ $totalpayment;
                    $impto_acumulado =  $impto_acumulado+ $imptopayment;
                    $neto_acumulado  =  $neto_acumulado+ $netopayment;

                      echo "<tr><td>".$no."</td>
                                <td>".$remesa."</td>
                                <td>".$usuario_revision_name."</td>
                                <td>".$sorteo."</td>
                                <td>".$numero."</td>
                                <td>".$decimos."</td>
                                <td>".$registro."</td>
                                <td>".$totalpayment."</td>
                                <td>".$imptopayment."</td>
                                <td>".$netopayment."</td>
                                <td>".$estado_revision."--".$estado_revision_txt."</td>
                            </tr>";
                            $no++;
                  }  
              }
              else
              {
                echo mysqli_error();
              }
              $no=$no-1;
              echo "<tr class='table-success'>
                        <td>".$no."</td>
                        <td colspan='6'></td>
                        <td>".number_format($total_acumulado,2)."</td>
                        <td>".number_format($impto_acumulado,2)."</td>
                        <td>".number_format($neto_acumulado,2)."</td>
                        <td></td>
                        
                    </tr>";

         ?>          
        </tbody>       
     </table>
  </div>
   
</section>


<script type="text/javascript">
       $(".div_wait").fadeOut("fast");  
</script>
