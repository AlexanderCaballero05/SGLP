<?php
require '../../conexion.php';

$year = $_GET['year'];
$filtro = $_GET['filtro'];
$acumulado_banco = 0;
$acumulado_fvp = 0;
$acumulado_tt = 0;
$utilidad_perdida_f = 0;
$utilidad_perdida_b = 0;
$concat_fecha = "";
$concat_utlididades_fvp = "";
$concat_utlididades_banco = "";
$concat_utlididades_acumuladas = "";
$a = 0;

echo "<table width = '100%' class = 'table table-bordered'>";
echo "<tr class = 'alert alert-info' >";
echo "<th>SORTEO</th>";
echo "<th>FECHA</th>";
echo "<th>NUMERO</th>";
if ($filtro == 1) {
	echo "<th>ENTIDAD</th>";
}

echo "<th>VENTA NETA</th>";
echo "<th>PROVISION DE PAGO</th>";
echo "<th>UTILIDAD</th>";
echo "<th>ACUMULADO</th>";
echo "</tr>";

$consulta_sorteos = mysqli_query($conn, "SELECT DISTINCT(a.id_sorteo) as id_sorteo, b.fecha_sorteo  FROM utilidades_perdidas_sorteos as a INNER JOIN sorteos_menores as b ON a.id_sorteo = b.id WHERE YEAR(b.fecha_sorteo) = '$year' AND a.tipo_loteria = 2 GROUP BY a.id_sorteo ORDER BY a.id_sorteo ASC ");

