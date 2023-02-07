<?php
//REPORTE VENTAS

require_once './phpexcel/Classes/PHPExcel/IOFactory.php';

if (isset($_POST['generar'])) {

$par = explode("-", $_POST['generar']);

$id_seccional = $par[0];
$id_sorteo = $par[1];
$tipo_consulta = $par[2];

$info_seccional = mysql_query("SELECT nombre FROM fvp_seccionales WHERE id = '$id_seccional' ");
if ($info_seccional === false) {
echo mysql_error();
}

$ob_seccional = mysql_fetch_object($info_seccional);
$nombre_seccional = $ob_seccional->nombre;

$info_sorteo = mysql_query("SELECT * FROM sorteos_menores WHERE id = '$id_sorteo'  ");
$ob_sorteo = mysql_fetch_object($info_sorteo);
$no_sorteo = $ob_sorteo->no_sorteo_men;
$fecha_sorteo = $ob_sorteo->fecha_sorteo;
$nombre_seccional = substr($nombre_seccional, 0,20);


        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0); 
        $objPHPExcel->getActiveSheet()->setTitle("VENTA ".$nombre_seccional);

        $objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
if ($tipo_consulta == 'consolidado') {
        $objPHPExcel->getActiveSheet()->SetCellValue('A1','REPORTE DE LOTERIA MENOR VENDIDA - CONSOLIDADO');
}elseif ($tipo_consulta == 'contado') {
        $objPHPExcel->getActiveSheet()->SetCellValue('A1','REPORTE DE LOTERIA MENOR VENDIDA - CONTADO ');
}elseif ($tipo_consulta == 'credito') {
        $objPHPExcel->getActiveSheet()->SetCellValue('A1','REPORTE DE LOTERIA MENOR VENDIDA - CREDITO ');
}

        $objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
        $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Punto de Venta '.$nombre_seccional);

        $objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Numero');
        $objPHPExcel->getActiveSheet()->SetCellValue('B3', 'Serie Inicial');
        $objPHPExcel->getActiveSheet()->SetCellValue('C3', 'Serie Final');
        $objPHPExcel->getActiveSheet()->SetCellValue('D3', 'Cantidad');









$b = 0;
$v = 0;
$n = 0;
$num = 0;
$total_vendido = 0;
$total_no_vendido = 0;
$total_pedido = 0;
$fila_excel = 4;
$v_n = 0;


$consulta_total_pedido = mysql_query("SELECT * FROM fvp_menor_reservas_seccionales_numeros WHERE  id_seccional = '$id_seccional' AND sorteos_menores_id = '$id_sorteo'  ORDER BY numero ASC");

while ($reg_total_pedido = mysql_fetch_array($consulta_total_pedido)) {

$cantidad_vendida = 0;

$numero = $reg_total_pedido['numero'];
$s_i = $reg_total_pedido['serie_inicial'];
$s_f = $reg_total_pedido['serie_final'];
$cantidad_pedido = $s_f - $s_i + 1; 

$total_pedido = $total_pedido + $cantidad_pedido;
echo "<tr>";


if ($tipo_consulta == 'consolidado') {
$venta_por_serie = mysql_query("SELECT DISTINCT(a.serie) FROM `fvp_detalles_ventas_menor` as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE a.id_sorteo = '$id_sorteo' AND b.id_seccional = '$id_seccional' AND b.estado_venta = 'APROBADO' AND a.numero = '$numero' AND a.serie BETWEEN '$s_i' AND '$s_f'  ORDER BY serie ASC");

}elseif ($tipo_consulta == 'contado') {
$venta_por_serie = mysql_query("SELECT DISTINCT(a.serie) FROM `fvp_detalles_ventas_menor` as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE b.forma_pago = '1' AND a.id_sorteo = '$id_sorteo' AND b.id_seccional = '$id_seccional' AND b.estado_venta = 'APROBADO' AND a.numero = '$numero' AND a.serie BETWEEN '$s_i' AND '$s_f'  ORDER BY serie ASC");

}elseif ($tipo_consulta == 'credito') {
$venta_por_serie = mysql_query("SELECT DISTINCT(a.serie) FROM `fvp_detalles_ventas_menor` as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE b.forma_pago != '1' AND b.cod_producto = '2' AND a.id_sorteo = '$id_sorteo' AND b.id_seccional = '$id_seccional' AND b.estado_venta = 'APROBADO' AND a.numero = '$numero' AND a.serie BETWEEN '$s_i' AND '$s_f'  ORDER BY serie ASC");

}


$v = 0;
while ($registro = mysql_fetch_array($venta_por_serie)) {
$v_series[$v] = $registro['serie'];

//echo $v_series[$v]." ";
//echo $registro['cod_factura']."<br>";


$v++;
};

$v = 0;
$s = 0;
$n = 0;
if (isset($v_series[$v])) {
$v_serie_i[$s] = $v_series[$v];


if ($s_i < $v_serie_i[$s]) {
$v_serie_n_i[$n] = $s_i;
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
//    $v_serie_n_i[$n] = $v_series[$v] + 1;

        if (isset($v_serie_f[$s])) {
            if ($s_f > $v_serie_f[$s]) {
            $v_serie_n_i[$n] = $v_serie_f[$s] + 1;
            $v_serie_n_f[$n] = $s_f;
            $n++;
            }
        }

    }

$v++;
}



$cantidad_vendida = count($v_series);  

}else{
$cantidad_vendida = 0;  
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
while (isset($v_serie_i[$s])) {

$cantidad_vendida_excel = $v_serie_f[$s] - $v_serie_i[$s] + 1;

$objPHPExcel->getActiveSheet()->SetCellValue('A'.$fila_excel,$numero);
$objPHPExcel->getActiveSheet()->SetCellValue('B'.$fila_excel,$v_serie_i[$s]);
$objPHPExcel->getActiveSheet()->SetCellValue('C'.$fila_excel,$v_serie_f[$s]);
$objPHPExcel->getActiveSheet()->SetCellValue('D'.$fila_excel,$cantidad_vendida_excel);

$fila_excel++;
$s++;
}
}


