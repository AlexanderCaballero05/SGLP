<?php
require '../../template/header.php';

$select_sorteos = mysqli_query($conn, "SELECT * FROM sorteos_mayores ORDER BY no_sorteo_may DESC ");
$maquinas = mysqli_query($conn, "SELECT * FROM pro_maquinas ");

?>


<script type="text/javascript">
function calcular_sobrantes_faltantes(s_f){
		total_liquidacion = document.getElementById('total_liquidacion_o').value;
		tt = parseInt(total_liquidacion) + parseInt(s_f);
		document.getElementById('total_liquidacion').value = tt;

		primera_etapa = document.getElementById('buenos_1').value;
		document.getElementById('faltante_sorante').value = parseInt(primera_etapa) - parseInt(tt);
	}
</script>

<style type="text/css">

@media print {

    @page {size: legal landscape; font-size: 7pt;margin-top: -100pt;}

}

</style>

<form method="POST">



<section style=" color:black;background-color:#ededed;">
<br>

<h6 align="center">PATRONATO NACIONAL DE LA INFANCIA</h6>
<h6 align="center">DEPARTAMENTO DE PRODUCCION</h6>
<h6 align="center">CONTROL DE LIQUIDACION DE PRODUCCION DE LOTERIA MAYOR


<?php

if (isset($_POST['seleccionar'])) {
	$id_sorteo = $_POST['sorteo'];
	echo "  DE SORTEO No. <u> " . $id_sorteo . " </u>";
}

?>

</h6>

<br>
</section>


<a style = "width:100%" id="non-printable"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse">
 SELECCION DE PARAMETROS
</a>


<div class="collapse " id="collapse1" align="center">
<div class="input-group" style="margin:10px 0px 10px 0px; width: 70%" >

<div class = "input-group-prepend"><span  class="input-group-text">Seleccione un sorteo: </span></div>
<select name="sorteo" style="width:25%" class="form-control">
<?php
while ($sorteo = mysqli_fetch_array($select_sorteos)) {
	echo "<option value = '" . $sorteo['id'] . "'>" . $sorteo['no_sorteo_may'] . "</option>";
}
?>
</select>

<input type="submit" name="seleccionar" style="margin-left: 5px;" class="btn btn-primary" value = "Seleccionar">

</div>
</div>


<br ><br id="non-printable">


<?php

