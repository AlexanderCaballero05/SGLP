<?php
require('../../conexion.php');

$id_sorteo = $_GET['id_s'];
$filtro	   = $_GET['filtro'];

date_default_timezone_set('America/Tegucigalpa');
$current_date = date("d-m-Y h:i:s a");

////////////////////////////////////////////////////
///////////// CONSULTA ASIGNACIONES ////////////////

if ($filtro != 3) {

$c_asignacion = mysqli_query($conn, "SELECT id_empresa, SUM(cantidad) as cantidad, receptor, SUM(valor_neto) + SUM(rebaja_depositario) as valor_neto FROM facturacion_menor WHERE id_sorteo = '$id_sorteo' AND id_empresa != '3' GROUP BY id_empresa ");

if ($c_asignacion === FALSE) {
echo mysqli_error();
}

echo "Fecha Emision: <u>".$current_date."</u><br>";

echo "<table class = 'table table-bordered'> ";
echo "<tr class = 'alert alert-info'>";
echo "<tr class = 'alert alert-info'><th  colspan = '10' > <h3 align = 'center'><b>OTROS PUNTOS DE VENTA Y REGIONALES </b></h3></th></tr>";
echo "</tr>";
echo "<tr class = 'alert alert-info'>";
echo "<th style = 'width:10%' >Entidad</th>";
echo "<th style = 'width:10%' >Numeros Asignados</th>";
echo "<th style = 'width:10%' >Numeros Vendidos</th>";
echo "<th style = 'width:10%' >Numeros Devueltos</th>";
echo "<th style = 'width:10%' >Precio De Venta</th>";
echo "<th style = 'width:10%' >Total Bruto</th>";
echo "<th style = 'width:10%' >Descuento</th>";
echo "<th style = 'width:10%' >Comision</th>";
echo "<th style = 'width:10%' >Utilidad</th>";
echo "</tr>";


$tt_asignacion = 0;
$tt_venta 	   = 0;
$tt_devolucion = 0;
$tt_bruto 	   = 0;
$tt_descuento  = 0;
$tt_comision   = 0;
$tt_aportacion = 0;
$tt_credito    = 0;

$concatenado_porcentaje_venta = '';
$concatenado_asociaciones 	  = '';

$total_general_venta = 0;

while ($reg_asignacion = mysqli_fetch_array($c_asignacion)) {
$id_entidad = $reg_asignacion['id_empresa'];
$valor_neto = $reg_asignacion['valor_neto'];

$precio_uni = $reg_asignacion['valor_neto']/$reg_asignacion['cantidad'];

////////////////////////////////////////////////////
////////////// CONSULTA DE VENTAS //////////////////

$c_ventas = mysqli_query($conn,"SELECT precio_unitario ,SUM(aportacion) as aportacion, SUM(cantidad) as venta, SUM(utilidad_pani) as credito, SUM(total_bruto) as total_bruto, SUM(comision_bancaria) as comision , SUM(descuento) as descuento , SUM(aportacion) as aportacion FROM transaccional_ventas WHERE estado_venta = 'APROBADO' AND id_sorteo = '$id_sorteo' AND id_entidad = '$id_entidad' AND cod_producto = 2 ");

$ob_ventas = mysqli_fetch_object($c_ventas);
$ventas_entidad  = $ob_ventas->venta;
$credito_entidad = $ob_ventas->credito;
$dev_entidad     = $reg_asignacion['cantidad'] - $ventas_entidad;
$venta_entidad_l = $ventas_entidad * $precio_uni;
$precio_unitario = $ob_ventas->precio_unitario;
$total_bruto 	 = $ob_ventas->total_bruto;
$descuento 		 = $ob_ventas->descuento;
$comision 		 = $ob_ventas->comision;
$aportacion 	 = $ob_ventas->aportacion;
$credito 		 = $ob_ventas->credito;

////////////// CONSULTA DE VENTAS //////////////////
////////////////////////////////////////////////////

if ($ventas_entidad == 0) {
$porcentaje_venta = 0;
}else{
$porcentaje_venta = $ventas_entidad/$reg_asignacion['cantidad'];
}

$porcentaje_venta = $porcentaje_venta * 100;
$concatenado_porcentaje_venta = $concatenado_porcentaje_venta.",".number_format($porcentaje_venta,"2");
$concatenado_asociaciones     = $concatenado_asociaciones.",".$reg_asignacion['receptor'];
echo "<tr>";
echo "<td>".$reg_asignacion['receptor']."</td>";
echo "<td>".number_format($reg_asignacion['cantidad'])."</td>";
echo "<td>".number_format($ventas_entidad)."</td>";
echo "<td>".number_format($dev_entidad)."</td>";
echo "<td>".number_format($precio_unitario,"2")."</td>";
echo "<td>".number_format($total_bruto,"2")."</td>";
echo "<td>".number_format($descuento,"2")."</td>";
echo "<td>".number_format($comision,"2")."</td>";
echo "<td>".number_format($credito,"2")."</td>";
echo "</tr>";

$tt_asignacion += $reg_asignacion['cantidad'];
$tt_venta 	   += $ventas_entidad;
$tt_devolucion += $dev_entidad;
$tt_bruto 	   += $total_bruto;
$tt_descuento  += $descuento;
$tt_comision   += $comision;
$tt_aportacion += $aportacion;
$tt_credito    += $credito;

}

echo "<tr>";
echo "<th> TOTALES </th>";
echo "<th>".number_format($tt_asignacion)."</th>";
echo "<th>".number_format($tt_venta)."</th>";
echo "<th>".number_format($tt_devolucion)."</th>";
echo "<th></th>";
echo "<th>".number_format($tt_bruto,"2")."</th>";
echo "<th>".number_format($tt_descuento,"2")."</th>";
echo "<th>".number_format($tt_comision,"2")."</th>";
echo "<th>".number_format($tt_credito,"2")."</th>";
echo "</tr>";

echo "</table>";

$total_general_venta += $tt_credito;
///////////// CONSULTA ASIGNACIONES ////////////////
////////////////////////////////////////////////////


}




