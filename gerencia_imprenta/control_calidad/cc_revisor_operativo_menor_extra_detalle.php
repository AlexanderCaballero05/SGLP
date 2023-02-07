<?php
require('../../template/header.php');
require('./cc_revisor_operativo_menor_extra_detalle_db.php');

$id_sorteo = $_GET['id_sort'];
$num_asignado = $_GET['num_asig'];
$id_revisor = $_GET['id_rev'];
$revision = $_GET['revision'];
$num_revision =  $revision;

$info_sorteo = mysql_query("SELECT * FROM sorteos_menores WHERE id =  '$id_sorteo' ");
$ob_sorteo = mysql_fetch_object($info_sorteo);
$no_sorteo = $ob_sorteo->no_sorteo_men;
$fecha_sorteo = $ob_sorteo->fecha_sorteo;
$series = $ob_sorteo->series;
$desde_registro = $ob_sorteo->desde_registro;

$id_revisor = $_SESSION['id_usuario'];


//$info_cierre = mysql_query("SELECT * FROM "); 

if ($revision == 1) {

$inventario_revisor = mysql_query("SELECT * FROM cc_revisores_sorteos_menores_extras WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$id_revisor' AND numero = '$num_asignado' ");
}else{
$inventario_revisor = mysql_query("SELECT * FROM cc_revisores_sorteos_menores_extras_control WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$id_revisor' AND numero_revision = '$revision' ORDER BY serie ASC ");
}

$v_numero_revisiones = mysql_query("SELECT * FROM cc_revisores_sorteos_menores_extras_control WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$id_revisor' AND num_lista =  '$num_asignado' ");


$i = 0;
while ($v_numero_fila = mysql_fetch_array($v_numero_revisiones)) {
$v_numero[$i] = $v_numero_fila['grupo'].$v_numero_fila['serie'];
$i ++;
}



// INFO RANGO
$info_rangos = mysql_query("SELECT * FROM cc_revisores_sorteos_menores_extras WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$id_revisor' AND numero = '$num_asignado' ");
$ob_info_rangos = mysql_fetch_object($info_rangos);
$grupo = $ob_info_rangos->grupo;

$parametros_rango = $id_sorteo."_".$id_revisor."_".$num_asignado."_".$num_revision."_".$grupo;

?>
<div style="width: 100%" align="center">
<form method="POST">

<div class="alert alert-info">
	<h3 align="center">Sorteo <?php echo $no_sorteo;?> | Fecha <?php echo $fecha_sorteo;?></h3>
</div>

<div style="width:90%;" >
<div class="well" style="width:100%">
<div class="alert alert-info">
	<h3 align="center">Revision No. <?php echo $revision;?> de Loteria Menor</h3>
</div>


<table class="table table-bordered">
	<tr>
		<th width="25%">Desde</th>
		<th width="25%">Hasta</th>
		<th width="25%">R.E.</th>
		<th width="25%">Accion</th>
	</tr>
	<tr>
		<td><input type= 'number' name="desde" style="width:80%"></input></td>
		<td><input type= 'number' name="hasta" style="width:80%"></input></td>
		<td align = 'center'><input class="form form-control" type = 'checkbox' name = 'e_rango' ></td>
		<td><button style="width:100%" type="submit" class = 'btn btn-danger' value="<?php echo $parametros_rango;?>" name="reprobar_rango">Reprobar</button></td>
	</tr>	
</table>
<hr>

<table id="table_id1" class="table table-bordered">
	<thead>
		<tr>
			<th style="width:20%">Numero</th>		
			<th style="width:20%">Serie</th>
			<th style="width:20%">Registro</th>			
			<th style="width:10%">R.E.</th>
			<th style="width:30%">Accion</th>
		</tr>
	</thead>
	<tbody>

