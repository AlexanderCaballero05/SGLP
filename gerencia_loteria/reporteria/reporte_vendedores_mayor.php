<?php

require "../../template/header.php";
date_default_timezone_set('America/Tegucigalpa');

?>

<section style="background-color:#ededed;">
<br>

<?php
if (isset($_POST['seleccionar'])) {
	$id_sorteo = $_POST['select_sorteo'];
	echo '<h3 align="center"><b>INFORME DE COMPRADORES DE LOTERIA MAYOR SORTEO ' . $id_sorteo . '</b></h3>';
} else {
	echo '<h3 align="center"><b>INFORME DE COMPRADORES DE LOTERIA MAYOR POR SORTEO</b></h3>';
}
?>

<br>
</section>


<form method="POST">

<a  id = 'non-printable' style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
Seleccion de Parametros
</a>

<div class="card collapse" id="collapse1" style="margin-left: 250px; margin-right: 250px;" >
<div class="card-body">

<div class="input-group " >
<div class="input-group-prepend"><span class="input-group-text">Sorteo: </span></div>
<select class="form-control" name="select_sorteo" id = 'select_sorteo' >
<?php

$sorteos = mysqli_query($conn, " SELECT * FROM sorteos_mayores ORDER BY id DESC ");

while ($reg_sorteos = mysqli_fetch_array($sorteos)) {
	echo "<option value = '" . $reg_sorteos['id'] . "' >" . $reg_sorteos['id'] . " | " . $reg_sorteos['fecha_sorteo'] . "</option>";
}
?>
</select>

<div class="input-group-append">
<button class="btn btn-success" type="submit" name="seleccionar" id = "seleccionar" > SELECCIONAR</button>
</div>
</div>

</div>
</div>


<?php
if (isset($_POST['seleccionar'])) {
	$id_sorteo = $_POST['select_sorteo'];

	$c_vendedores = mysqli_query($conn, "SELECT * FROM vendedores");
	while ($reg_vendedores = mysqli_fetch_array($c_vendedores)) {
		$identidad = $reg_vendedores['identidad'];
		$nombre = $reg_vendedores['nombre'];
		$asociacion = $reg_vendedores['asociacion'];
		$seccional = $reg_vendedores['seccional'];
		$codigo = $reg_vendedores['codigo'];
		$estado_civil = $reg_vendedores['estado_civil'];
		$sexo = $reg_vendedores['sexo'];
		$telefono = $reg_vendedores['telefono'];
		$zona_venta = $reg_vendedores['zona_venta'];
		$codigo_carnet = $asociacion . "-" . $seccional . "-" . $codigo;

		$v_vendedores[$reg_vendedores['identidad']] = array('identidad' => $reg_vendedores['identidad'], 'codigo' => $codigo_carnet, 'telefono' => $telefono, 'sexo' => $sexo, 'zona_venta' => $zona_venta);
	}

	$c_ventas = mysqli_query($conn, "SELECT identidad_comprador, nombre_comprador, SUM(cantidad) as cantidad FROM transaccional_ventas_general WHERE id_sorteo = '$id_sorteo' AND estado_venta = 'APROBADO' AND cod_producto = '1' GROUP BY identidad_comprador ");

	?>

<br>

<table class="table table-bordered" >
<tr>
	<th>NOMBRE COMPLETO</th>
	<th>IDENTIDAD</th>
	<th>CANTIDAD BILLETES</th>
	<th>NO. CARNET</th>
	<th>TELEFONO</th>
	<th>GENERO</th>
	<th>ZONA</th>
</tr>

	<?php
$tt_cantidad = 0;
	while ($reg_ventas = mysqli_fetch_array($c_ventas)) {

		echo "<tr>";
		echo "<td>" . $reg_ventas['nombre_comprador'] . "</td>";
		echo "<td>" . $reg_ventas['identidad_comprador'] . "</td>";
		echo "<td>" . $reg_ventas['cantidad'] . "</td>";
		$tt_cantidad += $reg_ventas['cantidad'];
		if (isset($v_vendedores[$reg_ventas['identidad_comprador']])) {
			echo "<td>" . $v_vendedores[$reg_ventas['identidad_comprador']]['codigo'] . "</td>";
			echo "<td>" . $v_vendedores[$reg_ventas['identidad_comprador']]['telefono'] . "</td>";
			echo "<td>" . $v_vendedores[$reg_ventas['identidad_comprador']]['sexo'] . "</td>";
			echo "<td>" . $v_vendedores[$reg_ventas['identidad_comprador']]['zona_venta'] . "</td>";
		} else {
			echo "<td colspan = '4'></td>";
		}
		echo "</tr>";

	}

	echo "<tr>";
	echo "<td colspan = '2'>TOTAL</td>";
	echo "<td >" . number_format($tt_cantidad) . "</td>";
	echo "<td colspan = '4'></td>";
	echo "</tr>";
	?>
</table>
<?php

	echo "<div class class = 'row'>";
	echo "<div class = 'col' style = 'text-align:center'>";
	echo "<button type = 'submit' class = 'btn btn-success' name = 'generar_excel' value = '" . $id_sorteo . "'>GENERAR EXCEL</button>";
	echo "</div>";
	echo "</div>";
}
?>

