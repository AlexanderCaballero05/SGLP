<?php

require_once '../assets/phpexcel/Classes/PHPExcel/IOFactory.php';
require_once '../conexion.php';

function cellColor($cells, $color, $size) {
	global $objPHPExcel;

	$objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
		'type' => PHPExcel_Style_Fill::FILL_SOLID,
		'startcolor' => array(
			'rgb' => $color,
		),
	));

	$objPHPExcel->getActiveSheet()->getStyle($cells)->getFont()->setBold(true)
		->setName('Verdana')
		->setSize($size)
		->getColor()->setRGB('ffffff');

}

if (isset($_GET['info'])) {

////////////////////////////////////////////////////////////////////
	/////////////////////////// DONUT INFO ////////////////////////////

	if ($_GET['info'] == 1) {

		$id_sorteo = $_GET['s'];

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle("DETALLE DE LOTERIA VENDIDA");

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DETALLE DE BOLSAS VENDIDAS');
		$objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
		cellColor('A1', '1a1a1a', '11');

		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'SERIE INICIAL');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'SERIE FINAL');
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'CANTIDAD');
		cellColor('A2', '333333', '10');
		cellColor('B2', '333333', '10');
		cellColor('C2', '333333', '10');
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

		$objWorkSheet = $objPHPExcel->createSheet(1);
		$objPHPExcel->setActiveSheetIndex(1);
		$objPHPExcel->getActiveSheet()->setTitle("DETALLE DE LOTERIA NO VENDIDA");

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DETALLE DE BOLSAS NO VENDIDAS');
		$objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
		cellColor('A1', '1a1a1a', '11');

		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'SERIE INICIAL');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'SERIE FINAL');
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'CANTIDAD');
		cellColor('A2', '333333', '10');
		cellColor('B2', '333333', '10');
		cellColor('C2', '333333', '10');
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment; filename=\"VENDIDO Y NO VENDIDO SORTEO " . $id_sorteo . ".xlsx\"");
		header("Cache-Control: max-age=0");

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_clean();
		$objWriter->save("php://output");

	}

/////////////////////////// DONUT INFO ////////////////////////////
	////////////////////////////////////////////////////////////////////

// "##############################################################################################"

