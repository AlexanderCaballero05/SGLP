<?php
require "../../template/header.php";
date_default_timezone_set('America/Tegucigalpa');
?>

<script type="text/javascript">
function funcion_seleccion_nuevo(id_depto,tipo){

}
</script>
<body>


<br>


<form  enctype="multipart/form-data" method="post" action="" accept-charset="UTF-8">


<ul class="nav nav-tabs">

<li class="nav-item">
<a   class="nav-link "  href="./screen_importacion_ventas_mayor.php"   >Lotería Mayor (EXCEL)</a>
</li>
<li class="nav-item">
<a  class="nav-link"  href="./importacion_ventas_mayor_txt.php">Lotería Mayor (FTP)</a>
</li>
<li class="nav-item">
<a  class="nav-link active" style="background-color:#ededed;"  >Lotería Menor</a>
</li>


</ul>

<section style="background-color:#ededed;">
<br>
<h3 align="center"><b>IMPORTACION DE VENTAS LOTERIA MENOR </b></h3>
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
<th width="30%">ARCHIVO PLANO</th>
<th width="10%">ACCION</th>
</tr>

<tr>
<td>
<select class="form-control"  name = "sorteo" id = 'sorteo'   style="margin-right: 5px;">
<?php
$sorteos = mysqli_query($conn, "SELECT a.id, a.no_sorteo_men, a.fecha_sorteo, a.descripcion_sorteo_men  FROM sorteos_menores as a inner join empresas_estado_venta as b ON a.id = b.id_sorteo WHERE  b.estado_venta = 'H'  AND b.cod_producto = 2 GROUP BY b.id_sorteo ORDER BY a.id DESC ");

