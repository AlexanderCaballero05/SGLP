<?php
require "../../template/header.php";
date_default_timezone_set('America/Tegucigalpa');
$current_date = date("Y-m-d h:i:s a");

$id_sorteo = $_GET['s'];
$id_entidad = $_GET['e'];
$id_empresa = $_GET['e'];
$distribuid = $_GET['d'];

$info_sorteo = mysqli_query($conn, "SELECT *  FROM sorteos_mayores WHERE id = '$id_sorteo' limit 1");
$value = mysqli_fetch_object($info_sorteo);
$sorteo = $value->no_sorteo_may;
$fecha_sorteo = $value->fecha_sorteo;
$mezcla = $value->mezcla;

$consulta_empresa = mysqli_query($conn, "SELECT nombre_empresa FROM empresas WHERE id = '$id_empresa' ");
$ob_empresa = mysqli_fetch_object($consulta_empresa);
$nombre_empresa = $ob_empresa->nombre_empresa;

$consulta_estado_sorteo = mysqli_query($conn, "SELECT estado_venta FROM empresas_estado_venta WHERE id_sorteo = '$id_sorteo' AND cod_producto = 1  ");
$ob_estado_sorteo = mysqli_fetch_object($consulta_estado_sorteo);
$estado_venta = $ob_estado_sorteo->estado_venta;

if ($estado_venta == 'F') {
	$msg_estado = "FINALIZADA";
} elseif ($estado_venta == 'H') {
	$msg_estado = "HABILITADA";
} elseif ($estado_venta == 'D') {
	$msg_estado = "DESHABILITADA";
}

$b = 0;
$v = 0;
$n = 0;
$num = 0;
$total_vendido = 0;
$total_no_vendido = 0;
$total_pedido = 0;
$fila = 4;
$total_t = 0;
$cantidad_vendida = 0;
$cantidad_pedido = 0;

$consulta_total_pedido = mysqli_query($conn, "SELECT a.rango FROM sorteos_mezclas_rangos as a  INNER JOIN sorteos_mezclas as b ON b.num_mezcla = a.num_mezcla AND a.id_sorteo = b.id_sorteo WHERE a.id_sorteo = '$id_sorteo' AND b.id_empresa = '$id_entidad' GROUP BY a.rango ORDER BY a.rango ASC ");

if ($distribuid == "NO") {

	$c_ventas = mysqli_query($conn, "SELECT MIN(billete) as minimo, MAX(billete) as maximo, MAX(billete) - MIN(billete) + 1 as cantidad , indicador FROM ( SELECT billete, @curRow := @curRow + 1 AS row_number, billete - @curRow AS indicador from fvp_detalles_ventas_mayor p INNER JOIN transaccional_ventas as b join (SELECT @curRow := 0) r ON p.cod_factura = b.cod_factura WHERE p.id_sorteo = '$id_sorteo' AND id_entidad = '$id_entidad' AND cod_producto = '1' AND p.estado_venta = 'APROBADO' ORDER BY billete ASC ) t GROUP BY indicador ");

} else {

	$c_ventas = mysqli_query($conn, "SELECT MIN(billete) as minimo, MAX(billete) as maximo, MAX(billete) -MIN(billete) +1 as cantidad , indicador FROM ( SELECT billete, @curRow := @curRow + 1 AS row_number, billete - @curRow AS indicador from transaccional_mayor_banco_detalle p join (SELECT @curRow := 0) r WHERE id_sorteo = '$id_sorteo' AND estado_venta = 'APROBADO' ORDER BY billete ASC ) t GROUP BY indicador ");

}

while ($r_ventas = mysqli_fetch_array($c_ventas)) {

	$billete = $r_ventas['minimo'];

	while ($billete <= $r_ventas['maximo']) {
		$v_vendido[$n] = $billete;
		$billete++;
		$n++;
	}

}

$n = 0;

while ($reg_total_pedido = mysqli_fetch_array($consulta_total_pedido)) {

	$b_asginado_i = $reg_total_pedido['rango'];
	$b_asginado_f = $reg_total_pedido['rango'] + $mezcla - 1;
	$cantidad_pedido += $mezcla;

	while ($b_asginado_i <= $b_asginado_f) {
		$v_asginado[$n] = $b_asginado_i;
		$b_asginado_i++;
		$n++;
	}

}

function getRanges($nums) {
	sort($nums);
	$ranges = array();

	for ($i = 0, $len = count($nums); $i < $len; $i++) {
		$rStart = $nums[$i];
		$rEnd = $rStart;
		while (isset($nums[$i + 1]) && $nums[$i + 1] - $nums[$i] == 1) {
			$rEnd = $nums[++$i];
		}

		$ranges[] = $rStart == $rEnd ? $rStart : $rStart . '-' . $rEnd;
	}

	return $ranges;
}


if (isset($v_vendido)) {
	$v_no_vendido = array_diff($v_asginado, $v_vendido);
} else {
	$v_no_vendido = $v_asginado;
}


$rangos_no_vendido = getRanges($v_no_vendido);

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
PATRONATO NACIONAL DE LA INFANCIA   <br>
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
        <td><?php echo $id_sorteo; ?></td>
        <td><?php echo $fecha_sorteo; ?></td>
        <td><?php echo $msg_estado; ?></td>
        <td><?php echo $nombre_empresa; ?></td>
    </tr>

</table>



<table  width="100%"  class="table table-bordered">
<tr>
    <th colspan="3" style="align:center">DETALLE DE LOTERIA MAYOR NO VENDIDA</th>
</tr>

<tr>
    <th>Billete Inicial</th>
    <th>Billete Final</th>
    <th>Cantidad</th>
</tr>
<?php

$tt_cantidad = 0;

$n = 0;
$total_no_vendido = 0;
while (isset($rangos_no_vendido[$n])) {

	$v_no = explode("-", $rangos_no_vendido[$n]);
	$v_serie_n_i = $v_no[0];

	if (isset($v_no[1])) {
		$v_serie_n_f = $v_no[1];
	} else {
		$v_serie_n_f = $v_serie_n_i;
	}

	$cantidad_entre_series = $v_serie_n_f - $v_serie_n_i + 1;

	echo "<tr>";
	echo "<td>" . $v_serie_n_i . "</td>";
	echo "<td>" . $v_serie_n_f . "</td>";
	echo "<td>" . $cantidad_entre_series . "</td>";
	echo "</tr>";
	$total_no_vendido = $total_no_vendido + $cantidad_entre_series;
//echo $v_serie_n_i[$n]." ".$v_serie_n_f[$n]." ".$cantidad_entre_series." <br>";
	$fila++;
	$n++;
}

?>

<tr>
    <th style="text-align: center" colspan="2">TOTAL NO VENDIDO</th>
    <th  style="text-align: center" ><?php echo number_format($total_no_vendido); ?></th>
</tr>

</table>

</div>


</div>



<br>
<br>



<?php

echo "<br>";
echo "<br>";
echo "<p align = 'justify'> Por este medio se hace entrega del reporte de loteria mayor a ser triturada segun la asignacion de loteria de la entidad <b><u>" . $nombre_empresa . "</u></b>, con un total de loteria no vendida de <b><u>" . $total_no_vendido . "</u></b> billetes de loteria mayor</p>";

$hoy = date("d-m-Y");

echo "<br>";
echo "Fecha de emision: <b><u>" . $current_date . "</u></b>";

echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";

echo "<table width = '100%' >";
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
echo "</table>";

?>