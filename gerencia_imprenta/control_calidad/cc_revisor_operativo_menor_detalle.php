<?php
require '../../template/header.php';

require './cc_revisor_operativo_menor_detalle_db.php';

$id_sorteo = $_GET['id_sort'];
$num_asignado = $_GET['num_asig'];
$id_revisor = $_GET['id_rev'];
$revision = $_GET['revision'];
$num_revision = $revision;

$siguiente_revision = $num_revision + 1;

$info_revisor = mysqli_query($conn, "SELECT * FROM pani_usuarios WHERE id = '$id_revisor' ");
$ob_revisor = mysqli_fetch_object($info_revisor);
$nombre_revisor = $ob_revisor->nombre_completo;

$validar_finalizacion = mysqli_query($conn, "SELECT COUNT(*) as finalizado FROM  cc_produccion_menor WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND numero_revision = '$num_revision' AND estado_revisor = 'FINALIZADA' ");
$ob_validar_finalizacion = mysqli_fetch_object($validar_finalizacion);
$conteo_finalizado = $ob_validar_finalizacion->finalizado;

$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE id =  '$id_sorteo' ");
$ob_sorteo = mysqli_fetch_object($info_sorteo);
$no_sorteo = $ob_sorteo->no_sorteo_men;
$fecha_sorteo = $ob_sorteo->fecha_sorteo;
$cantidad_billetes = $ob_sorteo->series;
$registro_inicial = $ob_sorteo->desde_registro;

if ($revision == 1) {

	$inventario_revisor = mysqli_query($conn, "SELECT * FROM cc_revisores_sorteos_menores WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$id_revisor' AND numero = '$num_asignado' ");
	$historico_revisado = mysqli_query($conn, " SELECT * FROM cc_revisores_sorteos_menores_control WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$id_revisor' AND numero_revision = '2' ");

} else {

	$inventario_revisor = mysqli_query($conn, " SELECT * FROM cc_revisores_sorteos_menores_control WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$id_revisor' AND numero_revision = '$num_revision' AND estado = 'PENDIENTE' ORDER BY serie, numero ASC ");

	$historico_revisado = mysqli_query($conn, " SELECT * FROM cc_revisores_sorteos_menores_control WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$id_revisor' AND numero_revision = '$num_revision' AND estado != 'PENDIENTE' ");

}

$v_numero_revisiones = mysqli_query($conn, "SELECT distinct numero,especial FROM cc_revisores_sorteos_menores_control WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$id_revisor'  ");

if ($v_numero_revisiones === false) {
	echo mysqli_error();
}

$i = 0;
while ($v_numero_fila = mysqli_fetch_array($v_numero_revisiones)) {
	$v_numero[$i] = $v_numero_fila['numero'];
	$i++;
}

$parametros_rango = $id_sorteo . "_" . $id_revisor . "_" . $num_asignado . "_" . $num_revision;

?>


<script type="text/javascript">

function show_revisiones_disponibles(id_sorteo){

revisor = document.getElementById('select_revisor').value;

token = Math.random();
consulta = 'cc_revisor_operativo_cambiar_revisor.php?s='+id_sorteo+"&r="+revisor+"&t=2&token="+token;
$("#resp_revisiones").load(consulta);

}



function add_final(inicial){

if ( inicial !== null && inicial !== '') {
document.getElementById('hasta').value = inicial;
}else{


  swal({
  title: "",
   text: "Debe ingresar el billete incial a reprobar",
    type: "error"
  });

}

}

function validar_rangos(){


decena     = document.getElementById('decena').value;
inicial    = parseInt(document.getElementById('desde').value);
final      = parseInt(document.getElementById('hasta').value);


parametros = document.getElementById('parametros_rango').value;

if ( inicial !== null && inicial !== '' &&  final !== null && final !== '') {

if (inicial > final) {

document.getElementById('reprobar_rango').disabled = false;

  swal({
  title: "",
   text: "La serie final no puede ser menor a la serie inicial",
    type: "error"
  });


}else{

var re = document.getElementById("re").checked;

token = Math.random();
consulta = 'cc_revisor_operativo_menor_validacion.php?p='+parametros+"&decena="+decena+"&bi="+inicial+"&bf="+final+"&re="+re+"&token="+token;
$("#validar_reprobacion").load(consulta);

}


}else{

document.getElementById('reprobar_rango').disabled = false;

  swal({
  title: "",
   text: "Debe ingresar el rango de series a reprobar",
    type: "error"
  });

}

}


