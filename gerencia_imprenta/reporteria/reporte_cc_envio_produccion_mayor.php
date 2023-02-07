<?php
require '../../template/header.php';
require './cc_envio_produccion_mayor_db.php';

$sorteos_mayores = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE control_calidad = 'SI' ORDER BY no_sorteo_may DESC ");

if ($sorteos_mayores === false) {
	echo mysqli_error($conn);
}

?>



<script type="text/javascript">
function confirar_apertura(id){


swal({
  title: "¿Esta seguro de aperturar revision?",
  text: "Al hacerlo todas las revisiones posteriores se eliminaran.",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {

document.getElementById('cancelar_finalizacion'+id).click();

  } else {

  }
});


}
</script>




<form method="POST">


<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2 align="center" style="color:black; "  >CONTROL DE REPOSICIONES LOTERIA MAYOR</h2>
<br>
</section>

<br>


<div class="card" style="margin-right: 10px; margin-left: 10px">
<div class="card-header" align="center" id="non-printable">

<div class="input-group" style="margin:10px 0px 10px 0px; width: 50%" >
<div class="input-group-prepend"><span style="width: 200px" class="input-group-text">Seleccione un sorteo: </span></div>
<select name="sorteo" class="form-control" style="width:10%">
<?php
while ($sorteo = mysqli_fetch_array($sorteos_mayores)) {
	echo "<option value = '" . $sorteo['id'] . "'>" . $sorteo['no_sorteo_may'] . "</option>";
}
?>
</select>
<input style="margin-left: 5px" name="seleccionar" type="submit" class="btn btn-primary" value="Seleccionar">
</div>

</div>
<div class="card-body">


