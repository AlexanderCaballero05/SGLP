<?php
require('../../template/header.php');
require('./fvp_distribucion_pedidos_menor_bolsas_db.php');
?>
<script type="text/javascript">
function calcular_cantidad(id){

i = id;
serie_inicial = parseInt(document.getElementById('serie_inicial'+i).value);
serie_final = parseInt(document.getElementById('serie_final'+i).value);

cantidad = serie_final - serie_inicial + 1;
if (isNaN(cantidad)) {
cantidad = '';  
}
document.getElementById('cantidad_seccional'+i).value = cantidad;

}


function validar_disponible(){

filas_distribucion = document.getElementById('tabla_distribucion').rows.length;
filas_distribucion --;
filas_historico = document.getElementById('tabla_historico').rows.length;
filas_historico = filas_historico - 2;

if (filas_historico != 0) {

for (var i = 0; i <  filas_historico; i++) {
s_i_h = parseInt(document.getElementById('serie_inicial_historico'+i).value); 
s_f_h = parseInt(document.getElementById('serie_final_historico'+i).value); 

for (var j = 0; j < filas_distribucion; j++) {
s_i = parseInt(document.getElementById('serie_inicial'+j).value);
s_f = parseInt(document.getElementById('serie_final'+j).value);

if (isNaN(s_i) || isNaN(s_f)) {

document.getElementById('serie_inicial'+j).value = '';
document.getElementById('serie_final'+j).value = '';
document.getElementById('cantidad_seccional'+j).value = '';

}else{

if (s_i <= s_f) {

if ((s_i >= s_i_h && s_f <= s_f_h) || (s_i <= s_f_h && s_f >= s_i_h)) {
indice = j;


swal({
  title: "",
  text: "Algunas series entre la "+s_i+" y "+s_f+" ya fueron asignadas previamente. \n Por favor verifique el historico de distribucion.",
  icon: "error",
  buttons: false,
  dangerMode: false,
})
.then(() => {


document.getElementById('serie_inicial'+indice).value = '';
document.getElementById('serie_inicial'+indice).focus();
document.getElementById('serie_final'+indice).value = '';
document.getElementById('cantidad_seccional'+indice).value = '';


});


}else{
cantidad = s_f - s_i + 1;
document.getElementById('cantidad_seccional'+j).value = cantidad;
}

}else{
indice = j;

swal({ 
  title: "",
   text: " La serie inicial no puede ser mayor a la serie final.",
    type: "error" 
  },
  function(){

document.getElementById('serie_inicial'+indice).value = '';
document.getElementById('serie_inicial'+indice).focus();
document.getElementById('serie_final'+indice).value = '';
document.getElementById('cantidad_seccional'+indice).value = '';

});


}

}

}

}

}else{


for (var j = 0; j < filas_distribucion; j++) {
s_i = parseInt(document.getElementById('serie_inicial'+j).value);
s_f = parseInt(document.getElementById('serie_final'+j).value);

if (isNaN(s_i) || isNaN(s_f)) {

document.getElementById('serie_inicial'+j).value = '';
document.getElementById('serie_final'+j).value = '';
document.getElementById('cantidad_seccional'+j).value = '';

}else{

if (s_i <= s_f) {

cantidad = s_f - s_i + 1;
document.getElementById('cantidad_seccional'+j).value = cantidad;

}else{
indice = j;

swal({ 
  title: "",
   text: " La serie inicial no puede ser mayor a la serie final.",
    type: "error" 
  },
  function(){

document.getElementById('serie_inicial'+indice).value = '';
document.getElementById('serie_inicial'+indice).focus();
document.getElementById('serie_final'+indice).value = '';
document.getElementById('cantidad_seccional'+indice).value = '';

});


}

}

}

}

}

</script>



<form method="POST">

<br>

<ul class="nav nav-tabs">
 <li class="nav-item">
    <a  class="nav-link" href="./screen_distribucion_pedidos_mayor.php" >Distribución Mayor</a>
  </li>
  <li class="nav-item">
    <a style="background-color:#ededed;" class="nav-link active"  >Distribución Menor Bolsas</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="./fvp_distribucion_pedidos_menor_numeros_grupos.php" >Distribución Menor Extra</a>
  </li>
