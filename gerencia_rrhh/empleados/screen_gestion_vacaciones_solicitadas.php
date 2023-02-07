<?php
require '../../template/header.php';

$c_vacaciones_solicitadas = mysqli_query($conn, "SELECT a.identidad, a.id_periodo, a.fecha, a.estado, a.estado_rrhh, a.id_usuario, a.fecha_registro, a.fecha_aprobacion FROM rr_hh_vacaciones_tomadas as a WHERE a.estado = 'A' AND a.estado_rrhh = 'P' ");

?>



<br>

<div class="card" style = 'margin-left: 10px; margin-right: 10px'>
<div class="card-header bg-success text-white" >
	<h4 align="center">SOLICITUDES DE VACACIONES PENDIENTES DE APROBACIÓN</h4>
</div>
<div class="card-body">

<table class="table table-bordered">
<tr>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
</tr>

<?php
while ($reg_vacaciones_solicitadas = mysqli_fetch_array($c_vacaciones_solicitadas)) {

	echo "<tr>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";

}
?>

</table>

</div>
</div>