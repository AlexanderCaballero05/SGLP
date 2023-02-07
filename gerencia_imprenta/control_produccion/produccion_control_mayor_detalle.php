<?php
require '../../template/header.php';
require './produccion_control_mayor_detalle_db.php';
?>


<script type="text/javascript">


jQuery(function($){
$("#billete_i").mask("99999", { placeholder: "_____" });
$("#billete_f").mask("99999", { placeholder: "_____" });
});








////////////////////////////////////////////////////////////////////////////
//////////////////// FUNCION PARA EL CALCULO DE REGISTROS //////////////////
//////////////////// FUNCION PARA EL CALCULO DE REGISTROS //////////////////

function calculo_registro(input){

id_sorteo = document.getElementById('id_sorteo_o').value;
billete   = document.getElementById('billete_'+input).value;
grupo     = document.getElementById('grupo').value;

loteria   = 1;


if (grupo != 'S/S') {

var rango_billetes = grupo.split(" - ");
billete_inicial_grupo = rango_billetes[0];
billete_final_grupo = rango_billetes[1];


if (parseInt(billete_final_grupo) < parseInt(billete) || parseInt(billete_inicial_grupo) > parseInt(billete)){

swal("El billete ingresado debe estar contenido dentro del grupo seleccionado.", "", "error");

document.getElementById("cantidad").value = 0;

document.getElementById('billete_'+input).value = "";
document.getElementById('registro_'+input).value = "";

}

}



if (billete === '') {

swal("Debe ingresar un numero de billete.", "", "error");

document.getElementById("cantidad").value = 0;

document.getElementById('registro_'+input).value = "";


}else{


if (billete.length < 5) {

swal("El billete ingresado debe contener 5 digitos y estar dentro del grupo seleccionado.", "", "error");

document.getElementById('billete_'+input).value = "";
document.getElementById('registro_'+input).value = "";

}else{


bi = parseInt(document.getElementById("billete_i").value);
bf = parseInt(document.getElementById("billete_f").value);

if (bf < bi) {

swal("El billete final no puede ser mayor al inicial.", "", "error");

document.getElementById("cantidad").value = 0;

document.getElementById("billete_f").value = "";
document.getElementById("registro_f").value = "";
document.getElementById("billete_f").focus();

c =  0;

}else{

if (bi >= 0  && bf >= 0) {
c =  bf - bi + 1;
}else{
c =  0;
}

}


document.getElementById("cantidad").value = c;


token = Math.random();
consulta = 'produccion_control_return_registro.php?sorteo='+id_sorteo+"&loteria="+loteria+"&billete="+billete+"&input="+input+"&token="+token;
$("#div_respuesta").load(consulta);


}

}

}


//////////////////// FUNCION PARA EL CALCULO DE REGISTROS //////////////////
//////////////////// FUNCION PARA EL CALCULO DE REGISTROS //////////////////
////////////////////////////////////////////////////////////////////////////












////////////////////////////////////////////////////////////////////////////
///////////////////////// CALCULO NUMERADORA PARCIAL ///////////////////////
///////////////////////// CALCULO NUMERADORA PARCIAL ///////////////////////


function calculo_numeradora_parcial(){

cantidad   =  document.getElementById('cantidad').value;
cantidad_i =  document.getElementById('cantidad_impresiones').value;
tipo 	   =  document.getElementById('tipo').value;


if (tipo == "B. Malos") {

if (cantidad === '') {
swal("","Debe ingresar los rangos de billetes a registrar.","error");
}else{

cantidad_total = parseInt(cantidad) * parseInt(cantidad_i);

}

}else{

cantidad_total = cantidad_i;

}

if (cantidad_total > 0) {

parcial 		 = parseInt(document.getElementById("numeradora_parcial_o").value);
if (cantidad_total > 0 ) {
parcial_final 	 = parseInt(parcial) + parseInt(cantidad_total);
document.getElementById("numeradora_parcial").value = parcial_final;
}else{
document.getElementById("numeradora_parcial").value = parcial;
}

}else{

swal("","Debe ingresar el numero de billetes.","error");
}

}

///////////////////////// CALCULO NUMERADORA PARCIAL ///////////////////////
///////////////////////// CALCULO NUMERADORA PARCIAL ///////////////////////
////////////////////////////////////////////////////////////////////////////





////////////////////////////////////////////////////////////////////////////
//////////////////// FUNCION PARA VALIDAR EL TIPO DE IMPR //////////////////
//////////////////// FUNCION PARA VALIDAR EL TIPO DE IMPR //////////////////

