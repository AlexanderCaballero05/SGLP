<?php

require '../../template/header.php';

$fecha_actual = date("Y-m-d"); //echo $fecha_actual;
//$fecha_actual = date("2018-11-17"); //echo $fecha_actual;
$query_validacion_captura = mysqli_query($conn, "SELECT id, estado_sorteo, fecha_sorteo,   DATE_FORMAT(vencimiento_sorteo, '%e-%c-%Y') fecha_vence FROM `sorteos_menores` WHERE date(fecha_sorteo) <= '$fecha_actual'  order by id desc limit 1");

if (mysqli_num_rows($query_validacion_captura) > 0) {

	while ($row = mysqli_fetch_array($query_validacion_captura)) {
		$sorteo = $row['id'];
		$no_sorteo = $row['id'];
		$fecha_sorteo = $row['fecha_sorteo'];
		$vencimiento_sorteo = $row['fecha_vence'];
		$estado_sorteo = $row['estado_sorteo'];
	}
}

//echo $sorteo."---".$fecha_sorteo."---".$vencimiento_sorteo."---".$estado_sorteo ;
$premios_menores = mysqli_query($conn, "SELECT * FROM sorteos_menores_premios WHERE premios_menores_id = '$sorteo' ");

$i = 0;

while ($reg_premios_menores = mysqli_fetch_array($premios_menores)) {

	$v_premios[$i] = $reg_premios_menores['premios_menores_id'];
	$i++;

}

?>
<head>
  <meta charset="UTF-8">
  <title>PANI: Captura de Sorteo Menor</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">





<script type="text/javascript" charset="utf-8">



function isNumberKey(evt){
var charCode = (evt.which) ? evt.which : event.keyCode
if (charCode > 31 && (charCode < 46 || charCode > 57))
return false;
return true;
}



function invertir(cadena)
{
var x = cadena.length;
var cadenaInvertida = "";
while (x>=0)
{
cadenaInvertida = cadenaInvertida + cadena.charAt(x);
x--;
}
document.getElementById("numero_reves0").value=cadenaInvertida;
}

function validarnum(e)
{
tecla = (document.all) ? e.keyCode : e.which;
if (tecla<=13 || tecla>=48 && tecla<=57) return true;
patron =/[1234567890-]/;
te = String.fromCharCode(tecla);
return patron.test(te);
}

function enviar_acta()  { window.open('_pdf_reporte_menor.php', '_blank'); }















//////////////////////////////////////////////////////////////////////
//////////////////////// SCRIPT DE CONSUTLA VENDIDO //////////////////

function consultar_billete(indice, dr){

t = 2;
s = document.getElementById('sorteo').value;

d = document.getElementById('numero_ganador0').value;
r = document.getElementById('numero_reves0').value;

id_d = document.getElementById('id_numero_d').value;
id_r = document.getElementById('id_numero_r').value;

if (dr == 1) {
ser = document.getElementById('numero_ganador'+indice).value;
p = document.getElementById('id_premio'+indice).value;
}else{
ser = document.getElementById('numero_reves'+indice).value;
p = document.getElementById('id_premio_reves'+indice).value;
}


if (d === '' || d == '') {
swal( "","Debe ingresar el numero premiado." ,"error");

}else{

if (ser === '' || ser == '') {

swal( "","Debe ingresar la serie premiada." ,"error");

}else{


$(".div_wait").fadeIn("fast");

token = Math.random();
consulta = 'registro_captura.php?s='+s+"&t="+t+"&d="+d+"&r="+r+"&ser="+ser+"&p="+p+"&idd="+id_d+"&idr="+id_r+"&dr="+dr+"&token="+token;


if (dr == 1) {

$("#respuesta_d"+indice).load(consulta);

}else{

$("#respuesta_r"+indice).load(consulta);

}

}






inputs = document.getElementById('contador_derecho').value;
inputs2 = document.getElementById('contador_reves').value;

indicador = 1;
for (var i = 0; i < inputs; i++) {
bill = document.getElementById('numero_ganador'+i).value;

if (bill === '') {
indicador = 0;
}

}


indicador = 1;
for (var i = 0; i < inputs; i++) {
bill = document.getElementById('numero_reves'+i).value;

if (bill === '') {
indicador = 0;
}

}

if (indicador == 1) {

document.getElementById("footer_captura").innerHTML = "<span style='width: 100%' onclick = 'confirmar_finalizado()'  class='btn btn-danger btn-lg'>Finalizar Captura</span><button type='submit' name='finalizar_captura' id='finalizar_captura' style = 'visibility: hidden' ></button>";

}else{

document.getElementById("footer_captura").innerHTML = "<div class = 'alert alert-info'>Aun hay premios pendientes de juego.</div>";

}



}

}