/////////////// BANRURAL ASIGNACION/VENTA ///////////////
/////////////// BANRURAL ASIGNACION/VENTA ///////////////
/////////////// BANRURAL ASIGNACION/VENTA ///////////////
/////////////// BANRURAL ASIGNACION/VENTA ///////////////



echo "<br>";


if ($filtro == 1 OR $filtro == 3) {
////////////////////////////////////////////////////
///////////// CONSULTA ASIGNACIONES ////////////////

$c_asignacion = mysqli_query($conn,"SELECT id_empresa, SUM(cantidad) as cantidad, receptor, SUM(valor_neto) + SUM(rebaja_depositario) as valor_neto FROM facturacion_menor WHERE id_sorteo = '$id_sorteo' AND id_empresa = '3' GROUP BY id_empresa ");

if ($c_asignacion === FALSE) {
echo mysqli_error($conn);
}


echo "<table class = 'table table-bordered'> ";
echo "<tr class = 'alert alert-info'><th  colspan = '10' > <h3 align = 'center'><b>BANRURAL </b></h3></th></tr>";
echo "<tr class = 'alert alert-info'>";
echo "<th style = 'width:10%' >Concepto</th>";
echo "<th style = 'width:10%' >Asignado (Bolsas)</th>";
echo "<th style = 'width:10%' >Vendido</th>";
echo "<th style = 'width:10%' >Devuelto (Bolsas)</th>";
echo "<th style = 'width:10%' >Precio de Venta (Bolsas)</th>";
echo "<th style = 'width:10%' >Total Bruto</th>";
echo "<th style = 'width:10%' >Descuento</th>";
echo "<th style = 'width:10%' >Comision Bancaria</th>";
echo "<th style = 'width:10%' >Utilidad</th>";
echo "</tr>";


$tt_asignacion = 0;
$tt_venta 	   = 0;
$tt_devolucion = 0;
$tt_bruto 	   = 0;
$tt_descuento  = 0;
$tt_comision   = 0;
$tt_aportacion = 0;
$tt_credito    = 0;

$ob_asignacion = mysqli_fetch_object($c_asignacion);
$id_entidad    = $ob_asignacion->id_empresa;
$cantidad_asig = $ob_asignacion->cantidad;
$cantidad_asig = $cantidad_asig/100;
$receptor      = $ob_asignacion->receptor;

////////////////////////////////////////////////////
////////////// CONSULTA DE VENTAS //////////////////

$c_ventas = mysqli_query($conn,"SELECT SUM(cantidad) as venta, cod_producto ,precio_unitario, SUM(total_bruto) as bruto ,   SUM(comision_bancaria) as comision ,   SUM(descuento) as descuento , SUM(aportacion) as aportacion , SUM(utilidad_pani) as credito FROM transaccional_ventas_general WHERE estado_venta = 'APROBADO' AND id_sorteo = '$id_sorteo' AND id_entidad = '$id_entidad' AND cod_producto IN (2,3) GROUP BY cod_producto ORDER BY cod_producto ASC ");

if ($c_ventas === FALSE) {
echo mysqli_error($conn);
}

////////////// CONSULTA DE VENTAS //////////////////
////////////////////////////////////////////////////

$cantidad_bolsa = 0;
if (mysqli_num_rows($c_ventas) > 0) {
while ($r_ventas = mysqli_fetch_array($c_ventas)) {

if ($r_ventas['cod_producto']  == 3) {
$cantidad_bolsa    = $r_ventas['venta'];
$precio_bolsa      = $r_ventas['precio_unitario'];
$bruto_bolsa       = $r_ventas['bruto'];
$comision_bolsa    = $r_ventas['comision'];
$descuento_bolsa   = $r_ventas['descuento'];
$aportacion_bolsa  = $r_ventas['aportacion'];
$credito_bolsa     = $r_ventas['credito'];

}else{

$cantidad_num   = $r_ventas['venta'];
$cantidad_num_b = $cantidad_num/100;
$precio_num     = $r_ventas['precio_unitario'];
$bruto_num      = $r_ventas['bruto'];
$comision_num   = $r_ventas['comision'];
$descuento_num  = $r_ventas['descuento'];
$aportacion_num = $r_ventas['aportacion'];
$credito_num    = $r_ventas['credito'];

}

}

$ventas_banco  = $cantidad_bolsa + $cantidad_num_b;
$devolu_banco  = $cantidad_asig - $ventas_banco;
$credito_banco = $credito_bolsa  + $credito_num;


if ($ventas_banco == 0) {
$porcentaje_venta = 0;
}else{
$porcentaje_venta = $ventas_banco/$cantidad_asig;
}

$porcentaje_venta = $porcentaje_venta * 100;
$concatenado_porcentaje_venta = $concatenado_porcentaje_venta.",".number_format($porcentaje_venta,'2');
$concatenado_asociaciones     = $concatenado_asociaciones.",BANRURAL";



echo "<tr>";
echo "<td>VENTA POR NUMERO</td>";
echo "<td rowspan = '2' align = 'center' style = 'vertical-align:middle' >".$cantidad_asig."</td>";
echo "<td>".number_format($cantidad_num)."</td>";
echo "<td rowspan = '2' align = 'center' style = 'vertical-align:middle' >".number_format($devolu_banco,"2")."</td>";

echo "<td>".number_format($precio_num, "2")."</td>";
echo "<td>".number_format($bruto_num, "2")."</td>";
echo "<td>".number_format($descuento_num, "2")."</td>";
echo "<td>".number_format($comision_num, "2")."</td>";
echo "<td>".number_format($credito_num, "2")."</td>";
echo "</tr>";

echo "<tr>";
echo "<td>VENTA POR BOLSA</td>";
echo "<td>".number_format($cantidad_bolsa)."</td>";
echo "<td>".number_format($precio_bolsa, "2")."</td>";
echo "<td>".number_format($bruto_bolsa, "2")."</td>";
echo "<td>".number_format($descuento_bolsa, "2")."</td>";
echo "<td>".number_format($comision_bolsa, "2")."</td>";
echo "<td>".number_format($credito_bolsa, "2")."</td>";
echo "</tr>";

$tt_asignacion = $cantidad_asig;
$tt_venta 	   = $ventas_banco;
$tt_devolucion = $devolu_banco;
$tt_bruto 	   = $bruto_num + $bruto_bolsa;
$tt_descuento  = $descuento_num + $descuento_bolsa;
$tt_comision   = $comision_num + $comision_bolsa;
$tt_aportacion = $aportacion_num + $aportacion_bolsa;
$tt_credito    = $credito_num + $credito_bolsa;

}


echo "<tr>";
echo "<th> TOTALES </th>";
echo "<th>".number_format($tt_asignacion)."</th>";
echo "<th>".number_format($tt_venta,"2")."</th>";
echo "<th>".number_format($tt_devolucion,"2")."</th>";
echo "<th></th>";
echo "<th>".number_format($tt_bruto,"2")."</th>";
echo "<th>".number_format($tt_descuento,"2")."</th>";
echo "<th>".number_format($tt_comision,"2")."</th>";
echo "<th>".number_format($tt_credito,"2")."</th>";
echo "</tr>";


echo "</table>";

$total_general_venta += $tt_credito;
///////////// CONSULTA ASIGNACIONES ////////////////
////////////////////////////////////////////////////

}