////////////////////////////////////////////////////////////////////
	/////////////////////////// DONUT INFO ////////////////////////////

	if ($_GET['info'] == 2) {

		$sorteo_anterior = $_GET['s1'];
		$sorteo_actual = $_GET['s2'];

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->setTitle("COMPARATIVO DE VENTAS");
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'COMPARATIVO DE VENTAS POR ASOCIADO (SORTEO ' . $sorteo_anterior . ' Y ' . $sorteo_actual . ')');
		$objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
		cellColor('A1', '1a1a1a', '11');

		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'ENTIDAD');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'SORTEO ' . $sorteo_anterior);
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'SORTEO ' . $sorteo_actual);
		cellColor('A2', '333333', '10');
		cellColor('B2', '333333', '10');
		cellColor('C2', '333333', '10');
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

		$total_venta = 0;

		$concatenado = $sorteo_actual;

		$c_ventas_b = mysqli_query($conn, "SELECT   a.id_sorteo, SUM(a.cantidad) as cantidad, b.nombre_empresa FROM transaccional_ventas_general as a INNER JOIN empresas as b ON a.id_entidad = b.id WHERE a.id_sorteo = '$sorteo_anterior'  AND a.estado_venta = 'APROBADO' AND a.cod_producto = 1 GROUP BY a.id_sorteo, a.id_entidad  ORDER BY a.id_sorteo, a.id_entidad ASC ");

		$i = 0;
		$v_sorteo_a = array();
		$v_sorteo_b = array();
		$v_entidades = array();

		$concatenado_sorteo_a = "";
		$concatenado_sorteo_b = "";

		foreach ($c_ventas_b as $ventas) {
			$empresa_venta = $ventas['nombre_empresa'];
			$cantidad_venta = $ventas['cantidad'];
			$v_sorteo_a[$empresa_venta] = $cantidad_venta;
			$v_sorteo_b[$empresa_venta] = 0;
			$v_entidades[$empresa_venta] = $empresa_venta;
		}

		$c_ventas_o = mysqli_query($conn, "SELECT   a.id_sorteo, SUM(a.cantidad) as cantidad, b.nombre_empresa FROM transaccional_ventas as a INNER JOIN empresas as b ON a.id_entidad = b.id WHERE a.id_sorteo = '$sorteo_anterior' AND a.estado_venta = 'APROBADO' AND a.cod_producto = 1 GROUP BY a.id_sorteo, a.id_entidad ORDER BY a.id_sorteo, a.id_entidad ASC ");

		foreach ($c_ventas_o as $ventas) {
			$empresa_venta = $ventas['nombre_empresa'];
			$cantidad_venta = $ventas['cantidad'];
			$v_sorteo_a[$empresa_venta] = $cantidad_venta;
			$v_sorteo_b[$empresa_venta] = 0;
			$v_entidades[$empresa_venta] = $empresa_venta;
		}

		$c_ventas_b = mysqli_query($conn, "SELECT   a.id_sorteo, SUM(a.cantidad) as cantidad, b.nombre_empresa FROM transaccional_ventas_general as a INNER JOIN empresas as b ON a.id_entidad = b.id WHERE a.id_sorteo = '$sorteo_actual'  AND a.estado_venta = 'APROBADO' AND a.cod_producto = 1 GROUP BY a.id_sorteo, a.id_entidad  ORDER BY a.id_sorteo, a.id_entidad ASC ");

		foreach ($c_ventas_b as $ventas) {
			$empresa_venta = $ventas['nombre_empresa'];
			$cantidad_venta = $ventas['cantidad'];
			if (!isset($v_sorteo_a[$empresa_venta])) {
				$v_sorteo_a[$empresa_venta] = 0;
			}
			$v_sorteo_b[$empresa_venta] = $cantidad_venta;
			$v_entidades[$empresa_venta] = $empresa_venta;
			$i++;
		}

		$c_ventas_o = mysqli_query($conn, "SELECT   a.id_sorteo, SUM(a.cantidad) as cantidad, b.nombre_empresa FROM transaccional_ventas as a INNER JOIN empresas as b ON a.id_entidad = b.id WHERE a.id_sorteo = '$sorteo_actual' AND a.estado_venta = 'APROBADO' AND a.cod_producto = 1 GROUP BY a.id_sorteo, a.id_entidad ORDER BY a.id_sorteo, a.id_entidad ASC ");

		foreach ($c_ventas_o as $ventas) {
			$empresa_venta = $ventas['nombre_empresa'];
			$cantidad_venta = $ventas['cantidad'];
			if (!isset($v_sorteo_a[$empresa_venta])) {
				$v_sorteo_a[$empresa_venta] = 0;
			}
			$v_sorteo_b[$empresa_venta] = $cantidad_venta;
			$v_entidades[$empresa_venta] = $empresa_venta;
		}

		$concatenado_entidades = "";
		foreach ($v_entidades as $entidad) {
			$concatenado_sorteo_a .= $v_sorteo_a[$entidad] . "%";
			$concatenado_sorteo_b .= $v_sorteo_b[$entidad] . "%";
			$concatenado_entidades .= $entidad . "%";
		}

		$v_entidades = explode("%", $concatenado_entidades);
		array_pop($v_entidades);
		$fila = 3;
		foreach ($v_entidades as $v_entidad) {

			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $fila, $v_entidad);

			if (isset($v_sorteo_a[$v_entidad])) {
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $fila, $v_sorteo_a[$v_entidad]);
			} else {
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $fila, 0);
			}

			if (isset($v_sorteo_b[$v_entidad])) {
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $fila, $v_sorteo_b[$v_entidad]);
			} else {
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $fila, 0);
			}

			$fila++;
		}

		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment; filename=\"COMPARATIVO POR ENTIDAD.xlsx\"");
		header("Cache-Control: max-age=0");

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_clean();
		$objWriter->save("php://output");

	}

/////////////////////////// DONUT INFO ////////////////////////////
	////////////////////////////////////////////////////////////////////

// "##############################################################################################"

