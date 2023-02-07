<?php
ob_start();
session_start();  

require("../../conexion.php");

if ($_GET['id'] != '') {

$identidad  = $_GET['id'];
$tipo_id 	= $_GET['tipo_id'];

if ($tipo_id == 1) {
$v_id = explode("-", $identidad);

if (isset($v_id[0]) AND isset($v_id[1]) AND isset($v_id[2]) ) {

$identidad = $v_id[0].$v_id[1].$v_id[2];

}

}

$consulta_identidad =  mysqli_query($conn,"SELECT * FROM vendedores WHERE identidad = '$identidad' ");


if (mysqli_num_rows($consulta_identidad) > 0) {

?>

<script type="text/javascript">
document.getElementById("nuevo_id").value = '';
document.getElementById("guardar_nuevo").disabled = true;
swal("ERROR", "El numero de identidad que intenta ingresar ya existe.", "error");
</script>

<?php

}else{

$c_censo = mysqli_query($conn,"SELECT * FROM censo_2017 WHERE identidad = '$identidad'  ");



if (mysqli_num_rows($c_censo) > 0) {

$ob_censo = mysqli_fetch_object($c_censo);
$nombre_censo = $ob_censo->nombre_completo;

?>

<script type="text/javascript">
document.getElementById("nuevo_nombre").value = '<?php echo $nombre_censo; ?>';
</script>

<?php

}else{

?>

<script type="text/javascript">
document.getElementById("nuevo_nombre").value = '';
swal("", "El numero de identidad no existe, por favor verifique que este sea el correcto antes de registrar al nuevo vendedor.", "warning");
</script>

<?php

}

}

}

?>