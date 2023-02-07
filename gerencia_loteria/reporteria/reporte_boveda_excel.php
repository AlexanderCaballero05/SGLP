<?php 

//REPORTE VENTAS


if (isset($_POST['generar_excel'])) 
{
		$id_sorteo = $_POST['generar_excel'];
	
		require_once './phpexcel/Classes/PHPExcel/IOFactory.php';
		
		$objPHPExcel = new PHPExcel(); 
		$objPHPExcel->setActiveSheetIndex(0); 

		$objPHPExcel->getActiveSheet()->SetCellValue('B1','REPORTE DE LOTERIA SIN DISTRIBUCION ');


						$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Numero');
						$objPHPExcel->getActiveSheet()->SetCellValue('B3', 'Serie Inicial');
						$objPHPExcel->getActiveSheet()->SetCellValue('C3', 'Serie Final');
						$objPHPExcel->getActiveSheet()->SetCellValue('D3', 'Cantidad');
					
$i = 0;
$k = 0;
$w = 0;
$f = 4;
$contador = 0;
$total_bobeda = 0;

$numeros_disponibles = mysql_query(" SELECT * FROM fvp_menor_reservas_numeros WHERE sorteos_menores_id = '$id_sorteo' ORDER BY numero ASC , serie_inicial ASC  ");

while ($row_disponible  = mysql_fetch_array($numeros_disponibles)) {

$v_numero[$i] = $row_disponible['numero'];
$v_cantidad[$i] = 0;

$s_i = $row_disponible['serie_inicial'];
$s_f = $row_disponible['serie_final'];
$cantidad_extra = $s_f - $s_i + 1;

$validar_distribuciones = mysql_query(" SELECT count(*) as conteo FROM fvp_menor_reservas_seccionales_numeros WHERE sorteos_menores_id = '$id_sorteo' AND numero = '$v_numero[$i]' AND serie_final  >= $s_i AND serie_final <= $s_f  ");

$ob_distribuciones = mysql_fetch_object($validar_distribuciones);
$conteo = $ob_distribuciones->conteo;

if ($conteo > 0) {
$max_serie_distribuida = mysql_query("SELECT  MAX(serie_final) as serie_maxima FROM fvp_menor_reservas_seccionales_numeros WHERE sorteos_menores_id = '$id_sorteo' AND numero = '$v_numero[$i]' AND serie_final  >= $s_i AND serie_final <= $s_f  ");

if ($max_serie_distribuida === false) {
echo mysql_error();
}

$ob_maximo = mysql_fetch_object($max_serie_distribuida);
$ultima_serie_dist = $ob_maximo->serie_maxima;
$serie_inicial_disp = $ultima_serie_dist + 1; 

}else{
$serie_inicial_disp = $s_i;     
}


$serie_final_disp = $s_f;

$cantidad = $serie_final_disp - $serie_inicial_disp + 1;
if ($cantidad > 0 ) {
$objPHPExcel->getActiveSheet()->SetCellValue('A'.$f, $v_numero[$i]);
$objPHPExcel->getActiveSheet()->SetCellValue('B'.$f, $serie_inicial_disp);
$objPHPExcel->getActiveSheet()->SetCellValue('C'.$f, $serie_final_disp);
$objPHPExcel->getActiveSheet()->SetCellValue('D'.$f, $cantidad);
$total_bobeda = $total_bobeda +  $cantidad;
$f++;
}

$k++;
$w++;
$i++;
}

$objPHPExcel->getActiveSheet()->mergeCells('A'.$f.':C'.$f);
$objPHPExcel->getActiveSheet()->SetCellValue('A'.$f, 'TOTAL');
$objPHPExcel->getActiveSheet()->SetCellValue('D'.$f, $total_bobeda);


		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment; filename=\"REPORTE LOTERIA EN BOVEDA SORTEO ".$id_sorteo.".xlsx\"");
		header("Cache-Control: max-age=0");

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_clean();
		$objWriter->save("php://output");

}
?>