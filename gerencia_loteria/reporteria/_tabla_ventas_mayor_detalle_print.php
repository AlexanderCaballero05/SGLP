<?php 
require("./template/header.php");

date_default_timezone_set("America/Tegucigalpa");

$current_date = date("Y-m-d h:i:s a");


//REPORTE VENTAS

$parametros = $_GET['v1'];
$par = explode("/", $parametros);
$id_seccional = $par[1];
$id_sorteo = $par[0];

if (isset($id_sorteo)) {

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

$consulta_estado_sorteo = mysql_query("SELECT estado_venta FROM empresas_estado_venta WHERE id_sorteo = '$id_sorteo' AND cod_producto = 1 AND id_empresa = '$id_empresa' ");
$ob_estado_sorteo       = mysql_fetch_object($consulta_estado_sorteo);
$estado_venta           = $ob_estado_sorteo->estado_venta;

if ($estado_venta == F) {
$msg_estado = "FINALIZADA";
}elseif ($estado_venta == H) {
$msg_estado = "HABILITADA";
}elseif ($estado_venta == D) {
$msg_estado = "DESHABILITADA";
}

$info_sorteo = mysql_query("SELECT *  FROM sorteos_mayores WHERE id = '$id_sorteo' limit 1");
$value = mysql_fetch_object($info_sorteo);
$sorteo = $value->no_sorteo_may;
$fecha_sorteo = $value->fecha_sorteo;
$mezcla = $value->mezcla;



$b = 0;
$v = 0;
$n = 0;
$num = 0;
$total_vendido = 0;
$total_no_vendido = 0;
$total_pedido = 0;
$fila = 4;
$total_t = 0;

$consulta_total_pedido = mysql_query("SELECT rango FROM sorteos_mezclas_rangos as a  WHERE a.id_sorteo = '$id_sorteo' AND a.id_seccional = '$id_seccional' ORDER BY a.rango ASC ");

if ($consulta_total_pedido === false) {
echo mysql_error();
}


$n = 0;

while ($reg_total_pedido = mysql_fetch_array($consulta_total_pedido)) {

$cantidad_vendida = 0;

$b_i = $reg_total_pedido['rango'];
$b_f = $reg_total_pedido['rango'] +  $mezcla - 1;
$cantidad_pedido = $b_f - $b_i + 1; 
$total_pedido = $total_pedido + $cantidad_pedido;


$venta_por_serie = mysql_query("SELECT DISTINCT(a.billete)  FROM fvp_detalles_ventas_mayor as a INNER JOIN fvp_seccionales as c  INNER JOIN transaccional_ventas as f  ON a.cod_factura = f.cod_factura AND f.id_seccional = c.id WHERE f.id_seccional = '$id_seccional' AND a.estado_venta = 'APROBADO' AND a.id_sorteo = '$id_sorteo'  AND a.billete BETWEEN '$b_i' AND '$b_f'  ORDER BY billete ASC");

if ($venta_por_serie === false) {
echo mysql_error();
}

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
while (isset($v_serie_i[$s])) {
$cantidad_entre_series = $v_serie_f[$s] - $v_serie_i[$s] + 1;

/*
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$fila,$v_serie_i[$s] );
        $objPHPExcel->getActiveSheet()->SetCellValue('B'.$fila,$v_serie_f[$s] );
        $objPHPExcel->getActiveSheet()->SetCellValue('C'.$fila, $cantidad_entre_series);
*/

//echo $v_dpto[$s]." ".$v_agencia[$s]." ".$v_serie_i[$s]." ".$v_serie_f[$s]." ".$cantidad_entre_series." <br>";

$total_t = $total_t + $cantidad_entre_series;
$fila++;
$s++;
}

}

unset($v_series);
unset($v_serie_i);
unset($v_serie_f);

}

//echo "<br>";
//*********************************************************************
// NEW SHEET 
//*********************************************************************




// TRITURACION



$fila  =  4;


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

<?php


echo "<table style = '' class = 'table table-bordered'>";
echo "<tr><th colspan = '3' ><h4 align = 'center'> DETALLE DE LOTERIA NO VENDIDA </h4></th></tr>";
echo "<tr>";
echo "<th>BILLETE INICIAL</th>";
echo "<th>BILLETE FINAL</th>";
echo "<th>CANTIDAD</th>";
echo "</tr>";


$n = 0;
$total_no_vendido = 0;
while (isset($v_serie_n_i[$n]) && isset($v_serie_n_f[$n]) ) {
$cantidad_entre_series = $v_serie_n_f[$n] - $v_serie_n_i[$n] + 1;

echo "<tr>";
echo "<td>".$v_serie_n_i[$n]."</td>";
echo "<td>".$v_serie_n_f[$n]."</td>";
echo "<td>".$cantidad_entre_series."</td>";
echo "</tr>";
$total_no_vendido = $total_no_vendido + $cantidad_entre_series;
//echo $v_serie_n_i[$n]." ".$v_serie_n_f[$n]." ".$cantidad_entre_series." <br>";
$fila++;
$n++;
}

echo "</table>";


echo "<br>";
echo "<br>";
echo "<p align = 'justify'> Por este medio se hace entrega del reporte de loteria mayor a ser triturada segun la asignacion de loteria del vendedor <b><u>".$nombre_seccional."</u></b>, con un total de loteria no vendida de <b><u>".$total_no_vendido."</u></b> billetes de loteria mayor</p>";

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



}
?>