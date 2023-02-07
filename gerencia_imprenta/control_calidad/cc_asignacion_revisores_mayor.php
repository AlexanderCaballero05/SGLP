<?php

require('../../template/header.php');
require('./cc_asignacion_revisores_mayor_db.php');


$revisores = mysqli_query($conn, "SELECT * FROM pani_usuarios WHERE estados_id = '1' AND roles_usuarios_id = '2' AND areas_id = '5' ORDER BY nombre_completo ASC ");
$revisores_select = mysqli_query($conn, "SELECT * FROM pani_usuarios WHERE estados_id = '1' AND areas_id = '5' ORDER BY nombre_completo ASC ");

$conteo_revisores = mysqli_num_rows($revisores_select);

if ($revisores===false) {
echo mysqli_error($conn);
}

?>

<script type="text/javascript">


function calculo_manual(fila){

ultimo_billete = document.getElementById('cantidad_o').value;
ultimo_billete = parseInt(ultimo_billete) - 1;

conteo_filas = $('#detalle_revisor tr').length;

for (var i = 0; i < conteo_filas; i++) {

cantidad = document.getElementById('cantidad'+i).value;

if (cantidad > 0) {

if (i == 0) {

hasta = parseInt(cantidad) - 1;

document.getElementById('desde'+i).value = 0;
document.getElementById('hasta'+i).value = hasta;

if (hasta == ultimo_billete) {
document.getElementById('guardar').disabled = false;  
}else if( hasta > ultimo_billete){


document.getElementById('desde0').value = "";
document.getElementById('hasta0').value = "";
document.getElementById('cantidad0').value = "";
document.getElementById('guardar').disabled = true;

swal('','Ha excedido la cantidad maxima disponible para asignacion','error');

}



}else{

previus = i - 1;
hasta_previus = document.getElementById('hasta'+previus).value;
desde = parseInt(hasta_previus) + 1;

hasta = parseInt(desde) + parseInt(cantidad) - 1;

document.getElementById('desde'+i).value = desde;
document.getElementById('hasta'+i).value = hasta;

if (hasta == ultimo_billete) {
document.getElementById('guardar').disabled = false;  
}else if( hasta > ultimo_billete){


document.getElementById('desde'+i).value = "";
document.getElementById('hasta'+i).value = "";
document.getElementById('cantidad'+i).value = "";
document.getElementById('guardar').disabled = true;

swal('','Ha excedido la cantidad maxima disponible para asignacion','error');

}


}

}else{

i = conteo_filas;

document.getElementById('guardar').disabled = true;

}

}

}



function remove_rows(){
$("#detalle_revisor tr").remove();   
document.getElementById('guardar').disabled = true;
}


function seleccion_revision(revisor){


tipo_asignacion = document.getElementById('tipo_asignacion').value;



if (tipo_asignacion === "SI") {


var count_revisores   = $('#select_revisor option:selected').length;
var cantidad_billetes = $('#cantidad_o').val();
var billete_maximo    = parseInt(cantidad_billetes) - 1;
tabla                 = document.getElementById("detalle_revisor");  


if (Number.isInteger(cantidad_billetes / count_revisores)) {
cantidad_asignar = cantidad_billetes/count_revisores;
}else{
cantidad_asignar = parseInt(cantidad_billetes/count_revisores) + 1 ;  
}


$("#detalle_revisor tr").remove(); 



billete_inicial = 0;
filas           = 0;

mySelect = document.getElementById('select_revisor');
for (var i = 0; i < mySelect.options.length; i++) {
   if (mySelect.options[i].selected){

    nombre_revisor = mySelect.options[i].text;
    id_revisor = mySelect.options[i].value;


billete_final   = parseInt(billete_inicial) + parseInt(cantidad_asignar) - 1;

if (billete_final > billete_maximo) {
billete_final = billete_maximo;
}

cantidad_entre_series = parseInt(billete_final) - parseInt(billete_inicial) + 1; 

var row = tabla.insertRow(filas);
var cell1 = row.insertCell(0);
var cell2 = row.insertCell(1);
var cell3 = row.insertCell(2);
var cell4 = row.insertCell(3);

cell1.style.width = '55%';
cell2.style.width = '15%';
cell3.style.width = '15%';
cell4.style.width = '15%';

cell1.innerHTML = "<input  type = 'hidden' class = 'form-control' name = 'id_o[]' value = '"+id_revisor+"' >"+nombre_revisor;
cell2.innerHTML = "<input  type = 'text' class = 'form-control' name = 'desde[]' id = 'desde"+filas+"' value = '"+billete_inicial+"' required readonly>";
cell3.innerHTML = "<input  type = 'text' class = 'form-control' name = 'hasta[]' id = 'hasta"+filas+"' value = '"+billete_final+"' required readonly>";
cell4.innerHTML = "<input  type = 'text' class = 'form-control' name = 'cantidad[]' id = 'cantidad"+filas+"' value ='"+cantidad_entre_series+"' required readonly>";

billete_inicial = parseInt(billete_final) + 1;
filas++;


if (billete_final === billete_maximo) {
document.getElementById('guardar').disabled = false;
}else{
document.getElementById('guardar').disabled = true;
}



   }

}   


if (tabla.rows.length > 0) {
document.getElementById('guardar').disabled = false;  
}else{
document.getElementById('guardar').disabled = true;  
}

}else{




///////////////////////////////////////////////////////////////////////////////////
////////////////////// ASIGNACION MANUAL ////////////////////////////

text_revisor   = document.getElementById('option_revisor'+revisor).text;
nombre_revisor = text_revisor;
id_revisor     = revisor;

var cantidad_billetes = $('#cantidad_o').val();
var billete_maximo    = parseInt(cantidad_billetes) - 1;
tabla                 = document.getElementById("detalle_revisor");  


filas = $('#detalle_revisor tr').length;

var row = tabla.insertRow(filas);
var cell1 = row.insertCell(0);
var cell2 = row.insertCell(1);
var cell3 = row.insertCell(2);
var cell4 = row.insertCell(3);

cell1.style.width = '55%';
cell2.style.width = '15%';
cell3.style.width = '15%';
cell4.style.width = '15%';

cell1.innerHTML = "<input  type = 'hidden' class = 'form-control' name = 'id_o[]' value = '"+id_revisor+"' >"+nombre_revisor;
cell2.innerHTML = "<input  type = 'text' class = 'form-control' name = 'desde[]' id = 'desde"+filas+"' value = '' required readonly>";
cell3.innerHTML = "<input  type = 'text' class = 'form-control' name = 'hasta[]' id = 'hasta"+filas+"' value = '' required readonly>";
cell4.innerHTML = "<input  type = 'text' class = 'form-control' name = 'cantidad[]' onblur = 'calculo_manual("+filas+")' id = 'cantidad"+filas+"' value ='' required >";


////////////////////// ASIGNACION MANUAL ////////////////////////////
/////////////////////////////////////////////////////////////////////


}


}



