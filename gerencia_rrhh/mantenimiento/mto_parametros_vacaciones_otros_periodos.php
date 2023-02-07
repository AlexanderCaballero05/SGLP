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
	$cantidad_dias = $_POST['cantidad_dias'];
	$porcentaje_vacaciones = $_POST['porcentaje_vacaciones'];
	$estado = "A";

	$periodo_inicial_vigencia = $_POST['periodo_inicial_vigencia'];
	$periodo_final_vigencia = $_POST['periodo_final_vigencia'];

	if (mysqli_query($conn, "INSERT INTO rr_hh_mto_vacaciones (de_dia, de_mes, de_year, a_dia, a_mes, a_year, dias_vacaciones, valor_porcentual_sueldo, tipo_contratacion , periodo_inicial_vigencia, periodo_final_vigencia) VALUES ('$de_dia','$de_mes','$de_year','$a_dia','$a_mes','$a_year','$cantidad_dias','$porcentaje_vacaciones','$id_tipo_contrato' ,'$periodo_inicial_vigencia' ,'$periodo_final_vigencia') ") === TRUE) {
		echo "<div class = 'alert alert-info'>Parametro de vacaciones registrado correctamente.</div>";
	} else {
		echo "<div class = 'alert alert-danger'>Error inesperado, por favor intente nuevamente o notifique a la unidad de informatica si el problema persiste.</div>";
		echo mysqli_error($conn);
	}

}
// CODIGO DE REGISTRO DE NUEVOS PARAMETROS //
/////////////////////////////////////////////

/////////////////////////////////////////////
// CODIGO DE EDICION DE PARAMETROS //
if (isset($_POST['guardar_edit'])) {

	$id_tipo_contrato = $_POST['id_tipo_contratacion_edit'];
	$id_registro_edit = $_POST['id_registro_edit'];

	$de_dia_edit = $_POST['de_dia_edit'];
	$de_mes_edit = $_POST['de_mes_edit'];
	$de_year_edit = $_POST['de_year_edit'];
	$a_dia_edit = $_POST['a_dia_edit'];
	$a_mes_edit = $_POST['a_mes_edit'];
	$a_year_edit = $_POST['a_year_edit'];
	$dias_vacaciones_edit = $_POST['dias_vacaciones_edit'];
	$valor_porcentual_sueldo_edit = $_POST['valor_porcentual_sueldo_edit'];

	$periodo_inicial_vigencia_edit = $_POST['periodo_inicial_vigencia_edit'];
	$periodo_final_vigencia_edit = $_POST['periodo_final_vigencia_edit'];
	$estado = "A";

	if (mysqli_query($conn, "UPDATE rr_hh_mto_vacaciones SET de_dia = '$de_dia_edit', de_mes = '$de_mes_edit', de_year = '$de_year_edit', a_dia = '$a_dia_edit', a_mes = '$a_mes_edit', a_year = '$a_year_edit', dias_vacaciones = '$dias_vacaciones_edit', valor_porcentual_sueldo = '$valor_porcentual_sueldo_edit', periodo_inicial_vigencia = '$periodo_inicial_vigencia_edit', periodo_final_vigencia = '$periodo_final_vigencia_edit' WHERE id = '$id_registro_edit' ") === TRUE) {
		echo "<div class = 'alert alert-info'>Cambio realizado correctamente.</div>";
	} else {
		echo "<div class = 'alert alert-danger'>Error inesperado, por favor intente nuevamente o notifique a la unidad de informatica si el problema persiste.</div>";
		echo mysqli_error($conn);
	}

}
// CODIGO DE EDICION DE PARAMETROS //
/////////////////////////////////////////////

?>

<script type="text/javascript">
	function cargar_id_contratacion(id_contratacion){
		document.getElementById('id_tipo_contratacion').value = id_contratacion;
	}


	function cargar_datos_edicion(id_tipo_contratacion_edit, id_registro_edit, de_year_edit, de_mes_edit, de_dia_edit, a_year_edit, a_mes_edit, a_dia_edit, dias_vacaciones_edit, valor_porcentual_sueldo_edit, periodo_inicial_vigencia_edit, periodo_final_vigencia_edit){
		document.getElementById('id_tipo_contratacion_edit').value = id_tipo_contratacion_edit;
		document.getElementById('id_registro_edit').value = id_registro_edit;
		document.getElementById('de_year_edit').value = de_year_edit;
		document.getElementById('de_mes_edit').value = de_mes_edit;
		document.getElementById('de_dia_edit').value = de_dia_edit;
		document.getElementById('a_year_edit').value = a_year_edit;
		document.getElementById('a_mes_edit').value = a_mes_edit;
		document.getElementById('a_dia_edit').value = a_dia_edit;
		document.getElementById('dias_vacaciones_edit').value = dias_vacaciones_edit;
		document.getElementById('valor_porcentual_sueldo_edit').value = valor_porcentual_sueldo_edit;
		document.getElementById('periodo_inicial_vigencia_edit').value = periodo_inicial_vigencia_edit;
		document.getElementById('periodo_final_vigencia_edit').value = periodo_final_vigencia_edit;
	}

