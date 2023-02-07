<?php
require('../../template/header.php');


if (isset($_POST['guardar'])) {

$identidad = $_POST['identidad'];
$nombre    = $_POST['nombre'];
$id_usuario = $_SESSION['id_usuario'];

$v_identidad = explode("-", $identidad);
$identidad   = $v_identidad[0].$v_identidad[1].$v_identidad[2]; 

$c_censo = mysqli_query($conn, "SELECT identidad FROM censo_2017 WHERE identidad = '$identidad' ");

if (mysqli_num_rows($c_censo) > 0) {

?>
<script type="text/javascript">
    swal('','La persona que intenta ingresar ya existe.','error');
</script>
<?php

}else{

$registro_persona = mysqli_query($conn,"INSERT INTO censo_2017 (identidad, nombre_completo, id_usuario_registro) VALUES ('$identidad','$nombre','$id_usuario') ");

if ($registro_persona === TRUE) {

?>
<script type="text/javascript">
    swal('','Persona registrada correctamente.','success');
</script>
<?php


}else{

?>
<script type="text/javascript">
    swal('','Error inesperado, por favor intente nuevamente.','success');
</script>
<?php
    
}

}

}

?>

<script type="text/javascript">

function validar_registro(){


id       = document.getElementById('identidad').value;
count_id = id.length;
nombre   = document.getElementById('nombre').value;

if (id != '' ) {

if (count_id == 15) {

if (nombre != '') {

document.getElementById('guardar').click();

}else{

swal('','Debe ingresar el nombre de la persona','error');

}

}else{

swal('','El numero de identidad debe tener 13 caracteres','error');

}

}else{

swal('','Debe ingresar un numero de identidad valido','error');

}

}

jQuery(function($){
$("#identidad").mask("9999-9999-99999", { placeholder: "____-____-_____" });
});


</script>

<form method="POST">

<section style="color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >GESTION DE SECCIONALES - ASOCIACIONES</h2> 
<br>
</section>
<br>

<div class="alert alert-danger">
<i class="fa fa-exclamation-triangle"></i>
 Precaucion: Por favor realice el registro de nuevas personas en base a la tarjeta de identidad de la misma y verifique que la información ingresada es la correcta antes de hacer el guardado.
</div>


<div style="width: 100%" align="center">

<div class="card" style="width: 50%">
<div class="card-header bg-primary text-white">
<h4 align="center">REGISTRO DE NUEVAS PERSONAS </h4>
</div>
<div class="card-body">

<div class="input-group">
<div class="input-group-prepend">
<div style="width: 100px" class="input-group-text">Identidad</div>
</div>
<input type="text" class="form-control" name="identidad" id="identidad" >
</div>

<div class="input-group" style="margin-top: 10px">
<div class="input-group-prepend">
<div style="width: 100px" class="input-group-text">Nombre</div>
</div>
<input type="text" class="form-control" name="nombre" id="nombre" >
</div>


</div>
<div class="card-footer">
<button type="submit" style="visibility: hidden" name="guardar" id="guardar" ></button>
<span class="btn btn-primary" onclick="validar_registro()">Guardar</span>
</div>
</div>

</div>


</form>
