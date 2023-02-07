<?php


date_default_timezone_set("America/Tegucigalpa");


require('../../conexion.php');

$id_sorteo = 3320;

$c_vendedores = mysqli_query($conn, "SELECT identidad_comprador as identidad, nombre_comprador, SUM(cantidad)/10 as numero_bolsas, COUNT(identidad_comprador) as sorteos_activos FROM  ( SELECT identidad_comprador, nombre_comprador,SUM(cantidad) as cantidad, id_sorteo FROM transaccional_ventas_general WHERE cod_producto = 3 AND id_sorteo BETWEEN 3255 AND 3264 AND estado_venta = 'APROBADO' GROUP BY identidad_comprador, id_sorteo ORDER BY  identidad_comprador, id_sorteo ASC) as tbl_a  GROUP BY identidad_comprador HAVING COUNT(identidad_comprador) > 9");


while ($reg_vendedores = mysqli_fetch_array($c_vendedores)) {
	$v_vendedores[$reg_vendedores['identidad']]['compromiso'] = round($reg_vendedores['numero_bolsas']) - 1;
	$v_vendedores[$reg_vendedores['identidad']]['nombre_comprador'] = $reg_vendedores['nombre_comprador'];
	$v_vendedores[$reg_vendedores['identidad']]['identidad_comprador'] = $reg_vendedores['identidad'];
}

$c_vendedores_venta = mysqli_query($conn, "SELECT identidad_comprador, SUM(cantidad) as total_comprado FROM transaccional_ventas_general WHERE cod_producto = '3' AND id_sorteo = '$id_sorteo' AND estado_venta = 'APROBADO' GROUP BY identidad_comprador ");

while ($reg_vendedores_venta = mysqli_fetch_array($c_vendedores_venta)) {

	if (isset($v_vendedores[$reg_vendedores_venta['identidad_comprador']])) {
		$v_vendedores[$reg_vendedores_venta['identidad_comprador']]['total_comprado'] = $reg_vendedores_venta['total_comprado'];
	}

}

echo "REGISTRANDO....";

echo "<pre>";
echo $v_vendedores;
echo "</pre>";

echo "REGISTRADO";

foreach ($v_vendedores as $vendedor) {

if (isset($vendedor['total_comprado'])) {

if ($vendedor['total_comprado'] >= $vendedor['compromiso']) {

$i = $vendedor['compromiso'];
$media_compra = $vendedor['compromiso'];
$ultima_compra = $vendedor['total_comprado'];

while ($i <= $vendedor['total_comprado']) {
$identidad_comprador = $vendedor['identidad_comprador'];
$nombre_comprador = $vendedor['nombre_comprador'];

mysqli_query($conn, "INSERT INTO sorteos_menores_incentivos_tickets (identidad_comprador, nombre_comprador, id_sorteo, media_compra, ultima_compra) VALUES ('$identidad_comprador', '$nombre_comprador', '$id_sorteo', '$media_compra', '$ultima_compra') ");

$i++;
}
	
}

}

}


?>