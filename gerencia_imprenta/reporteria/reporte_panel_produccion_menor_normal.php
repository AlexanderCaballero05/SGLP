<?php
require '../../template/header.php';

$sorteos = mysqli_query($conn, "SELECT * FROM sorteos_menores ORDER BY no_sorteo_men DESC");

?>


<form method="POST">

<section  style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >REPORTE DE PRODUCCION DE LOTERIA MENOR</h2>
<br>
</section>

<br>

<div class="card" id="non-printable">
  <div class="card-header" align="center">
<div class="input-group" style="margin:10px 0px 10px 0px; width: 50%" >
<div class="input-group-prepend"><span  class="input-group-text">Seleccione un sorteo: </span></div>
 <select class="form-control" name="sorteo">
   <?php
while ($sorteo = mysqli_fetch_array($sorteos)) {
	echo "<option value = '" . $sorteo['id'] . "'>" . $sorteo['no_sorteo_men'] . " -- Fecha " . $sorteo['fecha_sorteo'] . " -- " . "</option>";
}
?>
 </select>
<input type="submit" name="seleccionar" class="btn btn-primary" value = "Seleccionar">
</div>
  </div>
<br>
</div>


<?php

if (isset($_POST['seleccionar'])) {

	$id_sorteo = $_POST['sorteo'];

	$result = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE id = '$id_sorteo'");

	if ($result != null) {
		while ($row = mysqli_fetch_array($result)) {
			$sorteo = $row['no_sorteo_men'];
			$fecha_sorteo = $row['fecha_sorteo'];
			$fecha_sorteo_v = $row['vencimiento_sorteo'];
			$series = $row['series'] - 1;
			$desde_registro = $row['desde_registro'];
			$descripcion = $row['descripcion_sorteo_men'];
		}
		$masc = strlen($series);
	}

	$saltos = mysqli_query($conn, "SELECT * FROM sorteos_menores_produccion WHERE id_sorteo = '$id_sorteo' ");
	if ($saltos === false) {
		echo mysqli_error($conn);
	}
	$i = 1;
	while ($reg_saltos = mysqli_fetch_array($saltos)) {
		$v_saltos[$i] = $reg_saltos['salto'];
		$v_decena[$i] = $reg_saltos['decena'];
		$i++;
	}

	$max_extra = mysqli_query($conn, "SELECT MAX(cantidad) as maximo FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo'");
	if (mysqli_num_rows($max_extra) == 0) {
		$cantidad_extra_mayor = 0;
	} else {
		$ob_extra = mysqli_fetch_object($max_extra);
		$cantidad_extra_mayor = $ob_extra->maximo;
	}

	$result2 = mysqli_query($conn, "SELECT * FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo' ORDER BY numero  ");

	echo "<div style = 'page-break-inside:avoid'>";
	echo "<div class = 'alert alert-info' >";
	echo '<h3 align="center">Patronato Nacional de la Infancia</h3>
<p align="center">Departamento de Produccion<br>
Centro de Registro para Impresion de Loteria Menor<br>
Sorteo # ' . $sorteo . ' del ' . $fecha_sorteo . ' <br> Con fecha de caducidad ' . $fecha_sorteo_v . '</p>
</div>';

	?>
<div class = 'alert alert-info'>
<p align = 'center'>PRODUCCION NORMAL</p>
</div>

<div style = 'page-break-after: always;' >
 <table class="table table-hover table-bordered">
        <thead>
            <tr>
            <th width="15%">Numeros</th>
            <th width="15%">Series</th>
            <th width="15%">Registro Inicial</th>
            <th width="15%">Registro Final</th>
            </tr>
        </thead>
        <tbody>

<?php

	if (isset($desde_registro)) {

		$i = 0;
		$n_inicial = 0;
		$n_final = 0;
		$registro = $desde_registro;
		$registro_inicial = $desde_registro;
		$registro_final = $registro_inicial + $series;

		while ($i < 10) {

			$n_inicial = $i * 10;
			$n_final = $n_inicial + 9;

			if (isset($v_saltos[$i])) {
				$registro_adicional = $v_saltos[$i];
			} else {
				$registro_adicional = 0;
			}

			$registro_inicial = $registro_inicial + $registro_adicional;
			$registro_final = $registro_inicial + $series;

			if ($registro_inicial > 99999) {
				$sobrante = $registro_inicial - 100000;
				$registro_inicial = $sobrante;
			}

			if ($registro_final > 99999) {
				$sobrante = $registro_final - 100000;
				$registro_final = $sobrante;
			}

			$n_inicial = str_pad($n_inicial, 2, '0', STR_PAD_LEFT);
			$n_final = str_pad($n_final, 2, '0', STR_PAD_LEFT);

			$registro_inicial = str_pad($registro_inicial, 5, '0', STR_PAD_LEFT);
			$registro_final = str_pad($registro_final, 5, '0', STR_PAD_LEFT);

			echo "<tr>
<td>" . $n_inicial . " - " . $n_final . "</td>
<td>0000 - " . $series . "</td>
<td>" . $registro_inicial . "</td>
<td>" . $registro_final . "</td>
</tr>";

			$i = $i + 1;

			if (isset($v_saltos[1])) {
				if ($v_saltos[1] == 0) {
					$registro_inicial = $registro_final + 1 + $cantidad_extra_mayor;
				} else {
					$registro_inicial = $registro_final + 1;
				}
			} else {
				$registro_inicial = $registro_final + 1 + $cantidad_extra_mayor;
			}

			$registro_final = $registro_inicial + $series;

		}

	}

	?>

</tbody>
</table>
</div>


<?php

	echo "</div>";

	$cantidades = mysqli_query($conn, "SELECT DISTINCT grupo,cantidad  FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo' ORDER BY grupo ASC ");

	while ($registro_cantidad = mysqli_fetch_array($cantidades)) {
		$grupo = $registro_cantidad['grupo'];
		$cantidad = $registro_cantidad['cantidad'];

		echo "<div class = 'alert alert-info' style = 'page-break-before: always;'>";
		echo '<h3 align="center">Patronato Nacional de la Infancia</h3>
<p align="center">Departamento de Produccion<br>
Centro de Registro para Impresion de Loteria Menor<br>
Sorteo # ' . $sorteo . ' de Fecha ' . $fecha_sorteo . '</p>
</div>';

		echo "<div class = 'alert alert-info'>
<p style = 'page-break-inside:auto' align = 'center'>PRODUCCION EXTRA</p>
</div>
";

		echo ' <table style = "page-break-after: always;" class="table table-hover table-bordered">
';

		echo "<tr>
      <td colspan = '3'><h3 align = 'center'>Grupo No. " . $grupo . " Cantidad " . $cantidad . "</h3></td>
      <tr>
            <th >Numeros</th>
            <th >Series</th>
            <th >Registros</th>
      </tr>

      ";

		$numeros_extras = mysqli_query($conn, "SELECT * FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo' AND grupo = '$grupo' ORDER BY  numero ASC, serie_inicial ASC ");

		$conteo_extras = mysqli_num_rows($numeros_extras);

		while ($numero_extra = mysqli_fetch_array($numeros_extras)) {
			$numero = $numero_extra['numero'];
			$serie_inicial_extra = $numero_extra['serie_inicial'];
			$registro_inicial_extra = $numero_extra['registro_inicial'];

			if ($registro_inicial_extra > 99999) {
				$registro_inicial_extra = $registro_inicial_extra - 100000;
			}

			$serie_final_extra = $serie_inicial_extra + $cantidad - 1;
			$registro_final_extra = $registro_inicial_extra + $cantidad - 1;
			if ($registro_final_extra > 99999) {
				$registro_final_extra = $registro_final_extra - 100000;
			}

			$numero = str_pad($numero, 2, '0', STR_PAD_LEFT);
			$registro_inicial_extra = str_pad($registro_inicial_extra, 5, '0', STR_PAD_LEFT);
			$registro_final_extra = str_pad($registro_final_extra, 5, '0', STR_PAD_LEFT);

			echo "<tr>
      <td>" . $numero . "</td>
      <td>" . $serie_inicial_extra . " - " . $serie_final_extra . "</td>
      <td>" . $registro_inicial_extra . " - " . $registro_final_extra . "</td>
      <tr>";

		}

		echo "</tbody>
      </table>";

	}

}

?>

</form>