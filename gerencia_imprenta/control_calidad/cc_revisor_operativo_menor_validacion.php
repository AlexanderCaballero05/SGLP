<?php

require "../../conexion.php";

$parametros = $_GET['p'];
$decena = $_GET['decena'];
$serie_inicial = $_GET['bi'];
$serie_final = $_GET['bf'];
$status_re = $_GET['re'];

if ($decena != 10) {
	$desc_decena = $decena . "0 - " . $decena . "9";
} else {
	$desc_decena = "Todas";
}

$v_parametros = explode("_", $parametros);
$id_sorteo = $v_parametros[0];
$id_revisor = $v_parametros[1];
$num_asignado = $v_parametros[2];
$num_revision = $v_parametros[3];

$info_mayor = mysqli_query($conn, "SELECT * FROM sorteos_menores where id = '$id_sorteo' ");
$value_mayor = mysqli_fetch_object($info_mayor);
$cantidad_series = $value_mayor->series;
$desde_registro = $value_mayor->desde_registro;

if ($num_revision == 1) {

	$inventario_revisor = mysqli_query($conn, "SELECT * FROM cc_revisores_sorteos_menores WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$id_revisor' AND numero = '$num_asignado' ");

	$a = 0;
	while ($reg_inventario_revisor = mysqli_fetch_array($inventario_revisor)) {

		$v_asignado_i[$a] = $reg_inventario_revisor['serie_inicial'];
		$v_asignado_f[$a] = $reg_inventario_revisor['serie_final'];

		$a++;
	}

}

$verificar_reprobado = $num_revision + 1;
$loteria_reporbada = mysqli_query($conn, " SELECT distinct(CONCAT(numero,'-',serie)) as concatenado , numero,serie,estado,id FROM cc_revisores_sorteos_menores_control WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$id_revisor' AND numero_revision = '$verificar_reprobado' ");

$i = 0;
while ($reg_loteria_reporbada = mysqli_fetch_array($loteria_reporbada)) {
	$v_reprobado[$i] = $reg_loteria_reporbada['concatenado'];
	$i++;
}

/////////////////////////////////////////////////////
////////////// PARAMETROS DE REGISTRO ///////////////
$saltos = mysqli_query($conn, "SELECT * FROM sorteos_menores_produccion WHERE id_sorteo = '$id_sorteo' ");

$i = 0;
while ($reg_saltos = mysqli_fetch_array($saltos)) {
	$v_decena[$i] = $reg_saltos['decena'];
	$v_saltos[$i] = $reg_saltos['salto'];
	$i++;
}

