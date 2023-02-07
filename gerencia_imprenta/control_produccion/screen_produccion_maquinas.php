<?php
require('../../template/header.php');

$select_sorteos = mysqli_query($conn, "SELECT * FROM sorteos_menores ORDER BY no_sorteo_men DESC ");
$maquinas = mysqli_query($conn, "SELECT * FROM pro_maquinas ");


if (isset($_POST['editar_maquina'])) {

$id_maquina   = $_POST['editar_maquina'];
$desc_maquina = $_POST['maquina_'.$id_maquina];

if (mysqli_query($conn, "UPDATE pro_maquinas SET maquina = '$desc_maquina' WHERE id = '$id_maquina' ") === FALSE) {
echo "<div class = 'alert alert-danger'>Error inesperado</div>";
}else{
echo "<div class = 'alert alert-info'>Cambio realizado correctamente.</div>";	
}

$maquinas = mysqli_query($conn, "SELECT * FROM pro_maquinas ");

}




if (isset($_POST['guardar_nuevo'])) {

$desc_maquina = $_POST['nueva_maquina'];

if (mysqli_query($conn, "INSERT INTO pro_maquinas (maquina) VALUES ('$desc_maquina') ") === FALSE) {
echo "<div class = 'alert alert-danger'>Error inesperado</div>";
}else{
echo "<div class = 'alert alert-info'>Cambio realizado correctamente.</div>";	
}

$maquinas = mysqli_query($conn, "SELECT * FROM pro_maquinas ");

}



?>

<form method="POST">

<section style=" background-color:#ededed;">
<br>
<h2  align="center" style="color:black;"  >GESTION DE MAQUINAS DE PRODUCCION</h2> 
<br>
</section>


<br>

<div class="card" style="margin-left: 10px;margin-right: 10px; ">
<div class="card-header">
	<h4 align="center"> MAQUINAS </h4>
</div>

<div class="card-body">
<table class="table table-bordered">
	<tr>
		<th> DESCRIPCION MAQUINA</th>
		<th width="15%"> ACCION</th>
	</tr>

<?php 

while ($reg_maquina = mysqli_fetch_array($maquinas)) {

echo "
<tr>
<td><input class = 'form-control' type = 'text' name = 'maquina_".$reg_maquina['id']."' value = '".$reg_maquina['maquina']."' ></td>
<td align = 'center'><button name = 'editar_maquina'  class = 'btn btn-info fa fa-edit' type = 'submit' value = '".$reg_maquina['id']."' ></button></td>
</tr>";

}

?>

</table>	
</div>

<div class="card-footer">

<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text" >Ingresar nueva maquina: </span>	
</div>	
<input type="text" name="nueva_maquina" class="form-control">

<button type="submit" name="guardar_nuevo" class="btn btn-primary" >GUARDAR</button>

</div>


</div>

</div>

<br><br><br><br>

</form>