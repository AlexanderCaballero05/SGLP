Tipo<?php
require '../../template/header.php';
require './produccion_control_mayor_detalle_1_db.php';
?>

<script type="text/javascript">

function validar_detalle(){
		cantidades = document.getElementById('cantidades').value;

		if (cantidades == '') {
		  document.getElementById('cantidades').focus();
		  swal("Debe ingresar una cantidad", "", "error");
		}else{
		  document.getElementById('guardar_detalle').click();
		}
}

function validar_cierre(){
		h_i = document.getElementById('h_i').value;
		h_f = document.getElementById('h_f').value;
		billetes_buenos = document.getElementById('billetes_buenos').value;
		contador_final_maquina = document.getElementById('contador_final_maquina').value;

		if (h_i == '') {
		  document.getElementById('h_i').focus();
		  swal("Debe ingresar la hora inicial del control", "", "error");
		}else if(h_f == ''){
		  document.getElementById('h_f').focus();
		  swal("Debe ingresar la hora final del control", "", "error");
		}else if(billetes_buenos == ''){
		  document.getElementById('billetes_buenos').focus();
		  swal("Debe ingresar la cantidad de billetes buenos producidos", "", "error");
		}else if(contador_final_maquina == ''){
		  document.getElementById('contador_final_maquina').focus();
		  swal("Debe ingresar el contador final en maquina", "", "error");
		}else{
		  document.getElementById('guardar_cierre').click();
		}
}

function calculo_s_f(contador_maquina){

b_b = document.getElementById("billetes_buenos").value;

if (b_b === "") {
swal("","Debe ingresar los billetes buenos en el control.","error");

document.getElementById('contador_final_maquina').value = "";
document.getElementById('sobrante_faltante').value = "";

}else{

$contador_final = document.getElementById('contador_final').value;
$s_f = parseInt(contador_maquina) - parseInt($contador_final);

document.getElementById('sobrante_faltante').value = $s_f;

}


}

function f_contador_final(cantidad){
if (cantidad != '') {
$contador_final = document.getElementById('contador_final_o').value;
suma = parseInt($contador_final) + parseInt(cantidad) ;
}else{
$contador_final = document.getElementById('contador_final_o').value;
suma = parseInt($contador_final) + 0;
}
document.getElementById('contador_final').value = suma;
}


function parcial(){

cantidad = document.getElementById('cantidades').value;
if (cantidad == '') {
cantidad = 0;
}

$contador_final = document.getElementById('contador_final_o').value;
if ($contador_final == '') {
	$contador_final = 0;
}
suma = parseInt($contador_final) + parseInt(cantidad);
document.getElementById('numeradora_parcial').value = suma;

}


</script>


<form method="POST">

<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >GESTION DE CONTROLES DE PRODUCCION MAYOR</h2>
<br>
</section>


<br>


<div class="row">
<div class="col col-sm-4">

<div class="card" style=" margin-left: 10px">
<div class="card-header">
<h4 align="center">REGISTRO DE MALOS Y PRUEBAS</h4>
</div>

<div class="card-body">

<div class="input-group"   >
<div class="input-group-prepend" style="width: 25%" ><span style="width: 100%" class="input-group-text">Cantidad</span></div>
<input class="form-control" onblur="parcial()" id="cantidades"  type="text" name="cantidad" onkeypress="return isNumberKey(event)">
</div>

<div class="input-group" style="margin-top: 10px">
<div class="input-group-prepend" style="width: 25%"><span style="width: 100%" class="input-group-text">Tipo</span></div>
<select class="form-control" name="tipo">
<option value="B. Malos">B. Malos</option>
<option value="H. Blancas">H. Blancas</option>
<option value="P. Montaje">Pruebas de Montaje</option>
<option value="Para Reposicion">Para Reposicion</option>
</select>
</div>

<div class="input-group" style="margin-top: 10px">
<div class="input-group-prepend" style="width: 42%"><span style="width: 100%" class="input-group-text">Numeradora Parcial</span></div>
<input class="form-control"  type="text" id="numeradora_parcial" name="numeradora_parcial" value="<?php echo $contador_parcial; ?>" readonly><br>
</div>


