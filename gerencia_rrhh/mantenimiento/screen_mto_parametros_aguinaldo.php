<?php
require '../../template/header.php';

$c_tipo_contrataciones = mysqli_query($conn, "SELECT * FROM rr_hh_mto_contrataciones ");

/////////////////////////////////////////////
// CODIGO DE REGISTRO DE NUEVOS PARAMETROS //
if (isset($_POST['guardar_nuevo'])) {

	$id_tipo_contrato = $_POST['id_tipo_contratacion'];

	$de_dia = $_POST['de_dia'];
	$de_mes = $_POST['de_mes'];
	$de_year = $_POST['de_año'];
	$a_dia = $_POST['a_dia'];
	$a_mes = $_POST['a_mes'];
	$a_year = $_POST['a_año'];
	$porcentaje_vacaciones = $_POST['porcentaje_vacaciones'];
	$estado = "A";

	if (mysqli_query($conn, "INSERT INTO rr_hh_mto_aguinaldos (de_dia, de_mes, de_year, a_dia, a_mes, a_year, valor_porcentual_sueldo, tipo_contratacion) VALUES ('$de_dia','$de_mes','$de_year','$a_dia','$a_mes','$a_year','$porcentaje_vacaciones','$id_tipo_contrato') ") === TRUE) {
		echo "<div class = 'alert alert-info'>Parametro de aguinaldo registrado correctamente.</div>";
	} else {
		echo "<div class = 'alert alert-danger'>Error inesperado, por favor intente nuevamente o notifique a la unidad de informatica si el problema persiste.</div>";
		echo mysqli_error($conn);
	}

}
// CODIGO DE REGISTRO DE NUEVOS PARAMETROS //
/////////////////////////////////////////////

?>

<script type="text/javascript">
	function cargar_id_contratacion(id_contratacion){
		document.getElementById('id_tipo_contratacion').value = id_contratacion;
	}
</script>


<section style="color:rgb(63,138,214);background-color:#ededed;">
<br>
<h3  align="center" style="color:black;" ><b>MANTENIMIENTO DE PARAMETROS DE AGUINALDOS</b></h3>
<br>
</section>
<br>

<div class="row">
<?php

while ($reg_tipo_contrataciones = mysqli_fetch_array($c_tipo_contrataciones)) {
	$id_tipo_contratacion = $reg_tipo_contrataciones['id'];

	$c_parametros_en_contrato = mysqli_query($conn, "SELECT * FROM rr_hh_mto_aguinaldos WHERE estado = 'A' AND tipo_contratacion = '$id_tipo_contratacion' ");

	?>

<div class="col col-md-6">
	<div class="card">
		<div class="card-header bg-success text-white" >
			<h style="text-align: center"><?php echo $reg_tipo_contrataciones['descripcion']; ?></h>

<button class="btn btn-success text-light fa fa-plus" data-toggle="modal" href="#modal-new" onclick="cargar_id_contratacion('<?php echo $id_tipo_contratacion; ?>')" type="button" style="float: right">
</button>

		</div>
		<div class="card-body">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th width="35%">Rango Inicial Antiguedad </th>
						<th width="35%">Rango Final Antiguedad </th>
						<th>% Sueldo Prom. Base</th>
					</tr>
				</thead>
				<tbody>
					<?php
while ($reg_parametros_en_contrato = mysqli_fetch_array($c_parametros_en_contrato)) {
		echo "<tr>";
		if ($reg_parametros_en_contrato['a_dia'] == '' AND $reg_parametros_en_contrato['a_mes'] == '' AND $reg_parametros_en_contrato['a_year'] == '') {
			echo "<td> De " . $reg_parametros_en_contrato['de_year'] . " años - " . $reg_parametros_en_contrato['de_mes'] . " meses - " . $reg_parametros_en_contrato['de_dia'] . " dias  </td><td>En adelante";
		} else {
			echo "<td> De " . $reg_parametros_en_contrato['de_year'] . " años - " . $reg_parametros_en_contrato['de_mes'] . " meses - " . $reg_parametros_en_contrato['de_dia'] . " dias</td>  <td> " . $reg_parametros_en_contrato['a_year'] . " años " . $reg_parametros_en_contrato['a_mes'] . " meses " . $reg_parametros_en_contrato['a_dia'] . " dias </td>";
		}
		echo "<td>" . $reg_parametros_en_contrato['valor_porcentual_sueldo'] . "</td>";
		echo "</tr>";
	}
	?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php

}

?>

</div>




<form method="POST">

<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->
<!-- $$$$$$$$$$$$$$$$$$$$ MODAL DE NUEVO REG $$$$$$$$$$$$$$$$$$$$$$ -->



<div class="modal fade" role="dialog" tabindex="-1" id="modal-new">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">

<div class="modal-header bg-success text-white" id="modal-header" style="background-color:rgb(255,255,255);">
<h4 class="text-center modal-title" id="modal-heading" style="width:100%;">NUEVO PARAMETRO</h4>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>

<div class="modal-body" style="background-color:#f8f8f8;">

<input type="hidden" name="id_tipo_contratacion" id="id_tipo_contratacion" >


Inicio de rango de antiguedad
<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Año: </span></div>
<input type="number" min="0" class="form-control" id="de_año" name="de_año">
<div class="input-group-prepend"><span class="input-group-text">Mes: </span></div>
<input type="number" min="0" class="form-control" id="de_mes" name="de_mes">
<div class="input-group-prepend"><span class="input-group-text">día: </span></div>
<input type="number" min="0" class="form-control" id="de_dia" name="de_dia">
</div>

Fin de rango de antiguedad
<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Año:</span></div>
<input type="number" min="0" class="form-control" id="a_año" name="a_año">
<div class="input-group-prepend"><span class="input-group-text">Mes:</span></div>
<input type="number" min="0" class="form-control" id="a_mes" name="a_mes">
<div class="input-group-prepend"><span class="input-group-text">día: </span></div>
<input type="number" min="0" class="form-control" id="a_dia" name="a_dia">
</div>

<hr>


<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Porcentaje del Sueldo Prom. Base: </span></div>
<input type="text" class="form-control" id="porcentaje_vacaciones" name="porcentaje_vacaciones" >
</div>

</div>

<div class="modal-footer" id="modal-footer" style="background-color:rgb(255,255,255);">
<button name="guardar_nuevo" style="margin-top: 10px" class="btn btn-info" type="submit">Guardar</button>
</div>


</div>
</div>
</div>


<!-- $$$$$$$$$$$$$$$$$$$$ MODAL DE NUEVO REG $$$$$$$$$$$$$$$$$$$$$$ -->
<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->

</form>