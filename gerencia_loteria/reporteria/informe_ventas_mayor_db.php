<?php
require('../../conexion.php');
$id_sorteo = $_GET['id_s'];
$filtro	   = $_GET['filtro'];


////////////////////////////////////////////////////
///////////// CONSULTA ASIGNACIONES ////////////////

$c_asignacion = mysqli_query($conn,"SELECT id_empresa, SUM(cantidad) as cantidad, receptor, SUM(valor_neto) + SUM(rebaja_depositario) as valor_neto FROM facturacion_mayor WHERE id_sorteo = '$id_sorteo' AND id_empresa != '3' AND estado = 'A' GROUP BY id_empresa ");

if ($c_asignacion === FALSE) {
echo mysqli_error();
}


echo "<table class = 'table table-bordered'> ";
echo "<tr class = 'alert alert-info'>";
echo "<tr class = 'alert alert-info'><th  colspan = '9' > <h3 align = 'center'><b>OTROS PUNTOS DE VENTA Y REGIONALES </b></h3></th></tr>";
echo "</tr>";
echo "<tr class = 'alert alert-info'>";
echo "<th style = 'width:10%' >ENTIDAD</th>";
echo "<th style = 'width:10%' >BILLETES ASIGNADOS</th>";
echo "<th style = 'width:10%' >BILLETES VENDIDOS</th>";
echo "<th style = 'width:10%' >BILLETES DEVUELTOS</th>";
echo "<th style = 'width:10%' >PRECIO DE VENTA</th>";
echo "<th style = 'width:10%' >TOTAL BRUTO</th>";
echo "<th style = 'width:10%' >DESCUENTO</th>";
echo "<th style = 'width:10%' >COMISION BANCARIA</th>";
echo "<th style = 'width:10%' >CREDITO PANI</th>";
echo "</tr>";


$tt_asignacion = 0;
$tt_venta 	   = 0;
$tt_devolucion = 0;
$tt_bruto 	   = 0;
$tt_descuento  = 0;
$tt_comision   = 0;
$tt_credito    = 0;

$concatenado_porcentaje_venta = '';
$concatenado_asociaciones 	  = '';

$total_general_venta = 0;

while ($reg_asignacion = mysqli_fetch_array($c_asignacion)) {
$id_entidad = $reg_asignacion['id_empresa'];
$valor_neto = $reg_asignacion['valor_neto'];

$precio_uni = $reg_asignacion['valor_neto']/$reg_asignacion['cantidad'];

////////////////////////////////////////////////////
////////////// CONSULTA DE VENTAS //////////////////

$c_ventas = mysqli_query($conn,"SELECT precio_unitario ,SUM(aportacion) as aportacion, SUM(cantidad) as venta, SUM(credito_pani) as credito, SUM(total_bruto) as total_bruto, SUM(comision_bancaria) as comision , SUM(descuento) as descuento  FROM transaccional_ventas WHERE estado_venta = 'APROBADO' AND id_sorteo = '$id_sorteo' AND id_entidad = '$id_entidad' AND cod_producto = 1 ");

$ob_ventas = mysqli_fetch_object($c_ventas);
$ventas_entidad  = $ob_ventas->venta;
$credito_entidad = $ob_ventas->credito;
$dev_entidad     = $reg_asignacion['cantidad'] - $ventas_entidad;
$venta_entidad_l = $ventas_entidad * $precio_uni;
$precio_unitario = $ob_ventas->precio_unitario;
$total_bruto 	 = $ob_ventas->total_bruto;
$descuento 		 = $ob_ventas->descuento;
$comision 		 = $ob_ventas->comision;
$credito 		 = $ob_ventas->credito;

////////////// CONSULTA DE VENTAS //////////////////
////////////////////////////////////////////////////

if ($ventas_entidad == 0) {
$porcentaje_venta = 0;
}else{
$porcentaje_venta = $ventas_entidad/$reg_asignacion['cantidad'];	
}

$porcentaje_venta = $porcentaje_venta * 100;
$concatenado_porcentaje_venta = $concatenado_porcentaje_venta.",".number_format($porcentaje_venta,"2");
$concatenado_asociaciones     = $concatenado_asociaciones.",".$reg_asignacion['receptor'];
echo "<tr>";
echo "<td>".$reg_asignacion['receptor']."</td>";
echo "<td>".number_format($reg_asignacion['cantidad'])."</td>";
echo "<td>".number_format($ventas_entidad)."</td>";
echo "<td>".number_format($dev_entidad)."</td>";
echo "<td>".number_format($precio_unitario,"2")."</td>";
echo "<td>".number_format($total_bruto,"2")."</td>";
echo "<td>".number_format($descuento,"2")."</td>";
echo "<td>".number_format($comision,"2")."</td>";
echo "<td>".number_format($credito,"2")."</td>";
echo "</tr>";

$tt_asignacion += $reg_asignacion['cantidad'];
$tt_venta 	   += $ventas_entidad;
$tt_devolucion += $dev_entidad;
$tt_bruto 	   += $total_bruto;
$tt_descuento  += $descuento;
$tt_comision   += $comision;
$tt_credito    += $credito;

}

