<?php

require "../../conexion.php";

$entidad = $_GET['e'];

$agencias = mysqli_query($conn, "SELECT a.id, a.nombre, a.geocodigo , b.departamento, b.municipio FROM fvp_seccionales as a INNER JOIN departamentos_municipios as b  ON a.geocodigo_id = b.id  WHERE a.id_empresa = '$entidad'  ");

if (mysqli_num_rows($agencias) > 0) {

	echo '<div class="input-group" style="margin:5px 0px 5px 0px;">';
	echo '<div class="input-group-prepend"><span class="input-group-text">Agencia: </span></div>';

	echo "<select class='form-control' name='s_agencia'  >";
	while ($r_agencias = mysqli_fetch_array($agencias)) {

		$concat_info_agencia = $r_agencias['nombre'] . "!" . $r_agencias['departamento'] . "!" . $r_agencias['municipio'] . "!" . $r_agencias['id'];

		echo "<option value = '" . $concat_info_agencia . "'>" . $r_agencias['nombre'] . " - Depto. " . $r_agencias['departamento'] . " - Mun. " . $r_agencias['municipio'] . "</option>";
	}
	echo "</select>";

	echo "</div>";

} else {

	echo "<div class = 'alert alert-danger'>No existen agencias o puntos de ventas registrados para la entidad seleccionada.</div>";

}

?>
