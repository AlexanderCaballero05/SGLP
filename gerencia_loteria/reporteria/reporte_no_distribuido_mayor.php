<?php 

require("./template/header.php");

$consulta_sorteos = mysql_query("SELECT * FROM sorteos_mayores ORDER BY id DESC ");

?>

<style type="text/css">
@media print
{
#non-printable { display: none; }
#printable { display: block; }
}
</style>

<form method="POST">
	

<br>
<a class="btn btn-info" style="width:100%" role="button" data-toggle="collapse" href="#collapse3" aria-expanded="false" aria-controls="collapse3" id="non-printable">
<h3> Parametros de Seleccion </h3>
</a>

<div  class="collapse" style = "width:100%"  id="collapse3">
<div class="well" align="center">

<table style = "width:75%" class="table table-bordered">
  <tr>
    <th>Seleccion de Sorteo</th>
    <th>Accion</th>
  </tr>
  <tr>
    <td align="center">

<select class="form-control" name="sorteo" >
<?php
while ($row2 = mysql_fetch_array($consulta_sorteos)) {
echo '<option value = "'.$row2['id'].'">'.$row2['no_sorteo_may'].'</option>' ;
}
?>
</select>       
    </td>


    <td align="center">
<input  type="submit" name="seleccionar" class="btn btn-primary" value="Seleccionar"> 
    </td>
  </tr>
</table>
</div>
</div>

</form>

<?php

if (isset($_POST['seleccionar'])) {

$id_sorteo 	 = $_POST['sorteo'];

$info_sorteo  = mysql_query("SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo' ");
$ob_sorteo 	  = mysql_fetch_object($info_sorteo);
$fecha_sorteo = $ob_sorteo->fecha_sorteo;
$mezcla = $ob_sorteo->mezcla;

echo "<div class = 'alert alert-info' aling = 'center'>
<h3 align = 'center'>
INVENTARIO DE LOTERIA MAYOR SIN DISTRIBUCION <br>
SORTEO ".$id_sorteo." <br>
A JUGARSE ".$fecha_sorteo."
</h3>
</div>";

$inventario = mysql_query("SELECT * FROM sorteos_mezclas WHERE id_empresa IS NULL AND id_sorteo = '$id_sorteo' ORDER BY num_mezcla ASC ");

$current_date = date("d-m-Y H:i:s a");

echo "Fecha Emision: ".$current_date;
echo '<table class="table table-bordered" ><tr>';

$m = 0;
$c = 0;
while ($mezclas = mysql_fetch_array($inventario)) {

$num_mezcla  = $mezclas['num_mezcla'];
$cod_factura = $mezclas['cod_factura'];

$detalle_mezcla = mysql_query("SELECT * FROM sorteos_mezclas_rangos WHERE id_sorteo = $id_sorteo AND num_mezcla = $num_mezcla ");

if ($c < 5) {
echo "<td>";

echo "<table class = 'table table-bordered' style = 'font-size:10px'>";
echo "<tr>";
echo "<th> Mezcla No. ".$num_mezcla."</th>";
echo "</tr>";
while ($detalle = mysql_fetch_array($detalle_mezcla)) {
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

while ($detalle = mysql_fetch_array($detalle_mezcla)) {

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

echo "<h4 class = 'alert alert-info' align = 'center'>Total Paquetes sin Distribucion: ".$m." </h4>";

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
Jose Wilfredo Quezada
</td>

<td width = "25%">
</td>

</tr>
'; 


}

?>