<?php

$id_sorteo = $_GET['v1'];
$grupo = $_GET['v2'];
$id_empresa = $_GET['v3'];

$sorteos = mysqli_query($conn,"SELECT * FROM sorteos_menores   ORDER BY no_sorteo_men DESC ");

$info_sorteo = mysqli_query($conn,"SELECT *  FROM sorteos_menores WHERE id = '$id_sorteo' limit 1");
$value = mysqli_fetch_object($info_sorteo);
$sorteo = $value->no_sorteo_men;
$fecha_sorteo = $value->fecha_sorteo;
$precio_unitario = $value->precio_venta;

$info_empresa = mysqli_query($conn,"SELECT * FROM empresas WHERE id = '$id_empresa' ");
$value2 = mysqli_fetch_object($info_empresa);
$nombre_empresa = $value2->nombre_empresa;



////////////////////////////////////////
////////// CODIGO DE GUARDADO //////////

if (isset($_POST['guardar'])) {
$id_sorteo = $_POST['id_sorteo'];
$id_empresa = $_POST['id_empresa'];
$bandera = 0;

$i = 0;
while (isset($_POST['numero'][$i])) {
if ($_POST['numero'][$i] != '' AND $_POST['cantidad'][$i] != '' AND $_POST['cantidad'][$i] != 0) {
$numero = $_POST['numero'][$i];  
$s_i = $_POST['serie_inicial'][$i];  
$s_f = $_POST['serie_final'][$i];  
$cantidad = $_POST['cantidad'][$i];  

if ( mysqli_query($conn," INSERT INTO menor_seccionales_numeros (id_sorteo,numero,serie_inicial,serie_final,cantidad,id_empresa,origen) VALUES ('$id_sorteo','$numero','$s_i','$s_f','$cantidad','$id_empresa','numeros') ") === false) {
 echo mysqli_error();
$bandera = 1;
 }

}
$i++;
}

if ($bandera == 0) {
echo "<div class = 'alert alert-info'>Distribucion realizada correctamente</div>";	
}else{
echo "<div class = 'alert alert-info'>Error inesperado, por favor vuelva a intentarlo</div>";
}

}

////////// FIN DE GUARDADO //////////
/////////////////////////////////////




if (isset($_POST['eliminar_distribucion'])) {
$id = $_POST['eliminar_distribucion'];

if (mysqli_query($conn,"DELETE FROM menor_seccionales_numeros WHERE id = '$id' ") === true) {
echo "<div class = 'alert alert-info'> Cambios reaziados correctamente</div>";
}else{
echo "<div class = 'alert alert-danger'> Error inesperado, por favor vuelva a intentarlo</div>";

}

}

?>