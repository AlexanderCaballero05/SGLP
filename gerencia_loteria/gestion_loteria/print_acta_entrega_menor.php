<?php
require("../../conexion.php"); 

session_start();

if (isset($_GET['c'])) {
$cod_factura = $_GET['c'];
}else{
$cod_factura = $_SESSION['factura_menor'];	
}

$factura =  mysqli_query($conn,"SELECT * FROM facturacion_menor WHERE id = $cod_factura ");
$ob_factura = mysqli_fetch_object($factura);
$receptor = $ob_factura->receptor;
$id_empresa = $ob_factura->id_empresa; 
$fecha_expedicion = $ob_factura->fecha_expedicion; 

date_default_timezone_set('America/Tegucigalpa');
$fecha_actual = date("Y-m-d");
$v_fecha = explode('-', $fecha_actual);

$y = $v_fecha[0];
$m = $v_fecha[1];
$d = $v_fecha[2];

$no_factura = $ob_factura->no_factura; 
$id_sorteo = $ob_factura->id_sorteo; 
$fecha_sorteo = $ob_factura->fecha_sorteo; 
$valor_nominal = $ob_factura->valor_nominal; 
$descuento = $ob_factura->descuento; 
$rebaja_depositario = $ob_factura->rebaja_depositario; 
$valor_neto = $ob_factura->valor_neto; 



$info_empresa = mysqli_query($conn,"SELECT *  FROM empresas WHERE id = '$id_empresa' limit 1");
$value_e = mysqli_fetch_object($info_empresa);
$nombre_e = $value_e->nombre_empresa;
$descuento_e = $value_e->descuento_menor;
$tipo_descuento_e = $value_e->tipo_descuento_menor;
$rebaja_e = $value_e->rebaja_menor;
$tipo_rebaja_e = $value_e->tipo_rebaja_menor;



$inventario_asignado = mysqli_query($conn,"SELECT MIN(serie_inicial) as minimo, MAX(serie_final) as maximo, SUM(cantidad) as cantidad FROM menor_seccionales_bolsas  WHERE id_sorteo = '$id_sorteo'  AND id_empresa = '$id_empresa' AND cod_factura = '$no_factura'  ");

if ($inventario_asignado === false) {
echo mysqli_error();
}

$inventario = mysqli_fetch_object($inventario_asignado); 
$cantidad_asignada_bolsas = $inventario->cantidad;
$min_serie = $inventario->minimo;
$max_serie = $inventario->maximo;
$cantidad2 = $cantidad_asignada_bolsas * 100;

$inventario_asignado2 = mysqli_query($conn,"SELECT SUM(a.cantidad) as cantidad2 FROM menor_seccionales_numeros as a WHERE a.id_sorteo = '$id_sorteo' AND  a.id_empresa = '$id_empresa' AND a.cod_factura = '$no_factura' ");


$inventario2 = mysqli_fetch_object($inventario_asignado2); 
$cantidad_asignada_numeros = $inventario2->cantidad2;

$cantidad_total = $cantidad_asignada_numeros + $cantidad2;

?>

<style type="text/css">
</style>

<body >

<div id="Imprime" >

<table  width="100%">
	<tr>
		<td width="25%" style="vertical-align: top">
			<img src="../../template/images/logo-republica.png" width="100%">
		</td>
		<td width="50%">
			<h3 align = 'center'> PATRONATO NACIONAL DE LA INFANCIA </h3>
				<p align="center"> REPUBLICA DE HONDURAS</p>

		</td>
		<td width="25%" style="vertical-align: top">
			<img src="../../template/images/logo-pani.png" width="100%">			
		</td>
	</tr>
</table>


<P align = 'right'><b>FACTURA No:<ins><?php echo $id_sorteo." - ".$no_factura ?></ins></b></P>
<P align = 'right'>Tegucigalpa, M.D.C.,:<ins><?php echo $d ?></ins> de <ins><?php echo $m;?></ins> del <ins>
<?php echo $y;?></ins></b></P>
<p align="left">
Señor: <ins><?php echo $receptor;?></ins> Forma de Envio: <ins>Personal</ins>
</p>

<?php
echo '<p align="left">Con la presente factura remito a Ud. <ins>'.number_format($cantidad_total, 0).'</ins> billetes de la Loteria Menor, cuyo valor ha sido cargado a su preciable cuenta, como sigue:</p>';
?>

<div align="center"  style="width:100%">
<div align="center" style=" width:100%">
<table style="width: 100%" border = '1'>
<tr>
		<th>Fecha Expedicion</th>
		<th>No. Factura</th>
		<th>No. Sorteo</th>
		<th width="12%">Fecha Sorteo</th>
		<th>Cantidad</th>
		<th>Valor Nominal</th>
		<th>
		Descuento
<p style="font-size: 12px"> 		
<?php 
if ($tipo_descuento_e == 1) {
echo $descuento_e." Lps.";
}else{
echo $descuento_e." %";	
}
?>
</p>
		</th>
		<th>
		Rebaja Depositarios
<p style="font-size: 12px"> 		
<?php 
if ($tipo_rebaja_e == 1) {
echo $rebaja_e." Lps.";
}else{
echo $rebaja_e." %";	
}
?>
</p>
		</th>
		<th>Valor Neto</th>
