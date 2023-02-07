<?php 
require('../../template/header.php'); 

$_remesa=$_GET['remesa']; $_revisor=$_GET['revisor']; 
  
      $query_revisor_name=mysqli_query($conn, "SELECT nombre_completo from pani_usuarios where id=$_revisor");
      $ob_nombre = mysqli_fetch_object($query_revisor_name);
      $_revisor_name = $ob_nombre->nombre_completo;

       echo'<div class="font-style" style="text-align:center; font-size: 19;" id="Imprime">
                       Patronato Nacional de la Infancia<br>
                       Departamento de Revisión de Premios<br>
                       Liquidacion de Remesa No. '.$_remesa.' de Loteria Menor <br> Revisor '.$_revisor_name.'
            </div><br><br>';

      $query_sorteos=mysqli_query($conn, "SELECT  sorteo FROM menor_pagos_detalle  where estado_revision=1 and remesa=$_remesa  and usuario_revision=$_revisor  and ano_remesa in ('2021', '2022')  and date(transactiondate) >= '2021-11-31'  group by  sorteo;");
      if ($query_sorteos===false) {echo mysqli_error(); }  


        echo "<table width='95%'  id='tableinfo' align='center' class='table table-bordered table-sm'>         
          <thead>
          <tr>
             <td align='center'>No.</td>
             <td>Descripción de Billetes Pagados</td>  
             <td>Cantidad</td>  
             <td>Total</td> 
          </tr></thead><tbody>";   
      $cantidad_acumulado_final=0;  
      $neto_acumulado_final=0; 
      while ($row_sorteo=mysqli_fetch_array($query_sorteos)) 
      {             
          $sorteo=$row_sorteo['sorteo'];
          unset($array_numeros);          unset($array_series);
          $query = mysqli_query($conn, "SELECT numero_premiado_menor FROM sorteos_menores_premios where sorteos_menores_id=$sorteo and premios_menores_id in(1,3);");

          if ($query==false) { echo mysqli_error(); }

          while($row=mysqli_fetch_array($query))  {   $array_numeros[] = $row['numero_premiado_menor'];   }
   
          $query_series = mysqli_query($conn, "SELECT numero_premiado_menor FROM sorteos_menores_premios where sorteos_menores_id=$sorteo and (premios_menores_id =2 or premios_menores_id >3);");

          if ($query_series==false) { echo mysqli_error(); }

              while($row_series=mysqli_fetch_array($query_series)) 
              { $array_series[] = $row_series['numero_premiado_menor']; }

          echo "<tr class='info'><td colspan='4' align='center'>Sorteo : ".$sorteo." </td></tr>";   

          $query_numeros=mysqli_query($conn, "(SELECT a.numero numero, a.neto valor, COUNT(*) cantidad, SUM(a.neto) total_neto, 1 as vale, 'a' as 'orden'
                                    FROM menor_pagos_detalle a, menor_pagos_recibos b 
                                    WHERE 
                                    a.transactioncode=b.transactioncode and 
                                    a.remesa=$_remesa and
                                    a.usuario_revision=$_revisor and
                                    a.transactionstate in (1,3) and
                                    a.estado_revision in (1,3)  and
                                    a.sorteo=$sorteo and
                                    a.ano_remesa in ('2021', '2022') and
                                    a.serie not in( ".implode(',',$array_series)." )  and
                                    a.numero in( ".implode(',',$array_numeros)." )
                                    GROUP BY a.numero 
                                    )UNION
                                    (SELECT a.serie serie,  a.neto valor, COUNT(a.serie) cantidad, SUM(a.neto) total_neto, 2 as vale, 'b' as 'orden' 
                                    FROM menor_pagos_detalle a, menor_pagos_recibos b 
                                    WHERE 
                                    a.transactioncode=b.transactioncode and
                                    a.transactionstate in (1,3) and    
                                    a.estado_revision in (1,3)  and
                                    a.remesa=$_remesa and
                                    a.ano_remesa in ('2021', '2022') and
                                    a.usuario_revision=$_revisor and
                                    a.sorteo=$sorteo and
                                    a.serie in( ".implode(',',$array_series)." )  
                                    GROUP BY a.serie , valor  ) order by orden , valor desc  ;");

              if ($query_numeros==false){ echo mysqli_error(); }
                $total_acumulado=0;  $impto_acumulado=0; $neto_acumulado=0; $cantidad_acumulado=0; $contador=1;               
                while ($row_numeros=mysqli_fetch_array($query_numeros)) 
                {         

                         $cantidad_acumulado=$cantidad_acumulado+$row_numeros['cantidad'];
                         $neto_acumulado=$neto_acumulado+$row_numeros['total_neto'];

                         if (  in_array( $row_numeros['numero'],  $array_numeros)  ) 
                        {
                          $palabra='Numero';
                        }
                        else
                        {
                          $palabra='Serie';
                        }

                        if ($row_numeros['vale']=='1') { $palabra='Numero'; } else {  $palabra='Serie'; }  

                      echo "<tr><td align='center'>".$contador."</td>  
                                <td align='center'>  ".$palabra."   ".$row_numeros['numero']." por L. ".$row_numeros['valor']."</td>  
                                <td align='center'>".$row_numeros['cantidad']."</td>    
                                <td align='right'>".number_format($row_numeros['total_neto'],2,'.',',')."</td></tr>";
                          $contador++;      
                }

                echo "<tr class='font-style'>";
                echo " <td class=' font-style' colspan='2' align='center'><label>Total del sorteo  ".$sorteo."</label></td>
                        <td class=' font-style' align='center'><label>".number_format($cantidad_acumulado)."</label></td>
                        <td class=' font-style' align='right'><label>".number_format($neto_acumulado,2,'.',',')."</label></td>
                      </tr>";  
          $cantidad_acumulado_final=$cantidad_acumulado_final+$cantidad_acumulado;  $neto_acumulado_final=$neto_acumulado_final+$neto_acumulado; 

      }

echo "<tr><td colspan='4' align='center'><label> -- </label></td></tr>
      <tr><td colspan='4' align='center'><label> Liquidación Total de la Remesa</label></td></tr>
                <tr><td colspan='2' align='center'><label>Gran Total</label></td>
                        <td align='center'><label>".number_format($cantidad_acumulado_final)."</label></td>
                        <td align='right'><label>".number_format($neto_acumulado_final,2,'.',',')."</label></td>
                      </tr></table><br>";
                     
    ////////////////



      echo"<br><br><div align='center'>____________________________<br> Firma y Sello Obligatorio <br> ".$_revisor_name." </div>";
    
    echo "</div></body>";

 

?>
<script type="text/javascript">
    window.print(); 
    setTimeout(window.close, 1000);
     // window.close(); 
      
</script>
 
