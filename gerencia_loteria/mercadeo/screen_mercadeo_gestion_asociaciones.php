<?php

require('../../template/header.php');

$asociaciones = mysqli_query($conn,"SELECT * FROM asociaciones_vendedores ");

?>

<script type="text/javascript">
	function cargar_edicion(id,edicion_codigo,edicion_nombre){
document.getElementById("id_edicion").value = id;
document.getElementById("edicion_codigo").value = edicion_codigo;
document.getElementById("edicion_nombre").value = edicion_nombre;
	}
</script>

<form method="POST">

<section style="color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >GESTION DE ASOCIACIONES DE VENDEDORES</h2> 
<br>
</section>
<br>


<a style="margin-left: 15px; margin-right: 15px; width: 98%" data-toggle="modal" href="#nuevo" class="btn btn-primary">Agregar Asociacion</a>

<br>
<br>
<div class="card" style="margin-left: 15px; margin-right: 15px">
<div class="card-header">
	<h4 align="center">Historico de Asociaciones Registradas</h4>
</div>
<div class="card-body">

<table class="table table-bordered" id="table_id1">
	<thead>
		<th>Codigo</th>
		<th>Asociacion</th>
		<th>Estado</th>
		<th>Accion</th>
	</thead>
	<tbody>
<?php
while ($reg_asociacion = mysqli_fetch_array($asociaciones)) {

$id = $reg_asociacion['id'];
$cod = $reg_asociacion['codigo_asociacion'];
$nom = $reg_asociacion['nombre_asociacion'];

echo "<tr>";
echo "<td>";
echo $reg_asociacion['codigo_asociacion'];
echo "</td>";
echo "<td>";
echo $reg_asociacion['nombre_asociacion'];
echo "</td>";
echo "<td>";
if ($reg_asociacion['estado_asociacion'] == 1) {
echo "ACTIVO";
}else{
echo "INACTIVO";	
}
echo "</td>";
echo "<td align = 'center'>";

?>
<a onclick = "cargar_edicion('<?php echo $id;?>','<?php echo $cod;?>','<?php echo $nom;?>')" data-toggle='modal' href='#editar' class='btn btn-primary fa fa-edit'></a>
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
        <h4 align="center">Nueva asociacion</h4>
        </div><div class="container"></div>
        <div class="modal-body">

<div class="input-group">
<div class="input-group-prepend"><span class="input-group-text">Codigo</span></div>
<input class="form-control" type="text" name="nuevo_codigo" id="nuevo_codigo">
</div>

<div class="input-group" style="margin-top: 10px">
<div class="input-group-prepend"><span class="input-group-text">Nombre Asociacion</span></div>
<input class="form-control" type="text" name="nuevo_nombre" id="nuevo_nombre">
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
      <h4  align="center">Edicion de Asociacion</h4>

        </div><div class="container"></div>

        <div class="modal-body">

<input class="form-control" type="hidden" name="id_edicion" id="id_edicion">

<div class="input-group">
<div class="input-group-prepend"><span class="input-group-text">Codigo</span></div>
<input class="form-control" type="text" name="edicion_codigo" id="edicion_codigo">
</div>

<div class="input-group" style="margin-top: 10px">
<div class="input-group-prepend"><span class="input-group-text">Nombre Asociacion</span></div>
<input class="form-control" type="text" name="edicion_nombre" id="edicion_nombre">
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


<?php



if (isset($_POST['guardar_nuevo'])) {
$nuevo_codigo = $_POST['nuevo_codigo'];
$nuevo_nombre = $_POST['nuevo_nombre'];

if (mysqli_query($conn,"INSERT INTO asociaciones_vendedores (codigo_asociacion, nombre_asociacion,estado_asociacion) VALUES ('$nuevo_codigo','$nuevo_nombre','1') ") === TRUE) {

echo "<div class = 'alert alert-info'>Registro ingresado correctamente</div>";
}else{
echo "<div class = 'alert alert-danger'>Error inesperado, por favor intentelo de nuevo</div>";	
}


$asociaciones = mysqli_query($conn,"SELECT * FROM asociaciones_vendedores ");

}




if (isset($_POST['guardar_edicion'])) {
$id = $_POST['id_edicion'];
$codigo = $_POST['edicion_codigo'];
$nombre = $_POST['edicion_nombre'];

if (mysqli_query($conn," UPDATE asociaciones_vendedores SET codigo_asociacion = '$codigo' , nombre_asociacion = '$nombre' WHERE id = '$id' ") === TRUE) {

echo "<div class = 'alert alert-info'>Registro actualizado correctamente</div>";
}else{
echo "<div class = 'alert alert-danger'>Error inesperado, por favor intentelo de nuevo</div>";	
}


$asociaciones = mysqli_query($conn,"SELECT * FROM asociaciones_vendedores ");

}

?>