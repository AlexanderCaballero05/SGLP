<?php
require '../../template/header.php';
?>

<script type="text/javascript">
jQuery(function($){
$("#identidad").mask("9999-9999-99999", { placeholder: "____-____-_____" });
});


function consultar_empleado(){
identidad = document.getElementById('identidad').value;
accion = 1;

 document.getElementById('respuesta_calculo_prestaciones').innerHTML = "";


token = Math.random();
consulta = "calculo_prestaciones_js.php?id="+identidad+"&accion="+accion+"&token="+token;
$("#respuesta_info_general").load(consulta);


}

function cambio_fecha_final(fecha_final, id_tipo_contrato){

identidad = document.getElementById('identidad').value;
fecha_inicio = document.getElementById('fecha_inicio').value;
accion = 2;
token = Math.random();

consulta = "calculo_prestaciones_js.php?id="+identidad+"&fecha_inicio="+fecha_inicio+"&fecha_final="+fecha_final+"&id_tipo_contrato="+id_tipo_contrato+"&accion="+accion+"&token="+token;
$("#respuesta_calculo_prestaciones").load(consulta);
}

</script>

<br>


<div class="card " style="margin-left: 10px; margin-right: 10px;">
<div class="card-header bg-success text-white">
<h4 align="center">CALCULO DE PRESTACIONES LABORALES PANI</h4>
</div>
<div class="card-body" >

<div class="card">
	<div class="card-header">
		<h5 align="center"><b>DATOS GENERALES</b></h5>
	</div>

	<div class="card-body" >
		<div class="row">
		<div class="col col-md-6">
		<div class="input-group" >
			<div class="input-group-prepend" style="width: 25%"><span style="width: 100%" class="input-group-text">Identidad: </span></div>
			<input type="text" class="form-control" id="identidad" name="identidad"  >
			<div  class="input-group-append">
			<button class="btn btn-success" type="submit" name="seleccionar" onclick="consultar_empleado()" > <span class="fa fa-search"></span></button>
			</div>
		</div>
		</div>

		<div class="col col-md-6">
		<div class="input-group" style="margin:5px 0px 5px 0px;">
			<div class="input-group-prepend" style="width: 25%" ><span style="width: 100%" class="input-group-text">Finalizacion Por: </span></div>
			<select class = "form-control" name = "finalizacion_por">
			<option value = "Despido">Despido</option>
			<option value = "Renuncia">Renuncia</option>
			</select>
		</div>
		</div>

	</div>


		<div id="respuesta_info_general"></div>
		<div id="respuesta_calculo_prestaciones"></div>

		</div>

</div>
</div>

</div>