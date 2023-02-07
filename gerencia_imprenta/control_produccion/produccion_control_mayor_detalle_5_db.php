<?php
$id_control = $_GET['id'];
$id_sorteo = $_GET['id'];
$rep_ant = 0;

date_default_timezone_set('America/Tegucigalpa');
$fecha_actual = date('Y-m-d');

$v_fecha = explode('-', $fecha_actual);

$year = $v_fecha[0];
$month = $v_fecha[1];
$day = $v_fecha[2];

if (isset($id_control)) {

	$info_control = mysqli_query($conn, "SELECT a.estado ,a.contador_final_maquina ,a.id ,a.fecha ,a.etapa ,a.id_orden ,a.id_orden_2 ,a.maquina ,a.contador_inicial ,a.jornada ,a.billete_inicial ,a.billete_final ,a.hora_inicial ,a.hora_final ,a.billetes_buenos ,a.id_operador_encargado ,a.contador_final ,b.maquina as nombre_maquina, c.no_sorteo_may ,c.desde_registro ,c.patron_salto ,c.cantidad_numeros FROM pro_control as a INNER JOIN pro_maquinas as b INNER JOIN
	sorteos_mayores as c ON a.maquina = b.id AND a.id_orden = c.id WHERE  a.id = '$id_control' ");

	if ($info_control === false) {
		echo mysqli_error($conn);
	}

	$ob_control = mysqli_fetch_object($info_control);

	$id = $ob_control->id;
	$contador_inicial = $ob_control->contador_inicial;
	$no_sorteo = $ob_control->no_sorteo_may;
	$no_sorteo_2 = $ob_control->id_orden_2;
	$id_sorteo = $ob_control->id_orden;
	$id_maquina = $ob_control->maquina;
	$no_maquina = $ob_control->nombre_maquina;
	$jornada = $ob_control->jornada;
	$registro_inicial = $ob_control->desde_registro;
	$patron_salto = $ob_control->patron_salto;
	$etapa = $ob_control->etapa;
	$hora_inicial = $ob_control->hora_inicial;
	$hora_final = $ob_control->hora_final;
	$billetes_buenos = $ob_control->billetes_buenos;
	$contador_final = $ob_control->contador_final;

	$billete_inicial_control = $ob_control->billete_inicial;
	$billete_final_control = $ob_control->billete_final;
	$contador_final_maquina = $ob_control->contador_final_maquina;
	$id_operador_encargado = $ob_control->id_operador_encargado;
	$cantidad_numeros = $ob_control->cantidad_numeros;
	$estado_control = $ob_control->estado;
	$sobrante_faltante = $contador_final_maquina - $contador_final;

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////// CONSULTA CONTROL PARALELO ///////////////////////////////////////////////

	$controles_paralelos = mysqli_query($conn, "SELECT * FROM  pro_control WHERE id_orden = '$id_sorteo' AND contador_inicial = '$contador_inicial' AND etapa = '$etapa' AND etapa = '2' AND jornada = '$jornada' AND hora_inicial = '$hora_inicial' AND estado = 'FINALIZADO' ");

	$conteo_paralelos = mysqli_num_rows($controles_paralelos);

/////////////////////////////////// CONSULTA CONTROL PARALELO ///////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////// CONSULTA CONTADOR PARCIAL ///////////////////////////////////////////////

	$registros_anteriores = mysqli_query($conn, "SELECT * FROM pro_control_detalle WHERE id_control = '$id' ");

	$cantidades = mysqli_query($conn, "SELECT SUM(cantidad) as suma FROM pro_control_detalle WHERE id_control = '$id' ");
	$ob_cantidad = mysqli_fetch_object($cantidades);
	$malos_repos = $ob_cantidad->suma;

	$cantidades_r = mysqli_query($conn, "SELECT SUM(reposiciones) as suma_r FROM pro_control_detalle WHERE id_control = '$id' ");
	$ob_cantidad_r = mysqli_fetch_object($cantidades_r);
	$repos = $ob_cantidad_r->suma_r;

	$contador_parcial = $contador_inicial + $malos_repos + $repos;

/////////////////////////////////// CONSULTA CONTADOR PARCIAL ///////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////

}

if (isset($_POST['guardar_detalle'])) {

	$id_control = $_POST['guardar_detalle'];
	$cantidad = $_POST['cantidad_impresiones'];

	$tipo = $_POST['tipo'];

	$observaciones = $_POST['observaciones'];
	$numeradora_parcial = $_POST['numeradora_parcial'];

	if (mysqli_query($conn, "INSERT INTO pro_control_detalle (id_control,grupo,cantidad,tipo,de_billete,a_billete,de_registro,a_registro,numeradora_parcial,observaciones) VALUES ('$id_control','','$cantidad','$tipo','','','','','$numeradora_parcial','$observaciones') ") === false) {
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
		$numero_saltos = $cantidad_numeros / $patron_salto;

		$info_control = mysqli_query($conn, "SELECT a.contador_final,a.id,a.fecha,a.etapa,a.id_orden,a.maquina,a.contador_inicial,a.jornada,a.billete_inicial,a.billete_final,a.hora_inicial,a.id_operador_encargado,b.maquina as nombre_maquina, c.no_sorteo_may,c.desde_registro,c.patron_salto,c.cantidad_numeros FROM pro_control as a INNER JOIN pro_maquinas as b INNER JOIN
	sorteos_mayores as c ON a.maquina = b.id AND a.id_orden = c.id WHERE  a.id = '$id_control' ");

		if ($info_control === false) {
			echo mysqli_error($conn);
		}

		$ob_control = mysqli_fetch_object($info_control);
		$no_sorteo = $ob_control->no_sorteo_may;
		$id_sorteo = $ob_control->id_orden;
		$id_maquina = $ob_control->maquina;
		$no_maquina = $ob_control->nombre_maquina;
		$jornada = $ob_control->jornada;

		$etapa = $ob_control->etapa;
		$hora_inicial = $ob_control->hora_inicial;

		$billete_inicial = $ob_control->billete_inicial;
		$billete_final = $ob_control->billete_final;
		$registro_inicial = $ob_control->desde_registro;
		$patron_salto = $ob_control->patron_salto;
		$contador_final = $ob_control->contador_final;
		$cantidad_numeros = $ob_control->cantidad_numeros;

		$id_operador_encargado = $ob_control->id_operador_encargado;

		$i = 0;
		$billete_inicial_grupo = 0;
		$billete_final_grupo = 0;

		while ($numero_saltos > 0) {

			$billete_final_grupo = $billete_inicial_grupo + $patron_salto - 1;
			$num_saltos = $billete_inicial_grupo / $patron_salto;
			$num_saltos = floor($num_saltos);
			$k = 1;
			$acumulador = 0;

			while ($k <= $num_saltos) {

				if (isset($v_salto[$k])) {
					$acumulador = $acumulador + $v_salto[$k] - 1;
				}

				$k++;
			}

			$registro = $registro_inicial - $acumulador;
			$registro_inicial_grupo = $registro - $billete_inicial_grupo;
			$registro_final_grupo = $registro_inicial_grupo - 4999;

			$v_grupo[$i] = $billete_inicial_grupo . " ~ " . $billete_final_grupo;
			$v_grupo_r[$i] = $registro_inicial_grupo . " ~ " . $registro_final_grupo;
			$billete_inicial_grupo = $billete_final_grupo + 1;

			$i++;
			$numero_saltos--;
		}

/////////////////////////////////////////////////////////////////////
		//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
		$consulta_parcial = mysqli_query($conn, "SELECT  (SUM(cantidad)+SUM(reposiciones)) as suma FROM pro_control_detalle WHERE id_control = '$id_control' ");
		if ($consulta_parcial == false) {
			echo mysqli_error($conn);
		}
		$ob_parcial = mysqli_fetch_object($consulta_parcial);
		$suma_parcial = $ob_parcial->suma;

		$numeradora_parcial_calculo = $contador_inicial + $suma_parcial;
//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
		/////////////////////////////////////////////////////////////////////

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
	$contador_final_maquina = $_POST['contador_final_maquina'];
	$hora_inicial = $_POST['h_i'];
	$hora_final = $_POST['h_f'];
	$billete_inicial = $_POST['billete_inicial'];
	$billete_final = $_POST['billete_final'];
	$billetes_buenos = $_POST['billetes_buenos'];

	if (mysqli_query($conn, "UPDATE pro_control SET billete_inicial = '$billete_inicial' , contador_final_maquina = '$contador_final_maquina' ,  contador_final = '$contador_final', hora_inicial = '$hora_inicial',hora_final = '$hora_final', billete_final = '$billete_final',billetes_buenos = '$billetes_buenos', estado = 'FINALIZADO' WHERE  id = '$id_control' ") === false) {
		echo mysqli_error($conn);

	} else {

		$info_control = mysqli_query($conn, "SELECT a.contador_final_maquina,a.id,a.fecha,a.etapa,a.id_orden,a.maquina,a.contador_inicial,a.jornada,a.billete_inicial,a.billete_final,a.hora_inicial,a.hora_final,a.billetes_buenos,a.id_operador_encargado,a.contador_final,b.maquina as nombre_maquina, c.no_sorteo_may,c.desde_registro,c.patron_salto,c.cantidad_numeros FROM pro_control as a INNER JOIN pro_maquinas as b INNER JOIN
	sorteos_mayores as c ON a.maquina = b.id AND a.id_orden = c.id WHERE  a.id = '$id_control' ");

		if ($info_control === false) {
			echo mysqli_error($conn);
		}

		$ob_control = mysqli_fetch_object($info_control);
		$no_sorteo = $ob_control->no_sorteo_may;
		$id_sorteo = $ob_control->id_orden;
		$id_maquina = $ob_control->maquina;
		$no_maquina = $ob_control->nombre_maquina;
		$jornada = $ob_control->jornada;
		$billete_inicial_control = $ob_control->billete_inicial;
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
		$cantidad_numeros = $ob_control->cantidad_numeros;

		$sobrante_faltante = $contador_final_maquina - $contador_final;

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

if (isset($_POST['registrar_paralelo'])) {

/////////////////////////////////////////////////////////////////////////////////
	//////////////////////////  INFO CONTROL PARALELO ///////////////////////////////

	$id_control_paralelo = $_POST['registrar_paralelo'];
	$c_info_paralelo = mysqli_query($conn, "SELECT * FROM pro_control WHERE id = '$id_control_paralelo' ");
	$ob_info_paralelo = mysqli_fetch_object($c_info_paralelo);
	$billete_inicial_p = $ob_info_paralelo->billete_inicial;
	$billete_final_p = $ob_info_paralelo->billete_final;
	$hora_final_p = $ob_info_paralelo->hora_final;
	$billetes_buenos = $ob_info_paralelo->billetes_buenos;
	$contador_final = $ob_info_paralelo->contador_final;
	$contador_final_m_p = $ob_info_paralelo->contador_final_maquina;

//////////////////////////  INFO CONTROL PARALELO ///////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
	//////////////////////////  INFO CONTROL ACTUAL ///////////////////////////////

	$id_control_actual = $_GET['id'];
	$id_sorteo = $_POST['id_sorteo_o'];
	$billete_inicial_actual = $_POST['billete_inicial'];
	$grupo_actual = $_POST['grupo'];
	$billete_final_actual = $billete_inicial_actual + ($billete_final_p - $billete_inicial_p);
	$billetes_buenos_actual = $billete_final_actual - $billete_inicial_actual + 1;

//////////////////////////  INFO CONTROL ACTUAL ///////////////////////////////
	///////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////
	////////////////////// PARAMETROS PARA REGISTROS SEGURIDAD  ////////////////////////

	$info_mayor = mysqli_query($conn, "SELECT * FROM sorteos_mayores where id = '$id_sorteo' ");
	$value_mayor = mysqli_fetch_object($info_mayor);
	$cantidad_billetes = $value_mayor->cantidad_numeros;
	$registro_inicial = $value_mayor->desde_registro;
	$patron_salto = $value_mayor->patron_salto;

	$parametros_mayor = mysqli_query($conn, "SELECT * FROM sorteos_mayores_produccion where id_sorteo = '$id_sorteo' ");

	$i = 1;
	while ($reg = mysqli_fetch_array($parametros_mayor)) {
		$v_salto[$i] = $reg['salto'];
		$i++;
	}

////////////////////// PARAMETROS PARA REGISTROS SEGURIDAD  ////////////////////////
	////////////////////////////////////////////////////////////////////////////////////

	$registros_paralelo = mysqli_query($conn, "SELECT * FROM pro_control_detalle WHERE id_control = '$id_control_paralelo' ");
	while ($reg_paralelo = mysqli_fetch_array($registros_paralelo)) {

		$cantidad = $reg_paralelo['cantidad'];
		$tipo = $reg_paralelo['tipo'];
		$num_parcial = $reg_paralelo['numeradora_parcial'];

		if ($tipo == "B. Malos") {

			$de_billete = ((int) $reg_paralelo['de_billete'] - $billete_inicial_p) + $billete_inicial_actual;
			$a_billete = ((int) $reg_paralelo['a_billete'] - $billete_inicial_p) + $billete_inicial_actual;

			$num_saltos = $de_billete / $patron_salto;
			$num_saltos = floor($num_saltos);

			$k = 1;
			$acumulador = 0;
			while ($k <= $num_saltos) {
				if (isset($v_salto[$k])) {
					$acumulador = $acumulador + $v_salto[$k] - 1;
				}
				$k++;
			}

			$de_registro = $registro_inicial - $acumulador;
			$de_registro = $de_registro - $de_billete;

			$a_registro = $registro_inicial - $acumulador;
			$a_registro = $a_registro - $a_billete;

		} else {

			$de_billete = "";
			$a_billete = "";

			$de_registro = "";
			$a_registro = "";

		}

////////////////////////////////////////////////////
		/////////////////// INSERT DETALLE /////////////////

		mysqli_query($conn, "INSERT INTO pro_control_detalle (id_control, grupo, cantidad, tipo, de_billete, a_billete, de_registro, a_registro, numeradora_parcial) VALUES ('$id_control_actual', '$grupo_actual', '$cantidad', '$tipo', '$de_billete', '$a_billete', '$de_registro', '$a_registro', '$num_parcial' ) ");

/////////////////// INSERT DETALLE /////////////////
		////////////////////////////////////////////////////

	}

	mysqli_query($conn, "UPDATE pro_control SET  contador_final_maquina = '$contador_final_m_p' ,  contador_final = '$contador_final', hora_final = '$hora_final_p', billete_final = '$billete_final_actual' ,billetes_buenos = '$billetes_buenos_actual' WHERE  id = '$id_control_actual' ");

	?>

<script type="text/javascript">

swal({
  title: "",
  text: "Control importado correctamente.",
  icon: "success",
  buttons: false,
  dangerMode: false,
})
.then((willDelete) => {
    window.location.href = './produccion_control_mayor_detalle.php?id='+<?php echo $id_control_actual; ?>;
});

</script>

<?php

}

?>