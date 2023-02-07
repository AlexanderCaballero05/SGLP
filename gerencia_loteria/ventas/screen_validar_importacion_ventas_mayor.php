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

<section style="background-color:#ededed;">
<br>
<h3 align="center"><b>VALIDADOR DE VENTAS LOTERIA MAYOR (EXCEL)</b></h3>
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
	require "./validar_importacion_ventas_mayor_db.php";
}
?>


</form>
</body>







<?php
////////////////////////////////////////////////
//////////// CODIGO DE EXCEL ////////////////

if (isset($_POST['generar_irregularidades'])) {

	require_once '../../assets/phpexcel/Classes/PHPExcel/IOFactory.php';

	$fecha_actual = date('Y-m-d h:i:s a');
	$matriz_errores = unserialize($_POST['generar_irregularidades']);

	$id_sorteo = $matriz_errores[0][0];
	$id_entidad = $matriz_errores[0][1];
	$nombre_empresa = $matriz_errores[0][2];

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);

	$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'INFORME DE IRREGULARIDADES PREVIO A IMPORTACION DE VENTAS SORTEO ' . $id_sorteo . " ENTIDAD " . $nombre_empresa);
	$objPHPExcel->getActiveSheet()->mergeCells('A1:K1');

	$objPHPExcel->getActiveSheet()->SetCellValue('A3', '# FILA');
	$objPHPExcel->getActiveSheet()->SetCellValue('B3', 'BILLETE INICIAL');
	$objPHPExcel->getActiveSheet()->SetCellValue('C3', 'BILLETE FINAL');
	$objPHPExcel->getActiveSheet()->SetCellValue('D3', 'CANTIDAD');
	$objPHPExcel->getActiveSheet()->SetCellValue('E3', 'DESCRIPCION');

	$index = 1;
	$row = 4;

	while (isset($matriz_errores[$index][0])) {

		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $matriz_errores[$index][0]);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $matriz_errores[$index][1]);
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $matriz_errores[$index][2]);
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, $matriz_errores[$index][3]);
		$objPHPExcel->getActiveSheet()->SetCellValue('E' . $row, $matriz_errores[$index][4]);

		if ($matriz_errores[$index][4] != "OK") {

			$objPHPExcel->getActiveSheet()
				->getStyle('A' . $row . ':G' . $row)
				->getFill()
				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
				->getStartColor()
				->setARGB('ff8484');

		} else {

			$objPHPExcel->getActiveSheet()
				->getStyle('A' . $row . ':G' . $row)
				->getFill()
				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
				->getStartColor()
				->setARGB('a6c4a4');

		}

		$row++;
		$index++;
	}

	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=\"INFORME IRREGULARIDADES " . $nombre_empresa . " " . $id_sorteo . ".xlsx\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	ob_clean();
	$objWriter->save("php://output");

}

/////////// FIN CODIGO DE EXCEL /////////////
////////////////////////////////////////////////

// #####################################################################################################################################

////////////////////////////////////////////////
/////////// FIN CODIGO DE EXCEL /////////////

if (isset($_POST['generar_trituracion'])) {

	require_once '../../assets/phpexcel/Classes/PHPExcel/IOFactory.php';

	$fecha_actual = date('Y-m-d h:i:s a');
	$matriz_venta = unserialize($_POST['generar_trituracion']);

	$id_sorteo = $matriz_venta[0][0];
	$id_entidad = $matriz_venta[0][1];
	$nombre_empresa = $matriz_venta[0][2];

	$info_sorteo = mysqli_query($conn, "SELECT *  FROM sorteos_mayores WHERE id = '$id_sorteo' limit 1");
	$value = mysqli_fetch_object($info_sorteo);
	$sorteo = $value->no_sorteo_may;
	$fecha_sorteo = $value->fecha_sorteo;
	$mezcla = $value->mezcla;

	$consulta_total_pedido = mysqli_query($conn, "SELECT a.rango FROM sorteos_mezclas_rangos as a  INNER JOIN sorteos_mezclas as b ON b.num_mezcla = a.num_mezcla AND a.id_sorteo = b.id_sorteo WHERE a.id_sorteo = '$id_sorteo' AND b.id_empresa = '$id_entidad' GROUP BY a.rango ORDER BY a.rango ASC ");

	$n = 0;
	$cantidad_pedido = 0;

	while ($reg_total_pedido = mysqli_fetch_array($consulta_total_pedido)) {

		$b_asginado_i = $reg_total_pedido['rango'];
		$b_asginado_f = $reg_total_pedido['rango'] + $mezcla - 1;
		$cantidad_pedido += $mezcla;

		while ($b_asginado_i <= $b_asginado_f) {
			$v_asginado[$n] = $b_asginado_i;
			$b_asginado_i++;
			$n++;
		}

	}

	$f = 1;
	$n = 0;
	while (isset($matriz_venta[$f][0])) {

		$billete_i = $matriz_venta[$f][0];
		$billete_f = $matriz_venta[$f][1];

		while ($billete_i <= $billete_f) {
			$v_vendido[$n] = $billete_i;
			$billete_i++;
			$n++;
		}

		$f++;
	}

	function getRanges($nums) {
		sort($nums);
		$ranges = array();

		for ($i = 0, $len = count($nums); $i < $len; $i++) {
			$rStart = $nums[$i];
			$rEnd = $rStart;
			while (isset($nums[$i + 1]) && $nums[$i + 1] - $nums[$i] == 1) {
				$rEnd = $nums[++$i];
			}

			$ranges[] = $rStart == $rEnd ? $rStart : $rStart . '-' . $rEnd;
		}

		return $ranges;
	}

	if (isset($v_vendido[0])) {
		$v_no_vendido = array_diff($v_asginado, $v_vendido);
	} else {
		$v_no_vendido = $v_asginado;
	}

	$rangos_no_vendido = getRanges($v_no_vendido);

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);

	$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'INFORME DE PRELIMINAR DE TRITURACION SORTEO ' . $id_sorteo . " ENTIDAD " . $nombre_empresa);
	$objPHPExcel->getActiveSheet()->mergeCells('A1:K1');

	$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'BILLETE INICIAL');
	$objPHPExcel->getActiveSheet()->SetCellValue('B3', 'BILLETE FINAL');
	$objPHPExcel->getActiveSheet()->SetCellValue('C3', 'CANTIDAD');

	$index = 1;
	$row = 4;

	$n = 0;
	$total_no_vendido = 0;
	while (isset($rangos_no_vendido[$n])) {

		$v_no = explode("-", $rangos_no_vendido[$n]);
		$v_serie_n_i = $v_no[0];

		if (isset($v_no[1])) {
			$v_serie_n_f = $v_no[1];
		} else {
			$v_serie_n_f = $v_serie_n_i;
		}

		$cantidad_entre_series = $v_serie_n_f - $v_serie_n_i + 1;

		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $v_serie_n_i);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $v_serie_n_f);
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $cantidad_entre_series);

		$row++;
		$fila++;
		$n++;
	}

	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=\"INFORME PRELIMINAR DE TRITURACION " . $nombre_empresa . " " . $id_sorteo . ".xlsx\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	ob_clean();
	$objWriter->save("php://output");

}

/////////// FIN CODIGO DE EXCEL /////////////
////////////////////////////////////////////////
?>
