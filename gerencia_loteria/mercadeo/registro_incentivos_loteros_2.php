<?php
require '../../template/header.php';

$id_sorteo = $_GET['sort'];

$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE id = '$id_sorteo' ");
$og_info = mysqli_fetch_object($info_sorteo);
$sorteo = $og_info->no_sorteo_men;
$fecha_sorteo = $og_info->fecha_sorteo;

if (isset($_POST['agregar_incentivo'])) {

	$id_sorteo = $_POST['id_sorteo'];
	$descripcion = $_POST['descripcion'];
	$valor = $_POST['valor'];
	$cantidad = $_POST['cantidad'];

	$i = 1;
	while ($i <= $cantidad) {

		$registro_incentivo = mysqli_query($conn, "INSERT INTO sorteos_menores_incentivos (id_sorteo, descripcion_incentivo, valor_incentivo) VALUES ('$id_sorteo','$descripcion','$valor') ");

		if ($registro_incentivo == TRUE) {

			mysqli_query($conn, "UPDATE sorteos_menores SET incentivo_vendedores = 1 WHERE id = '$id_sorteo' ");

			echo "<div class = 'alert alert-success'>Incentivo <b>" . $descripcion . "</b> registrado correctamente.</div>";
		} else {
			echo "<div class = 'alert alert-danger'>Error inesperado, por favor vuelva a intentarlo.</div>";

			echo mysqli_error($conn);
		}

		$i++;
	}

}

if (isset($_POST['eliminar_incentivo'])) {

	$id_sorteo = $_POST['id_sorteo'];
	$id = $_POST['eliminar_incentivo'];

	$delete_incentivo = mysqli_query($conn, "DELETE FROM sorteos_menores_incentivos WHERE id = '$id' ");
	$incentivos_sorteos = mysqli_query($conn, "SELECT * FROM sorteos_menores_incentivos WHERE id_sorteo = '$id_sorteo' ");

	if (mysqli_num_rows($incentivos_sorteos) == 0) {
		mysqli_query($conn, "UPDATE sorteos_menores SET incentivo_vendedores = 0 WHERE id = '$id_sorteo' ");
	}

	if ($delete_incentivo == TRUE) {
		echo "<div class = 'alert alert-success'>Incentivo eliminado correctamente.</div>";
	} else {
		echo "<div class = 'alert alert-danger'>Error inesperado, por favor vuelva a intentarlo.</div>";
		echo mysqli_error($conn);
	}

}

?>

<script type="text/javascript">

function waiting(){
	$(".div_wait").fadeIn("fast");
}

</script>

<form method="POST">


<section style="background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >Asignacion de Incentivos a Vendedores de Loteria Menor</h2>
<h4 align='center'>Sorteo No. <?php echo $sorteo; ?> Fecha de Sorteo <?php echo $fecha_sorteo; ?></h4>
<br>
</section>
<br>


<?php

echo "<input type = 'hidden' name = 'id_sorteo' value = '" . $id_sorteo . "'>";
echo '<input type="hidden" id="filas" name="filas" >';

?>



<div class="card" style="margin-left: 10px;margin-right: 10px">
<div align="center" class="card-header">


<div class="input-group" style="margin:10px 0px 10px 0px; width: 90% " >

<div class="input-group-prepend"><span style="width: 140px" class="input-group-text">Cantidad: </span></div>
<input type="number" min="1" maxlength="100" style="max-width: 100px" class = 'form-control' name="cantidad" id="cantidad">

<div style="margin-left: 10px" class="input-group-prepend"><span style="width: 140px" class="input-group-text">Desc. Incentivo: </span></div>
<input type="text" maxlength="100" class = 'form-control' name="descripcion" id="descripcion">


<div style="margin-left: 10px" class="input-group-prepend"><span style="width: 100px" class="input-group-text">Valor Lps.: </span></div>
<input type="number" class = 'form-control' name="valor"  style="max-width: 150px" id="valor">

<div class="input-group-prepend"><button type = 'submit' style="margin-left: 10px" class="btn btn-success"  name="agregar_incentivo">Agregar</button></div>
</div>


</div>

<div class="panel-body">

<div  >

<br>
<div class="well" style="width:100%;" >
	<table id="tabla_premios" class="table table-bordered">
<tr>
	<th >Desc. Incentivo</th>
	<th style="width: 15%">Valor</th>
	<th >Accion</th>
</tr>

<?php

$incentivos_sorteos = mysqli_query($conn, "SELECT * FROM sorteos_menores_incentivos WHERE id_sorteo = '$id_sorteo' ");

while ($reg_incetivos = mysqli_fetch_array($incentivos_sorteos)) {
	echo "<tr>";
	echo "<td>" . $reg_incetivos['descripcion_incentivo'] . "</td>";
	echo "<td>" . number_format($reg_incetivos['valor_incentivo'], 2) . "</td>";

	if ($reg_incetivos['id_vendedor'] == NULL) {
		echo "<td align = 'center' width = '15%'><button type = 'submit' name = 'eliminar_incentivo' value = '" . $reg_incetivos['id'] . "' class = 'btn btn-danger' >Eliminar</button></td>";
	} else {
		echo "<td align = 'center' width = '15%'><button type = 'submit' name = 'eliminar_incentivo' value = '" . $reg_incetivos['id'] . "' class = 'btn btn-danger' disabled>Eliminar</button></td>";
	}
	echo "</tr>";
}

?>

</table>
</div>

</div>

</div>


</div>


</form>


