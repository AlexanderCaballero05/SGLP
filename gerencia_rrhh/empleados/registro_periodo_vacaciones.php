<?php
date_default_timezone_set("America/Tegucigalpa");

//$conn = mysqli_connect('localhost', 'root', 'pumalxqw1121', 'pani_2022') or die('No se pudo conectar: ' . mysqli_error($conn));
$conn = mysqli_connect('192.168.15.248:3306', 'SVR_APP', 'softlotpani**', 'pani') or die('No se pudo conectar: ' . mysqli_error($conn));

$c_parametros_vacaciones = mysqli_query($conn, "SELECT * FROM rr_hh_mto_vacaciones ");

$i = 0;
while ($r_parametros_vacaciones = mysqli_fetch_array($c_parametros_vacaciones)) {
	$v_parametros[$i] = $r_parametros_vacaciones;
	$i++;
}

$date = date("Y-m-d");
$v_date = explode("-", $date);
$year = $v_date[0];
$month = $v_date[1];
$day = $v_date[2];

//$c_empleados_nuevo_periodo = mysqli_query($conn, "SELECT * FROM rr_hh_tipo_contrato_salarios WHERE MONTH(fecha_inicio) = '$month' AND DAY(fecha_inicio) = '$day' AND status = 'A'  ");
$c_empleados_nuevo_periodo = mysqli_query($conn, "SELECT * FROM rr_hh_tipo_contrato_salarios WHERE  status = 'A'  ");

echo "<table border = '1'>";
echo "<tr>";
echo "<th>NO EMPLEADO</th>";
echo "<th>IDENTIDAD</th>";
echo "<th>FECHA INGRESO</th>";
echo "<th>PERIODO INICIAL</th>";
echo "<th>PERIODO FINAL</th>";
echo "<th>AÑOS ANTIGUEDAD</th>";
echo "<th>DIAS CORRESPONDEN</th>";
echo "<th>TIPO CONTRATO</th>";
echo "</tr>";

while ($reg_empleados_nuevo_periodo = mysqli_fetch_array($c_empleados_nuevo_periodo)) {

	$date = date("Y-m-d");
	$v_date = explode("-", $date);
	$year = $v_date[0];
	$month = $v_date[1];
	$day = $v_date[2];

	$identidad = $reg_empleados_nuevo_periodo['identidad'];
	$no_empleado = $reg_empleados_nuevo_periodo['cod_empleado'];
	$tipo_contratacion = $reg_empleados_nuevo_periodo['tipo_contratacion'];
	$fecha_inicio = $reg_empleados_nuevo_periodo['fecha_inicio'];

	$v_fecha_inicio = explode("-", $fecha_inicio);
	$year_inicio = $v_fecha_inicio[0];
	$month_inicio = $v_fecha_inicio[1];
	$day_inicio = $v_fecha_inicio[2];

	//$c_periodos_registrados = mysqli_query($conn, "SELECT * FROM rr_hh_periodos_vacaciones WHERE identidad = '$identidad' ORDER BY periodo_inicial DESC LIMIT 1 ");

	if ($month < $month_inicio) {
		$year--;

	} elseif ($month == $month_inicio) {
		if ($day < $day_inicio) {
			$year--;
		}
	}

	$diff_year_actual_inicio = $year - $year_inicio;
	if ($diff_year_actual_inicio > 2) {
		$year_inicio = $year - 2;
	}

	while ($year_inicio <= $year) {

		$periodo_inicial = $year_inicio . "-" . $month_inicio . "-" . $day_inicio;
		$year_final = $year_inicio + 1;
		$periodo_final = $year_final . "-" . $month_inicio . "-" . $day_inicio;

		$contratacion_inicio = new DateTime($fecha_inicio);
		$contratacion_fin = new DateTime($periodo_final);

		$interval = $contratacion_inicio->diff($contratacion_fin);
		$years_inicio_fin = $interval->format('%y');
		$meses_inicio_fin = $interval->format('%m');
		$dias_inicio_fin = $interval->format('%d');

		$dias_laborados = $years_inicio_fin * 360 + $meses_inicio_fin * 30 + $dias_inicio_fin;

		$dias_vacaciones_periodo = 0;
		$i = 0;
		while (isset($v_parametros[$i])) {
			if ($v_parametros[$i]['tipo_contratacion'] == $tipo_contratacion) {

				$min_dias_parametro = $v_parametros[$i]['de_year'] * 360 + $v_parametros[$i]['de_mes'] * 30 + $v_parametros[$i]['de_dia'];

				if ($v_parametros[$i]['a_year'] == "") {
					$max_dias_parametro = 99999999999;
				} else {
					$max_dias_parametro = $v_parametros[$i]['a_year'] * 360 + $v_parametros[$i]['a_mes'] * 30 + $v_parametros[$i]['a_dia'];
				}

				if ($dias_laborados >= $min_dias_parametro AND $dias_laborados <= $max_dias_parametro) {

					$min_vigencia_parametro = $v_parametros[$i]['periodo_inicial_vigencia'];
					if ($v_parametros[$i]['periodo_final_vigencia'] == "") {
						$max_vigencia_parametro = "9999-12-31";
					} else {
						$max_vigencia_parametro = $v_parametros[$i]['periodo_final_vigencia'];
					}

					if ($periodo_final >= $min_vigencia_parametro AND $periodo_final <= $max_vigencia_parametro) {
						$dias_vacaciones_periodo = $v_parametros[$i]['dias_vacaciones'];
						$id_parametro = $v_parametros[$i]['id'];
						$i = -1000;
					}

				}

			}

			$i++;
		}

		$antiguedad_years = $dias_laborados / 360;

		echo "<tr>";
		echo "<td>" . $no_empleado . "</td>";
		echo "<td>" . $identidad . "</td>";
		echo "<td>" . $fecha_inicio . "</td>";
		echo "<td>" . $periodo_inicial . "</td>";
		echo "<td>" . $periodo_final . "</td>";
		echo "<td>" . $dias_laborados / 360 . "</td>";
		echo "<td>" . $dias_vacaciones_periodo . "</td>";
		echo "<td>" . $tipo_contratacion . "</td>";
		echo "</tr>";

		mysqli_query($conn, "INSERT INTO rr_hh_periodos_vacaciones (cod_empleado, identidad, tipo_contratacion, id_parametro_vacaciones, periodo_inicial, periodo_final, antiguedad_years, dias_otorgados, estado) VALUES ('$no_empleado', '$identidad', '$tipo_contratacion', '$id_parametro', '$periodo_inicial', '$periodo_final', '$antiguedad_years', '$dias_vacaciones_periodo', 'A') ");

mysqli_error($conn);

		$year_inicio++;
	}

	echo "<tr><td colspan = '7'><br></td></tr>";

}

echo "</table>";

?>