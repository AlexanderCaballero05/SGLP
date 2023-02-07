<?php
date_default_timezone_set("America/Tegucigalpa");

require '../../template/header.php';
require '../../assets/phpmailer/class.phpmailer.php';
require '../../assets/phpmailer/class.smtp.php';

$fecha_actual = date("Y-m-d");

$id_sorteo = $_GET['s'];
$sorteo = $_GET['s'];

?>

<section style="background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >PROCESO DE CIERRE DE SORTEO MAYOR <?php echo $sorteo; ?></h2>
<br>
</section>


<br>
<br>

<div class="card" style="margin-left: 15px; margin-right: 15px;">

<div class="card-header bg-success text-white">
<h3 style="text-align: center">ACTUALIZACION DE REGISTROS DE SEGURIDAD</h3>
</div>

<div class="card-body">


<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////// UPDATE REGISTROS MAYOR /////////////////////////////////////////////////////////
$c_rangos_registros = mysqli_query($conn, "SELECT * FROM sorteos_mayores_registros WHERE id_sorteo = '$id_sorteo' ");

if (!$c_rangos_registros) {
	echo "error" . mysqli_error($conn);
}

$update_registros = 1;
while ($r_rango_registros = mysqli_fetch_array($c_rangos_registros)) {

	$b_i = $r_rango_registros['billete'];
	$b_f = $r_rango_registros['billete_final'];
	$r_i = $r_rango_registros['registro'];

	$update = mysqli_query($conn, " UPDATE ventas_distribuidor_mayor SET registro = $r_i - (billete - $b_i) WHERE sorteo = $id_sorteo AND billete BETWEEN $b_i AND $b_f ");
	if ($update === FALSE) {

		echo "<div class = 'alert alert-danger' >Error: " . mysqli_error($conn) . ".</div>";

		$update_registros = 0;
	}

}

if ($update_registros == 1) {

	echo "<div class = 'alert alert-success' >Registros de seguridad del sorteo " . $id_sorteo . " actualizados correctamente.</div>";

} else {

	echo "<div class = 'alert alert-danger' >Los registros de seguridad no fueron actualizados.</div>";

}

/////////////////////////////////////////////////// UPDATE REGISTROS MAYOR /////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>

</div>

</div>







<br><br>






<div class="card" style="margin-left: 15px; margin-right: 15px;">

<div class="card-header bg-success text-white">
<h3 style="text-align: center">REGISTRO DE BILLETES DISPONIBLES PARA PAGO</h3>
</div>

<div class="card-body">


<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////// INSERT TABLA PAGOS MAYOR /////////////////////////////////////////////////////////

$c_validacion_registro = mysqli_query($conn, "SELECT * FROM archivo_pagos_mayor WHERE sorteo = '$sorteo'  LIMIT 1 ");