//////////////////////// SCRIPT DE CONSUTLA VENDIDO //////////////////////////
//////////////////////////////////////////////////////////////////////////////







function confirmar_finalizado(){

swal("Esta acción es irreversible", " ¿Esta seguro de finalizar el sorteo? ", "warning",  {
  buttons: {
	cancel: "No",
    finalizar: {
      text: "Si",
      value: "si",
    }
  },
})
.then((value) => {
  switch (value) {

    case "no":
      swal("Finalización cancelada.");
      break;

    case "si":

    document.getElementById('finalizar_captura').click();
	$(".div_wait").fadeIn("fast");

      break;

    default:
      swal("Finalización cancelada.");
  }
});

}








</script>
</head>





<br>


<?php

$all_premios_registrados = 1;

$numero_ganador0 = mysqli_query($conn, "SELECT a.numero_premiado_menor premiado_ganador, b.id id_ganador FROM sorteos_menores_premios a , premios_menores b WHERE b.clasificacion = 'NUMERO' and b.tipo_serie= 'GANADOR' and a.premios_menores_id=b.id and a.sorteos_menores_id = $no_sorteo");
while ($row = mysqli_fetch_array($numero_ganador0)) {
	$cargar_ganador = $row['premiado_ganador'];
	$id_ganador = $row['id_ganador'];
}

$numero_reves0 = mysqli_query($conn, "SELECT a.numero_premiado_menor premiado_reves , b.id id_reves FROM sorteos_menores_premios a , premios_menores b WHERE b.clasificacion = 'NUMERO' and b.tipo_serie= 'REVES' and a.premios_menores_id=b.id and a.sorteos_menores_id = $no_sorteo");
while ($row = mysqli_fetch_array($numero_reves0)) {
	$cargar_reves = $row['premiado_reves'];
	$id_reves = $row['id_reves'];}
?>


<form method="POST">

<div class="card" style="margin-right: 15px;margin-left: 15px; ">

<div class="card-header bg-success text-white">
<h3 align="center">CAPTURA DE SORTEO DE LOTERIA MENOR REALIZADO EL <?php echo $fecha_sorteo; ?>

</h3>
</div>

<div class="card-body table-responsive">


<table width="100%"   class="table  table-bordered ">

<tr>
	<td colspan = "2" align="center" class="bg-secondary">


<input id="id_numero_d" type="hidden"  name="id_numero_d"   value="<?php echo $id_ganador ?>">
<input id="id_numero_r" type="hidden"  name="id_numero_r"   value="<?php echo $id_reves ?>">

<input id="sorteo" class="form-control"  style="width: 200px; height: 150px; font-family: Arial; font-size: 60pt; text-align: center;" size="32" readonly name="sorteo"   value="<?php echo $no_sorteo ?>">

	</td>
</tr>


<tr>
	<th style="text-align: center">
		<span style="font-size: 20pt;" ><b>NUMERO PREMIADO DE DERECHO</b></span>
	</th>

	<th style="text-align: center">
		<span style="font-size: 20pt;" ><b>NUMERO PREMIADO DE REVES</b></span>
	</th>

</tr>


<tr>

<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
<!--//////////////////////////////////////////////// DERECHO ////////////////////////////////////////////////////-->
<!--//////////////////////////////////////////////// DERECHO ////////////////////////////////////////////////////-->

