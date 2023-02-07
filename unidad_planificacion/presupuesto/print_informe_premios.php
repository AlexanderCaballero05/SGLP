<?php 

require('../../template/header.php');


$fecha_inicial = $_GET['f_i'];
$fecha_final = $_GET['f_f'];


date_default_timezone_set('America/Tegucigalpa');



$wsdl="http://192.168.15.248/_wsSvrBD/_GetDailyPaymentMenor.php?wsdl";
$cliente = new nusoap_client($wsdl,true);
$cliente->soap_defencoding = 'utf-8';//default is 
// $cliente->response_timeout = 200;//seconds
$cliente->useHTTPPersistentConnection();

$err = $cliente->getError();
if ($err)
{
echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
exit();
}

////  CAMBIAR POR EL GET/POST DE LOS PARAMETROS


$resultado = $cliente-> call("GetDailyPaymentMayor", array("fecha_inicial" => $fecha_inicial, "fecha_final" => $fecha_final ));


$err = $cliente->getError();
if ($err) 
{
echo '<h2>Constructor error</h2><pre>' . $err . '</pre><h2>Debug</h2><pre>' . htmlspecialchars($cliente->getDebug(), ENT_QUOTES) . '</pre>';
}
else 
{
$data=explode("$", $resultado);
$cantidad_mayor = $data[0];
$total_mayor 	= $data[1];
$impto_mayor 	= $data[2];
$neto_mayor 	= $data[3];
}



/*
$total_comision_mayor   = 0;
$total_aportacion_mayor = 0;
$total_descuento_mayor  = 0;

$consulta_ventas_fvp = mysqli_query($conn,"SELECT SUM(comision_bancaria) as total_comision , SUM(descuento) as total_descuento, SUM(aportacion) as total_aportacion FROM transaccional_ventas WHERE estado_venta = 'APROBADO' AND DATE(fecha_venta) BETWEEN '$fecha_inicial' AND '$fecha_final' AND cod_producto = 1 ");

$ob_consulta_ventas_fvp = mysqli_fetch_object($consulta_ventas_fvp);
$comision_fvp_mayor   = $ob_consulta_ventas_fvp->total_comision;
$aportacion_fvp_mayor = $ob_consulta_ventas_fvp->total_aportacion;
$descuento_fvp_mayor  = $ob_consulta_ventas_fvp->total_descuento;


$consulta_ventas_banco = mysqli_query($conn,"SELECT SUM(comision_bancaria) as total_comision , SUM(descuento) as total_descuento, SUM(aportacion) as total_aportacion FROM transaccional_ventas_general WHERE estado_venta = 'APROBADO' AND DATE(fecha_venta) BETWEEN '$fecha_inicial' AND '$fecha_final' AND cod_producto = 1 ");

$ob_consulta_ventas_banco = mysqli_fetch_object($consulta_ventas_banco);
$comision_banco_mayor   = $ob_consulta_ventas_banco->total_comision;
$aportacion_banco_mayor = $ob_consulta_ventas_banco->total_aportacion;
$descuento_banco_mayor  = $ob_consulta_ventas_banco->total_descuento;

$total_comision_mayor   = $comision_fvp_mayor 	+ $comision_banco_mayor;
$total_aportacion_mayor = $aportacion_fvp_mayor + $aportacion_banco_mayor;
$total_descuento_mayor  = $descuento_fvp_mayor  + $descuento_banco_mayor;

*/









//////////////////////////// MENOR //////////////////////








$wsdl="http://192.168.15.248/_wsSvrBD/_GetDailyPaymentMenor.php?wsdl";
$cliente = new nusoap_client($wsdl,true);
$cliente->soap_defencoding = 'utf-8';//default is 
// $cliente->response_timeout = 200;//seconds
$cliente->useHTTPPersistentConnection();

$err = $cliente->getError();
if ($err)
{
echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
exit();
}

////  CAMBIAR POR EL GET/POST DE LOS PARAMETROS

$resultado = $cliente-> call("GetDailyPaymentMenor", array("fecha_inicial" => $fecha_inicial, "fecha_final" => $fecha_final ));

$err = $cliente->getError();
if ($err) 
{
echo '<h2>Constructor error</h2><pre>' . $err . '</pre><h2>Debug</h2><pre>' . htmlspecialchars($cliente->getDebug(), ENT_QUOTES) . '</pre>';
}
else 
{
$data=explode("$", $resultado);
$cantidad_menor = $data[0];
$total_menor 	= $data[1];
$impto_menor 	= $data[2];
$neto_menor 	= $data[3];


}