</tr>
<tr>
<td style="font-size: 12px"><?php echo $fecha_expedicion;?></td>
<td style="font-size: 12px"><?php echo $no_factura;?></td>
<td style="font-size: 12px"><?php echo $id_sorteo;?></td>
<td style="font-size: 12px"><?php echo $fecha_sorteo;?></td>
<td style="font-size: 12px"><?php echo number_format($cantidad_total, 0);?></td>
<td style="font-size: 12px"><?php echo number_format($valor_nominal, 2);?></td>
<td style="font-size: 12px"><?php echo number_format($descuento, 2);?></td>
<td style="font-size: 12px"><?php echo number_format($rebaja_depositario, 2);?></td>
<td style="font-size: 12px"><?php echo number_format($valor_neto, 2);?></td>
</tr>
</table>
</div>
</div>

<br>
DETALLE DE SERIES
<table border="1" width="100%" >
<tr>
	<th width="33.33%">Numeros</th>
	<th width="33.33%">Series</th>
	<th width="33.33%">Cantidad Billetes</th>
</tr>
<?php
$cantidad = 0;


if (isset($cantidad_asignada_bolsas)) {

$detalle_inventario_bolsas =  mysqli_query($conn,"SELECT serie_inicial, serie_final, (cantidad*100) as cantidad FROM menor_seccionales_bolsas  WHERE id_sorteo = '$id_sorteo'  AND id_empresa = '$id_empresa' AND cod_factura = '$no_factura'  ");

while ($reg_inventario_bolsas = mysqli_fetch_array($detalle_inventario_bolsas)) {

echo "<tr>";
echo "<td>00 - 99</td>";
echo "<td>".$reg_inventario_bolsas['serie_inicial']." - ".$reg_inventario_bolsas['serie_final']."</td>";
echo "<td>".number_format($reg_inventario_bolsas['cantidad'], 0)."</td>";
echo "</tr>";

}

}




$detalle_inventario_bolsas_numeros =  mysqli_query($conn,"SELECT DISTINCT(serie_inicial) as serie_inicial, serie_final, (cantidad*100) as cantidad FROM menor_seccionales_numeros  WHERE id_sorteo = '$id_sorteo'  AND id_empresa = '$id_empresa' AND cod_factura = '$no_factura' AND origen = 'bolsas'  ");


while ($reg_inventario_bolsas_numeros = mysqli_fetch_array($detalle_inventario_bolsas_numeros)) {

echo "<tr>";
echo "<td>00 - 99</td>";
echo "<td>".$reg_inventario_bolsas_numeros['serie_inicial']." - ".$reg_inventario_bolsas_numeros['serie_final']."</td>";
echo "<td>".number_format($reg_inventario_bolsas_numeros['cantidad'], 0)."</td>";
echo "</tr>";

}



if (isset($cantidad_asignada_numeros)) {

$inventario_asignado2_detalle_bolsas = mysqli_query($conn,"SELECT * FROM menor_seccionales_numeros as a  WHERE a.id_sorteo = '$id_sorteo' AND  a.id_empresa = '$id_empresa' AND a.cod_factura = '$no_factura' AND a.origen = 'numeros' ORDER BY numero ASC, serie_inicial ASC  ");
if ($inventario_asignado2_detalle_bolsas === false) {
echo mysqli_error();
}

while ($inventario_detalle = mysqli_fetch_array($inventario_asignado2_detalle_bolsas)) {
echo "<tr style = ' page-break-inside: avoid;'>";
echo "<td>".$inventario_detalle['numero']."</td>";
echo "<td>".$inventario_detalle['serie_inicial']." - ".$inventario_detalle['serie_final']."</td>";
echo "<td>".$inventario_detalle['cantidad']."</td>";
echo "</tr>";
$cantidad = $cantidad + $inventario_detalle['cantidad'];
}

}

?>
</table>
<br>
<table border="1" width="100%" >
<tr>
	<td width="66.66%">TOTAL</td>
	<td width="33.33%"><?php  echo number_format( $cantidad_total, 0);?></td>
</tr>
</table>
<br><br><br>

<!--
<table width="100%">

	<tr>
	<td width="14">Entrega Por <br> Control de Calidad</td>
	<td width="35%" align="center"><hr> Tarquino Santos</td>
	<td width="2%"></td>
	<td width="14">Recibe Por <br> Boveda</td>
	<td width="35%" align="center"><hr> Lisandro Canaca</td>
	</tr>

</table>
<br><br><br>

<table width="100%">

	<tr>
	<td width="14">Entrega Por <br> Tesoreria</td>
	<td width="35%" align="center"><hr> Jose Wilfredo Quezada</td>
	<td width="2%"></td>
	<td width="14">Recibe Conforme <br> </td>
	<td width="35%" align="center"><hr> Representante <?php //echo $receptor;?> </td>
	</tr>

</table>
-->


<table width="100%">

	<tr>
	<td width="14">Recibi <br> Conforme</td>
	<td width="35%" align="center"><hr> Representante <?php echo $receptor;?></td>
	<td width="2%"></td>
	<td width="8">Tesorero <br> </td>
	<td width="41%" align="center"><hr> Jose Wilfredo Quezada </td>
	</tr>

</table>


<br><br>
<p align="left"></p>

</div>

</body>
<footer>
	
</footer>