while ($row2 = mysqli_fetch_array($sorteos)) {
	echo '<option value = "' . $row2['id'] . '">No.' . $row2['no_sorteo_men'] . ' -- Fecha ' . $row2['fecha_sorteo'] . ' -- ' . $row2['descripcion_sorteo_men'] . '</option>';
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
	require "./importacion_ventas_menor_db.php";
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
	$id_usuario = $_SESSION['id_usuario'];
	$usuario = $_SESSION['nombre_usuario'];

	$precio_unitario = $_POST['precio_unitario'];
	$total_cantidad = $_POST['total_cantidad'];
	$total_bruto = $_POST['total_bruto'];
	$total_descuento = $_POST['total_descuento'];
	$total_neto = $_POST['total_neto'];
	$total_comision = $_POST['total_comision'];
	$total_aportacion = 0.03 * $total_cantidad;
	$total_credito_pani = $_POST['total_credito_pani'];
	$forma_pago = 2;
	$asociacion = 'C';

	$total_descuento = $total_cantidad * 2.2;

	$bandera_transaccional = 0;
	$bandera_ventas_menor = 0;
	$bandera_detalles = 0;
	$bandera_erp = 0;

	$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE id = '$id_sorteo' ");
	$ob_sorteo = mysqli_fetch_object($info_sorteo);
	$fecha_captura = $ob_sorteo->fecha_sorteo;

//////////////////////////////////////////////
	/////////// INSERT TRANSACCIONAL /////////////

	$registro_transaccional = mysqli_query($conn, " INSERT INTO `transaccional_ventas`(`cod_factura`, `id_sorteo`, `id_entidad`, `cantidad`, `precio_unitario`, `total_bruto`, `descuento`, `total_neto`, `comision_bancaria`,aportacion , `credito_pani`, `id_usuario`, `id_seccional`,asociacion_comprador ,`estado_venta`, `forma_pago`, `cod_producto`, `importacion`)
SELECT (SELECT MAX(cod_factura)+1 from transaccional_ventas) , '$id_sorteo', '$id_empresa', '$total_cantidad', '$precio_unitario', '$total_bruto', '$total_descuento', '$total_neto', '$total_comision', '$total_aportacion' ,'$total_credito_pani', '$id_usuario', '0','$asociacion' ,'APROBADO' , '$forma_pago' , '2','s' ");

	if ($registro_transaccional === TRUE) {

		$busqueda_factura = mysqli_query($conn, "SELECT MAX(cod_factura) as maximo, fecha_venta FROM transaccional_ventas WHERE id_usuario = $id_usuario  AND id_sorteo = '$id_sorteo' AND id_entidad = $id_empresa ");
		$ob_max_factura = mysqli_fetch_object($busqueda_factura);
		$cod_factura = $ob_max_factura->maximo;
		$fecha_registro_venta = $ob_max_factura->fecha_venta;

		if ($fecha_registro_venta >= $fecha_captura) {
			$fecha_actualizada = date('Y-m-d H:i:s', strtotime($fecha_captura . ' -1 day'));
			mysqli_query($conn, "UPDATE transaccional_ventas SET fecha_venta = '$fecha_actualizada' WHERE cod_factura = '$cod_factura' ");
		}

	} else {
		$bandera_transaccional = 1;
		echo mysqli_error();
	}

/////// FIN INSERT TRANSACCIONAL /////////////
	//////////////////////////////////////////////

//////////////////////////////////////////////
	///////////// INSERT ORACLE //////////////////

	if ($bandera_ventas_menor == 0 AND $bandera_transaccional == 0) {
/*
$resultado_ERP="INSERT INTO  LOT_DETALLE_FACTURACION
(CODIGO_FACTURA, IDENTIDAD,  LOTERIA, SORTEO, DESCUENTO, MONTO, CODIGO_VENDEDOR, NOMBRE_VENDEDOR, PRECIO, CANTIDAD_BILLETES, NOMBRE_COMPRADOR, COMISION_BANCO, ENTIDAD, TOTAL_BRUTO, APORTACION, CREDITO_PANI, ID_USUARIO, ID_SECCIONAL, FORMA_PAGO )
VALUES('$cod_factura','','2','$id_sorteo', '$total_descuento','$total_neto', '$id_usuario', '$usuario', '$precio_unitario','$total_cantidad' , '','$total_comision','$id_empresa','$total_bruto','$total_aportacion','$total_credito_pani','$id_usuario','$id_seccional','$forma_pago' ) ";

$save_result=oci_parse($conn2, $resultado_ERP);

$rc=oci_execute($save_result);
oci_free_statement($rc);

if(!$rc)
{
$e=oci_error($save_result);
var_dump($e);
$bandera_erp = 1;
}

oci_close($conn2);

 */
	}

//////////// FIN INSERT ORACLE ////////////////
	///////////////////////////////////////////////

////////////////////////////////////////////////////////
	////////// REGISTRO DE DETALLE DE NUMEROS //////////////

	if ($bandera_ventas_menor == 0 AND $bandera_erp == 0 AND $bandera_transaccional == 0) {
		$i = 0;

		while (isset($matriz[$i][0])) {

			if (isset($matriz[$i][0])) {

				$numero = $matriz[$i][0];
				$serie_inicial = $matriz[$i][1];
				$serie_final = $matriz[$i][2];
				$cantidad = $matriz[$i][3];

				while ($serie_inicial <= $serie_final && $cantidad != 0) {
					if (mysqli_query($conn, "INSERT INTO fvp_detalles_ventas_menor (numero,serie,precio_unitario,cod_factura,id_sorteo,estado_venta)
VALUES ('$numero', '$serie_inicial','$precio_unitario','$cod_factura','$id_sorteo','APROBADO' ) ") === false) {
						echo mysqli_error();
						$bandera_detalles = 1;
					}
					$cantidad--;
					$serie_inicial++;
				}

			}

			$i++;
		}

		if ($fecha_registro_venta >= $fecha_captura) {
			$fecha_actualizada = date('Y-m-d H:i:s', strtotime($fecha_captura . ' -1 day'));
			mysqli_query($conn, "UPDATE fvp_detalles_ventas_menor SET fecha_transaccion = '$fecha_actualizada' WHERE cod_factura = '$cod_factura' ");
		}

	}
/////// FIN REGISTRO DE DETALLE DE NUMEROS //////////////
	/////////////////////////////////////////////////////////

	if ($bandera_ventas_menor == 0 AND $bandera_detalles == 0 AND $bandera_transaccional == 0 AND $bandera_erp == 0) {
		echo '<div class="alert alert-success" role="alert"> Venta realizada exitosamente</div>';
	} else {
		echo '<div class="alert alert-danger" role="alert"> La venta no pudo ser realizada</div>';
	}

}

/////////// FIN CODIGO DE GUARDADO /////////////
////////////////////////////////////////////////
?>