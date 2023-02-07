<?php
require('../../template/header.php');


if (isset($_POST['guardar'])) {

$i = 0;

while (isset($_POST['gerencia_opcion'][$i])) {

$gerencia        = $_POST['gerencia_opcion'][$i];
$depto           = $_POST['depto_opcion'][$i];
$pantalla        = $_POST['pantalla_opcion'][$i];
$nombre_opcion   = $_POST['nombre_opcion'][$i];
$ṕosicion_opcion = $_POST['posicion_opcion'][$i];

 mysqli_query($conn, "INSERT INTO accesos (descripcion_menu, gerencia,depto,pantalla, posicion ) VALUES ('$nombre_opcion', '$gerencia', '$depto', '$pantalla', '$ṕosicion_opcion') ");

$i++;
}

echo "<div class = 'alert alert-info'>Registros realizados correctamente.</div>";

}




if (isset($_POST['eliminar_acceso'])) {

$id = $_POST['eliminar_acceso'];

 mysqli_query($conn, "DELETE FROM accesos WHERE id = '$id' ");


}


if (isset($_POST['editar_acceso'])) {

$id = $_POST['editar_acceso'];
$nombre   = $_POST['editar_nombre_'.$id];
$posicion = $_POST['editar_posicion_'.$id];

 mysqli_query($conn, "UPDATE  accesos SET  descripcion_menu = '$nombre', posicion = '$posicion' WHERE id = '$id' ");


echo "<div class = 'alert alert-info'>Campos actualizados correctamente.</div>";

}


?>

<script type="text/javascript">
function cargar_datos(select){

if (select == 1) {

seleccion_gerencia = document.getElementById('s_gerencia').value;


var obj_select = document.getElementById("s_depto");
conteo_opciones = obj_select.length;
obj_select.options[0].selected = true;

for (var i = 0; i < conteo_opciones; i++) {

if (obj_select.options[i].id == seleccion_gerencia ) {
obj_select.options[i].style.display = "block";
}else{
obj_select.options[i].style.display = "none";
}
}


var obj_select = document.getElementById("s_pantalla");
conteo_opciones = obj_select.length;
for (var i = 0; i <= conteo_opciones; i++) {
obj_select.options[i].style.display = "none";
}


}else if (select == 2) {

seleccion_gerencia = document.getElementById('s_gerencia').value;
seleccion_depto    = document.getElementById('s_depto').value;

var obj_select = document.getElementById("s_pantalla");
conteo_opciones = obj_select.length;
obj_select.options[0].selected = true;

for (var i = 0; i < conteo_opciones; i++) {

if (obj_select.options[i].id == seleccion_gerencia+seleccion_depto ) {
obj_select.options[i].style.display = "block";
}else{
obj_select.options[i].style.display = "none";
}
}



token = Math.random();
consulta = 'mostrar_accesos.php?g='+seleccion_gerencia+"&d="+seleccion_depto+"&token="+token;
$("#div_historico").load(consulta);


}

}


function add_acceso(){

seleccion_gerencia = document.getElementById('s_gerencia').value;
seleccion_depto    = document.getElementById('s_depto').value;
seleccion_pantalla = document.getElementById('s_pantalla').value;


var table = document.getElementById("table_accesos");
var row = table.insertRow(1);

var cell1 = row.insertCell(0);
var cell2 = row.insertCell(1);
var cell3 = row.insertCell(2);
var cell4 = row.insertCell(3);
var cell5 = row.insertCell(4);
var cell6 = row.insertCell(5);

cell1.innerHTML = "<input type = 'hidden' name = 'gerencia_opcion[]' value = "+seleccion_gerencia+" class = 'form-control' >"+seleccion_gerencia;
cell2.innerHTML = "<input type = 'hidden' name = 'depto_opcion[]' value = "+seleccion_depto+" class = 'form-control' >"+seleccion_depto;
cell3.innerHTML = "<input type = 'hidden' name = 'pantalla_opcion[]' value = "+seleccion_pantalla+" class = 'form-control' >"+seleccion_pantalla;
cell4.innerHTML = "<input type = 'text' name = 'nombre_opcion[]' class = 'form-control' >";
cell5.innerHTML = "<input type = 'text' name = 'posicion_opcion[]' class = 'form-control' >";
cell6.innerHTML = "<span class = 'btn btn-danger fa fa-times'></span>";

}