<td width="50%" align="center">

<input id="numero_ganador0"  style="width: 140px; height: 120px; font-family: Arial; font-size: 60pt; text-align: center;" name="numero_ganador0" value="<?php echo $cargar_ganador ?>" onkeypress="return validarnum(event)" onkeyup=" invertir(this.value);"  class="form-control input-lg" type="text" maxlength="2" >

</td>

<!--//////////////////////////////////////////////// DERECHO ////////////////////////////////////////////////////-->
<!--//////////////////////////////////////////////// DERECHO ////////////////////////////////////////////////////-->
<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////-->





<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
<!--//////////////////////////////////////////////// REVES //////////////////////////////////////////////////////-->
<!--//////////////////////////////////////////////// REVES //////////////////////////////////////////////////////-->

<td width="50%" align="center">

<input id="numero_reves0" style="width: 140px; height: 120px; font-family: Arial; font-size: 60pt; text-align: center;" value="<?php echo $cargar_reves ?>" name="numero_reves0" onkeypress="return validarnum(event)" class="form-control input-lg" type="text" maxlength="2" readonly>

</td>

<!--//////////////////////////////////////////////// REVES //////////////////////////////////////////////////////-->
<!--//////////////////////////////////////////////// REVES //////////////////////////////////////////////////////-->
<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////-->

</tr>

<tr>

<td width="50%">
<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
<!--//////////////////////////////////////////////// DERECHO ////////////////////////////////////////////////////-->
<!--//////////////////////////////////////////////// DERECHO ////////////////////////////////////////////////////-->

<table   class="table table-hover table-bordered">
<thead>
<th width = '60%' align="center" style='font-family: Arial; font-size: 20pt; padding-bottom: 0px;'> PREMIO </th>
<th width = '25%' align="center" style='font-family: Arial; font-size: 20pt; padding-bottom: 0px;'> SERIE </th>
<th width = '15%' align="center" style='font-family: Arial; font-size: 20pt; padding-bottom: 0px;'> ACCION</th>
</thead>
<tbody>
<?php
$result = mysqli_query($conn, " SELECT b.`descripcion_premios` premio,  b.`pago_premio` pago , a.monto,a.numero_premiado_menor premiado , a.premios_menores_id id_premio FROM `sorteos_menores_premios` a, premios_menores b  WHERE a.`premios_menores_id` = b.id and b.tipo_serie='GANADOR' AND  a.sorteos_menores_id= $no_sorteo  AND clasificacion='SERIE'");
if (mysqli_num_rows($result) > 0) {

	$acum_derecho = 1;
	while ($row = mysqli_fetch_array($result)) {

		echo "
<input type='hidden'  id='id_premio" . $acum_derecho . "' name='id_premio" . $acum_derecho . "' value='" . $row['id_premio'] . "'>

<tr>

<td style='font-family: Arial; font-size: 25pt;' >" . number_format($row['monto']) . "</td>

<td ><input type='text' class='form-control'  style='width:100%; height: 50px; margin-left:0%; font-family: Arial; font-size: 25pt; text-align: center' onkeypress='return validarnum(event)' name='numero_ganador" . $acum_derecho . "' id='numero_ganador" . $acum_derecho . "' value='" . $row['premiado'] . "' ></td>

<td align = 'center' width='18%' style='font-family: Arial; font-size: 25pt; padding-bottom: 0px;'>";

		if ($estado_sorteo != 'CAPTURADO') {
			echo "<span class = 'btn btn-success  btn-lg fa fa-save' onclick = 'consultar_billete(" . $acum_derecho . ", 1)'></span>";
		} else {
			echo "<button class = 'btn btn-success  btn-lg fa fa-save'  disabled></button>";
		}

		echo "</td>


</tr>";

		echo "<tr id = 'respuesta_d" . $acum_derecho . "'>";

		if (isset($row['premiado'])) {

			$serie_gan = $row['premiado'];
			$consulta_mensaje = mysqli_query($conn, " SELECT * FROM `ventas_distribuidor_menor` WHERE  `numero`= '$cargar_ganador'  and  `serie` = '$serie_gan' AND sorteo = '$no_sorteo' ");
			if (mysqli_num_rows($consulta_mensaje) > 0) {

				$row = mysqli_fetch_array($consulta_mensaje);
				$_mensaje = "El Numero <b>" . $cargar_ganador . " </b> con Serie <b>" . $row['serie'] . "</b> ha sido Vendido  en <b>" . $row['agencia_banrural'] . "</b> ";
				echo "<td colspan = '3' class='alert  alert-success' > <strong>¡Vendido! </strong>" . $_mensaje . "</td>";

			} else {

				$_mensaje_reves = "El Numero <b>" . $cargar_ganador . " </b> con Serie <b>" . $row['premiado'] . "</b> No ha sido Vendido.";
				echo "<td colspan = '3' class='alert alert-danger' > <strong>¡No Vendido! </strong>" . $_mensaje_reves . "</td>";

			}

		} else {

			$all_premios_registrados = 0;

		}

		echo "</tr>";

		$acum_derecho = $acum_derecho + 1;
	}

	echo "<input type='hidden'  name='contador_derecho' id='contador_derecho' value='" . $acum_derecho . "'>";

} else {

	echo "<div class='alert alert-warning' id='non-printable' ><strong>Sorteo esta pendiente de asignación de premios de derecho</strong></div>";
}

