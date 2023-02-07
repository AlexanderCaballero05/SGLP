<?php 

require("../../template/header.php"); 
date_default_timezone_set('America/Tegucigalpa');

$id_sorteo  =  $_GET['s'];
$id_entidad =  $_GET['e'];
$id_empresa =  $_GET['e'];
$distribuid =  $_GET['d'];

$info_sorteo = mysqli_query($conn, "SELECT *  FROM sorteos_mayores WHERE id = '$id_sorteo' limit 1");
$value = mysqli_fetch_object($info_sorteo);
$sorteo = $value->no_sorteo_may;
$fecha_sorteo = $value->fecha_sorteo;
$mezcla = $value->mezcla;


$consulta_empresa   = mysqli_query($conn ,"SELECT nombre_empresa FROM empresas WHERE id = '$id_empresa' ");
$ob_empresa         = mysqli_fetch_object($consulta_empresa);
$nombre_empresa     = $ob_empresa->nombre_empresa;

$consulta_estado_sorteo = mysqli_query($conn ,"SELECT estado_venta FROM empresas_estado_venta WHERE id_sorteo = '$id_sorteo' AND cod_producto = 1  ");
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

$c_ventas = mysqli_query($conn, "SELECT MIN(billete) as minimo, MAX(billete) as maximo, MAX(billete) - MIN(billete) + 1 as cantidad , indicador FROM ( SELECT billete, @curRow := @curRow + 1 AS row_number, billete - @curRow AS indicador from fvp_detalles_ventas_mayor p INNER JOIN transaccional_ventas as b join (SELECT @curRow := 0) r ON p.cod_factura = b.cod_factura WHERE p.id_sorteo = '$id_sorteo' AND id_entidad = '$id_entidad' AND cod_producto = '1' AND p.estado_venta = 'APROBADO' ORDER BY billete ASC ) t GROUP BY indicador ");

}else{

$c_ventas = mysqli_query($conn, "SELECT MIN(billete) as minimo, MAX(billete) as maximo, MAX(billete) -MIN(billete) +1 as cantidad , indicador FROM ( SELECT billete, @curRow := @curRow + 1 AS row_number, billete - @curRow AS indicador from transaccional_mayor_banco_detalle p join (SELECT @curRow := 0) r WHERE id_sorteo = '$id_sorteo' AND estado_venta = 'APROBADO' ORDER BY billete ASC ) t GROUP BY indicador ");
	
}


if ($c_ventas === FALSE) {
echo mysqli_error($conn);
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



<table  width="100%"  class="table table-bordered">
<tr>
    <th colspan="3" style="align:center">DETALLE DE LOTERIA MAYOR VENDIDA</th>
</tr>

<tr>
    <th>Billete Inicial</th>
    <th>Billete Final</th>
    <th>Cantidad</th>
</tr>
<?php 

$tt_cantidad = 0;

while ($det = mysqli_fetch_array($c_ventas)) {
echo "<tr><td align = 'center'>".$det['minimo']."</td>";
echo "<td align = 'center'>".$det['maximo']."</td>";
echo "<td align = 'center'>".number_format($det['cantidad'])."</td></tr>";

$tt_cantidad += $det['cantidad'];

}
?>

<tr>
    <th style="text-align: center" colspan="2">TOTAL VENDIDO</th>
    <th  style="text-align: center" ><?php echo number_format($tt_cantidad); ?></th>
</tr>

</table>

</div>
</div>



<br>
<br>