<?php

require "../../template/header.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$sorteos_menores_1 = mysqli_query($conn, "SELECT a.id_sorteo, b.fecha_sorteo FROM empresas_estado_venta as a INNER JOIN sorteos_menores as b ON a.id_sorteo = b.id WHERE a.estado_venta = 'F' AND a.cod_producto != 1 GROUP BY a.id_sorteo ORDER BY a.id_sorteo DESC   ");

?>




<form method="POST">

<section style="background-color:#ededed;">
<br>
<h2 align="center" style="color:black;" >
  <b>LOTERIA MENOR PREMIADA Y NO VENDIDA
</b></h2>
<br>
</section>


<a class="btn btn-secondary" id="non-printable" style="width:100%" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
Selección de parametros
</a>

<div  class="collapse" style = "width:100%"  id="collapse1" align="center">
<div class="card" style="width: 50%">
<div class="card-body">


<div class="input-group" style="margin:10px 0px 10px 0px;">
<div class="input-group-prepend"><div class="input-group-text">Sorteo: </div></div>
<select name="sorteo_i" class="form-control" style="margin-bottom: 10px">
<?php
while ($reg_sorteo = mysqli_fetch_array($sorteos_menores_1)) {
	echo "<option value = '" . $reg_sorteo['id_sorteo'] . "'>" . $reg_sorteo['id_sorteo'] . " / " . $reg_sorteo['fecha_sorteo'] . "</option>";
}
?>
</select>

<input type="submit" name="seleccionar" class="btn btn-primary" value="Seleccionar" style="width: 100%">

</div>





<script>
$('#fecha_inicial').datepicker({
locale: 'es-es',
format: 'yyyy-mm-dd',
uiLibrary: 'bootstrap4'
});

$('#fecha_final').datepicker({
locale: 'es-es',
format: 'yyyy-mm-dd',
uiLibrary: 'bootstrap4'
});

</script>


</div>
</div>
</div>


</form>


<?php