$bandera = 0;
while ($reg_sorteos = mysqli_fetch_array($consulta_sorteos)) {

	$id_sorteo = $reg_sorteos['id_sorteo'];
	$fecha_sorteo = $reg_sorteos['fecha_sorteo'];
	$utilidad_perdida_sorteo = 0;

	$consulta_num = mysqli_query($conn, "SELECT numero_premiado_menor FROM sorteos_menores_premios WHERE sorteos_menores_id = '$id_sorteo' AND premios_menores_id = '1' ");
	$ob_numero = mysqli_fetch_object($consulta_num);
	$numero_premiado = $ob_numero->numero_premiado_menor;

	if ($bandera == 0) {
		$style = "#f2f2f2";
		$bandera = 1;
	} else {
		$style = "#ffffff";
		$bandera = 0;
	}

	$date_f = strtotime($fecha_sorteo);
	$datef = date('d-M', $date_f);
	$datef = strftime("%d %B", strtotime($datef));

	$v_date = explode(" ", $datef);
	$mes = strtoupper(substr($v_date['1'], 0, 3));

	if ($mes == 'JAN') {
		$mes = 'ENE';
	} elseif ($mes == 'AUG') {
		$mes = 'AGO';
	} elseif ($mes == 'DEC') {
		$mes = 'DIC';
	}

	$datef = $v_date[0] . " " . $mes;

	if ($filtro == 1) {
		echo "<tr style = 'background-color:" . $style . "'>";
		echo "<td rowspan = '3'  align = 'center' >" . $id_sorteo . "</td>";
		echo "<td rowspan = '3'  align = 'center' >" . $datef . "</td>";
		echo "<td rowspan = '3'  align = 'center' >" . $numero_premiado . "</td>";
	} else {
		echo "<tr style = 'background-color:" . $style . "'>";
		echo "<td align = 'center' >" . $id_sorteo . "</td>";
		echo "<td align = 'center' >" . $datef . "</td>";
		echo "<td align = 'center' >" . $numero_premiado . "</td>";
	}

	if ($concat_fecha == '') {
		$concat_fecha = $fecha_sorteo;
	} else {
		$concat_fecha .= "%" . $fecha_sorteo;
	}

	if ($filtro == 1 OR $filtro == 2) {

///////////////////////////////////////////////////////////////////////////////
		////////////////////////// CONSULTA UTILIDADES FVP //////////////////////////
		$consulta_utilidades_banco = mysqli_query($conn, "SELECT a.id_sorteo, a.id_entidad, SUM(a.credito_pani) as credito_pani, SUM(a.provision_pago) as provision_pago, SUM(a.utilidad_perdida) as utilidad_perdida, a.tipo_loteria, a.fecha_registro, c.nombre_empresa, b.fecha_sorteo FROM utilidades_perdidas_sorteos as a INNER JOIN sorteos_menores as b INNER JOIN empresas as c ON a.id_sorteo = b.id AND a.id_entidad = c.id WHERE c.distribuidor = 'NO' AND a.id_sorteo = '$id_sorteo'  AND YEAR(b.fecha_sorteo) = '$year' AND a.tipo_loteria = 2 ");

		if ($consulta_utilidades_banco == FALSE) {
			echo mysqli_error($conn);
		}

		if (mysqli_num_rows($consulta_utilidades_banco) > 0) {

			$ob_consulta_utilidades_banco = mysqli_fetch_object($consulta_utilidades_banco);
			$id_sorteo_p = $ob_consulta_utilidades_banco->id_sorteo;
			$fecha_sorteo_p = $ob_consulta_utilidades_banco->fecha_sorteo;
			$credito_pani_p = $ob_consulta_utilidades_banco->credito_pani;
			$provision_pago_p = $ob_consulta_utilidades_banco->provision_pago;
			$utilidad_perdida_f = $ob_consulta_utilidades_banco->utilidad_perdida;
			$nombre_empresa_p = $ob_consulta_utilidades_banco->nombre_empresa;

			$acumulado_fvp += $utilidad_perdida_f;

			if ($filtro == 1) {
				echo "<td> ASOCIADOS</td>";
			}
			echo "<td>" . number_format($credito_pani_p, "2") . "</td>";
			echo "<td>" . number_format($provision_pago_p, "2") . "</td>";

			$v_utilidad_fvp[$a] = $utilidad_perdida_f;

			if ($utilidad_perdida_f < 0) {
				echo "<td style = 'color:#800000'>" . number_format($utilidad_perdida_f, "2") . "</td>";
			} else {
				echo "<td style = 'color:#006600'>" . number_format($utilidad_perdida_f, "2") . "</td>";
			}

			if ($concat_utlididades_fvp == '') {
				$concat_utlididades_fvp = $acumulado_fvp;
			} else {
				$concat_utlididades_fvp .= "%" . $acumulado_fvp;
			}

			if ($acumulado_fvp < 0) {
				echo "<td style = 'color:#800000'>" . number_format($acumulado_fvp, "2") . "</td>";
			} else {
				echo "<td style = 'color:#006600'>" . number_format($acumulado_fvp, "2") . "</td></tr>";
			}

		}

////////////////////////// CONSULTA UTILIDADES FVP //////////////////////////
		///////////////////////////////////////////////////////////////////////////////

	}

	if ($filtro == 1 OR $filtro == 3) {

		if ($filtro == 1) {
			echo "<tr style = 'background-color:" . $style . "'>";
		}

///////////////////////////////////////////////////////////////////////////////
		////////////////////////// CONSULTA UTILIDADES BANCO //////////////////////////
		$consulta_utilidades_banco = mysqli_query($conn, "SELECT a.id_sorteo, a.id_entidad, SUM(a.credito_pani) as credito_pani, SUM(a.provision_pago) as provision_pago, SUM(a.utilidad_perdida) as utilidad_perdida, a.tipo_loteria, a.fecha_registro, c.nombre_empresa, b.fecha_sorteo FROM utilidades_perdidas_sorteos as a INNER JOIN sorteos_menores as b INNER JOIN empresas as c ON a.id_sorteo = b.id AND a.id_entidad = c.id WHERE c.distribuidor = 'SI' AND a.id_sorteo = '$id_sorteo'  AND YEAR(b.fecha_sorteo) = '$year' AND a.tipo_loteria = 2 ");

		if (mysqli_num_rows($consulta_utilidades_banco) > 0) {

			$ob_consulta_utilidades_banco = mysqli_fetch_object($consulta_utilidades_banco);
			$id_sorteo_p = $ob_consulta_utilidades_banco->id_sorteo;
			$fecha_sorteo_p = $ob_consulta_utilidades_banco->fecha_sorteo;
			$credito_pani_p = $ob_consulta_utilidades_banco->credito_pani;
			$provision_pago_p = $ob_consulta_utilidades_banco->provision_pago;
			$utilidad_perdida_b = $ob_consulta_utilidades_banco->utilidad_perdida;
			$nombre_empresa_p = $ob_consulta_utilidades_banco->nombre_empresa;

			$acumulado_banco += $utilidad_perdida_b;

			$v_utilidad_b[$a] = $utilidad_perdida_b;

			if ($filtro == 1) {
				echo "<td>" . $nombre_empresa_p . "</td>";
			}
			echo "<td>" . number_format($credito_pani_p, "2") . "</td>";
			echo "<td>" . number_format($provision_pago_p, "2") . "</td>";

			if ($utilidad_perdida_b < 0) {
				echo "<td style = 'color:#800000'>" . number_format($utilidad_perdida_b, "2") . "</td>";
			} else {
				echo "<td style = 'color:#006600'>" . number_format($utilidad_perdida_b, "2") . "</td>";
			}

			if ($concat_utlididades_banco == '') {
				$concat_utlididades_banco = $acumulado_banco;
			} else {
				$concat_utlididades_banco .= "%" . $acumulado_banco;
			}

			if ($acumulado_banco < 0) {
				echo "<td style = 'color:#800000'>" . number_format($acumulado_banco, "2") . "</td>";
			} else {
				echo "<td style = 'color:#006600'>" . number_format($acumulado_banco, "2") . "</td></tr>";
			}

		}

////////////////////////// CONSULTA UTILIDADES BANCO //////////////////////////
		///////////////////////////////////////////////////////////////////////////////

	}

	$utilidad_perdida_sorteo = $utilidad_perdida_f + $utilidad_perdida_b;
	$acumulado_tt = $acumulado_fvp + $acumulado_banco;

	if ($filtro == 1) {

		if ($utilidad_perdida_sorteo < 0) {
			echo "<tr style = 'background-color:" . $style . "'>";
			echo "<td colspan = '3'><b>PERDIDA DEL SORTEO<b></td>";
			echo "<td style = 'color:#800000' ><b>" . number_format($utilidad_perdida_sorteo, "2") . "<b></td>";
		} else {
			echo "<tr style = 'background-color:" . $style . "'>";
			echo "<td colspan = '3'><b>UTILIDAD DEL SORTEO<b></td>";
			echo "<td style = 'color:#006600' ><b>" . number_format($utilidad_perdida_sorteo, "2") . "<b></td>";
		}

		if ($acumulado_tt < 0) {
			echo "<td style = 'color:#800000' ><b>" . number_format($acumulado_tt, "2") . "<b></td>";
		} else {
			echo "<td style = 'color:#006600' ><b>" . number_format($acumulado_tt, "2") . "<b></td></tr>";
		}

		if ($concat_utlididades_acumuladas == '') {
			$concat_utlididades_acumuladas = $acumulado_tt;
		} else {
			$concat_utlididades_acumuladas .= "%" . $acumulado_tt;
		}

	}

	$conteo_sorteos = $a;
	$v_sorteos[$a] = $id_sorteo;
	$a++;
}

