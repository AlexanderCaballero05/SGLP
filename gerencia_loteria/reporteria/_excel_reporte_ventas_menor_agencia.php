<?php
//REPORTE VENTAS

if (isset($_POST['generar'])) {

require_once './phpexcel/Classes/PHPExcel/IOFactory.php';

$par = explode("-", $_POST['generar']);

$id_sorteo = $par[0];

if (!isset($_SESSION['id_empresa'])) {
$id_empresa = 5;
}else{
$id_empresa = $_SESSION['id_empresa'];    
}

$info_sorteo = mysql_query("SELECT * FROM sorteos_menores WHERE id = '$id_sorteo'  ");
$ob_sorteo = mysql_fetch_object($info_sorteo);
$no_sorteo = $ob_sorteo->no_sorteo_men;
$fecha_sorteo = $ob_sorteo->fecha_sorteo;


$seccionales_asignadas = mysql_query("SELECT id_seccional  FROM fvp_menor_reservas_seccionales_numeros WHERE sorteos_menores_id = '$id_sorteo' GROUP BY id_seccional ");

$objPHPExcel = new PHPExcel(); 

$h = 0;
while ($seccionales = mysql_fetch_array($seccionales_asignadas)) {
$id_seccional = $seccionales['id_seccional'];


$info_seccional = mysql_query("SELECT nombre FROM fvp_seccionales WHERE id = '$id_seccional' ");
if ($info_seccional === false) {
echo mysql_error();
}

$ob_seccional = mysql_fetch_object($info_seccional);
$nombre_seccional = $ob_seccional->nombre;
$nombre_seccional = substr($nombre_seccional, 0,20);


		$objWorkSheet = $objPHPExcel->createSheet($h);
        $objPHPExcel->setActiveSheetIndex($h); 
 		$objPHPExcel->getActiveSheet()->setTitle("VENTA ".$nombre_seccional);

        $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
        $objPHPExcel->getActiveSheet()->SetCellValue('A1','REPORTE DE VENTA DE LOTERIA MENOR ');
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


$venta_por_serie = mysql_query("SELECT DISTINCT(a.serie) FROM `fvp_detalles_ventas_menor` as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE a.id_sorteo = '$id_sorteo' AND b.id_seccional = '$id_seccional' AND b.estado_venta = 'APROBADO' AND a.numero = '$numero' AND a.serie BETWEEN '$s_i' AND '$s_f'  ORDER BY serie ASC");

//$venta_por_serie = mysql_query("SELECT DISTINCT(serie) FROM fvp_detalles_ventas_menor WHERE  estado_venta = 'APROBADO' AND id_sorteo = '$id_sorteo' AND numero = '$numero' AND serie BETWEEN '$s_i' AND '$s_f' AND estado_venta = 'APROBADO' ORDER BY serie ASC");


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

$h++;

 $objWorkSheet = $objPHPExcel->createSheet($h);
        $objPHPExcel->setActiveSheetIndex($h); 
 		$objPHPExcel->getActiveSheet()->setTitle("DEV. ".$nombre_seccional);

        $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
        $objPHPExcel->getActiveSheet()->SetCellValue('A1','REPORTE DE LOTERIA MENOR NO VENDIDA ');

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


unset($v_serie_i);
unset($v_serie_f);
unset($v_s_i_no);
unset($v_s_f_no);
unset($v_numero_no);

$h++;
}




// HOJA DE CALCULO DE LOTERIA EN BOBEDA/ NO VENDIDA Y NO DISTRIBUIDA

		$objWorkSheet = $objPHPExcel->createSheet($h);
		$objPHPExcel->setActiveSheetIndex($h); 
 		$objPHPExcel->getActiveSheet()->setTitle("EN BOVEDA ");
		$objPHPExcel->getActiveSheet()->SetCellValue('A1','REPORTE DE LOTERIA SIN DISTRIBUCION ');


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


if ($id_sorteo >= 3144) {

$numeros_disponibles = mysql_query("SELECT * FROM menor_seccionales_numeros WHERE id_sorteo = '$id_sorteo' AND id_empresa = '$id_empresa' ORDER BY numero ASC, serie_inicial ASC ");

}else{

$numeros_disponibles = mysql_query(" SELECT * FROM fvp_menor_reservas_numeros WHERE sorteos_menores_id = '$id_sorteo' ORDER BY numero ASC , serie_inicial ASC  ");

}

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
        header("Content-Disposition: attachment; filename=\"Reporte de venta menor sorteo ".$no_sorteo.".xlsx\"");
        header("Cache-Control: max-age=0");

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_clean();
        $objWriter->save("php://output");

}

?>