<?php

require '../../template/header.php';

$usuarios = mysqli_query($conn, "SELECT * FROM pani_usuarios ");

if (isset($_POST['guardar_nuevo'])) {

	$n_usuario = $_POST['n_usuario'];
	$n_nombre = $_POST['n_nombre'];
	$n_mail = $_POST['n_mail'] . "@pani.hn";
	$n_estado = 1;

	$password = md5($n_usuario);

	$n_mail = $_POST['n_mail'];
	$n_estado = 1;

	$n_entidad = $_POST['s_empresa'];

	if ($n_entidad != "") {

		$n_info_agencia = $_POST['s_agencia'];
		$v_agencia = explode("!", $n_info_agencia);
		$n_agencia = $v_agencia[0];
		$n_departamento = $v_agencia[1];
		$n_municipio = $v_agencia[2];
		$n_id_agencia = $v_agencia[3];

		$insert = mysqli_query($conn, "INSERT INTO pani_usuarios (usuario, nombre_completo, mail, roles_usuarios_id, areas_id, estados_id,  password, codigo_empleado, identidad, id_entidad, agencia, departamento, municipio) VALUES ('$n_usuario', '$n_nombre', '$n_mail', '1', '1', '$n_estado' , '$password' , '', '' ,  '$n_entidad',  '$n_id_agencia', '$n_departamento', '$n_municipio') ");

	} else {

		$insert = mysqli_query($conn, "INSERT INTO pani_usuarios (usuario, nombre_completo, mail, roles_usuarios_id, areas_id, estados_id,  password, codigo_empleado, identidad) VALUES ('$n_usuario', '$n_nombre', '$n_mail', '1', '1', '$n_estado', '$password' , '', '') ");

	}

	if ($insert === TRUE) {
		echo "<div class = 'alert alert-info'>Usuario creado correctamente.</div>";
	} else {
		echo "<div class = 'alert alert-danger'>Error inesperado, por favor vuelva a intentarlo. " . mysqli_error($conn) . "</div>";
	}
	$usuarios = mysqli_query($conn, "SELECT * FROM pani_usuarios ");
}

if (isset($_POST['guardar_accesos'])) {

	$usuario = $_POST['accesos_u'];
	$gerencia = $_POST['s_gerencia'];
	$depto = $_POST['s_depto'];

	mysqli_query($conn, "DELETE a.* FROM  pani_usuarios_accesos as a INNER JOIN accesos as b ON a.id_acceso = b.id  WHERE a.usuario = '$usuario' AND b.gerencia = '$gerencia' AND b.depto = '$depto'  ");

	$i = 0;
	while (isset($_POST['id_acceso' . $i])) {
		$id_acceso = $_POST['id_acceso' . $i];

		if (isset($_POST['check' . $i])) {

			mysqli_query($conn, "INSERT INTO pani_usuarios_accesos (usuario, id_acceso) VALUES ('$usuario', '$id_acceso') ");

		}

		$i++;
	}

	echo "<div class = 'alert alert-info'>Accesos asignados correctamente.</div>";

}

if (isset($_POST['editar_usuario'])) {

	$n_id = $_POST['e_id'];
	$n_usuario = $_POST['e_usuario'];
	$n_nombre = $_POST['e_nombre'];
	$n_mail = $_POST['e_mail'];
	$n_estado = $_POST['e_estado'];

	$update = mysqli_query($conn, " UPDATE pani_usuarios SET usuario = '$n_usuario', nombre_completo = '$n_nombre', mail = '$n_mail', estados_id = '$n_estado'  WHERE id = '$n_id' ");

	if ($update === TRUE) {
		echo "<div class = 'alert alert-info'>Datos actualizados correctamente.</div>";
	} else {
		echo "<div class = 'alert alert-danger'>Error inesperado, por favor vuelva a intentarlo. " . mysqli_error($conn) . "</div>";
	}

	$usuarios = mysqli_query($conn, "SELECT * FROM pani_usuarios ");

}

?>


<script type="text/javascript">


/////////////////////////////////////////
//// FUNCIONES CARGADO DE EDICION ///////

function cargar_edicion(id,user,nombre_completo,mail, estado){

document.getElementById("e_id").value      = id;
document.getElementById("e_nombre").value  = nombre_completo;
document.getElementById("e_usuario").value = user;
document.getElementById("e_mail").value    = mail;
document.getElementById("e_estado").value  = estado;



}

//// FUNCIONES CARGADO DE EDICION ///////
/////////////////////////////////////////









function asignar_accesos(id, user){
document.getElementById("accesos_u").value  = user;
document.getElementById("accesos_id").value = id;
}






/////////////////////////////////////////
//////////// ASIGNAR ACCESOS ////////////

function cargar_datos(select){


u = document.getElementById("accesos_u").value;
i = document.getElementById("accesos_id").value;


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

token = Math.random();
consulta = 'asignacion_accesos.php?g='+seleccion_gerencia+"&d="+seleccion_depto+"&i="+i+"&u="+u+"&token="+token;
$("#div_accesos_asignados").load(consulta);



}

}

