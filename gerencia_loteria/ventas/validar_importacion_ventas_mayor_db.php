<?php

$fileTempName = $_FILES['importacion']['tmp_name'];
$nombre_archivo = $_FILES["importacion"]["name"];

if ($fileTempName != '') {

	$sorteo_seleccion = $_POST['sorteo'];
	$id_empresa_seleccionada = $_POST['id_nueva_empresa'];
	$id_agencia_seleccionada = $_POST['id_nueva_seccional'];

	$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE id = '$sorteo_seleccion' ");
	$ob_sorteo = mysqli_fetch_object($info_sorteo);
	$mezcla = $ob_sorteo->mezcla;
	$precio_unitario = $ob_sorteo->precio_unitario;
	$precio_unitario = $precio_unitario * 10;

	$info_empresa = mysqli_query($conn, "SELECT * FROM empresas WHERE id = '$id_empresa_seleccionada' ");
	$ob_empresa = mysqli_fetch_object($info_empresa);
	$nombre_empresa = $ob_empresa->nombre_empresa;

	echo "<input type = 'hidden' name = 'id_sorteo_o' value ='" . $sorteo_seleccion . "' >";
	echo "<input type = 'hidden' name = 'id_empresa_o' value ='" . $id_empresa_seleccionada . "' >";
	echo "<input type = 'hidden' name = 'id_agencia_o' value ='" . $id_agencia_seleccionada . "' >";

	require_once $ruta . 'assets/phpexcel/Classes/PHPExcel/IOFactory.php';
	$objPHPExcel = PHPExcel_IOFactory::load($fileTempName);

	$worksheet = $objPHPExcel->setActiveSheetIndex(0);

	$worksheetTitle = $worksheet->getTitle();
	$highestRow = $worksheet->getHighestRow(); // e.g. 10
	$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
	$nrColumns = ord($highestColumn) - 64;

	$cell1 = $worksheet->getCellByColumnAndRow(1, 1);
	$sorteo = $cell1->getValue();

	if ($sorteo_seleccion == $sorteo) {

		$numeros_asignados = mysqli_query($conn, "SELECT a.rango FROM sorteos_mezclas_rangos as a INNER JOIN sorteos_mezclas as b ON a.num_mezcla = b.num_mezcla WHERE a.id_sorteo = '$sorteo_seleccion' AND b.id_sorteo = '$sorteo_seleccion'  AND b.id_empresa = '$id_empresa_seleccionada' ");

		echo mysqli_error($conn);

		$n = 0;
		$irregularidades = 0;
		while ($reg_numeros_asignados = mysqli_fetch_array($numeros_asignados)) {
			$billete_inicial_asignado = $reg_numeros_asignados['rango'];
			$billete_final_asignado = $reg_numeros_asignados['rango'] + $mezcla - 1;

			while ($billete_inicial_asignado <= $billete_final_asignado) {
				$v_b[$n] = $billete_inicial_asignado;
				$billete_inicial_asignado++;
				$n++;
			}

		}

		$billetes_vendidos = mysqli_query($conn, " SELECT billete FROM fvp_detalles_ventas_mayor WHERE id_sorteo = '$sorteo_seleccion' AND estado_venta = 'APROBADO' ");
		$i = 0;

		while ($detalle_vendidos = mysqli_fetch_array($billetes_vendidos)) {
			$v_v[$i] = $detalle_vendidos['billete'];
			$i++;
		}

		if (isset($v_b)) {

			echo "<br>";
			echo "<br>";

			echo "<div  class = 'card' style = 'margin-left: 10px; margin-right: 10px;' >";
			echo "<div  class = 'card-header bg-secondary text-white' align = 'center'>";

			echo "<h3 style = 'align:center'>";
			echo $nombre_empresa;
			echo "<hr>";
			echo "SORTEO " . $sorteo_seleccion;
			echo "</h3>";

			echo "</div>";

			echo "<div class = 'card-body' >";
			echo "<table  class = ' table table-bordered'>";
			echo "<tr>
	  <th>#</th>
	  <th>Billete Inicial</th>
	  <th>Billete Final</th>
	  <th>Cantidad</th>
	  <th>Estado</th>
	  </tr>";

			$i = 1;
			$j = 0;

			$matriz_import[0][0] = $sorteo_seleccion;
			$matriz_import[0][1] = $id_empresa_seleccionada;
			$matriz_import[0][2] = $nombre_empresa;

			$cantidad_total = 0;
			$acumulador = 1;
			$matriz_export_detalle[0][0] = $sorteo_seleccion;
			$matriz_export_detalle[0][1] = $id_empresa_seleccionada;
			$matriz_export_detalle[0][2] = $nombre_empresa;

			for ($row = 4; $row <= $highestRow; ++$row) {

				$cell1 = $worksheet->getCellByColumnAndRow(0, $row);
				$cell2 = $worksheet->getCellByColumnAndRow(1, $row);

				$billete_inicial = $cell1->getValue();
				$billete_inicial_validacion = $billete_inicial;
				$billete_final = $cell2->getValue();
				$billete_final_validacion = $billete_final;
				$cantidad = $billete_final - $billete_inicial + 1;
				$bandera = 0;

				if ($cantidad >= 0) {

					while ($billete_inicial_validacion <= $billete_final_validacion) {

						if (!in_array($billete_inicial_validacion, $v_b)) {
							$bandera = 1;
							$msg = 'Billete ' . $billete_inicial_validacion . " No asignado";
							$billete_inicial_validacion = $billete_final_validacion;

						} elseif (isset($v_v)) {

							if (in_array($billete_inicial_validacion, $v_v)) {
								$bandera = 1;
								$msg = 'Billete ' . $billete_inicial_validacion . " Ya vendido";
								$billete_inicial_validacion = $billete_final_validacion;

							} elseif (isset($v_por_importar)) {
								if (in_array($billete_inicial_validacion, $v_por_importar)) {
									$bandera = 1;
									$msg = 'Billete ' . $billete_inicial_validacion . " Repetido";
									$billete_inicial_validacion = $billete_final_validacion;
								}

							}

						} elseif (isset($v_por_importar)) {

							if (in_array($billete_inicial_validacion, $v_por_importar)) {
								$bandera = 1;
								$msg = 'Billete ' . $billete_inicial_validacion . " Repetido";
								$billete_inicial_validacion = $billete_final_validacion;
							}

						}

						$v_por_importar[$j] = $billete_inicial_validacion;
						$j++;

						$billete_inicial_validacion++;
					}

				} else {

					$bandera = 1;
					$msg = "Rango Incorrecto";
					$billete_inicial_validacion = $billete_final_validacion;

				}

				if ($bandera == 0) {

					$matriz_import[$i][0] = $billete_inicial;
					$matriz_import[$i][1] = $billete_final;
					$matriz_import[$i][2] = $cantidad;
					$cantidad_total = $cantidad_total + $cantidad;
					$i++;

					echo "<tr style = 'background-color:#ccffcc'>";
					echo "<td>" . $row . "</td>";
					echo "<td>" . $billete_inicial . "</td>";
					echo "<td>" . $billete_final . "</td><td>" . $cantidad . "</td>";
					echo "<td><font color = 'green'>OK</font></td>";
					echo "</tr>";

					$msg = "OK";

				} else {

					echo "<tr style = 'background-color:#ffcccc'>";
					echo "<td>" . $row . "</td>";
					echo "<td>" . $billete_inicial . "</td>";
					echo "<td>" . $billete_final . "</td><td>" . $cantidad . "</td>";
					echo "<td><font color = 'red'>" . $msg . "</font></td>";
					echo "</tr>";
					$irregularidades++;
				}

				$matriz_export_detalle[$acumulador][0] = $row;
				$matriz_export_detalle[$acumulador][1] = $billete_inicial;
				$matriz_export_detalle[$acumulador][2] = $billete_final;
				$matriz_export_detalle[$acumulador][3] = $cantidad;
				$matriz_export_detalle[$acumulador][4] = $msg;

				$acumulador++;
			}

			echo "</table>";
			echo "</div>";

			if ($irregularidades == 0) {

/////////////////////////////////////////////////////////////////
				///////////// BUSQUEDA DE PARAMETROS DE VENTA ///////////////////
				$parametros_venta = mysqli_query($conn, "SELECT * FROM empresas WHERE id = '$id_empresa_seleccionada' ");
				$ob_paramatros_venta = mysqli_fetch_object($parametros_venta);
				$descuento = $ob_paramatros_venta->descuento_mayor;
				$tipo_descuento = $ob_paramatros_venta->tipo_descuento_mayor;
				$comision = $ob_paramatros_venta->rebaja_mayor;
				$tipo_comision = $ob_paramatros_venta->tipo_rebaja_mayor;

				if ($tipo_descuento == 1) {
					$monto_descuento = $descuento;
				} else {
					$desc = $descuento / 100;
					$monto_descuento = $precio_unitario * $desc;
				}

				if ($tipo_comision == 1) {
					$monto_comision = $comision;
				} else {
					$com = $comision / 100;
					$monto_comision = $precio_unitario * $com;
				}

				$total_bruto = $cantidad_total * $precio_unitario;
				$total_descuento = $cantidad_total * $monto_descuento;
				$total_neto = $total_bruto - $total_descuento;
				$total_comision = $cantidad_total * $monto_comision;
				$total_credito_pani = $total_neto - $total_comision;

				echo "<div class = 'card-footer'>";

				echo "<table class = 'table table-bordered' >";
				echo "<tr>";
				echo "<th>Total Billetes</th>";
				echo "<th>Precio Unitario</th>";
				echo "<th>Total Bruto</th>";
				echo "<th>Total Descuento</th>";
				echo "<th>Total Neto</th>";
				echo "</tr>";
				echo "<tr>";
				echo "<td><input type = 'text' name = 'total_cantidad'  class = 'form-control' value = '" . $cantidad_total . "'  readonly></td>";
				echo "<td><input type = 'text' name = 'precio_unitario' class = 'form-control' value = '" . $precio_unitario . "' readonly></td>";
				echo "<td><input type = 'text' name = 'total_bruto'     class = 'form-control' value = '" . $total_bruto . "'     readonly></td>";
				echo "<td><input type = 'text' name = 'total_descuento' class = 'form-control' value = '" . $total_descuento . "' readonly></td>";
				echo "<td><input type = 'text' name = 'total_neto' class = 'form-control' value = '" . $total_neto . "'      readonly></td>";
				echo "</tr>";
				echo "</table>";

				echo "<input type = 'hidden' class = 'form-control' name = 'total_comision' value = '" . $total_comision . "' >";
				echo "<input type = 'hidden' class = 'form-control' name = 'total_credito_pani' value = '" . $total_credito_pani . "' >";

///////////// FIN BUSQUEDA DE PARAMETROS DE VENTA ///////////////////
				/////////////////////////////////////////////////////////////////////

				$dataString = serialize($matriz_import);

				echo "
<div class = 'alert alert-info' align = 'center'>
No. de irregularidades: " . $irregularidades . "<br>
<br>";

				echo "<button type = 'submit' value = '" . $dataString . "' class = 'btn btn-primary' id = 'generar_trituracion' name = 'generar_trituracion'>
GENERAR ACTA DE TRITURACION PRELIMINAR
</button>

<div>";

			} else {

				echo "
<div class = 'card-footer alert-danger' align = 'center'>
No. de irregularidades: " . $irregularidades . "<br><br>";

				$dataString = serialize($matriz_export_detalle);

				echo "El archivo que intenta importar contiene irregularidades, por favor notifique dichas irregularidades a la entidad correspondiente. <br><br>";

				echo "<button type = 'submit' value = '" . $dataString . "' class = 'btn btn-danger' id = 'generar_irregularidades' name = 'generar_irregularidades'>
GENERAR REPORTE DE IRREGULARIDADES
</button>";

				echo "</div>";

				echo "</div>";
				echo "<br><br><br>";

			}

		} else {
			echo '
<div class = "alert alert-danger">
<b>La entidad seleccionada no tiene asignacion de loteria, verifique que haya realizado la asignacion de loteria a nivel de Entidad.<b></div>';
		}

	} else {
		echo '<div class = "alert alert-danger">El sorteo seleccionado y el sorteo a importar no coinciden.</div>';
	}

} else {
	echo '<div class = "alert alert-danger">Debe seleccionar un archivo a validar.</div>';
}

?>


<?php

?>
