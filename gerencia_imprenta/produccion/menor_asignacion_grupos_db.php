<?php


if (isset($_POST['eliminar_extra'])) {
$id = $_POST['eliminar_extra'];

if (mysqli_query($conn,"DELETE FROM sorteos_menores_num_extras WHERE id = '$id' ") === TRUE ) {
echo "<div class = 'alert alert-danger' >Numero eliminado correctamente</div>";
}

}




if (isset($_POST['multiple_eliminado'])) {

$i = 0;
$conteo_asignado   = $_POST['conteo_asignado'];
$conteo_eliminado  = 0;
$bandera_eliminado = 0; 
while ($i <= $conteo_asignado) {

if (isset($_POST['seleccion'.$i])) {
$id_delete = $_POST['seleccion'.$i];

if (mysqli_query($conn,"DELETE  FROM sorteos_menores_num_extras WHERE id = '$id_delete' ") === TRUE) {
$conteo_eliminado++;

}else{
$bandera_eliminado = 1;  
}

}

$i++;
}


if ($bandera_eliminado == 0) {

echo "<div class = 'alert alert-info' >Numero eliminado correctamente</div>";

}else{

echo "<div class = 'alert alert-danger' >Error inesperado, por favor intente nuevamente.</div>";

}

}



if (isset($_POST['guardar'])) {

$id_sorteo = $_POST['hidden_sorteo'];
$max = 1;

$i = 0;
while (isset($_POST['numero_i'][$i])) {
$numero_i = $_POST['numero_i'][$i];
$numero_f = $_POST['numero_f'][$i];
$cantidad = $_POST['cantidad'][$i];
$grupo = $_POST['grupo'][$i];

if ($numero_i != '' && $numero_f != '' && $cantidad != '') {
while ($numero_i <= $numero_f) {

if (mysqli_query($conn,"INSERT into sorteos_menores_num_extras (id_sorteo,numero,cantidad,estado_sorteo,num_solicitud,grupo) VALUES ('$id_sorteo','$numero_i','$cantidad','PENDIENTE PRODUCCION','$max','$grupo') ") === TRUE) {
}else{
echo mysqli_error($conn);
};

$numero_i++;
}
}

$i++;
}

mysqli_query($conn,"UPDATE sorteos_menores SET estado_sorteo = 'PENDIENTE PRODUCCION' WHERE id = '$id_sorteo' ");

echo "<div class = 'alert alert-info' >Agrupacion importada correctamente</div>";

}

?>