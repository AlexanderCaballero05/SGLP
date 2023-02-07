<?php
require '../../template/header.php';

$c_tipo_contrataciones = mysqli_query($conn, "SELECT * FROM rr_hh_mto_contrataciones ");

/////////////////////////////////////////////
// CODIGO DE REGISTRO DE NUEVOS PARAMETROS //
if (isset($_POST['guardar_nuevo'])) {

	$id_tipo_contrato = $_POST['id_tipo_contratacion'];
	$descripcion = $_POST['descripcion'];
	$tipo = $_POST['tipo'];
	$valor = $_POST['valor'];
	$mes_aplicacion = $_POST['mes'];

	$estado = "A";

	if (mysqli_query($conn, "INSERT INTO rr_hh_mto_otros_derechos (descripcion, porcentual_fijo, valor, tipo_contratacion, mes_aplicacion) VALUES ('$descripcion','$tipo','$valor', '$id_tipo_contrato','$mes_aplicacion') ") === TRUE) {
		echo "<div class = 'alert alert-info'>Parametro de otros derechos registrado correctamente.</div>";
	} else {
		echo "<div class = 'alert alert-danger'>Error inesperado, por favor intente nuevamente o notifique a la unidad de informatica si el problema persiste.</div>";
		echo mysqli_error($conn);
	}

}
// CODIGO DE REGISTRO DE NUEVOS PARAMETROS //
/////////////////////////////////////////////

$v_meses[0] = "N/A";
$v_meses[1] = "ENERO";
$v_meses[2] = "FEBRERO";
$v_meses[3] = "MARZO";
$v_meses[4] = "ABRIL";
$v_meses[5] = "MAYO";
$v_meses[6] = "JUNIO";
$v_meses[7] = "JULIO";
$v_meses[8] = "AGOSTO";
$v_meses[9] = "SEPTIEMPRE";
$v_meses[10] = "OCTUBRE";
$v_meses[11] = "NOVIEMBRE";
$v_meses[12] = "DICIEMBRE";

?>

<script type="text/javascript">
	function cargar_id_contratacion(id_contratacion){
		document.getElementById('id_tipo_contratacion').value = id_contratacion;
	}
</script>


<section style="color:rgb(63,138,214);background-color:#ededed;">
<br>
<h3  align="center" style="color:black;" ><b>MANTENIMIENTO PARAMETROS DE OTROS DERECHOS</b></h3>
<br>
</section>
<br>

<div class="row">
<?php

while ($reg_tipo_contrataciones = mysqli_fetch_array($c_tipo_contrataciones)) {
	$id_tipo_contratacion = $reg_tipo_contrataciones['id'];

	$c_parametros_en_contrato = mysqli_query($conn, "SELECT * FROM rr_hh_mto_otros_derechos WHERE estado = 'A' AND tipo_contratacion = '$id_tipo_contratacion' ");

	?>

<div class="col col-md-6">
	<div class="card">
		<div class="card-header bg-dark text-white" >
			<h style="text-align: center"><?php echo $reg_tipo_contrataciones['descripcion']; ?></h>

<button class="btn btn-dark text-light fa fa-plus" data-toggle="modal" href="#modal-new" onclick="cargar_id_contratacion('<?php echo $id_tipo_contratacion; ?>')" type="button" style="float: right">
</button>

		</div>
		<div class="card-body">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Descripcion</th>
						<th >Valor</th>
						<th>Mes Aplicacion</th>
					</tr>
				</thead>
				<tbody>
					<?php
while ($reg_parametros_en_contrato = mysqli_fetch_array($c_parametros_en_contrato)) {
		echo "<tr>";
		echo "<td>" . $reg_parametros_en_contrato['descripcion'] . "</td>";
		if ($reg_parametros_en_contrato['porcentual_fijo'] == 1) {
			echo "<td>" . number_format($reg_parametros_en_contrato['valor'], 2) . " %</td>";
		} else {
			echo "<td>" . number_format($reg_parametros_en_contrato['valor'], 2) . " Lps.</td>";
		}

		echo "<td>" . $v_meses[$reg_parametros_en_contrato['mes_aplicacion']] . "</td>";

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


<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Descripcion: </span></div>
<input type="text" class="form-control" id="descripcion" name="descripcion"  >
</div>

<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Valor: </span></div>
<input type="text" class="form-control" id="valor" name="valor" >

<div class="input-group-prepend"><span class="input-group-text">Tipo: </span></div>
<select name="tipo" id="tipo" class="form-control" >
	<option value="1">Porcentual</option>
	<option value="2">Fijo</option>
</select>
</div>


<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Mes de aplicacion: </span></div>
<select name="mes" id="mes" class="form-control" >
	<option value="0">N/A</option>
	<option value="1">ENERO</option>
	<option value="2">FEBRERO</option>
	<option value="3">MARZO</option>
	<option value="4">ABRIL</option>
	<option value="5">MAYO</option>
	<option value="6">JUNIO</option>
	<option value="7">JULIO</option>
	<option value="8">AGOSTO</option>
	<option value="9">SEPTIEMBRE</option>
	<option value="10">OCTUBRE</option>
	<option value="11">NOVIEMBRE</option>
	<option value="12">DICIEMBRE</option>
</select>
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