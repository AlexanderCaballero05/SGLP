<?php
require('../../template/header.php');

$sorteos_mayores = mysqli_query($conn,"SELECT * FROM sorteos_mayores WHERE control_calidad = 'SI' ORDER BY no_sorteo_may DESC ");
$revisores_select = mysqli_query($conn,"SELECT * FROM pani_usuarios WHERE estados_id = '1'  AND areas_id = '5' ORDER BY nombre_completo ASC ");


if ($sorteos_mayores===false) {
echo mysqli_error();
}


?>

<form method="POST">

<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >REVISION DE LOTERIA MAYOR</h2> 
<br>
</section>

<br>

<div class="card" style="margin-left: 10px;margin-right: 10px">
<div class="card-header" align="center">

<div style="width: 50%">
<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span  class="input-group-text">Sorteo: </span></div>
<select name="sorteo" class="form-control" >
<?php 
while ($sorteo = mysqli_fetch_array($sorteos_mayores)) {
echo "<option value = '".$sorteo['id']."'>".$sorteo['no_sorteo_may']."</option>";
}
?>
</select>

<div class="input-group-prepend"><span  class="input-group-text">Revisor: </span></div>
<select name="select_revisor" class="form-control" >
<?php
while ($revisor_select = mysqli_fetch_array($revisores_select)) {
echo "<option value='".$revisor_select['id']."'>".$revisor_select['nombre_completo']."</option>";
}
?>
</select>


<input name="seleccionar" type="submit" class="btn btn-primary" value="Seleccionar" style="margin-left: 10px"></input>
</div>
</div>
	
</div>	
<div class="card-body">
	



<?php
if (isset($_POST['seleccionar'])) {
$id_sorteo = $_POST['sorteo'];
$_SESSION['id_sorteo_cc'] = $id_sorteo;
$id_revisor = $_POST['select_revisor'];

$info_revisor   = mysqli_query($conn ,"SELECT * FROM pani_usuarios WHERE id = '$id_revisor' ");
$ob_revisor     = mysqli_fetch_object($info_revisor); 
$nombre_revisor = $ob_revisor->nombre_completo;


$asignaciones =  mysqli_query($conn,"SELECT * FROM `cc_revisores_sorteos_mayores` WHERE `id_sorteo` = $id_sorteo AND `id_revisor` = $id_revisor ");


while ($num_revision = mysqli_fetch_array($asignaciones)) {
$num_lista = $num_revision['numero'];


echo "<br>";

echo '<div class=" card" >';

echo "<div class = 'card-header'><h4>SORTEO: ".$id_sorteo." <br> NUMERO DE LISTA: ".$num_lista." <br> REVISOR: ".$nombre_revisor." </h4></div>";

echo "<div class = 'card-body' >";

$n_l =  $num_revision['numero'];
$revisiones = mysqli_query($conn,"SELECT * FROM cc_produccion_mayor WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND numero_revisor = '$n_l'    ");
if ($revisiones=== false) {
echo mysqli_error($conn);
}

$concat_numero_revision = '';

if (mysqli_num_rows($revisiones) > 0) {

while ($row_revision = mysqli_fetch_array($revisiones)) {
$revision = $row_revision['numero_revision'];

$concat_numero_revision .= $revision.',';


echo"<a  class = 'btn btn-secondary' href = './cc_revisor_operativo_mayor_detalle.php?id_sort=".$id_sorteo."&num_asig=".$num_revision['numero']."&id_rev=".$id_revisor."&revision=".$revision."' style = 'width:100%; margin-top: 5px; margin-bottom: 5px;'> Reposicion ".$revision." Finalizada</a>";
}

}else{

echo"<a class = 'btn btn-success' href = './cc_revisor_operativo_mayor_detalle.php?id_sort=".$id_sorteo."&num_asig=".$num_revision['numero']."&id_rev=".$id_revisor."&revision=1' style = 'width:100%'> Realizar Reposicion 1</a>";

}


$concat_numero_revision = substr($concat_numero_revision, 0, -1);


if (strlen($concat_numero_revision) == 0) {

$revisiones_pendientes = mysqli_query($conn,"SELECT * FROM cc_revisores_sorteos_mayores_control WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND num_lista = '$num_lista' GROUP BY numero_revision ");

}else{

$revisiones_pendientes = mysqli_query($conn,"SELECT * FROM cc_revisores_sorteos_mayores_control WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND num_lista = '$num_lista' AND numero_revision NOT IN ($concat_numero_revision)  GROUP BY numero_revision ");

}



if ($revisiones_pendientes === FALSE) {
echo mysqli_error($conn);
}

while ($row_revisiones_pendietes = mysqli_fetch_array($revisiones_pendientes)) {

$revision_pendiente = $row_revisiones_pendietes['numero_revision'];

echo"<a class = 'btn btn-success' href = './cc_revisor_operativo_mayor_detalle.php?id_sort=".$id_sorteo."&num_asig=".$num_revision['numero']."&id_rev=".$id_revisor."&revision=".$revision_pendiente."' style = 'width:100%; margin-top: 5px; margin-bottom: 5px;' > Realizar Reposicion ".$revision_pendiente."</a>";

}




echo "</div>";
echo "</div>";


}

/*	
header('Location: cc_revisor_operativo_mayor_detalle.php');
*/
}
?>

</div>
</div>

</form>