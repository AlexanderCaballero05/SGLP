<?php
require("../../conexion.php"); 

session_start();

if (isset($_GET['c'])) {
$cod_factura = $_GET['c'];
}else{
$cod_factura = $_SESSION['factura_mayor'];	
}

$factura =  mysqli_query($conn,"SELECT * FROM facturacion_mayor WHERE id = $cod_factura ");
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


$info_sorteo = mysqli_query($conn,"SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo' ");
$ob_sorteo = mysqli_fetch_object($info_sorteo);
$mezcla = $ob_sorteo->mezcla;



$info_empresa = mysqli_query($conn,"SELECT *  FROM empresas WHERE id = '$id_empresa' limit 1");
$value_e = mysqli_fetch_object($info_empresa);
$nombre_e = $value_e->nombre_empresa;
$descuento_e = $value_e->descuento_mayor;
$tipo_descuento_e = $value_e->tipo_descuento_mayor;
$rebaja_e = $value_e->rebaja_mayor;
$tipo_rebaja_e = $value_e->tipo_rebaja_mayor;



$inventario_asignado = mysqli_query($conn,"SELECT COUNT(id_empresa) as conteo FROM sorteos_mezclas as a  WHERE a.id_sorteo = '$id_sorteo' AND  a.id_empresa = '$id_empresa' ");

if ($inventario_asignado === false) {
echo mysqli_error();
}

$inventario = mysqli_fetch_object($inventario_asignado); 
$cantidad_asignada = $inventario->conteo;
$cantidad2 = $cantidad_asignada * 100;

$cantidad_total = $cantidad2;

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
echo '<p align="left">Con la presente factura remito a Ud. <ins>'.number_format($cantidad_total, 0).'</ins> billetes de la Loteria Mayor, cuyo valor ha sido cargado a su preciable cuenta, como sigue:</p>';
?>

<div align="center"  style="width:100%">
<div align="center" style=" width:100%">
<table style="width: 100%" border = '1'>
<tr>
		<th>Fecha Expedicion</th>
		<th>No. Factura</th>
		<th>No. Sorteo</th>
		<th width="12%">Fecha Sorteo</th>
		<th>Cantidad Billetes</th>
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
PAQUETES
<table border="1" width="100%" >
<tr><th width="33%">PAQUETE INICIAL</th><th width="33%">PAQUETE FINAL</th><th width="34%">CANTIDAD BILLETES</th></tr>
<tr>
<?php

$detalle_inventario = mysqli_query($conn,"SELECT MIN(num_mezcla) as minimo, MAX(num_mezcla) as maximo FROM ( SELECT a.num_mezcla , @conteo := @conteo + 1 as fila FROM sorteos_mezclas as a INNER JOIN (SELECT @conteo := 0 ) as b WHERE cod_factura = '$no_factura' ORDER BY num_mezcla ASC ) as c GROUP BY (num_mezcla - fila) ");


$m = 0;
$c = 0;
while ($mezclas = mysqli_fetch_array($detalle_inventario)) {
$cantidad = ($mezclas['maximo'] - $mezclas['minimo'] + 1 ) * 100;	
echo "<tr><td>".$mezclas['minimo']."</td><td>".$mezclas['maximo']."</td><td>".number_format($cantidad, 0)."</td></tr>";
}


?>
</table>
<br>
<table border="1" width="100%" >
<tr>
	<td width="66.66%">TOTAL</td>
	<td width="33.33%"><?php echo number_format($cantidad_total, 0);?></td>
</tr>
</table>
<br><br><br>
<table width="100%">
	<tr>
	<td width="10">Recibi Conforme:</td>
	<td width="40%"><hr></td>
	<td width="5">Tesorero:</td>
	<td width="45%"><hr></td>
	</tr>
	<tr>
	<td colspan="2"  align="center">Representante <?php echo $receptor;?></td>
	<td colspan="2" align="center" >Jose Wilfredo Quezada</td>
	</tr>

</table>

<br>
<br>

<table width="100%">
	<tr>
		<td width="30%"></td>
		<td width="40%"><hr></td>
		<td width="30%"></td>
	</tr>
	<tr>
		<td ></td>
		<td align="center">
		Oscar Orlando Rivera <br>
		Deposito de Billetes
		</td>
		<td ></td>
	</tr>
</table>
<br>

<!--
<br><br><br><br><br>
<p align="center">ACTA DE MUESTRA</p>
-->

</div>

</body>
<footer>
	
</footer>
