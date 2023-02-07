<?php 

require_once '../../assets/nusoap/lib/nusoap.php';
require("../../conexion.php");


$fecha_inicial = $_GET['f_i'];
$fecha_final = $_GET['f_f'];
$filtro = $_GET['filtro'];




if ($filtro == 1) {

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$wsdl="http://192.168.15.248/_wsSvrBD/_GetDailyPaymentMenor.php?wsdl";
$cliente = new nusoap_client($wsdl,true);
$cliente->soap_defencoding = 'utf-8';//default is 
$cliente->response_timeout = 800;//seconds
$cliente->useHTTPPersistentConnection();

$err = $cliente->getError();
if ($err)
{
echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
exit();
}

////  CAMBIAR POR EL GET/POST DE LOS PARAMETROS

$cantidad = array();
$total    = array();
$impto    = array();
$neto     = array();

$resultado = $cliente-> call("GetDailyPaymentMayor", array("fecha_inicial" => $fecha_inicial, "fecha_final" => $fecha_final ));


$err = $cliente->getError();
if ($err) 
{
echo '<h2>Constructor error</h2><pre>' . $err . '</pre><h2>Debug</h2><pre>' . htmlspecialchars($cliente->getDebug(), ENT_QUOTES) . '</pre>';
}
else 
{
if ($resultado){
$data=explode("$", $resultado);
$cantidad=$data[0];
$total=$data[1];
$impto=$data[2];
$neto=$data[3];
}
else
{
$cantidad=0;
$total=0;
$impto=0;
$neto=0;
}



}




$total_comision   = 0;
$total_aportacion = 0;
$total_descuento  = 0;

$consulta_ventas_fvp = mysqli_query($conn,"SELECT SUM(comision_bancaria) as total_comision , SUM(descuento) as total_descuento, SUM(aportacion) as total_aportacion FROM transaccional_ventas WHERE estado_venta = 'APROBADO' AND DATE(fecha_venta) BETWEEN '$fecha_inicial' AND '$fecha_final' AND cod_producto = 1 ");

$ob_consulta_ventas_fvp = mysqli_fetch_object($consulta_ventas_fvp);
$comision_fvp   = $ob_consulta_ventas_fvp->total_comision;
$aportacion_fvp = $ob_consulta_ventas_fvp->total_aportacion;
$descuento_fvp  = $ob_consulta_ventas_fvp->total_descuento;


$consulta_ventas_banco = mysqli_query($conn,"SELECT SUM(comision_bancaria) as total_comision , SUM(descuento) as total_descuento, SUM(aportacion) as total_aportacion FROM transaccional_ventas_general WHERE estado_venta = 'APROBADO' AND DATE(fecha_venta) BETWEEN '$fecha_inicial' AND '$fecha_final' AND cod_producto = 1 ");

$ob_consulta_ventas_banco = mysqli_fetch_object($consulta_ventas_banco);
$comision_banco   = $ob_consulta_ventas_banco->total_comision;
$aportacion_banco = $ob_consulta_ventas_banco->total_aportacion;
$descuento_banco  = $ob_consulta_ventas_banco->total_descuento;

$total_comision   = $comision_fvp 	+ $comision_banco;
$total_aportacion = $aportacion_fvp + $aportacion_banco;
$total_descuento  = $descuento_fvp  + $descuento_banco;



echo "<table class = 'table table-bordered'>";
echo "<tr class = 'alert alert-success'><th colspan = '2'>POR CONCEPTO DE VENTA</th></tr>";
echo "<tr><td width = '75%'>COMISION BANCARIA</td><td width = '25%'>".number_format($total_comision,2)."</td></tr>";
echo "<tr><td>COMISION VENDEDORES</td><td>".number_format($total_descuento,2)."</td></tr>";
echo "</table>";


echo "<table class = 'table table-bordered'>";
echo "<tr class = 'alert alert-success'><th colspan = '2'>POR CONCEPTO DE PAGO DE PREMIOS</th></tr>";
echo "<tr><td width = '75%'>PAGO DE PREMIOS</td><td width = '25%'>".number_format($total,2)."</td></tr>";
echo "<tr><td>IMPUESTO</td><td>".number_format($impto,2)."</td></tr>";
echo "<tr><td>TOTAL NETO</td><td>".number_format($neto,2)."</td></tr>";
echo "</table>";



echo "<a class = 'btn btn-success' target = '_blanck' href = 'print_informe_comisiones.php?f_i=".$fecha_inicial."&f_f=".$fecha_final."' > <i class= 'fa fa-print'></i> Imprimir Ventas</a>";
echo "&nbsp";
echo "<a class = 'btn btn-success' target = '_blanck' href = 'print_informe_premios.php?f_i=".$fecha_inicial."&f_f=".$fecha_final."' > <i class= 'fa fa-print'></i> Imprimir Pagos</a>";


}else{




$wsdl="http://192.168.15.248/_wsSvrBD/_GetDailyPaymentMenor.php?wsdl";
$cliente = new nusoap_client($wsdl,true);
$cliente->soap_defencoding = 'utf-8';//default is 
$cliente->response_timeout = 900;//seconds
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
if(resultado)
{
$data=explode("$", $resultado);
$cantidad=$data[0];
$total=$data[1];
$impto=$data[2];
$neto=$data[3];
}
else
{
$cantidad=0;
$total=0;
$impto=0;
$neto=0;
}


}