?>
</tbody>
</table>

<!--//////////////////////////////////////////////// DERECHO ////////////////////////////////////////////////////-->
<!--//////////////////////////////////////////////// DERECHO ////////////////////////////////////////////////////-->
<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////-->

</td>































<td  width="50%">

<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
<!--//////////////////////////////////////////////// REVES //////////////////////////////////////////////////////-->
<!--//////////////////////////////////////////////// REVES //////////////////////////////////////////////////////-->

<table width="100%"  class="table table-hover table-bordered" >
<thead>
<th width = '60%' align="center" style='font-family: Arial; font-size: 20pt; padding-bottom: 0px;'> PREMIO </th>
<th width = '25%' align="center" style='font-family: Arial; font-size: 20pt; padding-bottom: 0px;'> SERIE </th>
<th width = '15%' align="center" style='font-family: Arial; font-size: 20pt; padding-bottom: 0px;'> ACCION</th>
</thead>
<tbody>
<?php
$result = mysqli_query($conn, " SELECT b.`descripcion_premios` premio,  b.`pago_premio` pago ,a.monto, a.numero_premiado_menor premiado_reves , a.premios_menores_id id_premio_reves FROM `sorteos_menores_premios` a, premios_menores b  WHERE a.`premios_menores_id` = b.id and b.tipo_serie='REVES'  and a.sorteos_menores_id= $no_sorteo and clasificacion='SERIE' ");
if (mysqli_num_rows($result) > 0) {
	$acum_reves = 1;

	while ($row = mysqli_fetch_array($result)) {

		echo "
<input type='hidden'  font-family: Arial; font-size: 25pt;' name='id_premio_reves" . $acum_reves . "' id='id_premio_reves" . $acum_reves . "' value='" . $row['id_premio_reves'] . "'>

<tr>
<td style='font-family: Arial; font-size: 25pt;' >" . number_format($row['monto']) . "</td>

<td width='20%' ><input type='text' class='form-control'  style='width:100%; height: 50px; font-family: Arial; font-size: 25pt;' id='numero_reves" . $acum_reves . "' name='numero_reves" . $acum_reves . "' value='" . $row['premiado_reves'] . "'  ></td>

<td align = 'center' width='18%' style='font-family: Arial; font-size: 25pt; padding-bottom: 0px;'>";

		if ($estado_sorteo != 'CAPTURADO') {
			echo "<span class = 'btn btn-success  btn-lg fa fa-save' onclick = 'consultar_billete(" . $acum_reves . ", 2)'></span>";
		} else {
			echo "<button class = 'btn btn-success  btn-lg fa fa-save'  disabled></button>";
		}

		echo "</td>
</tr>";

		echo "<tr id = 'respuesta_r" . $acum_reves . "'>";

		if (isset($row['premiado_reves'])) {

			$serie_reves = $row['premiado_reves'];
			//echo "este es el numero".$numero_reves_0."--  ESta es al serie".$serie_reves    ;
			$consulta_mensaje_reves = mysqli_query($conn, "SELECT agencia_banrural, fecha_venta  FROM `ventas_distribuidor_menor` WHERE  numero= '$cargar_reves'   and  serie =   '$serie_reves' AND sorteo = '$no_sorteo' ");

			if (mysqli_num_rows($consulta_mensaje_reves) > 0) {

				while ($row = mysqli_fetch_array($consulta_mensaje_reves)) {
					$detalle_venta_msg = $row['agencia_banrural'];
					$fecha_venta = $row['fecha_venta'];
				}

				$_mensaje_reves = "El Numero <b>" . $cargar_reves . "</b>  con Serie <b>" . $serie_reves . "</b>  fue vendido en <b>" . $detalle_venta_msg . "</b>.";
				echo " <td colspan = '3' class='alert alert-success' ><strong>¡Vendido! </strong>" . $_mensaje_reves . "</td>";

			} else {

				$_mensaje_reves = "El Numero <b>" . $cargar_reves . "</b>  con Serie <b>" . $row['premiado_reves'] . "</b> No ha sido Vendido";
				echo "<td colspan = '3' class='alert alert-danger' ><strong>¡No Vendido! </strong>" . $_mensaje_reves . "</td>";
			}

		} else {

			$all_premios_registrados = 0;

		}

		echo "</tr>";

		$acum_reves = $acum_reves + 1;
	}
	echo "<input type='hidden'  name='contador_reves' id='contador_reves' value='" . $acum_reves . "'>";

} else {

	echo "<div class='alert alert-warning' id='non-printable' ><strong>Sorteo esta pendiente de asignación de premios de reves</strong></div>";
}
?>
</tbody>
</table>

