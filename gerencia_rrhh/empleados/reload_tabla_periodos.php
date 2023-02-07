<?php

require '../../conexion.php';

$identidad = $_GET['identidad_o'];

$identidad = str_replace("-", "", $identidad);

$c_periodos = mysqli_query($conn, "SELECT a.id, a.cod_empleado, a.identidad, a.tipo_contratacion, a.id_parametro_vacaciones, a.periodo_inicial, a.periodo_final, a.antiguedad_years, a.dias_otorgados, a.estado, a.fecha_registro , (SELECT COUNT(id) FROM rr_hh_vacaciones_tomadas WHERE id_periodo = a.id AND estado = 'A' ) as dias_tomados FROM rr_hh_periodos_vacaciones as a WHERE identidad = '$identidad' AND estado = 'A' ORDER BY periodo_inicial ASC ");

if (mysqli_num_rows($c_periodos) > 0) {

	echo "<table class = 'table table-bordered'>";
	echo "<tr>";
	echo "<th>PERIODO INICIAL</th>";
	echo "<th>PERIODO FINAL</th>";
	echo "<th>AÑOS ANTIGUEDAD</th>";
	echo "<th width = '15%'>DIAS GANADOS</th>";
	echo "<th width = '15%'>DIAS TOMADOS</th>";
	echo "<th width = '15%'>DIAS DISPONIBLES</th>";
	echo "</tr>";

	$date_now = date('Y-m-d');
	$bandera_periodo_disponible = 1;
	$bandera_periodo_activo = 0;

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

		echo "<tr>";
		echo "<td>" . $periodo_inicial . "</td>";
		echo "<td>" . $periodo_final . "</td>";

		if ($date_now < $periodo_final) {

			$date_now = new DateTime($date_now);
			$periodo_final = new DateTime($periodo_final);
			$periodo_inicial = new DateTime($periodo_inicial);

			$interval = $periodo_inicial->diff($date_now);
			$years_inicio_fin = $interval->format('%y');
			$meses_inicio_fin = $interval->format('%m');
			$dias_inicio_fin = $interval->format('%d');
			$dias_laborados_periodo = $years_inicio_fin * 360 + $meses_inicio_fin * 30 + $dias_inicio_fin;
			$porcentaje_year = $dias_laborados_periodo / 360;
			$dias_vacaciones_periodo = number_format($dias_vacaciones_periodo * $porcentaje_year);

			$antiguedad_years += $porcentaje_year - 1;
		}

		echo "<td>" . number_format($antiguedad_years, 2) . "</td>";

		echo "<td><input id = 'dias_periodo_" . $id_periodo . "' name = 'dias_periodo_" . $id_periodo . "' class = 'form-control' type = 'text' value = '" . $dias_vacaciones_periodo . "' readonly></td>";
		$dias_disponibles = $dias_vacaciones_periodo - $dias_tomados;

		if ($dias_disponibles == 0 OR $bandera_periodo_activo == 1) {
			$bandera_periodo_disponible = 0;
		} elseif ($dias_disponibles > 0 AND $bandera_periodo_activo == 0) {
			$bandera_periodo_disponible = 1;
			$bandera_periodo_activo = 1;
		} elseif ($bandera_periodo_activo == 1) {
			$bandera_periodo_disponible = 1;
		}

		if ($bandera_periodo_disponible == 1) {

			?>
			<td>
			<div class = 'input-group'>
			<input type = 'text' id = 'dias_tomados_<?php echo $id_periodo; ?>' name = 'dias_tomados_<?php echo $id_periodo; ?>' class = 'form-control' value = '<?php echo $dias_tomados; ?>' readonly>
			<div class = input-group-append><span style="margin-right: 2px" data-toggle='modal' href='#modal-reg_vac_tomadas' onclick="cargar_datos_modal('<?php echo $id_periodo; ?>', '<?php echo $dias_disponibles; ?>')" class = 'btn btn-success'><i class = 'fa fa-plus'></i></span>
			<span data-toggle='modal' href='#modal_historico' onclick="cargar_datos_historico('<?php echo $id_periodo; ?>')" class = 'btn btn-info'><i class = 'fa fa-eye'></i></span>
			</div>
			</div>
			</td>
			<?php

		} else {
			?>
			<td>
			<div class = 'input-group'>
			<input type = 'text' id = 'dias_tomados_<?php echo $id_periodo; ?>' name = 'dias_tomados_<?php echo $id_periodo; ?>' class = 'form-control' value = '<?php echo $dias_tomados; ?>' readonly>
			<div class = input-group-append><button style="margin-right: 2px" data-toggle='modal'  class = 'btn btn-success' disabled><i class = 'fa fa-plus'></i></button>
			<span data-toggle='modal' href='#modal_historico' onclick="cargar_datos_historico('<?php echo $id_periodo; ?>')" class = 'btn btn-info'><i class = 'fa fa-eye'></i></span>
			</div>
			</div>
			</td>
			<?php

		}

		echo "<td><input id = 'dias_disponibles_" . $id_periodo . "' name = 'dias_disponibles_" . $id_periodo . "' class = 'form-control' type = 'text' value = '" . $dias_disponibles . "' readonly></td>";
		echo "</tr>";

	}

	echo "</table>";


} else {
	echo "<div class = 'alert alert-danger'>No se pudo encontrar el empleado, por favor verifique el numero de identidad ingresado.</div>";
}




$c_historico_now = mysqli_query($conn, "SELECT * FROM rr_hh_vacaciones_tomadas WHERE  estado = 'A' AND identidad = '$identidad'   ORDER BY fecha ASC ");

$mockData = [];
while ($reg_historico = mysqli_fetch_array($c_historico_now)) {
	$desc = ["time" => $reg_historico['fecha'], "cls" => "bg-green-alt", "desc" => "Vacaciones"];
	array_push($mockData, $desc);
}

$mockDataJSON = json_encode($mockData);


?>


<script >

var mockDataJSON = <?php echo $mockDataJSON; ?>;
loadCalendar(mockDataJSON);


</script>