echo "<tr>";
echo "<th> TOTALES </th>";
echo "<th>".number_format($tt_asignacion)."</th>";
echo "<th>".number_format($tt_venta)."</th>";
echo "<th>".number_format($tt_devolucion)."</th>";
echo "<th></th>";
echo "<th>".number_format($tt_bruto,"2")."</th>";
echo "<th>".number_format($tt_descuento,"2")."</th>";
echo "<th>".number_format($tt_comision,"2")."</th>";
echo "<th>".number_format($tt_credito,"2")."</th>";
echo "</tr>";

echo "</table>";

$total_general_venta += $tt_credito;
///////////// CONSULTA ASIGNACIONES ////////////////
////////////////////////////////////////////////////



/////////////// BANRURAL ASIGNACION/VENTA ///////////////
/////////////// BANRURAL ASIGNACION/VENTA ///////////////
/////////////// BANRURAL ASIGNACION/VENTA ///////////////
/////////////// BANRURAL ASIGNACION/VENTA ///////////////



echo "<br>";


if ($filtro == 1 ) {
////////////////////////////////////////////////////
///////////// CONSULTA ASIGNACIONES ////////////////

$c_asignacion = mysqli_query($conn,"SELECT id_empresa, SUM(cantidad) as cantidad, receptor, SUM(valor_neto) + SUM(rebaja_depositario) as valor_neto FROM facturacion_mayor WHERE id_sorteo = '$id_sorteo' AND id_empresa = '3' AND estado = 'A' GROUP BY id_empresa ");

if ($c_asignacion === FALSE) {
echo mysqli_error();
}

echo "<table class = 'table table-bordered'> ";
echo "<tr class = 'alert alert-info'><th  colspan = '9' > <h3 align = 'center'><b>BANRURAL </b></h3></th></tr>";
echo "<tr class = 'alert alert-info'>";
echo "<th style = 'width:10%' >ENTIDAD</th>";
echo "<th style = 'width:10%' >BILLETES ASIGNADOS</th>";
echo "<th style = 'width:10%' >BILLETES VENDIDOS</th>";
echo "<th style = 'width:10%' >BILLETES DEVUELTOS</th>";
echo "<th style = 'width:10%' >PRECIO DE VENTA</th>";
echo "<th style = 'width:10%' >TOTAL BRUTO</th>";
echo "<th style = 'width:10%' >DESCUENTO</th>";
echo "<th style = 'width:10%' >COMISION BANCARIA</th>";
echo "<th style = 'width:10%' >CREDITO PANI</th>";
echo "</tr>";


$tt_asignacion = 0;
$tt_venta 	   = 0;
$tt_devolucion = 0;
$tt_bruto 	   = 0;
$tt_descuento  = 0;
$tt_comision   = 0;
$tt_credito    = 0;

$ob_asignacion = mysqli_fetch_object($c_asignacion);
$id_entidad    = $ob_asignacion->id_empresa;
$cantidad_asig = $ob_asignacion->cantidad;
$receptor      = $ob_asignacion->receptor;

////////////////////////////////////////////////////
////////////// CONSULTA DE VENTAS //////////////////

$c_ventas = mysqli_query($conn,"SELECT SUM(cantidad) as venta, SUM(credito_pani) as credito, cod_producto ,precio_unitario, SUM(total_bruto) as bruto ,   SUM(comision_bancaria) as comision ,   SUM(descuento) as descuento , SUM(credito_pani) as credito FROM transaccional_ventas_general WHERE estado_venta = 'APROBADO' AND id_sorteo = '$id_sorteo' AND id_entidad = '$id_entidad' AND cod_producto IN (1) GROUP BY cod_producto ORDER BY cod_producto ASC ");

if ($c_ventas === FALSE) {
echo mysqli_error();
}

////////////// CONSULTA DE VENTAS //////////////////
////////////////////////////////////////////////////

$cantidad_bolsa = 0;
if (mysqli_num_rows($c_ventas) > 0) {
while ($r_ventas = mysqli_fetch_array($c_ventas)) {

$ventas_banco 	 = $r_ventas['venta'];
$precio_banco    = $r_ventas['precio_unitario'];
$bruto_banco     = $r_ventas['bruto'];
$comision_banco  = $r_ventas['comision'];
$descuento_banco = $r_ventas['descuento'];
$credito_banco   = $r_ventas['credito'];

}

$devolu_banco    = $cantidad_asig - $ventas_banco; 

if ($ventas_banco == 0) {
$porcentaje_venta = 0;
}else{
$porcentaje_venta = $ventas_banco/$cantidad_asig;	
}

$porcentaje_venta = $porcentaje_venta * 100;
$concatenado_porcentaje_venta = $concatenado_porcentaje_venta.",".number_format($porcentaje_venta,'2');
$concatenado_asociaciones     = $concatenado_asociaciones.",BANRURAL";

echo "<tr>";
echo "<th>Todas las Agencias</th>";
echo "<th>".number_format($cantidad_asig)."</th>";
echo "<th>".number_format($ventas_banco)."</th>";
echo "<th>".number_format($devolu_banco)."</th>";
echo "<th>".number_format($precio_banco, "2")."</th>";
echo "<th>".number_format($bruto_banco, "2")."</th>";
echo "<th>".number_format($descuento_banco, "2")."</th>";
echo "<th>".number_format($comision_banco, "2")."</th>";
echo "<th>".number_format($credito_banco, "2")."</th>";
echo "</tr>";

$tt_asignacion = $cantidad_asig;
$tt_venta 	   = $ventas_banco;
$tt_devolucion = $devolu_banco;
$tt_bruto 	   = $bruto_banco;
$tt_descuento  = $descuento_banco;
$tt_comision   = $comision_banco;
$tt_credito    = $credito_banco;

}


echo "</table>";

$total_general_venta += $tt_credito;
///////////// CONSULTA ASIGNACIONES ////////////////
////////////////////////////////////////////////////

}