</script>


<br>

<ul class="nav nav-tabs">
 <li class="nav-item">
    <a  class="nav-link active" href="./screen_mto_parametros_vacaciones.php">Parametros Vigentes</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" style="background-color:#ededed;"  href="#" >Parametros de Otros Periodos</a>
  </li>
</ul>


<section style="color:rgb(63,138,214);background-color:#ededed;">
<br>
<h3  align="center" style="color:black;" ><b>MANTENIMIENTO DE PARAMETROS DE VACACIONES NO VIGENTES</b></h3>
<br>
</section>
<br>

<?php

while ($reg_tipo_contrataciones = mysqli_fetch_array($c_tipo_contrataciones)) {
	$id_tipo_contratacion = $reg_tipo_contrataciones['id'];

	$c_parametros_en_contrato = mysqli_query($conn, "SELECT * FROM rr_hh_mto_vacaciones WHERE estado = 'A' AND tipo_contratacion = '$id_tipo_contratacion' AND periodo_final_vigencia != '' ORDER BY de_year ASC ");

	?>

<div class="row">
<div class="col">
	<div class="card" style="margin-right: 5px;margin-left: 5px;">
		<div class="card-header bg-success text-white" >
			<h style="text-align: center"><?php echo $reg_tipo_contrataciones['descripcion']; ?></h>

<button class="btn btn-success text-light fa fa-plus" data-toggle="modal" href="#modal-new" onclick="cargar_id_contratacion('<?php echo $id_tipo_contratacion; ?>')" type="button" style="float: right">
</button>

		</div>
		<div class="card-body">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th >Rango Inicial Antiguedad </th>
						<th >Rango Final Antiguedad </th>
						<th>Cantidad Dias</th>
						<th>% Sueldo Prom. Base</th>
						<th  colspan="2">Vigencia</th>
						<th>Editar</th>
					</tr>
				</thead>
				<tbody>
					<?php
while ($reg_parametros_en_contrato = mysqli_fetch_array($c_parametros_en_contrato)) {

		$id_registro = $reg_parametros_en_contrato['id'];
		$de_year = $reg_parametros_en_contrato['de_year'];
		$de_mes = $reg_parametros_en_contrato['de_mes'];
		$de_dia = $reg_parametros_en_contrato['de_dia'];

		$a_year = $reg_parametros_en_contrato['a_year'];
		$a_mes = $reg_parametros_en_contrato['a_mes'];
		$a_dia = $reg_parametros_en_contrato['a_dia'];

		$dias_vacaciones = $reg_parametros_en_contrato['dias_vacaciones'];
		$valor_porcentual_sueldo = $reg_parametros_en_contrato['valor_porcentual_sueldo'];
		$periodo_inicial_vigencia = $reg_parametros_en_contrato['periodo_inicial_vigencia'];
		$periodo_final_vigencia = $reg_parametros_en_contrato['periodo_final_vigencia'];

		echo "<tr>";
		if ($reg_parametros_en_contrato['a_dia'] == '' AND $reg_parametros_en_contrato['a_mes'] == '' AND $reg_parametros_en_contrato['a_year'] == '') {
			echo "<td> De " . $reg_parametros_en_contrato['de_year'] . " años - " . $reg_parametros_en_contrato['de_mes'] . " meses - " . $reg_parametros_en_contrato['de_dia'] . " dias  </td><td>En adelante";
		} else {
			echo "<td> De " . $reg_parametros_en_contrato['de_year'] . " años - " . $reg_parametros_en_contrato['de_mes'] . " meses - " . $reg_parametros_en_contrato['de_dia'] . " dias</td>  <td> " . $reg_parametros_en_contrato['a_year'] . " años " . $reg_parametros_en_contrato['a_mes'] . " meses " . $reg_parametros_en_contrato['a_dia'] . " dias </td>";
		}

		echo "<td>" . $reg_parametros_en_contrato['dias_vacaciones'] . "</td>";
		echo "<td>" . $reg_parametros_en_contrato['valor_porcentual_sueldo'] . "</td>";
		echo "<td>" . $reg_parametros_en_contrato['periodo_inicial_vigencia'] . "</td>";

		if ($reg_parametros_en_contrato['periodo_final_vigencia'] == "") {

			echo "<td>En adelante</td>";
		} else {

			echo "<td>" . $reg_parametros_en_contrato['periodo_final_vigencia'] . "</td>";
		}

		?>
		<td align = 'center'><span class = 'btn btn-info fa fa-edit' data-toggle="modal" href="#modal-edit" onclick="cargar_datos_edicion('<?php echo $id_tipo_contratacion; ?>','<?php echo $id_registro; ?>','<?php echo $de_year; ?>','<?php echo $de_mes; ?>','<?php echo $de_dia; ?>','<?php echo $a_year; ?>','<?php echo $a_mes; ?>','<?php echo $a_dia; ?>','<?php echo $dias_vacaciones; ?>','<?php echo $valor_porcentual_sueldo; ?>','<?php echo $periodo_inicial_vigencia; ?>','<?php echo $periodo_final_vigencia; ?>')"></span></td>
		<?php
echo "</tr>";
	}
	?>
				</tbody>
			</table>
		</div>
	</div>
</div>

</div>

<br>

<?php

}

