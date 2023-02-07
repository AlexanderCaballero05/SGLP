<?php 

require("../../template/header.php");




if (isset($_GET['s'])) {

$current_date = date("d-m-Y H:i:s a");

$id_sorteo 	 = $_GET['s'];

$info_sorteo  = mysqli_query($conn,"SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo' ");
$ob_sorteo 	  = mysqli_fetch_object($info_sorteo);
$fecha_sorteo = $ob_sorteo->fecha_sorteo;
$mezcla = $ob_sorteo->mezcla;

echo '
<section style="background-color:#ededed;">
<br>
<h3 align="center">

INVENTARIO DE LOTERIA MAYOR SIN DISTRIBUCION <br>
SORTEO '.$id_sorteo.' <br>
A JUGARSE '.$fecha_sorteo.'

</h3>
<br>

Fecha Emision: '.$current_date.'

</section>';



$inventario = mysqli_query($conn,"SELECT * FROM sorteos_mezclas WHERE id_empresa IS NULL AND id_sorteo = '$id_sorteo' ORDER BY num_mezcla ASC ");

echo '<table class="table table-bordered" ><tr>';

$m = 0;
$c = 0;
while ($mezclas = mysqli_fetch_array($inventario)) {

$num_mezcla  = $mezclas['num_mezcla'];
$cod_factura = $mezclas['cod_factura'];

$detalle_mezcla = mysqli_query($conn,"SELECT * FROM sorteos_mezclas_rangos WHERE id_sorteo = $id_sorteo AND num_mezcla = $num_mezcla ");

if ($c < 5) {
echo "<td>";

echo "<table class = 'table table-bordered' style = 'font-size:10px'>";
echo "<tr>";
echo "<th> Mezcla No. ".$num_mezcla."</th>";
echo "</tr>";
while ($detalle = mysqli_fetch_array($detalle_mezcla)) {
$rango_final_mezcla = $detalle['rango'] + $mezcla - 1;
echo "<tr>";
echo "<td>".$detalle['rango']." - ".$rango_final_mezcla."</td>";
echo "</tr>";
}


echo "</table>";

echo "</td>";
}else{

echo "</tr>";		
echo "<tr>";	

echo "<td>";

echo "<table class = 'table table-bordered'>";
echo "<tr>";
echo "<th> Mezcla No. ".$num_mezcla."</th>";
echo "</tr>";

while ($detalle = mysqli_fetch_array($detalle_mezcla)) {

$rango_final_mezcla = $detalle['rango'] + $mezcla - 1;
echo "<tr>";
echo "<td>".$detalle['rango']." - ".$rango_final_mezcla."</td>";
echo "</tr>";
}

echo "</table>";

echo "</td>";

$c = 0;	
}

$c++;
$m++;
}

echo '</tr>	</table>';

echo "<h4 style='background-color:#ededed;' align = 'center'>Total Paquetes sin Distribución: ".$m." </h4>";

echo "<br>";

echo "<br>";
echo "<br>";

echo '
<table width = "100%">
<tr>
<td width = "25%">
</td>
<td width = "50%" align = "center">
<hr> 
Tesorero <br>
Daniel Fúnez
</td>

<td width = "25%">
</td>

</tr>
'; 


}

?>
