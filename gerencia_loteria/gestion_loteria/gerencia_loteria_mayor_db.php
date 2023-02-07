<?php

if (isset($_POST['eliminar'])) {
	if (!empty($_POST['id_oculto'])) {
		$id_reg = $_POST['id_oculto'];

		if (mysqli_query($conn, "DELETE FROM sorteos_mayores WHERE id = '$id_reg' ") === TRUE) {
			?>
<script type="text/javascript">
swal("Se elimino el registro correctamente", "", "success");
</script>
<?php
} else {
			// echo mysql_error();
			?>
<script type="text/javascript">
swal("Error, Este sorteo no puede eliminarse ya que ha sido procesado por otros departamentos", "", "error");
</script>
<?php

		}

	}
}

if (isset($_POST['guardar'])) {

	if (empty($_POST['id_oculto'])) {

		$sorteo = $_POST['sorteo'];
		$fecha_sorteo = $_POST['fecha_sorteo'];
		$fecha_sorteo = date('Y-m-d', strtotime($fecha_sorteo));

		$fecha_vencimiento = $_POST['fecha_vencimiento'];
		$fecha_vencimiento = date('Y-m-d', strtotime($fecha_vencimiento));
		$lugar_captura = $_POST['lugar'];

		$descripcion = $_POST['descripcion'];
		$cantidad_billetes = $_POST['c_billetes'];
		$precio = $_POST['precio'];
		if (isset($_POST['decimos'])) {
			$dec = 'SI';
		} else {
			$dec = 'NO';
		}

		if (mysqli_query($conn, "INSERT INTO sorteos_mayores (id,no_sorteo_may,fecha_sorteo,descripcion_sorteo_may,cantidad_numeros,estado_sorteo,precio_unitario,fecha_vencimiento,decimos, lugar_captura) VALUES
  ('$sorteo','$sorteo', '$fecha_sorteo','$descripcion','$cantidad_billetes', 'PENDIENTE PRODUCCION','$precio','$fecha_vencimiento','$dec' , '$lugar_captura')  ") === TRUE) {
			$inserto = 0;

			$consulta_entidades = mysqli_query($conn, "SELECT * FROM empresas WHERE estado = 'ACTIVO' ");

			while ($reg_consulta_entidades = mysqli_fetch_array($consulta_entidades)) {

				$id_entidad = $reg_consulta_entidades['id'];
				mysqli_query($conn, "INSERT INTO empresas_estado_venta (id_empresa,id_sorteo,cod_producto,estado_venta) VALUES ('$id_entidad', '$sorteo', '1','D') ");

			}

		} else {
			$inserto = 1;
			echo mysqli_error($conn);
		}

		if ($inserto == 0) {
			?>
<script type="text/javascript">
swal({
     title: "",
     text: "Sorteo registrado correctamente, Por favor establezca los premios para el mismo",
     type: "success",
     timer: 7000
     });
</script>

<?php
} else {

			?>
<script type="text/javascript">
  swal("Error inesperado, por favor vuelva a intentarlo", "", "error");
</script>
<?php
}

	} else {

		$id_oculto = $_POST['id_oculto'];
		$sorteo = $_POST['sorteo'];
		$fecha_sorteo = $_POST['fecha_sorteo'];
		$fecha_sorteo = date('Y-m-d', strtotime($fecha_sorteo));

		$fecha_vencimiento = $_POST['fecha_vencimiento'];
		$fecha_vencimiento = date('Y-m-d', strtotime($fecha_vencimiento));

		$descripcion = $_POST['descripcion'];
		$cantidad_billetes = $_POST['c_billetes'];
		$precio = $_POST['precio'];
		$lugar_captura = $_POST['lugar'];


		if (isset($_POST['decimos'])) {
			$dec = 'SI';
		} else {
			$dec = 'NO';
		}

		if (mysqli_query($conn, "UPDATE sorteos_mayores SET id = '$sorteo',  no_sorteo_may = '$sorteo', fecha_sorteo = '$fecha_sorteo', descripcion_sorteo_may = '$descripcion' , cantidad_numeros = '$cantidad_billetes', precio_unitario = '$precio' , fecha_vencimiento = '$fecha_vencimiento', decimos = '$dec' , lugar_captura = '$lugar_captura' WHERE id = '$id_oculto' ") === TRUE) {
			$inserto = 0;

			?>

<script type="text/javascript">
	swal("Se realizaron los cambios correctamente", "", "success");
</script>
<?php

		} else {
			$inserto = 1;
			echo mysqli_error($conn);
			?>
<script type="text/javascript">
	swal("Error inesperado, por favor vuelva a intentarlo", "", "error");
</script>
<?php

		}

	}

}

?>