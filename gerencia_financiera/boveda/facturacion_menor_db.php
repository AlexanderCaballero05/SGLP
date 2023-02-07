<?php
$sorteos = mysqli_query($conn,"SELECT * FROM sorteos_menores  ORDER BY no_sorteo_men DESC ");


if (isset($_POST['guardar_factura'])) {

$sorteo = $_POST['id_sorteo'];
$receptor = $_POST['receptor'];
$id_empresa = $_POST['id_empresa'];
$fecha_expedicion = $_POST['fecha_expedicion'];
$no_factura = $_POST['no_factura'];
$fecha_sorteo = $_POST['fecha_sorteo'];
$cantidad_asignada = $_POST['cantidad_asignada'];
$valor_nominal = $_POST['valor_nominal'];
$descuento = $_POST['descuento'];
$rebaja = $_POST['rebaja'];
$valor_neto = $_POST['valor_neto'];


$nombre_empresa = mysqli_query($conn,"SELECT * FROM empresas WHERE id = '$receptor' LIMIT 1 ");
$ob_empresa = mysqli_fetch_object($nombre_empresa);
$nombre = $ob_empresa->nombre_empresa;

if ( mysqli_query($conn,"INSERT INTO facturacion_menor (`id_empresa`,`receptor`, `fecha_expedicion`, `no_factura`, `id_sorteo`, `fecha_sorteo`, `cantidad`, `valor_nominal`, `descuento`, `rebaja_depositario`, `valor_neto`) VALUES ('$id_empresa','$nombre','$fecha_expedicion', '$no_factura','$sorteo','$fecha_sorteo','$cantidad_asignada','$valor_nominal','$descuento','$rebaja','$valor_neto' ) ") === TRUE ) {

$consulta_id = mysqli_query($conn,"SELECT * FROM facturacion_menor WHERE no_factura = '$no_factura' ");
$ob_factura = mysqli_fetch_object($consulta_id);
$id = $ob_factura->id;

$_SESSION['factura_menor'] = $id;


mysqli_query($conn,"UPDATE menor_seccionales_bolsas SET cod_factura = '$no_factura' WHERE id_sorteo = '$sorteo' AND id_empresa = '$id_empresa' AND cod_factura IS NULL  ");

mysqli_query($conn,"UPDATE menor_seccionales_numeros SET cod_factura = '$no_factura'  WHERE id_sorteo = '$sorteo' AND  id_empresa = '$id_empresa' AND cod_factura IS NULL ");


?>
<script type="text/javascript">
swal({ 
title: "",
text: "Registro guardado correctamente",
type: "success" 
})
.then(() => {
window.open("./print_acta_entrega_menor.php");
});
</script>


<?php

}else{
echo 	mysqli_error();
}

}



if (isset($_POST['eliminar_factura'])) {
$id_factura = $_POST['eliminar_factura'];

if (mysqli_query($conn,"DELETE FROM facturacion_menor WHERE id = $id_factura ")) {

?>
<script type="text/javascript">
  swal({ 
  title: "",
   text: "Registro eliminado correctamente",
    type: "success" 
  },
  function(){
    window.location.href = './facturacion_menor.php';
});
</script>
<?php

}else{
echo mysqli_error();
}

}







if (isset($_POST['aceptar_anulacion'])) {

$cod_factura = $_POST['id_factura_oculto'];
$user_autorizado = $_POST['username'];
$pass_autorizado = md5($_POST['password']);

$consulta_usuario = mysqli_query($conn,"SELECT * FROM pani_usuarios WHERE areas_id = 10 AND roles_usuarios_id = 1 AND estados_id = 1 AND usuario = '$user_autorizado' AND password = '$pass_autorizado' ");


if (mysqli_num_rows($consulta_usuario) > 0) {

$update_inventario = mysqli_query($conn,"UPDATE menor_seccionales_bolsas SET cod_factura = NULL  WHERE  cod_factura = '$cod_factura' ");

if ($update_inventario === FALSE) {
echo mysqli_error();
}else{


$update_inventario = mysqli_query($conn,"UPDATE menor_seccionales_numeros SET cod_factura = NULL  WHERE  cod_factura = '$cod_factura' ");

if ($update_inventario === FALSE) {
echo mysqli_error();
}else{

$update_inventario = mysqli_query($conn,"UPDATE facturacion_menor SET estado = 'C'  WHERE no_factura = '$cod_factura' ");

if ($update_inventario === FALSE) {
echo mysqli_error();
}else{

echo "<div class = 'alert alert-info'>Factura ".$cod_factura." anulada correctamente.</div>";

}

}

}

}else{

echo "<div class = 'alert alert-danger'>Usuario o contraseña de autorizacion incorrectos.</div>";

}

}

?>