<?php 
require('./template/header.php');
require('./_excel_reporte_ventas_mayor_agencia.php');

//REPORTE VENTAS


$sorteos = mysql_query("SELECT * FROM sorteos_mayores   ORDER BY no_sorteo_may DESC ");

?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PANI</title>     

<link href="./dates/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css">
<script src="./dates/moment.min.js"></script>
<script src="./dates/bootstrap-datetimepicker.min.js"></script>

<style type="text/css">
	    @media print
    {
        #non-printable { display: none; }
        #printable { display: block; }
    }
</style>

<script type="text/javascript">
$(function () {
$('#datetimepicker1').datetimepicker({
maxDate: moment().add(1, 'days')
});
});

$(function () {
$('#datetimepicker2').datetimepicker({
maxDate: moment().add(1, 'days')
});
});

function cambiar_icon(id){
if ( document.getElementById("span"+id).className == "glyphicon glyphicon-chevron-down" ){
document.getElementById("span"+id).className = "glyphicon glyphicon-chevron-up"
}else{
document.getElementById("span"+id).className = "glyphicon glyphicon-chevron-down"
}
}

</script>


</head>

<body>

<ul id="non-printable" class="nav nav-tabs nav-justified">
  <li class="active"  ><a data-toggle="tab"  href="./_tabla_ventas_mayor.php" >Loteria Mayor</a></li>
  <li ><a  href="./_tabla_ventas_menor.php">Loteria Menor</a></li>
</ul>

<div class="tab-content">
  <div id="home" class="tab-pane fade in active">

<form method="post">
<br>
<div id="non-printable" class="alert alert-info">
  <h3 align="center">Reporte de Ventas de Lotería Mayor</h3>

<a style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
  Seleccion de Parametros
</a>

<div class="collapse" id="collapse1">
<div class="well">
<table class="table table-bordered">
    <tr>
        <th width="20%">Sorteo</th>
        <th width="20%">Entidad</th>
        <th width="15%">Fecha Inicial</th>
        <th width="15%">Fecha Final</th>
        <th width="7.5%">Consolidado</th>
        <th width="7.5%">Contado</th>
        <th width="7.5%">Credito</th>
        <th width="7.5%">Accion</th>
    </tr>
    <tr>
<td>
<select name="sorteo" class = 'form-control' style="width: 100%">
<?php
while ($row2 = mysql_fetch_array($sorteos))
{
echo '<option value = "'.$row2['id'].'">No.'.$row2['no_sorteo_may'].' -- Fecha '.$row2['fecha_sorteo'].' -- '.$row2['descripcion_sorteo_may'].'</option>' ;
}
?>
</select> 
	
</td>

<td>
<select  name="s_empresa" id = 's_empresa' class="selectpicker" data-live-search="true" data-width="100%" class="form-control" >
<?php

$empresas = mysql_query("SELECT * FROM empresas WHERE estado = 'ACTIVO' ");

while ($reg_empresa = mysql_fetch_array($empresas)) {
echo '<option value = "'.$reg_empresa['id'].'">'.$reg_empresa['nombre_empresa'].'</option>' ;
}

?>
</select>
</td>       

<td>
                <div class='input-group date' id='datetimepicker1'>
                    <input type='text' id ="fecha_i" name = "fecha_inicial" class="form-control" />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
	
</td>
<td>
                <div class='input-group date' id='datetimepicker2'>
                    <input type='text' id ="fecha_f" name="fecha_final" class="form-control" />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
	
</td>
<td><input class = 'form-control' type="radio" name="tipo_consulta" value="consolidado" checked></td>
<td><input class = 'form-control' type="radio" name="tipo_consulta" value="contado"></td>
<td><input class = 'form-control' type="radio" name="tipo_consulta" value="credito"></td>
<td align="center">
<input type="submit" name="seleccionar" class="btn btn-primary" style="background-color: #005c7a;" value="Seleccionar">            
</td>       
</tr>
</table></div>
</div>

</div>
 
<?php

