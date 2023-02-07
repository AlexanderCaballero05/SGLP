<?php
require('../../template/header.php');
require('./mto_premios_mayores_detalle_db.php');


$id_sorteo = $_GET['sort'];

$info_sorteo = mysqli_query($conn,"SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo' ");
$og_info = mysqli_fetch_object($info_sorteo);
$sorteo = $og_info->no_sorteo_may;
$fecha_sorteo = $og_info->fecha_sorteo;

?>

<script type="text/javascript">

$(document).ready(function(){
$('input[id="monto"]').mask('000,000,000.00', {reverse: true});
});

function limpiar_modal(){

tabla = document.getElementById('tabla_premios2');
filas = tabla.rows.length;
filas = parseInt(filas) - 1;


while(filas > 0){
tabla.deleteRow(filas);
filas = filas - 1;
}

}



function descripcion_acta(indice, select){
if (select == 'EFECTIVO') {
document.getElementById('desc_acta'+indice).value = "EFECTIVO";
document.getElementById('desc_acta'+indice).readOnly = true;
}else{
document.getElementById('desc_acta'+indice).readOnly = false;
document.getElementById('desc_acta'+indice).value = "";
}

}

function asignar_id(id,d){

document.getElementById('id_premio_modal').value = id;
document.getElementById('descripcion_premio_modal').value = d;

}

function eliminar_fila(elemento){

	f =  elemento.parentNode.parentNode.rowIndex;

tabla = document.getElementById('tabla_premios');
filas = tabla.rows.length;
tabla.deleteRow(f);

filas_final = tabla.rows.length;

i = 1;
}

function eliminar_fila2(elemento){

	f =  elemento.parentNode.parentNode.rowIndex;

tabla = document.getElementById('tabla_premios2');
filas = tabla.rows.length;
tabla.deleteRow(f);

filas_final = tabla.rows.length;

i = 1;
}

	function agregar_premio(){
		select = document.getElementById('premio');
		premio = select.value;
		texto_premio = select.options[select.selectedIndex].text;

tabla = document.getElementById('tabla_premios');
filas = tabla.rows.length;

if (document.getElementById('filas').value == '') {
document.getElementById('filas').value = filas;
}else{
document.getElementById('filas').value = parseInt(document.getElementById('filas').value ) + 1;

}

filas = tabla.rows.length;
var row = tabla.insertRow(filas);

filas = document.getElementById('filas').value;

var cell1 = row.insertCell(0);
var cell2 = row.insertCell(1);
var cell3 = row.insertCell(2);
var cell4 = row.insertCell(3);
var cell5 = row.insertCell(4);
var cell6 = row.insertCell(5);


cell6.align = "center";

cell1.innerHTML = "<input class = 'form-control'  name = 'cantidad"+filas+"'     id = 'cantidad"+filas+"' type= 'text' style='width:100%' value = '1' > ";
cell2.innerHTML = "<input class = 'form-control' name = 'descripcion"+filas+"'  id = 'descripcion"+filas+"' type= 'text' style='width:100%' value = '"+texto_premio+"' > <input type = 'hidden' name = 'id_premio"+filas+"' value = '"+premio+"'> ";
cell3.innerHTML = "<select class = 'form-control' name = 'select"+filas+"'       id = 'select"+filas+"' style='width:100%'></select>";
cell4.innerHTML = "<input class = 'form-control'  name = 'monto"+filas+"'        id = 'monto' type= 'text' style='width:100%' >";
cell5.innerHTML = "<input  class = 'form-control' name = 'desc_acta"+filas+"' value = 'EFECTIVO'  id = 'desc_acta"+filas+"' type= 'text' style='width:100%' readonly required>";
cell6.innerHTML = "<i class= 'fa fa-times-circle btn btn-danger' onclick = 'eliminar_fila(this)'></i>";


var x = document.getElementById("select"+filas);
var option = document.createElement("option");
option.text = "EFECTIVO";
option.value = "EFECTIVO";
x.add(option);

var option2 = document.createElement("option");
option2.text = "ESPECIES";
option2.value = "ESPECIES";
x.add(option2);


$(document).ready(function(){
$('input[id="monto"]').mask('000,000,000.00', {reverse: true});
});

}



	function agregar_premio2(){

tabla = document.getElementById('tabla_premios2');
filas = tabla.rows.length;

if (document.getElementById('filas2').value == '') {
document.getElementById('filas2').value = filas;
}else{
document.getElementById('filas2').value = parseInt(document.getElementById('filas2').value ) + 1;

}

filas = tabla.rows.length;
var row = tabla.insertRow(filas);

filas = document.getElementById('filas2').value;

id_premio2 = document.getElementById('id_premio_modal').value;
desc_premio2 = document.getElementById('descripcion_premio_modal').value;


  // Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
var cell1 = row.insertCell(0);
var cell2 = row.insertCell(1);
var cell3 = row.insertCell(2);
var cell4 = row.insertCell(3);

cell4.align = "center";

// Add some text to the new cells:
cell1.innerHTML = "<input  class = 'form-control' name = 'descripcion2"+filas+"'  id = 'descripcion2"+filas+"' type= 'text' style='width:100%' value = 'Premio Sec. de "+desc_premio2+"' > <input type = 'hidden' name = 'id_premio2"+filas+"' value = '"+id_premio2+"'> ";
cell2.innerHTML = "<select class = 'form-control' name = 'select2"+filas+"'       id = 'select2"+filas+"' style='width:100%'><option value = 'EFECTIVO'>EFECTIVO</option><option value = 'ESPECIES'>ESPECIES</option></select>";
cell3.innerHTML = "<input class = 'form-control' name = 'monto2"+filas+"'        id = 'monto2' type= 'text' style='width:100%' >";
cell4.innerHTML = "<i class= 'fa fa-times-circle btn btn-danger' onclick = 'eliminar_fila2(this)'></i>";

$(document).ready(function(){
$('input[id="monto2"]').mask('000,000,000.00', {reverse: true});
});


	}



