<?php
require '../../template/header.php';

$id_sorteo = $_GET['sort'];

$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE id = '$id_sorteo' ");
$og_info = mysqli_fetch_object($info_sorteo);
$sorteo = $og_info->no_sorteo_men;
$fecha_sorteo = $og_info->fecha_sorteo;

$sorteos = mysqli_query($conn, " SELECT * FROM sorteos_menores WHERE premios_asignados = 'NO' OR  premios_asignados IS NULL  ");

?>

<script type="text/javascript">
function eliminar_fila(elemento){

f =  elemento.parentNode.parentNode.rowIndex;

tabla = document.getElementById('tabla_premios');
filas = tabla.rows.length;
tabla.deleteRow(f);

filas_final = tabla.rows.length;

i = 1;

}

function waiting(){
	$(".div_wait").fadeIn("fast");
}


function agregar_premio(){
	select = document.getElementById('premio');
	premio = select.value;
	texto_premio = select.options[select.selectedIndex].text;

tabla = document.getElementById('tabla_premios');
filas = tabla.rows.length;

if (document.getElementById('filas').value == '') {
document.getElementById('filas').value = filas;
}else{
document.getElementById('filas').value = parseInt(document.getElementById('filas').value ) + 1;

}

filas = tabla.rows.length;
var row = tabla.insertRow(filas);

filas = document.getElementById('filas').value;
var cell1 = row.insertCell(0);
var cell2 = row.insertCell(1);
var cell3 = row.insertCell(2);
var cell4 = row.insertCell(3);
var cell5 = row.insertCell(4);

cell5.align = "center";

// Add some text to the new cells:
cell1.innerHTML = "<input  class = 'form-control' name = 'cantidad"+filas+"'     id = 'cantidad"+filas+"' type= 'text' style='width:100%' value = '1' > ";
cell2.innerHTML = "<input  class = 'form-control' name = 'descripcion"+filas+"'  id = 'descripcion"+filas+"' type= 'text' style='width:100%' value = '"+texto_premio+"' > <input type = 'hidden' name = 'id_premio"+filas+"' value = '"+premio+"'> ";
cell3.innerHTML = "<select class = 'form-control' name = 'select"+filas+"'       id = 'select"+filas+"' style='width:100%'></select>";
cell4.innerHTML = "<input class = 'form-control' name = 'monto"+filas+"'        id = 'monto' type= 'text' style='width:100%' >";
cell5.innerHTML = "<i class= 'fa fa-times-circle btn btn-danger' onclick = 'eliminar_fila(this)'></i>";

$(document).ready(function(){
$('input[id="monto"]').mask('000,000,000', {reverse: true});
});


var x = document.getElementById("select"+filas);
var option = document.createElement("option");
option.text = "EFECTIVO";
option.value = "EFECTIVO";
x.add(option);

var option2 = document.createElement("option");
option2.text = "ESPECIES";
option2.value = "ESPECIES";
x.add(option2);


}

$(document).ready(function(){
$('input[id="monto"]').mask('000,000,000', {reverse: true});
});

</script>

<form method="POST">

<section style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >Asignacion de premios de loteria menor</h2>
<h4 align='center'>Sorteo No. <?php echo $sorteo; ?> Fecha de Sorteo <?php echo $fecha_sorteo; ?></h4>
<br>
</section>
<br>


<?php

echo "<input type = 'hidden' name = 'id_sorteo' value = '" . $id_sorteo . "'>";

$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE  id = '$id_sorteo' ");
$ob_sorteo = mysqli_fetch_object($info_sorteo);
$num_sorteo = $ob_sorteo->no_sorteo_men;
$descripcion = $ob_sorteo->descripcion_sorteo_men;
$premios_asignados = $ob_sorteo->premios_asignados;

$ultimo_sorteo_asignado = mysqli_query($conn, "SELECT MAX(id) as id FROM sorteos_menores WHERE  premios_asignados = 'SI' ");

if (mysqli_num_rows($ultimo_sorteo_asignado) > 0) {
	$ob_ultimo = mysqli_fetch_object($ultimo_sorteo_asignado);
	$ultimo_id = $ob_ultimo->id;
}

$premios = mysqli_query($conn, "SELECT * FROM premios_menores  ");

?>






<div class="card" style="margin-left: 10px;margin-right: 10px">
<div align="center" class="card-header">


