<?php 
require('../../template/header.php'); 

$_remesa=$_GET['remesa']; $_revisor=$_GET['revisor']; $usuario_id=$_revisor;
$s_year=$_GET['year'];

      $query_revisor_name=mysqli_query($conn, "SELECT nombre_completo from pani_usuarios where id=$_revisor");
      $ob_nombre = mysqli_fetch_object($query_revisor_name);
      $_revisor_name = $ob_nombre->nombre_completo;

       echo'<div class="font-style" style="text-align:center; font-size: 19;" id="Imprime">
                       Patronato Nacional de la Infancia<br>
                       Departamento de Revisión de Premios<br>
                       Liquidacion de Remesa No. '.$_remesa.' de Loteria Mayor <br> Revisor '.$_revisor_name.'
            </div><br><br>';

      $query_sorteos=mysqli_query($conn, "SELECT sorteo FROM mayor_pagos_detalle  WHERE estado_revision=1 AND remesa=$_remesa and ano_remesa = '$s_year' AND usuario_revision=$_revisor  GROUP BY  sorteo;");
      if ($query_sorteos===false) {echo mysqli_error($conn); }  


    
echo "<table class='table table-hover table-bordered table-sm' >         
                <thead><tr><th>No.</th>
                           <th>Descripción de Pliegos Pagados</th>  
                           <th>Cantidad de Decimos</th>  
                           <th>Total</th>                 
                       </tr><thead><tbody>";

      $cantidad_acumulado_final=0;
      $neto_acumulado_final=0;
      while ($row_sorteo=mysqli_fetch_array($query_sorteos)) 
      {             
                        $sorteo=$row_sorteo['sorteo'];          
                        $query_numeros=mysqli_query($conn, "(SELECT a.totalpayment valor_premio , a.numero numero, a.tipo_premio tipo_premio , count(decimos) cantidad, sum(a.totalpayment) total, sum(a.imptopayment) total_impto, sum(a.netopayment) total_neto, sum(decimos) num_dec, sum(decimos) decimos,  (sum(a.totalpayment)/decimos)*10 pago_por_terminacion
                        FROM mayor_pagos_detalle a, mayor_pagos_recibos b
                        WHERE
                        a.transactioncode=b.transactioncode and
                        a.tipo_premio='U' and 
                        a.transactionstate in (1,3)  and
                        a.estado_revision in (1,3) and
                        a.sorteo=$sorteo and
                        a.remesa=$_remesa and                        
                        ano_remesa = $s_year and
                        a.usuario_revision=$_revisor
                        group by a.numero order by  a.totalpayment desc
                        )UNION
                        (SELECT a.totalpayment valor_premio , a.numero numero, a.tipo_premio  tipo_premio, count(decimos) cantidad, sum(a.totalpayment) total, sum(a.imptopayment) total_impto, sum(a.netopayment) total_neto , a.decimos, sum(decimos) num_dec , (a.totalpayment/a.decimos )*10 pago_por_terminacion
                        FROM mayor_pagos_detalle a, mayor_pagos_recibos b
                        WHERE
                        a.transactioncode=b.transactioncode and
                        a.tipo_premio='T' and
                        a.transactionstate in (1,3)  and
                        a.estado_revision in (1,3) and
                        a.sorteo=$sorteo and
                        a.remesa=$_remesa and                         
                        ano_remesa = $s_year and
                        a.usuario_revision=$_revisor
                        group by pago_por_terminacion order by pago_por_terminacion desc ) order by valor_premio desc ;");

    if ($query_numeros==false)  { echo mysqli_error($conn); }       
       $total_acumulado=0;       $impto_acumulado=0;   $neto_acumulado=0;  $cantidad_acumulado=0;  $contador=1;
        while ($row_numeros=mysqli_fetch_array($query_numeros)) 
        {        
           $cantidad_acumulado=$cantidad_acumulado+$row_numeros['decimos'];
           $neto_acumulado=$neto_acumulado+$row_numeros['total_neto'];
           $pago_por_decimo=  number_format($row_numeros['pago_por_terminacion'],2,'.',',');//($row_numeros['total']/$row_numeros['decimos']);

          if (   $row_numeros['tipo_premio']=='U'  ) 
          {
            $numero=$row_numeros['numero'];
            $palabra='Premio de Urna ';  
            $query_pago_termi=mysqli_query($conn, "SELECT numero_premiado_mayor, monto FROM `sorteos_mayores_premios` WHERE numero_premiado_mayor=$numero and sorteos_mayores_id=$sorteo limit 1 ");
            while ($_row_termi=mysqli_fetch_array($query_pago_termi))
            {
              $numero_termi=$_row_termi['numero_premiado_mayor'];
              $total_premio= number_format(($_row_termi['monto']/10),2,'.',',');   //($row_numeros['valor_premio']/ $pago_por_decimo);
            }
          }
          else
          {
            $palabra='Premio por Terminación'; 
            $query_pago_termi=mysqli_query($conn, "SELECT numero_premiado_mayor, monto FROM `sorteos_mayores_premios` WHERE respaldo='terminacion' and monto=$pago_por_decimo and sorteos_mayores_id=$sorteo;");
            while ($_row_termi=mysqli_fetch_array($query_pago_termi))
            {
              $numero_termi=$_row_termi['numero_premiado_mayor'];
              $total_premio= number_format(($_row_termi['monto']/10),2,'.',',');   //($row_numeros['valor_premio']/ $pago_por_decimo);
            }
          }

          echo "<tr>
                <td align='center'>".$contador."</td>  
                <td align='left'>  ".$palabra."   ".$numero_termi." por L. ".$pago_por_decimo." el billete y L.  ".$total_premio." el decimo</td>  
                <td align='center'>".$row_numeros['decimos']."</td>     
                <td align='right'>".number_format($row_numeros['total_neto'],2,'.',',')."</td> 
                </tr>";
          $contador++;      
          }
        echo "<tr class='table-info'><td colspan='2' align='center'>Total Sorteo ".$sorteo."</td>
            <td align='center'><label>".number_format($cantidad_acumulado)."</label></td>
            <td align='right'><label>".number_format($neto_acumulado,2,'.',',')."</label></td></tr>"; 
        

        $cantidad_acumulado_final=$cantidad_acumulado_final+$cantidad_acumulado;
        $neto_acumulado_final=$neto_acumulado_final+$neto_acumulado;
    }
  

 
