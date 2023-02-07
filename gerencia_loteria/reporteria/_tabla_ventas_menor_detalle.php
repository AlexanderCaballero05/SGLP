<?php
require('./template/header.php');
require('./_tabla_ventas_menor_detalle_excel.php');

//REPORTE VENTAS


$parametros = explode("/", $_GET['dat']);
$id_sorteo = $parametros[0];
$id_seccional = $parametros[1];
$fecha_inicial = $parametros[2];
$fecha_final = $parametros[3];
$tipo_consulta = $parametros[4];

$p_excel = $id_seccional."-".$id_sorteo."-".$tipo_consulta;

$info_seccional = mysql_query("SELECT nombre FROM fvp_seccionales WHERE id = '$id_seccional' ");
if ($info_seccional === false) {
echo mysql_error();
}

$ob_seccional = mysql_fetch_object($info_seccional);
$nombre_seccional = $ob_seccional->nombre;

$info_sorteo = mysql_query("SELECT *  FROM sorteos_menores WHERE id = '$id_sorteo' limit 1");
$value = mysql_fetch_object($info_sorteo);
$sorteo = $value->no_sorteo_men;
$fecha_sorteo = $value->fecha_sorteo;

echo "<form method = 'POST'>";

echo "<div class = 'alert alert-info'>";
echo "<h3  align = 'center'>Sorteo # ".$sorteo."</h3>";
echo "<p align = 'center'> Seccional ".$nombre_seccional."</p>";
if ($tipo_consulta == 'consolidado') {
echo "<p align = 'center'>Contado y Credito</p>";
}elseif ($tipo_consulta == 'contado') {
echo "<p align = 'center'>Contado</p>";    
}elseif ($tipo_consulta == 'credito') {
echo "<p align = 'center'>Credito</p>";
}
echo "</div>";

echo "<div class = 'well'>";

echo "<table id = 'table_id1' class = 'table table-bordered'>";
echo "<thead>";
echo "<tr>";
echo "<th>Numero</th>";
echo "<th>Cant. Pedido</th>";
echo "<th>Series en Pedido</th>";
echo "<th>Cant. Vendida</th>";
echo "<th>Series Vendidas</th>";
echo "<th>Cant. Disp.</th>";
echo "<th>Series Disp.</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

$b = 0;
$v = 0;
$n = 0;
$num = 0;
$total_vendido = 0;
$total_no_vendido = 0;
$total_pedido = 0;



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
$venta_por_serie = mysql_query("SELECT DISTINCT(a.serie) FROM `fvp_detalles_ventas_menor` as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE b.forma_pago != '1' AND b.cod_producto != '2' AND a.id_sorteo = '$id_sorteo' AND b.id_seccional = '$id_seccional' AND b.estado_venta = 'APROBADO' AND a.numero = '$numero' AND a.serie BETWEEN '$s_i' AND '$s_f'  ORDER BY serie ASC");

}


$v = 0;
while ($registro = mysql_fetch_array($venta_por_serie)) {
$v_series[$v] = $registro['serie'];

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

echo "<td>".$numero."</td>";
echo "<td>".$cantidad_pedido."</td>";
echo "<td>".$s_i." - ".$s_f."</td>";
echo "<td>".$cantidad_vendida."</td>";
if ($cantidad_vendida != 0 ) {

$s = 0;
echo "<td>";
while (isset($v_serie_i[$s])) {
echo $v_serie_i[$s]." - ".$v_serie_f[$s]."<br>";
$s++;
}
echo "</td>";
}else{
echo "<td></td>"; 
}

echo "<td>".$cantidad_disponible."</td>";

$n = 0;
if ($cantidad_vendida != 0) {
echo "<td>";
while (isset($v_serie_n_f[$n])) {
echo $v_serie_n_i[$n]." - ".$v_serie_n_f[$n]."<br>";
$n++;
}
echo "</td>";
}else{
echo "<td>".$s_i." - ".$s_f."</td>"; 
}

echo "</tr>";

unset($v_series);
unset($v_serie_i);
unset($v_serie_f);

unset($v_serie_n_i);
unset($v_serie_n_f);
}
echo "</tbody>";

echo "<tr>";
echo "<td>";
echo "<b>TOTAL</b>";
echo "</td>";
echo "<td><b>";
echo $total_pedido;
echo "</b></td>";
echo "<td><b>";
echo "</td>";
echo "<td><b>";
echo $total_vendido;
echo "</b></td>";
echo "<td>";
echo "</td>";
echo "<td><b>";
echo $total_no_vendido;
echo "</b></td>";
echo "<td>";
echo "</td>";
echo "</tr>";

echo "</table>";

echo "<br>";

echo "<p align = 'center'>";
echo "<button type = 'submit' class ='btn btn-success' name = 'generar' value = '".$p_excel."'>Generar Excel</button>";

if ($tipo_consulta == 'consolidado') {
echo " <a class ='btn btn-primary'  target = '_blanck' href = './_tabla_ventas_menor_detalle_print.php?v1=".$p_excel."' >Acta De Trituracion</a>";
}else{
echo " <a class ='btn btn-primary'  disabled>Acta De Trituracion</a>";
}


echo "</p>";
echo "</div>";


echo "</div>";
echo "</div>";

echo "</form>";
?>