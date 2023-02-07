<?php
require('../../template/header.php');

$consulta_pendientes = mysqli_query($conn, "SELECT a.id_sorteo, SUM(a.cantidad) as cantidad, b.fecha_sorteo, b.descripcion_sorteo_men FROM sorteos_menores_solicitudes_extras as a INNER JOIN sorteos_menores as b ON a.id_sorteo = b.no_sorteo_men  GROUP BY a.id_sorteo ORDER BY a.id_sorteo DESC ");

if ($consulta_pendientes === FALSE) {
echo  mysql_error();
}

?>

<br>

<div class="card" style="margin-left: 10px;margin-right: 10px">
<div class="card-header">

<h2  align="center" style="color:black; "  >Agrupacion de Produccion Loteria Menor</h2> 

</div>

<div class="card-body">

<table id="table_id1" width="100%" class="table table-bordered">
<thead>
	<tr>
		<th>Sorteo</th>
		<th>Cantidad Extra</th>
		<th>Descripcion</th>
		<th>Fecha Sorteo</th>
		<th>Accion</th>
	</tr>
</thead>	

<tbody>	

<?php

while ($reg_pendientes = mysqli_fetch_array($consulta_pendientes)) {

echo "<tr>";
echo "<td>".$reg_pendientes['id_sorteo']."</td>";
echo "<td>".$reg_pendientes['cantidad']."</td>";
echo "<td>".$reg_pendientes['descripcion_sorteo_men']."</td>";
echo "<td>".$reg_pendientes['fecha_sorteo']."</td>";
echo "<td align = 'center' ><a href = './menor_asignacion_grupos.php?s=".$reg_pendientes['id_sorteo']."' class = 'btn btn-primary' > Asignar Grupos</td>";
echo "</tr>";

}

?>

</tbody>

</table>
	
</div>	
</div>