<?php 
require("../../conexion.php");
$numero=$_GET['numero']; 
$sorteo=$_GET['sorteo']; 
$decimo=$_GET['decimo']; 
$estado_premio_caduco="";
$query_caduco=mysqli_query($conn, "SELECT  (45 - DATEDIFF(CURRENT_DATE, fecha_sorteo)) dias FROM sorteos_mayores where id=$sorteo");
if (mysqli_num_rows($query_caduco)>0) {
  $ob_caduco      = mysqli_fetch_object($query_caduco);
  $dias_caduco     = $ob_caduco->dias;
  if ($dias_caduco>0) {
    $estado_premio_caduco="Premio no fue cobrado";
  }else{
    $estado_premio_caduco="";
  }
}
 
$detalle_venta='';
$html='';
$html_pagado_totalidad='' ;
$html_pagado_parcial='' ;

$query_extraordinario=mysqli_query($conn, "SELECT * FROM reposiciones_especiales_mayor where id_sorteo=$sorteo and billete=$numero  and estado_pago=0;");
 
$query_comprobacion_alerta=mysqli_query($conn,"SELECT a.transactionusername cajero, a.transactionagencyname agencia, a.transactiondate fecha, a.transactionstate FROM mayor_pagos_alertas a WHERE  a.sorteo=$sorteo and a.numero=$numero ");

$query_pagado=mysqli_query($conn,"SELECT b.transactioncode recibo ,  a.decimos, a.transactioncore, b.transactionagencyname agencia , b.transactionusername cajero, b.transactiondate fecha, b.transactionwinnername ganador, a.totalpayment , a.imptopayment , a.netopayment, a.registro FROM mayor_pagos_detalle a, mayor_pagos_recibos b where a.transactioncode=b.transactioncode and a.sorteo=$sorteo and a.numero=$numero and a.transactionstate=1 ;");


if (mysqli_num_rows($query_extraordinario)>0 )
{
  echo '<div class="card">
              <div class="card-header bg-info text-white">                
              <strong>R.E. !</strong>
              </div>
              <div class="card-body">
                  <div class="alert alert-info">
                    <strong>¡Atencion!</strong> El billete numero <strong>'.$numero.'</strong> del sorteo <strong>'.$sorteo.'</strong> se encuentra registrado en las <strong>REPOSICIONES ESPECIALES </strong> <br>
                    Favor solicitar autorizacion a la coordinacion del proyecto de Lotería para realizar el pago 
                  </div> </div></div>';

  
}
else if (mysqli_num_rows($query_comprobacion_alerta)>0) 
{
   while ($_row_alert=mysqli_fetch_array($query_comprobacion_alerta))
   {
      $_cajero=$_row_alert['cajero']; 
      $_nombreagencia=$_row_alert['agencia'];
      $_fechaalerta=$_row_alert['fecha'];
      $_estado_alerta=$_row_alert['transactionstate'];
   }

   if ($_estado_alerta==1) 
   {
    echo "<div class='card'>
         <div class='card-header bg-danger text-white'>El Billete ".$numero." del sorteo ".$sorteo." tiene ALERTA
         </div>
         <div class='card-body' >
         <p class='form-control' style='color:red; font-size:15pt;'> Este billete debe ser remitido al Proyecto de Lotería en casa matríz para su validacion física y autorización de pago por parte del PANI</p>
         </div></div>";
        
   }
   else if ($_estado_alerta==3) 
   {
    echo "<div class='card'>
         <div class='card-header bg-info text-white'>El Billete Número ".$numero." del sorteo ".$sorteo." tiene Premio Mayor o igual a  L. 100,000.00
         </div>
         <div class='card-body' >
         <p class='form-control' style='color:red; font-size:15pt;'> Este billete debe ser remitido al Proyecto de Lotería en casa matríz para su validacion física y autorización de pago por parte del PANI</p>
         </div></div>";
   }

 


}
else if (mysqli_num_rows($query_pagado)>0)
{
  $html_pagado_totalidad .= ' <div class="card">
              <div class="card-header bg-danger text-white">                
              <strong>Billete número '.$numero.' del sorteo No. '.$sorteo.'  ya fué pagado en su totalidad !</strong>
              </div>
              <div class="card-body">';

  $html_pagado_totalidad .= '<div class="table-responsive" >
                              <table class="table table-hover table-sm table-bordered" style="font-size:12px;" >
                                  <thead align="center">
                                      <tr class="table-danger">
                                          <th>Fecha</th>
                                          <th>Recibo</th>
                                          <th>Agencia</th>
                                          <th>Cajero</th>
                                          <th>Ganador</th>
                                          <th>Registro</th>
                                          <th>Decimos</th>                                          
                                          <th>Neto</th>
                                      </tr>
                                  </thead>
                                  <tbody>';                

    $neto_acumulado=0;
    $decimos_acumulado=0;
  
   while ($_row_alert=mysqli_fetch_array($query_pagado))
   {
      $_cajero=$_row_alert['cajero']; 
      $_nombreagencia=$_row_alert['agencia'];
      $_fechaalerta=$_row_alert['fecha'];
      $_recibo=$_row_alert['recibo'];
      $_ganador=$_row_alert['ganador'];
      $_monto_ganador=$_row_alert['totalpayment'];
      $_impto=$_row_alert['imptopayment'];
      $_neto=$_row_alert['netopayment'];
      $_decimos=$_row_alert['decimos'];
      $_registro=$_row_alert['registro'];

      $neto_acumulado=$neto_acumulado+$_neto;
      $decimos_acumulado=$decimos_acumulado+$_decimos;
      $html_pagado_totalidad .='<tr class="table-light">
                                        <td>'.$_fechaalerta.'</td>
                                        <td>'.$_recibo.'</td>
                                        <td>'.$_nombreagencia.'</td>
                                        <td>'.$_cajero.'</td>
                                        <td>'.$_ganador.'</td>
                                        <td>'.$_registro.'</td>
                                        <td align="center">'.$_decimos.'</td>
                                        <td align="right">'.$_neto.'</td>
                                    </tr>';                                  
                                    
   }
   
   $html_pagado_totalidad .= '<tr class="table-success">
                                        <td class="table-active" colspan="6" align="center"> Total Pagado</td>
                                        <td align="center">'.$decimos_acumulado.'</td> 
                                        <td align="right">L.  '.number_format($neto_acumulado,2).'</td> 
                                    </tr>
                                </tbody>
                            </table>
                        </div>';            

      $html .= $html_pagado_totalidad;  
      $html .=  "</div>
    </div>";     

echo $html;
}

