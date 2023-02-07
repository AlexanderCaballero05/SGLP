<?php
require '../../template/header.php';

$select_sorteos = mysqli_query($conn, "SELECT * FROM sorteos_mayores ORDER BY no_sorteo_may DESC ");

?>




<form method="POST">

<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >CONTROL DE PRODUCCION DE LOTERIA NACIONAL MAYOR </h2>

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
<select name="sorteo" style="width:25%" class="form-control">
<?php
while ($sorteo = mysqli_fetch_array($select_sorteos)) {
	echo "<option value = '" . $sorteo['id'] . "'>" . $sorteo['no_sorteo_may'] . "</option>";
}
?>
</select>

<div class="input-group-prepend" style="margin-left: 5px;" ><span  class="input-group-text">Fecha Inicio Control: </span></div>
<input type='date' id ="fecha_i" style="width:25%" name = "fecha_inicial" class="form-control" >
<input type="submit" name="seleccionar" style="margin-left: 5px;" class="btn btn-primary" value = "Seleccionar">

</div>

</div>


<br><br>






<?php

if (isset($_POST['seleccionar'])) {
	$id_sorteo = $_POST['sorteo'];

	$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo' ");
	$i_sorteo = mysqli_fetch_object($info_sorteo);
	$numero_sorteo = $i_sorteo->no_sorteo_may;
	$registro_inicial = $i_sorteo->desde_registro;
	$patron_salto = $i_sorteo->patron_salto;
	$fecha_sorteo = $i_sorteo->fecha_sorteo;
	$fecha = $_POST['fecha_inicial'];

	$parametros_mayor = mysqli_query($conn, "SELECT * FROM sorteos_mayores_produccion where id_sorteo = '$id_sorteo' ");

	$i = 1;
	while ($reg = mysqli_fetch_array($parametros_mayor)) {
		$v_salto[$i] = $reg['salto'];
		$i++;
	}

	if ($fecha != '') {
		$fecha = date('Y-m-d', strtotime($fecha));

		$controles_iniciados = mysqli_query($conn, "SELECT a.contador_final,a.billetes_buenos,a.id,a.fecha,a.id_orden,a.contador_final_maquina,a.etapa,a.maquina,a.contador_inicial,a.jornada FROM pro_control as a  WHERE a.id_orden = '$id_sorteo' AND a.fecha  = '$fecha' AND a.estado = 'FINALIZADO' ORDER BY a.fecha ,a.hora_inicial ,a.etapa , a.contador_final, a.billete_inicial, a.maquina ASC");

		if ($controles_iniciados === false) {
			echo mysqli_error();
		}

	} else {

		$controles_iniciados = mysqli_query($conn, "SELECT a.contador_final,a.billetes_buenos,a.id,a.fecha,a.id_orden,a.contador_final_maquina,a.etapa,a.maquina,a.contador_inicial,a.jornada FROM pro_control as a  WHERE a.id_orden = '$id_sorteo' AND a.estado = 'FINALIZADO' ORDER BY a.fecha ,a.hora_inicial ,a.etapa , a.contador_final, a.billete_inicial, a.maquina ASC ");

		if ($controles_iniciados === false) {
			echo mysqli_error();
		}

	}

	$blancos = 0;
	$malos = 0;
	$para_repos = 0;
	$montaje = 0;
	$buenos = 0;
	$s_f = 0;

	$fecha_actual = date("d-m-Y h:m:i a");
	echo "<b>Fecha de consulta: " . $fecha_actual . "</b>";

	while ($control_iniciado = mysqli_fetch_array($controles_iniciados)) {

		$id_detalle_control = $control_iniciado['id'];
		$sobrante_faltante = $control_iniciado['contador_final'] - $control_iniciado['contador_final_maquina'];

		$max_min_contador = mysqli_query($conn, "SELECT fecha, etapa ,jornada,  billetes_buenos,  hora_final, hora_inicial, contador_inicial as minimo,maquina , contador_final_maquina as maximo, billete_inicial, billete_final FROM pro_control WHERE id_orden = '$id_sorteo' AND id = '$id_detalle_control'  ");

		$ob_maximo_menor = mysqli_fetch_object($max_min_contador);
		$max_cont = $ob_maximo_menor->maximo;
		$min_cont = $ob_maximo_menor->minimo;
		$no_maquina = $ob_maximo_menor->maquina;
		$fecha_control = $ob_maximo_menor->fecha;
		$hora_inicial = $ob_maximo_menor->hora_inicial;
		$hora_final = $ob_maximo_menor->hora_final;
		$billetes_buenos = $ob_maximo_menor->billetes_buenos;
		$etapa = $ob_maximo_menor->etapa;
		$jornada = $ob_maximo_menor->jornada;
		$billete_inicial = $ob_maximo_menor->billete_inicial;
		$billete_final = $ob_maximo_menor->billete_final;

		$c_prensistas = mysqli_query($conn, "SELECT * FROM pro_control_prensistas WHERE id_control = '$id_detalle_control' ");

		$concat_prensistas = "";
		$i = 0;
		while ($r_prensistas = mysqli_fetch_array($c_prensistas)) {
			if ($i == 0) {
				$concat_prensistas .= $r_prensistas['nombre'];
			} else {
				$concat_prensistas .= " - " . $r_prensistas['nombre'];
			}
			$i++;
		}

		?>


<div class="card" style="margin-left: 10px; margin-right: 10px; page-break-inside: avoid; page-break-after: auto;"  >
<div class="card-header">
  <h4 style = "text-align: center" >CONTROL DE PRODUCCION MAYOR</h4>
</div>
<div class="card-body">



<table style="font-size: 8pt;page-break-inside: avoid;" class="table table-bordered" >
<tr>
  <th width="25%" style="text-align: center" >
    CONTADOR INICIAL: <?php echo $min_cont; ?>
  </th>
  <th width="25%" style="text-align: center" >
    SORTEO: <?php echo $numero_sorteo . " | " . $fecha_sorteo; ?>

  </th>

<th width="25%" align="center" style="text-align: center" >
  <?php
if ($etapa == "4") {
			echo "Etapa Reposicion";
		} elseif ($etapa == "5") {
			echo "Etapa 2 Para Reposicion";
		} else {
			echo "Etapa " . $etapa;
		}

		?>
 --- MAQUINA: <?php echo $no_maquina; ?>

</th>

  <th width="25%" style="text-align: center" >
CONTADOR FINAL: <?php echo $max_cont; ?>
    <input style="width:50%" type="hidden" id="contador_final_o" name="contador_final_o" value="<?php echo $contador_parcial; ?>" class="" readonly>
  </th>
</tr>
<tr>
  <th style="text-align: center" >

FECHAS:  <?php echo $fecha_control; ?>

  </th>
  <th style="text-align: center" >
H. I.:
  <?php echo $hora_inicial; ?>
  <br>
  H. F.:
  <?php echo $hora_final; ?>

  </th>

  <th style="text-align: center" >

  <?php
if ($etapa == "2") {

			$num_saltos = $billete_inicial / $patron_salto;
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
			$registro = $registro - $billete_inicial;

			echo "Billete/Registro: " . $billete_inicial . "/" . $registro;
			echo "<br>";

			$num_saltos = $billete_final / $patron_salto;
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
			$registro = $registro - $billete_final;

			echo "Billete/Registro: " . $billete_final . "/" . $registro;
		} else {
			echo "Billete/Registro: -/-";
			echo "<br>";
			echo "Billete/Registro: -/-";
		}

		?>


  </th>

  <th colspan="2" style="text-align: center" >
BILLETES BUENOS:
<?php echo $billetes_buenos; ?>

  </th>

</tr>


<tr>
  <th colspan="4" style="text-align: center" >
PRENSISTAS:  <?php echo $concat_prensistas; ?>
  </th>
</tr>

</table>




<div class="table-responsive">
<table style="font-size: 8pt;" id="tabla_control" class="table table-bordered">

  <tr>
    <th colspan="2" style="text-align: center">PRODUCCION</th>
    <th colspan="4" style="text-align: center">PRUEBAS Y MALOS</th>
    <th colspan="3" style="text-align: center"></th>
  </tr>

  <tr>
    <th>Billetes Buenos</th>
    <th>Reposiciones</th>
    <th>Cantidad</th>
    <th>Tipo</th>
    <th>Billete No.<br> Del ~ Al</th>
    <th>Registro <br> Del ~ Al</th>
    <th>Numeradora Parcial</th>
    <th>F/S</th>
    <th>OBSERVACIONES</th>
  </tr>

<?php

		echo "<tr style = 'background-color:#e6e6e6'>
<td>" . $control_iniciado['billetes_buenos'] . "</td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td>" . $control_iniciado['contador_final_maquina'] . "</td>
<td>" . $sobrante_faltante . "</td>
<td></td>
</tr>";

		$detalle = mysqli_query($conn, "SELECT * FROM pro_control_detalle WHERE id_control = '$id_detalle_control' ");
		if ($detalle === false) {
			echo mysqli_error();
		}

		$contador = 0;

		if (!isset($v_contador_inicial[$min_cont]) AND !isset($v_contador_final[$max_cont])) {
			$v_contador_inicial[$min_cont] = $min_cont;
			$v_contador_final[$max_cont] = $max_cont;
			$sumar_a_totales = 1;
			$buenos += $billetes_buenos;
			$s_f += $sobrante_faltante;

		}

		while ($detalle_registro = mysqli_fetch_array($detalle)) {

			if ($sumar_a_totales == 1) {

				if ($detalle_registro['tipo'] == 'H. Blancas') {
					$blancos += $detalle_registro['cantidad'];
				} elseif ($detalle_registro['tipo'] == 'B. Malos') {
					$malos += $detalle_registro['cantidad'];
				} elseif ($detalle_registro['tipo'] == 'P. Montaje') {
					$montaje += $detalle_registro['cantidad'];
				} elseif ($detalle_registro['tipo'] == 'Para Reposicion') {
					$para_repos += $detalle_registro['cantidad'];
				}

			}

			echo "<tr>
    <td></td>
    <td>" . $detalle_registro['reposiciones'] . "</td>
    <td>" . $detalle_registro['cantidad'] . "</td>
    <td>" . $detalle_registro['tipo'] . "</td>
    <td>" . $detalle_registro['de_billete'] . " ~ " . $detalle_registro['a_billete'] . "</td>
    <td>" . $detalle_registro['de_registro'] . " ~ " . $detalle_registro['a_registro'] . "</td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
";

		}

		$sumar_a_totales = 0;

		?>

</table>
</div>
</div>

<div class="card-footer">
<br>
<p align="center">______________________________________________________</p>
<p align="center">PRUEBA 1<br>Controlador de Produccion</p>
</div>

</div>
<br>

<?php

	}

	if ($fecha != '') {
		?>

<div class="card" style="margin-left: 10px;margin-right: 10px">
  <div class="card-body">

<table class="table table-bordered" >
<tr>
  <th style="text-align: center" colspan="5">RESUMEN DE CONTROLES REALIZADOS EN LA FECHA <?php echo $fecha; ?></th>
</tr>
<tr>
<th>BILLETES BUENOS</th>
<th>BILLETES MALOS</th>
<th>HOLAS BLANCAS</th>
<th>PRUEBAS DE MONTAJE</th>
<th>TOTAL</th>
</tr>

<?php

		$tt = $buenos + $malos + $blancos + $montaje;

		if ($s_f < 0) {
			$tt_maquinas = $tt + ($s_f) * (-1);
		} else {
			$tt_maquinas = $tt - $s_f;
		}

		echo "<tr>";
		echo "<td>" . number_format($buenos) . "</td>";
		echo "<td>" . number_format($malos) . "</td>";
		echo "<td>" . number_format($blancos) . "</td>";
		echo "<td>" . number_format($montaje) . "</td>";
		echo "<td>" . number_format($tt) . "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td colspan = '4'><b>TOTAL SEGUN MAQUINAS  </b></td>";
		echo "<td>" . number_format($tt_maquinas) . "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td colspan = '4'><b>FALTANTES / SOBRANTES </b></td>";
		echo "<td>" . number_format($s_f) . "</td>";
		echo "</tr>";

		?>

</table>

  </div>
</div>



<?php
}

}

?>


</form>