<div class="input-group" style="margin-top: 10px">
<div class="input-group-prepend" style="width: 42%" ><span style="width: 100%" class="input-group-text">Observaciones</span></div>
<input class="form-control" onblur="parcial()" id="observaciones"  type="text" name="observaciones">
</div>

</div>

<div class="card-footer" align="center">
<?php

if ($estado_control == 'INICIADO') {
	echo '<span id="boton_validar_2" onclick="validar_detalle()"  class="btn btn-primary" >Guardar</span>';
} else {
	echo "<div class = 'alert alert-danger'>Control Finalizado</div>";
}

?>

</div>
</div>

</div>
<div class="col">

<div class="card"  style="margin-right: 10px;">
<div class="card-header" >
<h4 align="center">HISTORICO DE REGISTROS</h4>
</div>
<div class="card-body">

<table style="width:100%;font-size: 10pt" id="table_id1" class="table table-bordered table-responsive-sm">
<thead>
<tr>
	<th width ="20%">Numeradora Parcial</th>
	<th width ="20%">Tipo</th>
	<th width ="10%">Cantidad</th>
	<th width ="30%">Observaciones</th>
	<th width ="20%">Accion</th>
</tr>
</thead>
<tbody>
<?php

$tt_registrado = 0;

while ($reg_anterior = mysqli_fetch_array($registros_anteriores)) {

	$tt_registrado += $reg_anterior['cantidad'];

	echo "	<tr>
		<td>" . $reg_anterior['numeradora_parcial'] . "</td>
		<td> <select class = 'form-control' name ='edicion_tipo" . $reg_anterior['id'] . "' >";
	if ($reg_anterior['tipo'] == 'B. Malos') {
		echo "<option value='B. Malos' selected>B. Malos</option>";
		echo "<option value='H. Blancas' >H. Blancas</option>";
		echo "<option value='P. Montaje' >P. Montaje</option>";
		echo "<option value='Para Reposicion' >Para Reposicion</option>";

	} elseif ($reg_anterior['tipo'] == 'H. Blancas') {
		echo "<option value='B. Malos'>B. Malos</option>";
		echo "<option value='H. Blancas' selected>H. Blancas</option>";
		echo "<option value='P. Montaje' >P. Montaje</option>";
		echo "<option value='Para Reposicion' >Para Reposicion</option>";

	} elseif ($reg_anterior['tipo'] == 'P. Montaje') {
		echo "<option value='B. Malos' >B. Malos</option>";
		echo "<option value='H. Blancas' >H. Blancas</option>";
		echo "<option value='P. Montaje' selected>P. Montaje</option>";
		echo "<option value='Para Reposicion' >Para Reposicion</option>";
	} elseif ($reg_anterior['tipo'] == 'Para Reposicion') {
		echo "<option value='B. Malos' >B. Malos</option>";
		echo "<option value='H. Blancas' >H. Blancas</option>";
		echo "<option value='P. Montaje' >P. Montaje</option>";
		echo "<option value='Para Reposicion' selected>Para Reposicion</option>";
	}

	echo "</select></td>

		<td>" . $reg_anterior['cantidad'] . "</td>
		<td>" . $reg_anterior['observaciones'] . "</td>
		<td align = 'center'>";

	if ($estado_control == 'INICIADO') {
		echo "<button class = 'btn btn-info fa fa-edit' name = 'actualizar_detalle' value = '" . $reg_anterior['id'] . "'></button>
		<button class = 'btn btn-danger fa fa-times-circle' name = 'eliminar_detalle' value = '" . $reg_anterior['id'] . "'></button>";

	}
	echo "</td>
	  </tr>";

}
?>
</tbody>
<tfoot>
	<th colspan="2">TOTAL</th>
	<th><?php echo $tt_registrado; ?></th>
	<th ></th>
	<th ></th>
</tfoot>

</table>
</div>
</div>


</div>
</div>





<br>


<div class="card" style="margin-right: 10px; margin-left: 10px;">
<div class="card-header">
<h4 align="center">Cierre del Control</h4>
</div>

