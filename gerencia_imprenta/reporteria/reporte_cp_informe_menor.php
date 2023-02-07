<?php
require '../../template/header.php';

$select_sorteos = mysqli_query($conn, "SELECT * FROM sorteos_menores ORDER BY no_sorteo_men DESC ");

?>


<style type="text/css" >

@media print{
	@page {
		}
}

</style>


<form method="POST">

<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >INFORME DE PRODUCCION DE LOTERIA NACIONAL FINAL</h2>

<?php

if (isset($_POST['seleccionar'])) {

	$id_sorteo = $_POST['sorteo'];

	echo "<h3 style='color:black;' align = 'center' >SORTEO " . $id_sorteo . "</h3>";

}

?>

<br>
</section>




<a style = "width:100%" id="non-printable"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse">
 SELECCION DE PARAMETROS
</a>

<div class="collapse " id="collapse1" align="center">


<div class="input-group" style="margin:10px 0px 10px 0px; width: 70%" >

<div class = "input-group-prepend"><span  class="input-group-text">Seleccione un sorteo: </span></div>
<select name="sorteo" style="width:10%" class="form-control">
<?php
while ($sorteo = mysqli_fetch_array($select_sorteos)) {
	echo "<option value = '" . $sorteo['id'] . "'>" . $sorteo['no_sorteo_men'] . "</option>";
}
?>
</select>

<div class="input-group-prepend" style="margin-left: 5px;" ><span  class="input-group-text">Fecha Inicio Control: </span></div>
<input type='date' id ="fecha_i" style="width:15%" name = "fecha_inicial" class="form-control" >

<div class="input-group-prepend" style="margin-left: 5px;" ><span  class="input-group-text">Fecha Final Control: </span></div>
<input type='date' id ="fecha_f" style="width:15%" name = "fecha_final" class="form-control" >

<input type="submit" name="seleccionar" style="margin-left: 5px;" class="btn btn-primary" value = "Seleccionar">


</div>

</div>


<br><br>

</form>



<?php

