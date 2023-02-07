<?php
require "../../template/header.php";
date_default_timezone_set('America/Tegucigalpa');
?>


<script type="text/javascript">
function generar_acta(){

sorteo = document.getElementById('s_sorteo').value;

window.open("../captura_sorteo/acta_sorteo_menor_oficial.php?sorteo="+sorteo, "Acta de loteria mayor", "width=900,height=900");

}
</script>


<section style="background-color:#ededed;">
<br>
<h3 align="center"><b>EMISION DE ACTAS CAPTURA DE SORTEO LOTERIA MENOR </b></h3>
<br>
</section>



<a  id = 'non-printable' style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
Seleccion de Parametros
</a>

<form method="POST">

<div class="card collapse" id="collapse1" style="margin-left: 250px; margin-right: 250px;" >
<div class="card-body">

<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">Sorteo:</div>
</div>

<select class="form-control"  name = "s_sorteo" id = 's_sorteo' ">
<?php
$sorteos = mysqli_query($conn, "SELECT a.id, a.no_sorteo_men, a.fecha_sorteo, a.descripcion_sorteo_men  FROM sorteos_menores as a inner join empresas_estado_venta as b ON a.id = b.id_sorteo WHERE   b.cod_producto != 1 GROUP BY b.id_sorteo ORDER BY a.id DESC ");

while ($row2 = mysqli_fetch_array($sorteos)) {
	echo '<option value = "' . $row2['id'] . '">No.' . $row2['no_sorteo_men'] . ' -- Fecha ' . $row2['fecha_sorteo'] . ' -- ' . $row2['descripcion_sorteo_men'] . '</option>';
}
?>
</select>


<div class="input-group-append">
	<span  name="seleccionar" onclick="generar_acta()" id="seleccionar" class="btn btn-primary">Seleccionar</span>
</div>

</div>

</div>
</div>

</form>