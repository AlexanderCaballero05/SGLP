<?php
require '../../template/header.php';

$sorteos = mysqli_query($conn, "SELECT * FROM sorteos_mayores ORDER BY no_sorteo_may");

?>


<form method="POST">

<section style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >REPORTE DE PRODUCCION POR SALTOS</h2>
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

	$masc = strlen($cantidad_billetes);
	$masc_rec = strlen($registro_inicial);

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
	$b_i = 0;
	$billete_f = 999;
	$b_f = 999;
	$registro = $registro_inicial;
	$registro_i = $registro_inicial;
	$r_i = $registro_i;
	$registro_f = $registro_i - 999;
	$r_f = $registro_f;

	echo "
<div class = 'alert alert-info' align = 'center'>
<h4>PATRONATO NACIONAL DE LA INFANCIA</h4>
<p>SISTEMA DE LOTERIA MAYOR <br>
 Sorteo No. " . $sorteo . " Del " . $fecha . " <br>  Con fecha de caducidad " . $fecha_v . " </p>
</div>
";

	echo "<div align = 'center'><table style = 'width:60%' class= 'table table-hover table-bordered' >";

	echo "<tr>
<th width = '33.33%'></th>
<th width = '33.34%'>Inicial</th>
<th width = '33.33%'>Final</th>
</tr>";

	$acum = 0;
	while ($i < $cantidad_billetes) {

		if ($acumulador_salto == $patron_salto) {
			$indicador = true;

			$b_f = $billete_f - 1000;
			$r_f = $registro_f + 1000;

			$dif = $b_f - $b_i;
			$acum = $acum + $dif + 1;

			$b_i = str_pad($b_i, $masc, '0', STR_PAD_LEFT);
			$b_f = str_pad($b_f, $masc, '0', STR_PAD_LEFT);

			$r_i = str_pad($r_i, $masc_rec, '0', STR_PAD_LEFT);
			$r_f = str_pad($r_f, $masc_rec, '0', STR_PAD_LEFT);

			echo "<td>
Numero <br>
Registro
</td>";
			echo "<td>" . $b_i . "<br>";
			echo $r_i . "</td>";
			echo "<td>" . $b_f . "<br>";
			echo $r_f . "</td>";
			echo "</tr>";

			$registro = $registro - $v_salto[$j] + 1;
			$registro_i = $registro;
			$registro_f = $registro_i - 999;

			$b_i = $b_f + 1;
			$r_i = $registro_i;

			$j++;
			$acumulador_salto = 0;
		}

		if ($indicador == true) {
//echo "<tr style = 'background-color:green;'>";

			$indicador = false;
		} else {
			echo "<tr>";
		}

		$i = $i + 1000;
		$acumulador_salto = $acumulador_salto + 1000;

		$billete_i = $billete_i + 1000;
		$billete_f = $billete_i + 999;
		$registro = $registro - 1000;
		$registro_i = $registro;
		$registro_f = $registro_i - 999;

	}

	$b_f = $billete_f - 1000;
	$r_f = $registro_f + 1000;

	$b_i = str_pad($b_i, $masc, '0', STR_PAD_LEFT);
	$b_f = str_pad($b_f, $masc, '0', STR_PAD_LEFT);

	$r_i = str_pad($r_i, $masc_rec, '0', STR_PAD_LEFT);
	$r_f = str_pad($r_f, $masc_rec, '0', STR_PAD_LEFT);

	if ($acum != $cantidad_billetes) {
		echo "<tr><td>
Numero <br>
Registro
</td>";
		echo "<td>" . $b_i . "<br>";
		echo $r_i . "</td>";
		echo "<td>" . $b_f . "<br>";
		echo $r_f . "</td>";
		echo "</tr>";

		echo "</table></div>";

	}

}

?>


</div>

</div>


</form>