if ($decena == 10) {

	echo "<table class = 'table table-bordered'>";
	echo "<tr><th>Decena</th><th>Serie</th><th>Registro</th><th>R. E.</th></tr>";

	$decena = 0;
	$cantidad = 0;
	$asignado = true;
	$reprobado_historico = true;

	while ($decena <= 9) {

		$r = 0;
		$acum_registro = $desde_registro;
		while ($r < $decena) {
			$acum_registro += $cantidad_series + $v_saltos[$r];
			$r++;
		}

////////////// PARAMETROS DE REGISTRO ///////////////
		/////////////////////////////////////////////////////

		$h = 0;
		$j = 1;

		$serie_inicial = $_GET['bi'];
		$serie_final = $_GET['bf'];

		while ($serie_inicial <= $serie_final) {

			$registro = $acum_registro + $serie_inicial;

			if ($registro > 99999) {
				$registro = $registro - 100000;
			}

			$a = 0;
			while (isset($v_asignado_i[$a])) {

				if ($serie_inicial < $v_asignado_i[$a] OR $serie_inicial > $v_asignado_f[$a]) {
					$asignado = false;
				}

				$a++;
			}

			echo "<tr>";

			if (isset($v_reprobado[$h])) {

				if (in_array($decena . '-' . $serie_inicial, $v_reprobado)) {

					echo "<td colspan = '4'><div class = 'alert alert-danger'> El numero " . $decena . "0 - " . $decena . "9 con serie " . $serie_inicial . " ya fue reprobada.</div></td>";

				} else {

					$cantidad++;

					echo "<td><input type = 'text' class = 'form-control' name = 'decena_reprobado[]'  value  = '" . $decena . "0 - " . $decena . "9' readonly></td>";
					echo "<td><input type = 'text' class = 'form-control' name = 'serie_reprobado[]'  value  = '" . $serie_inicial . "' readonly></td>";
					echo "<td><input type = 'text' class = 'form-control' name = 'registro_reprobado[]' value  = '" . $registro . "' readonly></td>";

					if ($status_re === "true") {
						echo "<td><input type = 'checkbox' name = 're_reprobado" . $j . "' class = 'form-control' checked></input> </td>";
					} else {
						echo "<td><input type = 'checkbox' name = 're_reprobado" . $j . "' class = 'form-control' > </input></td>";
					}

				}

			} else {

				$cantidad++;

				echo "<td><input type = 'text' class = 'form-control' name = 'decena_reprobado[]'  value  = '" . $decena . "0 - " . $decena . "9' readonly></td>";
				echo "<td><input type = 'text' class = 'form-control' name = 'serie_reprobado[]'  value  = '" . $serie_inicial . "' readonly></td>";
				echo "<td><input type = 'text' class = 'form-control' name = 'registro_reprobado[]' value  = '" . $registro . "' readonly></td>";

				if ($status_re === "true") {
					echo "<td><input type = 'checkbox' name = 're_reprobado" . $j . "' class = 'form-control' checked></input> </td>";
				} else {
					echo "<td><input type = 'checkbox' name = 're_reprobado" . $j . "' class = 'form-control' > </input></td>";
				}

			}

			echo "</tr>";

			$j++;
			$h++;
			$serie_inicial++;
		}

		$decena++;

	}

	if ($asignado === true) {

		?>

<script type="text/javascript">
	document.getElementById('reprobar_rango').disabled = false;
</script>

<?php

	} else {

		?>

<script type="text/javascript">
document.getElementById('reprobar_rango').disabled = true;

  swal({
  title: "Al menor uno de las series a reprobar no le fue asignado.",
   text: "",
    type: "error"
  });

</script>

<?php

	}

	echo "<tr><th >TOTAL SERIES A REPROBAR</th><th colspan = '3' style = 'text-align :center'>" . $cantidad . "</th></tr>";
	echo "</table>";

} else {

	$r = 0;
	$acum_registro = $desde_registro;
	while ($r < $decena) {
		$acum_registro += $cantidad_series + $v_saltos[$r];
		$r++;
	}

////////////// PARAMETROS DE REGISTRO ///////////////
	/////////////////////////////////////////////////////

	echo "<table class = 'table table-bordered'>";
	echo "<tr><th>Decena</th><th>Serie</th><th>Registro</th><th>R. E.</th></tr>";

	$h = 0;
	$j = 1;
	$cantidad = $serie_final - $serie_inicial + 1;
	$asignado = true;

	while ($serie_inicial <= $serie_final) {

		$registro = $acum_registro + $serie_inicial;

		if ($registro > 99999) {
			$registro = $registro - 100000;
		}

		$a = 0;
		while (isset($v_asignado_i[$a])) {

			if ($serie_inicial < $v_asignado_i[$a] OR $serie_inicial > $v_asignado_f[$a]) {
				$asignado = false;
			}

			$a++;
		}

		echo "<tr>";

		if (isset($v_reprobado[$h])) {

			if (in_array($decena . '-' . $serie_inicial, $v_reprobado)) {

				echo "<td colspan = '4'><div class = 'alert alert-danger'> El numero " . $desc_decena . " con serie " . $serie_inicial . " ya fue reprobada.</div></td>";

			} else {

				echo "<td><input type = 'text' class = 'form-control' name = 'decena_reprobado[]'  value  = '" . $desc_decena . "' readonly></td>";
				echo "<td><input type = 'text' class = 'form-control' name = 'serie_reprobado[]'  value  = '" . $serie_inicial . "' readonly></td>";
				echo "<td><input type = 'text' class = 'form-control' name = 'registro_reprobado[]' value  = '" . $registro . "' readonly></td>";

				if ($status_re === "true") {
					echo "<td><input type = 'checkbox' name = 're_reprobado" . $j . "' class = 'form-control' checked></input> </td>";
				} else {
					echo "<td><input type = 'checkbox' name = 're_reprobado" . $j . "' class = 'form-control' > </input></td>";
				}

			}

		} else {

			echo "<td><input type = 'text' class = 'form-control' name = 'decena_reprobado[]'  value  = '" . $desc_decena . "' readonly></td>";
			echo "<td><input type = 'text' class = 'form-control' name = 'serie_reprobado[]'  value  = '" . $serie_inicial . "' readonly></td>";
			echo "<td><input type = 'text' class = 'form-control' name = 'registro_reprobado[]' value  = '" . $registro . "' readonly></td>";

			if ($status_re === "true") {
				echo "<td><input type = 'checkbox' name = 're_reprobado" . $j . "' class = 'form-control' checked></input> </td>";
			} else {
				echo "<td><input type = 'checkbox' name = 're_reprobado" . $j . "' class = 'form-control' > </input></td>";
			}

		}

		echo "</tr>";

		$j++;
		$h++;
		$serie_inicial++;
	}

	echo "<tr><th >TOTAL SERIES A REPROBAR</th><th colspan = '3' style = 'text-align :center'>" . $cantidad . "</th></tr>";
	echo "</table>";

	if ($asignado === true) {

		?>

<script type="text/javascript">
	document.getElementById('reprobar_rango').disabled = false;
</script>

<?php

	} else {

		?>

<script type="text/javascript">
document.getElementById('reprobar_rango').disabled = true;

  swal({
  title: "Al menor uno de las series a reprobar no le fue asignado.",
   text: "",
    type: "error"
  });

</script>

<?php

	}

}

?>
