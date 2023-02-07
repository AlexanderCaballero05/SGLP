<?php
require('../../template/header.php');
require('./cc_asignacion_revisores_menor_extra_db.php');


$revisores = mysqli_query($conn,"SELECT * FROM pani_usuarios WHERE estados_id = '1' AND roles_usuarios_id = '2' AND areas_id = '5' ORDER BY nombre_completo ASC ");

$revisores_select = mysqli_query($conn,"SELECT * FROM pani_usuarios WHERE estados_id = '1' AND roles_usuarios_id = '2' AND areas_id = '5' ORDER BY nombre_completo ASC ");

if ($revisores===false) {
echo mysqli_error($conn);
}

?>

<script type="text/javascript">

function calcular_hasta(desde,indice){

indice--;

if (indice >= 0) {
desde_anterior = parseInt(document.getElementById('desde'+indice).value);
hasta_anterior = parseInt(document.getElementById('hasta'+indice).value);

cantidad = hasta_anterior - desde_anterior;

indice++;

hasta = parseInt(desde) + parseInt(cantidad);
document.getElementById('hasta'+indice).value = hasta;
}

};


function calcular_desde(hasta,indice){

value_desde = parseInt(hasta) + 1;
indice = parseInt(indice) + 1;

document.getElementById('desde'+indice).value = value_desde;

};


function agregar_revisor(){

id_revisor = document.getElementById('select_revisor').value;
concatenado_grupo = document.getElementById('concatenado_grupo').value;
var res = concatenado_grupo.split("-");
var grupo_menor = res[0];
var grupo_mayor = res[1];
var opciones = '';
while (grupo_menor <= grupo_mayor){
opciones = opciones+"<option value = '"+grupo_menor+"'>"+grupo_menor+"</option>";
grupo_menor++; 
}

nombre_revisor =document.getElementById('select_revisor').options[document.getElementById('select_revisor').selectedIndex].text;
;

tabla = document.getElementById('detalle_revisor');
filas = tabla.rows.length;

document.getElementById('filas_guardadas').value = parseInt(document.getElementById('filas_guardadas').value) + 1; 

  // Create an empty <tr> element and add it to the 1st position of the table:
var row = tabla.insertRow(filas);
  // Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
var cell1 = row.insertCell(0);
var cell2 = row.insertCell(1);
var cell3 = row.insertCell(2);
var cell4 = row.insertCell(3);
var cell5 = row.insertCell(4);



cell1.innerHTML = "<input type = 'hidden' name = 'id_o"+filas+"' value = '"+id_revisor+"' >"+nombre_revisor;
cell2.innerHTML = "<select class = 'form-control' style = 'width:100%'  name = 'grupo"+filas+"' >"+opciones+"</select>";


cell3.innerHTML = "<input class = 'form-control' style = 'width:100%' type = 'number' name = 'desde"+filas+"' >";
cell4.innerHTML = "<input class = 'form-control' style = 'width:100%' type = 'number' name = 'hasta"+filas+"' >";
cell5.innerHTML = "<SPAN onclick='eliminar_revisor(this)' class='btn btn-danger'>-</SPAN>";


}


  function eliminar_revisor(elemento){

tabla = document.getElementById('detalle_revisor');
f =  elemento.parentNode.parentNode.rowIndex;
document.getElementById("detalle_revisor").deleteRow(f);  
filas = tabla.rows.length;

}  
</script>




<form method="POST">

<?php

$id_sorteo = $_SESSION['cc_menor'];
$info_sorteo = mysqli_query($conn,"SELECT * FROM sorteos_menores WHERE id = '$id_sorteo' ");

$i_sorteo = mysqli_fetch_object($info_sorteo);
$numero_sorteo = $i_sorteo->no_sorteo_men;
$fecha_sorteo = $i_sorteo->fecha_sorteo;
$series = $i_sorteo->series;
$estado_cc_normal = $i_sorteo->control_calidad;