//////////// ASIGNAR ACCESOS ////////////
/////////////////////////////////////////




////////////////////////////////////////
/////////// BUSCAR AGENCIAS ////////////

function buscar_agencias(entidad, consulta){

if (consulta == 1) {

token = Math.random();
consulta = 'datos_agente_venta.php?e='+entidad+"&c="+consulta+"&token="+token;
$("#respuesta_agencias").load(consulta);

}

}

/////////// BUSCAR AGENCIAS ////////////
////////////////////////////////////////

</script>


<section style="color:rgb(63,138,214);background-color:#ededed;">
<br>
<h1  align="center" style="color:black; "  >GESTION DE USUARIOS</h1>
<br>
</section>


<br>
<br>


<div class="card" style="margin-left: 10px; margin-right: 10px">
<div class="card card-header bg-secondary text-white">
<h4 align="center">USUARIOS</h4>


</div>
<div class="card card-body">


<table id="table_id1" class="table table-bordered">
	<thead>
		<tr>
			<th>USUARIO</th>
			<th>NOMBRE COMPLETO</th>
			<th>ESTADO</th>
			<th>EDITAR</th>
			<th>ACCESOS</th>
		</tr>
	</thead>
	<tbody>
<?php

while ($reg_usuarios = mysqli_fetch_array($usuarios)) {

	$id = $reg_usuarios['id'];
	$user = $reg_usuarios['usuario'];
	$nombre_completo = $reg_usuarios['nombre_completo'];
	$mail = $reg_usuarios['mail'];
	$estado = $reg_usuarios['estados_id'];

	echo "<tr>";
	echo "<td>";
	echo $reg_usuarios['usuario'];
	echo "</td>";
	echo "<td>";
	echo $reg_usuarios['nombre_completo'];
	echo "</td>";
	echo "<td>";
	if ($reg_usuarios['estados_id'] == 1) {
		echo "Activo";		
	}elseif($reg_usuarios['estados_id'] == 2){
		echo "Inactivo";		
	}else{
		echo "Nuevo | Restauracion de Pass";		
	}
	echo "</td>";
	echo "<td align = 'center'>";
	?>
<a onclick = "cargar_edicion('<?php echo $id; ?>','<?php echo $user; ?>','<?php echo $nombre_completo; ?>','<?php echo $mail; ?>','<?php echo $estado; ?>')" data-toggle='modal' href='#modal-edit'  class='btn btn-primary fa fa-edit'></a>
<?php
echo "</td>";
	echo "<td align = 'center'>";
	?>
<a onclick = "asignar_accesos('<?php echo $id; ?>','<?php echo $user; ?>')" data-toggle='modal' href='#modal-accesos'  class= 'btn btn-success fa fa-sign-in-alt' ></a>
<?php
echo "</td>";
	echo "</tr>";

}

?>

	</tbody>
</table>

</div>

<div class="card-footer" align="center">
	<a  data-toggle="modal" href="#modal-login" class="btn btn-primary">Agregar Nuevo Usuario</a>
</div>

</div>

<br><br>



<form method="POST">

<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->
<!-- $$$$$$$$$$$$$$$$$$$$ MODAL DE NUEVO REG $$$$$$$$$$$$$$$$$$$$$$ -->



<div class="modal fade" role="dialog" tabindex="-1" id="modal-login">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">

<div class="modal-header" id="modal-header" style="background-color:rgb(255,255,255);">
<h4 class="text-center modal-title" id="modal-heading" style="width:100%;">CREACION DE USUARIO</h4>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>

<div class="modal-body" style="background-color:#f8f8f8;">

<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Usuario: </span></div>
<input type="text" class="form-control" id="n_usuario" name="n_usuario">
</div>

<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Nombre Completo: </span></div>
<input type="text" class="form-control" id="n_nombre" name="n_nombre"  >
</div>

<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Correo: </span></div>
<input type="text" class="form-control" id="n_mail" name="n_mail" >
</div>

<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Estado: </span></div>
<select class="form-control" name="s_estado" readonly = "true">
<option value="1" selected="true">Activo</option>
</select>
</div>

<hr>
<div class="alert alert-info">
En caso de que el usuario sea agente de venta por favor ingrese la siguiente información:
</div>

<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Entidad: </span></div>
<?php
$entidades = mysqli_query($conn, "SELECT * FROM empresas WHERE estado != 'INACTIVO' ");
?>
<select class="form-control" name="s_empresa" onchange="buscar_agencias(this.value, 1)" >
<option value="">Seleccione una opción</option>
<?php
while ($reg_entidad = mysqli_fetch_array($entidades)) {
	echo "<option value = '" . $reg_entidad['id'] . "' >" . $reg_entidad['nombre_empresa'] . "</option>";
}
?>
</select>
</div>


<div id = 'respuesta_agencias'>
</div>


</div>

<div class="modal-footer" id="modal-footer" style="background-color:rgb(255,255,255);">
<button name="guardar_nuevo" style="margin-top: 10px" class="btn btn-info" type="submit">Guardar</button>
</div>


