<?php
require '../../template/header.php';

if (isset($_POST['procesar_mayor'])) {
	$_SESSION['deposito_mayor'] = $_POST['procesar_mayor'];
	?>
<script type="text/javascript">
window.location = "./deposito_billetes.php";
</script>
<?php
}

?>

<br>



<ul class="nav nav-tabs">
<li class="nav-item">
<a style="background-color:#ededed;" class="nav-link"  >PENDIENTES DE MEZCLA</a>
</li>
<li class="nav-item">
<a  class="nav-link" href="./deposito_billetes_historico.php">HISTORICO</a>
</li>
</ul>


<section style="background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >SORTEOS MAYORES PENDIENTES DE MEZCLA</h2>
<br>
</section>
<br>


<form method="POST">



<div class="card" style="margin-left: 15px; margin-right: 15px">

<div <col class="card-body">

<table id="table_id1" class="table table-hover table-bordered">
<thead>
<tr>
<th width="20%">Sorteo</th>
<th width="20%">Fecha de Sorteo</th>
<th width="20%">Cantidad</th>
<th width="30%">Descripcion</th>
<th width="10%">Accion</th>
</tr>
</thead>
<tbody>

<?php

$result2 = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE estado_sorteo = 'PENDIENTE DEPOSITO BILLETES' ");

if ($result2 != null) {

	while ($row = mysqli_fetch_array($result2)) {

		echo '<tr>
<td>' . $row['no_sorteo_may'] . '</td>
<td>' . $row['fecha_sorteo'] . '</td>
<td>' . $row['cantidad_numeros'] . ' billetes</td>
<td>' . $row['descripcion_sorteo_may'] . '</td>
<td>
<button class="btn btn-primary"  name="procesar_mayor" value="' . $row['id'] . '" type="submit">Procesar</button>
</td>
</tr>
';

	}
}

?>


</tbody>
</table>

</div>
</div>
</form>


<br>