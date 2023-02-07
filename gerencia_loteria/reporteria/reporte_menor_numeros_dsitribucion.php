<?php
require '../../template/header.php';
$years = mysqli_query($conn, "SELECT YEAR(fecha_sorteo) as year FROM sorteos_menores GROUP BY YEAR(fecha_sorteo) ORDER BY YEAR(fecha_sorteo) DESC ");

$fecha_actual = date('Y-m-d h:i:s a');

?>




<body>
<form method="POST">




<section style="color:rgb(63,138,214);background-color:#ededed;">
<br>
<h3  align="center" style="color:black; "  >REPORTE DE DISTRIBUCIONES DE LOTERIA MENOR NUMEROS (AGENCIAS)</h3>
<br>
</section>


<a class="btn btn-secondary" id="non-printable" style="width:100%" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
Selección de parametros
</a>

<div  class="collapse" style = "width:100%"  id="collapse1" align="center">
<div class="card" style="width: 50%">
<div class="card-body">


<div class="input-group " style="margin:0px 0px 0px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Año: </span></div>
<select class="form-control" name="select_year" id = 'select_year' style="margin-right: 5px">
<?php
while ($reg_year = mysqli_fetch_array($years)) {
	echo "<option value = '" . $reg_year['year'] . "' >" . $reg_year['year'] . "</option>";
}
?>
</select>


<div class="input-group-append">
<button class="btn btn-success" type="submit" name="seleccionar" > SELECCIONAR</button>
</div>
</div>




</div>
</div>
</div>