if (isset($_POST['seleccionar'])) {

	$id_sorteo = $_POST['sorteo'];

	$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE id = '$id_sorteo' ");
	$i_sorteo = mysqli_fetch_object($info_sorteo);
	$numero_sorteo = $i_sorteo->no_sorteo_men;
	$registro_inicial = $i_sorteo->desde_registro;
	$fecha_sorteo = $i_sorteo->fecha_sorteo;
	$fecha = $_POST['fecha_inicial'];
	$fecha_f = $_POST['fecha_final'];

	if ($fecha != '') {
		$fecha = date('Y-m-d', strtotime($fecha));

		$controles_iniciados = mysqli_query($conn, "SELECT a.contador_final,a.billetes_buenos,a.id,a.fecha,a.id_orden,a.contador_final_maquina,a.etapa,a.maquina,a.contador_inicial,a.jornada, a.grupo FROM pro_control_menor as a  WHERE a.id_orden = '$id_sorteo' AND a.fecha  BETWEEN '$fecha' AND '$fecha_f' AND a.estado = 'FINALIZADO'   ORDER BY a.maquina, a.fecha ,a.hora_inicial  , a.contador_inicial ASC");

		if ($controles_iniciados === false) {
			echo mysqli_error();
		}

	} else {

		$controles_iniciados = mysqli_query($conn, "SELECT a.contador_final, a.billetes_buenos,a.id,a.fecha,a.id_orden,a.contador_final_maquina,a.etapa,a.maquina,a.contador_inicial,a.jornada, a.grupo FROM pro_control_menor as a  WHERE a.id_orden = '$id_sorteo' AND a.estado = 'FINALIZADO'  ORDER BY a.maquina, a.fecha ,a.hora_inicial  , a.contador_inicial ASC ");

		if ($controles_iniciados === false) {
			echo mysqli_error($conn);
		}

	}

	?>

<div class=" card border-dark">
<div class="card-body " >

<table class="table table-bordered table-sm" >

<tr>
	<th width="8%">FECHA</th>
	<th>MAQUINA</th>
	<th>SORTEO</th>
	<th>CONTADOR INICIAL</th>
	<th>BILLETES BUENOS</th>
	<th>REPOSICIONES</th>
	<th>BILLETES MALOS</th>
	<th>CONTADOR FINAL</th>
	<th>DIF + </th>
	<th>DIF - </th>
</tr>

<?php

	$t_billetes_para_revision = 0;
	$t_billetes_pruebas_malos = 0;
	$t_billetes_para_reposicion = 0;
	$t_impreso = 0;
	$t_diferencia_positiva = 0;
	$t_diferencia_negativa = 0;

	$contador = 0;

	$conteo_controles = mysqli_num_rows($controles_iniciados);

	while ($reg_controles = mysqli_fetch_array($controles_iniciados)) {

		$id_control = $reg_controles['id'];

		$maquina = $reg_controles['maquina'];
		$fecha = $reg_controles['fecha'];
		$contador_inicial = $reg_controles['contador_inicial'];
		$contador_final_maquina = $reg_controles['contador_final_maquina'];
		$contador_final = $reg_controles['contador_final'];
		$grupo = $reg_controles['grupo'];
		$diferencias = $contador_final - $contador_final_maquina;

//		echo "FECHA: " . $fecha . " | MAQUINA: " . $maquina . " | CONTADOR INICIAL: " . $contador_inicial . " | CONTADOR FINAL: " . $contador_final_maquina . "<br>";

		if ($grupo == "S/N") {
			$billetes_buenos = 0;
			$reposiciones = $reg_controles['billetes_buenos'];
		} else {
			$billetes_buenos = $reg_controles['billetes_buenos'];
			$reposiciones = 0;
		}

		$c_detalle_control_malos = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad_pruebas_malos FROM pro_control_detalle_menor WHERE id_control = '$id_control' AND tipo IN ('B. Malos', 'H. Blancas', 'P. Montaje', 'S/S')  ");
		$ob_detalle_control_malos = mysqli_fetch_object($c_detalle_control_malos);
		$cantidad_pruebas_malos = $ob_detalle_control_malos->cantidad_pruebas_malos;

		$c_detalle_control_reposiciones = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad_reposiciones FROM pro_control_detalle_menor WHERE id_control = '$id_control' AND tipo IN ('Reposiciones')  ");
		$ob_detalle_control_reposiciones = mysqli_fetch_object($c_detalle_control_reposiciones);
		$reposiciones += $ob_detalle_control_reposiciones->cantidad_reposiciones;

		$total_impreso = $billetes_buenos + $cantidad_pruebas_malos;

		if (!isset($indicador_fecha)) {

			$indicador_fecha = $fecha;
			$indicador_contador_inicial = $contador_inicial;
			$indicador_contador_final = $contador_final_maquina;
			$indicador_maquina = $maquina;

			$t_billetes_para_revision += $billetes_buenos;
			$t_billetes_pruebas_malos += $cantidad_pruebas_malos;
			$t_billetes_para_reposicion += $reposiciones;
			$t_impreso += $total_impreso;

			if ($diferencias < 0) {
				$t_diferencia_negativa += $diferencias;
			} else {
				$t_diferencia_positiva += $diferencias;
			}

			$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][0] = $indicador_fecha;
			$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][1] = $indicador_maquina;
			$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][2] = $id_sorteo;
			$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][3] = $indicador_contador_inicial;
			$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][4] = $t_billetes_para_revision;
			$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][5] = $t_billetes_para_reposicion;
			$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][6] = $t_billetes_pruebas_malos;
			$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][7] = $indicador_contador_final;
			$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][8] = $t_diferencia_positiva;
			$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][9] = $t_diferencia_negativa;

		} else {

			if ($indicador_fecha == $fecha AND $indicador_contador_final == $contador_inicial AND $indicador_maquina == $maquina) {

				$indicador_contador_final = $contador_final_maquina;

				$t_billetes_para_revision += $billetes_buenos;
				$t_billetes_pruebas_malos += $cantidad_pruebas_malos;
				$t_billetes_para_reposicion += $reposiciones;
				$t_impreso += $total_impreso;

				if ($diferencias < 0) {
					$t_diferencia_negativa += $diferencias;
				} else {
					$t_diferencia_positiva += $diferencias;
				}

				$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][4] += $billetes_buenos;
				$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][5] += $reposiciones;
				$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][6] += $cantidad_pruebas_malos;
				$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][7] = $indicador_contador_final;

				if ($diferencias < 0) {
					$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][9] += $diferencias;
				} else {
					$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][8] += $diferencias;
				}

			} else {

/*
echo "<tr>";
echo "<td>" . $indicador_fecha . "</td>";
echo "<td>" . $indicador_maquina . "</td>";
echo "<td>" . $id_sorteo . "</td>";
echo "<td>" . $indicador_contador_inicial . "</td>";
echo "<td>" . number_format($t_billetes_para_revision) . "</td>";
echo "<td>" . number_format($t_billetes_para_reposicion) . "</td>";
echo "<td>" . number_format($t_billetes_pruebas_malos) . "</td>";
echo "<td>" . $indicador_contador_final . "</td>";
echo "<td>" . $t_diferencia_positiva . " </td><td> " . $t_diferencia_negativa . "</td>";
echo "</tr>";
 */

				$indicador_fecha = $fecha;
				$indicador_contador_inicial = $contador_inicial;
				$indicador_contador_final = $contador_final_maquina;
				$indicador_maquina = $maquina;

				$diferencias = $contador_final - $contador_final_maquina;

				$t_billetes_para_revision = $billetes_buenos;
				$t_billetes_pruebas_malos = $cantidad_pruebas_malos;
				$t_billetes_para_reposicion = $reposiciones;
				$t_impreso = $total_impreso;

				if ($diferencias < 0) {
					$t_diferencia_negativa = $diferencias;
					$t_diferencia_positiva = 0;
				} else {
					$t_diferencia_negativa = 0;
					$t_diferencia_positiva = $diferencias;
				}

				$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][0] = $indicador_fecha;
				$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][1] = $indicador_maquina;
				$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][2] = $id_sorteo;
				$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][3] = $indicador_contador_inicial;
				$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][4] = $t_billetes_para_revision;
				$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][5] = $t_billetes_para_reposicion;
				$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][6] = $t_billetes_pruebas_malos;
				$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][7] = $indicador_contador_final;
				$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][8] = $t_diferencia_positiva;
				$v_registros[$indicador_fecha . $indicador_contador_inicial . $indicador_maquina][9] = $t_diferencia_negativa;

			}

		}

	}

	if (isset($contador_final)) {
		$diferencias = $contador_final - $contador_final_maquina;
	} else {
		$diferencias = 0;
	}

	if ($diferencias < 0) {
		$t_diferencia_negativa = $diferencias;
		$t_diferencia_positiva = 0;
	} else {
		$t_diferencia_negativa = 0;
		$t_diferencia_positiva = $diferencias;
	}
