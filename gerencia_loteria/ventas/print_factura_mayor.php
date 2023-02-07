<?php
require '../../template/header.php';

if (isset($_GET['c'])) {
	$cod_factura = $_GET['c'];
} else {
	$cod_factura = $_SESSION['cod_impresion'];
}

$factura = mysqli_query($conn, "SELECT fecha_venta, identidad_comprador, total_neto, id_seccional, id_sorteo, identidad_comprador, nombre_comprador, cantidad, id_entidad, estado_venta, b.nombre_empresa, c.nombre  FROM transaccional_ventas as a INNER JOIN empresas as b INNER JOIN fvp_seccionales as c ON a.id_entidad = b.id AND a.id_seccional = c.id  WHERE cod_factura = $cod_factura ");

if ($factura == FALSE) {
	echo mysqli_error($conn);
}

$ob_factura = mysqli_fetch_object($factura);
$fecha_venta = $ob_factura->fecha_venta;
$identidad = $ob_factura->identidad_comprador;
$total = $ob_factura->total_neto;
$id_seccional = $ob_factura->id_seccional;
$id_sorteo = $ob_factura->id_sorteo;
$identidad_comprador = $ob_factura->identidad_comprador;
$nombre_comprador = $ob_factura->nombre_comprador;
$cantidad_factura = $ob_factura->cantidad;
$id_entidad = $ob_factura->id_entidad;
$entidad = $ob_factura->nombre_empresa;
$seccional = $ob_factura->nombre;
$estado = $ob_factura->estado_venta;

$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo' ");
$ob_sorteo = mysqli_fetch_object($info_sorteo);
$fecha_captura = $ob_sorteo->fecha_sorteo;

$detalle_venta = mysqli_query($conn, "SELECT MIN(billete) as minimo, MAX(billete) as maximo, MAX(billete) -MIN(billete) +1 as cantidad , indicador FROM ( SELECT billete, @curRow := @curRow + 1 AS row_number, billete - @curRow AS indicador from fvp_detalles_ventas_mayor p join (SELECT @curRow := 0) r WHERE cod_factura = '$cod_factura' ORDER BY billete ASC ) t GROUP BY indicador ");

$date = date('Y-m-d');

?>


<br>

<div class="card"  style="margin-right: 10px; margin-left: 10px">

<div class="card-header" align="center">

PANI HONDURAS<br>
FACTURA DE VENTA DE LOTERIA MAYOR<br><br>
ENTIDAD:
<b>
<?php
echo $entidad;
echo " / " . $seccional;
?>
</b>
<br>

Sorteo: <b><?php echo $id_sorteo; ?></b>
 a jugarse el <b><?php echo $fecha_captura; ?></b><br><br>


</div>

<br>

<?php
echo " Fecha de impresión: " . $date;
?>

<div  class = card-body>

<table class="table table-bordered">
    <tr>
        <th>FACTURA</th>
        <th>ID COMPRADOR</th>
        <th>NOMBRE COMPRADOR</th>
        <th>CANTIDAD</th>
        <th>TOTAL LPS.</th>
        <th>FECHA DE VENTA</th>
        <th>ESTADO</th>
    </tr>
    <tr>
        <td><?php echo $cod_factura; ?></td>
        <td><?php echo $identidad_comprador; ?></td>
        <td><?php echo $nombre_comprador; ?></td>
        <td><?php echo number_format($cantidad_factura); ?></td>
        <td><?php echo number_format($total, 2); ?></td>
        <td><?php echo $fecha_venta; ?></td>
        <td><?php echo $estado; ?></td>
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
while ($det = mysqli_fetch_array($detalle_venta)) {
	echo "<tr><td align = 'center'>" . $det['minimo'] . "</td>";
	echo "<td align = 'center'>" . $det['maximo'] . "</td>";
	echo "<td align = 'center'>" . $det['cantidad'] . "</td></tr>";
}
?>
</table>

</div>
</div>

<br>

