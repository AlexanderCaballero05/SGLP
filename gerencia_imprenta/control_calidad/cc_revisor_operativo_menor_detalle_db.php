<?php

if (isset($_POST['finalizar_reposicion'])) {

	$v_parametros = explode("_", $_POST['parametros_rango']);

	$id_sorteo = $v_parametros[0];
	$id_revisor = $v_parametros[1];
	$num_lista = $v_parametros[2];
	$revision = $v_parametros[3];

	date_default_timezone_set('America/Tegucigalpa');
	$fecha = date("Y-m-d H:i:s");

	if (mysqli_query($conn, "INSERT INTO cc_produccion_menor (id_sorteo,id_revisor,numero_revisor,numero_revision,estado_revisor,fecha_cierre_revisor) VALUES ('$id_sorteo','$id_revisor','$num_lista','$revision','FINALIZADA','$fecha') ") === true) {

		mysqli_query($conn, "UPDATE cc_revisores_sorteos_menores_control SET estado = 'APROBADO' WHERE id_sorteo = $id_sorteo AND estado != 'REPROBADO' AND id_revisor = $id_revisor  AND num_lista = $num_lista AND numero_revision = $revision  ");

		echo "<div class = 'alert alert-info'>
Se ha finalizado la reposicion " . $revision . "
</div>";

		?>
<script type="text/javascript">

swal({
title: "",
  text: "Reposicion finalizada correctamente.",
  type: "success"
})
.then(() => {
    window.location.href = './cc_revisor_operativo_menor.php';
});

</script>
<?php

	} else {

		echo "<div class = 'alert alert-danger'>
Error inesperado, por favor vuelva a intentarlo
</div>";

	}

}

if (isset($_POST['reprobar_rango'])) {

	$v_parametros = explode("_", $_POST['parametros_rango']);

	$id_sorteo = $v_parametros[0];
	$id_revisor = $v_parametros[1];
	$num_lista = $v_parametros[2];
	$revision = $v_parametros[3];
	$revision = $revision + 1;

	$i = 0;
	$acum = 1;
	$bandera_registro = 1;
	while (isset($_POST['decena_reprobado'][$i])) {
		$decena_reprobado = $_POST['decena_reprobado'][$i];
		$serie_reprobado = $_POST['serie_reprobado'][$i];
		$registro_reprobado = $_POST['registro_reprobado'][$i];

		$num_decena = $decena_reprobado[0];

		if (isset($_POST['re_reprobado' . $acum])) {
			$re_billete = "SI";
		} else {
			$re_billete = "NO";
		}

		$id_posteo = $_SESSION['id_usuario'];

		$c_validar_registro = mysqli_query($conn, "SELECT * FROM cc_revisores_sorteos_menores_control WHERE id_sorteo = '$id_sorteo' AND numero = '$num_decena' AND serie = '$serie_reprobado' ");

		if (mysqli_num_rows($c_validar_registro) == 0) {

			if (mysqli_query($conn, "INSERT INTO cc_revisores_sorteos_menores_control (id_sorteo,id_revisor,num_lista,numero_revision,numero,serie,registro,especial,id_posteo) VALUES ('$id_sorteo','$id_revisor','$num_lista','$revision','$num_decena','$serie_reprobado','$registro_reprobado','$re_billete','$id_posteo') ") === FALSE) {

				$bandera_registro = 0;

				echo mysqli_error($conn);
				echo "<div class = 'alert alert-danger'>Error: El numero " . $decena_reprobado . " con serie " . $serie_reprobado . " No pudo ser Ingresado para reposicion</div>";

			}

		} else {

			echo "<div class = 'alert alert-danger'>Error: La decena " . $decena_reprobado . " con serie " . $serie_reprobado . " Ya fue reprobado anteriormente.</div>";

		}

		$acum++;
		$i++;
	}

	if ($bandera_registro == 1) {

		$_SESSION['keep_decena'] = $decena_reprobado;

		echo "<div class = 'alert alert-info'>Numeros ingresados para reposicion correctamente.</div>";

	}

}

///////////////////////////////////////////////////////

//////////////// REPORBAR NUEVAMENTE //////////////////

///////////////////////////////////////////////////////

if (isset($_POST['reprobar_nuevamente'])) {

	$v_parametros = explode("_", $_POST['parametros_rango']);

	$id_sorteo = $v_parametros[0];
	$id_revisor = $v_parametros[1];
	$num_lista = $v_parametros[2];
	$revision = $v_parametros[3];
	$revision = $revision + 1;
	$revision_anterior = $revision - 1;

	$tt_billetes = $_POST['tt_revision'];

	$i = 0;

	while ($i <= $tt_billetes) {

		if (isset($_POST['check' . $i])) {

			$decimo = $_POST['decimo' . $i];
			$serie = $_POST['serie' . $i];
			$registro = $_POST['registro' . $i];
			$especial = $_POST['especial' . $i];
			$id_reprobacion = $_POST['id_reprobacion' . $i];

			$id_posteo = $_SESSION['id_usuario'];

			$c_validar_registro = mysqli_query($conn, "SELECT * FROM cc_revisores_sorteos_menores_control WHERE id_sorteo = '$id_sorteo' AND numero = '$decimo' AND serie = '$serie' AND numero_revision = '$revision' ");

			if (mysqli_num_rows($c_validar_registro) == 0) {

				mysqli_query($conn, "INSERT INTO cc_revisores_sorteos_menores_control (id_sorteo,id_revisor,num_lista,numero_revision,numero,serie, registro ,especial, id_posteo) VALUES ('$id_sorteo','$id_revisor','$num_lista','$revision','$decimo','$serie','$registro','$especial','$id_posteo') ");

				mysqli_query($conn, "UPDATE cc_revisores_sorteos_menores_control SET estado = 'REPROBADO' WHERE id = '$id_reprobacion' ");

			} else {

				echo "<div class = 'alert alert-danger'>Error: La decena " . $decimo . "0 - " . $decimo . "9 con serie " . $serie . " Ya fue reprobada anteriormente.</div>";

			}

		}

		$i++;
	}

	echo "<div class = 'alert alert-info'>Billetes reprobados correctamente.</div>";

}

if (isset($_POST['anular_reposicion'])) {

	$concat_anulacion = $_POST['anular_reposicion'];

	$numero = $concat_anulacion[0];
	$serie = substr($concat_anulacion, 1);

	$v_parametros = explode("_", $_POST['parametros_rango']);
	$id_sorteo = $v_parametros[0];
	$id_revisor = $v_parametros[1];
	$num_lista = $v_parametros[2];
	$revision = $v_parametros[3];
	$revision = $revision + 1;
	$revision_anterior = $revision - 1;

	if ($revision > 2) {

		mysqli_query($conn, "UPDATE cc_revisores_sorteos_menores_control SET estado = 'PENDIENTE' WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND numero = '$numero' AND serie = '$serie' AND numero_revision = '$revision_anterior' ");

	} else {

		mysqli_query($conn, "DELETE FROM cc_revisores_sorteos_menores_control WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND  numero = '$numero' AND serie = '$serie' ");

	}

	if (mysqli_query($conn, "DELETE FROM cc_revisores_sorteos_menores_control WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND  numero = '$numero' AND serie = '$serie' AND numero_revision >= '$revision' ") === TRUE) {

		echo "<div class = 'alert alert-info'>Reposicion anulada correctamente</div>";

	} else {

		echo mysqli_error($conn);
		echo "<div class = 'alert alert-danger'>Error inesperado, por favor intente nuevamente.</div>";

	}

}

?>