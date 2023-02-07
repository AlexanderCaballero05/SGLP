<?php
require('../../template/header.php');

require('./gerencia_loteria_menor_asignacion_extra_db.php');

?>

<script type="text/javascript">


function isNumberKey(evt){
var charCode = (evt.which) ? evt.which : event.keyCode
if (charCode > 31 && (charCode < 46 || charCode > 57))
return false;

return true;
}


function agregar_fila_extra(){

tabla = document.getElementById('numeros_adicionales');
filas = tabla.rows.length;

var row = tabla.insertRow(0);
var cell1 = row.insertCell(0);
var cell2 = row.insertCell(1);
var cell3 = row.insertCell(2);

cell1.style = "width:34%";
cell2.style = "width:35%";
cell3.style = "width:33%";
// Add some text to the new cells:
cell1.innerHTML = "<input class = 'form-control' onkeypress='return isNumberKey(event)' type= 'number' min = '0' max = '99' id = 'numero_i"+filas+"' name = 'numero_i[]' required> ";
cell2.innerHTML = "<input class = 'form-control' onkeypress='return isNumberKey(event)' type= 'number' min = '0' max = '99' id = 'numero_f"+filas+"' name = 'numero_f[]' required> ";
cell3.innerHTML = "<input class = 'form-control' onkeypress='return isNumberKey(event)' type= 'number' id = 'cantidad"+filas+"' name = 'cantidad[]' required>";
}

function eliminar_fila_extra(){
document.getElementById("numeros_adicionales").deleteRow(0);
}
</script>

<form method="POST">

<section style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);;background-color:#ededed;">
<br>
<h3 align='center'>Creacion de Numeros Extras de Loteria Menor</h3>

<h4 align='center'>Sorteo No. <?php echo $sorteo; ?> Fecha de Sorteo <?php echo $fecha_sorteo; ?></h4>
<br>
</section>
<br>


<div class="row">
<div class="col">
	










<div class="card" style="margin-left: 5px">
<div class="card-header">
<h3 align="center">Produccion Extra Por Asignar</h3>		
</div>
<div class="card-body">
<p align="right">
<SPAN onclick="agregar_fila_extra()" class="btn btn-primary"><i class="fa fa-plus"></i> Fila</SPAN>
<SPAN onclick="eliminar_fila_extra()" class="btn btn-danger"><i class="fa fa-minus"></i> Fila</SPAN>   
</p>



<table class="table table-hover table-bordered" width="100%" >
<tr>
	<thead>
		<th width="33%">Numero Inicial</th>
		<th width="33%">Numero Final</th>
		<th width="33%">Cantidad</th>				
	</thead>
</tr>
</table>

<div style=" overflow: scroll;height:400px ;align:left;width:99%" align="left" >
<div style="width:100%" >

<table class="table table-hover table-bordered" width="100%" id="numeros_adicionales">

<?php 
$filas = 0;
$conteo_extra_por_asignar = 0;

if ($conteo_extras_asignados == 0) {


while ($extras_anteriores = mysqli_fetch_array($num_extras_anteriores)) {
echo "<tr>";
echo "<td width = '33%'>";
echo "<input class = 'form-control' onkeypress='return isNumberKey(event)' type= 'number' min = '0' max = '99' id = 'numero_i".$filas."' name = 'numero_i[]' value = '".$extras_anteriores['numero']."' required>";
echo "</td>";
echo "<td width = '33%'>";
echo "<input class = 'form-control' onkeypress='return isNumberKey(event)' type= 'number' min = '0' max = '99' id = 'numero_f".$filas."' name = 'numero_f[]' value = '".$extras_anteriores['numero']."' required> ";
echo "</td>";
echo "<td width = '33%'>";
echo "<input class = 'form-control' onkeypress='return isNumberKey(event)' type= 'number' id = 'cantidad".$filas."' name = 'cantidad[]' value = '".$extras_anteriores['cantidad']."' required>";
echo "</td>";
echo "</tr>";
$filas ++;

$conteo_extra_por_asignar += $extras_anteriores['cantidad'];

}

}


?>
</table>
</div>
</div>


</div>
<div align="center" class="card-footer">

<input  type="submit" id="guardar" name="guardar" value="Guardar Numeros Extras" class="btn btn-primary">

</div>
</div>


</div>	











<div class="col">


<div class="card" style="margin-right: 5px">
<div class="card-header">
<h3 align="center">Produccion Extra Asignada</h3>
</div>

<div class="card-body">

<p align="center">
Total Extra Asignado: <?php echo $cantidad_extra_asignada; ?>	
</p>
<br>

<table width="100%" class="table table-hover table-bordered">
<thead>
<tr>
  <th width="5%"></th>
  <th width="35%">Numero</th>
  <th width="30%">Cantidad</th>
  <th width="30%">Accion</th>
</tr>
</thead>
</table>

<div style=" overflow: scroll;height:400px ;float:right;width:100%; margin-top: -10px"  align="right"> 

<table width="100%" class="table table-hover table-bordered">
<tbody>
<?php
$i = 0;
while ($row = mysqli_fetch_array($result2)) {
echo '<tr>';
echo '<td width = "5%" > <input type="checkbox" name="seleccion'.$i.'" value="'.$row['id'].'"></td>';
echo '<td width = "36%" >'.$row['numero'].'</td>';
echo '<td width = "31%" >'.$row['cantidad'].'</td>';
echo "<td width = '31%' align = 'center'>";
echo "<button class = 'btn btn-danger' name = 'eliminar_extra' value = '".$row['id']."' ><span  class = 'fa fa-times-circle'></span></button>";
echo "</td></tr>";
$i ++;
}

echo "<input type = 'hidden' name = 'conteo_asignado' value = '".$i."' >";

?>
</tbody>
</table>

</div>

	
</div>

<div align="center" class="card-footer">
<input type="submit" class="btn btn-danger" name="multiple_eliminado" value="Eliminar Seleccion">	
</div>

</div>

	
</div>	
</div>



<input type="hidden" id = 'id_oculto' name="id_oculto" value="<?php echo $id_sorteo; ?>">    

<br>
</form>