/*
echo "<tr>";
echo "<td>" . $indicador_fecha . "</td>";
echo "<td>" . $indicador_maquina . "</td>";
echo "<td>" . $id_sorteo . "</td>";
echo "<td>" . $indicador_contador_inicial . "</td>";
echo "<td>" . number_format($t_billetes_para_revision) . "</td>";
echo "<td>" . number_format($t_billetes_para_reposicion) . "</td>";
echo "<td>" . number_format($t_billetes_pruebas_malos) . "</td>";
echo "<td>" . $indicador_contador_final . "</td>";
echo "<td>" . $t_diferencia_positiva . " </td><td> " . $t_diferencia_negativa . "</td>";
echo "</tr>";
 */

	$tt_billetes_buenos = 0;
	$tt_reposiciones = 0;
	$tt_billetes_malos = 0;
	$tt_impreso = 0;
	$tt_diferencias = 0;

	if (isset($v_registros)) {
		$conteo_vector = count($v_registros);

		$i = 0;
		foreach ($v_registros as $registro_control) {
			$i++;

			if (!isset($indicador_inicial)) {

				$indicador_inicial = $registro_control[3];
				$indicador_final = $registro_control[7];
				$indicador_maquina = $registro_control[1];
				$indicador_fecha_i = $registro_control[0];
				$indicador_fecha_f = $registro_control[0];

				$tt_billetes_buenos += $registro_control[4];
				$tt_reposiciones += $registro_control[5];
				$tt_billetes_malos += $registro_control[6];
				$tt_diferencias += $registro_control[8] + $registro_control[9];

				echo "<tr>";
				echo "<td>" . $registro_control[0] . "</td>";
				echo "<td>" . $registro_control[1] . "</td>";
				echo "<td>" . $registro_control[2] . "</td>";
				echo "<td>" . $registro_control[3] . "</td>";
				echo "<td>" . number_format($registro_control[4]) . "</td>";
				echo "<td>" . number_format($registro_control[5]) . "</td>";
				echo "<td>" . number_format($registro_control[6]) . "</td>";
				echo "<td>" . $registro_control[7] . "</td>";
				echo "<td>" . $registro_control[8] . "</td>";
				echo "<td>" . $registro_control[9] . "</td>";
				echo "</tr>";

			} else {

				if ($indicador_final == $registro_control[3] AND $indicador_maquina == $registro_control[1]) {

					$indicador_fecha_f = $registro_control[0];
					$indicador_final = $registro_control[7];

					$tt_billetes_buenos += $registro_control[4];
					$tt_reposiciones += $registro_control[5];
					$tt_billetes_malos += $registro_control[6];
					$tt_diferencias += $registro_control[8] + $registro_control[9];

					echo "<tr>";
					echo "<td>" . $registro_control[0] . "</td>";
					echo "<td>" . $registro_control[1] . "</td>";
					echo "<td>" . $registro_control[2] . "</td>";
					echo "<td>" . $registro_control[3] . "</td>";
					echo "<td>" . number_format($registro_control[4]) . "</td>";
					echo "<td>" . number_format($registro_control[5]) . "</td>";
					echo "<td>" . number_format($registro_control[6]) . "</td>";
					echo "<td>" . $registro_control[7] . "</td>";
					echo "<td>" . $registro_control[8] . "</td>";
					echo "<td>" . $registro_control[9] . "</td>";
					echo "</tr>";

					if ($conteo_vector == $i) {

						echo "</table>";

						echo "<br>";

						echo '<table class="table table-bordered table-sm" >

				<tr>
				<th colspan = "8"  style = "text-align:center">PERIODO DEL ' . $indicador_fecha_i . ' AL  ' . $indicador_fecha_f . ' </th>
				</tr>
				<tr>
					<th>MAQUINA</th>
					<th>MARCADOR INICIAL</th>
					<th>BILLETES PARA REVISION</th>
					<th>BILLETES PRUEBAS Y MALOS</th>
					<th>BILLETES PARA REPOSICION</th>
					<th>DIFERENCIAS</th>
					<th>TOTAL BILLETES IMPRESOS</th>
					<th>MARCADOR FINAL</th>
				</tr>';

						$tt_impreso = $tt_billetes_buenos + $tt_billetes_malos + $tt_reposiciones;

						echo "<tr>";
						echo "<td>" . $indicador_maquina . "</td>";
						echo "<td>" . $indicador_inicial . "</td>";
						echo "<td>" . number_format($tt_billetes_buenos) . "</td>";
						echo "<td>" . number_format($tt_billetes_malos) . "</td>";
						echo "<td>" . number_format($tt_reposiciones) . "</td>";
						echo "<td>" . number_format($tt_diferencias) . "</td>";
						echo "<td>" . number_format($tt_impreso) . "</td>";
						echo "<td>" . $indicador_final . "</td>";
						echo "</tr>";
						echo "</table>";

					}

				} else {

					echo "</table>";

					echo "<br>";

					echo '<table class="table table-bordered table-sm" >

				<tr>
				<th colspan = "8"  style = "text-align:center">PERIODO DEL ' . $indicador_fecha_i . ' AL  ' . $indicador_fecha_f . ' </th>
				</tr>
				<tr>
					<th>MAQUINA</th>
					<th>MARCADOR INICIAL</th>
					<th>BILLETES PARA REVISION</th>
					<th>BILLETES PRUEBAS Y MALOS</th>
					<th>BILLETES PARA REPOSICION</th>
					<th>DIFERENCIAS</th>
					<th>TOTAL BILLETES IMPRESOS</th>
					<th>MARCADOR FINAL</th>
				</tr>';

					$tt_impreso = $tt_billetes_buenos + $tt_billetes_malos + $tt_reposiciones;

					echo "<tr>";
					echo "<td>" . $indicador_maquina . "</td>";
					echo "<td>" . $indicador_inicial . "</td>";
					echo "<td>" . number_format($tt_billetes_buenos) . "</td>";
					echo "<td>" . number_format($tt_billetes_malos) . "</td>";
					echo "<td>" . number_format($tt_reposiciones) . "</td>";
					echo "<td>" . number_format($tt_diferencias) . "</td>";
					echo "<td>" . number_format($tt_impreso) . "</td>";
					echo "<td>" . $indicador_final . "</td>";
					echo "</tr>";
					echo "</table>";

					echo '</div></div><br><div class=" card border-dark"><div class="card-body">';

					echo '<table class="table table-bordered table-sm" >

					<tr>
						<th width="8%">FECHA</th>
						<th>MAQUINA</th>
						<th>SORTEO</th>
						<th>CONTADOR INICIAL</th>
						<th>BILLETES BUENOS</th>
						<th>REPOSICIONES</th>
						<th>BILLETES MALOS</th>
						<th>CONTADOR FINAL</th>
						<th>DIF + </th>
						<th>DIF - </th>
					</tr>';

					echo "<tr>";
					echo "<td>" . $registro_control[0] . "</td>";
					echo "<td>" . $registro_control[1] . "</td>";
					echo "<td>" . $registro_control[2] . "</td>";
					echo "<td>" . $registro_control[3] . "</td>";
					echo "<td>" . number_format($registro_control[4]) . "</td>";
					echo "<td>" . number_format($registro_control[5]) . "</td>";
					echo "<td>" . number_format($registro_control[6]) . "</td>";
					echo "<td>" . $registro_control[7] . "</td>";
					echo "<td>" . $registro_control[8] . "</td>";
					echo "<td>" . $registro_control[9] . "</td>";
					echo "</tr>";

					$tt_billetes_buenos = $registro_control[4];
					$tt_reposiciones = $registro_control[5];
					$tt_billetes_malos = $registro_control[6];
					$tt_diferencias = $registro_control[8] + $registro_control[9];

					$indicador_inicial = $registro_control[3];
					$indicador_final = $registro_control[7];
					$indicador_maquina = $registro_control[1];
					$indicador_fecha_i = $registro_control[0];
					$indicador_fecha_f = $registro_control[0];

					if ($conteo_vector == $i) {

						echo "</table>";

						echo "<br>";

						echo '<table class="table table-bordered table-sm" >

				<tr>
				<th colspan = "8"  style = "text-align:center">PERIODO DEL ' . $indicador_fecha_i . ' AL  ' . $indicador_fecha_f . ' </th>
				</tr>
				<tr>
					<th>MAQUINA</th>
					<th>MARCADOR INICIAL</th>
					<th>BILLETES PARA REVISION</th>
					<th>BILLETES PRUEBAS Y MALOS</th>
					<th>BILLETES PARA REPOSICION</th>
					<th>DIFERENCIAS</th>
					<th>TOTAL BILLETES IMPRESOS</th>
					<th>MARCADOR FINAL</th>
				</tr>';

						$tt_impreso = $tt_billetes_buenos + $tt_billetes_malos + $tt_reposiciones;

						echo "<tr>";
						echo "<td>" . $indicador_maquina . "</td>";
						echo "<td>" . $indicador_inicial . "</td>";
						echo "<td>" . number_format($tt_billetes_buenos) . "</td>";
						echo "<td>" . number_format($tt_billetes_malos) . "</td>";
						echo "<td>" . number_format($tt_reposiciones) . "</td>";
						echo "<td>" . number_format($tt_diferencias) . "</td>";
						echo "<td>" . number_format($tt_impreso) . "</td>";
						echo "<td>" . $indicador_final . "</td>";
						echo "</tr>";
						echo "</table>";

					}

				}

			}

		}

	}

	?>

</div>
</div>
<?php

}

?>