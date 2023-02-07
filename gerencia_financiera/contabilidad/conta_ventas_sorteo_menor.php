<?php

require("../../template/header.php");
$recaudadores = mysqli_query($conn," SELECT * FROM empresas WHERE estado = 'ACTIVO' ");
date_default_timezone_set('America/Tegucigalpa');

?>




<body>
<form method="POST">


<br>


<ul class="nav nav-tabs">
 <li class="nav-item">
    <a class="nav-link" href="./screen_conta_ventas_sorteo_mayor.php" >Lotería Mayor</a>
  </li>
  <li class="nav-item">
    <a style="background-color:#ededed;" class="nav-link active" href="#">Lotería Menor</a>
  </li>
</ul>



<section style="background-color:#ededed;">
<br>
<h2 align="center" style="color:black;" >
  <b>VENTA DE LOTERIA MENOR

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

$fecha = date('Y-m-d');
echo "<p align = 'right'>".$fecha."</p>";

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

//////////////////////////////////
////// VENTAS BANCO BOLSA/////////


$consulta_banco = mysqli_query($conn,"SELECT id_sorteo , YEAR(fecha_venta) as year , MONTH(fecha_venta) as mes , precio_unitario ,SUM(cantidad) as cantidad,  SUM(total_bruto) as bruto, SUM(descuento) as descuento, SUM(total_neto) as neto,  SUM(comision_bancaria) as comision_bancaria , SUM(utilidad_pani) as utilidad_pani, SUM(credito_pani) as credito_pani , SUM(aportacion) as aportacion FROM transaccional_ventas_general WHERE estado_venta = 'APROBADO' AND cod_producto = 3 AND id_entidad = 3 AND date(fecha_venta) BETWEEN '$fecha_i' AND '$fecha_f' GROUP BY YEAR(fecha_venta), MONTH(fecha_venta), id_sorteo ");



if ($consulta_banco === FALSE) {
echo mysqli_error();
}

echo "<div class = 'card' style = 'margin-left:5px;margin-right:5px;'>";
echo "<div class = 'card-header bg-primary text-white'><h4 align = 'center'>BANRURAL BOLSAS</h4></div>";
echo "<div class = 'card-body'>";


echo "<table class = 'table table-bordered'>";
echo "<tr>";
echo "<th>AÑO</th>";
echo "<th>MES</th>";
echo "<th>SORTEO</th>";
echo "<th>CANTIDAD</th>";
echo "<th>PRECIO BOLSA</th>";
echo "<th>TOTAL BRUTO</th>";
echo "<th>COMISION + DESCUENTO</th>";
echo "<th>APORTACION</th>";
echo "<th>UTILIDAD PANI</th>";
echo "</tr>";

$acumulado_cantidad   = 0;
$acumulado_bruto      = 0;
$acumulado_descuento  = 0;
$acumulado_neto       = 0;
$acumulado_comision   = 0;
$acumulado_aportacion = 0;
$acumulado_credito    = 0;
$acumulado_utilidad   = 0;


$i = 0;
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
$v_descuento[$i]       = $reg_consulta_banco['descuento'];
$v_neto[$i]            = $reg_consulta_banco['neto'];
$v_comision[$i]        = $reg_consulta_banco['comision_bancaria'] + $reg_consulta_banco['descuento'];
$v_aportacion[$i]      = $reg_consulta_banco['aportacion'];
$v_utilidad[$i]        = $reg_consulta_banco['utilidad_pani'];
$v_credito[$i]         = $reg_consulta_banco['credito_pani'];


echo "<tr>";
echo "<td>".$reg_consulta_banco['year']."</td>";
echo "<td>".$nombre_mes."</td>";
echo "<td>".$reg_consulta_banco['id_sorteo']."</td>";
echo "<td>".number_format($reg_consulta_banco['cantidad'])."</td>";
echo "<td>".number_format($reg_consulta_banco['precio_unitario'], 2)."</td>";
echo "<td>".number_format($reg_consulta_banco['bruto'], 2)."</td>";
echo "<td>".number_format($v_comision[$i], 2)."</td>";
echo "<td>".number_format($reg_consulta_banco['aportacion'], 2)."</td>";
echo "<td>".number_format($reg_consulta_banco['utilidad_pani'], 2)."</td>";
echo "</tr>";

