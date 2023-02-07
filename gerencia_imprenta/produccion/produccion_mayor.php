<?php
require('../../template/header.php');

$id_sorteo = $_SESSION['produccion_mayor'];
$info_mayor = mysqli_query($conn,"SELECT * FROM sorteos_mayores where id = '$id_sorteo' ");
$value_mayor = mysqli_fetch_object($info_mayor);
$cantidad_numeros = $value_mayor->cantidad_numeros;
$desde_registro = $value_mayor->desde_registro;
$patron_sugerido = $value_mayor->patron_salto;
$sorteo = $value_mayor->no_sorteo_may;
$fecha_sorteo = $value_mayor->fecha_sorteo;
$descripcion = $value_mayor->descripcion_sorteo_may;
$estado_sorteo = $value_mayor->estado_sorteo;
$estado_cc = $value_mayor->control_calidad;


if (isset($_POST['resetar_paramatros'])) {
$id_sorteo = $_POST['id_oculto'];


$update_sorteo =  mysqli_query($conn,"UPDATE sorteos_mayores SET patron_salto = null, desde_registro = null,estado_sorteo = 'PENDIENTE PRODUCCION' WHERE id = '$id_sorteo'  ");
$update_saltos =  mysqli_query($conn,"DELETE FROM sorteos_mayores_produccion  WHERE id_sorteo = '$id_sorteo'  ");

if ($update_saltos === TRUE AND $update_sorteo === TRUE) {

unset($_SESSION['alert_pendientes_produccion']);

?>
<script type="text/javascript">

swal({
title: "",
  text: "Se realizaron los cambios correctamente",
  type: "success" 
})
.then(() => {
    window.location.href = './produccion_mayor.php';
});

</script>

<?php


}else{

echo "<div class = 'alert alert-danger'>Error inesperado, por favor intente nuevamente.</div>";  

}

}



if (isset($_POST['guardar'])) {

$id_sorteo    = $_POST['id_oculto'];
$patron_salto = $_POST['patron'];
$registro_ini = $_POST['registro_inicial'];

$i = 1;
while (isset($_POST['salto'.$i])) {
$salto = $_POST['salto'.$i];
mysqli_query($conn,"INSERT INTO sorteos_mayores_produccion (id_sorteo, salto) VALUES ('$id_sorteo','$salto') ");
$i ++;
}

mysqli_query($conn,"UPDATE sorteos_mayores SET estado_sorteo = 'PENDIENTE DEPOSITO BILLETES', desde_registro = '$registro_ini' , patron_salto = '$patron_salto' WHERE id = '$id_sorteo' ");

unset($_SESSION['alert_pendientes_produccion']);

?>
<script type="text/javascript">

swal({
title: "",
  text: "Registros guardados correctamente.",
  type: "success" 
})
.then(() => {
    window.location.href = './asignacion_registros_mayor.php?id_s=<?php echo $id_sorteo; ?>';
});

</script>

<?php
}

?>

<script type="text/javascript">

function validar_terminacion(valor,i){
ultimo = valor.substr(valor.length - 1);

if (ultimo != 1) {
document.getElementById('salto'+i).value = '';
swal({ 
title: "",
text: "Los saltos deben terminar en 1",
type: "info" 
});

};
}


function validar_salto(){
patron =  document.getElementById('patron').value;    
patron = parseInt(patron);

division =  patron/1000;

if (patron%1000==0) {
document.getElementById('establecer_saltos').removeAttribute("disabled", false);
}else{
document.getElementById('establecer_saltos').setAttribute("disabled", true);

swal({ 
title: "",
text: "EL patron de saltos debe ser multiplo de 1000",
type: "error" 
});

};

}

function generar_saltos(){

id_sorteo        = document.getElementById('id_oculto').value;
registro_inicial = document.getElementById('registro_inicial').value;
patron_salto     = document.getElementById('patron').value;
token = Math.random();
consulta = 'produccion_mayor_saltos.php?s='+id_sorteo+"&r_i="+registro_inicial+"&p_s="+patron_salto+"&token="+token;     
$("#div_saltos").load(consulta);

}

