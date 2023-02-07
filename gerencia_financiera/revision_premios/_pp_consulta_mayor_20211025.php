<?php 
require("../../conexion.php");
$numero=$_GET['numero']; 
$sorteo=$_GET['sorteo']; 

 
$detalle_venta='';
$html='';
$html_pagado_totalidad='' ;
$html_pagado_parcial='' ;

$query_extraordinario=mysqli_query($conn, "SELECT * FROM reposiciones_especiales_mayor where id_sorteo=$sorteo and billete=$numero  and estado_pago=0");
 
$query_comprobacion_alerta=mysqli_query($conn, "SELECT a.transactionusername cajero, a.transactionagencyname agencia, a.transactiondate fecha, a.transactionstate FROM mayor_pagos_alertas a WHERE  a.sorteo=$sorteo and a.numero=$numero  ");

$query_pagado=mysqli_query($conn, "SELECT b.transactioncode recibo ,  a.decimos, a.transactioncore, b.transactionagencyname agencia , b.transactionusername cajero, b.transactiondate fecha, b.transactionwinnername ganador, a.totalpayment , a.imptopayment , a.netopayment, a.registro,  estado_revision, usuario_revision, usuario_revision_name, fecha_revision , tipo_premio FROM mayor_pagos_detalle a, mayor_pagos_recibos b where a.transactioncode=b.transactioncode and a.sorteo=$sorteo and a.numero=$numero and a.transactionstate=1 ;");