?>





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
<div class="input-group-prepend"><span class="input-group-text"># de Dias Vacaciones: </span></div>
<input type="text" class="form-control" id="cantidad_dias" name="cantidad_dias"  >
<div class="input-group-prepend"><span class="input-group-text">Porcentaje del Sueldo Base: </span></div>
<input type="text" class="form-control" id="porcentaje_vacaciones" name="porcentaje_vacaciones" >
</div>


<hr>

<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Vigencia del Año: </span></div>
<input type="text" class="form-control" id="periodo_inicial_vigencia" name="periodo_inicial_vigencia"  >
<div class="input-group-prepend"><span class="input-group-text">al Año: </span></div>
<input type="text" class="form-control" id="periodo_final_vigencia" name="periodo_final_vigencia" >
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




<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->
<!-- $$$$$$$$$$$$$$$$$$$$ MODAL DE EDIT REG $$$$$$$$$$$$$$$$$$$$$$ -->



<div class="modal fade" role="dialog" tabindex="-1" id="modal-edit">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">

<div class="modal-header bg-success text-white" id="modal-header" style="background-color:rgb(255,255,255);">
<h4 class="text-center modal-title" id="modal-heading" style="width:100%;">EDICION DE PARAMETRO</h4>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>

<div class="modal-body" style="background-color:#f8f8f8;">

<input type="hidden" name="id_tipo_contratacion_edit" id="id_tipo_contratacion_edit" >
<input type="hidden" name="id_registro_edit" id="id_registro_edit" >


Inicio de rango de antiguedad
<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Año: </span></div>
<input type="number" min="0" class="form-control" id="de_year_edit" name="de_year_edit">
<div class="input-group-prepend"><span class="input-group-text">Mes: </span></div>
<input type="number" min="0" class="form-control" id="de_mes_edit" name="de_mes_edit">
<div class="input-group-prepend"><span class="input-group-text">día: </span></div>
<input type="number" min="0" class="form-control" id="de_dia_edit" name="de_dia_edit">
</div>

Fin de rango de antiguedad
<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Año:</span></div>
<input type="number" min="0" class="form-control" id="a_year_edit" name="a_year_edit">
<div class="input-group-prepend"><span class="input-group-text">Mes:</span></div>
<input type="number" min="0" class="form-control" id="a_mes_edit" name="a_mes_edit">
<div class="input-group-prepend"><span class="input-group-text">día: </span></div>
<input type="number" min="0" class="form-control" id="a_dia_edit" name="a_dia_edit">
</div>

<hr>


<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text"># de Dias Vacaciones: </span></div>
<input type="text" class="form-control" id="dias_vacaciones_edit" name="dias_vacaciones_edit"  >
<div class="input-group-prepend"><span class="input-group-text">Porcentaje del Sueldo Base: </span></div>
<input type="text" class="form-control" id="valor_porcentual_sueldo_edit" name="valor_porcentual_sueldo_edit" >
</div>

<hr>

<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Vigencia del Año: </span></div>
<input type="text" class="form-control" id="periodo_inicial_vigencia_edit" name="periodo_inicial_vigencia_edit"  >
<div class="input-group-prepend"><span class="input-group-text">al Año: </span></div>
<input type="text" class="form-control" id="periodo_final_vigencia_edit" name="periodo_final_vigencia_edit" >
</div>


</div>

<div class="modal-footer" id="modal-footer" style="background-color:rgb(255,255,255);">
<button name="guardar_edit" style="margin-top: 10px" class="btn btn-info" type="submit">Guardar Edición</button>
</div>


</div>
</div>
</div>


<!-- $$$$$$$$$$$$$$$$$$$$ MODAL DE EDIT REG $$$$$$$$$$$$$$$$$$$$$$ -->
<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->

</form>