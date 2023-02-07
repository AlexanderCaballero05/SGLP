<?php

require "../../template/header.php";

$c_asociaciones = mysqli_query($conn, "SELECT * FROM asociaciones_vendedores ");

$c_sorteos = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE id >= 3154 ORDER BY id DESC ");

?>

<style type="text/css">
@media print
{
#non-printable { display: none; }
#printable { display: block; }
}
</style>


<script type="text/javascript">
function consultar_venta(tipo){

if (tipo == 1) {

sorteo = document.getElementById('select_sorteo').value;
asocia = document.getElementById('select_asociacion').value;

var div = document.getElementById('info_sorteo');
div.innerHTML = '<h3 align = "center" style="color:black; ">SORTEO '+sorteo+'</h3>';



token = Math.random();
consulta = 'informe_venta_asociaciones_db.php?id_s='+sorteo+"&filtro="+tipo+"&asocia="+asocia+"&token="+token;
$("#consulta_asociacion").load(consulta);

}

}
</script>


<section style="color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2 align="center" style="color:black;" id="titulo" >INFORME DE VENTA DE LOTERIA MENOR POR ASOCIACION</h2>
<div id="info_sorteo"></div>
<br>
</section>




<form method="POST">

<br>

<div class="card" style="margin-right: 10px; margin-left: 10px">
<div class="card-header" id="non-printable">

<div class="row">
<div class="col col-md-2"></div>
<div class="col">

<div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">SORTEO</span>
  </div>

<select class="form-control" name="select_sorteo" id = 'select_sorteo'>
<?php
while ($reg_sorteos = mysqli_fetch_array($c_sorteos)) {
	echo "<option value = '" . $reg_sorteos['id'] . "' >" . $reg_sorteos['id'] . " | " . $reg_sorteos['fecha_sorteo'] . "</option>";
}
?>
</select>

<span class="input-group-text">ASOCIACION</span>

<select class="form-control" name="select_asociacion" id = 'select_asociacion'>
<option value="t">TODAS</option>
<?php
while ($reg_asociaciones = mysqli_fetch_array($c_asociaciones)) {
	echo "<option value = '" . $reg_asociaciones['codigo_asociacion'] . "' >" . $reg_asociaciones['nombre_asociacion'] . "</option>";
}
?>
</select>

<span class="btn btn-primary"  onclick="consultar_venta(1)">CONSULTAR</span>

</div>


</div>

<div class="col col-md-2"></div>
</div>
</div>

<div class="card-body">
	<div class="col col-sm-12" id="consulta_asociacion" align="center" ></div>
</div>

</div>



</form>


<?php

