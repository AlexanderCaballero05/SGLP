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


$total_numeros = 0;
$contador = 0;
$numeros_vendidos = mysqli_query($conn, "SELECT a.numero FROM `fvp_detalles_ventas_menor` as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura AND a.id_sorteo = b.id_sorteo WHERE a.id_sorteo = '$id_sorteo' AND b.id_entidad = '$id_entidad' AND b.cod_producto = '2' AND a.estado_venta = 'APROBADO' GROUP BY a.numero ORDER BY numero  ASC ");


while ($reg_numeros = mysqli_fetch_array($numeros_vendidos)) {
$num = $reg_numeros['numero'];
$venta_por_serie = mysqli_query($conn, "SELECT a.serie FROM `fvp_detalles_ventas_menor` as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura AND a.id_sorteo = b.id_sorteo WHERE  a.id_sorteo = '$id_sorteo' AND b.id_entidad = '$id_entidad' AND b.cod_producto = '2' AND numero = '$num' AND a.estado_venta = 'APROBADO'  ORDER BY serie ASC");

$v = 0;
while ($registro = mysqli_fetch_array($venta_por_serie)) {
$v_numeros[$v] = $num;
$v_series[$v] = $registro['serie'];
$v++;
};


$v = 0;
$s = 0;
$n = 0;

$v_numero_i[$s] = $num;
$v_serie_i[$s] = $v_series[$v];
$v_serie_f[$s] = $v_series[$v];


while (isset($v_series[$v])) {
    
    if (isset($v_series[$v + 1])) {

        if ($v_series[$v] + 1 == $v_series[$v + 1]) {
        $v_serie_f[$s] = $v_series[$v + 1];
        
        }else{

        $v_serie_f[$s] = $v_series[$v];

        $s++;

        $v_serie_i[$s] = $v_series[$v + 1];  
        $v_serie_f[$s] = $v_series[$v + 1];
        $v_numero_i[$s] = $v_numeros[$v + 1];

         }

    }else{
    $v_serie_f[$s] = $v_series[$v];  
    $v_numero_i[$s] = $v_numeros[$v];  
    $s++;
    }


$v++;
}

$s = 0;
while (isset($v_serie_i[$s])) {
$cant = $v_serie_f[$s] - $v_serie_i[$s] + 1;

$v_venta_n[$contador]  = $v_numeros[$s];
$v_venta_si[$contador] = $v_serie_i[$s];
$v_venta_sf[$contador] = $v_serie_f[$s];
$v_venta_c[$contador]  = $cant;
$contador++;

$total_numeros = $total_numeros + $cant;
$s++;
}


unset($v_series);
unset($v_numeros);
unset($v_serie_i);
unset($v_serie_f);

}







}else{








$c_ventas_b = mysqli_query($conn, "SELECT MIN(serie) as minimo, MAX(serie) as maximo, MAX(serie) -MIN(serie) +1 as cantidad , indicador FROM ( SELECT serie, @curRow := @curRow + 1 AS row_number, serie - @curRow AS indicador from transaccional_menor_banco_bolsas_detalle p join (SELECT @curRow := 0) r WHERE id_sorteo = '$id_sorteo' AND estado_venta = 'APROBADO' ORDER BY serie ASC ) t GROUP BY indicador ");




$total_numeros = 0;
$contador = 0;
$numeros_vendidos = mysqli_query($conn, "SELECT a.numero FROM `transaccional_menor_banco_numeros_detalle` as a INNER JOIN transaccional_ventas_general as b ON a.cod_factura = b.cod_factura_recaudador AND a.id_sorteo = b.id_sorteo WHERE a.id_sorteo = '$id_sorteo' AND b.id_entidad = '$id_entidad' AND b.cod_producto = '2'  AND a.estado_venta = 'APROBADO' GROUP BY a.numero ORDER BY numero ASC ");


while ($reg_numeros = mysqli_fetch_array($numeros_vendidos)) {
$num = $reg_numeros['numero'];
$venta_por_serie = mysqli_query($conn, "SELECT a.serie FROM `transaccional_menor_banco_numeros_detalle` as a INNER JOIN transaccional_ventas_general as b ON a.cod_factura = b.cod_factura_recaudador AND a.id_sorteo = b.id_sorteo WHERE  a.id_sorteo = '$id_sorteo' AND b.id_entidad = '$id_entidad' AND b.cod_producto = '2' AND numero = '$num' AND a.estado_venta = 'APROBADO' ORDER BY serie ASC");
 
$v = 0;
while ($registro = mysqli_fetch_array($venta_por_serie)) {
$v_numeros[$v] = $num;
$v_series[$v] = $registro['serie'];
$v++;
};


$v = 0;
$s = 0;
$n = 0;

$v_numero_i[$s] = $num;
$v_serie_i[$s] = $v_series[$v];
$v_serie_f[$s] = $v_series[$v];


while (isset($v_series[$v])) {
    
    if (isset($v_series[$v + 1])) {

        if ($v_series[$v] + 1 == $v_series[$v + 1]) {
        $v_serie_f[$s] = $v_series[$v + 1];
        
        }else{

        $v_serie_f[$s] = $v_series[$v];

        $s++;

        $v_serie_i[$s] = $v_series[$v + 1];  
        $v_serie_f[$s] = $v_series[$v + 1];
        $v_numero_i[$s] = $v_numeros[$v + 1];

         }

    }else{
    $v_serie_f[$s] = $v_series[$v];  
    $v_numero_i[$s] = $v_numeros[$v];  
    $s++;
    }


$v++;
}

$s = 0;
while (isset($v_serie_i[$s])) {
$cant = $v_serie_f[$s] - $v_serie_i[$s] + 1;

$v_venta_n[$contador]  = $v_numeros[$s];
$v_venta_si[$contador] = $v_serie_i[$s];
$v_venta_sf[$contador] = $v_serie_f[$s];
$v_venta_c[$contador]  = $cant;
$contador++;

$total_numeros = $total_numeros + $cant;
$s++;
}


unset($v_series);
unset($v_numeros);
unset($v_serie_i);
unset($v_serie_f);

}

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
REPORTE DE LOTERIA VENDIDA 
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
    <th colspan="3" class = "alert alert-secondary" style="text-align:center" >DETALLE DE LOTERIA MENOR POR BOLSA VENDIDA</th>
</tr>

<tr>
    <th>Serie Inicial</th>
    <th>Serie Final</th>
    <th>Cantidad</th>
</tr>
<?php 

$tt_cantidad = 0;

while ($det = mysqli_fetch_array($c_ventas_b)) {
echo "<tr><td align = 'center'>".$det['minimo']."</td>";
echo "<td align = 'center'>".$det['maximo']."</td>";
echo "<td align = 'center'>".number_format($det['cantidad'])."</td></tr>";

$tt_cantidad += $det['cantidad'];

}
?>

<tr>
    <th style="text-align: center" colspan="2">TOTAL VENDIDO BOLSAS</th>
    <th  style="text-align: center" ><?php echo number_format($tt_cantidad); ?></th>
</tr>

</table>






<br><br>


<?php

}

?>


<table  width="100%"  class="table table-bordered">
<tr>
    <th colspan="4" class = "alert alert-secondary" style="text-align:center">DETALLE DE LOTERIA MENOR POR NUMERO VENDIDA</th>
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
while (isset($v_venta_n[$contador])) {
echo "<tr>
      <td align = 'center'>".$v_venta_n[$contador]."</td>";
echo "<td align = 'center'>".$v_venta_si[$contador]."</td>";
echo "<td align = 'center'>".$v_venta_sf[$contador]."</td>";
echo "<td align = 'center'>".$v_venta_c[$contador]."</td>
      <tr>";

$tt_cantidad += $v_venta_c[$contador];
$contador ++;

}
?>

<tr>
    <th style="text-align: center" colspan="3">TOTAL VENDIDO NUMEROS</th>
    <th  style="text-align: center" ><?php echo number_format($tt_cantidad); ?></th>
</tr>

</table>

</div>
</div>



<br>
<br>