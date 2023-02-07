<?php

require '../../template/header.php';

if (isset($_POST['guardar'])) {

	$identidad = $_POST['identidad'];
	$nombre = $_POST['nombre'];
	$puesto = $_POST['puesto'];
	$sorteo = $_POST['sorteo'];

	$v_identidad = explode("-", $identidad);
	$i = 0;
	$id = "";
	while (isset($v_identidad[$i])) {
		$id .= $v_identidad[$i];
		$i++;
	}

	if (mysqli_query($conn, " INSERT INTO cs_autoridades_sorteo( sorteo, identidad, nombre_completo, puesto_labora) VALUES (  '$sorteo', '$id', '$nombre', '$puesto')")) {

		?>
<script type="text/javascript">
swal("","Registro guardado correctamente.","success");
</script>
<?php
} else {

		?>
<script type="text/javascript">
swal("","Error inesperado.","error");
</script>
<?php
echo mysqli_error();
	}

}

if (isset($_POST['actualizar'])) {
	$id = $_POST['id'];
	$identidad = $_POST['identidad'];
	$nombre = $_POST['nombre'];
	$puesto = $_POST['puesto'];
	$sorteo = $_POST['sorteo'];

	if (mysqli_query($conn, " UPDATE cs_autoridades_sorteo SET identidad='$identidad', nombre_completo= '$nombre', puesto_labora= '$puesto' , sorteo= '$sorteo' WHERE id='$id' ")) {
		?>

<script type="text/javascript">
swal("","Registro actualizado correctamente.","success");
</script>

<?php
} else {
		?>
<script type="text/javascript">
swal("Error inesperado, por favor vuelva a intentarlo", "", "error");
</script>
<?php
}

}

if (isset($_POST['eliminar'])) {

	$id = $_POST['id'];
	if (mysqli_query($conn, " DELETE FROM cs_autoridades_sorteo  WHERE id=$id ")) {
		?>
<script type="text/javascript">
swal("","Registro eliminado correctamente.","success");
</script>
<?php
} else {
		?>
<script type="text/javascript">
swal("Error inesperado, por favor vuelva a intentarlo", "", "error");
</script>
<?php
}
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PANI</title>


<script type="text/javascript" src="../../assets/mask/jquery.maskedinput.js" ></script>


<script>


function validar_registro(){

identidad = document.getElementById('identidad').value;
nombre  = document.getElementById('nombre').value;
puesto  = document.getElementById('puesto').value;

if (identidad == '' || identidad === '') {
swal("","Debe ingresar un numero de identidad.","error");
}else if (nombre == '' || nombre === '') {
swal("","Debe ingresar el nombre completo.","error");
}else if (puesto == '' || puesto === '') {
swal("","Debe seleccionar el rol del delegado.","error");
}else{
document.getElementById('guardar').click();
}


}


function cargar_datos(id, identidad, nombre, empresa, puesto, originario){

document.getElementById('id').value =id;
document.getElementById('identidad').value =identidad;
document.getElementById('nombre').value =nombre;
document.getElementById('puesto').value =puesto;

document.getElementById('actualizar').disabled = false;
document.getElementById('eliminar').disabled = false;

}




jQuery(function($){
$("#identidad").mask("9999-9999-99999", { placeholder: "____-____-_____" });
});




$(document).ready(function(){


$('#table_delegados').DataTable({
//    "order": [[ 0, 'asc' ], [ 1, 'asc' ]],
"order": [[ 3, 'desc' ]],
"language": {
"sProcessing":     "Procesando...",
"sLengthMenu":     "Mostrar _MENU_ registros",
"sZeroRecords":    "No se encontraron resultados",
"sEmptyTable":     "Ningún dato disponible en esta tabla",
"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
"sInfoPostFix":    "",
"sSearch":         "Buscar:",
"sUrl":            "",
"sInfoThousands":  ",",
"sLoadingRecords": "Cargando...",
"oPaginate": {
"sFirst":    "Primero",
"sLast":     "Último",
"sNext":     "Siguiente",
"sPrevious": "Anterior"
},
"oAria": {
"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
"sSortDescending": ": Activar para ordenar la columna de manera descendente"
}
}
});

});


</script>

</head>





<body>

<form method = 'POST' >
<br>

<ul class="nav nav-tabs">
<li class="nav-item">
<a style="background-color:#ededed;" class="nav-link active" href="#">Lotería Menor</a>
</li>
</ul>



<section style="background-color:#ededed;">
<br>
<h2 align="center" style="color:black;" >
<b>GESTION DE DELEGADOS LOTERIA MENOR</b>
</h2>
<br>
</section>


<br>

<div class="row">
<div class="col col-md-4">

<div class="card" style="margin-left: 15px">
<div class="card-header alert alert-info">
<h3 style="text-align: center">REGISTRO DE DELEGADO</h3>
</div>
<div class="card-body">

<input type="hidden"  name="id" id="id">

<div class="input-group" style="margin-bottom: 10px">
<div class="input-group-prepend">
<div class="input-group-text" style="min-width: 180px; ">Identidad: </div>
</div>
<input type="text" name="identidad"  class="form-control" id="identidad" required>
</div>


<div class="input-group" style="margin-bottom: 10px">
<div class="input-group-prepend">
<div class="input-group-text" style="min-width: 180px; ">Nombre Completo: </div>
</div>
<input type="text" name="nombre" class="form-control" id="nombre" required>
</div>





<div class="input-group" style="margin-bottom: 10px">
<div class="input-group-prepend">
<div style="min-width: 82px; " class="input-group-text">Rol: </div>
</div>

<select name="puesto" id="puesto"  class="form-control"  >
<option value="">Seleccione uno</option>
<?php
$query_area = mysqli_query($conn, "SELECT * FROM cs_tipo_representacion ");
if (mysqli_num_rows($query_area) > 0) {
	while ($row = mysqli_fetch_array($query_area)) {
		echo "<option value = '" . $row['id'] . "'>" . $row['descripcion'] . "</option>";
	}
}
?>
</select>

</div>


<div class="input-group" style="margin-bottom: 10px">
<div class="input-group-prepend">
<div class="input-group-text">Sorteo: </div>
</div>

<select name="sorteo" id="sorteo"  class="form-control"  class="">
<option value="">Seleccione Uno </option>
<?php
$result = mysqli_query($conn, " SELECT * FROM `sorteos_menores` order by id DESC ");
if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_array($result)) {
		echo "<option value = '" . $row['id'] . "'>" . $row['no_sorteo_men'] . "</option>";
	}
}
?>
</select>
</div>