$conteo_sorteos++;
$a = 0;

echo "<table width = '100%'>";

if ($filtro == 1) {
	echo "<tr><td width = '49%'>";

	if (isset($v_utilidad_fvp[0])) {

		$media = array_sum($v_utilidad_fvp) / $conteo_sorteos;
		$max = max($v_utilidad_fvp);
		$min = min($v_utilidad_fvp);
		$s_max = array_search($max, $v_utilidad_fvp);
		$s_min = array_search($min, $v_utilidad_fvp);

		echo "<table class = 'table table-bordered' width = '20%'>";
		echo "<tr class = 'alert alert-success'><td colspan = '2' align = 'center'>ANALISIS DE UTILIDADES ASOC.</td></tr>";
		echo "<tr><td>MEDIA</td><td>" . number_format($media, '2') . "</td><tr>";
		echo "<tr><td>MAXIMO (SORTEO " . $v_sorteos[$s_max] . ")</td><td>" . number_format($max, '2') . "</td><tr>";
		echo "<tr><td>MINIMO (SORTEO " . $v_sorteos[$s_min] . ")</td><td>" . number_format($min, '2') . "</td><tr>";
		echo "<tr><td>SORTEOS JUGADOS</td><td>" . number_format($conteo_sorteos) . "</td><tr>";

		echo "</table>";

	}

	echo "</td>
<td width = '2%'></td>
<td width = '49%'>";

	if (isset($v_utilidad_b[0])) {

		$media = array_sum($v_utilidad_b) / $conteo_sorteos;
		$max = max($v_utilidad_b);
		$min = min($v_utilidad_b);
		$s_max = array_search($max, $v_utilidad_b);
		$s_min = array_search($min, $v_utilidad_b);

		echo "<table class = 'table table-bordered' width = '20%'>";
		echo "<tr class = 'alert alert-success'><td colspan = '2' align = 'center'>ANALISIS DE UTILIDADES BANCO DISTRIBUIDOR</td></tr>";
		echo "<tr><td>MEDIA</td><td>" . number_format($media, '2') . "</td><tr>";
		echo "<tr><td>MAXIMO (SORTEO " . $v_sorteos[$s_max] . ")</td><td>" . number_format($max, '2') . "</td><tr>";
		echo "<tr><td>MINIMO (SORTEO " . $v_sorteos[$s_min] . ")</td><td>" . number_format($min, '2') . "</td><tr>";
		echo "<tr><td>SORTEOS JUGADOS</td><td>" . number_format($conteo_sorteos) . "</td><tr>";

		echo "</table>";

	}

	echo "</td></tr>";
	echo "</table>";

} else {

	echo "<tr>";
	echo "<td width = '26%'></td>";
	echo "<td width = '48%'>";

	if (isset($v_utilidad_fvp[0])) {

		$media = array_sum($v_utilidad_fvp) / $conteo_sorteos;
		$max = max($v_utilidad_fvp);
		$min = $v_utilidad_fvp[0];
		$s_max = array_search($max, $v_utilidad_fvp);
		$s_min = array_search($min, $v_utilidad_fvp);

		$h = 0;
		while (isset($v_utilidad_fvp[$h])) {
			if ($min > $v_utilidad_fvp[$h]) {
				$min = $v_utilidad_fvp[$h];
			}

			$h++;
		}
		$s_min = array_search($min, $v_utilidad_fvp);

		echo "<table class = 'table table-bordered' width = '20%'>";
		echo "<tr class = 'alert alert-success'><td colspan = '2' align = 'center'>ANALISIS DE UTILIDADES  ASOC.</td></tr>";
		echo "<tr><td>MEDIA</td><td>" . number_format($media, '2') . "</td><tr>";
		echo "<tr><td>MAXIMA UTILIDAD (SORTEO " . $v_sorteos[$s_max] . ")</td><td>" . number_format($max, '2') . "</td><tr>";
		echo "<tr><td>MAXIMA PERDIDA (SORTEO " . $v_sorteos[$s_min] . ")</td><td>" . number_format($min, '2') . "</td><tr>";
		echo "<tr><td>SORTEOS JUGADOS</td><td>" . number_format($conteo_sorteos) . "</td><tr>";
		echo "</table>";

	}

	if (isset($v_utilidad_b[0])) {

		$media = array_sum($v_utilidad_b) / $conteo_sorteos;
		$max = max($v_utilidad_b);
		$min = $v_utilidad_b[0];
		$s_max = array_search($max, $v_utilidad_b);
		$s_min = array_search($min, $v_utilidad_b);

		$h = 0;
		while (isset($v_utilidad_b[$h])) {
			if ($min > $v_utilidad_b[$h]) {
				$min = $v_utilidad_b[$h];
			}

			$h++;
		}
		$s_min = array_search($min, $v_utilidad_b);

		echo "<table class = 'table table-bordered' width = '20%'>";
		echo "<tr class = 'alert alert-success'><td colspan = '2' align = 'center'>ANALISIS DE UTILIDADES BANCO DISTRIBUIDOR</td></tr>";
		echo "<tr><td>MEDIA</td><td>" . number_format($media, '2') . "</td><tr>";
		echo "<tr><td>MAXIMA UTILIDAD (SORTEO " . $v_sorteos[$s_max] . ")</td><td>" . number_format($max, '2') . "</td><tr>";
		echo "<tr><td>MINIMA PERDIDA (SORTEO " . $v_sorteos[$s_min] . ")</td><td>" . number_format($min, '2') . "</td><tr>";
		echo "<tr><td>SORTEOS JUGADOS</td><td>" . number_format($conteo_sorteos) . "</td><tr>";
		echo "</table>";

	}
	echo "</td>";
	echo "<td width = '26%'></td>";
	echo "</tr>";

}

echo "</table>";

?>


<table width="100%">
<tr>
<td width="50%">

<span style = 'width:100%' class = 'btn btn-info' onclick = "generar('<?php echo $concat_fecha ?>','<?php echo $concat_utlididades_fvp ?>','<?php echo $concat_utlididades_banco ?>','<?php echo $filtro ?>','<?php echo $concat_utlididades_acumuladas ?>')" id = 'non-printable'>
GENERAR GRAFICO DE UTILIDADES
</span>

<div class="well" style=" width:100%;heigth:50px">
<canvas style = 'display:none' id="myChart" width="600" height="250"></canvas>
</div>

</td>

</tr>
</table>

<br>
<form method="POST">
<div class="row">
	<div class="col" >
		<button style="width: 100%" type="submit" name = 'generar_excel' value = '<?php echo $year; ?>' class="btn btn-success">GENERAR EXCEL</button>
	</div>
</div>
</form>
<br>

<script>
$(".div_wait").fadeOut("fast");
</script>

