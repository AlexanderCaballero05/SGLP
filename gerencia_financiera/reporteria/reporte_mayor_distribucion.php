<?php
require '../../template/header.php';
$years = mysqli_query($conn, "SELECT YEAR(fecha_sorteo) as year FROM sorteos_mayores GROUP BY YEAR(fecha_sorteo) ORDER BY YEAR(fecha_sorteo) DESC ");
?>



<body>
<form method="POST">




<section style="color:rgb(63,138,214);background-color:#ededed;">
<br>
<h1  align="center" style="color:black; "  >REPORTE DE DISTRIBUCIONES DE LOTERIA MAYOR (AGENCIAS)</h1>
<br>
</section>


<a class="btn btn-secondary" id="non-printable" style="width:100%" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
Selección de parametros
</a>

<div  class="collapse" style = "width:100%"  id="collapse1" align="center">
<div class="card" style="width: 50%">
<div class="card-body">


<div class="input-group " style="margin:0px 0px 0px 0px;">
<div class="input-group-prepend"><span class="input-group-text">Año: </span></div>
<select class="form-control" name="select_year" id = 'select_year' style="margin-right: 5px">
<?php
while ($reg_year = mysqli_fetch_array($years)) {
	echo "<option value = '" . $reg_year['year'] . "' >" . $reg_year['year'] . "</option>";
}
?>
</select>


<div class="input-group-append">
<button class="btn btn-success" type="submit" name="seleccionar" > SELECCIONAR</button>
</div>
</div>







</div>
</div>
</div>



<?php
if (isset($_POST['seleccionar'])) {

	$year = $_POST['select_year'];
	$sorteos_en_fecha = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE YEAR(fecha_sorteo) = '$year'  ORDER BY id ");

	?>

<div class="card" style="margin-right: 5px; margin-left: 5px">
	<div class="card-header">
		<h3 align="center">AÑO <?php echo $year; ?> </h3>
	</div>

	<div class="card-body">



	</div>
</div>

	<?php

}
?>



</form>