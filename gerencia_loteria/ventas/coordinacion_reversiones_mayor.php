<?php
require('../../template/header.php');





if (isset($_POST['reversar_venta_mayor'])) {

require('./conexion_oracle.php');


$factura = $_POST['reversar_venta_mayor'];


$busqueda_estado = mysqli_query($conn,"SELECT * FROM transaccional_ventas WHERE cod_factura = '$factura' LIMIT 1");
$o_estado = mysqli_fetch_object($busqueda_estado);
$estado = $o_estado->estado_venta;


if ($estado == 'APROBADO') {
if (mysqli_query($conn,"UPDATE transaccional_ventas SET estado_venta = 'CANCELADA' WHERE cod_factura = '$factura' ") === TRUE) {
mysqli_query($conn,"UPDATE fvp_detalles_ventas_mayor SET estado_venta = 'CANCELADA' WHERE cod_factura = '$factura' ");


////////////////////////////////////////////////
/////////// REGISTRO EN BITACORA ///////////////
$id_usuario_bitacora  = $_SESSION['id_usuario'];

$modulo_bitacora    = "VENTAS";
$tipo_mod_bitacora    = "UPDATE";
$tabla_bitacora     = "transaccional_ventas";
$descripcion_bitacora = "Cancelacion de factura loteria mayor, Codigo Factura: ".$factura;

$cod_accion       = "6";
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

}




?>

<script type="text/javascript">
/////////////////////////////////////////
/// FUNCION PARA CARGADO DE SECCIONALES //
function funcion_seleccion_nuevo(id_empresa){

}
/// FUNCION PARA CARGADO DE SECCIONALES //
///////////////////////////////////////// 
</script>

<body>

<form method="POST" autocomplete="off">



<br>

<ul class="nav nav-tabs">
 <li class="nav-item">
<a style="background-color:#ededed;" class="nav-link"  >Lotería Mayor</a>
</li>
<li class="nav-item">
<a  class="nav-link" href="./importacion_ventas_menor.php">Lotería Menor</a>
</li>
</ul>

<section style="background-color:#ededed;">
<br>
<h3 align="center"><b>GESTIÓN DE FACTURAS DE VENTA DE LOTERIA MAYOR </b></h3>
<br>
</section>



<a style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
Seleccion de Parametros
</a>





<div class="card collapse" id="collapse1" style="margin-left: 15px; margin-right: 15px;" >
<div class="card-body">



<div class="input-group">

<div class="input-group-prepend">
<div class="input-group-text">SORTEO: </div>  
</div>  


<select class="form-control"  name = "sorteo" id = 'sorteo'   style="margin-right: 5px;">
<?php
$sorteos = mysqli_query($conn,"SELECT a.id, a.no_sorteo_may, a.fecha_sorteo, a.descripcion_sorteo_may  FROM sorteos_mayores as a inner join empresas_estado_venta as b ON a.id = b.id_sorteo WHERE  b.estado_venta = 'H'  AND b.cod_producto = 1 GROUP BY b.id_sorteo ORDER BY a.id DESC ");

while ($row2 = mysqli_fetch_array($sorteos)) {
echo '<option value = "'.$row2['id'].'">No.'.$row2['no_sorteo_may'].' -- Fecha '.$row2['fecha_sorteo'].' -- '.$row2['descripcion_sorteo_may'].'</option>' ;
}
?>
</select>

</div>




<table class="table table-bordered">
<tr>
<th width="20%">SORTEO</th>  
<th width="20%">ENTIDAD</th>  
<th width="30%">ARCHIVO PLANO</th>  
<th width="10%">ACCION</th>  
</tr>  

<tr>
<td>
</td>  

<td>
<select  onchange="funcion_seleccion_nuevo(this.value,'1')" class="form-control" name="id_nueva_empresa" id = 'id_nueva_empresa'  style="margin-right: 5px;">
<option>Seleccione una opcion</option>
<?php 
$empresas = mysqli_query($conn,"SELECT * FROM empresas WHERE estado = 'activo' ");        
while ($empresa = mysqli_fetch_array($empresas)) {
echo "<option value = '".$empresa['id']."'>".$empresa['nombre_empresa']."</option>";
}
?>    
</select>      
</td>

<td>
<input class="form-control" type="file" name="importacion" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" style="margin-right: 5px;">
</td>

<td align="center">
<input type="submit" class="btn btn-success" name ="seleccionar" value="seleccionar">
</td>
</tr>

</table>

</div>
</div>






<?php
if (isset($_POST['seleccionar'])) {



$id_sorteo = $_POST['sorteo'];
$id_empresa = $_POST['s_entidad'];
$id_seccional = $_POST['s_seccional'];

$info_empresa = mysqli_query($conn,"SELECT * FROM empresas WHERE id = '$id_empresa' ");
$ob_empresa = mysqli_fetch_object($info_empresa);
$nombre_empresa = $ob_empresa->nombre_empresa;

$info_seccional = mysqli_query($conn,"SELECT * FROM fvp_seccionales WHERE id = '$id_seccional' ");
$ob_seccional = mysqli_fetch_object($info_seccional);
$nombre_seccional = $ob_seccional->nombre;


$ventas_sorteo = mysqli_query($conn,"SELECT a.estado_venta,a.fecha_venta, a.id_usuario, a.cod_factura, a.total_neto, b.codigo_empleado  FROM transaccional_ventas as a INNER JOIN pani_usuarios as b ON a.id_usuario = b.id  WHERE a.id_sorteo = $id_sorteo AND a.id_entidad = '$id_empresa' AND a.id_seccional = '$id_seccional' AND a.cod_producto = '1' ORDER BY  a.cod_factura DESC ");

if ($ventas_sorteo === false) {
echo mysqli_error();
}

?>

<div class="panel panel-primary">
<div class="panel-heading"> 
<h3 align="center">
TRANSACCIONES DE LOTERIA MAYOR <br><br> ENTIDAD  <?php echo $nombre_empresa; ?><br> <?php echo $nombre_seccional; ?>
<br>

</h3>
</div>
<div class="panel-body">
<table id="table_id1" style="width:100%" class="table table-hover table-bordered">
<thead>
  <tr>
    <th>Vendedor</th>
    <th>Factura</th>
    <th>Fecha Venta</th>
    <th>Total Pagado</th>
    <th>Estado</th>
    <th>Accion</th>
  </tr>
</thead>
<tbody>
<?php
while ($venta = mysqli_fetch_array($ventas_sorteo)) {
  $cod = $venta['cod_factura'];
echo "<tr>
<td width='25%' >".$venta['codigo_empleado']."</td>
<td width='15%'>".$venta['cod_factura']."</td>
<td width='15%'>".$venta['fecha_venta']."</td>
<td width='15%'>".$venta['total_neto']."</td>
<td width='10%'>".$venta['estado_venta']."</td>
<td width='10%' align= 'center'>

<a class='btn btn-primary' target='_blank' href= './print_factura_mayor.php?c=".$cod."'>
<span class = 'glyphicon glyphicon-eye-open'></span>  
</a>";

echo "&nbsp";

if ($venta['estado_venta'] == 'CANCELADA') {
echo "<button name = 'reversar_venta_mayor' value = '".$venta['cod_factura']."' class = 'btn btn-danger' disabled><span class = 'glyphicon glyphicon glyphicon-retweet'></span>  
</button>";
}else{
echo "<button name = 'reversar_venta_mayor' value = '".$venta['cod_factura']."' class = 'btn btn-danger' ><span class = 'glyphicon glyphicon glyphicon-retweet'></span>  
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

<br>
<br>

<?php

}

?>



</form>
</body>