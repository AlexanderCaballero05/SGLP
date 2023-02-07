<?php
require '../../template/header.php';
$sorteos = mysqli_query($conn, "SELECT * FROM sorteos_mayores ORDER BY no_sorteo_may");
?>


<form method="POST">

<section style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >REPORTE DE REGISTROS LOTERIA MAYOR</h2>
<br>
</section>

<br>

<div class="card" style="margin-left: 10px; margin-right: 10px">
<div class="card-header" align="center" id="non-printable">
<div class="input-group" style="margin:10px 0px 10px 0px; width: 50%" >
<div class="input-group-prepend"><span  class="input-group-text">Seleccione un sorteo: </span></div>
 <select class="form-control" name="sorteo">
   <?php
while ($sorteo = mysqli_fetch_array($sorteos)) {
	echo "<option value = '" . $sorteo['id'] . "'>" . $sorteo['no_sorteo_may'] . " -- Fecha " . $sorteo['fecha_sorteo'] . " -- " . "</option>";
}
?>
 </select>
<input type="submit" name="seleccionar" class="btn btn-primary" value = "Seleccionar">
</div>
</div>

<div class="card-body">

<?php

if (isset($_POST['seleccionar'])) {

	$id_s = $_POST['sorteo'];

	$info_mayor = mysqli_query($conn, "SELECT * FROM sorteos_mayores where id = '$id_s' ");
	$value_mayor = mysqli_fetch_object($info_mayor);
	$cantidad_billetes = $value_mayor->cantidad_numeros;

	$registro_inicial = $value_mayor->desde_registro;
	$patron_salto = $value_mayor->patron_salto;
	$sorteo = $value_mayor->no_sorteo_may;
	$fecha = $value_mayor->fecha_sorteo;
	$fecha_v = $value_mayor->fecha_vencimiento;

	$break = round($cantidad_billetes / 1000);
	$break = round($break / 2);
	$parametros_mayor = mysqli_query($conn, "SELECT * FROM sorteos_mayores_produccion where id_sorteo = '$id_s' ");

	$i = 1;
	while ($row = mysqli_fetch_array($parametros_mayor)) {
		$v_salto[$i] = $row['salto'];
		$i++;
	}
	$num_saltos = $cantidad_billetes / $patron_salto;

	$i = 0;
	$j = 1;
	$acumulador_salto = 0;
	$indicador = false;
	$billete_i = 0;
	$billete_f = 999;
	$registro = $registro_inicial;
	$registro_i = $registro_inicial;
	$registro_f = $registro_i - 999;

	echo "
<div class = 'alert alert-info' align = 'center'>
<h4>PATRONATO NACIONAL DE LA INFANCIA</h4>
<p>SISTEMA DE LOTERIA MAYOR<br>
LISTADO DE REGISTROS<br>
 Sorteo No. " . $sorteo . " Del " . $fecha . " <br> Con fecha de caducidad " . $fecha_v . " </p>
</div>
";

	echo "
<table style = 'page-break-after: always;' width='100%'  class = 'table table-bordered'>
<tr>
<th width = '10%'></th>
<th width = '15%'>Desde</th>
<th width = '15%'>Hasta</th>
<th width = '15%'>Desde</th>
<th width = '15%'>Hasta</th>
<th width = '15%'>Desde</th>
<th width = '15%'>Hasta</th>
</tr>";

	echo "<tr><td>
Billete <br>
Registro
</td>";

	$fila = 0;
	$contador = 0;
	while ($i < $cantidad_billetes) {

		if ($acumulador_salto == $patron_salto) {
			$indicador = true;
			$registro = $registro - $v_salto[$j] + 1;
			$registro_i = $registro;
			$registro_f = $registro_i - 999;
			$j++;
			$acumulador_salto = 0;
		}

		if ($contador == $break) {

			$fila = 0;

			echo "</table>";

			echo "
<table  width='100%'  class = 'table table-bordered'>
<tr>
<th width = '10%'></th>
<th width = '15%'>Desde</th>
<th width = '15%'>Hasta</th>
<th width = '15%'>Desde</th>
<th width = '15%'>Hasta</th>
<th width = '15%'>Desde</th>
<th width = '15%'>Hasta</th>
</tr>";

			echo "<tr><td>
Billete <br>
Registro
</td>";

		}

		if ($fila == 3) {
			echo "<tr><td>
Numero <br>
Registro
</td>";
			$fila = 0;
		}

		$billete_i = str_pad($billete_i, 5, '0', STR_PAD_LEFT);
		$billete_f = str_pad($billete_f, 5, '0', STR_PAD_LEFT);

		if ($indicador == true) {
			echo "<td colspan = '2' style = 'background-color:#a9bbc4'>";
			$indicador = false;
		} else {
			echo "<td colspan = '2'>";
		}
		echo "<table  width = '100%'>
<tr >
<td width= '50%'>" . $billete_i . "</td>";
		echo "<td width= '50%'>" . $billete_f . "</td>";
		echo "</tr><tr >";
		echo "<td>" . $registro_i . "</td>";
		echo "<td>" . $registro_f . "</td>
</tr>
</table>
</td>";
//echo "</tr>";
		$i = $i + 1000;
		$acumulador_salto = $acumulador_salto + 1000;

		$billete_i = $billete_i + 1000;
		$billete_f = $billete_i + 999;
		$registro = $registro - 1000;
		$registro_i = $registro;
		$registro_f = $registro_i - 999;

		$fila++;
		$contador++;

	}

	echo "</table>";

}

?>




</div>
</div>



</form>