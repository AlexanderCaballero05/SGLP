<?php 
require("../../conexion.php");
  ob_start();
  session_start();
  setlocale(LC_MONETARY, 'es_HN');

 
  $sorteo  =$_GET['sorteo']; 
  $numero  =$_GET['numero']; 
  $serie   =$_GET['serie']; 
 
  $detalle_venta='';
  $html='';
  $html_pagado_totalidad='' ;

  $tipo_serie_numero='';
  $tipo_serie_serie=''; 
  $detalle_venta='';
  $_SESSION['flag_valida']=1;


$query_extraordinario=mysqli_query($conn, "SELECT * FROM reposiciones_especiales_menor where id_sorteo=$sorteo and numero=$numero and serie=$serie and estado_pago=0");
 
$query_comprobacion_alerta=mysqli_query($conn,"SELECT a.transactionusername cajero, a.transactionagencyname agencia, a.transactiondate fecha, a.transactionstate FROM menor_pagos_alertas a WHERE  a.sorteo=$sorteo and a.numero=$numero and serie=$serie");

$query_pagado=mysqli_query($conn,"SELECT b.transactioncode recibo , a.transactioncore, b.transactionagencyname agencia , b.transactionusername cajero, b.transactiondate fecha, b.transactionwinnername ganador, a.principal totalpayment , a.impto imptopayment , a.neto netopayment, a.registro, a.estado_revision, a.usuario_revision_name, a.fecha_revision FROM menor_pagos_detalle a, menor_pagos_recibos b where a.transactioncode=b.transactioncode and a.sorteo=$sorteo and a.numero=$numero and serie=$serie and a.transactionstate=1 ;");


if (mysqli_num_rows($query_extraordinario)>0 )
{
	echo '<div class="card">
	       <div class="card-header bg-info text-white"><strong>R.E. !</strong></div>
	         <div class="card-body">
	            <div class="alert alert-info">
	            <strong>¡Atencion!</strong> El billete numero <strong>'.$numero.'</strong>  con serie <strong>'.$serie.'</strong>  del sorteo <strong>'.$sorteo.'</strong> se encuentra registrado en las <strong>REPOSICIONES ESPECIALES </strong><br>
	                   
	         </div></div></div>';  
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

      
       	echo "<div class='card'>
             <div class='card-header bg-danger text-white'>El Número <strong>".$numero."</strong> con serie <strong>".$serie."</strong> del sorteo <strong>".$sorteo."</strong> tiene ALERTA
             </div>
             <div class='card-body' >
             <p class='form-control' style='color:red; font-size:15pt;'> Este billete debe ser remitido al PANI para su validacion física y autorización de pago</p></div></div>";            
      
      
}
else if (mysqli_num_rows($query_pagado)>0)
{
	$html_pagado_totalidad .= ' <div class="card">
	            <div class="card-header bg-danger text-white">                
	            Billete Número <strong>'.$numero.'</strong> con serie <strong>'.$serie.'</strong> del sorteo No. <strong>'.$sorteo.'</strong>  ya fue pagado en su totalidad !</strong></div><div class="card-body">';

	$html_pagado_totalidad .= '<div class="table-responsive" >
	                            <table class="table table-hover table-sm table-bordered" style="font-size:12px;" >
	                                <thead align="center">
	                                    <tr class="table-danger">
	                                        <th>Fecha</th>
	                                        <th>Recibo</th>
                                          <th>Registro</th>
	                                        <th>Agencia</th>
	                                        <th>Cajero</th>
	                                        <th>Ganador</th>
	                                        <th>Neto</th>
                                          <th>Revisado</th>
                                          <th>Fecha Revision</th>
	                                    </tr></thead><tbody>';
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
      $_registro=$_row_alert['registro'];
      $_estado_revision=$_row_alert['estado_revision'];
      $_usuario_revision_name=$_row_alert['usuario_revision_name']; 
      $_fecha_revision=$_row_alert['fecha_revision']; 


      $neto_acumulado=$neto_acumulado+$_neto; 
      $html_pagado_totalidad .='<tr class="table-light">
                                        <td>'.$_fechaalerta.'</td>
                                        <td>'.$_recibo.'</td>
                                        <td class="text-danger">'.$_registro.'</td>
                                        <td>'.$_nombreagencia.'</td>
                                        <td>'.$_cajero.'</td>
                                        <td>'.$_ganador.'</td>
                                        <td align="right">'.number_format($_neto,2).'</td>
                                        <td>'.$_usuario_revision_name.'</td>
                                        <td>'.$_fecha_revision.'</td>
                                        </tr>';    
   }
   
   $html_pagado_totalidad .= '<tr class="table-success">
                                        <td class="table-active" colspan="6" align="center"> Total Pagado</td>
                                        <td align="right">'.number_format($neto_acumulado,2).'</td> 
                                        <td colspan="2    "></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div></div></div>';
    	$html .= $html_pagado_totalidad;   
      echo $html;
}

