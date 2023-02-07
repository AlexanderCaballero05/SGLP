<?php 
$sorteos = mysqli_query($conn,"SELECT * FROM sorteos_menores WHERE estado_sorteo = 'PENDIENTE DISTRIBUCION' ORDER BY no_sorteo_men DESC ");

$empresas = mysqli_query($conn,"SELECT * FROM empresas WHERE estado = 'ACTIVO' ");



///////////////////////////////////////////////////
///// CODIGO DE ASIGNACION COMPLETA DE GRUPO //////
if (isset($_POST['asignar_grupo'])) {
$v_parametros = explode("-", $_POST['asignar_grupo']);
$id_sorteo = $v_parametros[0];
$id_grupo = $v_parametros[1];
$id_empresa = $_POST['id_empresa_oculto'];

$numeros_extras = mysqli_query($conn,"SELECT * FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo' AND grupo = '$id_grupo' ");

while ($detalle_extra = mysqli_fetch_array($numeros_extras)) {
$numero = $detalle_extra['numero'];
$s_i = $detalle_extra['serie_inicial'];
$cantidad = $detalle_extra['cantidad'];
$s_f = $s_i + $cantidad - 1;

if ( mysqli_query($conn," INSERT INTO menor_seccionales_numeros (id_sorteo,numero,serie_inicial,serie_final,cantidad,id_empresa,origen) VALUES ('$id_sorteo','$numero','$s_i','$s_f','$cantidad','$id_empresa','numeros') ") === false) {
 echo mysqli_error();
 }


}

echo "<div class = 'alert alert-info'>Distribucion realizada correctamente</div>";
}
/////////// FIN DE GUARDADO DE GRUPO //////////////
///////////////////////////////////////////////////


if (isset($_POST['eliminar_distribucion'])) {
$id = $_POST['eliminar_distribucion'];
if (mysqli_query($conn,"DELETE FROM menor_seccionales_numeros WHERE id = '$id' ") === TRUE) {

?>
<script type="text/javascript">
  swal({ 
  title: "",
   text: "Registro Eliminado Correctamente",
    type: "success" 
})
.then(() => {
window.location.href = './fvp_distribucion_pedidos_menor_numeros_grupos.php';
});
</script>
<?php


}
}

?>