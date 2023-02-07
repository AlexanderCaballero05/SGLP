<script type="text/javascript">
	$(function () {
  $('[data-toggle="popover"]').popover()
})
</script>

<?php
require '../../conexion.php';

$accion = $_GET['accion'];
if ($accion == 1) {

	echo "<div class = 'row'>";
	echo "<div class = 'col col-md-6'>";

	$identidad = $_GET['id'];

	$datos_empleado = mysqli_query($conn, "SELECT * FROM rr_hh_empleados WHERE identidad = '$identidad' ");

	if (mysqli_num_rows($datos_empleado) > 0) {

		$ob_datos_empleado = mysqli_fetch_object($datos_empleado);
		$cod_empleado = $ob_datos_empleado->cod_empleado;
		$nombre_completo = $ob_datos_empleado->nombre_completo;

		echo '<div class="input-group" style="margin:5px 0px 5px 0px;">
			<div class="input-group-prepend" style="width: 25%"><span style="width: 100%" class="input-group-text">Cod. Empleado: </span></div>
			<input type="text" class="form-control" id="cod_empleado" name="cod_empleado" value = "' . $cod_empleado . '"  >
		</div>';

		echo '<div class="input-group" style="margin:5px 0px 5px 0px;">
			<div class="input-group-prepend" style="width: 25%" ><span style="width: 100%" class="input-group-text">Nombre Completo: </span></div>
			<input type="text" class="form-control" id="nombre" name="nombre" value = "' . $nombre_completo . '"  >
		</div>';

	}

	$datos_contratacion = mysqli_query($conn, "SELECT a.fecha_inicio, a.fecha_fin, a.salario_base , a.tipo_contratacion, b.descripcion, b.base_codigo_trabajo FROM rr_hh_tipo_contrato_salarios as a INNER JOIN rr_hh_mto_contrataciones as b ON a.tipo_contratacion = b.id WHERE identidad = '$identidad' AND status = 'A' ");

	if (mysqli_num_rows($datos_contratacion) > 0) {

		$ob_datos_contratacion = mysqli_fetch_object($datos_contratacion);
		$tipo_contratacion = $ob_datos_contratacion->tipo_contratacion;
		$descripcion_contratacion = $ob_datos_contratacion->descripcion;
		$salario_base = $ob_datos_contratacion->salario_base;
		$fecha_inicio = $ob_datos_contratacion->fecha_inicio;
		$fecha_inicio = date("d/m/Y", strtotime($fecha_inicio));
		$fecha_fin = $ob_datos_contratacion->fecha_fin;
		$fecha_fin = $ob_datos_contratacion->fecha_fin;
		$base_codigo_trabajo = $ob_datos_contratacion->base_codigo_trabajo;

		echo '<div class="input-group" style="margin:5px 0px 5px 0px;">
			<div class="input-group-prepend" style="width: 25%"><span style="width: 100%" class="input-group-text">Tipo Contrato: </span></div>
			<input type="text" class="form-control" id="descripcion_contratacion" name="descripcion_contratacion" value = "' . $descripcion_contratacion . '"  >
		</div>';

		echo "</div>";
		echo "<div class = 'col col-md-6'>";

		echo '<div class="input-group" style="margin:5px 0px 5px 0px;">
			<div class="input-group-prepend" style="width: 25%"><span style="width: 100%"  class="input-group-text"  >Fecha de Inicio: </span></div>
			<input type="text" class="form-control" id="fecha_inicio" name="fecha_inicio" value = "' . $fecha_inicio . '"  >
		</div>';

		echo '<div class="input-group" style="margin:5px 0px 5px 0px;">
			<div class="input-group-prepend" style="width: 25%"><span style="width: 100%" class="input-group-text">Fecha Final: </span></div>
			<input type="date" class="form-control" id="fecha_final" name="fecha_final" value = "' . $fecha_fin . '" onchange = "cambio_fecha_final(this.value,' . $tipo_contratacion . ')" >
		</div>';

		echo "</div></div>";

	}

}

