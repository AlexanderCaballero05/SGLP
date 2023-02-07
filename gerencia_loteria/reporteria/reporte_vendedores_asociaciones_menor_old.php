<?php

require "../../template/header.php";
date_default_timezone_set('America/Tegucigalpa');

?>


<form method="POST">


<section style="background-color:#ededed;">
<br>
<h3 align="center"><b>LISTADO DE VENDEDORES POR SECCIONAL</b></h3>
<br>
</section>



<a  id = 'non-printable' style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
Seleccion de Parametros
</a>


<div class="card collapse" id="collapse1" style="margin-left: 250px; margin-right: 250px;" >
<div class="card-body">

<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">Seccional:</div>
</div>

<select class="form-control"  name = "s_seccional" id = 's_seccional' ">
<?php

$seccionales = mysqli_query($conn, "SELECT *  FROM asociaciones_seccionales  ORDER BY CONCAT(codigo_asociacion, codigo_seccional) ASC ");

echo '<option value = "todas">TODAS</option>';

while ($row2 = mysqli_fetch_array($seccionales)) {

	if ($row2['codigo_asociacion'] == "A") {
		$desc_asociacion = "ANAVELH";
	} elseif ($row2['codigo_asociacion'] == 'B') {
		$desc_asociacion = "ANVLUH";
	} else {
		$desc_asociacion = "SIN ASOCIACION";
	}

	echo '<option value = "' . $row2['codigo_asociacion'] . "-" . $row2['codigo_seccional'] . "-" . $row2['zona'] . '">' . $desc_asociacion . ' - ' . $row2['codigo_seccional'] . ' - ' . $row2['zona'] . '</option>';
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

	if ($_POST['s_seccional'] == "todas") {

		$vendedores = mysqli_query($conn, "SELECT * FROM vendedores  ORDER BY  asociacion, seccional, codigo ");

		echo "<br>";
		echo "<br>";
		echo "<div class = 'card'>";
		echo "<div class = 'card-header bg-dark text-white'>";

		echo "<h4>TODAS LAS ASOCIACIONES Y SECCIONALES </h4>";

		echo "</div>";

		echo "<div class = 'card-body'>";

		echo "<table class = 'table table-bordered'>";
		echo "<tr>";
		echo "<th>N0.</th>";
		echo "<th>NOMBRE COMPLETO</th>";
		echo "<th>IDENTIDAD</th>";
		echo "<th>NO. DE CARNET</th>";
		echo "<th>BOLSAS ASIGNADAS</th>";
		echo "<th>ZONA</th>";
		echo "<th>TELEFONO</th>";
		echo "<th>GENERO</th>";
		echo "</tr>";

		$i = 1;
		$tt_bolsas = 0;
		while ($reg_vendedores = mysqli_fetch_array($vendedores)) {
			echo "<tr>";
			echo "<td>" . $i . "</td>";
			echo "<td>" . $reg_vendedores['nombre'] . "</td>";
			echo "<td>" . $reg_vendedores['identidad'] . "</td>";
			echo "<td>" . $reg_vendedores['asociacion'] . "-" . $reg_vendedores['seccional'] . "-" . $reg_vendedores['codigo'] . "</td>";
			echo "<td>" . $reg_vendedores['numero_bolsas'] . "</td>";
			echo "<td>" . $reg_vendedores['zona_venta'] . "</td>";
			echo "<td>" . $reg_vendedores['telefono'] . "</td>";
			echo "<td>" . $reg_vendedores['sexo'] . "</td>";
			echo "</tr>";
			$i++;
			$tt_bolsas += $reg_vendedores['numero_bolsas'];
		}

		echo "<tr>";
		echo "<th colspan = '4' style = 'text-align: center'>TOTAL BOLSAS</th>";
		echo "<th>" . number_format($tt_bolsas) . "</th>";
		echo "<td colspan = '3'></td>";
		echo "</tr>";

		echo "</table>";

		echo "</div>";

		echo "<div class = 'card-footer' id = 'non-printable' align = 'center'>";

		echo "<button class = 'btn btn-success' value = 'todas' name = 'excel' type = 'submit'>GENERAR EXCEL</button>";

		echo "</div>";

		echo "</div>";

	} else {

		$v_parametros = explode("-", $_POST['s_seccional']);
		$cod_asociacion = $v_parametros[0];
		$cod_seccional = $v_parametros[1];
		$zona = $v_parametros[2];

		if ($cod_asociacion == "A") {
			$desc_asociacion = "ANAVELH";
		} elseif ($cod_asociacion == "B") {
			$desc_asociacion = "ANVLUH";
		} else {
			$desc_asociacion = "SIN ASOCIACION";
		}

		$vendedores = mysqli_query($conn, "SELECT * FROM vendedores WHERE asociacion = '$cod_asociacion' AND seccional = '$cod_seccional' ORDER BY codigo ");

		echo "<br>";
		echo "<br>";
		echo "<div class = 'card'>";
		echo "<div class = 'card-header bg-dark text-white'>";

		echo "<h4>ASOCIACION: " . $desc_asociacion . " </h4>";
		echo "<h4>SECCIONAL: " . $cod_seccional . " </h4>";
		echo "<h4>ZONA: " . strtoupper($zona) . " </h4>";

		echo "</div>";

		echo "<div class = 'card-body'>";

		echo "<table class = 'table table-bordered'>";
		echo "<tr>";
		echo "<th>N0.</th>";
		echo "<th>NOMBRE COMPLETO</th>";
		echo "<th>IDENTIDAD</th>";
		echo "<th>NO. DE CARNET</th>";
		echo "<th>BOLSAS ASIGNADAS</th>";
		echo "<th>TELEFONO</th>";
		echo "<th>GENERO</th>";
		echo "</tr>";

		$i = 1;
		$tt_bolsas = 0;
		while ($reg_vendedores = mysqli_fetch_array($vendedores)) {
			echo "<tr>";
			echo "<td>" . $i . "</td>";
			echo "<td>" . $reg_vendedores['nombre'] . "</td>";
			echo "<td>" . $reg_vendedores['identidad'] . "</td>";
			echo "<td>" . $reg_vendedores['asociacion'] . "-" . $reg_vendedores['seccional'] . "-" . $reg_vendedores['codigo'] . "</td>";
			echo "<td>" . $reg_vendedores['numero_bolsas'] . "</td>";
			echo "<td>" . $reg_vendedores['telefono'] . "</td>";
			echo "<td>" . $reg_vendedores['sexo'] . "</td>";
			echo "</tr>";
			$i++;
			$tt_bolsas += $reg_vendedores['numero_bolsas'];
		}

		echo "<tr>";
		echo "<th colspan = '4' style = 'text-align: center'>TOTAL BOLSAS</th>";
		echo "<th>" . number_format($tt_bolsas) . "</th>";
		echo "<td colspan = '2'></td>";
		echo "</tr>";

		echo "</table>";

		echo "</div>";

		echo "<div class = 'card-footer' id = 'non-printable' align = 'center'>";

		echo "<button class = 'btn btn-success' value = '" . $cod_asociacion . "-" . $cod_seccional . "-" . $zona . "' name = 'excel' type = 'submit'>GENERAR EXCEL</button>";

		echo "</div>";

		echo "</div>";

	}

}

