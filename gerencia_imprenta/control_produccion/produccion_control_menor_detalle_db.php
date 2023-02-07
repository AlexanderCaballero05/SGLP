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

	$info_control = mysqli_query($conn, "SELECT  a.estado,a.fecha,a.grupo,a.contador_final_maquina,a.id,a.fecha,a.etapa,a.id_orden,a.id_orden_2,a.maquina,a.contador_inicial,a.jornada,a.grupo,a.hora_inicial,a.hora_final,a.billetes_buenos,a.id_operador_encargado,a.contador_final,b.maquina as nombre_maquina,c.series,c.desde_registro , c.no_sorteo_men FROM pro_control_menor as a INNER JOIN pro_maquinas as b INNER JOIN
	sorteos_menores as c ON a.maquina = b.id AND a.id_orden = c.id WHERE  a.id = '$id_control' ");

	if ($info_control === false) {
		echo mysqli_error();
	}

	$ob_control = mysqli_fetch_object($info_control);
	$no_sorteo = $ob_control->no_sorteo_men;
	$no_sorteo_2 = $ob_control->id_orden_2;
	$id_sorteo = $ob_control->id_orden;
	$id_maquina = $ob_control->maquina;
	$no_maquina = $ob_control->nombre_maquina;
	$jornada = $ob_control->jornada;
	$estado_control = $ob_control->estado;
	$grupo = $ob_control->grupo;
	$decena = substr($grupo, 0, 1);
	$decena++;

	$series = $ob_control->series;
	$max_serie = $series - 1;

	$fecha = $ob_control->fecha;
	$etapa = $ob_control->etapa;
	$hora_inicial = $ob_control->hora_inicial;
	$hora_final = $ob_control->hora_final;
	$billetes_buenos = $ob_control->billetes_buenos;
	$contador_final = $ob_control->contador_final;
	$contador_final_maquina = $ob_control->contador_final_maquina;
	$id_operador_encargado = $ob_control->id_operador_encargado;
	$desde_registro = $ob_control->desde_registro;

	$sobrante_faltante = $contador_final - $contador_final_maquina;

	$max_extra = mysqli_query($conn, "SELECT MAX(cantidad) as maximo FROM sorteos_menores_num_extras WHERE id_sorteo = '$no_sorteo'");
	if (mysqli_num_rows($max_extra) == 0) {
		$cantidad_extra_mayor = 0;
	} else {
		$ob_extra = mysqli_fetch_object($max_extra);
		$cantidad_extra_mayor = $ob_extra->maximo;
	}

	$i = 0;
	while ($i <= 9) {
		$multiplo = $series * $i;
		$registro = $multiplo + $desde_registro;
		if ($registro > 99999) {
			$registro = $registro - 100000;
		}
		$v_registros_grupo[$i] = $registro;
		$i++;
	}

	$busqueda_registros = mysqli_query($conn, "SELECT * FROM pro_control_menor WHERE id = '$id_control' ");

	$num_registros = mysqli_num_rows($busqueda_registros);

	while ($row_registro = mysqli_fetch_array($busqueda_registros)) {
		$id = $row_registro['id'];
		$jornada = $row_registro['jornada'];
		$contador_inicial = $row_registro['contador_inicial'];
		$contador_final = $row_registro['contador_final'];
	}

	$registros_anteriores = mysqli_query($conn, "SELECT * FROM pro_control_detalle_menor WHERE id_control = '$id' ");

	$cantidades = mysqli_query($conn, "SELECT SUM(cantidad) as suma FROM pro_control_detalle_menor WHERE id_control = '$id' ");
	$ob_cantidad = mysqli_fetch_object($cantidades);
	$malos_repos = $ob_cantidad->suma;

	$cantidades_r = mysqli_query($conn, "SELECT SUM(reposiciones) as suma_r FROM pro_control_detalle_menor WHERE id_control = '$id' ");
	$ob_cantidad_r = mysqli_fetch_object($cantidades_r);
	$repos = $ob_cantidad_r->suma_r;

	$cantidades_b = mysqli_query($conn, "SELECT SUM(billetes_buenos) as suma_b FROM pro_control_detalle_menor WHERE id_control = '$id' ");
	$ob_cantidad_b = mysqli_fetch_object($cantidades_b);
	$bue = $ob_cantidad_b->suma_b;

	$max_contador = mysqli_query($conn, "SELECT max(numeradora_parcial) as contador_parcial FROM pro_control_detalle_menor WHERE id_control = '$id' ");
	$ob_max_contador = mysqli_fetch_object($max_contador);
	$contador_parcial = $ob_max_contador->contador_parcial;

	if ($contador_parcial == '') {
		$contador_parcial = $contador_inicial;
	}

	$reposiciones_hoy = mysqli_query($conn, "SELECT SUM(reposiciones) as suma_reposiciones FROM pro_control_detalle_menor WHERE id_control = '$id'  ");
	$ob_reposicion = mysqli_fetch_object($reposiciones_hoy);
	$reposiciones_hoy = $ob_reposicion->suma_reposiciones;

//   Reposiciones anteriores /////////////////////////////////////////////////////
	$suma_repos = 0;
	$controles_anteriores = mysqli_query($conn, "SELECT * FROM pro_control_menor WHERE id_orden = '$id_sorteo' AND maquina = '$id_maquina'  ");

	while ($reg_control = mysqli_fetch_array($controles_anteriores)) {
		$id_anterior = $reg_control['id'];

		$rep = mysqli_query($conn, "SELECT SUM(reposiciones) as suma_rep_ant FROM pro_control_detalle_menor WHERE id_control = '$id_anterior' ");

		$ob_rep = mysqli_fetch_object($rep);
		$rep_ant = $ob_rep->suma_rep_ant;
		$suma_repos = $suma_repos + $rep_ant;
	}

/////////////////////////////////////////////////////////////////////////////////////////////

	$rep_totales = $suma_repos + $reposiciones_hoy;

}

