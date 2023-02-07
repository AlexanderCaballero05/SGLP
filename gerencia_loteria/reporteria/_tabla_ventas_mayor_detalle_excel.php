<?php 

if (isset($_POST['excel_pani_c'])) {

require_once './phpexcel/Classes/PHPExcel/IOFactory.php';

$parametros = explode("/", $_POST['excel_pani_c']);
$id_sorteo = $parametros[0];
$id_seccional = $parametros[1];


$info_seccional = mysql_query("SELECT * FROM fvp_seccionales WHERE id = '$id_seccional' ");
$ob_seccional = mysql_fetch_object($info_seccional);
$nombre_seccional = $ob_seccional ->nombre;
$nombre_seccional = substr($nombre_seccional, 0,20);


$info_sorteo = mysql_query("SELECT *  FROM sorteos_mayores WHERE id = '$id_sorteo' limit 1");
$value = mysql_fetch_object($info_sorteo);
$sorteo = $value->no_sorteo_may;
$fecha_sorteo = $value->fecha_sorteo;
$mezcla = $value->mezcla;


$objPHPExcel = new PHPExcel(); 
$objPHPExcel->setActiveSheetIndex(0); 
$objPHPExcel->getActiveSheet()->setTitle("VENTA ".$nombre_seccional);



$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'REPORTE DE LOTERIA MAYOR VENDIDA');
$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'NUMERO DE SORTEO');
$objPHPExcel->getActiveSheet()->SetCellValue('B2', $sorteo );
$objPHPExcel->getActiveSheet()->SetCellValue('C2', $fecha_sorteo );
$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Vendedor');
$objPHPExcel->getActiveSheet()->SetCellValue('B3', $nombre_seccional );


$objPHPExcel->getActiveSheet()->SetCellValue('A5', 'Billete Inicial');
$objPHPExcel->getActiveSheet()->SetCellValue('B5', 'Billete Final');
$objPHPExcel->getActiveSheet()->SetCellValue('C5', 'Cantidad Vendida');




$b = 0;
$v = 0;
$n = 0;
$num = 0;
$total_vendido = 0;
$total_no_vendido = 0;
$total_pedido = 0;
$fila = 6;
$total_t = 0;

$consulta_total_pedido = mysql_query("SELECT rango FROM sorteos_mezclas_rangos as a  WHERE a.id_sorteo = '$id_sorteo' AND a.id_seccional = '$id_seccional' ORDER BY rango ASC ");


$n = 0;