$acumulado_cantidad   += $reg_consulta_banco['cantidad'];
$acumulado_bruto      += $reg_consulta_banco['bruto'];
$acumulado_descuento  += $reg_consulta_banco['descuento'];
$acumulado_neto       += $reg_consulta_banco['neto'];
$acumulado_comision   += $reg_consulta_banco['comision_bancaria'] + $reg_consulta_banco['descuento'];
$acumulado_aportacion += $reg_consulta_banco['aportacion'];
$acumulado_utilidad   += $reg_consulta_banco['utilidad_pani'];
$acumulado_credito    += $reg_consulta_banco['credito_pani'];


$i++;
}

echo "<tr class = 'alert alert-success'>";
echo "<th colspan = '3'>TOTALES</th>";
echo "<th>".number_format($acumulado_cantidad)."</th>";
echo "<th></th>";
echo "<th>".number_format($acumulado_bruto,2)."</th>";
echo "<th>".number_format($acumulado_comision,2)."</th>";
echo "<th>".number_format($acumulado_aportacion,2)."</th>";
echo "<th>".number_format($acumulado_utilidad,2)."</th>";
echo "</tr>";


echo "</table>";

echo "</div>";
echo "</div>";
////// VENTAS BANCO BOLSAS /////////
////////////////////////////////////






//////////////////////////////////
////// VENTAS BANCO NUMEROS/////////


$consulta_banco = mysqli_query($conn," SELECT id_sorteo , YEAR(fecha_venta) as year , MONTH(fecha_venta) as mes , precio_unitario ,SUM(cantidad) as cantidad,  SUM(total_bruto) as bruto, SUM(descuento) as descuento, SUM(total_neto) as neto,  SUM(comision_bancaria) as comision_bancaria , SUM(utilidad_pani) as utilidad_pani, SUM(credito_pani) as credito_pani , SUM(aportacion) as aportacion FROM transaccional_ventas_general WHERE estado_venta = 'APROBADO' AND cod_producto = 2 AND id_entidad = 3 AND date(fecha_venta) BETWEEN '$fecha_i' AND '$fecha_f' GROUP BY YEAR(fecha_venta), MONTH(fecha_venta), id_sorteo ");



if ($consulta_banco === FALSE) {
echo mysqli_error();
}

echo "<div class = 'card' style = 'margin-left:5px;margin-right:5px;'>";
echo "<div class = 'card-header bg-primary text-white'><h4 align = 'center'>BANRURAL NUMEROS</h4></div>";
echo "<div class = 'card-body'>";


echo "<table class = 'table table-bordered'>";
echo "<tr>";
echo "<th>AÑO</th>";
echo "<th>MES</th>";
echo "<th>SORTEO</th>";
echo "<th>CANTIDAD</th>";
echo "<th>PRECIO NUMERO</th>";
echo "<th>TOTAL BRUTO</th>";
echo "<th>COMISION + DESCUENTO</th>";
echo "<th>APORTACION</th>";
echo "<th>UTILIDAD PANI</th>";
echo "</tr>";

$i = 0;

$acumulado_cantidad   = 0;
$acumulado_bruto      = 0;
$acumulado_descuento  = 0;
$acumulado_neto       = 0;
$acumulado_comision   = 0;
$acumulado_aportacion = 0;
$acumulado_utilidad   = 0;
$acumulado_credito    = 0;