else
{  
     
   $query=mysqli_query($conn,"SELECT token,   detalle_venta, registro, totalpayment as total, imptopayment as impto, netopayment as neto, tipo_premio as tipo_pago, estado FROM archivo_pagos_menor WHERE  sorteo=$sorteo and numero=$numero and serie= $serie");
    if ($query === false) {  echo mysqli_error();  }
        $tipo_pago="";
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
        } 
        $decimos_disponibles=10;
        $decimos_pagados=0;  

        if ($estado==1) 
        {
 
                       
                                   
         echo "<div class='card'>
         <div class='card-header bg-success text-white' >El Billete numero <strong>".$numero."</strong> con serie <strong>".$serie."</strong> del sorteo <strong>".$sorteo."</strong> tiene premio.</div>
         <div class='card-body'  align='center' name='info_sorteo' id='info_sorteo' style='padding-top:2px;'>
         <input type='hidden' id='_key' name='_key' value='".$sorteo."".$registro."".$serie."".$numero."'> 
         <input type='hidden' id='_tipo_premio' name='_tipo_premio' value='".$tipo_pago."' >
         <input type='hidden' id='_detalle_venta' name='_detalle_venta' value='".$detalle_venta."'>
         <input type='hidden' id='_registro' name='_registro' value='".$registro."' >
         <input type='hidden' id='_sorteo' name='_sorteo' value='".$sorteo."'>
         <input type='hidden' id='_numero' name='_numero' value='".$numero."'>
         <input type='hidden' id='_serie' name='_serie' value='".$serie."'>
         <input type='hidden' id='_impto' name='_impto' value='".$impto."'>
         <input type='hidden' id='_impto' name='_impto' value='".$impto."'>
         <input type='hidden' id='_monto_total' name='_monto_total' value='".$monto_total."'>
         <input type='hidden' id='_neto' name='_neto' value='".$neto."'>
         <div class='row'>
         <div class='col-sm-6'>
         <div class='table-responsive' >
         		<table class='table table-sm table-hover borderless' >        
		         <tbody>
		         <tr  style='padding-top:2px;'>
		          <td style='font-size:12pt;'>Vendido en : </td>
		          <td style='font-size:12pt;'>".$detalle_venta."</td>
		         </tr>
		         <tr>
		          <td style='font-size:12pt;'>Registro: </td>
		          <td style='color:red; font-size:16pt; text-align:left'>".$registro."</td>
		         </tr>
		         <tr>
		          <td style='font-size:12pt;'>Total </td>
		          <td style='font-size:12pt;' align='right'> L. ".number_format($monto_total,2)."</td>
		         </tr>
		         <tr>
		          <td style='font-size:12pt;'>Impto </td>
		          <td style='font-size:12pt;' align='right'>L. ".number_format($impto,2)."</td>
		         </tr>
		         <tr>
		          <td>Neto </td>
		          <td align='right' style='color:red; font-size:16pt; text-align:right'>L. ".number_format($neto,2)."</td>
		         </tr>
		         </tbody>
		         </table>
		      </div>
		  </div>
		  <div class='col-sm-6'></div>
         </div>
         <div class='row'>
         <div class='col-sm-3'>
         		 <!-- button id='add_recibo' type='button' name='add_recibo'  onclick='agregar_info_recibo()' class=' btn btn-success active btn-sm justify-content-center' style='margin-left:' >ADJUNTAR AL RECIBO<i class='fas fa-list-ol' style='padding-bottom:3px;padding-left:6px;font-size:15px;''></i></button -->
         </div>
         <div class='col-sm-6'></div>
         </div>
         </div></div> ";  
        } 
        else if ($estado==3) 
        {

       echo "<div class='card'>
         <div class='card-header bg-info text-white'>El Billete numero <strong>".$numero."</strong> con serie <strong>".$serie."</strong> del sorteo <strong>".$sorteo."</strong> tiene Premio Mayor o igual a  L. 10,000.00
         </div>
         <div class='card-body' >
         <p class='form-control' style='color:red; font-size:15pt;'> Este billete debe ser remitido al Proyecto de Lotería en casa matríz para su validacion física y autorización de pago por parte del PANI</p>
         </div></div>";

        }
       


       }
       else
       {            
         echo "<div class='card'>
         <div class='card-header bg-danger text-white'>El Billete numero <strong>".$numero."</strong> con serie <strong>".$serie."</strong> del sorteo <strong>".$sorteo."</strong> no esta Premiado</div>
         <div class='card-body' name='info_sorteo1' id='info_sorteo1'>
                  <div class='alert alert-danger'> Este billete no se encuentra en los registros de numeros premiados del PANI</div>
         </div></div>";
      }
}




 ?>
 <script type="text/javascript">
 	$(".div_wait").fadeOut("fast");
 </script>
