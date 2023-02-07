<?php
require '../../template/header.php';

$select_sorteos = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE control_calidad = 'SI' ORDER BY no_sorteo_may DESC ");

?>




<form method="POST">


<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h4 align="center" style="color:black;" >ACTA DE TRITURACION DE LOS BILLETES EMITIDOS PARA REPOSICION</h4>

<?php

if (isset($_POST['seleccionar'])) {
	echo '<h4 align="center" style="color:black;" >SORTEO ' . $_POST['sorteo'] . ' </h4>';

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

<input type="submit" name="seleccionar" style="margin-left: 5px;" class="btn btn-primary" value = "Seleccionar">

</div>

</div>


<br><br>



<?php

if (isset($_POST['seleccionar'])) {
	$id_sorteo = $_POST['sorteo'];

	$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo' ");
	$ob_sorteo = mysqli_fetch_object($info_sorteo);
	$no_sorteo = $ob_sorteo->no_sorteo_may;
	$fecha_sorteo = $ob_sorteo->fecha_sorteo;
	$cantidad_billetes = $ob_sorteo->cantidad_numeros;
	$registro_inicial = $ob_sorteo->desde_registro;
	$patron_salto = $ob_sorteo->patron_salto;
	$fecha_vencimiento = $ob_sorteo->fecha_vencimiento;

	$fecha_actual = date("d-m-Y h:m:i a");
	echo "Fecha de emisión: <u>" . $fecha_actual . "</u>";

	?>


<table  class='table table-bordered' id='detalle_revisor' border = '1' style= 'width:100%'>
  <thead>
    <tr>
      <th style="width:25%">Numero de Billete</th>
      <th style="width:25%">Registro</th>
      <th style="width:25%">Cantidad</th>
      <th style="width:25%">R. E.</th>
    </tr>
  </thead>
  <tbody>

<?php

	$inventario_rechazado = mysqli_query($conn, " SELECT billete , registro, especial FROM cc_revisores_sorteos_mayores_control   WHERE id_sorteo = '$id_sorteo'  AND  numero_revision = '2'  ORDER BY billete ASC ");

	$i = 0;
	while ($reg_inventerio_rechazado = mysqli_fetch_array($inventario_rechazado)) {

		$v_billete[$i] = $reg_inventerio_rechazado['billete'];
		$v_registro[$i] = $reg_inventerio_rechazado['registro'];
		$v_re[$i] = $reg_inventerio_rechazado['especial'];

		$i++;
	}

	$i = 0;
	$j = 0;
	$tt = 0;
	$tt_re = 0;
	$tt_n = 0;

	while (isset($v_billete[$i])) {

		if (isset($v_billete[$i + 1])) {

			if ($v_billete[$i] + 1 == $v_billete[$i + 1]) {

				if ($v_re[$i] == $v_re[$i + 1]) {

				} else {

					$cantidad = $v_billete[$i] - $v_billete[$j] + 1;
					echo "<tr>";
					echo "<td>" . str_pad((string) $v_billete[$j], 5, "0", STR_PAD_LEFT) . " - " . str_pad((string) $v_billete[$i], 5, "0", STR_PAD_LEFT) . "</td>";
					echo "<td>" . $v_registro[$j] . " - " . $v_registro[$i] . "</td>";
					if ($v_re[$i] == "NO") {
						echo "<td>" . $cantidad . "</td>";
						echo "<td></td>";
						$tt_n += $cantidad;
					} else {
						echo "<td></td>";
						echo "<td>" . $cantidad . "</td>";
						$tt_re += $cantidad;
					}
					echo "</tr>";

					$j = $i + 1;
					$tt += $cantidad;

				}

			} else {

				$cantidad = $v_billete[$i] - $v_billete[$j] + 1;
				echo "<tr>";
				echo "<td>" . str_pad((string) $v_billete[$j], 5, "0", STR_PAD_LEFT) . " - " . str_pad((string) $v_billete[$i], 5, "0", STR_PAD_LEFT) . "</td>";
				echo "<td>" . $v_registro[$j] . " - " . $v_registro[$i] . "</td>";
				if ($v_re[$i] == "NO") {
					echo "<td>" . $cantidad . "</td>";
					echo "<td></td>";
					$tt_n += $cantidad;
				} else {
					echo "<td></td>";
					echo "<td>" . $cantidad . "</td>";
					$tt_re += $cantidad;
				}
				echo "</tr>";

				$j = $i + 1;
				$tt += $cantidad;

			}

		} else {

			$cantidad = $v_billete[$i] - $v_billete[$j] + 1;
			echo "<tr>";
			echo "<td>" . str_pad((string) $v_billete[$j], 5, "0", STR_PAD_LEFT) . " - " . str_pad((string) $v_billete[$i], 5, "0", STR_PAD_LEFT) . "</td>";
			echo "<td>" . $v_registro[$j] . " - " . $v_registro[$i] . "</td>";
			if ($v_re[$i] == "NO") {
				echo "<td>" . $cantidad . "</td>";
				echo "<td></td>";
				$tt_n += $cantidad;
			} else {
				echo "<td></td>";
				echo "<td>" . $cantidad . "</td>";
				$tt_re += $cantidad;
			}
			echo "</tr>";

			$j = $i + 1;
			$tt += $cantidad;

		}

		$i++;
	}

	echo "<tr>";
	echo "<td align = 'center' colspan = '2'><b>TOTAL DEL SORTEO</b></td>";
	echo "<td align = 'center' ><b>" . $tt_n . "</b></td>";
	echo "<td align = 'center' ><b>" . $tt_re . "</b></td>";
	echo "</tr>";

	?>

</tbody>
</table>

<?php

}

?>

</form>
