<?php
require '../../template/header.php';
require './facturacion_menor_db.php';

$empresas = mysqli_query($conn, "SELECT * FROM empresas WHERE estado = 'ACTIVO' AND id = 3 ");

?>


<script type="text/javascript">

function cancelar_factura(no_factura){

document.getElementById('id_factura_oculto').value = no_factura;

  swal({
  title: "Atancion",
   text: "Esta accion es irreversible, al anular el acta de entrega de loteria se eliminaran las distribuciones de loteria que esta contenga, en tal sentido dicha accion debe ser autorizada por la autoridad correspondiente. ",
    type: "error"
  });


}

</script>


<form method="POST">



<section style="background-color:#ededed;">
<br>
<h2 align="center" style="color:black;" ><b>EMISION DE ACTAS DE ENTREGA DE LOTERIA MENOR</b></h2>
<br>
</section>

<a class="btn btn-info" style="width:100%" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
Selección de parametros
</a>

<div  class="collapse" style = "width:100%"  id="collapse1" align="center">
<div class="card" style="width: 50%">
<div class="card-body">

<div class="input-group">

<div class="input-group-prepend">
<div class="input-group-text">Sorteo: </div>
</div>

<select  name="sorteo"  class = 'form form-control'>
<?php
while ($row2 = mysqli_fetch_array($sorteos)) {
	echo '<option value = "' . $row2['id'] . '">' . $row2['no_sorteo_men'] . '</option>';
}
?>
</select>


<div class="input-group-prepend">
<div class="input-group-text">Entidad: </div>
</div>

<select  name = 'select_receptor'  class = 'form form-control'>
<?php
while ($empresa = mysqli_fetch_array($empresas)) {
	echo "<option value = '" . $empresa['id'] . "' >" . $empresa['nombre_empresa'] . "</option>";
}
?>
</select>


<div class="input-group-append">
<input type="submit" name="seleccionar" class="btn btn-primary" value="Seleccionar">
</div>

</div>


</div>
</div>
</div>




