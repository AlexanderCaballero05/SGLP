<?php

require '../../template/header.php';

?>




<script type="text/javascript">

jQuery(function($){
$("#identidad").mask("9999-9999-99999", { placeholder: "____-____-_____" });
});



function calcular_dias(){

fecha_i = document.getElementById('fecha_inicial_vacaciones').value;
fecha_f = document.getElementById('fecha_final_vacaciones').value;
id_periodo = document.getElementById('id_periodo').value;
identidad = document.getElementById('identidad_o').value;

if (fecha_i === "" || fecha_f === "") {
document.getElementById('btn_agregar_vacaciones').disabled = true;
}else{
if (fecha_i <= fecha_f) {

accion = 1;
token = Math.random();
dias_disponibles =  document.getElementById('dias_disponibles').value;
consulta = "calculo_vacaciones_js.php?f_i="+fecha_i+"&f_f="+fecha_f+"&dias_disponibles="+dias_disponibles+"&accion="+accion+"&token="+token+"&id_periodo="+id_periodo+"&identidad="+identidad;

$("#div_respuesta").load(consulta);

}else{
document.getElementById('btn_agregar_vacaciones').disabled = true;
}
}

}


function cargar_datos_modal(id_periodo, dias_disponibles){
document.getElementById('id_periodo').value  = id_periodo;
document.getElementById('dias_disponibles').value  = dias_disponibles;
}

function registrar_vacaciones(){
identidad_o = document.getElementById('identidad_o').value;
fecha_i = document.getElementById('fecha_inicial_vacaciones').value;
fecha_f = document.getElementById('fecha_final_vacaciones').value;
id_periodo = document.getElementById('id_periodo').value;
accion = 2;
token = Math.random();
consulta = "calculo_vacaciones_js.php?f_i="+fecha_i+"&f_f="+fecha_f+"&id_periodo="+id_periodo+"&identidad_o="+identidad_o+"&accion="+accion+"&token="+token;
$("#div_respuesta").load(consulta);
}


function cargar_datos_historico(id_periodo){
id_periodo = id_periodo;
accion = 3;

token = Math.random();
consulta = "calculo_vacaciones_js.php?id_periodo="+id_periodo+"&accion="+accion+"&token="+token;
$("#div_respuesta_historico").load(consulta);
}


function cancelar_dia_tomado(id_dia_tomado, id_periodo){
id_dia_tomado = id_dia_tomado;
id_periodo = id_periodo;
accion = 4;
token = Math.random();
consulta = "calculo_vacaciones_js.php?id_dia_tomado="+id_dia_tomado+"&id_periodo="+id_periodo+"&accion="+accion+"&token="+token;
$("#div_respuesta_historico").load(consulta);

}


</script>

<section style="color:rgb(63,138,214);background-color:#ededed;">
<br>
<h3  align="center" style="color:black;" ><b>GESTION DE VACACIONES</b></h3>
<br>
</section>


<form method="POST">

<a style = "width:100%"  class="btn btn-info" id = "non-printable" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
  Seleccion de Parametros
</a>

<div class="collapse " id="collapse1" align="center">
		<div class="row">
		<div class="col col-md-4"></div>
		<div class="col col-md-4 card">
<br>
		<div class="input-group" >
			<div class="input-group-prepend" ><span  class="input-group-text">Identidad: </span></div>
			<input type="text" class="form-control" id="identidad" name="identidad"  >
			<div  class="input-group-append">
			<button class="btn btn-primary" type="submit" name="seleccionar"  > <span class="fa fa-search"></span></button>
			</div>
		</div>
<br>
		</div>
		<div class="col col-md-4"></div>
		</div>
</div>

<br>

<br>


</form>

<div id="respuesta_info_general"></div>