function validar_tipo(tipo){

if (tipo == "B. Malos") {

document.getElementById('billete_i').readOnly = false;
document.getElementById('billete_f').readOnly = false;
document.getElementById('cantidad').readOnly = true;
document.getElementById('cantidad').value 	= '';
document.getElementById('cantidad_impresiones').value 	= '';
document.getElementById('numeradora_parcial').value = document.getElementById('numeradora_parcial_o').value;


}else if(tipo == "P. Montaje"){

document.getElementById('billete_i').readOnly = true;
document.getElementById('billete_f').readOnly = true;

document.getElementById('billete_i').value = '';
document.getElementById('billete_f').value = '';

document.getElementById('registro_i').value = "";
document.getElementById('registro_f').value = "";
document.getElementById('cantidad').value 	= "";
document.getElementById('cantidad_impresiones').value 	= "";
document.getElementById('numeradora_parcial').value = document.getElementById('numeradora_parcial_o').value;


}else if(tipo == "H. Blancas"){

document.getElementById('billete_i').readOnly = true;
document.getElementById('billete_f').readOnly = true;

document.getElementById('billete_i').value = "";
document.getElementById('billete_f').value = "";
document.getElementById('cantidad').value 	= "";
document.getElementById('cantidad_impresiones').value 	= "";

document.getElementById('registro_i').value = "";
document.getElementById('registro_f').value = "";
document.getElementById('numeradora_parcial').value = document.getElementById('numeradora_parcial_o').value;

}else if(tipo == "S/S"){

document.getElementById('billete_i').readOnly = true;
document.getElementById('billete_f').readOnly = true;

document.getElementById('billete_i').value = "";
document.getElementById('billete_f').value = "";
document.getElementById('cantidad').value 	= "";
document.getElementById('cantidad_impresiones').value 	= "";

document.getElementById('registro_i').value = "";
document.getElementById('registro_f').value = "";
document.getElementById('numeradora_parcial').value = document.getElementById('numeradora_parcial_o').value;

}

}


//////////////////// FUNCION PARA VALIDAR EL TIPO DE IMPR //////////////////
//////////////////// FUNCION PARA VALIDAR EL TIPO DE IMPR //////////////////
////////////////////////////////////////////////////////////////////////////





///////////////////////////////////////////////////////////////////
//////////////////// FUNCION PARA VALIDAR GRUPO  //////////////////
//////////////////// FUNCION PARA VALIDAR GRUPO  //////////////////

function validar_grupo(grupo){

document.getElementById('billete_i').value = '';
document.getElementById('billete_f').value = '';
document.getElementById('registro_i').value = '';
document.getElementById('registro_f').value = '';
document.getElementById('cantidad').value  = '';
document.getElementById('cantidad_impresiones').value 	= '';
document.getElementById('numeradora_parcial').value = document.getElementById('numeradora_parcial_o').value;

}

//////////////////// FUNCION PARA VALIDAR GRUPO  //////////////////
//////////////////// FUNCION PARA VALIDAR GRUPO  //////////////////
///////////////////////////////////////////////////////////////////






////////////////////////////////////////////////////////////////////////////
//////////////////// FUNCION PARA VALIDAR EL DETALLE A RE //////////////////
//////////////////// FUNCION PARA VALIDAR EL DETALLE A RE //////////////////

function validar_detalle(){


cantidad_r = document.getElementById('cantidad').value;
cantidad_i = document.getElementById('cantidad_impresiones').value;
tipo 	   =  document.getElementById('tipo').value;

if (tipo == "B. Malos") {

if (cantidad === '') {
swal("","Debe ingresar los rangos de billetes a registrar.","error");
cantidad = 0;

}else{

if (cantidad_i === '') {

swal("","Debe ingresar la cantidad de billetes a registrar.","error");
cantidad = 0;

}else{

cantidad = parseInt(cantidad_i) * parseInt(cantidad_r);

}

}


if (cantidad > 0) {

document.getElementById('guardar_detalle').click();

}else{

swal("Al menos uno de los datos ingresados es incorrecto, por favor verifique la informacion ingresada.", "", "error");

}



}else{


if (cantidad_i === '') {

swal("","Debe ingresar la cantidad de billetes a registrar.","error");
cantidad = 0;

}else{

document.getElementById('guardar_detalle').click();


}


}



}