</script>

<?php

$id_sorteo = $_SESSION['cc_mayor'];
$info_sorteo = mysqli_query($conn,"SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo' ");

$i_sorteo = mysqli_fetch_object($info_sorteo);
$numero_sorteo = $i_sorteo->no_sorteo_may;
$fecha_sorteo = $i_sorteo->fecha_sorteo;
$cantidad_billetes = $i_sorteo->cantidad_numeros;


?>

<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >ASIGNACION DE LOTERIA MAYOR PARA REVISION</h2> 

<h4 style="color:black; " align="center">
Sorteo Numero: <?php echo $numero_sorteo;?>
 | Fecha del Sorteo: <?php echo $fecha_sorteo;?>
<br>
Cantidad de Billetes: <?php echo number_format($cantidad_billetes);?> 
</h4>
</section>

<br>


<form method="POST">

<input type="hidden" name="id_sorteo_oculto"  value="<?php echo $id_sorteo;?>"></input>
<input type="hidden" name="cantidad_o" id="cantidad_o"  value="<?php echo $cantidad_billetes;?>"></input>


<div class="row">
<div class="col col-sm-4" style="margin-left: 10px">

<div class="card">
<div class="card-header alert alert-info">
<h4 align="center" >Revisores Disponibles</h4>
</div>  
<div class="card-body">

<div class="input-group">
  <div class="input-group-prepend">
    <div class="input-group-text">Asignacion automatica</div>
  </div>
<select onchange="remove_rows()" name = "tipo_asignacion" id = "tipo_asignacion" class = "form-control">
  <option value="SI">Si</option>
  <option value="NO">No</option>
</select>
</div>

<br>


<select class="form-control" name="select_revisor" id = "select_revisor" size="<?php echo $conteo_revisores; ?>"  multiple="true">
<?php
while ($revisor_select = mysqli_fetch_array($revisores_select)) {
echo "<option onclick='seleccion_revision(this.value)' id = 'option_revisor".$revisor_select['id']."' value='".$revisor_select['id']."' >".$revisor_select['nombre_completo']."</option>";
}
?>
</select>

</div>
</div>


  
</div>
<div class="col">

<div class="card" style="margin-right: 10px">
<div class="card-header alert alert-success">
<h4 align="center" >Revisores Seleccionados</h4>
</div>  
<div class="card-body">

  <table  class="table table-bordered">
    <tr>
      <th width="55%">Revisor</th>
      <th width="15%">Billete Inicial</th>
      <th width="15%">Billete Final</th>
      <th width="15%">Cantidad</th>
    </tr>
  </table>

  <table class="table table-bordered" id="detalle_revisor">
  </table>

  
</div>
<div class="card-footer">
<p align="center">
  <span onclick="remove_rows()" class = 'btn btn-danger' >Limpiar Tabla</span>
  <button type="submit" name="guardar" id="guardar" class="btn btn-info" disabled="true">Guardar</button>
</p>  
</div>
</div>

  
</div>
</div>




</form>