<?php
if (isset($_POST['seleccionar'])) {

	$year = $_POST['select_year'];
	$sorteos_en_fecha = mysqli_query($conn, "SELECT MIN(id) as sorteo_minimo, MAX(id) as sorteo_maximo FROM sorteos_menores WHERE YEAR(fecha_sorteo) = '$year'  ORDER BY id ");

	if (mysqli_num_rows($sorteos_en_fecha) > 0) {
		$ob_sorteos_en_fecha = mysqli_fetch_object($sorteos_en_fecha);
		$min_sorteo = $ob_sorteos_en_fecha->sorteo_minimo;
		$max_sorteo = $ob_sorteos_en_fecha->sorteo_maximo;

		$agencias = mysqli_query($conn, "SELECT DISTINCT(id_seccional) as id_seccional, nombre_seccional, CONCAT(departamento,'/',municipio) as depto_muni  FROM distribucion_menor_numeros_banco WHERE id_sorteo BETWEEN '$min_sorteo' AND '$max_sorteo' GROUP BY id_seccional ORDER BY  id_seccional ASC ");

		while ($reg_agencias = mysqli_fetch_array($agencias)) {
			$cod_agencia = $reg_agencias['id_seccional'];
			$nombre_agencia = $reg_agencias['nombre_seccional'];
			$depto_muni = $reg_agencias['depto_muni'];
			$info_agencia = ['cod_agencia' => $cod_agencia, 'nombre_agencia' => $nombre_agencia, 'depto_muni' => $depto_muni];

			$m_agencias[$cod_agencia] = array();
			array_push($m_agencias[$cod_agencia], $info_agencia);
		}

		$distribuciones_sorteos = mysqli_query($conn, "SELECT id_sorteo ,id_seccional ,SUM(serie_final - serie_inicial + 1) as cantidad  FROM distribucion_menor_numeros_banco WHERE id_sorteo BETWEEN '$min_sorteo' AND '$max_sorteo' GROUP BY id_sorteo , id_seccional ORDER BY id_sorteo, id_seccional ASC ");

		while ($reg_distribucion = mysqli_fetch_array($distribuciones_sorteos)) {

			$cod_agencia = $reg_distribucion['id_seccional'];
			$id_sorteo = $reg_distribucion['id_sorteo'];
			$cantidad = $reg_distribucion['cantidad'];

			$info_sorteo = ['id_sorteo' => $id_sorteo, 'cantidad' => $cantidad];

			$m_agencias[$cod_agencia][$id_sorteo] = $info_sorteo;

		}

	}

	?>


<br>
<br>

<div class="card" style="margin-right: 5px; margin-left: 5px ;margin-bottom: 10px">
	<div class="card-header">
		<h3 align="center">AÑO <?php echo $year; ?> </h3>

Fecha de emisión: <?php echo $fecha_actual; ?>
	</div>

	<div class="card-body">

<table class="table table-bordered table-striped table-responsive" id="table_id1" >

<thead class="thead-dark">
<tr>
<th>COD AGENCIA</th>
<th>AGENCIA</th>
<th>DEPARTAMENTO/MUNICIPIO</th>

<?php
$c_sorteos = $min_sorteo;
	while ($c_sorteos <= $max_sorteo) {
		echo "<th>" . $c_sorteos . "</th>";
		$c_sorteos++;
	}
	?>


</tr>
</thead>
<tbody>


	<?php

	$totales = array();
	foreach ($m_agencias as $agencia) {

		$cod_agencia = $agencia[0]['cod_agencia'];
		$nombre_agencia = $agencia[0]['nombre_agencia'];
		$depto_muni = $agencia[0]['depto_muni'];

		echo "<tr>";
		echo "<td>" . $cod_agencia . "</td>";
		echo "<td>" . $nombre_agencia . "</td>";
		echo "<td>" . $depto_muni . "</td>";

		$c_sorteos = $min_sorteo;
		while ($c_sorteos <= $max_sorteo) {

			if (isset($agencia[$c_sorteos])) {
				echo "<td>" . $agencia[$c_sorteos]['cantidad'] . "</td>";

				if (!isset($totales[$c_sorteos])) {
					$totales[$c_sorteos] = $agencia[$c_sorteos]['cantidad'];
				} else {
					$totales[$c_sorteos] += $agencia[$c_sorteos]['cantidad'];
				}

			} else {
				echo "<td>0</td>";
			}

			$c_sorteos++;
		}

		echo "</tr>";

	}

	?>

</tbody>

<tfoot>
	<tr>
		<th colspan="3">TOTALES</th>
		<?php
$c_sorteos = $min_sorteo;
	while ($c_sorteos <= $max_sorteo) {

		if (isset($totales[$c_sorteos])) {
			echo "<th>" . number_format($totales[$c_sorteos]) . "</th>";
		} else {
			echo "<th>0</th>";
		}

		$c_sorteos++;
	}
	?>
	</tr>
</tfoot>

</table>

	</div>
	<div class="card-footer" align="center">

		<button type="submit" name="generar_excel" value="<?php echo $year; ?>" class="btn btn-success"><i class=" fa fa-file-excel"></i>  GENERAR EXCEL</button>

	</div>
</div>

<br>
<br>

	<?php

}
?>



</form>


<?php

