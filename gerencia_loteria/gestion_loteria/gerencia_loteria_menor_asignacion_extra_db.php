<?php

$id_sorteo = $_SESSION['id_sorteo_menor_extra'];

$result = mysqli_query($conn,"SELECT * FROM sorteos_menores WHERE id = '$id_sorteo'");
 
if ($result != null){

while ($row = mysqli_fetch_array($result)) {
$sorteo = $row['no_sorteo_men'] ;
$fecha_sorteo = $row['fecha_sorteo'] ;
$series = $row['series'];
$descripcion = $row['descripcion_sorteo_men'];
}

}



$result2 = mysqli_query($conn,"SELECT * FROM sorteos_menores_solicitudes_extras WHERE id_sorteo = '$id_sorteo' ORDER BY numero ASC ");
$conteo_extras_asignados = mysqli_num_rows($result2);

$id_sorteo_anterior = $id_sorteo - 1;
$num_extras_anteriores = mysqli_query($conn,"SELECT * FROM sorteos_menores_solicitudes_extras WHERE id_sorteo = '$id_sorteo_anterior' ORDER BY numero ASC ");


$cantidad_extra_asignada  = 0;
$result3    = mysqli_query($conn,"SELECT SUM(cantidad) as ya_asignado FROM sorteos_menores_solicitudes_extras WHERE id_sorteo = '$id_sorteo' ");
$ob_result3 = mysqli_fetch_object($result3);
$cantidad_extra_asignada = $ob_result3->ya_asignado;




if (isset($_POST['eliminar_extra'])) {
$id = $_POST['eliminar_extra'];

if (mysqli_query($conn,"DELETE FROM sorteos_menores_solicitudes_extras WHERE id = '$id' ")) {
?>

<script type="text/javascript">
swal({
title: "",
  text: "Se realizaron los cambios correctamente",
  type: "success" 
})
.then(() => {
    window.location.href = './gerencia_loteria_menor_asignacion_extra.php';
});
</script>

<?php
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

if (mysqli_query($conn,"DELETE  FROM sorteos_menores_solicitudes_extras WHERE id = '$id_delete' ") === TRUE) {
$conteo_eliminado++;
}else{
$bandera_eliminado = 1;  
}

}

$i++;
}


if ($bandera_eliminado == 0) {

?>

<script type="text/javascript">
swal({
title: "",
  text: "Se realizaron los cambios correctamente",
  type: "success" 
})
.then(() => {
    window.location.href = './gerencia_loteria_menor_asignacion_extra.php';
});
</script>

<?php

}else{


?>

<script type="text/javascript">
swal({
title: "",
  text: "Se realizaron los cambios correctamente",
  type: "success" 
})
.then(() => {
    window.location.href = './gerencia_loteria_menor_asignacion_extra.php';
});
</script>

<?php


}

}



if (isset($_POST['guardar'])) {
$id_sorteo = $_SESSION['id_sorteo_menor_extra'];

$i = 0;
while (isset($_POST['numero_i'][$i])) {
$numero_i = $_POST['numero_i'][$i];
$numero_f = $_POST['numero_f'][$i];
$cantidad = $_POST['cantidad'][$i];

if ($numero_i != '' && $numero_f != '' && $cantidad != '') {
while ($numero_i <= $numero_f) {

if (mysqli_query($conn,"INSERT INTO sorteos_menores_solicitudes_extras (id_sorteo,numero,cantidad,estado)
VALUES ('$id_sorteo','$numero_i','$cantidad','1') ") === TRUE) {
}else{
echo mysqli_error($conn);
};

$numero_i++;
}
}

$i++;
}


mysqli_query($conn,"UPDATE sorteos_menores SET estado_sorteo = 'PENDIENTE PRODUCCION' WHERE id = '$id_sorteo' ");


?>

<script type="text/javascript">

swal({
title: "",
  text: "Se realizaron los cambios correctamente",
  type: "success" 
})
.then(() => {
    window.location.href = './gerencia_loteria_menor_asignacion_extra.php';
});

</script>

<?php

}


?>