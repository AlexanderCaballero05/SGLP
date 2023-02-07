<?php
require('../../template/header.php');

$sorteos_menores = mysqli_query($conn,"SELECT * FROM sorteos_menores WHERE control_calidad = 'SI' ORDER BY no_sorteo_men DESC ");
$revisores_select = mysqli_query($conn,"SELECT * FROM pani_usuarios WHERE estados_id = '1' AND roles_usuarios_id = '2' AND areas_id = '5' ORDER BY nombre_completo ASC ");

if ($sorteos_menores===false) {
echo mysqli_error();
}


?>

<form method="POST">

<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >CONTROL DE REVISION DE LOTERIA MENOR</h2> 
<br>
</section>

<br>

<div class="card" style="margin-left: 10px;margin-right: 10px">
<div class="card-header" align="center" id="non-printable">

<div style="width: 50%">
<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span  class="input-group-text">Sorteo: </span></div>
<select name="sorteo" class="form-control" >
<?php 
while ($sorteo = mysqli_fetch_array($sorteos_menores)) {
echo "string";
echo "<option value = '".$sorteo['id']."'>".$sorteo['no_sorteo_men']."</option>";
}
?>
</select>

<div class="input-group-prepend"><span  class="input-group-text">Revisor: </span></div>
<select name="select_revisor" class="form-control" >
<?php
while ($revisor_select = mysqli_fetch_array($revisores_select)) {
echo "<option value='".$revisor_select['id']."'>".$revisor_select['nombre_completo']."</option>";
}
?>
</select>

<input name="seleccionar" type="submit" class="btn btn-primary" value="Seleccionar" style="margin-left: 10px"></input>
</div>
</div>
	
</div>	
<div class="card-body">
	



