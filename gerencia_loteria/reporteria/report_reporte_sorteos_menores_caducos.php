<?php

require("./template/header.php");

date_default_timezone_set('America/Tegucigalpa');
$current_date = date("d-m-Y h:i:s a");

?>

<script type="text/javascript" src="../assets/js/jquery.table2excel.js"></script>

<style type="text/css">
@media print
{

   @page
   {
    size: landscape;
  }


#non-printable { display: none; }
#printable { display: block; }
}
</style>


<section style=" background-color:#ededed;">
<br>
<h4  align="center" style="color:black; " id="titulo"  >INFORME DE VENTA, PROVISION, PAGO Y CADUCIDAD DE LOTERIA MENOR</h4> 
<button class="btn btn-info" style="width: 100%" id="non-printable" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
  Seleccion de Parametros
</button>
</section>


<form method="POST">


<div class="collapse" id="collapseOne" style="width: 100%"  align="center"  style="background-color: grey">
<div class="card" style="margin-left: 150px; margin-right: 150px;">
<div class="card card-body" id="non-printable"  style="width: 100%;" >
<div class="input-group " style="; width: 70%;">
<div class="input-group-prepend"><span class="input-group-text">FECHA INICIO: </span></div>
<input type="date" class="form-control" min="2018-01-01"  name="fecha_i" style="margin-right: 5px">

<div class="input-group-prepend"><span class="input-group-text">FECHA FIN: </span></div>
<input type="date" class="form-control" min="2018-01-01" name="fecha_f" style="margin-right: 5px">

<div class="input-group-append">
<button class="btn btn-success" type="submit" name="seleccionar" > SELECCIONAR</button>     
</div>

</div>
</div>
</div>
</div>


</form>

<br>

<?php 