</div>
</div>
</div>


<!-- $$$$$$$$$$$$$$$$$$$$ MODAL DE NUEVO REG $$$$$$$$$$$$$$$$$$$$$$ -->
<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->
































<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->
<!-- $$$$$$$$$$$$$$$$$$$$ MODAL DE EDIT REG $$$$$$$$$$$$$$$$$$$$$$ -->



<div class="modal fade" role="dialog" tabindex="-1" id="modal-edit">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">

<div class="modal-header" id="modal-header" style="background-color:rgb(255,255,255);">
<h4 class="text-center modal-title" id="modal-heading" style="width:100%;">EDICION DE USUARIO</h4>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>

<div class="modal-body" style="background-color:#f8f8f8;">

<input type="hidden" class="form-control" id="e_id" name="e_id">

<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Usuario: </span></div>
<input type="text" class="form-control" id="e_usuario" name="e_usuario">
</div>

<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Nombre Completo: </span></div>
<input type="text" class="form-control" id="e_nombre" name="e_nombre"  >
</div>

<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Correo: </span></div>
<input type="text" class="form-control" id="e_mail" name="e_mail" >
</div>

<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Estado: </span></div>
<select class="form-control" name="e_estado" id="e_estado" >
<option value="1" >Activo</option>
<option value="2" >Inactivo</option>
<option value="3" >Nuevo Usuario</option>
</select>
</div>

</div>

<div class="modal-footer" id="modal-footer" style="background-color:rgb(255,255,255);">
<button name="editar_usuario" style="margin-top: 10px" class="btn btn-info" type="submit">Guardar Cambios</button>
</div>


</div>
</div>
</div>


<!-- $$$$$$$$$$$$$$$$$$$$ MODAL DE EDIT REG $$$$$$$$$$$$$$$$$$$$$$ -->
<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->










<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->
<!-- $$$$$$$$$$$$$$$$$$$$ MODAL ACCESSOSSSS $$$$$$$$$$$$$$$$$$$$$$ -->



<div class="modal fade" role="dialog" tabindex="-1" id="modal-accesos">
<div class="modal-dialog modal-xl" style="max-width: 80%;" role="document">
<div class="modal-content">

<div class="modal-header bg-secondary text-white" id="modal-header"  >
<h4 class="text-center modal-title" id="modal-heading" style="width:100%;">GESTION DE ACCESOS</h4>
<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>

<div class="modal-body" id="div_accesos" >

<div class="input-group" >
<div class="input-group-prepend"><span class="input-group-text">USUARIO: </span></div>
<input type="text" class="form-control" id="accesos_u" name="accesos_u" readonly="true">
</div>

<br>
<input type="hidden" class="form-control" id="accesos_id" name="accesos_id">



<div class="row">
<div class="col">

<div class="card"  >
<div class="card-header">
<h4 align="center">GERENCIA</h4>
</div>

<div class="card-body">

<select id="s_gerencia" name="s_gerencia" onclick="cargar_datos('1')" class="form-control"  size="5">

<?php

foreach (glob('../../*', GLOB_ONLYDIR) as $dir) {
	$dirname = basename($dir);
	if ($dirname != 'assets' && $dirname != 'template') {
		echo "<option value ='" . $dirname . "' >" . $dirname . "</option>";
	}
}

?>

</select>

</div>

</div>

</div>





<div class="col">

<div class="card"  >
<div class="card-header">
<h4 align="center">DEPARTAMENTO</h4>
</div>

<div class="card-body" >

<select id="s_depto" name="s_depto" onclick="cargar_datos('2')" class="form-control"  size="5">

<?php

foreach (glob('../../*', GLOB_ONLYDIR) as $dir) {
	$dirname = basename($dir);

	if ($dirname != 'assets' && $dirname != 'template' && $dirname != 'imagenes') {
		foreach (glob('../../' . $dirname . '/*', GLOB_ONLYDIR) as $subdir) {
			$subdirname = basename($subdir);

			if ($subdirname != 'assets' && $subdirname != 'template' && $subdirname != 'imagenes') {
				echo "<option style = 'display:none' id = '" . $dirname . "' value = '" . $subdirname . "' >" . $subdirname . "</option>";
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

<hr>

<table id="" class="table table-bordered">
<thead>
<tr>
<th>GERENCIA</th>
<th>DEPARTAMENTO</th>
<th>PANTALLA</th>
<th>DESCRIPCIÓN MENU</th>
<th>ACCIÓN</th>
</tr>
</thead>
<tbody id="div_accesos_asignados">



</tbody>
</table>



</div>

<div class="modal-footer" id="modal-footer" >
<button name="guardar_accesos" style="margin-top: 10px" class="btn btn-info" type="submit">Guardar</button>
</div>


</div>
</div>
</div>


<!-- $$$$$$$$$$$$$$$$$$$$ MODAL ACCESSOSSSS $$$$$$$$$$$$$$$$$$$$$$ -->
<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->


</form>