while ($reg_consulta_banco = mysqli_fetch_array($consulta_banco)) {

$indice = $reg_consulta_banco['year'].$reg_consulta_banco['mes'].$reg_consulta_banco['id_sorteo'];
$nombre_mes = funcion_nombre_mes($reg_consulta_banco['mes']);

if (isset($v_indice[0])) {

if (in_array($indice,$v_indice)) {

$posicion = array_search($indice, $v_indice);

$v_cantidad[$posicion]        = $v_cantidad[$posicion]        + $reg_consulta_banco['cantidad'];
$v_bruto[$posicion]           = $v_bruto[$posicion]           + $reg_consulta_banco['bruto'];
$v_descuento[$posicion]       = $v_descuento[$posicion]       + $reg_consulta_banco['descuento'] + $reg_consulta_banco['comision_bancaria'];
$v_neto[$posicion]            = $v_neto[$posicion]            + $reg_consulta_banco['neto'];
$v_comision[$posicion]        = $v_comision[$posicion]        + $reg_consulta_banco['comision_bancaria'];
$v_aportacion[$posicion]      = $v_aportacion[$posicion]	    +	$reg_consulta_banco['aportacion'];
$v_utilidad[$posicion]        = $v_utilidad[$posicion]        + $reg_consulta_banco['utilidad_pani'];
$v_credito[$posicion]         = $v_credito[$posicion]         + $reg_consulta_banco['credito_pani'];

}else{

end($v_indice);
$key = key($v_indice);
$key++;

$v_indice[$key]          = $indice;
$v_year[$key]            = $reg_consulta_banco['year'];
$v_mes[$key]             = $reg_consulta_banco['mes'];
$v_sorteo[$key]          = $reg_consulta_banco['id_sorteo'];
$v_cantidad[$key]        = $reg_consulta_banco['cantidad'];
$v_precio_unitario[$key] = $reg_consulta_banco['precio_unitario'];
$v_bruto[$key]           = $reg_consulta_banco['bruto'];
$v_descuento[$key]       = $reg_consulta_banco['descuento'] + $reg_consulta_banco['comision_bancaria'];
$v_neto[$key]            = $reg_consulta_banco['neto'];
$v_comision[$key]        = $reg_consulta_banco['comision_bancaria'];
$v_aportacion[$key]      = $reg_consulta_banco['aportacion'];
$v_utilidad[$key]        = $reg_consulta_banco['utilidad_pani'];
$v_credito[$key]         = $reg_consulta_banco['credito_pani'];


}

}else{

$v_indice[0]          = $indice;
$v_year[0]            = $reg_consulta_banco['year'];
$v_mes[0]             = $reg_consulta_banco['mes'];
$v_sorteo[0]          = $reg_consulta_banco['id_sorteo'];
$v_cantidad[0]        = $reg_consulta_banco['cantidad'];
$v_precio_unitario[0] = $reg_consulta_banco['precio_unitario'];
$v_bruto[0]           = $reg_consulta_banco['bruto'];
$v_descuento[0]       = $reg_consulta_banco['descuento'] + $reg_consulta_banco['comision_bancaria'];
$v_neto[0]            = $reg_consulta_banco['neto'];
$v_comision[0]        = $reg_consulta_banco['comision_bancaria'];
$v_aportacion[0]      = $reg_consulta_banco['aportacion'];
$v_utilidad[0]        = $reg_consulta_banco['utilidad_pani'];
$v_credito[0]         = $reg_consulta_banco['credito_pani'];

}

$comision_descuento = $reg_consulta_banco['descuento'] + $reg_consulta_banco['comision_bancaria'];

echo "<tr>";
echo "<td>".$reg_consulta_banco['year']."</td>";
echo "<td>".$nombre_mes."</td>";
echo "<td>".$reg_consulta_banco['id_sorteo']."</td>";
echo "<td>".number_format($reg_consulta_banco['cantidad'])."</td>";
echo "<td>".number_format($reg_consulta_banco['precio_unitario'], 2)."</td>";
echo "<td>".number_format($reg_consulta_banco['bruto'], 2)."</td>";
echo "<td>".number_format($comision_descuento, 2)."</td>";
echo "<td>".number_format($reg_consulta_banco['aportacion'], 2)."</td>";
echo "<td>".number_format($reg_consulta_banco['utilidad_pani'], 2)."</td>";
echo "</tr>";

$acumulado_cantidad   += $reg_consulta_banco['cantidad'];
$acumulado_bruto      += $reg_consulta_banco['bruto'];
$acumulado_descuento  += $reg_consulta_banco['descuento'] + $reg_consulta_banco['comision_bancaria'];
$acumulado_neto       += $reg_consulta_banco['neto'];
$acumulado_comision   += $reg_consulta_banco['comision_bancaria'];
$acumulado_aportacion += $reg_consulta_banco['aportacion'];
$acumulado_utilidad   += $reg_consulta_banco['utilidad_pani'];
$acumulado_credito    += $reg_consulta_banco['credito_pani'];


$i++;
}

echo "<tr class = 'alert alert-success'>";
echo "<th colspan = '3'>TOTALES</th>";
echo "<th>".number_format($acumulado_cantidad)."</th>";
echo "<th></th>";
echo "<th>".number_format($acumulado_bruto,2)."</th>";
echo "<th>".number_format($acumulado_descuento,2)."</th>";
echo "<th>".number_format($acumulado_aportacion,2)."</th>";
echo "<th>".number_format($acumulado_utilidad,2)."</th>";
echo "</tr>";


