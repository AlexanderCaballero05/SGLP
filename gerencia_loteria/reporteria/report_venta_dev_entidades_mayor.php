<?php 
require("../../template/header.php"); 
date_default_timezone_set('America/Tegucigalpa');
?> 




<section style="background-color:#ededed;">
<br>
<h3 align="center"><b>REPORTE DE VENTA Y DEVOLUCION DETALLADA LOTERIA MAYOR</b></h3>
<br>
</section>



<a  id = 'non-printable' style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
Seleccion de Parametros
</a>

<form method="POST">

<div class="card collapse" id="collapse1" style="margin-left: 250px; margin-right: 250px;" >
<div class="card-body">

<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">Sorteo:</div>
</div>	

<select class="form-control"  name = "s_sorteo" id = 's_sorteo' ">
<?php
$sorteos = mysqli_query($conn,"SELECT a.id, a.no_sorteo_may, a.fecha_sorteo, a.descripcion_sorteo_may  FROM sorteos_mayores as a inner join empresas_estado_venta as b ON a.id = b.id_sorteo WHERE   b.cod_producto = 1 GROUP BY b.id_sorteo ORDER BY a.id DESC ");

while ($row2 = mysqli_fetch_array($sorteos)) {
echo '<option value = "'.$row2['id'].'">No.'.$row2['no_sorteo_may'].' -- Fecha '.$row2['fecha_sorteo'].' -- '.$row2['descripcion_sorteo_may'].'</option>' ;
}
?>
</select>


<div class="input-group-append">
	<button type="submit" name="seleccionar" id="seleccionar" class="btn btn-primary">Seleccionar</button>
</div>

</div>

</div>
</div>


<?php 


if (isset($_POST['seleccionar'])) {


$id_sorteo = $_POST['s_sorteo'];

$info_sorteo    = mysqli_query($conn, "SELECT a.estado_venta, b.mezcla FROM empresas_estado_venta as a INNER JOIN sorteos_mayores as b ON a.id_sorteo = b.id WHERE a.id_sorteo = '$id_sorteo'  LIMIT 1");
$ob_info_sorteo = mysqli_fetch_object($info_sorteo);
$estado_venta   = $ob_info_sorteo->estado_venta;
$mezcla         = $ob_info_sorteo->mezcla;


$entidades = mysqli_query($conn ,"SELECT SUM(a.cantidad) as cantidad, b.nombre_empresa, b.id, b.distribuidor   FROM facturacion_mayor as a INNER JOIN empresas as b ON a.id_empresa = b.id WHERE id_sorteo = '$id_sorteo' AND a.estado =  'A' GROUP BY id_empresa ORDER BY  SUM(cantidad) ASC ");


?>

<br>

<div class="card" style="margin-left: 10px; margin-right: 10px">
<div class="card-header bg-secondary text-white">
<h4 align="center"> SORTEO <?php echo $id_sorteo; ?></h4>
</div>	

<div class="card-body">

<table class = 'table table-bordered table-hover'>
	<thead>
		<tr>
			<th>Entidad</th>
			<th>Asignado</th>
			<th>Vendido</th>
			<th>No Vendido</th>
			<th>Acta de Venta</th>
			<th>Acta de Trituración</th>
		</tr>
	</thead>

	<tbody>

<?php 

$tt_vendido = 0;
$tt_no_vendido = 0;
$tt_asignado = 0;

while ($reg_asig = mysqli_fetch_array($entidades)) {


$id_entidad = $reg_asig['id'];
$dist       = $reg_asig['distribuidor'];

$c_venta = mysqli_query($conn, " SELECT SUM(t.cantidad) AS total_cantidad FROM (SELECT cantidad FROM transaccional_ventas WHERE id_sorteo = '$id_sorteo' AND id_entidad = '$id_entidad' AND estado_venta = 'APROBADO' AND cod_producto = '1' UNION ALL SELECT cantidad FROM transaccional_ventas_general WHERE id_sorteo = '$id_sorteo' AND id_entidad = '$id_entidad' AND estado_venta = 'APROBADO' AND cod_producto = '1' ) t  ");

$ob_venta = mysqli_fetch_object($c_venta);
$venta    = $ob_venta->total_cantidad;
$dev      = $reg_asig['cantidad'] - $venta;

echo "<tr>";
echo "<td>".$reg_asig['nombre_empresa']."</td>";
echo "<td>".number_format($reg_asig['cantidad'])."</td>";
echo "<td>".number_format($venta)."</td>";
echo "<td>".number_format($dev)."</td>";
echo "<td align = 'center'><a target = '_blank' href = './venta_entidades_mayor_detalle.php?s=".$id_sorteo."&e=".$id_entidad."&d=".$dist."'  class = 'btn btn-info'>Generar</a></td>";
echo "<td align = 'center'><a target = '_blank' href = './dev_entidades_mayor_detalle.php?s=".$id_sorteo."&e=".$id_entidad."&d=".$dist."' class = 'btn btn-success'>Generar</a></td>";
echo "</tr>";


$tt_vendido += $venta;
$tt_no_vendido += $dev;
$tt_asignado += $reg_asig['cantidad'];

}


echo "
<tr>
	<th align='center' >TOTALES</th>
	<th align='center' >".number_format($tt_asignado)."</th>
	<th align='center' >".number_format($tt_vendido)."</th>
	<th align='center' >".number_format($tt_no_vendido)."</th>
	<th align='center' ></th>
	<th align='center' ></th>
</tr>";


$c_no_asignada = mysqli_query($conn, "SELECT COUNT(num_mezcla) * 100 as cantidad_no_asignada FROM sorteos_mezclas WHERE id_sorteo = '$id_sorteo' AND id_empresa IS NULL ");

if (mysqli_num_rows($c_no_asignada) > 0) {

$ob_no_asignada = mysqli_fetch_object($c_no_asignada);
$cantidad_no_asignada =  $ob_no_asignada->cantidad_no_asignada;

echo "
<tr>
	<td align='center' >LOTERIA NO ASIGNADA</td>
	<td align='center' colspan='3'>".number_format($cantidad_no_asignada)."</td>
	<td align='center' ></td>
	<td align='center' ><a target = '_blank' href = './no_asignado_mayor_detalle.php?s=".$id_sorteo."' class = 'btn btn-success'>Generar</a></td>
</tr>";



}else{

$cantidad_no_asignada = 0;

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