</div>

<div class="card-footer" align="center">
<button type="submit" name="guardar" id="guardar" style="visibility: hidden;" ></button>
<span class="btn btn-success" onclick="validar_registro()" >Guardar Nuevo</span>
<button type="submit" class="btn btn-info" name="actualizar" id="actualizar" disabled="true">Actualizar</button>
<button type="submit" class="btn btn-danger" name="eliminar" id="eliminar" disabled="true" >Eliminar</button>
</div>
</div>

</div>








<div class="col">

<div  class="card " style="margin-right: 15px">
<div class="card-header alert alert-success" >
<h3 style="text-align: center">HISTORICO</h3>
</div>
<div class="card-body">


<table class="table table-hover table-bordered" id="table_delegados" name="table_delegados" >
<thead align="center">
<tr>
<th align="center">Identidad</th>
<th align="center">Nombre</th>
<th align="center">Rol</th>
<th align="center">Sorteo</th>
</tr>
</thead>
<tbody>
<?php

$resultado = mysqli_query($conn, "SELECT a.id,  a.sorteo, a.identidad, a.nombre_completo, a.puesto_labora, a.empresa, a.originario, b.descripcion,  a.originario FROM cs_autoridades_sorteo a,  cs_tipo_representacion b WHERE  a.puesto_labora=b.id  ");

while ($row = mysqli_fetch_array($resultado)) {
	?>

<tr onclick = "cargar_datos('<?php echo $row['id']; ?>','<?php echo $row['identidad']; ?>','<?php echo $row['nombre_completo']; ?>','<?php echo $row['empresa']; ?>','<?php echo $row['puesto_labora']; ?>','<?php echo $row['originario']; ?>')">

<?php
echo "
<td><input type='hidden' value='" . $row['id'] . "'>" . $row['identidad'] . "</td>
<td>" . $row['nombre_completo'] . "</td>
<td><input type='hidden' value='" . $row['puesto_labora'] . "'>" . $row['descripcion'] . "</td>
<td>" . $row['sorteo'] . "</td> ";

	?>

</tr>
<?php

}

?>

</tbody>
</table>


</div>
</div>

</div>
</div>

</form>


</html>