</script>

<form method="POST">  


<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >PRODUCCION DE SORTEOS DE LOTERIA MAYOR</h2> 
<br>
</section>

<br>



<div class = 'row'>
    <div class = "col"  valign="top" >
      
<div class="card card-primary">
<div class="card-header">
<h3 align="center">Informacion de Sorteo</h3>
</div>
<div class="card-body">

<input type="hidden" id = 'id_oculto' name="id_oculto" value="<?php echo $id_sorteo; ?>">

<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span style="width: 140px" class="input-group-text">Sorteo: </span></div>
<input class = 'form-control' type="text" id="sorteo" name="sorteo" value="<?php echo $sorteo; ?>"  readonly>
</div>


<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span style="width: 140px" class="input-group-text">Fecha Sorteo: </span></div>
<input class = 'form-control' id="fecha_sorto" name="fecha_sorteo" type="text" value="<?php echo $fecha_sorteo; ?>" readonly> 
</div>


<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span style="width: 140px" class="input-group-text">Billetes: </span></div>
<input class = 'form-control'  id="c_billetes" type="text" value="<?php echo $cantidad_numeros; ?>"  disabled>
</div>


<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span style="width: 140px" class="input-group-text">Descripcion: </span></div>
<input type="text" class = 'form-control' value="<?php echo $descripcion;?>"  name="descripcion" id="descripcion" disabled>
</div>


</div>
</div>


    </div>
    <div class = 'col'  valign="top" >


<div class="card card-primary">
<div class="card-header">
<h3 align="center">Parametros de Produccion</h3>
</div>
<div class="card-body">

<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span style="width: 150px" class="input-group-text">Registro Inicial: </span></div>
<?php 
if ($desde_registro != '') {
echo '<input class="form-control" type="number" max = "999999" id="registro_inicial" name="registro_inicial" value="'.$desde_registro.'" readonly>';
}else{
echo '<input class="form-control" type="number" max = "999999" id="registro_inicial" name="registro_inicial" value="<?php echo $desde_registro; ?>" required>';  
}
?>
</div>


<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span style="width: 150px" class="input-group-text">Patron de Salto: </span></div>
<?php 
if ($patron_sugerido != '') {
echo '<input   class="form-control" id="patron" name="patron" onblur = "validar_salto()" type="text" value="'.$patron_sugerido.'" readonly>';
}else{
echo '<input   class="form-control" id="patron" name="patron" onblur = "validar_salto()" type="text" value="'.$patron_sugerido.'" required>';  
}
?>
</div>

</div>

<div align="center" class="card-footer">

<?php

if ($desde_registro != '' OR $patron_sugerido != '') {
  if ($estado_sorteo == 'PENDIENTE DEPOSITO BILLETES' AND $estado_cc == 'NO') {
echo '<input type="submit"  name="resetar_paramatros" class="btn btn-danger" value="Eliminar Parametros" >';

  }
}else{
echo '<span  class="btn btn-primary" onclick="generar_saltos()" id="establecer_saltos" name="establecer_saltos" >Establecer Saltos</span>';  
}

?>

  
</div>
</div>

      
    </div>
    <div class = 'col' valign="top" >


<div class="card panel-primary">
<div class="card-header">
<h3 align="center">Establecimiento de Saltos</h3>
</div>

<div class="card-body" id="div_saltos" >

<?php

if ($desde_registro != '' OR $patron_sugerido != '') {
$consulta_saltos = mysqli_query($conn,"SELECT * FROM sorteos_mayores_produccion WHERE id_sorteo = '$id_sorteo' "); 

$i = 1;
while ($reg_saltos = mysqli_fetch_array($consulta_saltos)) {

echo "<div style='width: 100%' class='input-group'>
<span style='width: 40%' class='input-group-addon'>Salto ".$i."</span>
<input class = 'form-control' onblur = 'validar_terminacion(this.value,".$i.")' name = 'salto".$i."' id = 'salto".$i."' type = 'text' value = '".$reg_saltos['salto']."' readonly>
</div>";

$i++;
}

}

?>

</div>

</div>

      
    </div>
  </div>


</form>