/*

$total_comision_menor   = 0;
$total_aportacion_menor = 0;
$total_descuento_menor  = 0;

$consulta_ventas_fvp = mysqli_query($conn,"SELECT SUM(comision_bancaria) as total_comision , SUM(descuento) as total_descuento, SUM(aportacion) as total_aportacion FROM transaccional_ventas WHERE estado_venta = 'APROBADO' AND DATE(fecha_venta) BETWEEN '$fecha_inicial' AND '$fecha_final' AND cod_producto != 1 ");

$ob_consulta_ventas_fvp = mysqli_fetch_object($consulta_ventas_fvp);
$comision_fvp_menor   = $ob_consulta_ventas_fvp->total_comision;
$aportacion_fvp_menor = $ob_consulta_ventas_fvp->total_aportacion;
$descuento_fvp_menor  = $ob_consulta_ventas_fvp->total_descuento;


$consulta_ventas_banco = mysqli_query($conn,"SELECT SUM(comision_bancaria) as total_comision , SUM(descuento) as total_descuento, SUM(aportacion) as total_aportacion FROM transaccional_ventas_general WHERE estado_venta = 'APROBADO' AND DATE(fecha_venta) BETWEEN '$fecha_inicial' AND '$fecha_final' AND cod_producto != 1 ");

$ob_consulta_ventas_banco = mysqli_fetch_object($consulta_ventas_banco);
$comision_banco_menor   = $ob_consulta_ventas_banco->total_comision;
$aportacion_banco_menor = $ob_consulta_ventas_banco->total_aportacion;
$descuento_banco_menor  = $ob_consulta_ventas_banco->total_descuento;

$total_comision_menor   = $comision_fvp_menor 	+ $comision_banco_menor;
$total_aportacion_menor = $aportacion_fvp_menor + $aportacion_banco_menor;
$total_descuento_menor  = $descuento_fvp_menor  + $descuento_banco_menor;

*/



$tf_total = 0;


echo "<h2 align = 'center'>PATRONATO NACIONAL DE LA INFANCIA</h2>";
echo "<h3 align = 'center'>INFORME DE PAGO DE PREMIOS DE LOTERIA <br> <b> DEL ".$fecha_inicial." AL ".$fecha_final."</b></h3>";

echo "<br><br>";

$date = date('d/m/Y h:i:s a', time()); 
echo "<p align = 'right'>Fecha de emision: <b><u>".$date."</u></b></p>";



echo "<table class = 'table table-bordered'>";
echo "<tr ><th >CONCEPTO</th><th >LOTERIA MAYOR</th><th>LOTERIA MENOR</th><th>TOTAL</th></tr>";

echo "<tr>";
echo "<td>PAGO</td><td>".number_format($total_mayor,2)."</td>";
echo "<td>".number_format($total_menor,2)."</td>";
$total_concepto = $total_mayor + $total_menor; 
echo "<td>".number_format($total_concepto,2)."</td>";
echo "</tr>";


//$tf_total += $total_concepto;

echo "</table>";



/*
echo "<table class = 'table table-bordered'>";
echo "<tr class = 'alert alert-success'><th colspan = '2'>POR CONCEPTO DE PAGO DE PREMIOS</th></tr>";
echo "<tr><td width = '75%'>PAGO DE PREMIOS</td><td width = '25%'>".number_format($total_mayor,2)."</td></tr>";
echo "<tr><td>IMPUESTO</td><td>".number_format($impto_mayor,2)."</td></tr>";
echo "<tr><td>TOTAL NETO</td><td>".number_format($neto_mayor,2)."</td></tr>";
echo "</table>";


echo "<table class = 'table table-bordered'>";
echo "<tr class = 'alert alert-success'><th colspan = '2'>POR CONCEPTO DE PAGO DE PREMIOS</th></tr>";
echo "<tr><td width = '75%'>PAGO DE PREMIOS</td><td width = '25%'>".number_format($total_menor,2)."</td></tr>";
echo "<tr><td>IMPUESTO</td><td>".number_format($impto_menor,2)."</td></tr>";
echo "<tr><td>TOTAL NETO</td><td>".number_format($neto_menor,2)."</td></tr>";
echo "</table>";
*/

?>