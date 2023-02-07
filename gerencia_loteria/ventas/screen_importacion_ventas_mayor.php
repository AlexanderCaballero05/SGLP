<?php
require "../../template/header.php";
date_default_timezone_set('America/Tegucigalpa');
?>

<script type="text/javascript">
function funcion_seleccion_nuevo(id_depto,tipo){

if (tipo == 1) {
var obj_select = document.getElementById("id_nueva_seccional");
}

conteo_opciones = obj_select.length;
obj_select.options[0].selected = true;

for (var i = 1; i <= conteo_opciones; i++) {

if (obj_select.options[i].id == id_depto ) {
obj_select.options[i].style.display = "block";
}else{
obj_select.options[i].style.display = "none";
}
}

}
</script>


<body>



<form  enctype="multipart/form-data" method="post" action="" accept-charset="UTF-8">



<br>

<ul class="nav nav-tabs">

<li class="nav-item">
<a   class="nav-link active"  style="background-color:#ededed;"   >Lotería Mayor (EXCEL)</a>
</li>
<li class="nav-item">
<a  class="nav-link"  href="./importacion_ventas_mayor_txt.php">Lotería Mayor (FTP)</a>
</li>
<li class="nav-item">
<a  class="nav-link" href="./importacion_ventas_menor.php">Lotería Menor</a>
</li>

</ul>

<section style="background-color:#ededed;">
<br>
<h3 align="center"><b>IMPORTACION DE VENTAS LOTERIA MAYOR (EXCEL)</b></h3>
<br>
</section>



<a style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
Seleccion de Parametros
</a>





<div class="card collapse" id="collapse1" style="margin-left: 15px; margin-right: 15px;" >
<div class="card-body">

<table class="table table-bordered">
<tr>
<th width="20%">SORTEO</th>
<th width="20%">ENTIDAD</th>
<th width="20%">AGENCIA</th>
<th width="30%">ARCHIVO PLANO</th>
<th width="10%">ACCION</th>
</tr>

<tr>
<td>
<select class="form-control"  name = "sorteo" id = 'sorteo'   style="margin-right: 5px;">
<?php
$sorteos = mysqli_query($conn, "SELECT a.id, a.no_sorteo_may, a.fecha_sorteo, a.descripcion_sorteo_may  FROM sorteos_mayores as a inner join empresas_estado_venta as b ON a.id = b.id_sorteo WHERE  b.estado_venta = 'H'  AND b.cod_producto = 1 GROUP BY b.id_sorteo ORDER BY a.id DESC ");

while ($row2 = mysqli_fetch_array($sorteos)) {
	echo '<option value = "' . $row2['id'] . '">No.' . $row2['no_sorteo_may'] . ' -- Fecha ' . $row2['fecha_sorteo'] . ' -- ' . $row2['descripcion_sorteo_may'] . '</option>';
}
?>
</select>
</td>

<td>
<select  onchange="funcion_seleccion_nuevo(this.value,'1')" class="form-control" name="id_nueva_empresa" id = 'id_nueva_empresa'  style="margin-right: 5px;">
<option>Seleccione una opcion</option>
<?php
$empresas = mysqli_query($conn, "SELECT * FROM empresas WHERE estado = 'activo' ");
while ($empresa = mysqli_fetch_array($empresas)) {
	echo "<option value = '" . $empresa['id'] . "'>" . $empresa['nombre_empresa'] . "</option>";
}
?>
</select>
</td>

<td>
    <select class="form-control" name="id_nueva_seccional" id="id_nueva_seccional" >
    <option>Seleccione una opcion</option>
    <?php
$seccionales = mysqli_query($conn, "SELECT a.id, a.nombre, a.id_empresa, b.departamento, b.municipio FROM fvp_seccionales as a INNER JOIN departamentos_municipios as b  ON a.geocodigo_id = b.id  ");

