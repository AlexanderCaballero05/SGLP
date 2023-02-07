<?php
require('../assets/nusoap/lib/nusoap.php');
date_default_timezone_set("America/Tegucigalpa");


$servicio = new soap_server();

$ns = "urn:consulta_ventas";
$servicio ->configureWSDL("ws_ventas",$ns);
$servicio -> schemaTargetNamespace = $ns;

$servicio -> register("ws_ventas", array('sorteo_envio' => 'xsd:string'), array('return' => 'xsd:string'), $ns);

$servicio -> register("ws_no_asignado", array('sorteo_envio' => 'xsd:string'), array('return' => 'xsd:string'), $ns);


function ws_ventas($id_sorteo){


$conn = mysqli_connect('localhost', 'root', 'pumalxqw1121',"cidesoft_banco")
 or die('Error: ' . mysqli_error($conn));


$info_sorteo = mysqli_query($conn, "SELECT *  FROM sorteos_menores WHERE id = '$id_sorteo' limit 1");
$value = mysqli_fetch_object($info_sorteo);
$precio_unitario = $value->precio_unitario;
$precio_unitario = $precio_unitario * 100;
$comision = $value->comision;
$porcentaje_comision=$comision/100;
$total_cantidad = 0;
$total_vendido = 0;
$total_no_vendido = 0;
$sorteo = $value->no_sorteo_men;
$descripcion = $value->descripcion_sorteo_men;
$fecha_sorteo = $value->fecha_sorteo;


date_default_timezone_set("America/Tegucigalpa");

$date = date("Y-m-d h:i:s a");


$seccionales=mysqli_query($conn,"SELECT SUM(a.cantidad) as cantidad_asignada, b.nombre, b.cod_seccional   FROM menor_reservas_seccionales_bolsas as a INNER JOIN `seccionales` as b ON a.id_seccional = b.id AND sorteos_menores_id = '$id_sorteo' GROUP BY a.id_seccional ");
$i = 0;

while ($r_seccional = mysqli_fetch_array($seccionales)){

$v_seccional_cantidad[$i] = $r_seccional['cantidad_asignada'];
$v_seccional_cod[$i] 	  = $r_seccional['cod_seccional'];
$v_seccional_nombre[$i]   = $r_seccional['nombre'];

$v_seccional_venta[$i]  	= 0;
$v_seccional_fecha[$i] = "";
$v_seccional_no_vendido[$i] = 0;

$i++;
}


$venta_seccionales=mysqli_query($conn,"SELECT SUM(a.cantidad) as cantidad_vendida, b.nombre, b.cod_seccional , MAX(fecha_venta) as f_v  FROM menor_ventas_bolsas as a INNER JOIN `seccionales` as b ON a.id_seccional = b.id  WHERE estado_venta = 'APROBADO' AND id_sorteo = '$id_sorteo' GROUP BY a.id_seccional ");
$i = 0;

while ($rv_seccional = mysqli_fetch_array($venta_seccionales)){

$vv_seccional_cantidad[$i] = $rv_seccional['cantidad_vendida'];
$vv_seccional_cod[$i] 	  = $rv_seccional['cod_seccional'];
$vv_seccional_nombre[$i]   = $rv_seccional['nombre'];

$index =  array_search($vv_seccional_cod[$i], $v_seccional_cod);

$v_seccional_venta[$index]  	= $rv_seccional['cantidad_vendida'];
$v_seccional_fecha[$index]      = $rv_seccional['f_v'];

$i++;
}


$i = 0;
$tt_asignado 	= 0;
$tt_vendido   	= 0;
$tt_no_vendido  = 0;

$trama = "";

while (isset($v_seccional_cod[$i])) {

if ($i != 0) {
$trama .= "#";
}

$v_seccional_no_vendido[$i] = $v_seccional_cantidad[$i] - $v_seccional_venta[$i];



$trama .= $v_seccional_cod[$i];
$trama .= "$".$v_seccional_nombre[$i];
$trama .= "$".$v_seccional_cantidad[$i];
$trama .= "$".$v_seccional_venta[$i];
$trama .= "$".$v_seccional_no_vendido[$i];

if ($v_seccional_fecha[$i] != '') {
$trama .= "$".$v_seccional_fecha[$i];
}else{
$trama .= "$ "."N/A";
}

$i++;
}

return $trama;
mysqli_close($conn); 	

/////////////////////////////////////////////

}












/////////////////////////////////////////////////////

function ws_no_asignado($id_sorteo){


$conn = mysqli_connect('localhost', 'root', 'pumalxqw1121',"cidesoft_banco")
 or die('Error: ' . mysqli_error($conn));


$info_sorteo = mysqli_query($conn, "SELECT *  FROM sorteos_menores WHERE id = '$id_sorteo' limit 1");
$value = mysqli_fetch_object($info_sorteo);
$precio_unitario = $value->precio_unitario;
$precio_unitario = $precio_unitario * 100;
$comision = $value->comision;
$porcentaje_comision=$comision/100;
$total_cantidad = 0;
$total_vendido = 0;
$total_no_vendido = 0;
$sorteo = $value->no_sorteo_men;
$descripcion = $value->descripcion_sorteo_men;
$fecha_sorteo = $value->fecha_sorteo;


date_default_timezone_set("America/Tegucigalpa");

$date = date("Y-m-d h:i:s a");


$seccionales = mysqli_query($conn, "SELECT a.cod_seccional, a.nombre, b.municipio, c.descripcion as depto FROM  `seccionales` as a INNER JOIN geocodigos as b INNER JOIN dptos as c ON a.geocodigo_id = b.id AND b.dpto_id = c.id WHERE a.id NOT IN (SELECT id_seccional FROM menor_reservas_seccionales_bolsas WHERE sorteos_menores_id = '$id_sorteo' )  ");

if ($seccionales === false) {
echo mysqli_error($conn);
}

$trama = "";

$i = 0;
while ($reg_seccionales = mysqli_fetch_array($seccionales)) {

if ($i != 0) {
$trama .= "#";
}

$trama .= $reg_seccionales['depto'];
$trama .= "$".$reg_seccionales['municipio'];
$trama .= "$".$reg_seccionales['cod_seccional'];
$trama .= "$".$reg_seccionales['nombre'];

$i++;
}



return $trama;
mysqli_close($conn); 	

/////////////////////////////////////////////


}


/////////////////////////////////////////////








$servicio -> service(file_get_contents("php://input"));

?>