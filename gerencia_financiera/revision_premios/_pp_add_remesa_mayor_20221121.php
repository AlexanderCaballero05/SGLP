<?php
require("../../conexion.php"); 
$_remesa=$_GET['remesa'];  $_agency=$_GET['agencia'];   $_fecha_pago=$_GET['fecha_pago'];   $_action=$_GET['acc'];

  if ($_action==1)   
  { 
    $requete = mysqli_query($conn, "UPDATE mayor_pagos_detalle a, mayor_pagos_recibos b set remesa=$_remesa , ano_remesa=year(CURRENT_DATE),  fecha_recepcion_banco=CURRENT_TIMESTAMP  where a.transactioncode=b.transactioncode and date(a.transactiondate) = '$_fecha_pago'  and b.transactionagency='$_agency' and a.transactionstate in (1,3) ");  
    $requete_consulta_agencia= mysqli_query($conn, "SELECT sum(decimos) conteo_ag , sum(a.totalpayment) principal_ag, sum(a.imptopayment) impto_ag, sum(a.netopayment) neto_ag from mayor_pagos_detalle a, mayor_pagos_recibos b where a.transactioncode=b.transactioncode and b.transactionagency=$_agency and date(a.transactiondate)='$_fecha_pago' and a.transactionstate IN (1,3)");    

   if ($requete_consulta_agencia==false) {  echo mysqli_error();  } else { echo "bien la consulta "; }
    while ($row_agencias_info= mysqli_fetch_array($requete_consulta_agencia) ) 
    {
       $cantidad_ag=$row_agencias_info['conteo_ag']; $monto_ag=$row_agencias_info['principal_ag'];  $impto_ag=$row_agencias_info['impto_ag']; $neto_ag=$row_agencias_info['neto_ag'];
       $requete_insert = mysqli_query($conn, "INSERT INTO rp_asignacion_agencias_revisor_mayor(transactionagency, transactiondate, cant_decimos, totalpayment, imptopayment, netopayment, remesa, ano_remesa) 
        VALUES($_agency, '$_fecha_pago', $cantidad_ag, $monto_ag, $impto_ag, $neto_ag, $_remesa,  year(CURRENT_DATE) ) ");  
         if ($requete_insert==false) {  echo mysqli_error();  } else { echo "bien el insert "; }
    }   
  }
  else if ($_action==2)    
  { 
    $requete = mysqli_query($conn, "UPDATE mayor_pagos_detalle a, mayor_pagos_recibos b SET remesa=0, fecha_recepcion_banco=null WHERE a.transactioncode=b.transactioncode and date(a.transactiondate) = '$_fecha_pago'  and b.transactionagency=$_agency and a.transactionstate IN (1,3) ");  
    $requete_insert = mysqli_query($conn, "DELETE FROM rp_asignacion_agencias_revisor_mayor WHERE date(transactiondate)='$_fecha_pago' AND transactionagency=$_agency  ");
  }
  





  if ($requete==true) 
  {    
              $_agencia=$_agency;
              echo "<div class='alert alert-info' align='center'> <label style='font-family: Arial; font-size: 12pt;'>Información de Billetes Pagados en fecha ".$_fecha_pago." con número de remesa No. ".$_remesa.".</label></div>";
                         echo  "<div class='row' style='padding-bottom: 0px;'>                             
                                <div class='col-md-1' align='center'></div>
                                <div class='col-md-10' align='center'><legend></legend>
                                  <div class='table-responsive' style='padding-bottom: 0px; page-break-after: always;'>
                                      <table class='table table-hover table-sm'   id='table' >
                                            <thead><tr><th  align='center'>Agencia</td> 
                                                       <th  align='center'>Cantidad</td>
                                                       <th  align='right'>Principal</td>
                                                       <th  align='right'>Impto</td>                
                                                       <th  align='right'>Neto</th>                    
                                                       <th  id='btn-rev'></th></tr></thead><tbody>";
                                                  
                         $result = mysqli_query($conn, "SELECT  b.transactionagency,  b.transactionagencyname seccional,  sum(a.decimos) conteo, sum(a.totalpayment) total, sum(a.imptopayment) impto,  sum(a.netopayment) neto
                                                FROM mayor_pagos_detalle a, mayor_pagos_recibos b
                                                WHERE  a.transactioncode=b.transactioncode AND 
                                                       a.transactionstate IN (1,3) AND 
                                                       (a.remesa=0 OR a.remesa is NULL)  AND
                                                       date(a.transactiondate) ='$_fecha_pago'
                                                GROUP BY  b.transactionagency  ORDER BY b.transactionagency ASC");

                      if (mysqli_num_rows($result)>0)
                        {
                          $acumulado_neto=0; $contador=0; $acumulado_total=0; $acumulado_impto=0; $contador_billetes=0;
                          while ($row = mysqli_fetch_array($result)) 
                          {  
                             $acumulado_total=$row['total']+$acumulado_total;  $acumulado_impto=$row['impto']+$acumulado_impto;  $acumulado_neto=$row['neto']+$acumulado_neto; $contador_billetes= $contador_billetes+ $row['conteo'];
                            echo "<tr><td align='left'>".$row['transactionagency']."--".$row['seccional']."</td>
                                      <td align='center'>".$row['conteo']."</td>
                                      <td align='right'>".number_format($row['total'],2,'.',',')."</td>
                                      <td align='right'>".number_format($row['impto'],2,'.',',')."</td>
                                      <td align='right'>".number_format($row['neto'],2,'.',',')."</td>
                                      <td align= 'center' id='btn-rev'>
                                        <button type='button' name='option1".$contador."' id='option1".$contador."' onclick='add_remesa(this.value, 1)'  value='".$_remesa."--".$row['transactionagency']."--".$row['seccional']."--".$_fecha_pago."' class='btn btn-sm btn-success'><i class='far fa-thumbs-up fa-lg'></i>  Agregar a la Remesa</button>                                   
                                      </td></tr></tbody>";
                                      $contador ++;     
                          }
                          echo "<tr class='table-info'>
                                        <td align='center'>Total de Billetes</td>
                                        <td align='center'>".$contador_billetes."</td>
                                        <td align='right'> L. ".number_format($acumulado_total,2,'.',',')."</td>
                                        <td align='right'> L. ".number_format($acumulado_impto,2,'.',',')."</td> 
                                        <td align='right'> L. ".number_format($acumulado_neto,2,'.',',')."</td>
                                        <td id='btn-rev'></td></tr>";                       
                        }  
                        else
                        { 
                             echo  "<tr class='table-danger'><td colspan='8' align='center'> No existen pagos pendientes de remesa para esta fecha!<td></tr></tbody>";    
                        }  

 echo "</table></div></div><div class='col-md-1' align='center'></div></div>";
            
                    echo  "<div class='row'>                             
                        <div class='col-md-1' align='center'></div>
                          <div class='col-md-10' align='center'><legend><div class='alert alert-success'>Información Seleccionada para la Remesa No. ".$_remesa." </div></legend> 
                                  <div class='table-responsive' style='padding-bottom: 0px; page-break-after: always;'>
                                      <table class='table table-hover table-sm table-bordered'   id='table'>
                                            <thead><tr><th align='center'>Agencia</th> 
                                                       <th align='center'>Cantidad</th>
                                                       <th align='right'>Principal</th>
                                                       <th align='right'>Impto</th>                
                                                       <th align='right'>Neto</th>                    
                                                       <th id='btn-rev'></th></tr></thead><tbody>";

                            $result_remesados = mysqli_query($conn,"SELECT  b.transactionagency,  b.transactionagencyname seccional,  sum(a.decimos) conteo, sum(a.totalpayment) total, sum(a.imptopayment) impto,  sum(a.netopayment) neto
                                                FROM mayor_pagos_detalle a, mayor_pagos_recibos b 
                                                WHERE  a.transactioncode=b.transactioncode AND 
                                                       a.transactionstate IN (1,3) AND 
                                                      date(a.transactiondate) ='$_fecha_pago' AND
                                                      a.remesa=$_remesa 
                                                GROUP BY  b.transactionagency  ORDER BY  b.transactionagency ASC");

                                if (mysqli_num_rows($result_remesados)>0)
                                  {
                                    $acumulado_neto_remesados=0; $contador_remesados=0; $acumulado_total_remesados=0; $acumulado_impto_remesados=0; $contador_billetes_remesados=0;  
                                    while ($row_remesados = mysqli_fetch_array($result_remesados)) 
                                    {  
                                       $acumulado_total_remesados=$row_remesados['total']+$acumulado_total_remesados;  $acumulado_impto_remesados=$row_remesados['impto']+$acumulado_impto_remesados;  $acumulado_neto_remesados=$row_remesados['neto']+$acumulado_neto_remesados;  $contador_billetes_remesados= $contador_billetes_remesados+ $row_remesados['conteo'];

                                      echo "<tr><td  align='left'>".$row_remesados['transactionagency']." -- ".$row_remesados['seccional']."</td>
                                                <td  align='center'>".$row_remesados['conteo']."</td>
                                                <td  align='right'>".number_format($row_remesados['total'],2,'.',',')."</td>
                                                <td  align='right'>".number_format($row_remesados['impto'],2,'.',',')."</td>
                                                <td  align='right'>".number_format($row_remesados['neto'],2,'.',',')."</td>
                                                <td  align='center'  id='btn-rev'>  
                                                <button type='button' name='option1".$contador_remesados."' id='option1".$contador_remesados."' onclick='add_remesa(this.value, 2)'  value='".$_remesa."--".$row_remesados['transactionagency']."--".$row_remesados['seccional']."--".$_fecha_pago."' class='btn btn-sm btn-danger'><i class='far fa-thumbs-down fa-lg'></i>  Quitar a la Remesa</button>       
                                                </td></tr></tbody>";
                                                $contador_remesados ++;    
                                     }                                      
                                      echo "<tr class='table-success' ><td  align='center'>Total de Billetes Agregados a la Remesa</td>
                                                <td align='center'>".$contador_billetes_remesados."</td>
                                                <td align='right'> L. ".number_format($acumulado_total_remesados,2,'.',',')."</td><td align='right'> L. ".number_format($acumulado_impto_remesados,2,'.',',')."</td> 
                                                <td align='right'> L. ".number_format($acumulado_neto_remesados,2,'.',',')."</td><td id='btn-rev'></td></tr></table>";  
 
                                  }  
                                  else
                                  {     
                                    
                                      echo  "<tr class='table-success'><td colspan='8' align='center'>No se han ingresado valores a la remesa<td></tr></tbody>";    
                                   } 

                         
  }
  else 
  { echo "<div class='alert alert-danger'><label>".mysqli_error()." en el update</label></div>";  }       
   

?>
 <script type="text/javascript">
   $(".div_wait").fadeOut("fast");
 </script>