<div class="card-body">

<table class="table table-bordered table-responsive-sm">
<tr>
<td width="25%" align="center">

<div class="input-group">
<div class="input-group-prepend"><span class="input-group-text">Contador Inicial:</span></div>
<input  class= "form-control" style="width:50%" type="text" id="contador_inicial" value="<?php echo $contador_inicial; ?>" readonly>
</div>

</td>
<td width="25%" align="center">
Sorteo: <?php echo $no_sorteo . " - " . $no_sorteo_2; ?>

</td>
<td width="25%" align="center">
<?php echo $no_maquina; ?><br>

Etapa No. <?php echo $etapa; ?>

</td>
<td width="25%" align="center">

<div class="input-group">
<div class="input-group-prepend"><div class="input-group-text">Contador Final:</div></div>
<input class= "form-control" style="width:50%" type="text" id="contador_final" name="contador_final" value="<?php echo $contador_final; ?>" class="" readonly>
</div>

<input style="width:50%" type="hidden" id="contador_final_o" name="contador_final_o" value="<?php echo $contador_parcial; ?>" class="" readonly>

</td>
</tr>


<tr>
<td align="center">
<br>
Fecha: <?php echo $fecha_actual; ?><br><br>

<?php
if ($jornada == 'D') {
	echo "JORNADA DIURNA";
} else {
	echo "JORNADA NOCURNA";
}
?>

</td>
<td align="center">

<div class="input-group">
<div class="input-group-prepend"><span class="input-group-text">Hora Inicio</span></div>
<input style="width:50%;text-align: center" class="form-control" type="time" id="h_i" name="" value="<?php echo $hora_inicial; ?>" readonly>
</div>


<div class="input-group" style="margin-top: 10px">
<div class="input-group-prepend"><span class="input-group-text">Hora Final </span></div>
<input style="width:50%;text-align: center" class="form-control" type="time" id="h_f" name="h_f" value="<?php echo $hora_final; ?>" >
</div>


</td>

<td align="center">

<div class="input-group">
<div class="input-group-prepend"><span class="input-group-text">Billetes Buenos </span></div>
<input style="text-align: center" class="form-control" type="text" onblur ="f_contador_final(this.value)"  id="billetes_buenos" name="billetes_buenos" value="<?php echo $billetes_buenos; ?>" onkeypress="return isNumberKey(event)" >
</div>


<div class="input-group" style="margin-top: 10px">
<div class="input-group-prepend"><span class="input-group-text">Cont. Final en Maq.:</span></div>
<input class="form-control"  onkeyup="calculo_s_f(this.value)" type="text" id="contador_final_maquina" name="contador_final_maquina" value = '<?php echo $contador_final_maquina; ?>' onkeypress="return isNumberKey(event)" >
</div>

</td>

<td align="center">

<p align="center"> Diferencias: <input id="sobrante_faltante" type="text" class="form-control" style="width:60%;text-align: center" id ="diferencia" value = '<?php echo $sobrante_faltante; ?>' readonly></p>

<p align="center">
</p>

</td>

</tr>
</table>


</div>
<div class="card-footer" align="center">

<?php

if ($estado_control == 'INICIADO') {
	?>
<span id="boton_validar" onclick="validar_cierre()"  class="btn btn-primary" >Realizar Cierre de Control</span>
<?php

} else {

	?>

<div class="alert alert-danger">El control ya fue finalizado, para aperturarlo debe dar clic con el mouse en el siguiente boton:
<button type="submit" class="btn btn-danger" value="<?php echo $id; ?>" name="reaperturar_control">REAPERTURAR</button>
</div>
<?php

}

?>

</div>
</div>

<button style="visibility:hidden;" class= 'btn btn-primary' id="guardar_detalle" name="guardar_detalle" value=" <?php echo $id ?>">Guardar</button>
<button style="visibility:hidden;" class= 'btn btn-primary' id="guardar_cierre" name="guardar_cierre" value=" <?php echo $id ?>">Realizar Cierre de Control</button>



</form>