<?php
$c = 0;
$numeros = '';
if ($revision == 1 ) {
while ($inventario = mysql_fetch_array($inventario_revisor)) {
$serie_inicial = $inventario['serie_inicial'];
$serie_final = $inventario['serie_final'];
$grupo = $inventario['grupo'];
$contador_r = 0;
$registros_grupos  = mysql_query("SELECT * FROM sorteos_menores_num_extras WHERE grupo = '$grupo' AND id_sorteo = '$id_sorteo' ");

while ($registro_grupo = mysql_fetch_array($registros_grupos)) {
$numeros = $numeros.",".$registro_grupo['numero'];
$cantidad = $registro_grupo['cantidad'];
$v_registros[$contador_r] =  $registro_grupo['registro_inicial'];
$contador_r++;
}

$serie_inicial_i = $serie_inicial; 
while ($serie_inicial <= 6800) {

$dif = $serie_inicial - $serie_inicial_i;

echo "<tr>";

echo "<td>".$numeros."</td>";
echo "<td>".$serie_inicial."</td>";
echo "<td>";

$z = 0;
$concat_registro = '';
while (isset($v_registros[$z])) {
$registro = $v_registros[$z] + $dif;
$concat_registro = $concat_registro.",".$registro;
echo $registro.',';
if ($z == 4) {
echo "<br>";
}
$z++;
}
echo "</td>";


//$para_check = $num_ini." - ".$num_fin.$serie_inicial;
//echo $para_check;

echo "<td align = 'center'><input type = 'checkbox' name = 'e".$c."' class = 'form form-control' ></td>";

$concatenado = $grupo.$serie_inicial;

$concatenado_reprobar = $id_sorteo."_".$id_revisor."_".$num_asignado."_".$revision."_".$grupo."_".$serie_inicial."_".$c."_".$numeros."_".$concat_registro;

if (isset($v_numero[0])) {

if (in_array($concatenado, $v_numero)) {
echo "<td align = 'center'><b style = 'color:red'> REPROBADO </b></td>";
}else{
echo "<td><button style = 'width:100%' type = 'submit' class = 'btn btn-danger' name = 'reprobar' value = '".$concatenado_reprobar."'>Reprobar</button></td>";
}
}else{
echo "<td><button style = 'width:100%' type = 'submit' class = 'btn btn-danger' name = 'reprobar' value = '".$concatenado_reprobar."'>Reprobar</button></td>";
}

echo "</tr>";

$c++;
$serie_inicial++;
}
}

}else{

$c = 0;
while ($inventario = mysql_fetch_array($inventario_revisor)) {

$grupo = $inventario['grupo'];
$contador_r = 0;
$registros_grupos  = mysql_query("SELECT * FROM sorteos_menores_num_extras WHERE grupo = '$grupo' AND id_sorteo = '$id_sorteo' ");

while ($registro_grupo = mysql_fetch_array($registros_grupos)) {
$numeros = $numeros.",".$registro_grupo['numero'];
$cantidad = $registro_grupo['cantidad'];
$v_registros[$contador_r] =  $registro_grupo['registro_inicial'];
$contador_r++;
}

echo "<tr>";
echo "<td>".$inventario['detalle_numeros']."</td>";
echo "<td>".$inventario['serie']."</td>";
echo "<td>".$inventario['detalle_registros']."</td>";


echo "<td align = 'center'><input type = 'checkbox' name = 'e".$c."' class = 'form form-control' ></td>";
echo "<td>";

if ($inventario['estado'] != 'PENDIENTE') {

if ($inventario['estado'] == 'REPROBADO') {
echo "<b style = 'color:red'>".$inventario['estado']."</b>";	
}elseif ($inventario['estado'] == 'APROBADO') {
echo "<b style = 'color:blue'>".$inventario['estado']."</b>";	
}

}else{

$concatenado_reprobar = $inventario['id']."_".$c;


echo "<button style = 'width:100%' type = 'submit' class = 'btn btn-danger' name = 'reprobar_nuevamente' value = '".$concatenado_reprobar."'>Reprobar</button>";

}

echo "</td>";
echo "</tr>";

$c++;
}

}

$parametros_cierre_revision = $id_sorteo."_".$id_revisor."_".$num_asignado."_".$num_revision;

?>

</tbody>
</table>

</div>
<p align="center">
<button type = 'submit' value="<?php echo $parametros_cierre_revision;?>" name ='finalizar_revision'  class="btn btn-primary">Finalizar Revision No. <?php echo $num_revision;?></button>
</p>
<br><br>
</div>


</form>
</div>