while ($reg_total_pedido = mysql_fetch_array($consulta_total_pedido)) {

$cantidad_vendida = 0;

$b_i = $reg_total_pedido['rango'];
$b_f = $reg_total_pedido['rango'] +  $mezcla - 1;
$cantidad_pedido = $b_f - $b_i + 1; 
$total_pedido = $total_pedido + $cantidad_pedido;


$venta_por_serie = mysql_query("SELECT DISTINCT(a.billete) ,c.nombre FROM fvp_detalles_ventas_mayor as a INNER JOIN fvp_seccionales as c INNER JOIN transaccional_ventas as f  ON a.cod_factura = f.cod_factura AND f.id_seccional = c.id  WHERE  a.estado_venta = 'APROBADO' AND a.id_sorteo = '$id_sorteo'  AND a.billete BETWEEN '$b_i' AND '$b_f'  ORDER BY billete ASC");


$v = 0;
while ($registro = mysql_fetch_array($venta_por_serie)) {
$v_series[$v] = $registro['billete'];
$v++;
};


$v = 0;
$s = 0;
if (isset($v_series[$v])) {
$v_serie_i[$s] = $v_series[$v];


if ($b_i < $v_serie_i[$s]) {
$v_serie_n_i[$n] = $b_i;
$v_serie_n_f[$n] = $v_serie_i[$s] - 1;
$n++;
}

while (isset($v_series[$v])) {
    
    if (isset($v_series[$v + 1])) {

        if ($v_series[$v] + 1 == $v_series[$v + 1]) {

        $v_serie_f[$s] = $v_series[$v + 1];
        
        }else{

        $v_serie_f[$s] = $v_series[$v];
        $v_serie_n_i[$n] = $v_series[$v] + 1;

        $s++;

        $v_serie_i[$s] = $v_series[$v + 1];  
        $v_serie_n_f[$n] = $v_series[$v + 1] - 1;
        $n++;

        }

    }else{

    $v_serie_f[$s] = $v_series[$v];  
    $v_serie_n_i[$n] = $v_series[$v] + 1;

        if (isset($v_serie_f[$s])) {
            if ($b_f > $v_serie_f[$s]) {

            $v_serie_n_i[$n] = $v_serie_f[$s] + 1;
            $v_serie_n_f[$n] = $b_f;

            $n++;
            }
        }

    }

$v++;
}



$cantidad_vendida = count($v_series);  

}else{
$cantidad_vendida = 0;  
$v_serie_n_i[$n] = $b_i;
$v_serie_n_f[$n] = $b_f;


$n++;
}



$total_vendido = $total_vendido + $cantidad_vendida;


if (isset($cantidad_vendida)) {
$cantidad_disponible = $cantidad_pedido - $cantidad_vendida;
$total_no_vendido = $total_no_vendido + $cantidad_disponible;
}else{
  $cantidad_vendida = 0;
$cantidad_disponible = $cantidad_pedido;  
$total_no_vendido = $total_no_vendido + $cantidad_disponible;
}

if ($cantidad_vendida != 0 ) {

$s = 0;
while (isset($v_serie_i[$s]) AND isset($fila,$v_serie_f[$s])) {
$cantidad_entre_series = $v_serie_f[$s] - $v_serie_i[$s] + 1;

        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$fila,$v_serie_i[$s] );
        $objPHPExcel->getActiveSheet()->SetCellValue('B'.$fila,$v_serie_f[$s] );
        $objPHPExcel->getActiveSheet()->SetCellValue('C'.$fila, $cantidad_entre_series);
$total_t = $total_t + $cantidad_entre_series;
$fila++;
$s++;
}


}


unset($v_series);
unset($v_serie_i);
unset($v_serie_f);

}


//*********************************************************************
// NEW SHEET 
//*********************************************************************


 $objWorkSheet = $objPHPExcel->createSheet(1);
        $objPHPExcel->setActiveSheetIndex(1); 
        $objPHPExcel->getActiveSheet()->setTitle("DEV. ".$nombre_seccional);

        $objPHPExcel->getActiveSheet()->SetCellValue('A1','REPORTE DE LOTERIA MAYOR NO VENDIDA ');

$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'NUMERO DE SORTEO' );
$objPHPExcel->getActiveSheet()->SetCellValue('B2', $sorteo );
$objPHPExcel->getActiveSheet()->SetCellValue('C2', $fecha_sorteo );
$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Vendedor');
$objPHPExcel->getActiveSheet()->SetCellValue('B3', $nombre_seccional );


$objPHPExcel->getActiveSheet()->SetCellValue('A5', 'Billete Inicial');
$objPHPExcel->getActiveSheet()->SetCellValue('B5', 'Billete Final');
$objPHPExcel->getActiveSheet()->SetCellValue('C5', 'Cantidad Vendida');


$fila  =  6;


$n = 0;
while (isset($v_serie_n_i[$n]) AND isset($v_serie_n_f[$n])) {
$cantidad_entre_series = $v_serie_n_f[$n] - $v_serie_n_i[$n] + 1;

        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$fila,$v_serie_n_i[$n] );
        $objPHPExcel->getActiveSheet()->SetCellValue('B'.$fila,$v_serie_n_f[$n] );
        $objPHPExcel->getActiveSheet()->SetCellValue('C'.$fila, $cantidad_entre_series);

$fila++;
$n++;
}


header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"Reporte de venta y debolucion.xlsx\"");
header("Cache-Control: max-age=0");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_clean();
$objWriter->save("php://output");

}
?>