echo "<br>";

/////////////////////////////////////////////////////
////////////////////// PREMIOS //////////////////////
/////////////////////////////////////////////////////


$numeros_premiados = mysqli_query($conn,"SELECT * FROM sorteos_menores_premios WHERE premios_menores_id IN (1,3)  AND sorteos_menores_id = '$id_sorteo' ");

while ($reg_premios = mysqli_fetch_array($numeros_premiados)) {
if ($reg_premios['premios_menores_id'] == 1) {
$derecho = $reg_premios['numero_premiado_menor'];
}else{
$reves   = $reg_premios['numero_premiado_menor'];
}
}

if ($derecho != "" AND $reves != "") {



if ($filtro == 1) {
$entidades = mysqli_query($conn,"SELECT * FROM empresas WHERE estado = 'ACTIVO' ");
}elseif($filtro == 2){
$entidades = mysqli_query($conn,"SELECT * FROM empresas WHERE estado = 'ACTIVO' AND id != 3 ");
}elseif ($filtro == 3) {
$entidades = mysqli_query($conn,"SELECT * FROM empresas WHERE estado = 'ACTIVO' AND id = 3 ");
}


$tt_derecho   = 0;
$tt_reves     = 0;
$tt_derecho_p = 0;
$tt_reves_p   = 0;
$tt_p         = 0;
$tt_p_series  = 0;

///////////////////////////////////////////////////////////////
////////////////// CONSULTA SERIES PREMIADAS //////////////////



$series_premiadas = mysqli_query($conn," SELECT b.numero_premiado_menor,b.monto,a.tipo_serie,a.clasificacion FROM pani.premios_menores as a INNER JOIN sorteos_menores_premios AS b ON a.id = b.premios_menores_id WHERE b.sorteos_menores_id = '$id_sorteo' AND b.numero_premiado_menor IS NOT NULL AND b.premios_menores_id NOT IN (1,3) ");

$i = 0;
$v = 0;
while ($reg_series_premiadas = mysqli_fetch_array($series_premiadas)) {
$serie = $reg_series_premiadas['numero_premiado_menor'];
$v_series[$i] = $serie;

if ($i == 0) {
$concat_series = $v_series[$i];
}else{
$concat_series = $concat_series.",".$v_series[$i];
}

$i++;

if ($reg_series_premiadas['tipo_serie'] == 'GANADOR' AND $reg_series_premiadas['clasificacion'] == 'SERIE' ) {

if ($filtro == 1) {

$verificar_serie = mysqli_query($conn," SELECT (SELECT COUNT(serie) FROM fvp_detalles_ventas_menor WHERE id_sorteo = '$id_sorteo' AND numero = '$derecho' AND serie = '$serie' AND estado_venta = 'APROBADO'  ) as conteo1 , (SELECT COUNT(serie) FROM transaccional_menor_banco_bolsas_detalle WHERE id_sorteo = '$id_sorteo' AND serie = '$serie' AND estado_venta = 'APROBADO'  ) as conteo2, (SELECT COUNT(serie) FROM transaccional_menor_banco_numeros_detalle WHERE id_sorteo = '$id_sorteo' AND numero = '$derecho' AND serie = '$serie' AND estado_venta = 'APROBADO'  ) as conteo3  ");

$ob_verificar_serie = mysqli_fetch_object($verificar_serie);
$c_1 = $ob_verificar_serie->conteo1;
$c_2 = $ob_verificar_serie->conteo2;
$c_3 = $ob_verificar_serie->conteo3;

}elseif($filtro == 2){

$verificar_serie = mysqli_query($conn," SELECT (SELECT COUNT(serie) FROM fvp_detalles_ventas_menor WHERE id_sorteo = '$id_sorteo' AND numero = '$derecho' AND serie = '$serie' AND estado_venta = 'APROBADO'  ) as conteo1 ");

$ob_verificar_serie = mysqli_fetch_object($verificar_serie);
$c_1 = $ob_verificar_serie->conteo1;
$c_2 = 0;
$c_3 = 0;

}elseif ($filtro == 3) {


$verificar_serie = mysqli_query($conn," SELECT  (SELECT COUNT(serie) FROM transaccional_menor_banco_bolsas_detalle WHERE id_sorteo = '$id_sorteo' AND serie = '$serie' AND estado_venta = 'APROBADO'  ) as conteo2, (SELECT COUNT(serie) FROM transaccional_menor_banco_numeros_detalle WHERE id_sorteo = '$id_sorteo' AND numero = '$derecho' AND serie = '$serie' AND estado_venta = 'APROBADO'  ) as conteo3  ");

$ob_verificar_serie = mysqli_fetch_object($verificar_serie);
$c_2 = $ob_verificar_serie->conteo2;
$c_3 = $ob_verificar_serie->conteo3;

}


if ($c_1 > 0 OR $c_2 > 0 OR $c_3 > 0) {

if ($c_1 > 0 ) {

$consulta_empresa_venta = mysqli_query($conn," SELECT c.nombre_empresa  FROM fvp_detalles_ventas_menor as a INNER JOIN transaccional_ventas as b INNER JOIN empresas as c ON a.cod_factura = b.cod_factura AND b.id_entidad = c.id  WHERE a.id_sorteo = '$id_sorteo' AND a.numero = '$derecho' AND a.serie = '$serie' AND a.estado_venta = 'APROBADO' ");

$ob_empresa_venta = mysqli_fetch_object($consulta_empresa_venta);
$empresa_premio   = $ob_empresa_venta->nombre_empresa;

$v_premiaciones_series[$v][0] =  "SERIE GANADORA DE DERECHO VENDIDA POR ".$empresa_premio.": <b>".$reg_series_premiadas['numero_premiado_menor']."</b>";

}else{

$consulta_empresa_venta = mysqli_query($conn," SELECT nombre_empresa FROM empresas WHERE distribuidor = 'SI' ");
$ob_empresa_venta = mysqli_fetch_object($consulta_empresa_venta);
$empresa_premio   = $ob_empresa_venta->nombre_empresa;

$v_premiaciones_series[$v][0] =  "SERIE GANADORA DE DERECHO VENDIDA POR ".$empresa_premio.": <b>".$reg_series_premiadas['numero_premiado_menor']."</b>";

}

$v_premiaciones_series[$v][1] =  number_format($reg_series_premiadas['monto'],"2");
$v_premiaciones_series[$v][2] =  number_format($reg_series_premiadas['monto'],"2");
$v ++;


$tt_p         += $reg_series_premiadas['monto'];

}



}elseif ($reg_series_premiadas['tipo_serie'] == 'REVES' AND $reg_series_premiadas['clasificacion'] == 'SERIE') {

if ($filtro == 1) {

$verificar_serie = mysqli_query($conn," SELECT (SELECT COUNT(serie) FROM fvp_detalles_ventas_menor WHERE id_sorteo = '$id_sorteo' AND numero = '$reves' AND serie = '$serie' AND estado_venta = 'APROBADO'  ) as conteo1 , (SELECT COUNT(serie) FROM transaccional_menor_banco_bolsas_detalle WHERE id_sorteo = '$id_sorteo' AND serie = '$serie' AND estado_venta = 'APROBADO'  ) as conteo2, (SELECT COUNT(serie) FROM transaccional_menor_banco_numeros_detalle WHERE id_sorteo = '$id_sorteo' AND numero = '$reves' AND serie = '$serie' AND estado_venta = 'APROBADO'  ) as conteo3  ");

$ob_verificar_serie = mysqli_fetch_object($verificar_serie);
$c_1 = $ob_verificar_serie->conteo1;
$c_2 = $ob_verificar_serie->conteo2;
$c_3 = $ob_verificar_serie->conteo3;

}elseif($filtro == 2){

$verificar_serie = mysqli_query($conn," SELECT (SELECT COUNT(serie) FROM fvp_detalles_ventas_menor WHERE id_sorteo = '$id_sorteo' AND numero = '$reves' AND serie = '$serie' AND estado_venta = 'APROBADO'  ) as conteo1  ");

$ob_verificar_serie = mysqli_fetch_object($verificar_serie);
$c_1 = $ob_verificar_serie->conteo1;
$c_2 = 0;
$c_3 = 0;

}elseif ($filtro == 3) {

$verificar_serie = mysqli_query($conn," SELECT (SELECT COUNT(serie) FROM transaccional_menor_banco_bolsas_detalle WHERE id_sorteo = '$id_sorteo' AND serie = '$serie' AND estado_venta = 'APROBADO'  ) as conteo2, (SELECT COUNT(serie) FROM transaccional_menor_banco_numeros_detalle WHERE id_sorteo = '$id_sorteo' AND numero = '$reves' AND serie = '$serie' AND estado_venta = 'APROBADO'  ) as conteo3  ");

$ob_verificar_serie = mysqli_fetch_object($verificar_serie);
$c_2 = $ob_verificar_serie->conteo2;
$c_3 = $ob_verificar_serie->conteo3;

}


if ($c_1 > 0 OR $c_2 > 0 OR $c_3 > 0) {

if ($c_1 > 0) {

$consulta_empresa_venta = mysqli_query($conn," SELECT c.nombre_empresa  FROM fvp_detalles_ventas_menor as a INNER JOIN transaccional_ventas as b INNER JOIN empresas as c ON a.cod_factura = b.cod_factura AND b.id_entidad = c.id  WHERE a.id_sorteo = '$id_sorteo' AND a.numero = '$reves' AND a.serie = '$serie' AND a.estado_venta = 'APROBADO' ");
$ob_empresa_venta = mysqli_fetch_object($consulta_empresa_venta);
$empresa_premio   = $ob_empresa_venta->nombre_empresa;

$v_premiaciones_series[$v][0] =  "SERIE GANADORA DE REVES VENDIDA POR ".$empresa_premio.": <b>".$reg_series_premiadas['numero_premiado_menor']."</b>";

}else{

$consulta_empresa_venta = mysqli_query($conn," SELECT nombre_empresa FROM empresas WHERE distribuidor = 'SI' ");
$ob_empresa_venta = mysqli_fetch_object($consulta_empresa_venta);
$empresa_premio   = $ob_empresa_venta->nombre_empresa;

$v_premiaciones_series[$v][0] =  "SERIE GANADORA DE REVES VENDIDA POR ".$empresa_premio.": <b>".$reg_series_premiadas['numero_premiado_menor']."</b>";

}

$v_premiaciones_series[$v][1] =  number_format($reg_series_premiadas['monto'],"2");
$v_premiaciones_series[$v][2] =  number_format($reg_series_premiadas['monto'],"2");
$v ++;


$tt_p         += $reg_series_premiadas['monto'];

}

}

}


///////////////// SERIES PREMIADAS ////////////////////////////
///////////////////////////////////////////////////////////////



echo "<table class = 'table table-bordered'>";
echo "<tr class = 'alert alert-info'>";
echo "<th colspan = '7'><h3 align = 'center'><b> Venta por numero premiado: Derecho ".$derecho." | Reves ".$reves." </b></h3></th>";
echo "</tr>";
echo "<tr class = 'alert alert-info'>";
echo "<th>Entidad</th>";
echo "<th> Venta (".$derecho.")</th>";
echo "<th> Venta (".$reves.")</th>";
echo "<th> Monto a pagar (".$derecho.")</th>";
echo "<th> Monto a pagar (".$reves.")</th>";
echo "<th> Pago por series</th>";
echo "<th> Total a pagar </th>";
echo "</tr>";

$conteo_derecho = 0;
$conteo_reves   = 0;

while ($reg_entidades = mysqli_fetch_array($entidades)) {
$id_entidad 	= $reg_entidades['id'];
$mombre_entidad = $reg_entidades['nombre_empresa'];

if ($id_entidad != 3) {

$venta_derecho = mysqli_query($conn,"SELECT count(a.numero) as conteo FROM fvp_detalles_ventas_menor as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE b.id_entidad = '$id_entidad' AND a.estado_venta = 'APROBADO' AND a.numero = '$derecho'  AND a.id_sorteo = '$id_sorteo' ");
$ob_venta_derecho = mysqli_fetch_object($venta_derecho);
$conteo_derecho   = $ob_venta_derecho->conteo;

$venta_reves = mysqli_query($conn,"SELECT count(a.numero) as conteo FROM fvp_detalles_ventas_menor as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE b.id_entidad = '$id_entidad' AND a.estado_venta = 'APROBADO' AND a.numero = '$reves' AND a.id_sorteo = '$id_sorteo'  ");
$ob_venta_reves = mysqli_fetch_object($venta_reves);
$conteo_reves   = $ob_venta_reves->conteo;

}else{

$venta_derecho = mysqli_query($conn,"SELECT count(a.numero) as conteo FROM transaccional_menor_banco_numeros_detalle as a WHERE a.estado_venta = 'APROBADO' AND a.numero = '$derecho'  AND a.id_sorteo = '$id_sorteo' ");
$ob_venta_derecho = mysqli_fetch_object($venta_derecho);
$conteo_derecho   = $ob_venta_derecho->conteo;
$conteo_derecho  += $cantidad_bolsa;


$venta_reves = mysqli_query($conn,"SELECT count(a.numero) as conteo FROM transaccional_menor_banco_numeros_detalle as a WHERE a.estado_venta = 'APROBADO' AND a.numero = '$reves'  AND a.id_sorteo = '$id_sorteo' ");
$ob_venta_reves = mysqli_fetch_object($venta_reves);
$conteo_reves   = $ob_venta_reves->conteo;
$conteo_reves  += $cantidad_bolsa;


}



///////////////////////////////////////////////////////
//////////////// VERIFICACION SERIE VENDIDA ///////////
$cant_pago_series = 0;
$c_1 = 0;
$c_2 = 0;
$c_3 = 0;

$i = 0;
while (isset($v_series[$i])) {

$serie = $v_series[$i];

if ($id_entidad == 3) {

$consulta_venta_serie = mysqli_query($conn," SELECT (SELECT COUNT(serie) FROM transaccional_menor_banco_bolsas_detalle as a INNER JOIN transaccional_ventas_general as b ON a.cod_factura = b.cod_factura_recaudador WHERE a.id_sorteo = '$id_sorteo' AND a.serie IN ($concat_series) AND a.estado_venta = 'APROBADO' AND b.id_entidad = '$id_entidad'  ) as conteo2, (SELECT COUNT(serie) FROM transaccional_menor_banco_numeros_detalle as a INNER JOIN transaccional_ventas_general as b ON a.cod_factura = b.cod_factura_recaudador WHERE a.id_sorteo = '$id_sorteo' AND a.numero NOT IN('$derecho','$reves') AND a.serie IN ($concat_series) AND a.estado_venta = 'APROBADO' AND b.id_entidad = '$id_entidad' ) as conteo3  ");

$ob_cantidad_serie = mysqli_fetch_object($consulta_venta_serie);
$c_1 = 0;
$c_2 = $ob_cantidad_serie->conteo2;
$c_3 = $ob_cantidad_serie->conteo3;

}else{

$consulta_venta_serie = mysqli_query($conn," SELECT COUNT(serie) as conteo1 FROM fvp_detalles_ventas_menor as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE a.id_sorteo = '$id_sorteo' AND a.numero NOT IN ('$derecho','$reves') AND a.serie IN ($concat_series) AND a.estado_venta = 'APROBADO' AND b.id_entidad = '$id_entidad'   ");

$ob_cantidad_serie = mysqli_fetch_object($consulta_venta_serie);
$c_1 = $ob_cantidad_serie->conteo1;
$c_2 = 0;
$c_3 = 0;


}


if ($c_1 > 0 OR $c_2 > 0 OR $c_3 > 0 ) {

if ($derecho == $reves) {
$c_2 *= 99;
}else{
$c_2 *= 98;
}

$cant_pago_series = $c_1 + $c_2 + $c_3;

}

$i++;
}

$pago_derecho = $conteo_derecho * 1000;
$pago_reves   = $conteo_reves   * 100;
$monto_pago_series = $cant_pago_series * 100;
$total_pago   = $pago_derecho + $pago_reves + $monto_pago_series;


//////////////// VERIFICACION SERIE VENDIDA ///////////
///////////////////////////////////////////////////////


echo "<tr >";
echo "<td>".$mombre_entidad."</td>";
echo "<td>".number_format($conteo_derecho)."</td>";
echo "<td>".number_format($conteo_reves)."</td>";
echo "<td>".number_format($pago_derecho, 2)."</td>";
echo "<td>".number_format($pago_reves, 2)."</td>";
echo "<td>".number_format($monto_pago_series,"2")."</td>";
echo "<td>".number_format($total_pago, 2)."</td>";
echo "</tr>";

$tt_derecho   += $conteo_derecho;
$tt_reves     += $conteo_reves;
$tt_derecho_p += $pago_derecho;
$tt_reves_p   += $pago_reves;
$tt_p         += $total_pago;
$tt_p_series  += $monto_pago_series;
}


$v = 0;
while (isset($v_premiaciones_series[$v][0])) {
echo "<tr>";
echo "<td colspan = 6 >".$v_premiaciones_series[$v][0]."</td>";
echo "<td>".$v_premiaciones_series[$v][2]."</td>";
echo "</tr>";
$v++;
}



echo "<tr>";
echo "<th> TOTALES </th>";
echo "<th>".number_format($tt_derecho)."</th>";
echo "<th>".number_format($tt_reves)."</th>";
echo "<th>".number_format($tt_derecho_p,"2")."</th>";
echo "<th>".number_format($tt_reves_p,"2")."</th>";
echo "<th>".number_format($tt_p_series,"2")."</th>";
echo "<th>".number_format($tt_p,"2")."</th>";
echo "</tr>";

echo "</table>";

$utilidad_perdida = $total_general_venta - $tt_p;

}

