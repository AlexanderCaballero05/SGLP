<?php
require '../../template/header.php';
require './produccion_control_mayor_db.php';

$maquinas = mysqli_query($conn, "SELECT * FROM pro_maquinas ");
$select_sorteos = mysqli_query($conn, "SELECT * FROM sorteos_mayores  WHERE produccion = 'INICIADO CP' ORDER BY no_sorteo_may DESC ");

////////////////////////////////////////////////////////////////////////
///////////////////// CONSULTA EMPLEADOS ORACLE ////////////////////////
$conn2 = oci_connect('cide', 'pani2017', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.15.102)(PORT=1521)))(CONNECT_DATA=(SID=dbpani)(SERVER = DEDICATED)(SERVICE_NAME = DBPANITG)))');

if ($conn2 == FALSE) {
	$e = oci_error();
	$msg_error = "ERROR DE CONEXION ORACLE: " . $e['message'] . "<br>";
	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	exit;
}

$consulta_empleados = oci_parse($conn2, "SELECT CEDULA ,NOMBRE  FROM PL_EMPLEADOS  WHERE DEPTO = '2.1' AND ESTADO = 'A'  ");
oci_execute($consulta_empleados);

$i = 0;
while (($reg_empleado = oci_fetch_array($consulta_empleados, OCI_BOTH))!= false) {

	$prensistas_produccion[$i][0] = $reg_empleado['CEDULA'];
	$prensistas_produccion[$i][1] = $reg_empleado['NOMBRE'];
$i++;
}

oci_close($conn2);
/*
$prensistas_produccion[0][0] = "0000-0000-00000";
$prensistas_produccion[0][1] = "PRUEBA PRENSISTA 1";
$prensistas_produccion[1][0] = "0000-0000-00000";
$prensistas_produccion[1][1] = "PRUEBA PRENSISTA 2";
$prensistas_produccion[2][0] = "0000-0000-00000";
$prensistas_produccion[2][1] = "PRUEBA PRENSISTA 3";
*/
///////////////////// CONSULTA EMPLEADOS ORACLE ////////////////////////
////////////////////////////////////////////////////////////////////////

date_default_timezone_set('America/Tegucigalpa');
$fecha_actual = date('Y-m-d');

$v_fecha = explode('-', $fecha_actual);

$year = $v_fecha[0];
$month = $v_fecha[1];
$day = $v_fecha[2];

?>



<script type="text/javascript">




jQuery(function($){
$("#billete_inicial").mask("99999", { placeholder: "_____" });
$("#billete_inicial2").mask("99999", { placeholder: "_____" });
});







function cargar_prensistas(){

mySelect = document.getElementById('select_prensistas');
$concat_prensistas = "";

for (var i = 0; i < mySelect.options.length; i++) {
if (mySelect.options[i].selected){

nombre_revisor = mySelect.options[i].text;
if ($concat_prensistas != '') {
$concat_prensistas = $concat_prensistas+" - "+nombre_revisor;
}else{
$concat_prensistas = nombre_revisor;
}

}

}

document.getElementById('prensistas').value = $concat_prensistas;

}










function fun_etapa(e){
if (e != 2) {
document.getElementById('billete_inicial').setAttribute("disabled", true);
document.getElementById('billete_inicial2').setAttribute("disabled", true);
document.getElementById('grupo_inicial').setAttribute("disabled", true);
document.getElementById('grupo_final').setAttribute("disabled", true);
}else{
document.getElementById('billete_inicial').removeAttribute("disabled");
document.getElementById('billete_inicial2').removeAttribute("disabled");
document.getElementById('grupo_inicial').removeAttribute("disabled");
document.getElementById('grupo_final').removeAttribute("disabled");
}
}


