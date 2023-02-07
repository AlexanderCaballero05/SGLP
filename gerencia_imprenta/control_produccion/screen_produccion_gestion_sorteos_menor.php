<?php
require '../../template/header.php';

$select_sorteos = mysqli_query($conn, "SELECT * FROM sorteos_menores ORDER BY no_sorteo_men DESC ");

if (isset($_POST['cambiar_estado'])) {

	$id_sorteo = $_POST['id_sorteo_o'];
	$new_estado = $_POST['cambiar_estado'];

	if (mysqli_query($conn, " UPDATE sorteos_menores SET produccion = '$new_estado' WHERE id = '$id_sorteo' ") === TRUE) {

		echo "<div class = 'alert alert-info'><span class = 'fa fa-info-circle'></span> Cambios realizados correctamente.</div>";

	} else {

		echo "<div class = 'alert alert-error'><span class = 'fa fa-exclamation-triangle'></span> Error inesperado, por favor vuelva a intentarlo.</div>";

	}

}

?>

<br>

<ul class="nav nav-tabs" style="margin-left: 10px; margin-right: 10px">
  <li class="nav-item">
    <a  class="nav-link" href="./screen_produccion_gestion_sorteos_mayor.php" >Lotería Mayor</a>
  </li>
  <li class="nav-item">
    <a style="background-color:#ededed;" class="nav-link active"  >Lotería Menor</a>
  </li>
</ul>

<section style="background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >GESTION DE ETAPAS DE PRODUCCION MENOR</h2>
<br>
</section>




<form method="POST">

<a style = "width:100%" id="non-printable"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse">
 SELECCION DE PARAMETROS
</a>

<div class="collapse " id="collapse1" align="center">


<div class="input-group" style="margin:10px 0px 10px 0px; width: 70%" >

<div class = "input-group-prepend"><span  class="input-group-text">Seleccione un sorteo: </span></div>
<select name="sorteo" style="width:25%" class="form-control">
<?php
while ($sorteo = mysqli_fetch_array($select_sorteos)) {
	echo "<option value = '" . $sorteo['id'] . "'>" . $sorteo['no_sorteo_men'] . "</option>";
}
?>
</select>

<input type="submit" name="seleccionar" style="margin-left: 5px;" class="btn btn-primary" value = "Seleccionar">

</div>
</div>

<br><br><br>


<?php

if (isset($_POST['seleccionar'])) {

	$id_sorteo = $_POST['sorteo'];

	$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE id = '$id_sorteo' ");
	$ob_sorteo = mysqli_fetch_object($info_sorteo);
	$estado_pro = $ob_sorteo->produccion;

	$desc_estado = "";
	if ($estado_pro == "PENDIENTE RS") {
		$desc_estado = "PENDIENTE ASIGNACION DE REGISTROS";
	} elseif ($estado_pro == "PENDIENTE CP" OR $estado_pro == "") {
		$desc_estado = "PENDIENTE APERTURA PARA CONTROL DE PRODUCCION";
	} elseif ($estado_pro == "INICIADO CP") {
		$desc_estado = "CONTROL DE PRODUCCION INICIADO";
	} elseif ($estado_pro == "FINALIZADO CP") {
		$desc_estado = "CONTROL DE PRODUCCION FINALIZADO";
	}

	?>

<input type="hidden" name="id_sorteo_o" value = '<?php echo $id_sorteo; ?>' >

<div class="row">
<div class="col"></div>

<div class="col col-md-6">
<div class="card" style=" ;margin-right: 10px; margin-left: 10px; ">
<div class="card-header">
	<h4 align="center">SORTEO <?php echo $id_sorteo; ?></h4>
</div>
<div class="card-body" align="center">

<div class="alert alert-info"><span class = 'fa fa-info-circle'></span> El estado del sorteo es: <b><?php echo $desc_estado; ?></b></div>

</div>
<div class="card-footer" align="center">

<?php
if ($estado_pro == "PENDIENTE RS") {
		echo "<div class = 'alert alert-danger'> <span class = 'fa fa-exclamation-triangle'></ span> Debe asignar los registros de seguridad del sorteo para continuar.</div>";
	}

	if ($estado_pro == "PENDIENTE CP" OR $estado_pro == "") {
		echo "<button type = 'submit' name = 'cambiar_estado' value = 'INICIADO CP' class = 'btn btn-primary' >INICIAR ETAPA DE CONTROL DE PRODUCCION</button>";
	}

	if ($estado_pro == "INICIADO CP") {
		echo "<button type = 'submit' name = 'cambiar_estado' value = 'FINALIZADO CP' class = 'btn btn-primary' >FINALIZAR ETAPA DE CONTROL DE PRODUCCION</button>";
	}

	if ($estado_pro == "FINALIZADO CP") {
		echo "<div class = 'alert alert-danger'> <span class = 'fa fa-exclamation-triangle'></ span> El proceso de produccion para este sorteo ya finalizo.</div>";
	}

	?>

</div>
</div>
</div>

<div class="col"></div>
</div>

<?php

}

?>


</form>