<?php

require "../../template/header.php";
date_default_timezone_set('America/Tegucigalpa');

?>


<form method="POST">


	<section style="background-color:#ededed;">
		<br>
		<h3 align="center"><b>LISTADO DE VENDEDORES POR SECCIONAL</b></h3>
		<br>
	</section>



	<a id='non-printable' style="width:100%" class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">Seleccion de Parametros</a>


	<div class="card collapse" id="collapse1" style="margin-left: 250px; margin-right: 250px;">
		<div class="card-body">


			<div class="row">
				<div class="col">


					<div class="input-group">
						<div class="input-group-prepend">
							<div class="input-group-text">Sorteo:</div>
						</div>

						<select class="form-control" name="s_sorteo" id='s_sorteo' ">
<?php

$c_sorteos = mysqli_query($conn, "SELECT id, fecha_sorteo FROM sorteos_menores  ORDER BY id DESC");
echo '<option value = "NINGUNO"> NINGUNO</option>';

while ($r_sorteos = mysqli_fetch_array($c_sorteos)) {
	echo '<option value = "' . $r_sorteos['id'] . '"> Sorteo ' . $r_sorteos['id'] . ' | ' . $r_sorteos['fecha_sorteo'] . '</option>';
}

?>
</select>
</div>

</div>

<div class=" col">

							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text">Seccional:</div>
								</div>

								<select class="form-control" name="s_seccional" id='s_seccional' ">
<?php

$seccionales = mysqli_query($conn, "SELECT *  FROM asociaciones_seccionales  ORDER BY CONCAT(codigo_asociacion, codigo_seccional) ASC ");

echo '<option value = "todas">TODAS</option>';

while ($row2 = mysqli_fetch_array($seccionales)) {

	if ($row2['codigo_asociacion'] == "A") {
		$desc_asociacion = "ANAVELH";
	} elseif ($row2['codigo_asociacion'] == 'B') {
		$desc_asociacion = "ANVLUH";
	} else {
		$desc_asociacion = "SIN ASOCIACION";
	}

	echo '<option value = "' . $row2['codigo_asociacion'] . "-" . $row2['codigo_seccional'] . "-" . $row2['zona'] . '">' . $desc_asociacion . ' - ' . $row2['codigo_seccional'] . ' - ' . $row2['zona'] . '</option>';
}
?>
</select>