if (isset($_POST['seleccionar'])) {

	$sorteo = $_POST['sorteo_i'];

	$c_info_sorteo = mysqli_query($conn, "SELECT series FROM sorteos_menores WHERE id = '$sorteo' ");
	$ob_info_sorteo = mysqli_fetch_object($c_info_sorteo);
	$series_producidas = $ob_info_sorteo->series;
	$ultima_bolsa = $series_producidas - 1;

	$consulta_numeros = mysqli_query($conn, " SELECT numero_premiado_menor FROM sorteos_menores_premios WHERE premios_menores_id = 1  AND sorteos_menores_id = '$sorteo' ");
	$ob_numeros_premiados = mysqli_fetch_object($consulta_numeros);
	$derecho = $ob_numeros_premiados->numero_premiado_menor;

	$consulta_numeros = mysqli_query($conn, " SELECT numero_premiado_menor FROM sorteos_menores_premios WHERE premios_menores_id = 3  AND sorteos_menores_id = '$sorteo' ");
	$ob_numeros_premiados = mysqli_fetch_object($consulta_numeros);
	$reves = $ob_numeros_premiados->numero_premiado_menor;

	$consulta_series = mysqli_query($conn, " SELECT numero_premiado_menor, premios_menores_id, monto FROM sorteos_menores_premios WHERE premios_menores_id != 1 AND premios_menores_id != 3 AND sorteos_menores_id = '$sorteo' ORDER BY premios_menores_id ASC ");

	$i = 0;

	$concat_series_premiadas = "";

	$conteo_asignado_banco = 0;
	$conteo_asignado_fvp = 0;
	while ($reg_series = mysqli_fetch_array($consulta_series)) {
		$v_serie_premiada[$i] = $reg_series['numero_premiado_menor'];
		$v_tipo_serie[$i] = $reg_series['premios_menores_id'];
		$v_monto_premio[$i] = $reg_series['monto'];
		$concat_series_premiadas .= $reg_series['numero_premiado_menor'] . ",";

		$consulta_serie = $v_serie_premiada[$i];

		$c_distribucion_bolsa = mysqli_query($conn, "SELECT COUNT(cantidad) as conteo, id_empresa FROM menor_seccionales_bolsas WHERE serie_inicial <= '$consulta_serie' AND serie_final >= '$consulta_serie' AND id_sorteo = '$sorteo' ");

		$ob_distribucion_bolsa = mysqli_fetch_object($c_distribucion_bolsa);
		$indicador_bolsa = $ob_distribucion_bolsa->conteo;
		$indicador_entidad = $ob_distribucion_bolsa->id_empresa;

		if ($indicador_bolsa == 0) {
			$c_distribucion_numero = mysqli_query($conn, "SELECT COUNT(cantidad) as conteo, id_empresa FROM menor_seccionales_numeros WHERE serie_inicial <= '$consulta_serie' AND serie_final >= '$consulta_serie' AND id_sorteo = '$sorteo' ");
			$ob_distribucion_numero = mysqli_fetch_object($c_distribucion_numero);
			$indicador_numero = $ob_distribucion_numero->conteo;
			$indicador_entidad = $ob_distribucion_numero->id_empresa;

			if ($indicador_numero == 0) {
				$v_asignado[$i] = 0;
			} else {
				$v_asignado[$i] = $indicador_entidad;
			}

		} else {
			$v_asignado[$i] = $indicador_entidad;
		}

		if ($v_asignado[$i] == 3) {
			$conteo_asignado_banco += 1;

			$c_derecho_serie_venta = mysqli_query($conn, "SELECT COUNT(serie) as conteo_venta FROM transaccional_menor_banco_bolsas_detalle WHERE id_sorteo = '$sorteo' AND estado_venta = 'APROBADO' AND serie = '$consulta_serie'   ");
			$ob_derecho_serie_venta = mysqli_fetch_object($c_derecho_serie_venta);
			$indicador_derecho_serie = $ob_derecho_serie_venta->conteo_venta;

			if ($indicador_derecho_serie == 0) {

				$c_derecho_serie_venta = mysqli_query($conn, "SELECT COUNT(serie) as conteo_venta FROM transaccional_menor_banco_numeros_detalle WHERE id_sorteo = '$sorteo' AND estado_venta = 'APROBADO' AND serie = '$consulta_serie' AND numero = '$derecho'   ");
				$ob_derecho_serie_venta = mysqli_fetch_object($c_derecho_serie_venta);
				$indicador_derecho_serie = $ob_derecho_serie_venta->conteo_venta;

				if ($indicador_derecho_serie == 0) {
					$v_vendido_derecho[$i] = 0;
					$v_tipo_venta_derecho[$i] = 'numero';
				} else {
					$v_vendido_derecho[$i] = 1;
					$v_tipo_venta_derecho[$i] = 'numero';
				}

				$c_reves_serie_venta = mysqli_query($conn, "SELECT COUNT(serie) as conteo_venta FROM transaccional_menor_banco_numeros_detalle WHERE id_sorteo = '$sorteo' AND estado_venta = 'APROBADO' AND serie = '$consulta_serie' AND numero = '$reves'   ");
				$ob_reves_serie_venta = mysqli_fetch_object($c_reves_serie_venta);
				$indicador_reves_serie = $ob_reves_serie_venta->conteo_venta;

				if ($indicador_reves_serie == 0) {
					$v_vendido_reves[$i] = 0;
					$v_tipo_venta_reves[$i] = 'numero';
				} else {
					$v_vendido_reves[$i] = 1;
					$v_tipo_venta_reves[$i] = 'numero';
				}

			} else {

				$v_vendido_derecho[$i] = 1;
				$v_vendido_reves[$i] = 1;
				$v_tipo_venta_derecho[$i] = 'bolsa';
				$v_tipo_venta_reves[$i] = 'bolsa';

			}

		} else {
			$conteo_asignado_fvp += 1;

			$c_derecho_serie_venta = mysqli_query($conn, "SELECT COUNT(serie) as conteo_venta FROM fvp_detalles_ventas_menor WHERE id_sorteo = '$sorteo' AND estado_venta = 'APROBADO' AND serie = '$consulta_serie' AND numero = '$derecho'  ");
			$ob_derecho_serie_venta = mysqli_fetch_object($c_derecho_serie_venta);
			$indicador_derecho_serie = $ob_derecho_serie_venta->conteo_venta;

			$c_reves_serie_venta = mysqli_query($conn, "SELECT COUNT(serie) as conteo_venta FROM fvp_detalles_ventas_menor WHERE id_sorteo = '$sorteo' AND estado_venta = 'APROBADO' AND serie = '$consulta_serie' AND numero = '$reves'   ");
			$ob_reves_serie_venta = mysqli_fetch_object($c_reves_serie_venta);
			$indicador_reves_serie = $ob_reves_serie_venta->conteo_venta;

			if ($indicador_derecho_serie == 0) {
				$v_vendido_derecho[$i] = 0;
				$v_tipo_venta_derecho[$i] = 'numero';
			} else {
				$v_vendido_derecho[$i] = 1;
				$v_tipo_venta_derecho[$i] = 'numero';
			}

			if ($indicador_reves_serie == 0) {
				$v_vendido_reves[$i] = 0;
				$v_tipo_venta_reves[$i] = 'numero';
			} else {
				$v_vendido_reves[$i] = 1;
				$v_tipo_venta_reves[$i] = 'numero';
			}

		}

		$i++;
	}

	$concat_series_premiadas = substr($concat_series_premiadas, 0, -1);

	?>

<br>

<div class="card" style="margin-left: 10px; margin-right: 10px;">
	<div class="card-header bg-primary text-white">
		<h3 align="center">SORTEO <?php echo $sorteo; ?> </h3>
	</div>
	<div class="card-body">


<div class="">

<h3 class="alert alert-info" style=" text-align: center">BANRURAL</h3>




<table class="table table-bordered" width="100%" style="font-size: 12px" >
	<tr>
		<th colspan="6">NO VENDIDO PREMIADO SOLO POR NUMERO</th>
	</tr>
	<tr>
		<th>DESCRIPCION</th>
		<th>NUMERO</th>
		<th>ASIGNADO</th>
		<th>NO VENDIDO</th>
		<th>MONTO PREMIADO</th>
	</tr>

<?php

	$c_asignado_bolsas = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad_bolsas FROM menor_seccionales_bolsas WHERE id_sorteo = '$sorteo' AND id_empresa = 3 ");
	$ob_asignado_bolsas = mysqli_fetch_object($c_asignado_bolsas);
	$asignado_bolsas = $ob_asignado_bolsas->cantidad_bolsas;

	$c_asignado_numeros = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad_numeros FROM menor_seccionales_numeros WHERE id_sorteo = '$sorteo' AND id_empresa = 3 AND numero = '$derecho' ");
	$ob_asignado_numeros = mysqli_fetch_object($c_asignado_numeros);
	$asignado_numeros = $ob_asignado_numeros->cantidad_numeros;

	$asignado_derecho = $asignado_bolsas + $asignado_numeros;

	$c_venta_bolsas = mysqli_query($conn, "SELECT COUNT(DISTINCT(serie)) as bolsas_vendidas FROM transaccional_menor_banco_bolsas_detalle WHERE id_sorteo = '$sorteo' AND estado_venta = 'APROBADO'  ");
	$ob_venta_bolsas = mysqli_fetch_object($c_venta_bolsas);
	$bolsas_vendidas = $ob_venta_bolsas->bolsas_vendidas;

	$c_venta_numero = mysqli_query($conn, "SELECT COUNT(numero) as numeros_venidos FROM transaccional_menor_banco_numeros_detalle WHERE id_sorteo = '$sorteo' AND estado_venta = 'APROBADO' AND numero = '$derecho'  ");
	$ob_venta_numero = mysqli_fetch_object($c_venta_numero);
	$numeros_venidos = $ob_venta_numero->numeros_venidos;

	$no_vendido_derecho = $asignado_derecho - $bolsas_vendidas - $numeros_venidos;

	$i = 0;
	while (isset($v_serie_premiada[$i])) {
		if ($v_asignado[$i] == 3) {
			if ($v_vendido_derecho[$i] != 1) {
				$no_vendido_derecho--;
			}
		}

		$i++;
	}

	if ($no_vendido_derecho < 0) {
		$no_vendido_derecho = 0;
	}

	$monto_no_vendido_derecho = $no_vendido_derecho * 1000;

	?>

	<tr>
		<td>Derecho</td>
		<td><?php echo $derecho ?></td>
		<td><?php echo number_format($asignado_derecho) ?></td>
		<td><?php echo number_format($no_vendido_derecho) ?></td>
		<td><?php echo number_format($monto_no_vendido_derecho, 2) ?></td>
	</tr>

<?php

	$c_asignado_numeros = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad_numeros FROM menor_seccionales_numeros WHERE id_sorteo = '$sorteo' AND id_empresa = 3 AND numero = '$reves' ");
	$ob_asignado_numeros = mysqli_fetch_object($c_asignado_numeros);
	$asignado_numeros = $ob_asignado_numeros->cantidad_numeros;

	$asignado_reves = $asignado_bolsas + $asignado_numeros;

	$c_venta_numero = mysqli_query($conn, "SELECT COUNT(numero) as numeros_venidos FROM transaccional_menor_banco_numeros_detalle WHERE id_sorteo = '$sorteo' AND estado_venta = 'APROBADO' AND numero = '$reves'  ");
	$ob_venta_numero = mysqli_fetch_object($c_venta_numero);
	$numeros_venidos = $ob_venta_numero->numeros_venidos;

	$no_vendido_reves = $asignado_reves - $bolsas_vendidas - $numeros_venidos;

	$i = 0;
	while (isset($v_serie_premiada[$i])) {
		if ($v_asignado[$i] == 3) {
			if ($v_vendido_reves[$i] != 1) {
				$no_vendido_reves--;
			}
		}

		$i++;
	}

	if ($no_vendido_reves < 0) {
		$no_vendido_reves = 0;
	}

	$monto_no_vendido_reves = $no_vendido_reves * 100;

	?>

	<tr>
		<td>Reves</td>
		<td><?php echo $reves ?></td>
		<td><?php echo number_format($asignado_reves) ?></td>
		<td><?php echo number_format($no_vendido_reves) ?></td>
		<td><?php echo number_format($monto_no_vendido_reves, 2) ?></td>
	</tr>
</table>


<table width="100%">
<tr>
	<td width="49%" valign="top">

<table class="table table-bordered" width="100%" style="font-size: 12px" >
	<tr>
		<th colspan="3">NO VENDIDO PREMIADO SOLO POR SERIE</th>
	</tr>
	<tr>
		<th>SERIE</th>
		<th>CANTIDAD</th>
		<th>MONTO PREMIADO</th>
	</tr>


<?php
$i = 0;
	$total_serie = 0;

	while (isset($v_serie_premiada[$i])) {

		if ($v_asignado[$i] == 3) {

			$c_vendido_serie = mysqli_query($conn, "SELECT COUNT(serie) vendido_serie FROM transaccional_menor_banco_bolsas_detalle WHERE id_sorteo = '$sorteo' AND serie = '$v_serie_premiada[$i]' AND estado_venta = 'APROBADO' ");
			$ob_vendido_serie = mysqli_fetch_object($c_vendido_serie);
			$vendido_serie_bolsa = $ob_vendido_serie->vendido_serie;

			$c_vendido_serie = mysqli_query($conn, "SELECT COUNT(serie) vendido_serie FROM transaccional_menor_banco_numeros_detalle WHERE id_sorteo = '$sorteo' AND serie = '$v_serie_premiada[$i]' AND estado_venta = 'APROBADO' ");
			$ob_vendido_serie = mysqli_fetch_object($c_vendido_serie);
			$vendido_serie_numero = $ob_vendido_serie->vendido_serie;

			if ($vendido_serie_bolsa == 0 AND $vendido_serie_numero == 0) {
				$no_vendido_serie = 98;
			} else {

				if ($vendido_serie_bolsa != 0) {
					$no_vendido_serie = 0;
				}

				if ($vendido_serie_numero != 0 AND $vendido_serie_bolsa == 0) {
					$no_vendido_serie = 100 - $vendido_serie_numero;
					if ($v_vendido_derecho[$i] == 0) {
						$no_vendido_serie -= 1;
					}
					if ($v_vendido_reves[$i] == 0) {
						$no_vendido_serie -= 1;
					}
				}

			}

			if ($no_vendido_serie < 0) {
				$no_vendido_serie = 0;
			}

			$no_vendido_serie_monto = $no_vendido_serie * 100;

			$total_serie += $no_vendido_serie_monto;

			?>

<tr>
	<td><?php echo $v_serie_premiada[$i];
			if ($v_serie_premiada[$i] > $ultima_bolsa) {
				echo " (EXTRA)";
			} ?></td>
	<td><?php echo number_format($no_vendido_serie) ?></td>
	<td><?php echo number_format($no_vendido_serie_monto, 2) ?></td>
</tr>

<?php

		}

		$i++;
	}

	?>
<tr><th colspan="2">Total</th><th ><?php echo number_format($total_serie, 2); ?></th></tr>
</table>


	</td>
	<td width="2%"></td>
	<td width="49%">


<table class="table table-bordered" width="100%" style="font-size: 12px" >
	<tr>
		<th colspan="3">NO VENDIDO PREMIADO POR NUMERO Y SERIE</th>
	</tr>
	<tr>
		<th>NUMERO</th>
		<th>SERIE</th>
		<th>MONTO PREMIADO</th>
	</tr>

<?php
$i = 0;
	while (isset($v_serie_premiada[$i])) {

		if ($v_asignado[$i] == 3) {

			if ($derecho != $reves) {

				if ($v_tipo_serie[$i] == 2) {
					if ($v_vendido_derecho[$i] == 0) {
						$monto_pago = $v_monto_premio[$i] + 1000;
					} else {
						$monto_pago = 0;
					}
				} else {
					if ($v_vendido_derecho[$i] == 0) {
						$monto_pago = 1100;
					} else {
						$monto_pago = 0;
					}
				}

			} else {

				if ($v_vendido_derecho[$i] == 0) {
					$monto_pago = $v_monto_premio[$i] + 1100;
				} else {
					$monto_pago = 0;
				}

			}

			?>

<tr>
	<td><?php echo $derecho ?></td>
	<td><?php echo $v_serie_premiada[$i];
			if ($v_serie_premiada[$i] > $ultima_bolsa) {
				echo " (EXTRA)";
			} ?></td>
	<td><?php echo number_format($monto_pago, 2) ?></td>
</tr>

<?php

		}

		$i++;
	}

	if ($derecho != $reves) {

		$i = 0;
		while (isset($v_serie_premiada[$i])) {

			if ($v_asignado[$i] == 3) {

				if ($v_tipo_serie[$i] != 2) {
					if ($v_vendido_reves[$i] == 0) {
						$monto_pago = $v_monto_premio[$i] + 100;
					} else {
						$monto_pago = 0;
					}
				} else {
					if ($v_vendido_reves[$i] == 0) {
						$monto_pago = 200;
					} else {
						$monto_pago = 0;
					}
				}

				?>

<tr>
	<td><?php echo $reves ?></td>
	<td><?php echo $v_serie_premiada[$i];
				if ($v_serie_premiada[$i] > $ultima_bolsa) {
					echo " (EXTRA)";
				} ?></td>
	<td><?php echo number_format($monto_pago, 2) ?></td>
</tr>

<?php
}

			$i++;
		}

	}

	?>


</table>

	</td>

</tr>

</table>


</div>









<br>



<!-- °°°°°°°°°°°°°°°°°°°°°°°°° OTRAS ENTIDADES °°°°°°°°°°°°°°°°°°°°°°°°°° -->
<!-- °°°°°°°°°°°°°°°°°°°°°°°°° OTRAS ENTIDADES °°°°°°°°°°°°°°°°°°°°°°°°°° -->
<!-- °°°°°°°°°°°°°°°°°°°°°°°°° OTRAS ENTIDADES °°°°°°°°°°°°°°°°°°°°°°°°°° -->
<!-- °°°°°°°°°°°°°°°°°°°°°°°°° OTRAS ENTIDADES °°°°°°°°°°°°°°°°°°°°°°°°°° -->




<div class="">

<h3 class="alert alert-success" style="align: center">FVP Y REGIONALES</h3>


<table class="table table-bordered" width="100%" style="font-size: 12px" >
	<tr>
		<th colspan="7">NO VENDIDO PREMIADO SOLO POR NUMERO</th>
	</tr>
	<tr>
		<th>DESCRIPCION</th>
		<th>NUMERO</th>
		<th>ASIGNADO</th>
		<th>NO VENDIDO NORMAL</th>
		<th>NO VENDIDO EXTRA</th>
		<th>NO VENDIDO TOTAL</th>
		<th>MONTO PREMIADO</th>
	</tr>


<?php

	$c_asignado_bolsas = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad_bolsas FROM menor_seccionales_bolsas WHERE id_sorteo = '$sorteo' AND id_empresa != 3 ");
	$ob_asignado_bolsas = mysqli_fetch_object($c_asignado_bolsas);
	$asignado_bolsas = $ob_asignado_bolsas->cantidad_bolsas;

	$c_asignado_numeros = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad_numeros FROM menor_seccionales_numeros WHERE id_sorteo = '$sorteo' AND id_empresa != 3 AND numero = '$derecho' ");
	$ob_asignado_numeros = mysqli_fetch_object($c_asignado_numeros);
	$asignado_numeros = $ob_asignado_numeros->cantidad_numeros;

	$asignado_derecho = $asignado_bolsas + $asignado_numeros;

	$c_venta_numero = mysqli_query($conn, "SELECT COUNT(numero) as numeros_venidos FROM fvp_detalles_ventas_menor WHERE id_sorteo = '$sorteo' AND estado_venta = 'APROBADO' AND numero = '$derecho'  ");
	$ob_venta_numero = mysqli_fetch_object($c_venta_numero);
	$numeros_venidos = $ob_venta_numero->numeros_venidos;

	$no_vendido_derecho = $asignado_derecho - $numeros_venidos;

	$i = 0;
	while (isset($v_serie_premiada[$i])) {
		if ($v_asignado[$i] != 3) {
			if ($v_vendido_derecho[$i] != 1) {
				$no_vendido_derecho--;
			}
		}

		$i++;
	}

	if ($no_vendido_derecho < 0) {
		$no_vendido_derecho = 0;
	}

	$monto_no_vendido_derecho = $no_vendido_derecho * 1000;

	$c_asignado_extra = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad_numeros FROM menor_seccionales_numeros WHERE id_sorteo = '$sorteo' AND id_empresa != 3 AND numero = '$derecho' AND serie_inicial > '$ultima_bolsa' ");
	echo mysqli_error($conn);
	$ob_asignado_extra = mysqli_fetch_object($c_asignado_extra);
	$cant_asig_extra = $ob_asignado_extra->cantidad_numeros;

	$c_vendido_extra = mysqli_query($conn, "SELECT COUNT(DISTINCT(CONCAT(numero,serie))) as numeros_venidos FROM fvp_detalles_ventas_menor WHERE id_sorteo = '$sorteo' AND estado_venta = 'APROBADO' AND numero = '$derecho' AND serie > '$ultima_bolsa' ");
	$ob_vendido_extra = mysqli_fetch_object($c_vendido_extra);
	$cant_vend_extra = $ob_vendido_extra->numeros_venidos;

	$c_vendido_extra = mysqli_query($conn, "SELECT COUNT(DISTINCT(CONCAT(numero,serie))) as numeros_venidos FROM fvp_detalles_ventas_menor_ajuste WHERE id_sorteo = '$sorteo' AND estado_venta = 'APROBADO' AND numero = '$derecho' AND serie > '$ultima_bolsa' ");
	$ob_vendido_extra = mysqli_fetch_object($c_vendido_extra);
	$cant_vend_extra += $ob_vendido_extra->numeros_venidos;

	$cant_no_vendida_extra = $cant_asig_extra - $cant_vend_extra;

	$i = 0;
	while (isset($v_serie_premiada[$i])) {
		if ($v_asignado[$i] != 3) {
			if ($v_vendido_derecho[$i] != 1) {
				if ($v_serie_premiada[$i] > $ultima_bolsa) {
					$cant_no_vendida_extra--;
				}
			}
		}
		$i++;
	}

	if ($cant_no_vendida_extra < 0) {
		$cant_no_vendida_extra = 0;
	}

	$cant_no_vendida_bolsa = $no_vendido_derecho - $cant_no_vendida_extra;

	if ($cant_no_vendida_bolsa < 0) {
		$cant_no_vendida_bolsa = 0;
	}

	?>
	<tr>
		<td>Derecho</td>
		<td><?php echo $derecho ?></td>
		<td><?php echo number_format($asignado_derecho) ?></td>
		<td><?php echo number_format($cant_no_vendida_bolsa) ?></td>
		<td><?php echo number_format($cant_no_vendida_extra) ?></td>
		<td><?php echo number_format($no_vendido_derecho) ?></td>
		<td><?php echo number_format($monto_no_vendido_derecho, 2) ?></td>
	</tr>
<?php

	$c_asignado_numeros = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad_numeros FROM menor_seccionales_numeros WHERE id_sorteo = '$sorteo' AND id_empresa != 3 AND numero = '$reves' ");
	$ob_asignado_numeros = mysqli_fetch_object($c_asignado_numeros);
	$asignado_numeros = $ob_asignado_numeros->cantidad_numeros;

	$asignado_reves = $asignado_bolsas + $asignado_numeros;

	$c_venta_numero = mysqli_query($conn, "SELECT COUNT(numero) as numeros_venidos FROM fvp_detalles_ventas_menor WHERE id_sorteo = '$sorteo' AND estado_venta = 'APROBADO' AND numero = '$reves'  ");
	$ob_venta_numero = mysqli_fetch_object($c_venta_numero);
	$numeros_venidos = $ob_venta_numero->numeros_venidos;

	$no_vendido_reves = $asignado_reves - $numeros_venidos;

	$i = 0;
	while (isset($v_serie_premiada[$i])) {
		if ($v_asignado[$i] != 3) {
			if ($v_vendido_reves[$i] != 1) {
				$no_vendido_reves--;
			}
		}

		$i++;
	}

	if ($no_vendido_reves < 0) {
		$no_vendido_reves = 0;
	}

	$monto_no_vendido_reves = $no_vendido_reves * 100;

	$c_asignado_extra = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad_numeros FROM menor_seccionales_numeros WHERE id_sorteo = '$sorteo' AND id_empresa != 3 AND numero = '$reves' AND serie_inicial > '$ultima_bolsa' ");
	echo mysqli_error($conn);
	$ob_asignado_extra = mysqli_fetch_object($c_asignado_extra);
	$cant_asig_extra = $ob_asignado_extra->cantidad_numeros;

	$c_vendido_extra = mysqli_query($conn, "SELECT COUNT(DISTINCT(CONCAT(numero,serie))) as numeros_venidos FROM fvp_detalles_ventas_menor WHERE id_sorteo = '$sorteo' AND estado_venta = 'APROBADO' AND numero = '$reves' AND serie > '$ultima_bolsa' ");
	$ob_vendido_extra = mysqli_fetch_object($c_vendido_extra);
	$cant_vend_extra = $ob_vendido_extra->numeros_venidos;

	$c_vendido_extra = mysqli_query($conn, "SELECT COUNT(DISTINCT(CONCAT(numero,serie))) as numeros_venidos FROM fvp_detalles_ventas_menor_ajuste WHERE id_sorteo = '$sorteo' AND estado_venta = 'APROBADO' AND numero = '$reves' AND serie > '$ultima_bolsa' ");
	$ob_vendido_extra = mysqli_fetch_object($c_vendido_extra);
	$cant_vend_extra += $ob_vendido_extra->numeros_venidos;

	$cant_no_vendida_extra = $cant_asig_extra - $cant_vend_extra;

	$i = 0;
	while (isset($v_serie_premiada[$i])) {
		if ($v_asignado[$i] != 3) {
			if ($v_vendido_reves[$i] != 1) {
				if ($v_serie_premiada[$i] > $ultima_bolsa) {
					$cant_no_vendida_extra--;
				}
			}
		}
		$i++;
	}

	if ($cant_no_vendida_extra < 0) {
		$cant_no_vendida_extra = 0;
	}

	$cant_no_vendida_bolsa = $no_vendido_reves - $cant_no_vendida_extra;

	if ($cant_no_vendida_bolsa < 0) {
		$cant_no_vendida_bolsa = 0;
	}

	?>



	<tr>
		<td>Reves</td>
		<td><?php echo $reves ?></td>
		<td><?php echo number_format($asignado_reves) ?></td>
		<td><?php echo number_format($cant_no_vendida_bolsa) ?></td>
		<td><?php echo number_format($cant_no_vendida_extra) ?></td>
		<td><?php echo number_format($no_vendido_reves) ?></td>
		<td><?php echo number_format($monto_no_vendido_reves, 2) ?></td>
	</tr>
</table>


<table width="100%">
<tr>
	<td width="49%" valign="top">

<table class="table table-bordered" width="100%" style="font-size: 12px">
	<tr>
		<th colspan="3">NO VENDIDO PREMIADO SOLO POR SERIE</th>
	</tr>
	<tr>
		<th>SERIE</th>
		<th>CANTIDAD</th>
		<th>MONTO PREMIADO</th>
	</tr>


<?php
$i = 0;
	$total_serie = 0;

	while (isset($v_serie_premiada[$i])) {

		if ($v_asignado[$i] != 3) {

			$consulta_serie = $v_serie_premiada[$i];

			$series_asignadas = mysqli_query($conn, "SELECT COUNT(*) as  asignado_series FROM menor_seccionales_bolsas WHERE  id_empresa != '3' AND serie_inicial <= '$consulta_serie' AND serie_final >= '$consulta_serie' AND id_sorteo = '$sorteo' ");
			$ob_asignado_series = mysqli_fetch_object($series_asignadas);
			$asignado_series_bolsas = $ob_asignado_series->asignado_series;

			$series_asignadas = mysqli_query($conn, "SELECT COUNT(numero) as  asignado_series FROM menor_seccionales_numeros WHERE id_empresa != '3' AND serie_inicial <= '$consulta_serie' AND serie_final >= '$consulta_serie' AND id_sorteo = '$sorteo' ");
			$ob_asignado_series = mysqli_fetch_object($series_asignadas);
			$asignado_series_numeros = $ob_asignado_series->asignado_series;

			$c_vendido_serie = mysqli_query($conn, "SELECT COUNT(serie) vendido_serie FROM fvp_detalles_ventas_menor WHERE id_sorteo = '$sorteo' AND serie = '$v_serie_premiada[$i]' AND estado_venta = 'APROBADO' ");
			$ob_vendido_serie = mysqli_fetch_object($c_vendido_serie);
			$vendido_serie_numero = $ob_vendido_serie->vendido_serie;

			$c_vendido_serie_ajuste = mysqli_query($conn, "SELECT COUNT(serie) vendido_serie FROM fvp_detalles_ventas_menor_ajuste WHERE id_sorteo = '$sorteo' AND serie = '$v_serie_premiada[$i]' AND estado_venta = 'APROBADO' ");
			$ob_vendido_serie_ajuste = mysqli_fetch_object($c_vendido_serie_ajuste);
			$vendido_serie_numero_ajuste = $ob_vendido_serie_ajuste->vendido_serie;

			$asignado_series_bolsas *= 100;
			$vendido_serie_numero += $vendido_serie_numero_ajuste;

			$asignado_series = $asignado_series_bolsas + $asignado_series_numeros;

//echo $asignado_series_bolsas;

			if ($vendido_serie_numero == 0) {
				$no_vendido_serie = $asignado_series;
			} else {

				if ($vendido_serie_numero != 0) {
					$no_vendido_serie = $asignado_series - $vendido_serie_numero;
					if ($v_vendido_derecho[$i] == 0) {
						$no_vendido_serie -= 1;
					}
					if ($v_vendido_reves[$i] == 0) {
						$no_vendido_serie -= 1;
					}
				}

			}

			if ($no_vendido_serie < 0) {
				$no_vendido_serie = 0;
			}

			$no_vendido_serie_monto = $no_vendido_serie * 100;
			$total_serie += $no_vendido_serie_monto;

			?>

<tr>
	<td><?php echo $v_serie_premiada[$i];
			if ($v_serie_premiada[$i] > $ultima_bolsa) {
				echo " (EXTRA)";
			} ?></td>
	<td><?php echo number_format($no_vendido_serie) ?></td>
	<td><?php echo number_format($no_vendido_serie_monto, 2) ?></td>
</tr>

<?php

		}

		$i++;
	}

	?>
<tr><th colspan="2">Total</th><th ><?php echo number_format($total_serie, 2); ?></th></tr>

</table>


	</td>
	<td width="2%"></td>
	<td width="49%">


<table class="table table-bordered" width="100%" style="font-size: 12px">
	<tr>
		<th colspan="3">NO VENDIDO PREMIADO POR NUMERO Y SERIE</th>
	</tr>
	<tr>
		<th>NUMERO</th>
		<th>SERIE</th>
		<th>MONTO PREMIADO</th>
	</tr>

<?php
$i = 0;
	while (isset($v_serie_premiada[$i])) {

		if ($v_asignado[$i] != 3) {

			if ($derecho != $reves) {

				if ($v_tipo_serie[$i] == 2) {
					if ($v_vendido_derecho[$i] == 0) {
						$monto_pago = $v_monto_premio[$i] + 1000;

					} else {
						$monto_pago = 0;
					}
				} else {
					if ($v_vendido_derecho[$i] == 0) {
						$monto_pago = 1100;
					} else {
						$monto_pago = 0;
					}
				}

			} else {

				if ($v_vendido_derecho[$i] == 0) {

					$monto_pago = $v_monto_premio[$i] + 1100;

				} else {

					$monto_pago = 0;

				}

			}

			?>

<tr>
	<td><?php echo $derecho ?></td>
	<td><?php echo $v_serie_premiada[$i];
			if ($v_serie_premiada[$i] > $ultima_bolsa) {
				echo " (EXTRA)";
			} ?></td>
	<td><?php echo number_format($monto_pago, 2) ?></td>
</tr>

<?php

		}

		$i++;
	}

	if ($derecho != $reves) {

		$i = 0;
		while (isset($v_serie_premiada[$i])) {

			if ($v_asignado[$i] != 3) {

				if ($v_tipo_serie[$i] != 2) {
					if ($v_vendido_reves[$i] == 0) {
						$monto_pago = $v_monto_premio[$i] + 100;
					} else {
						$monto_pago = 0;
					}
				} else {
					if ($v_vendido_reves[$i] == 0) {
						$monto_pago = 200;
					} else {
						$monto_pago = 0;
					}
				}

				?>

<tr>
	<td><?php echo $reves ?></td>
	<td><?php echo $v_serie_premiada[$i];
				if ($v_serie_premiada[$i] > $ultima_bolsa) {
					echo " (EXTRA)";
				} ?></td>
	<td><?php echo number_format($monto_pago, 2) ?></td>
</tr>

<?php
}

			$i++;
		}

	}

	?>


</table>

	</td>

</tr>

</table>


</div>




	</div>
</div>

<?php

}

?>