while ($seccional = mysqli_fetch_array($seccionales)) {
	$concat_agencia = $seccional['nombre'] . "!" . $seccional['departamento'] . "!" . $seccional['municipio'];
	echo "<option style = 'display:none;' id = '" . $seccional['id_empresa'] . "' value = '" . $concat_agencia . "' >" . $seccional['nombre'] . "</option>";
}
?>
    </select>
</td>

<td>
<input class="form-control" type="file" name="importacion" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" style="margin-right: 5px;">
</td>

<td align="center">
<input type="submit" class="btn btn-success" name ="importar" value="Validar">
</td>
</tr>

</table>

</div>
</div>




<?php
if (isset($_POST['importar'])) {
	require "./importacion_ventas_mayor_db.php";
}
?>


</form>
</body>







<?php
////////////////////////////////////////////////
//////////// CODIGO DE GUARDADO ////////////////

if (isset($_POST['guardar_importacion'])) {

	?>

<script type="text/javascript">

document.getElementById('guardar_importacion').disabled = true;
$(".div_wait").fadeIn("fast");

</script>

<?php

//require('./conexion_oracle.php');

	$matriz = unserialize($_POST['guardar_importacion']);

	$id_sorteo = $_POST['id_sorteo_o'];
	$id_empresa = $_POST['id_empresa_o'];
	$id_agencia = $_POST['id_agencia_o'];

	$v_info_agencia = explode("!", $id_agencia);
	$agencia = $v_info_agencia[0];
	$departamento = $v_info_agencia[1];
	$municipio = $v_info_agencia[2];

	$id_usuario = $_SESSION['id_usuario'];
	$precio = $_POST['precio_unitario'];
	$precio_total = $_POST['total_bruto'];
	$descuento_total = $_POST['total_descuento'];
	$neto_total = $_POST['total_neto'];
	$cantidad = $_POST['total_cantidad'];
	$usuario = $_SESSION['nombre'];
	$comision_total = $_POST['total_comision'];
	$credito_pani = $_POST['total_credito_pani'];
	$forma_pago = 2;

	$bandera_transaccional = 0;
	$bandera_detalle = 0;
	$bandera_erp = 0;
	$bandera_mayor_ventas = 0;

	$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo' ");
	$ob_sorteo = mysqli_fetch_object($info_sorteo);
	$fecha_captura = $ob_sorteo->fecha_sorteo;

////////////////////////////////////////////////////////////
	///////////// INSERT TRANSACCIONAL /////////////////////////

	if (mysqli_query($conn, " INSERT INTO `transaccional_ventas`(`cod_factura`, `id_sorteo`, `id_entidad`, `cantidad`, `precio_unitario`, `total_bruto`, `descuento`, `total_neto`, `comision_bancaria`, `credito_pani`, `id_usuario`, `estado_venta`, `id_seccional`, `forma_pago`, `cod_producto`,importacion)
SELECT(SELECT MAX(cod_factura)+1 from transaccional_ventas), '$id_sorteo', '$id_empresa', '$cantidad', '$precio', '$precio_total', '$descuento_total', '$neto_total', '$comision_total', '$credito_pani', '$id_usuario', 'APROBADO' ,'0' , '$forma_pago' , '1', 's' ") === false) {

		$bandera_transaccional = 1;
		echo mysqli_error($conn);

	} else {

		$busqueda_factura = mysqli_query($conn, "SELECT MAX(cod_factura) as maximo, fecha_venta FROM transaccional_ventas WHERE id_usuario = $id_usuario  AND id_sorteo = '$id_sorteo' AND id_entidad = '$id_empresa' ");
		$ob_max_factura = mysqli_fetch_object($busqueda_factura);
		$cod_factura = $ob_max_factura->maximo;
		$fecha_registro_venta = $ob_max_factura->fecha_venta;

		if ($fecha_registro_venta >= $fecha_captura) {
			$fecha_actualizada = date('Y-m-d H:i:s', strtotime($fecha_captura . ' -1 day'));
			mysqli_query($conn, "UPDATE transaccional_ventas SET fecha_venta = '$fecha_actualizada' WHERE cod_factura = '$cod_factura' ");
		}

	}

//////////// FIN INSERT TRANSACCIONAL ///////////////////
	/////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////
	//////////////// CODIGO DE GUARDADO ERP ///////////////////
	if ($bandera_transaccional == 0 AND $bandera_mayor_ventas == 0) {

/*

$resultado_ERP="INSERT INTO  LOT_DETALLE_FACTURACION
(CODIGO_FACTURA, IDENTIDAD, LOTERIA, SORTEO,DESCUENTO ,  MONTO, CODIGO_VENDEDOR, NOMBRE_VENDEDOR, PRECIO, CANTIDAD_BILLETES, NOMBRE_COMPRADOR, COMISION_BANCO,ENTIDAD,TOTAL_BRUTO,APORTACION,CREDITO_PANI,ID_USUARIO,ID_SECCIONAL,FORMA_PAGO)
VALUES('$cod_factura','','1','$id_sorteo', '$descuento_total','$neto_total', '$id_usuario', '$usuario', '$precio', '$cantidad','','$comision_total','$id_empresa','$precio_total','','$credito_pani','$id_usuario','$id_seccional','$forma_pago' ) ";
$save_result=oci_parse($conn2, $resultado_ERP);

$rc=oci_execute($save_result);
oci_free_statement($rc);

if(!$rc)
{
$bandera_erp = 1;
$e=oci_error($save_result);
var_dump($e);

}else{
}

oci_close($conn2);

 */

	}
//////////////// CODIGO DE GUARDADO ERP ///////////////////
	///////////////////////////////////////////////////////////

//////////////////////////////////////////////
	////////////// CODIGO DE DETALLE /////////////

	if ($bandera_erp == 0 AND $bandera_transaccional == 0 AND $bandera_mayor_ventas == 0) {

		$i = 0;
		while (isset($matriz[$i][0])) {

			if (isset($matriz[$i][0])) {
				if ($matriz[$i][0] >= 0) {

					$billete_inicial = $matriz[$i][0];
					$billete_final = $matriz[$i][1];

					while ($billete_inicial <= $billete_final) {

						if (mysqli_query($conn, "INSERT INTO fvp_detalles_ventas_mayor (billete,precio_unitario,decimos,cod_factura,id_sorteo,estado_venta, agencia, departamento, municipio)
VALUES ('$billete_inicial','$precio','10','$cod_factura','$id_sorteo','APROBADO','$agencia','$departamento' ,'$municipio' ) ") === false) {
							$bandera_detalle = 1;
							$i = -2;
							$billete_inicial = -2;
						}

						$billete_inicial++;
					}

				}
			}

			$i++;
		}

		if ($fecha_registro_venta >= $fecha_captura) {
			$fecha_actualizada = date('Y-m-d H:i:s', strtotime($fecha_captura . ' -1 day'));
			mysqli_query($conn, "UPDATE fvp_detalles_ventas_mayor SET fecha_transaccion = '$fecha_actualizada' WHERE cod_factura = '$cod_factura' ");
		}

	}

///////////// FIN DE CODIGO DETALLE ////////////////
	////////////////////////////////////////////////////

	if ($bandera_mayor_ventas == 0 AND $bandera_erp == 0 AND $bandera_detalle == 0 AND $bandera_transaccional == 0) {
		echo '<div class="alert alert-success" role="alert"> Venta realizada exitosamente</div>';
	} else {
		echo '<div class="alert alert-danger" role="alert"> Error en la venta, por favor intente nuevamente.</div>';
	}

	unset($_SESSION['token']);

	?>

<script>
$(".div_wait").fadeOut("fast");
</script>

<?php

}

/////////// FIN CODIGO DE GUARDADO /////////////
////////////////////////////////////////////////
?>
