<?php

if (isset($_POST['eliminar_control'])) {
$id_control = $_POST['eliminar_control'];

mysqli_query($conn, "DELETE FROM  pro_control_detalle_menor WHERE id_control = '$id_control' ");

if (mysqli_query($conn, "DELETE FROM  pro_control_menor WHERE id = '$id_control' ") === true) {

mysqli_query($conn, "DELETE FROM  pro_control_prensistas_menor WHERE id_control = '$id_control' ");

echo "<div class = 'alert alert-info'>Registro eliminado correctamente.</div>";
}else{
echo "<div class = 'alert alert-danger'>Error inesperado, por favor vuelva a intentarlo. ".mysqli_error()."</div>";	
}

}








if (isset($_POST['iniciar_control'])) {

$id_sorteo =  $_POST['sorteo1'];
$id_maquina =  $_POST['id_maquina'];
$jornada =  $_POST['jornada'];
$contador_inicial =  $_POST['contador_inicial'];
$h_i = $_POST['h_i'];

$prensistas 	  = $_POST['select_prensistas'];


if (isset($_POST['grupo'])) {
$grupo = $_POST['grupo'];
}else{
$grupo = '';	
}


$v_operador = explode("/", $_POST['id_operador']);
$id_operador = $v_operador[0];
$nombre_operador = $v_operador[1];

$fecha = $_POST['fecha_inicial'];
$fecha_actual = date("Y-m-d", strtotime($fecha));


if (mysqli_query($conn, "INSERT INTO pro_control_menor (id_orden,id_orden_2,maquina,jornada,fecha,contador_inicial,id_operador_encargado, nombre_operador_encargado,hora_inicial,grupo) VALUES ('$id_sorteo','$id_sorteo','$id_maquina','$jornada','$fecha_actual','$contador_inicial','$id_operador', '$nombre_operador','$h_i','$grupo') ") === false) {
echo mysqli_error($conn);
}else{



$c_max_control = mysqli_query($conn, "SELECT MAX(id) as maximo FROM pro_control_menor WHERE contador_inicial = '$contador_inicial' AND id_orden = '$id_sorteo'  ");

if ($c_max_control === FALSE) {
echo mysqli_error($conn);
}

$ob_max_control = mysqli_fetch_object($c_max_control);
$id_control = $ob_max_control->maximo;
 
$i = 0;
while (isset($prensistas[$i])) {

$v_prensista =  explode("%", $prensistas[$i]);

$cedula =  $v_prensista[0];
$name   =  $v_prensista[1];

mysqli_query($conn, "INSERT INTO pro_control_prensistas_menor (id_control ,cedula, nombre) VALUES ('$id_control' ,'$cedula','$name') ");

$i++;
}



echo "<div class = 'alert alert-info' >Control Iniciado Correctamente</div>";	
}

}


?>