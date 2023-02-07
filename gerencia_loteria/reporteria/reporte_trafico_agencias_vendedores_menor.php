<?php
require "../../template/header.php";
date_default_timezone_set('America/Tegucigalpa');
?>


<form method="POST">


	<section style="background-color:#ededed;">
		<br>
		<h3 align="center"><b>REPORTE DE CONCURRENCIA DE VENDEDORES EN AGENCIAS (BANCO DISTRIBUIDOR)</b></h3>
		<br>
	</section>



	<a id='non-printable' style="width:100%" class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">Seleccion de Parametros</a>

	<div class="card collapse" id="collapse1">
		<div class="card-body">




			<div class="row">


				<div class=" col col-md-3">

					<div class="input-group">
						<div class="input-group-prepend">
							<div class="input-group-text">Del Mes:</div>
						</div>

						<select class="form-control" name="del_mes" id='del_mes' ">
		<option value="">Seleccione uno</option>
<?php

$meses = mysqli_query($conn, "SELECT CONCAT( MONTH(fecha_venta), '-', YEAR(fecha_venta) ) as fecha  FROM transaccional_ventas_general  GROUP BY YEAR(fecha_venta) DESC , MONTH(fecha_venta) DESC ");

while ($r_meses = mysqli_fetch_array($meses)) {
	echo '<option value = "' . $r_meses['fecha'] . '">  ' . $r_meses['fecha'] . '</option>';
}

?>
</select>
</div>

</div>


				<div class=" col col-md-3">

							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text">Al Mes:</div>
								</div>

								<select class="form-control" name="al_mes" id='al_mes' ">
							<option value="">Seleccione uno</option>
<?php

$meses = mysqli_query($conn, "SELECT CONCAT( MONTH(fecha_venta), '-', YEAR(fecha_venta) ) as fecha  FROM transaccional_ventas_general  GROUP BY YEAR(fecha_venta) DESC , MONTH(fecha_venta) DESC ");

while ($r_meses = mysqli_fetch_array($meses)) {
	echo '<option value = "' . $r_meses['fecha'] . '">  ' . $r_meses['fecha'] . '</option>';
}

?>
</select>
</div>

			</div>


			<div class=" col">

									<div class="input-group">
										<div class="input-group-prepend">
											<div class="input-group-text">Departamento:</div>
										</div>

										<select class="form-control" name="s_departamento" id='s_departamento' ">
	<option value="">Seleccione uno</option>mayor

<?php

$departamentos = mysqli_query($conn, " SELECT DISTINCT(departamento) as departamento FROM distribucion_menor_bolsas_banco WHERE departamento != '' ORDER BY departamento ASC ");

while ($r_departamentos = mysqli_fetch_array($departamentos)) {
	echo '<option value = "' . $r_departamentos['departamento'] . '">  ' . $r_departamentos['departamento'] . '</option>';
}

?>
</select>
</div>

</div>




		</div>






			<div class=" row" style="margin-top: 15px;">



											<div class=" col col-md-3">


												<div class="input-group">
													<div class="input-group-prepend">
														<div class="input-group-text">Sorteo:</div>
													</div>

													<select class="form-control" name="s_sorteo" id='s_sorteo' ">
<?php

$c_sorteos = mysqli_query($conn, "SELECT id, fecha_sorteo FROM sorteos_menores  ORDER BY id DESC");

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
																<div class="input-group-text">Agencia:</div>
															</div>

															<select class="form-control" name="s_seccional" id='s_seccional' ">
<?php

$agencias = mysqli_query($conn, "SELECT *  FROM distribucion_menor_bolsas_banco GROUP BY id_seccional  ORDER BY departamento, municipio ASC  ");

echo '<option value = "todas">TODAS</option>';

while ($row2 = mysqli_fetch_array($agencias)) {

	echo '<option value = "' . $row2['id_seccional'] . '-' . $row2['nombre_seccional'] . '" >' . $row2['departamento'] . ' | ' . $row2['municipio'] . ' | ' . $row2['nombre_seccional'] . '</option>';
}
?>
</select>


