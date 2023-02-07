<?php

require("../../template/header.php");
//require("./tesoreria_reporte_consolidado_banco_mayor_excel.php"); 
$recaudadores = mysqli_query($conn," SELECT * FROM empresas WHERE estado = 'ACTIVO' ");
date_default_timezone_set('America/Tegucigalpa');

$max_date = date("Y-m-d");


?>


<body>
<form method="POST">


<br>

<ul class="nav nav-tabs">
 <li class="nav-item">
    <a style="background-color:#ededed;" class="nav-link active" href="#">Lotería Mayor</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="./conta_ventas_sorteo_menor.php" >Lotería Menor</a>
  </li>
</ul>



<section style="background-color:#ededed;">
<br>
<h2 align="center" style="color:black;" >
  <b>VENTA DE LOTERIA MAYOR

<?php

if (isset($_POST['seleccionar'])) {

$fecha_i = $_POST['fecha_inicial'];
$fecha_i = date("Y-m-d", strtotime($fecha_i));
$fecha_f = $_POST['fecha_final'];
$fecha_f = date("Y-m-d", strtotime($fecha_f));

echo "<p align = 'center'>Del  ".$fecha_i." Al ".$fecha_f."</p>";

}


?>

</b></h2> 
<br>
</section>


<a class="btn btn-secondary" id="non-printable" style="width:100%" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
Selección de parametros 
</a>

<div  class="collapse" style = "width:100%"  id="collapse1" align="center">
<div class="card" style="width: 50%">
<div class="card-body">
  

<div class="input-group" style="margin:10px 0px 10px 0px;">
<input type="text" placeholder="Fecha Inicial" name="fecha_inicial" id="fecha_inicial" class="form-control" >
</div>


<div class="input-group" style="margin:10px 0px 10px 0px;">
<input type="text"  placeholder="Fecha Inicial" name="fecha_final" id="fecha_final" class="form-control" >
</div>


<input type="submit" name="seleccionar" class="btn btn-primary" value="Seleccionar" style="width: 100%">


<script>
$('#fecha_inicial').datepicker({
locale: 'es-es',
format: 'yyyy-mm-dd',
uiLibrary: 'bootstrap4'
});

$('#fecha_final').datepicker({
locale: 'es-es',
format: 'yyyy-mm-dd',
uiLibrary: 'bootstrap4'
});

</script>


</div>
</div>
</div>








<?php

function funcion_nombre_mes($numero_mes){

if ($numero_mes == 1) {
$nombre_mes = "Enero";
}elseif ($numero_mes == 2) {
$nombre_mes = "Febrero";
}elseif ($numero_mes == 3) {
$nombre_mes = "Marzo";
}elseif ($numero_mes == 4) {
$nombre_mes = "Abril";
}elseif ($numero_mes == 5) {
$nombre_mes = "Mayo";
}elseif ($numero_mes == 6) {
$nombre_mes = "Junio";
}elseif ($numero_mes == 7) {
$nombre_mes = "Julio";
}elseif ($numero_mes == 8) {
$nombre_mes = "Agosto";
}elseif ($numero_mes == 9) {
$nombre_mes = "Septiembre";
}elseif ($numero_mes == 10) {
$nombre_mes = "Octubre";
}elseif ($numero_mes == 11) {
$nombre_mes = "Noviembre";
}elseif ($numero_mes == 12) {
$nombre_mes = "Diciembre";
}

return $nombre_mes;

}


