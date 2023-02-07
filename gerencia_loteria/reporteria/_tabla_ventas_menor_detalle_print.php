<?php
require("./template/header.php");

date_default_timezone_set("America/Tegucigalpa");

$current_date = date("Y-m-d h:i:s a");

//REPORTE VENTAS


$parametros = $_GET['v1'];
$par = explode("-", $parametros);

$id_seccional = $par[0];
$id_sorteo = $par[1];

$info_seccional = mysql_query("SELECT nombre, id_empresa FROM fvp_seccionales WHERE id = '$id_seccional' ");
if ($info_seccional === false) {
echo mysql_error();
}

$ob_seccional = mysql_fetch_object($info_seccional);
$nombre_seccional = $ob_seccional->nombre;
$id_empresa = $ob_seccional->id_empresa;

$consulta_empresa   = mysql_query("SELECT nombre_empresa FROM empresas WHERE id = '$id_empresa' ");
$ob_empresa         = mysql_fetch_object($consulta_empresa);
$nombre_empresa     = $ob_empresa->nombre_empresa;

$consulta_estado_sorteo = mysql_query("SELECT estado_venta FROM empresas_estado_venta WHERE id_sorteo = '$id_sorteo' AND cod_producto = 2 AND id_empresa = '$id_empresa' ");
$ob_estado_sorteo       = mysql_fetch_object($consulta_estado_sorteo);
$estado_venta           = $ob_estado_sorteo->estado_venta;

if ($estado_venta == F) {
$msg_estado = "FINALIZADA";
}elseif ($estado_venta == H) {
$msg_estado = "HABILITADA";
}elseif ($estado_venta == D) {
$msg_estado = "DESHABILITADA";
}


$info_sorteo = mysql_query("SELECT * FROM sorteos_menores WHERE id = '$id_sorteo'  ");
$ob_sorteo = mysql_fetch_object($info_sorteo);
$no_sorteo = $ob_sorteo->no_sorteo_men;
$fecha_sorteo = $ob_sorteo->fecha_sorteo;

echo "<body>";
?>

<script type="text/javascript">
//window.print();
//window.onfocus=function(){ window.close();}
</script>

<table  width="100%">
    <tr>
        <td width="20%" style="vertical-align: top">
            <img src="./imagenes/logo-republica.png" width="100%">
        </td>
        <td width="60%" align="center">
<h3>
REPORTE DE LOTERIA NO VENDIDA 
</h3>
        </td>
        <td width="20%" style="vertical-align: top">
            <img src="./imagenes/logo-pani.png" width="100%">           
        </td>
    </tr>
</table>


<br>
<br>


<table width="100%" class="table table-bordered">
    <tr>
        <th>SORTEO</th>
        <th>FECHA SORTEO</th>
        <th>ESTADO VENTA</th>
        <th>ENTIDAD RECAUDADORA</th>
        <th>VENDEDOR</th>
    </tr>
    <tr>
        <td><?php echo $id_sorteo;?></td>
        <td><?php echo $fecha_sorteo;?></td>
        <td><?php echo $msg_estado;?></td>
        <td><?php echo $nombre_empresa;?></td>
        <td><?php echo $nombre_seccional;?></td>
    </tr>
</table>

<br>
<br>

<?php

echo "<br>";

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


$venta_por_serie = mysql_query("SELECT DISTINCT(a.serie) FROM `fvp_detalles_ventas_menor` as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE a.id_sorteo = '$id_sorteo' AND b.id_seccional = '$id_seccional' AND b.estado_venta = 'APROBADO' AND a.numero = '$numero' AND a.serie BETWEEN '$s_i' AND '$s_f'  ORDER BY serie ASC");


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

if ($cantidad_vendida != 0 ) {

$s = 0;
while (isset($v_serie_i[$s])) {

$cantidad_vendida_excel = $v_serie_f[$s] - $v_serie_i[$s] + 1;

//$objPHPExcel->getActiveSheet()->SetCellValue('A'.$fila_excel,$numero);
//$objPHPExcel->getActiveSheet()->SetCellValue('B'.$fila_excel,$v_serie_i[$s]);
//$objPHPExcel->getActiveSheet()->SetCellValue('C'.$fila_excel,$v_serie_f[$s]);
//$objPHPExcel->getActiveSheet()->SetCellValue('D'.$fila_excel,$cantidad_vendida_excel);

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




// LOTERIA NO VENDIDA POR VENDEDOR


echo "<table class = 'table table-bordered'>";
echo "<tr><th colspan = '4' ><h4 align = 'center'> DETALLE DE LOTERIA NO VENDIDA </h4></th></tr>";
echo "<tr>";
echo "<th>NUMERO</th>";
echo "<th>SERIE INICIAL</th>";
echo "<th>SERIE FINAL</th>";
echo "<th>CANTIDAD</th>";
echo "</tr>";

$total_no_vendido = 0;
$i = 0;
$fila_excel = 4;
while (isset($v_numero_no[$i])) {
$cantidad_no_vendida_excel = $v_s_f_no[$i] - $v_s_i_no[$i] + 1;

echo "<tr>";
echo "<td>".$v_numero_no[$i]."</td>";
echo "<td>".$v_s_i_no[$i]."</td>";
echo "<td>".$v_s_f_no[$i]."</td>";
echo "<td>".$cantidad_no_vendida_excel."</td>";
echo "</tr>";
$total_no_vendido = $total_no_vendido +  $cantidad_no_vendida_excel;
$fila_excel++;
$i++;
}

echo "</table>";

echo "<br>";
echo "<br>";
echo "<p align= 'justify'> Por este medio se hace entrega del reporte de loteria menor a ser triturada segun la asignacion de loteria del vendedor <b><u>".$nombre_seccional."</u></b>, con un total de loteria no vendida de <b><u>".$total_no_vendido."</u></b> numeros de loteria menor</p>";

$hoy = date("d-m-Y");

echo "<br>";
echo "Fecha de emision: <b><u>".$current_date."</u></b>";

echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";

echo"<table width = '100%' >";
echo "<tr>";
echo "<td width = '40%'>
<b><hr></b>
<p align = 'center'>FIRMA DE JEFE</p>
</td>";
echo "<td width = '20%'></td>";
echo "<td width = '40%'>
<b><hr></b>
<p align = 'center'>FIRMA DE VENDEDOR</p>
</td>";
echo "</tr>";
echo"</table>";

echo "</body>";

?>