<div class="input-group" style="margin:10px 0px 10px 0px; width: 50%" >
<div class="input-group-prepend"><span style="width: 140px" class="input-group-text">Premio: </span></div>
<select class = 'form-control' name="premio" id="premio"  >
<?php
while ($premio = mysqli_fetch_array($premios)) {
	echo "<option value = '" . $premio['id'] . "'>" . $premio['descripcion_premios'] . "</option>";
}
?>
</select>
<div class="input-group-prepend"><span class="btn btn-success" onclick="agregar_premio()" name="agregar_premio">Agregar</span></div>
</div>


</div>

<div class="card-body">

<table id="tabla_premios" class="table table-bordered">
<tr>
	<th width="10%">Cant.</th>
	<th width="50%">Descripcion</th>
	<th width="15%">Tipo de Premio</th>
	<th width="15%">Premio</th>
	<th width="10%">Accion</th>
</tr>

<?php

if ($ultimo_id != '') {

	$premios_anteriores = mysqli_query($conn, "SELECT COUNT(*) conteo, a.premios_menores_id, a.monto , b.descripcion_premios FROM sorteos_menores_premios as a INNER JOIN premios_menores as b ON a.premios_menores_id = b.id WHERE a.sorteos_menores_id = '$ultimo_id'  GROUP BY a.monto , a.tipo_premio, b.descripcion_premios ORDER BY a.monto DESC ");

	$i = 1;
	while ($row4 = mysqli_fetch_array($premios_anteriores)) {
		$id_premio = $row4['premios_menores_id'];
		$descripcion = $row4['descripcion_premios'];
		$monto = $row4['monto'];
		$cont = $row4['conteo'];

		echo "<tr>";
		echo "<td><input class = 'form-control' name = 'cantidad" . $i . "'     id = 'cantidad" . $i . "' type= 'text' style='width:100%' value = '" . $cont . "' > </td>";
		echo "<td><input class = 'form-control' name = 'descripcion" . $i . "'  id = 'descripcion" . $i . "' type= 'text' style='width:100%' value = '" . $descripcion . "' > <input type = 'hidden' name = 'id_premio" . $i . "' value = '" . $id_premio . "'></td>";
		echo "<td><select class = 'form-control' name = 'select" . $i . "'       id = 'select" . $i . "' style='width:100%'>
		<option value = 'EFECTIVO' >EFECTIVO</option>
		<option value = 'ESPECIES' >ESPECIES</option>
		</select></td>";
		echo "<td><input class = 'form-control' name = 'monto" . $i . "' value = '" . $monto . "'  id = 'monto' type= 'text' style='width:100%' ></td>";
		echo "<td align = 'center'><i class= 'fa fa-times-circle btn btn-danger' onclick = 'eliminar_fila(this)'></i></td>";
		echo "</tr>";

		$i++;
	}

	echo '<input type="hidden" id="filas" name="filas" value = "' . $i . '" >';

} else {

	echo '<input type="hidden" id="filas" name="filas"  >';

}

?>

	</table>

</div>

<div class="card-footer">
<?php
echo "<p align = 'center'><button class = 'btn btn-primary' type = 'submit' onclick='waiting()' name = 'guardar'>Guardar</button></p>";
?>
</div>
</div>




</form>


<?php

if (isset($_POST['guardar'])) {
	$id_sorteo = $_POST['id_sorteo'];
	$filas = $_POST['filas'];

	$k = 1;
	$bandera = true;

	while ($k <= $filas) {
		if (isset($_POST['cantidad' . $k])) {
			$id_premio = $_POST['id_premio' . $k];
			$monto = $_POST['monto' . $k];
			$tipo = $_POST['select' . $k];
			$cantidad = $_POST['cantidad' . $k];
			$monto = str_replace(",", "", $monto);

			$j = 1;
			while ($j <= $cantidad) {
				if (mysqli_query($conn, "INSERT INTO sorteos_menores_premios (sorteos_menores_id,premios_menores_id,tipo_premio,monto) VALUES ('$id_sorteo','$id_premio','$tipo','$monto') ") === false) {
					$bandera = false;
					echo mysqli_error($conn);
				}

				$j++;
			}
		}
		$k++;
	}

	if ($bandera == true) {

		mysqli_query($conn, "UPDATE sorteos_menores SET premios_asignados = 'SI' WHERE id = '$id_sorteo' ");

		?>
<script type="text/javascript">
swal({
title: "",
  text: "Premios asignados correctamente",
  type: "success"
})
.then(() => {
    window.location.href = './mto_premios_menores_pendientes.php';
});
</script>
<?php
} else {
		?>
<script type="text/javascript">
  swal({
  title: "",
   text: "Error inesperado por favor vuelva a intentarlo",
    type: "error"
  });
</script>
<?php
}

}

?>