if (isset($_POST['seleccionar'])) {

$fecha = date('Y-m-d h:i:s a');
echo "<p align = 'right'> ".$fecha."</p>";



$fecha_i = $_POST['fecha_inicial'];
$fecha_i = date("Y-m-d", strtotime($fecha_i));
$fecha_f = $_POST['fecha_final'];
$fecha_f = date("Y-m-d", strtotime($fecha_f));

$current_date = date("Y-m-d");
if ($fecha_f >= $current_date OR $fecha_i >= $current_date ) {

echo "<div class = 'alert alert-danger'>La fecha inicial y final por consultar deben ser anterior al dia de hoy.</div>";

}else{


$first_sorteo = 0;
$bandera_sorteo = 0;

/////////////////////////////
////// VENTAS BANCO /////////


$consulta_banco = mysqli_query($conn,"SELECT id_sorteo , YEAR(fecha_venta) as year , MONTH(fecha_venta) as mes , precio_unitario ,SUM(cantidad) as cantidad,  SUM(total_bruto) as bruto, SUM(descuento) as descuento, SUM(total_neto) as neto, SUM(comision_bancaria) as comision_bancaria , SUM(credito_pani) as credito_pani FROM transaccional_ventas_general WHERE estado_venta = 'APROBADO'  AND cod_producto = 1 AND id_entidad = 3 AND date(fecha_venta)  BETWEEN '$fecha_i' AND '$fecha_f' GROUP BY YEAR(fecha_venta), MONTH(fecha_venta), id_sorteo ");

if ($consulta_banco === FALSE) {
echo mysqli_error();
}

echo "<div class = 'card' style = 'margin-left:5px;margin-right:5px;'>";
echo "<div class = 'card-header bg-primary text-white'><h4 align = 'center'>BANRURAL</h4></div>";
echo "<div class = 'card-body'>";

echo "<table class = 'table table-bordered'>";
echo "<tr>";
echo "<th>AÑO</th>";
echo "<th>MES</th>";
echo "<th>SORTEO</th>";
echo "<th>CANTIDAD</th>";
echo "<th>PRECIO PLIEGO</th>";
echo "<th>TOTAL BRUTO</th>";
echo "<th>COMISION + DESCUENTO</th>";
echo "<th>UTILIDAD PANI</th>";
echo "</tr>";

$i = 0;

$acumulado_cantidad   = 0;
$acumulado_bruto      = 0;
$acumulado_descuento  = 0;
$acumulado_neto       = 0;
$acumulado_comision   = 0;
$acumulado_credito    = 0;

while ($reg_consulta_banco = mysqli_fetch_array($consulta_banco)) {

$indice = $reg_consulta_banco['year'].$reg_consulta_banco['mes'].$reg_consulta_banco['id_sorteo'];
$nombre_mes = funcion_nombre_mes($reg_consulta_banco['mes']);

$v_indice[$i]          = $indice;
$v_year[$i]            = $reg_consulta_banco['year'];
$v_mes[$i]             = $reg_consulta_banco['mes'];
$v_sorteo[$i]          = $reg_consulta_banco['id_sorteo'];
$v_cantidad[$i]        = $reg_consulta_banco['cantidad'];
$v_precio_unitario[$i] = $reg_consulta_banco['precio_unitario'];
$v_bruto[$i]           = $reg_consulta_banco['bruto'];
$v_descuento[$i]       = $reg_consulta_banco['descuento'] + $reg_consulta_banco['comision_bancaria'];
$v_neto[$i]            = $reg_consulta_banco['neto'];
$v_comision[$i]        = $reg_consulta_banco['comision_bancaria'];
$v_credito[$i]         = $reg_consulta_banco['credito_pani'];

$acumulado_cantidad   += $v_cantidad[$i];
$acumulado_bruto      += $v_bruto[$i];
$acumulado_descuento  += $v_descuento[$i];
$acumulado_neto       += $v_neto[$i];
$acumulado_comision   += $v_comision[$i];
$acumulado_credito    += $v_credito[$i];


echo "<tr>";
echo "<td>".$reg_consulta_banco['year']."</td>";
echo "<td>".$nombre_mes."</td>";
echo "<td>".$reg_consulta_banco['id_sorteo']."</td>";
echo "<td>".number_format($reg_consulta_banco['cantidad'])."</td>";
echo "<td>".number_format($reg_consulta_banco['precio_unitario'], 2)."</td>";
echo "<td>".number_format($reg_consulta_banco['bruto'], 2)."</td>";
echo "<td>".number_format($v_descuento[$i], 2)."</td>";
echo "<td>".number_format($reg_consulta_banco['credito_pani'], 2)."</td>";
echo "</tr>";

$i++;
}

echo "<tr class = 'alert alert-success'>";
echo "<th colspan = '3'>TOTALES</th>";
echo "<th>".number_format($acumulado_cantidad)."</th>";
echo "<th></th>";
echo "<th>".number_format($acumulado_bruto,2)."</th>";
echo "<th>".number_format($acumulado_descuento,2)."</th>";
echo "<th>".number_format($acumulado_credito,2)."</th>";
echo "</tr>";

echo "</table>";



$venta_minorista = mysqli_query($conn,"SELECT id_sorteo , YEAR(fecha_venta) as year , MONTH(fecha_venta) as mes , precio_unitario ,SUM(cantidad) as cantidad,  SUM(total_bruto) as bruto, SUM(descuento) as descuento, SUM(total_neto) as neto, SUM(comision_bancaria) as comision_bancaria , SUM(credito_pani) as credito_pani FROM transaccional_ventas_general WHERE cantidad < 10 AND estado_venta = 'APROBADO'  AND cod_producto = 1 AND id_entidad = 3 AND date(fecha_venta)  BETWEEN '$fecha_i' AND '$fecha_f' GROUP BY YEAR(fecha_venta), MONTH(fecha_venta), id_sorteo");


echo "<table width = '100%'>";
echo "<tr>";
echo "<td width = '49%' valign = 'top'>";

echo "<table class = 'table table-bordered'>";
echo "<tr>";
echo "<th colspan = '7' class = 'alert alert-info' > VENTA MINORISTA </th>";
echo "</tr>";

echo "<tr>";
echo "<th>AÑO</th>";
echo "<th>MES</th>";
echo "<th>SORTEO</th>";
echo "<th>CANTIDAD</th>";
echo "<th>DESCUENTO</th>";
echo "<th>COMISION</th>";
echo "<th>UTILIDAD PANI</th>";
echo "</tr>";

$acumulado_cantidad   = 0;
$acumulado_bruto      = 0;
$acumulado_descuento  = 0;
$acumulado_neto       = 0;
$acumulado_comision   = 0;
$acumulado_credito    = 0;
$acumulado_credit_pani = 0;

while ($reg_venta_minorista = mysqli_fetch_array($venta_minorista)) {

$nombre_mes = funcion_nombre_mes($reg_venta_minorista['mes']);

$acumulado_cantidad   += $reg_venta_minorista['cantidad'];
$acumulado_descuento  += $reg_venta_minorista['descuento'];
$acumulado_comision   += $reg_venta_minorista['comision_bancaria'];
$acumulado_credit_pani   += $reg_venta_minorista['credito_pani'];

echo "<tr>";
echo "<td>".$reg_venta_minorista['year']."</td>";
echo "<td>".$nombre_mes."</td>";
echo "<td>".$reg_venta_minorista['id_sorteo']."</td>";
echo "<td>".number_format($reg_venta_minorista['cantidad'])."</td>";
echo "<td>".number_format($reg_venta_minorista['descuento'], 2)."</td>";
echo "<td>".number_format($reg_venta_minorista['comision_bancaria'], 2)."</td>";
echo "<td>".number_format($reg_venta_minorista['credito_pani'], 2)."</td>";
echo "</tr>";
}

echo "<tr class = 'alert alert-success'>";
echo "<th colspan = '3'>TOTALES</th>";
echo "<th>".number_format($acumulado_cantidad)."</th>";
echo "<th>".number_format($acumulado_descuento,2)."</th>";
echo "<th>".number_format($acumulado_comision,2)."</th>";
echo "<th>".number_format($acumulado_credit_pani,2)."</th>";
echo "</tr>";

echo "</table>";

echo "</td>";
echo "<td width = '2%'></td>";
echo "<td width = '49%' valign = 'top'>";

$venta_mayorista = mysqli_query($conn,"SELECT id_sorteo , YEAR(fecha_venta) as year , MONTH(fecha_venta) as mes , precio_unitario ,SUM(cantidad) as cantidad,  SUM(total_bruto) as bruto, SUM(descuento) as descuento, SUM(total_neto) as neto, SUM(comision_bancaria) as comision_bancaria , SUM(credito_pani) as credito_pani FROM transaccional_ventas_general WHERE cantidad >= 10 AND estado_venta = 'APROBADO'  AND cod_producto = 1 AND id_entidad = 3 AND date(fecha_venta)  BETWEEN '$fecha_i' AND '$fecha_f' GROUP BY YEAR(fecha_venta), MONTH(fecha_venta), id_sorteo");


echo "<table class = 'table table-bordered'>";
echo "<tr>";
echo "<th colspan = '7' class = 'alert alert-info'> VENTA MAYORISTA </th>";
echo "</tr>";

echo "<tr>";
echo "<th>AÑO</th>";
echo "<th>MES</th>";
echo "<th>SORTEO</th>";
echo "<th>CANTIDAD</th>";
echo "<th>DESCUENTO</th>";
echo "<th>COMISION</th>";
echo "<th>UTILIDAD PANI</th>";
echo "</tr>";

$acumulado_cantidad   = 0;
$acumulado_bruto      = 0;
$acumulado_descuento  = 0;
$acumulado_neto       = 0;
$acumulado_comision   = 0;
$acumulado_credito    = 0;


while ($reg_venta_mayorista = mysqli_fetch_array($venta_mayorista)) {

$nombre_mes = funcion_nombre_mes($reg_venta_mayorista['mes']);

$acumulado_cantidad   += $reg_venta_mayorista['cantidad'];
$acumulado_descuento  += $reg_venta_mayorista['descuento'];
$acumulado_comision   += $reg_venta_mayorista['comision_bancaria'];
$acumulado_credito    += $reg_venta_mayorista['credito_pani'];

echo "<tr>";
echo "<td>".$reg_venta_mayorista['year']."</td>";
echo "<td>".$nombre_mes."</td>";
echo "<td>".$reg_venta_mayorista['id_sorteo']."</td>";
echo "<td>".number_format($reg_venta_mayorista['cantidad'])."</td>";
echo "<td>".number_format($reg_venta_mayorista['descuento'], 2)."</td>";
echo "<td>".number_format($reg_venta_mayorista['comision_bancaria'], 2)."</td>";
echo "<td>".number_format($reg_venta_mayorista['credito_pani'], 2)."</td>";
echo "</tr>";
}

echo "<tr class = 'alert alert-success'>";
echo "<th colspan = '3'>TOTALES</th>";
echo "<th>".number_format($acumulado_cantidad)."</th>";
echo "<th>".number_format($acumulado_descuento,2)."</th>";
echo "<th>".number_format($acumulado_comision,2)."</th>";
echo "<th>".number_format($acumulado_credito,2)."</th>";
echo "</tr>";

echo "</table>";

echo "</td>";
echo "</tr>";
echo "</table>";


echo "</div>";
echo "</div>";
////// VENTAS BANCO /////////
/////////////////////////////









echo "<br>";


$entidades = mysqli_query($conn, "SELECT * FROM empresas ");

while ($reg_entidades = mysqli_fetch_array($entidades)) {
$v_entidades[$reg_entidades["id"]] = $reg_entidades["nombre_empresa"];
}

////////////////////////
///// VENTAS FVP ///////

$consulta_fvp = mysqli_query($conn,"SELECT id_sorteo , YEAR(fecha_venta) as year , MONTH(fecha_venta) as mes , SUM(total_bruto)/SUM(cantidad) as precio_unitario ,SUM(cantidad) as cantidad,  SUM(total_bruto) as bruto, SUM(descuento) as descuento, SUM(total_neto) as neto, SUM(comision_bancaria) as comision_bancaria, SUM(credito_pani) as credito_pani , id_entidad  FROM ( 

  SELECT id_sorteo , fecha_venta ,  cantidad, total_bruto,  descuento, total_neto,  comision_bancaria, credito_pani, id_entidad FROM transaccional_ventas WHERE estado_venta = 'APROBADO' AND date(fecha_venta) BETWEEN '$fecha_i' AND '$fecha_f' AND cod_producto = 1 

  UNION ALL  

  SELECT id_sorteo , fecha_venta ,  cantidad, total_bruto,  descuento, total_neto,  comision_bancaria, credito_pani, id_entidad  FROM transaccional_ventas_ajuste WHERE estado_venta = 'APROBADO' AND date(fecha_venta) BETWEEN '$fecha_i' AND '$fecha_f'  AND cod_producto = 1 ) as t  GROUP BY YEAR(fecha_venta), MONTH(fecha_venta), id_sorteo, id_entidad  ");




echo "<div class = 'card' style = 'margin-right: 5px; margin-left: 5px; '>";
echo "<div class = 'card-header bg-primary text-white'><h4 align = 'center'>OTRAS ENTIDADES RECAUDADORAS</h4></div>";
echo "<div class = 'card-body'>";

echo "<table class = 'table table-bordered'>";
echo "<tr>";
echo "<th>AÑO</th>";
echo "<th>MES</th>";
echo "<th>SORTEO</th>";
echo "<th>ENTIDAD</th>";
echo "<th>CANTIDAD</th>";
echo "<th>PRECIO PLIEGO</th>";
echo "<th>TOTAL BRUTO</th>";
echo "<th>COMISION + DESCUENTO</th>";
echo "<th>UTILIDAD PANI</th>";
echo "</tr>";


$acumulado_cantidad   = 0;
$acumulado_bruto      = 0;
$acumulado_descuento  = 0;
$acumulado_neto       = 0;
$acumulado_comision   = 0;
$acumulado_credito    = 0;


$i = 0;
while ($reg_consulta_fvp = mysqli_fetch_array($consulta_fvp)) {

$indice = $reg_consulta_fvp['year'].$reg_consulta_fvp['mes'].$reg_consulta_fvp['id_sorteo'];

if (isset($v_indice[0])) {

if (in_array($indice,$v_indice)) {

$posicion = array_search($indice, $v_indice);

$v_cantidad[$posicion]        = $v_cantidad[$posicion]        + $reg_consulta_fvp['cantidad'];
$v_bruto[$posicion]           = $v_bruto[$posicion]           + $reg_consulta_fvp['bruto'];
$v_descuento[$posicion]       = $v_descuento[$posicion]       + $reg_consulta_fvp['descuento'] + $reg_consulta_fvp['comision_bancaria'];
$v_neto[$posicion]            = $v_neto[$posicion]            + $reg_consulta_fvp['neto'];
$v_comision[$posicion]        = $v_comision[$posicion]        + $reg_consulta_fvp['comision_bancaria'];
$v_credito[$posicion]         = $v_credito[$posicion]         + $reg_consulta_fvp['credito_pani'];

}else{

end($v_indice);
$key = key($v_indice);
$key++;

$v_indice[$key]          = $indice;
$v_year[$key]            = $reg_consulta_fvp['year'];
$v_mes[$key]             = $reg_consulta_fvp['mes'];
$v_sorteo[$key]          = $reg_consulta_fvp['id_sorteo'];
$v_cantidad[$key]        = $reg_consulta_fvp['cantidad'];
$v_precio_unitario[$key] = $reg_consulta_fvp['precio_unitario'];
$v_bruto[$key]           = $reg_consulta_fvp['bruto'];
$v_descuento[$key]       = $reg_consulta_fvp['descuento'] + $reg_consulta_fvp['comision_bancaria'];
$v_neto[$key]            = $reg_consulta_fvp['neto'];
$v_comision[$key]        = $reg_consulta_fvp['comision_bancaria'];
$v_credito[$key]         = $reg_consulta_fvp['credito_pani'];


}

}else{

$v_indice[0]          = $indice;
$v_year[0]            = $reg_consulta_fvp['year'];
$v_mes[0]             = $reg_consulta_fvp['mes'];
$v_sorteo[0]          = $reg_consulta_fvp['id_sorteo'];
$v_cantidad[0]        = $reg_consulta_fvp['cantidad'];
$v_precio_unitario[0] = $reg_consulta_fvp['precio_unitario'];
$v_bruto[0]           = $reg_consulta_fvp['bruto'];
$v_descuento[0]       = $reg_consulta_fvp['descuento'] + $reg_consulta_fvp['comision_bancaria'];
$v_neto[0]            = $reg_consulta_fvp['neto'];
$v_comision[0]        = $reg_consulta_fvp['comision_bancaria'];
$v_credito[0]         = $reg_consulta_fvp['credito_pani'];

}


$nombre_mes = funcion_nombre_mes($reg_consulta_fvp['mes']);

$acumulado_cantidad   += $reg_consulta_fvp['cantidad'];
$acumulado_bruto      += $reg_consulta_fvp['bruto'];
$acumulado_descuento  += $reg_consulta_fvp['descuento'] + $reg_consulta_fvp['comision_bancaria'];
$acumulado_neto       += $reg_consulta_fvp['neto'];
$acumulado_comision   += $reg_consulta_fvp['comision_bancaria'];
$acumulado_credito    += $reg_consulta_fvp['credito_pani'];


$comision_descuento = $reg_consulta_fvp['descuento'] + $reg_consulta_fvp['comision_bancaria'];

echo "<tr>";
echo "<td>".$reg_consulta_fvp['year']."</td>";
echo "<td>".$nombre_mes."</td>";
echo "<td>".$reg_consulta_fvp['id_sorteo']."</td>";
echo "<td>".$v_entidades[$reg_consulta_fvp['id_entidad']]."</td>";
echo "<td>".number_format($reg_consulta_fvp['cantidad'])."</td>";
echo "<td>".number_format($reg_consulta_fvp['precio_unitario'], 2)."</td>";
echo "<td>".number_format($reg_consulta_fvp['bruto'], 2)."</td>";
echo "<td>".number_format($comision_descuento, 2)."</td>";
echo "<td>".number_format($reg_consulta_fvp['credito_pani'], 2)."</td>";
echo "</tr>";

}

echo "<tr class = 'alert alert-success'>";
echo "<th colspan = '4'>TOTALES</th>";
echo "<th>".number_format($acumulado_cantidad)."</th>";
echo "<th></th>";
echo "<th>".number_format($acumulado_bruto,2)."</th>";
echo "<th>".number_format($acumulado_descuento,2)."</th>";
echo "<th>".number_format($acumulado_credito,2)."</th>";
echo "</tr>";


echo "</table>";

echo "</div>";
echo "</div>";



///// VENTAS FVP ///////
////////////////////////

echo "<br>";

///////////////////////////
////// TABLA RESUMEN //////

echo "<div class = 'card' style = 'margin-right: 5px; margin-left: 5px; '>";

echo "<div class = 'card-header bg-primary text-white'><h4 align = 'center'>TOTAL DE VENTAS</h4></div>";
echo "<div class = 'panel-body'>";

echo "<table class = 'table table-bordered'>";
echo "<tr>";
echo "<th>AÑO</th>";
echo "<th>MES</th>";
echo "<th>SORTEO</th>";
echo "<th>CANTIDAD</th>";
echo "<th>TOTAL BRUTO</th>";
echo "<th>COMISION + DESCUENTO</th>";
echo "<th>UTILIDAD PANI</th>";
echo "</tr>";

sort($v_indice);
$i = 0;

$total_cantidad = 0;
$total_bruto = 0;
$total_descuento = 0;
$total_neto = 0;
$total_comision = 0;
$total_credito = 0;

while (isset($v_indice[$i])) {

$total_cantidad  = $total_cantidad + $v_cantidad[$i];
$total_bruto     = $total_bruto + $v_bruto[$i];
$total_descuento = $total_descuento + $v_descuento[$i];
$total_neto      = $total_neto + $v_neto[$i];
$total_comision  = $total_comision + $v_comision[$i];
$total_credito   = $total_credito + $v_credito[$i];

$nombre_mes = funcion_nombre_mes($v_mes[$i]);

echo "<tr>";
echo "<td>".$v_year[$i]."</td>";
echo "<td>".$nombre_mes."</td>";
echo "<td>".$v_sorteo[$i]."</td>";
echo "<td>".number_format($v_cantidad[$i])."</td>";
echo "<td>".number_format($v_bruto[$i], 2)."</td>";
echo "<td>".number_format($v_descuento[$i], 2)."</td>";
echo "<td>".number_format($v_credito[$i], 2)."</td>";
echo "</tr>";

$i++;
}

echo "<tr class = 'alert alert-success'>";
echo "<th colspan = '3' >TOTALES</th>";
echo "<th>".number_format($total_cantidad)."</th>";
echo "<th>".number_format($total_bruto, 2)."</th>";
echo "<th>".number_format($total_descuento, 2)."</th>";
echo "<th>".number_format($total_credito, 2)."</th>";
echo "</tr>";

echo "</table>";

echo "</div>";
echo "</div>";

////// TABLA RESUMEN //////
///////////////////////////

}


}


?>


</form>
</body>

<?php

?>
