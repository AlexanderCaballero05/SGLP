<?php

require "../../conexion.php";

$id_sorteo = $_GET['sorteo'];
//$id_sorteo = 1205;

$loteria = $_GET['loteria'];
$billete = $_GET['billete'];
if (isset($_GET['serie'])) {
	$serie = $_GET['serie'];
}
$input = $_GET['input'];

if ($loteria == 1) {

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

	$num_saltos = $billete / $patron_salto;
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
	$registro = $registro - $billete;

	if ($input == "i") {

		?>
<script type="text/javascript">
document.getElementById('registro_i').value = <?php echo $registro; ?>;
</script>
<?php

	} else {

		?>
<script type="text/javascript">
document.getElementById('registro_f').value = <?php echo $registro; ?>;
</script>
<?php

	}

/////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////// LOTERIA MENOR ///////////////////////////////////////

} else {

	$decena = $_GET['decena'];
	$decena--;
	if ($decena > 0) {
		$decena = $decena . "0";
	} else {
		$decena = 0;
	}

	$result = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE id = '$id_sorteo'");

	if ($result != null) {
		while ($row = mysqli_fetch_array($result)) {
			$sorteo = $row['no_sorteo_men'];
			$fecha_sorteo = $row['fecha_sorteo'];
			$series = $row['series'] - 1;
			$desde_registro = $row['desde_registro'];
			$descripcion = $row['descripcion_sorteo_men'];
		}
		$masc = strlen($series);
	}

	$registros = mysqli_query($conn, "SELECT * FROM sorteos_menores_registros WHERE id_sorteo = '$id_sorteo' AND numero  = '$decena' AND serie_inicial <= $billete AND serie_final >= $billete   ");
	if ($registros === false) {
		echo mysqli_error($conn);
	} else {

		$ob_registro = mysqli_fetch_object($registros);
		$registro_inicial = $ob_registro->registro_inicial;
		$registro = $registro_inicial + $billete;

		if ($registro > 99999) {
			$sobrante = $registro - 100000;
			$registro = $sobrante;
		}

	}

	if ($input == "i") {

		?>
<script type="text/javascript">
document.getElementById('registro_i').value = <?php echo $registro; ?>;
</script>
<?php

	} else {

		?>
<script type="text/javascript">
document.getElementById('registro_f').value = <?php echo $registro; ?>;
</script>
<?php

	}

}

?>