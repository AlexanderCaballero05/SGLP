<?php

if (isset($_POST['eliminar_respaldo'])) {
	$id = $_POST['eliminar_respaldo'];

	mysqli_query($conn, "DELETE FROM sorteos_mayores_premios WHERE id = '$id' ");

}

if (isset($_POST['guardar_respaldo'])) {

	$id_sorteo = $_POST['id_sorteo'];
	$filas = $_POST['filas2'];
	$id_premio = $_POST['id_premio_modal'];

	$k = 1;
	while ($k <= $filas) {

		if (isset($_POST['monto2' . $k])) {
			$monto = $_POST['monto2' . $k];
			$tipo = $_POST['select2' . $k];
			$descripcion_respaldo = $_POST['descripcion2' . $k];
			$j = 1;

			$monto = str_replace(",", "", $monto);

			mysqli_query($conn, "INSERT INTO sorteos_mayores_premios (sorteos_mayores_id,premios_mayores_id,tipo_premio,monto,respaldo,descripcion_respaldo)
             VALUES ('$id_sorteo','$id_premio','$tipo','$monto','SI','$descripcion_respaldo') ");

		}

		$k++;

	}

	?>

<script type="text/javascript">
  swal({
  title: "",
   text: "Premios asignados correctamente",
    type: "success"
  });
</script>
<?php

}

if (isset($_POST['guardar'])) {

	$id_sorteo = $_POST['id_sorteo'];
	$filas = $_POST['filas'];

	mysqli_query($conn, "DELETE FROM sorteos_mayores_premios WHERE sorteos_mayores_id = '$id_sorteo' AND respaldo != 'SI'  ");

	$k = 1;
	$bandera = true;

	while ($k <= $filas) {
		if (isset($_POST['cantidad' . $k])) {
			$id_premio = $_POST['id_premio' . $k];
			$monto = $_POST['monto' . $k];
			$tipo = $_POST['select' . $k];
			$cantidad = $_POST['cantidad' . $k];
			$desc_acta = $_POST['desc_acta' . $k];
			$j = 1;

			$monto = str_replace(",", "", $monto);

			while ($j <= $cantidad) {

				if ($id_premio == 9 || $id_premio == 10 || $id_premio == 11 || $id_premio == 12) {
					$respaldo = 'terminacion';
				} else {
					$respaldo = '';
				}

				if (mysqli_query($conn, "INSERT INTO sorteos_mayores_premios (sorteos_mayores_id,premios_mayores_id,tipo_premio,monto,respaldo, desc_premio) VALUES ('$id_sorteo','$id_premio','$tipo','$monto', '$respaldo','$desc_acta') ") === false) {
					$bandera = false;
					echo mysql_error();
				}
				$j++;
			}

		}
		$k++;
	}

	if ($bandera == true) {

		mysqli_query($conn, "UPDATE sorteos_mayores SET premios_asignados = 'SI' WHERE id = '$id_sorteo' ");

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
