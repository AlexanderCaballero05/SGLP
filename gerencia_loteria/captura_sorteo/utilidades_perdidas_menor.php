<?php
require '../../template/header.php';
//$id_sorteo = $_GET['i_s'];
$id_sorteo = 3279;
$filtro	   = 1;



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