////////////////////////////////////////////////////////////////////
	/////////////////////////// LINE INFO ////////////////////////////

	if ($_GET['info'] == 3) {

		$year_anterior = $_GET['y1'];
		$year_actual = $_GET['y2'];

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->setTitle("COMPARATIVO ANUAL DE VENTAS");
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'COMPARATIVO ANUAL DE VENTAS  (AÑO ' . $year_anterior . ' Y ' . $year_actual . ')');
		$objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
		cellColor('A1', '1a1a1a', '11');

		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'SORTEO');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'AÑO ' . $year_anterior);
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'AÑO ' . $year_actual);
		cellColor('A2', '333333', '10');
		cellColor('B2', '333333', '10');
		cellColor('C2', '333333', '10');
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

		$concatenado = $year_actual;

		$c_ventas_b = mysqli_query($conn, "SELECT  SUM(a.cantidad) as cantidad,  MONTH(b.fecha_sorteo) as mes FROM transaccional_ventas_general as a INNER JOIN sorteos_mayores as b ON a.id_sorteo = b.id WHERE YEAR(b.fecha_sorteo) = '$year_anterior'  AND a.estado_venta = 'APROBADO' AND a.cod_producto = 1 GROUP BY MONTH(b.fecha_sorteo)  ORDER BY MONTH(b.fecha_sorteo) ASC ");

		$i = 0;
		$v_sorteo_a = array();
		$v_sorteo_b = array();
		$v_meses = array();

		$concatenado_year_a = "";
		$concatenado_year_b = "";

		foreach ($c_ventas_b as $ventas) {
			$mes_venta = $ventas['mes'];
			$cantidad_venta = $ventas['cantidad'];
			$v_sorteo_a[$mes_venta] = $cantidad_venta;
			$v_sorteo_b[$mes_venta] = 0;
			$v_meses[$mes_venta] = $mes_venta;
		}

		$c_ventas_o = mysqli_query($conn, "SELECT   SUM(a.cantidad) as cantidad,  MONTH(b.fecha_sorteo) as mes FROM transaccional_ventas as a INNER JOIN sorteos_mayores as b ON a.id_sorteo = b.id WHERE YEAR(b.fecha_sorteo) = '$year_anterior'  AND a.estado_venta = 'APROBADO' AND a.cod_producto = 1 GROUP BY MONTH(b.fecha_sorteo)  ORDER BY MONTH(b.fecha_sorteo) ASC ");

		foreach ($c_ventas_o as $ventas) {
			$mes_venta = $ventas['mes'];
			$cantidad_venta = $ventas['cantidad'];

			if (!isset($v_sorteo_a[$mes_venta])) {
				$v_sorteo_a[$mes_venta] = $cantidad_venta;
			} else {
				$v_sorteo_a[$mes_venta] += $cantidad_venta;
			}

			if (!isset($v_sorteo_b[$mes_venta])) {
				$v_sorteo_b[$mes_venta] = 0;
			}

			$v_meses[$mes_venta] = $mes_venta;
		}

		$c_ventas_b = mysqli_query($conn, "SELECT SUM(a.cantidad) as cantidad,  MONTH(b.fecha_sorteo) as mes FROM transaccional_ventas_general as a INNER JOIN sorteos_mayores as b ON a.id_sorteo = b.id WHERE YEAR(b.fecha_sorteo) = '$year_actual'  AND a.estado_venta = 'APROBADO' AND a.cod_producto = 1 GROUP BY MONTH(b.fecha_sorteo)  ORDER BY MONTH(b.fecha_sorteo) ASC ");

		foreach ($c_ventas_b as $ventas) {
			$mes_venta = $ventas['mes'];
			$cantidad_venta = $ventas['cantidad'];

			if (!isset($v_sorteo_b[$mes_venta])) {
				$v_sorteo_b[$mes_venta] = $cantidad_venta;
			} else {
				$v_sorteo_b[$mes_venta] += $cantidad_venta;
			}

			if (!isset($v_sorteo_a[$mes_venta])) {
				$v_sorteo_a[$mes_venta] = 0;
			}

			$v_meses[$mes_venta] = $mes_venta;
			$i++;
		}

		$c_ventas_b = mysqli_query($conn, "SELECT SUM(a.cantidad) as cantidad,  MONTH(b.fecha_sorteo) as mes FROM transaccional_ventas as a INNER JOIN sorteos_mayores as b ON a.id_sorteo = b.id WHERE YEAR(b.fecha_sorteo) = '$year_actual'  AND a.estado_venta = 'APROBADO' AND a.cod_producto = 1 GROUP BY MONTH(b.fecha_sorteo)  ORDER BY MONTH(b.fecha_sorteo) ASC ");

		foreach ($c_ventas_b as $ventas) {
			$mes_venta = $ventas['mes'];
			$cantidad_venta = $ventas['cantidad'];
			if (!isset($v_sorteo_a[$mes_venta])) {
				$v_sorteo_a[$mes_venta] = 0;
			}

			if (!isset($v_sorteo_b[$mes_venta])) {
				$v_sorteo_b[$mes_venta] = $cantidad_venta;
			} else {
				$v_sorteo_b[$mes_venta] += $cantidad_venta;
			}

			$v_meses[$mes_venta] = $mes_venta;
		}

		$concatenado_meses = "";
		foreach ($v_meses as $mes) {
			$concatenado_year_a .= $v_sorteo_a[$mes] . "%";
			$concatenado_year_b .= $v_sorteo_b[$mes] . "%";
			$concatenado_meses .= $mes . "%";
		}

		$descripcion_titulo = '<b>COMPARATIVO DE VENTAS POR AÑO (' . $year_anterior . ' Y ' . $year_actual . ') </b>';

		$v_meses = explode("%", $concatenado_meses);
		array_pop($v_meses);
		$fila = 3;
		foreach ($v_meses as $v_mes) {

			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $fila, $v_mes);

			if (isset($v_sorteo_a[$v_mes])) {
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $fila, number_format($v_sorteo_a[$v_mes]));
			} else {
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $fila, 0);
			}

			if (isset($v_sorteo_b[$v_mes])) {
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $fila, number_format($v_sorteo_b[$v_mes]));
			} else {
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $fila, 0);
			}

			$fila++;
		}

		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment; filename=\"COMPARATIVO POR AÑO.xlsx\"");
		header("Cache-Control: max-age=0");

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_clean();
		$objWriter->save("php://output");

	}

/////////////////////////// LINE INFO ////////////////////////////
	////////////////////////////////////////////////////////////////////
}

?>