<?php
if (isset($_POST['seleccionar'])) {

	$identidad = $_POST['identidad'];

	$identidad = str_replace("-", "", $identidad);
	echo "<input type = 'hidden' id = 'identidad_o' value = '" . $identidad . "'>";

	$c_periodos = mysqli_query($conn, "SELECT a.id, a.cod_empleado, a.identidad, a.tipo_contratacion, a.id_parametro_vacaciones, a.periodo_inicial, a.periodo_final, a.antiguedad_years, a.dias_otorgados, a.estado, a.fecha_registro , (SELECT COUNT(id) FROM rr_hh_vacaciones_tomadas WHERE id_periodo = a.id AND estado = 'A' ) as dias_tomados  FROM rr_hh_periodos_vacaciones as a WHERE identidad = '$identidad' AND estado = 'A' ORDER BY periodo_inicial ASC ");

	if (mysqli_num_rows($c_periodos) > 0) {

		echo "<div class = 'card' style = 'margin-left:5px; margin-right:5px'>";
		echo "<div class = 'card-header bg-success text-white' >";
		echo "DETALLE DE PERIODOS DE VACACIONES";
		echo "</div>";
		echo "<div class = 'card-body' id = 'div_tabla_periodos'>";

		echo "<table class = 'table table-bordered'>";
		echo "<tr>";
		echo "<th>PERIODO INICIAL</th>";
		echo "<th>PERIODO FINAL</th>";
		echo "<th>AÑOS ANTIGUEDAD</th>";
		echo "<th width = '15%'>DIAS GANADOS</th>";
		echo "<th width = '15%'>DIAS TOMADOS</th>";
		echo "<th width = '15%'>DIAS DISPONIBLES</th>";
		echo "</tr>";

		$date_now = date('Y-m-d');
		$bandera_periodo_disponible = 1;
		$bandera_periodo_activo = 0;
		while ($r_periodos = mysqli_fetch_array($c_periodos)) {

			$id_periodo = $r_periodos['id'];
			$cod_empleado = $r_periodos['cod_empleado'];
			$identidad = $r_periodos['identidad'];
			$tipo_contratacion = $r_periodos['tipo_contratacion'];
			$id_parametro_vacaciones = $r_periodos['id_parametro_vacaciones'];
			$periodo_inicial = $r_periodos['periodo_inicial'];
			$periodo_final = $r_periodos['periodo_final'];
			$antiguedad_years = $r_periodos['antiguedad_years'];
			$dias_vacaciones_periodo = $r_periodos['dias_otorgados'];
			$dias_tomados = $r_periodos['dias_tomados'];

			echo "<tr>";
			echo "<td>" . $periodo_inicial . "</td>";
			echo "<td>" . $periodo_final . "</td>";

			if ($date_now < $periodo_final) {

				$date_now = new DateTime($date_now);
				$periodo_final = new DateTime($periodo_final);

				$periodo_inicial = new DateTime($periodo_inicial);

				$interval = $periodo_inicial->diff($date_now);
				$years_inicio_fin = $interval->format('%y');
				$meses_inicio_fin = $interval->format('%m');
				$dias_inicio_fin = $interval->format('%d');
				$dias_laborados_periodo = $years_inicio_fin * 360 + $meses_inicio_fin * 30 + $dias_inicio_fin;
				$porcentaje_year = $dias_laborados_periodo / 360;
				$dias_vacaciones_periodo = number_format($dias_vacaciones_periodo * $porcentaje_year);

				$antiguedad_years += $porcentaje_year - 1;
			}

			echo "<td>" . number_format($antiguedad_years, 2) . "</td>";

			echo "<td><input id = 'dias_periodo_" . $id_periodo . "' name = 'dias_periodo_" . $id_periodo . "' class = 'form-control' type = 'text' value = '" . $dias_vacaciones_periodo . "' readonly></td>";

			$dias_disponibles = $dias_vacaciones_periodo - $dias_tomados;

			if ($dias_disponibles == 0 OR $bandera_periodo_activo == 1) {
				$bandera_periodo_disponible = 0;
			} elseif ($dias_disponibles > 0 AND $bandera_periodo_activo == 0) {
				$bandera_periodo_disponible = 1;
				$bandera_periodo_activo = 1;
			} elseif ($bandera_periodo_activo == 1) {
				$bandera_periodo_disponible = 1;
			}

			if ($bandera_periodo_disponible == 1) {

				?>
			<td>
			<div class = 'input-group'>
			<input type = 'text' id = 'dias_tomados_<?php echo $id_periodo; ?>' name = 'dias_tomados_<?php echo $id_periodo; ?>' class = 'form-control' value = '<?php echo $dias_tomados; ?>' readonly>
			<div class = input-group-append>
			<span style="margin-right: 2px" data-toggle='modal' href='#modal-reg_vac_tomadas' onclick="cargar_datos_modal('<?php echo $id_periodo; ?>', '<?php echo $dias_disponibles; ?>')" class = 'btn btn-success'><i class = 'fa fa-plus'></i></span>
			<span data-toggle='modal' href='#modal_historico' onclick="cargar_datos_historico('<?php echo $id_periodo; ?>')" class = 'btn btn-info'><i class = 'fa fa-eye'></i></span>
			</div>
			</div>
			</td>
			<?php

			} else {
				?>
			<td>
			<div class = 'input-group'>
			<input type = 'text' id = 'dias_tomados_<?php echo $id_periodo; ?>' name = 'dias_tomados_<?php echo $id_periodo; ?>' class = 'form-control' value = '<?php echo $dias_tomados; ?>' readonly>
			<div class = input-group-append><button style="margin-right: 2px" data-toggle='modal'  class = 'btn btn-success' disabled><i class = 'fa fa-plus'></i></button>
			<span data-toggle='modal' href='#modal_historico' onclick="cargar_datos_historico('<?php echo $id_periodo; ?>')" class = 'btn btn-info'><i class = 'fa fa-eye'></i></span></div>
			</div>
			</td>
			<?php

			}

			echo "<td><input id = 'dias_disponibles_" . $id_periodo . "' name = 'dias_disponibles_" . $id_periodo . "' class = 'form-control' type = 'text' value = '" . $dias_disponibles . "' readonly></td>";
			echo "</tr>";

		}
		echo "</table>";

		$id_serial = bin2hex($identidad);

		echo "</div>";
		echo "<div class = 'card-footer' style = 'text-align: center'>";
//		echo "<a class = 'btn btn-success' href = './print_notificacion_vacaciones.php?id=" . $id_serial . "' target = '_blank'><i class = 'fa fa-print' ></i> IMPRIMIR NOTIFICACION</a>";
		echo "</div>";
		echo "</div>";



		$action = "vacaciones";

		echo "<div id = 'DivCalendar'>";
		require 'calendar.php';
		echo "</>";


	} else {
		echo "<div class = 'alert alert-danger'>No se pudo encontrar el empleado, por favor verifique el numero de identidad ingresado.</div>";


	}

}

