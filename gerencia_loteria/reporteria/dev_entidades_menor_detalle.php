<?php 

require("../../template/header.php"); 
date_default_timezone_set('America/Tegucigalpa');
$current_date = date("Y-m-d h:i:s a");

$id_sorteo  =  $_GET['s'];
$id_entidad =  $_GET['e'];
$id_empresa =  $_GET['e'];
$distribuid =  $_GET['d'];

$info_sorteo = mysqli_query($conn, "SELECT *  FROM sorteos_menores WHERE id = '$id_sorteo' limit 1");
$value = mysqli_fetch_object($info_sorteo);
$sorteo = $value->no_sorteo_men;
$fecha_sorteo = $value->fecha_sorteo;


$consulta_empresa   = mysqli_query($conn ,"SELECT nombre_empresa FROM empresas WHERE id = '$id_empresa' ");
$ob_empresa         = mysqli_fetch_object($consulta_empresa);
$nombre_empresa     = $ob_empresa->nombre_empresa;

$consulta_estado_sorteo = mysqli_query($conn ,"SELECT estado_venta FROM empresas_estado_venta WHERE id_sorteo = '$id_sorteo' AND cod_producto = 3  ");
$ob_estado_sorteo       = mysqli_fetch_object($consulta_estado_sorteo);
$estado_venta           = $ob_estado_sorteo->estado_venta;

if ($estado_venta == 'F') {
$msg_estado = "FINALIZADA";
}elseif ($estado_venta == 'H') {
$msg_estado = "HABILITADA";
}elseif ($estado_venta == 'D') {
$msg_estado = "DESHABILITADA";
}