if (mysqli_num_rows($query_extraordinario)>0 )
{
	echo '<div class="card">
	            <div class="card-header bg-info text-white">                
	            <strong>R.E. !</strong>
	            </div>
	            <div class="card-body">
	                <div class="alert alert-info">
	                  <strong>¡Atencion!</strong> El billete numero <strong>'.$numero.'</strong> del sorteo <strong>'.$sorteo.'</strong> se encuentra registrado en las <strong>REPOSICIONES ESPECIALES </strong> 
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

   if ($_estado_alerta==0) 
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
      $_estado_revision=$_row_alert['estado_revision'];
      $_usuario_revision=$_row_alert['usuario_revision_name'];
      $_fecha_revision=$_row_alert['fecha_revision'];
      $tipo_premio=$_row_alert['tipo_premio'];

   

      $neto_acumulado=$neto_acumulado+$_neto;
      $decimos_acumulado=$decimos_acumulado+$_decimos;

          if ($tipo_premio=='E') 
      {
         $query_decimo_premio_pagado=mysqli_query($conn, "SELECT decimo decimo_ganador FROM archivo_pagos_mayor WHERE sorteo=$sorteo AND numero=$numero");
        while ($row_decimo_especies = mysqli_fetch_array($query_decimo_premio_pagado)) 
        {
           $_decimos=$row_decimo_especies['decimo_ganador'];
        }
      }
      $html_pagado_totalidad .='<tr class="table-light">
                                        <td>'.$_fechaalerta.'</td>
                                        <td>'.$_recibo.'</td>
                                        <td>'.$_nombreagencia.'</td>
                                        <td>'.$_cajero.'</td>
                                        <td>'.$_ganador.' -- '.$_estado_revision.'</td>
                                        <td align="center" class="text-danger" >'.$_registro.'</td>
                                        <td align="center">'.$_decimos.'</td>
                                        <td align="right">'.$_neto.'</td>
                                </tr>';                                  
                                    
   }

 
              $html_pagado_totalidad .= '<tr class="table-success">
                                        <td class="table-active" colspan="6" align="center"> Total Pagado</td>
                                        <td align="center">'.$decimos_acumulado.'</td> 
                                        <td align="right">L.  '.number_format($neto_acumulado,2).'</td> 
                                    </tr>';
  
   

    if ($_estado_revision > 0 or !empty($_estado_revision) ) {
        $html_pagado_totalidad .= '<tr class="table-secondary">
                                        <td class="table-active" colspan="5" align="center">Revisado</td>
                                        <td align="center">'.$_usuario_revision.'</td> 
                                        <td align="right">Fecha de revision :  '.$_fecha_revision.'</td> 
                                    </tr>';
    }
    else
    {
          $html_pagado_totalidad .= '<tr class="table-secondary">
                                        <td class="table-active" colspan="8" align="center">Este Billete No ha sido Revisado</td> 
                                    </tr>';
    }

                         echo "</tbody>
                            </table>
                        </div>";
            

    if ($decimos_acumulado<10)       	
    {
    	$decimos_disponibles= 10-$decimos_acumulado;
    	$decimos_pagados=$decimos_acumulado;

    	$query=mysqli_query($conn, " SELECT  token, detalle_venta, registro, total, impto, neto, tipo_pago  FROM archivo_pagos_mayor WHERE sorteo=$sorteo and numero=$numero");
          if ($query === false) {  echo mysql_error();  }

            if (mysqli_num_rows($query)>0) 
            {        
              while ($fila = mysqli_fetch_array($query))
              {
               $token=$fila['token'];
               $registro=$fila['registro'];         
               $detalle_venta=$fila['detalle_venta'];
               $monto_total=$fila['total'];
               $impto=$fila['impto'];
               $neto=$fila['neto'];
               $tipo_pago=$fila['tipo_pago'];
              } 
            }

              $monto_total_disponible=($monto_total/10)*$decimos_disponibles;
            $impto_disponible=($impto/10)*$decimos_disponibles;
            $neto_disponible=($neto/10)*$decimos_disponibles;
            $html_pagado_parcial .= ' <div class="card">
              <div class="card-header bg-primary text-white">                
              <strong>El billete '.$numero.' del sorteo '.$sorteo.' tiene decimos pendientes de pago!</strong>
              </div>
              <div class="card-body">'; 

            $query_historico_numero=mysqli_query($conn ,"SELECT a.transactiondate, a.transactioncode, b.transactionagencyname, b.transactionusername, a.decimos, a.totalpayment total, a.imptopayment impto, a.netopayment neto, a.estado_revision , a.usuario_revision_name, a.fecha_revision FROM mayor_pagos_detalle a, mayor_pagos_recibos b WHERE a.transactioncode=b.transactioncode and a.sorteo=$sorteo and a.numero=$numero and a.transactionstate=1 ");
            if (mysqli_num_rows($query_historico_numero)>0) 
            {
              $html_pagado_parcial .='<div class="table-responsive">  
                  <table class="table table-hover table-sm table-bordered">                             
                        <thead align="center">
                          <tr class="table-secondary">
                            <th colspan="10">DECIMOS PAGADOS</th> 
                            </tr>
                            <tr class="table-secondary">
                            <th>Fecha de pago</th>
                            <th>Factura</th>
                            <th>Agencia</th>
                            <th>Cajero</th>
                            <th>Decimos</th>
                            <th>Total</th>
                            <th>Impto</th>
                            <th>Neto</th>
                            <th>Revisado</th>
                            <th>Fec. Revision</th>
                            </tr>
                        </thead>
                        <tbody>';

                 $acumulado_decimos_historico=0;
                 $acumulado_total_historico=0;
                 $acumulado_impto_historico=0;
                 $acumulado_neto_historico=0;

              while ($row_historico_pagado=mysqli_fetch_array($query_historico_numero)) 
              {
                 $fecha_historico                   = $row_historico_pagado['transactiondate'];
                 $factura_historico                 = $row_historico_pagado['transactioncode'];
                 $agencia_historico                 = $row_historico_pagado['transactionagencyname'];
                 $cajero_historico                  = $row_historico_pagado['transactionusername'];
                 $decimos_historico                 = $row_historico_pagado['decimos'];
                 $total_historico                   = $row_historico_pagado['total'];
                 $impto_historico                   = $row_historico_pagado['impto'];
                 $neto_historico                    = $row_historico_pagado['neto'];
                 $estado_revision_historico         = $row_historico_pagado['estado_revision'];
                 $usuario_revision_name_historico   = $row_historico_pagado['usuario_revision_name'];
                 $fecha_revision_historico          = $row_historico_pagado['fecha_revision'];

                 if ($estado_revision_historico === NULL ) 
                 {
                   $usuario_revision_name_historico   = "";
                    $fecha_revision_historico         = "";
                 }
               

                 $acumulado_decimos_historico=$acumulado_decimos_historico+$decimos_historico;
                 $acumulado_total_historico=$acumulado_total_historico+$total_historico;
                 $acumulado_impto_historico=$acumulado_impto_historico+$impto_historico;
                 $acumulado_neto_historico=$acumulado_neto_historico+$neto_historico;

                 $html_pagado_parcial .="<tr><td>".$fecha_historico."</td>
                                             <td>".$factura_historico."</td>
                                             <td>".$agencia_historico."</td>
                                             <td>".$cajero_historico."</td>
                                             <td>".$decimos_historico."</td>
                                             <td>".number_format($total_historico,2)."</td>
                                             <td>".number_format($impto_historico,2)."</td>
                                             <td>".number_format($neto_historico,2)."</td>
                                             <td>".$usuario_revision_name_historico."</td>
                                             <td>".$fecha_revision_historico."</td>
                                         </tr>";
              }
              $html_pagado_parcial .= "<tr><td colspan='4'> Total pagado</td>
                                           <td>".$acumulado_decimos_historico."</td>
                                           <td>".number_format($acumulado_total_historico,2)."</td>
                                           <td>".number_format($acumulado_impto_historico,2)."</td>                                          
                                           <td>".number_format($acumulado_neto_historico,2)."</td>  
                                           <td colspan='2'></td> 
                                       </tr>";
              $html_pagado_parcial .= "</tbody></table></div>";
            }

          
          $html_pagado_parcial .= "<input type='hidden' id='_key' name='_key' value='".$token."' >
          		<input type='hidden' id='_tipo_premio' name='_tipo_premio' value='".$tipo_pago."' >
          		<input type='hidden' id='_sorteo' name='_sorteo' value='".$sorteo."' >
          		<input type='hidden' id='_numero' name='_numero' value='".$numero."' >
          		<input type='hidden' id='_detalle_venta' name='_detalle_venta' value='".$detalle_venta."'>
          		<input type='hidden' id='_registro' name='_registro' value='".$registro."' >
          		<input type='hidden' id='_monto_total' name='_monto_total' value='".$monto_total."'>
          		<input type='hidden' id='_impto' name='_impto' value='".$impto."'>
          		<input type='hidden' id='_decimos_disponibles' name='_decimos_disponibles' value='".$decimos_disponibles."'>
          		<input type='hidden' id='_neto' name='_neto' value='".$neto."' >";

              if ($tipo_premio<>'E') 
              {
                     $html_pagado_parcial .=   '<div class="table-responsive"> 
                                <table class="table table-hover table-sm table-bordered" style="font-size:16px;" >                             
                                    <thead align="center">
                                        <tr class="table-primary">
                                        <th colspan="5">DISPONIBLES PARA PAGO</th> 
                                        </tr>
                                        <tr class="table-primary">
                                        <th>Registro</th>
                                        <th>Decimos Disponibles</th>
                                        <th>Total</th>
                                        <th>Impto</th>
                                        <th>Neto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="table-light">
                                            <td align="center" class="text-danger" >'.$registro.'</td>
                                            <td align="center">'.$decimos_disponibles.'</td>                                
                                            <td align="right">'.number_format($monto_total_disponible,2).'</td>
                                            <td align="right">'.number_format($impto_disponible,2).'</td>
                                            <td align="right">'.number_format($neto_disponible,2).'</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>';
               }


				$html .= $html_pagado_parcial;
    	
    }
    else
    {
    	$html .= $html_pagado_totalidad;
    }

      $html .=  "</div>
    </div>";     

