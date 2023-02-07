<?php
require '../../template/header.php';

if (isset($_POST['reversar_venta_menor'])) {

	require './conexion_oracle.php';

	$factura = $_POST['reversar_venta_mayor'];

	$busqueda_estado = mysqli_query($conn, "SELECT * FROM transaccional_ventas WHERE cod_factura = '$factura' LIMIT 1");
	$o_estado = mysqli_fetch_object($busqueda_estado);
	$estado = $o_estado->estado_venta;

	if ($estado == 'APROBADO') {
		if (mysqli_query($conn, "UPDATE transaccional_ventas SET estado_venta = 'CANCELADA' WHERE cod_factura = '$factura' ") === TRUE) {
			mysqli_query($conn, "UPDATE fvp_detalles_ventas_menor SET estado_venta = 'CANCELADA' WHERE cod_factura = '$factura' ");

////////////////////////////////////////////////
			/////////// REGISTRO EN BITACORA ///////////////
			$id_usuario_bitacora = $_SESSION['id_usuario'];

			$modulo_bitacora = "VENTAS";
			$tipo_mod_bitacora = "UPDATE";
			$tabla_bitacora = "transaccional_ventas";
			$descripcion_bitacora = "Cancelacion de factura loteria menor, Codigo Factura: " . $factura;

			$cod_accion = "6";
			$registro_bitacora = registro_bitacora($id_usuario_bitacora, $modulo_bitacora, $cod_accion, $tipo_mod_bitacora, $tabla_bitacora, $descripcion_bitacora);
/////////// REGISTRO EN BITACORA ///////////////
			////////////////////////////////////////////////

			$resultado_ERP = "UPDATE LOT_DETALLE_FACTURACION  SET ANULADO = 'S' WHERE codigo_factura = '$factura' ";
			$save_result = oci_parse($conn2, $resultado_ERP);

			$rc = oci_execute($save_result);
			oci_free_statement($rc);

			if (!$rc) {
				$e = oci_error($save_result);
				var_dump($e);
			}

			echo '<div class="alert alert-success" role="alert"> La venta ha sido cancelada exitosamente</div>';
		} else {
			echo '<div class="alert alert-danger" role="alert"> Error inesperado por favor vuelva a intentarlo.</div>';
		}

	}

}

?>


<body>

<form method="POST" autocomplete="off">


<br>

<ul class="nav nav-tabs">
 <li class="nav-item">
<a  class="nav-link" href="./screen_coordinacion_facturas_mayor_agencias.php">Lotería Mayor</a>
</li>
<li class="nav-item">
<a style="background-color:#ededed;" class="nav-link"  >Lotería Menor</a>
</li>
</ul>


<section style="background-color:#ededed;">
<br>
<h3 align="center"><b>FACTURAS POR CONCEPTO DE VENTA DE LOTERIA MENOR </b></h3>
<br>
</section>



<a style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
Seleccion de Parametros
</a>





<div class="card collapse" id="collapse1" style="margin-left: 150px; margin-right: 150px;" >
<div class="card-body">


<div class="input-group">

  <div class="input-group-prepend">
    <div class="input-group-text">Sorteo: </div>
  </div>

<select  name="sorteo" id = 'sorteo'  class="form-control" >
<?php

$sorteos2 = mysqli_query($conn, "SELECT * FROM sorteos_menores  ORDER BY no_sorteo_men DESC ");

while ($row2 = mysqli_fetch_array($sorteos2)) {
	echo '<option value = "' . $row2['id'] . '">No.' . $row2['no_sorteo_men'] . ' -- Fecha ' . $row2['fecha_sorteo'] . '</option>';
}
?>
</select>


  <div class="input-group-prepend" style="margin-left: 5px">
    <div class="input-group-text">Entidad: </div>
  </div>


<select  name="s_empresa" id = 's_empresa'   class="form-control"  ">
<?php

$id_empresa = $_SESSION['entidad_id'];
if ($id_empresa == "") {

	$empresas = mysqli_query($conn, "SELECT * FROM empresas  ");

} else {

	$empresas = mysqli_query($conn, "SELECT * FROM empresas  ");

}

