<?php
require '../../template/header.php';
require './cc_revisor_operativo_mayor_detalle_db.php';

$id_sorteo = $_GET['id_sort'];
$num_asignado = $_GET['num_asig'];
$id_revisor = $_GET['id_rev'];
$revision = $_GET['revision'];
$num_revision = $revision;

$info_revisor = mysqli_query($conn, "SELECT * FROM pani_usuarios WHERE id = '$id_revisor' ");
$ob_revisor = mysqli_fetch_object($info_revisor);
$nombre_revisor = $ob_revisor->nombre_completo;

$validar_finalizacion = mysqli_query($conn, "SELECT COUNT(*) as finalizado FROM  cc_produccion_mayor WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND numero_revision = '$num_revision' AND estado_revisor = 'FINALIZADA' ");
$ob_validar_finalizacion = mysqli_fetch_object($validar_finalizacion);
$conteo_finalizado = $ob_validar_finalizacion->finalizado;

$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE id =  '$id_sorteo' ");
$ob_sorteo = mysqli_fetch_object($info_sorteo);
$no_sorteo = $ob_sorteo->no_sorteo_may;
$fecha_sorteo = $ob_sorteo->fecha_sorteo;
$cantidad_billetes = $ob_sorteo->cantidad_numeros;
$registro_inicial = $ob_sorteo->desde_registro;
$patron_salto = $ob_sorteo->patron_salto;

$parametros_mayor = mysqli_query($conn, "SELECT * FROM sorteos_mayores_produccion where id_sorteo = '$id_sorteo' ");

$i = 1;
while ($reg = mysqli_fetch_array($parametros_mayor)) {
	$v_salto[$i] = $reg['salto'];
	$i++;
}

if ($revision == 1) {

	$inventario_revisor = mysqli_query($conn, "SELECT * FROM cc_revisores_sorteos_mayores WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$id_revisor' AND numero = '$num_asignado' ");
	$historico_revisado = mysqli_query($conn, " SELECT * FROM cc_revisores_sorteos_mayores_control WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$id_revisor' AND numero_revision = '2' ");

} else {

	$inventario_revisor = mysqli_query($conn, " SELECT * FROM cc_revisores_sorteos_mayores_control WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$id_revisor' AND numero_revision = '$num_revision' AND estado = 'PENDIENTE' ORDER BY billete ");

	$historico_revisado = mysqli_query($conn, " SELECT * FROM cc_revisores_sorteos_mayores_control WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$id_revisor' AND numero_revision = '$num_revision' AND estado != 'PENDIENTE' ");

}

$v_numero_revisiones = mysqli_query($conn, "SELECT distinct billete,especial FROM cc_revisores_sorteos_mayores_control WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$id_revisor'  ");

if ($v_numero_revisiones === false) {
	echo mysqli_error();
}

$i = 0;
while ($v_numero_fila = mysqli_fetch_array($v_numero_revisiones)) {
	$v_numero[$i] = $v_numero_fila['billete'];
	$i++;
}

$parametros_rango = $id_sorteo . "_" . $id_revisor . "_" . $num_asignado . "_" . $num_revision;

?>


<script type="text/javascript">