if (isset($_POST['excel'])) {

	$c_vendedores = mysqli_query($conn, "SELECT LPAD(identidad, 13, '0') as id, nombre FROM vendedores");

	$i = 0;
	while ($r_vendedores = mysqli_fetch_array($c_vendedores)) {
		$v_vendedores[$r_vendedores['id']] = $r_vendedores['nombre'];
		$i++;
	}

	$id_sorteo = $_POST['excel'];

	$fecha_actual = date('Y-m-d h:i:s a');

	require_once '../../assets/phpexcel/Classes/PHPExcel/IOFactory.php';

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);

	$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'REPORTE DE VENTA DE LOTERIA MENOR POR ASOCIACION  ');
	$objPHPExcel->getActiveSheet()->mergeCells('A1:K1');

	$consulta_ventas_a = mysqli_query($conn, " SELECT SUM(a.cantidad) as cantidad, LPAD( a.identidad_comprador, 13, '0') as identidad_comprador, a.nombre_comprador, a.asociacion_comprador FROM transaccional_ventas_general as a WHERE a.cod_producto = 3 AND a.estado_venta = 'APROBADO' AND a.id_sorteo = '$id_sorteo' AND a.asociacion_comprador = 'A' GROUP BY a.identidad_comprador  ORDER BY a.identidad_comprador ");

	$consulta_ventas_b = mysqli_query($conn, " SELECT SUM(a.cantidad) as cantidad, LPAD( a.identidad_comprador, 13, '0') as identidad_comprador, a.nombre_comprador, a.asociacion_comprador FROM transaccional_ventas_general as a  WHERE a.cod_producto = 3 AND a.estado_venta = 'APROBADO' AND a.id_sorteo = '$id_sorteo' AND a.asociacion_comprador = 'B' GROUP BY a.identidad_comprador  ORDER BY a.identidad_comprador ");

	$consulta_ventas_c = mysqli_query($conn, " SELECT SUM(a.cantidad) as cantidad, a.identidad_comprador, a.nombre_comprador, a.asociacion_comprador FROM transaccional_ventas_general as a WHERE a.cod_producto = 3 AND a.estado_venta = 'APROBADO' AND a.id_sorteo = '$id_sorteo' AND a.asociacion_comprador = 'C'   GROUP BY a.identidad_comprador  ORDER BY a.identidad_comprador ");

	$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'IDENTIDAD');
	$objPHPExcel->getActiveSheet()->SetCellValue('B3', 'NOMBRE');
	$objPHPExcel->getActiveSheet()->SetCellValue('C3', 'CANTIDAD');
	$objPHPExcel->getActiveSheet()->SetCellValue('D3', 'ASOCIACION');

	$row = 4; // 1-based index

	$conteo_vendido = 0;

	if (isset($consulta_ventas_a)) {

		while ($reg_ventas_a = mysqli_fetch_array($consulta_ventas_a)) {

			if (isset($v_vendedores[$reg_ventas_a['identidad_comprador']])) {
				$nombre_comprador = $v_vendedores[$reg_ventas_a['identidad_comprador']];
			} else {
				$nombre_comprador = "";
			}

			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $reg_ventas_a['identidad_comprador']);
			$objPHPExcel->getActiveSheet()->getStyle('A' . $row)->getNumberFormat()->setFormatCode('0000000000000');
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $nombre_comprador);
			$objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $reg_ventas_a['cantidad']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, 'ANAVELH');
			$row++;

		}

	}

	if (isset($consulta_ventas_b)) {

		while ($reg_ventas_b = mysqli_fetch_array($consulta_ventas_b)) {

			if (isset($v_vendedores[$reg_ventas_b['identidad_comprador']])) {
				$nombre_comprador = $v_vendedores[$reg_ventas_b['identidad_comprador']];
			} else {
				$nombre_comprador = "";
			}

			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $reg_ventas_b['identidad_comprador']);
			$objPHPExcel->getActiveSheet()->getStyle('A' . $row)->getNumberFormat()->setFormatCode('0000000000000');
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $nombre_comprador);
			$objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $reg_ventas_b['cantidad']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, 'ANVLUH');
			$row++;

		}

	}

	if (isset($consulta_ventas_c)) {

		while ($reg_ventas_c = mysqli_fetch_array($consulta_ventas_c)) {

			if (isset($v_vendedores[$reg_ventas_c['identidad_comprador']])) {
				$nombre_comprador = $v_vendedores[$reg_ventas_c['identidad_comprador']];
			} else {
				$nombre_comprador = "";
			}

			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $reg_ventas_c['identidad_comprador']);
			$objPHPExcel->getActiveSheet()->getStyle('A' . $row)->getNumberFormat()->setFormatCode('0000000000000');
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $nombre_comprador);
			$objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $reg_ventas_c['cantidad']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, 'SIN ASOCIACION');
			$row++;

		}

	}

	$objPHPExcel->createSheet(1);
	$objPHPExcel->setActiveSheetIndex(1);

	$consulta_ventas = mysqli_query($conn, "SELECT b.nombre_asociacion , SUM(a.cantidad) as cantidad, a.asociacion_comprador, COUNT(DISTINCT(a.identidad_comprador))  as activo FROM transaccional_ventas_general as a INNER JOIN asociaciones_vendedores as b ON a.asociacion_comprador = b.codigo_asociacion  WHERE a.id_sorteo = '$id_sorteo' AND a.cod_producto = 3 AND a.estado_venta = 'APROBADO'   GROUP BY a.asociacion_comprador ");

	if ($consulta_ventas === false) {
		echo mysqli_error();
	}

	$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ASOCIACION');
	$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'CANTIDAD COMPRADA');
	$objPHPExcel->getActiveSheet()->SetCellValue('C1', '# VENDEDORES ACTIVOS');

	$tt = 0;
	$f = 2;
	while ($reg_ventas = mysqli_fetch_array($consulta_ventas)) {

		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $f, $reg_ventas['nombre_asociacion']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $f, number_format($reg_ventas['cantidad']));
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $f, $reg_ventas['activo']);
		$tt += $reg_ventas['cantidad'];
		$f++;
	}

	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $f, "TOTAL");
	$objPHPExcel->getActiveSheet()->SetCellValue('B' . $f, number_format($tt));

	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=\"Informe de Ventas por asociacion.xlsx\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	ob_clean();
	$objWriter->save("php://output");

}

?>