if (isset($_POST['seleccionar'])) {
	$id_sorteo = $_POST['sorteo'];
	$busqueda = mysqli_query($conn, "SELECT * FROM pro_control_sobrantes_primera_etapa WHERE id_orden = '$id_sorteo' ");
	if ($busqueda === false) {
	}

	if (mysqli_num_rows($busqueda) > 0) {
		$ob_busqueda = mysqli_fetch_object($busqueda);
		$cant_sobrantes = $ob_busqueda->cantidad;
		$flag_sobrante_registrado = 1;
	} else {
		$cant_sobrantes = 0;
		$flag_sobrante_registrado = 0;
	}

	$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo' ");

	$i_sorteo = mysqli_fetch_object($info_sorteo);
	$numero_sorteo = $i_sorteo->no_sorteo_may;
	$fecha_sorteo = $i_sorteo->fecha_sorteo;
	$mezcla = $i_sorteo->mezcla;

	$controles_primera_etapa = mysqli_query($conn, "SELECT fecha, contador_inicial, etapa , maquina, contador_final,contador_final_maquina, billetes_buenos, id   FROM pro_control    WHERE id_orden = '$id_sorteo' AND etapa = 1   ORDER BY contador_inicial  , maquina , fecha ASC ");

	if ($controles_primera_etapa === FALSE) {
		echo mysqli_error($conn);
	}

	$fecha_actual = date("d-m-Y h:m:i a");
	echo "Fecha de emisión: <u>" . $fecha_actual . "</u>";

	?>



<table  class="table table-bordered table-sm " style="page-break-inside: avoid; font-size: 10pt">

	<tr style="background-color:#ededed;">
		<th colspan="8" style="text-align: center">PRIMER ETAPA (IMPRESION 4 COLORES)</th>
	</tr>

<tr>
  <th>Fecha</th>
  <th>Contador Inicial</th>
  <th>Pruebas blancas</th>
  <th>Billetes Malos</th>
  <th>I Etapa</th>
  <th>Contador Final</th>
  <th>Diferencia</th>
  <th>Observaciones</th>
</tr>

<?php

	$indicador_etapa1 = 0;
	$indicador_etapa2 = 0;
	$total_buenos = 0;
	$total_repos = 0;
	$total_blancas = 0;
	$total_malos = 0;
	$total_imp = 0;

	while ($control = mysqli_fetch_array($controles_primera_etapa)) {
		$id_control = $control['id'];

		if ($control['etapa'] == 1 && $indicador_etapa1 == 0) {
			$indicador_etapa1 = 1;
		}

		$consulta_cantidades_blancas = mysqli_query($conn, "SELECT SUM(cantidad) as suma FROM pro_control_detalle  WHERE id_control = '$id_control' AND tipo = 'H. Blancas' ");
		$ob_cantidades_blancas = mysqli_fetch_object($consulta_cantidades_blancas);
		$cantidad_blancas = $ob_cantidades_blancas->suma;

		$consulta_cantidades_malos = mysqli_query($conn, "SELECT SUM(cantidad) as suma FROM pro_control_detalle  WHERE id_control = '$id_control' AND tipo IN ('B. Malos','P. Montaje') ");
		$ob_cantidades_malos = mysqli_fetch_object($consulta_cantidades_malos);
		$cantidad_malos = $ob_cantidades_malos->suma;

		$consulta_repos = mysqli_query($conn, "SELECT SUM(reposiciones) as suma_r FROM pro_control_detalle  WHERE id_control = '$id_control' ");
		$ob_cantidades_r = mysqli_fetch_object($consulta_repos);
		$cantidad_r = $ob_cantidades_r->suma_r;

		$total_impreso = $control['billetes_buenos'] + $cantidad_malos + $cantidad_blancas + $cantidad_r;

		$diferencia = $control['contador_final'] - $control['contador_final_maquina'];

		echo "<tr>";
		echo "<td>" . $control['fecha'] . "</td>";
		echo "<td>" . $control['contador_inicial'] . "</td>";
		echo "<td>" . number_format($cantidad_blancas) . "</td>";
		echo "<td>" . number_format($cantidad_malos) . "</td>";
		echo "<td>" . number_format($control['billetes_buenos']) . "</td>";
		echo "<td>" . $control['contador_final'] . "</td>";
		echo "<td>" . number_format($diferencia) . "</td>";
		echo "<td></td>";
		echo "</tr>";

		$total_buenos = $total_buenos + $control['billetes_buenos'];
		$total_malos = $total_malos + $cantidad_malos;
		$total_blancas = $total_blancas + $cantidad_blancas;
		$total_repos = $total_repos + $cantidad_r;
		$total_imp = $total_imp + $total_impreso;

	}

	$total_buenos1 = $total_buenos * 2;

	echo "<tr>";
	echo "<td colspan ='2'>TOTAL</td>";
	echo "<td>" . number_format($total_blancas) . " x 2 = <b>" . number_format($total_blancas * 2) . "</b> </td>";
	echo "<td>" . number_format($total_malos) . " x 2 = <b>" . number_format($total_malos * 2) . "</b></td>";
	echo "<td>" . number_format($total_buenos) . " x 2 = <b>" . number_format($total_buenos * 2) . "</b></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";

	?>

</table>




<br id="non-printable">

<?php

	$controles_second_etapa = mysqli_query($conn, "SELECT fecha, contador_inicial, etapa , maquina, contador_final,contador_final_maquina, billetes_buenos, id   FROM pro_control    WHERE id_orden = '$id_sorteo' AND etapa IN (2,5)  GROUP BY contador_inicial  ORDER BY contador_inicial  , maquina , fecha ASC ");

	?>



<table  class="table table-bordered table-sm " style="page-break-inside: avoid; font-size: 10pt">
	<tr style="background-color:#ededed;">
		<th colspan="9" style="text-align: center">SEGUNDA ETAPA (IMPRESION DE NUMERO, REGISTRO, COMBINACION Y SEGURIDAD)</th>
	</tr>
<tr>
  <th>Fecha</th>
  <th>Contador Inicial</th>
  <th>Pruebas Blancas</th>
  <th>Billetes Malos</th>
  <th>II Etapa</th>
  <th>Cant. Para Rep.</th>
  <th>Contador Final</th>
  <th>Diferencia</th>
  <th>Observaciones</th>
</tr>

<?php

	$indicador_etapa1 = 0;
	$indicador_etapa2 = 0;
	$total_buenos = 0;
	$total_repos = 0;
	$total_blancas = 0;
	$total_malos = 0;
	$total_imp = 0;
	$tt_para_rep = 0;

	while ($control = mysqli_fetch_array($controles_second_etapa)) {
		$id_control = $control['id'];

		if ($control['etapa'] == 1 && $indicador_etapa1 == 0) {
			$indicador_etapa1 = 1;
		}

		$diferencia = $control['contador_final'] - $control['contador_final_maquina'];

		$consulta_cantidades_blancas = mysqli_query($conn, "SELECT SUM(cantidad) as suma FROM pro_control_detalle  WHERE id_control = '$id_control' AND tipo = 'H. Blancas' ");
		$ob_cantidades_blancas = mysqli_fetch_object($consulta_cantidades_blancas);
		$cantidad_blancas = $ob_cantidades_blancas->suma;

		$consulta_cantidades_malos = mysqli_query($conn, "SELECT SUM(cantidad) as suma FROM pro_control_detalle  WHERE id_control = '$id_control' AND tipo IN ('B. Malos','P. Montaje') ");
		$ob_cantidades_malos = mysqli_fetch_object($consulta_cantidades_malos);
		$cantidad_malos = $ob_cantidades_malos->suma;

		$consulta_repos = mysqli_query($conn, "SELECT SUM(reposiciones) as suma_r FROM pro_control_detalle  WHERE id_control = '$id_control' ");
		$ob_cantidades_r = mysqli_fetch_object($consulta_repos);
		$cantidad_r = $ob_cantidades_r->suma_r;

		$total_impreso = $control['billetes_buenos'] + $cantidad_malos + $cantidad_blancas + $cantidad_r;

		if (!isset($v_datos[$control['fecha']][0])) {

			$v_datos[$control['fecha']][6] = $control['fecha'];
			$v_datos[$control['fecha']][0] = $control['contador_inicial'];
			$v_datos[$control['fecha']][1] = $cantidad_blancas;
			$v_datos[$control['fecha']][2] = $cantidad_malos;
			if ($control['etapa'] != 5) {
				$v_datos[$control['fecha']][3] = $control['billetes_buenos'];
				$v_datos[$control['fecha']][7] = 0;
			} else {
				$v_datos[$control['fecha']][7] = $control['billetes_buenos'];
			}
			$v_datos[$control['fecha']][4] = $control['contador_final'];
			$v_datos[$control['fecha']][5] = $diferencia;

		} else {

			$v_datos[$control['fecha']][6] = $control['fecha'];
			$v_datos[$control['fecha']][1] += $cantidad_blancas;
			$v_datos[$control['fecha']][2] += $cantidad_malos;
			if ($control['etapa'] != 5) {
				$v_datos[$control['fecha']][3] = $control['billetes_buenos'];
			} else {
				$v_datos[$control['fecha']][7] = $control['billetes_buenos'];
			}
			$v_datos[$control['fecha']][4] = $control['contador_final'];
			$v_datos[$control['fecha']][5] += $diferencia;

		}

		if ($control['etapa'] != 5) {
			$total_buenos = $total_buenos + $control['billetes_buenos'];
		}

		$total_malos = $total_malos + $cantidad_malos;
		$total_blancas = $total_blancas + $cantidad_blancas;
		$total_repos = $total_repos + $cantidad_r;
		$total_imp = $total_imp + $total_impreso;

	}

	foreach ($v_datos as $value) {

		echo "<tr>";
		echo "<td>" . $value[6] . "</td>";
		echo "<td>" . $value[0] . "</td>";
		echo "<td>" . number_format($value[1]) . "</td>";
		echo "<td>" . number_format($value[2]) . "</td>";
		if (isset($value[3])) {
			echo "<td>" . number_format($value[3]) . "</td>";
		} else {
			echo "<td></td>";
		}

		if (isset($value[7])) {
			echo "<td>" . number_format($value[7]) . "</td>";
			$tt_para_rep += $value[7];
		} else {
			echo "<td></td>";
		}

		echo "<td>" . $value[4] . "</td>";
		echo "<td>" . number_format($value[5]) . "</td>";
		echo "<td></td>";
		echo "</tr>";

	}

	$total_buenos2 = $total_buenos * 2;

	echo "<tr>";
	echo "<td colspan ='2'>TOTAL</td>";
	echo "<td>" . number_format($total_blancas) . " x 2 = <b>" . number_format($total_blancas * 2) . "</b> </td>";
	echo "<td>" . number_format($total_malos) . " x 2 = <b>" . number_format($total_malos * 2) . "</b></td>";
	echo "<td>" . number_format($total_buenos) . " x 2 = <b>" . number_format($total_buenos * 2) . "</b></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";

	?>

</table>

<?php

	$total_liquidacion = $total_buenos2 + $total_repos + $cant_sobrantes + $total_malos;
	$diferencia = $total_buenos - $total_liquidacion;

	?>



<br id="non-printable">



<table  class="table table-bordered table-sm " style="page-break-inside: avoid; font-size: 10pt">
	<tr style="background-color:#ededed;">
		<th colspan="3" style="text-align: center">LIQUIDACIÓN DE PRODUCCIÓN</th>
	</tr>
  <tr>
    <td width="80%">Billetes Buenos I Etapa</td>
    <td width="10%" align="center"></td>
    <td width="10%" align="center">
    	<input class="form-control" style="text-align: center" type="hidden"  id="buenos_1" name="buenos_1" value="<?php echo $total_buenos1; ?>" readonly>
    	<?php echo number_format($total_buenos1); ?>
    	</td>
  </tr>
  <tr>
    <td>Billetes Emitidos Terminados</td>
    <td align="center" ><?php echo number_format($total_buenos2); ?></td>
    <td align="center"></td>
  </tr>
  <tr>
    <td>Billetes Para Reposiciones Segun Dato Reporte</td>
    <td align="center"><?php echo number_format($tt_para_rep * 2); ?></td>
    <td align="center"></td>
  </tr>
  <tr>
    <td>Billetes Primera Etapa Pruebas no Utilizadas Trituradas S/N y S/R</td>
    <td align="center">
    	<?php
if ($flag_sobrante_registrado == 1) {

		echo $cant_sobrantes;

	} else {

		?>
    <div class="input-group">
    	<input class="form-control" style="text-align: center" onchange = "calcular_sobrantes_faltantes(this.value)" type="number" min="0" id="cantidad_sobrantes" name="cantidad_sobrantes" value="<?php echo $cant_sobrantes; ?>">
    	<div class="input-group-append">  <button name="guardar" type="submit" value="<?php echo $id_sorteo; ?>" class=" btn btn-success  fa fa-save"></button></div>
    </div>

    		<?php
}
	?>
    </td>
    <td align="center"></td>
  </tr>
  <tr>
    <td>Billetes Malos Segunda Etapa Triturados Terminados </td>
    <td align="center"><?php echo number_format($total_malos * 2); ?></td>
    <td align="center"></td>
  </tr>
  <tr>
    <td>Total Liquidacion </td>
    <td align="center"></td>
    <td align="center">
    	<?php
$total_liquidacion = $total_buenos2 + $tt_para_rep + $cant_sobrantes + $total_malos * 2;

	if ($flag_sobrante_registrado == 1) {

		echo number_format($total_liquidacion);

	} else {
		?>
    	<input class="form-control" style="text-align: center" type="hidden"  id="total_liquidacion_o" name="total_liquidacion_o" value="<?php echo $total_liquidacion; ?>" readonly>

    	<input class="form-control" style="text-align: center" type="text"  id="total_liquidacion" name="total_liquidacion" value="<?php echo number_format($total_liquidacion); ?>" readonly>
<?php

	}

	?>

    	</td>
  </tr>
  <tr>
    <td>Diferencia Faltante o Sobrante </td>
    <td align="center"></td>
    <td align="center">
    	<?php
$faltante_sorante = $total_buenos1 - $total_liquidacion;

	if ($flag_sobrante_registrado == 1) {

		echo number_format($faltante_sorante);

	} else {
		?>
    	<input class="form-control" style="text-align: center" type="text"  id="faltante_sorante" name="faltante_sorante" value="<?php echo number_format($faltante_sorante); ?>" readonly>
<?php

	}

	?>
	</td>
  </tr>

</table>






<?php

}

?>

</form>



<?php
if (isset($_POST["guardar"])) {

	$id_orden = $_POST["guardar"];
	$cantidad_sobrantes = $_POST['cantidad_sobrantes'];
	$registro = mysqli_query($conn, "INSERT INTO pro_control_sobrantes_primera_etapa (id_orden, cantidad) values ('$id_orden','$cantidad_sobrantes')  ");

	if ($registro === TRUE) {
		echo "<div class = 'alert alert-info'>Registro guardado correctamente, por favor proceda a generar el reporte nuevamente.</div>";
	} else {
		echo "<div class = 'alert alert-info'>Error inesperado, por favor informe del error a la unidad de informatica.</div>";
	}

}
?>