function show_revisiones_disponibles(id_sorteo){

revisor = document.getElementById('select_revisor').value;

token = Math.random();
consulta = 'cc_revisor_operativo_cambiar_revisor.php?s='+id_sorteo+"&r="+revisor+"&t=1&token="+token;
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


inicial    = parseInt(document.getElementById('desde').value);
final      = parseInt(document.getElementById('hasta').value);

parametros = document.getElementById('parametros_rango').value;


if ( inicial !== null && inicial !== '' &&  final !== null && final !== '') {

if (inicial > final) {

document.getElementById('reprobar_rango').disabled = false;

  swal({
  title: "",
   text: "El billete final no puede ser menor al billete inicial",
    type: "error"
  });


}else{

var re = document.getElementById("re").checked;

token = Math.random();
consulta = 'cc_revisor_operativo_mayor_validacion.php?p='+parametros+"&bi="+inicial+"&bf="+final+"&re="+re+"&token="+token;
$("#validar_reprobacion").load(consulta);

}


}else{

document.getElementById('reprobar_rango').disabled = false;

  swal({
  title: "",
   text: "Debe ingresar el rango de billetes a reprobar",
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
  text: "Al anular la reposicion se eliminara esta revision y cualquier revision posterior del billete seleccionado.",
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
<h2 align="center" style="color:black;" >SORTEO <?php echo $no_sorteo; ?> | FECHA <?php echo $fecha_sorteo; ?></h2>
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
	REPOSICION NO. <?php echo $revision; ?> DE LOTERIA MAYOR
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
<th>Billete Inicial</th><th>Billete Final</th><th>Cantidad Asignada</th>
</tr>

<?php

	$i = 0;
	while ($inventario = mysqli_fetch_array($inventario_revisor)) {

		$billete_inicial = $inventario['billete_inicial'];
		$billete_final = $inventario['billete_final'];
		$cantidad_asignada = $billete_final - $billete_inicial + 1;

		echo "<tr><td>" . $billete_inicial . "</td><td>" . $billete_final . "</td><td>" . number_format($cantidad_asignada) . "</td></tr>";

		$i++;
	}

	?>

</table>

<br>

<table class="table table-bordered">
<tr><th colspan="4">VALIDAR BILLETES</th></tr>
<tr>
<th>Billete Inicial</th>
<th>Billete Final</th>
<th>R. E.</th>
<th>Accion</th>
</tr>

<tr>
<td width="30%"><input type  = 'number' min="0" class="form-control" name="desde" id="desde" onblur="add_final(this.value)" autofocus ></input></td>
<td  width="30%"><input type = 'number' min="0" class="form-control" name="hasta" id="hasta" ></input></td>
<td><input type= 'checkbox' name="re" id="re" class="form-control" ></input></td>
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
<h4>DETALLE DE BILLETES A REPONER</h4>
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
<th>Billete</th>
<th>Registro</th>
<th>R. E.</th>
<th width="15%">Accion</th>
</tr>
</thead>
<tbody>

<?php

	$count_re = 0;
	$count_normal = 0;
	while ($reg_historico = mysqli_fetch_array($historico_revisado)) {

		echo "<tr>";
		echo "<td>" . $reg_historico['billete'] . "</td>";
		echo "<td>" . $reg_historico['registro'] . "</td>";
		echo "<td>" . $reg_historico['especial'] . "</td>";

		if ($reg_historico['especial'] == 'SI') {
			$count_re++;
		} else {
			$count_normal++;
		}

		if ($conteo_finalizado > 0) {
			echo "<td align = 'center'><button class = 'btn btn-danger' disabled>Anular Reposicion</button></td>";
		} else {
			echo "
<td align = 'center'>
<span class = 'btn btn-danger' onclick = 'anular(" . $reg_historico['billete'] . ")' >Anular Reposicion</span>
<button style = 'visibility:hidden' type = 'submit' id = 'anular" . $reg_historico['billete'] . "' name = 'anular_reposicion' value = '" . $reg_historico['billete'] . "' ></button>
</td>";
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
<th>Billete</th>
<th>Registro</th>
<th>R. E. </th>
</tr>
</thead>
<tbody>

<?php

	$conteo_pendiente = 0;
	while ($reg_inventario = mysqli_fetch_array($inventario_revisor)) {

		echo "<input type = 'hidden' name = 'id_reprobacion" . $conteo_pendiente . "' value = '" . $reg_inventario['id'] . "' >";

		echo "<tr>";
		echo "<td width = '10%' align = 'center'><input type = 'checkbox' name = 'check" . $conteo_pendiente . "'    id = 'check" . $conteo_pendiente . "'      onchange = 'calcular_reprobados()'  class = 'form-control' style = 'width: 30px; height: 30px;' ></td>";
		echo "<td ><input type = 'text'     name = 'billete" . $conteo_pendiente . "'  id = 'billete' class = 'form-control'  value = '" . $reg_inventario['billete'] . "' readonly></td>";
		echo "<td ><input type = 'text'     name = 'registro" . $conteo_pendiente . "' id = 'registro' class = 'form-control' value = '" . $reg_inventario['registro'] . "' readonly></td>";
		echo "<td ><input type = 'text'     name = 'especial" . $conteo_pendiente . "' id = 'especial' class = 'form-control' value = '" . $reg_inventario['especial'] . "' readonly></td>";
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
	<span   type="submit" class="btn btn-primary" onclick="validar_reprobado()">Reprobar Para Reposicion <?php echo $num_revision; ?></span>
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
<th>Billete</th>
<th>Registro</th>
<th>R. E.</th>
<th>Estado</th>
<th>Accion</th>
</tr>
</thead>
<tbody>

<?php

	$count_re = 0;
	$count_normal = 0;
	while ($reg_historico = mysqli_fetch_array($historico_revisado)) {
		echo "<tr>";
		echo "<td>" . $reg_historico['billete'] . "</td>";
		echo "<td>" . $reg_historico['registro'] . "</td>";

		if ($reg_historico['especial'] == 'SI') {
			$count_re++;
		} else {
			$count_normal++;
		}
		echo "<td>" . $reg_historico['especial'] . "</td>";

		echo "<td>" . $reg_historico['estado'] . "</td>";

		if ($conteo_finalizado > 0) {
			echo "<td align = 'center'><button class = 'btn btn-danger' disabled>Anular Reposicion</button></td>";
		} else {
			echo "
<td align = 'center'>
<span class = 'btn btn-danger' onclick = 'anular(" . $reg_historico['billete'] . ")' >Anular Reposicion</span>
<button style = 'visibility:hidden' type = 'submit' id = 'anular" . $reg_historico['billete'] . "' name = 'anular_reposicion' value = '" . $reg_historico['billete'] . "' ></button>
</td>";
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
$c_finalizado_anterior = mysqli_query($conn, "SELECT id FROM cc_produccion_mayor WHERE id_sorteo = '$id_sorteo' AND numero_revision = '$revision_anterior' ");

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