if (isset($_POST['seleccionar'])) {


$fecha_i = $_POST['fecha_i'];
$fecha_f = $_POST['fecha_f'];


echo "<h3 class = 'alert alert-info' align = 'center'>DEL ".$fecha_i." AL ".$fecha_f." </h3>";

echo "<br>";

//echo "<p align = 'center'><span class = 'btn btn-success' onclick = 'generar_excel()' >GENERAR EXCEL</span></p>";

?>
<p align = 'center'><a class = 'btn btn-success' target="_blank" href="reporte_sorteos_menores_caducos_excel.php?fi=<?php echo $fecha_i ?>&ff=<?php echo $fecha_f ?>" >GENERAR EXCEL</a></p>
<?php 


echo "<table class = 'table table-bordered  table-responsive' style = '' >";
echo "<thead>";


echo "<tr>";
echo "<th>";
echo "SORTEO";
echo "</th>";

echo "<th>";
echo "PREMIADO POR";
echo "</th>";


echo "<th>";
echo "NUMERO";
echo "</th>";

echo "<th>";
echo "SERIE";
echo "</th>";

echo "<th>";
echo "PRODUCCION";
echo "</th>";


echo "<th style = 'text-align:center' >";
echo "VENTA UND";
echo "</th>";

echo "<th style = 'text-align:center'>";
echo "VENTA LPS";
echo "</th>";


echo "<th style = 'text-align:center'>";
echo "NO VENDIDO UND";
echo "</th>";

echo "<th style = 'text-align:center'>";
echo "NO VENDIDO LPS";
echo "</th>";


echo "<th style = 'text-align:center'>";
echo "PREMIADO UND";
echo "</th>";

echo "<th style = 'text-align:center'>";
echo "PREMIADO LPS";
echo "</th>";


echo "<th style = 'text-align:center'>";
echo "PAGADO UND";
echo "</th>";

echo "<th style = 'text-align:center'>";
echo "PAGADO LPS";
echo "</th>";


echo "<th style = 'text-align:center'>";
echo "NO PAGADO UND";
echo "</th>";

echo "<th style = 'text-align:center'>";
echo "NO PAGADO LPS";
echo "</th>";


echo "<th style = 'text-align:center'>";
echo "CADUCO UND";
echo "</th>";

echo "<th style = 'text-align:center'>";
echo "CADUCO LPS";
echo "</th>";


echo "</tr>";

echo "</thead>";

echo "<tbody>";
















$query_sorteos=mysqli_query($conn, "SELECT id as sorteo from sorteos_menores where fecha_sorteo between '$fecha_i' and '$fecha_f' order by sorteo asc");

if (!mysqli_num_rows($query_sorteos) > 0)
{
     echo "<div class='alert alert-danger'> Ha selecciona un rango de fechas donde no hay sorteos</div>";
}
else
{
    while ( $row_sorteos = mysqli_fetch_array($query_sorteos, MYSQLI_ASSOC ))
    {


$sorteo = $row_sorteos['sorteo'];

$c_info_ventas = mysqli_query($conn, "SELECT  a.precio_unitario ,a.series, COUNT(b.id) as vendido  FROM sorteos_menores as a INNER JOIN ventas_distribuidor_menor as b ON a.id = b.sorteo  WHERE a.id  = '$sorteo'  ");


$ob_info_ventas  = mysqli_fetch_object($c_info_ventas);
$precio_unitario = $ob_info_ventas->precio_unitario;

$c_info_ventas_extra = mysqli_query($conn, "SELECT  SUM(cantidad)  as extra FROM sorteos_menores_num_extras   WHERE id_sorteo  = '$sorteo'  ");

$ob_extra = mysqli_fetch_object($c_info_ventas_extra);
$cantidad_extra = $ob_extra->extra;


$produccion 	 = $ob_info_ventas->series;
$produccion 	 = $produccion * 100;
$produccion 	 += $cantidad_extra; 	

$venta_unds 	 = $ob_info_ventas->vendido;
$venta_lps 	 	 = $precio_unitario * $venta_unds;

$no_vendido_unds = $produccion - $venta_unds;
$no_vendido_lps  = $no_vendido_unds * $precio_unitario;



unset($array_numeros);
unset($array_series);
$query = mysqli_query($conn, "SELECT numero_premiado_menor FROM sorteos_menores_premios where sorteos_menores_id=$sorteo and premios_menores_id in(1,3);");

while($row=mysqli_fetch_array($query ,MYSQLI_ASSOC))  
{   $array_numeros[] = $row['numero_premiado_menor'];}

$query_series = mysqli_query($conn, "SELECT numero_premiado_menor FROM sorteos_menores_premios where sorteos_menores_id=$sorteo and (premios_menores_id =2 or premios_menores_id >3);");

while($row_series=mysqli_fetch_array($query_series, MYSQLI_ASSOC)) 
{ $array_series[] = $row_series['numero_premiado_menor']; } 


$query_pago_combinaciones= mysqli_query($conn,"SELECT sorteo, tipo_premio , numero, serie, 1 as cantidad, netopayment FROM archivo_pagos_menor WHERE sorteo=$sorteo and tipo_premio in ('PC') order by sorteo, netopayment desc");
$columnas='';
while ($row_pago_combinaciones= mysqli_fetch_array($query_pago_combinaciones,MYSQLI_ASSOC)) 
{


$numero=$row_pago_combinaciones['numero'];
$serie= $row_pago_combinaciones['serie'];
$cantidad_vendida =$row_pago_combinaciones['cantidad'];
$neto_vendido= $row_pago_combinaciones['netopayment'];

$cantidad_pagada =0;
$neto_pagado= 0;
$cantidad_caducada= 0;
$neto_caducado= 0;

$query_pago_combinaciones_pagado= mysqli_query($conn,"SELECT count(serie) cantidad, neto FROM menor_pagos_detalle WHERE sorteo=$sorteo and numero=$numero and serie= $serie and transactionstate in (1,3)");
while ($row_pago_combinaciones_pagado= mysqli_fetch_array($query_pago_combinaciones_pagado,MYSQLI_ASSOC)) 
{
$cantidad_pagada =$row_pago_combinaciones_pagado['cantidad'];
$neto_pagado= $row_pago_combinaciones_pagado['neto'];
$cantidad_caducada= $cantidad_vendida - $cantidad_pagada;
$neto_caducado= $neto_vendido - $neto_pagado;


$columnas .= "<tr>
<td>".$sorteo."</td>
<td>COMBINACION NUMERO Y SERIE</td>
<td>".$numero."</td>
<td>".$serie."</td>
<td>".number_format($produccion)."</td>

<td>".number_format($venta_unds)."</td>
<td>".number_format($venta_lps, 2)."</td>

<td>".number_format($no_vendido_unds)."</td>
<td>".number_format($no_vendido_lps, 2)."</td>

<td>".number_format($cantidad_vendida)."</td>
<td>".number_format($neto_vendido, 2)."</td>

<td>".number_format($cantidad_pagada)."</td>
<td>".number_format($neto_pagado, 2)."</td>
<td>".number_format($cantidad_caducada)."</td>
<td>".number_format($neto_caducado,2)."</td>
<td>".number_format($cantidad_caducada)."</td>
<td>".number_format($neto_caducado,2)."</td>
</tr>";

}

}


/////////////////////////    PAGOS DE DERECHO Y DE REVES


$query_pago_derecho_reves= mysqli_query($conn,"SELECT  numero, null as serie, count(*) cantidad, sum(netopayment) netopayment FROM `archivo_pagos_menor` WHERE sorteo=$sorteo and tipo_premio in ('PD', 'PR', 'PM') group by sorteo, tipo_premio order by sorteo desc");

while ($row_pago_derecho_reves= mysqli_fetch_array($query_pago_derecho_reves,MYSQLI_ASSOC)) 
{


$numero=$row_pago_derecho_reves['numero'];
$serie= $row_pago_derecho_reves['serie'];
$cantidad_vendida_derecho_reves =$row_pago_derecho_reves['cantidad'];
$neto_vendido_derecho_reves= $row_pago_derecho_reves['netopayment'];

$cantidad_pagada_derecho_reves =0;
$neto_pagado_derecho_reves= 0;
$cantidad_caducada_derecho_reves= 0;
$neto_caducado_derecho_reves= 0;

$query_pago_derecho_reves_pagado= mysqli_query($conn,"SELECT count(serie) cantidad, sum(neto) neto FROM menor_pagos_detalle WHERE sorteo=$sorteo and numero =$numero and serie not in ( ".implode(',',$array_series)." ) and transactionstate in (1,3)");
while ($row_pago_derecho_reves_pagado= mysqli_fetch_array($query_pago_derecho_reves_pagado,MYSQLI_ASSOC)) 
{
$cantidad_pagada_derecho_reves =$row_pago_derecho_reves_pagado['cantidad'];
$neto_pagado_derecho_reves= $row_pago_derecho_reves_pagado['neto'];
$cantidad_caducada_derecho_reves= $cantidad_vendida_derecho_reves - $cantidad_pagada_derecho_reves;
$neto_caducado_derecho_reves= $neto_vendido_derecho_reves - $neto_pagado_derecho_reves;


$columnas .= "<tr>
<td>".$sorteo."</td>
<td>NUMERO DE DERECHO O REVES</td>
<td>".$numero."</td>
<td>".$serie."</td>
<td>".number_format($produccion)."</td>

<td>".number_format($venta_unds)."</td>
<td>".number_format($venta_lps,2)."</td>

<td>".number_format($no_vendido_unds)."</td>
<td>".number_format($no_vendido_lps,2)."</td>

<td>".number_format($cantidad_vendida_derecho_reves)."</td>
<td>".number_format($neto_vendido_derecho_reves,2)."</td>
<td>".number_format($cantidad_pagada_derecho_reves)."</td>
<td>".number_format($neto_pagado_derecho_reves,2)."</td>
<td>".number_format($cantidad_caducada_derecho_reves)."</td>
<td>".number_format($neto_caducado_derecho_reves,2)."</td>
<td>".number_format($cantidad_caducada_derecho_reves)."</td>
<td>".number_format($neto_caducado_derecho_reves,2)."</td>

</tr>";

}

}


/////////////////////////    PAGOS DE series


$query_pago_serie= mysqli_query($conn,"SELECT sorteo, tipo_premio , null as numero, serie, count(*) cantidad, sum(netopayment) netopayment FROM `archivo_pagos_menor` WHERE sorteo=$sorteo and tipo_premio in ('PS') group by sorteo, serie order by sorteo desc");

while ($row_pago_serie= mysqli_fetch_array($query_pago_serie,MYSQLI_ASSOC)) 
{


$numero=$row_pago_serie['numero'];
$serie= $row_pago_serie['serie'];
$cantidad_vendida_serie = $row_pago_serie['cantidad'];
$neto_vendido_serie     = $row_pago_serie['netopayment'];

$cantidad_pagada_serie =0;
$neto_pagado_serie= 0;
$cantidad_caducada_serie= 0;
$neto_caducado_serie= 0;

$query_pago_series= mysqli_query($conn,"SELECT count(serie) cantidad, sum(neto) neto FROM menor_pagos_detalle WHERE sorteo=$sorteo  and serie=$serie and transactionstate in (1,3) and numero not in ( ".implode(',',$array_numeros)." )");
while ($row_pago_series= mysqli_fetch_array($query_pago_series,MYSQLI_ASSOC)) 
{
$cantidad_pagada_series =$row_pago_series['cantidad'];
$neto_pagado_series= $row_pago_series['neto'];
$cantidad_caducada_series = $cantidad_vendida_serie - $cantidad_pagada_series;
$neto_caducado_series     = $neto_vendido_serie - $neto_pagado_series;


$columnas .= "<tr>
<td>".$sorteo."</td>
<td>SOLO SERIE</td>
<td>".$numero."</td>
<td>".$serie."</td>
<td>".number_format($produccion)."</td>

<td>".number_format($venta_unds)."</td>
<td>".number_format($venta_lps,2)."</td>

<td>".number_format($no_vendido_unds)."</td>
<td>".number_format($no_vendido_lps,2)."</td>

<td>".number_format($cantidad_vendida_serie)."</td>
<td>".number_format($neto_vendido_serie,2)."</td>
<td>".number_format($cantidad_pagada_series)."</td>
<td>".number_format($neto_pagado_series,2)."</td>
<td>".number_format($cantidad_caducada_series)."</td>
<td>".number_format($neto_caducado_series,2)."</td>
<td>".number_format($cantidad_caducada_series)."</td>
<td>".number_format($neto_caducado_series,2)."</td>
</tr>";

}

}



echo $columnas;

        
    }
       
}














echo "</tbody>";

echo "</table>";


}

?>