if (!isset($tt_p)) {
$tt_p = 0;
$utilidad_perdida = $total_general_venta;
}


?>

<br>

<table width="100%">
<tr>
<td width="25%">
</td>

<td width="50%">

<table class="table table-bordered">
	<tr>
		<th colspan="2" class="alert alert-info"><h3 align="center"> RESUMEN DE INGRESOS </h3></th>
	</tr>
	<tr>
		<td>TOTAL INGRESOS POR VENTA</td>
		<td><?php echo number_format($total_general_venta,"2") ?></td>
	</tr>
	<tr>
		<td>TOTAL PROVISION PAGO</td>
		<td><?php echo number_format($tt_p,"2") ?></td>
	</tr>

<?php
if ( $utilidad_perdida < 0) {
?>

	<tr class="alert alert-danger">
		<td>UTILIDAD O PERDIDA SORTEO <?php echo $id_sorteo; ?></td>
		<td><?php echo number_format($utilidad_perdida,"2") ?></td>
	</tr>

<?php
}else{
?>
	<tr class="alert alert-success">
		<td>UTILIDAD O PERDIDA SORTEO <?php echo $id_sorteo; ?></td>
		<td><?php echo number_format($utilidad_perdida,"2") ?></td>
	</tr>
<?php
}

$consulta_info_sorteo = mysqli_query($conn,"SELECT fecha_sorteo FROM sorteos_menores WHERE id = '$id_sorteo'  ");
$ob_info_sorteo = mysqli_fetch_object($consulta_info_sorteo);
$f = $ob_info_sorteo->fecha_sorteo;

