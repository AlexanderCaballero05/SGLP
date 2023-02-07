<?php
require('../../template/header.php');

$id_empresa = $_SESSION['id_empresa'];
$id_seccional = $_SESSION['id_seccional'];
$sorteos2 = mysqli_query($conn,"SELECT a.id, a.no_sorteo_men, a.fecha_sorteo, a.descripcion_sorteo_men  FROM sorteos_menores as a inner join empresas_estado_venta as b ON a.id = b.id_sorteo WHERE  b.estado_venta = 'H' AND b.id_empresa = '$id_empresa' AND b.cod_producto = 2 ORDER BY a.id DESC ");


if (isset($_POST['reversar_venta_menor'])) {

require('./conexion_oracle.php');


$factura = $_POST['reversar_venta_menor'];


$busqueda_estado = mysqli_query($conn,"SELECT * FROM transaccional_ventas WHERE cod_factura = '$factura' LIMIT 1");
$o_estado = mysqli_fetch_object($busqueda_estado);
$estado = $o_estado->estado_venta;


if ($estado == 'APROBADO') {
if (mysqli_query($conn,"UPDATE transaccional_ventas SET estado_venta = 'CANCELADA' WHERE cod_factura = '$factura' ")=== true) {
mysqli_query($conn,"UPDATE fvp_detalles_ventas_menor SET estado_venta = 'CANCELADA' WHERE cod_factura = '$factura' ");


////////////////////////////////////////////////
/////////// REGISTRO EN BITACORA ///////////////
$id_usuario_bitacora  = $_SESSION['id_usuario'];

$modulo_bitacora    = "VENTAS";
$tipo_mod_bitacora    = "UPDATE";
$tabla_bitacora     = "transaccional_ventas";
$descripcion_bitacora = "Cancelacion de factura loteria menor, Codigo Factura: ".$factura;

$cod_accion       = "7";
$registro_bitacora = registro_bitacora($id_usuario_bitacora,$modulo_bitacora,$cod_accion,$tipo_mod_bitacora ,$tabla_bitacora , $descripcion_bitacora);
/////////// REGISTRO EN BITACORA ///////////////
////////////////////////////////////////////////

$resultado_ERP="UPDATE LOT_DETALLE_FACTURACION  SET ANULADO = 'S' WHERE codigo_factura = '$factura' ";     
$save_result=oci_parse($conn2, $resultado_ERP);
 
$rc=oci_execute($save_result);
oci_free_statement($rc);

if(!$rc)
{
$e=oci_error($save_result);
var_dump($e);
}

echo '<div class="alert alert-success" role="alert"> La venta ha sido cancelada exitosamente</div>';
}else{
echo '<div class="alert alert-danger" role="alert"> Error inesperado por favor vuelva a intentarlo</div>';
}

}

$ventas_sorteo2 = mysqli_query($conn,"SELECT a.estado_venta,a.fecha_venta, a.identidad_comprador, a.id_usuario, a.cod_factura, a.total_neto as precio_total,  b.codigo_empleado FROM transaccional_ventas as a INNER JOIN fvp_usuarios as b ON a.id_usuario = b.id WHERE a.id_sorteo = $id_sorteo  ORDER BY  a.cod_factura DESC ");

}

?>

<script type="text/javascript">
/////////////////////////////////////////
/// FUNCION PARA CARGADO DE SECCIONALES //

function funcion_seleccion_nuevo(id_empresa){


var obj_select = document.getElementById("s_seccional");
conteo_opciones = obj_select.length;
obj_select.options[0].selected = true;

for (var i = 1; i <= conteo_opciones; i++) {
if (obj_select.options[i].id == id_empresa ) {
obj_select.options[i].style.display = "block";
}else{
obj_select.options[i].style.display = "none";  
}
}

}


/// FUNCION PARA CARGADO DE SECCIONALES //
///////////////////////////////////////// 
</script>



<body>

<form method="POST" autocomplete="off">

<div class="alert alert-info">
<h3 align="center">Reversion de Ventas Loteria Menor</h3>

<a style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
  Seleccion de Parametros
</a>

<div class="collapse" id="collapse1">
<div class="well">
<table class="table table-bordered">
<tr>
<th width="25%">Sorteo</th>
<th width="25%">Entidad</th>
<th width="25%">Seccional</th>
<th width="25%">Accion</th>
</tr>
<tr>
<td>
<select name="sorteo"  class="selectpicker" data-width="100%" data-live-search="true" required>
<?php

