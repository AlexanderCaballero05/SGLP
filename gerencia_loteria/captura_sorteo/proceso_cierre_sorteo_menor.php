<?php

date_default_timezone_set("America/Tegucigalpa");

require '../../template/header.php';
require '../../assets/phpmailer/class.phpmailer.php';
require '../../assets/phpmailer/class.smtp.php';
require '_functions.php';

$fecha_actual = date("Y-m-d");
$id_sorteo = $_GET['s'];
$_sorteo = $_GET['s'];
$sorteo = $_GET['s'];

?>

<section style="background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >PROCESO DE CIERRE DE SORTEO MENOR <?php echo $sorteo; ?></h2>
<br>
</section>


<br>
<br>

<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////// CODIGO DE REGISTRO ARCHIVO DE PAGOS MENOR ////////////////////////////////////////

///////////////////////////////////////////////// VERIFICAR REGISTRO ///////////////////////////////////////////////////

$c_registrado = mysqli_query($conn, "SELECT * FROM archivo_pagos_menor WHERE sorteo = '$id_sorteo' ");

$flag_registrado = mysqli_num_rows($c_registrado);
///////////////////////////////////////////////// VERIFICAR REGISTRO ///////////////////////////////////////////////////

if ($flag_registrado == 0) {

	unset($query_numero_derecho);

	$cantidad_derecho_total     = 0;
	$cantidad_reves_totla       = 0;
	$totalpayment_derecho_total = 0;
	$totalpayment_reves_total   = 0;
	$imptopayment_derecho_total = 0;
	$imptopayment_reves_total   = 0;
	$netopayment_derecho_total  = 0;
	$netopayment_reves_total    = 0;
	$cantidad_reves_total       = 0;

	$error_Actualiza_registros_ventas = 0;
/// AQUI TRAEMOS EL SORTEO   SELECT sorteo, count(serie), sum(totalpayment), sum(imptopayment), sum(netopayment) FROM `archivo_pagos_menor` group by sorteo order by sorteo desc   -- SELECT sorteo, count(serie), sum(totalpayment), sum(imptopayment), sum(netopayment) FROM `archivo_pagos_menor` group by sorteo order by sorteo desc
	$_query_sorteo = mysqli_query($conn, "SELECT sorteos_menores_id FROM sorteos_menores_premios  WHERE numero_premiado_menor IS NOT NULL AND sorteos_menores_id = '$id_sorteo' ORDER BY sorteos_menores_id DESC LIMIT 1");

	while ($row_sorteo = mysqli_fetch_array($_query_sorteo)) {
		$_sorteo = $row_sorteo['sorteos_menores_id'];
	}

//$_sorteo=3223;
	$_rango_inicial_serie = 0;
	$_rango_final_serie = 0;
	$errores = '';

	unset($array_numeros);unset($array_series);
///// ARRARY DE LOS NUMEROS
	$query = mysqli_query($conn, "SELECT numero_premiado_menor, monto FROM sorteos_menores_premios where sorteos_menores_id=$_sorteo and premios_menores_id in(1,3) order by premios_menores_id asc;");
	if ($query == false) {$errores .= "<br>Error en query de asignacion de premios en numeros : " . mysqli_error($conn);}

	while ($row = mysqli_fetch_array($query)) {
		$array_numeros[] = $row['numero_premiado_menor'];
		$array_monto_numeros[] = $row['monto'];}

	$numero_maestro = count(array_unique($array_numeros));
//echo "<br> numero maestro " . $numero_maestro ;
	/////// ARRAY DE LAS SERIES
	$query_series = mysqli_query($conn, "SELECT numero_premiado_menor FROM sorteos_menores_premios where sorteos_menores_id=$_sorteo and (premios_menores_id =2 or premios_menores_id >3) order by premios_menores_id asc;");
	if ($query_series == false) {$errores .= "<br>Error en la seleccion de las series premiadas : " . mysqli_error($conn);}

	while ($row_series = mysqli_fetch_array($query_series)) {$array_series[] = $row_series['numero_premiado_menor'];}
// print_r($array_series);
	$numero_derecho = $array_numeros[0];
	$numero_reves = $array_numeros[1];
	$monto_numero_derecho = $array_monto_numeros[0];
	$monto_numero_reves = $array_monto_numeros[1];
	$monto_pago_maestro = 0;

	$error_Actualiza_registros = "";
	if (mysqli_query($conn, "UPDATE ventas_distribuidor_menor a, sorteos_menores_registros b
SET a.registro=b.registro_inicial+a.serie
WHERE sorteo=$_sorteo and a.sorteo=b.id_sorteo and a.numero=b.numero and a.numero in ($numero_derecho, $numero_reves);")) {
		$error_Actualiza_registros .= "<br>Todos los registros se actualizaron bien en ventas numeros " . mysqli_affected_rows($conn);

		if (mysqli_query($conn, "UPDATE ventas_distribuidor_menor a, sorteos_menores_registros b
SET a.registro=b.registro_inicial+a.serie
WHERE sorteo=$_sorteo and a.sorteo=b.id_sorteo and a.numero=b.numero and a.serie in (" . implode(',', $array_series) . ") ")) {
			$error_Actualiza_registros .= "<br>Todos los registros se actualizaron bien en ventas " . mysqli_affected_rows($conn);
		} else {
			$error_Actualiza_registros .= '<br>Error al momento de actualizar los registos en venta de series' . mysqli_error($conn);
		}

	} else {
		$error_Actualiza_registros .= 'Error al momento de actualizar los registos en venta ' . mysqli_error($conn);
	}


	if ($numero_maestro == 2) {
////  --------- definicion de los montos a pagar
		if ($monto_numero_derecho > 30000) {
			$imptopayment_derecho = $monto_numero_derecho * 0.10;
			$netopayment_derecho = $monto_numero_derecho - $imptopayment_derecho;
		} else {
			$imptopayment_derecho = 0;
			$netopayment_derecho = $monto_numero_derecho - $imptopayment_derecho;
		}

		if ($monto_numero_reves > 30000) {
			$imptopayment_reves = $monto_numero_reves * 0.10;
			$netopayment_reves = $monto_numero_reves - $imptopayment_reves;
		} else {
			$imptopayment_reves = 0;
			$netopayment_reves = $monto_numero_reves - $imptopayment_reves;
		}

		$totalpayment_serie = 100;
		$imptopayment_serie = 0;
		$netopayment_serie = 100;

		$query_insert_numero = mysqli_query($conn, "INSERT INTO archivo_pagos_menor(transactionsellcode, sorteo, numero, serie, detalle_venta, fecha_venta, empresa_venta,  identidad_comprador, tipo_venta, nombre_comprador)
SELECT SOFTLOTTransactionCode, sorteo, numero, serie, agencia_banrural, fecha_venta, empresas_venta, identidad_venta, tipo_venta, nombre_comprador FROM ventas_distribuidor_menor WHERE sorteo=$_sorteo and numero in( " . implode(',', $array_numeros) . " ) ");

		if ($query_insert_numero == false) {
			$errores .= "<br>Error al momento de insertar los numeros : " . mysqli_error($conn);
		} else {
			$query_insert_series = mysqli_query($conn, "INSERT INTO archivo_pagos_menor(transactionsellcode, sorteo, numero, serie, detalle_venta, fecha_venta, empresa_venta, tipo_venta, identidad_comprador, nombre_comprador)
SELECT SOFTLOTTransactionCode, sorteo, numero, serie, agencia_banrural, fecha_venta, empresas_venta, tipo_venta, identidad_venta, nombre_comprador FROM ventas_distribuidor_menor WHERE sorteo=$_sorteo and numero not in( " . implode(',', $array_numeros) . " ) and serie in( " . implode(',', $array_series) . " )  ");

			if ($query_insert_series == false) {
				$errores .= "<br>Error al momento de insertar las series : " . mysqli_error($conn);
			} else {
				if (mysqli_query($conn, "UPDATE archivo_pagos_menor SET tipo_premio='PD', totalpayment='$monto_numero_derecho', imptopayment=$imptopayment_derecho, netopayment=$netopayment_derecho, estado=1, registeruser=1 WHERE sorteo=$_sorteo and numero=$numero_derecho and serie not in (" . implode(',', $array_series) . ") ") == false) {
					$errores .= "<br>Error al momento de actualizar los premios de derecho : " . mysqli_error($conn);
				} else {
					if (mysqli_query($conn, "UPDATE archivo_pagos_menor set tipo_premio='PR', totalpayment='$monto_numero_reves', imptopayment=$imptopayment_reves, netopayment=$netopayment_reves, estado=1, registeruser=1 where sorteo=$_sorteo and numero=$numero_reves and serie not in (" . implode(',', $array_series) . ") ") == false) {
						$errores .= "<br>Error al momento de actualizar los premios de reves : " . mysqli_error($conn);
					} else {
						if (mysqli_query($conn, "UPDATE archivo_pagos_menor set tipo_premio='PS', totalpayment='$totalpayment_serie', imptopayment=$imptopayment_serie, netopayment=$netopayment_serie, estado=1, registeruser=1  where sorteo=$_sorteo and numero not in( " . implode(',', $array_numeros) . " ) and serie in (" . implode(',', $array_series) . ") ") == false) {
							$errores .= "<br>Error al momento de actualizar los pagos de serie : " . mysqli_error($conn);
						} else {
							if (mysqli_query($conn, "UPDATE archivo_pagos_menor set tipo_premio='PC' where sorteo=$_sorteo and numero in (" . implode(',', $array_numeros) . ") and serie in (" . implode(',', $array_series) . ") ") == false) {
								$errores .= "<br>Error al momento de actualizar los pagos de combinacion : " . mysqli_error($conn);
							} else {
////////////////// ------------------ aCTUALIZACION DE PAGOS EN COMBINACION  ---------------------
								$query_pago_combinacion = mysqli_query($conn, "SELECT id, sorteo, numero, serie from archivo_pagos_menor where sorteo=$_sorteo and tipo_premio='PC' ");
								if (mysqli_num_rows($query_pago_combinacion) > 0) {
									while ($row_combinacion = mysqli_fetch_array($query_pago_combinacion)) {
										$_sorteo_combinacion = $row_combinacion['sorteo'];
										$_numero_combinacion = $row_combinacion['numero'];
										$_serie_combinacion = $row_combinacion['serie'];
										$_id_combinacion = $row_combinacion['id'];

										$totalpayment = consulta_valor_premio($_sorteo_combinacion, $_numero_combinacion, $_serie_combinacion);

										if ($totalpayment > 30000) {
											$estado = 1;
											$imptopayment = $totalpayment * 0.10;} else {
											$estado = 1;
											$imptopayment = 0;}

										$netopayment = $totalpayment - $imptopayment;

										if (mysqli_query($conn, "UPDATE archivo_pagos_menor SET totalpayment=$totalpayment, imptopayment=$imptopayment, netopayment=$netopayment, estado=$estado where id=$_id_combinacion ") == false) {
											$errores .= "<br>Error al momento de actualizar los montos de pago de combinacion  : " . mysqli_error($conn);
										}
									}
								} else {echo mysqli_error($conn);}
							}
						}
					}
				}
////////////////////// -------------------  ACTUALIZACION DE REGISTROS     -----------------
				if (mysqli_query($conn, "UPDATE archivo_pagos_menor a, sorteos_menores_registros b set a.registro=b.registro_inicial+a.serie where sorteo=$_sorteo and a.sorteo=b.id_sorteo and a.numero=b.numero") == false) {
					$errores .= "<br>Error al momento de actualizar los registros: " . mysqli_error($conn);
				} else {
					$actualizacion_registros = "<br> se actualizaron  : " . mysqli_affected_rows($conn) . "  registros ";
					
					if (mysqli_query($conn, " UPDATE ventas_distribuidor_menor SET registro=registro-100000  WHERE sorteo=$_sorteo and registro>=100000") == false) {
						$errores .= "<br>Error al momento de actualizar los registros que superan 100,000 : " . mysqli_error($conn);
					} else { $actualizacion_registros .= "<br>se actualizaron  : " . mysqli_affected_rows($conn) . "  registros que superan 100,000 ";}

				}
			}
		}

	} else if ($numero_maestro == 1) {
		$monto_pago_maestro = $monto_numero_derecho + $monto_numero_reves;
		if ($monto_pago_maestro > 30000) {
			$imptopayment_maestro = $monto_pago_maestro * 0.10;
			$netopayment_maestro = $monto_pago_maestro - $imptopayment_maestro;
		} else {
			$imptopayment_maestro = 0;
			$netopayment_maestro = $monto_pago_maestro - $imptopayment_maestro;
		}

		$query_insert_numero = mysqli_query($conn, " INSERT INTO archivo_pagos_menor(transactionsellcode, sorteo, numero, serie, detalle_venta, fecha_venta, empresa_venta, tipo_venta, identidad_comprador, nombre_comprador)
SELECT SOFTLOTTransactionCode, sorteo, numero, serie, agencia_banrural, fecha_venta, empresas_venta, tipo_venta, identidad_venta, nombre_comprador FROM ventas_distribuidor_menor WHERE sorteo=$_sorteo and numero=$numero_derecho ");

		if ($query_insert_numero == false) {
			$errores .= "<br>Error al momento de insertar los numeros : " . mysqli_error($conn);
		} else {
			$query_insert_series = mysqli_query($conn, " INSERT INTO archivo_pagos_menor(transactionsellcode, sorteo, numero, serie, detalle_venta, fecha_venta, empresa_venta, tipo_venta,  identidad_comprador, nombre_comprador)
SELECT SOFTLOTTransactionCode, sorteo, numero, serie, agencia_banrural, fecha_venta, empresas_venta, tipo_venta, identidad_venta, nombre_comprador FROM ventas_distribuidor_menor WHERE sorteo=$_sorteo and numero not in( " . implode(',', $array_numeros) . " ) and serie in( " . implode(',', $array_series) . " )  ");

			if ($query_insert_series == false) {
				$errores .= "<br>Error al momento de insertar los series : " . mysqli_error($conn);
			} else {
				if (mysqli_query($conn, "UPDATE archivo_pagos_menor set tipo_premio='PM', totalpayment=$monto_pago_maestro, imptopayment=$imptopayment_maestro, netopayment=$netopayment_maestro, estado=1 where sorteo=$_sorteo and numero=$numero_derecho and serie not in (" . implode(',', $array_series) . ") ") == false) {
					$errores .= "<br>Error al momento de actualizar los premios maestros : " . mysqli_error($conn);
				} else {
					if (mysqli_query($conn, "UPDATE archivo_pagos_menor SET tipo_premio='PS', totalpayment=100, imptopayment=0, netopayment=100 WHERE sorteo=$_sorteo and numero not in (" . implode(',', $array_numeros) . ") and serie in (" . implode(',', $array_series) . ") ") == false) {
						$errores .= "<br>Error al momento de actualizar los premios de serie : " . mysqli_error($conn);
					} else {
						if (mysqli_query($conn, "UPDATE archivo_pagos_menor set tipo_premio='PC' where sorteo=$_sorteo and numero in (" . implode(',', $array_numeros) . ") and serie in (" . implode(',', $array_series) . ") ") == false) {
							$errores .= "<br>Error al momento de actualizar los premios combinacion : " . mysqli_error($conn);
						} else {
							$query_pago_combinacion = mysqli_query($conn, "SELECT id, sorteo, numero, serie from archivo_pagos_menor where sorteo=$_sorteo and tipo_premio='PC' ");
							if (mysqli_num_rows($query_pago_combinacion) > 0) {
								while ($row_combinacion = mysqli_fetch_array($query_pago_combinacion)) {
									$_sorteo_combinacion = $row_combinacion['sorteo'];
									$_numero_combinacion = $row_combinacion['numero'];
									$_serie_combinacion = $row_combinacion['serie'];
									$_id_combinacion = $row_combinacion['id'];

									$totalpayment = consulta_valor_premio($_sorteo_combinacion, $_numero_combinacion, $_serie_combinacion);

									if ($totalpayment > 30000) {$imptopayment = $totalpayment * 0.10;} else { $imptopayment = 0;}

									$netopayment = $totalpayment - $imptopayment;

									if (mysqli_query($conn, "UPDATE archivo_pagos_menor SET totalpayment=$totalpayment, imptopayment=$imptopayment, netopayment=$netopayment where id=$_id_combinacion ") == false) {
										$errores .= "<br>Error al momento de actualizar los pagos de combinacion : " . mysqli_error($conn);
									}
								}
							} else { $errores .= "<br>Error al momento de consultar los pagos de serie : " . mysqli_error($conn);}
						}
					}
				}

				if (mysqli_query($conn, "UPDATE archivo_pagos_menor a, sorteos_menores_registros b set a.registro=b.registro_inicial+a.serie where sorteo=$_sorteo and a.sorteo=b.id_sorteo and a.numero=b.numero") == false) {
					$errores .= "<br>Error al momento de actualizar los registros : " . mysqli_error($conn);
				} else {
					$actualizacion_registros = "<br>se actualizaron  : " . mysqli_affected_rows($conn) . "  registros  ";
					if (mysqli_query($conn, " UPDATE ventas_distribuidor_menor SET registro=registro-100000  WHERE sorteo=$_sorteo and registro>=100000") == false) {
						$errores .= "<br>Error al momento de actualizar los registros que superan los 100,000 : " . mysqli_error($conn);
					} else {
						$actualizacion_registros .= "<br>se actualizaron  : " . mysqli_affected_rows($conn) . "  registros que superan 100,000 ";
					}
				}
			}

		}

	}

	if ($numero_maestro == 2) {
		$texto_correo = "<br><table border='1' class = 'table table-bordered'>
<tr><td Colspan='5' align='center'> Informacion generada del sorteo " . $_sorteo . "</td></tr>
<tr><td >Descripcion</td>
<td align='center'>Cantidad</td>
<td align='center'>Total</td>
<td align='center'>Impuesto</td>
<td align='center'>Neto</td></tr>";

		$query_correo_combinacion = mysqli_query($conn, "SELECT concat(numero , ' serie ', serie) combinacion, count(*) cantidad, sum(totalpayment) totalpayment, sum(imptopayment) imptopayment , sum(netopayment) netopayment  FROM `archivo_pagos_menor` WHERE sorteo=$_sorteo and tipo_premio='PC' group by combinacion order by netopayment desc");

		if ($query_correo_combinacion) {
			$cantidad_combinacion_total = 0;
			$totalpayment_combinacion_total = 0;
			$imptopayment_combinacion_total = 0;
			$netopayment_combinacion_total = 0;

			while ($row_correo_combinacion = mysqli_fetch_array($query_correo_combinacion)) {
				$descripcion_combinacion = $row_correo_combinacion['combinacion'];
				$cantidad_combinacion = $row_correo_combinacion['cantidad'];
				$totalpayment_combinacion = $row_correo_combinacion['totalpayment'];
				$imptopayment_combinacion = $row_correo_combinacion['imptopayment'];
				$netopayment_combinacion = $row_correo_combinacion['netopayment'];

				$texto_correo .= "<tr><td>" . $descripcion_combinacion . "</td>
<td align='center'>" . number_format($cantidad_combinacion) . "</td>
<td align='right'>" . number_format($totalpayment_combinacion, 2) . "</td>
<td align='right'>" . number_format($imptopayment_combinacion, 2) . "</td>
<td align='right'>" . number_format($netopayment_combinacion, 2) . "</td></tr>";

				$cantidad_combinacion_total = $cantidad_combinacion_total + $cantidad_combinacion;
				$totalpayment_combinacion_total = $totalpayment_combinacion_total + $totalpayment_combinacion;
				$imptopayment_combinacion_total = $imptopayment_combinacion_total + $imptopayment_combinacion;
				$netopayment_combinacion_total = $netopayment_combinacion_total + $netopayment_combinacion;
			}
		}

		$query_correo_derecho = mysqli_query($conn, "SELECT numero, count(*) cantidad, sum(totalpayment) totalpayment, sum(imptopayment) imptopayment , sum(netopayment) netopayment FROM `archivo_pagos_menor` WHERE sorteo=$_sorteo and tipo_premio='PD'  group by tipo_premio order by netopayment desc");

		if ($query_correo_derecho) {
			$cantidad_derecho_total = 0;
			$totalpayment_derecho_total = 0;
			$imptopayment_derecho_total = 0;
			$netopayment_derecho_total = 0;
			while ($row_correo_derecho = mysqli_fetch_array($query_correo_derecho)) {
				$descripcion_derecho = $row_correo_derecho['numero'];
				$cantidad_derecho = $row_correo_derecho['cantidad'];
				$totalpayment_derecho = $row_correo_derecho['totalpayment'];
				$imptopayment_derecho = $row_correo_derecho['imptopayment'];
				$netopayment_derecho = $row_correo_derecho['netopayment'];

				$texto_correo .= "<tr><td>" . $descripcion_derecho . "</td>
<td align='center'>" . number_format($cantidad_derecho) . "</td>
<td align='right'>" . number_format($totalpayment_derecho, 2) . "</td>
<td align='right'>" . number_format($imptopayment_derecho, 2) . "</td>
<td align='right'>" . number_format($netopayment_derecho, 2) . "</td></tr> ";

				$cantidad_derecho_total = $cantidad_derecho_total + $cantidad_derecho;
				$totalpayment_derecho_total = $totalpayment_derecho_total + $totalpayment_derecho;
				$imptopayment_derecho_total = $imptopayment_derecho_total + $imptopayment_derecho;
				$netopayment_derecho_total = $netopayment_derecho_total + $netopayment_derecho;
			}
		}

		$query_correo_reves = mysqli_query($conn, "SELECT numero, count(*) cantidad, sum(totalpayment) totalpayment, sum(imptopayment) imptopayment , sum(netopayment) netopayment FROM `archivo_pagos_menor` WHERE sorteo=$_sorteo and tipo_premio='PR'  group by tipo_premio order by netopayment desc");

		if ($query_correo_reves) {
			$cantidad_reves_total = 0;
			$totalpayment_reves_total = 0;
			$imptopayment_reves_total = 0;
			$netopayment_reves_total = 0;
			while ($row_correo_reves = mysqli_fetch_array($query_correo_reves)) {
				$descripcion_reves = $row_correo_reves['numero'];
				$cantidad_reves = $row_correo_reves['cantidad'];
				$totalpayment_reves = $row_correo_reves['totalpayment'];
				$imptopayment_reves = $row_correo_reves['imptopayment'];
				$netopayment_reves = $row_correo_reves['netopayment'];

				$texto_correo .= "<tr><td>" . $descripcion_reves . "</td>
<td align='center'>" . number_format($cantidad_reves) . "</td>
<td align='right'>" . number_format($totalpayment_reves, 2) . "</td>
<td align='right'>" . number_format($imptopayment_reves, 2) . "</td>
<td align='right'>" . number_format($netopayment_reves, 2) . "</td></tr>";

				$cantidad_reves_total = $cantidad_reves_total + $cantidad_reves;
				$totalpayment_reves_total = $totalpayment_reves_total + $totalpayment_reves;
				$imptopayment_reves_total = $imptopayment_reves_total + $imptopayment_reves;
				$netopayment_reves_total = $netopayment_reves_total + $netopayment_reves;
			}
		}

		$query_correo_serie = mysqli_query($conn, "SELECT serie, count(*) cantidad, sum(totalpayment) totalpayment, sum(imptopayment) imptopayment , sum(netopayment) netopayment FROM `archivo_pagos_menor` WHERE sorteo=$_sorteo and tipo_premio='PS' group by serie order by netopayment desc ");
		if ($query_correo_serie) {
			$cantidad_serie_total = 0;
			$totalpayment_serie_total = 0;
			$imptopayment_serie_total = 0;
			$netopayment_serie_total = 0;

			while ($row_correo_serie = mysqli_fetch_array($query_correo_serie)) {
				$descripcion_serie = $row_correo_serie['serie'];
				$cantidad_serie = $row_correo_serie['cantidad'];
				$totalpayment_serie = $row_correo_serie['totalpayment'];
				$imptopayment_serie = $row_correo_serie['imptopayment'];
				$netopayment_serie = $row_correo_serie['netopayment'];

				$texto_correo .= "<tr>
<td>" . $descripcion_serie . "</td>
<td align='center'>" . number_format($cantidad_serie) . "</td>
<td align='right'>" . number_format($totalpayment_serie, 2) . "</td>
<td align='right'>" . number_format($imptopayment_serie, 2) . "</td>
<td align='right'>" . number_format($netopayment_serie, 2) . "</td>
</tr>";

				$cantidad_serie_total = $cantidad_serie_total + $cantidad_serie;
				$totalpayment_serie_total = $totalpayment_serie_total + $totalpayment_serie;
				$imptopayment_serie_total = $imptopayment_serie_total + $imptopayment_serie;
				$netopayment_serie_total = $netopayment_serie_total + $netopayment_serie;
			}
			$cantidad_total = $cantidad_derecho_total + $cantidad_reves_total + $cantidad_combinacion_total + $cantidad_serie_total;
			$totalpayment_total = $totalpayment_derecho_total + $totalpayment_reves_total + $totalpayment_combinacion_total + $totalpayment_serie_total;
			$imptopayment_total = $imptopayment_derecho_total + $imptopayment_reves_total + $imptopayment_combinacion_total + $imptopayment_serie_total;
			$netopayment_total = $netopayment_derecho_total + $netopayment_reves_total + $netopayment_combinacion_total + $netopayment_serie_total;

		}

	} else {
		$texto_correo = "<br>
<table border='1'  class = 'table table-bordered' >
<tr>
<td Colspan='5' align='center'> Informacion generada del sorteo " . $_sorteo . "</td>
</tr>
<tr>
<td align='center'>Descripcion</td>
<td align='center'>Cantidad</td>
<td align='center'>Total</td>
<td align='center'>Impuesto</td>
<td align='center'>Neto</td>
</tr>";

		$query_correo_combinacion = mysqli_query($conn, "SELECT concat(numero , ' serie ', serie) combinacion, count(*) cantidad, sum(totalpayment) totalpayment, sum(imptopayment) imptopayment , sum(netopayment) netopayment  FROM `archivo_pagos_menor` WHERE sorteo=$_sorteo and tipo_premio='PC' group by combinacion order by netopayment desc");

		if ($query_correo_combinacion) {
			$cantidad_combinacion_total = 0;
			$totalpayment_combinacion_total = 0;
			$imptopayment_combinacion_total = 0;
			$netopayment_combinacion_total = 0;
			while ($row_correo_combinacion = mysqli_fetch_array($query_correo_combinacion)) {
				$descripcion_combinacion = $row_correo_combinacion['combinacion'];
				$cantidad_combinacion = $row_correo_combinacion['cantidad'];
				$totalpayment_combinacion = $row_correo_combinacion['totalpayment'];
				$imptopayment_combinacion = $row_correo_combinacion['imptopayment'];
				$netopayment_combinacion = $row_correo_combinacion['netopayment'];

				$texto_correo .= "<tr>
<td>" . $descripcion_combinacion . "</td>
<td align='center'>" . number_format($cantidad_combinacion) . "</td>
<td align='right'>" . number_format($totalpayment_combinacion, 2) . "</td>
<td align='right'>" . number_format($imptopayment_combinacion, 2) . "</td>
<td align='right'>" . number_format($netopayment_combinacion, 2) . "</td>
</tr>";

				$cantidad_combinacion_total = $cantidad_combinacion_total + $cantidad_combinacion;
				$totalpayment_combinacion_total = $totalpayment_combinacion_total + $totalpayment_combinacion;
				$imptopayment_combinacion_total = $imptopayment_combinacion_total + $imptopayment_combinacion;
				$netopayment_combinacion_total = $netopayment_combinacion_total + $netopayment_combinacion;
			}
		}

		$query_correo_derecho = mysqli_query($conn, "SELECT numero, count(*) cantidad, sum(totalpayment) totalpayment, sum(imptopayment) imptopayment , sum(netopayment) netopayment FROM `archivo_pagos_menor` WHERE sorteo=$_sorteo and tipo_premio='PM'  group by tipo_premio order by netopayment desc");

		if ($query_correo_derecho) {
			$cantidad_derecho_total = 0;
			$totalpayment_derecho_total = 0;
			$imptopayment_derecho_total = 0;
			$netopayment_derecho_total = 0;
			while ($row_correo_derecho = mysqli_fetch_array($query_correo_derecho)) {
				$descripcion_derecho = $row_correo_derecho['numero'];
				$cantidad_derecho = $row_correo_derecho['cantidad'];
				$totalpayment_derecho = $row_correo_derecho['totalpayment'];
				$imptopayment_derecho = $row_correo_derecho['imptopayment'];
				$netopayment_derecho = $row_correo_derecho['netopayment'];

				$texto_correo .= "<tr>
<td>" . $descripcion_derecho . "</td>
<td align='center'>" . number_format($cantidad_derecho) . "</td>
<td align='right'>" . number_format($totalpayment_derecho, 2) . "</td>
<td align='right'>" . number_format($imptopayment_derecho, 2) . "</td>
<td align='right'>" . number_format($netopayment_derecho, 2) . "</td>
</tr> ";

				$cantidad_derecho_total = $cantidad_derecho_total + $cantidad_derecho;
				$totalpayment_derecho_total = $totalpayment_derecho_total + $totalpayment_derecho;
				$imptopayment_derecho_total = $imptopayment_derecho_total + $imptopayment_derecho;
				$netopayment_derecho_total = $netopayment_derecho_total + $netopayment_derecho;
			}
		}

		$query_correo_serie = mysqli_query($conn, "SELECT serie, count(*) cantidad, sum(totalpayment) totalpayment, sum(imptopayment) imptopayment , sum(netopayment) netopayment FROM `archivo_pagos_menor` WHERE sorteo=$_sorteo and tipo_premio='PS' group by serie order by netopayment desc ");

		if ($query_correo_serie) {
			$cantidad_serie_total = 0;
			$totalpayment_serie_total = 0;
			$imptopayment_serie_total = 0;
			$netopayment_serie_total = 0;

			while ($row_correo_serie = mysqli_fetch_array($query_correo_serie)) {
				$descripcion_serie = $row_correo_serie['serie'];
				$cantidad_serie = $row_correo_serie['cantidad'];
				$totalpayment_serie = $row_correo_serie['totalpayment'];
				$imptopayment_serie = $row_correo_serie['imptopayment'];
				$netopayment_serie = $row_correo_serie['netopayment'];

				$texto_correo .= "<tr>
<td>" . $descripcion_serie . "</td>
<td align='center'>" . number_format($cantidad_serie) . "</td>
<td align='right'>" . number_format($totalpayment_serie, 2) . "</td>
<td align='right'>" . number_format($imptopayment_serie, 2) . "</td>
<td align='right'>" . number_format($netopayment_serie, 2) . "</td>
</tr>";

				$cantidad_serie_total = $cantidad_serie_total + $cantidad_serie;
				$totalpayment_serie_total = $totalpayment_serie_total + $totalpayment_serie;
				$imptopayment_serie_total = $imptopayment_serie_total + $imptopayment_serie;
				$netopayment_serie_total = $netopayment_serie_total + $netopayment_serie;
			}
			$cantidad_total = $cantidad_derecho_total + $cantidad_combinacion_total + $cantidad_serie_total;
			$totalpayment_total = $totalpayment_derecho_total + $totalpayment_combinacion_total + $totalpayment_serie_total;
			$imptopayment_total = $imptopayment_derecho_total + $imptopayment_combinacion_total + $imptopayment_serie_total;
			$netopayment_total = $netopayment_derecho_total + $netopayment_combinacion_total + $netopayment_serie_total;

		}

	}
	$text_validacion = '';
//// -----  ACTUALIZACION DE REGISTROS MAYORES A 100,000

		if (mysqli_query($conn, "UPDATE archivo_pagos_menor SET registro=registro-100000  WHERE sorteo=$_sorteo and registro>=100000;")) {
		$error_Actualiza_registros_ventas .= "<br> Se actualizaron los registrs mayores de 100,000" . mysqli_affected_rows($conn);
	}


//// ---------------- ACTUALIZACION DE TOKEN Y ESTADO  ------------------------

	if (mysqli_query($conn, "UPDATE archivo_pagos_menor set token=concat(sorteo, '--' , numero, '--', serie, '--', registro) where sorteo=$_sorteo ") == false) {
		$errores .= "Error al actualizar el token : " . mysqli_error($conn);
	} else { $text_validacion .= "<br>Se han actualizado todos los registros con token ";}

//// ---------------- VALIDACION DE NUMEROS REPETIDOS ------------------------
	$query_validacion_repetidos = mysqli_query($conn, "SELECT concat(sorteo, '-', numero, '-', serie, '-', registro) tok, count(*) conteo FROM archivo_pagos_menor WHERE sorteo=$_sorteo group by tok HAVING conteo > 1");

	if (mysqli_num_rows($query_validacion_repetidos) > 0) {
		while ($row_validacion = mysqli_fetch_array($query_validacion_repetidos)) {
			$text_validacion .= "<br> repetido en  " . $row_validacion['tok'] . " - " . $row_validacion['conteo'] . " veces. ";
		}
	} else { $text_validacion .= "<br> No existen duplicidades";}

//// ---------------- VALIDACION DE INSERCION DE REGISTROS    ------------------------
	$query_validacion_registros = mysqli_query($conn, "SELECT sorteo, numero, serie FROM `archivo_pagos_menor` WHERE sorteo=3168 and registro is null ");
	if (mysqli_num_rows($query_validacion_registros) > 0) {
		while ($row_validacion_registros = mysqli_fetch_array($query_validacion_registros)) {
			$text_validacion .= "<br>No tiene registro " . $row_validacion_registros['sorteo'] . "-- " . $row_validacion_registros['numero'] . "-" . $row_validacion_registros['serie'];
		}
	} else { $text_validacion .= "<br>Fueron actualizados todos los registros de seguridad ";}

	$texto_correo .= "<tr>
<td>Comprometido del Sorteo</td>
<td align='center'>" . number_format($cantidad_total) . "</td>
<td align='right'>" . number_format($totalpayment_total, 2) . "</td>
<td align='right'>" . number_format($imptopayment_total, 2) . "</td>
<td align='right'>" . number_format($netopayment_total, 2) . "</td>
</tr>
<tr>
<td colspan='5'> " . $actualizacion_registros . "</td>
</tr>
<tr>
<td colspan='5'> Validaciones: " . $text_validacion . "</td>
</tr>
<tr>
<td colspan='5'> " . $errores . "</td>
</tr>
</table>";

} else {
	$texto_correo = "<div class = 'alert alert-info'><i class = 'fa fa-exclamation-circle'></i> Este proceso ya fue realizado anteriormente por lo cual no puede volver a ser ejecutado.</i></div>";
}

?>


<div class = "card" style="margin-left: 15px; margin-right: 15px;">
<div class="card-header bg-success text-white">
<h3 style="text-align: center">REGISTRO DE ARCHIVOS DE PAGO MENOR <?php echo $id_sorteo; ?></h3>
</div>
<div class="card-body">

<?php
echo $texto_correo;
?>

</div>
</div>

<?php

/*
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPDebug = 1;
$mail->SMTPAuth = true;
$mail->SMTPSecure = "tls";
$mail->Host = "smtp.gmail.com";
$mail->Port = 587;
$mail->Username = "pani.informatica2016@gmail.com";
$mail->Password = "pani2016**";
$asunto = "Archivo de Pagos sorteo  " . $_sorteo;
$mensaje = "Se han ejecutado las tareas para la habilitacion de pagos de premios de loteria menor!<br>";
$mensaje .= $texto_correo;
$mail->From = "INFORMATICA PANI -- SISTEMA DE VENTAS";
$mail->From = "pani.informatica2016@gmail.com";
$mail->Subject = $asunto;
$mail->AltBody = " ";
$mail->MsgHTML($mensaje);
// $mail->AddAddress('javi0622@gmail.com','JAVIER OSEGUERA' );
//$mail->AddAddress('@gmail.com','JAVIER OSEGUERA' );
$mail->AddAddress("djjulio13@gmail.com", "Julio Lopez");
$mail->AddAddress('joseguera@pani.hn', 'JAVIER OSEGUERA');
$mail->IsHTML(true);
$mail->Send(); // Envía el correo.
 */

///////////////////////////////////// CODIGO DE REGISTRO ARCHIVO DE PAGOS MENOR ////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////// CODIGO DE INSERT PROVISION PAGOS /////////////////////////////////////////////////

/// AQUI TRAEMOS EL SORTEO   SELECT sorteo, count(serie), sum(totalpayment), sum(imptopayment), sum(netopayment) FROM `archivo_pagos_menor` group by sorteo order by sorteo desc   -- SELECT sorteo, count(serie), sum(totalpayment), sum(imptopayment), sum(netopayment) FROM `archivo_pagos_menor` group by sorteo order by sorteo desc
$_query_sorteo = mysqli_query($conn, "SELECT sorteos_menores_id
                            FROM sorteos_menores_premios
                            WHERE numero_premiado_menor  IS NOT NULL AND sorteos_menores_id = '$id_sorteo' ORDER BY sorteos_menores_id DESC LIMIT 1");

while ($row_sorteo = mysqli_fetch_array($_query_sorteo)) {$_sorteo = $row_sorteo['sorteos_menores_id'];}

$sorteo = $_sorteo;
//$sorteo=3222;
$query = mysqli_query($conn, "SELECT b.fecha_sorteo , b.vencimiento_sorteo fecha_vencimiento, count(a.serie) cantidad_billetes, sum(a.totalpayment) total_pagar, sum(a.imptopayment) impto_pagar, sum(a.netopayment) neto_pagar  FROM archivo_pagos_menor a, sorteos_menores b WHERE a.sorteo=b.id and a.sorteo=$sorteo");

if (mysqli_num_rows($query) > 0) {

	while ($row_sorteo = mysqli_fetch_array($query)) {
		$fecha_sorteo = $row_sorteo['fecha_sorteo'];
		$fecha_vencimiento = $row_sorteo['fecha_vencimiento'];
		$cantidad_billetes = $row_sorteo['cantidad_billetes'];
		$total_pagar = $row_sorteo['total_pagar'];
		$impto_pagar = $row_sorteo['impto_pagar'];
		$neto_pagar = $row_sorteo['neto_pagar'];

		$fecha_sorteo = date('d-m-Y h:i:s', strtotime($fecha_sorteo));
		$fecha_vencimiento = date('d-m-Y h:i:s', strtotime($fecha_vencimiento));
	}

	$texto_correo = "<br><table border='1' class = 'table table-bordered'>
                              <tr><td Colspan='8' align='center'> Informacion generada del sorteo " . $sorteo . "</td></tr>
                              <tr><td align='center'>Sorteo</td>
                                  <td align='center'>Fecha de sorteo</td>
                                  <td align='center'>Fecha de vencimiento</td>
                                  <td align='center'>Cantidad billetes</td>
                                  <td align='center'>Total</td>
                                  <td align='center'>Impto</td>
                                  <td align='center'>Neto</td>
                                  <td align='center'>Producto</td>
                              </tr>
                              <tr class='success'>
	                                  <td align='center'><label>" . $sorteo . "</label></td>
	                                  <td align='center'><label>" . $fecha_sorteo . "</label></td>
	                                  <td align='center'><label>" . $fecha_vencimiento . "</label></td>
	                                  <td align='center'><label>" . number_format($cantidad_billetes) . "</label></td>
	                                  <td align='right'><label> L. " . number_format($total_pagar, 2, '.', ',') . "</label></td>
	                                  <td align='right'><label> L. " . number_format($impto_pagar, 2, '.', ',') . "</label></td>
	                                  <td align='right'><label> L. " . number_format($neto_pagar, 2, '.', ',') . "  <label></td>
	                                  <td align='center'>Loteria Menor</td>
                              </tr>
                    </table>";

} else {
	$texto_correo = mysqli_error($conn);
}



$conn2 = oci_connect('cide', 'pani2017', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.15.102)(PORT=1521)))(CONNECT_DATA=(SID=dbpani)(SERVER = DEDICATED)(SERVICE_NAME = DBPANITG)))');

if ($conn2==FALSE)
{
$e = oci_error();
echo $e['message']."<br>";
trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$resultado_ERP="INSERT INTO LOT_PROVISION_PAGOS (FECHA_SORTEO, FECHA_VENCIMIENTO, SORTEO, CANTIDAD_BILLETES, TOTAL_PAGAR, IMPTO_PAGAR, NETO_PAGAR, PRODUCTO, ESTADO, ORIGEN)
VALUES ( TO_DATE('".$fecha_sorteo."','DD-MM-YYYY hh24:mi:ss'), TO_DATE('".$fecha_vencimiento."','DD-MM-YYYY hh24:mi:ss'), $sorteo , $cantidad_billetes, $total_pagar, $impto_pagar, $neto_pagar, 2, 1, 1 ) ";

$save_result=oci_parse($conn2, $resultado_ERP);

$rc=oci_execute($save_result);

if(!$rc){
$e=oci_error($save_result);
var_dump($e);
$texto_correo=$e;
}

oci_close($conn2);



?>

<br><br>

<div class="card" style="margin-left:15px; margin-right: 15px;">
  <div class="card-header bg-success text-white">
    <h3 style="text-align: center">GENERACION DE PROVISION DEL SORTEO MENOR <?php echo $id_sorteo; ?></h3>
  </div>
  <div class="card-body">
    <?php
echo $texto_correo;
?>
  </div>
</div>

<?php

/*

$mail = new PHPMailer();

try
{

$mail->IsSMTP();
//$mail->SMTPDebug = 2;
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Host = "smtp.gmail.com";
$mail->Port = 465;
$mail->Username = "pani.informatica2016@gmail.com";
$mail->Password = "pani2016**";
$asunto = "Generacion de la provision del sorteo: ".$sorteo;
$mensaje = "Resumen <br><br><br>";
$mensaje .= $texto_correo;
$mensaje .= "<br>";
$mensaje .= "<br>";
$mail->From = "pani.informatica2016@gmail.com";
$mail->FromName = "PANI-INFORMATICA";
$mail->Subject =  $asunto;
$mail->AltBody = " ";
$mail->MsgHTML($mensaje);
$mail->AddAddress("joseguera@pani.hn","Javier" );
$mail->AddAddress("djjulio13@gmail.com","Julio Lopez" );
$mail->IsHTML(true);
$mail->Send();
} catch (phpmailerException $e) {
echo $e->errorMessage();
} catch (Exception $e) {
echo $e->getMessage();
}

 */
///////////////////////////////////// CODIGO DE INSERT PROVISION PAGOS /////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////












////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////// CODIGO DE REGISTRO DE UTILIDAD   /////////////////////////////////////////////////

$filtro = 1;

$c_registro_utilidad = mysqli_query($conn, "SELECT * FROM utilidades_perdidas_sorteos WHERE id_sorteo = '$id_sorteo' ");
$flag_registro_utilidad = mysqli_num_rows($c_registro_utilidad);

if ($flag_registro_utilidad == 0) {


////////////////////////////////////////////////////
///////////// CONSULTA ASIGNACIONES ////////////////

$c_asignacion = mysqli_query($conn,"SELECT id_empresa, SUM(cantidad) as cantidad, receptor, SUM(valor_neto) + SUM(rebaja_depositario) as valor_neto FROM facturacion_menor WHERE id_sorteo = '$id_sorteo' AND id_empresa != '3' GROUP BY id_empresa ");

if ($c_asignacion === FALSE) {
	echo mysqli_error($conn);
}

$tt_asignacion = 0;
$tt_venta = 0;
$tt_devolucion = 0;
$tt_bruto = 0;
$tt_descuento = 0;
$tt_comision = 0;
$tt_aportacion = 0;
$tt_credito = 0;

$concatenado_porcentaje_venta = '';
$concatenado_asociaciones = '';

$total_general_venta = 0;
$j = 0;
while ($reg_asignacion = mysqli_fetch_array($c_asignacion)) {
	$id_entidad = $reg_asignacion['id_empresa'];
	$valor_neto = $reg_asignacion['valor_neto'];

	$precio_uni = $reg_asignacion['valor_neto'] / $reg_asignacion['cantidad'];

////////////////////////////////////////////////////
	////////////// CONSULTA DE VENTAS //////////////////

	$c_ventas = mysqli_query($conn,"SELECT precio_unitario ,SUM(aportacion) as aportacion, SUM(cantidad) as venta, SUM(utilidad_pani) as credito, SUM(total_bruto) as total_bruto, SUM(comision_bancaria) as comision , SUM(descuento) as descuento , SUM(aportacion) as aportacion FROM transaccional_ventas WHERE estado_venta = 'APROBADO' AND id_sorteo = '$id_sorteo' AND id_entidad = '$id_entidad' AND cod_producto = 2 ");

	$ob_ventas = mysqli_fetch_object($c_ventas);
	$ventas_entidad = $ob_ventas->venta;
	$credito_entidad = $ob_ventas->credito;
	$dev_entidad = $reg_asignacion['cantidad'] - $ventas_entidad;
	$venta_entidad_l = $ventas_entidad * $precio_uni;
	$precio_unitario = $ob_ventas->precio_unitario;
	$total_bruto = $ob_ventas->total_bruto;
	$descuento = $ob_ventas->descuento;
	$comision = $ob_ventas->comision;
	$aportacion = $ob_ventas->aportacion;
	$credito = $ob_ventas->credito;

////////////// CONSULTA DE VENTAS //////////////////
	////////////////////////////////////////////////////
	if ($ventas_entidad == 0) {
		$porcentaje_venta = 0;
	} else {
		$porcentaje_venta = $ventas_entidad / $reg_asignacion['cantidad'];
	}

	$porcentaje_venta = $porcentaje_venta * 100;
	$concatenado_porcentaje_venta = $concatenado_porcentaje_venta . "," . number_format($porcentaje_venta, "2");
	$concatenado_asociaciones = $concatenado_asociaciones . "," . $reg_asignacion['receptor'];

	$tt_asignacion += $reg_asignacion['cantidad'];
	$tt_venta += $ventas_entidad;
	$tt_devolucion += $dev_entidad;
	$tt_bruto += $total_bruto;
	$tt_descuento += $descuento;
	$tt_comision += $comision;
	$tt_aportacion += $aportacion;
	$tt_credito += $credito;

	$v_utilidades_perdidad[$j][0] = $id_sorteo;
	$v_utilidades_perdidad[$j][1] = $id_entidad;
	$v_utilidades_perdidad[$j][2] = $credito;
	$j++;

}

$total_general_venta += $tt_credito;
///////////// CONSULTA ASIGNACIONES ////////////////
////////////////////////////////////////////////////

/////////////// BANRURAL ASIGNACION/VENTA ///////////////
/////////////// BANRURAL ASIGNACION/VENTA ///////////////
/////////////// BANRURAL ASIGNACION/VENTA ///////////////
/////////////// BANRURAL ASIGNACION/VENTA ///////////////

if ($filtro == 1) {
////////////////////////////////////////////////////
	///////////// CONSULTA ASIGNACIONES ////////////////

	$c_asignacion = mysqli_query($conn,"SELECT id_empresa, SUM(cantidad) as cantidad, receptor, SUM(valor_neto) + SUM(rebaja_depositario) as valor_neto FROM facturacion_menor WHERE id_sorteo = '$id_sorteo' AND id_empresa = '3' GROUP BY id_empresa ");

	if ($c_asignacion === FALSE) {
		echo mysqli_error($conn);
	}

	$tt_asignacion = 0;
	$tt_venta = 0;
	$tt_devolucion = 0;
	$tt_bruto = 0;
	$tt_descuento = 0;
	$tt_comision = 0;
	$tt_aportacion = 0;
	$tt_credito = 0;

	$ob_asignacion = mysqli_fetch_object($c_asignacion);
	$id_entidad = $ob_asignacion->id_empresa;
	$cantidad_asig = $ob_asignacion->cantidad;
	$cantidad_asig = $cantidad_asig / 100;
	$receptor = $ob_asignacion->receptor;

////////////////////////////////////////////////////
	////////////// CONSULTA DE VENTAS //////////////////

	$c_ventas = mysqli_query($conn,"SELECT SUM(cantidad) as venta, cod_producto ,precio_unitario, SUM(total_bruto) as bruto ,   SUM(comision_bancaria) as comision ,   SUM(descuento) as descuento , SUM(aportacion) as aportacion , SUM(utilidad_pani) as credito FROM transaccional_ventas_general WHERE estado_venta = 'APROBADO' AND id_sorteo = '$id_sorteo' AND id_entidad = '$id_entidad' AND cod_producto IN (2,3) GROUP BY cod_producto ORDER BY cod_producto ASC ");

	if ($c_ventas === FALSE) {
		echo mysqli_error($conn);
	}

////////////// CONSULTA DE VENTAS //////////////////
	////////////////////////////////////////////////////

	$cantidad_bolsa = 0;
	if (mysqli_num_rows($c_ventas) > 0) {
		while ($r_ventas = mysqli_fetch_array($c_ventas)) {

			if ($r_ventas['cod_producto'] == 3) {
				$cantidad_bolsa = $r_ventas['venta'];
				$precio_bolsa = $r_ventas['precio_unitario'];
				$bruto_bolsa = $r_ventas['bruto'];
				$comision_bolsa = $r_ventas['comision'];
				$descuento_bolsa = $r_ventas['descuento'];
				$aportacion_bolsa = $r_ventas['aportacion'];
				$credito_bolsa = $r_ventas['credito'];

			} else {

				$cantidad_num = $r_ventas['venta'];
				$cantidad_num_b = $cantidad_num / 100;
				$precio_num = $r_ventas['precio_unitario'];
				$bruto_num = $r_ventas['bruto'];
				$comision_num = $r_ventas['comision'];
				$descuento_num = $r_ventas['descuento'];
				$aportacion_num = $r_ventas['aportacion'];
				$credito_num = $r_ventas['credito'];

			}

		}

		$ventas_banco = $cantidad_bolsa + $cantidad_num_b;
		$devolu_banco = $cantidad_asig - $ventas_banco;
		$credito_banco = $credito_bolsa + $credito_num;

		if ($ventas_banco == 0) {
			$porcentaje_venta = 0;
		} else {
			$porcentaje_venta = $ventas_banco / $cantidad_asig;
		}

		$porcentaje_venta = $porcentaje_venta * 100;
		$concatenado_porcentaje_venta = $concatenado_porcentaje_venta . "," . number_format($porcentaje_venta, '2');
		$concatenado_asociaciones = $concatenado_asociaciones . ",BANRURAL";

		$tt_asignacion = $cantidad_asig;
		$tt_venta = $ventas_banco;
		$tt_devolucion = $devolu_banco;
		$tt_bruto = $bruto_num + $bruto_bolsa;
		$tt_descuento = $descuento_num + $descuento_bolsa;
		$tt_comision = $comision_num + $comision_bolsa;
		$tt_aportacion = $aportacion_num + $aportacion_bolsa;
		$tt_credito = $credito_num + $credito_bolsa;

	}

	$v_utilidades_perdidad[$j][0] = $id_sorteo;
	$v_utilidades_perdidad[$j][1] = 3;
	$v_utilidades_perdidad[$j][2] = $tt_credito;

	$j++;

	$total_general_venta += $tt_credito;
///////////// CONSULTA ASIGNACIONES ////////////////
	////////////////////////////////////////////////////

}

/////////////////////////////////////////////////////
////////////////////// PREMIOS //////////////////////
/////////////////////////////////////////////////////

$numeros_premiados = mysqli_query($conn,"SELECT * FROM sorteos_menores_premios WHERE premios_menores_id IN (1,3)  AND sorteos_menores_id = '$id_sorteo' ");

while ($reg_premios = mysqli_fetch_array($numeros_premiados)) {
	if ($reg_premios['premios_menores_id'] == 1) {
		$derecho = $reg_premios['numero_premiado_menor'];
	} else {
		$reves = $reg_premios['numero_premiado_menor'];
	}
}

if ($derecho != "" AND $reves != "") {

	if ($filtro == 1) {
		$entidades = mysqli_query($conn,"SELECT * FROM empresas WHERE estado = 'ACTIVO' ");
	} else {
		$entidades = mysqli_query($conn,"SELECT * FROM empresas WHERE estado = 'ACTIVO' AND id != 3 ");
	}

	$tt_derecho = 0;
	$tt_reves = 0;
	$tt_derecho_p = 0;
	$tt_reves_p = 0;
	$tt_p = 0;
	$tt_p_series = 0;

///////////////////////////////////////////////////////////////
	////////////////// CONSULTA SERIES PREMIADAS //////////////////

	$series_premiadas = mysqli_query($conn," SELECT b.numero_premiado_menor,b.monto,a.tipo_serie,a.clasificacion FROM pani.premios_menores as a INNER JOIN sorteos_menores_premios AS b ON a.id = b.premios_menores_id WHERE b.sorteos_menores_id = '$id_sorteo' AND b.numero_premiado_menor IS NOT NULL AND b.premios_menores_id NOT IN (1,3) ");

	$i = 0;
	$v = 0;
	while ($reg_series_premiadas = mysqli_fetch_array($series_premiadas)) {
		$serie = $reg_series_premiadas['numero_premiado_menor'];
		$v_series[$i] = $serie;

		if ($i == 0) {
			$concat_series = $v_series[$i];
		} else {
			$concat_series = $concat_series . "," . $v_series[$i];
		}

		$i++;

		if ($reg_series_premiadas['tipo_serie'] == 'GANADOR' AND $reg_series_premiadas['clasificacion'] == 'SERIE') {

			if ($filtro == 1) {

				$verificar_serie = mysqli_query($conn," SELECT (SELECT COUNT(serie) FROM fvp_detalles_ventas_menor WHERE id_sorteo = '$id_sorteo' AND numero = '$derecho' AND serie = '$serie' AND estado_venta = 'APROBADO'  ) as conteo1 , (SELECT COUNT(serie) FROM transaccional_menor_banco_bolsas_detalle WHERE id_sorteo = '$id_sorteo' AND serie = '$serie' AND estado_venta = 'APROBADO'  ) as conteo2, (SELECT COUNT(serie) FROM transaccional_menor_banco_numeros_detalle WHERE id_sorteo = '$id_sorteo' AND numero = '$derecho' AND serie = '$serie' AND estado_venta = 'APROBADO'  ) as conteo3  ");

				$ob_verificar_serie = mysqli_fetch_object($verificar_serie);
				$c_1 = $ob_verificar_serie->conteo1;
				$c_2 = $ob_verificar_serie->conteo2;
				$c_3 = $ob_verificar_serie->conteo3;

			} else {

				$verificar_serie = mysqli_query($conn," SELECT (SELECT COUNT(serie) FROM fvp_detalles_ventas_menor WHERE id_sorteo = '$id_sorteo' AND numero = '$derecho' AND serie = '$serie' AND estado_venta = 'APROBADO'  ) as conteo1 ");

				$ob_verificar_serie = mysqli_fetch_object($verificar_serie);
				$c_1 = $ob_verificar_serie->conteo1;
				$c_2 = 0;
				$c_3 = 0;

			}

			if ($c_1 > 0 OR $c_2 > 0 OR $c_3 > 0) {

				if ($c_1 > 0) {

					$consulta_empresa_venta = mysqli_query($conn," SELECT c.nombre_empresa, c.id  FROM fvp_detalles_ventas_menor as a INNER JOIN transaccional_ventas as b INNER JOIN empresas as c ON a.cod_factura = b.cod_factura AND b.id_entidad = c.id  WHERE a.id_sorteo = '$id_sorteo' AND a.numero = '$derecho' AND a.serie = '$serie' AND a.estado_venta = 'APROBADO' ");

					$ob_empresa_venta = mysqli_fetch_object($consulta_empresa_venta);
					$empresa_premio = $ob_empresa_venta->nombre_empresa;
					$id_empresa_u = $ob_empresa_venta->id;

					$h = 0;
					while (isset($v_utilidades_perdidad[$h][0])) {

						if ($v_utilidades_perdidad[$h][1] == $id_empresa_u) {
							$v_utilidades_perdidad[$h][3] += $reg_series_premiadas['monto'];
						}

						$h++;
					}

					$v_premiaciones_series[$v][0] = "SERIE GANADORA DE DERECHO VENDIDA POR " . $empresa_premio . ": <b>" . $reg_series_premiadas['numero_premiado_menor'] . "</b>";

				} else {

					$consulta_empresa_venta = mysqli_query($conn," SELECT nombre_empresa,id FROM empresas WHERE distribuidor = 'SI' ");
					$ob_empresa_venta = mysqli_fetch_object($consulta_empresa_venta);
					$empresa_premio = $ob_empresa_venta->nombre_empresa;
					$id_empresa_u = $ob_empresa_venta->id;

					$h = 0;
					while (isset($v_utilidades_perdidad[$h][0])) {

						if ($v_utilidades_perdidad[$h][1] == $id_empresa_u) {
              if (isset($v_utilidades_perdidad[$h][3])) {
                $v_utilidades_perdidad[$h][3] += $reg_series_premiadas['monto'];
              }else{
                $v_utilidades_perdidad[$h][3] = $reg_series_premiadas['monto'];
              }
            }

						$h++;
					}

					$v_premiaciones_series[$v][0] = "SERIE GANADORA DE DERECHO VENDIDA POR " . $empresa_premio . ": <b>" . $reg_series_premiadas['numero_premiado_menor'] . "</b>";
				}

				$v_premiaciones_series[$v][1] = number_format($reg_series_premiadas['monto'], "2");
				$v_premiaciones_series[$v][2] = number_format($reg_series_premiadas['monto'], "2");

				$tt_derecho_p += $reg_series_premiadas['monto'];
				$tt_p += $reg_series_premiadas['monto'];

			}

		} elseif ($reg_series_premiadas['tipo_serie'] == 'REVES' AND $reg_series_premiadas['clasificacion'] == 'SERIE') {

			if ($filtro == 1) {

				$verificar_serie = mysqli_query($conn," SELECT (SELECT COUNT(serie) FROM fvp_detalles_ventas_menor WHERE id_sorteo = '$id_sorteo' AND numero = '$reves' AND serie = '$serie' AND estado_venta = 'APROBADO'  ) as conteo1 , (SELECT COUNT(serie) FROM transaccional_menor_banco_bolsas_detalle WHERE id_sorteo = '$id_sorteo' AND serie = '$serie' AND estado_venta = 'APROBADO'  ) as conteo2, (SELECT COUNT(serie) FROM transaccional_menor_banco_numeros_detalle WHERE id_sorteo = '$id_sorteo' AND numero = '$reves' AND serie = '$serie' AND estado_venta = 'APROBADO'  ) as conteo3  ");

				$ob_verificar_serie = mysqli_fetch_object($verificar_serie);
				$c_1 = $ob_verificar_serie->conteo1;
				$c_2 = $ob_verificar_serie->conteo2;
				$c_3 = $ob_verificar_serie->conteo3;

			} else {

				$verificar_serie = mysqli_query($conn," SELECT (SELECT COUNT(serie) FROM fvp_detalles_ventas_menor WHERE id_sorteo = '$id_sorteo' AND numero = '$reves' AND serie = '$serie' AND estado_venta = 'APROBADO'  ) as conteo1  ");

				$ob_verificar_serie = mysqli_fetch_object($verificar_serie);
				$c_1 = $ob_verificar_serie->conteo1;
				$c_2 = 0;
				$c_3 = 0;

			}

			if ($c_1 > 0 OR $c_2 > 0 OR $c_3 > 0) {

				if ($c_1 > 0) {

					$consulta_empresa_venta = mysqli_query($conn," SELECT c.nombre_empresa, c.id  FROM fvp_detalles_ventas_menor as a INNER JOIN transaccional_ventas as b INNER JOIN empresas as c ON a.cod_factura = b.cod_factura AND b.id_entidad = c.id  WHERE a.id_sorteo = '$id_sorteo' AND a.numero = '$reves' AND a.serie = '$serie' AND a.estado_venta = 'APROBADO' ");
					$ob_empresa_venta = mysqli_fetch_object($consulta_empresa_venta);
					$empresa_premio = $ob_empresa_venta->nombre_empresa;
					$id_empresa_u = $ob_empresa_venta->id;

					$v_premiaciones_series[$v][0] = "SERIE GANADORA DE REVES VENDIDA POR " . $empresa_premio . ": <b>" . $reg_series_premiadas['numero_premiado_menor'] . "</b>";

					$h = 0;
					while (isset($v_utilidades_perdidad[$h][0])) {

						if ($v_utilidades_perdidad[$h][1] == $id_empresa_u) {
							$v_utilidades_perdidad[$h][3] += $reg_series_premiadas['monto'];
						}

						$h++;
					}

				} else {

					$consulta_empresa_venta = mysqli_query($conn," SELECT nombre_empresa, id FROM empresas WHERE distribuidor = 'SI' ");
					$ob_empresa_venta = mysqli_fetch_object($consulta_empresa_venta);
					$empresa_premio = $ob_empresa_venta->nombre_empresa;
					$id_empresa_u = $ob_empresa_venta->id;

					$h = 0;
					while (isset($v_utilidades_perdidad[$h][0])) {

						if ($v_utilidades_perdidad[$h][1] == $id_empresa_u) {
							$v_utilidades_perdidad[$h][3] += $reg_series_premiadas['monto'];
						}

						$h++;
					}

					$v_premiaciones_series[$v][0] = "SERIE GANADORA DE REVES VENDIDA POR " . $empresa_premio . ": <b>" . $reg_series_premiadas['numero_premiado_menor'] . "</b>";

				}

				$v_premiaciones_series[$v][1] = number_format($reg_series_premiadas['monto'], "2");
				$v_premiaciones_series[$v][2] = number_format($reg_series_premiadas['monto'], "2");

				$tt_reves_p += $reg_series_premiadas['monto'];
				$tt_p += $reg_series_premiadas['monto'];

			}

		}

		$v++;
	}

///////////////// SERIES PREMIADAS ////////////////////////////
	///////////////////////////////////////////////////////////////

	$conteo_derecho = 0;
	$conteo_reves = 0;

	while ($reg_entidades = mysqli_fetch_array($entidades)) {
		$id_entidad = $reg_entidades['id'];
		$mombre_entidad = $reg_entidades['nombre_empresa'];

		if ($id_entidad != 3) {

			$venta_derecho = mysqli_query($conn,"SELECT count(a.numero) as conteo FROM fvp_detalles_ventas_menor as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE b.id_entidad = '$id_entidad' AND a.estado_venta = 'APROBADO' AND a.numero = '$derecho'  AND a.id_sorteo = '$id_sorteo' ");
			$ob_venta_derecho = mysqli_fetch_object($venta_derecho);
			$conteo_derecho = $ob_venta_derecho->conteo;

			$venta_reves = mysqli_query($conn,"SELECT count(a.numero) as conteo FROM fvp_detalles_ventas_menor as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE b.id_entidad = '$id_entidad' AND a.estado_venta = 'APROBADO' AND a.numero = '$reves' AND a.id_sorteo = '$id_sorteo'  ");
			$ob_venta_reves = mysqli_fetch_object($venta_reves);
			$conteo_reves = $ob_venta_reves->conteo;

		} else {

			$venta_derecho = mysqli_query($conn,"SELECT count(a.numero) as conteo FROM transaccional_menor_banco_numeros_detalle as a WHERE a.estado_venta = 'APROBADO' AND a.numero = '$derecho'  AND a.id_sorteo = '$id_sorteo' ");
			$ob_venta_derecho = mysqli_fetch_object($venta_derecho);
			$conteo_derecho = $ob_venta_derecho->conteo;
			$conteo_derecho += $cantidad_bolsa;

			$venta_reves = mysqli_query($conn,"SELECT count(a.numero) as conteo FROM transaccional_menor_banco_numeros_detalle as a WHERE a.estado_venta = 'APROBADO' AND a.numero = '$reves'  AND a.id_sorteo = '$id_sorteo' ");
			$ob_venta_reves = mysqli_fetch_object($venta_reves);
			$conteo_reves = $ob_venta_reves->conteo;
			$conteo_reves += $cantidad_bolsa;

		}

///////////////////////////////////////////////////////
		//////////////// VERIFICACION SERIE VENDIDA ///////////
		$cant_pago_series = 0;
		$c_1 = 0;
		$c_2 = 0;
		$c_3 = 0;

		$i = 0;
		while (isset($v_series[$i])) {

			$serie = $v_series[$i];

			if ($id_entidad == 3) {

				$consulta_venta_serie = mysqli_query($conn," SELECT (SELECT COUNT(serie) FROM transaccional_menor_banco_bolsas_detalle as a INNER JOIN transaccional_ventas_general as b ON a.cod_factura = b.cod_factura_recaudador WHERE a.id_sorteo = '$id_sorteo' AND a.serie IN ($concat_series) AND a.estado_venta = 'APROBADO' AND b.id_entidad = '$id_entidad'  ) as conteo2, (SELECT COUNT(serie) FROM transaccional_menor_banco_numeros_detalle as a INNER JOIN transaccional_ventas_general as b ON a.cod_factura = b.cod_factura_recaudador WHERE a.id_sorteo = '$id_sorteo' AND a.numero NOT IN('$derecho','$reves') AND a.serie IN ($concat_series) AND a.estado_venta = 'APROBADO' AND b.id_entidad = '$id_entidad' ) as conteo3  ");

				$ob_cantidad_serie = mysqli_fetch_object($consulta_venta_serie);
				$c_1 = 0;
				$c_2 = $ob_cantidad_serie->conteo2;
				$c_3 = $ob_cantidad_serie->conteo3;

			} else {

				$consulta_venta_serie = mysqli_query($conn," SELECT COUNT(serie) as conteo1 FROM fvp_detalles_ventas_menor as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE a.id_sorteo = '$id_sorteo' AND a.numero NOT IN ('$derecho','$reves') AND a.serie IN ($concat_series) AND a.estado_venta = 'APROBADO' AND b.id_entidad = '$id_entidad'   ");

				$ob_cantidad_serie = mysqli_fetch_object($consulta_venta_serie);
				$c_1 = $ob_cantidad_serie->conteo1;
				$c_2 = 0;
				$c_3 = 0;

			}

			if ($c_1 > 0 OR $c_2 > 0 OR $c_3 > 0) {

				if ($derecho == $reves) {
					$c_2 *= 99;
				} else {
					$c_2 *= 98;
				}

				$cant_pago_series = $c_1 + $c_2 + $c_3;

			}

			$i++;
		}

		$pago_derecho = $conteo_derecho * 1000;
		$pago_reves = $conteo_reves * 100;
		$monto_pago_series = $cant_pago_series * 100;
		$total_pago = $pago_derecho + $pago_reves + $monto_pago_series;

//////////////// VERIFICACION SERIE VENDIDA ///////////
		///////////////////////////////////////////////////////

		$h = 0;
		while (isset($v_utilidades_perdidad[$h][0])) {

			if ($v_utilidades_perdidad[$h][1] == $id_entidad) {

//echo "<br>".$id_entidad." PAGO TOTAL ".$total_pago." ".$v_utilidades_perdidad[$h][3];

				$v_utilidades_perdidad[$h][3] += $total_pago;
			}

			$h++;
		}

		$tt_derecho += $conteo_derecho;
		$tt_reves += $conteo_reves;
		$tt_derecho_p += $pago_derecho;
		$tt_reves_p += $pago_reves;
		$tt_p += $total_pago;
		$tt_p_series += $monto_pago_series;
	}

	$v = 0;
	while (isset($v_premiaciones_series[$v][0])) {
		$v++;
	}

	$utilidad_perdida = $total_general_venta - $tt_p;

}

if (!isset($tt_p)) {
	$tt_p = 0;
	$utilidad_perdida = $total_general_venta;
}

$h = 0;
while (isset($v_utilidades_perdidad[$h][0])) {

	$v_utilidades_perdidad[$h][4] = $v_utilidades_perdidad[$h][2] - $v_utilidades_perdidad[$h][3];
	$v_utilidades_perdidad[$h][5] = 2;

	$id_sorteo = $v_utilidades_perdidad[$h][0];
	$id_entidad = $v_utilidades_perdidad[$h][1];
	$credito_pani = $v_utilidades_perdidad[$h][2];
	$provision_pago = $v_utilidades_perdidad[$h][3];
	$utilidad_perdida = $v_utilidades_perdidad[$h][4];
	$tipo_loteria = $v_utilidades_perdidad[$h][5];

	$reg_bd = mysqli_query($conn,"INSERT INTO  utilidades_perdidas_sorteos (id_sorteo, id_entidad, credito_pani, provision_pago, utilidad_perdida, tipo_loteria) VALUES ('$id_sorteo', '$id_entidad', '$credito_pani', '$provision_pago', '$utilidad_perdida', '$tipo_loteria') ");

	if ($reg_bd === FALSE) {
		echo mysqli_error($conn);
	}

	$h++;
}

mysqli_query($conn,"UPDATE sorteos_menores SET estado_sorteo = 'CAPTURADO' WHERE id = '$id_sorteo' ");

$msg = "<div class = 'alert alert-info'><i class = 'fa fa-exclamation-circle'></i> REGUISTRO DE UTLIDADES REALIZADO CORRECTAMENTE <br><br><i class = 'fa fa-exclamation-circle'></i> CAPTURA DE SORTEO " . $id_sorteo . " FINALIZADA CORRECTAMENTE</div>";


}else{

  $msg = "<div class = 'alert alert-info'><i class = 'fa fa-exclamation-circle'></i> Este proceso ya fue realizado anteriormente por lo cual no puede volver a ser ejecutado.</div>";


}


?>

<div class="card" style="margin-left:15px; margin-right: 15px;">
  <div class = 'card-header bg-success text-white'>
    <h3 style="text-align: center">REGISTRO DE UTILIDADES SORTEO <?php echo $id_sorteo; ?></h3>
  </div>
  <div class="card-body">
<?php
echo $msg;
?>
  </div>
</div>

<?php


///////////////////////////////////// CODIGO DE REGISTRO DE UTILIDAD   /////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
