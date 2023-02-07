<?php
require '../../template/header.php';
require './deposito_billetes_db.php';
?>

<script type="text/javascript">


function espera(){

$(".div_wait").fadeIn("fast");


}

  function cargar_sorteo(id,sorteo,fecha,billetes,descripcion){
id_reg = id;
s = sorteo;
f = fecha;
b = billetes;
d = descripcion;

document.getElementById("id_oculto").value = id_reg;
document.getElementById("sorteo").value = s;
document.getElementById("fecha_sorteo").value = f;
document.getElementById("c_billetes").value = b;
document.getElementById("descripcion").value = d;

var cancel = document.getElementById("guardar");
if (cancel.style.visibility == "hidden") {
cancel.style.visibility = "visible";
}


};
</script>

<section style="background-color:#ededed;">
<br>
<h2  align="center" style="color:black; " ><b> CREACION DE MEZCLA DE LOTERIA MAYOR</b></h2>
<br>
</section>

<br>

<form method="POST">

<div align="center" style=" width:100%">

<div class="col col-md-6">
<div class="card">
  <div class="card-header bg-secondary text-white">
    <h3 style="text-align: center">SORTEO <?php echo $num_sorteo ?></h3>
  </div>
  <div class="card-body">

<input type="hidden" name="id_oculto" value="<?php echo $num_sorteo; ?>">

<div class="input-group" style="margin-bottom: 10px">
<div  class="input-group-prepend"><div style="width: 190px" class="input-group-text">Sorteo: </div></div>
<input class="form-control" style=" width:70%;" type="text" id="sorteo" value ="<?php echo $num_sorteo; ?>" name="sorteo" disabled="true">
</div>

<div class="input-group" style="margin-bottom: 10px">
<div class="input-group-prepend"><div style="width: 190px" class="input-group-text">Fecha de Sorteo: </div></div>
 <input class="form-control" style=" width:70%;" id="fecha_sorteo" value ="<?php echo $fecha; ?>" name="fecha_sorteo" type="date"  disabled="true">
</div>


<div class="input-group" style="margin-bottom: 10px">
<div class="input-group-prepend"><div style="width: 190px" class="input-group-text">Cantidad de billetes: </div></div>
<input class="form-control" style=" width:70%;" name="c_billetes" value ="<?php echo number_format($cantidad); ?>" id="c_billetes" type="text"  disabled="true">
</div>

<div class="input-group" style="margin-bottom: 10px">
<div class="input-group-prepend"><div style="width: 190px" class="input-group-text">Descripción: </div></div>
<input class="form-control" type="text"  name="descripcion" value ="<?php echo $descripcion; ?>" id="descripcion" disabled="true">
</div>

<div class="input-group">
<div class="input-group-prepend"><div style="width: 190px" class="input-group-text">Rango de Mezcla: </div></div>
<select class="form-control" id="mezcla" name="mezcla" style="width:15%">
<option>20</option>
<option>50</option>
</select>
</div>


  </div>
  <div align="center" class="card-footer">
<input type="submit" id="guardar" name="guardar" onclick="espera()" value="Guardar Mezcla" class="btn btn-primary" >
  </div>
</div>
</div>


</div>
</form>

<br>