echo "</table>";

echo "</div>";
echo "</div>";
////// VENTAS BANCO NUMEROS /////////
////////////////////////////////////



//////////////////////////////////
////// VENTAS FVP NUMEROS/////////



$consulta_fvp = mysqli_query($conn,"SELECT id_sorteo , YEAR(fecha_venta) as year , MONTH(fecha_venta) as mes , SUM(total_bruto)/SUM(cantidad) as precio_unitario ,SUM(cantidad) as cantidad,  SUM(total_bruto) as bruto, SUM(descuento) as descuento, SUM(total_neto) as neto, SUM(comision_bancaria) as comision_bancaria , SUM(utilidad_pani) as utilidad_pani, SUM(credito_pani) as credito_pani  , SUM(aportacion) as aportacion   FROM ( 

  SELECT id_sorteo , fecha_venta ,  cantidad, total_bruto,  descuento, total_neto,  comision_bancaria, utilidad_pani, credito_pani, aportacion FROM transaccional_ventas WHERE estado_venta = 'APROBADO' AND date(fecha_venta) BETWEEN '$fecha_i' AND '$fecha_f' AND cod_producto = 2 

  UNION ALL  

  SELECT id_sorteo , fecha_venta ,  cantidad, total_bruto,  descuento, total_neto,  comision_bancaria, utilidad_pani, credito_pani, aportacion  FROM transaccional_ventas_ajuste WHERE estado_venta = 'APROBADO' AND date(fecha_venta) BETWEEN '$fecha_i' AND '$fecha_f'  AND cod_producto = 2 ) as t  GROUP BY YEAR(fecha_venta), MONTH(fecha_venta), id_sorteo  ");



if ($consulta_fvp === FALSE) {
echo mysqli_error();
}

echo "<div class = 'card' style = 'margin-left:5px;margin-right:5px;'>";
echo "<div class = 'card-header bg-primary text-white'><h4 align = 'center'>OTRAS ENTIDADES NUMEROS</h4></div>";
echo "<div class = 'card-body'>";


echo "<table class = 'table table-bordered'>";
echo "<tr>";
echo "<th>AÑO</th>";
echo "<th>MES</th>";
echo "<th>SORTEO</th>";
echo "<th>CANTIDAD</th>";
echo "<th>PRECIO NUMERO</th>";
echo "<th>TOTAL BRUTO</th>";
echo "<th>COMISION + DESCUENTO</th>";
echo "<th>APORTACION</th>";
echo "<th>UTILIDAD PANI</th>";
echo "</tr>";

$i = 0;

$acumulado_cantidad   = 0;
$acumulado_bruto      = 0;
$acumulado_descuento  = 0;
$acumulado_neto       = 0;
$acumulado_comision   = 0;
$acumulado_aportacion = 0;
$acumulado_utilidad   = 0;
$acumulado_credito    = 0;

