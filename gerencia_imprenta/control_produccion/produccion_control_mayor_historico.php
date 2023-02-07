<?php
require '../../template/header.php';
?>


<form method="POST">

<br>

<ul class="nav nav-tabs" style="margin-left: 10px; margin-right: 10px">
  <li class="nav-item">
    <a class="nav-link " href="./screen_produccion_control_mayor.php" >Controles Nuevos</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" style="background-color:#ededed;"  >Historico de Controles</a>
  </li>
</ul>




<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >HISTORICO DE CONTROLES DE PRODUCCION MAYOR</h2>
<br>
</section>



<?php

//if (isset($_POST['seleccionar']) || isset($_SESSION['id_sorteo_control_mayor'])) {

$controles_iniciados = mysqli_query($conn, "SELECT a.contador_final,a.contador_final_maquina,a.id,a.fecha,a.id_orden,a.id_orden_2,a.etapa,a.maquina,a.contador_inicial,a.jornada,a.billete_inicial,a.estado,b.maquina as nombre_maquina, c.no_sorteo_may FROM pro_control as a INNER JOIN pro_maquinas as b INNER JOIN sorteos_mayores as c ON a.maquina = b.id AND a.id_orden = c.id WHERE estado != 'INICIADO' ORDER BY a.id DESC ");

?>

<br>

<div class="card" style="margin-right: 10px; margin-left: 10px" >
<div class="card-header bg-success text-white">
<h3 align="center">Historico de Controles de Loteria Mayor</h3>
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
	<th>Billete Inicial</th>
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
	echo "<td>" . $control['no_sorteo_may'] . "</td>";
	echo "<td>" . $control['fecha'] . "</td>";
	echo "<td>" . $control['maquina'] . "</td>";
	if ($control['etapa'] == 5) {
		echo "<td>2 Para Reposicion</td>";
	} elseif ($control['etapa'] == 4) {
		echo "<td>Reposicion</td>";
	} else {
		echo "<td>" . $control['etapa'] . "</td>";
	}
	echo "<td>" . $control['contador_inicial'] . "</td>";
	echo "<td>" . $control['contador_final_maquina'] . "</td>";
	echo "<td>" . $faltante_sobrante . "</td>";
	echo "<td>" . $control['billete_inicial'] . "</td>";
	echo "<td>" . $control['jornada'] . "</td>";
	echo "<td>" . $control['estado'] . "</td>";

	if ($control['etapa'] == 4) {

		echo "<td align = 'center'>
		<a href = 'produccion_control_mayor_detalle_4.php?id=" . $control['id'] . "' class = 'btn btn-primary'>Ingresar</a>
	</td>";

		echo "</tr>";

	} else {

		if ($control['etapa'] == 5) {

			echo "<td align = 'center'>
		<a href = 'produccion_control_mayor_detalle_5.php?id=" . $control['id'] . "' class = 'btn btn-primary'>Ingresar</a>
	</td>";

			echo "</tr>";

		} elseif ($control['etapa'] != 2) {

			echo "<td align = 'center'>
		<a href = 'produccion_control_mayor_detalle_1.php?id=" . $control['id'] . "' class = 'btn btn-primary'>Ingresar</a>
	</td>";

			echo "</tr>";

		} else {

			echo "<td align = 'center'>
		<a href = 'produccion_control_mayor_detalle.php?id=" . $control['id'] . "' class = 'btn btn-primary'>Ingresar</a>
	</td>";

			echo "</tr>";

		}
	}

}

?>
</tbody>
</table>
<br>

</div>
</div>



</form>