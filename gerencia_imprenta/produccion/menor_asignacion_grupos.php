<?php

require("../../template/header.php");

$id_sorteo 	 = $_GET['s'];
$id_sorteo_a = $id_sorteo - 1; 
$bandera = 0;
?>

<script type="text/javascript">
function add_grupo(numero){

series = parseInt(document.getElementById("input_cantidad_series").value);

if (series > 0) {

sin_grupo_cantidad = parseInt(document.getElementById("sin_grupo_cantidad"+numero).value);

if (series  >  sin_grupo_cantidad) {

swal('', 'Las series del grupo son mayores a las del numero seleccionado.', 'error')
	
}else{

for (var i = 1; i <= 10; i++) {

if (document.getElementById("input_numero_"+i).value == '') {

document.getElementById("input_numero_"+i).value = numero;	
restante = sin_grupo_cantidad - series;
document.getElementById("sin_grupo_cantidad"+numero).value = restante;

span = document.getElementById("sin_grupo"+numero);
span.style.background = "grey";
span.disabled = true; 

i = 11;

}

}

}



}else{
swal('Debe ingresar las series que tendra el prupo','','error')
}

}



function remove_grupo(id){

series = parseInt(document.getElementById("input_cantidad_series").value);

num = document.getElementById("input_numero_"+id).value;
document.getElementById("input_numero_"+id).value = '';

sin_grupo_cantidad = parseInt(document.getElementById("sin_grupo_cantidad"+num).value);
restante = sin_grupo_cantidad + series;
document.getElementById("sin_grupo_cantidad"+num).value = restante;

span = document.getElementById("sin_grupo"+num);
span.style.background = "#337ab7"; 
span.disabled = false; 


}


</script>



<?php


$pendientes = mysqli_query($conn,"SELECT numero, SUM(cantidad) as cant FROM  sorteos_menores_solicitudes_extras WHERE id_sorteo = '$id_sorteo'  GROUP BY numero ORDER BY numero ASC ");
$anteriores = mysqli_query($conn,"SELECT numero, SUM(cantidad) as cant FROM  sorteos_menores_solicitudes_extras WHERE id_sorteo = '$id_sorteo_a'  GROUP BY numero ORDER BY numero ASC ");


$i = 0;
while ($reg_pendientes = mysqli_fetch_array($pendientes)) {
$matriz[$i][0] = $reg_pendientes['numero'];
$matriz[$i][1] = $reg_pendientes['cant'];
$i++;
}

$i = 0;
while ($reg_anteriores = mysqli_fetch_array($anteriores)) {
$matriz2[$i][0] = $reg_anteriores['numero'];
$matriz2[$i][1] = $reg_anteriores['cant'];

$i++;
}

$i = 0;
while (isset($matriz2[$i][0])) {

if ($matriz[$i][0] == $matriz2[$i][0] AND $matriz[$i][1] == $matriz2[$i][1]) {
$bandera = 0;
}else{
$bandera = 1;
$i = 10000;
}

$i++;
}