?>



<div class="modal fade" role="dialog" tabindex="-1" id="modal-reg_vac_tomadas">
<div class="modal-dialog modal-md" role="document">
<div class="modal-content">
<div class="modal-header" id="modal-header" style="background-color:#e7e7e7;">
<h4 class="text-center modal-title" id="modal-heading" style="width:100%;">REGISTRO DE VACACIONES A TOMAR </h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
<div class="modal-body" style="background-color:#f8f8f8;">

<input type="hidden" name="id_periodo" id="id_periodo">
<input type="hidden" name="dias_disponibles" id="dias_disponibles">

<div class="input-group" style="margin-bottom: 5px">
	<div class="input-group-prepend"><span class="input-group-text">Fecha Inicio</span></div>
	<input type="date" name="fecha_inicial_vacaciones" id="fecha_inicial_vacaciones" onchange="calcular_dias()"  class="form-control">
</div>

<div class="input-group" style="margin-bottom: 5px">
	<div class="input-group-prepend"><span class="input-group-text">Fecha Final</span></div>
	<input type="date" name="fecha_final_vacaciones" id="fecha_final_vacaciones" onchange="calcular_dias()" class="form-control">
</div>

<div class="input-group" style="margin-bottom: 5px">
	<div class="input-group-prepend"><span class="input-group-text">Total dias </span></div>
	<input name="total_dias" id="total_dias" class="form-control">
<div class="input-group-append"><button type ="button" name="btn_agregar_vacaciones" id="btn_agregar_vacaciones" onclick="registrar_vacaciones()" class = 'btn btn-success' disabled>AGREGAR</button></div>
</div>


<div id="div_respuesta"></div>



</div>
</div>
</div>
</div>


<div class="modal fade" role="dialog" tabindex="-1" id="modal_historico">
<div class="modal-dialog modal-md" role="document">
<div class="modal-content">
<div class="modal-header" id="modal-header" style="background-color:#e7e7e7;">
<h4 class="text-center modal-title" id="modal-heading" style="width:100%;">HISTORICO DE VACACIONES TOMADAS </h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
<div class="modal-body" style="background-color:#f8f8f8;">

<div id="div_respuesta_historico"></div>



</div>
</div>
</div>
</div>