if ($distribuid == "NO") {








/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////// OTRAS ENTIDADES RECAU /////////////////////////////////////////////////////


$total_pedido = 0;
$total_vendido = 0;
$total_no_vendido = 0;
$v_n = 0;
$fila_excel = 0;
$consulta_total_pedido = mysqli_query($conn ,"SELECT * FROM menor_seccionales_numeros WHERE  id_empresa = '$id_empresa' AND id_sorteo = '$id_sorteo'  ORDER BY numero ASC");

while ($reg_total_pedido = mysqli_fetch_array($consulta_total_pedido)) {

$cantidad_vendida = 0;

$numero = $reg_total_pedido['numero'];
$s_i = $reg_total_pedido['serie_inicial'];
$s_f = $reg_total_pedido['serie_final'];
$cantidad_pedido = $s_f - $s_i + 1; 

$total_pedido = $total_pedido + $cantidad_pedido;
echo "<tr>";


$venta_por_serie = mysqli_query($conn ,"SELECT DISTINCT(a.serie) FROM `fvp_detalles_ventas_menor` as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE a.id_sorteo = '$id_sorteo' AND b.id_entidad = '$id_entidad' AND b.estado_venta = 'APROBADO' AND a.numero = '$numero' AND a.serie BETWEEN '$s_i' AND '$s_f'  ORDER BY serie ASC");


$v = 0;
while ($registro = mysqli_fetch_array($venta_por_serie)) {
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

/*
$objPHPExcel->getActiveSheet()->SetCellValue('A'.$fila_excel,$numero);
$objPHPExcel->getActiveSheet()->SetCellValue('B'.$fila_excel,$v_serie_i[$s]);
$objPHPExcel->getActiveSheet()->SetCellValue('C'.$fila_excel,$v_serie_f[$s]);
$objPHPExcel->getActiveSheet()->SetCellValue('D'.$fila_excel,$cantidad_vendida_excel);
*/

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




///////////////////////////////////////////// OTRAS ENTIDADES RECAU /////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////







}else{











/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////// ENTIDAD DISTRIBUIDORA /////////////////////////////////////////////////////






$total_pedido = 0;
$total_vendido = 0;
$total_no_vendido = 0;
$v_n = 0;
$fila_excel = 0;
$consulta_total_pedido = mysqli_query($conn ,"SELECT min(serie) as serie_inicial, max(serie) as serie_final FROM transaccional_menor_banco_numeros_detalle WHERE  id_sorteo = '$id_sorteo' AND estado_venta = 'APROBADO'   ORDER BY serie ASC");


while ($reg_total_pedido = mysqli_fetch_array($consulta_total_pedido)) {
$numero = 0;
while ($numero <= 99) {



$cantidad_vendida = 0;
$s_i = $reg_total_pedido['serie_inicial'];
$s_f = $reg_total_pedido['serie_final'];
$cantidad_pedido = $s_f - $s_i + 1; 

$total_pedido = $total_pedido + $cantidad_pedido;
echo "<tr>";


$venta_por_serie = mysqli_query($conn ,"SELECT DISTINCT(a.serie) FROM `transaccional_menor_banco_numeros_detalle` as a INNER JOIN transaccional_ventas_general as b ON a.cod_factura = b.cod_factura_recaudador WHERE a.id_sorteo = '$id_sorteo' AND b.id_entidad = '$id_entidad' AND b.estado_venta = 'APROBADO' AND a.numero = '$numero' AND a.serie BETWEEN '$s_i' AND '$s_f'  ORDER BY serie ASC");


$v = 0;
while ($registro = mysqli_fetch_array($venta_por_serie)) {
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

/*
$objPHPExcel->getActiveSheet()->SetCellValue('A'.$fila_excel,$numero);
$objPHPExcel->getActiveSheet()->SetCellValue('B'.$fila_excel,$v_serie_i[$s]);
$objPHPExcel->getActiveSheet()->SetCellValue('C'.$fila_excel,$v_serie_f[$s]);
$objPHPExcel->getActiveSheet()->SetCellValue('D'.$fila_excel,$cantidad_vendida_excel);
*/

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

$numero ++;
}

}







////////////////////////////////////// NUMEROS /////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////// BOLSAS /////////////////////////////////////////////////




$b = 0;
$v = 0;
$n = 0;
$num = 0;
$total_vendido = 0;
$total_no_vendido = 0;
$total_pedido = 0;
$fila = 4;

$consulta_total_pedido = mysqli_query($conn,"SELECT a.serie_inicial, a.serie_final, a.cantidad  FROM menor_seccionales_bolsas as a  WHERE a.id_sorteo = '$id_sorteo' AND id_empresa = '$id_entidad' ORDER BY a.serie_inicial ASC");

if ($consulta_total_pedido === false) {
echo mysqli_error($conn);
}


$n = 0;

while ($reg_total_pedido = mysqli_fetch_array($consulta_total_pedido)) {

$cantidad_vendida = 0;

$s_i = $reg_total_pedido['serie_inicial'];
$s_f = $reg_total_pedido['serie_final'];

$cantidad_pedido = $s_f - $s_i + 1; 
$total_pedido = $total_pedido + $cantidad_pedido;


$venta_por_serie = mysqli_query($conn,"SELECT serie FROM transaccional_menor_banco_bolsas_detalle WHERE  estado_venta = 'APROBADO' AND id_sorteo = '$id_sorteo'  AND serie BETWEEN '$s_i' AND '$s_f' AND estado_venta = 'APROBADO'  UNION ALL SELECT serie FROM transaccional_menor_banco_numeros_detalle WHERE  estado_venta = 'APROBADO' AND id_sorteo = '$id_sorteo'  AND serie BETWEEN '$s_i' AND '$s_f' AND estado_venta = 'APROBADO'  GROUP BY serie ORDER BY serie ASC");


$v = 0;
while ($registro = mysqli_fetch_array($venta_por_serie)) {
$v_series[$v] = $registro['serie'];
$v++;
};

$v = 0;
$s = 0;
if (isset($v_series[$v])) {
$v_serie_i[$s] = $v_series[$v];


if ($s_i < $v_serie_i[$s]) {
$v_serie_n_i[$n] = $s_i;
$v_serie_n_f[$n] = $v_serie_i[$s] - 1;


//echo  " SErie no s ".$v_serie_n_i[$n]." FINAL NO ".$v_serie_n_f[$n]."<br>";

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

//echo $v_serie_n_i[$n]." ".$v_serie_n_f[$n]."<br>";

        $n++;

        }

    }else{


    $v_serie_f[$s] = $v_series[$v];  
    $v_serie_n_i[$n] = $v_series[$v] + 1;

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
$v_serie_n_i[$n] = $s_i;
$v_serie_n_f[$n] = $s_f;


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



unset($v_series);
unset($v_serie_i);
unset($v_serie_f);

}


///////////////////////////////////////////// ENTIDAD DISTRIBUIDORA /////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////













}



?> 


<div class="card" style="margin-left: 10px; margin-right: 10px;">


<div class="card-body">

<table  width="100%" >
    <tr>
        <td width="20%" style="vertical-align: top">
            <img src="<?php echo $ruta; ?>template/images/logo-republica.png" width="80%">
        </td>
        <td width="60%" align="center">
<h4>
PATRONATO NACIONAL DE LA INFANCIA	<br>
REPORTE DE LOTERIA NO VENDIDA 
</h4>

        </td>
        <td width="20%" style="vertical-align: top">
            <img src="<?php echo $ruta; ?>template/images/logo-pani.png" width="100%">           
        </td>
    </tr>
</table>

<br>
<hr>
<br>

<table width="100%" class="table table-bordered">
    <tr>
        <th>SORTEO</th>
        <th>FECHA SORTEO</th>
        <th>ESTADO VENTA</th>
        <th>ENTIDAD RECAUDADORA</th>
    </tr>
    <tr>
        <td><?php echo $id_sorteo;?></td>
        <td><?php echo $fecha_sorteo;?></td>
        <td><?php echo $msg_estado;?></td>
        <td><?php echo $nombre_empresa;?></td>
    </tr>

</table>



<?php 

if ($distribuid == "SI") {

?>

<table  width="100%"  class="table table-bordered">
<tr>
    <th colspan="3" class = "alert alert-secondary" style="text-align:center" >DETALLE DE LOTERIA MENOR POR BOLSA NO VENDIDA</th>
</tr>

<tr>
    <th>Serie Inicial</th>
    <th>Serie Final</th>
    <th>Cantidad</th>
</tr>
<?php 

$tt_cantidad = 0;

$i = 0;
while (isset($v_serie_n_i[$i]) AND isset($v_serie_n_f[$i])) {

$cant_no = $v_serie_n_f[$i] - $v_serie_n_i[$i] + 1;

echo "<tr><td align = 'center'>".$v_serie_n_i[$i]."</td>";
echo "<td align = 'center'>".$v_serie_n_f[$i]."</td>";
echo "<td align = 'center'>".number_format($cant_no)."</td></tr>";

$tt_cantidad += $cant_no;

$i++;
}

?>

<tr>
    <th style="text-align: center" colspan="2">TOTAL NO VENDIDO BOLSAS</th>
    <th  style="text-align: center" ><?php echo number_format($tt_cantidad); ?></th>
</tr>

</table>






<br><br>


<?php

}

?>


<table  width="100%"  class="table table-bordered">
<tr>
    <th colspan="4" class = "alert alert-secondary" style="text-align:center">DETALLE DE LOTERIA MENOR POR NUMERO NO VENDIDA</th>
</tr>

<tr>
    <th>Numero</th>
    <th>Serie Inicial</th>
    <th>Serie Final</th>
    <th>Cantidad</th>
</tr>
<?php 

$tt_cantidad = 0;
$contador = 0;
while (isset($v_numero_no[$contador])) {

$c_no = $v_s_f_no[$contador] - $v_s_i_no[$contador] + 1;

echo "<tr>
      <td align = 'center'>".$v_numero_no[$contador]."</td>";
echo "<td align = 'center'>".$v_s_i_no[$contador]."</td>";
echo "<td align = 'center'>".$v_s_f_no[$contador]."</td>";
echo "<td align = 'center'>".number_format($c_no)."</td>
      <tr>";

$tt_cantidad += $c_no;
$contador ++;

}
?>

<tr>
    <th style="text-align: center" colspan="3">TOTAL NO VENDIDO NUMEROS</th>
    <th  style="text-align: center" ><?php echo number_format($tt_cantidad); ?></th>
</tr>

</table>

</div>
</div>


<br>
<br>

<?php 

echo "<br>";
echo "<br>";
echo "<p align = 'justify'> Por este medio se hace entrega del reporte de loteria menor a ser triturada segun la asignacion de loteria de la entidad <b><u>".$nombre_empresa."</u></b>, con un total de loteria no vendida de <b><u>".number_format($tt_cantidad)."</u></b> billetes de loteria menor</p>";

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

?>