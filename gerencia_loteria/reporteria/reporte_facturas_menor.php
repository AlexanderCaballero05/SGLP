<?php
require('./template/header.php');
$sorteos = mysql_query("SELECT * FROM sorteos_menores WHERE id >= 3147  ORDER BY no_sorteo_men DESC ");
?>

<body>

<form method="POST" autocomplete="off">

<br>

<div id="non-printable" class="alert alert-info">
  <h3 align="center">TRANSACCIONES REALIZADAS LOTERIA MENOR</h3>

<a style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
  Seleccion de Parametros
</a>

<div class="collapse" id="collapse1">
<div class="well">
<table class="table table-bordered">
    <tr>
        <th width="20%">Sorteo</th>
        <th width="10%">Tipo Venta</th>
        <th width="10%">Accion</th>
    </tr>
    <tr>
<td>
<select name="sorteo" class = 'form-control' style="width: 100%">
<?php
while ($row2 = mysql_fetch_array($sorteos))
{
echo '<option value = "'.$row2['id'].'">No.'.$row2['no_sorteo_men'].'</option>' ;
}
?>
</select> 
</td>

<td>
<select name="tipo_consulta" class = 'form-control'>
    <option value="1">GENERAL</option>
    <option value="2">CONTADO</option>
    <option value="3">CREDITO</option>
    <option value="4">DEDUCCION</option>
</select>    
</td>

<td align="center">
<input type="submit" name="seleccionar" class="btn btn-primary" style="background-color: #005c7a;" value="Seleccionar">            
</td>       
</tr>
</table>
</div>
</div>

</div>

<hr>
<br>

<?php
if (isset($_POST['seleccionar'])) {

require('./conexion_oracle.php');

$tipo_consulta =  $_POST['tipo_consulta'];
$id_sorteo = $_POST['sorteo'];

if ($tipo_consulta == 1) {
$consulta_ventas = oci_parse($conn2, "SELECT SORTEO, CODIGO_FACTURA, FECHA_VENTA, IDENTIDAD, MONTO, NOMBRE_VENDEDOR, EMITIDO, ANULADO, CANTIDAD_BILLETES, NOMBRE_COMPRADOR, FORMA_PAGO FROM LOT_DETALLE_FACTURACION  WHERE SORTEO = $id_sorteo AND LOTERIA = 2 ");
oci_execute($consulta_ventas);                   
}elseif ($tipo_consulta == 2) {
$consulta_ventas = oci_parse($conn2, "SELECT SORTEO, CODIGO_FACTURA, FECHA_VENTA, IDENTIDAD, MONTO, NOMBRE_VENDEDOR, EMITIDO, ANULADO, CANTIDAD_BILLETES, NOMBRE_COMPRADOR, FORMA_PAGO FROM LOT_DETALLE_FACTURACION  WHERE SORTEO = $id_sorteo AND LOTERIA = 2  AND FORMA_PAGO = 1 ");
oci_execute($consulta_ventas);                   
}elseif ($tipo_consulta == 3) {
$consulta_ventas = oci_parse($conn2, "SELECT SORTEO, CODIGO_FACTURA, FECHA_VENTA, IDENTIDAD, MONTO, NOMBRE_VENDEDOR, EMITIDO, ANULADO, CANTIDAD_BILLETES, NOMBRE_COMPRADOR, FORMA_PAGO FROM LOT_DETALLE_FACTURACION  WHERE SORTEO = $id_sorteo AND LOTERIA = 2  AND FORMA_PAGO = 2 ");
oci_execute($consulta_ventas);                   
}elseif ($tipo_consulta == 4) {
$consulta_ventas = oci_parse($conn2, "SELECT SORTEO, CODIGO_FACTURA, FECHA_VENTA, IDENTIDAD, MONTO, NOMBRE_VENDEDOR, EMITIDO, ANULADO, CANTIDAD_BILLETES, NOMBRE_COMPRADOR, FORMA_PAGO FROM LOT_DETALLE_FACTURACION  WHERE SORTEO = $id_sorteo AND LOTERIA = 2  AND FORMA_PAGO = 3 ");
oci_execute($consulta_ventas);                   
}




?>

<div class="well">
<h3 align="center">Transacciones de Loteria Menor</h3>
 <table id="table_id1" style="width:100%" class="table table-hover table-bordered">
<thead>
  <tr>
    <th>SORTEO</th>
    <th>FACTURA</th>
    <th>VENDEDOR</th>
    <th>COMPRADOR</th>
    <th>CANTIDAD </th>
    <th>TOTAL NETO</th>
    <th>FORMA PAGO</th>
    <th>ESTADO</th>
    <th>COMPROBANTE</th>
    <th>FECHA VENTA</th>
    <th></th>
  </tr>
</thead>
<tbody>
<?php

while ($reg_ventas = oci_fetch_array($consulta_ventas, OCI_ASSOC+OCI_RETURN_NULLS)) 
{  


echo "<tr>

<td width='25%'>".$reg_ventas['SORTEO']."</td>
<td width='15%'>".$reg_ventas['CODIGO_FACTURA']."</td>
<td width='15%'>".$reg_ventas['NOMBRE_VENDEDOR']."</td>
<td width='15%'>".$reg_ventas['NOMBRE_COMPRADOR']."</td>
<td width='10%'>".$reg_ventas['CANTIDAD_BILLETES']."</td>
<td width='15%'>".$reg_ventas['MONTO']."</td>";

if ($reg_ventas['FORMA_PAGO'] == 1) {
echo "<td width='15%'>CONTADO</td>";
}elseif ($reg_ventas['FORMA_PAGO'] == 2) {
echo "<td width='15%'>CREDITO</td>";
}elseif ($reg_ventas['FORMA_PAGO'] == 3) {
echo "<td width='15%'>DEDUCCION</td>";
}


if ($reg_ventas['ANULADO'] == 'N') {
echo "<td width='10%'>APROBADA</td>";
}else{
echo "<td width='10%'>CANCELADA</td>";    
}

if ($reg_ventas['EMITIDO'] == 'N') {
echo "<td width='15%'>NO EMITIDO</td>";
}else{
echo "<td width='15%'>EMITIDO</td>";
}

echo " <td width='15%'>".$reg_ventas['FECHA_VENTA']."</td>

<td width='10%' align= 'center'>
<a class='btn btn-primary' target='_blank' href= './print_factura_mayor.php?c=".$reg_ventas['CODIGO_FACTURA']."'>
<span class = 'glyphicon glyphicon-eye-open'></span>  
</a>
</td>

</tr>
";


}

?>


    </tbody>
  </table> 
</div>

<br>
<br>

<?php

}

?>



</form>
</body>