if (isset($_POST['guardar_detalle'])) {

	$id_control = $_POST['guardar_detalle'];

	$cantidad = $_POST['cantidad_impresiones'];
	$tipo = $_POST['tipo'];
	$de_billete = $_POST['del_billete'];
	$a_billete = $_POST['al_billete'];
	$de_registro = $_POST['del_registro'];
	$a_registro = $_POST['al_registro'];
	$numeradora_parcial = $_POST['numeradora_parcial'];
	$observaciones = $_POST['observaciones'];

	if (mysqli_query($conn, "INSERT INTO pro_control_detalle_menor (id_control,reposiciones,cantidad,tipo,de_billete,a_billete,de_registro,a_registro,numeradora_parcial, observaciones) VALUES ('$id_control','','$cantidad','$tipo','$de_billete','$a_billete','$de_registro','$a_registro','$numeradora_parcial','$observaciones') ") === false) {
		echo mysqli_error();
	} else {

		$registros_anteriores = mysqli_query($conn, "SELECT * FROM pro_control_detalle_menor WHERE id_control = '$id_control' ");

		$registros_anteriores = mysqli_query($conn, "SELECT * FROM pro_control_detalle_menor WHERE id_control = '$id' ");

		$cantidades = mysqli_query($conn, "SELECT SUM(cantidad) as suma FROM pro_control_detalle_menor WHERE id_control = '$id' ");
		$ob_cantidad = mysqli_fetch_object($cantidades);
		$malos_repos = $ob_cantidad->suma;

		$cantidades_r = mysqli_query($conn, "SELECT SUM(reposiciones) as rep FROM pro_control_detalle_menor WHERE id_control = '$id' ");
		$ob_cantidad_r = mysqli_fetch_object($cantidades_r);
		$repos = $ob_cantidad_r->rep;

		$cantidades_b = mysqli_query($conn, "SELECT SUM(billetes_buenos) as suma_b FROM pro_control_detalle_menor WHERE id_control = '$id' ");
		$ob_cantidad_b = mysqli_fetch_object($cantidades_b);
		$bue = $ob_cantidad_b->suma_b;

		$contador_parcial = $contador_inicial + $malos_repos + $repos + $bue;

		echo "<div class = 'alert alert-info' >Registro Guardado Correctamente</div>";
	}

}

if (isset($_POST['eliminar_detalle'])) {

	$id_control = $_GET['id'];
	$id = $_GET['id'];

	$id_detalle = $_POST['eliminar_detalle'];

	if (mysqli_query($conn, "DELETE FROM pro_control_detalle_menor WHERE id = '$id_detalle' ") === TRUE) {
		echo "<div class = 'alert alert-info' >Registro Eliminado Correctamente</div>";
		$registros_anteriores = mysqli_query($conn, "SELECT * FROM pro_control_detalle_menor WHERE id_control = '$id_control' ");

		$registros_anteriores = mysqli_query($conn, "SELECT * FROM pro_control_detalle_menor WHERE id_control = '$id' ");

		$cantidades = mysqli_query($conn, "SELECT SUM(cantidad) as suma FROM pro_control_detalle_menor WHERE id_control = '$id' ");
		$ob_cantidad = mysqli_fetch_object($cantidades);
		$malos_repos = $ob_cantidad->suma;

		$cantidades_r = mysqli_query($conn, "SELECT SUM(reposiciones) as suma_r FROM pro_control_detalle_menor WHERE id_control = '$id' ");
		$ob_cantidad_r = mysqli_fetch_object($cantidades_r);
		$repos = $ob_cantidad_r->suma_r;

		$cantidades_b = mysqli_query($conn, "SELECT SUM(billetes_buenos) as suma_b FROM pro_control_detalle_menor WHERE id_control = '$id' ");
		$ob_cantidad_b = mysqli_fetch_object($cantidades_b);
		$bue = $ob_cantidad_b->suma_b;

		$max_contador = mysqli_query($conn, "SELECT max(numeradora_parcial) as contador_parcial FROM pro_control_detalle_menor WHERE id_control = '$id' ");
		$ob_max_contador = mysqli_fetch_object($max_contador);
		$contador_parcial = $ob_max_contador->contador_parcial;
		if ($contador_parcial == '') {
			$contador_parcial = $contador_inicial;
		}

// calculo de numeradora parcial
		//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
		$consulta_parcial = mysqli_query($conn, "SELECT  (SUM(cantidad)+SUM(reposiciones)) as suma FROM pro_control_detalle_menor WHERE id_control = '$id_control' ");
		if ($consulta_parcial == false) {
			echo mysqli_error();
		}
		$ob_parcial = mysqli_fetch_object($consulta_parcial);
		$suma_parcial = $ob_parcial->suma;

		$numeradora_parcial_calculo = $contador_inicial + $suma_parcial;
//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$4

	} else {
		echo mysqli_error();
	}

}