echo "<br>";

/////////////////////////////////////////////////////
////////////////////// PREMIOS //////////////////////
/////////////////////////////////////////////////////

?>

<table width="100%">
<tr>
<td width="49%" valign="top">


<?php 

$premios_mayores = mysqli_query($conn,"SELECT a.premios_mayores_id, a.numero_premiado_mayor, a.monto, a.respaldo, b.descripcion_premios FROM sorteos_mayores_premios as a INNER JOIN premios_mayores as b ON a.premios_mayores_id = b.id WHERE a.sorteos_mayores_id  = '$id_sorteo' AND a.numero_premiado_mayor IS NOT NULL  AND a.premios_mayores_id NOT IN (9,10,11,12) ORDER BY a.premios_mayores_id ASC, a.monto DESC ");


echo "<table class = 'table table-bordered' width = '100' valign='top'>";
echo "<tr class = 'alert alert-info'><th colspan = '3'><h3 align = 'center'><b>BILLETES PREMIADOS VENDIDOS </b></h3></th></tr>";

echo "<tr>";
echo "<th>Premio</th>";
echo "<th>Billete</th>";
echo "<th>Monto</th>";
echo "</tr>";

$tt_p = 0;
while ($reg_premios_mayores = mysqli_fetch_array($premios_mayores)){

$id 	  = $reg_premios_mayores['premios_mayores_id'];
$billete  = $reg_premios_mayores['numero_premiado_mayor'];
$descrip  = $reg_premios_mayores['descripcion_premios'];
$monto 	  = $reg_premios_mayores['monto'];
$respaldo = $reg_premios_mayores['respaldo'];


if ($id == 1) {
$billete_premio_mayor = $billete; 
}

$verificar_venta = mysqli_query($conn," SELECT (SELECT COUNT(billete) FROM fvp_detalles_ventas_mayor WHERE id_sorteo = '$id_sorteo' AND billete = '$billete' AND estado_venta = 'APROBADO' ) AS conteo1, (SELECT COUNT(billete) FROM transaccional_mayor_banco_detalle WHERE id_sorteo = '$id_sorteo' AND billete = '$billete' AND estado_venta = 'APROBADO' ) AS conteo2 ");

if ($verificar_venta === FALSE ) {
echo mysqli_error();
}

$ob_verificar_venta = mysqli_fetch_object($verificar_venta);
$conteo1 = $ob_verificar_venta->conteo1;
$conteo2 = $ob_verificar_venta->conteo2;

if ($conteo1 > 0 OR $conteo2 > 0) {
echo "<tr>";
echo "<td>".$descrip."</td>";
echo "<td>".$billete."</td>";
echo "<td>".number_format($monto,'2')."</td>";
echo "</tr>";

$tt_p +=  $monto;
}

}