function validar(){

sorteo1  	 = document.getElementById('sorteo1').value;
sorteo2 	 = sorteo1;

cont_inicial = document.getElementById('contador_inicial').value;
maquina 	 = document.getElementById('id_maquina').value;
billete 	 = document.getElementById('billete_inicial').value;
operador 	 = document.getElementById('id_operador').value;
etapa 		 = document.getElementById('etapa').value;
fecha 		 = document.getElementById('fecha_inicial').value;
jornada 	 = document.getElementById('jornada').value;
hora_inicial = document.getElementById('h_i').value;
prensistas   = document.getElementById('prensistas').value;



if (prensistas === '') {

swal("Debe seleccionar los prensistas en el control.", "", "error");

}else if (cont_inicial == '') {
document.getElementById('contador_inicial').focus();
swal("Debe ingresar el contador inicial del control", "", "error");
}else if(maquina == ''){
document.getElementById('id_maquina').focus();
swal("Debe seleccionar una maquina", "", "error");
}else if(operador == ''){
document.getElementById('id_operador').focus();
swal("Debe seleccionar un controlador de produccion", "", "error");
}else if(etapa == ''){
document.getElementById('etapa').focus();
swal("Debe seleccionar una etapa", "", "error");
}else if(fecha == ''){
document.getElementById('fecha_inicial').focus();
swal("Debe seleccionar una fecha", "", "error");
}else if(jornada == ''){
radio1.focus();
swal("Debe seleccionar una jornada", "", "error");
}else if(hora_inicial == ''){
document.getElementById('h_i').focus();
swal("Debe seleccionar una hora inicial", "", "error");
}else{

if (etapa == '2') {

var n = billete.length;

if ( billete == '' || n < 5) {

document.getElementById('billete_inicial').focus();
swal("Debe ingresar un billete inicial, asegurese de ingresar los 5 digitos de este.", "", "error");

}else{

document.getElementById('iniciar_control').click();

}

}else{

document.getElementById('iniciar_control').click();

}

}


}

</script>

<form method="POST">

<br>

<ul class="nav nav-tabs" style="margin-left: 10px; margin-right: 10px">
  <li class="nav-item">
    <a class="nav-link active" style="background-color:#ededed;">Controles Nuevos</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="./produccion_control_mayor_historico.php" >Historico de Controles</a>
  </li>
</ul>




<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >GESTION DE CONTROLES DE PRODUCCION MAYOR</h2>
<br>
</section>

<br>



<?php

//if (isset($_POST['seleccionar']) || isset($_SESSION['id_sorteo_control_mayor'])) {

$contador_final_maximo = mysqli_query($conn, "SELECT MAX(contador_final_maquina) as max FROM pro_control  ");
$ob_max = mysqli_fetch_object($contador_final_maximo);
$contador_maximo = $ob_max->max;

$b = 0;
$i = 0;
$grupo_inicial = 0;

$operadores = mysqli_query($conn, "SELECT * FROM pani_usuarios WHERE  areas_id = '4' AND estados_id = '1' ");

date_default_timezone_set('America/El_Salvador');
$fecha_actual = date('Y-m-d');

$controles_iniciados = mysqli_query($conn, "SELECT a.contador_final,a.contador_final_maquina,a.id,a.fecha,a.id_orden,a.id_orden_2,a.etapa,a.maquina,a.contador_inicial,a.jornada,a.billete_inicial,a.estado,b.maquina as nombre_maquina, c.no_sorteo_may FROM pro_control as a INNER JOIN pro_maquinas as b INNER JOIN sorteos_mayores as c ON a.maquina = b.id AND a.id_orden = c.id WHERE estado = 'INICIADO' ORDER BY a.id DESC ");

?>

<div class="card" style="margin-right: 10px; margin-left: 10px" >
<div class="card-header bg-primary text-white"><h3 align="center">Creacion de controles de produccion Mayor<h3></div>


<div class="card-body">

<input type="hidden" name="id_sorteo_oculto" value="<?php echo "$id_sorteo"; ?>" >

<table class="table table-bordered table-responsive-sm" >
<tr>
<td width="25%" align="center">

<div class="input-group">
<div class="input-group-prepend"><span class="input-group-text">Contador Inicial:</span></div>
 <input class="form-control"  type="text" onblur="validar_contador(this.value)" onkeypress="return isNumberKey(event)" name="contador_inicial" id="contador_inicial"  value="<?php echo $contador_maximo; ?>"  >
