<?php

if (isset($_POST['guardar_grupo'])) {

$id_sorteo 	  = $_POST['hidden_sorteo'];
$num_grupo 	  = $_POST['hidden_grupo'];
$series_grupo = $_POST['input_cantidad_series'];
$bandera_registro = 0;

$i = 1;
while (isset($_POST['input_numero_'.$i])) {
$numero = $_POST['input_numero_'.$i];

if ($numero != '') {

if (mysqli_query($conn," INSERT INTO sorteos_menores_num_extras (id_sorteo, numero,cantidad, estado_sorteo, num_solicitud, grupo) VALUES ('$id_sorteo','$numero','$series_grupo','PENDIENTE PRODUCCION','2','$num_grupo')  ") === FALSE) {
echo mysqli_error($conn);
$bandera_registro = 1;
}

}

$i++;
}


if ($bandera_registro == 0) {

echo "<div class = 'alert alert-success'>Grupo creado correctamente.</div>";

}else{

echo "<div class = 'alert alert-danger'>Error inesperado, por favor vuelva a intentarlo.</div>";

}

}


if (isset($_POST['eliminar_grupo'])) {

$id_sorteo 	  = $_POST['hidden_sorteo'];
$grupo = $_POST['eliminar_grupo'];


if (mysqli_query($conn,"DELETE FROM sorteos_menores_num_extras WHERE grupo = '$grupo' AND id_sorteo = '$id_sorteo' ") === TRUE) {

echo "<div class = 'alert alert-success'>Grupo <b>".$grupo."</b> eliminado correctamente.</div>";

}else{

echo "<div class = 'alert alert-success'>Error inesperado, por favor intente nuevamente. <br>".mysqli_error($conn)."</div>";

}


}


?>