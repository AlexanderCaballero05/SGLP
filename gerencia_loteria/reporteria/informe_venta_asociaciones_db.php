<?php

require "../../conexion.php";

$c_vendedores = mysqli_query($conn, "SELECT LPAD(identidad, 13, '0') as id, nombre FROM vendedores");

$i = 0;
while ($r_vendedores = mysqli_fetch_array($c_vendedores)) {
	$v_vendedores[$r_vendedores['id']] = $r_vendedores['nombre'];
	$i++;
}

$filtro = $_GET['filtro'];

if ($filtro == 1) {

	$id_sorteo = $_GET['id_s'];
	$asoc = $_GET['asocia'];

	if ($asoc != "t") {
		$consulta_ventas = mysqli_query($conn, "SELECT b.nombre_asociacion , SUM(a.cantidad) as cantidad, a.asociacion_comprador, COUNT(DISTINCT(a.identidad_comprador))  as activo FROM transaccional_ventas_general as a INNER JOIN asociaciones_vendedores as b ON a.asociacion_comprador = b.codigo_asociacion  WHERE a.id_sorteo = '$id_sorteo' AND a.asociacion_comprador = '$asoc' AND a.cod_producto = 3 AND a.estado_venta = 'APROBADO' GROUP BY a.asociacion_comprador ");
	} else {
		$consulta_ventas = mysqli_query($conn, "SELECT b.nombre_asociacion , SUM(a.cantidad) as cantidad, a.asociacion_comprador, COUNT(DISTINCT(a.identidad_comprador))  as activo FROM transaccional_ventas_general as a INNER JOIN asociaciones_vendedores as b ON a.asociacion_comprador = b.codigo_asociacion  WHERE a.id_sorteo = '$id_sorteo' AND a.cod_producto = 3 AND a.estado_venta = 'APROBADO'   GROUP BY a.asociacion_comprador ");
	}

	if ($consulta_ventas === false) {
		echo mysqli_error();
	}

	echo "<table class = 'table table-bordered' id = 'table_format' >";
	echo "<tr>";
	echo "<th>ASOCIACION</th>";
	echo "<th>CANTIDAD COMPRADA</th>";
	echo "<th># VENDEDORES ACTIVOS</th>";
	echo "</tr>";

	$tt = 0;
	while ($reg_ventas = mysqli_fetch_array($consulta_ventas)) {
		echo "<tr>";
		echo "<td>" . $reg_ventas['nombre_asociacion'] . "</td>";
		echo "<td>" . number_format($reg_ventas['cantidad']) . "</td>";
		echo "<td>" . $reg_ventas['activo'] . "</td>";
		echo "</tr>";

		$tt += $reg_ventas['cantidad'];
	}

	echo "<tr><th>TOTAL </th>";
	echo "<th colspan = '2'>" . number_format($tt) . "</th></tr>";
	echo "</table>";

	if ($asoc == "t") {

		$consulta_ventas_a = mysqli_query($conn, " SELECT SUM(a.cantidad) as cantidad, LPAD( a.identidad_comprador, 13, '0') as identidad_comprador, a.nombre_comprador, a.asociacion_comprador FROM transaccional_ventas_general as a WHERE a.cod_producto = 3 AND a.estado_venta = 'APROBADO' AND a.id_sorteo = '$id_sorteo' AND a.asociacion_comprador = 'A' GROUP BY a.identidad_comprador  ORDER BY a.identidad_comprador ");

		$consulta_ventas_b = mysqli_query($conn, " SELECT SUM(a.cantidad) as cantidad, LPAD( a.identidad_comprador, 13, '0') as identidad_comprador, a.nombre_comprador, a.asociacion_comprador FROM transaccional_ventas_general as a  WHERE a.cod_producto = 3 AND a.estado_venta = 'APROBADO' AND a.id_sorteo = '$id_sorteo' AND a.asociacion_comprador = 'B' GROUP BY a.identidad_comprador  ORDER BY a.identidad_comprador ");

		$consulta_ventas_c = mysqli_query($conn, " SELECT SUM(a.cantidad) as cantidad, a.identidad_comprador, a.nombre_comprador, a.asociacion_comprador FROM transaccional_ventas_general as a WHERE a.cod_producto = 3 AND a.estado_venta = 'APROBADO' AND a.id_sorteo = '$id_sorteo' AND a.asociacion_comprador = 'C'   GROUP BY a.identidad_comprador  ORDER BY a.identidad_comprador ");

	} elseif ($asoc == "A") {

		$consulta_ventas_a = mysqli_query($conn, " SELECT SUM(a.cantidad) as cantidad, LPAD( a.identidad_comprador, 13, '0') as identidad_comprador, a.nombre_comprador, a.asociacion_comprador FROM transaccional_ventas_general as a WHERE a.cod_producto = 3 AND a.estado_venta = 'APROBADO' AND a.id_sorteo = '$id_sorteo' AND a.asociacion_comprador = 'A' GROUP BY a.identidad_comprador  ORDER BY a.identidad_comprador ");

	} elseif ($asoc == "B") {
		$consulta_ventas_b = mysqli_query($conn, " SELECT SUM(a.cantidad) as cantidad, LPAD( a.identidad_comprador, 13, '0') as identidad_comprador, a.nombre_comprador, a.asociacion_comprador FROM transaccional_ventas_general as a  WHERE a.cod_producto = 3 AND a.estado_venta = 'APROBADO' AND a.id_sorteo = '$id_sorteo' AND a.asociacion_comprador = 'B' GROUP BY a.identidad_comprador  ORDER BY a.identidad_comprador ");

	} elseif ($asoc == "C") {
		$consulta_ventas_c = mysqli_query($conn, " SELECT SUM(a.cantidad) as cantidad, a.identidad_comprador, a.nombre_comprador, a.asociacion_comprador FROM transaccional_ventas_general as a WHERE a.cod_producto = 3 AND a.estado_venta = 'APROBADO' AND a.id_sorteo = '$id_sorteo' AND a.asociacion_comprador = 'C'   GROUP BY a.identidad_comprador  ORDER BY a.identidad_comprador ");

	}

	echo "<table class = 'table table-bordered' >";
	echo "<tr>";
	echo "<th>Identidad</th>";
	echo "<th>Nombre</th>";
	echo "<th>Cantidad</th>";
	echo "<th>Asociacion</th>";
	echo "</tr>";

	$conteo_vendido = 0;

	if (isset($consulta_ventas_a)) {

		while ($reg_ventas_a = mysqli_fetch_array($consulta_ventas_a)) {

			if (isset($v_vendedores[$reg_ventas_a['identidad_comprador']])) {
				$nombre_comprador = $v_vendedores[$reg_ventas_a['identidad_comprador']];
			} else {
				$nombre_comprador = "";
			}

			echo "<tr>";
			echo "<td>" . $reg_ventas_a['identidad_comprador'] . "</td>";
			echo "<td>" . $nombre_comprador . "</td>";
			echo "<td>" . number_format($reg_ventas_a['cantidad']) . "</td>";
			echo "<td>ANAVELH</td>";
			echo "</tr>";

			$conteo_vendido += $reg_ventas_a['cantidad'];
		}

	}

	if (isset($consulta_ventas_b)) {

		while ($reg_ventas_b = mysqli_fetch_array($consulta_ventas_b)) {

			if (isset($v_vendedores[$reg_ventas_b['identidad_comprador']])) {
				$nombre_comprador = $v_vendedores[$reg_ventas_b['identidad_comprador']];
			} else {
				$nombre_comprador = "";
			}

			echo "<tr>";
			echo "<td>" . $reg_ventas_b['identidad_comprador'] . "</td>";
			echo "<td>" . $nombre_comprador . "</td>";
			echo "<td>" . number_format($reg_ventas_b['cantidad']) . "</td>";
			echo "<td>ANVLUH</td>";
			echo "</tr>";

			$conteo_vendido += $reg_ventas_b['cantidad'];

		}

	}

	if (isset($consulta_ventas_c)) {

		while ($reg_ventas_c = mysqli_fetch_array($consulta_ventas_c)) {

			echo "<tr>";
			echo "<td>" . $reg_ventas_c['identidad_comprador'] . "</td>";
			echo "<td>" . $reg_ventas_c['nombre_comprador'] . "</td>";
			echo "<td>" . number_format($reg_ventas_c['cantidad']) . "</td>";
			echo "<td>SIN ASOCIACION</td>";
			echo "</tr>";

			$conteo_vendido += $reg_ventas_c['cantidad'];

		}

	}

	echo "<tr><th colspan = '2'>TOTALES</th><th colspan = '2'>" . number_format($conteo_vendido) . "</th></tr>";
	echo "</table>";
	echo "<br>";
	echo "<button type = 'submit' class = 'btn btn-success' name = 'excel' value = '" . $id_sorteo . "'>GENERAR EXCEL</button>";

}

?>