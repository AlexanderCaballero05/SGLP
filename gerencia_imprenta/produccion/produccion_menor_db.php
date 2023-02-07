<?php

$id_sorteo = $_SESSION['produccion_menor'];

$result = mysqli_query($conn,"SELECT * FROM sorteos_menores WHERE id = '$id_sorteo'");

$max_extra  = mysqli_query($conn,"SELECT MAX(cantidad) as maximo FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo'");
if (mysqli_num_rows($max_extra) == 0) {
$cantidad_extra_mayor =  0;	
}else{
$ob_extra = mysqli_fetch_object($max_extra); 
$cantidad_extra_mayor =  $ob_extra->maximo;	
}

if ($result != null){

while ($row = mysqli_fetch_array($result)) {
$sorteo = $row['no_sorteo_men'] ;
$fecha_sorteo = $row['fecha_sorteo'] ;
$series = $row['series'];
$descripcion = $row['descripcion_sorteo_men'];
$desde_registro = $row['desde_registro'];
$hasta_reg = $row['hasta_registro'];
}

$masc = strlen($series);

}

$busqueda_saltos = mysqli_query($conn,"SELECT * FROM sorteos_menores_produccion WHERE id_sorteo = '$id_sorteo' ");
if ($busqueda_saltos=== false) {
echo mysqli_error($conn);
}










//sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss

if (isset($_POST['guardar_produccion'])) {
  
$id_oculto = $_POST['id_oculto'];
$registro_inicial = $_POST['registro_inicial'];

if (mysqli_query($conn,"UPDATE sorteos_menores SET desde_registro = '$registro_inicial', estado_sorteo = 'PENDIENTE DISTRIBUCION' WHERE id = '$id_oculto' ") === TRUE) {
?>
<script type="text/javascript">
  swal("Se realizaron los cambios correctamente", "", "success");
</script>
<?php

}else{
echo mysqli_error($conn);
?>
<script type="text/javascript">
  swal("Error inesperado, por favor vuelva a intentarlo", "", "error");
</script>
<?php
}


$i = 1;

$busqueda_saltos = mysqli_query($conn,"SELECT * FROM sorteos_menores_produccion WHERE id_sorteo = '$id_sorteo' ");
if ($busqueda_saltos=== false) {
echo mysqli_error($conn);
}

$conteo_saltos = mysqli_num_rows($busqueda_saltos);
if ($conteo_saltos > 0) {

}else{

while (isset($_POST['salto'.$i])) {
$salto = $_POST['salto'.$i];	
if ( mysqli_query($conn,"INSERT INTO sorteos_menores_produccion (id_sorteo,salto,decena) VALUES ('$id_oculto','$salto','$i')") === false) {
echo mysqli_error($conn);
}

$i++;
}

}


$i = 0;
while (isset($_POST["id".$i])) {
$id = $_POST["id".$i];

$serie =  $_POST["serie_inicial".$i];
$registro =  $_POST["registro_inicial".$i];
$i ++;



mysqli_query($conn,"UPDATE sorteos_menores_num_extras SET serie_inicial = '$serie',
 registro_inicial = $registro, estado_sorteo = 'PENDIENTE DISTRIBUCION' WHERE id = '$id' ");

}

?>
<script type="text/javascript">

swal({
title: "",
  text: "Registros guardados correctamente.",
  type: "success" 
})
.then(() => {
    window.location.href = './asignacion_registros_menor.php?id_s=<?php echo $id_sorteo; ?>';
});

</script>
<?php


}







//////////////////////////////////


if (isset($_POST['eliminar_produccion'])) {

$id_oculto = $_POST['id_oculto'];

mysqli_query($conn,"UPDATE sorteos_menores SET desde_registro = '', hasta_registro = '', estado_sorteo = 'PENDIENTE PRODUCCION' WHERE id = '$id_oculto' ");

mysqli_query($conn,"DELETE FROM sorteos_menores_produccion WHERE id_sorteo = '$id_oculto' ");

mysqli_query($conn,"DELETE FROM sorteos_menores_registros WHERE id_sorteo = '$id_oculto' ");


?>
<script type="text/javascript">

swal({
title: "",
  text: "Sorteo formateado correctamente.",
  type: "success" 
})
.then(() => {
    window.location.href = './asignacion_registros_menor.php?id_s=<?php echo $id_sorteo; ?>';
});

</script>
<?php


}

?>