?>

</form>


<?php

if (isset($_POST['excel'])) {

	if ($_POST['excel'] == "todas") {

		$vendedores = mysqli_query($conn, "SELECT * FROM vendedores  ORDER BY  asociacion, seccional, codigo ");

		require_once '../../assets/phpexcel/Classes/PHPExcel/IOFactory.php';

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'NO.');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'NOMBRE');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'IDENTIDAD');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'NO. CARNET');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'BOLSAS ASIGNADAS');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'ZONA');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'TELEFONO');
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'GENERO');

		$row = 2;
		$i = 1;
		$tt_bolsas = 0;
		while ($reg_vendedores = mysqli_fetch_array($vendedores)) {

			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $i);
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $reg_vendedores['nombre']);

			$objPHPExcel->getActiveSheet()->getStyle('C' . $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			$objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $reg_vendedores['identidad']);
			$objPHPExcel->getActiveSheet()->getStyle('C' . $row)->getNumberFormat()->setFormatCode("0000000000000");

			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, $reg_vendedores['asociacion'] . "-" . $reg_vendedores['seccional'] . "-" . $reg_vendedores['codigo']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E' . $row, $reg_vendedores['numero_bolsas']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F' . $row, $reg_vendedores['zona_venta']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G' . $row, $reg_vendedores['telefono']);
			$objPHPExcel->getActiveSheet()->SetCellValue('H' . $row, $reg_vendedores['sexo']);

			$tt_bolsas += $reg_vendedores['numero_bolsas'];
			$row++;
			$i++;
		}

		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, 'TOTAL BOLSAS  ');
		$objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':D' . $row);
		$objPHPExcel->getActiveSheet()->SetCellValue('E' . $row, $tt_bolsas);

	} else {

		$v_parametros = explode("-", $_POST['excel']);
		$cod_asociacion = $v_parametros[0];
		$cod_seccional = $v_parametros[1];
		$zona = $v_parametros[2];

		if ($cod_asociacion == "A") {
			$desc_asociacion = "ANAVELH";
		} elseif ($cod_asociacion == "B") {
			$desc_asociacion = "ANVLUH";
		} else {
			$desc_asociacion = "SIN ASOCIACION";
		}

		$vendedores = mysqli_query($conn, "SELECT * FROM vendedores WHERE asociacion = '$cod_asociacion' AND seccional = '$cod_seccional' ORDER BY codigo ");

		require_once '../../assets/phpexcel/Classes/PHPExcel/IOFactory.php';

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', "ASOCIACION: " . $desc_asociacion);
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', "SECCIONAL: " . $cod_seccional);
		$objPHPExcel->getActiveSheet()->SetCellValue('A3', "ZONA: " . $zona);

		$objPHPExcel->getActiveSheet()->SetCellValue('A5', 'NO.');
		$objPHPExcel->getActiveSheet()->SetCellValue('B5', 'NOMBRE');
		$objPHPExcel->getActiveSheet()->SetCellValue('C5', 'IDENTIDAD');
		$objPHPExcel->getActiveSheet()->SetCellValue('D5', 'NO. CARNET');
		$objPHPExcel->getActiveSheet()->SetCellValue('E5', 'BOLSAS ASIGNADAS');
		$objPHPExcel->getActiveSheet()->SetCellValue('F5', 'TELEFONO');
		$objPHPExcel->getActiveSheet()->SetCellValue('G5', 'GENERO');

		$row = 6;
		$i = 1;
		$tt_bolsas = 0;
		while ($reg_vendedores = mysqli_fetch_array($vendedores)) {

			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $i);
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $reg_vendedores['nombre']);

			$objPHPExcel->getActiveSheet()->getStyle('C' . $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			$objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $reg_vendedores['identidad']);
			$objPHPExcel->getActiveSheet()->getStyle('C' . $row)->getNumberFormat()->setFormatCode("0000000000000");

			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, $reg_vendedores['asociacion'] . "-" . $reg_vendedores['seccional'] . "-" . $reg_vendedores['codigo']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E' . $row, $reg_vendedores['numero_bolsas']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F' . $row, $reg_vendedores['telefono']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G' . $row, $reg_vendedores['sexo']);

			$tt_bolsas += $reg_vendedores['numero_bolsas'];
			$row++;
			$i++;
		}

		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, 'TOTAL BOLSAS  ');
		$objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':D' . $row);
		$objPHPExcel->getActiveSheet()->SetCellValue('E' . $row, $tt_bolsas);

	}

	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=\"Listado_vendedores_seccionales.xlsx\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	ob_clean();
	$objWriter->save("php://output");

}

?>