<?php

require('../../template/header.php');



$empresas = mysqli_query($conn,"SELECT * FROM empresas WHERE estado = 'ACTIVO' ");

$seccionales = mysqli_query($conn,"SELECT a.id, a.id_empresa, a.cod_seccional, a.nombre, a.telefono, a.direccion, a.geocodigo , b.departamento, b.municipio, c.nombre_empresa FROM fvp_seccionales as a INNER JOIN departamentos_municipios as b INNER JOIN empresas as c ON a.geocodigo_id = b.id AND a.id_empresa = c.id WHERE c.estado = 'ACTIVO'  ");

if ($seccionales === false) {
echo mysqli_error($conn);
}


////////////////////////////////////////
//////// CODIGO DE GUARDADO ////////////

if (isset($_POST['guardar_nuevo'])) {

$id_empresa 			= $_POST['nueva_empresa'];
$nombre 					= $_POST['nuevo_nombre'];
$cod_departamento = $_POST['nuevo_departamento'];
$id_geocodigo 		= $_POST['nuevo_municipio'];


if (mysqli_query($conn,"INSERT INTO fvp_seccionales (id_empresa,nombre,geocodigo_id) VALUES ('$id_empresa','$nombre','$id_geocodigo') ")) {

echo "<div class = 'alert alert-info'>Punto de venta ingresado correctamente</div>";

$seccionales = mysqli_query($conn,"SELECT a.id, a.id_empresa, a.cod_seccional, a.nombre, a.telefono, a.direccion, a.geocodigo , b.departamento, b.municipio, c.nombre_empresa FROM fvp_seccionales as a INNER JOIN departamentos_municipios as b INNER JOIN empresas as c ON a.geocodigo_id = b.id AND a.id_empresa = c.id WHERE c.estado = 'ACTIVO'  ");

}else{

echo mysqli_error($conn);
echo "<div class = 'alert alert-danger'>Error inesperado, Por favor intentelo nuevamente</div>";

}

}

////// FIN CODIGO DE GUARDADO //////////
////////////////////////////////////////






///////////////////////////////////////
//////// CODIGO DE EDICION ////////////

if (isset($_POST['guardar_edicion'])) {

$id_seccional = $_POST['edicion_id_seccional'];
$nombre 			= $_POST['edicion_nombre'];
$id_geocodigo = $_POST['edicion_municipio'];


if (mysqli_query($conn,"UPDATE fvp_seccionales SET  nombre = '$nombre' , geocodigo_id = '$id_geocodigo' WHERE id = '$id_seccional' ")) {

echo "<div class = 'alert alert-info'>Punto de venta editado correctamente</div>";

$seccionales = mysqli_query($conn,"SELECT a.id, a.id_empresa, a.cod_seccional, a.nombre, a.telefono, a.direccion, a.geocodigo , b.departamento, b.municipio, c.nombre_empresa FROM fvp_seccionales as a INNER JOIN departamentos_municipios as b INNER JOIN empresas as c ON a.geocodigo_id = b.id AND a.id_empresa = c.id WHERE c.estado = 'ACTIVO'  ");

}else{
echo mysqli_error($conn);
echo "<div class = 'alert alert-danger'>Error inesperado, Por favor intentelo nuevamente</div>";
}

}

////// FIN CODIGO DE EDICION //////////
///////////////////////////////////////

?>

<script type="text/javascript">

function cargar_edicion(id_seccional,id_empresa_seccional,nombre_seccional){

document.getElementById("edicion_id_seccional").value = id_seccional;
document.getElementById("edicion_nombre").value = nombre_seccional;

}


function funcion_seleccion_nuevo(id_depto){
var obj_select = document.getElementById("nuevo_municipio");
conteo_opciones = obj_select.length;
obj_select.options[0].selected = true;

for (var i = 1; i <= conteo_opciones; i++) {

if (obj_select.options[i].id == id_depto ) {
obj_select.options[i].style.display = "block";
}else{
obj_select.options[i].style.display = "none";
}
}

}


function funcion_seleccion_edicion(id_depto){
var obj_select = document.getElementById("edicion_municipio");
conteo_opciones = obj_select.length;
obj_select.options[0].selected = true;

for (var i = 1; i <= conteo_opciones; i++) {

if (obj_select.options[i].id == id_depto ) {
obj_select.options[i].style.display = "block";
}else{
obj_select.options[i].style.display = "none";
}
}

}

</script>


<section style="color:rgb(63,138,214);background-color:#ededed;">
<br>
<h1  align="center" style="color:black; "  >GESTION DE ENTIDADES</h1>
<br>
</section>


<form  method="POST">
<hr>
<p align="center">
<a style="width: 100%" data-toggle="modal" href="#nuevo" class="btn btn-primary">
Ingresar Nuevo Punto de Venta
</a>
</p>

<table width="100%" id="table_id1" class="table table-bordered">
	<thead>
		<tr>
      <th >Entidad</th>
			<th >Agencia</th>
			<th >Departamento</th>
			<th >Municipio</th>
			<th >Accion</th>
		</tr>
		</thead>
	<tbody>


