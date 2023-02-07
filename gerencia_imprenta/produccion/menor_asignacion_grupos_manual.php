<?php

require("../../template/header.php");
require("./menor_asignacion_grupos_manual_db.php");

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

if (restante == 0) {
span = document.getElementById("sin_grupo"+numero);
span.style.background = "grey";
span.disabled = true; 	
}

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



///////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////// CODIGO PARA ASIGNACION DE GRUPOS A PRODUCCCIONES EXTRAS /////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////

$solicitud_extras = mysqli_query($conn,"SELECT numero, SUM(cantidad) as cantidad FROM  sorteos_menores_solicitudes_extras WHERE id_sorteo = '$id_sorteo' AND estado = 1 GROUP BY numero ORDER BY cantidad DESC,  numero ASC ");

echo "<div class = 'alert alert-info'>Los numeros extras de sorteo ".$id_sorteo." difieren del sorteo anterior, por tanto, no se puede realizar la importacion de agrupacion anterior. </div>";


echo "<table width = '100%'>";
echo "<tr>";
echo "<td width = '25%' valign='top' >";

echo "<div class = 'card' >";
echo "<div class = 'card-header'>";
echo "<h4>NUMEROS SIN GRUPO SORTEO ".$id_sorteo."</h4>";
echo "</div>";

echo "<div class = 'card-body'>";
echo "<table class = 'table table-bordered'>";
echo "<tr>";
echo "<th width = '33%'>Numero</th>";
echo "<th width = '33%'>Cantidad</th>";
echo "<th width = '33%'>Accion</th>";
echo "</tr>";

$i = 0;
$total_solicitud = 0;
$total_asignado  = 0;

while ($reg_extras = mysqli_fetch_array($solicitud_extras)) {
$num_consulta	   = $reg_extras['numero'];


$numeros_agrupados 	= mysqli_query($conn,"SELECT numero, SUM(cantidad) as cantidad FROM  sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo' AND numero = '$num_consulta' GROUP BY numero ORDER BY cantidad DESC,  numero ASC ");
if (mysqli_num_rows($numeros_agrupados) > 0) {
$ob_numeros_agrupados = mysqli_fetch_object($numeros_agrupados);
$cantidad_agrupada 	  = $ob_numeros_agrupados->cantidad;
}else{
$cantidad_agrupada = 0;	
}

$total_solicitud += $reg_extras['cantidad'];
$total_asignado += $cantidad_agrupada;
$faltante_agrupacion = $reg_extras['cantidad'] - $cantidad_agrupada;

if ($faltante_agrupacion > 0) {
echo "<tr>";
echo "<td>".$reg_extras['numero']."</td>";
echo "<td><input type = 'text' class = 'form-control' id = 'sin_grupo_cantidad".$reg_extras['numero']."' value = ".$faltante_agrupacion." readonly ></td>";
echo "<td align = 'center'> <button onclick = 'add_grupo(".$reg_extras['numero'].")' id = 'sin_grupo".$reg_extras['numero']."' class = 'btn btn-primary fa fa-arrow-circle-right' > </button></td>";
echo "</tr>";

}

$i++;
}

echo "</table>";
echo "</div>";

$total_pendiente = $total_solicitud - $total_asignado;

echo "<div class = 'card-footer'>";
echo "<table class = 'table table-bordered'>";
echo "<tr><th>Total Extra</th><th>Agrupado</th><th>Sin Agrupacion</th></tr>";
echo "<tr><td>".number_format($total_solicitud)."</td><td>".number_format($total_asignado)."</td><td>".number_format($total_pendiente)."</td></tr>";
echo "</table>";
echo "</div>";

echo "</div>";

echo "</td>";
echo "<td width = '1%'></td>";
echo "<td width = '74%' valign='top' >";


echo "<form method='POST'>";

echo "<div class = 'card'>";
echo "<div class = 'card-header'>";
echo "<h4>CREACION DE GRUPOS SORTEO ".$id_sorteo."</h4>";
echo "<input type = 'hidden' name = 'hidden_sorteo' value = '".$id_sorteo."' >";
echo "</div>";

echo "<div class = 'card-body'>";

$grupos_existentes = mysqli_query($conn,"SELECT MAX(grupo) + 1 as grupo_maximo FROM sorteos_menores_num_extras WHERE id_sorteo = $id_sorteo  "); 

$ob_grupo_max = mysqli_fetch_object($grupos_existentes);
$grupo_max = $ob_grupo_max->grupo_maximo;


echo "<table class = 'table table-bordered' >";
echo "<tr>";

if ($grupo_max == '') {
echo "<th colspan = '3'> GRUPO # 1  </th>";
echo "<input type = 'hidden' name = 'hidden_grupo' value = '1' >";
}else{
echo "<th colspan = '3'> GRUPO # ".$grupo_max."  </th>";
echo "<input type = 'hidden' name = 'hidden_grupo' value = '".$grupo_max."' >";

}

echo "<th colspan = '7'>";


echo '
<div class="input-group" >
<div class="input-group-prepend"><span  class="input-group-text">SERIES: </span></div>
<input name = "input_cantidad_series" id = "input_cantidad_series" type="text" class="form-control" placeholder="Series por cada numero en grupo" required>
</div>
';

echo "</th>";
echo "</tr>";

echo "<tr>";