while ($reg_empresa = mysqli_fetch_array($empresas)) {
	echo '<option value = "' . $reg_empresa['id'] . '">' . $reg_empresa['nombre_empresa'] . '</option>';
}
?>
</select>


<div class="input-group-append" style="margin-left: 5px">
<input style="width: 100%"  type="submit" name="seleccionar" class="btn btn-primary" value="Seleccionar">
</div>


</div>

</div>
</div>




<br>

<?php
if (isset($_POST['seleccionar'])) {

	$id_sorteo = $_POST['sorteo'];
	$id_empresa = $_POST['s_empresa'];

	$info_sorteo = mysqli_query($conn, "SELECT * FROM empresas_estado_venta WHERE id_sorteo = '$id_sorteo' AND id_empresa = '$id_empresa' LIMIT 1 ");
	$ob_info_sorteo = mysqli_fetch_object($info_sorteo);
	$estado_venta = $ob_info_sorteo->estado_venta;

	$info_empresa = mysqli_query($conn, " SELECT * FROM empresas WHERE id = '$id_empresa' ");
	$ob_empresa = mysqli_fetch_object($info_empresa);
	$nombre_empresa = $ob_empresa->nombre_empresa;

	$ventas_sorteo = mysqli_query($conn, "SELECT a.estado_venta,a.fecha_venta, a.cod_factura, a.total_neto as precio_total  FROM transaccional_ventas as a  WHERE  a.id_entidad = '$id_empresa' AND a.id_sorteo = $id_sorteo AND a.cod_producto = '2' ORDER BY  a.cod_factura DESC ");
	if ($ventas_sorteo === false) {
		echo mysqli_error();
	}

	?>

<br><br>

<div class="card" style="margin-left: 10px; margin-right: 10px">
<div class = "card-header bg-secondary text-white">
<h3 align="center">TRANSACCIONES DE LOTERIA MENOR SORTEO<?php echo $id_sorteo; ?> <br> ENTIDAD <?php echo $nombre_empresa; ?> </h3>
</div>

<div class = "card-body">

<?php

	if ($estado_venta == "F") {
		echo '<div class="alert alert-danger">No puede realizar modificaciones en la venta del sorteo seleccionado ya que este esta <b>FINALIZADO</b>.</div>';
	}

	?>


<table id="table_id1" style="width:100%" class="table table-hover table-bordered">
<thead>
  <tr>
    <th>Fecha Venta</th>
    <th>Factura</th>
    <th>Total Pagado</th>
    <th>Estado</th>
    <th>Ver</th>
    <th>Reversar</th>
  </tr>
</thead>
<tbody>
<?php

	$tt_facturas = 0;
	$tt_neto = 0;

	while ($venta = mysqli_fetch_array($ventas_sorteo)) {

		$cod = $venta['cod_factura'];
		echo "<tr>
<td >" . $venta['fecha_venta'] . "</td>
<td >" . $venta['cod_factura'] . "</td>
<td >" . number_format($venta['precio_total'], '2') . "</td>
<td >" . $venta['estado_venta'] . "</td>

<td align= 'center'>

<a class='btn btn-primary fa fa-eye' target='_blank' href= './print_factura_menor.php?c=" . $cod . "'></a>

</td>

<td align = 'center'>";

		if ($venta['estado_venta'] == 'CANCELADA' OR $estado_venta == "F") {
			echo "<button name = 'reversar_venta_mayor' value = '" . $venta['cod_factura'] . "' class = 'btn btn-danger fa fa-times-circle' disabled></button>";
		} else {
			echo "<button name = 'reversar_venta_mayor' value = '" . $venta['cod_factura'] . "' class = 'btn btn-danger fa fa-times-circle' ></button>";
		}

		echo "</td>
</tr>";

		$tt_facturas++;
		$tt_neto += $venta['precio_total'];
	}

	?>


    </tbody>
<tr>
  <th>TOTAL TRANSACCIONES</th>
  <th><?php echo $tt_facturas; ?></th>
  <th><?php echo number_format($tt_neto, '2'); ?></th>
  <th></th>
  <th></th>
  <th></th>
</tr>

  </table>
  </div>
</div>

<br>
<br>

<?php

}

?>



</form>
</body>
