<?php

$c_years = mysqli_query($conn, "SELECT YEAR(fecha_venta) as year FROM transaccional_ventas_general WHERE YEAR(fecha_venta) >= 2017 GROUP BY YEAR(fecha_venta)  ORDER BY YEAR(fecha_venta) DESC  ");
$c_years2 = mysqli_query($conn, "SELECT YEAR(fecha_venta) as year FROM transaccional_ventas_general WHERE YEAR(fecha_venta) >= 2017 GROUP BY YEAR(fecha_venta) ORDER BY YEAR(fecha_venta) DESC  ");

$c_sorteos = mysqli_query($conn, "SELECT a.id_sorteo as sorteo, b.fecha_sorteo  FROM transaccional_ventas_general as a INNER JOIN sorteos_mayores as b ON a.id_sorteo = b.id WHERE cod_producto = '1' GROUP BY a.id_sorteo ORDER BY a.id_sorteo DESC  ");

$c_sorteos2 = mysqli_query($conn, "SELECT a.id_sorteo as sorteo, b.fecha_sorteo  FROM transaccional_ventas_general as a INNER JOIN sorteos_mayores as b ON a.id_sorteo = b.id WHERE cod_producto = '1' GROUP BY a.id_sorteo ORDER BY a.id_sorteo DESC   ");

$c_entidades = mysqli_query($conn, "SELECT id_entidad, nombre_empresa as nombre_entidad FROM empresas ");

$min_max_sorteo = mysqli_query($conn, "SELECT MIN(id_sorteo) as min_sorteo, MAX(id_sorteo) as max_sorteo FROM transaccional_ventas_general WHERE cod_producto = '1'  ");
$ob_min_max_sorteo = mysqli_fetch_object($min_max_sorteo);
$min_sorteo = $ob_min_max_sorteo->min_sorteo;
$max_sorteo = $ob_min_max_sorteo->max_sorteo;

$year_actual = date('Y');
$year_anterior = $year_actual - 1;

?>