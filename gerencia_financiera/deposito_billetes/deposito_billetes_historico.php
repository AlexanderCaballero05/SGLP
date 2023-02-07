<?php
require '../../template/header.php';

if (isset($_POST['procesar_mayor'])) {
	$_SESSION['procesar_mayor'] = $_POST['procesar_mayor'];
	?>
<script type="text/javascript">
window.location = "./deposito_billetes_historico_detalle.php";
</script>
<?php
}

if (isset($_POST['procesar_mayor_reporte'])) {
	$_SESSION['procesar_mayor'] = $_POST['procesar_mayor_reporte'];
	?>
<script type="text/javascript">
window.location = "./deposito_billetes_historico_detalle_reporte.php";
</script>
<?php
}

?>

<br>

<ul class="nav nav-tabs">
 <li class="nav-item">
    <a  class="nav-link" href="./screen_sorteos_mezclas.php">PENDIENTES DE MEZCLA</a>
  </li>
  <li class="nav-item">
    <a style="background-color:#ededed;" class="nav-link"  >HISTORICO</a>
  </li>
</ul>


<section style="background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >HISTORICO DE MEZCLA DE SORTEOS</h2>
<br>
</section>
<br>


<form method="POST">

<div class="card" style="margin-left: 15px; margin-right: 15px">

<div <col class="card-body">


<div class="well">
<div class="panel panel-default" style="width:100%">

 <table id="table_id1" class="table table-hover table-bordered">
        <thead>
            <tr>
                <th width="20%">Sorteo</th>
                <th width="20%">Fecha de Sorteo</th>
                <th width="20%">Cantidad</th>
                <th width="20%">Descripcion</th>
                <th width="20%">Accion</th>
                </tr>
        </thead>
        <tbody>

<?php

$result2 = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE estado_sorteo = 'PENDIENTE DISTRIBUCION' OR estado_sorteo = 'CAPTURADO' ORDER BY no_sorteo_may DESC ");

if ($result2 != null) {

	while ($row = mysqli_fetch_array($result2)) {

		echo '<tr>
   <td>' . $row['no_sorteo_may'] . '</td>
   <td>' . $row['fecha_sorteo'] . '</td>
   <td>' . $row['cantidad_numeros'] . ' billetes</td>
   <td>' . $row['descripcion_sorteo_may'] . '</td>

    <td align = "center">
    <button class="btn btn-primary"  name="procesar_mayor" value="' . $row['id'] . '" type="submit">Procesar</button>
    <button class="btn btn-success"  name="procesar_mayor_reporte" value="' . $row['id'] . '" type="submit">Imprimir</button>
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

</div>
</div>

<br>