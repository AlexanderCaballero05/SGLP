<?php

if (isset($_POST['finalizar_reposicion'])) {

$v_parametros = explode("_",$_POST['parametros_rango']);

$id_sorteo = $v_parametros[0];
$id_revisor = $v_parametros[1];
$num_lista = $v_parametros[2];
$revision  = $v_parametros[3];

date_default_timezone_set('America/Tegucigalpa');
$fecha = date("Y-m-d H:i:s");

if (mysqli_query($conn,"INSERT INTO cc_produccion_mayor (id_sorteo,id_revisor,numero_revisor,numero_revision,estado_revisor,fecha_cierre_revisor) VALUES ('$id_sorteo','$id_revisor','$num_lista','$revision','FINALIZADA','$fecha') ") === true) {

mysqli_query($conn,"UPDATE cc_revisores_sorteos_mayores_control SET estado = 'APROBADO' WHERE id_sorteo = $id_sorteo AND estado != 'REPROBADO' AND id_revisor = $id_revisor  AND num_lista = $num_lista AND numero_revision = $revision  ");	

echo "<div class = 'alert alert-info'>
Se ha finalizado la reposicion ".$revision."
</div>";

?>
<script type="text/javascript">
	
swal({
title: "",
  text: "Reposicion finalizada correctamente.",
  type: "success" 
})
.then(() => {
    window.location.href = './cc_revisor_operativo_mayor.php';
});

</script>
<?php



}else{

echo "<div class = 'alert alert-danger'>
Error inesperado, por favor vuelva a intentarlo
</div>";

}

}












//////////////////////////////////////////////////////////////////
////////////////// REPORBAR RANGO DE BILLETE /////////////////////


if (isset($_POST['reprobar_rango'])) {

$v_parametros = explode("_",$_POST['parametros_rango']);


$id_sorteo  = $v_parametros[0];
$id_revisor = $v_parametros[1];
$num_lista  = $v_parametros[2];
$revision   = $v_parametros[3];
$revision   = $revision + 1;

//$billete_inicial = $_POST['desde'];
//$billete_final = $_POST['hasta'];


$i = 0;
$acum = 1;
$bandera_registro = 1;
while (isset($_POST['billete_reprobado'][$i])) {
$billete_reprobado  = $_POST['billete_reprobado'][$i];
$registro_reprobado = $_POST['registro_reprobado'][$i];

if (isset( $_POST['re_reprobado'.$acum])) {
$re_billete = "SI";
}else{
$re_billete = "NO";	
}

//echo $billete_reprobado." ".$registro_reprobado." ".$re_billete;
//echo "<br>";

$id_posteo = $_SESSION['id_usuario'];


$c_validar_registro = mysqli_query($conn, "SELECT * FROM cc_revisores_sorteos_mayores_control WHERE id_sorteo = '$id_sorteo' AND billete = '$billete_reprobado' ");

if (mysqli_num_rows($c_validar_registro) == 0 ) {

if (mysqli_query($conn,"INSERT INTO cc_revisores_sorteos_mayores_control (id_sorteo,id_revisor,num_lista,numero_revision,billete,registro,especial,id_posteo) VALUES ('$id_sorteo','$id_revisor','$num_lista','$revision','$billete_reprobado','$registro_reprobado','$re_billete','$id_posteo') ") === FALSE) {

$bandera_registro = 0;

echo "<div class = 'alert alert-danger'>Error: El billete ".$billete_reprobado." No pudo ser Ingresado para reposicion</div>";

 } 

}else{

echo "<div class = 'alert alert-danger'>Error: El billete ".$billete_reprobado." Ya fue reprobado anteriormente.</div>";


}

$acum ++; 
$i++;
}

if ($bandera_registro == 1) {

echo "<div class = 'alert alert-info'>Billetes ingresados para reposicion correctamente.</div>";



}

}


////////////////// REPORBAR RANGO DE BILLETE /////////////////////
//////////////////////////////////////////////////////////////////














if (isset($_POST['reprobar_nuevamente'])) {

$v_parametros = explode("_",$_POST['parametros_rango']);

$id_sorteo  	   = $v_parametros[0];
$id_revisor 	   = $v_parametros[1];
$num_lista  	   = $v_parametros[2];
$revision   	   = $v_parametros[3];
$revision   	   = $revision + 1;
$revision_anterior = $revision - 1; 

$tt_billetes = $_POST['tt_revision'];

$i = 0;

while ($i <= $tt_billetes) {

if (isset($_POST['check'.$i])) {

$billete  		= $_POST['billete'.$i];
$registro 		= $_POST['registro'.$i];
$especial 		= $_POST['especial'.$i];
$id_reprobacion = $_POST['id_reprobacion'.$i];

$id_posteo = $_SESSION['id_usuario'];


$c_validar_registro = mysqli_query($conn, "SELECT * FROM cc_revisores_sorteos_mayores_control WHERE id_sorteo = '$id_sorteo' AND billete = '$billete' AND numero_revision = '$revision' ");

if (mysqli_num_rows($c_validar_registro) == 0 ) {

mysqli_query($conn, "INSERT INTO cc_revisores_sorteos_mayores_control (id_sorteo,id_revisor,num_lista,numero_revision,billete, registro ,especial, id_posteo) VALUES ('$id_sorteo','$id_revisor','$num_lista','$revision','$billete','$registro','$especial','$id_posteo') ");

mysqli_query($conn, "UPDATE cc_revisores_sorteos_mayores_control SET estado = 'REPROBADO' WHERE id = '$id_reprobacion' ");

}else{


echo "<div class = 'alert alert-danger'>Error: El billete ".$billete." Ya fue reprobado anteriormente.</div>";

}



}

$i++;
}

echo "<div class = 'alert alert-info'>Billetes reprobados correctamente.</div>";




}





if (isset($_POST['anular_reposicion'])) {

$billete = $_POST['anular_reposicion'];

$v_parametros = explode("_",$_POST['parametros_rango']);
$id_sorteo  = $v_parametros[0];
$id_revisor = $v_parametros[1];
$num_lista  = $v_parametros[2];
$revision   = $v_parametros[3];
$revision   = $revision + 1;
$revision_anterior = $revision - 1;



if ($revision > 2) {
mysqli_query($conn, "UPDATE cc_revisores_sorteos_mayores_control SET estado = 'PENDIENTE' WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND billete = '$billete' AND numero_revision = '$revision_anterior' ");
}

if (mysqli_query($conn, "DELETE FROM cc_revisores_sorteos_mayores_control WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND billete = '$billete' AND numero_revision >= '$revision' ") === TRUE) {

echo "<div class = 'alert alert-info'>Reposicion anulada correctamente</div>";

}else{

echo "<div class = 'alert alert-danger'>Error inesperado, por favor intente nuevamente.</div>";

} 



}


?>