<div class=" input-group-append">
									<button type="submit" name="seleccionar" id="seleccionar" class="btn btn-primary">Seleccionar</button>
							</div>
					</div>

				</div>
			</div>

		</div>
	</div>



	<?php

	if (isset($_POST['seleccionar'])) {

		if ($_POST['s_sorteo'] == "NINGUNO") {

			if ($_POST['s_seccional'] == "todas") {

				$vendedores = mysqli_query($conn, "SELECT * FROM vendedores  ORDER BY  asociacion, seccional, codigo ");

				echo "<br>";
				echo "<br>";
				echo "<div class = 'card'>";
				echo "<div class = 'card-header bg-dark text-white'>";

				echo "<h4>TODAS LAS ASOCIACIONES Y SECCIONALES </h4>";

				echo "</div>";

				echo "<div class = 'card-body'>";

				echo "<table class = 'table table-bordered'>";
				echo "<tr>";
				echo "<th>N0.</th>";
				echo "<th>NOMBRE COMPLETO</th>";
				echo "<th>IDENTIDAD</th>";
				echo "<th>NO. DE CARNET</th>";
				echo "<th>BOLSAS ASIGNADAS</th>";
				echo "<th>ZONA</th>";
				echo "<th>TELEFONO</th>";
				echo "<th>GENERO</th>";
				echo "</tr>";

				$i = 1;
				$tt_bolsas = 0;
				while ($reg_vendedores = mysqli_fetch_array($vendedores)) {
					echo "<tr>";
					echo "<td>" . $i . "</td>";
					echo "<td>" . $reg_vendedores['nombre'] . "</td>";
					echo "<td>" . $reg_vendedores['identidad'] . "</td>";
					echo "<td>" . $reg_vendedores['asociacion'] . "-" . $reg_vendedores['seccional'] . "-" . $reg_vendedores['codigo'] . "</td>";
					echo "<td>" . $reg_vendedores['numero_bolsas'] . "</td>";
					echo "<td>" . $reg_vendedores['zona_venta'] . "</td>";
					echo "<td>" . $reg_vendedores['telefono'] . "</td>";
					echo "<td>" . $reg_vendedores['sexo'] . "</td>";
					echo "</tr>";
					$i++;
					$tt_bolsas += $reg_vendedores['numero_bolsas'];
				}

				echo "<tr>";
				echo "<th colspan = '4' style = 'text-align: center'>TOTAL BOLSAS</th>";
				echo "<th>" . number_format($tt_bolsas) . "</th>";
				echo "<td colspan = '3'></td>";
				echo "</tr>";

				echo "</table>";

				echo "</div>";

				echo "<div class = 'card-footer' id = 'non-printable' align = 'center'>";
				echo "<input type = 'hidden' name = 'sorteo_oculto' value = '" . $_POST['s_sorteo'] . "' >";
				echo "<input type = 'hidden' name = 'seccional_oculto' value = '" . $_POST['s_seccional'] . "' >";

				echo "<button class = 'btn btn-success' value = 'todas' name = 'excel' type = 'submit'>GENERAR EXCEL</button>";

				echo "</div>";

				echo "</div>";
			} else {

				$v_parametros = explode("-", $_POST['s_seccional']);
				$cod_asociacion = $v_parametros[0];
				$cod_seccional = $v_parametros[1];
				$zona = $v_parametros[2];

				if ($cod_asociacion == "A") {
					$desc_asociacion = "ANAVELH";
				} elseif ($cod_asociacion == "B") {
					$desc_asociacion = "ANVLUH";
				} else {
					$desc_asociacion = "SIN ASOCIACION";
				}

				$vendedores = mysqli_query($conn, "SELECT * FROM vendedores WHERE asociacion = '$cod_asociacion' AND seccional = '$cod_seccional' ORDER BY codigo ");

				echo "<br>";
				echo "<br>";
				echo "<div class = 'card'>";
				echo "<div class = 'card-header bg-dark text-white'>";

				echo "<h4>ASOCIACION: " . $desc_asociacion . " </h4>";
				echo "<h4>SECCIONAL: " . $cod_seccional . " </h4>";
				echo "<h4>ZONA: " . strtoupper($zona) . " </h4>";

				echo "</div>";

				echo "<div class = 'card-body'>";

				echo "<table class = 'table table-bordered'>";
				echo "<tr>";
				echo "<th>N0.</th>";
				echo "<th>NOMBRE COMPLETO</th>";
				echo "<th>IDENTIDAD</th>";
				echo "<th>NO. DE CARNET</th>";
				echo "<th>BOLSAS ASIGNADAS</th>";
				echo "<th>TELEFONO</th>";
				echo "<th>GENERO</th>";
				echo "</tr>";

				$i = 1;
				$tt_bolsas = 0;
				while ($reg_vendedores = mysqli_fetch_array($vendedores)) {
					echo "<tr>";
					echo "<td>" . $i . "</td>";
					echo "<td>" . $reg_vendedores['nombre'] . "</td>";
					echo "<td>" . $reg_vendedores['identidad'] . "</td>";
					echo "<td>" . $reg_vendedores['asociacion'] . "-" . $reg_vendedores['seccional'] . "-" . $reg_vendedores['codigo'] . "</td>";
					echo "<td>" . $reg_vendedores['numero_bolsas'] . "</td>";
					echo "<td>" . $reg_vendedores['telefono'] . "</td>";
					echo "<td>" . $reg_vendedores['sexo'] . "</td>";
					echo "</tr>";
					$i++;
					$tt_bolsas += $reg_vendedores['numero_bolsas'];
				}

				echo "<tr>";
				echo "<th colspan = '4' style = 'text-align: center'>TOTAL BOLSAS</th>";
				echo "<th>" . number_format($tt_bolsas) . "</th>";
				echo "<td colspan = '2'></td>";
				echo "</tr>";

				echo "</table>";

				echo "</div>";

				echo "<div class = 'card-footer' id = 'non-printable' align = 'center'>";

				echo "<input type = 'hidden' name = 'sorteo_oculto' value = '" . $_POST['s_sorteo'] . "' >";
				echo "<input type = 'hidden' name = 'seccional_oculto' value = '" . $_POST['s_seccional'] . "' >";

				echo "<button class = 'btn btn-success' value = '" . $cod_asociacion . "-" . $cod_seccional . "-" . $zona . "' name = 'excel' type = 'submit'>GENERAR EXCEL</button>";

				echo "</div>";

				echo "</div>";
			}
		} else {

			$id_sorteo = $_POST['s_sorteo'];
			$c_ventas_sorteo = mysqli_query($conn, "SELECT identidad_comprador, nombre_comprador, SUM(cantidad) as cantidad, MAX(fecha_venta) as ultima_compra FROM transaccional_ventas_general WHERE id_sorteo = '$id_sorteo' AND estado_venta = 'APROBADO' AND cod_producto = 3  GROUP BY identidad_comprador  ");

			$v = 0;
			while ($reg_compradores = mysqli_fetch_array($c_ventas_sorteo)) {
				$v_compradores[$v] = ['identidad_comprador' => $reg_compradores['identidad_comprador'], 'nombre_comprador' => $reg_compradores['nombre_comprador'], 'cantidad_compra' => $reg_compradores['cantidad'],  'fecha_compra' => $reg_compradores['ultima_compra']];
				$v++;
			}


			if ($_POST['s_seccional'] == "todas") {

				$c_vendedores = mysqli_query($conn, "SELECT * FROM vendedores  ORDER BY  asociacion, seccional, codigo ");
			} else {

				$v_parametros = explode("-", $_POST['s_seccional']);
				$cod_asociacion = $v_parametros[0];
				$cod_seccional = $v_parametros[1];
				$zona = $v_parametros[2];

				if ($cod_asociacion == "A") {
					$desc_asociacion = "ANAVELH";
				} elseif ($cod_asociacion == "B") {
					$desc_asociacion = "ANVLUH";
				} else {
					$desc_asociacion = "SIN ASOCIACION";
				}

				$c_vendedores = mysqli_query($conn, "SELECT * FROM vendedores WHERE asociacion = '$cod_asociacion' AND seccional = '$cod_seccional' ORDER BY codigo ");
			}

			while ($reg_vendedores = mysqli_fetch_array($c_vendedores)) {
				$v_con_carnet[$reg_vendedores['identidad']] = ['nombre_comprador' => $reg_vendedores['nombre'], 'nombre_comprador' => $reg_vendedores['nombre'], 'asociacion' => $reg_vendedores['asociacion'], 'seccional' => $reg_vendedores['seccional'], 'codigo' => $reg_vendedores['codigo'], 'numero_bolsas' => $reg_vendedores['numero_bolsas'], 'telefono' => $reg_vendedores['telefono'], 'geocodigo' => $reg_vendedores['geocodigo'], 'zona_venta' => $reg_vendedores['zona_venta'], 'direccion' => $reg_vendedores['direccion']];
			}


			echo "<br>";
			echo "<br>";
			echo "<div class = 'card'>";
			echo "<div class = 'card-header bg-dark text-white'>";

			echo "<h4>SORTEO: " . $id_sorteo . " </h4>";

			if ($_POST['s_seccional'] != "todas") {
				echo "<h4>ASOCIACION: " . $desc_asociacion . " </h4>";
				echo "<h4>SECCIONAL: " . $cod_seccional . " </h4>";
				echo "<h4>ZONA: " . strtoupper($zona) . " </h4>";
			} else {
				echo "<h4>TODAS LAS ASOCIACIONES Y SECCIONALES </h4>";
			}

			echo "</div>";

			echo "<div class = 'card-body'>";

			echo "<table class = 'table table-bordered'>";
			echo "<tr>";
			echo "<th>N0.</th>";
			echo "<th>NOMBRE COMPLETO</th>";
			echo "<th>IDENTIDAD</th>";
			echo "<th>NO. DE CARNET</th>";
			echo "<th>TELEFONO</th>";
			echo "<th>BOLSAS ASIGNADAS</th>";
			echo "<th>COMPRA EN SORTEO</th>";
			echo "<th>FECHA ULTIMA COMPRA</th>";
			echo "</tr>";

			$i = 1;
			$tt_bolsas = 0;
			$tt_compra = 0;
			$v = 0;
			while (isset($v_compradores[$v])) {

				if ($_POST['s_seccional'] != "todas") {

					if (isset($v_con_carnet[$v_compradores[$v]['identidad_comprador']])) {

						if ($v_con_carnet[$v_compradores[$v]['identidad_comprador']]['asociacion'] == $cod_asociacion and $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['seccional'] == $cod_seccional) {

							echo "<tr>";
							echo "<td>" . $i . "</td>";
							echo "<td>" . $v_compradores[$v]['nombre_comprador'] . "</td>";
							echo "<td>" . $v_compradores[$v]['identidad_comprador'] . "</td>";
							$tt_bolsas += $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['numero_bolsas'];

							echo "<td>" . $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['asociacion'] . "-" . $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['seccional'] . "-" . $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['codigo'] . "</td>";
							echo "<td>" . $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['telefono'] . "</td>";
							echo "<td>" . $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['numero_bolsas'] . "</td>";

							echo "<td>" . $v_compradores[$v]['cantidad_compra'] . "</td>";
							echo "<td>" . $v_compradores[$v]['fecha_compra'] . "</td>";

							echo "</tr>";
							$i++;
							$tt_compra += $v_compradores[$v]['cantidad_compra'];
							$v++;
						}
					} else {
						$v++;
					}
				} else {

					echo "<tr>";
					echo "<td>" . $i . "</td>";
					echo "<td>" . $v_compradores[$v]['nombre_comprador'] . "</td>";
					echo "<td>" . $v_compradores[$v]['identidad_comprador'] . "</td>";
					if (isset($v_con_carnet[$v_compradores[$v]['identidad_comprador']])) {
						$tt_bolsas += $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['numero_bolsas'];

						echo "<td>" . $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['asociacion'] . "-" . $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['seccional'] . "-" . $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['codigo'] . "</td>";
						echo "<td>" . $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['telefono'] . "</td>";
						echo "<td>" . $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['numero_bolsas'] . "</td>";
					} else {
						echo "<td colspan = '3'>NO REGISTRADO COMO VENDEDOR</td>";
					}

					echo "<td>" . $v_compradores[$v]['cantidad_compra'] . "</td>";
					echo "<td>" . $v_compradores[$v]['fecha_compra'] . "</td>";

					echo "</tr>";
					$i++;
					$tt_compra += $v_compradores[$v]['cantidad_compra'];
					$v++;
				}
			}


			echo "</table>";

			echo "<b> TOTAL ASIGNADO A LOTEROS: " . number_format($tt_bolsas) . "</b><br>";
			echo "<b> TOTAL VENDIDO: " . number_format($tt_compra) . "</b>";

			echo "</div>";

			echo "<div class = 'card-footer' id = 'non-printable' align = 'center'>";

			echo "<input type = 'hidden' name = 'sorteo_oculto' value = '" . $_POST['s_sorteo'] . "' >";
			echo "<input type = 'hidden' name = 'seccional_oculto' value = '" . $_POST['s_seccional'] . "' >";

			echo "<button class = 'btn btn-success' value = '' name = 'excel' type = 'submit'>GENERAR EXCEL</button>";

			echo "</div>";

			echo "</div>";
		}
	}

	?>