</ul>


<section style="background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >DISTRIBUCION DE LOTERIA MENOR</h2> 
<br>
</section>


<a class="btn btn-info" style="width:100%" role="button" data-toggle="collapse" href="#collapse3" aria-expanded="false" aria-controls="collapse3">
 Selección de Parametros 
</a>

<div  class="collapse" style = "width:100%"  id="collapse3" align="center">
<div class="card" align="center" style="width: 50%">
<div class="card-body">

<div class="input-group">
<div class="input-group-prepend"><div class="input-group-text">Sorteo: </div></div>  

<select class="form-control" name="sorteo" >
<?php
while ($row2 = mysqli_fetch_array($sorteos)) {
echo '<option value = "'.$row2['id'].'">'.$row2['no_sorteo_men'].'</option>' ;
}
?>
</select>       



<div class="input-group-append">
<input  type="submit" name="seleccionar" class="btn btn-primary" value="Seleccionar"> 
</div>

</div>

</div>
</div>
</div>


<br>

<?php 
if (isset($_POST['seleccionar'])) {
?>

<br>

<input type="hidden" name="id_sorteo_oculto" value="<?php $id_sorteo?>">
<input type="hidden" id="billetes_disponibles_oculto" value="<?php echo $bolsas_disponibles; ?>">



<div class="card" style="margin-right: 10px; margin-left: 10px">
<div class="card-header bg-secondary text-white"  align="center">

 <h3 align="center">
 Sorteo Numero: <?php if (isset($sorteo)) {echo $sorteo;} ?>  
 Fecha de Sorteo: <?php if (isset($sorteo)) {echo $fecha_sorteo;} ?>  
 </h3>   

<hr>

<div class="input-group" align="center" style="width: 25%">

<div class="input-group-prepend"><div class="input-group-text">Bolsas Disponibles: </div></div>
<input type="text" class="form-control" style="text-align: center;" id="billetes_disponibles" value="<?php echo $bolsas_disponibles; ?>" readonly >

</div>

  
</div>  
<div class="card-body">

<?php


echo "<table  width = '100%' id = 'tabla_distribucion' class= 'table table-hover table-bordered'>";
echo "<tr>
<th width = '50%'>Entidad Recaudadora</th>
<th width = '10%'>Serie Inicial</th>
<th width = '10%'>Serie Final</th>
<th width = '10%'>Cantidad a Asignar</th>
<th width = '10%'>Bolsas</th>
<th width = '10%'>Numeros</th>
</tr>";

$i = 0; 
while ($row = mysqli_fetch_array($empresas)) {

echo "<tr>
<td align= 'center'>".$row['nombre_empresa']."</td>
<input type = 'hidden' value = '".$row['id']."' name = 'id_empresa".$i."'>

<td  align= 'center'><input min = '0' max = '".$num_series."' class = 'form form-control' type = 'number' id = 'serie_inicial".$i."' name = 'serie_inicial".$i."' ></td>

<td  align= 'center'><input min = '0' max = '".$num_series."' class = 'form form-control' type = 'number' id = 'serie_final".$i."' name = 'serie_final".$i."' onblur = 'validar_disponible()' ></td>

<td align= 'center'><input class = 'form form-control' type = 'text' id = 'cantidad_seccional".$i."' name = 'cantidad_seccional".$i."' readonly ></td>


<td><input type = 'radio' name = 'tipo_venta".$i."' class = 'form-control' value = 'bolsas' checked></td>

<td><input type = 'radio' name = 'tipo_venta".$i."' class = 'form-control' value = 'numeros' ></td>

</tr>";
$i++;

}

echo "</table>";


?>




</div>

<div class="card-footer" align="center">
<input type="submit" name="guardar_distribucion" class="btn btn-primary" value="Guardar Distribucion">
</div>

</div>




<br>

<a class="btn btn-info" style="width:100%" role="button" data-toggle="collapse" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
<h3>  Historico de Distribucion </h3>
</a>

<div  class="collapse" style = "width:100%"  id="collapse2">
<div class="well">
<?php 

echo "<table id = 'tabla_historico' width = '100%'  class= 'table table-hover table-bordered'>";
echo "<tr>
<th width = '40%'>Nombre Empresa</th>
<th width = '10%'>Serie Inicial</th>
<th width = '10%'>Serie Final</th>
<th width = '10%'>Cantidad</th>
<th width = '20%'>Modalidad Venta</th>
<th width = '10%'>Accion</th>
</tr>";

$cantidad_total = 0;

$bolsas_asignadas = mysqli_query($conn,"SELECT a.id,a.cantidad, a.serie_inicial, a.serie_final, a.cod_factura , b.nombre_empresa FROM menor_seccionales_bolsas as a INNER JOIN empresas as b ON a.id_empresa = b.id  WHERE a.id_sorteo = '$id_sorteo' ORDER BY serie_inicial ASC ");

$numeros_asignados_bolsas = mysqli_query($conn,"SELECT a.id,a.cantidad, a.serie_inicial, a.serie_final, a.cod_factura , b.nombre_empresa FROM menor_seccionales_numeros as a INNER JOIN empresas as b ON a.id_empresa = b.id  WHERE a.id_sorteo = '$id_sorteo' AND a.origen = 'bolsas' GROUP BY a.serie_inicial ASC, a.serie_final ASC ");

$fila = 0;

while ($bolsa_asignada = mysqli_fetch_array($bolsas_asignadas)) {

$cantidad_total = $cantidad_total + $bolsa_asignada['cantidad'];
echo "<tr>";
echo "<td>". $bolsa_asignada['nombre_empresa']."</td>";
echo "<td><input type = 'number' id = 'serie_inicial_historico".$fila."' value = '". $bolsa_asignada['serie_inicial']."' class = 'form-control' disabled> </td>";
echo "<td><input type = 'number' id = 'serie_final_historico".$fila."' value = '". $bolsa_asignada['serie_final']."' class = 'form-control' disabled></td>";
echo "<td>". $bolsa_asignada['cantidad']."</td>";
echo "<td>BOLSAS</td>";
if ($bolsa_asignada['cod_factura'] == NULL) {
echo "<td align = 'center'><button class = 'btn btn-danger' value = '".$bolsa_asignada['id']."' name = 'eliminar_distribucion'>x</button></td>";
}else{
echo "<td align = 'center'><button class = 'btn btn-danger' value = '".$bolsa_asignada['id']."' name = 'eliminar_distribucion' disabled>x</button></td>";  
}
echo "</tr>";

$fila++;
}


while ($num_asignado = mysqli_fetch_array($numeros_asignados_bolsas)) {
$cantidad_total = $cantidad_total + $num_asignado['cantidad'];

echo "<tr>";
echo "<td>". $num_asignado['nombre_empresa']."</td>";
echo "<td><input type = 'number' id = 'serie_inicial_historico".$fila."' value = '". $num_asignado['serie_inicial']."' class = 'form-control' disabled></td>";
echo "<td><input type = 'number' id = 'serie_final_historico".$fila."' value = '". $num_asignado['serie_final']."' class = 'form-control' disabled></td>";
echo "<td>". $num_asignado['cantidad']."</td>";
echo "<td>NUMEROS (00-99)</td>";

$parametros = $num_asignado['serie_inicial']."/".$num_asignado['serie_final'];

if ($num_asignado['cod_factura'] == NULL) {
echo "<td align = 'center'><button class = 'btn btn-danger' value = '".$parametros."' name = 'eliminar_distribucion_numeros'>x</button></td>";
}else{
echo "<td align = 'center'><button class = 'btn btn-danger' value = '' name = 'eliminar_distribucion_numeros' disabled>x</button></td>";  
}

echo "</tr>";

$fila++;
}

echo "<tr>";
echo "<th colspan = '3'>TOTAL</th>";
echo "<th>".$cantidad_total."</th>";
echo "<th></th>";
echo "<th></th>";
echo "</tr>";
echo "</table>";

?>
</div>
</div>
<br>
<br><br>
<br><br>

<?php 

}

?>

  </div>
</div>
</form>