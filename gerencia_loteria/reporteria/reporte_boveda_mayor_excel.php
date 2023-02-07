<?php
//REPORTE VENTAS

if (isset($_POST['generar_excel'])) {

$id_sorteo = $_POST['generar_excel'];
$id_empresa = $_SESSION['id_empresa'];


$sorteos = mysql_query("SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo'  ORDER BY no_sorteo_may DESC ");

$info_sorteo = mysql_query("SELECT *  FROM sorteos_mayores WHERE id = '$id_sorteo' limit 1");
$value = mysql_fetch_object($info_sorteo);
$sorteo = $value->no_sorteo_may;
$fecha_sorteo = $value->fecha_sorteo;
$precio_unitario = $value->precio_unitario;
$mezcla = $value->mezcla;

require_once './phpexcel/Classes/PHPExcel/IOFactory.php';
		
$objPHPExcel = new PHPExcel(); 
$objPHPExcel->setActiveSheetIndex(0); 


$i = 0;
$k = 0;
$contador = 0;
$w = 0;
$total_disponible = 0;

$numeros_disponibles = mysql_query(" SELECT a.rango FROM sorteos_mezclas_rangos as a INNER JOIN sorteos_mezclas as b ON a.num_mezcla = b.num_mezcla WHERE a.id_sorteo = '$id_sorteo' AND b.id_sorteo = '$id_sorteo' AND b.id_empresa = '$id_empresa' AND a.id_seccional IS NULL");


$objPHPExcel->getActiveSheet()->SetCellValue('A1','REPORTE DE LOTERIA SIN DISTRIBUCION ');
$objPHPExcel->getActiveSheet()->SetCellValue('A2','Sorteo');
$objPHPExcel->getActiveSheet()->SetCellValue('B2',$id_sorteo);

$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Billete Inicial');
$objPHPExcel->getActiveSheet()->SetCellValue('B3', 'Billete Final');
$objPHPExcel->getActiveSheet()->SetCellValue('C3', 'Cantidad');


$f = 4;

while ($reg_boveda = mysql_fetch_array($numeros_disponibles)) {
$b_i = $reg_boveda['rango'];
$b_f = $b_i + $mezcla - 1;
$cantidad = $b_f - $b_i + 1;
$total_disponible = $total_disponible + $cantidad;

$objPHPExcel->getActiveSheet()->SetCellValue('A'.$f, $b_i);
$objPHPExcel->getActiveSheet()->SetCellValue('B'.$f, $b_f);
$objPHPExcel->getActiveSheet()->SetCellValue('C'.$f, $cantidad);

$f++;
}

$objPHPExcel->getActiveSheet()->mergeCells('A'.$f.':B'.$f);
$objPHPExcel->getActiveSheet()->SetCellValue('A'.$f, 'TOTAL');
$objPHPExcel->getActiveSheet()->SetCellValue('C'.$f, $total_disponible);


header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"REPORTE LOTERIA EN BOVEDA SORTEO ".$id_sorteo.".xlsx\"");
header("Cache-Control: max-age=0");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_clean();
$objWriter->save("php://output");

}


?>