</div>

</td>
<td width="25%" align="center">

<div class="input-group"  >
<div class="input-group-prepend"><span  class="input-group-text">Sorteo: </span></div>
<select class = "form-control" name = "sorteo1" id = "sorteo1" >

<?php
while ($r_sorteo1 = mysqli_fetch_array($select_sorteos)) {
	echo "<option value = '" . $r_sorteo1['id'] . "'>" . $r_sorteo1['id'] . "</option>";
}
?>

</select>
</div>

</td>
<td width="25%" align="center">

<div class="input-group">
<div class="input-group-prepend"><span class="input-group-text">Maquina:</span></div>
<select class="form-control" id="id_maquina" name="id_maquina" style=""  >
<?php
while ($row1 = mysqli_fetch_array($maquinas)) {
	echo '<option value = "' . $row1['id'] . '" >' . $row1['maquina'] . '</option>';
}
?>
</select>
</div>

 </td>
<td width="25%" align="center">

<div class="input-group">
<div class="input-group-prepend"><div class="input-group-text">Controlador:</div></div>
<select class="form-control" id="id_operador" name="id_operador" style=""  >
<?php
while ($operador = mysqli_fetch_array($operadores)) {
	echo '<option value = "' . $operador['id'] . '" >' . $operador['nombre_completo'] . '</option>';
}
?>
</select>
</div>


</td>
</tr>

<tr>
<td width="25%" align="center">

<div class="input-group">
<div class="input-group-prepend"><div class="input-group-text">Etapa</div></div>
<select onblur="fun_etapa(this.value)" class="form-control" id="etapa" name="etapa" style="width:50%"  >
<option value = "1" >Etapa 1</option>
<option value = "2" >Etapa 2</option>
<option value = "5" >Etapa 2 Para Reposición</option>
<option value = "3" >Etapa 3</option>
<option value = "4" >Reposicion</option>
</select>
</div>


</td>
<td width="25%" align="center">

<div class="input-group">
<div class="input-group-prepend"><div class="input-group-text">Billete Inicial</div></div>
<input class="form-control"   id="billete_inicial" onkeypress="return isNumberKey(event)" type="number" name="billete_inicial" disabled>

<input class="form-control"   id="billete_inicial2" onkeypress="return isNumberKey(event)" type="number" name="billete_inicial2" disabled>
</div>

</td>
<td width="25%" align="center">

<div class="input-group">
<div class="input-group-prepend"><div class="input-group-text">Fecha Inicial</div></div>
<input type='date' id ="fecha_inicial" name = "fecha_inicial" class="form-control">
</div>

</td>
<td width="25%" align="center">

<div class="input-group">
<div class="input-group-prepend"><div class="input-group-text">Jornada</div></div>
<select name="jornada" id="jornada" class="form-control">
<option value="D">Diurna</option>
<option value="N">Nocturna</option>
</select>
<input type="time" id = 'h_i' name = 'h_i' >
</div>


</td>
</tr>




<tr>
<td colspan="4">

<div class="input-group">
<div class="input-group-prepend"><span class="input-group-text">Prensistas</span></div>
<input class="form-control" type="text" name="prensistas"  id="prensistas" readonly="true">
<div class="input-group-apend">
<span class="btn btn-primary" data-toggle="modal" data-target="#modal-prensistas">Seleccionar Prensistas</span>
</div>
</div>


</td>
</tr>




</table>


</div>

<div class="card-footer">

<p align="center">
<span id="boton_validar" onclick="validar()"  class="btn btn-primary" >Crear Control</span>
<br>
</p>

</div>
</div>


<button type="submit" style="visibility:hidden" id="iniciar_control" name="iniciar_control" class="btn btn-primary" >Crear Control</button>




<br>
<div class="card" style="margin-right: 10px; margin-left: 10px" >
<div class="card-header bg-success text-white">
<h3 align="center">Controles Iniciados</h3>
</div>

