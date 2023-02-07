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
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Detalle de Pagos de Lotería Mayor del Sorteo # <?php echo $sorteo; ?>  </h3>   <br></section>
 <?php    
    $cantidad_acumulado_final=0;  $neto_acumulado_final=0;      
        
         echo  "<table width='96%'  id='tableinfo' align='center' class='table table-hover table-sm table-bordered'>         
                <thead><th>No.</th>
                      <th>Descripción de Pliegos Pagados</th>
                      <th>Cantidad de Decimos</th>
                      <th>Total Pagado</th>
                </thead><tbody>";
         
      
          $query_numeros=mysqli_query($conn, "(SELECT a.totalpayment valor_premio , a.numero numero, a.tipo_premio tipo_premio , count(decimos) cantidad, sum(a.totalpayment) total, sum(a.imptopayment) total_impto, sum(a.netopayment) total_neto, sum(decimos) num_dec, decimos,  (sum(a.totalpayment)/sum(decimos))*10 pago_por_terminacion
          FROM mayor_pagos_detalle a, mayor_pagos_recibos b
          WHERE
          a.transactioncode=b.transactioncode and a.tipo_premio in ('U', 'E') and  a.transactionstate in (1,3)  and  a.sorteo=$sorteo 
          GROUP BY  a.numero order by  a.totalpayment desc
          )UNION
          (SELECT a.totalpayment valor_premio , a.numero numero, a.tipo_premio  tipo_premio, count(decimos) cantidad, sum(a.totalpayment) total, sum(a.imptopayment) total_impto, sum(a.netopayment) total_neto , a.decimos, sum(decimos) num_dec , (a.totalpayment/a.decimos )*10 pago_por_terminacion
          FROM mayor_pagos_detalle a, mayor_pagos_recibos b
          WHERE
          a.transactioncode=b.transactioncode and a.tipo_premio='T' and  a.transactionstate in (1,3)  and a.sorteo=$sorteo
          GROUP BY pago_por_terminacion order by pago_por_terminacion desc ) order by pago_por_terminacion desc ;");

        $total_acumulado=0;  $impto_acumulado=0;   $neto_acumulado=0;    $cantidad_acumulado=0;   $contador=1; $numero_termi=0; $pago_por_decimo=0; $total_premio=0;
        $conteo_termi=0; $detalle='';
        while ($row_numeros=mysqli_fetch_array($query_numeros)) 
        {
                   $neto_acumulado=$neto_acumulado+$row_numeros['total_neto'];
                   $pago_por_decimo=  number_format($row_numeros['pago_por_terminacion'],2,'.',',');//($row_numeros['total']/$row_numeros['decimos']);
                   $numero_termi="";  $total_premio=0;

                  if ( $row_numeros['tipo_premio']=='E' ) 
                  {                    
                     $cantidad_acumulado++;   $cantidad_billetes=1; $numero=$row_numeros['valor_premio'];   $palabra='Valor Monetario de Premio de especies '; 
                     $numero_termi=$row_numeros['numero'];  $total_premio= number_format(($row_numeros['total_neto']),2,'.',',');
                     $decimos_especie=$row_numeros['decimos'];
                     $detalle=$palabra."   ".$numero_termi." por L. ".$total_premio." pagados al decimo numero ".$decimos_especie;                    
                  }
                  else if ( $row_numeros['tipo_premio']=='U' ) 
                  {
                    $cantidad_acumulado=$cantidad_acumulado+$row_numeros['num_dec'];  $cantidad_billetes=$row_numeros['num_dec'];  $numero=$row_numeros['numero'];  $palabra='Premio de Urna ';  
                    $query_pago_urna=mysqli_query($conn, "SELECT CEIL(numero_premiado_mayor) numero_premiado_mayor,  b.total monto FROM `sorteos_mayores_premios` a, archivo_pagos_mayor b WHERE b.sorteo=a.sorteos_mayores_id and a.numero_premiado_mayor=b.numero and  a.numero_premiado_mayor=$numero and a.sorteos_mayores_id=$sorteo  ");
                    while ($_row_urna=mysqli_fetch_array($query_pago_urna)) {  $numero_termi=$_row_urna['numero_premiado_mayor'];     $total_premio= number_format(($_row_urna['monto']/10),2,'.',',');  }
                    $detalle=$palabra."   ".$numero_termi." por L. ".$pago_por_decimo." el billete y L.  ".$total_premio." el decimo";
                  }
                  else
                  {
                    $palabra='Premio por Terminación';  
                    $cantidad_acumulado=$cantidad_acumulado+$row_numeros['decimos'];  
                    $cantidad_billetes=$row_numeros['decimos'];
                    $query_pago_termi=mysqli_query($conn, "SELECT CEIL(numero_premiado_mayor) numero_premiado_mayor, numero_premiado_mayor_desc, monto FROM sorteos_mayores_premios WHERE respaldo='terminacion' and monto='$pago_por_decimo' and sorteos_mayores_id=$sorteo ;");

                    while ($_row_termi=mysqli_fetch_array($query_pago_termi)) 
                      {  
                          $numero_termi=$_row_termi['numero_premiado_mayor_desc'];   $total_premio= number_format(($_row_termi['monto']/10),2,'.',',');   
                      }  
                      $detalle=$palabra."   ".$numero_termi." por L. ".$pago_por_decimo." el billete y L.  ".$total_premio." el decimo";
                  }    

                  echo "<tr><td align='center'>".$contador."</td>  
                            <td align='left'>  ".$detalle."</td>  
                            <td align='center'>".$cantidad_billetes."</td>     
                            <td align='right'>".number_format($row_numeros['total_neto'],2,'.',',')."</td></tr>";
                  $contador++;      
          }     
                  echo "<tr><td colspan='2' align='center'><label>Total</label></td><td align='center'><label>".number_format($cantidad_acumulado)."</label></td><td align='right'><label>".number_format($neto_acumulado,2,'.',',')."</label></td></tr>";
                  
           $cantidad_acumulado_final=$cantidad_acumulado_final+$cantidad_acumulado;  $neto_acumulado_final=$neto_acumulado_final+$neto_acumulado;
 
           $query_info_revisado_notas=mysqli_query($conn, "SELECT decimos, neto, tipo_documento FROM rp_notas_credito_debito_mayor WHERE sorteo=$sorteo;");

           
           $cantidad_acumulado_notas=0;   $monto_acumulado_notas=0;  
           $cantidad_notas_cc=0;          $monto_notas_cc=0;   
           if (mysqli_num_rows($query_info_revisado_notas)) 
           {   
             $text_nota='Nota de Debito';
             while ($row_notas_aa=mysqli_fetch_array($query_info_revisado_notas))  
            {              
              if ($row_notas_aa['tipo_documento']==1 or $row_notas_aa['tipo_documento']==3  ) 
              {  
                 $monto_notas_cc_2=  $row_notas_aa['neto'] ;  
                 $cantidad_notas_cc_2= $row_notas_aa['decimos'];  
                 $text_nota='Nota de Credito';
              }
              else
              { 
                $cantidad_notas_cc=$row_notas_aa['decimos'];     
                $monto_notas_cc=$row_notas_aa['neto'];  
              }
             
              echo "<tr class='table-danger'><td></td>
                        <td align='center'><label> ".$text_nota."</label></td>
                        <td align='center'><label>".$cantidad_notas_cc."</label></td>
                        <td align='right'><label>".number_format($monto_notas_cc,2,'.',',')."</label></td></tr>";   

                  $cantidad_acumulado_notas= $cantidad_acumulado_notas + $cantidad_notas_cc ;
                  $monto_acumulado_notas= $monto_acumulado_notas + $monto_notas_cc ;
            }        
        } 
        echo "<tr><td></td>
                  <td colspan='4' align='center'><label> -- </label></td></tr>              
              <tr class='table-success'><td></td>
                  <td colspan='1' align='center'><label> Total</label></td>
                  <td align='center'><label>".number_format($cantidad_acumulado_final-$cantidad_acumulado_notas)."</label></td>
                  <td align='right'><label>".number_format($neto_acumulado_final-$monto_acumulado_notas,2,'.',',')."</label></td></tr></table>";

  ?>
                



   
 </section>
 <section id="no_print">
    <div align="center">
    <button class="btn btn-danger btn-lg"  onclick='window.print();' type="button" id="no_print"> <i class="fas fa-print"></i> Imprimir </button>
    <a class="btn btn-success btn-lg text-white"  role="button" href="_EXCEL_mayor_detalle_pagos.php?sorteo=<?php echo $sorteo; ?>" target="blank" id="no_print"> <i class="far fa-file-excel"></i> Exportar detalle a Excel </a>
    </div>
 </section>
</div>
</form>
<script type="text/javascript">
  $(".div_wait").fadeOut("fast");  
</script>