<?php
if (isset($_POST['seleccionar'])) {
	$id_sorteo = $_POST['sorteo'];
	$receptor = $_POST['select_receptor'];
	$id_empresa = $receptor;

	$info_sorteo = mysqli_query($conn, "SELECT *  FROM sorteos_menores WHERE id = '$id_sorteo' limit 1");
	$value = mysqli_fetch_object($info_sorteo);
	$sorteo = $value->no_sorteo_men;
	$fecha_sorteo = $value->fecha_sorteo;
	$precio_unitario = $value->precio_unitario;

	$info_empresa = mysqli_query($conn, "SELECT *  FROM empresas WHERE id = '$receptor' limit 1");
	$value_e = mysqli_fetch_object($info_empresa);
	$nombre_e = $value_e->nombre_empresa;
	$descuento_e = $value_e->descuento_menor;
	$tipo_descuento_e = $value_e->tipo_descuento_menor;
	$rebaja_e = $value_e->rebaja_menor;
	$tipo_rebaja_e = $value_e->tipo_rebaja_menor;

	$inventario_asignado = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad FROM menor_seccionales_bolsas  WHERE id_sorteo = '$id_sorteo'  AND id_empresa = '$receptor' AND cod_factura IS NULL ");

	if ($inventario_asignado === false) {
		echo mysqli_error();
	}

	$inventario = mysqli_fetch_object($inventario_asignado);
	$cantidad_asignada_bolsas = $inventario->cantidad;

	$inventario_asignado2 = mysqli_query($conn, "SELECT SUM(a.cantidad) as cantidad2 FROM menor_seccionales_numeros as a  WHERE a.id_sorteo = '$id_sorteo' AND  a.id_empresa = '$receptor' AND a.cod_factura IS NULL ");

	if ($inventario_asignado2 === false) {
		echo mysqli_error();
	}

	$inventario2 = mysqli_fetch_object($inventario_asignado2);
	$cantidad_asignada_numeros = $inventario2->cantidad2;

	$cantidad_asignada_bolsas = $cantidad_asignada_bolsas * 100;
	$cantidad_asignada = $cantidad_asignada_bolsas + $cantidad_asignada_numeros;

	$valor_nominal = $precio_unitario * $cantidad_asignada;

	if ($tipo_descuento_e == 2) {
		$porcentaje_descuento = $descuento_e / 100;
		$descuento = $valor_nominal * $porcentaje_descuento;
	} else {
		$descuento = $cantidad_asignada * $descuento_e;
	}

	if ($tipo_rebaja_e == 2) {
		$porcentaje_comision = $rebaja_e / 100;
		$comision = $valor_nominal * $rebaja_e;
	} else {
		$comision = $cantidad_asignada * $rebaja_e;
	}

	$neto = $valor_nominal - $descuento - $comision;

	$consulta_facturas = mysqli_query($conn, "SELECT MAX(no_factura) as max_id FROM facturacion_menor ");
	if (mysqli_num_rows($consulta_facturas) > 0) {
		$consulta_factura = mysqli_fetch_object($consulta_facturas);
		$factura = $consulta_factura->max_id;
		$factura = $factura + 1;
	} else {
		$factura = 1;
	}

	?>


<br>
<br>

<div class="card" style="margin-right: 15px; margin-left: 15px">
<div class="card-header alert alert-info">

<h3 align="center"> SORTEO <?php echo $sorteo; ?> - FECHA DE SORTEO <?php echo $fecha_sorteo; ?>
<hr>
ENTIDAD <?php echo $nombre_e; ?>
</h3>


</div>

<div class="card-body">

<input type = 'hidden' name = 'id_sorteo' value = '<?php echo $id_sorteo; ?>'>
<input type = 'hidden' name = 'id_empresa' value = '<?php echo $id_empresa; ?>'>
<input type = 'hidden' name = 'receptor' value = '<?php echo $receptor; ?>'>

<p align = 'left'>

PRECIO UNITARIO: <?php echo $precio_unitario . " Lps."; ?><br>

<?php
if ($tipo_descuento_e == 1) {
		echo "VALOR DESCUENTO: " . $descuento_e . " Lps.";
	} else {
		echo "VALOR DESCUENTO: " . $descuento_e . " %";
	}

	echo "<br>";

	if ($tipo_rebaja_e == 1) {
		echo "VALOR COMISION: " . $rebaja_e . " Lps.";
	} else {
		echo "VALOR COMISION: " . $rebaja_e . " %";
	}

	?>
</p>



<table class = 'table table-bordered'>

	<tr>
		<th>Fecha Expedicion</th>
		<th>No. Factura</th>
		<th>No. Sorteo</th>
		<th>Fecha Sorteo</th>
		<th>Cantidad</th>
		<th>Valor Nominal</th>
		<th>Descuento</th>
		<th>Rebaja Depositarios</th>
		<th>Valor Neto</th>
	</tr>
	<tr>
		<td width = '10%'>
		<input class = 'form form-control' style = 'width:100%' type = 'hidden' value="<?php echo date('Y-m-d'); ?>" name = 'fecha_expedicion' id="fecha_expedicion" readonly>
		<?php echo date('Y-m-d'); ?>
		</td>

		<td width = '10%'>
		<input class = 'form form-control' style = 'width:100%' type = 'hidden' name = 'no_factura' value = '<?php echo $factura; ?>' >
		<?php echo $factura; ?>
		</td>

		<td width = '10%'>
		<?php echo $id_sorteo; ?>
		</td>

		<td width = '10%'>
		<input class = 'form form-control' style = 'width:100%' type = 'hidden' name = 'fecha_sorteo' value = '<?php echo $fecha_sorteo; ?>' readonly>
		<?php echo $fecha_sorteo; ?>
		</td>

		<td width = '10%'>
		<input class = 'form form-control' style = 'width:100%' type = 'hidden' name = 'cantidad_asignada' value = '<?php echo $cantidad_asignada; ?>' >
		<?php echo number_format($cantidad_asignada, 0); ?>
		</td>

		<td width = '10%'>
		<input style = 'width:100%' class = 'form form-control' type = 'hidden' name = 'valor_nominal' value = '<?php echo $valor_nominal; ?>' >
		<?php echo number_format($valor_nominal, 2); ?>
		</td>

		<td width = '10%'>
			<input style = 'width:100%' class = 'form form-control' type = 'hidden' name = 'descuento' value = '<?php echo $descuento; ?>'>
		<?php echo number_format($descuento, 2); ?>
		</td>

		<td width = '10%'>
			<input style = 'width:100%' class = 'form form-control' type = 'hidden' name = 'rebaja' value = '<?php echo $comision; ?>' >
		<?php echo number_format($comision, 2); ?>
		</td>

		<td width = '10%'>
			<input style = 'width:100%' class = 'form form-control' type = 'hidden' name = 'valor_neto' value = '<?php echo $neto; ?>' >
		<?php echo number_format($neto, 2); ?>
		</td>
	</tr>
</table>

</div>

<div class="card-footer" align="center">

<p align="center">
<?php
if ($cantidad_asignada == 0) {
		echo '<button type="submit" class="btn btn-primary" name = "guardar_factura" disabled>Guardar Factura</button>';
	} else {
		echo '<button type="submit" class="btn btn-primary" name = "guardar_factura">Guardar Factura</button>';
	}
	?>
</p>

</div>
</div>


<br><br>


<div class="card" style="margin-right: 15px; margin-left: 15px">
<div class="card-header alert alert-success">
<h4 align="center">HISTORICO DE ACTAS EMITIDAS</h4>
</div>


<table class = 'table table-bordered'>
	<tr>
		<th>Receptor</th>
		<th>Fecha Expedicion</th>
		<th>No. Factura</th>
		<th>No. Sorteo</th>
		<th>Fecha Sorteo</th>
		<th>Cantidad</th>
		<th>Valor Nominal</th>
		<th>Descuento</th>
		<th>Rebaja Depositarios</th>
		<th>Valor Neto</th>
		<th>Accion</th>
	</tr>
<?php
$facturaciones = mysqli_query($conn, " SELECT * FROM facturacion_menor WHERE id_sorteo = '$id_sorteo' ");

	if ($facturaciones === false) {
		echo mysqli_error();
	}

	while ($factura = mysqli_fetch_array($facturaciones)) {
		echo "<tr>";
		echo "<td>" . $factura['receptor'] . "</td>";
		echo "<td>" . $factura['fecha_expedicion'] . "</td>";
		echo "<td>" . $factura['no_factura'] . "</td>";
		echo "<td>" . $factura['id_sorteo'] . "</td>";
		echo "<td>" . $factura['fecha_sorteo'] . "</td>";
		echo "<td>" . number_format($factura['cantidad'], 0) . "</td>";
		echo "<td>" . number_format($factura['valor_nominal'], 2) . "</td>";
		echo "<td>" . number_format($factura['descuento'], 2) . "</td>";
		echo "<td>" . number_format($factura['rebaja_depositario'], 2) . "</td>";
		echo "<td>" . number_format($factura['valor_neto'], 2) . "</td>";
		echo "<td>
<a class='btn btn-primary' target='_blank' href= './print_acta_entrega_menor.php?c=" . $factura['id'] . "'>
<span class = 'fa fa-print'></span>
</a>


<a data-toggle='modal' data-target='#myModal'  href='#' class='btn btn-danger' onclick = 'cancelar_factura(" . $factura['no_factura'] . ")' >
<span class = 'fa fa-times-circle'></span>
</a>


</td>";
		echo "</tr>";
	}

	?>

</table>

</div>




<div id="myModal" class="modal fade" role="dialog" tabindex="-1" id="modal-login">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header" id="modal-header" style="background-color:#e7e7e7;">
<h4 class="text-center modal-title" id="modal-heading" style="width:100%;">
AUTORIZACION DE ANULACION DE FACTURA</h4>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
<div class="modal-body" style="background-color:#f8f8f8;">

<input type="hidden" name="id_factura_oculto" id="id_factura_oculto">

<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-user" style="font-size:27px;color:rgb(58,58,58);"></i></span></div>
<input class="form-control" type="text" name="username" id="uss" placeholder="Usuario"  >
<div class="input-group-append"></div>
</div>

<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-eye-slash" style="font-size:19px;color:rgb(47,47,47);"></i></span></div>
<input class="form-control" type="password" name="password" id="psw" placeholder="Contraseña"  >
<div class="input-group-append"></div>
</div>

<div style="display:none;" id="caps" class="alert alert-danger">Mayusculas activadas.</div>

<div class="container" align="right" style="padding:0px; ">
        <button type="submit" name="aceptar_anulacion" class="btn btn-primary">Aceptar</button>
        <button type="submit" class="btn btn-default" data-dismiss="modal">Cancelar</button>
</div>
</div>
</div>
</div>
</div>




<?php
}
?>


</form>