else
{  
     
   $query=mysqli_query($conn,"SELECT a.token, a.detalle_venta, a.registro, a.total, a.impto , a.neto, b.desc_premio, tipo_premio, a.estado_especies, a.tipo_pago, a.decimo as decimo_premiado, a.estado
                              FROM  archivo_pagos_mayor a, sorteos_mayores_premios b 
                              WHERE a.sorteo=b.sorteos_mayores_id and a.sorteo =$sorteo and a.numero=b.numero_premiado_mayor  and a.numero=$numero and a.decimo=$decimo ;");
    if ($query === false) {  echo mysqli_error();  }

      if (mysqli_num_rows($query)>0) 
      {      
      $desc_premio='';
        while ($fila = mysqli_fetch_array($query))
        {
         $token            =         $fila['token'];
         $registro         =         $fila['registro'];         
         $detalle_venta    =         $fila['detalle_venta'];
         $monto_total      =         $fila['total'];
         $impto            =         $fila['impto'];
         $neto             =         $fila['neto'];
         $tipo_pago        =         $fila['tipo_pago'];
         $estado           =         $fila['estado'];
         $estado_especies  =         $fila['estado_especies'];
         $decimo_premiado  =         $fila['decimo_premiado'];
         $desc_premio      =         $fila['desc_premio'];
        } 
        $decimos_disponibles=10;
        $decimos_pagados=0;  

        if ($estado_especies==1) 
        {
                       
                                     
         echo "<div class='card'>
         <div class='card-header bg-success text-white' >El billete número <strong>".$numero."</strong> del sorteo <strong>".$sorteo."</strong> con décimo número  <strong>".$decimo_premiado."</strong>  es el ganador de un premio en especies   ".$estado_premio_caduco."</div>
         <div class='card-body'  align='center' name='info_sorteo' id='info_sorteo' style='padding-top:2px;'>
         <input type='hidden' id='_key' name='_key' value='".$token."' >
         <input type='hidden' id='_tipo_premio' name='_tipo_premio' value='".$tipo_pago."' >
         <input type='hidden' id='_detalle_venta' name='_detalle_venta' value='".$detalle_venta."'>
         <input type='hidden' id='_registro' name='_registro' value='".$registro."' >
         <input type='hidden' id='_sorteo' name='_sorteo' value='".$sorteo."'>
         <input type='hidden' id='_numero' name='_numero' value='".$numero."'>
         <input type='hidden'  id='_impto' name='_impto' value='".$impto."'>
         <input type='hidden' id='_monto_total' name='_monto_total' value='".$monto_total."'>
         <input type='hidden' id='_neto' name='_neto' value='".$neto."'>
         <input type='hidden' id='_decimos_disponibles' name='_decimos_disponibles'  value='".$decimo_premiado."'>
         <div class='row'>
         <div class='col-sm-6'>
         <div class='table-responsive' >
            <table class='table table-sm table-hover' >        
             <tbody>
             <tr  style='padding-top:2px;'>
              <td  style='font-size:12pt;'>Vendido en : </td>
              <td  style='font-size:12pt;'>".$detalle_venta."</td>
             </tr>
              <tr  style='padding-top:2px;'>
              <td  style='font-size:12pt;'>Descripcion: </td>
              <td  style='font-size:12pt;'><strong>".$desc_premio."</strong></td>
             </tr>
             <tr>
              <td  style='font-size:12pt;'>Registro: </td>
              <td  style='color:red; font-size:16pt; text-align:left'>".$registro."</td>
             </tr>
             <tr>
              <td  style='font-size:12pt;'>Total </td>
              <td  style='font-size:12pt;' align='right'> L. ".number_format($monto_total,2)."</td>
             </tr>
             <tr>
              <td  style='font-size:12pt;'>Impto </td>
              <td  style='font-size:12pt;' align='right'>L. ".number_format($impto,2)."</td>
             </tr>
             <tr>
              <td>Neto </td>
              <td align='right'>L. ".number_format($neto,2)."</td>
             </tr>
             <tr>
              <td colspan='2' style='color:red; font-size:16pt' >Decimo Premiado : ".$decimo_premiado."  </td> 
             </tr>
             </tbody>
             </table>
          </div>
      </div>
      <div class='col-sm-6'></div>
         </div>
         <div class='row'>
         <div class='col-sm-3'>
            <input type='hidden' id='_decimos' class='form-control' name='_decimos'  style='font-size:12pt; padding-bottom:1px;  padding-top:1px;' value='".$decimo_premiado."' > 
         </div>
         <div class='col-sm-3'>
             <button id='add_recibo' type='button' name='add_recibo'  onclick='agregar_info_recibo()' class=' btn btn-success active btn-sm justify-content-center' style='margin-left:' >ADJUNTAR AL RECIBO<i class='fas fa-list-ol' style='padding-bottom:3px;padding-left:6px;font-size:15px;''></i></button>
         </div>
         <div class='col-sm-6'></div>
         </div>
         
         </div></div> ";
        }          
        else if ($estado_especies==2)
        {


        $query_especie=mysqli_query($conn,"SELECT descripcion_respaldo, decimos FROM sorteos_mayores_premios where sorteos_mayores_id=$sorteo and numero_premiado_mayor=$numero");
        while($row_especie=mysqli_fetch_array($query_especie))
        {
         $descripcion_especie=$row_especie['descripcion_respaldo'];
         $decimos=$row_especie['decimos'];
        }

        echo "<div class='card'>
         <div class='card-header bg-info text-white'>Billete numero ".$numero." del sorteo ".$sorteo." tiene premio en especies</div>
         <div class='card-body' name='info_sorteo1' id='info_sorteo1'>
         <p>El decimo Numero <strong style='color:red; font-size:20pt; text-align:center'>&nbsp; ".$decimos." &nbsp; </strong>  es el ganador de  un(a) :<br>
         <strong style='color:red; font-size:20pt; text-align:center'> ".$descripcion_especie."</strong><br>
         <strong style='color:red; font-size:20pt; text-align:center'> Registro : ".$registro."</strong><br>Remitir el plíego de lotería a Revisión de Premios del PANI para realizar la validación física correspondiente y liberar el pago.</div></div>";
        }

       }
       else
       {            
         echo "<div class='card'>
         <div class='card-header bg-danger text-white'>El billete número ".$numero." del sorteo ".$sorteo." no está premiado</div>
         <div class='card-body' name='info_sorteo1' id='info_sorteo1'>
                  <div class='alert alert-danger'> Este billete no se encuentra en los registros de números premiados del PANI</div>
         </div></div>";
      }
}




 ?>
 <script type="text/javascript">
  $(".div_wait").fadeOut("fast");
 </script>