//////////////////// FUNCION PARA VALIDAR EL DETALLE A RE //////////////////
//////////////////// FUNCION PARA VALIDAR EL DETALLE A RE //////////////////
////////////////////////////////////////////////////////////////////////////






////////////////////////////////////////////////////////////////////////////
//////////////////// FUNCION PARA VALIDAR CIERRE CONTROL ///////////////////
//////////////////// FUNCION PARA VALIDAR CIERRE CONTROL ///////////////////


function validar_cierre(){
		h_i = document.getElementById('h_i').value;
		h_f = document.getElementById('h_f').value;
		billete_final = document.getElementById('billete_final').value;
		billetes_buenos = document.getElementById('billetes_buenos').value;
		contador_final_maquina = document.getElementById('contador_final_maquina').value;

		if (h_i == '') {
		  document.getElementById('h_i').focus();
		  swal("Debe ingresar la hora inicial del control", "", "error");
		}else if(h_f == ''){
		  document.getElementById('h_f').focus();
		  swal("Debe ingresar la hora final del control", "", "error");
		}else if(billete_final == ''){
		  document.getElementById('billete_final').focus();
		  swal("Debe ingresar el billete final producido", "", "error");
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

//////////////////// FUNCION PARA VALIDAR CIERRE CONTROL ///////////////////
//////////////////// FUNCION PARA VALIDAR CIERRE CONTROL ///////////////////
////////////////////////////////////////////////////////////////////////////





/////////////////////////////////////////////////////////////////////////////////
//////////////////// FUNCION PARA CALCULO SOBRANTES FALTANTES ///////////////////
//////////////////// FUNCION PARA VALIDAR SOBRANTES FALTANTES ///////////////////


function calculo_s_f(contador_maquina){
	if (contador_maquina != '') {
$contador_final = document.getElementById('contador_final').value;
$s_f = parseInt(contador_maquina) - parseInt($contador_final);

document.getElementById('sobrante_faltante').value = $s_f;
	}
}

//////////////////// FUNCION PARA CALCULO SOBRANTES FALTANTES ///////////////////
//////////////////// FUNCION PARA VALIDAR SOBRANTES FALTANTES ///////////////////
/////////////////////////////////////////////////////////////////////////////////




/////////////////////////////////////////////////////////////////////
//////////////////// FUNCION PARA CALCULO BUENOS  ///////////////////
//////////////////// FUNCION PARA CALCULO BUENOS  ///////////////////

function calculo_buenos(billete_final){


billete_inicial = parseInt(document.getElementById('billete_inicial').value);

if (billete_final < billete_inicial) {

 swal({
  title: "",
   text: "El billete final debe ser mayor o igual al inicial.",
    type: "error"
  });
 document.getElementById('billete_final').value = '';


}else{

if (billete_inicial !== '') {
billetes_buenos = parseInt(billete_final) - parseInt(billete_inicial) + 1;
document.getElementById('billetes_buenos').value = billetes_buenos;
f_contador_final(billetes_buenos);

}else{

billetes_buenos = 0;
document.getElementById('billetes_buenos').value = billetes_buenos;
f_contador_final(billetes_buenos);

}


}

}

//////////////////// FUNCION PARA CALCULO BUENOS  ///////////////////
//////////////////// FUNCION PARA CALCULO BUENOS  ///////////////////
/////////////////////////////////////////////////////////////////////





/////////////////////////////////////////////////////////////////////////////
//////////////////// FUNCION PARA CALCULO CONTADOR FINAL  ///////////////////
//////////////////// FUNCION PARA CALCULO CONTADOR FINAL  ///////////////////

function f_contador_final(cantidad){

if (cantidad != '') {
$contador_final = document.getElementById('numeradora_parcial_o').value;
suma = parseInt($contador_final) + parseInt(cantidad) ;
}else{
$contador_final = document.getElementById('numeradora_parcial_o').value;
suma = parseInt($contador_final) + 0;
}

document.getElementById('contador_final').value = suma;

}

//////////////////// FUNCION PARA CALCULO CONTADOR FINAL  ///////////////////
//////////////////// FUNCION PARA CALCULO CONTADOR FINAL  ///////////////////
/////////////////////////////////////////////////////////////////////////////


</script>


<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >Control de Produccion de Loteria Nacional Mayor</h2>
<br>
</section>


<form method="POST">

<?php

if ($conteo_paralelos > 0 AND $estado_control == 'INICIADO') {

	$ob_control_paralelo = mysqli_fetch_object($controles_paralelos);
	$id_control_paralelo = $ob_control_paralelo->id;

	echo "<br><div class = 'alert alert-info' align = 'center'>Existe un control creado en paralelo y finalizado, si desea importar los registros de dicho control de clic con el mouse en el siguiente boton: <br> <button type = 'submit' name = 'registrar_paralelo' id = 'registrar_paralelo' class = 'btn btn-primary' value = '" . $id_control_paralelo . "' >Importar Control Paralelo</button></div>";

}

?>



<div id="div_respuesta"></div>

<br>

<input type="hidden" name="patron_salto" id="patron_salto" value="<?php echo $patron_salto; ?>" >


<div class="row">
<div class="col col-md-4">

<div class="card" style="margin-left: 10px">
<div class="card-header">
	<h4>REGISTRO DE MALOS Y PRUEBAS</h4>
</div>

<div class="card-body">

<?php

if ($billete_inicial_control < $patron_salto) {

	$billete_inicial_grupo = 0;

} else {

	$residuo = $billete_inicial_control / $patron_salto;
	$multiplo = substr($residuo, 0, 1);
	$billete_inicial_grupo = $patron_salto * $multiplo;

}

?>

<div class="input-group">
<div class="input-group-prepend">
<span style="width: 100px" class="input-group-text">Grupo</span>
</div>
<select class="form-control"  name="grupo" id="grupo" onchange="validar_grupo(this.value)" readonly>
<?php

if ($billete_inicial_control < $patron_salto) {

	$billete_inicial_grupo = 0;

} else {

	$residuo = $billete_inicial_grupo / $patron_salto;
	$multiplo = substr($residuo, 0, 1);

	$billete_inicial_grupo = $patron_salto * $multiplo;

}

$billete_final_grupo = $billete_inicial_grupo + $patron_salto - 1;

$concat_grupo = $billete_inicial_grupo . " - " . $billete_final_grupo;
echo "<option value = '" . $concat_grupo . "'>" . $concat_grupo . "</option>";
?>
</select>
</div>



<div class="input-group" style="margin-top: 5px">
	<div class="input-group-prepend">
	<span style="width: 100px" class="input-group-text">Tipo</span>
	</div>
	<select class="form-control" style="" id="tipo" name="tipo" onchange="validar_tipo(this.value)">
		<option value="B. Malos">B. Malos</option>
		<option value="H. Blancas">H. Blancas</option>
		<option value="P. Montaje">P. Montaje</option>
		<option value="S/S">S/S</option>
	</select>
</div>

<div class="input-group" style="margin-top: 5px">
	<div class="input-group-prepend">
	<span style="width: 100px"  class="input-group-text">Billete I</span>
	</div>
	<input class="form-control"   width="40%" onkeypress="return isNumberKey(event)" onblur="calculo_registro('i',this.value)"  type="text" name="del_billete" id="billete_i">

	<div class="input-group-prepend">
	<span class="input-group-text">Registro</span>
	</div>
	<input class="form-control"  type="text"  name="del_registro" id="registro_i" readonly>
</div>


<div class="input-group" style="margin-top: 5px">
	<div class="input-group-prepend">
	<span style="width: 100px" class="input-group-text">Billete F</span>
	</div>
	<input class="form-control"  width="40%"  onkeypress="return isNumberKey(event)" onblur="calculo_registro('f',this.value)"  type="text" name="al_billete" id="billete_f">

	<div class="input-group-prepend">
	<span class="input-group-text">Registro</span>
	</div>
	<input class="form-control"  type="text"  name="al_registro" id="registro_f" readonly>
</div>


<div class="input-group" style="margin-top: 5px">
	<div class="input-group-prepend">
	<span style="width: 200px" class="input-group-text">Cantidad en Rango</span>
	</div>
	<input class="form-control" onkeypress="return isNumberKey(event)" style="" type="text" id="cantidad" name="cantidad" readonly="true">
</div>



<div class="input-group" style="margin-top: 5px">
	<div class="input-group-prepend">
	<span style="width: 200px" class="input-group-text">Cantidad Billetes</span>
	</div>
	<input class="form-control" onkeypress="return isNumberKey(event)" onblur="calculo_numeradora_parcial()"  onkeypress="return isNumberKey(event)" type="text" id="cantidad_impresiones" name="cantidad_impresiones" >
</div>



<div class="input-group" style="margin-top: 5px">
	<div class="input-group-prepend">
	<span class="input-group-text">Observacion</span>
	</div>
	<input class="form-control"  id="observaciones"  type="text" name="observaciones">
</div>


<div class="input-group" style="margin-top: 5px">
	<div class="input-group-prepend">
	<span class="input-group-text">Numeradora Parcial</span>
	</div>
	<input class="form-control"  type="text" id="numeradora_parcial" name="numeradora_parcial" value="<?php echo $contador_parcial; ?>" readonly>
	<input class="form-control"  type="hidden" id="numeradora_parcial_o" name="numeradora_parcial_o" value="<?php echo $contador_parcial; ?>" readonly>
</div>


</div>

<div class="card-footer" align="center">

<?php
if ($estado_control == 'INICIADO') {
	?>
<span id="boton_validar_2" onclick="validar_detalle()"  class="btn btn-primary" >Guardar</span>
<?php
} else {
	?>
<div class="alert alert-info">Control finalizado</div>
<?php
}

?>

</div>

</div>

</div>

<div class="col ">


<div class="card" style="margin-right: 10px">
	<div class="card-header">
	<h4 align="center">Historico de Registros</h4>
	</div>

	<div class="card-body">

<table  id="table_id1" class="table table-bordered">
	<thead>
	<tr>
		<th style="width: 15%">Billetes <br> Del ~ Al</th>
		<th >Registro <br> Del ~ Al</th>
		<th >Cantidad</th>
		<th >Tipo</th>
		<th >Observaciones</th>
		<th >Numeradora Parcial</th>
		<th >Accion</th>
	</tr>
	</thead>
	<tbody>
<?php

$contador_parcial_detalle = $contador_inicial;
while ($reg_anterior = mysqli_fetch_array($registros_anteriores)) {
	$contador_parcial_detalle = $contador_parcial_detalle + $reg_anterior['reposiciones'] + $reg_anterior['cantidad'];

	echo "	<tr>

<td>" . $reg_anterior['de_billete'] . " ~ " . $reg_anterior['a_billete'] . "</td>
<td>" . $reg_anterior['de_registro'] . " ~ " . $reg_anterior['a_registro'] . "</td>

		<td>" . $reg_anterior['cantidad'] . "</td>
		<td> <select class = 'form-control' name ='edicion_tipo" . $reg_anterior['id'] . "' >";
	if ($reg_anterior['tipo'] == 'B. Malos') {
		echo "<option value='B. Malos' selected>B. Malos</option>";
		echo "<option value='H. Blancas' >H. Blancas</option>";
		echo "<option value='P. Montaje' >P. Montaje</option>";
		echo "<option value='S/S' >S/S</option>";
	} elseif ($reg_anterior['tipo'] == 'H. Blancas') {
		echo "<option value='B. Malos'>B. Malos</option>";
		echo "<option value='H. Blancas' selected>H. Blancas</option>";
		echo "<option value='P. Montaje' >P. Montaje</option>";
		echo "<option value='S/S' >S/S</option>";
	} elseif ($reg_anterior['tipo'] == 'P. Montaje') {
		echo "<option value='B. Malos' >B. Malos</option>";
		echo "<option value='H. Blancas' >H. Blancas</option>";
		echo "<option value='P. Montaje' selected>P. Montaje</option>";
		echo "<option value='S/S' >S/S</option>";
	} elseif ($reg_anterior['tipo'] == 'S/S') {
		echo "<option value='B. Malos' >B. Malos</option>";
		echo "<option value='H. Blancas' >H. Blancas</option>";
		echo "<option value='P. Montaje' >P. Montaje</option>";
		echo "<option value='S/S' selected>S/S</option>";
	}

	echo "</select></td>
<td>" . $reg_anterior['observaciones'] . "</td>
<td>" . $contador_parcial_detalle . "</td>
<td   align = 'center'>";
	if ($estado_control == 'INICIADO') {
		echo "<button class = 'btn btn-info fa fa-edit' name = 'actualizar_detalle' value = '" . $reg_anterior['id'] . "'></button>";
	}
	echo "</td>
</tr>";

	$ultimo_registro = $reg_anterior['id'];
}

?>
</tbody>
</table>


	</div>

<div class="card-footer" align="center">

<?php
if ($estado_control == 'INICIADO') {

	if (isset($ultimo_registro)) {
		echo "<button  class = 'btn btn-danger' name = 'eliminar_detalle' value = '" . $ultimo_registro . "'>Eliminar Ultimo Registro</button>";
	}

}
?>

</div>

</div>




</div>

</div>


<br>

<input type="hidden" name="id_sorteo_o" id="id_sorteo_o" value="<?php echo $no_sorteo; ?>">

<div class="card" style="margin-left: 10px; margin-right: 10px">
<div class="card-header">
<h3 align="center">Cierre del Control</h3>
</div>

<div class="card-body">

<table class="table table-bordered" >
<tr>
<td  align="center" style="width: 20%">

<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">Contador Inicial: </div>
</div>
<input class = 'form-control' style="" type="text" id="contador_inicial" value="<?php echo $contador_inicial; ?>" readonly>
</div>

</td>
<td  align="center" style="width: 20%">
Sorteo: <?php echo $no_sorteo . " - " . $no_sorteo_2; ?>

</td>
<td  align="center" style="width: 20%">
<?php echo $no_maquina; ?><br>
</td>
<td  align="center" style="width: 20%">
Etapa No. <?php echo $etapa; ?>
</td>
<td  align="center" style="width: 20%">

<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">Contador Final: </div>
</div>
<input class = 'form-control'  type="text" id="contador_final" name="contador_final" value="<?php echo $contador_final; ?>" class="" readonly>
</div>

</td>
</tr>
<tr>
<td align="center">

Fecha: <?php echo $fecha_actual; ?><br><br>

<?php
if ($jornada == 'D') {
	echo "JORNADA DIURNA";
} else {
	echo "JORNADA NOCURNA";
}
?>

</td>
<td align="center" >

<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">Hora Inicio </div>
</div>
<input style="text-align: center" class="form-control" type="time" id="h_i" name="h_i" value="<?php echo $hora_inicial; ?>" >
</div>

<div class="input-group" style="margin-top: 10px">
<div class="input-group-prepend">
<div class="input-group-text">Hora Final </div>
</div>
<input style="text-align: center" class="form-control" type="time" id="h_f" name="h_f" value="<?php echo $hora_final; ?>" >
</div>

</td>

<td align="center">

<div class="input-group" >
<div class="input-group-prepend">
<div class="input-group-text">Billete Inicial: </div>
</div>
<input style="width:50%;text-align: center" class="form-control" id="billete_inicial" type="text"  name="billete_inicial" value="<?php echo $billete_inicial_control; ?>" readonly = 'true' >
</div>


<div class="input-group" style="margin-top: 10px">
<div class="input-group-prepend">
<div class="input-group-text">Billete Final: </div>
</div>
<input style="width:50%;text-align:  center" min="<?php echo $billete_inicial_control; ?>" max="<?php echo $billete_final_grupo; ?>" onblur="calculo_buenos(this.value)" onkeypress="return isNumberKey(event)" class="form-control" type="number"  id="billete_final" name="billete_final" value="<?php echo $billete_final_control; ?>"  >
</div>

</td>


<td align="center">

<div class="input-group" >
<div class="input-group-prepend">
<div class="input-group-text" readonly = 'true'>Billetes Buenos: </div>
</div>
<input style="text-align: center" class="form-control" type="text"  onblur ="f_contador_final(this.value)" id = 'billetes_buenos' name="billetes_buenos" value="<?php echo $billetes_buenos; ?>" readonly >
</div>


<div class="input-group" style="margin-top: 10px">
<div class="input-group-prepend">
<div class="input-group-text">Cont. F. en Maq.: </div>
</div>
<input class="form-control" onkeypress="return isNumberKey(event)" onkeyup = "calculo_s_f(this.value)" style="text-align: center" type="text" id="contador_final_maquina" name="contador_final_maquina" value = '<?php echo $contador_final_maquina; ?>' >
</div>

</td>

<td align="center">

<div class="input-group" >
<div class="input-group-prepend">
<div class="input-group-text">Diferencias: </div>
</div>
<input onkeypress="return isNumberKey(event)" id="sobrante_faltante" type="text" class="form-control" style="text-align: center" id ="diferencia" value = '<?php echo $sobrante_faltante; ?>' readonly>
</div>

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

<button style="visibility:hidden;" class= 'btn btn-primary' id="guardar_detalle" name="guardar_detalle" value=" <?php echo $id ?>"></button>
<button style="visibility:hidden;" class= 'btn btn-primary' id="guardar_cierre" name="guardar_cierre" value=" <?php echo $id ?>"></button>

</form>