$n = 0;
if ($cantidad_vendida != 0) {

while (isset($v_serie_n_f[$n])) {
$v_numero_no[$v_n] = $numero;    
$v_s_i_no[$v_n] = $v_serie_n_i[$n];
$v_s_f_no[$v_n] = $v_serie_n_f[$n];
$n++;
$v_n++;
}

}else{

$v_numero_no[$v_n] = $numero;    
$v_s_i_no[$v_n] = $s_i;
$v_s_f_no[$v_n] = $s_f;
$v_n++;
}

unset($v_series);
unset($v_serie_i);
unset($v_serie_f);

unset($v_serie_n_i);
unset($v_serie_n_f);
}






//*********************************************************************
// NEW SHEET 
//*********************************************************************


 $objWorkSheet = $objPHPExcel->createSheet(1);
        $objPHPExcel->setActiveSheetIndex(1); 
        $objPHPExcel->getActiveSheet()->setTitle("DEV. ".$nombre_seccional);

        $objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
if ($tipo_consulta == 'consolidado') {
        $objPHPExcel->getActiveSheet()->SetCellValue('A1','REPORTE DE LOTERIA MENOR NO VENDIDA - CONSOLIDADO');
}elseif ($tipo_consulta == 'contado') {
        $objPHPExcel->getActiveSheet()->SetCellValue('A1','REPORTE DE LOTERIA MENOR NO VENDIDA - CONTADO ');
}elseif ($tipo_consulta == 'credito') {
        $objPHPExcel->getActiveSheet()->SetCellValue('A1','REPORTE DE LOTERIA MENOR NO VENDIDA - CREDITO ');
}


        $objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
        $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Punto de Venta '.$nombre_seccional);


        $objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Numero');
        $objPHPExcel->getActiveSheet()->SetCellValue('B3', 'Serie Inicial');
        $objPHPExcel->getActiveSheet()->SetCellValue('C3', 'Serie Final');
        $objPHPExcel->getActiveSheet()->SetCellValue('D3', 'Cantidad No Vendida');



$i = 0;
$fila_excel = 4;
while (isset($v_numero_no[$i])) {

$cantidad_no_vendida_excel = $v_s_f_no[$i] - $v_s_i_no[$i] + 1;

$objPHPExcel->getActiveSheet()->SetCellValue('A'.$fila_excel,$v_numero_no[$i]);
$objPHPExcel->getActiveSheet()->SetCellValue('B'.$fila_excel,$v_s_i_no[$i]);
$objPHPExcel->getActiveSheet()->SetCellValue('C'.$fila_excel,$v_s_f_no[$i]);
$objPHPExcel->getActiveSheet()->SetCellValue('D'.$fila_excel,$cantidad_no_vendida_excel);

$fila_excel++;
$i++;
}



        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=\"Reporte de venta menor sorteo ".$no_sorteo.".xlsx\"");
        header("Cache-Control: max-age=0");

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_clean();
        $objWriter->save("php://output");

}

?>