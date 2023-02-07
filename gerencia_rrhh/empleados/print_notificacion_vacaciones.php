<?php
require '../../template/header.php';

$identidad = hex2bin($_GET['id']);

$c_periodos = mysqli_query($conn, "SELECT a.id, a.cod_empleado, a.identidad, a.tipo_contratacion, a.id_parametro_vacaciones, a.periodo_inicial, a.periodo_final, a.antiguedad_years, a.dias_otorgados, a.estado, a.fecha_registro , (SELECT COUNT(id) FROM rr_hh_vacaciones_tomadas WHERE id_periodo = a.id AND estado = 'A' ) as dias_tomados  FROM rr_hh_periodos_vacaciones as a WHERE identidad = '$identidad' AND estado = 'A'  ORDER BY periodo_inicial ASC ");

?>

<br>

<table class="table table-bordered">
<thead>
	<tr>
	<td>PERIODO</td>
	<td>DIAS GANADOS</td>
	<td>DIAS TOMADOS</td>
	<td>DIAS DISPONIBLES</td>
	</tr>
</thead>
<tbody>
<?php

$date_now = date('Y-m-d');

while ($r_periodos = mysqli_fetch_array($c_periodos)) {

	$id_periodo = $r_periodos['id'];
	$cod_empleado = $r_periodos['cod_empleado'];
	$identidad = $r_periodos['identidad'];
	$tipo_contratacion = $r_periodos['tipo_contratacion'];
	$id_parametro_vacaciones = $r_periodos['id_parametro_vacaciones'];
	$periodo_inicial = $r_periodos['periodo_inicial'];
	$periodo_final = $r_periodos['periodo_final'];
	$antiguedad_years = $r_periodos['antiguedad_years'];
	$dias_vacaciones_periodo = $r_periodos['dias_otorgados'];
	$dias_tomados = $r_periodos['dias_tomados'];

	if ($date_now < $periodo_final) {

		$date_now = new DateTime($date_now);
		$periodo_final = new DateTime($periodo_final);

		$interval = $date_now->diff($periodo_final);
		$years_inicio_fin = $interval->format('%y');
		$meses_inicio_fin = $interval->format('%m');
		$dias_inicio_fin = $interval->format('%d');
		$dias_laborados_periodo = $years_inicio_fin * 360 + $meses_inicio_fin * 30 + $dias_inicio_fin;
		$porcentaje_year = $dias_laborados_periodo / 360;
		$dias_vacaciones_periodo = number_format($dias_vacaciones_periodo * $porcentaje_year);

		$antiguedad_years += $porcentaje_year - 1;
	}

	$dias_disponibles = $r_periodos['dias_otorgados'] - $r_periodos['dias_tomados'];

	echo "<tr>";
	echo "<td>" . $r_periodos['periodo_inicial'] . " | " . $r_periodos['periodo_final'] . "</td>";
	echo "<td>" . $r_periodos['dias_otorgados'] . "</td>";
	echo "<td>" . $r_periodos['dias_tomados'] . "</td>";
	echo "<td>" . $dias_disponibles . "</td>";
	echo "</tr>";

	if ($r_periodos['dias_tomados'] > 0) {

		$c_historico = mysqli_query($conn, "SELECT * FROM rr_hh_vacaciones_tomadas WHERE id_periodo = '$id_periodo' AND estado = 'A' ORDER BY fecha ASC ");

		$i = 0;
		$v_historico[$i] = array();
		while ($r_historico = mysqli_fetch_array($c_historico)) {
			array_push($v_historico[$i], array('dia_solicitado' => $r_historico['fecha'], 'fecha_solicitud' => $r_historico['fecha_registro']));

		}

		echo "<pre>";
		print_r($v_historico);
		echo "</pre>";

		echo "<tr>";
		echo "<td colspan = '4'>";

		echo "<table class = 'table table-bordered'>";
		echo "<tr><td> INICIO </td><td> FINAL </td><td>FECHA SOLICITUD</td></tr>";

		echo "</table>";

		echo "</td>";
		echo "</tr>";

	}

}
?>
</tbody>
</table>