<?php

require '../../template/header.php';

$years = mysqli_query($conn, "SELECT YEAR(fecha_sorteo) as year FROM sorteos_menores WHERE YEAR(fecha_sorteo) >= '2017'  GROUP BY YEAR(fecha_sorteo) ORDER BY YEAR(fecha_sorteo) DESC ");

?>

<form method="POST">

<section style="background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  id="titulo" >INGRESOS CON CONTRAPRESTACION NICSP 9</h2>
<h4  align="center" style="color:black; "  id="titulo" >MOVIMIENTO DE VENTAS DE LOTERIA MENOR

<?php

if (isset($_POST['seleccionar'])) {
	echo $_POST['select_year'];
}

?>

</h4>

<button class="btn btn-info" style="width: 100%" id="non-printable" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
  Seleccion de Parametros
</button>
</section>

<div class="collapse" id="collapseOne" style="width: 100%" align="center"  style="background-color: grey">
<div class="card card-body" id="non-printable" style="width: 100%">
<div class="input-group " style="margin:0px 0px 0px 0px; width: 50%">
<div class="input-group-prepend"><span class="input-group-text">Año: </span></div>
<select class="form-control" name="select_year" id = 'select_year' style="margin-right: 5px">
<?php
while ($reg_year = mysqli_fetch_array($years)) {
	echo "<option value = '" . $reg_year['year'] . "' >" . $reg_year['year'] . "</option>";
}
?>
</select>

<div class="input-group-append">
<button type = 'submit' name = 'seleccionar' class="btn btn-success" > SELECCIONAR</button>
</div>
</div>
</div>
</div>

</form>

<br>

<?php

if (isset($_POST['seleccionar'])) {

	$select_year = $_POST['select_year'];

	$actual_year = date('Y');

	if ($select_year < $actual_year) {

		$actual_month = '12';
		$indicador_mes = (int) $actual_month + 1;

	} else {

		$actual_month = date('m');
		$indicador_mes = (int) $actual_month;

	}

	$v_meses[1] = "ENERO";
	$v_meses[2] = "FEBRERO";
	$v_meses[3] = "MARZO";
	$v_meses[4] = "ABRIL";
	$v_meses[5] = "MAYO";
	$v_meses[6] = "JUNIO";
	$v_meses[7] = "JULIO";
	$v_meses[8] = "AGOSTO";
	$v_meses[9] = "SEPTIEMBRE";
	$v_meses[10] = "OCTUBRE";
	$v_meses[11] = "NOVIEMBRE";
	$v_meses[12] = "DICIEMBRE";

	echo "<table class = 'table table-bordered'>";
	echo "<tr>";
	echo "<th>MES</th>";
	echo "<th>EMISION</th>";
	echo "<th>DEVOLUC</th>";
	echo "<th>% DEV</th>";
	echo "<th>MONTO</th>";
	echo "<th>VENTA</th>";
	echo "<th>% VENTA</th>";
	echo "<th>MONTO</th>";
	echo "</tr>";

	$tt_emision = 0;
	$tt_venta = 0;
	$tt_devolucion = 0;
	$tt_venta_lps = 0;
	$tt_devolucion_lps = 0;

	$i = 1;
	while ($i < $indicador_mes) {

////////////////////////////////// EMISION /////////////////////////////////////

		$consulta_emision = mysqli_query($conn, "SELECT SUM(series) as emision, precio_unitario FROM sorteos_menores WHERE YEAR(fecha_sorteo) = '$select_year' AND MONTH(fecha_sorteo) = '$i'  ");

		echo mysqli_error($conn);

		$ob_consulta_emision = mysqli_fetch_object($consulta_emision);
		$cantidad_emision = $ob_consulta_emision->emision;
		$precio_unitario = $ob_consulta_emision->precio_unitario * 100;

////////////////////////////////// EMISION /////////////////////////////////////

//////////////////////////////// VENTA BANCO ///////////////////////////////////

		$consulta_venta_banco = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad FROM transaccional_ventas_general WHERE estado_venta = 'APROBADO'  AND cod_producto = 3 AND id_entidad = 3 AND MONTH(fecha_venta) = '$i' AND YEAR(fecha_venta) = '$select_year' ");

		$ob_venta_banco = mysqli_fetch_object($consulta_venta_banco);
		$cantidad_banco = $ob_venta_banco->cantidad;

//////////////////////////////// VENTA BANCO ///////////////////////////////////

//////////////////////////////// VENTA FFVPP ///////////////////////////////////

		$consulta_fvp = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad  FROM ( SELECT cantidad FROM transaccional_ventas WHERE estado_venta = 'APROBADO'  AND MONTH(fecha_venta) = '$i' AND YEAR(fecha_venta) = '$select_year' AND cod_producto = 3 UNION ALL SELECT cantidad FROM transaccional_ventas_ajuste WHERE estado_venta = 'APROBADO'  AND MONTH(fecha_venta) = '$i' AND YEAR(fecha_venta) = '$select_year'  AND cod_producto = 3 ) as t   ");

		$ob_venta_fvp = mysqli_fetch_object($consulta_fvp);
		$cantidad_otros = $ob_venta_fvp->cantidad;

//////////////////////////////// VENTA FFVPP ///////////////////////////////////

		$venta_global = $cantidad_otros + $cantidad_banco;
		$venta_lps = $venta_global * $precio_unitario;

		$porcentaje_venta = ($venta_global / $cantidad_emision) * 100;

		$dev_global = $cantidad_emision - $venta_global;
		$dev_lps = $dev_global * $precio_unitario;

		$porcentaje_dev = ($dev_global / $cantidad_emision) * 100;

		$tt_emision += $cantidad_emision;
		$tt_venta += $venta_global;
		$tt_devolucion += $dev_global;
		$tt_venta_lps += $venta_lps;
		$tt_devolucion_lps += $dev_lps;

		echo "<tr>";
		echo "<td>" . $v_meses[$i] . "</td>";
		echo "<td>" . number_format($cantidad_emision) . "</td>";
		echo "<td>" . number_format($dev_global) . "</td>";
		echo "<td>" . number_format($porcentaje_dev, 2) . "%</td>";
		echo "<td> L. " . number_format($dev_lps, 2) . "</td>";
		echo "<td>" . number_format($venta_global) . "</td>";
		echo "<td>" . number_format($porcentaje_venta, 2) . "%</td>";
		echo "<td> L. " . number_format($venta_lps, 2) . "</td>";
		echo "</tr>";

		$i++;
	}

	$tt_porcentaje_dev = ($tt_devolucion / $tt_emision) * 100;
	$tt_porcentaje_venta = ($tt_venta / $tt_emision) * 100;

	echo "<tr>";
	echo "<th>TOTAL</th>";
	echo "<th>" . number_format($tt_emision) . "</th>";
	echo "<th>" . number_format($tt_devolucion) . "</th>";
	echo "<th>" . number_format($tt_porcentaje_dev, 2) . "%</th>";
	echo "<th> L. " . number_format($tt_devolucion_lps, 2) . "</th>";
	echo "<th>" . number_format($tt_venta) . "</th>";
	echo "<th>" . number_format($tt_porcentaje_venta, 2) . "%</th>";
	echo "<th> L. " . number_format($tt_venta_lps, 2) . "</th>";
	echo "</tr>";

	echo "</table>";

}

?>