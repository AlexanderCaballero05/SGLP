<?php 
$parametros = $_GET['par'];

$v_par = explode('-', $parametros);

$id_empresa = $v_par[0];
$id_sorteo = $v_par[1];

$info_sorteo = mysqli_query($conn,"SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo' ");
$ob_sorteo = mysqli_fetch_object($info_sorteo);
$mezcla = $ob_sorteo->mezcla;

$info_empresa = mysqli_query($conn,"SELECT * FROM empresas WHERE id = '$id_empresa' ");
if ($info_empresa=== false) {
echo mysqli_error();
}
$ob_empresa = mysqli_fetch_object($info_empresa);
$nombre_empresa = $ob_empresa->nombre_empresa;

$inventario = mysqli_query($conn,"SELECT * FROM sorteos_mezclas WHERE id_empresa = '$id_empresa' AND id_sorteo = '$id_sorteo' ORDER BY num_mezcla ASC ");


if (isset($_POST['formatear_asignacion'])) {
$id_mezcla = $_POST['formatear_asignacion'];

if (mysqli_query($conn," UPDATE sorteos_mezclas SET  estado = 'PENDIENTE DISTRIBUCION', id_empresa = NULL WHERE id = '$id_mezcla' ") === TRUE) {
echo "<div class = 'alert alert-info'>Cambios realizados correctamente</div>";
}else{
echo "<div class = 'alert alert-info'>Error inesperado, por favor vuelva a intentarlo</div>";
}

}

?>