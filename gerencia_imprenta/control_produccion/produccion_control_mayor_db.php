<?php

if (isset($_POST['iniciar_control'])) {

	$id_sorteo = $_POST['sorteo1'];
	$id_sorteo2 = $_POST['sorteo1'];
	$id_maquina = $_POST['id_maquina'];
	$jornada = $_POST['jornada'];
	$contador_inicial = $_POST['contador_inicial'];
	$id_operador = $_POST['id_operador'];
	$h_i = $_POST['h_i'];
	$etapa = $_POST['etapa'];
	$fecha = $_POST['fecha_inicial'];
	$fecha_actual = date("Y-m-d", strtotime($fecha));

	$prensistas = $_POST['select_prensistas'];

	if ($etapa == 2) {
		$billete_inicial = $_POST['billete_inicial'];
		$billete_inicial2 = $_POST['billete_inicial2'];

		if ($billete_inicial != $billete_inicial2 AND $billete_inicial2 != "") {

			mysqli_query($conn, "INSERT INTO pro_control (id_orden,id_orden_2,maquina,jornada,fecha,billete_inicial,contador_inicial,id_operador_encargado,hora_inicial,etapa) VALUES ('$id_sorteo','$id_sorteo2','$id_maquina','$jornada','$fecha_actual','$billete_inicial2','$contador_inicial','$id_operador','$h_i','$etapa')");

		}

	} else {
		$billete_inicial = '';
	}

	if (mysqli_query($conn, "INSERT INTO pro_control (id_orden,id_orden_2,maquina,jornada,fecha,billete_inicial,contador_inicial,id_operador_encargado,hora_inicial,etapa) VALUES ('$id_sorteo','$id_sorteo2','$id_maquina','$jornada','$fecha_actual','$billete_inicial','$contador_inicial','$id_operador','$h_i','$etapa') ") === false) {

		echo mysqli_error($conn);

	} else {

		$c_max_control = mysqli_query($conn, "SELECT MAX(id) as maximo FROM pro_control WHERE contador_inicial = '$contador_inicial' AND id_orden = '$id_sorteo' AND etapa = '$etapa' ");

		if ($c_max_control === FALSE) {
			echo mysqli_error($conn);
		}

		$ob_max_control = mysqli_fetch_object($c_max_control);
		$id_control = $ob_max_control->maximo;

		$i = 0;
		while (isset($prensistas[$i])) {

			$v_prensista = explode("%", $prensistas[$i]);

			$cedula = $v_prensista[0];
			$name = $v_prensista[1];

			mysqli_query($conn, "INSERT INTO pro_control_prensistas (id_control ,cedula, nombre) VALUES ('$id_control' ,'$cedula','$name') ");

			$i++;
		}

		?>

<script type="text/javascript">

swal({
  title: "",
  text: "Control iniciado correctamente.",
  icon: "success",
  buttons: false,
  dangerMode: false,
})
.then((willDelete) => {
    window.location.href = './screen_produccion_control_mayor.php';
});

</script>

<?php

		echo "<div class = 'alert alert-info' >Control Iniciado Correctamente</div>";

	}

}

if (isset($_POST['eliminar_control'])) {

	$id_control = $_POST['eliminar_control'];

	mysqli_query($conn, "DELETE FROM  pro_control_detalle WHERE id_control = '$id_control' ");

	if (mysqli_query($conn, "DELETE FROM  pro_control WHERE id = '$id_control' ") === true) {

		mysqli_query($conn, "DELETE FROM  pro_control_prensistas WHERE id_control = '$id_control' ");

		echo "<div class = 'alert alert-info'>Registro eliminado correctamente.</div>";

	} else {
		echo "<div class = 'alert alert-danger'>Error inesperado, por favor vuelva a intentarlo. " . mysqli_error($conn) . "</div>";
	}

}

if (isset($_POST['sobrantes'])) {

	$id_sorteo = $_POST['id_sorteo_oculto'];
	$id_sorteo2 = $_SESSION['id_sorteo_control_mayor2'];
	$cantidad = $_POST['cantidad_sobrantes'];

	$busqueda = mysqli_query($conn, "SELECT * FROM pro_control_sobrantes_primera_etapa WHERE id_orden = '$id_sorteo' ");
	if ($busqueda === false) {
		echo mysqli_error($conn);
	}

	if (mysqli_num_rows($busqueda) > 0) {
		mysqli_query($conn, "UPDATE pro_control_sobrantes_primera_etapa SET cantidad = '$cantidad' WHERE id_orden = '$id_sorteo' ");
	} else {
		mysqli_query($conn, "INSERT INTO pro_control_sobrantes_primera_etapa (id_orden,id_orden_2,cantidad) VALUES ('$id_sorteo','$id_sorteo2','$cantidad') ");
	}

}

?>