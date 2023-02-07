<?php

require "../../template/header.php";
date_default_timezone_set('America/Tegucigalpa');

?>


<section style="background-color:#ededed;">
<br>
<h3 align="center"><b>DETALLE DE PRODUCCION DE LOTERIA POR NUMERO</b></h3>


<?php
if (isset($_POST['seleccionar'])) {

	$s1 = $_POST['s_sorteo'];
	$s2 = $_POST['s_sorteo2'];

	echo "<h4 align='center'><b> DEL SORTEO " . $s1 . " AL SORTEO " . $s2 . " </b></h4>";

}
?>
<br>
</section>



<a  id = 'non-printable' style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
Seleccion de Parametros
</a>

<form method="POST">

<div class="card collapse" id="collapse1" style="margin-left: 250px; margin-right: 250px;" >
<div class="card-body">

<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">Sorteo Inicial:</div>
</div>

<select class="form-control"  name = "s_sorteo" id = 's_sorteo' ">
<?php
$sorteos = mysqli_query($conn, "SELECT a.id, a.no_sorteo_men, a.fecha_sorteo, a.descripcion_sorteo_men  FROM sorteos_menores as a ORDER BY id DESC ");

while ($row = mysqli_fetch_array($sorteos)) {
	echo '<option value = "' . $row['id'] . '">No.' . $row['no_sorteo_men'] . ' -- Fecha ' . $row['fecha_sorteo'] . '</option>';
}
?>
</select>


<div class="input-group-prepend">
<div class="input-group-text">Sorteo Final:</div>
</div>

<select class="form-control"  name = "s_sorteo2" id = 's_sorteo2' ">
<?php
$sorteos2 = mysqli_query($conn, "SELECT a.id, a.no_sorteo_men, a.fecha_sorteo, a.descripcion_sorteo_men  FROM sorteos_menores as a ORDER BY id DESC ");

while ($row2 = mysqli_fetch_array($sorteos2)) {
	echo '<option value = "' . $row2['id'] . '">No.' . $row2['no_sorteo_men'] . ' -- Fecha ' . $row2['fecha_sorteo'] . '</option>';
}
?>
</select>



<div class="input-group-append">
    <button type="submit" name="seleccionar" id="seleccionar" class="btn btn-primary">Seleccionar</button>
</div>

</div>

</div>
</div>



<?php

if (isset($_POST['seleccionar'])) {

	$s1 = $_POST['s_sorteo'];
	$s2 = $_POST['s_sorteo2'];

	$indicador_columna = 1;

	echo "<br>";
	echo "<button id = 'non-printable' name = 'generar_excel' value = '" . $s1 . "-" . $s2 . "' type = 'submit' class = 'btn btn-success'>GENERAR EXCEL</button>";
	echo "<br>";
	echo "<br>";

	echo "<div class = 'row'>";
	while ($s1 <= $s2) {

		$c_produccion_normal = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE id = '$s1' ");
		$ob_produccion_normal = mysqli_fetch_object($c_produccion_normal);
		$fecha_sorteo = $ob_produccion_normal->fecha_sorteo;
		$cantidad_normal = $ob_produccion_normal->series;

		echo "<div class = 'col'>";
		echo "<table class = 'table table-bordered table-sm'>";
		echo "<tr>";
		echo "<th colspan = '2' style = 'text-align : center'>SORTEO " . $s1 . " - " . $fecha_sorteo . "</th>";
		echo "</tr>";

		echo "<tr>";
		echo "<th colspan = '2' style = 'text-align : center'>PROD. NORMAL</th>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>00 - 99</td>";
		echo "<td>" . number_format($cantidad_normal) . "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<th colspan = '2' style = 'text-align : center'>PROD. COMPLEMENTARIA</th>";
		echo "</tr>";

		echo "<tr>";
		echo "<th>NUMERO</th>";
		echo "<th>CANTIDAD</th>";
		echo "</tr>";

		$c_extra = mysqli_query($conn, " SELECT  numero, SUM(cantidad) as cantidad_extra FROM sorteos_menores_num_extras WHERE id_sorteo = '$s1' GROUP BY numero ORDER BY numero ASC ");

		$tt_producido = 0;
		while ($r_extra = mysqli_fetch_array($c_extra)) {
			echo "<tr>";
			echo "<td>" . $r_extra['numero'] . "</td>";
			echo "<td>" . number_format($r_extra['cantidad_extra']) . "</td>";
			echo "</tr>";
			$tt_producido += $r_extra['cantidad_extra'];
		}

		echo "<tr>";
		echo "<th>TOTAL COMPLEMENTARIO</th>";
		echo "<th>" . number_format($tt_producido) . "</th>";
		echo "</tr>";

		echo "</table>";
		echo "</div>";
		$s1++;

		if ($indicador_columna == 3) {
			echo "</div>";

			$indicador_columna = 0;
			echo "<div class = 'row'>";

		}

		$indicador_columna++;

	}

}

?>

</form>

<?php

if (isset($_POST['generar_excel'])) {

	$v_p = explode('-', $_POST['generar_excel']);
	$s1 = $v_p[0];
	$s2 = $v_p[1];

	require_once '../../assets/phpexcel/Classes/PHPExcel/IOFactory.php';
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);

	$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DETALLE DE PRODUCCION DE LOTERIA POR NUMERO DEL SORTEO ' . $s1 . ' AL SORTEO ' . $s2);
	$objPHPExcel->getActiveSheet()->mergeCells('A1:K1');

	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=\"DETALLE PRODUCCION LOTERIA POR NUMERO.xlsx\"");
	header("Cache-Control: max-age=0");

	$row = 3;
	while ($s1 <= $s2) {

		$c_produccion_normal = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE id = '$s1' ");
		$ob_produccion_normal = mysqli_fetch_object($c_produccion_normal);
		$fecha_sorteo = $ob_produccion_normal->fecha_sorteo;
		$cantidad_normal = $ob_produccion_normal->series;

		$row++;
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, 'SORTEO ' . $s1 . " - " . $fecha_sorteo);
		$objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':B' . $row);

		$row++;
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, "PROD. NORMAL");
		$objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':B' . $row);

		$row++;
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, "00 - 99");
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $cantidad_normal);

		$row++;
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, "PROD. COMPLEMENTARIA");
		$objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':B' . $row);

		$row++;
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, "NUMERO");
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, "CANTIDAD");

		$c_extra = mysqli_query($conn, " SELECT  numero, SUM(cantidad) as cantidad_extra FROM sorteos_menores_num_extras WHERE id_sorteo = '$s1' GROUP BY numero ORDER BY numero ASC ");

		$tt_producido = 0;
		while ($r_extra = mysqli_fetch_array($c_extra)) {

			$row++;
			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $r_extra['numero']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $r_extra['cantidad_extra']);
			$tt_producido += $r_extra['cantidad_extra'];

		}

		$row++;
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, 'TOTAL COMPLEMENTARIO');
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $tt_producido);

		$row++;

		$s1++;
	}

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	ob_clean();
	$objWriter->save("php://output");

}

?>