echo "<td width = '10%'>
	  <div class='input-group'  >
	  <input type='text' id = 'input_numero_1' name = 'input_numero_1' class='form-control' readonly>
	  <div class='input-group-prepend'><span class=' btn btn-danger fa fa-times-circle' onclick = 'remove_grupo(1)' ></span></div>
	  </div>
	  </td>";

echo "<td width = '10%'>
	  <div class='input-group' >
	  <input type='text' id = 'input_numero_2' name = 'input_numero_2' class='form-control' readonly>
	  <div class='input-group-prepend'><span class=' btn btn-danger fa fa-times-circle' onclick = 'remove_grupo(2)' ></span></div>
	  </div>
	  </td>";

echo "<td width = '10%'>
	  <div class='input-group' >
	  <input type='text' class='form-control' id = 'input_numero_3' name = 'input_numero_3' readonly>
	  <div class='input-group-prepend'><span class=' btn btn-danger fa fa-times-circle' onclick = 'remove_grupo(3)' ></span></div>
	  </div>
	  </td>";

echo "<td width = '10%'>
	  <div class='input-group' >
	  <input type='text' class='form-control' id = 'input_numero_4' name = 'input_numero_4' readonly>
	  <div class='input-group-prepend'><span class=' btn btn-danger fa fa-times-circle' onclick = 'remove_grupo(4)' ></span></div>
	  </div>
	  </td>";

echo "<td width = '10%'>
	  <div class='input-group' >
	  <input type='text' class='form-control' id = 'input_numero_5' name = 'input_numero_5' readonly>
	  <div class='input-group-prepend'><span class=' btn btn-danger fa fa-times-circle' onclick = 'remove_grupo(5)' ></span></div>
	  </div>
	  </td>";

echo "<td width = '10%'>
 	  <div class='input-group' >
	  <input type='text' class='form-control' id = 'input_numero_6' name = 'input_numero_6' readonly>
	  <div class='input-group-prepend'><span class=' btn btn-danger fa fa-times-circle' onclick = 'remove_grupo(6)' ></span></div>
	  </div>
 	  </td>";

echo "<td width = '10%'>
 	  <div class='input-group' >
	  <input type='text' class='form-control' id = 'input_numero_7' name = 'input_numero_7' readonly>
	  <div class='input-group-prepend'><span class=' btn btn-danger fa fa-times-circle' onclick = 'remove_grupo(7)' ></span></div>
	  </div>
	  </td>";

echo "<td width = '10%'>
 	  <div class='input-group' >
	  <input type='text' class='form-control' id = 'input_numero_8' name = 'input_numero_8' readonly>
	  <div class='input-group-prepend'><span class=' btn btn-danger fa fa-times-circle' onclick = 'remove_grupo(8)' ></span></div>
	  </div>
 	  </td>";

echo "<td width = '10%'>
 	  <div class='input-group' >
	  <input type='text' class='form-control' id = 'input_numero_9' name = 'input_numero_9' readonly>
	  <div class='input-group-prepend'><span class=' btn btn-danger fa fa-times-circle' onclick = 'remove_grupo(9)' ></span></div>
	  </div>
	  </td>";

echo "<td width = '10%'>
      <div class='input-group' >
	  <input type='text' class='form-control' id = 'input_numero_10' name = 'input_numero_10' readonly>
	  <div class='input-group-prepend'><span class=' btn btn-danger fa fa-times-circle' onclick = 'remove_grupo(10)' ></span></div>
	  </div>
      </td>";

echo "</tr>";

echo "</table>";


echo "</div>";

echo "<div align = 'center' class = 'card-footer'> ";
echo "<button type = 'submit' name = 'guardar_grupo'  class  = 'btn btn-success' >Guardar Grupo</button>";
echo "</div>";

echo "</div>";

echo "<br>";

$grupos_existentes = mysqli_query($conn,"SELECT * FROM sorteos_menores_num_extras WHERE  id_sorteo = '$id_sorteo' GROUP BY grupo ORDER BY grupo ASC ");

echo '
<div class="card">
<div class="card-header">
<h4 align="center">Grupos Exisentes</h4>
</div>
<div class="card-body">
';

while ($reg_grupos_existentes = mysqli_fetch_array($grupos_existentes)) {

$num_grupo_existente =  $reg_grupos_existentes['grupo'];
$can_grupo_existente =  $reg_grupos_existentes['cantidad'];

$detalle_numeros_grupo = mysqli_query($conn,"SELECT * FROM sorteos_menores_num_extras WHERE  id_sorteo = '$id_sorteo' AND grupo = '$num_grupo_existente' ORDER BY numero ASC ");

?>
<table width="100%" class="table table-bordered">
<tr>
<th colspan="3">GRUPO # <?php echo $num_grupo_existente; ?></th>
<th colspan="6">Series en grupo  <?php echo $can_grupo_existente; ?> </th>
<th style="text-align: center " ><button name="eliminar_grupo" value = '<?php echo $num_grupo_existente;?>' class="btn btn-danger">x</button></th>
</tr>
<tr>
<tr>
<?php
$i = 1;
while ($reg_detalle_numeros_grupo = mysqli_fetch_array($detalle_numeros_grupo)) {
echo "<td>".$reg_detalle_numeros_grupo['numero']."</td>";
$i++;
}
?>
</tr>
</tr>
</table>
<?php

}

echo '
</div>
</div>';

echo "</td>";
echo "</tr>";
echo "</table>";

///////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////// CODIGO PARA ASIGNACION DE GRUPOS A PRODUCCCIONES EXTRAS /////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////

?>

</form>