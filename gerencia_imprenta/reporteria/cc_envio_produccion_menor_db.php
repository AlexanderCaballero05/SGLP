<?php
if (isset($_POST['enviar_produccion'])) {

$id = $_POST['enviar_produccion'];

date_default_timezone_set('America/El_Salvador');
$fecha_actual = date('Y-m-d H:i:s');

if (mysqli_query($conn,"UPDATE cc_produccion_menor  SET estado_envio_produccion = 'ENVIADO', fecha_envio_produccion = '$fecha_actual' WHERE id = '$id' ")) {

echo "<div class = 'alert alert-success'>Solicitud de reposicion enviada a produccion correctamente. </div>";

}else{
echo mysqli_error();	
}
}

if (isset($_POST['recepcion_cc'])) {
$id = $_POST['recepcion_cc'];

date_default_timezone_set('America/El_Salvador');
$fecha_actual = date('Y-m-d H:i:s');

if (mysqli_query($conn,"UPDATE cc_produccion_menor  SET estado_recepcion_cc = 'RECIBIDO', fecha_recepcion_cc = '$fecha_actual' WHERE id = '$id' ")) {

echo "<div class = 'alert alert-success'>Solicitud de reposicion enviada a produccion correctamente. </div>";

}else{
echo mysqli_error();	
}
}



if (isset($_POST['cancelar_finalizacion'])) {

$id = $_POST['cancelar_finalizacion'];

$info_finalizacion = mysqli_query($conn, "SELECT * FROM cc_produccion_menor WHERE id = '$id' ");
$ob_finalizacion   = mysqli_fetch_object($info_finalizacion); 
$id_revisor 	   = $ob_finalizacion->id_revisor;
$id_sorteo  	   = $ob_finalizacion->id_sorteo;
$numero_revision   = $ob_finalizacion->numero_revision;
$num_lista 		   = $ob_finalizacion->numero_revisor;

/////////////////////////////////////////////////////////////////////////////////
///////////////////// CAMBIO DE ESTADOS A LOTERIA APROBADA //////////////////////

$delete_finalizacion =  mysqli_query($conn, "DELETE FROM cc_produccion_menor WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND numero_revision >= '$numero_revision' ");


$update  =  mysqli_query($conn, "UPDATE cc_revisores_sorteos_menores_control SET estado = 'PENDIENTE' WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND num_lista = '$num_lista' AND  numero_revision = '$numero_revision' AND estado = 'APROBADO' ");


$update  =  mysqli_query($conn, "UPDATE cc_revisores_sorteos_menores_control SET estado = 'PENDIENTE' WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND num_lista = '$num_lista' AND numero_revision > '$numero_revision'  ");


$numero_revision ++;

$delete_posteriores = mysqli_query($conn, "DELETE FROM cc_revisores_sorteos_menores_control WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND numero_revision > '$numero_revision' AND num_lista = '$num_lista'  ");


$numero_revision --;

if ( $delete_finalizacion === TRUE) {
echo "<div class = 'alert alert-info'>Revision ".$numero_revision." aperturada nuevamente</div>";
}else{
echo "<div class = 'alert alert-danger'>Error inesperado, por favor intente nuevamente.</div>";	
}

///////////////////// CAMBIO DE ESTADOS A LOTERIA APROBADA //////////////////////
/////////////////////////////////////////////////////////////////////////////////

}









/////////////////////////////////////////////////////////////////////////////////
//////////////////////////// FINALIZAR REPOSICION ///////////////////////////////

if (isset($_POST['finalizar_reposicion'])) {

$v_parametros = explode("_",$_POST['finalizar_reposicion']);

$id_sorteo = $v_parametros[0];
$revision  = $v_parametros[1];

date_default_timezone_set('America/Tegucigalpa');
$fecha = date("Y-m-d H:i:s");

$i = 0;

while (isset($_POST['num_lista_'.$revision][$i])) {
$num_lista  =  $_POST['num_lista_'.$revision][$i];
$id_revisor =  $_POST['id_revisor_'.$revision][$i];


if (mysqli_query($conn,"INSERT INTO cc_produccion_menor (id_sorteo,id_revisor,numero_revisor,numero_revision,estado_revisor,fecha_cierre_revisor) VALUES ('$id_sorteo','$id_revisor','$num_lista','$revision','FINALIZADA','$fecha') ") === true) {

mysqli_query($conn,"UPDATE cc_revisores_sorteos_menores_control SET estado = 'APROBADO' WHERE id_sorteo = $id_sorteo AND estado != 'REPROBADO' AND id_revisor = $id_revisor  AND num_lista = $num_lista AND numero_revision = $revision  ");	

}

$i++;
}

echo "<div class = 'alert alert-info'>
Se ha finalizado la reposicion ".$revision."
</div>";


/*



}else{

echo "<div class = 'alert alert-danger'>
Error inesperado, por favor vuelva a intentarlo
</div>";

}

*/

}

//////////////////////////// FINALIZAR REPOSICION ///////////////////////////////
/////////////////////////////////////////////////////////////////////////////////



?>