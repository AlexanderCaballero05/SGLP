<?php
require '../../template/header.php';

if (isset($_POST['reversar_venta_mayor'])) {


	$factura = $_POST['reversar_venta_mayor'];

	$busqueda_estado = mysqli_query($conn, "SELECT * FROM transaccional_ventas WHERE cod_factura = '$factura' LIMIT 1");
	$o_estado = mysqli_fetch_object($busqueda_estado);
	$estado = $o_estado->estado_venta;

	if ($estado == 'APROBADO') {
		if (mysqli_query($conn, "UPDATE transaccional_ventas SET estado_venta = 'CANCELADA' WHERE cod_factura = '$factura' AND cod_producto = '1' ") === TRUE) {
			mysqli_query($conn, "UPDATE fvp_detalles_ventas_mayor SET estado_venta = 'CANCELADA' WHERE cod_factura = '$factura' ");


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
<a style="background-color:#ededed;" class="nav-link"  >Lotería Mayor</a>
</li>
<!--
<li class="nav-item">
<a  class="nav-link" href="./coordinacion_facturas_menor_agencias.php">Lotería Menor</a>
</li>
-->
</ul>


<section style="background-color:#ededed;">
<br>
<h3 align="center"><b>FACTURAS POR CONCEPTO DE VENTA DE LOTERIA MAYOR </b></h3>
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

$sorteos2 = mysqli_query($conn, "SELECT * FROM sorteos_mayores  ORDER BY no_sorteo_may DESC ");

while ($row2 = mysqli_fetch_array($sorteos2)) {
	echo '<option value = "' . $row2['id'] . '">No.' . $row2['no_sorteo_may'] . ' -- Fecha ' . $row2['fecha_sorteo'] . '</option>';
}
?>
</select>


  <div class="input-group-prepend" style="margin-left: 5px">
    <div class="input-group-text">Entidad: </div>
  </div>


<select  name="s_empresa" id = 's_empresa'   class="form-control"  >
<?php

$id_empresa = $_SESSION['entidad_id'];
if ($id_empresa == "") {

	$empresas = mysqli_query($conn, "SELECT * FROM empresas WHERE estado = 'ACTIVO' ");

} else {

	$empresas = mysqli_query($conn, "SELECT * FROM empresas WHERE estado = 'ACTIVO' ");

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

	$info_sorteo = mysqli_query($conn, "SELECT * FROM empresas_estado_venta WHERE id_sorteo = '$id_sorteo' AND id_empresa = '$id_empresa'  LIMIT 1");
	$ob_info_sorteo = mysqli_fetch_object($info_sorteo);
	$estado_venta = $ob_info_sorteo->estado_venta;

	$info_empresa = mysqli_query($conn, " SELECT * FROM empresas WHERE id = '$id_empresa' ");
	$ob_empresa = mysqli_fetch_object($info_empresa);
	$nombre_empresa = $ob_empresa->nombre_empresa;

	$ventas_sorteo = mysqli_query($conn, "SELECT a.estado_venta,a.fecha_venta, a.cantidad, a.cod_factura, a.total_neto as precio_total, date(fecha_venta) as date_transaction  FROM transaccional_ventas as a  WHERE  a.id_entidad = '$id_empresa' AND a.id_sorteo = $id_sorteo AND a.cod_producto = '1' ORDER BY  a.cod_factura DESC ");
	if ($ventas_sorteo === false) {
		echo mysqli_error();
	}

	?>

<br><br>

<div class="card" style="margin-left: 10px; margin-right: 10px">
<div class = "card-header bg-secondary text-white">
<h4 align="center"><b>SORTEO <?php echo $id_sorteo; ?> - ENTIDAD <?php echo $nombre_empresa; ?></b> </h4>
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
    <th>Cant. Pliegos</th>
    <th>Total Pagado</th>
    <th>Estado</th>
    <th>Ver</th>
    <th>Reversar</th>
  </tr>
</thead>
<tbody>
<?php

	$today = date('Y-m-d');

	$tt_facturas = 0;
	$tt_neto = 0;
	$tt_cantidad = 0;
	$tt_cantidad_a = 0;
	$tt_cantidad_c = 0;
	$tt_lps_a = 0;
	$tt_lps_c = 0;
	while ($venta = mysqli_fetch_array($ventas_sorteo)) {

		$cod = $venta['cod_factura'];
		echo "<tr>
<td >" . $venta['fecha_venta'] . "</td>
<td >" . $venta['cod_factura'] . "</td>
<td >" . $venta['cantidad'] . "</td>
<td >" . number_format($venta['precio_total'], '2') . "</td>
<td >" . $venta['estado_venta'] . "</td>

<td align= 'center'>

<a class='btn btn-primary fa fa-eye' target='_blank' href= './print_factura_mayor.php?c=" . $cod . "'></a>

</td>

<td align = 'center'>";

		if ($venta['estado_venta'] == 'CANCELADA' OR $estado_venta == "F") {
			echo "<button name = 'reversar_venta_mayor' value = '" . $venta['cod_factura'] . "' class = 'btn btn-danger fa fa-times-circle' disabled></button>";
		} else {
			echo "<button name = 'reversar_venta_mayor' value = '" . $venta['cod_factura'] . "' class = 'btn btn-danger fa fa-times-circle' ></button>";
		}

		echo "</td>
</tr>";

		if ($venta['estado_venta'] == "CANCELADA") {
			$tt_cantidad_c += $venta['cantidad'];
			$tt_lps_c += $venta['precio_total'];
		} else {
			$tt_cantidad_a += $venta['cantidad'];
			$tt_lps_a += $venta['precio_total'];
		}

		$tt_facturas++;
		$tt_neto += $venta['precio_total'];
		$tt_cantidad += $venta['cantidad'];
	}

	?>


    </tbody>
<tr>
  <th>TOTAL TRANSACCIONES</th>
  <th><?php echo $tt_facturas; ?></th>
  <th> <?php echo number_format($tt_cantidad); ?></th>
  <th><?php echo number_format($tt_neto, '2'); ?></th>
  <th></th>
  <th></th>
  <th></th>
</tr>

  </table>

<br>

 <table class="table table-bordered">
 	<tr>
 		<th>DESCRIPCION</th>
 		<th>CANTIDAD</th>
 		<th>MONTO EN LPS.</th>
 	</tr>
	<tr>
		<td>PLIEGOS VENDIDOS APROBADOS</td>
		<td><?php echo number_format($tt_cantidad_a); ?></td>
		<td><?php echo number_format($tt_lps_a, 2); ?></td>
	</tr>
 </table>

  </div>


  <div class="card-footer" align="center">
    <a class = "btn btn-success" href="../reporteria/venta_entidades_mayor_detalle.php?s=<?php echo $id_sorteo; ?>&e=<?php echo $id_empresa; ?>&d=NO"  target="_blank">EMITIR ACTA DE VENTA</a>
    <a class = "btn btn-success" href="../reporteria/dev_entidades_mayor_detalle.php?s=<?php echo $id_sorteo; ?>&e=<?php echo $id_empresa; ?>&d=NO"  target="_blank">EMITIR ACTA DE NO VENDIDO</a>
  </div>
</div>

<br>
<br>

<?php

}

?>



</form>
</body>