<div class=" input-group-append">
														</div>
												</div>

											</div>
									</div>




									<div class="row" style="margin-top:15px">
										<div class="col" style="text-align:center">
											<button type="submit" name="seleccionar" id="seleccionar" class="btn btn-primary">Seleccionar</button>
										</div>
									</div>



							</div>
					</div>



					<?php

					if (isset($_POST['seleccionar'])) {


						$c_vendedores = mysqli_query($conn, "SELECT * FROM vendedores WHERE estado = '1' ");

						while ($reg_vendedores = mysqli_fetch_array($c_vendedores)) {
							$v_vendedores[$reg_vendedores['identidad']] = $reg_vendedores['asociacion'];
						}



						$id_sorteo = $_POST['s_sorteo'];
						$id_agencia = $_POST['s_seccional'];
						$mes = $_POST['del_mes'];
						$mes2 = $_POST['al_mes'];
						$departamento = $_POST['s_departamento'];

						$conulta_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE id = '$id_sorteo'  ");
						$ob_sorteo = mysqli_fetch_object($conulta_sorteo);
						$fecha_sorteo = $ob_sorteo->fecha_sorteo;

						echo "<input type = 'hidden' name = 'seccional_oculto' value = '$id_agencia' >";
						echo "<input type = 'hidden' name = 'sorteo_oculto' value = '$id_sorteo' >";

						echo "<input type = 'hidden' name = 'departamento_oculto' value = '$departamento' >";
						echo "<input type = 'hidden' name = 'mes_oculto' value = '$mes' >";
						echo "<input type = 'hidden' name = 'mes_oculto2' value = '$mes2' >";

						echo "<br>";

						if ($mes != "" and $departamento == "") {

							$v_mes = explode("-", $mes);

							$num_mes = $v_mes[0];
							$year = $v_mes[1];

							$v_mes2 = explode("-", $mes2);

							$num_mes2 = $v_mes2[0];
							$year2 = $v_mes2[1];

							$fecha_1 = $year . "-" . $num_mes . "-01";
							$fecha_2 = $year2 . "-" . $num_mes2 . "-31";

							$consulta_agencias = mysqli_query($conn, "SELECT a.cod_factura_recaudador, a.id_sorteo, a.id_entidad, SUM(a.cantidad) as cantidad, a.id_seccional, a.identidad_comprador, a.nombre_comprador , a.asociacion_comprador , a.fecha_venta, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a INNER JOIN  distribucion_menor_bolsas_banco as b ON a.id_seccional = b.id_seccional AND a.id_sorteo = b.id_sorteo WHERE  a.estado_venta = 'APROBADO' AND cod_producto = '3' AND DATE(fecha_venta) BETWEEN '$fecha_1' AND  '$fecha_2'   GROUP BY a.id_seccional  ");
						} else if ($mes != "" and $departamento != "") {

							$v_mes = explode("-", $mes);

							$num_mes = $v_mes[0];
							$year = $v_mes[1];

							$v_mes2 = explode("-", $mes2);

							$num_mes2 = $v_mes2[0];
							$year2 = $v_mes2[1];

							$fecha_1 = $year . "-" . $num_mes . "-01";
							$fecha_2 = $year2 . "-" . $num_mes2 . "-31";

							$consulta_agencias = mysqli_query($conn, "SELECT a.cod_factura_recaudador, a.id_sorteo, a.id_entidad, SUM(a.cantidad) as cantidad, a.id_seccional, a.identidad_comprador, a.nombre_comprador , a.asociacion_comprador , a.fecha_venta, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a LEFT JOIN  distribucion_menor_bolsas_banco as b ON a.id_seccional = b.id_seccional AND a.id_sorteo = b.id_sorteo WHERE  a.estado_venta = 'APROBADO' AND cod_producto = '3' AND DATE(fecha_venta) BETWEEN '$fecha_1' AND  '$fecha_2'  AND b.departamento = '" . $departamento . "' GROUP BY a.id_seccional  ");
						} else if ($id_agencia == "todas") {

							$consulta_agencias = mysqli_query($conn, "SELECT a.cod_factura_recaudador, a.id_sorteo, a.id_entidad, SUM(a.cantidad) as cantidad, a.id_seccional, a.identidad_comprador, a.nombre_comprador , a.asociacion_comprador , a.fecha_venta, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a INNER JOIN  distribucion_menor_bolsas_banco as b ON a.id_seccional = b.id_seccional AND a.id_sorteo = b.id_sorteo WHERE a.id_sorteo = '" . $id_sorteo . "' AND a.estado_venta = 'APROBADO' AND cod_producto = '3' AND b.id_sorteo = '" . $id_sorteo . "' GROUP BY a.id_seccional  ");
						} else {

							$v_seccional = explode("-", $_POST['s_seccional']);

							$id_agencia = $v_seccional[0];
							
							$nombre_agencia = $v_seccional[1];
							$consulta_agencias = mysqli_query($conn, "SELECT a.cod_factura_recaudador, a.id_sorteo, a.id_entidad, SUM(a.cantidad) as cantidad, a.id_seccional, a.identidad_comprador, a.nombre_comprador , a.asociacion_comprador , a.fecha_venta, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a LEFT JOIN  distribucion_menor_bolsas_banco as b ON a.id_seccional = b.id_seccional AND a.id_sorteo = b.id_sorteo WHERE a.id_sorteo = '" . $id_sorteo . "' AND a.estado_venta = 'APROBADO' AND cod_producto = '3' AND a.id_seccional = '" . $id_agencia . "' AND b.id_sorteo = '" . $id_sorteo . "' GROUP BY a.id_seccional  ");
						}

					?>



						<div class="card">
							<div class="card-header bg-secondary text-white">
								<h4 style="text-align:center">LISTA DE CONCURRENCIA EN AGENCIA (BANCO DISTRIBUIDOR)</h4>
								<br>
							</div>



							<div class="card-body">


								<?php

								$tt_general = 0;

								while ($reg_consulta_agencias = mysqli_fetch_array($consulta_agencias)) {

									$id_seccional_consulta = $reg_consulta_agencias['id_seccional'];

									if ($mes != "" and $departamento == "") {

										$consulta_agencias_v = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad FROM transaccional_ventas_general as a  INNER JOIN vendedores as b ON a.identidad_comprador = b.identidad WHERE  a.estado_venta = 'APROBADO' AND cod_producto = '3' AND  DATE(fecha_venta) BETWEEN '$fecha_1' AND  '$fecha_2' AND a.id_seccional = '$id_seccional_consulta'  ");
										$ob_venta = mysqli_fetch_object($consulta_agencias_v);
										$vendido = $ob_venta->cantidad;
									} else if ($mes != "" and $departamento != "") {

										$consulta_agencias_v = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad FROM transaccional_ventas_general as a  INNER JOIN vendedores as b ON a.identidad_comprador = b.identidad  WHERE  a.estado_venta = 'APROBADO' AND cod_producto = '3' AND DATE(fecha_venta) BETWEEN '$fecha_1' AND  '$fecha_2' AND a.id_seccional = '$id_seccional_consulta'  ");
										$ob_venta = mysqli_fetch_object($consulta_agencias_v);
										$vendido = $ob_venta->cantidad;

										echo mysqli_error($conn);
									} else if ($id_agencia == "todas") {

										$consulta_agencias_v = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad FROM transaccional_ventas_general as a  INNER JOIN vendedores as b ON a.identidad_comprador = b.identidad  WHERE  a.estado_venta = 'APROBADO' AND cod_producto = '3' AND a.id_seccional = '$id_seccional_consulta' AND  a.id_sorteo = '" . $id_sorteo . "' ");
										$ob_venta = mysqli_fetch_object($consulta_agencias_v);
										$vendido = $ob_venta->cantidad;
									} else {

										$consulta_agencias_v = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad FROM transaccional_ventas_general as a  INNER JOIN vendedores as b ON a.identidad_comprador = b.identidad  WHERE  a.estado_venta = 'APROBADO' AND cod_producto = '3' AND a.id_seccional = '$id_seccional_consulta' AND  a.id_sorteo = '" . $id_sorteo . "' ");
										$ob_venta = mysqli_fetch_object($consulta_agencias_v);
										$vendido = $ob_venta->cantidad;
									}

									$tt_general += $vendido;

								?>


									<div id="accordion">
										<div class="card">

											<div class="card-header">
												<div class="row">
													<div class="col co-md-8">
														<a class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo $id_seccional_consulta; ?>" aria-expanded="true" aria-controls="collapseOne">
															<?php echo $reg_consulta_agencias['departamento'] . " - " . $reg_consulta_agencias['municipio'] . " - " . $reg_consulta_agencias['nombre_seccional']; ?>

														</a>
													</div>
													<div class="col col-md-2" style="text-align: end;">
														<?php echo number_format($vendido); ?>
													</div>
												</div>
											</div>

											<div id="collapse<?php echo $id_seccional_consulta; ?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
												<div class="card-body">



													<table class="table table-bordered" id="">
														<thead>
															<tr>
																<th>#</th>
																<th>AGENCIA</th>
																<th>DEPARTAMENTO</th>
																<th>MUNICIPIO</th>
																<th>IDENTIDAD</th>
																<th>NOMBRE</th>
																<th>CANTIDAD COMPRA</th>
																<th>FECHA COMPRA</th>
																<th>ASOCIACION</th>
															</tr>
														</thead>
														<tbody>



															<?php

															if ($mes != "" and $departamento == "") {

																$v_mes = explode("-", $mes);

																$num_mes = $v_mes[0];
																$year = $v_mes[1];

																$v_mes2 = explode("-", $mes2);

																$num_mes2 = $v_mes2[0];
																$year2 = $v_mes2[1];

																$consulta_ventas = mysqli_query($conn, "SELECT a.cod_factura_recaudador, a.id_sorteo, a.id_entidad, a.cantidad, a.id_seccional, a.identidad_comprador, a.nombre_comprador , a.asociacion_comprador , a.fecha_venta, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a INNER JOIN  distribucion_menor_bolsas_banco as b INNER JOIN vendedores as c ON a.identidad_comprador = c.identidad AND a.identidad_comprador = c.identidad AND  a.id_seccional = b.id_seccional AND a.id_sorteo = b.id_sorteo WHERE  a.estado_venta = 'APROBADO' AND cod_producto = '3' AND DATE(fecha_venta) BETWEEN '$fecha_1' AND  '$fecha_2' AND a.id_seccional = '$id_seccional_consulta' GROUP BY a.cod_factura_recaudador  ");
															} else if ($mes != "" and $departamento != "") {

																$v_mes = explode("-", $mes);

																$num_mes = $v_mes[0];
																$year = $v_mes[1];

																$v_mes2 = explode("-", $mes2);

																$num_mes2 = $v_mes2[0];
																$year2 = $v_mes2[1];

																$consulta_ventas = mysqli_query($conn, "SELECT a.cod_factura_recaudador, a.id_sorteo, a.id_entidad, a.cantidad, a.id_seccional, a.identidad_comprador, a.nombre_comprador , a.asociacion_comprador , a.fecha_venta, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a LEFT JOIN  distribucion_menor_bolsas_banco as b INNER JOIN vendedores as c ON a.identidad_comprador = c.identidad AND a.identidad_comprador = c.identidad AND a.id_seccional = b.id_seccional AND a.id_sorteo = b.id_sorteo WHERE  a.estado_venta = 'APROBADO' AND cod_producto = '3' AND DATE(fecha_venta) BETWEEN '$fecha_1' AND  '$fecha_2'  AND b.departamento = '" . $departamento . "' AND a.id_seccional = '$id_seccional_consulta' GROUP BY a.cod_factura_recaudador  ");
															} else if ($id_agencia == "todas") {

																$consulta_ventas = mysqli_query($conn, "SELECT a.cod_factura_recaudador, a.id_sorteo, a.id_entidad, a.cantidad, a.id_seccional, a.identidad_comprador, a.nombre_comprador , a.asociacion_comprador , a.fecha_venta, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a INNER JOIN  distribucion_menor_bolsas_banco as b INNER JOIN vendedores as c ON a.identidad_comprador = c.identidad AND a.identidad_comprador = c.identidad AND a.id_seccional = b.id_seccional AND a.id_sorteo = b.id_sorteo WHERE a.id_sorteo = '" . $id_sorteo . "' AND a.estado_venta = 'APROBADO' AND cod_producto = '3' AND b.id_sorteo = '" . $id_sorteo . "' AND a.id_seccional = '$id_seccional_consulta' GROUP BY a.cod_factura_recaudador  ");
															} else {

																$v_seccional = explode("-", $_POST['s_seccional']);

																$id_agencia = $v_seccional[0];
																$nombre_agencia = $v_seccional[1];
//																$consulta_ventas = mysqli_query($conn, "SELECT a.cod_factura_recaudador, a.id_sorteo, a.id_entidad, a.cantidad, a.id_seccional, a.identidad_comprador, a.nombre_comprador , a.asociacion_comprador , a.fecha_venta, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a INNER JOIN  distribucion_menor_bolsas_banco as b INNER JOIN vendedores as c ON a.identidad_comprador = c.identidad AND a.identidad_comprador = c.identidad AND a.id_seccional = b.id_seccional AND a.id_sorteo = b.id_sorteo WHERE a.id_sorteo = '" . $id_sorteo . "' AND a.estado_venta = 'APROBADO' AND cod_producto = '3' AND b.id_sorteo = '" . $id_sorteo . "' AND a.id_seccional = '$id_seccional_consulta' GROUP BY a.cod_factura_recaudador  ");
																$consulta_ventas = mysqli_query($conn, "SELECT a.cod_factura_recaudador, a.id_sorteo, a.id_entidad, a.cantidad, a.id_seccional, a.identidad_comprador, a.nombre_comprador , a.asociacion_comprador , a.fecha_venta, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a INNER JOIN  distribucion_menor_bolsas_banco as b INNER JOIN vendedores as c ON a.identidad_comprador = c.identidad AND a.identidad_comprador = c.identidad AND a.id_seccional = b.id_seccional AND a.id_sorteo = b.id_sorteo WHERE a.id_sorteo = '" . $id_sorteo . "' AND a.estado_venta = 'APROBADO' AND cod_producto = '3' AND b.id_sorteo = '" . $id_sorteo . "' AND a.id_seccional = '$id_seccional_consulta' GROUP BY a.cod_factura_recaudador  ");
															}

															$total_agencia = 0;
															$contador = 1;
															echo mysqli_error($conn);
															while ($reg_ventas = mysqli_fetch_array($consulta_ventas)) {

															?>

																<tr>
																	<td><?php echo $contador; ?></td>
																	<td><?php echo $reg_ventas['nombre_seccional']; ?></td>
																	<td><?php echo $reg_ventas['departamento']; ?></td>
																	<td><?php echo $reg_ventas['municipio']; ?></td>
																	<td><?php echo $reg_ventas['identidad_comprador']; ?></td>
																	<td><?php echo $reg_ventas['nombre_comprador']; ?></td>
																	<td><?php echo $reg_ventas['cantidad']; ?></td>
																	<td><?php echo $reg_ventas['fecha_venta']; ?></td>
																	<?php
																	if (isset($v_vendedores[$reg_ventas['identidad_comprador']])) {
																	?>
																		<td><?php echo $v_vendedores[$reg_ventas['identidad_comprador']]; ?></td>
																	<?php
																	} else {
																	?>
																		<td></td>
																	<?php
																	}
																	?>

																</tr>

															<?php
																$contador++;
																$total_agencia += $reg_ventas['cantidad'];
															}
															?>

														</tbody>
														<tr>
															<td colspan="6">TOTAL VENDIDO</td>
															<td><?php echo number_format($total_agencia); ?></td>
															<td colspan="2">
																<button type="submit" name="generar_excel_especifico" value='<?php echo $id_seccional_consulta; ?>' class="btn btn-success">EXCEL</button>
															</td>
														</tr>
													</table>

												</div>
											</div>
										</div>
									</div>


								<?php

								}

								?>


								<div class="card">
									<div class="card-header">
										<div class="row">
											<div class="col">
												<b>TOTAL GENERAL </b>
											</div>
											<div class="col" style="text-align: end;">
												<b><?php echo number_format($tt_general); ?></b>
											</div>
										</div>

									</div>
								</div>








								<br>



							</div>
							<div class="card-footer" style="text-align: center;">
								<button type="submit" name="generar_excel" class="btn btn-success">
									GENERAR EXCEL
								</button>
							</div>
						</div>

					<?php

					}

					?>