echo "<tr><th colspan = '2' >TOTAL</th><th>".number_format($tt_p,'2')."</th></tr>";

echo "</table>";


?>

</td>
<td width="2%"></td>
<td width="49%" valign="top">
	
<?php 

//////////////////////////////////////////////////////////////////////////////
//////////////////////// 

?>

<table width="100%" class="table table-bordered">
	<tr class = 'alert alert-info'><th colspan = '4'><h3 align = 'center'><b>PAGO POR TERMINACIONES </b></h3></th></tr>
	<tr><th>Terminacion</th><th>Cantidad</th><th>Premio</th><th>Monto a Pagar</th></tr>

<?php 

$consulta_terminaciones = mysqli_query($conn,"SELECT pago_terminacion as premio ,SUM(pago_terminacion) as monto_total, count(total) as conteo FROM `archivo_pagos_mayor` WHERE sorteo = '$id_sorteo' AND pago_terminacion > 0 GROUP BY pago_terminacion ORDER BY pago_terminacion asc limit 4 ");

$i = 1;
$total_terminaciones = 0;
while ($reg_terminaciones = mysqli_fetch_array($consulta_terminaciones)) {

echo "<tr>
<td>Terminacion ".$i."</td>
<td>".number_format($reg_terminaciones['conteo'])."</td>
<td>".$reg_terminaciones['premio']."</td>
<td>".number_format($reg_terminaciones['monto_total'], "2") ."</td>
</tr>
";


$total_terminaciones += $reg_terminaciones['monto_total'];
$i++;
}
echo "<tr><th colspan = '3'>TOTAL</th><th>".number_format( $total_terminaciones, "2")."</th></tr>";

$tt_p += $total_terminaciones;
$utilidad_perdida = $total_general_venta - $tt_p;

?>

</table>


</td>
</tr>
</table>


<br>

<table width="100%">
<tr>

<td width="25%"></td>

<td width="50%">
	
<table class="table table-bordered">
	<tr>
		<th colspan="2" class="alert alert-info"><h3 align="center"><b> RESUMEN DE INGRESOS  </b></h3></th>
	</tr>
	<tr>
		<td>TOTAL INGRESOS POR VENTA</td>
		<td><?php echo number_format($total_general_venta,"2") ?></td>
	</tr>
	<tr>
		<td>TOTAL PROVISION PAGO</td>
		<td><?php echo number_format($tt_p,"2") ?></td>
	</tr>


<?php
if ( $utilidad_perdida < 0) {
?>

	<tr class="alert alert-danger">
		<td>UTILIDAD O PERDIDA SORTEO <?php echo $id_sorteo; ?></td>
		<td><?php echo number_format($utilidad_perdida,"2") ?></td>
	</tr>

<?php 
}else{
?>
	<tr class="alert alert-success">
		<td>UTILIDAD O PERDIDA SORTEO <?php echo $id_sorteo; ?></td>
		<td><?php echo number_format($utilidad_perdida,"2") ?></td>
	</tr>
<?php 
}