<!--//////////////////////////////////////////////// REVES //////////////////////////////////////////////////////-->
<!--//////////////////////////////////////////////// REVES //////////////////////////////////////////////////////-->
<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////-->

</td>

</tr>

</table>

</div>

<div  class="card-footer"  align="center">


<div class="row">
	<div class="col">
		<a  style="width: 100%" href="acta_info_sorteo_menor.php?sorteo=<?php echo $no_sorteo ?> " target='_blank' class="btn btn-info btn-lg " >Información del sorteo</a>
	</div>
	<div class="col">
		<a  style="width: 100%" href="acta_sorteo_menor_oficial.php?sorteo=<?php echo $no_sorteo ?> "  target='_blank' class="btn btn-primary btn-lg">Acta del sorteo</a>
	</div>
	<div class="col" id="footer_captura">
<?php

if ($estado_sorteo == "PENDIENTE DISTRIBUCION" OR $estado_sorteo == "PENDIENTE CAPTURA") {

	if ($all_premios_registrados == 1) {

		echo '<span style="width: 100%" onclick = "confirmar_finalizado()"  class="btn btn-danger btn-lg"">Finalizar Captura</span>
<button type="submit" name="finalizar_captura" id="finalizar_captura" style = "visibility: hidden" ></button>';

	} else {

		echo "<div class = 'alert alert-info'>Aun hay premios pendientes de juego.</div>";

	}

} else {
	echo "<div class = 'alert alert-info'><b><i class = 'fa fa-exclamation-circle'></i> SORTEO FINALIZADO</b></div>";
}

?>

	</div>
</div>








</div>

</div>

<br><br>


</form>

<?php

if (isset($_POST['finalizar_captura'])) {

	header("Location:./proceso_cierre_sorteo_menor.php?s=" . $no_sorteo . "");

}

?>