</form>



















<?php

if (isset($_POST['generar_excel'])) {

	require_once '../../assets/phpexcel/Classes/PHPExcel/IOFactory.php';

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);

	$id_sorteo = $_POST['sorteo_oculto'];

	$conulta_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE id = '$id_sorteo'  ");
	$ob_sorteo = mysqli_fetch_object($conulta_sorteo);
	$fecha_sorteo = $ob_sorteo->fecha_sorteo;

	$c_vendedores = mysqli_query($conn, "SELECT * FROM vendedores WHERE estado = '1' ");

	while ($reg_vendedores = mysqli_fetch_array($c_vendedores)) {
		$v_vendedores[$reg_vendedores['identidad']] = $reg_vendedores['asociacion'];
	}


	$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'AGENCIA');
	$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'DEPARTAMENTO');
	$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'MUNICIPIO');
	$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'IDENTIDAD');
	$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'NOMBRE');
	$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'CANTIDAD COMPRA');
	$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'FECHA COMPRA');
	$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'ASOCIACION');

	$mes = $_POST['mes_oculto'];
	$mes2 = $_POST['mes_oculto2'];
	//	echo $mes2;

	$departamento = $_POST['departamento_oculto'];

	if ($mes != "" and $departamento == "") {

		$v_mes = explode("-", $mes);

		$num_mes = $v_mes[0];
		$year = $v_mes[1];

		$v_mes2 = explode("-", $mes2);

		$num_mes2 = $v_mes2[0];
		$year2 = $v_mes2[1];

		$fecha_1 = $year . "-" . $num_mes . "-01";
		$fecha_2 = $year2 . "-" . $num_mes2 . "-31";

		$consulta_ventas = mysqli_query($conn, "SELECT a.cod_factura_recaudador, a.id_sorteo, a.id_entidad, a.cantidad, a.id_seccional, a.identidad_comprador, a.nombre_comprador , a.asociacion_comprador , a.fecha_venta, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a INNER JOIN  distribucion_menor_bolsas_banco as b INNER JOIN vendedores as c ON a.identidad_comprador = c.identidad AND a.id_seccional = b.id_seccional AND a.id_sorteo = b.id_sorteo WHERE  a.estado_venta = 'APROBADO' AND cod_producto = '3' AND DATE(fecha_venta) BETWEEN '$fecha_1' AND  '$fecha_2' GROUP BY a.cod_factura_recaudador  ");
	} else if ($mes != "" and $departamento != "") {

		$v_mes = explode("-", $mes);

		$num_mes = $v_mes[0];
		$year = $v_mes[1];

		$v_mes2 = explode("-", $mes2);

		$num_mes2 = $v_mes2[0];
		$year2 = $v_mes2[1];

		$fecha_1 = $year . "-" . $num_mes . "-01";
		$fecha_2 = $year2 . "-" . $num_mes2 . "-31";

		$consulta_ventas = mysqli_query($conn, "SELECT a.cod_factura_recaudador, a.id_sorteo, a.id_entidad, a.cantidad, a.id_seccional, a.identidad_comprador, a.nombre_comprador , a.asociacion_comprador , a.fecha_venta, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a LEFT JOIN  distribucion_menor_bolsas_banco as b INNER JOIN vendedores as c ON a.identidad_comprador = c.identidad AND a.id_seccional = b.id_seccional AND a.id_sorteo = b.id_sorteo WHERE  a.estado_venta = 'APROBADO' AND cod_producto = '3' AND DATE(fecha_venta) BETWEEN '$fecha_1' AND  '$fecha_2'  AND b.departamento = '" . $departamento . "' GROUP BY a.cod_factura_recaudador  ");
	} else if ($_POST['seccional_oculto'] == "todas") {

		$consulta_ventas = mysqli_query($conn, "SELECT a.cod_factura_recaudador, a.id_sorteo, a.id_entidad, a.cantidad, a.id_seccional, a.identidad_comprador, a.nombre_comprador , a.asociacion_comprador , a.fecha_venta, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a LEFT JOIN  distribucion_menor_bolsas_banco as b INNER JOIN vendedores as c ON a.identidad_comprador = c.identidad AND a.id_seccional = b.id_seccional AND a.id_sorteo = b.id_sorteo WHERE a.id_sorteo = '" . $id_sorteo . "' AND a.estado_venta = 'APROBADO' AND cod_producto = '3' AND b.id_sorteo = '" . $id_sorteo . "' GROUP BY a.cod_factura_recaudador ");
	} else {

		$v_seccional = explode("-", $_POST['seccional_oculto']);

		$id_agencia = $v_seccional[0];
		$nombre_agencia = $v_seccional[1];

		$consulta_ventas = mysqli_query($conn, "SELECT a.cod_factura_recaudador, a.id_sorteo, a.id_entidad, a.cantidad, a.id_seccional, a.identidad_comprador, a.nombre_comprador , a.asociacion_comprador , a.fecha_venta, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a LEFT JOIN  distribucion_menor_bolsas_banco as b INNER JOIN vendedores as c ON a.identidad_comprador = c.identidad AND a.id_seccional = b.id_seccional AND a.id_sorteo = b.id_sorteo WHERE a.id_sorteo = '" . $id_sorteo . "' AND a.estado_venta = 'APROBADO' AND cod_producto = '3' AND a.id_seccional = '" . $id_agencia . "' AND b.id_sorteo = '" . $id_sorteo . "' GROUP BY a.cod_factura_recaudador ");
	}

	echo mysqli_error($conn);

	$row = 2;
	$i = 1;
	$tt_bolsas = 0;

	while ($reg_ventas = mysqli_fetch_array($consulta_ventas)) {

		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $reg_ventas['nombre_seccional']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $reg_ventas['departamento']);
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $reg_ventas['municipio']);

		$objPHPExcel->getActiveSheet()->getStyle('D' . $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, $reg_ventas['identidad_comprador']);

		$objPHPExcel->getActiveSheet()->getStyle('D' . $row)->getNumberFormat()->setFormatCode("0000000000000");

		$objPHPExcel->getActiveSheet()->SetCellValue('E' . $row, $reg_ventas['nombre_comprador']);
		$objPHPExcel->getActiveSheet()->SetCellValue('F' . $row, $reg_ventas['cantidad']);
		$objPHPExcel->getActiveSheet()->SetCellValue('G' . $row, $reg_ventas['fecha_venta']);

		if (isset($v_vendedores[$reg_ventas['identidad_comprador']])) {
			$objPHPExcel->getActiveSheet()->SetCellValue('H' . $row, $v_vendedores[$reg_ventas['identidad_comprador']]);
		} else {
			$objPHPExcel->getActiveSheet()->SetCellValue('H' . $row, "");
		}



		$row++;
		$i++;
	}

	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=\"Concurrencia_Agencias.xlsx\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	ob_clean();
	$objWriter->save("php://output");

}





