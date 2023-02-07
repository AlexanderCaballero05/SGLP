<?php

/////////////////////////   Eliminar Sorteo    //////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////

if (isset($_POST['eliminar'])) {
	if (!empty($_POST['id_oculto'])) {
		$id_reg = $_POST['id_oculto'];

		mysqli_query($conn, "DELETE FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_reg' ");

		mysqli_query($conn, "DELETE FROM `sorteos_menores_premios` WHERE  sorteos_menores_id = '$id_reg' ");

		if (mysqli_query($conn, "DELETE FROM sorteos_menores WHERE id = '$id_reg' ") === TRUE) {
			?>
<script type="text/javascript">
swal("Se elimino el registro correctamente", "", "success");
</script>
<?php
} else {
			?>
<script type="text/javascript">
swal("Error, Debe seleccionar el registro a eliminar", "", "error");
</script>
<?php
}

	}
}

/////////////////////////////////// fin //////////////////////////////////////////////////////

/////////////////////////   Guardar Sorteo    //////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////

if (isset($_POST['guardar'])) {

	$sorteo = $_POST['sorteo'];

	$fecha_sorteo = $_POST['fecha_sorteo'];
	$fecha_sorteo = date('Y-m-d', strtotime($fecha_sorteo));

	$fecha_vencimiento = $_POST['fecha_vencimiento'];
	$fecha_vencimiento = date('Y-m-d', strtotime($fecha_vencimiento));
	$lugar_captura = $_POST['lugar'];

	$cantidad_series = $_POST['series'];
	$descripcion = $_POST['descripcion'];
	$precio = $_POST['precio'];

	if (empty($_POST["id_oculto"])) {

		if (mysqli_query($conn, "INSERT INTO sorteos_menores (id,no_sorteo_men,fecha_sorteo,cantidad_numeros,
  series,descripcion_sorteo_men,estado_sorteo,precio_unitario,vencimiento_sorteo, lugar_captura) VALUES
  ('$sorteo','$sorteo', '$fecha_sorteo',99,'$cantidad_series',
  '$descripcion','PENDIENTE PRODUCCION', '$precio','$fecha_vencimiento','$lugar_captura' )  ") === TRUE) {
			$inserto = 0;

			$consulta_entidades = mysqli_query($conn, "SELECT * FROM empresas WHERE estado = 'ACTIVO' ");

			while ($reg_consulta_entidades = mysqli_fetch_array($consulta_entidades)) {

				$id_entidad = $reg_consulta_entidades['id'];
				mysqli_query($conn, "INSERT INTO empresas_estado_venta (id_empresa,id_sorteo,cod_producto,estado_venta) VALUES ('$id_entidad', '$sorteo', '3','D') ");
				mysqli_query($conn, "INSERT INTO empresas_estado_venta (id_empresa,id_sorteo,cod_producto,estado_venta) VALUES ('$id_entidad', '$sorteo', '2','D') ");
			}

		} else {
			$inserto = 1;
			echo mysqli_error($conn);
		}

		if (mysqli_query($conn, "INSERT INTO `sorteos_menores_premios`(`sorteos_menores_id`, `premios_menores_id`) VALUES ('$sorteo','1'),('$sorteo','3') ") === TRUE) {
			$inserto = 0;
		} else {
			$inserto = 1;
			echo mysqli_error($conn);
		}

/////////////////// id Del sorteo previamente ingresado //////////////////////////////////////////
		$result = mysqli_query($conn, "SELECT id FROM sorteos_menores WHERE no_sorteo_men ='$sorteo' limit 1");
		$value = mysqli_fetch_object($result);
		$id_sorteo = $value->id;
//////////////////////////////////////////////////////////////////////////////////////////////////

		if ($inserto == 0) {
			?>
<script type="text/javascript">
swal("Se registro el sorteo correctamente", "", "success");
</script>
<?php
} else {
			?>
<script type="text/javascript">
swal("Error inesperado por favor vuelva a intentarlo", "", "error");
</script>
<?php
}

///********************* Codigo de actualizacion de sorteos
	} else {

		$sorteo = $_POST['sorteo'];

		$fecha_sorteo = $_POST['fecha_sorteo'];
		$fecha_sorteo = date('Y-m-d', strtotime($fecha_sorteo));

		$fecha_vencimiento = $_POST['fecha_vencimiento'];
		$fecha_vencimiento = date('Y-m-d', strtotime($fecha_vencimiento));

		$cantidad_series = $_POST['series'];
		$descripcion = $_POST['descripcion'];
		$precio = $_POST['precio'];
		$id_oculto = $_POST["id_oculto"];

		$lugar_captura = $_POST['lugar'];

		if (mysqli_query($conn, " UPDATE sorteos_menores SET no_sorteo_men = '$sorteo',
	fecha_sorteo = '$fecha_sorteo',cantidad_numeros = '99' , descripcion_sorteo_men = '$descripcion',
	series = '$cantidad_series',estado_sorteo = 'PENDIENTE PRODUCCION', premios_asignados = 'NO', precio_unitario = '$precio' , vencimiento_sorteo = '$fecha_vencimiento', lugar_captura = '$lugar_captura'
	 WHERE id = '$id_oculto' ") === false) {
			echo mysqli_error($conn);
			?>
<script type="text/javascript">
swal("Error inesperado por favor vuelva a intentarlo", "", "error");
</script>
<?php

		} else {
			?>
<script type="text/javascript">
swal("Se realizaron los cambios correctamente", "", "success");
</script>
<?php
}

	}

}

?>
