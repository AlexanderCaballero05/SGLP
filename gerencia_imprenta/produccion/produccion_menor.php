<?php
require('../../template/header.php');
require('./produccion_menor_db.php');
?>

<script type="text/javascript">

function calcular_serie_final(cantidad,numero,inicial){
c = parseInt(cantidad);
n = parseInt(numero);
i = parseInt(inicial);

document.getElementById('serie_inicial'+numero).value =  i + c - 1;

}

function calcular_registro_final(cantidad,numero,inicial){
c = parseInt(cantidad);
n = parseInt(numero);
i = parseInt(inicial);

document.getElementById('registro_inicial'+numero).value =  i + c - 1;

}

function asignar_registros(){

id_sorteo        = document.getElementById('id_oculto').value;
series           = document.getElementById('series').value;

registro_inicial = parseInt(document.getElementById('registro_inicial').value);
salto1           = parseInt(document.getElementById('salto1').value);
salto2           = parseInt(document.getElementById('salto2').value);
salto3           = parseInt(document.getElementById('salto3').value);
salto4           = parseInt(document.getElementById('salto4').value);
salto5           = parseInt(document.getElementById('salto5').value);
salto6           = parseInt(document.getElementById('salto6').value);
salto7           = parseInt(document.getElementById('salto7').value);
salto8           = parseInt(document.getElementById('salto8').value);
salto9           = parseInt(document.getElementById('salto9').value);

bandera = 0;
for ( i =  1; i < 10; i++) {
if ( isNaN(parseInt(document.getElementById('salto'+i).value))) {
bandera = 1;
i = 11;
}
}

if (isNaN(registro_inicial)) {

swal({ 
title: "",
text: "Debe ingresar el registro inicial",
type: "error" 
});

}else{

if (bandera == 1) {
swal({ 
title: "",
text: "Tiene saltos pendientes de ingresar",
type: "error" 
});
}else{

token = Math.random();
consulta = 'produccion_menor_saltos.php?s='+id_sorteo+"&ser="+series+"&r_i="+registro_inicial+"&s1="+salto1+"&s2="+salto2+"&s3="+salto3+"&s4="+salto4+"&s5="+salto5+"&s6="+salto6+"&s7="+salto7+"&s8="+salto8+"&s9="+salto9+"&token="+token;     
$("#previsualizacion_registros").load(consulta);

}

}

}

</script>



<form method="POST">  


<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >PRODUCCION DE SORTEOS DE LOTERIA MENOR</h2> 
<br>
</section>

<br>



  <div class = "row" >
    <div class = "col" >
      
<div class="card " style="margin-left: 10px">
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
<input class = 'form-control'  id="series" type="text" value="<?php echo $series; ?>"  disabled>
</div>

<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span style="width: 140px" class="input-group-text">Descripcion: </span></div>
<input type="text" class = 'form-control' value="<?php echo $descripcion;?>"  name="descripcion" id="descripcion" disabled>
</div>

</div>
</div>

</div>



<div class="col">

<div class="card">
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

</div>
</div>
 
</div>


<div class = 'col' style="margin-right: 10px" >

<div class="card">
<div class="card-header">
<h3 align="center">Establecimiento de Saltos</h3>
</div>
<div class="card-body" id="div_saltos" >

<?php

if ($desde_registro != '') {
$consulta_saltos = mysqli_query($conn,"SELECT * FROM sorteos_menores_produccion WHERE id_sorteo = '$id_sorteo' "); 

$i = 1;
while ($reg_saltos = mysqli_fetch_array($consulta_saltos)) {
echo "<div  class='input-group'>
<div  class='input-group-prepend'><span class = 'input-group-text'> Salto ".$i."</span></div>
<input class = 'form-control' onblur = 'validar_terminacion(this.value,".$i.")' name = 'salto".$i."' id = 'salto".$i."' type = 'text' value = '".$reg_saltos['salto']."' readonly>
</div>";

$i++;
}

}else{
$i = 1;

while ($i < 10) {

echo "<div class='input-group'>
<div class='input-group-prepend'><span class = 'input-group-text'> Salto ".$i."</span></div>
<input class = 'form-control' name = 'salto".$i."' id = 'salto".$i."' type = 'text'  required>
</div>";

$i++;
}

}

?>

</div>

<div class="card-footer" align="center">
  <span onclick="asignar_registros()" class="btn btn-primary">Visualizar Produccion</span>
</div>

</div>

      
</div>
</div >

<br>

<div id="previsualizacion_registros"   >
</div>

</form>