</script>

<form method="POST">

<section style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >Edición de premios de loteria mayor</h2>
<h4 align='center'>Sorteo No. <?php echo $sorteo; ?> Fecha de Sorteo <?php echo $fecha_sorteo; ?></h4>
<br>
</section>
<br>

<?php

echo "<input type = 'hidden' name = 'id_sorteo' value = '".$id_sorteo."'>";

$info_sorteo = mysqli_query($conn,"SELECT * FROM sorteos_mayores WHERE  id = '$id_sorteo' ");
$ob_sorteo  = mysqli_fetch_object($info_sorteo);
$num_sorteo = $ob_sorteo->no_sorteo_may;
$descripcion = $ob_sorteo->descripcion_sorteo_may;
$premios_asignados = $ob_sorteo->premios_asignados;

$fecha_sorteo_f = $ob_sorteo->fecha_sorteo;

$current_date = date("Y-m-d");

if ($current_date > $fecha_sorteo_f) {
$update = 0;
}else{
$update = 1;
}

$premios = mysqli_query($conn,"SELECT * FROM premios_mayores ");

?>





<div class="card" style="margin-left: 10px;margin-right: 10px">
<div align="center" class="card-header"  >



</div>
<div class="card-body">



<div id ='a' style=" height:400px ; align:left; width:100%"  >

<table id="tabla_premios" class="table table-bordered">
<tr>
	<th width="10%">Cant.</th>
	<th width="35%">Descripcion</th>
	<th width="15%">Tipo de Premio</th>
	<th width="15%">Premio</th>
	<th width="15%">Desc. Premio</th>
	 
</tr>

<?php