$sorteos = mysqli_query($conn,"SELECT a.id, a.no_sorteo_men, a.fecha_sorteo, a.descripcion_sorteo_men  FROM sorteos_menores as a inner join empresas_estado_venta as b ON a.id = b.id_sorteo WHERE  b.estado_venta = 'H'  AND b.cod_producto = 3 ORDER BY a.id DESC ");

while ($row2 = mysqli_fetch_array($sorteos)) {
echo '<option value = "'.$row2['id'].'">'.$row2['no_sorteo_men'].'</option>' ;
}

?>
</select> 
</td>       

<td>
<select name="s_entidad" id="s_entidad" class = 'form-control' onchange=" funcion_seleccion_nuevo(this.value);" required>
<option value="ninguno">Seleccione una opcion</option>

<?php

$c_entidades = mysqli_query($conn," SELECT * FROM empresas WHERE estado = 'ACTIVO' ");

while ($r_entidades = mysqli_fetch_array($c_entidades) ) {
echo "<option value = '".$r_entidades['id']."' >".$r_entidades['nombre_empresa']."</option>";
}

?>
</select> 
</td>       


<td>
<select name="s_seccional" id="s_seccional" class = 'form-control' required>
<option value="ninguno">Seleccione una opcion</option>

<?php

$c_seccionales = mysqli_query($conn," SELECT * FROM fvp_seccionales ");

while ($r_seccionales = mysqli_fetch_array($c_seccionales) ) {
echo "<option style = 'display:none' id = '".$r_seccionales['id_empresa']."' value = '".$r_seccionales['id']."' >".$r_seccionales['nombre']."</option>";
}

?>
</select> 
</td>       


<td align="center">
<input type="submit" name="seleccionar" class="btn btn-primary" value="Seleccionar">            
</td>       
</tr>
</table>
</div>
</div>

</div>

<hr>

<?php
if (isset($_POST['seleccionar'])) {
$id_sorteo = $_POST['sorteo'];
$ventas_sorteo2 = mysqli_query($conn,"SELECT a.estado_venta,a.fecha_venta, a.identidad_comprador, a.id_usuario, a.cod_factura, a.total_neto as precio_total,  b.codigo_empleado FROM transaccional_ventas as a INNER JOIN fvp_usuarios as b ON a.id_usuario = b.id WHERE a.id_sorteo = $id_sorteo  ORDER BY  a.cod_factura DESC ");
?>

<div class="panel panel-primary">
<div class="panel-heading">
  <h3 align="center">Transacciones de Loteria Menor <?php echo $id_sorteo; ?></h3>
</div>

<div class="table-body">
<br>
<table id="table_id2" style="width:100%" class="table table-hover table-bordered">
<thead>
  <tr>
    <th>Cajero</th>
    <th>Factura</th>
    <th>Fecha Venta</th>
    <th>Total Pagado</th>
    <th>Estado</th>
    <th>Accion</th>
  </tr>
</thead>
<tbody>
<?php
while ($venta2 = mysqli_fetch_array($ventas_sorteo2)) {
$cod= $venta2['cod_factura'];
echo "<tr>
<td width='25%' >".$venta2['codigo_empleado']."</td>
<td width='15%'>".$venta2['cod_factura']."</td>
<td width='15%'>".$venta2['fecha_venta']."</td>
<td width='15%'>".$venta2['precio_total']."</td>
<td width='10%'>".$venta2['estado_venta']."</td>
<td width='10%' align= 'center'>

<a class='btn btn-primary' target='_blank' href= './print_factura_menor.php?c=".$cod."'>
<span class = 'glyphicon glyphicon-eye-open'></span>  
</a>";

echo "&nbsp";

if ($venta2['estado_venta'] == 'CANCELADA') {
echo "<button name = 'reversar_venta_menor' value = '".$venta2['cod_factura']."' class = 'btn btn-danger' disabled><span class = 'glyphicon glyphicon glyphicon-retweet'></span>  
</button>";
}else{
echo "<button name = 'reversar_venta_menor' value = '".$venta2['cod_factura']."' class = 'btn btn-danger' ><span class = 'glyphicon glyphicon glyphicon-retweet'></span>  
</button>";
}

echo "
</td>
</tr>
";
}
?>

</tbody>
</table> 
</div>
</div>

<?php

}

?>



</form>
</body>