</script>









<br>

<form method="POST">

<input type="hidden" name="usuario_o" value = '<?php echo $usuario; ?>'>

<div class="card" style="margin-left: 10px ; margin-right: 10px">

<div class="card-header bg-secondary text-white">
<h4 align="center">GESTION DE ACCESOS</h4>
</div>

<div class="card-body">

<div class="row" style="width: 100%" >

<div class="col-md-3">

<div class="card"  >
<div class="card-header">
<h4 align="center">GERENCIA</h4>
</div>

<div class="card-body">

<select id="s_gerencia" name="s_gerencia" onclick="cargar_datos('1')" class="form-control"  size="5">

<?php

foreach(glob('../../*', GLOB_ONLYDIR) as $dir) {
    $dirname = basename($dir);
    if ($dirname != 'assets' && $dirname != 'template') {
    echo "<option value ='".$dirname."' >".$dirname."</option>";
    }
}

?>

</select>

</div>

</div>

</div>



<div class="col-md-3">

<div class="card"  >
<div class="card-header">
<h4 align="center">DEPARTAMENTO</h4>
</div>

<div class="card-body" >

<select id="s_depto" name="s_depto" onclick="cargar_datos('2')" class="form-control"  size="5">

<?php

foreach(glob('../../*', GLOB_ONLYDIR) as $dir) {
$dirname = basename($dir);

if ($dirname != 'assets' && $dirname != 'template' && $dirname != 'imagenes' ) {
foreach(glob('../../'.$dirname.'/*', GLOB_ONLYDIR) as $subdir) {
$subdirname = basename($subdir);


if ($subdirname != 'assets' && $subdirname != 'template' && $subdirname != 'imagenes' ) {
echo "<option style = 'display:none' id = '".$dirname."' value = '".$subdirname."' >".$subdirname."</option>";
}

}
}

}

?>

</select>

</div>

</div>

</div>




<div class="col">

<div class="card" >
<div class="card-header">
<h4 align="center"> PANTALLA</h4>
</div>

<div class="card-body">

<select id="s_pantalla" name="s_pantalla[]"  class="form-control"  size="5">

<?php

foreach(glob('../../*', GLOB_ONLYDIR) as $dir) {
$dirname = basename($dir);

if ($dirname != 'assets' && $dirname != 'template' && $dirname != 'imagenes' ) {
foreach(glob('../../'.$dirname.'/*', GLOB_ONLYDIR) as $subdir) {
$subdirname = basename($subdir);

if ($subdirname != 'assets' && $subdirname != 'template' && $subdirname != 'imagenes' ) {


$directorio = "../../".$dirname."/".$subdirname;
$ficheros  = scandir($directorio);

$i = 0;
while (isset($ficheros[$i])) {


if (substr($ficheros[$i], 0, 6) == "screen" OR substr($ficheros[$i], 0, 6) == "report") {

if ($ficheros[$i] != '.' && $ficheros[$i] != '..' ) {
echo "<option style = 'display:none' id = '".$dirname.$subdirname."' value = '".$ficheros[$i]."' >".$ficheros[$i]."</option>";
}

}


$i++;
}

}

}
}

}

?>

</select>

</div>


</div>

</div>

</div>


</div>


<div align="center" class="card-footer">
	<span onclick="add_acceso()" class="btn btn-primary">Agregar Acceso</span>
</div>


</div>


<br>


<div class="card" style="margin-left: 10px; margin-right: 10px">
<div class="card-header bg-success text-white">
<h4 align="center">HISTORICO DE OPCIONES</h4>
</div>
<div class="card-body">

<table id="table_accesos" class="table table-bordered">
<thead>
<tr>
<th>GERENCIA</th>
<th>DEPARTAMENTO</th>
<th>PANTALLA</th>
<th>DESCRIPCIÓN MENU</th>
<th>POSICION</th>
<th>ACCIÓN</th>
</tr>
</thead>
<tbody id="div_historico">



</tbody>
</table>


</div>
<div align="center" class="card-footer">

<button style="" class="btn btn-primary" name="guardar" id="guardar" type="submit">GUARDAR</button>

</div>
</div>


</form>


<br><br>