if (mysqli_num_rows($c_validacion_registro) > 0) {
	echo "<div class = 'alert alert-info'>Esta accion ya fue realizada para este sorteo por lo cual no puede volver a ser ejecutada.</div>";

} else {

	$consul_fecha_vence = mysqli_query($conn, " SELECT fecha_sorteo FROM sorteos_mayores  WHERE id = $sorteo ");
	while ($ro = mysqli_fetch_array($consul_fecha_vence)) {
		$fecha_vencimiento = $ro['fecha_sorteo'];
	}
	$dias = array("DOMINGO", "LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SÁBADO");
	$dia = $dias[date("w")];
	$meses = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE");
	$mes = $meses[date("m") - 1];
	$ano = date("Y");
	$diadate = date("d");

	$num = mysqli_query($conn, " SELECT cantidad_numeros FROM sorteos_mayores WHERE  id = $sorteo ");
	if (mysqli_num_rows($num) > 0) {
		while ($row3 = mysqli_fetch_array($num)) {
			$cant_series = $row3['cantidad_numeros'];
		}
	}

	$premi = mysqli_query($conn, " SELECT tipo_premio, monto, numero_premiado_mayor FROM sorteos_mayores_premios WHERE `sorteos_mayores_id`=$sorteo and respaldo != 'terminacion'    and `numero_premiado_mayor` is not null order by monto desc ");

	if (mysqli_num_rows($premi) > 0) {
		$i = 0;
		while ($rocopre = mysqli_fetch_array($premi)) {
			$_premios[$i] = $rocopre['monto']; // de cuanto es el premio
			$_premiado[$i] = $rocopre['numero_premiado_mayor']; /// cual es el numero premiado
			// echo  "<br>".$copremios."---".$_premios[$i]."---".$_premiado[$i];
			$i++;
		}
	}

	$_terminaciones = strlen($_premiado[0]) - 1;
	//echo $_terminaciones;
	$j = 1;
	while ($j <= $_terminaciones) {
		$query_pago_terminaciones = mysqli_query($conn, " SELECT monto FROM `sorteos_mayores_premios`  WHERE respaldo= 'terminacion' and sorteos_mayores_id=$sorteo order by monto asc ");
		$n = 1;
		while ($pago_ter = mysqli_fetch_array($query_pago_terminaciones)) {
			$pago_terminacion[$n] = $pago_ter['monto'];
			$n++;
		}
		$termina[$j] = substr($_premiado[0], -$j);
//         echo "<br>".$termina[$j]."--".$pago_terminacion[$j];
		$j++;
	}
	?>


  <table class="table table-hover table-bordered" style="width:99%;" id="table_id1" >
      <thead align="center">
      <td width="5%" style="font-family: Arial; font-size: 11pt;">No</td>
      <td width="5%" style="font-family: Arial; font-size: 11pt;">Billete</td>
      <td width="7%" style="font-family: Arial; font-size: 11pt;">urna</td>
      <td width="7%" style="font-family: Arial; font-size: 11pt;">serie</td>
      <td width="10%" style="font-family: Arial; font-size: 11pt;">total</td>
      <td width="10%" style="font-family: Arial; font-size: 11pt;">Impto</td>
      <td width="10%" style="font-family: Arial; font-size: 11pt;">Neto</td>
      <td width="10%" style="font-family: Arial; font-size: 11pt;">Tipo</td>
      <td width="30%" style="font-family: Arial; font-size: 11pt;">venta</td>
      <td width="5%" style="font-family: Arial; font-size: 11pt;">Insert</td>
      </thead>
      <tbody>
         <?php
///////////////////////////// serie del ganador
	$acum = 0;
	$acum_pp = 1;
	$total = $cant_series;
	while ($acum < $total) {
		$_pago = 0;
		if (in_array($acum, $_premiado) == true) {
			$indice = array_search($acum, $_premiado);
			$_pago = $_premios[$indice];
			$tipo_pago = 'U';
		} else {
			$_pago = 0;
		}

		$_pago_terminacion = 0;
		$lenacum = 1;
		while ($lenacum <= strlen($acum)) {
			$valor = substr($acum, -$lenacum);
			if (in_array($valor, $termina) == true) {
				$indice = array_search($valor, $termina);
				$_pago_terminacion = $pago_terminacion[$indice];
				$tipo_pago = 'T';
			}

			$lenacum++;
		}

		if ($acum == $_premiado[0]) {
			$_pago = $_premios[0];
			$_pago_terminacion = 0;
		}

		$_totalpagar = $_pago + $_pago_terminacion;
		if ($_totalpagar > 0) {

			if ($_totalpagar > 30000) {
				$_impto = $_totalpagar * 0.10;
			} else {
				$_impto = 0;
			}

			$_neto = $_totalpagar - $_impto;

			$query_vendido = mysqli_query($conn, "SELECT * FROM ventas_distribuidor_mayor where sorteo=$sorteo and billete=$acum;");
			if (mysqli_num_rows($query_vendido) > 0) {
				while ($_row_venta = mysqli_fetch_array($query_vendido)) {
					$detalle_venta = $_row_venta['detalle_venta'];
					$registro = $_row_venta['registro'];
				}

				$insert_archivo_pago = mysqli_query($conn, "INSERT INTO archivo_pagos_mayor(  sorteo,    detalle_venta,  numero, pago_urna,   pago_terminacion,        total,   impto,   neto,    tipo_pago,  registro)
                                                                   values  ( $sorteo, '$detalle_venta',   $acum,    $_pago, $_pago_terminacion, $_totalpagar, $_impto, $_neto, '$tipo_pago', $registro);");
				if ($insert_archivo_pago == false) {
					$_insertado = mysqli_error($conn);
				} else {
					$_insertado = 'ok';
				}

				echo "<tr>
            <td align='center' style='font-family: Arial; font-size: 11pt;'>" . $acum_pp . "</td>
            <td align='center' style='font-family: Arial; font-size: 11pt;'>" . sprintf("%05d", $acum) . "</td>
            <td align='center' style='font-family: Arial; font-size: 11pt;'>" . $_pago . "</td>
            <td align='center' style='font-family: Arial; font-size: 11pt;'>" . $_pago_terminacion . "</td>
            <td align='center' style='font-family: Arial; font-size: 11pt;'>" . $_totalpagar . "</td>
            <td align='center' style='font-family: Arial; font-size: 11pt;'>" . $_impto . "</td>
            <td align='center' style='font-family: Arial; font-size: 11pt;'>" . $_neto . "</td>
            <td align='center' style='font-family: Arial; font-size: 11pt;'>" . $tipo_pago . "</td>
            <td align='center' style='font-family: Arial; font-size: 11pt;'>" . $detalle_venta . "</td>
            <td align='center' style='font-family: Arial; font-size: 11pt;'>" . $_insertado . "</td>
            </tr>";
				$acum_pp++;

			}
		}
		$acum = $acum + 1;

	}
	?>
      </tbody>
  </table>


<?php

}

/////////////////////////////////////////////////// INSERT TABLA PAGOS MAYOR /////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>

</div>

</div>









<br><br>






<div class="card" style="margin-left: 15px; margin-right: 15px;">

<div class="card-header bg-success text-white">
<h3 style="text-align: center">BUSQUEDA Y REGISTRO DE CADUCOS MAYOR</h3>
</div>

<div class="card-body">

<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////// INSERT TABLA PAGOS MAYOR /////////////////////////////////////////////////////////

/// AQUI TRAEMOS EL SORTEO   SELECT sorteo, count(serie), sum(totalpayment), sum(imptopayment), sum(netopayment) FROM `archivo_pagos_menor` group by sorteo order by sorteo desc   -- SELECT sorteo, count(serie), sum(totalpayment), sum(imptopayment), sum(netopayment) FROM `archivo_pagos_menor` group by sorteo order by sorteo desc
$_query_sorteo = mysqli_query($conn, "SELECT sorteos_mayores_id
                            FROM   sorteos_mayores_premios
                            WHERE  numero_premiado_mayor IS NOT NULL ORDER BY sorteos_mayores_id DESC LIMIT 1");

while ($row_sorteo = mysqli_fetch_array($_query_sorteo)) {$_sorteo = $row_sorteo['sorteos_mayores_id'];}

$sorteo = $_sorteo;
$query = mysqli_query($conn, "SELECT b.fecha_sorteo , b.fecha_vencimiento fecha_vencimiento, (count(numero)*10) cantidad_billetes, sum(a.total) total_pagar, sum(a.impto) impto_pagar, sum(a.neto) neto_pagar FROM archivo_pagos_mayor a, sorteos_mayores b WHERE a.sorteo=b.id and a.sorteo=$sorteo");

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

	$texto_correo = "<br><table border='1' width='100%' class = 'table table-bordered'>
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
VALUES ( TO_DATE('".$fecha_sorteo."','DD-MM-YYYY hh24:mi:ss'), TO_DATE('".$fecha_vencimiento."','DD-MM-YYYY hh24:mi:ss'), $sorteo , $cantidad_billetes, $total_pagar, $impto_pagar, $neto_pagar, 1, 1, 1 ) ";

$save_result=oci_parse($conn2, $resultado_ERP);

$rc=oci_execute($save_result);

if(!$rc)
{
$e=oci_error($save_result);
var_dump($e);

$texto_correo=$e;

}

oci_close($conn2);

 

echo $texto_correo;

?>


</div>

</div>


<?php



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

 

?>




















<br><br>


<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////// REGISTRO DE UTILIDADES Y PERDIDAS ///////////////////////////////////////

?>



<div class="card" style="margin-left: 15px; margin-right: 15px;">

<div class="card-header bg-success text-white">
<h3 style="text-align: center">REGISTRO DE APROXIMACION DE UTILIDADES Y PERDIDAS</h3>
</div>

<div class="card-body">


<?php

$c_validacion_registro_u = mysqli_query($conn, " SELECT * FROM utilidades_perdidas_sorteos WHERE id_sorteo = '$id_sorteo'  AND tipo_loteria = 1 ");

if (mysqli_num_rows($c_validacion_registro_u) > 0) {

	echo "<div class = 'alert alert-info'>Esta accion no pudo realizarse puesto que ya existen registros de utilidades y perdidas para este sorteo.</div>";

} else {

	$filtro = 1;

////////////////////////////////////////////////////
	///////////// CONSULTA ASIGNACIONES ////////////////

	$c_asignacion = mysqli_query($conn, "SELECT id_empresa, SUM(cantidad) as cantidad, receptor, SUM(valor_neto) + SUM(rebaja_depositario) as valor_neto FROM facturacion_mayor WHERE id_sorteo = '$id_sorteo' AND id_empresa != '3' GROUP BY id_empresa ");

	if ($c_asignacion === FALSE) {
		echo mysqli_error($conn);
	}

	$tt_asignacion = 0;
	$tt_venta = 0;
	$tt_devolucion = 0;
	$tt_bruto = 0;
	$tt_descuento = 0;
	$tt_comision = 0;
	$tt_credito = 0;

	$concatenado_porcentaje_venta = '';
	$concatenado_asociaciones = '';

	$total_general_venta = 0;

	$u = 0;
	while ($reg_asignacion = mysqli_fetch_array($c_asignacion)) {
		$id_entidad = $reg_asignacion['id_empresa'];

		$v_utilidad[$u][0] = $id_sorteo;
		$v_utilidad[$u][1] = $reg_asignacion['id_empresa'];

		$valor_neto = $reg_asignacion['valor_neto'];
		$precio_uni = $reg_asignacion['valor_neto'] / $reg_asignacion['cantidad'];

////////////////////////////////////////////////////
		////////////// CONSULTA DE VENTAS //////////////////

		$c_ventas = mysqli_query($conn, "SELECT precio_unitario ,SUM(aportacion) as aportacion, SUM(cantidad) as venta, SUM(credito_pani) as credito, SUM(total_bruto) as total_bruto, SUM(comision_bancaria) as comision , SUM(descuento) as descuento  FROM transaccional_ventas WHERE estado_venta = 'APROBADO' AND id_sorteo = '$id_sorteo' AND id_entidad = '$id_entidad' AND cod_producto = 1 ");

		$ob_ventas = mysqli_fetch_object($c_ventas);
		$ventas_entidad = $ob_ventas->venta;
		$credito_entidad = $ob_ventas->credito;
		$dev_entidad = $reg_asignacion['cantidad'] - $ventas_entidad;
		$venta_entidad_l = $ventas_entidad * $precio_uni;
		$precio_unitario = $ob_ventas->precio_unitario;
		$total_bruto = $ob_ventas->total_bruto;
		$descuento = $ob_ventas->descuento;
		$comision = $ob_ventas->comision;
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

		$v_utilidad[$u][2] = $credito;
		$v_utilidad[$u][3] = 0;
		$v_utilidad[$u][4] = 0;
		$v_utilidad[$u][5] = 1;

		$u++;

		$tt_asignacion += $reg_asignacion['cantidad'];
		$tt_venta += $ventas_entidad;
		$tt_devolucion += $dev_entidad;
		$tt_bruto += $total_bruto;
		$tt_descuento += $descuento;
		$tt_comision += $comision;
		$tt_credito += $credito;

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

		$c_asignacion = mysqli_query($conn, "SELECT id_empresa, SUM(cantidad) as cantidad, receptor, SUM(valor_neto) + SUM(rebaja_depositario) as valor_neto FROM facturacion_mayor WHERE id_sorteo = '$id_sorteo' AND id_empresa = '3' GROUP BY id_empresa ");

		if ($c_asignacion === FALSE) {
			echo mysqli_error($conn);
		}

		$tt_asignacion = 0;
		$tt_venta = 0;
		$tt_devolucion = 0;
		$tt_bruto = 0;
		$tt_descuento = 0;
		$tt_comision = 0;
		$tt_credito = 0;

		$ob_asignacion = mysqli_fetch_object($c_asignacion);
		$id_entidad = $ob_asignacion->id_empresa;
		$cantidad_asig = $ob_asignacion->cantidad;
		$receptor = $ob_asignacion->receptor;

////////////////////////////////////////////////////
		////////////// CONSULTA DE VENTAS //////////////////

		$c_ventas = mysqli_query($conn, "SELECT SUM(cantidad) as venta, SUM(credito_pani) as credito, cod_producto ,precio_unitario, SUM(total_bruto) as bruto ,   SUM(comision_bancaria) as comision ,   SUM(descuento) as descuento , SUM(credito_pani) as credito FROM transaccional_ventas_general WHERE estado_venta = 'APROBADO' AND id_sorteo = '$id_sorteo' AND id_entidad = '$id_entidad' AND cod_producto IN (1) GROUP BY cod_producto ORDER BY cod_producto ASC ");

		if ($c_ventas === FALSE) {
			echo mysqli_error($conn);
		}

////////////// CONSULTA DE VENTAS //////////////////
		////////////////////////////////////////////////////

		$cantidad_bolsa = 0;
		if (mysqli_num_rows($c_ventas) > 0) {
			while ($r_ventas = mysqli_fetch_array($c_ventas)) {

				$ventas_banco = $r_ventas['venta'];
				$precio_banco = $r_ventas['precio_unitario'];
				$bruto_banco = $r_ventas['bruto'];
				$comision_banco = $r_ventas['comision'];
				$descuento_banco = $r_ventas['descuento'];
				$credito_banco = $r_ventas['credito'];

			}

			$devolu_banco = $cantidad_asig - $ventas_banco;

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
			$tt_bruto = $bruto_banco;
			$tt_descuento = $descuento_banco;
			$tt_comision = $comision_banco;
			$tt_credito = $credito_banco;

			$v_utilidad[$u][0] = $id_sorteo;
			$v_utilidad[$u][1] = 3;
			$v_utilidad[$u][2] = $credito_banco;
			$v_utilidad[$u][3] = 0;
			$v_utilidad[$u][4] = 0;
			$v_utilidad[$u][5] = 1;

		}

		$total_general_venta += $tt_credito;
///////////// CONSULTA ASIGNACIONES ////////////////
		////////////////////////////////////////////////////

	}

/////////////////////////////////////////////////////
	////////////////////// PREMIOS //////////////////////
	/////////////////////////////////////////////////////

	?>


<?php

	$premios_mayores_t = mysqli_query($conn, "SELECT a.premios_mayores_id, a.numero_premiado_mayor, a.monto, a.respaldo, b.descripcion_premios FROM sorteos_mayores_premios as a INNER JOIN premios_mayores as b ON a.premios_mayores_id = b.id WHERE a.sorteos_mayores_id  = '$id_sorteo'  AND a.premios_mayores_id IN (9,10,11,12) ORDER BY a.premios_mayores_id ASC, a.monto DESC ");

	while ($reg_premios_mayores_t = mysqli_fetch_array($premios_mayores_t)) {
		$id = $reg_premios_mayores_t['premios_mayores_id'];
		$respaldo = $reg_premios_mayores_t['respaldo'];

		if ($id == 9 AND $respaldo == "terminacion") {
			$monto_terminacion_1 = $reg_premios_mayores_t['monto'];
		}

		if ($id == 10 AND $respaldo == "terminacion") {
			$monto_terminacion_2 = $reg_premios_mayores_t['monto'];
		}

		if ($id == 11 AND $respaldo == "terminacion") {
			$monto_terminacion_3 = $reg_premios_mayores_t['monto'];
		}

		if ($id == 12 AND $respaldo == "terminacion") {
			$monto_terminacion_4 = $reg_premios_mayores_t['monto'];
		}

	}

	if (!isset($monto_terminacion_1)) {
		$monto_terminacion_1 = 0;
	}

	if (!isset($monto_terminacion_2)) {
		$monto_terminacion_2 = 0;
	}

	if (!isset($monto_terminacion_3)) {
		$monto_terminacion_3 = 0;
	}

	if (!isset($monto_terminacion_4)) {
		$monto_terminacion_4 = 0;
	}

	$premios_mayores = mysqli_query($conn, "SELECT a.premios_mayores_id, a.numero_premiado_mayor, a.monto, a.respaldo, b.descripcion_premios FROM sorteos_mayores_premios as a INNER JOIN premios_mayores as b ON a.premios_mayores_id = b.id WHERE a.sorteos_mayores_id  = '$id_sorteo' AND a.numero_premiado_mayor IS NOT NULL  AND a.premios_mayores_id NOT IN (9,10,11,12) ORDER BY a.premios_mayores_id ASC, a.monto DESC ");

	$tt_p = 0;
	while ($reg_premios_mayores = mysqli_fetch_array($premios_mayores)) {

		$id = $reg_premios_mayores['premios_mayores_id'];
		$billete = $reg_premios_mayores['numero_premiado_mayor'];
		$descrip = $reg_premios_mayores['descripcion_premios'];
		$monto = $reg_premios_mayores['monto'];
		$respaldo = $reg_premios_mayores['respaldo'];

		if (!isset($billete_premio_mayor) AND $id == 1 AND $respaldo != "SI")  {
			$billete_premio_mayor = $billete;
//echo "BILLETE MAYOR ".$billete_premio_mayor. "<br>";
		}

		$verificar_venta = mysqli_query($conn, " SELECT (SELECT COUNT(billete) FROM fvp_detalles_ventas_mayor WHERE id_sorteo = '$id_sorteo' AND billete = '$billete' AND estado_venta = 'APROBADO' ) AS conteo1, (SELECT COUNT(billete) FROM transaccional_mayor_banco_detalle WHERE id_sorteo = '$id_sorteo' AND billete = '$billete' AND estado_venta = 'APROBADO' ) AS conteo2 ");

		if ($verificar_venta === FALSE) {
			echo mysqli_error($conn);
		}

		$ob_verificar_venta = mysqli_fetch_object($verificar_venta);
		$conteo1 = $ob_verificar_venta->conteo1;
		$conteo2 = $ob_verificar_venta->conteo2;

		if ($conteo1 > 0 OR $conteo2 > 0) {

			if ($conteo1 > 0) {

				$consulta_entidad = mysqli_query($conn, "SELECT b.id_entidad FROM fvp_detalles_ventas_mayor as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE a.id_sorteo = '$id_sorteo' AND a.billete = '$billete' AND a.estado_venta = 'APROBADO' ");
				$ob_consulta_entidad = mysqli_fetch_object($consulta_entidad);
				$entidad_venta_premiado = $ob_consulta_entidad->id_entidad;

				$r = 0;
				while (isset($v_utilidad[$r][1])) {
					if ($v_utilidad[$r][1] == $entidad_venta_premiado) {
						$v_utilidad[$r][3] += $monto;
					}
					$r++;
				}

			} elseif ($conteo2 > 0) {

				$r = 0;
				while (isset($v_utilidad[$r][1])) {
					if ($v_utilidad[$r][1] == 3) {
						$v_utilidad[$r][3] += $monto;
					}
					$r++;
				}

			}

			$tt_p += $monto;
		}

	}

//////////////////////////////////////////////////////////////////////////////
	////////////////////////  TERMINACIONES

	$terminacion_1 = substr($billete_premio_mayor, -1);
	$terminacion_2 = substr($billete_premio_mayor, -2);
	$terminacion_3 = substr($billete_premio_mayor, -3);
	$terminacion_4 = substr($billete_premio_mayor, -4);

/*
echo "Terminacion 1 = ".$terminacion_1."<br>";
echo "Terminacion 2 = ".$terminacion_2."<br>";
echo "Terminacion 3 = ".$terminacion_3."<br>";
echo "Terminacion 4 = ".$terminacion_4."<br>";
echo "BILLETE PREMIADO = ".$billete_premio_mayor."<br>";

echo "<br>";
echo "<br>=====================================================<br>";
echo "<br>=================== TERMINACION 1 ===================<br>";
 */

	$r = 0;
	$conteo = 0;

	while (isset($v_utilidad[$r][1])) {
		$entidad = $v_utilidad[$r][1];

		$consulta_terminacion = mysqli_query($conn, " SELECT COUNT(DISTINCT(a.billete)) as conteo FROM fvp_detalles_ventas_mayor as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE a.id_sorteo = '$id_sorteo' AND a.estado_venta = 'APROBADO' AND b.id_entidad = '$entidad' AND SUBSTRING(LPAD(a.billete, 5, '0'), -1) = '$terminacion_1'  AND SUBSTRING(LPAD(a.billete, 5, '0'), -2) != '$terminacion_2' AND SUBSTRING(LPAD(a.billete, 5, '0'), -3) != '$terminacion_3' AND SUBSTRING(LPAD(a.billete, 5, '0'), -4) != '$terminacion_4' AND SUBSTRING(LPAD(a.billete, 5, '0'), -5) != '$billete_premio_mayor' ");
		$ob_conteo = mysqli_fetch_object($consulta_terminacion);
		$conteo_e = $ob_conteo->conteo;

//echo " ENTIDAD ".$entidad." CONTEO ".$conteo_e."<br>";

		$conteo += $conteo_e;

//echo " Entidad = ".$entidad." Cantidad = ".$conteo_e." <br>";

		$monto = $conteo_e * $monto_terminacion_1;
		$v_utilidad[$r][3] += $monto;

		$r++;
	}

	$conteo_b = 0;
	$consulta_terminacion = mysqli_query($conn, "SELECT COUNT(DISTINCT(a.billete)) as conteo FROM transaccional_mayor_banco_detalle as a WHERE a.id_sorteo = '$id_sorteo' AND a.estado_venta = 'APROBADO' AND SUBSTRING(LPAD(a.billete, 5, '0'), -1) = '$terminacion_1' AND SUBSTRING(LPAD(a.billete, 5, '0'), -2) != '$terminacion_2' AND SUBSTRING(LPAD(a.billete, 5, '0'), -3) != '$terminacion_3' AND SUBSTRING(LPAD(a.billete, 5, '0'), -4) != '$terminacion_4' AND SUBSTRING(LPAD(a.billete, 5, '0'), -5) != '$billete_premio_mayor'  ");
	$ob_conteo = mysqli_fetch_object($consulta_terminacion);
	$conteo_b = $ob_conteo->conteo;

	$conteo += $conteo_b;

//echo "<br> Conteo Banco = ".$conteo_b;

	$r = 0;
	while (isset($v_utilidad[$r][1])) {
		if ($v_utilidad[$r][1] == 3) {
			$monto = $conteo_b * $monto_terminacion_1;
			$v_utilidad[$r][3] += $monto;

//echo " ENTIDAD B ".$entidad." CONTEO ".$conteo_b."<br>";

		}
		$r++;
	}

//echo $conteo." T1<br>";

/*
echo "<br><br> TOTAL = ".$conteo;

echo "<br>=================== TERMINACION 1 ===================<br>";
echo "<br>=====================================================<br>";

echo "<br>";
echo "<br>";
echo "<br>";

echo "<br>=====================================================<br>";
echo "<br>=================== TERMINACION 2 ===================<br>";
 */

	$r = 0;
	$conteo = 0;

	while (isset($v_utilidad[$r][1])) {
		$entidad = $v_utilidad[$r][1];

		$consulta_terminacion = mysqli_query($conn, "SELECT COUNT(DISTINCT(a.billete)) as conteo FROM fvp_detalles_ventas_mayor as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE a.id_sorteo = '$id_sorteo' AND a.estado_venta = 'APROBADO' AND b.id_entidad = '$entidad' AND SUBSTRING(LPAD(a.billete, 5, '0'), -2) = '$terminacion_2' AND SUBSTRING(LPAD(a.billete, 5, '0'), -3) != '$terminacion_3' AND SUBSTRING(LPAD(a.billete, 5, '0'), -4) != '$terminacion_4'  AND SUBSTRING(LPAD(a.billete, 5, '0'), -5) != '$billete_premio_mayor' ");
		$ob_conteo = mysqli_fetch_object($consulta_terminacion);
		$conteo_e = $ob_conteo->conteo;

		$conteo += $conteo_e;

		$monto = $conteo_e * $monto_terminacion_2;
		$v_utilidad[$r][3] += $monto;

//echo " Entidad = ".$entidad." Cantidad = ".$conteo_e." <br>";

		$r++;
	}

	$conteo_b = 0;
	$consulta_terminacion = mysqli_query($conn, "SELECT COUNT(DISTINCT(a.billete)) as conteo FROM transaccional_mayor_banco_detalle as a WHERE a.id_sorteo = '$id_sorteo' AND a.estado_venta = 'APROBADO' AND SUBSTRING(LPAD(a.billete, 5, '0'), -2) = '$terminacion_2' AND SUBSTRING(LPAD(a.billete, 5, '0'), -3) != '$terminacion_3' AND SUBSTRING(LPAD(a.billete, 5, '0'), -4) != '$terminacion_4' AND SUBSTRING(LPAD(a.billete, 5, '0'), -5) != '$billete_premio_mayor' ");
	$ob_conteo = mysqli_fetch_object($consulta_terminacion);
	$conteo_b += $ob_conteo->conteo;

	$conteo += $conteo_b;
//echo "<br> Conteo Banco = ".$conteo_b;

	$r = 0;
	while (isset($v_utilidad[$r][1])) {
		if ($v_utilidad[$r][1] == 3) {
			$monto = $conteo_b * $monto_terminacion_2;
			$v_utilidad[$r][3] += $monto;
		}
		$r++;
	}

//echo $conteo." T2<br>";

/*
echo "<br><br> TOTAL = ".$conteo;

echo "<br>=================== TERMINACION 2 ===================<br>";
echo "<br>=====================================================<br>";
echo "<br>";
echo "<br>";
echo "<br>";

echo "<br>=====================================================<br>";
echo "<br>=================== TERMINACION 3 ===================<br>";
 */

	$r = 0;
	$conteo = 0;

	while (isset($v_utilidad[$r][1])) {
		$entidad = $v_utilidad[$r][1];

		$consulta_terminacion = mysqli_query($conn, "SELECT COUNT(DISTINCT(a.billete)) as conteo FROM fvp_detalles_ventas_mayor as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE a.id_sorteo = '$id_sorteo' AND a.estado_venta = 'APROBADO' AND b.id_entidad = '$entidad' AND SUBSTRING(LPAD(a.billete, 5, '0'), -3) = '$terminacion_3' AND SUBSTRING(LPAD(a.billete, 5, '0'), -4) != '$terminacion_4' AND SUBSTRING(LPAD(a.billete, 5, '0'), -5) != '$billete_premio_mayor' ");
		$ob_conteo = mysqli_fetch_object($consulta_terminacion);
		$conteo_e = $ob_conteo->conteo;
		$conteo += $conteo_e;

		$monto = $conteo_e * $monto_terminacion_3;
		$v_utilidad[$r][3] += $monto;

//echo " Entidad = ".$entidad." Cantidad = ".$conteo_e." <br>";

		$r++;
	}

	$conteo_b = 0;
	$consulta_terminacion = mysqli_query($conn, "SELECT COUNT(DISTINCT(a.billete)) as conteo FROM transaccional_mayor_banco_detalle as a WHERE a.id_sorteo = '$id_sorteo' AND a.estado_venta = 'APROBADO'  AND SUBSTRING(LPAD(a.billete, 5, '0'), -3) = '$terminacion_3' AND SUBSTRING(LPAD(a.billete, 5, '0'), -4) != '$terminacion_4' AND SUBSTRING(LPAD(a.billete, 5, '0'), -5) != '$billete_premio_mayor' ");
	$ob_conteo = mysqli_fetch_object($consulta_terminacion);
	$conteo_b = $ob_conteo->conteo;

	$conteo += $conteo_b;

//echo "<br> Conteo Banco = ".$conteo_b;

	$r = 0;
	while (isset($v_utilidad[$r][1])) {
		if ($v_utilidad[$r][1] == 3) {
			$monto = $conteo_b * $monto_terminacion_3;
			$v_utilidad[$r][3] += $monto;
		}
		$r++;
	}

//echo $conteo." T3<br>";

/*
echo "<br><br> TOTAL = ".$conteo;

echo "<br>=================== TERMINACION 3 ===================<br>";
echo "<br>=====================================================<br>";

echo "<br>";
echo "<br>";
echo "<br>";

echo "<br>=====================================================<br>";
echo "<br>=================== TERMINACION 4 ===================<br>";
 */

	$r = 0;
	$conteo = 0;

	while (isset($v_utilidad[$r][1])) {
		$entidad = $v_utilidad[$r][1];

		$consulta_terminacion = mysqli_query($conn, "SELECT COUNT(DISTINCT(a.billete)) as conteo FROM fvp_detalles_ventas_mayor as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE a.id_sorteo = '$id_sorteo' AND a.estado_venta = 'APROBADO' AND b.id_entidad = '$entidad' AND SUBSTRING(LPAD(a.billete, 5, '0'), -4) = '$terminacion_4' AND SUBSTRING(LPAD(a.billete, 5, '0'), -5) != '$billete_premio_mayor' ");
		$ob_conteo = mysqli_fetch_object($consulta_terminacion);
		$conteo_e = $ob_conteo->conteo;
		$conteo += $conteo_e;

		$monto = $conteo_e * $monto_terminacion_4;
		$v_utilidad[$r][3] += $monto;

//echo " Entidad = ".$entidad." Cantidad = ".$conteo_e." <br>";

		$r++;
	}

	$conteo_b = 0;
	$consulta_terminacion = mysqli_query($conn, "SELECT COUNT(DISTINCT(a.billete)) as conteo FROM transaccional_mayor_banco_detalle as a WHERE a.id_sorteo = '$id_sorteo' AND a.estado_venta = 'APROBADO'  AND SUBSTRING(LPAD(a.billete, 5, '0'), -4) = '$terminacion_4' AND SUBSTRING(LPAD(a.billete, 5, '0'), -5) != '$billete_premio_mayor' ");
	$ob_conteo = mysqli_fetch_object($consulta_terminacion);
	$conteo_b = $ob_conteo->conteo;

	$conteo += $conteo_b;

//echo "<br> Conteo Banco = ".$conteo_b;

	$r = 0;
	while (isset($v_utilidad[$r][1])) {
		if ($v_utilidad[$r][1] == 3) {
			$monto = $conteo_b * $monto_terminacion_4;
			$v_utilidad[$r][3] += $monto;
		}
		$r++;
	}

//echo $conteo." T4<br>";

/*
echo "<br><br> TOTAL = ".$conteo;

echo "<br>=================== TERMINACION 4 ===================<br>";
echo "<br>=====================================================<br>";
 */

	echo "<table class = 'table table-bordered'>";
	echo "<tr>";
	echo "<th> SORTEO</th>";
	echo "<th> ENTIDAD</th>";
	echo "<th> CREDITO</th>";
	echo "<th> PAGO</th>";
	echo "<th> UTILIDAD</th>";
	echo "<th> TIPO</th>";
	echo "</tr>";

	$r = 0;
	while (isset($v_utilidad[$r][1])) {

		$v_utilidad[$r][4] = $v_utilidad[$r][2] - $v_utilidad[$r][3];

		$sorteo_r = $v_utilidad[$r][0];
		$entidad_r = $v_utilidad[$r][1];
		$credito_r = $v_utilidad[$r][2];
		$pago_r = $v_utilidad[$r][3];
		$utilidad_r = $v_utilidad[$r][4];
		$tipo_r = $v_utilidad[$r][5];

		echo "<tr>";
		echo "<td>" . $sorteo_r . "</td>";
		echo "<td>" . $entidad_r . "</td>";
		echo "<td>" . $credito_r . "</td>";
		echo "<td>" . $pago_r . "</td>";
		echo "<td>" . $utilidad_r . "</td>";
		echo "<td>" . $tipo_r . "</td>";
		echo "</tr>";

		if ($credito_r == "") {
			$credito_r = 0;
		}

		$registro = mysqli_query($conn, "INSERT INTO utilidades_perdidas_sorteos (id_sorteo, id_entidad, credito_pani, provision_pago, utilidad_perdida, tipo_loteria) VALUES ('$sorteo_r', '$entidad_r', '$credito_r', '$pago_r', '$utilidad_r', '$tipo_r') ");

		if ($registro === FALSE) {
			echo mysqli_error($conn);
		}

		$r++;

	}

	echo "</table>";

	mysqli_query($conn, "UPDATE sorteos_mayores SET estado_sorteo = 'CAPTURADO' WHERE id = '$id_sorteo' ");

	echo "<div class = 'alert alert-success'>CAPTURA DE SORTEO " . $id_sorteo . " FINALIZADA CORRECTAMENTE</div>";

}

?>


</div>

</div>