if (isset($_POST['generar_excel_especifico'])) {

	require_once '../../assets/phpexcel/Classes/PHPExcel/IOFactory.php';

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);

	$id_sorteo = $_POST['sorteo_oculto'];
	$id_seccional = $_POST['generar_excel_especifico'];

	$conulta_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE id = '$id_sorteo'  ");
	$ob_sorteo = mysqli_fetch_object($conulta_sorteo);
	$fecha_sorteo = $ob_sorteo->fecha_sorteo;

	$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'AGENCIA');
	$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'DEPARTAMENTO');
	$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'MUNICIPIO');
	$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'IDENTIDAD');
	$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'NOMBRE');
	$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'CANTIDAD COMPRA');
	$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'FECHA COMPRA');
	$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'ASOCIACION');

	$mes = $_POST['mes_oculto'];
	$mes2 = $_POST['mes_oculto2'];
	//	echo $mes2;

	$departamento = $_POST['departamento_oculto'];

	if ($mes != "" and $departamento == "") {

		$v_mes = explode("-", $mes);

		$num_mes = $v_mes[0];
		$year = $v_mes[1];

		$v_mes2 = explode("-", $mes2);

		$num_mes2 = $v_mes2[0];
		$year2 = $v_mes2[1];

		$fecha_1 = $year . "-" . $num_mes . "-01";
		$fecha_2 = $year2 . "-" . $num_mes2 . "-31";

		$consulta_ventas = mysqli_query($conn, "SELECT a.cod_factura_recaudador, a.id_sorteo, a.id_entidad, a.cantidad, a.id_seccional, a.identidad_comprador, a.nombre_comprador , a.asociacion_comprador , a.fecha_venta, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a INNER JOIN  distribucion_menor_bolsas_banco as b INNER JOIN vendedores as c ON a.identidad_comprador = c.identidad AND a.id_seccional = b.id_seccional AND a.id_sorteo = b.id_sorteo WHERE  a.estado_venta = 'APROBADO' AND cod_producto = '3' AND DATE(fecha_venta) BETWEEN '$fecha_1' AND  '$fecha_2' AND a.id_seccional = '$id_seccional' GROUP BY a.cod_factura_recaudador  ");
	} else if ($mes != "" and $departamento != "") {

		$v_mes = explode("-", $mes);

		$num_mes = $v_mes[0];
		$year = $v_mes[1];

		$v_mes2 = explode("-", $mes2);

		$num_mes2 = $v_mes2[0];
		$year2 = $v_mes2[1];

		$fecha_1 = $year . "-" . $num_mes . "-01";
		$fecha_2 = $year2 . "-" . $num_mes2 . "-31";

		$consulta_ventas = mysqli_query($conn, "SELECT a.cod_factura_recaudador, a.id_sorteo, a.id_entidad, a.cantidad, a.id_seccional, a.identidad_comprador, a.nombre_comprador , a.asociacion_comprador , a.fecha_venta, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a LEFT JOIN  distribucion_menor_bolsas_banco as b INNER JOIN vendedores as c ON a.identidad_comprador = c.identidad AND a.id_seccional = b.id_seccional AND a.id_sorteo = b.id_sorteo WHERE  a.estado_venta = 'APROBADO' AND cod_producto = '3' AND DATE(fecha_venta) BETWEEN '$fecha_1' AND  '$fecha_2'  AND b.departamento = '" . $departamento . "' AND a.id_seccional = '$id_seccional' GROUP BY a.cod_factura_recaudador  ");
	} else if ($_POST['seccional_oculto'] == "todas") {

		$consulta_ventas = mysqli_query($conn, "SELECT a.cod_factura_recaudador, a.id_sorteo, a.id_entidad, a.cantidad, a.id_seccional, a.identidad_comprador, a.nombre_comprador , a.asociacion_comprador , a.fecha_venta, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a LEFT JOIN  distribucion_menor_bolsas_banco as b INNER JOIN vendedores as c ON a.identidad_comprador = c.identidad AND a.id_seccional = b.id_seccional AND a.id_sorteo = b.id_sorteo WHERE a.id_sorteo = '" . $id_sorteo . "' AND a.estado_venta = 'APROBADO' AND cod_producto = '3' AND b.id_sorteo = '" . $id_sorteo . "' AND a.id_seccional = '$id_seccional' GROUP BY a.cod_factura_recaudador ");
	} else {

		$v_seccional = explode("-", $_POST['seccional_oculto']);

		$id_agencia = $v_seccional[0];
		$nombre_agencia = $v_seccional[1];
		$consulta_ventas = mysqli_query($conn, "SELECT a.cod_factura_recaudador, a.id_sorteo, a.id_entidad, a.cantidad, a.id_seccional, a.identidad_comprador, a.nombre_comprador , a.asociacion_comprador , a.fecha_venta, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a INNER JOIN  distribucion_menor_bolsas_banco as b INNER JOIN vendedores as c ON a.identidad_comprador = c.identidad AND a.identidad_comprador = c.identidad AND a.id_seccional = b.id_seccional AND a.id_sorteo = b.id_sorteo WHERE a.id_sorteo = '" . $id_sorteo . "' AND a.estado_venta = 'APROBADO' AND cod_producto = '3' AND b.id_sorteo = '" . $id_sorteo . "' AND a.id_seccional = '$id_seccional' GROUP BY a.cod_factura_recaudador  ");

	}

	echo mysqli_error($conn);

	$row = 2;
	$i = 1;
	$tt_bolsas = 0;

	while ($reg_ventas = mysqli_fetch_array($consulta_ventas)) {

		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $reg_ventas['nombre_seccional']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $reg_ventas['departamento']);
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $reg_ventas['municipio']);

		$objPHPExcel->getActiveSheet()->getStyle('D' . $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, $reg_ventas['identidad_comprador']);
		$objPHPExcel->getActiveSheet()->getStyle('D' . $row)->getNumberFormat()->setFormatCode("0000000000000");

		$objPHPExcel->getActiveSheet()->SetCellValue('E' . $row, $reg_ventas['nombre_comprador']);
		$objPHPExcel->getActiveSheet()->SetCellValue('F' . $row, $reg_ventas['cantidad']);
		$objPHPExcel->getActiveSheet()->SetCellValue('G' . $row, $reg_ventas['fecha_venta']);

		if (isset($v_vendedores[$reg_ventas['identidad_comprador']])) {
			$objPHPExcel->getActiveSheet()->SetCellValue('H' . $row, $v_vendedores[$reg_ventas['identidad_comprador']]);
		} else {
			$objPHPExcel->getActiveSheet()->SetCellValue('H' . $row, "");
		}

		$row++;
		$i++;
	}

	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=\"Concurrencia_Agencias.xlsx\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	ob_clean();
	$objWriter->save("php://output");

}


?>