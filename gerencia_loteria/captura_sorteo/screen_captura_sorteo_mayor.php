<?php
date_default_timezone_set("America/Tegucigalpa");
require '../../template/header.php';

$fecha_actual = date("Y-m-d");
//$fecha_actual = "2018-10-30";
$query_validacion_captura = mysqli_query($conn, "SELECT id, estado_sorteo, fecha_sorteo,   DATE_FORMAT(fecha_vencimiento, '%e-%c-%Y') fecha_vence FROM `sorteos_mayores` WHERE  date(fecha_sorteo) <= '$fecha_actual'  order by id desc limit 1");


if (mysqli_num_rows($query_validacion_captura) > 0) {
	while ($row = mysqli_fetch_array($query_validacion_captura)) {
		
		$sorteo = $row['id'];
		$no_sorteo = $row['id'];
		$fecha_sorteo = $row['fecha_sorteo'];
		$vencimiento_sorteo = $row['fecha_vence'];
		$estado_sorteo = $row['estado_sorteo'];
	}
}

$time = strtotime($fecha_sorteo);
$fecha_sorteo_f = date('d-m-Y', $time);

$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE id = '$no_sorteo' ");
$obj_sorteo = mysqli_fetch_object($info_sorteo);
$numero_sorteo = $obj_sorteo->no_sorteo_may;

$numero_terminaciones = mysqli_query($conn, "SELECT count(*) conteo FROM sorteos_mayores_premios a, premios_mayores b WHERE b.tipo_premio = 'TERMINACION' and sorteos_mayores_id=$no_sorteo and a.premios_mayores_id=b.id order by b.id");

if (mysqli_num_rows($numero_terminaciones) > 0) {
	$row2 = mysqli_fetch_array($numero_terminaciones);
	$terminaciones = $row2['conteo'];
} else {
	echo "<div class = 'alert alert-danger'>No se seleccionaron terminaciones a jugar en este sorteo.</div>";
}

?>


<head>

<meta charset="UTF-8">
<title>PANI: Captura de Sorteo Mayor</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<script type="text/javascript">

function isNumberKey(evt){
var charCode = (evt.which) ? evt.which : event.keyCode
if (charCode > 31 && (charCode < 46 || charCode > 57))
return false;
return true;
}





function generarDecenas(sorteo) {

	document.getElementById("respDecenasDisponibles").innerHTML = "";

token = Math.random();
consulta = "decenas_disponibles_mayor.php?sorteo="+sorteo+"&token="+token;
$("#respDecenasDisponibles").load(consulta);

}




function consultar_billete(indice){



t = 1;
s = document.getElementById('sorteo').value;
b = document.getElementById('numero_ganador'+indice).value;
d = document.getElementById('decimos'+indice).value;
p = document.getElementById('id_premio'+indice).value;

if (b === '' || b == '') {

swal( "","Debe ingresar el billete premiado." ,"error");

}else{

$(".div_wait").fadeIn("fast");
token = Math.random();
consulta = 'registro_captura.php?s='+s+"&t="+t+"&b="+b+"&d="+d+"&p="+p+"&token="+token;

$("#respuesta_"+indice).load(consulta);

}

inputs = document.getElementById('cantidad_premios').value;

indicador = 1;
for (var i = 0; i < inputs; i++) {
bill = document.getElementById('numero_ganador'+i).value;

if (bill === '') {
indicador = 0;
}

}


if (indicador == 1) {

document.getElementById("footer_captura").innerHTML = '<p aling = "center"><span onclick = "confirmar_finalizado()"  class="btn btn-danger btn-lg btn-block" style="width:30%;">Finalizar Captura</span><button type="submit" name="finalizar_captura" id="finalizar_captura" style = "visibility: hidden" ></button></p>';

}else{

document.getElementById("footer_captura").innerHTML = "<div class = 'alert alert-info'>Aun hay premios pendientes de juego.</div>";

}

}










