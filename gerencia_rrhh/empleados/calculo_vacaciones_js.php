<?php

require '../../conexion.php';

$accion = $_GET['accion'];

if ($accion == 1) {

	$f_i = $_GET['f_i'];
	$f_f = $_GET['f_f'];

	$fecha_i = $_GET['f_i'];
	$fecha_f = $_GET['f_f'];

	$bandera = 1;
	$id_periodo = $_GET['id_periodo'];
	$identidad = $_GET['identidad'];
	$c_dias_tomados = mysqli_query($conn, "SELECT * FROM rr_hh_vacaciones_tomadas WHERE identidad = '$identidad' AND estado = 'A' ");

	while ($reg_dias_tomados = mysqli_fetch_array($c_dias_tomados)) {
		$v_fechas_tomadas[$reg_dias_tomados['fecha']] = $reg_dias_tomados['fecha'];
	}

	while ($fecha_i <= $fecha_f) {

		if (isset($v_fechas_tomadas[$fecha_i])) {
			echo "<div class = 'alert alert-danger'>La fecha " . $fecha_i . " ya fue registrada como tomada.</div>";
			$bandera = 0;
		}

		$fecha_i = date('Y-m-d', strtotime($fecha_i . ' + 1 days'));
	}

	if ($f_i === $f_f) {
		$tt_days = 1;
	} else {
		$f_f = date('Y-m-d', strtotime($f_f . ' + 1 days'));

		$f_i = new DateTime($f_i);
		$f_f = new DateTime($f_f);

		$tt_days = $f_f->diff($f_i)->format('%a');
	}

	$dias_disponibles = $_GET['dias_disponibles'];

	if ($tt_days > $dias_disponibles) {

		echo "<div class = 'alert alert-danger'>Solo cuenta con " . $dias_disponibles . " dias disponibles en este periodo, por favor cambie las fechas de los dias a tomar.</div>";

		?>
		<script type="text/javascript">
			document.getElementById('total_dias').value = "<?php echo $tt_days; ?>";
			document.getElementById('btn_agregar_vacaciones').disabled = true;
		</script>
		<?php

			} elseif ($bandera == 0) {

				?>
		<script type="text/javascript">
			document.getElementById('total_dias').value = "<?php echo $tt_days; ?>";
			document.getElementById('btn_agregar_vacaciones').disabled = true;
		</script>
		<?php

			} else {

				?>
		<script type="text/javascript">
			document.getElementById('total_dias').value = "<?php echo $tt_days; ?>";
			document.getElementById('btn_agregar_vacaciones').disabled = false;
		</script>
		<?php

	}

}

if ($accion == 2) {

	ob_start();
	session_start();

	$id_periodo = $_GET['id_periodo'];
	$identidad = $_GET['identidad_o'];
	$identidad = str_replace("-", "", $identidad);

	$fecha_i = $_GET['f_i'];
	$fecha_f = $_GET['f_f'];
	$id_u = $_SESSION['id_usuario'];

	$c_dias_tomados = mysqli_query($conn, "SELECT * FROM rr_hh_vacaciones_tomadas WHERE identidad = '$identidad' AND estado = 'A' ");

	while ($reg_dias_tomados = mysqli_fetch_array($c_dias_tomados)) {
		$v_fechas_tomadas[$reg_dias_tomados['fecha']] = $reg_dias_tomados['fecha'];
	}

	$bandera = 1;
	while ($fecha_i <= $fecha_f) {

		if (isset($v_fechas_tomadas[$fecha_i])) {
			echo "<div class = 'alert alert-danger'>La fecha " . $fecha_i . " ya fue registrada como tomada.</div>";
			$bandera = 0;
		} else {
			$registro_periodo = mysqli_query($conn, "INSERT INTO rr_hh_vacaciones_tomadas (id_periodo, fecha, id_usuario, identidad) VALUES ('$id_periodo','$fecha_i','$id_u', '$identidad') ");

			if ($registro_periodo == null) {
				echo mysqli_error($conn);
				$bandera = 0;
			}

		}

		$fecha_i = date('Y-m-d', strtotime($fecha_i . ' + 1 days'));
	}

	if ($bandera == 0) {
		echo "<div class = 'alert alert-danger'>Al menos uno de los dias que intenta registrar no pudo guardarse, por favor verifique el historico de dias tomados e intente nuevamente.</div>";
	} else {





		?>
		<script type="text/javascript">

	identidad_o = document.getElementById('identidad_o').value;
	token = Math.random();

		$('#modal-reg_vac_tomadas').modal('hide');
		swal("","Vacaciones registradas correctamente","success");

		consulta = "reload_tabla_periodos.php?token="+token+"&identidad_o="+identidad_o;
		$("#div_tabla_periodos").load(consulta);


		</script>
		<?php

	}

}

if ($accion == 3) {

	$id_periodo = $_GET['id_periodo'];
	$c_historico = mysqli_query($conn, "SELECT * FROM rr_hh_vacaciones_tomadas WHERE id_periodo = '$id_periodo' AND estado = 'A' ORDER BY fecha ASC ");
	$i = 1;

	echo "<table class = 'table table-bordered' >";
	echo "<tr><th>#</th><th>Dia Solicitado</th><th>Fecha Solicitud</th><th width = '20%'>Acccion</th></tr>";

	while ($reg_historico = mysqli_fetch_array($c_historico)) {
		$id_dia_tomado = $reg_historico['id'];

		echo "<tr>";
		echo "<td>" . $i . "</td>";
		echo "<td>" . $reg_historico['fecha'] . "</td>";
		echo "<td>" . $reg_historico['fecha_registro'] . "</td>";
		?>
		<td><button type='button' class = 'btn btn-danger fa fa-times-circle' onclick="cancelar_dia_tomado('<?php echo $id_dia_tomado; ?>', '<?php echo $id_periodo; ?>')"></button></td>
		<?php

		echo "</tr>";
		$i++;
	}
	$i--;

	echo "</table>";

}








if ($accion == 4) {
	$id_dia_tomado = $_GET['id_dia_tomado'];
	$id_periodo = $_GET['id_periodo'];

	if (mysqli_query($conn, "UPDATE rr_hh_vacaciones_tomadas SET estado = 'C' WHERE id = '$id_dia_tomado' LIMIT 1 ") === TRUE) {

		?>
<script type="text/javascript">

	cargar_datos_historico("<?php echo $id_periodo; ?>");

	identidad_o = document.getElementById('identidad_o').value;
	token = Math.random();

	consulta = "reload_tabla_periodos.php?token="+token+"&identidad_o="+identidad_o;
	$("#div_tabla_periodos").load(consulta);

</script>

<?php

	} else {

		echo mysqli_error($conn);

	}

}

?>