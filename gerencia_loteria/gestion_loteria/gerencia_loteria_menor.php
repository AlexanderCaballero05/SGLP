<?php
require '../../template/header.php';
require './gerencia_loteria_menor_db.php';

if (isset($_POST['produccion_extra'])) {
	$_SESSION['id_sorteo_menor_extra'] = $_POST['produccion_extra'];

	?>
<script type="text/javascript">
window.location = "./gerencia_loteria_menor_asignacion_extra.php";
</script>

<?php

}

$result = mysqli_query($conn, "SELECT MAX(no_sorteo_men) as no_sorteo_men , fecha_sorteo, cantidad_numeros,series, desde_registro, hasta_registro,estado_sorteo FROM sorteos_menores");

if ($result != null) {
	while ($row = mysqli_fetch_array($result)) {
		$sorteo = $row['no_sorteo_men'] + 1;
		$cantidad_numeros = $row['cantidad_numeros'];
	}
}

if (!isset($cantidad_numeros)) {
	$sorteo = 1;
}

$cantidad_series = 6500;
$desde_registro = 1000000;
$fecha_sorteo = '';
$descripcion = '';

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




<br>

<form method="POST">

<ul class="nav nav-tabs">
 <li class="nav-item">
    <a  class="nav-link" href="./screen_gerencia_loteria_mayor.php">Lotería Mayor</a>
  </li>
  <li class="nav-item">
    <a style="background-color:#ededed;" class="nav-link"  >Lotería Menor</a>
  </li>
</ul>

<section style="color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  ><b>GESTION DE SORTEOS LOTERIA MENOR</b></h2>
<br>
</section>
<br>


<form method="POST">
<div id="respuesta_consulta"></div>


<input type="hidden" name="id_oculto" id="id_oculto">


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



<input type="text" id="datepicker_1" placeholder="Fecha de Sorteo" onchange = "calcular_vencimiento(this.value)" value="<?php echo $fecha_sorteo; ?>" name="fecha_sorteo" class="form-control" required readonly="true">

<script>
$('#datepicker_1').datepicker({
locale: 'es-es',
format: 'yyyy-mm-dd',
uiLibrary: 'bootstrap4'
});
</script>



<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span style="width: 140px" class="input-group-text">Vencimiento: </span></div>
      <input type='text'  id="fecha_vencimiento" value="" name="fecha_vencimiento" class="form-control" readonly="true">
</div>

<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span style="width: 140px" class="input-group-text">Lugar de Captura: </span></div>
      <input class="form-control" type ='text'  name="lugar" id="lugar">
</div>


<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span  style="width: 140px" class="input-group-text">Cantidad: </span></div>
      <input class="form-control" onkeypress="return isNumberKey(event)" name="series" id="c_billetes" type="text" value=""  required>
</div>


<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span style="width: 140px" class="input-group-text">Precio: </span></div>
      <input class="form-control" onkeypress="return isNumberKey(event)" type="number" id="precio" name="precio" min="1" required>
</div>



<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span style="width: 140px" class="input-group-text">Descripcion: </span></div>
      <input class="form-control" type ='text'  name="descripcion" id="descripcion">
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
                <th width="10%">Accion</th>
            </tr>
        </thead>
        <tbody>
<?php
$result = mysqli_query($conn, "SELECT * FROM sorteos_menores ORDER BY id DESC ");

if ($result != null) {
	while ($row = mysqli_fetch_array($result)) {

if ($row['estado_sorteo'] != 'CAPTURADO') {

		?>
<tr onclick = "cargar_sorteo('<?php echo $row['id']; ?>','<?php echo $row['no_sorteo_men']; ?>','<?php echo $row['fecha_sorteo']; ?>','<?php echo $row['series']; ?>','<?php echo $row['descripcion_sorteo_men']; ?> '  ,'<?php echo $row['precio_unitario']; ?> ' ,'<?php echo $row['vencimiento_sorteo']; ?> ','<?php echo $row['lugar_captura']; ?> ' )">
<?php

}else{

    ?>
<tr >
<?php

}



		echo "
   <td>" . $row['no_sorteo_men'] . "</td>
   <td>" . $row['fecha_sorteo'] . "</td>
   <td>" . $row['vencimiento_sorteo'] . "</td>
   <td>" . number_format($row['series']) . "</td>
   <td>" . number_format($row['precio_unitario'], 2) . "</td>
   <td align = 'center'><button type = 'submit' name = 'produccion_extra' value = '" . $row['id'] . "' class = 'btn btn-primary' >+ Numeros</button></td>
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