<br>
</form>

<?php

if (isset($_POST['generar_excel'])) {
	$id_sorteo = $_POST['generar_excel'];

	require_once '../../assets/phpexcel/Classes/PHPExcel/IOFactory.php';

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);

	$i = 0;
	$k = 0;
	$contador = 0;
	$w = 0;
	$total_disponible = 0;

	$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'INFORME DE COMPRADORES DE LOTERIA MAYOR SORTEO' . $id_sorteo);

	$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'NOMBRE COMPLETO');
	$objPHPExcel->getActiveSheet()->SetCellValue('B3', 'IDENTIDAD');
	$objPHPExcel->getActiveSheet()->SetCellValue('C3', 'NO. DE CARNET');
	$objPHPExcel->getActiveSheet()->SetCellValue('D3', 'CANTIDAD DE BILLETES');
	$objPHPExcel->getActiveSheet()->SetCellValue('E3', 'TELEFONO');
	$objPHPExcel->getActiveSheet()->SetCellValue('F3', 'GENERO');
	$objPHPExcel->getActiveSheet()->SetCellValue('G3', 'ZONA');

	$c_vendedores = mysqli_query($conn, "SELECT * FROM vendedores");
	while ($reg_vendedores = mysqli_fetch_array($c_vendedores)) {
		$identidad = $reg_vendedores['identidad'];
		$nombre = $reg_vendedores['nombre'];
		$asociacion = $reg_vendedores['asociacion'];
		$seccional = $reg_vendedores['seccional'];
		$codigo = $reg_vendedores['codigo'];
		$estado_civil = $reg_vendedores['estado_civil'];
		$sexo = $reg_vendedores['sexo'];
		$telefono = $reg_vendedores['telefono'];
		$zona_venta = $reg_vendedores['zona_venta'];
		$codigo_carnet = $asociacion . "-" . $seccional . "-" . $codigo;

		$v_vendedores[$reg_vendedores['identidad']] = array('identidad' => $reg_vendedores['identidad'], 'codigo' => $codigo_carnet, 'telefono' => $telefono, 'sexo' => $sexo, 'zona_venta' => $zona_venta);
	}

	$c_ventas = mysqli_query($conn, "SELECT identidad_comprador, nombre_comprador, SUM(cantidad) as cantidad FROM transaccional_ventas_general WHERE id_sorteo = '$id_sorteo' AND estado_venta = 'APROBADO' AND cod_producto = '1' GROUP BY identidad_comprador ");

	$f = 4;

	$tt_cantidad = 0;
	while ($reg_ventas = mysqli_fetch_array($c_ventas)) {

		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $f, $reg_ventas['nombre_comprador']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $f, $reg_ventas['identidad_comprador']);
		$objPHPExcel->getActiveSheet()
			->getStyle('B' . $f)
			->getNumberFormat()
			->setFormatCode('0000000000000');
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $f, $reg_ventas['cantidad']);

		$tt_cantidad += $reg_ventas['cantidad'];

		if (isset($v_vendedores[$reg_ventas['identidad_comprador']])) {

			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $f, $v_vendedores[$reg_ventas['identidad_comprador']]['codigo']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E' . $f, $v_vendedores[$reg_ventas['identidad_comprador']]['telefono']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F' . $f, $v_vendedores[$reg_ventas['identidad_comprador']]['sexo']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G' . $f, $v_vendedores[$reg_ventas['identidad_comprador']]['zona_venta']);

		}

		$f++;
	}

	$objPHPExcel->getActiveSheet()->mergeCells('A' . $f . ':B' . $f);
	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $f, 'TOTAL');
	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $f, $tt_cantidad);

	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=\"INFORME DE COMPRADORES DE LOTERIA MAYOR SORTEO " . $id_sorteo . ".xlsx\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	ob_clean();
	$objWriter->save("php://output");

}

?>