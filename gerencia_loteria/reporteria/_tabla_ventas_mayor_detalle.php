<?php 
require('./template/header.php');
require('./_tabla_ventas_mayor_detalle_excel.php');

//REPORTE VENTAS

$parametros = explode("/", $_GET['dat']);
$id_sorteo = $parametros[0];
$id_seccional = $parametros[1];
$fecha_inicial = $parametros[2];
$fecha_final = $parametros[3];
$id_empresa = $_SESSION['id_empresa'];

$a = 0;
$b = 0;

$info_sorteo = mysql_query("SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo' ");
$objeto = mysql_fetch_object($info_sorteo);
$mezcla = $objeto->mezcla;


$info_seccional = mysql_query("SELECT * FROM fvp_seccionales WHERE id = '$id_seccional' ");
$objeto_s = mysql_fetch_object($info_seccional);
$detalle_seccional  = $objeto_s->nombre;
$total_vendido 		= 0;
$total_no_vendido 	= 0;
$total_asignado 	= 0;

echo "<form method= 'POST'>
<hr>
<div class = 'alert alert-info' align = 'center'>
<h3 align = 'center'>Sorteo ".$id_sorteo."</h3>

 Inventario Asignado a ".$detalle_seccional."</div>";

$paquetes = mysql_query("SELECT DISTINCT num_mezcla  FROM sorteos_mezclas_rangos WHERE id_sorteo = '$id_sorteo' AND id_seccional = '$id_seccional' ");

$billetes_vendidos = mysql_query("SELECT * FROM fvp_detalles_ventas_mayor WHERE  estado_venta = 'APROBADO' AND id_sorteo = '$id_sorteo' ");

$i = 0;
while ($reg_billletes_vendidos = mysql_fetch_array($billetes_vendidos)) {
$v_vendido[$i] = $reg_billletes_vendidos['billete'];
$i++;
}


while ($paquete = mysql_fetch_array($paquetes)) {
$total_asignado++;
$num_paquete =  $paquete['num_mezcla'];
$j = 0; 
$vendido = 0;
$no_vendido = 0;

$consulta_paquetes  = mysql_query("SELECT * FROM sorteos_mezclas_rangos WHERE id_sorteo = '$id_sorteo' AND num_mezcla = '$num_paquete' AND id_seccional = '$id_seccional'  ");


echo '<br>
<a style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse'.$num_paquete.'" aria-expanded="false" aria-controls="collapse'.$num_paquete.'">
  Numero de Paquete # '.$num_paquete.'
</a>';

echo "<br>";

echo '<div class="collapse" id="collapse'.$num_paquete.'">';

echo "<div class = 'well'>
	  <table class = 'table table-hover table-bodered ' >";
echo "<tr><td colspan = '10' align = 'center'> Billetes en Paquete </td></tr>";
echo "<tr>";


while ($fila_paquetes = mysql_fetch_array($consulta_paquetes)) {
$rango_inicial = $fila_paquetes['rango'];
$rango_final = $rango_inicial + $mezcla;

while ($rango_inicial < $rango_final) {

if (in_array($rango_inicial, $v_vendido)) {
$bandera_vendido = 1;
}else{
$bandera_vendido = 0;	
}


if ($j == 10) {
echo "</tr><tr>";
$j = 0;
}

if ($bandera_vendido == 1) {
echo "<td style = 'background-color:green'>".$rango_inicial."</td>";
$vendido  = $vendido + 1;
}else{
echo "<td>".$rango_inicial."</td>";
$no_vendido  = $no_vendido + 1;
}

$rango_inicial ++;
$j++;
}

}

echo "</tr>";
echo "</table>";
echo "<hr>";

$total = $vendido + $no_vendido;
echo "Total Vendido: ".$vendido."<br>";
echo "Total No Vendido: ".$no_vendido."<br>";
echo "Total: ".$total."<br>";
$total_vendido = $total_vendido + $vendido;
$total_no_vendido = $total_no_vendido + $no_vendido;

echo "</div></div>";

}

echo "<br>";
echo "<br>";

$total_asignado = $total_asignado * 100;
echo "<br>";
echo "<br>";
echo "<p align = 'center'>";
echo "Total Vendido: ".$total_vendido."<br>";
echo "Total No Vendido: ".$total_no_vendido."<br>";
echo "Total: ".$total_asignado."<br>";
echo "</p>";

$parametros_excel = $id_sorteo."/".$id_seccional."/".$fecha_inicial."/".$fecha_final;

echo "<p align = 'center'><button name ='excel_pani_c' value = '".$parametros_excel."'  type = 'submit' class = 'btn btn-success'>Exportar a Excel</button>";


//$consulta_estado = 

echo " <a class ='btn btn-primary'  target = '_blanck' href = './_tabla_ventas_mayor_detalle_print.php?v1=".$parametros_excel."' >Imprimir Reporte</a></p>";

?>

<br><br>
<br><br>


</form>