if ($accion == 2) {

	$identidad = $_GET['id'];
	$id_tipo_contrato = $_GET['id_tipo_contrato'];
	$fecha_inicio = $_GET['fecha_inicio'];

	$v_f_i = explode("/", $fecha_inicio);
	
	$fecha_inicio = $v_f_i[2]."-".$v_f_i[1]."-".$v_f_i[0];

	$fecha_inicio = date('Y-m-d', strtotime($fecha_inicio));	

	$fecha_inicio = new DateTime($fecha_inicio);


	$v_meses[1] = "Enero";
	$v_meses[2] = "Febrero";
	$v_meses[3] = "Marzo";
	$v_meses[4] = "Abril";
	$v_meses[5] = "Mayo";
	$v_meses[6] = "Junio";
	$v_meses[7] = "Julio";
	$v_meses[8] = "Agosto";
	$v_meses[9] = "Septiembre";
	$v_meses[10] = "Octubre";
	$v_meses[11] = "Noviembre";
	$v_meses[12] = "Diciembre";

	$fecha_final = $_GET['fecha_final'];
	$v_fecha_finalizacion = explode("-", $fecha_final);
	$mes_finalizacion = $v_fecha_finalizacion[1];
	$dia_finalizacion = $v_fecha_finalizacion[2];

	$dias_ultimo_mes = substr($fecha_final, -2);
	$fecha_seis_meses = date('Y-m-d', strtotime("-5 months", strtotime($fecha_final)));

	$fecha_final = date('Y-m-d', strtotime("+2 days", strtotime($fecha_final)));
	$fecha_final = new DateTime($fecha_final);
	$interval = $fecha_inicio->diff($fecha_final);


	$diff_years = $interval->format('%y');
	$diff_meses = $interval->format('%m');
	$diff_dias = $interval->format('%d');

	$datos_contratacion = mysqli_query($conn, "SELECT a.fecha_inicio, a.fecha_fin, a.salario_base , a.tipo_contratacion, b.descripcion, b.base_codigo_trabajo  FROM rr_hh_tipo_contrato_salarios as a INNER JOIN rr_hh_mto_contrataciones as b ON a.tipo_contratacion = b.id WHERE identidad = '$identidad' AND status = 'A' LIMIT 1 ");

	echo mysqli_error($conn);

	if (mysqli_num_rows($datos_contratacion) > 0) {

		$ob_datos_contratacion = mysqli_fetch_object($datos_contratacion);
		$tipo_contratacion = $ob_datos_contratacion->tipo_contratacion;
		$descripcion_contratacion = $ob_datos_contratacion->descripcion;
		$salario_base = $ob_datos_contratacion->salario_base;
		$fecha_inicio = $ob_datos_contratacion->fecha_inicio;
		$fecha_inicio = date("d/m/Y", strtotime($fecha_inicio));
		$fecha_fin = $ob_datos_contratacion->fecha_fin;
		$base_codigo_trabajo = $ob_datos_contratacion->base_codigo_trabajo;

	}

	echo "<div class = 'row'>";
	echo "<div class = 'col'>";

	echo '<div class="input-group" style="margin:5px 0px 5px 0px;">
			<div class="input-group-prepend" style="width: 25%"><span style="width: 100%" class="input-group-text">Salario Base Mensual: </span></div>
			<input type="text" class="form-control" id="salario_base" name="salario_base" value = "' . number_format($salario_base, 2) . '"  >
		</div>';

	echo "</div>";

	echo "<div class = 'col'>";

	echo '<div class="input-group" style="margin:5px 0px 5px 0px;">
			<div class="input-group-prepend"  ><span class="input-group-text">Tiempo Laborado: </span></div>
			<input type="text" class="form-control" style = "text-align:right" id="diff_years" name="diff_years" value = "' . $diff_years . '"  >
			<div class="input-group-append" ><span  class="input-group-text">Años </span></div>
			<input type="text" class="form-control" style = "text-align:right" id="diff_meses" name="diff_meses" value = "' . $diff_meses . '"  >
			<div class="input-group-append" ><span  class="input-group-text">Meses </span></div>
			<input type="text" class="form-control" style = "text-align:right" id="diff_dias" name="diff_dias" value = "' . $diff_dias . '"  >
			<div class="input-group-append" ><span  class="input-group-text">Dias </span></div>
		</div>';

	echo "</div>";
	echo "</div>";

	/////////////////////////////////////////////////////////////
	/////// INICIO DE CALCULO DE PROMEDIO BASE //////////////////
	/////////////////////////////////////////////////////////////

	// DESHABILITAR EL SHOW DEL COLSPAN UNA VEZ REALIZADO EL DESAROLLO

	$c_salario_actual = mysqli_query($conn, "SELECT * FROM rr_hh_tipo_contrato_salarios WHERE identidad = '$identidad' AND status = 'A' ");
	echo mysqli_error($conn);
	$ob_salario_actual = mysqli_fetch_object($c_salario_actual);
	$salario_base_actual = $ob_salario_actual->salario_base;

	$salario_base_actual_diario = $salario_base_actual / 30;
	$salario_parcial_ultimo_mes = $salario_base_actual_diario * $dias_ultimo_mes;

	$salario_base_actual_format = number_format($salario_base_actual, 2);
	$salario_parcial_ultimo_mes_format = number_format($salario_parcial_ultimo_mes, 2);

	if ($base_codigo_trabajo == "N") {

		echo "<div class = 'row'>";
		echo "<div class = 'col'>";

//		echo '<div id="collapsePromedioBase" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">';
		echo '<div id="collapsePromedioBase" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">';
		echo "<input type = 'hidden' id = 'salario_base_actual_format' value = '" . $salario_base_actual_format . "'>";
		echo "<input type = 'hidden' id = 'salario_parcial_ultimo_mes_format' value = '" . $salario_parcial_ultimo_mes_format . "'>";

		$fecha_inicio_c = $ob_salario_actual->fecha_inicio;
		$fecha_fin_c = $ob_salario_actual->fecha_fin;

		$v_fecha_seis_meses = explode("-", $fecha_seis_meses);
		$year_seis_meses = $v_fecha_seis_meses[0];
		$mes_seis_meses = $v_fecha_seis_meses[1];
		$mes_seis_meses = $mes_seis_meses + 0;

		if ($fecha_inicio_c < $fecha_seis_meses) {

			$salario_ultimos_seis_meses = $salario_base_actual * 6;
			echo '<div class="input-group" style="margin:5px 0px 5px 0px;">
			<div class="input-group-prepend" style="width: 40%"><span style="width: 100%"  class="input-group-text">Sumatoria de ultimos 6 salarios: </span></div>
			<input type="text" class="form-control" id="salario_seis_meses" name="salario_seis_meses" value = "' . number_format($salario_ultimos_seis_meses, 2) . '"  >
			</div>';

/*
$i = 1;

while ($i <= 6) {

if ($i == 6) {
?>
<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend" style="width: 25%"><span style="width: 100%" class="input-group-text"><?php echo $v_meses[$mes_seis_meses] ?> : </span></div>
<input type="text" class="form-control" id="salario<?php echo $i ?>" name="salario<?php echo $i ?>" value = "<?php echo number_format($salario_base_actual, 2) ?> "  >
<div class="input-group-append" >
<button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<span class="sr-only">Toggle Dropdown</span>
</button>
<div class="dropdown-menu">
<a class="dropdown-item" onclick = "document.getElementById('salario6').value = document.getElementById('salario_base_actual_format').value">Mes completo</a>
<a class="dropdown-item" onclick = "document.getElementById('salario6').value = document.getElementById('salario_parcial_ultimo_mes_format').value" >Dias (<?php echo $dias_ultimo_mes; ?>)</a>
</div>
</div>
</div>
<?php

} else {
echo '<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend" style="width: 25%"><span style="width: 100%" class="input-group-text">' . $v_meses[$mes_seis_meses] . ': </span></div>
<input type="text" class="form-control" id="salario' . $i . '" name="salario' . $i . '" value = "' . number_format($salario_base_actual, 2) . '"  >
</div>';
}

$mes_seis_meses++;
if ($mes_seis_meses == 13) {
$mes_seis_meses = 1;
}
$i++;
}
 */
		} else {
			echo "Ultimos seis meses no ";
		}

		/////////////////////////////////////////////////////////////////
		///////////////////////// AGUINALDO  ////////////////////////////

		$c_aguinaldo = mysqli_query($conn, "SELECT * FROM rr_hh_mto_aguinaldos WHERE tipo_contratacion = '$id_tipo_contrato' AND estado = 'A' AND aplica_promedio_base = 'S' ORDER BY de_year ");

		echo mysqli_error($conn);

		$dias_laborados = $diff_dias + $diff_meses * 30 + $diff_years * 360;
		$salario_aguinaldo = 0;
		$valor_porcentual_aguinaldo = 100;
		while ($reg_aguinaldo = mysqli_fetch_array($c_aguinaldo)) {
			$de_dia = $reg_aguinaldo['de_dia'];
			$de_mes = $reg_aguinaldo['de_mes'];
			$de_year = $reg_aguinaldo['de_year'];
			$a_dia = $reg_aguinaldo['a_dia'];
			$a_mes = $reg_aguinaldo['a_mes'];
			$a_year = $reg_aguinaldo['a_year'];

			$dias_en_rango_inicial = $de_dia + $de_mes * 30 + $de_year * 360;

			if ($dias_laborados >= $dias_en_rango_inicial) {

				if ($a_year != '') {
					$dias_en_rango_final = $a_dia + $a_mes * 30 + $a_year * 360;

					if ($dias_laborados <= $dias_en_rango_final) {

						$valor_porcentual_aguinaldo = $reg_aguinaldo['valor_porcentual_sueldo'];
						$salario_aguinaldo = ($valor_porcentual_aguinaldo * $salario_base_actual) / 100;

					}

				} else {

					$valor_porcentual_aguinaldo = $reg_aguinaldo['valor_porcentual_sueldo'];
					$salario_aguinaldo = ($valor_porcentual_aguinaldo * $salario_base_actual) / 100;

				}

			}

		}

		echo '<div class="input-group" style="margin:5px 0px 5px 0px;">
					<div class="input-group-prepend" style="width: 40%"><span style="width: 100%" class="input-group-text">Aguinaldo: </span></div>
					<input type="text" class="form-control" id="salario_aguinaldo" name="salario_aguinaldo" value = "' . number_format($salario_aguinaldo, 2) . '"  >

					<div  class="input-group-append">
					<button type="button" class="btn btn-info fa fa-info" data-container="body" data-toggle="popover" data-placement="top" data-content=" ' . $valor_porcentual_aguinaldo . '% Del salario base mensual ">
					</button>
					</div>
					</div>';

		///////////////////////// AGUINALDO  ////////////////////////////
		/////////////////////////////////////////////////////////////////

		//////////////////////////////////////////////////////////////////
		///////////////////////// CATORCEAVO  ////////////////////////////

		$c_catorceavo = mysqli_query($conn, "SELECT * FROM rr_hh_mto_catorceavo WHERE tipo_contratacion = '$id_tipo_contrato' AND estado = 'A' AND aplica_promedio_base = 'S' ORDER BY de_year ASC ");

		echo mysqli_error($conn);

		$dias_laborados = $diff_dias + $diff_meses * 30 + $diff_years * 360;
		$salario_catorceavo = 0;

		while ($reg_catorceavo = mysqli_fetch_array($c_catorceavo)) {
			$de_dia = $reg_catorceavo['de_dia'];
			$de_mes = $reg_catorceavo['de_mes'];
			$de_year = $reg_catorceavo['de_year'];
			$a_dia = $reg_catorceavo['a_dia'];
			$a_mes = $reg_catorceavo['a_mes'];
			$a_year = $reg_catorceavo['a_year'];

			$dias_en_rango_inicial = $de_dia + $de_mes * 30 + $de_year * 360;
			if ($dias_laborados >= $dias_en_rango_inicial) {

				if ($a_year != '') {

					$dias_en_rango_final = $a_dia + $a_mes * 30 + $a_year * 360;

					if ($dias_laborados <= $dias_en_rango_final) {

						$valor_porcentual_catorceavo = $reg_catorceavo['valor_porcentual_sueldo'];
						$salario_catorceavo = ($valor_porcentual_catorceavo * $salario_base_actual) / 100;

					}

				} else {

					$valor_porcentual_catorceavo = $reg_catorceavo['valor_porcentual_sueldo'];
					$salario_catorceavo = ($valor_porcentual_catorceavo * $salario_base_actual) / 100;

				}

			}

		}

		echo '<div class="input-group" style="margin:5px 0px 5px 0px;">
			<div class="input-group-prepend" style="width: 40%"><span style="width: 100%" class="input-group-text">Catorceavo: </span></div>
			<input type="text" class="form-control" id="salario_catorceavo" name="salario_catorceavo" value = "' . number_format($salario_catorceavo, 2) . '"  >

			<div  class="input-group-append">
			<button type="button" class="btn btn-info fa fa-info" data-container="body" data-toggle="popover" data-placement="top" data-content=" ' . $valor_porcentual_catorceavo . '% Del salario base mensual ">
			</button>
			</div>

			</div>';

		///////////////////////// CATORCEAVO  ////////////////////////////
		//////////////////////////////////////////////////////////////////

		/////////////////////////////////////////////////////////////////
		////////////////// VACACIONES APLICABLES ////////////////////////

		$c_vacaciones = mysqli_query($conn, "SELECT * FROM rr_hh_mto_vacaciones WHERE tipo_contratacion = '$id_tipo_contrato' AND estado = 'A' AND aplica_promedio_base = 'S' ORDER BY de_year ");

		echo mysqli_error($conn);

		$dias_laborados = $diff_dias + $diff_meses * 30 + $diff_years * 360;

		while ($reg_vacaciones = mysqli_fetch_array($c_vacaciones)) {
			$de_dia = $reg_vacaciones['de_dia'];
			$de_mes = $reg_vacaciones['de_mes'];
			$de_year = $reg_vacaciones['de_year'];
			$a_dia = $reg_vacaciones['a_dia'];
			$a_mes = $reg_vacaciones['a_mes'];
			$a_year = $reg_vacaciones['a_year'];

			$dias_en_rango_inicial = $de_dia + $de_mes * 30 + $de_year * 360;

			if ($dias_laborados >= $dias_en_rango_inicial) {

				if ($a_year != '') {
					$dias_en_rango_final = $a_dia + $a_mes * 30 + $a_year * 360;

					if ($dias_laborados <= $dias_en_rango_final) {

						$valor_porcentual_vacaciones = $reg_vacaciones['valor_porcentual_sueldo'];
						$bono_vacaciones = ($valor_porcentual_vacaciones * $salario_base_actual) / 100;

					}

				} else {

					$valor_porcentual_vacaciones = $reg_vacaciones['valor_porcentual_sueldo'];
					$bono_vacaciones = ($valor_porcentual_vacaciones * $salario_base_actual) / 100;

				}

			}

		}

		echo '<div class="input-group" style="margin:5px 0px 5px 0px;">
			<div class="input-group-prepend" style="width: 40%"><span style="width: 100%" class="input-group-text">Bono Vacacional: </span></div>
			<input type="text" class="form-control" id="salario_vacaciones" name="salario_vacaciones" value = "' . number_format($bono_vacaciones, 2) . '"  >

			<div  class="input-group-append">
			<button type="button" class="btn btn-info fa fa-info" data-container="body" data-toggle="popover" data-placement="top" data-content=" ' . $valor_porcentual_vacaciones . '% Del salario base mensual ">
			</button>
			</div>

			</div>';

		////////////////// VACACIONES APLICABLES ////////////////////////
		/////////////////////////////////////////////////////////////////

		/////////////////////////////////////////////////////////////////
		/////////////// HORAS EXTRAS / SUSTITUCIONES ////////////////////
		$horas_extras_sustituciones = 0;
		echo '<div class="input-group" style="margin:5px 0px 5px 0px;">
					<div class="input-group-prepend" style="width: 40%"><span style="width: 100%" class="input-group-text">Horas extras y sustituciones: </span></div>
					<input type="text" class="form-control" id="salario_horas_extras" name="salario_horas_extras" value = "' . number_format($horas_extras_sustituciones, 2) . '"  >
					</div>';

		/////////////// HORAS EXTRAS / SUSTITUCIONES ////////////////////
		/////////////////////////////////////////////////////////////////

		/////////////////////////////////////////////////////////////////
		/////////////// VACACIONES O PROPORCIONALES  ////////////////////
		$salario_vacaciones = 0;
		if ($diff_years < 1) {
			$salario_vacaciones = ($bono_vacaciones / 360) * $dias_laborados;
		} else {
			$salario_vacaciones = ($bono_vacaciones / 360) * ($dias_laborados - ($diff_years * 360));
		}
		echo '<div class="input-group" style="margin:5px 0px 5px 0px;">
					<div class="input-group-prepend" style="width: 40%"><span style="width: 100%" class="input-group-text">Vacaciones: </span></div>
					<input type="text" class="form-control" id="salario_vacaciones_proporcionales" name="salario_vacaciones_proporcionales" value = "' . number_format($salario_vacaciones, 2) . '"  >
					</div>';

		/////////////// VACACIONES O PROPORCIONALES ////////////////////
		////////////////////////////////////////////////////////////////

		echo '</div>';

		$salario_promedio_base_mensual = ($salario_vacaciones + $horas_extras_sustituciones + $bono_vacaciones + $salario_catorceavo + $salario_aguinaldo + $salario_ultimos_seis_meses) / 6;
		$salario_promedio_base_diario = $salario_promedio_base_mensual / 30;

		echo '<div class="input-group" style="margin:5px 0px 5px 0px;">
			<div class="input-group-prepend" style="width: 40%"><span style="width: 100%" class="input-group-text">Salario Base Prom. Mensual: </span></div>
			<input type="text" class="form-control" readonly = "true" id="salario_promedio_base_mensual" name="salario_promedio_base_mensual" value = "' . number_format($salario_promedio_base_mensual, 2) . '"  >
			<div  class="input-group-append">
	        <button class="btn btn-info fa fa-eye" type="button" data-toggle="collapse" data-target="#collapsePromedioBase" aria-expanded="true" aria-controls="collapsePromedioBase"></button>
			</div>
			</div>';

		echo "</div>";
		echo "<div class = 'col'>";

		echo '<div class="input-group" style="margin:5px 0px 5px 0px;">
			<div class="input-group-prepend" style="width: 40%"><span style="width: 100%" class="input-group-text">Salario Base Prom. diario: </span></div>
			<input type="text" class="form-control" readonly = "true" id="salario_promedio_base_diario" name="salario_promedio_base_diario" value = "' . number_format($salario_promedio_base_diario, 2) . '"  >
			<div  class="input-group-append">
			<button type="button" class="btn btn-info fa fa-info" data-container="body" data-toggle="popover" data-placement="top" data-content=" Salario Base Prom. Diario = Salario Base Prom. Mensual / 30 ">
			</button>
			</div>
			</div>';

		echo "</div>";
		echo "</div>";

	} else {

		$salario_promedio_base_mensual = ($salario_base_actual * 14) / 12;
		$salario_promedio_base_diario = $salario_promedio_base_mensual / 30;

		echo "<div class = 'row' >";
		echo "<div class = 'col col-md-6'>";

		echo '<div class="input-group" style="margin:5px 0px 5px 0px;">
			<div class="input-group-prepend" style="width: 40%"><span style="width: 100%" class="input-group-text">Salario Base Prom. Mensual: </span></div>
			<input type="text" class="form-control" readonly = "true" id="salario_promedio_base_mensual" name="salario_promedio_base_mensual" value = "' . number_format($salario_promedio_base_mensual, 2) . '"  >
			<div  class="input-group-append">
			<button type="button" class="btn btn-info fa fa-info" data-container="body" data-toggle="popover" data-placement="top" data-content=" Salario Promedio Base Mensual = (Salario base mensual x 14)/12 ">
			</button>
			</div></div>';

		echo "</div>";
		echo "<div class = 'col'>";
		echo '<div class="input-group" style="margin:5px 0px 5px 0px;">
			<div class="input-group-prepend" style="width: 40%"><span style="width: 100%" class="input-group-text">Salario Base Prom. diario: </span></div>
			<input type="text" class="form-control" readonly = "true" id="salario_promedio_base_diario" name="salario_promedio_base_diario" value = "' . number_format($salario_promedio_base_diario, 2) . '"  >
			<div  class="input-group-append">
			<button type="button" class="btn btn-info fa fa-info" data-container="body" data-toggle="popover" data-placement="top" data-content=" Salario Base Prom. Diario = Salario Base Prom. Mensual / 30 ">
			</button>
			</div></div>';

		echo "</div>";
		echo "</div>";

	}

	/////////////////////////////////////////////////////////////////
	/////////////// SALARIO PROMEDIO BASE MENSUAL  //////////////////

	/////////////// SALARIO PROMEDIO BASE MENSUAL  //////////////////
	/////////////////////////////////////////////////////////////////

	//
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////// DERECHOS ADQUIRIDOS /////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//

	////////////////// CALCULO DE PREAVISO ////////////////////////

	echo "<br>";
	echo "<table class = 'table table-bordered'>";
	echo "<tr><th colspan = '4' style = 'text-align : center'>DERECHOS E INDEMNIZACIONES</th></tr>";
	echo "<tr>";
	echo "<th>Derecho</th>";
	echo "<th width = '50%'>Descripción</th>";
	echo "<th>Tiempo (Días)</th>";
	echo "<th>Total</th>";
	echo "</tr>";

	$c_preaviso = mysqli_query($conn, "SELECT * FROM rr_hh_mto_preavisos WHERE tipo_contratacion = '$id_tipo_contrato' AND estado = 'A'  ORDER BY de_year ");

	echo mysqli_error($conn);

	$dias_laborados = $diff_dias + $diff_meses * 30 + $diff_years * 360;

	while ($reg_preaviso = mysqli_fetch_array($c_preaviso)) {
		$de_dia = $reg_preaviso['de_dia'];
		$de_mes = $reg_preaviso['de_mes'];
		$de_year = $reg_preaviso['de_year'];
		$a_dia = $reg_preaviso['a_dia'];
		$a_mes = $reg_preaviso['a_mes'];
		$a_year = $reg_preaviso['a_year'];

		$dias_en_rango_inicial = $de_dia + $de_mes * 30 + $de_year * 360;

		if ($dias_laborados >= $dias_en_rango_inicial) {

			if ($a_year != '') {
				$dias_en_rango_final = $a_dia + $a_mes * 30 + $a_year * 360;

				if ($dias_laborados <= $dias_en_rango_final) {
					$dias_preaviso = $reg_preaviso['dias_preaviso'];
					$total_preaviso = ($salario_promedio_base_diario) * $dias_preaviso;

				}

			} else {

				$dias_preaviso = $reg_preaviso['dias_preaviso'];
				$total_preaviso = ($salario_promedio_base_diario) * $dias_preaviso;

			}

		}

	}

	echo "<tr>";
	echo "<td>";
	echo "Preaviso";
	echo "</td>";
	echo "<td>";
	echo "(" . $dias_preaviso . " dias * L. " . number_format($salario_promedio_base_diario, 2) . ")";
	echo "</td>";
	echo "<td>";
	echo $dias_preaviso;
	echo "</td>";
	echo "<td>";
	echo "L. " . number_format($total_preaviso, 2);
	echo "</td>";
	echo "</tr>";

	////////////////// CALCULO DE PREAVISO ////////////////////////

	////////////////// CALCULO DE CESANTIA ////////////////////////

	$c_cesantia = mysqli_query($conn, "SELECT * FROM rr_hh_mto_cesantias WHERE tipo_contratacion = '$id_tipo_contrato' AND estado = 'A'  ORDER BY de_year ASC ");

	echo mysqli_error($conn);

	$dias_laborados = $diff_dias + $diff_meses * 30 + $diff_years * 360;

	while ($reg_cesantia = mysqli_fetch_array($c_cesantia)) {
		$de_dia = $reg_cesantia['de_dia'];
		$de_mes = $reg_cesantia['de_mes'];
		$de_year = $reg_cesantia['de_year'];
		$a_dia = $reg_cesantia['a_dia'];
		$a_mes = $reg_cesantia['a_mes'];
		$a_year = $reg_cesantia['a_year'];
		$base_year_trabajado = $reg_cesantia['base_year_trabajado'];

		$dias_en_rango_inicial = $de_dia + $de_mes * 30 + $de_year * 360;


		if ($dias_laborados >= $dias_en_rango_inicial) {

			if ($a_year != '') {

				$dias_en_rango_final = $a_dia + $a_mes * 30 + $a_year * 360;

				if ($dias_laborados <= $dias_en_rango_final) {

					if ($base_year_trabajado == 'S') {

						$dias_cesantia = $reg_cesantia['dias_cesantia'];

						if ($reg_cesantia['maximo_dias'] != "") {

							if ($dias_cesantia > $reg_cesantia['maximo_dias']) {
								$total_dias_cesantia = $reg_cesantia['maximo_dias'];
								$desc_cesantia = $dias_cesantia . " dias por año trabajado con un maximo de " . $total_dias_cesantia . " dias (" . $total_dias_cesantia . " * " . number_format($salario_promedio_base_diario, 2) . ")";
							} else {
								$total_dias_cesantia = $dias_cesantia * $diff_years;
								$desc_cesantia = $dias_cesantia . " dias por año trabajado (" . $dias_cesantia . " dias * " . $diff_years . " * " . number_format($salario_promedio_base_diario, 2) . ")";
							}

						} else {

							$total_dias_cesantia = $dias_cesantia * $diff_years;
							$desc_cesantia = $dias_cesantia . " dias por año trabajado (" . $dias_cesantia . " dias * " . $diff_years . " * " . number_format($salario_promedio_base_diario, 2) . ")";

						}

					} else {
						$dias_cesantia = $reg_cesantia['dias_cesantia'];
						$total_dias_cesantia = $dias_cesantia;
						$desc_cesantia = "(" . $dias_cesantia . " dias * L. " . number_format($salario_promedio_base_diario, 2) . ")";
					}

					$total_cesantia = ($salario_promedio_base_diario) * $total_dias_cesantia;
				}

			} else {

				if ($base_year_trabajado == 'S') {

					$dias_cesantia = $reg_cesantia['dias_cesantia'];
					$total_dias_cesantia = $dias_cesantia * $diff_years;

					if ($total_dias_cesantia > $reg_cesantia['maximo_dias']) {
						$total_dias_cesantia = $reg_cesantia['maximo_dias'];
						$desc_cesantia = $dias_cesantia . " dias por año trabajado con un maximo de " . $total_dias_cesantia . " dias (" . $total_dias_cesantia . " * " . number_format($salario_promedio_base_diario, 2) . ")";
					} else {
						$desc_cesantia = $dias_cesantia . " dias por año trabajado (" . $dias_cesantia . " dias * " . $diff_years . " * " . number_format($salario_promedio_base_diario, 2) . ")";
					}

				} else {
					$dias_cesantia = $reg_cesantia['dias_cesantia'];
					$total_dias_cesantia = $dias_cesantia;
					$desc_cesantia = "(" . $dias_cesantia . " dias * L. " . number_format($salario_promedio_base_diario, 2) . ")";
				}

				$total_cesantia = ($salario_promedio_base_diario) * $total_dias_cesantia;

			}

		}

	}

	echo "<tr>";
	echo "<td>";
	echo "Cesantia";
	echo "</td>";
	echo "<td>";
	echo $desc_cesantia;
	echo "</td>";
	echo "<td>";
	echo $total_dias_cesantia;
	echo "</td>";
	echo "<td>";
	echo "L. " . number_format($total_cesantia, 2);
	echo "</td>";
	echo "</tr>";

	////////////////// CALCULO DE CESANTIA ////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////// CESANTIA PROPORCIONAL //////////////////////

	$porcentaje_year_ganado = (($diff_meses * 30) + $diff_dias) / 360;
	$porcentaje_dias_cesantia = $total_dias_cesantia * $porcentaje_year_ganado;
	$total_cesantia_proporcional = $porcentaje_dias_cesantia * $salario_promedio_base_diario;

	$desc_cesantia_proporcional = "" . number_format($porcentaje_dias_cesantia, 2) . " dias  * L. " . number_format($salario_promedio_base_diario, 2);

	echo "<tr>";
	echo "<td>";
	echo "Cesantia Proporcional";
	echo "</td>";
	echo "<td>";
	echo $desc_cesantia_proporcional;
	echo "</td>";
	echo "<td>";
	echo number_format($porcentaje_dias_cesantia, 2);
	echo "</td>";
	echo "<td>";
	echo "L. " . number_format($total_cesantia_proporcional, 2);
	echo "</td>";
	echo "</tr>";

	////////////////// CESANTIA PROPORCIONAL //////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////// VACACIONES PROPORCIONAL //////////////////////

	$c_vacaciones_p = mysqli_query($conn, "SELECT * FROM rr_hh_mto_vacaciones WHERE tipo_contratacion = '$id_tipo_contrato' AND estado = 'A'  ORDER BY de_year ");

	echo mysqli_error($conn);

	while ($reg_vacaciones_p = mysqli_fetch_array($c_vacaciones_p)) {
		$de_dia = $reg_vacaciones_p['de_dia'];
		$de_mes = $reg_vacaciones_p['de_mes'];
		$de_year = $reg_vacaciones_p['de_year'];
		$a_dia = $reg_vacaciones_p['a_dia'];
		$a_mes = $reg_vacaciones_p['a_mes'];
		$a_year = $reg_vacaciones_p['a_year'];
		$valor_porcentual_vacaciones_p = $reg_vacaciones_p['valor_porcentual_sueldo'];
		$bono_vacaciones = 0;

		$dias_en_rango_inicial_p = $de_dia + $de_mes * 30 + $de_year * 360;

		if ($dias_laborados >= $dias_en_rango_inicial_p) {

			if ($a_year != '') {
				$dias_en_rango_final_p = $a_dia + $a_mes * 30 + $a_year * 360;

				if ($dias_laborados <= $dias_en_rango_final_p) {
					$bono_vacaciones_p = ($valor_porcentual_vacaciones_p * $salario_base_actual) / 100;
					$dias_vacaciones_p = $reg_vacaciones_p['dias_vacaciones'];
				}

			} else {
				$bono_vacaciones_p = ($valor_porcentual_vacaciones_p * $salario_base_actual) / 100;
				$dias_vacaciones_p = $reg_vacaciones_p['dias_vacaciones'];
			}

		}

	}

	$dias_vacaciones_proporcional = (($diff_meses * 30) + $diff_dias);
	$vacaciones_proporcional_year = $dias_vacaciones_proporcional / 360;
	$desc_vacaciones_proporcional = "(" . $dias_vacaciones_proporcional . " dias  / 360 )  * " . $dias_vacaciones_p . ") * L. " . number_format($salario_promedio_base_diario, 2);
	$total_vacaciones_proporcional = ($vacaciones_proporcional_year * $dias_vacaciones_p) * $salario_promedio_base_diario;
	$dias_vacaciones_ganados = $vacaciones_proporcional_year * $dias_vacaciones_p;

	echo "<tr>";
	echo "<td>";
	echo "Vacaciones Proporcional";
	echo "</td>";
	echo "<td>";
	echo $desc_vacaciones_proporcional;
	echo "</td>";
	echo "<td>";
	echo number_format($dias_vacaciones_ganados, 2);
	echo "</td>";
	echo "<td>";
	echo "L. " . number_format($total_vacaciones_proporcional, 2);
	echo "</td>";
	echo "</tr>";

	////////////////// VACACIONES PROPORCIONAL //////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////// AGUINALDO PROPORCIONAL //////////////////////

	$c_aguinaldo_p = mysqli_query($conn, "SELECT * FROM rr_hh_mto_aguinaldos WHERE tipo_contratacion = '$id_tipo_contrato' AND estado = 'A'  ORDER BY de_year ");

	echo mysqli_error($conn);

	$dias_laborados = $diff_dias + $diff_meses * 30 + $diff_years * 360;
	$salario_aguinaldo_p = 0;
	while ($reg_aguinaldo_p = mysqli_fetch_array($c_aguinaldo_p)) {
		$de_dia = $reg_aguinaldo_p['de_dia'];
		$de_mes = $reg_aguinaldo_p['de_mes'];
		$de_year = $reg_aguinaldo_p['de_year'];
		$a_dia = $reg_aguinaldo_p['a_dia'];
		$a_mes = $reg_aguinaldo_p['a_mes'];
		$a_year = $reg_aguinaldo_p['a_year'];
		$valor_porcentual_aguinaldo_p = $reg_aguinaldo_p['valor_porcentual_sueldo'];

		$dias_en_rango_inicial = $de_dia + $de_mes * 30 + $de_year * 360;

		if ($dias_laborados >= $dias_en_rango_inicial) {

			if ($a_year != '') {
				$dias_en_rango_final = $a_dia + $a_mes * 30 + $a_year * 360;

				if ($dias_laborados <= $dias_en_rango_final) {

					$salario_aguinaldo_p = ($valor_porcentual_aguinaldo_p * $salario_base_actual) / 100;

				}

			} else {

				$salario_aguinaldo_p = ($valor_porcentual_aguinaldo_p * $salario_base_actual) / 100;

			}

		}

	}

	$salario_aguinaldo_p_diario = $salario_aguinaldo_p / 30;

	$dias_aguinaldo_proporcional = ((($mes_finalizacion - 1) * 30) + $dia_finalizacion);
	$aguinaldo_proporcional_year = ((($mes_finalizacion - 1) * 30) + $dia_finalizacion) / 12;

	$desc_aguinaldo_proporcional = "(" . $dias_aguinaldo_proporcional . " dias  / 12) * L. " . number_format($salario_aguinaldo_p_diario, 2);
	$total_aguinaldo_proporcional = $aguinaldo_proporcional_year * $salario_aguinaldo_p_diario;

	echo "<tr>";
	echo "<td>";
	echo "Aguinaldo Proporcional";
	echo "</td>";
	echo "<td>";
	echo $desc_aguinaldo_proporcional;
	echo "</td>";
	echo "<td>";
	echo number_format($dias_aguinaldo_proporcional, 2);
	echo "</td>";
	echo "<td>";
	echo "L. " . number_format($total_aguinaldo_proporcional, 2);
	echo "</td>";
	echo "</tr>";

	////////////////// AGUINALDO PROPORCIONAL //////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////// DECIMOCUARTO PROPORCIONAL //////////////////////

	$c_catorceavo_p = mysqli_query($conn, "SELECT * FROM rr_hh_mto_catorceavo WHERE tipo_contratacion = '$id_tipo_contrato' AND estado = 'A'  ORDER BY de_year ");

	echo mysqli_error($conn);

	$dias_laborados = $diff_dias + $diff_meses * 30 + $diff_years * 360;
	$salario_catorceavo_p = 0;
	while ($reg_catorceavo_p = mysqli_fetch_array($c_catorceavo_p)) {
		$de_dia = $reg_catorceavo_p['de_dia'];
		$de_mes = $reg_catorceavo_p['de_mes'];
		$de_year = $reg_catorceavo_p['de_year'];
		$a_dia = $reg_catorceavo_p['a_dia'];
		$a_mes = $reg_catorceavo_p['a_mes'];
		$a_year = $reg_catorceavo_p['a_year'];
		$valor_porcentual_catorceavo_p = $reg_catorceavo_p['valor_porcentual_sueldo'];

		$dias_en_rango_inicial = $de_dia + $de_mes * 30 + $de_year * 360;

		if ($dias_laborados >= $dias_en_rango_inicial) {

			if ($a_year != '') {
				$dias_en_rango_final = $a_dia + $a_mes * 30 + $a_year * 360;

				if ($dias_laborados <= $dias_en_rango_final) {

					$salario_catorceavo_p = ($valor_porcentual_catorceavo_p * $salario_base_actual) / 100;

				}

			} else {

				$salario_catorceavo_p = ($valor_porcentual_catorceavo_p * $salario_base_actual) / 100;

			}

		}

	}

	if ($mes_finalizacion <= 6) {
		$dias_catorceavo_proporcional = ((($mes_finalizacion + 5) * 30) + $dia_finalizacion);
		$catorceavo_proporcional_year = ((($mes_finalizacion + 5) * 30) + $dia_finalizacion) / 12;

	} else {

		$dias_catorceavo_proporcional = ((($mes_finalizacion - 7) * 30) + $dia_finalizacion);
		$catorceavo_proporcional_year = ((($mes_finalizacion - 7) * 30) + $dia_finalizacion) / 12;

	}

	$salario_catorceavo_p_diario = $salario_catorceavo_p / 30;

	$desc_catorceavo_proporcional = "(" . $dias_catorceavo_proporcional . " dias  / 12) * L. " . number_format($salario_catorceavo_p_diario, 2);
	$total_catorceavo_proporcional = $catorceavo_proporcional_year * $salario_catorceavo_p_diario;

	echo "<tr>";
	echo "<td>";
	echo "Catorceavo Proporcional";
	echo "</td>";
	echo "<td>";
	echo $desc_catorceavo_proporcional;
	echo "</td>";
	echo "<td>";
	echo number_format($dias_catorceavo_proporcional, 2);
	echo "</td>";
	echo "<td>";
	echo "L. " . number_format($total_catorceavo_proporcional, 2);
	echo "</td>";
	echo "</tr>";

	////////////////// DECIMOCUARTO PROPORCIONAL //////////////////////

	$sub_total_derechos = $total_preaviso + $total_cesantia + $total_cesantia_proporcional + $total_vacaciones_proporcional + $total_aguinaldo_proporcional + $total_catorceavo_proporcional;

	echo "<tr>";
	echo "<td colspan = '3'><b> SUB TOTAL A PAGAR </b></td>";
	echo "<td><b>L. " . number_format($sub_total_derechos, 2) . "</b></td>";
	echo "</tr>";
	echo "</table>";

}

?>