<?php
require '../../template/header.php';
?>


<form method="POST">

<br>

<ul class="nav nav-tabs" style="margin-left: 10px; margin-right: 10px">
  <li class="nav-item">
    <a class="nav-link " href="./screen_produccion_control_menor.php" >Controles Nuevos</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" style="background-color:#ededed;"  >Historico de Controles</a>
  </li>
</ul>




<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >HISTORICO DE CONTROLES DE PRODUCCION MENOR</h2>
<br>
</section>





<?php

//if (isset($_POST['seleccionar']) || isset($_SESSION['id_sorteo_control_mayor'])) {

$controles_iniciados = mysqli_query($conn, "SELECT a.contador_final,a.contador_final_maquina,a.id,a.fecha,a.id_orden,a.id_orden_2,a.etapa,a.maquina,a.contador_inicial,a.jornada,a.grupo,a.estado,b.maquina as nombre_maquina, c.no_sorteo_men FROM pro_control_menor as a INNER JOIN pro_maquinas as b INNER JOIN sorteos_menores as c ON a.maquina = b.id AND a.id_orden = c.id WHERE estado != 'INICIADO' ORDER BY a.id DESC ");

?>

<br>

<div class="card" style="margin-right: 10px; margin-left: 10px" >
<div class="card-header bg-success text-white">
<h3 align="center">Historico de Controles de Loteria Menor</h3>
</div>

<div class="card-body">
<table id="table_id1" width="100%" class="table table-bordered">
<thead>
<tr>
	<th>Sorteo</th>
	<th>Fecha</th>
	<th>Maquina</th>
	<th>Etapa</th>
	<th>Contador Inicial</th>
	<th>Contador Final</th>
	<th>F/S</th>
	<th>Jornada</th>
	<th>Estado</th>
	<th>Accion</th>
</tr>
</thead>
<tbody>
<?php

while ($control = mysqli_fetch_array($controles_iniciados)) {

	if ($control['contador_final_maquina'] != '') {
		$faltante_sobrante = $control['contador_final'] - $control['contador_final_maquina'];
	} else {
		$faltante_sobrante = '';
	}

	echo "<tr>";
	echo "<td>" . $control['no_sorteo_men'] . "</td>";
	echo "<td>" . $control['fecha'] . "</td>";
	echo "<td>" . $control['maquina'] . "</td>";
	echo "<td>" . $control['etapa'] . "</td>";
	echo "<td>" . $control['contador_inicial'] . "</td>";
	echo "<td>" . $control['contador_final_maquina'] . "</td>";
	echo "<td>" . $faltante_sobrante . "</td>";
	echo "<td>" . $control['jornada'] . "</td>";
	echo "<td>" . $control['estado'] . "</td>";
	echo "<td align = 'center'>";

	if ($control['grupo'] == "Reposiciones") {

		echo "<a href = 'produccion_control_menor_detalle_r.php?id=" . $control['id'] . "' class = 'btn btn-primary'>Ingresar</a> ";

	} else {

		echo "<a href = 'produccion_control_menor_detalle.php?id=" . $control['id'] . "' class = 'btn btn-primary'>Ingresar</a> ";

	}

	echo "</td>";

	echo "</tr>";

}

?>
</tbody>
</table>
<br>

</div>
</div>



</form>