while ($reg_consulta_banco = mysqli_fetch_array($consulta_fvp)) {

$indice = $reg_consulta_banco['year'].$reg_consulta_banco['mes'].$reg_consulta_banco['id_sorteo'];
$nombre_mes = funcion_nombre_mes($reg_consulta_banco['mes']);

if (isset($v_indice[0])) {

if (in_array($indice,$v_indice)) {

$posicion = array_search($indice, $v_indice);

$v_cantidad[$posicion]        = $v_cantidad[$posicion]        + $reg_consulta_banco['cantidad'];
$v_bruto[$posicion]           = $v_bruto[$posicion]           + $reg_consulta_banco['bruto'];
$v_descuento[$posicion]       = $v_descuento[$posicion]       + $reg_consulta_banco['descuento'] + $reg_consulta_banco['comision_bancaria'];
$v_neto[$posicion]            = $v_neto[$posicion]            + $reg_consulta_banco['neto'];
$v_comision[$posicion]        = $v_comision[$posicion]        + $reg_consulta_banco['comision_bancaria'];
$v_aportacion[$posicion]      = $v_aportacion[$posicion]	    +	$reg_consulta_banco['aportacion'];
$v_utilidad[$posicion]        = $v_utilidad[$posicion]        + $reg_consulta_banco['utilidad_pani'];
$v_credito[$posicion]         = $v_credito[$posicion]         + $reg_consulta_banco['credito_pani'];

}else{

end($v_indice);
$key = key($v_indice);
$key++;

$v_indice[$key]          = $indice;
$v_year[$key]            = $reg_consulta_banco['year'];
$v_mes[$key]             = $reg_consulta_banco['mes'];
$v_sorteo[$key]          = $reg_consulta_banco['id_sorteo'];
$v_cantidad[$key]        = $reg_consulta_banco['cantidad'];
$v_precio_unitario[$key] = $reg_consulta_banco['precio_unitario'];
$v_bruto[$key]           = $reg_consulta_banco['bruto'];
$v_descuento[$key]       = $reg_consulta_banco['descuento'] + $reg_consulta_banco['comision_bancaria'];
$v_neto[$key]            = $reg_consulta_banco['neto'];
$v_comision[$key]        = $reg_consulta_banco['comision_bancaria'];
$v_aportacion[$key]      = $reg_consulta_banco['aportacion'];
$v_utilidad[$key]        = $reg_consulta_banco['utilidad_pani'];
$v_credito[$key]         = $reg_consulta_banco['credito_pani'];


}

}else{

$v_indice[0]          = $indice;
$v_year[0]            = $reg_consulta_banco['year'];
$v_mes[0]             = $reg_consulta_banco['mes'];
$v_sorteo[0]          = $reg_consulta_banco['id_sorteo'];
$v_cantidad[0]        = $reg_consulta_banco['cantidad'];
$v_precio_unitario[0] = $reg_consulta_banco['precio_unitario'];
$v_bruto[0]           = $reg_consulta_banco['bruto'];
$v_descuento[0]       = $reg_consulta_banco['descuento'] + $reg_consulta_banco['comision_bancaria'];
$v_neto[0]            = $reg_consulta_banco['neto'];
$v_comision[0]        = $reg_consulta_banco['comision_bancaria'];
$v_aportacion[0]      = $reg_consulta_banco['aportacion'];
$v_utilidad[0]        = $reg_consulta_banco['utilidad_pani'];
$v_credito[0]         = $reg_consulta_banco['credito_pani'];

}


$comision_descuento = $reg_consulta_banco['descuento'] + $reg_consulta_banco['comision_bancaria'];

echo "<tr>";
echo "<td>".$reg_consulta_banco['year']."</td>";
echo "<td>".$nombre_mes."</td>";
echo "<td>".$reg_consulta_banco['id_sorteo']."</td>";
echo "<td>".number_format($reg_consulta_banco['cantidad'])."</td>";
echo "<td>".number_format($reg_consulta_banco['precio_unitario'], 2)."</td>";
echo "<td>".number_format($reg_consulta_banco['bruto'], 2)."</td>";
echo "<td>".number_format($comision_descuento, 2)."</td>";
echo "<td>".number_format($reg_consulta_banco['aportacion'], 2)."</td>";
echo "<td>".number_format($reg_consulta_banco['utilidad_pani'], 2)."</td>";
echo "</tr>";

$acumulado_cantidad   += $reg_consulta_banco['cantidad'];
$acumulado_bruto      += $reg_consulta_banco['bruto'];
$acumulado_descuento  += $reg_consulta_banco['descuento'] + $reg_consulta_banco['comision_bancaria'];
$acumulado_neto       += $reg_consulta_banco['neto'];
$acumulado_comision   += $reg_consulta_banco['comision_bancaria'];
$acumulado_aportacion += $reg_consulta_banco['aportacion'];
$acumulado_utilidad   += $reg_consulta_banco['utilidad_pani'];
$acumulado_credito    += $reg_consulta_banco['credito_pani'];


$i++;
}

echo "<tr class = 'alert alert-success'>";
echo "<th colspan = '3'>TOTALES</th>";
echo "<th>".number_format($acumulado_cantidad)."</th>";
echo "<th></th>";
echo "<th>".number_format($acumulado_bruto,2)."</th>";
echo "<th>".number_format($acumulado_descuento,2)."</th>";
echo "<th>".number_format($acumulado_aportacion,2)."</th>";
echo "<th>".number_format($acumulado_utilidad,2)."</th>";
echo "</tr>";

echo "</table>";

echo "</div>";
echo "</div>";

}

}


?>


</form>
</body>

<?php

?>