$v_f = explode("-", $f);
$year = $v_f[0];

if ($filtro == 1) {
$consulta_acumulado = mysqli_query($conn,"SELECT  SUM(a.utilidad_perdida) as utilidad_perdida FROM utilidades_perdidas_sorteos as a INNER JOIN sorteos_menores as b INNER JOIN empresas as c ON a.id_sorteo = b.id AND a.id_entidad = c.id WHERE a.id_sorteo <= '$id_sorteo'  AND YEAR(b.fecha_sorteo) = '$year' AND a.tipo_loteria = 2 ");
$ob_consulta_camulado = mysqli_fetch_object($consulta_acumulado);
$acumulado = $ob_consulta_camulado->utilidad_perdida;
}elseif($filtro == 2){
$consulta_acumulado = mysqli_query($conn,"SELECT  SUM(a.utilidad_perdida) as utilidad_perdida FROM utilidades_perdidas_sorteos as a INNER JOIN sorteos_menores as b INNER JOIN empresas as c ON a.id_sorteo = b.id AND a.id_entidad = c.id WHERE c.distribuidor = 'NO' AND a.id_sorteo <= '$id_sorteo'  AND YEAR(b.fecha_sorteo) = '$year' AND a.tipo_loteria = 2 ");
$ob_consulta_camulado = mysqli_fetch_object($consulta_acumulado);
$acumulado = $ob_consulta_camulado->utilidad_perdida;
}elseif ($filtro == 3) {
$consulta_acumulado = mysqli_query($conn,"SELECT  SUM(a.utilidad_perdida) as utilidad_perdida FROM utilidades_perdidas_sorteos as a INNER JOIN sorteos_menores as b INNER JOIN empresas as c ON a.id_sorteo = b.id AND a.id_entidad = c.id WHERE c.distribuidor = 'SI' AND a.id_sorteo <= '$id_sorteo'  AND YEAR(b.fecha_sorteo) = '$year' AND a.tipo_loteria = 2 ");
$ob_consulta_camulado = mysqli_fetch_object($consulta_acumulado);
$acumulado = $ob_consulta_camulado->utilidad_perdida;
}