function check_all(){

revisar = document.getElementById('billetes_revisar').value;

for (var i = 0; i < revisar; i++) {
document.getElementById('check'+i).checked = true;
}

calcular_reprobados();

}


function uncheck_all(){

revisar = document.getElementById('billetes_revisar').value;

for (var i = 0; i < revisar; i++) {
document.getElementById('check'+i).checked = false;
}

calcular_reprobados();

}


function calcular_reprobados(){

revisar = document.getElementById('billetes_revisar').value;

reprobar = 0;
no_reprobar = 0;
for (var i = 0; i < revisar; i++) {
if (document.getElementById('check'+i).checked == true) {
reprobar++;
}else{
no_reprobar++;
}
}

document.getElementById('billetes_reprobar').value = reprobar;
document.getElementById('billetes_sin_reprobar').value = no_reprobar;

}



function validar_reprobado(){

reprobar = document.getElementById('billetes_reprobar').value;

if (reprobar > 0) {


swal({
  title: "¿Esta seguro?",
  text: "Los billetes seleccionados seran enviados a reposicion nuevamente.",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {

document.getElementById('boton-reprobar').click();

  } else {

  }
});


}else{

swal("Debe seleccionar loteria a reprobar.", "", "error");

}

}


function anular(id){


swal({
  title: "¿Esta seguro de la anulacion?",
  text: "Al anular la reposicion se eliminara esta revision y cualquier revision posterior.",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {

document.getElementById('anular'+id).click();

  } else {

  }
});

}

</script>



<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >Sorteo <?php echo $no_sorteo; ?> | Fecha <?php echo $fecha_sorteo; ?></h2>
<h4 align="center" style="color:black;" >REVISOR <?php echo $nombre_revisor; ?></h4>
<br>
</section>

<br>

<form method="POST">

<input type="hidden" name="parametros_rango" id="parametros_rango" value="<?php echo $parametros_rango; ?>">

<?php