<?php
if (isset($_POST['seleccionar'])) {

$bolsas_completas   = 0;
$bolsas_incompletas = 0;
$bolsas_pendientes  = 0;
$cantidad_asignada  = 0;


$id_sorteo = $_POST['sorteo'];
$_SESSION['id_sorteo_cc'] = $id_sorteo;
$id_revisor = $_POST['select_revisor'];

$info_revisor   = mysqli_query($conn ,"SELECT * FROM pani_usuarios WHERE id = '$id_revisor' ");
$ob_revisor     = mysqli_fetch_object($info_revisor); 
$nombre_revisor = $ob_revisor->nombre_completo;

echo "<h4 align = 'center' class = 'alert alert-info'>
DETALLE DE BOLSAS SORTEO ".$id_sorteo."
<br>
REVISOR ".$nombre_revisor."</h4>";

$asignaciones =  mysqli_query($conn,"SELECT * FROM `cc_revisores_sorteos_menores` WHERE `id_sorteo` = $id_sorteo AND `id_revisor` = $id_revisor ");

while ($reg_asignaciones = mysqli_fetch_array($asignaciones)) {

$serie_inicial     = $reg_asignaciones['serie_inicial'];
$serie_final   	   = $reg_asignaciones['serie_final'];
$cantidad_asignada = $serie_final - $serie_inicial + 1;
$num_lista 		   = $reg_asignaciones['numero'];

echo "<table class = 'table table-bordered' >";
echo "<tr><th colspan = '3'>Inventario de Loteria Asignada Con Numero de Lista ".$num_lista."</th></tr>";
echo "<tr><th>Serie Inicial</th><th>Serie Final</th><th>Cantidad</th></tr>";
echo "<tr><td>".$serie_inicial."</td><td>".$serie_final."</td><td>".number_format($cantidad_asignada)."</td></tr>";
echo "</table>";


$consulta_max_bolsa_revisada = mysqli_query($conn, "SELECT MAX(serie) as max FROM cc_revisores_sorteos_menores_control WHERE id_sorteo = '$id_sorteo' AND serie BETWEEN '$serie_inicial' AND '$serie_final' AND estado != 'APROBADO' AND num_lista = '$num_lista'  ");
$ob_max_bolsa_revisada = mysqli_fetch_object($consulta_max_bolsa_revisada);
$max_bolsa_revisada    = $ob_max_bolsa_revisada->max; 


$consulta_reprobados = mysqli_query($conn, "SELECT numero, serie FROM cc_revisores_sorteos_menores_control WHERE id_sorteo = '$id_sorteo' AND serie BETWEEN '$serie_inicial' AND '$serie_final' AND estado != 'APROBADO' AND num_lista = '$num_lista' AND  CONCAT(numero,serie) NOT IN (SELECT CONCAT(numero,serie) FROM  cc_revisores_sorteos_menores_control WHERE id_sorteo = '$id_sorteo' AND serie BETWEEN '$serie_inicial' AND '$serie_final' AND estado = 'APROBADO' AND num_lista = '$num_lista'  ) GROUP BY CONCAT(numero,serie) ORDER BY numero, serie ASC ");


$consulta_primera_revision = mysqli_query($conn, "SELECT * FROM cc_produccion_menor WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND numero_revisor = '$num_lista' AND numero_revision = 1 AND estado_revisor = 'FINALIZADA' ");

$finalizada_revision = false;
if (mysqli_num_rows($consulta_primera_revision) > 0) {
$finalizada_revision = true;
}


$i = 0;
while ($reg_consulta_reprobados =  mysqli_fetch_array($consulta_reprobados)) {
$v_concat_reprobado[$i] = $reg_consulta_reprobados['numero'].$reg_consulta_reprobados['serie'];
$v_serie_reprobado[$i]  = $reg_consulta_reprobados['serie'];
$i++;
}





echo "<div style = 'width: 100%; height: 250px; overflow-y: scroll; '>";

echo "<table class = 'table table-bordered table-sm' >";
echo "<tr><th colspan = '8'>ESTADO DE BOLSAS ASIGNADAS</th></tr>";


$col = 0;
$serie_completa = true;

$bolsas_completas   = 0;
$bolsas_incompletas = 0;
$bolsas_pendientes  = 0;

while ($serie_inicial <= $serie_final) {

if (isset($v_serie_reprobado[0])) {

if (in_array($serie_inicial, $v_serie_reprobado)) {
$serie_completa = false;
}else{
$serie_completa = true;	
}

}


if ($col == 0) {
echo "<tr>";
}elseif ($col == 8) {
echo "</tr>";
$col = 0;
}


echo "<td width = '12.5%'>";
echo "<table class = 'table table-bordered' style = 'font-size:9px'>";
echo "<tr><th>Serie ".$serie_inicial."</th></tr>";


if ($serie_completa == TRUE AND $finalizada_revision == TRUE) {
$bolsas_completas++;
echo "<tr><td class = 'alert alert-success'>COMPLETA</td></tr>";

}elseif($serie_completa == TRUE AND $finalizada_revision == FALSE){

$bolsas_pendientes++;
echo "<tr><td class = 'alert alert-secondary'>PENDIENTE</td></tr>";

}elseif ($serie_completa == FALSE) {

$bolsas_incompletas++;
$n = 0;
$desc = '|';
while ($n <= 9) {
if (in_array($n.$serie_inicial, $v_concat_reprobado)) {
$desc .= $n."0-".$n."9"." | ";
}
$n++;
}

echo "<tr><td class = 'alert alert-danger'>EN REP.: ".$desc."</td></tr>";

}



echo "</table>";
echo "</td>";

$col++;

$serie_inicial++;
}

echo "</table>";
echo "</div>";

}


echo "<table class = 'table table-bordered' >";
echo "<tr><th colspan = '4'>RESUMEN DE ESTADO DE INVENTARIO</th></tr>";
echo "<tr><th>BOLSAS COMPLETAS</th><th>BOLSAS INCOMPLETAS</th><th>BOLSAS PENDIENTES DE REVISION</th><th>TOTAL BOLSAS</th></tr>";
echo "<tr><td>".number_format($bolsas_completas)."</td><td>".number_format($bolsas_incompletas)."</td><td>".number_format($bolsas_pendientes)."</td><td>".number_format($cantidad_asignada)."</td></tr>";
echo "</table>";

}
?>

</div>
</div>

</form>