if (isset($_POST['actualizar_detalle'])) {

	$id_control = $_GET['id'];
	$id_detalle = $_POST['actualizar_detalle'];
	$tipo = $_POST['edicion_tipo' . $id_detalle];

	if (mysqli_query($conn, "UPDATE  pro_control_detalle_menor SET tipo = '$tipo' WHERE id = '$id_detalle' ") === TRUE) {
		echo "<div class = 'alert alert-info' >Registro Actualizados Correctamente</div>";

		$registros_anteriores = mysqli_query($conn, "SELECT * FROM pro_control_detalle_menor WHERE id_control = '$id_control' ");

	} else {
		echo mysqli_error();
	}

}

if (isset($_POST['guardar_cierre'])) {

	$id_control = $_POST['guardar_cierre'];
	$contador_final = $_POST['contador_final'];
	$contador_final_maquina = $_POST['contador_final_maquina'];
	$hora_inicial = $_POST['h_i'];
	$hora_final = $_POST['h_f'];
//$billete_final = $_POST['billete_final'];
	$billetes_buenos = $_POST['billetes_buenos'];

	if (mysqli_query($conn, "UPDATE pro_control_menor SET contador_final_maquina = '$contador_final_maquina', contador_final = '$contador_final', hora_inicial = '$hora_inicial', hora_final = '$hora_final',billetes_buenos = '$billetes_buenos', estado = 'FINALIZADO' WHERE  id = '$id_control' ") === false) {
		echo mysqli_error();
	} else {

		$info_control = mysqli_query($conn, "SELECT a.grupo,a.contador_final_maquina,a.id,a.fecha,a.etapa,a.id_orden,a.maquina,a.contador_inicial,a.jornada,a.grupo,a.hora_inicial,a.hora_final,a.billetes_buenos,a.id_operador_encargado,a.contador_final,b.maquina as nombre_maquina, c.no_sorteo_men FROM pro_control_menor as a INNER JOIN pro_maquinas as b INNER JOIN
	sorteos_menores as c ON a.maquina = b.id AND a.id_orden = c.id WHERE  a.id = '$id_control' ");

		if ($info_control === false) {
			echo mysqli_error();
		}

		$ob_control = mysqli_fetch_object($info_control);
		$no_sorteo = $ob_control->no_sorteo_men;
		$id_sorteo = $ob_control->id_orden;
		$id_maquina = $ob_control->maquina;
		$no_maquina = $ob_control->nombre_maquina;
		$jornada = $ob_control->jornada;
		$grupo = $ob_control->grupo;

		$numero_grupo = $grupo;

		$etapa = $ob_control->etapa;
		$hora_inicial = $ob_control->hora_inicial;
		$hora_final = $ob_control->hora_final;
		$billetes_buenos = $ob_control->billetes_buenos;
		$contador_final = $ob_control->contador_final;
		$contador_final_maquina = $ob_control->contador_final_maquina;
		$id_operador_encargado = $ob_control->id_operador_encargado;

		$sobrante_faltante = $contador_final_maquina - $contador_final;

		$sobrante_faltante = $contador_final - $contador_final_maquina;

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

    window.location.href = './screen_produccion_control_menor.php';

});

</script>

<?php

	}

}

if (isset($_POST['reaperturar_control'])) {

	$id_control = $_POST['reaperturar_control'];

	if (mysqli_query($conn, "UPDATE pro_control_menor SET  estado = 'INICIADO' WHERE  id = '$id_control' ") === false) {
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

    window.location.href = './screen_produccion_control_menor.php';

});

</script>

<?php

	}

}

?>