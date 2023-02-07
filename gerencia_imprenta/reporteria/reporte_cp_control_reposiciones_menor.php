<?php
require '../../template/header.php';

$select_sorteos = mysqli_query($conn, "SELECT * FROM sorteos_menores ORDER BY no_sorteo_men DESC ");

?>


<style type="text/css" >

@media print{
	@page {
		}
}

</style>


<form method="POST">

<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h3  align="center" style="color:black; "  >PATRONATO NACIONAL DE LA INFANCIA </h3>
<h3  align="center" style="color:black; "  >DEPARTAMENTO DE PRODUCCION </h3>
<h3  align="center" style="color:black; "  >CONTROL DE REPOSICIONES </h3>

</section>




<a style = "width:100%" id="non-printable"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse">
 SELECCION DE PARAMETROS
</a>

<div class="collapse " id="collapse1" align="center">


<div class="input-group" style="margin:10px 0px 10px 0px; width: 70%" >

<div class = "input-group-prepend"><span  class="input-group-text">Seleccione un sorteo: </span></div>
<select name="sorteo" style="width:10%" class="form-control">
<?php
while ($sorteo = mysqli_fetch_array($select_sorteos)) {
	echo "<option value = '" . $sorteo['id'] . "'>" . $sorteo['no_sorteo_men'] . "</option>";
}
?>
</select>

<div class="input-group-prepend" style="margin-left: 5px;" ><span  class="input-group-text">Fecha Inicio Control: </span></div>
<input type='date' id ="fecha_i" style="width:15%" name = "fecha_inicial" class="form-control" >

<div class="input-group-prepend" style="margin-left: 5px;" ><span  class="input-group-text">Fecha Final Control: </span></div>
<input type='date' id ="fecha_f" style="width:15%" name = "fecha_final" class="form-control" >

<input type="submit" name="seleccionar" style="margin-left: 5px;" class="btn btn-primary" value = "Seleccionar">


</div>

</div>


<br><br>

</form>

<?php

if (isset($_POST['seleccionar'])) {

	$id_sorteo = $_POST['sorteo'];

	$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE id = '$id_sorteo' ");
	$i_sorteo = mysqli_fetch_object($info_sorteo);
	$numero_sorteo = $i_sorteo->no_sorteo_men;
	$registro_inicial = $i_sorteo->desde_registro;
	$fecha_sorteo = $i_sorteo->fecha_sorteo;
	$fecha = $_POST['fecha_inicial'];
	$fecha_f = $_POST['fecha_final'];

	echo "Sorteo No. <u>" . $id_sorteo . "</u>";
	echo "<table class = 'table table-bordered table-sm'>";
	echo "<tr>";
	echo "<th rowspan = '2' style = 'text-align: center'>NUMERO</th>";
	echo "<th rowspan = '2' style = 'text-align: center'>PRODUCIDAS</th>";
	echo "<th colspan = '2' style = 'text-align: center'>TIRTURACION</th>";
	echo "<th rowspan = '2' style = 'text-align: center'>DIFERENCIA</th>";
	echo "</tr>";
	echo "<tr>";
	echo "<th style = 'text-align: center'>No Utilizadas</th>";
	echo "<th style = 'text-align: center'>Repuestas</th>";
	echo "</tr>";

	$i = 0;

	$total = 0;
	$tt_formas_pro = 0;
	while ($i < 10) {
		$concat = $i . "0 ~ " . $i . "9";

		$consulta_rep = mysqli_query($conn, "SELECT SUM(b.cantidad) as cantidad FROM pro_control_menor as a INNER JOIN pro_control_detalle_menor as b ON a.id = b.id_control WHERE a.id_orden = '$id_sorteo' AND a.grupo LIKE '$concat' AND b.tipo IN ('Reposiciones')   ");

		$ob_rep = mysqli_fetch_object($consulta_rep);
		$cantidad = $ob_rep->cantidad;

		$total += $cantidad;
		$tt_formas_pro += $cantidad;

		echo "<tr>";
		echo "<td>" . $concat . "</td>";
		echo "<td>" . number_format($cantidad) . "</td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "</tr>";

		$i++;
	}

	$consulta_rep = mysqli_query($conn, "SELECT SUM(billetes_buenos) as cantidad FROM pro_control_menor as a  WHERE a.id_orden = '$id_sorteo' AND a.grupo LIKE 'S/N'    ");

	$ob_rep = mysqli_fetch_object($consulta_rep);
	$formas_sn = $ob_rep->cantidad;

	echo "<tr>";
	echo "<td>S/N</td>";
	echo "<td>" . number_format($formas_sn) . "</td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>S/S</td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";

	$total += $formas_sn;

	echo "<tr>";
	echo "<td>TOTAL</td>";
	echo "<td>" . number_format($total) . "</td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";

	echo "</table>";

	echo "<br>";

	?>

<table  width="100%">

<tr>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td colspan="2" align="center"><b>LIQUIDACIÓN</b></td>
</tr>

<tr>
	<td width="20%">TOTAL FORMAS PRODUCIDAS</td>
	<td width="10%"><b><u><?php echo number_format($tt_formas_pro); ?></b></u></td>
	<td width="1%" rowspan="4" align="center" valign="center"></td>
	<td width="36%" rowspan="4" align="center" valign="center"><b><hr style="border: 1px solid">Inspector de procesos</b></td>
	<td width="3%" rowspan="4" align="center" valign="center"></td>
	<td width="20%">FORMAS NO UTILIZADAS</td>
	<td width="10%">_______</td>
</tr>

<tr>
	<td width="20%">MÁS FORMAS SIN NUMERO</td>
	<td width="10%"><b><u><?php echo number_format($formas_sn); ?></b></u></td>
	<td width="20%">FORMAS UTILIZADAS</td>
	<td width="10%">_______</td>
</tr>

<tr>
	<td width="20%">OTRAS</td>
	<td width="10%">_______</td>
	<td width="20%">FORMAS UTILIZADAS</td>
	<td width="10%">_______</td>
</tr>

<tr>
	<td width="20%">DISPONIBILIDAD</td>
	<td width="10%"><b><u><?php echo number_format($total); ?></b></u></td>
	<td width="20%">DIFERENCIAS</td>
	<td width="10%">_______</td>
</tr>

</table>
<br><br>
<?php

}

?>