echo $html;

 
}

else
{  
     
   $query=mysqli_query($conn," SELECT  token, detalle_venta, registro, total, impto, neto, tipo_pago, estado, estado_especies FROM archivo_pagos_mayor WHERE sorteo=$sorteo and numero=$numero");
    if ($query === false) {  echo mysqli_error();  }

      if (mysqli_num_rows($query)>0) 
      {     


        while ($fila = mysqli_fetch_array($query))
        {
         $token=$fila['token'];
         $registro=$fila['registro'];         
         $detalle_venta=$fila['detalle_venta'];
         $monto_total=$fila['total'];
         $impto=$fila['impto'];
         $neto=$fila['neto'];
         $tipo_pago=$fila['tipo_pago'];
         $estado=$fila['estado'];
         $estado_especies=$fila['estado_especies'];
        } 
        $decimos_disponibles=10;
        $decimos_pagados=0;  

        if ($estado==1 or $estado==3) 
        {
                       
                                   
         echo "<div class='card'>
         <div class='card-header bg-success text-white' >El billete número ".$numero." del sorteo ".$sorteo." está premiado</div>
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
         <input type='hidden' id='_decimos_disponibles' name='_decimos_disponibles'  value='".$decimos_disponibles."'>
         <div class='row'>
         <div class='col-sm-6'>
         <div class='table-responsive' >
         		<table class='table table-sm table-hover' >        
		         <tbody>
		         <tr  style='padding-top:2px;'>
		          <td  style='font-size:12pt;'>Vendido en : </td>
		          <td  style='font-size:12pt;'>".$detalle_venta."</td>
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
		          <td colspan='2' style='color:red; font-size:16pt' >Decimos Disponibles : ".$decimos_disponibles."  </td> 
		         </tr>
		         </tbody>
		         </table>
		      </div>
		  </div>
		  <div class='col-sm-6'></div>
         </div>
         
         </div></div> ";  
        } 
        else if ($estado==4) 
        {

       echo "<div class='card'>
         <div class='card-header bg-info text-white'>El Billete numero ".$numero." del sorteo ".$sorteo." tiene Premio Mayor o igual a  L. 100,000.00
         </div>
         <div class='card-body' >
         <p class='form-control' style='color:red; font-size:15pt;'> Este billete debe ser remitido al Proyecto de Lotería en casa matríz para su validacion física y autorización de pago por parte del PANI</p>
         </div></div>";

        }
        else if ($estado==9 and $estado_especies==2)
        {


        $query_especie=mysqli_query($conn,"SELECT descripcion_respaldo, desc_premio, decimos FROM sorteos_mayores_premios where sorteos_mayores_id=$sorteo and numero_premiado_mayor=$numero");
        $decimos=0; 
        while($row_especie=mysqli_fetch_array($query_especie))
        {
         $descripcion_especie=$row_especie['desc_premio'];
         $decimos=$row_especie['decimos'];
        }

        echo "<div class='card'>
         <div class='card-header bg-info text-white'>Billete numero ".$numero." del sorteo ".$sorteo." tiene premio en especies</div>
         <div class='card-body' name='info_sorteo1' id='info_sorteo1'>
         <p>El decimo Numero <strong style='color:red; font-size:20pt; text-align:center'>&nbsp; ".$decimos." &nbsp; </strong>  es el ganador de  un(a) :<br>
         <strong style='color:red; font-size:20pt; text-align:center'> ".$descripcion_especie."</strong><br>
         <strong style='color:red; font-size:20pt; text-align:center'> Registro : ".$registro."</strong><br>
              Indicar al cliente que se avoque a las oficinas del PANI a realizar la validación correspondiente y reclamar el premio.</div></div>";
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