function confirmar_finalizado(){

swal("Esta acción es irreversible", "¿Esta seguro de finalizar el sorteo?", "warning",  {
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



function handleCargarDecena(){

premioDecena = document.getElementById("select_decena").value;
billeteDecena = document.getElementById("billete_decena").value;

if (billeteDecena == ''){

	alert("Debe ingresar un numero de billete para cargar decena.");

}else{

	
i = 0;
pivote = 0;
banderaCompletado = false;
contadorAsignado  = 0;

document.getElementById('btn-cargar-decena').disabled = true;

billeteDecena = billeteDecena.substring(0, billeteDecena.length - 1);
billeteDecena = billeteDecena + '0';


while (document.getElementById("montoPremio" + i) != undefined) {
	pivote = i; 


	if (contadorAsignado < 10) {
		
		if (document.getElementById("montoPremio" + i).value == premioDecena) {
			
			if (document.getElementById("numero_ganador" + i).value == "") {
				
				document.getElementById("numero_ganador" + i).value = billeteDecena;
				consultar_billete(i);
				
				

				billeteDecena = parseInt(billeteDecena) + 1;
				contadorAsignado = contadorAsignado + 1;
			}

		}	
		
	}else{

		i = i + 50000;

	}

	i = i + 1;

}


document.getElementById("numero_ganador" + pivote).focus();
document.getElementById('btn-cargar-decena').disabled = false;
document.getElementById("billete_decena").value = '';
$('#modal-decena').modal('hide');


}


}

</script>

</head>




<div class="modal fade" role="dialog" tabindex="-1" id="modal-decena">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h4 class="text-center modal-title" style="width:100%;">CARGAR DECENA</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
<div class="modal-body" style="padding-bottom:0px;"><div class="form-group">

<div class="input-group">
<div class="input-group-prepend">
<span  class="input-group-text">
Premios Con decena</span>
</div>
<div id="respDecenasDisponibles"></div>
</div>


<div class="input-group"  style = "margin-top:8px">
<div class="input-group-prepend">
<span style="width: 80px;" class="input-group-text" >
Billete
</span>
</div>
<input type = "number" class = "form-control" min = "1" max = "9999" id="billete_decena" >
</div>



<button id = 'btn-cargar-decena' class = 'btn btn-success' onclick="handleCargarDecena()" style = 'margin-top:10px; width:100%' >Cargar Decena  </button>


</div></div>
<div class="modal-footer">

</div>
</div>
</div>
</div>


<button data-toggle="modal" data-target="#modal-decena" onclick="generarDecenas('<?php echo $numero_sorteo; ?>')" class="btn btn-success" style="position:fixed;
	bottom:40px;
	left:40px;
	box-shadow: 2px 2px 3px #999; z-index: 1000;">GENERAR DECENA</button>



<form method="post">

<br>

<div class="card" style="margin-right: 15px;margin-left: 15px;" >

<div class="card-header bg-success text-white">
<h3 align="center">CAPTURA DE SORTEO DE LOTERIA MAYOR REALIZADO EL <?php echo $fecha_sorteo_f; ?>

</h3>
</div>

<div class="card-body table-responsive" >

<input type="hidden" name="num_terminaciones" id="num_terminaciones" value=" <?php echo $terminaciones; ?> ">

<table class="table table-bordered table-hover" >

<tr>
<td colspan="6" align="center" class="bg-secondary">
<input id="sorteo" class="form-control"  style="width: 200px; height: 150px; font-family: Arial; font-size: 60pt; text-align: center;" size="32" readonly name="sorteo"   value="<?php echo $numero_sorteo ?>">
</td>
</tr>

<tr>
<th style='font-family: Arial; text-align:center; font-size: 20pt; margin-left: 15%;'> # </th>
<th style='width: 35%;font-family: Arial; text-align:center; font-size: 20pt; margin-left: 20%;'> PREMIO </th>
<th style='width: 35%;font-family: Arial; text-align:center; font-size: 20pt; '>DESCRIPCION </th>
<th style='width: 35%;font-family: Arial; text-align:center; font-size: 20pt; '>NUMERO PREMIADO </th>
<th style='width: 20%;font-family: Arial; text-align:center; font-size: 20pt; '>DECIMOS </th>
<th style='width: 10%;font-family: Arial; text-align:center; font-size: 20pt; '>ACCION</th>
</tr>

<?php

$all_premios_registrados = 1;

$result = mysqli_query($conn, "  SELECT b.`descripcion_premios` premio,  b.`pago_premio` pago , a.numero_premiado_mayor premiado , a.premios_mayores_id id_premio, a.decimos decimos, a.id, a.monto, a.desc_premio FROM `sorteos_mayores_premios` a, premios_mayores b  WHERE  a.`premios_mayores_id` = b.id  AND a.respaldo != 'SI'  AND  a.sorteos_mayores_id = $no_sorteo   AND b.tipo_premio != 'TERMINACION' ORDER BY monto  DESC ");
if (mysqli_num_rows($result) > 0) {

	$acum = 0;
	$focus = false;
	$pp = 0;

	while ($row = mysqli_fetch_array($result)) {
	$pp++;

echo "<tr><td style='font-family: Arial; font-size: 25pt;'>".$pp."</td>
<td align='center' width='25%'  style='with:100%; font-family: Arial; font-size: 25pt;'>
<input  type = 'hidden' id = 'montoPremio" . $acum . "'  name = 'montoPremio" . $acum . "' value = '" . $row['monto'] . "'  > 
" . number_format($row['monto'], 2) . "
</td>
<td align='center' width='25%'  style='with:100%; font-family: Arial; font-size: 25pt;'> " .$row['desc_premio']. "</td>
<td align='center' width='25%'>";

////////////////////////////////////////////////////////////////////////////////////////
		/////////////////////////// COLUMNA BILLETE ////////////////////////////////////////////

		if ($row['premiado'] == '' AND $focus == false) {
			echo " <input type = 'text' class = 'form-control'  style='width:80%; height: 50px; font-family: Arial; font-size: 30pt; text-align:center;' onkeypress = 'return isNumberKey(event)' name='numero_ganador" . $acum . "' id='numero_ganador" . $acum . "' value='" . $row['premiado'] . "'  autofocus>";
			$focus = true;
		} else {
			echo " <input type = 'text' class = 'form-control' style='width:80%; height: 50px; font-family: Arial; font-size: 30pt; text-align:center;' onkeypress = 'return isNumberKey(event)' name='numero_ganador" . $acum . "' id='numero_ganador" . $acum . "' value='" . $row['premiado'] . "'  >";
		}

		if ($row['premiado'] == '') {
			$all_premios_registrados = 0;
		}

/////////////////////////// COLUMNA BILLETE ////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////
		/////////////////////////// COLUMNA DECIMO  ////////////////////////////////////////////

		echo " </td>
<input type='hidden'  id='id_premio" . $acum . "' name='id_premio" . $acum . "' value='" . $row['id'] . "'>";

		if ($row['decimos'] == '10' AND $focus == false) {
			echo "<td align='center' width='13%'> <input type='text' style='width:60%; height: 50px; margin-left:0%; font-family: Arial; font-size:20pt;  text-align:center;' onkeypress = 'return isNumberKey(event)'  value='" . $row['decimos'] . "' name='decimos" . $acum . "' id='decimos" . $acum . "' class='form-control' autofocus ></td>";
			$focus = true;
		} else {
			echo "<td align='center' width='13%'> <input type='text' style='width:60%; height: 50px; margin-left:0%; font-family: Arial; font-size:20pt;  text-align:center;' onkeypress = 'return isNumberKey(event)'  value='" . $row['decimos'] . "' name='decimos" . $acum . "' id='decimos" . $acum . "' class='form-control' ></td>";
		}

/////////////////////////// COLUMNA DECIMO  ////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////
		/////////////////////////// COLUMNA GUARDAR  ////////////////////////////////////////////
		if ($estado_sorteo != 'CAPTURADO') {
			echo "<td align = 'center'><span class = 'btn btn-success  btn-lg fa fa-save' id = 'btn-save-billete' onclick = 'consultar_billete(" . $acum . ")'></span></td>";
		} else {
			echo "<td align = 'center'> <button class = 'btn btn-success  btn-lg fa fa-save'  disabled></button></td>";
		}
/////////////////////////// COLUMNA GUARDAR  ////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////////////////////

		echo "</tr>";

		echo "<tr id = 'respuesta_" . $acum . "'>";
		$numero_ganador = $row['premiado'];

		if ($numero_ganador == '') {
		echo "</tr>";
		}else{


		$consulta_mensaje = mysqli_query($conn, " SELECT a.billete, a.detalle_venta  FROM `ventas_distribuidor_mayor` as a  WHERE  a.billete='$numero_ganador' AND a.sorteo = '$no_sorteo' ");

		if (mysqli_num_rows($consulta_mensaje) > 0) {

			$ob_geo = mysqli_fetch_object($consulta_mensaje);
			$municipio = '';
			$dpto = '';
			$v_muni = '';
			$detalle_venta = $ob_geo->detalle_venta;

			$_mensaje = "El Numero <b>" . $numero_ganador . "</b> ha sido Vendido en <b>" . $detalle_venta . "</b> ";
			echo " <td colspan = '6' class = 'alert alert-success'><div class='' style='width:100%;'> <strong>¡Vendido! </strong>" . $_mensaje . "</div></td>";

			echo "</tr>";

		} else {

			if (isset($numero_ganador)) {

				$_mensaje = " El Numero <b>" . $numero_ganador . "</b>  No Fue Vendido";
				echo "<td colspan = '6' class = 'alert alert-danger'> <div class='' style='width:100%;'> <strong>¡No Vendido!</strong>" . $_mensaje . "</div></td>";

				echo "</tr>";

/////////////////////////////////////////////////////////////////////////////////////////////
				////////////////////////////// PREMIOS DE RESPALDO DE BILLETES NO VENDIDOS //////////////////

				$id_premio_r = $row['id_premio'];

				$consulta = mysqli_query($conn, "SELECT * FROM  sorteos_mayores_premios WHERE premios_mayores_id  = '$id_premio_r' AND sorteos_mayores_id = '$no_sorteo' AND respaldo = 'SI' ");

				while ($respaldo_premio = mysqli_fetch_array($consulta)) {

					$acum = $acum + 1;

					$descripcion_respaldo = $respaldo_premio['descripcion_respaldo'];
					$monto_respaldo = $respaldo_premio['monto'];
					$num_premiado_respaldo = $respaldo_premio['numero_premiado_mayor'];
					$decimos_respaldo = $respaldo_premio['decimos'];

					echo "<tr>
<td align='center' style='with:100%; font-family: Arial; font-size: 25pt;'> " . number_format($monto_respaldo, 2) . "</td>

<td align='center' ><input type=text  style='width:50%; height: 50px; font-family: Arial; font-size: 30pt; text-align: center'  class = 'form-control' id='numero_ganador" . $acum . "' name='numero_ganador" . $acum . "'  value='" . $num_premiado_respaldo . "'  onkeypress = 'return isNumberKey(event)'></td>

<input type='hidden' id='id_premio" . $acum . "' name='id_premio" . $acum . "' value='" . $respaldo_premio['id'] . "'>

<td align='center' width='13%'> <input type='text' style='width:60%; height: 50px; margin-left:0%; font-family: Arial; font-size:20pt; text-align: center'  value='" . $decimos_respaldo . "' name='decimos" . $acum . "' id='decimos" . $acum . "' class='form-control' onkeypress = 'return isNumberKey(event)' ></td>";

					if ($estado_sorteo != 'CAPTURADO') {
						echo "<td align = 'center'><span class = 'btn btn-success  btn-lg fa fa-save' onclick = 'consultar_billete(" . $acum . ")'></span></td>";
					} else {
						echo "<td align = 'center'> <button class = 'btn btn-success  btn-lg fa fa-save'  disabled></button></td>";
					}

					echo "</tr>";

					$consulta_mensaje = mysqli_query($conn, " SELECT a.billete, a.detalle_venta FROM `ventas_distribuidor_mayor` as a  WHERE  a.billete='$num_premiado_respaldo' AND a.sorteo = '$no_sorteo' ");

					if (mysqli_num_rows($consulta_mensaje) > 0) {

						$row = mysqli_fetch_array($consulta_mensaje);
						$v_muni = $row['detalle_venta'];

						$_mensaje = "El Numero <b>" . $num_premiado_respaldo . "</b> ha sido Vendido en <b>" . $v_muni . "</b>";
						echo " <tr id = 'respuesta_" . $acum . "'><td colspan = '6' class = 'alert alert-success' ><div class='' style='width:100%;'> <strong>¡Vendido! </strong>" . $_mensaje . "</div></td></tr>";
					} else {
						if ($num_premiado_respaldo != '') {
							$_mensaje = "El Numero <b>" . $num_premiado_respaldo . "</b> No Fue Vendido";
							echo " <tr id = 'respuesta_" . $acum . "'><td colspan = '6' class = 'alert alert-danger'> <div class='' style='width:100%;'> <strong>¡No Vendido!</strong> " . $_mensaje . "</div></td></tr>";
						}
					}

				}

////////////////////////////// PREMIOS DE RESPALDO DE BILLETES NO VENDIDOS //////////////////
				/////////////////////////////////////////////////////////////////////////////////////////////

			}

		}

		}


		$acum = $acum + 1;
	}
}
?>
</table>

<input type="hidden"  id="cantidad_premios" name="cantidad_premios" value="<?php echo $acum; ?>">

</div>
<div class="card-footer" id="footer_captura" align="center">

<a style="width:30%;" target="_blank" class="btn btn-info btn-lg" href="./acta_sorteo_mayor_oficial.php?s=<?php echo $no_sorteo; ?>">Emitir Acta Oficial</a>

<?php
if ($estado_sorteo == "PENDIENTE DISTRIBUCION" OR $estado_sorteo == "PENDIENTE CAPTURA") {

	if ($all_premios_registrados == 1) {

		echo '<hr><span onclick = "confirmar_finalizado()"  class="btn btn-danger btn-lg btn-block" style="width:30%;">Finalizar Captura</span>
<button type="submit" name="finalizar_captura" id="finalizar_captura" style = "visibility: hidden" ></button>';

	} else {

		echo "<div class = 'alert alert-info'>Aun hay premios pendientes de juego.</div>";

	}

}

?>


</div>
</div>


</form>



<br><br>



<?php

if (isset($_POST['finalizar_captura'])) {

	$ganador = $_POST['numero_ganador0'];
	$no_sorteo = $_POST['sorteo'];

	$conte = 1;

	while ($conte <= $terminaciones) {

		$_terminacion = substr($ganador, strlen($ganador) - $conte, $conte);

		$captura_terminaciones = mysqli_query($conn, "UPDATE sorteos_mayores_premios, premios_mayores  SET  numero_premiado_mayor=$_terminacion  WHERE sorteos_mayores_premios.premios_mayores_id = premios_mayores.id and premios_mayores.tipo_premio='TERMINACION' and indice = $conte and sorteos_mayores_premios.sorteos_mayores_id=$no_sorteo");

		if (!$captura_terminaciones) {
			echo mysqli_error($conn);
		}

		$conte = $conte + 1;
	}

	header("Location:./proceso_cierre_sorteo_mayor.php?s=" . $no_sorteo . "");

}

?>