if ($bandera == 0) {

//////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////// IMPORTACION DE AGRUPACION ANTERIOR /////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////


require("./menor_asignacion_grupos_db.php");

?>


<form method="POST">

<?php

$result = mysqli_query($conn," SELECT * FROM sorteos_menores WHERE id = '$id_sorteo' ");
 
if ($result != null){
while ($row = mysqli_fetch_array($result)) {
$sorteo = $row['no_sorteo_men'] ;
$fecha_sorteo = $row['fecha_sorteo'] ;
$series = $row['series'];
$descripcion = $row['descripcion_sorteo_men'];
}
}

$result2 = mysqli_query($conn,"SELECT * FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo' ");
$conteo_extras_asignados = mysqli_num_rows($result2);
$num_extras_anteriores = mysqli_query($conn,"SELECT * FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo_a' ORDER BY grupo ASC, numero ASC ");


$consulta_total_asignado = mysqli_query($conn,"SELECT SUM(cantidad) as cantidad FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo' ");
$ob_total_asignado 		 = mysqli_fetch_object($consulta_total_asignado);
$total_asignado 		 = $ob_total_asignado->cantidad;

$consulta_total_solicitado  = mysqli_query($conn,"SELECT SUM(cantidad) as cantidad FROM sorteos_menores_solicitudes_extras WHERE id_sorteo = '$id_sorteo' ");
$ob_total_solicitado 		= mysqli_fetch_object($consulta_total_solicitado);
$total_solicitado 		 	= $ob_total_solicitado->cantidad;


echo "<input name = 'hidden_sorteo' type = 'hidden' value = '".$id_sorteo."' >";

echo '
<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center"  style="color:black;"  >AGRUPACION DE PRODUCCION EXTRA LOTERIA MENOR SORTEO # '.$id_sorteo.'</h2>';

echo '<p style="color:black;" align= "center">';
if ($total_solicitado > $total_asignado) {
echo "Aun hay numeros extras sin una agrupacion, por favor proceda a  ";
echo "<a class = 'btn btn-primary' href = './menor_asignacion_grupos_manual.php?s=".$id_sorteo."' >Realizar creacion manual de grupos</a>";
}else{
echo "<b>Produccion extra agrupada en su totalidad</b><br>";
echo "<b>Total Extra: ".number_format($total_solicitado)."</b>";
}

echo "</p>";

echo '</section>';


?>


<table width="100%" >
	<tr>
		<td width="49%">

<div class="panel panel-primary">
<div class="panel-heading">
<h3 align="center">Agrupacion Sorteo <?php echo $id_sorteo_a;?></h3>		
</div>
<div class="panel-body">

<table class="table table-hover table-bordered" width="100%" >
<tr>
	<thead>
		<th width="25%">Numero Inicial</th>
		<th width="25%">Numero Final</th>
		<th width="25%">Cantidad</th>				
		<th width="25%">Grupo</th>				
	</thead>
</tr>
</table>

<div style=" overflow: scroll;height:400px ;align:left;width:99%" align="left" >
<div style="width:100%" >

<table class="table table-hover table-bordered" width="100%" id="numeros_adicionales">

<?php 
$filas = 0;
$conteo_extra_por_asignar = 0;

if ($conteo_extras_asignados == 0) {

while ($extras_anteriores = mysqli_fetch_array($num_extras_anteriores)) {
echo "<tr>";
echo "<td>";
echo "<input class = 'form-control' type= 'text' name = 'numero_i[]' value = '".$extras_anteriores['numero']."' readonly>";
echo "</td>";
echo "<td>";
echo "<input class = 'form-control' type= 'text' name = 'numero_f[]' value = '".$extras_anteriores['numero']."' readonly> ";
echo "</td>";
echo "<td>";
echo "<input class = 'form-control' type= 'text' name = 'cantidad[]' value = '".$extras_anteriores['cantidad']."' readonly>";
echo "</td>";
echo "<td>";
echo "<input class = 'form-control' type= 'text' name = 'grupo[]' value = '".$extras_anteriores['grupo']."' readonly>";
echo "</td>";
echo "</tr>";
$filas ++;

$conteo_extra_por_asignar += $extras_anteriores['cantidad'];

}

}


?>
</table>
</div>
</div>


</div>
<div align="center" class="panel-footer">
<input type="submit" id="guardar" name="guardar" value="Guardar Numeros Extras" class="btn btn-primary">
</div>
</div>

			
</td>
<td width="2%"></td>
<td width="49%">

<div class="panel panel-primary">
<div class="panel-heading">
<h3 align="center">Agrupacion Sorteo <?php echo $id_sorteo;?></h3>
</div>

<div class="panel-body">

<table width="100%" class="table table-hover table-bordered">
<thead>
<tr>
  <th width="5%"></th>
  <th width="24%">Numero</th>
  <th width="24%">Cantidad</th>
  <th width="24%">Grupo</th>
  <th width="25%">Accion</th>
</tr>
</thead>
</table>

<div style=" overflow: scroll;height:400px ;float:right;width:99%" align="right"> 
 <div class="div_inicio" align="right" style=" width:100%"> 

<table width="100%" class="table table-hover table-bordered">
<tbody>
<?php
$i = 0;
while ($row = mysqli_fetch_array($result2)) {
echo '<tr>';
echo '<td width = "5%" > <input type="checkbox" name="seleccion'.$i.'" value="'.$row['id'].'"></td>';
echo '<td width = "24%" >'.$row['numero'].'</td>';
echo '<td width = "24%" >'.number_format($row['cantidad']).'</td>';
echo '<td width = "24%" >'.$row['grupo'].'</td>';
echo "<td width = '25%' align = 'center'>";
echo "<button class = 'btn btn-danger' name = 'eliminar_extra' value = '".$row['id']."' >x</button>";
echo "</td></tr>";
$i ++;
}

echo "<input type = 'hidden' name = 'conteo_asignado' value = '".$i."' >";

?>
</tbody>
</table>

</div>
</div>

	
</div>

<div align="center" class="panel-footer">	
<input type="submit" class="btn btn-danger" name="multiple_eliminado" value="Eliminar Seleccion">	
</div>

</div>

			
</td>
</tr>
</table>

<input type="hidden" id = 'id_oculto' name="id_oculto" value="<?php echo $id_sorteo; ?>">    
<br>
</form>

<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////// IMPORTACION DE AGRUPACION ANTERIOR /////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////



}else{

//header("Location: menor_asignacion_grupos_manual.php?s=".$id_sorteo);

}

?>