if ($revision == 1) {

	?>


<div class="row">

<div class="col">

<div class="card" style="margin-left: 10px">
<div class="card-header alert-info">
<h4 align="center">
	REPOSICION NO. <?php echo $revision; ?> DE LOTERIA MENOR
<?php
if ($conteo_finalizado > 0) {
		echo "<br> FINALIZADA";
	}
	?>
</h4>
</div>
<div class="card-body">

<table class="table table-bordered">
<tr>
<th colspan="3">LOTERIA ASIGNADA</th>
</tr>
<tr>
<th>Serie Inicial</th><th>Serie Final</th><th>Cantidad Asignada</th>
</tr>

<?php

	$i = 0;
	while ($inventario = mysqli_fetch_array($inventario_revisor)) {

		$billete_inicial = $inventario['serie_inicial'];
		$billete_final = $inventario['serie_final'];
		$cantidad_asignada = $billete_final - $billete_inicial + 1;

		echo "<tr><td>" . $billete_inicial . "</td><td>" . $billete_final . "</td><td>" . number_format($cantidad_asignada) . "</td></tr>";

		$i++;
	}

	?>

</table>

<br>

<table class="table table-bordered">
<tr><th colspan="5">VALIDAR SERIES</th></tr>
<tr>
<th>Decena</th>
<th>Serie Inicial</th>
<th>Serie Final</th>
<th>R. E.</th>
<th>Accion</th>
</tr>

<tr>
<td width="30%">
<select class="form-control" name="decena" id="decena" >

<?php
if (isset($_SESSION['keep_decena'])) {

		if ($_SESSION['keep_decena'] == '00 - 09') {
			echo '<option value="0" selected>00 - 09</option>
			<option value="1">10 - 19</option>
			<option value="2">20 - 29</option>
			<option value="3">30 - 39</option>
			<option value="4">40 - 49</option>
			<option value="5">50 - 59</option>
			<option value="6">60 - 69</option>
			<option value="7">70 - 79</option>
			<option value="8">80 - 89</option>
			<option value="9">90 - 99</option>
			<option value="10">TODAS</option>
			';

		} elseif ($_SESSION['keep_decena'] == '10 - 19') {
			echo '<option value="0" >00 - 09</option>
			<option value="1" selected>10 - 19</option>
			<option value="2">20 - 29</option>
			<option value="3">30 - 39</option>
			<option value="4">40 - 49</option>
			<option value="5">50 - 59</option>
			<option value="6">60 - 69</option>
			<option value="7">70 - 79</option>
			<option value="8">80 - 89</option>
			<option value="9">90 - 99</option>
			<option value="10">TODAS</option>
			';
		} elseif ($_SESSION['keep_decena'] == '20 - 29') {
			echo '<option value="0" >00 - 09</option>
			<option value="1" >10 - 19</option>
			<option value="2" selected>20 - 29</option>
			<option value="3">30 - 39</option>
			<option value="4">40 - 49</option>
			<option value="5">50 - 59</option>
			<option value="6">60 - 69</option>
			<option value="7">70 - 79</option>
			<option value="8">80 - 89</option>
			<option value="9">90 - 99</option>
			<option value="10">TODAS</option>
			';
		} elseif ($_SESSION['keep_decena'] == '30 - 39') {
			echo '<option value="0" >00 - 09</option>
			<option value="1" >10 - 19</option>
			<option value="2">20 - 29</option>
			<option value="3" selected>30 - 39</option>
			<option value="4">40 - 49</option>
			<option value="5">50 - 59</option>
			<option value="6">60 - 69</option>
			<option value="7">70 - 79</option>
			<option value="8">80 - 89</option>
			<option value="9">90 - 99</option>
			<option value="10">TODAS</option>
			';
		} elseif ($_SESSION['keep_decena'] == '40 - 49') {
			echo '<option value="0" >00 - 09</option>
			<option value="1" >10 - 19</option>
			<option value="2">20 - 29</option>
			<option value="3">30 - 39</option>
			<option value="4" selected>40 - 49</option>
			<option value="5">50 - 59</option>
			<option value="6">60 - 69</option>
			<option value="7">70 - 79</option>
			<option value="8">80 - 89</option>
			<option value="9">90 - 99</option>
			<option value="10">TODAS</option>
			';
		} elseif ($_SESSION['keep_decena'] == '50 - 59') {
			echo '<option value="0" >00 - 09</option>
			<option value="1" >10 - 19</option>
			<option value="2">20 - 29</option>
			<option value="3">30 - 39</option>
			<option value="4">40 - 49</option>
			<option value="5" selected>50 - 59</option>
			<option value="6">60 - 69</option>
			<option value="7">70 - 79</option>
			<option value="8">80 - 89</option>
			<option value="9">90 - 99</option>
			<option value="10">TODAS</option>
			';
		} elseif ($_SESSION['keep_decena'] == '60 - 69') {
			echo '<option value="0" >00 - 09</option>
			<option value="1" >10 - 19</option>
			<option value="2">20 - 29</option>
			<option value="3">30 - 39</option>
			<option value="4">40 - 49</option>
			<option value="5">50 - 59</option>
			<option value="6" selected>60 - 69</option>
			<option value="7">70 - 79</option>
			<option value="8">80 - 89</option>
			<option value="9">90 - 99</option>
			<option value="10">TODAS</option>
			';
		} elseif ($_SESSION['keep_decena'] == '70 - 79') {
			echo '<option value="0" >00 - 09</option>
			<option value="1" >10 - 19</option>
			<option value="2">20 - 29</option>
			<option value="3">30 - 39</option>
			<option value="4">40 - 49</option>
			<option value="5">50 - 59</option>
			<option value="6">60 - 69</option>
			<option value="7" selected>70 - 79</option>
			<option value="8">80 - 89</option>
			<option value="9">90 - 99</option>
			<option value="10">TODAS</option>
			';
		} elseif ($_SESSION['keep_decena'] == '80 - 89') {
			echo '<option value="0" >00 - 09</option>
			<option value="1" >10 - 19</option>
			<option value="2">20 - 29</option>
			<option value="3">30 - 39</option>
			<option value="4">40 - 49</option>
			<option value="5">50 - 59</option>
			<option value="6">60 - 69</option>
			<option value="7">70 - 79</option>
			<option value="8" selected>80 - 89</option>
			<option value="9">90 - 99</option>
			<option value="10">TODAS</option>
			';
		} elseif ($_SESSION['keep_decena'] == '90 - 99') {
			echo '<option value="0" >00 - 09</option>
			<option value="1" >10 - 19</option>
			<option value="2">20 - 29</option>
			<option value="3">30 - 39</option>
			<option value="4">40 - 49</option>
			<option value="5">50 - 59</option>
			<option value="6">60 - 69</option>
			<option value="7">70 - 79</option>
			<option value="8">80 - 89</option>
			<option value="9" selected>90 - 99</option>
			<option value="10">TODAS</option>
			';
		} elseif ($_SESSION['keep_decena'] == 'TODAS') {
			echo '<option value="0" >00 - 09</option>
			<option value="1" >10 - 19</option>
			<option value="2">20 - 29</option>
			<option value="3">30 - 39</option>
			<option value="4">40 - 49</option>
			<option value="5">50 - 59</option>
			<option value="6">60 - 69</option>
			<option value="7">70 - 79</option>
			<option value="8">80 - 89</option>
			<option value="9" selected>90 - 99</option>
			<option value="10">TODAS</option>
			';
		}

	} else {
		?>

<option value="0" >00 - 09</option>
<option value="1" >10 - 19</option>
<option value="2">20 - 29</option>
<option value="3">30 - 39</option>
<option value="4">40 - 49</option>
<option value="5">50 - 59</option>
<option value="6">60 - 69</option>
<option value="7">70 - 79</option>
<option value="8">80 - 89</option>
<option value="9">90 - 99</option>
<option value="10">TODAS</option>

<?php
}
	?>
</select>
</td>
<td width="30%"><input type  = 'number' min="0" class="form-control" name="desde" id="desde" onblur="add_final(this.value)" autofocus ></input></td>
<td  width="30%"><input type = 'number' min="0" class="form-control" name="hasta" id="hasta" ></input></td>
<td><input type= 'checkbox' name="re" id="re" class="form-control" style = 'width: 30px; height: 30px;' ></input></td>
<td align="center">
<?php
if ($conteo_finalizado > 0) {
		echo '<button class = "btn btn-danger" id="btn-validar" onclick=""  name="reprobar_rango" disabled>Validar</button>';
	} else {
		echo '<span class = "btn btn-danger" id="btn-validar" onclick="validar_rangos()"  name="reprobar_rango" >Validar</span>';
	}
	?>

</td>
</tr>

</table>


</div>
</div>

</div>


<div class="col">

<div class="card " style="margin-right: 10px" >
<div class="card-header alert-danger">
<h4>DETALLE DE SERIES A REPONER</h4>
</div>
<div class="card-body">
<div id = 'validar_reprobacion' style = 'height: 300px; overflow-y: scroll;'></div>
</div>
<div class="card-footer" >
<button type="submit" class = 'btn btn-danger'  id="reprobar_rango" name="reprobar_rango" disabled>Reprobar</button>
</div>
</div>


</div>

</div>

<br>

<div class="row">

<div class="col">
<div class="card" style="margin-left: 10px; margin-right: 10px">
<div class="card-header">
<h4 align="center">HISTORICO DE REPOSICION 1</h4>
</div>
<div class="card-body">
<table id="table_id1" class="table table-bordered">
<thead>
<tr>
<th>Decena</th>
<th>Serie</th>
<th>Registro</th>
<th>R. E.</th>
<th>Estado Revision <?php echo $siguiente_revision; ?></th>
<th width="15%">Accion</th>
</tr>
</thead>
<tbody>

<?php

	$count_re = 0;
	$count_normal = 0;

	while ($reg_historico = mysqli_fetch_array($historico_revisado)) {

		$concat_anulacion = $reg_historico['numero'] . $reg_historico['serie'];

		if ($reg_historico['especial'] == 'SI') {
			$count_re++;
		} else {
			$count_normal++;
		}

		echo "<tr>";
		echo "<td>" . $reg_historico['numero'] . "0 - " . $reg_historico['numero'] . "9</td>";
		echo "<td>" . $reg_historico['serie'] . "</td>";
		echo "<td>" . $reg_historico['registro'] . "</td>";
		echo "<td>" . $reg_historico['especial'] . "</td>";
		echo "<td>" . $reg_historico['estado'] . "</td>";

		if ($conteo_finalizado > 0) {
			echo "<td align = 'center'><button class = 'btn btn-danger' disabled>Anular Reposicion</button></td>";
		} else {

			?>

<td align = 'center'>
<span class = 'btn btn-danger' onclick = "anular('<?php echo $concat_anulacion; ?>')" >Anular Reposicion</span>
<button style = 'visibility:hidden' type = 'submit' id = 'anular<?php echo $concat_anulacion; ?>' name = 'anular_reposicion' value = '<?php echo $concat_anulacion; ?>' ></button>
</td>

<?php

		}

		echo "</tr>";
	}

	$tt_reposiciones = $count_re + $count_normal;

	?>

</tbody>
</table>


Total reposiciones normales: <b><?php echo $count_normal; ?></b><br>
Total reposiciones especiales: <b><?php echo $count_re; ?></b><br>
Total reposiciones : <b><?php echo $tt_reposiciones; ?></b>

</div>
<div class="card-footer" align="center">
<?php
if ($conteo_finalizado > 0) {
		echo "<div class = 'alert alert-danger'>Ya finalizo la reposicion 1 </div>";
	} else {
//echo '<button type="submit" name="finalizar_reposicion" class="btn btn-success"> Finalizar Reposicion 1</button>';
	}
	?>
</div>
</div>

</div>
</div>

<br><br><br>

<?php

} else {

////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////// REVISIONES POSTERIORES A 1 /////////////////////////////////////////////

	$revision_anterior = $num_revision - 1;

	?>


<div class="row">

<div class="col">
<div class="card" style="margin-left: 10px; margin-right: 10px">
<div class="card-header alert alert-info" >
<h4>REVISION PARA ENVIO A REPOSICION  <?php echo $num_revision; ?></h4>
</div>
<div class="card-body" style="height: 400px ; overflow-y: scroll;">
<table  class="table table-bordered table-striped">
<thead>
<tr>
<th>Reponer</th>
<th>Numero</th>
<th>Serie</th>
<th>Registro</th>
<th>R. E. </th>
</tr>
</thead>
<tbody>

<?php

	$conteo_pendiente = 0;
	while ($reg_inventario = mysqli_fetch_array($inventario_revisor)) {

		$desc_decena = $reg_inventario['numero'] . "0 - " . $reg_inventario['numero'] . "9";

		echo "<input type = 'hidden' name = 'id_reprobacion" . $conteo_pendiente . "' value = '" . $reg_inventario['id'] . "' >";

		echo "<tr>";
		echo "<td width = '10%' align = 'center'><input type = 'checkbox' name = 'check" . $conteo_pendiente . "'    id = 'check" . $conteo_pendiente . "'      onchange = 'calcular_reprobados()'  class = 'form-control' style = 'width: 30px; height: 30px;' ></td>";

		echo "<td ><input type = 'hidden'     name = 'decimo" . $conteo_pendiente . "'  id = 'numero' class = 'form-control'  value = '" . $reg_inventario['numero'] . "' readonly><input type = 'text'     name = 'Decena" . $conteo_pendiente . "'  id = 'numero' class = 'form-control'  value = '" . $desc_decena . "' readonly></td>";

		echo "<td ><input type = 'text'     name = 'serie" . $conteo_pendiente . "'  id = 'serie' class = 'form-control'  value = '" . $reg_inventario['serie'] . "' readonly></td>";
		echo "<td ><input type = 'text'     name = 'registro" . $conteo_pendiente . "' id = 'registro' class = 'form-control' value = '" . $reg_inventario['registro'] . "' readonly></td>";
		echo "<td > <input type = 'text'     name = 'especial" . $conteo_pendiente . "' id = 'especial' class = 'form-control' value = '" . $reg_inventario['especial'] . "' readonly> </td>";
		echo "</tr>";

		$conteo_pendiente++;
	}

	?>

<input type="hidden" name="tt_revision" value="<?php echo $conteo_pendiente; ?>" >

</tbody>
</table>
</div>

<div class="card-footer" align="center">
<span class="btn btn-secondary" onclick="check_all()"  id="check-all">Seleccionar todos</span>
<span class="btn btn-secondary" onclick="uncheck_all()"  id="uncheck-all"> Deseleccionar todos</span>
</div>

</div>

</div>

<div class="col">

<div class="card" style="margin-right: 10px;  height: 525px ; ">
<div class="card-header alert alert-danger" >
<h4>DETALLE REPOSICION <?php echo $num_revision ?></h4>
</div>

<div class="card-body">

<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span style="width: 200px" class="input-group-text">Billetes a Revisar: </span></div>
<input class="form-control" type="text" name = 'billetes_revisar' id = 'billetes_revisar' value="<?php echo $conteo_pendiente; ?>" readonly>
</div>


<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span style="width: 200px" class="input-group-text">Billetes a Reprobar: </span></div>
<input class="form-control" type="text" name = 'billetes_reprobar' id = 'billetes_reprobar' value = '0' readonly>
</div>


<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span style="width: 200px" class="input-group-text">Billetes Sin reprobar: </span></div>
<input class="form-control" type="text" name = 'billetes_sin_reprobar' id = 'billetes_sin_reprobar' value = '<?php echo $conteo_pendiente; ?>' readonly>
</div>


</div>

<div class="card-footer" >
	<span class="btn btn-primary" onclick="validar_reprobado()">Reprobar Para Reposicion <?php echo $num_revision; ?></span>
	<button type="submit" name="reprobar_nuevamente" id="boton-reprobar"  style="visibility: hidden;" ></button>
</div>

</div>

</div>

</div>



<br>

<div class="row">
<div class="col">

<div class="card" style="margin-left: 10px; margin-right: 10px;">
<div class="card-header">
<h4 align="center">HISTORICO DE REPOSICION <?php echo $num_revision; ?></h4>
</div>
<div class="card-body">
<table id="table_id1" class="table table-bordered">
<thead>
<tr>
<th>Decena</th>
<th>Serie</th>
<th>Registro</th>
<th>R. E.</th>
<th>Estado</th>
<th width="15%">Accion</th>
</tr>
</thead>
<tbody>

<?php

	$count_re = 0;
	$count_normal = 0;

	while ($reg_historico = mysqli_fetch_array($historico_revisado)) {

		if ($reg_historico['especial'] == 'SI') {
			$count_re++;
		} else {
			$count_normal++;
		}

		$concat_anulacion = $reg_historico['numero'] . $reg_historico['serie'];

		echo "<tr>";
		echo "<td>" . $reg_historico['numero'] . "0 - " . $reg_historico['numero'] . "9</td>";
		echo "<td>" . $reg_historico['serie'] . "</td>";
		echo "<td>" . $reg_historico['registro'] . "</td>";
		echo "<td>" . $reg_historico['especial'] . "</td>";
		echo "<td>" . $reg_historico['estado'] . "</td>";

		if ($conteo_finalizado > 0) {
			echo "<td align = 'center'><button class = 'btn btn-danger' disabled>Anular Reposicion</button></td>";
		} else {

			?>

<td align = 'center'>
<span class = 'btn btn-danger' onclick = "anular('<?php echo $concat_anulacion; ?>')" >Anular Reposicion</span>
<button style = 'visibility:hidden' type = 'submit' id = 'anular<?php echo $concat_anulacion; ?>' name = 'anular_reposicion' value = '<?php echo $concat_anulacion; ?>' ></button>
</td>

<?php

		}

		echo "</tr>";
	}

	$tt_reposiciones = $count_re + $count_normal;

	?>

</tbody>
</table>

Total reposiciones normales: <b><?php echo $count_normal; ?></b><br>
Total reposiciones especiales: <b><?php echo $count_re; ?></b><br>
Total reposiciones : <b><?php echo $tt_reposiciones; ?></b>

</div>
<div class="card-footer" align="center">
<?php
if ($conteo_finalizado > 0) {
		echo "<div class = 'alert alert-danger'>Ya finalizo la reposicion " . $num_revision . " </div>";
	} else {

/*
$c_finalizado_anterior = mysqli_query($conn, "SELECT id FROM cc_produccion_menor WHERE id_sorteo = '$id_sorteo' AND numero_revision = '$revision_anterior' ");

if (mysqli_num_rows($c_finalizado_anterior) > 0) {
echo '<button type="submit" name="finalizar_reposicion" class="btn btn-success"> Finalizar Reposicion '.$num_revision.'</button>';
}else{
echo "<div class = 'alert alert-danger'>Debe finalizar la revision anterior</div>";
}
 */

	}
	?>
</div>
</div>

</div>
</div>



<?php

/////////////////////////////// REVISIONES POSTERIORES A 1 /////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////

}

//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////// SELECT OTRO REVISOR /////////////////////////////////////
$revisores_select = mysqli_query($conn, "SELECT * FROM pani_usuarios WHERE estados_id = '1'  AND areas_id = '5' ORDER BY nombre_completo ASC ");

?>

<div class = "row">
<div class = 'col'>
<div class = 'card' >
<div class="card-header bg-dark text-white">
	SELECCION DE OTRO REVISOR
</div>
<div class="card-body">

<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><span  class="input-group-text">Revisor: </span></div>
<select name="select_revisor" id="select_revisor" class="form-control" >
<?php
while ($revisor_select = mysqli_fetch_array($revisores_select)) {
	echo "<option value='" . $revisor_select['id'] . "'>" . $revisor_select['nombre_completo'] . "</option>";
}
?>
</select>

<div class="input-group-append"><span class = "btn btn-primary" onclick="show_revisiones_disponibles('<?php echo $id_sorteo; ?>')">Seleccionar</span></div>

</div>

</div>
</div>
</div>

<div class = 'col' id = 'resp_revisiones'>

</div>

</div>


<?php
//////////////////////////// SELECT OTRO REVISOR /////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
?>


</form>