$query_info_revisado_notas=mysqli_query($conn, "SELECT mpd.fecha_revision fecha, notas.decimos_nota decimos, 
  sum(mpd.netopayment) neto_detalle,   notas.neto_nota   neto
  FROM mayor_pagos_detalle mpd, mayor_pagos_recibos mpr, pani_usuarios paniusers, rp_notas_credito_debito_mayor notas
  WHERE 
  mpd.transactioncode=mpr.transactioncode  and mpd.transactioncode = notas.transactioncode and notas.sorteo=mpd.sorteo and notas.numero=mpd.numero and notas.ano_remesa = '$s_year'  and
  notas.remesa=mpd.remesa and  mpd.transactionstate in(1,3) and   mpd.estado_revision in (1,2) and
  mpd.remesa=$_remesa and mpd.usuario_revision=$usuario_id  and  mpd.usuario_revision=paniusers.id and mpd.usuario_revision=notas.usuario group by mpr.transactionagencyname, mpr.transactionusername, date(mpd.transactiondate)  order by fecha asc;");

  if (mysqli_num_rows($query_info_revisado_notas)>0) 
  {
     $sumado_notas=0;	
     $suma_cantidad_notas = 0;
     while ($row_notas_aa=mysqli_fetch_array($query_info_revisado_notas))  
     {
        $cantidad_notas_cc=$row_notas_aa['decimos'];     $monto_notas_cc=$row_notas_aa['neto'];  
        echo "<tr class='table-danger'><td colspan='2' align='center'><label>Notas Debito</label></td>
                  <td align='center'><label>".$cantidad_notas_cc."</label></td>
                  <td align='right'><label>".number_format($monto_notas_cc,2,'.',',')."</label></td></tr>";
	
	$sumado_notas = $sumado_notas + $monto_notas_cc;
	$suma_cantidad_notas = $suma_cantidad_notas + $cantidad_notas_cc;
     }
        $cantidad_acumulado_final=$cantidad_acumulado_final-$suma_cantidad_notas;
        $neto_acumulado_final=$neto_acumulado_final-$sumado_notas;
  }
  else  {     $cantidad_notas_cc=0;      $monto_notas_cc=0;  }
 
        echo "<tr><td colspan='4' align='center'><label> -- </label></td></tr>";
        echo " <tr><td colspan='4' align='center'><label> Liquidación Total de la Remesa</label></td></tr>";
        echo "<tr class='table-success'><td colspan='2' align='center'><label>Gran Total</label></td>
                  <td align='center'><label>".number_format($cantidad_acumulado_final)."</label></td>
                  <td align='right'><label>".number_format($neto_acumulado_final,2,'.',',')."</label></td></tr></table>";
   



      echo"<br><br><br><div align='center'>____________________________<br> Firma y Sello Obligatorio <br> ".$_revisor_name." </div>";
    
    echo "</div></body>";

 

?>
<script type="text/javascript">
   window.print(); 
   setTimeout(window.close, 1000);
     // window.close(); 
      
</script>
 
