<?php
require '../../template/header.php';
require './gerencia_loteria_mayor_db.php';
?>


<script type="text/javascript">

function calcular_vencimiento(fecha_sorteo){
consulta = 'gerencia_loteria_calculo_vencimiento.php?fecha='+fecha_sorteo;
$("#respuesta_consulta").load(consulta);
}

function isNumberKey(evt){
var charCode = (evt.which) ? evt.which : event.keyCode
if (charCode > 31 && (charCode < 46 || charCode > 57))
return false;

return true;
}


function cargar_sorteo(id,sorteo,fecha,billetes,descripcion,precio,vencimiento, lugar){
id_reg = id;
s = sorteo;
f = fecha;
v = vencimiento;
b = billetes;
d = descripcion;
p = parseInt(precio);
l = lugar;

document.getElementById("id_oculto").value = id_reg;
document.getElementById("sorteo").value = s;
document.getElementById("datepicker_1").value = f;
document.getElementById("fecha_vencimiento").value = v;
document.getElementById("c_billetes").value = b;
document.getElementById("descripcion").value = d;
document.getElementById("precio").value = p;
document.getElementById("lugar").value = l;

var elem = document.getElementById("guardar");
if (elem.value=="Guardar") elem.value = "Guardar Cambios";

var cancel = document.getElementById("cancelar");
if (cancel.style.visibility == "hidden") {
cancel.style.visibility = "visible";
}

if (document.getElementById('eliminar').disabled == true ) {
  document.getElementById('eliminar').disabled = false;
};
};

</script>



    <?php

$result = mysqli_query($conn, "SELECT MAX(no_sorteo_may) as no_sorteo_may , fecha_sorteo, descripcion_sorteo_may, cantidad_numeros,mezcla, desde_registro, hasta_registro,salto,patron_salto FROM sorteos_mayores");

if ($result != null) {

	while ($row = mysqli_fetch_array($result)) {
		$sorteo = $row['no_sorteo_may'] + 1;
		$cantidad_numeros = $row['cantidad_numeros'];
	}

} else {

	$sorteo = 1;

}

$fecha_sorteo = '';
$descripcion = '';

?>


<br>


<ul class="nav nav-tabs">
 <li class="nav-item">
    <a style="background-color:#ededed;" class="nav-link active" href="#">Lotería Mayor</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="./gerencia_loteria_menor.php" >Lotería Menor</a>
  </li>
</ul>



<section style="background-color:#ededed;">
<br>
<h2 align="center" style="color:black;" ><b>GESTION DE SORTEOS LOTERIA MAYOR</b></h2>
<br>
</section>
<br>


<form method="POST">
<input type="hidden" name="id_oculto" id="id_oculto">
<div id="respuesta_consulta"></div>



<div class="row">

<div class="col col-md-4">

<div class="card" style="margin-left: 5px">
<div class="card-header alert-info">
<h3 class="card-title" align="center">Nuevo Sorteo</h3>
</div>
<div class="card-body">

<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span style="width: 140px" class="input-group-text">Sorteo: </span></div>
<input class="form-control" type="text" onkeypress="return isNumberKey(event)" id="sorteo" name="sorteo" value="<?php echo $sorteo; ?>" required >
</div>



<input type="text" placeholder="Fecha de Sorteo" id="datepicker_1"  onchange = "calcular_vencimiento(this.value)" value="<?php echo $fecha_sorteo; ?>" name="fecha_sorteo" class="form-control" required readonly="true">

<script>
$('#datepicker_1').datepicker({
locale: 'es-es',
format: 'yyyy-mm-dd',
uiLibrary: 'bootstrap4'
});
</script>


<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span style="width: 140px" class="input-group-text">Vencimiento: </span></div>
      <input type='text'  id="fecha_vencimiento" value="" name="fecha_vencimiento" class="form-control" required readonly="true">
</div>

<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span style="width: 140px" class="input-group-text">Lugar de Captura: </span></div>
      <input class="form-control" type ='text'  name="lugar" id="lugar">
</div>


<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span  style="width: 140px" class="input-group-text">Cantidad: </span></div>
      <input class="form-control" onkeypress="return isNumberKey(event)" name="c_billetes" id="c_billetes" type="text" value=""  required>
</div>


<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span style="width: 140px" class="input-group-text">Precio decimo: </span></div>
      <input class="form-control" onkeypress="return isNumberKey(event)" type="number" id="precio" name="precio" min="1" required>
</div>



<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span style="width: 140px" class="input-group-text">Descripcion: </span></div>
      <input class="form-control" type ='text'  name="descripcion" id="descripcion">
</div>


<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span style="width: 140px" class="input-group-text">Decimos: </span></div>
<div class="input-group-text">
      <input  type="checkbox" name="decimos" required checked="true">
</div>
</div>


</div>

<div class="card-footer" align="center" >
<input type="submit" id="guardar" name="guardar" value="Guardar" class="btn btn-primary">
<input type="submit" id="eliminar" name="eliminar" value="Eliminar" class="btn btn-danger" disabled>
<input type="submit" id="cancelar" name="cancelar" value="Cancelar" class="btn btn-default" style="visibility: hidden;" >
</div>

</div>

</div>


<div class="col col-md-8">

<div class="card" style="margin-right: 5px">
<div class="card-header alert-success">
  <h3 align="center" class="card-title"> Historico de Sorteos</h3>
</div>

<div class="card-body">

<div class="table-responsive">
<table class="table table-striped table-bordered" id="table_id1" >

        <thead>
            <tr>
                <th width="10%">Sorteo</th>
                <th width="10%">Fecha Sorteo</th>
                <th width="10%">Vencimiento</th>
                <th width="10%">Cantidad</th>
                <th width="10%">Precio</th>
            </tr>
        </thead>
        <tbody>
<?php
$result = mysqli_query($conn, "SELECT * FROM sorteos_mayores ORDER BY id DESC ");

if ($result != null) {
	while ($row = mysqli_fetch_array($result)) {

if ($row['estado_sorteo'] != 'CAPTURADO') {

		?>
<tr onclick = "cargar_sorteo('<?php echo $row['id']; ?>','<?php echo $row['no_sorteo_may']; ?>','<?php echo $row['fecha_sorteo']; ?>','<?php echo $row['cantidad_numeros']; ?>','<?php echo $row['descripcion_sorteo_may']; ?> '  ,'<?php echo $row['precio_unitario']; ?> ' ,'<?php echo $row['fecha_vencimiento']; ?> ','<?php echo $row['lugar_captura']; ?> ' )">
<?php

}else{

    ?>
<tr >
<?php

}

		echo "
   <td>" . $row['no_sorteo_may'] . "</td>
   <td>" . $row['fecha_sorteo'] . "</td>
   <td>" . $row['fecha_vencimiento'] . "</td>
   <td>" . number_format($row['cantidad_numeros']) . "</td>
   <td>" . number_format($row['precio_unitario'], 2) . "</td>
   </tr>
   ";
	}
}

?>
</tbody>
</table>

</div>
</div>
</div>

</div>

</div>




</form>