<?php
while ($seccional = mysqli_fetch_array($seccionales)) {

$id_seccional = $seccional['id'];
$id_empresa_seccional = $seccional['id_empresa'];
$cod_seccional 			  = $seccional['cod_seccional'];
$nombre_seccional 	  = $seccional['nombre'];
$telefono_seccional   = $seccional['telefono'];
$direccion_seccional  = $seccional['direccion'];
$geocodigo_seccional  = $seccional['departamento'];
$municipio_seccional  = $seccional['municipio'];
$nombre_empresa_seccional = $seccional['nombre_empresa'];


echo "<tr>";
echo "<td>".$nombre_empresa_seccional."</td>";
echo "<td>".$nombre_seccional."</td>";
echo "<td>".$geocodigo_seccional."</td>";
echo "<td>".$municipio_seccional ."</td>";

echo "<td align = 'center'>";
?>

<a onclick = "cargar_edicion('<?php echo $id_seccional; ?>','<?php echo $id_empresa_seccional; ?>','<?php echo $nombre_seccional;?>')" data-toggle='modal' href='#editar' class='btn btn-primary'>Editar</a>

<?php
			echo "</td>";
			echo "</tr>";
}
		?>
		</tbody>
	</table>



<!-- //////////////////////////////// -- >
<!--       Modal NUEVA ENTIDAD         -->


<div class="modal" id="nuevo" >
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div  class="modal-header alert-info">
        <h4 class="modal-title" align="center">NUEVO PUNTO DE VENTA</h4>
      </div>
      <div class="modal-body">
      <div class="panel-body">

<br>

<div class="input-group" style="margin-bottom: 10px;">
<div class="input-group-prepend"><div class="input-group-text">Entidad</div></div>
<select name="nueva_empresa" id="nueva_empresa" class = 'form-control' >
<?php
while ($empresa = mysqli_fetch_array($empresas)) {
echo "<option value = '".$empresa['id']."'>".$empresa['nombre_empresa']."</option>";
}
?>
</select>
</div>

<div class="input-group" style="margin-bottom: 10px;">
<div class="input-group-prepend"><div class="input-group-text">Agencia</div></div>
<input class="form-control"  type="text" name="nuevo_nombre">
</div>


<div class="input-group" style="margin-bottom: 10px;">
<div class="input-group-prepend"><div class="input-group-text">Departamento</div></div>
<select required class="form-control" id="nuevo_departamento"  name="nuevo_departamento" onchange=" funcion_seleccion_nuevo(this.value);" >
<option>Seleccione una opcion</option>
<?php

$departamentos = mysqli_query($conn,"SELECT * FROM departamentos_municipios GROUP BY departamento ORDER BY id ASC ");

while ($dpto = mysqli_fetch_array($departamentos)) {
echo '<option value="'.$dpto['cod_departamento'].'">'.$dpto['departamento'].'</option>';
}

?>
</select>
</div>


<div class="input-group" style="margin-bottom: 10px;">
<div class="input-group-prepend"><div class="input-group-text">Municipio</div></div>
<select required name="nuevo_municipio" id="nuevo_municipio"  class="form-control"  >
<option>Seleccione una opcion</option>
<?php

$municipios = mysqli_query($conn,"SELECT * FROM departamentos_municipios GROUP BY municipio ORDER BY id ASC");

while ($municipio = mysqli_fetch_array($municipios)) {
echo "<option style = 'display:none' id = '".$municipio['cod_departamento']."' value = '".$municipio['id']."'>".utf8_encode($municipio['municipio'])."</option>";
}
?>

</select>
</div>

<br>

</div>
      <div class="modal-footer" align="center">
        <button type="submit" name="guardar_nuevo" class="btn btn-primary">Aceptar</button>
        <span  class="btn btn-danger" data-dismiss="modal" >Cancelar</span>
      </div>
    </div>
  </div>
</div>
</div>














<!-- MODAL DE EDICION  -->

<div class="modal" id="editar" >
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header alert-info">
        <h4 align="center">EDICION DE PUNTO DE VENTA</h4>
        </div>
      <div class="container"></div>
        <div class="modal-body">

<input class="form-control" type="hidden"  id="edicion_id_seccional" name="edicion_id_seccional">


<div class="input-group" style="margin-bottom: 10px;">
<div class="input-group-prepend"><div class="input-group-text">Nombre</div></div>
<input class="form-control"  type="text" id="edicion_nombre" name="edicion_nombre">
</div>



<div class="input-group" style="margin-bottom: 10px;">
<div class="input-group-prepend"><div class="input-group-text">Departamento</div></div>
<select required class="form-control" id="edicion_departamento"  name="edicion_departamento" onchange=" funcion_seleccion_edicion(this.value);" >
<option>Seleccione una opcion</option>
<?php

$departamentos = mysqli_query($conn,"SELECT * FROM departamentos_municipios GROUP BY departamento ORDER BY id ASC ");

while ($dpto = mysqli_fetch_array($departamentos)) {
echo '<option value="'.$dpto['cod_departamento'].'">'.$dpto['departamento'].'</option>';
}

?>
</select>
</div>


<div class="input-group" style="margin-bottom: 10px;">
<div class="input-group-prepend"><div class="input-group-text">Municipio</div></div>
<select required name="edicion_municipio" id="edicion_municipio"  class="form-control"  >
<option>Seleccione una opcion</option>
<?php

$municipios = mysqli_query($conn,"SELECT * FROM departamentos_municipios GROUP BY municipio ORDER BY id ASC");

while ($municipio = mysqli_fetch_array($municipios)) {
echo "<option style = 'display:none' id = '".$municipio['cod_departamento']."' value = '".$municipio['id']."'>".utf8_encode($municipio['municipio'])."</option>";
}

?>

</select>
</div>



<br>




        </div>
        <div class="modal-footer">
        <button type="submit" name="guardar_edicion" class="btn btn-primary">Actualizar</button>
        <span  class="btn btn-danger" data-dismiss="modal" >Cancelar</span>
        </div>
      </div>
    </div>
</div>


</form>
