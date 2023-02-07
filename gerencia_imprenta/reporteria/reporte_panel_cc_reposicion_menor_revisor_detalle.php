<?php
require '../../template/header.php';

$parametros = $_GET['par'];
$vector = explode("_", $parametros);

$id_sorteo = $vector[0];
$rev = $vector[1];
$num_rev = $vector[2];
$num_lista = $vector[3];
$orden = $vector[4];

$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE id = '$id_sorteo' ");
$ob_sorteo = mysqli_fetch_object($info_sorteo);
$no_sorteo = $ob_sorteo->no_sorteo_men;
$fecha_sorteo = $ob_sorteo->fecha_sorteo;
$cantidad_billetes = $ob_sorteo->series;
$registro_inicial = $ob_sorteo->desde_registro;
$fecha_vencimiento = $ob_sorteo->vencimiento_sorteo;

$fecha_actual = date('d-m-Y');

$masc = strlen($cantidad_billetes);
$masc_rec = strlen($registro_inicial);

$info_revisor = mysqli_query($conn, "SELECT a.nombre_completo, b.numero FROM pani_usuarios as a INNER JOIN cc_revisores_sorteos_menores as b ON a.id = b.id_revisor  WHERE a.id = '$rev' ");

$ob_revisor = mysqli_fetch_object($info_revisor);
$num_revisor = $ob_revisor->numero;
$nombre_revisor = $ob_revisor->nombre_completo;

?>
<section style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >
<b>DEPARTAMENTO DE CONTROL DE CALIDAD PANI</b>
<p>REPOSICIONES LOTERIA MENOR</p>
</h2>
</section>
<br>
<?php

echo "Fecha de emisión: <u>" . $fecha_actual . "</u>";

echo "<p style = ''>Reporte de errores detectados en loteria menor ";
echo " Sorteo No. <u>" . $no_sorteo . "</u> De fecha: <u>" . $fecha_sorteo . "</u> y Vencimiento <u>" . $fecha_vencimiento . "</u>";
echo "<br> Nombre de Revisor. <u>" . $nombre_revisor . "</u><br> Lista No: <u>" . $num_revisor . "</u></p>";

?>
<table  class='table table-bordered' id='detalle_revisor' border = '1' style= 'width:100%'>
  <thead>
    <tr>
      <th style="width:20%">Decimo</th>
      <th style="width:20%">Serie</th>
      <th style="width:20%">Registro</th>
      <th style="width:20%">R. E.</th>
      <th style="width:20%">Cantidad</th>
    </tr>
  </thead>
  <tbody>

<?php

if ($orden == 0) {
	$inventario_rechazado = mysqli_query($conn, " SELECT numero , serie, registro, especial FROM cc_revisores_sorteos_menores_control   WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$rev' AND numero_revision = '$num_rev'  ORDER BY  serie, numero  ASC ");
} else {
	$inventario_rechazado = mysqli_query($conn, " SELECT numero , serie, registro, especial FROM cc_revisores_sorteos_menores_control   WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$rev' AND numero_revision = '$num_rev'  ORDER BY   numero, serie  ASC ");
}

$i = 0;
while ($reg_inventerio_rechazado = mysqli_fetch_array($inventario_rechazado)) {

	$v_concat[$i] = $reg_inventerio_rechazado['numero'] . $reg_inventerio_rechazado['serie'];
	$v_numero[$i] = $reg_inventerio_rechazado['numero'];
	$v_serie[$i] = $reg_inventerio_rechazado['serie'];
	$v_registro[$i] = $reg_inventerio_rechazado['registro'];
	$v_re[$i] = $reg_inventerio_rechazado['especial'];

	$i++;
}

$i = 0;
$j = 0;

while (isset($v_concat[$i])) {

	if (isset($v_concat[$i + 1])) {

		if ($v_concat[$i] + 1 == $v_concat[$i + 1]) {

			if ($v_re[$i] == $v_re[$i + 1]) {

			} else {

				$cantidad = $v_serie[$i] - $v_serie[$j] + 1;
				echo "<tr>";
				echo "<td>" . $v_numero[$i] . "0 - " . $v_numero[$i] . "9</td>";
				echo "<td>" . str_pad((string) $v_serie[$j], 5, "0", STR_PAD_LEFT) . " - " . str_pad((string) $v_serie[$i], 5, "0", STR_PAD_LEFT) . "</td>";
				echo "<td>" . $v_registro[$j] . " - " . $v_registro[$i] . "</td>";
				echo "<td>" . $v_re[$i] . "</td>";
				echo "<td>" . $cantidad . "</td>";
				echo "</tr>";

				$j = $i + 1;

			}

		} else {

			$cantidad = $v_serie[$i] - $v_serie[$j] + 1;
			echo "<tr>";
			echo "<td>" . $v_numero[$i] . "0 - " . $v_numero[$i] . "9</td>";
			echo "<td>" . str_pad((string) $v_serie[$j], 5, "0", STR_PAD_LEFT) . " - " . str_pad((string) $v_serie[$i], 5, "0", STR_PAD_LEFT) . "</td>";
			echo "<td>" . $v_registro[$j] . " - " . $v_registro[$i] . "</td>";
			echo "<td>" . $v_re[$i] . "</td>";
			echo "<td>" . $cantidad . "</td>";
			echo "</tr>";

			$j = $i + 1;

		}

	} else {

		$cantidad = $v_serie[$i] - $v_serie[$j] + 1;
		echo "<tr>";
		echo "<td>" . $v_numero[$i] . "0 - " . $v_numero[$i] . "9</td>";
		echo "<td>" . str_pad((string) $v_serie[$j], 5, "0", STR_PAD_LEFT) . " - " . str_pad((string) $v_serie[$i], 5, "0", STR_PAD_LEFT) . "</td>";
		echo "<td>" . $v_registro[$j] . " - " . $v_registro[$i] . "</td>";
		echo "<td>" . $v_re[$i] . "</td>";
		echo "<td>" . $cantidad . "</td>";
		echo "</tr>";

		$j = $i + 1;

	}

	$i++;
}

?>

</tbody>
</table>


<p >Para reponer se entregan <u><?php echo count($v_serie); ?></u> pliegos <br> </p>
