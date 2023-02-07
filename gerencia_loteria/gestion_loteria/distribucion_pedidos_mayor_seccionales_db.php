<?php

$sorteos_seleccion = mysqli_query($conn, "SELECT * FROM sorteos_mayores ORDER BY id DESC ");

$empresas = mysqli_query($conn, "SELECT * FROM empresas WHERE estado = 'ACTIVO' ");

///////////////////////// Si se selecciono un sorteo ////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////////////////

if (isset($_POST['guardar_distribucion'])) {

	$id_sorteo = $_POST['id_sorteo_oculto'];
	$id_empresa = $_POST['id_empresa_oculto'];
	$id_seccional = $_POST['id_seccional'];

	$paquete_inicial = $_POST['paquete_inicial'];
	$paquete_final = $_POST['paquete_final'];
	$cantidad = $_POST['cantidad'];

	$asignacion = mysqli_query($conn, "UPDATE sorteos_mezclas SET id_seccional = '$id_seccional'  WHERE id_sorteo = '$id_sorteo' AND num_mezcla BETWEEN '$paquete_inicial' AND '$paquete_final' AND id_empresa = '$id_empresa' ");

	if ($asignacion === FALSE) {

		echo mysqli_error();

		?>
<script type="text/javascript">

swal({
  title: "",
  text: "Error inesperado, por favor intente nuevamente.",
  icon: "error",
  buttons: false,
  dangerMode: false,
})
.then(() => {
window.location.href = './screen_distribucion_pedidos_mayor_seccionales.php';
});

</script>
<?php

	} else {

		?>
<script type="text/javascript">
  swal({
  title: "",
  text: "Paquetes asignados correctamente",
  type: "success"
})
.then(() => {
window.location.href = './screen_distribucion_pedidos_mayor_seccionales.php';
});

</script>
<?php

	}

}

if (isset($_POST['borrar_distribucion'])) {

	$id_sorteo = $_POST['id_sorteo_oculto'];
	$id_seccional = $_POST['borrar_distribucion'];

	if (mysqli_query($conn, "UPDATE sorteos_mezclas SET id_seccional = NULL  WHERE id_sorteo = '$id_sorteo' AND id_seccional = '$id_seccional'  ") === TRUE) {
		echo "<div class = 'alert alert-success'>
Distribucion eliminada correctamente.
</div>";
	} else {
		echo "<div class = 'alert alert-danger'>Error inesperado, por favor reportarlo a la unidad de informatica.</div>";
		echo mysqli_error();
	}

}

?>