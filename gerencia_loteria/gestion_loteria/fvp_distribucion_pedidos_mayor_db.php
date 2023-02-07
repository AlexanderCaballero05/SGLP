<?php

$sorteos_seleccion = mysqli_query($conn,"SELECT * FROM sorteos_mayores ORDER BY id DESC ");

$empresas = mysqli_query($conn,"SELECT * FROM empresas WHERE estado = 'ACTIVO' ");

///////////////////////// Si se selecciono un sorteo ////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////////////////


if (isset($_POST['guardar_distribucion'])) {

$id_sorteo = $_POST['id_sorteo_oculto'];
$id_empresa = $_POST['id_empresa_oculto'];

$paquete_inicial = $_POST['paquete_inicial'];
$paquete_final = $_POST['paquete_final'];
$cantidad = $_POST['cantidad'];



$asignacion =  mysqli_query($conn,"UPDATE sorteos_mezclas SET id_empresa = '$id_empresa' , estado = 'DISTRIBUIDO' WHERE id_sorteo = '$id_sorteo' AND num_mezcla BETWEEN '$paquete_inicial' AND '$paquete_final' ");


if ($asignacion === FALSE) {

echo  mysqli_error();

?>
<script type="text/javascript">

swal({
  title: "",
  text: "Error inesperado, por favor intente nuevamente.",
  icon: "error",
  buttons: false,
  dangerMode: false,
})
.then(() => {
window.location.href = './screen_distribucion_pedidos_mayor.php';
});

</script>
<?php

}else{

?>
<script type="text/javascript">
  swal({ 
  title: "",
  text: "Paquetes asignados correctamente",
  type: "success" 
})
.then(() => {
window.location.href = './screen_distribucion_pedidos_mayor.php';
});

</script>
<?php


}



}



if (isset($_POST['borrar_distribucion'])) {
$id_sorteo = $_POST['id_sorteo_oculto'];

if (mysqli_query($conn,"UPDATE sorteos_mezclas SET id_empresa = NULL , estado = 'PENDIENTE DISTRIBUCION' WHERE id_sorteo = '$id_sorteo' AND cod_factura IS NULL ") === TRUE) {
echo  "<div class = 'alert alert-danger'>
Distribucion eliminada correctamente, aquellas distribuciones a las cuales ya se haya asignado factura de entrega no podran ser eliminadas.
</div>";
} else{
echo mysqli_error();	
} 


}

?>