if ($estado_cc_normal == "NO") {
echo '<div class="alert alert-danger"  align="CENTER"> 
Nota: Debe Asignar la produccion Normal Primero
</div>';
}else{

?>

<input type="hidden" name="id_sorteo_oculto"  value="<?php echo $id_sorteo;?>"></input>


<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >ASIGNACION DE LOTERIA MENOR EXTRA PARA REVISION</h2> 

<h4 style="color:black; " align="center">
Sorteo Numero: <?php echo $numero_sorteo;?>
 | Fecha del Sorteo: <?php echo $fecha_sorteo;?>
<br>
Cantidad de Series: <?php echo number_format($series);?> 
</h4>
</section>

<br>

<div style="width: 100%" align="center">
<div class="well" style="width:90%">



<table class="table table-bordered">
<tr>
  <th>Grupo</th>
  <th>Detalle</th>
  <th>Serie Inicial</th>  
  <th>Serie Final</th>    
  <th>Cantidad Series</th>
</tr>

<?php 
$j = 1;
$i = 0;

$grupos = mysqli_query($conn,"SELECT serie_inicial ,cantidad ,grupo , MAX(numero) as maximo, MIN(numero) as minimo FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo' GROUP BY grupo ASC ");
while ($grupo = mysqli_fetch_array($grupos)) {
$serie_final =  $grupo['serie_inicial'] + $grupo['cantidad'] -1;
$v_grupo[$i] = $grupo['grupo'];
echo "<tr>";
echo "<td>".$grupo['grupo']."</td>";

$numeros_extras = mysqli_query($conn,"SELECT * FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo' AND grupo = '$v_grupo[$i]' ");
echo "<td>";
while ($numero_extra = mysqli_fetch_array($numeros_extras)) {

echo str_pad($numero_extra['numero'],2, '0', STR_PAD_LEFT).",";
}
echo "</td>";
echo "<td>".$grupo['serie_inicial']."</td>";
echo "<td>".$serie_final."</td>";
echo "<td>".number_format($grupo['cantidad'])."</td>";

echo "</tr>";
$ultimo_grupo = $v_grupo[$i]; 
$i++;
}
$concatenado_grupo = $v_grupo[0]."-".$ultimo_grupo;
echo "<input type = 'hidden' id = 'concatenado_grupo' value = '".$concatenado_grupo."' >";

?>  
</table>


<div class="card">
<div class="card-header">


<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span  class="input-group-text">Revisor: </span></div>
<select class="form-control" name="select_revisor" id = "select_revisor" >
<?php
while ($revisor_select = mysqli_fetch_array($revisores_select)) {
echo "<option value='".$revisor_select['id']."'>".$revisor_select['nombre_completo']."</option>";
}
?>
</select>

<div class="input-group-prepend" style="margin-left: 10px"><span  class="input-group-text">Grupo </span></div>
<select name="select_grupo" id="select_grupo" class="form-control">
<?php
$i = 0;
while (isset($v_grupo[$i])) {
echo "<option value = '".$v_grupo[$i]."'>".$v_grupo[$i]."</option>";
$i++;
}
?>
</select>


<div class="input-group-prepend" style="margin-left: 10px"><span  class="input-group-text">Cantidad </span></div>
<input type="text" name="cantidad_asignar" id="cantidad_asignar" class="form-control">

<span style="margin-left: 10px;margin-right: 10px;" onclick='agregar_revisor()' class='btn btn-primary'>Agregar Nuevo</span>

<span style="margin-left: 10px;margin-right: 10px" onclick='eliminar_revisor(this)' class='btn btn-danger'>Eliminar Ultimo</span>

</div>

</div>
<div class="card-body">
<table class="table table-bordered" id="detalle_revisor">
<tr>
<th width="55%">Nombre</th>
<th width="15%">Grupo</th>      
<th width="15%">Desde</th>      
<th width="15%">Hasta</th>            
<th style="align:center" width="5%">Accion </th>      
</tr>
</table>

<input type="hidden" id="filas_guardadas" name="filas_guardadas" value="<?php echo $j;?>"></input>

<p align="center">
<button type="submit" name="guardar" class="btn btn-info">Guardar</button>
</p>    
</div>
</div>





</div>
</div>

<?php 
}
?>

</form>