if ($id_sorteo != '') {


$premios_anteriores = mysqli_query($conn,"SELECT a.premios_mayores_id, a.tipo_premio , a.monto ,a.desc_premio , b.descripcion_premios FROM sorteos_mayores_premios as a INNER JOIN premios_mayores as b ON a.premios_mayores_id = b.id WHERE sorteos_mayores_id = '$id_sorteo' AND respaldo != 'SI' ORDER BY a.monto DESC ");

$i = 1;
while ($row4 = mysqli_fetch_array($premios_anteriores)) {
$id_premio = $row4['premios_mayores_id'];
$tipo = $row4['tipo_premio'];
$descripcion = $row4['descripcion_premios'];
$monto = $row4['monto'];
$desc_acta = $row4['desc_premio'];

echo "<tr>";
echo "<td><input class = 'form-control'  name = 'cantidad".$i."'     id = 'cantidad".$i."' type= 'text' style='width:100%' value = '1' > </td>";
echo "<td><input class = 'form-control' name = 'descripcion".$i."'  id = 'descripcion".$i."' type= 'text' style='width:100%' value = '".$descripcion."' > <input type = 'hidden' name = 'id_premio".$i."' value = '".$id_premio."'></td>";
echo "<td><select class = 'form-control' name = 'select".$i."'       id = 'select".$i."' style='width:100%' onclick = 'descripcion_acta(".$i.",this.value)'>";
if ($tipo == 'EFECTIVO') {
echo "		<option value = 'EFECTIVO' selected>EFECTIVO</option >";
echo "		<option value = 'ESPECIES' >ESPECIES</option>";
}else{
echo "		<option value = 'EFECTIVO' >EFECTIVO</option >";
echo "		<option value = 'ESPECIES' selected>ESPECIES</option >";
}
echo "	  </select></td>";
echo "<td><input class = 'form-control' name = 'monto".$i."' value = '".$monto."'  id = 'monto' type= 'text' style='width:100%' ></td>";

if ($tipo == 'EFECTIVO') {
	echo "<td><input class = 'form-control' name = 'desc_acta".$i."' value = 'EFECTIVO'  id = 'desc_acta".$i."' type= 'text' style='width:100%' readonly required></td>";
}else{
	echo "<td><input class = 'form-control' name = 'desc_acta".$i."' value = '".$desc_acta."'  id = 'desc_acta".$i."' type= 'text' style='width:100%' required></td>";
}

 

echo "</tr>";

$i++;


$busqueda_respaldos  =  mysqli_query($conn," SELECT * FROM sorteos_mayores_premios WHERE sorteos_mayores_id = '$id_sorteo' AND premios_mayores_id =  '$id_premio' AND respaldo = 'SI' ");
while ($reg_busqueda = mysqli_fetch_array($busqueda_respaldos)) {

$descripcion_respaldo = $reg_busqueda['descripcion_respaldo'];
$monto_respaldo = $reg_busqueda['monto'];
$tipo_respaldo = $reg_busqueda['tipo_premio'];
$id = $reg_busqueda['id'];

echo "<tr><td></td>";
echo "<td><input class = 'form-control'  type= 'text' style='width:100%' value = '".$descripcion_respaldo."' ></td>";
echo "<td><input class = 'form-control' type = 'text' value = '".$tipo_respaldo."'></td>";

echo "<td><input class = 'form-control'  value = '".$monto_respaldo."'  id = 'monto'  type= 'text' style='width:100%' ></td>";

if ($update != 0) {
echo "<td align = 'center'><button type = 'submit' value = '".$id."' class = 'btn btn-danger' name = 'eliminar_respaldo' ><i class = 'fa fa-times-circle'></i></button></td>";
}else{
echo "<td></td>";
}

echo "</tr>";


}

}

echo '<input type="hidden" id="filas" name="filas" value = "'.$i.'" >';

}else{

echo '<input type="text" id="filas" name="filas"  >';

}



?>
</table>

</div>

</div>

<div align="center" class="card-footer">
 

</div>

</div>

<br><br>






<div class="modal fade " id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" align="center">Premios de Resplado</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
      <div class="card-body">

<span class="btn btn-primary" onclick="agregar_premio2()" style="width: 100%" name="agregar_premio">Agregar Premio de Respaldo</span>

<br>
<div class="" style="width:100%;" >
<input type="hidden" id="filas2" name = 'filas2'>
<input type="hidden" id="id_premio_modal" name="id_premio_modal">
<input type="hidden" id="descripcion_premio_modal" name="descripcion_premio_modal">

<table id="tabla_premios2" class="table table-bordered">
<tr>
	<th width="50%">Descripcion</th>
	<th width="20%">Tipo de Premio</th>
	<th width="20%">Premio</th>
	<th width="10%">Accion</th>
</tr>

</table>

	  </div>



      </div>
      <div class="modal-footer" align="center">
        <button type="submit" name="guardar_respaldo" class="btn btn-primary">Aceptar</button>
        <span onclick = "limpiar_modal()" class="btn btn-danger" data-dismiss="modal" >Cancelar</span>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>


</form>
<script type="text/javascript">
    window.print(); 
    setTimeout(window.close, 1000);
     // window.close(); 
      
</script>
 