if (isset($_POST["seleccionar"])) {

$_SESSION['id_empresa'] = $_POST['s_empresa'];

if (!isset($_SESSION['id_empresa'])) {
$id_empresa = 5;
}else{
$id_empresa = $_SESSION['id_empresa'];
	
}
$id_sorteo = $_POST["sorteo"];				
$fecha_inicial = $_POST['fecha_inicial'];
$fecha_final = $_POST['fecha_final'];

$tipo_consulta = $_POST['tipo_consulta'];

if ($fecha_inicial != '' AND $fecha_final != '' ) {
$fecha_inicial = date("Y-m-d", strtotime($fecha_inicial));
$fecha_final = date("Y-m-d", strtotime($fecha_final));
}

$info_sorteo = mysql_query("SELECT *  FROM sorteos_mayores WHERE id = '$id_sorteo' limit 1");
$value = mysql_fetch_object($info_sorteo);
$sorteo = $value->no_sorteo_may;
$fecha_sorteo = $value->fecha_sorteo;
$mezcla = $value->mezcla;
$precio_unitario = $value->precio_unitario;
$descripcion = $value->descripcion_sorteo_may;



echo "<div class = 'alert alert-info' align = 'center'>
<h3> Sorteo No. ".$sorteo." ".$descripcion." </h3>";

if ($fecha_inicial != '' AND $fecha_final != '' ) {
echo "Reporte de venta al por agencia desde  ".$fecha_inicial."  hasta  ".$fecha_final;
}else{
echo "Reporte de venta por agencia";	
}

if ($tipo_consulta == 'consolidado') {
echo "<br>Contado y Credito";
}elseif ($tipo_consulta == 'credito') {
echo "<br>Credito";
}elseif ($tipo_consulta == 'contado') {
echo "<br>Contado";
}
echo "</div>";				


$info_sorteo = mysql_query("SELECT *  FROM sorteos_mayores WHERE id = '$id_sorteo' limit 1");
$value = mysql_fetch_object($info_sorteo);
$precio_unitario = $value->precio_unitario;

$precio_unitario = $precio_unitario * 10;
$precio_venta    = $precio_unitario;

$porcentaje_comision= 0;			     
$total_cantidad = 0;
$total_vendido = 0;
$total_no_vendido = 0;
$total_pliego_vendido = 0;
$total_pliego_no_vendido = 0;
$precio_unitario = $precio_unitario;
$acumulado_credito = 0;
$acumulado_contado = 0;
$acumulado_credito_lps = 0;
$acumulado_contado_lps = 0;

$seccionales=mysql_query("SELECT * FROM `fvp_seccionales` WHERE id_empresa = '$id_empresa' ORDER BY cod_seccional ");

echo '<table width="100%"  class="table table-hover table-bordered">';
echo "<th>Seccional</th>";
echo "<th>Asignados</th>";
echo "<th>Vendidos</th>";
echo "<th>No Vendido</th>";
echo "<th>Total Distribuido </th>";
echo "<th>Total Vendido </th>";
echo "<th>Total No Vendido </th>";
echo "<th id='non-printable' >Accion </th>";

while ( $seccional=mysql_fetch_array($seccionales)){

$id_seccional = $seccional["id"];
$seccional = $seccional["nombre"];
$consulta_inventario = mysql_query("SELECT * FROM sorteos_mezclas_rangos  WHERE id_seccional = '$id_seccional' AND id_sorteo = '$id_sorteo' ");
$cantidad = 0;

while ($reg_inventario = mysql_fetch_array($consulta_inventario)){
$cantidad ++;
}

$cantidad = $cantidad * $mezcla;
$total_cantidad = $total_cantidad + $cantidad;


if ($fecha_inicial != '' AND $fecha_final != '' ) {

if ($tipo_consulta == 'consolidado') {
$consulta_ventas = mysql_query("SELECT SUM(total_neto) as total FROM transaccional_ventas  WHERE estado_venta = 'APROBADO' AND  id_sorteo =  '$id_sorteo' AND id_seccional = '$id_seccional' AND cod_producto = '1' and date(fecha_venta) BETWEEN  '$fecha_inicial' and '$fecha_final'  ");

$consulta_contado = mysql_query("SELECT SUM(total_neto) as total FROM transaccional_ventas  WHERE forma_pago = '1' AND estado_venta = 'APROBADO' AND  id_sorteo =  '$id_sorteo' AND id_seccional = '$id_seccional' AND cod_producto = '1'  and date(fecha_venta) BETWEEN  '$fecha_inicial' and '$fecha_final' ");

$ob_contado = mysql_fetch_object($consulta_contado);
$consulta_total_contado = $ob_contado->total;
$consulta_total_contado_lps = $consulta_total_contado;
$acumulado_contado_lps = $acumulado_contado_lps + $consulta_total_contado_lps;

$consulta_total_contado = $consulta_total_contado/$precio_venta;
$acumulado_contado = $acumulado_contado + $consulta_total_contado;


$consulta_credito = mysql_query("SELECT SUM(total_bruto) as total FROM transaccional_ventas  WHERE forma_pago != '1' AND estado_venta = 'APROBADO' AND  id_sorteo =  '$id_sorteo' AND id_seccional = '$id_seccional' AND cod_producto = '1' and date(fecha_venta) BETWEEN  '$fecha_inicial' and '$fecha_final' ");
$ob_credito = mysql_fetch_object($consulta_credito);
$consulta_total_credito = $ob_credito->total;
$consulta_total_credito_lps = $consulta_total_credito;
$acumulado_credito_lps = $acumulado_credito_lps + $consulta_total_credito_lps;

$consulta_total_credito = $consulta_total_credito/$precio_venta;
$acumulado_credito = $acumulado_credito + $consulta_total_credito;


}elseif ($tipo_consulta == 'contado') {
$consulta_ventas = mysql_query("SELECT SUM(total_bruto) as total FROM transaccional_ventas  WHERE forma_pago = '1' AND estado_venta = 'APROBADO' AND  id_sorteo =  '$id_sorteo' AND id_seccional = '$id_seccional'  AND cod_producto = '1' and date(fecha_venta) BETWEEN  '$fecha_inicial' and '$fecha_final'  ");

}elseif ($tipo_consulta == 'credito'){
$consulta_ventas = mysql_query("SELECT SUM(total_bruto) as total FROM transaccional_ventas  WHERE forma_pago != '1' AND tipo_venta != 'bolsas' AND estado_venta = 'APROBADO' AND  id_sorteo =  '$id_sorteo' AND id_seccional = '$id_seccional' AND cod_producto = '1'  and date(fecha_venta) BETWEEN  '$fecha_inicial' and '$fecha_final'  ");

}


}else{

if ($tipo_consulta == 'consolidado') {
$consulta_ventas = mysql_query("SELECT SUM(total_bruto) as total FROM transaccional_ventas  WHERE estado_venta = 'APROBADO' AND  id_sorteo =  '$id_sorteo' AND id_seccional = '$id_seccional' AND cod_producto = '1'  ");

$consulta_contado = mysql_query("SELECT SUM(total_bruto) as total FROM transaccional_ventas  WHERE forma_pago = '1' AND estado_venta = 'APROBADO' AND  id_sorteo =  '$id_sorteo' AND id_seccional = '$id_seccional' AND cod_producto = '1' ");
$ob_contado = mysql_fetch_object($consulta_contado);
$consulta_total_contado = $ob_contado->total;
$consulta_total_contado_lps = $consulta_total_contado;
$acumulado_contado_lps = $acumulado_contado_lps + $consulta_total_contado_lps;

$consulta_total_contado = $consulta_total_contado/$precio_venta;
$acumulado_contado = $acumulado_contado + $consulta_total_contado;


$consulta_credito = mysql_query("SELECT SUM(total_bruto) as total FROM transaccional_ventas  WHERE forma_pago != '1' AND estado_venta = 'APROBADO' AND  id_sorteo =  '$id_sorteo' AND id_seccional = '$id_seccional' AND cod_producto = '1' ");
$ob_credito = mysql_fetch_object($consulta_credito);
$consulta_total_credito = $ob_credito->total;
$consulta_total_credito_lps = $consulta_total_credito;
$acumulado_credito_lps = $acumulado_credito_lps + $consulta_total_credito_lps;

$consulta_total_credito = $consulta_total_credito/$precio_venta;
$acumulado_credito = $acumulado_credito + $consulta_total_credito;

}elseif ($tipo_consulta == 'contado') {
$consulta_ventas = mysql_query("SELECT SUM(total_bruto) as total FROM transaccional_ventas  WHERE forma_pago = '1' AND estado_venta = 'APROBADO' AND  id_sorteo =  '$id_sorteo' AND id_seccional = '$id_seccional' AND cod_producto = '1' ");

}elseif ($tipo_consulta == 'credito'){
$consulta_ventas = mysql_query("SELECT SUM(total_bruto) as total FROM transaccional_ventas  WHERE forma_pago != '1' AND tipo_venta != 'bolsas' AND estado_venta = 'APROBADO' AND  id_sorteo =  '$id_sorteo' AND id_seccional = '$id_seccional' AND cod_producto = '1' ");

}

}


while ($reg_venta = mysql_fetch_array($consulta_ventas)){

$valor_pliego = $precio_venta; 
$billetes_vendidos  = ($reg_venta['total']/ $valor_pliego);
$total_vendido = $total_vendido + $billetes_vendidos;
$pliegos_vendidos = $billetes_vendidos; 
$total_pliego_vendido = $total_pliego_vendido + $pliegos_vendidos;
$no_vendidos = $cantidad - $pliegos_vendidos;
$total_pliego_no_vendido = $total_pliego_no_vendido + $no_vendidos;

echo "<tr>";


echo "<td onclick = 'cambiar_icon(".$id_seccional.")' role='button' data-toggle='collapse' href='#collapse".$id_seccional."' aria-expanded='false' aria-controls='collapse".$id_seccional."'>".$seccional."";

if ($tipo_consulta == 'consolidado') {
echo "<span style = 'color:#bfbfbf ;float:right' id = 'span".$id_seccional."' class = 'glyphicon glyphicon-chevron-down'>";
}

echo "</td>";


echo "<td>".$cantidad."</td>";
echo "<td>".$pliegos_vendidos."</td>";
						
echo "<td>".$no_vendidos."</td>";
echo "<td> L.".number_format($cantidad*$valor_pliego,2)."</td>";
echo "<td> L. ".number_format($billetes_vendidos*$valor_pliego,2)."</td>";
echo "<td> L. ".number_format($no_vendidos*$valor_pliego,2)."</td>";

$parametros = $id_sorteo."/".$id_seccional."/".$fecha_inicial."/".$fecha_final ;
echo "
<td id='non-printable'>
<a class='btn btn-primary' target='_blank' href= './_tabla_ventas_mayor_detalle.php?dat=".$parametros."'>
<span class = 'glyphicon glyphicon-eye-open'></span>  
</a>
</td>";
echo "</tr>";

if ($tipo_consulta == 'consolidado') {
echo "<tr style = 'background-color:#f2f2f2' class='collapse' id='collapse".$id_seccional."'>";
echo "<td align = 'center' colspan = '9'>
<br>
<table width = '60%' >";
echo "<tr>";
echo "<td align = 'center'>Contado ".$consulta_total_contado."</td>";
echo "<td align = 'center'>Credito ".$consulta_total_credito."</td>";
echo "<td align = 'center'>Contado Lps. ".number_format($consulta_total_contado_lps,2)."</td>";
echo "<td align = 'center'>Credito Lps. ".number_format($consulta_total_credito_lps,2)."</td>";
echo "<tr>
	  </table>";
echo "<br>";	  
echo "</td>";
echo "</tr>";
}


}
}


$inventario_bobeda = mysql_query(" SELECT COUNT(a.rango) as conteo FROM sorteos_mezclas_rangos as a INNER JOIN sorteos_mezclas as b ON a.num_mezcla = b.num_mezcla WHERE a.id_sorteo = '$id_sorteo' AND b.id_sorteo = '$id_sorteo' AND b.id_empresa = '$id_empresa' AND a.id_seccional IS NULL");

if ($inventario_bobeda=== false) {
echo mysql_error();
}
$ob_boveda = mysql_fetch_object($inventario_bobeda);
$paquetes_sin_asignacion = $ob_boveda->conteo;

$total_bobeda =  $paquetes_sin_asignacion * $mezcla; 

	echo "<tr>";
	echo "<td>";
	echo "BOVEDA";
	echo "</td>";
	echo "<td>";
	echo $total_bobeda;
	echo "</td>";
	echo "<td colspan = '5' >";
	echo "</td>";
	echo "<td id='non-printable'>
	<a class='btn btn-primary' target='_blank' href= './reporte_boveda_mayor.php?v1=".$id_sorteo."'>
	<span class = 'glyphicon glyphicon-eye-open'></span></a></td>";
	echo "</tr>";

$total_cantidad = $total_cantidad + $total_bobeda;
$total_no_vendido = $total_cantidad - $total_vendido; 
$total_pliego_no_vendido = $total_cantidad - $total_pliego_vendido;
			
			echo "<tr>
			<td onclick = 'cambiar_icon(t)' role='button' data-toggle='collapse' href='#collapset' aria-expanded='false' aria-controls='collapset' ><b>TOTAL</b> ";
if ($tipo_consulta == 'consolidado') {
echo "<span style = 'color:#bfbfbf ;float:right' id = 'spant' class = 'glyphicon glyphicon-chevron-down'>";
}
			echo "</td>

			<td>".$total_cantidad."</td>
			<td>".$total_pliego_vendido."</td>
			<td>".$total_pliego_no_vendido."</td>
			<td> L. ".number_format($total_cantidad*$precio_venta,2)."</td>
			<td> L. ".number_format($total_vendido*$precio_venta,2)."</td>
			<td> L. ".number_format($total_pliego_no_vendido*$precio_venta,2)."</td>
			<td id='non-printable'></td>
			</tr>";

if ($tipo_consulta == 'consolidado') {
echo "<tr style = 'background-color:#f2f2f2' class='collapse' id='collapset'>";
echo "<td align = 'center' colspan = '9'>
<br>
<table width = '60%' >";
echo "<tr>";
echo "<td align = 'center'>Contado ".$acumulado_contado."</td>";
echo "<td align = 'center'>Credito ".$acumulado_credito."</td>";
echo "<td align = 'center'>Contado Lps. ".number_format($acumulado_contado_lps,2)."</td>";
echo "<td align = 'center'>Credito Lps. ".number_format($acumulado_credito_lps,2)."</td>";
echo "<tr>
	  </table>";
echo "<br>";	  
echo "</td>";
echo "</tr>";
}


			echo "</table><br><br>";
$parametros  = $id_sorteo;
echo ' <button id="non-printable" type="submit"  class="btn btn-success" style=" width:20%; margin-left:40%;" name="envio_excel" value='.$parametros.' >Exportar Excel Consolidado</button>';
			}


			?>
 
</form><br>
</div>
</div>	 

</body>