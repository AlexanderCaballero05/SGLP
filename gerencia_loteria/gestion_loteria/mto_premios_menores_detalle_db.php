<?php

if (isset($_POST['guardar'])) {

	$id_sorteo = $_POST['id_sorteo'];
	$filas = $_POST['filas'];

	mysqli_query($conn, "DELETE FROM sorteos_menores_premios WHERE sorteos_menores_id = '$id_sorteo'    ");

	$k = 1;
	$bandera = true;

	while ($k <= $filas) {
		if (isset($_POST['cantidad' . $k])) {
			$id_premio = $_POST['id_premio' . $k];
			$monto = $_POST['monto' . $k];

			$monto = str_replace(",", "", $monto);

			$tipo = $_POST['select' . $k];
			$cantidad = $_POST['cantidad' . $k];
			$j = 1;
			while ($j <= $cantidad) {
				if (mysqli_query($conn, "INSERT INTO sorteos_menores_premios (sorteos_menores_id,premios_menores_id,tipo_premio,monto) VALUES ('$id_sorteo','$id_premio','$tipo','$monto') ") === false) {
					$bandera = false;
				}

				$j++;
			}
		}
		$k++;
	}

	if ($bandera == true) {

		mysqli_query($conn, "UPDATE sorteos_menores SET premios_asignados = 'SI' WHERE id = '$id_sorteo' ");

		?>
<script type="text/javascript">
  swal({
  title: "",
   text: "Premios asignados correctamente",
    type: "success"
  });
</script>
<?php
} else {
		?>
<script type="text/javascript">
  swal({
  title: "",
   text: "Error inesperado por favor vuelva a intentarlo",
    type: "error"
  });
</script>
<?php
}

}

?>