if ($acumulado < 0) {
echo "<tr class = 'alert alert-danger'>";
}else{
echo "<tr class = 'alert alert-success'>";
}
?>

<td>UTLIDAD ACUMULADA AÑO <?php echo $year; ?></td>
<td><?php echo number_format($acumulado,"2") ?></td>
</tr>

</table>



</td>
<td width="25%" valign="top">

</td>
</tr>
</table>


<table width="100%">
	<tr>
		<td width="50%">

<span style = 'width:100%' class = 'btn btn-success' onclick = "generar_u('<?php echo $total_general_venta;?>','<?php echo $tt_p;?>')" id = 'non-printable'>
GENERAR GRAFICO DE UTILIDADES
</span>

<div class="well" style=" width:100%;heigth:50px">
<canvas style = 'display:none' id="myChart_u" width="600" height="250"></canvas>
</div>

		</td>
		<td width="50%">

<span style = 'width:100%' class = 'btn btn-info' onclick = "generar('<?php echo $concatenado_asociaciones;?>','<?php echo $concatenado_porcentaje_venta;?>')" id = 'non-printable'>
GENERAR GRAFICO COMPARATIVO DE VENTAS
</span>

<div class="well" style=" width:100%;heigth:50px">
<canvas style = 'display:none' id="myChart" width="600" height="250"></canvas>
</div>

		</td>

	</tr>
</table>

<br>


<script>
$(".div_wait").fadeOut("fast");
</script>