if (isset($_POST['generar_excel'])) {

	$year = $_POST['generar_excel'];

	$fecha_actual = date('Y-m-d h:i:s a');

	require_once '../../assets/phpexcel/Classes/PHPExcel/IOFactory.php';

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);

	$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'REPORTE DE DISTRIBUCION DE LOTERIA MENOR NUMEROS (AGENCIAS) ');
	$objPHPExcel->getActiveSheet()->mergeCells('A1:K1');

	$sorteos_en_fecha = mysqli_query($conn, "SELECT MIN(id) as sorteo_minimo, MAX(id) as sorteo_maximo FROM sorteos_menores WHERE YEAR(fecha_sorteo) = '$year'  ORDER BY id ");

	if (mysqli_num_rows($sorteos_en_fecha) > 0) {
		$ob_sorteos_en_fecha = mysqli_fetch_object($sorteos_en_fecha);
		$min_sorteo = $ob_sorteos_en_fecha->sorteo_minimo;
		$max_sorteo = $ob_sorteos_en_fecha->sorteo_maximo;

		echo $min_sorteo . " " . $max_sorteo;

		$agencias = mysqli_query($conn, "SELECT DISTINCT(id_seccional) as id_seccional, nombre_seccional, CONCAT(departamento,'/',municipio) as depto_muni  FROM distribucion_menor_numeros_banco WHERE id_sorteo BETWEEN '$min_sorteo' AND '$max_sorteo' GROUP BY id_seccional ORDER BY  id_seccional ASC ");

		while ($reg_agencias = mysqli_fetch_array($agencias)) {
			$cod_agencia = $reg_agencias['id_seccional'];
			$nombre_agencia = $reg_agencias['nombre_seccional'];
			$depto_muni = $reg_agencias['depto_muni'];
			$info_agencia = ['cod_agencia' => $cod_agencia, 'nombre_agencia' => $nombre_agencia, 'depto_muni' => $depto_muni];

			$m_agencias[$cod_agencia] = array();
			array_push($m_agencias[$cod_agencia], $info_agencia);
		}

		$distribuciones_sorteos = mysqli_query($conn, "SELECT id_sorteo ,id_seccional ,SUM(serie_final - serie_inicial + 1) as cantidad  FROM distribucion_menor_numeros_banco WHERE id_sorteo BETWEEN '$min_sorteo' AND '$max_sorteo' GROUP BY id_sorteo , id_seccional ORDER BY id_sorteo, id_seccional ASC ");

		while ($reg_distribucion = mysqli_fetch_array($distribuciones_sorteos)) {

			$cod_agencia = $reg_distribucion['id_seccional'];
			$id_sorteo = $reg_distribucion['id_sorteo'];
			$cantidad = $reg_distribucion['cantidad'];

			$info_sorteo = ['id_sorteo' => $id_sorteo, 'cantidad' => $cantidad];

			$m_agencias[$cod_agencia][$id_sorteo] = $info_sorteo;

		}

	}

	$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'COD AGENCIA');
	$objPHPExcel->getActiveSheet()->SetCellValue('B3', 'AGENCIA');
	$objPHPExcel->getActiveSheet()->SetCellValue('C3', 'DEPARTAMENTO/MUNICIPIO');

	$c_sorteos = $min_sorteo;
	$row = 3; // 1-based index
	$col = 3;
	while ($c_sorteos <= $max_sorteo) {
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $c_sorteos);
		$c_sorteos++;
		$col++;
	}

	$row = 4; // 1-based index
	$totales = array();
	foreach ($m_agencias as $agencia) {

		$cod_agencia = $agencia[0]['cod_agencia'];
		$nombre_agencia = $agencia[0]['nombre_agencia'];
		$depto_muni = $agencia[0]['depto_muni'];

		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $cod_agencia);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $nombre_agencia);
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $depto_muni);

		$col = 3;
		$c_sorteos = $min_sorteo;
		while ($c_sorteos <= $max_sorteo) {

			if (isset($agencia[$c_sorteos])) {

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $agencia[$c_sorteos]['cantidad']);

				if (!isset($totales[$c_sorteos])) {
					$totales[$c_sorteos] = $agencia[$c_sorteos]['cantidad'];
				} else {
					$totales[$c_sorteos] += $agencia[$c_sorteos]['cantidad'];
				}

			} else {
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 0);
			}

			$c_sorteos++;

			$col++;
		}
		$row++;
	}

	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, 'TOTALES ');
	$objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':C' . $row);

	$col = 3;
	$c_sorteos = $min_sorteo;
	while ($c_sorteos <= $max_sorteo) {

		if (isset($totales[$c_sorteos])) {
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getNumberFormat()->setFormatCode("#,##0");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $totales[$c_sorteos]);
		} else {
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 0);
		}

		$c_sorteos++;
		$col++;
	}

	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=\"Reporte_ventas_mayor_agencia.xlsx\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	ob_clean();
	$objWriter->save("php://output");

}

?>