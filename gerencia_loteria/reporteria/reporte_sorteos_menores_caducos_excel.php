<?php

require("../con.php");

$fecha_i = $_GET['fi'];
$fecha_f = $_GET['ff'];


$file="REPORTE_GENERAL.xls";



$test = "<table >";
$test .= "<thead>";

$test .= "<tr style = 'background-color:blue'>";
$test .= "<th rowspan = '2'>";
$test .= "SORTEO";
$test .= "</th>";


$test .= "<th rowspan = '2'>";
$test .= "PREMIADO POR";
$test .= "</th>";

$test .= "<th rowspan = '2'>";
$test .= "NUMERO";
$test .= "</th>";

$test .= "<th rowspan = '2'>";
$test .= "SERIE";
$test .= "</th>";


$test .= "<th rowspan = '2'>";
$test .= "PRODUCCION";
$test .= "</th>";

$test .= "<th style = 'text-align:center' colspan = '2' >";
$test .= "VENTA";
$test .= "</th>";


$test .= "<th style = 'text-align:center' colspan = '2'>";
$test .= "NO VENDIDO ";
$test .= "</th>";


$test .= "<th style = 'text-align:center' colspan = '2'>";
$test .= "PREMIADO ";
$test .= "</th>";


$test .= "<th style = 'text-align:center' colspan = '2'>";
$test .= "PAGADO ";
$test .= "</th>";


$test .= "<th style = 'text-align:center' colspan = '2'>";
$test .= "NO PAGADO ";
$test .= "</th>";


$test .= "<th style = 'text-align:center' colspan = '2'>";
$test .= "CADUCO ";
$test .= "</th>";



$test .= "<tr>";




$test .= "<th style = 'text-align:center' >";
$test .= "UND";
$test .= "</th>";

$test .= "<th style = 'text-align:center'>";
$test .= "LPS";
$test .= "</th>";


$test .= "<th style = 'text-align:center'>";
$test .= "UND";
$test .= "</th>";

$test .= "<th style = 'text-align:center'>";
$test .= "LPS";
$test .= "</th>";


$test .= "<th style = 'text-align:center'>";
$test .= "UND";
$test .= "</th>";

$test .= "<th style = 'text-align:center'>";
$test .= "LPS";
$test .= "</th>";


$test .= "<th style = 'text-align:center'>";
$test .= "UND";
$test .= "</th>";

$test .= "<th style = 'text-align:center'>";
$test .= "LPS";
$test .= "</th>";


$test .= "<th style = 'text-align:center'>";
$test .= "UND";
$test .= "</th>";

$test .= "<th style = 'text-align:center'>";
$test .= "LPS";
$test .= "</th>";


$test .= "<th style = 'text-align:center'>";
$test .= "UND";
$test .= "</th>";

$test .= "<th style = 'text-align:center'>";
$test .= "LPS";
$test .= "</th>";


$test .= "</tr>";

$test .= "</thead>";

$test .= "<tbody>";















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


$test .= "<tr>
<td>".$sorteo."</td>
<td>COMBINACION NUMERO Y SERIE</td>
<td>".$numero."</td>
<td>".$serie."</td>
<td>".$produccion."</td>

<td>".$venta_unds."</td>
<td>".$venta_lps."</td>

<td>".$no_vendido_unds."</td>
<td>".$no_vendido_lps."</td>

<td>".$cantidad_vendida."</td>
<td>".$neto_vendido."</td>

<td>".$cantidad_pagada."</td>
<td>".$neto_pagado."</td>
<td>".$cantidad_caducada."</td>
<td>".$neto_caducado."</td>
<td>".$cantidad_caducada."</td>
<td>".$neto_caducado."</td>
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


$test .= "<tr>
<td>".$sorteo."</td>
<td>NUMERO DERECHO O REVES</td>
<td>".$numero."</td>
<td>".$serie."</td>
<td>".$produccion."</td>

<td>".$venta_unds."</td>
<td>".$venta_lps."</td>

<td>".$no_vendido_unds."</td>
<td>".$no_vendido_lps."</td>

<td>".$cantidad_vendida_derecho_reves."</td>
<td>".$neto_vendido_derecho_reves."</td>
<td>".$cantidad_pagada_derecho_reves."</td>
<td>".$neto_pagado_derecho_reves."</td>
<td>".$cantidad_caducada_derecho_reves."</td>
<td>".$neto_caducado_derecho_reves."</td>
<td>".$cantidad_caducada_derecho_reves."</td>
<td>".$neto_caducado_derecho_reves."</td>

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


$test .= "<tr>
<td>".$sorteo."</td>
<td>SOLO SERIE</td>
<td>".$numero."</td>
<td>".$serie."</td>
<td>".$produccion."</td>

<td>".$venta_unds."</td>
<td>".$venta_lps."</td>

<td>".$no_vendido_unds."</td>
<td>".$no_vendido_lps."</td>

<td>".$cantidad_vendida_serie."</td>
<td>".$neto_vendido_serie."</td>
<td>".$cantidad_pagada_series."</td>
<td>".$neto_pagado_series."</td>
<td>".$cantidad_caducada_series."</td>
<td>".$neto_caducado_series."</td>
<td>".$cantidad_caducada_series."</td>
<td>".$neto_caducado_series."</td>
</tr>";

}

}




        
    }
       
}















$test .= "</tbody>";

$test .= "</table>";



header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file");
echo $test;
?>