$consulta_info_sorteo = mysqli_query($conn,"SELECT fecha_sorteo FROM sorteos_mayores WHERE id = '$id_sorteo'  ");
$ob_info_sorteo = mysqli_fetch_object($consulta_info_sorteo);
$f = $ob_info_sorteo->fecha_sorteo;

$v_f = explode("-", $f);
$year = $v_f[0];

if ($filtro == 1) {
$consulta_acumulado = mysqli_query($conn,"SELECT  SUM(a.utilidad_perdida) as utilidad_perdida FROM utilidades_perdidas_sorteos as a INNER JOIN sorteos_mayores as b INNER JOIN empresas as c ON a.id_sorteo = b.id AND a.id_entidad = c.id WHERE a.id_sorteo <= '$id_sorteo'  AND YEAR(b.fecha_sorteo) = '$year' AND a.tipo_loteria = 1 ");
$ob_consulta_camulado = mysqli_fetch_object($consulta_acumulado);
$acumulado = $ob_consulta_camulado->utilidad_perdida;
}elseif($filtro == 2){
$consulta_acumulado = mysqli_query($conn,"SELECT  SUM(a.utilidad_perdida) as utilidad_perdida FROM utilidades_perdidas_sorteos as a INNER JOIN sorteos_mayores as b INNER JOIN empresas as c ON a.id_sorteo = b.id AND a.id_entidad = c.id WHERE c.distribuidor = 'NO' AND a.id_sorteo <= '$id_sorteo'  AND YEAR(b.fecha_sorteo) = '$year' AND a.tipo_loteria = 1 ");	
$ob_consulta_camulado = mysqli_fetch_object($consulta_acumulado);
$acumulado = $ob_consulta_camulado->utilidad_perdida;
}elseif ($filtro == 3) {
$consulta_acumulado = mysqli_query($conn,"SELECT  SUM(a.utilidad_perdida) as utilidad_perdida FROM utilidades_perdidas_sorteos as a INNER JOIN sorteos_mayores as b INNER JOIN empresas as c ON a.id_sorteo = b.id AND a.id_entidad = c.id WHERE c.distribuidor = 'SI' AND a.id_sorteo <= '$id_sorteo'  AND YEAR(b.fecha_sorteo) = '$year' AND a.tipo_loteria = 1 ");	
$ob_consulta_camulado = mysqli_fetch_object($consulta_acumulado);
$acumulado = $ob_consulta_camulado->utilidad_perdida;
}


if ($acumulado < 0) {
echo "<tr class = 'alert alert-danger'>";
}else{
echo "<tr class = 'alert alert-success'>";	
}
?>
	
<td>UTLIDAD ACUMULADA AÑO <?php echo $year; ?></td>
<td><?php echo number_format($acumulado,"2") ?></td>
</tr>

</table>


</td>

<td width="25%">

</td>
</tr>
</table>


<table width="100%">
	<tr>
		<td width="50%">

<span style = 'width:100%' class = 'btn btn-success' onclick = "generar_u('<?php echo $total_general_venta;?>','<?php echo $tt_p;?>')" id = 'non-printable'>
GENERAR GRAFICO DE UTILIDADES
</span>

<div class="well" style=" width:100%;height:50px">
<canvas style = 'display:none' id="myChart_u" width="600" height="250"></canvas>	
</div>
			
		</td>
		<td width="50%">
			
<span style = 'width:100%' class = 'btn btn-info' onclick = "generar('<?php echo $concatenado_asociaciones;?>','<?php echo $concatenado_porcentaje_venta;?>')" id = 'non-printable'>
GENERAR GRAFICO COMPARATIVO DE VENTAS
</span>

<div class="" style=" width:100%;height:50px">
<canvas style = 'display:none' id="myChart" width="600" height="250"></canvas>	
</div>

		</td>

	</tr>
</table>

<br>

<script>
$(".div_wait").fadeOut("fast");
</script>