<?php
if (isset($_POST['seleccionar'])) {
	$id_sorteo = $_POST['sorteo'];

	echo "<h3 align = 'center'class = 'alert alert-info' >Sorteo Seleccionado " . $id_sorteo . "</h3>";

	echo "<div class = 'card'>";
	echo "<div class = 'card-header bg-primary text-white' >Reposicion 1</div>";

	echo "<div class = 'card-body' >";

	$consulta_detalle = mysqli_query($conn, "SELECT a.numero as num_lista ,a.id_revisor, a.billete_inicial, a.billete_final , a.billete_final -  a.billete_inicial + 1 as cantidad  , b.nombre_completo, (select count(id) from cc_revisores_sorteos_mayores_control as c where c.id_sorteo = a.id_sorteo AND c.id_revisor = a.id_revisor AND numero_revision = 2 AND especial = 'NO') as conteo_reposicion , (select count(id) from cc_revisores_sorteos_mayores_control as d where d.id_sorteo = a.id_sorteo AND d.id_revisor = a.id_revisor AND especial = 'SI' AND numero_revision = 2) as conteo_reposicion_re  FROM cc_revisores_sorteos_mayores as a INNER JOIN pani_usuarios as b ON a.id_revisor = b.id WHERE a.id_sorteo = '$id_sorteo' ");

	if ($consulta_detalle === FALSE) {
		echo mysqli_error($conn);
	}

	echo "<table class = 'table table-bordered'>";
	echo "<tr>";
	echo "<th rowspan = '2'>Lista #</th>";
	echo "<th rowspan = '2'>Revisor</th>";
	echo "<th colspan = '3'>Asignacion</th>";
	echo "<th rowspan = '2'>Rep.</th>";
	echo "<th rowspan = '2'>RE</th>";
	echo "<th rowspan = '2'>Total</th>";

	echo "</tr>";

	echo "<tr>";
	echo "<th>Billete I</th>";
	echo "<th>Billete F</th>";
	echo "<th>Cantidad</th>";
	echo "</tr>";

	$tt_normal = 0;
	$tt_re = 0;

	while ($reg_consulta_detalle = mysqli_fetch_array($consulta_detalle)) {

		echo "<input type = 'hidden' name = 'num_lista_1[]' value = '" . $reg_consulta_detalle['num_lista'] . "'  >";
		echo "<input type = 'hidden' name = 'id_revisor_1[]' value = '" . $reg_consulta_detalle['id_revisor'] . "'  >";

		$tt = $reg_consulta_detalle['conteo_reposicion'] + $reg_consulta_detalle['conteo_reposicion_re'];

		$tt_normal += $reg_consulta_detalle['conteo_reposicion'];
		$tt_re += $reg_consulta_detalle['conteo_reposicion_re'];

		echo "<tr>";
		echo "<td>" . $reg_consulta_detalle['num_lista'] . "</td>";
		echo "<td>" . $reg_consulta_detalle['nombre_completo'] . "</td>";
		echo "<td>" . $reg_consulta_detalle['billete_inicial'] . "</td>";
		echo "<td>" . $reg_consulta_detalle['billete_final'] . "</td>";
		echo "<td>" . $reg_consulta_detalle['cantidad'] . "</td>";
		echo "<td>" . $reg_consulta_detalle['conteo_reposicion'] . "</td>";
		echo "<td>" . $reg_consulta_detalle['conteo_reposicion_re'] . "</td>";
		echo "<td>" . $tt . "</td>";
		echo "</tr>";
	}

	$tt_revision = $tt_normal + $tt_re;
	echo "<tr>";
	echo "<th colspan = '5'>TOTALES</th>";
	echo "<th>" . number_format($tt_normal) . "</th>";
	echo "<th>" . number_format($tt_re) . "</th>";
	echo "<th>" . number_format($tt_revision) . "</th>";

	echo "</tr>";

	echo "</table>";

	echo "</div>";

	echo "<div class = 'card-footer' align = 'center'>";

	$consulta_fin = mysqli_query($conn, "SELECT * FROM cc_produccion_mayor WHERE id_sorteo = '$id_sorteo' AND numero_revision = '1' LIMIT 1 ");

	if (mysqli_num_rows($consulta_fin) > 0) {

		$ob_consulta_fin = mysqli_fetch_object($consulta_fin);
		$fecha_finalizacion = $ob_consulta_fin->fecha_cierre_revisor;

		$fecha_finalizacion = date_create($fecha_finalizacion);
		$fecha_finalizacion = date_format($fecha_finalizacion, 'd-m-Y');

		echo "<div class = 'alert alert-success'>
Reposicion 1 finalizada
<br>
Fecha de finalizacion y envio a produccion: " . $fecha_finalizacion . "
</div>";

	} else {
		$concat_parametros = $id_sorteo . '_1';
		echo "<button class = 'btn btn-danger' type = 'submit' name = 'finalizar_reposicion' value = '" . $concat_parametros . "' >FINALIZAR REPOSICION 1</button>";
	}

	echo "</div>";

	echo "</div>";

	echo "<br>";
	echo "<br>";

/////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////// NUMERO DE REVISION /////////////////////////////////

	$revisiones_realizadas = mysqli_query($conn, "SELECT numero_revision  FROM cc_revisores_sorteos_mayores_control WHERE id_sorteo = '$id_sorteo' AND estado != 'APROBADO' GROUP BY numero_revision ORDER BY numero_revision ASC ");

	while ($reg_revisiones = mysqli_fetch_array($revisiones_realizadas)) {

		$numero_revision = $reg_revisiones['numero_revision'];

		echo "<div class = 'card'>";
		echo "<div class = 'card-header bg-primary text-white' >Reposicion " . $numero_revision . "</div>";

		echo "<div class = 'card-body' >";

		$consulta_detalle = mysqli_query($conn, "SELECT a.numero as num_lista ,a.id_revisor, a.billete_inicial, a.billete_final , a.billete_final -  a.billete_inicial + 1 as cantidad  , b.nombre_completo, (select count(id) from cc_revisores_sorteos_mayores_control as c where c.id_sorteo = a.id_sorteo AND c.id_revisor = a.id_revisor AND numero_revision = '$numero_revision' AND estado = 'REPROBADO' and especial != 'SI') as conteo_reposicion , (select count(id) from cc_revisores_sorteos_mayores_control as d where d.id_sorteo = a.id_sorteo AND d.id_revisor = a.id_revisor AND especial = 'SI' AND numero_revision  = '$numero_revision' AND estado = 'REPROBADO') as conteo_reposicion_re  , (select count(id) from cc_revisores_sorteos_mayores_control as d where d.id_sorteo = a.id_sorteo AND d.id_revisor = a.id_revisor  AND numero_revision  = '$numero_revision' AND estado = 'PENDIENTE') as  conteo_pendiente   FROM cc_revisores_sorteos_mayores as a INNER JOIN pani_usuarios as b ON a.id_revisor = b.id WHERE a.id_sorteo = '$id_sorteo' ");

		if ($consulta_detalle === FALSE) {
			echo mysqli_error($conn);
		}

		echo "<table class = 'table table-bordered'>";
		echo "<tr>";
		echo "<th >Lista #</th>";
		echo "<th >Revisor</th>";
		echo "<th >Rep.</th>";
		echo "<th >RE</th>";
		echo "<th >Total</th>";
		echo "</tr>";

		$tt_normal = 0;
		$tt_re = 0;

		while ($reg_consulta_detalle = mysqli_fetch_array($consulta_detalle)) {

			if ($reg_consulta_detalle['conteo_reposicion'] > 0 OR $reg_consulta_detalle['conteo_pendiente'] > 0) {

				echo "<input type = 'hidden' name = 'num_lista_" . $numero_revision . "[]' value = '" . $reg_consulta_detalle['num_lista'] . "'  >";
				echo "<input type = 'hidden' name = 'id_revisor_" . $numero_revision . "[]' value = '" . $reg_consulta_detalle['id_revisor'] . "'  >";

			}

			$tt = $reg_consulta_detalle['conteo_reposicion'] + $reg_consulta_detalle['conteo_reposicion_re'];

			$tt_normal += $reg_consulta_detalle['conteo_reposicion'];
			$tt_re += $reg_consulta_detalle['conteo_reposicion_re'];

			echo "<tr>";
			echo "<td>" . $reg_consulta_detalle['num_lista'] . "</td>";
			echo "<td>" . $reg_consulta_detalle['nombre_completo'] . "</td>";
			echo "<td>" . $reg_consulta_detalle['conteo_reposicion'] . "</td>";
			echo "<td>" . $reg_consulta_detalle['conteo_reposicion_re'] . "</td>";
			echo "<td>" . $tt . "</td>";

			echo "</tr>";
		}

		$tt_revision = $tt_normal + $tt_re;
		echo "<tr>";
		echo "<th colspan = '2'>TOTALES</th>";
		echo "<th>" . number_format($tt_normal) . "</th>";
		echo "<th>" . number_format($tt_re) . "</th>";
		echo "<th>" . number_format($tt_revision) . "</th>";

		echo "</tr>";

		echo "</table>";

		echo "</div>";

		echo "<div class = 'card-footer' align = 'center'>";

		$consulta_fin = mysqli_query($conn, "SELECT * FROM cc_produccion_mayor WHERE id_sorteo = '$id_sorteo' AND numero_revision = '$numero_revision' LIMIT 1 ");

		if (mysqli_num_rows($consulta_fin) > 0) {

			$ob_consulta_fin = mysqli_fetch_object($consulta_fin);
			$fecha_finalizacion = $ob_consulta_fin->fecha_cierre_revisor;

			$fecha_finalizacion = date_create($fecha_finalizacion);
			$fecha_finalizacion = date_format($fecha_finalizacion, 'd-m-Y');

			echo "<div class = 'alert alert-success'>
Reposicion " . $numero_revision . " finalizada
<br>
Fecha de finalizacion y envio a produccion: " . $fecha_finalizacion . "
</div>";

		} else {
			$concat_parametros = $id_sorteo . '_' . $numero_revision;
			echo "<button class = 'btn btn-danger' type = 'submit' name = 'finalizar_reposicion' value = '" . $concat_parametros . "' >FINALIZAR REPOSICION " . $numero_revision . "</button>";
		}

		echo "</div>";

		echo "</div>";

		echo "<br>";
		echo "<br>";

	}

//////////////////////////////// NUMERO DE REVISION /////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////

}
?>

</div>
</div>



</form>
