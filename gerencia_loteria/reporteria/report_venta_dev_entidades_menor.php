<?php 
require("../../template/header.php"); 
date_default_timezone_set('America/Tegucigalpa');
?> 




<section style="background-color:#ededed;">
<br>
<h3 align="center"><b>REPORTE DE VENTA Y DEVOLUCION DETALLADA LOTERIA MENOR</b></h3>
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
$sorteos = mysqli_query($conn,"SELECT a.id, a.no_sorteo_men, a.fecha_sorteo, a.descripcion_sorteo_men  FROM sorteos_menores as a inner join empresas_estado_venta as b ON a.id = b.id_sorteo WHERE   b.cod_producto = 2 GROUP BY b.id_sorteo ORDER BY a.id DESC ");

while ($row2 = mysqli_fetch_array($sorteos)) {
echo '<option value = "'.$row2['id'].'">No.'.$row2['no_sorteo_men'].' -- Fecha '.$row2['fecha_sorteo'].' -- '.$row2['descripcion_sorteo_men'].'</option>' ;
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

$info_sorteo    = mysqli_query($conn, "SELECT a.estado_venta FROM empresas_estado_venta as a INNER JOIN sorteos_menores as b ON a.id_sorteo = b.id WHERE a.id_sorteo = '$id_sorteo'  LIMIT 1");
$ob_info_sorteo = mysqli_fetch_object($info_sorteo);
$estado_venta   = $ob_info_sorteo->estado_venta;


$entidades = mysqli_query($conn ,"SELECT SUM(a.cantidad) as cantidad, b.nombre_empresa, b.id, b.distribuidor   FROM facturacion_menor as a INNER JOIN empresas as b ON a.id_empresa = b.id WHERE id_sorteo = '$id_sorteo' AND a.estado =  'A' GROUP BY id_empresa ORDER BY  SUM(cantidad) ASC ");


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
			<th>Asignado Bolsas</th>
			<th>Vendido Bolsas</th>
			<th>No Vendido Bolsas</th>
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
$c_asignada = $reg_asig['cantidad']/100;


$c_venta_b = mysqli_query($conn, " SELECT SUM(t.cantidad) AS total_cantidad FROM (SELECT cantidad FROM transaccional_ventas WHERE id_sorteo = '$id_sorteo' AND id_entidad = '$id_entidad' AND estado_venta = 'APROBADO' AND cod_producto = '3' UNION ALL SELECT cantidad FROM transaccional_ventas_general WHERE id_sorteo = '$id_sorteo' AND id_entidad = '$id_entidad' AND estado_venta = 'APROBADO' AND cod_producto = '3' ) t  ");

$ob_venta_b = mysqli_fetch_object($c_venta_b);
$venta_b    = $ob_venta_b->total_cantidad;


$c_venta_n = mysqli_query($conn, " SELECT SUM(t.cantidad) AS total_cantidad FROM (SELECT cantidad FROM transaccional_ventas WHERE id_sorteo = '$id_sorteo' AND id_entidad = '$id_entidad' AND estado_venta = 'APROBADO' AND cod_producto = '2' UNION ALL SELECT cantidad FROM transaccional_ventas_general WHERE id_sorteo = '$id_sorteo' AND id_entidad = '$id_entidad' AND estado_venta = 'APROBADO' AND cod_producto = '2' ) t  ");


$ob_venta_n = mysqli_fetch_object($c_venta_n);
$venta_n    = $ob_venta_n->total_cantidad;
$venta_n = $venta_n/100;

$venta = $venta_b + $venta_n; 
$dev   = $c_asignada - $venta; 



echo "<tr>";
echo "<td>".$reg_asig['nombre_empresa']."</td>";
echo "<td>".number_format($c_asignada)."</td>";
echo "<td>".number_format($venta, 2)."</td>";
echo "<td>".number_format($dev, 2)."</td>";
echo "<td align = 'center'><a target = '_blank' href = './venta_entidades_menor_detalle.php?s=".$id_sorteo."&e=".$id_entidad."&d=".$dist."'  class = 'btn btn-info'>Generar</a></td>";
echo "<td align = 'center'><a target = '_blank' href = './dev_entidades_menor_detalle.php?s=".$id_sorteo."&e=".$id_entidad."&d=".$dist."' class = 'btn btn-success'>Generar</a></td>";
echo "</tr>";

$tt_vendido += $venta;
$tt_no_vendido += $dev;
$tt_asignado += $venta + $dev;

}


echo "
<tr>
	<th align='center' >TOTALES</th>
	<th align='center' >".number_format($tt_asignado)."</th>
	<th align='center' >".number_format($tt_vendido, 2)."</th>
	<th align='center' >".number_format($tt_no_vendido, 2)."</th>
	<th align='center' ></th>
	<th align='center' ></th>
</tr>";

?>

		
	</tbody>

</table>

</div>
</div>

<?php



}

?>


</form>