<div class="card-body">
<table width="100%" class="table table-bordered">
<tr>
	<th>Sorteo</th>
	<th>Fecha</th>
	<th>Maquina</th>
	<th>Etapa</th>
	<th>Contador Inicial</th>
	<th>Contador Final</th>
	<th>F/S</th>
	<th>Billete Inicial</th>
	<th>Estado</th>
	<th>Accion</th>

</tr>
<?php

while ($control = mysqli_fetch_array($controles_iniciados)) {

	if ($control['contador_final_maquina'] != '') {
		$faltante_sobrante = $control['contador_final'] - $control['contador_final_maquina'];
	} else {
		$faltante_sobrante = '';
	}

	echo "<tr>";
	echo "<td>" . $control['no_sorteo_may'] . " - " . $control['id_orden_2'] . "</td>";
	echo "<td>" . $control['fecha'] . "</td>";
	echo "<td>" . $control['maquina'] . "</td>";
	if ($control['etapa'] == 5) {
		echo "<td>2 Para Reposicion</td>";
	} elseif ($control['etapa'] == 4) {
		echo "<td>Reposicion</td>";
	} else {
		echo "<td>" . $control['etapa'] . "</td>";
	}
	echo "<td>" . $control['contador_inicial'] . "</td>";
	echo "<td>" . $control['contador_final_maquina'] . "</td>";
	echo "<td>" . $faltante_sobrante . "</td>";
	echo "<td>" . $control['billete_inicial'] . "</td>";
	echo "<td>" . $control['estado'] . "</td>";

	if ($control['etapa'] == 4) {

		echo "<td align = 'center'>
		<a href = 'produccion_control_mayor_detalle_4.php?id=" . $control['id'] . "' class = 'btn btn-primary'>Ingresar</a>
		<button name = 'eliminar_control' value = '" . $control['id'] . "' class = 'btn btn-danger'>Eliminar</button>
	</td>";

		echo "</tr>";

	} else {

		if ($control['etapa'] == 5) {

			echo "<td align = 'center'>
		<a href = 'produccion_control_mayor_detalle_5.php?id=" . $control['id'] . "' class = 'btn btn-primary'>Ingresar</a>
		<button name = 'eliminar_control' value = '" . $control['id'] . "' class = 'btn btn-danger'>Eliminar</button>
	</td>";

			echo "</tr>";

		} elseif ($control['etapa'] != 2) {

			echo "<td align = 'center'>
		<a href = 'produccion_control_mayor_detalle_1.php?id=" . $control['id'] . "' class = 'btn btn-primary'>Ingresar</a>
		<button name = 'eliminar_control' value = '" . $control['id'] . "' class = 'btn btn-danger'>Eliminar</button>
	</td>";

			echo "</tr>";

		} else {

			echo "<td align = 'center'>
		<a href = 'produccion_control_mayor_detalle.php?id=" . $control['id'] . "' class = 'btn btn-primary'>Ingresar</a>
		<button name = 'eliminar_control' value = '" . $control['id'] . "' class = 'btn btn-danger'>Eliminar</button>
	</td>";

			echo "</tr>";

		}
	}

}

?>

</table>
<br>

</div>
</div>











<!-- MODAL PRENSISTAS -->


<div class="modal fade" role="dialog" tabindex="-1" id="modal-prensistas">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header" id="modal-header" style="background-color:#e7e7e7;">
<h4 class="text-center modal-title" id="modal-heading" style="width:100%;">PRENSISTAS</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
<div class="modal-body" style="background-color:#f8f8f8;">

<select class="form-control" onclick="cargar_prensistas()" name="select_prensistas[]" id="select_prensistas" multiple="true"  size="10">

<?php
$i = 0;
while (isset($prensistas_produccion[$i][0])) {

	echo "<option value = '" . $prensistas_produccion[$i][0] . "%" . $prensistas_produccion[$i][1] . "'>" . $prensistas_produccion[$i][1] . "</option>";

	$i++;
}

?>

</select>

</div>
</div>
</div>
</div>


<!-- MODAL PRENSISTAS -->









</form>