</form>



















<?php

if (isset($_POST['excel'])) {

	require_once '../../assets/phpexcel/Classes/PHPExcel/IOFactory.php';

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);


	if ($_POST['sorteo_oculto'] == "NINGUNO") {

		if ($_POST['seccional_oculto'] == "todas") {

			$vendedores = mysqli_query($conn, "SELECT * FROM vendedores  ORDER BY  asociacion, seccional, codigo ");

		
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'NO.');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'NOMBRE');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'IDENTIDAD');
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'NO. CARNET');
			$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'BOLSAS ASIGNADAS');
			$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'ZONA');
			$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'TELEFONO');
			$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'GENERO');
			$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'ESTADO');
		
			$row = 2;
			$i = 1;
			$tt_bolsas = 0;
			while ($reg_vendedores = mysqli_fetch_array($vendedores)) {

				if ($reg_vendedores['estado'] == '1') {
					$estado = "ACTIVO"; 
				}else{
					$estado = "INACTIVO"; 
				}
		
				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $i);
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $reg_vendedores['nombre']);
		
				$objPHPExcel->getActiveSheet()->getStyle('C' . $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $reg_vendedores['identidad']);
				$objPHPExcel->getActiveSheet()->getStyle('C' . $row)->getNumberFormat()->setFormatCode("0000000000000");
		
				$objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, $reg_vendedores['asociacion'] . "-" . $reg_vendedores['seccional'] . "-" . $reg_vendedores['codigo']);
				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $row, $reg_vendedores['numero_bolsas']);
				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $row, $reg_vendedores['zona_venta']);
				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $row, $reg_vendedores['telefono']);
				$objPHPExcel->getActiveSheet()->SetCellValue('H' . $row, $reg_vendedores['sexo']);
				$objPHPExcel->getActiveSheet()->SetCellValue('I' . $row, $estado);
		
				$tt_bolsas += $reg_vendedores['numero_bolsas'];
				$row++;
				$i++;
			}
		
			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, 'TOTAL BOLSAS  ');
			$objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':D' . $row);
			$objPHPExcel->getActiveSheet()->SetCellValue('E' . $row, $tt_bolsas);

		} else {

			$v_parametros = explode("-", $_POST['seccional_oculto']);
			$cod_asociacion = $v_parametros[0];
			$cod_seccional = $v_parametros[1];
			$zona = $v_parametros[2];

			if ($cod_asociacion == "A") {
				$desc_asociacion = "ANAVELH";
			} elseif ($cod_asociacion == "B") {
				$desc_asociacion = "ANVLUH";
			} else {
				$desc_asociacion = "SIN ASOCIACION";
			}

			$vendedores = mysqli_query($conn, "SELECT * FROM vendedores WHERE asociacion = '$cod_asociacion' AND seccional = '$cod_seccional' ORDER BY codigo ");

			
	$objPHPExcel->getActiveSheet()->SetCellValue('A1', "ASOCIACION: " . $desc_asociacion);
    $objPHPExcel->getActiveSheet()->SetCellValue('A2', "SECCIONAL: " . $cod_seccional);
    $objPHPExcel->getActiveSheet()->SetCellValue('A3', "ZONA: " . $zona);

    $objPHPExcel->getActiveSheet()->SetCellValue('A5', 'NO.');
    $objPHPExcel->getActiveSheet()->SetCellValue('B5', 'NOMBRE');
    $objPHPExcel->getActiveSheet()->SetCellValue('C5', 'IDENTIDAD');
    $objPHPExcel->getActiveSheet()->SetCellValue('D5', 'NO. CARNET');
    $objPHPExcel->getActiveSheet()->SetCellValue('E5', 'BOLSAS ASIGNADAS');
    $objPHPExcel->getActiveSheet()->SetCellValue('F5', 'TELEFONO');
    $objPHPExcel->getActiveSheet()->SetCellValue('G5', 'GENERO');
    $objPHPExcel->getActiveSheet()->SetCellValue('H5', 'ESTADO');

    $row = 6;
    $i = 1;
    $tt_bolsas = 0;
    while ($reg_vendedores = mysqli_fetch_array($vendedores)) {

		if ($reg_vendedores['estado'] == '1') {
			$estado = "ACTIVO"; 
		}else{
			$estado = "INACTIVO"; 
		}



        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $i);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $reg_vendedores['nombre']);

        $objPHPExcel->getActiveSheet()->getStyle('C' . $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $reg_vendedores['identidad']);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $row)->getNumberFormat()->setFormatCode("0000000000000");

        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, $reg_vendedores['asociacion'] . "-" . $reg_vendedores['seccional'] . "-" . $reg_vendedores['codigo']);
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $row, $reg_vendedores['numero_bolsas']);
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $row, $reg_vendedores['telefono']);
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $row, $reg_vendedores['sexo']);
        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $row, $estado);

        $tt_bolsas += $reg_vendedores['numero_bolsas'];
        $row++;
        $i++;
    }

    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, 'TOTAL BOLSAS  ');
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':D' . $row);
    $objPHPExcel->getActiveSheet()->SetCellValue('E' . $row, $tt_bolsas);
			

		}
	} else {

		$id_sorteo = $_POST['sorteo_oculto'];
		$c_ventas_sorteo = mysqli_query($conn, "SELECT identidad_comprador, nombre_comprador, SUM(cantidad) as cantidad, MAX(fecha_venta) as ultima_compra FROM transaccional_ventas_general WHERE id_sorteo = '$id_sorteo' AND estado_venta = 'APROBADO' AND cod_producto = 3  GROUP BY identidad_comprador  ");

		$v = 0;
		while ($reg_compradores = mysqli_fetch_array($c_ventas_sorteo)) {
			$v_compradores[$v] = ['identidad_comprador' => $reg_compradores['identidad_comprador'], 'nombre_comprador' => $reg_compradores['nombre_comprador'], 'cantidad_compra' => $reg_compradores['cantidad'],  'fecha_compra' => $reg_compradores['ultima_compra'] ];
			$v++;
		}


		if ($_POST['seccional_oculto'] == "todas") {

			$c_vendedores = mysqli_query($conn, "SELECT * FROM vendedores  ORDER BY  asociacion, seccional, codigo ");

		} else {

			$v_parametros = explode("-", $_POST['seccional_oculto']);
			$cod_asociacion = $v_parametros[0];
			$cod_seccional = $v_parametros[1];
			$zona = $v_parametros[2];

			if ($cod_asociacion == "A") {
				$desc_asociacion = "ANAVELH";
			} elseif ($cod_asociacion == "B") {
				$desc_asociacion = "ANVLUH";
			} else {
				$desc_asociacion = "SIN ASOCIACION";
			}

			$c_vendedores = mysqli_query($conn, "SELECT * FROM vendedores WHERE asociacion = '$cod_asociacion' AND seccional = '$cod_seccional' ORDER BY codigo ");
		}

		while ($reg_vendedores = mysqli_fetch_array($c_vendedores)) {
			$v_con_carnet[$reg_vendedores['identidad']] = ['nombre_comprador' => $reg_vendedores['nombre'], 'nombre_comprador' => $reg_vendedores['nombre'], 'asociacion' => $reg_vendedores['asociacion'], 'seccional' => $reg_vendedores['seccional'], 'codigo' => $reg_vendedores['codigo'], 'numero_bolsas' => $reg_vendedores['numero_bolsas'], 'telefono' => $reg_vendedores['telefono'], 'geocodigo' => $reg_vendedores['geocodigo'], 'zona_venta' => $reg_vendedores['zona_venta'], 'direccion' => $reg_vendedores['direccion'] , 'estado' => $reg_vendedores['estado']];
		
		
		}



		$objPHPExcel->getActiveSheet()->SetCellValue('A1', "SORTEO: " . $_POST['sorteo_oculto']);
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', "ASOCIACION: " . $desc_asociacion);
		$objPHPExcel->getActiveSheet()->SetCellValue('A3', "SECCIONAL: " . $cod_seccional);
		$objPHPExcel->getActiveSheet()->SetCellValue('A4', "ZONA: " . $zona);
	
		$objPHPExcel->getActiveSheet()->SetCellValue('A6', 'NO.');
		$objPHPExcel->getActiveSheet()->SetCellValue('B6', 'NOMBRE');
		$objPHPExcel->getActiveSheet()->SetCellValue('C6', 'IDENTIDAD');
		$objPHPExcel->getActiveSheet()->SetCellValue('D6', 'NO. CARNET');
		$objPHPExcel->getActiveSheet()->SetCellValue('E6', 'TELEFONO');
		$objPHPExcel->getActiveSheet()->SetCellValue('F6', 'BOLSAS ASIGNADAS');
		$objPHPExcel->getActiveSheet()->SetCellValue('G6', 'COMPRA EN SORTEO');
		$objPHPExcel->getActiveSheet()->SetCellValue('H6', 'FECHA ULTIMA COMPRA');
		$objPHPExcel->getActiveSheet()->SetCellValue('I6', 'ESTADO');
	
	
		$row = 7;
		$i = 1;
		$tt_bolsas = 0;
		$tt_compra = 0;
		$v = 0;

		while (isset($v_compradores[$v])) {

			if ($_POST['seccional_oculto'] != "todas") {

				if (isset($v_con_carnet[$v_compradores[$v]['identidad_comprador']])) {

					if ($v_con_carnet[$v_compradores[$v]['identidad_comprador']]['asociacion'] == $cod_asociacion and $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['seccional'] == $cod_seccional) {


						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $i);
						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $v_compradores[$v]['nombre_comprador']);
						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $v_compradores[$v]['identidad_comprador']);
						
						$tt_bolsas += $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['numero_bolsas'];

						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['asociacion'] . "-" . $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['seccional'] . "-" . $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['codigo']);
						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $row, $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['telefono']);
						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $row, $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['numero_bolsas']);
						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $row, $v_compradores[$v]['cantidad_compra']);
						$objPHPExcel->getActiveSheet()->SetCellValue('H' . $row, $v_compradores[$v]['fecha_compra']);
						$objPHPExcel->getActiveSheet()->SetCellValue('I' . $row, $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['estado']);

						$i++;
						$tt_compra += $v_compradores[$v]['cantidad_compra'];
						$v++;
						$row++;
					}
				} else {
					$v++;
				}


			} else {


				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $i);
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $v_compradores[$v]['nombre_comprador']);
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $v_compradores[$v]['identidad_comprador']);
				
				$tt_bolsas += $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['numero_bolsas'];

				if (isset($v_con_carnet[$v_compradores[$v]['identidad_comprador']])) {
					$tt_bolsas += $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['numero_bolsas'];

					$objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['asociacion'] . "-" . $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['seccional'] . "-" . $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['codigo']);
					$objPHPExcel->getActiveSheet()->SetCellValue('E' . $row, $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['telefono']);
					$objPHPExcel->getActiveSheet()->SetCellValue('F' . $row, $v_con_carnet[$v_compradores[$v]['identidad_comprador']]['numero_bolsas']);


if ($v_con_carnet[$v_compradores[$v]['identidad_comprador']]['estado'] == '1'){
$estado = 'ACTIVO';
}else{
$estado = 'INACTIVO';
}

					$objPHPExcel->getActiveSheet()->SetCellValue('I' . $row, $estado);
					


				} else {
					$objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, "NO REGISTRADO COMO VENDEDOR");
					$objPHPExcel->getActiveSheet()->mergeCells('D'.$row.':F'.$row);
				}


				
				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $row, $v_compradores[$v]['cantidad_compra']);
				$objPHPExcel->getActiveSheet()->SetCellValue('H' . $row, $v_compradores[$v]['fecha_compra']);
	

				$i++;
				$tt_compra += $v_compradores[$v]['cantidad_compra'];
				$v++;
				$row++;


			}
		}

	}



	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=\"Listado_vendedores_seccionales.xlsx\"");
	header("Cache-Control: max-age=0");
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	ob_clean();
	$objWriter->save("php://output");
	

}

?>
