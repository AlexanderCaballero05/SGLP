<?php
$id_control = $_GET['id'];
$rep_ant = 0;

date_default_timezone_set('America/El_Salvador');
$fecha_actual = date('Y-m-d');

$v_fecha = explode('-', $fecha_actual);

$year = $v_fecha[0];
$month = $v_fecha[1];
$day = $v_fecha[2];

if (isset($id_control)) {

	$info_control = mysqli_query($conn, "SELECT a.contador_final_maquina,a.id,a.fecha,a.etapa,a.id_orden,a.id_orden_2,a.maquina,a.contador_inicial,a.jornada,a.billete_inicial,a.billete_final,a.hora_inicial,a.hora_final,a.billetes_buenos,a.id_operador_encargado,a.contador_final,a.estado,b.maquina as nombre_maquina, c.no_sorteo_may,c.desde_registro,c.patron_salto FROM pro_control as a INNER JOIN pro_maquinas as b INNER JOIN
	sorteos_mayores as c ON a.maquina = b.id AND a.id_orden = c.id WHERE  a.id = '$id_control' ");

	if ($info_control === false) {
		echo mysqli_error($conn);
	}

	$ob_control = mysqli_fetch_object($info_control);
	$no_sorteo = $ob_control->no_sorteo_may;
	$no_sorteo_2 = $ob_control->id_orden_2;
	$id_sorteo = $ob_control->id_orden;
	$id_maquina = $ob_control->maquina;
	$no_maquina = $ob_control->nombre_maquina;
	$jornada = $ob_control->jornada;
	$billete_inicial = $ob_control->billete_inicial;
	$billete_final_control = $ob_control->billete_final;
	$registro_inicial = $ob_control->desde_registro;
	$patron_salto = $ob_control->patron_salto;
	$etapa = $ob_control->etapa;
	$hora_inicial = $ob_control->hora_inicial;
	$hora_final = $ob_control->hora_final;
	$billetes_buenos = $ob_control->billetes_buenos;
	$contador_final = $ob_control->contador_final;
	$contador_final_maquina = $ob_control->contador_final_maquina;
	$id_operador_encargado = $ob_control->id_operador_encargado;
	$estado_control = $ob_control->estado;

	$sobrante_faltante = $contador_final_maquina - $contador_final;

	$busqueda_registros = mysqli_query($conn, "SELECT * FROM pro_control WHERE id = '$id_control' ");

	$num_registros = mysqli_num_rows($busqueda_registros);

	while ($row_registro = mysqli_fetch_array($busqueda_registros)) {
		$id = $row_registro['id'];
//$reposiciones_vienen = $row_registro['reposiciones_vienen'];
		//$reposiciones_emitidas = $row_registro['reposiciones_emitidas'];
		$jornada = $row_registro['jornada'];
		$contador_inicial = $row_registro['contador_inicial'];
		$contador_final = $row_registro['contador_final'];
	}

	$registros_anteriores = mysqli_query($conn, "SELECT * FROM pro_control_detalle WHERE id_control = '$id' ");

	$cantidades = mysqli_query($conn, "SELECT SUM(cantidad) as suma FROM pro_control_detalle WHERE id_control = '$id' ");
	$ob_cantidad = mysqli_fetch_object($cantidades);
	$malos_repos = $ob_cantidad->suma;

	$cantidades_r = mysqli_query($conn, "SELECT SUM(reposiciones) as suma_r FROM pro_control_detalle WHERE id_control = '$id' ");
	$ob_cantidad_r = mysqli_fetch_object($cantidades_r);
	$repos = $ob_cantidad_r->suma_r;

	$contador_parcial = $contador_inicial + $malos_repos + $repos;

	$reposiciones_hoy = mysqli_query($conn, "SELECT SUM(reposiciones) as suma_reposiciones FROM pro_control_detalle WHERE id_control = '$id' ");
	$ob_reposicion = mysqli_fetch_object($reposiciones_hoy);
	$reposiciones_hoy = $ob_reposicion->suma_reposiciones;

//   Reposiciones anteriores /////////////////////////////////////////////////////

	$controles_anteriores = mysqli_query($conn, "SELECT * FROM pro_control WHERE id_orden = '$id_sorteo' AND maquina = '$id_maquina' AND etapa = '$etapa' AND id != '$id_control'  ");

	while ($reg_control = mysqli_fetch_array($controles_anteriores)) {
		$id_anterior = $reg_control['id'];

		$rep = mysqli_query($conn, "SELECT SUM(reposiciones) as suma_rep_ant FROM pro_control_detalle WHERE id_control = '$id_anterior' ");

		$ob_rep = mysqli_fetch_object($rep);
		$rep_ant = $ob_rep->suma_rep_ant;

	}

/////////////////////////////////////////////////////////////////////////////////////////////

	$rep_totales = $rep_ant + $reposiciones_hoy;

}

if (isset($_POST['guardar_detalle'])) {

	$id_control = $_POST['guardar_detalle'];
	$cantidad = $_POST['cantidad'];
	$tipo = $_POST['tipo'];
	$numeradora_parcial = $_POST['numeradora_parcial'];
	$observaciones = $_POST['observaciones'];

	if (mysqli_query($conn, "INSERT INTO pro_control_detalle (id_control,cantidad,tipo,numeradora_parcial,observaciones) VALUES ('$id_control','$cantidad','$tipo','$numeradora_parcial','$observaciones') ") === false) {
		echo mysqli_error($conn);

	} else {

		$registros_anteriores = mysqli_query($conn, "SELECT * FROM pro_control_detalle WHERE id_control = '$id_control' ");

		$registros_anteriores = mysqli_query($conn, "SELECT * FROM pro_control_detalle WHERE id_control = '$id' ");

		$cantidades = mysqli_query($conn, "SELECT SUM(cantidad) as suma FROM pro_control_detalle WHERE id_control = '$id' ");
		$ob_cantidad = mysqli_fetch_object($cantidades);
		$malos_repos = $ob_cantidad->suma;
		$cantidades_r = mysqli_query($conn, "SELECT SUM(reposiciones) as suma_r FROM pro_control_detalle WHERE id_control = '$id' ");
		$ob_cantidad_r = mysqli_fetch_object($cantidades_r);
		$repos = $ob_cantidad_r->suma_r;

		$contador_parcial = $contador_inicial + $malos_repos + $repos;

		echo "<div class = 'alert alert-info' >Registro Guardado Correctamente</div>";

	}

}

if (isset($_POST['eliminar_detalle'])) {

	$id_control = $_GET['id'];
	$id_detalle = $_POST['eliminar_detalle'];

	if (mysqli_query($conn, "DELETE FROM pro_control_detalle WHERE id = '$id_detalle' ") === TRUE) {
		echo "<div class = 'alert alert-info' >Registro Eliminado Correctamente</div>";
		$registros_anteriores = mysqli_query($conn, "SELECT * FROM pro_control_detalle WHERE id_control = '$id_control' ");

// calculo de numeradora parcial
		//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
		$consulta_parcial = mysqli_query($conn, "SELECT  (SUM(cantidad)+SUM(reposiciones)) as suma FROM pro_control_detalle WHERE id_control = '$id_control' ");
		if ($consulta_parcial == false) {
			echo mysqli_error($conn);
		}
		$ob_parcial = mysqli_fetch_object($consulta_parcial);
		$suma_parcial = $ob_parcial->suma;

		$numeradora_parcial_calculo = $contador_inicial + $suma_parcial;
//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$4

	} else {
		echo mysqli_error($conn);
	}

}

if (isset($_POST['actualizar_detalle'])) {

	$id_control = $_GET['id'];
	$id_detalle = $_POST['actualizar_detalle'];
	$tipo = $_POST['edicion_tipo' . $id_detalle];

	if (mysqli_query($conn, "UPDATE  pro_control_detalle SET tipo = '$tipo' WHERE id = '$id_detalle' ") === TRUE) {
		echo "<div class = 'alert alert-info' >Registro Actualizados Correctamente</div>";

		$registros_anteriores = mysqli_query($conn, "SELECT * FROM pro_control_detalle WHERE id_control = '$id_control' ");

	} else {
		echo mysqli_error($conn);
	}

}

if (isset($_POST['guardar_cierre'])) {

	$id_control = $_POST['guardar_cierre'];
	$contador_final = $_POST['contador_final'];
	$hora_final = $_POST['h_f'];
	$billetes_buenos = $_POST['billetes_buenos'];
	$contador_final_maquina = $_POST['contador_final_maquina'];

	if (mysqli_query($conn, "UPDATE pro_control SET contador_final = '$contador_final', hora_final = '$hora_final',billetes_buenos = '$billetes_buenos', estado = 'FINALIZADO', contador_final_maquina = '$contador_final_maquina' WHERE  id = '$id_control' ") === false) {
		echo mysqli_error($conn);
	} else {

//echo "<div class = 'alert alert-info' >Cierre Realizado Correctamente</div>";

		?>
<script type="text/javascript">

swal({
  title: "Cierre Realizado Correctamente",
  text: "¿Desea crear un nuevo control?",
  icon: "success",
  buttons: true,
  dangerMode: false,
})
.then((willDelete) => {

    window.location.href = './screen_produccion_control_mayor.php';

});

</script>

<?php

	}

}

if (isset($_POST['reaperturar_control'])) {

	$id_control = $_POST['reaperturar_control'];

	if (mysqli_query($conn, "UPDATE pro_control SET  estado = 'INICIADO' WHERE  id = '$id_control' ") === false) {
		echo mysqli_error($conn);
	} else {

//echo "<div class = 'alert alert-info' >Cierre Realizado Correctamente</div>";

		?>
<script type="text/javascript">

swal({
  title: "Control aperturado Correctamente",
  text: "",
  icon: "success",
  buttons: true,
  dangerMode: false,
})
.then((willDelete) => {

    window.location.href = './screen_produccion_control_mayor.php';

});

</script>

<?php

	}

}

?>