$total_comision   = 0;
$total_aportacion = 0;
$total_descuento  = 0;

$consulta_ventas_fvp = mysqli_query($conn,"SELECT SUM(comision_bancaria) as total_comision , SUM(descuento) as total_descuento, SUM(aportacion) as total_aportacion FROM transaccional_ventas WHERE estado_venta = 'APROBADO' AND DATE(fecha_venta) BETWEEN '$fecha_inicial' AND '$fecha_final' AND cod_producto != 1 ");

$ob_consulta_ventas_fvp = mysqli_fetch_object($consulta_ventas_fvp);
$comision_fvp   = $ob_consulta_ventas_fvp->total_comision;
$aportacion_fvp = $ob_consulta_ventas_fvp->total_aportacion;
$descuento_fvp  = $ob_consulta_ventas_fvp->total_descuento;


$consulta_ventas_banco = mysqli_query($conn,"SELECT SUM(comision_bancaria) as total_comision , SUM(descuento) as total_descuento, SUM(aportacion) as total_aportacion FROM transaccional_ventas_general WHERE estado_venta = 'APROBADO' AND DATE(fecha_venta) BETWEEN '$fecha_inicial' AND '$fecha_final' AND cod_producto != 1 ");

$ob_consulta_ventas_banco = mysqli_fetch_object($consulta_ventas_banco);
$comision_banco   = $ob_consulta_ventas_banco->total_comision;
$aportacion_banco = $ob_consulta_ventas_banco->total_aportacion;
$descuento_banco  = $ob_consulta_ventas_banco->total_descuento;

$total_comision   = $comision_fvp 	+ $comision_banco;
$total_aportacion = $aportacion_fvp + $aportacion_banco;
$total_descuento  = $descuento_fvp  + $descuento_banco;


echo "<table class = 'table table-bordered'>";
echo "<tr class = 'alert alert-success'><th colspan = '2'>POR CONCEPTO DE VENTA</th></tr>";
echo "<tr><td width = '75%'>COMISION BANCARIA</td><td width = '25%'>".number_format($total_comision,2)."</td></tr>";
echo "<tr><td>COMISION VENDEDORES</td><td>".number_format($total_descuento,2)."</td></tr>";
echo "<tr><td>APORTACIONES</td><td>".number_format($total_aportacion,2)."</td></tr>";
echo "</table>";


echo "<table class = 'table table-bordered'>";
echo "<tr class = 'alert alert-success'><th colspan = '2'>POR CONCEPTO DE PAGO DE PREMIOS</th></tr>";
echo "<tr><td width = '75%'>PAGO DE PREMIOS</td><td width = '25%'>".number_format($total,2)."</td></tr>";
echo "<tr><td>IMPUESTO</td><td>".number_format($impto,2)."</td></tr>";
echo "<tr><td>TOTAL NETO</td><td>".number_format($neto,2)."</td></tr>";
echo "</table>";


echo "<a class = 'btn btn-success' target = '_blanck' href = 'print_informe_comisiones.php?f_i=".$fecha_inicial."&f_f=".$fecha_final."' > <i class= 'fa fa-print'></i> Imprimir Ventas</a>";
echo "&nbsp";
echo "<a class = 'btn btn-success' target = '_blanck' href = 'print_informe_premios.php?f_i=".$fecha_inicial."&f_f=".$fecha_final."' > <i class= 'fa fa-print'></i> Imprimir Pagos</a>";


}



?>

<script>
$(".div_wait").fadeOut("fast");
</script>
