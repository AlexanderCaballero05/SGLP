<?php
require('../../template/header.php');



if (isset($_POST['guardar_nuevo'])) {
$codigo_seccional  = $_POST['nuevo_codigo'];
$codigo_asociacion = $_POST['nuevo_asociacion'];

if (mysqli_query($conn,"INSERT INTO asociaciones_seccionales (codigo_asociacion, codigo_seccional) VALUES ('$codigo_asociacion','$codigo_seccional') ") === TRUE) {

echo "<div class = 'alert alert-info'>Registro ingresado correctamente</div>";
}else{
echo "<div class = 'alert alert-danger'>Error inesperado, por favor intentelo de nuevo</div>";  
}


$seccionales  = mysqli_query($conn,"SELECT a.id, a.codigo_asociacion, a.codigo_seccional, a.estado, b.nombre_asociacion FROM asociaciones_seccionales as a INNER JOIN asociaciones_vendedores as b ON a.codigo_asociacion = b.codigo_asociacion  AND a.estado = '1' ");

}


if (isset($_POST['guardar_edicion'])) {

$id                = $_POST['id_edicion'];
$codigo_seccional  = $_POST['edicion_codigo'];
$codigo_asociacion = $_POST['edicion_asociacion'];

if (mysqli_query($conn," UPDATE asociaciones_seccionales SET codigo_asociacion = '$codigo_asociacion' , codigo_seccional = '$codigo_seccional' WHERE id = '$id' ") === TRUE) {

echo "<div class = 'alert alert-info'>Registro actualizado correctamente</div>";
}else{
echo "<div class = 'alert alert-danger'>Error inesperado, por favor intentelo de nuevo</div>";  
}


$seccionales  = mysqli_query($conn,"SELECT a.id, a.codigo_asociacion, a.codigo_seccional, a.estado, b.nombre_asociacion FROM asociaciones_seccionales as a INNER JOIN asociaciones_vendedores as b ON a.codigo_asociacion = b.codigo_asociacion  AND a.estado = '1' ");

}








$seccionales  = mysqli_query($conn,"SELECT a.id, a.codigo_asociacion, a.codigo_seccional, a.estado, b.nombre_asociacion FROM asociaciones_seccionales as a INNER JOIN asociaciones_vendedores as b ON a.codigo_asociacion = b.codigo_asociacion  AND a.estado = '1' ");

$asociaciones = mysqli_query($conn, "SELECT * FROM asociaciones_vendedores WHERE estado_asociacion = '1' ");
$asociaciones2 = mysqli_query($conn, "SELECT * FROM asociaciones_vendedores WHERE estado_asociacion = '1' ");

if ($seccionales === FALSE) {
echo mysqli_error($conn);
}

?>

<script type="text/javascript">
function cargar_edicion(id,edicion_codigo,asociacion){

document.getElementById("id_edicion").value = id;
document.getElementById("edicion_codigo").value = edicion_codigo;
document.getElementById("edicion_asociacion").value = asociacion;

}


</script>

<form method="POST">

<section style="color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >GESTION DE SECCIONALES - ASOCIACIONES</h2> 
<br>
</section>
<br>


<a style="margin-left: 15px; margin-right: 15px; width: 98%" data-toggle="modal" href="#nuevo" class="btn btn-primary">Agregar Seccional</a>

<br>
<br>
<div class="card" style="margin-left: 15px; margin-right: 15px">
<div class="card-header">
	<h4 align="center">Historico de Seccionales Registradas</h4>
</div>
<div class="card-body">

<table class="table table-bordered" id="table_id1">
	<thead>
    <th>Asociacion</th>
    <th>Seccional</th>
		<th>Estado</th>
		<th>Accion</th>
	</thead>
	<tbody>
<?php
while ($reg_asociacion = mysqli_fetch_array($seccionales)) {

$id = $reg_asociacion['id'];
$cod = $reg_asociacion['codigo_seccional'];
$asociacion = $reg_asociacion['codigo_asociacion'];

echo "<tr>";
echo "<td>";
echo $reg_asociacion['nombre_asociacion'];
echo "</td>";
echo "<td>";
echo $reg_asociacion['codigo_seccional'];
echo "</td>";
echo "<td>";
if ($reg_asociacion['estado'] == 1) {
echo "ACTIVO";
}else{
echo "INACTIVO";	
}
echo "</td>";
echo "<td align = 'center'>";

?>
<a onclick = "cargar_edicion('<?php echo $id;?>','<?php echo $cod;?>','<?php echo $asociacion;?>')" data-toggle='modal' href='#editar' class='btn btn-primary fa fa-edit'></a>
<?php

echo "</td>";
echo "</tr>";
}
?>
	</tbody>
</table>
</div>
</div>






<div class="modal" id="nuevo">
	<div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header alert alert-info" align="center">
        <h4 align="center">Nueva Seccional</h4>
        </div><div class="container"></div>
        <div class="modal-body">

<div class="input-group">
<div class="input-group-prepend"><span class="input-group-text">Asociacion</span></div>
<select class="form-control" name="nuevo_asociacion" id="nuevo_asociacion">
<?php 
while ($reg_asociacion = mysqli_fetch_array($asociaciones)) {
echo "<option value = '".$reg_asociacion['codigo_asociacion']."'>".$reg_asociacion['nombre_asociacion']."</option>";
}
?>
</select>
</div>

<div class="input-group" style="margin-top: 10px">
<div class="input-group-prepend"><span class="input-group-text">Codigo</span></div>
<input class="form-control" type="text" name="nuevo_codigo" id="nuevo_codigo">
</div>


        </div>
        <div class="modal-footer">
        <button type="submit" name="guardar_nuevo" class="btn btn-primary">Guardar</button>
        <span  class="btn btn-danger" data-dismiss="modal" >Cancelar</span>
        </div>
      </div>
    </div>
</div>











<div class="modal" id="editar" >
	<div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header alert alert-info" align="center">
      <h4  align="center">Edicion de Seccional</h4>

        </div><div class="container"></div>

        <div class="modal-body">

<input class="form-control" type="hidden" name="id_edicion" id="id_edicion">



<div class="input-group">
<div class="input-group-prepend"><span class="input-group-text">Asociacion</span></div>
<select class="form-control" name="edicion_asociacion" id="edicion_asociacion">
<?php 
while ($reg_asociacion = mysqli_fetch_array($asociaciones2)) {
echo "<option value = '".$reg_asociacion['codigo_asociacion']."'>".$reg_asociacion['nombre_asociacion']."</option>";
}
?>
</select>
</div>

<div class="input-group" style="margin-top: 10px">
<div class="input-group-prepend"><span class="input-group-text">Codigo</span></div>
<input class="form-control" type="text" name="edicion_codigo" id="edicion_codigo">
</div>



        </div>
        <div class="modal-footer">
        <button type="submit" name="guardar_edicion" class="btn btn-primary">Actualizar</button>
        <span  class="btn btn-danger" data-dismiss="modal" >Cancelar</span>
        </div>
      </div>
    </div>
</div>


</form>