<?php 

require("./template/header.php");

$consulta_sorteos = mysql_query("SELECT * FROM sorteos_menores ORDER BY id DESC ");

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
echo '<option value = "'.$row2['id'].'">'.$row2['no_sorteo_men'].'</option>' ;
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

$id_sorteo   = $_POST['sorteo'];

$info_sorteo  = mysql_query("SELECT * FROM sorteos_menores WHERE id = '$id_sorteo' ");
$ob_sorteo    = mysql_fetch_object($info_sorteo);
$fecha_sorteo = $ob_sorteo->fecha_sorteo;
$series = $ob_sorteo->series;


echo "<div class = 'alert alert-info' aling = 'center'>
<h3 align = 'center'>
INVENTARIO DE LOTERIA MENOR SIN DISTRIBUCION <br>
SORTEO ".$id_sorteo." <br>
A JUGARSE ".$fecha_sorteo."
</h3>
</div>";

//$inventario = mysql_query("SELECT * FROM sorteos_mezclas WHERE id_empresa IS NULL AND id_sorteo = '$id_sorteo' ORDER BY num_mezcla ASC ");

$inventario_bolsas         = mysql_query("SELECT * FROM menor_seccionales_bolsas WHERE id_sorteo = '$id_sorteo' ORDER BY serie_inicial ASC ");
$inventario_bolsas_numeros = mysql_query("SELECT * FROM menor_seccionales_numeros WHERE id_sorteo = '$id_sorteo' AND origen = 'bolsas' GROUP BY serie_inicial ORDER BY serie_inicial ASC ");

$i = 0;
while ($reg_inventario_bolsas = mysql_fetch_array($inventario_bolsas)) {
$serie_inicial = $reg_inventario_bolsas['serie_inicial'];
$serie_final   = $reg_inventario_bolsas['serie_final'];

while ($serie_inicial <= $serie_final) {
$v_asignado_bolsas[$i] = $serie_inicial;
$serie_inicial++;
$i++;
}

}


while ($reg_inventario_bolsas_numeros = mysql_fetch_array($inventario_bolsas_numeros)) {
$serie_inicial = $reg_inventario_bolsas_numeros['serie_inicial'];
$serie_final   = $reg_inventario_bolsas_numeros['serie_final'];

while ($serie_inicial <= $serie_final) {
$v_asignado_bolsas[$i] = $serie_inicial;
$serie_inicial++;
$i++;
}

}



$i = 0;
$serie_no = 0;
while ($serie_no < $series) {

if (isset($v_asignado_bolsas[0])) {

if (!in_array($serie_no, $v_asignado_bolsas)) {
$v_no_asignado[$i] = $serie_no;
$i++;
}

}else{

$v_no_asignado[$i] = $serie_no;
$i++;

}

$serie_no ++;
}



if (isset($v_no_asignado[0])) {

$i = 0;
$j = 0;

$v_rango_no_i[$j] = $v_no_asignado[0];
$v_rango_no_f[$j] = $v_no_asignado[0];
while (isset($v_no_asignado[$i])) {


if (isset($v_no_asignado[$i + 1])) {

if ($v_no_asignado[$i + 1] == $v_no_asignado[$i] + 1) {
$v_rango_no_f[$j] = $v_no_asignado[$i + 1];
}else{

$j++;
$v_rango_no_i[$j] = $v_no_asignado[$i + 1];
$v_rango_no_f[$j] = $v_no_asignado[$i + 1];


}

}

$i++;
}

}



$current_date = date("d-m-Y H:i:s a");

echo "Fecha Emision: ".$current_date;
echo '<table class="table table-bordered" >';

echo "
<tr>
<th>Serie Inicial</th>
<th>Serie Final</th>
<th>Cantidad</th>
</tr>";

$i = 0;
$tt = 0;
while (isset($v_rango_no_i[$i])) {

$cantidad_no = $v_rango_no_f[$i] - $v_rango_no_i[$i] + 1;
$tt += $cantidad_no;
echo "<tr><td>".$v_rango_no_i[$i]."</td><td>".$v_rango_no_f[$i]."</td><td>".number_format($cantidad_no)."</td></tr>";

$i++;
}


echo '</table>';

echo "<h4 class = 'alert alert-info' align = 'center'>Total Bolsas sin Distribucion:  ".number_format($tt)."</h4>";

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