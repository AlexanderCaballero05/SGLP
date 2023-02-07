<?php
//require('./conexion_oracle.php');

$id_empresa = $_SESSION['entidad_id'];

$sorteos = mysqli_query($conn, "SELECT a.id, a.no_sorteo_may, a.fecha_sorteo, a.descripcion_sorteo_may  FROM sorteos_mayores as a inner join empresas_estado_venta as b ON a.id = b.id_sorteo WHERE  b.estado_venta = 'H' AND b.id_empresa = '$id_empresa' AND b.cod_producto = 1 GROUP BY a.id ORDER BY a.id DESC  ");

if (isset($_POST['seleccionar'])) {

	$id_sorteo = $_POST['sorteo'];
	$_SESSION['id_sorteo'] = $id_sorteo;
	$id_user = $_SESSION['id_usuario'];
	$id_empresa = $_SESSION['entidad_id'];
	$id_seccional = $_SESSION['agencia_id'];
	$_SESSION['token'] = 0;

	$maximo = mysqli_query($conn, "SELECT MAX(cod_factura)  as mx  FROM transaccional_ventas  ");
	$objeto = mysqli_fetch_object($maximo);
	$cod_factura = $objeto->mx;
	$cod_factura = $cod_factura + 1;

	$info_sorteo = mysqli_query($conn, "SELECT *  FROM sorteos_mayores WHERE id = '$id_sorteo' limit 1");
	$value = mysqli_fetch_object($info_sorteo);
	$sorteo = $value->no_sorteo_may;
	$fecha_sorteo = $value->fecha_sorteo;
	$mezcla = $value->mezcla;
	$precio_decimo = $value->precio_unitario;
	$descripcion = $value->descripcion_sorteo_may;
	$precio_pliego = $precio_decimo * 10;

/////////////////////////////////////////////////////////////////
	///////////// BUSQUEDA DE PARAMETROS DE VENTA ///////////////////
	$parametros_venta = mysqli_query($conn, "SELECT * FROM empresas WHERE id = '$id_empresa' ");
	$ob_paramatros_venta = mysqli_fetch_object($parametros_venta);
	$descuento = $ob_paramatros_venta->descuento_mayor;
	$tipo_descuento = $ob_paramatros_venta->tipo_descuento_mayor;
	$comision = $ob_paramatros_venta->rebaja_mayor;
	$tipo_comision = $ob_paramatros_venta->tipo_rebaja_mayor;

	if ($tipo_descuento == 1) {
		$precio_neto = $precio_pliego - $descuento;
		$monto_descuento = $descuento;
	} else {
		$desc = $descuento / 100;
		$monto_descuento = $precio_pliego * $desc;
		$precio_neto = $precio_pliego - $monto_descuento;
	}

	if ($tipo_comision == 1) {
		$monto_comision = $comision;
	} else {
		$com = $comision / 100;
		$monto_comision = $precio_pliego * $com;
	}

	$precio_neto = number_format($precio_pliego, 2, '.', '');

///////////// FIN BUSQUEDA DE PARAMETROS DE VENTA ///////////////////
	/////////////////////////////////////////////////////////////////////

	$today = date('Y-m-d');

	$ventas_sorteo = mysqli_query($conn, "SELECT *  FROM transaccional_ventas WHERE id_sorteo = '$id_sorteo' AND id_entidad = '$id_empresa' AND date(fecha_venta) = '$today' ORDER BY  fecha_venta DESC ");

	$busqueda_billetes_no_disponibles = mysqli_query($conn, "SELECT b.billete  FROM transaccional_ventas as a INNER JOIN fvp_detalles_ventas_mayor as b ON a.cod_factura = b.cod_factura WHERE a.id_sorteo = '$id_sorteo' AND a.cod_producto = '1' AND a.estado_venta != 'CANCELADA' ");

	$i = 0;
	while ($busqueda_facturas = mysqli_fetch_array($busqueda_billetes_no_disponibles)) {
		$v_no_billetes_disponibles[$i] = $busqueda_facturas['billete'];
		$i++;
	}

//////////////////  Busqueda de Billetes disponibles para seccional /////////////////////////

	$billetes_en_seccional = mysqli_query($conn, "SELECT a.rango FROM sorteos_mezclas_rangos as a INNER JOIN sorteos_mezclas as b ON a.num_mezcla = b.num_mezcla AND a.id_sorteo = b.id_sorteo WHERE b.id_empresa = '$id_empresa' AND b.id_seccional = '$id_seccional' AND b.id_sorteo = '$id_sorteo' ");
	$j = 0;
	$i = 0;

	while ($billetes = mysqli_fetch_array($billetes_en_seccional)) {
		$billete_inicial = $billetes['rango'];
		$billete_final = $billete_inicial + $mezcla - 1;
		while ($billete_inicial <= $billete_final) {

			if (isset($v_no_billetes_disponibles)) {
				if (in_array($billete_inicial, $v_no_billetes_disponibles)) {} else {
					$v_billetes_disponibles[$j] = $billete_inicial;
					$j++;
				}
			} else {
				$v_billetes_disponibles[$j] = $billete_inicial;
				$j++;
			}

			$billete_inicial++;
		}

		$i++;
	}

}
/////////////////////////////////// fin de seleccion ///////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////// CODIGO DE GUARDADO /////////////////////////////////////////

if (isset($_POST['guardar']) AND isset($_SESSION['token'])) {

	$id_sorteo = $_POST['id_sorteo'];
	$factura = $_POST['factura'];

	$identidad_comprador = $_POST['identidad'];
	$nombre_comprador = strtoupper($_POST['nombre']);

	$v_identidad = explode("-", $identidad_comprador);

	$identidad_comprador = $v_identidad[0] . $v_identidad[1] . $v_identidad[2];

	$mezcla = $_POST['mezcla'];
	$id_usuario = $_SESSION['id_usuario'];
	$id_empresa = $_SESSION['entidad_id'];
	$agencia_id = $_SESSION['agencia_id'];
	$departamento_id = $_SESSION['departamento_id'];
	$municipio_id = $_SESSION['municipio_id'];

	$precio = $_POST['precio'];
	$precio_total = $_POST['total_pagar'];
	$descuento_total = $_POST['descuento_total'];
	$neto_total = $_POST['neto'];
	$cantidad = $precio_total / $precio;
	$usuario = $_SESSION['nombre_usuario'];
	$comision_unitaria = $_POST['comision'];
	$comision_total = $comision_unitaria * $cantidad;
	$credito_pani = $precio_total - $descuento_total - $comision_total;
	$utilidad_pani = $precio_total - $descuento_total - $comision_total;
	$forma_pago = 1;

	$bandera_transaccional = 0;
	$bandera_detalle = 0;
	$bandera_erp = 0;
	$bandera_mayor_ventas = 0;

	$c_info_seccional = mysqli_query($conn, "SELECT * FROM fvp_seccionales WHERE id = '$agencia_id' ");
	$ob_seccional = mysqli_fetch_object($c_info_seccional);
	$desc_acta = $ob_seccional->descripcion_acta;

//////////////////////////////////////////////
	/////////// CALCULO FECHA LIMITE /////////////

	$sorteo_sig = $id_sorteo + 1;
	$info_sorteo_sig = mysqli_query($conn, "SELECT date(fecha_sorteo) as fecha_sorteo FROM sorteos_mayores WHERE id = $sorteo_sig ");
	if (mysqli_num_rows($info_sorteo_sig) > 0) {

		$ob_sorteo_sig = mysqli_fetch_object($info_sorteo_sig);
		$fecha_sorteo_sig = $ob_sorteo_sig->fecha_sorteo;
		$fecha_vencimiento = new DateTime($fecha_sorteo_sig);
		$fecha_vencimiento = date_format($fecha_vencimiento, 'Y-m-d');
		$fecha_vencimiento = date('Y-m-d', strtotime($fecha_vencimiento . ' - 1 days'));

	} else {

		$fecha_vencimiento = NULL;

	}

/////////// CALCULO FECHA LIMITE /////////////
	//////////////////////////////////////////////

////////////////////////////////////////////////////////////
	///////////// INSERT TRANSACCIONAL /////////////////////////

	if (mysqli_query($conn, " INSERT INTO `transaccional_ventas`(`cod_factura`, `id_sorteo`, `id_entidad`, `cantidad`, `precio_unitario`, `total_bruto`, `descuento`, `total_neto`, `comision_bancaria`, `utilidad_pani` , `credito_pani`, `id_usuario`, `id_seccional`, `identidad_comprador`, `nombre_comprador`, `estado_venta`, `forma_pago`, `cod_producto` , fecha_limite_pago, descripcion_agencia)
SELECT(SELECT MAX(cod_factura)+1 from transaccional_ventas), '$id_sorteo', '$id_empresa', '$cantidad', '$precio', '$precio_total', '$descuento_total', '$neto_total', '$comision_total', '$utilidad_pani' , '$credito_pani', '$id_usuario', '$agencia_id', '$identidad_comprador', '$nombre_comprador', 'APROBADO' , '$forma_pago' , '1' , '$fecha_vencimiento', '$desc_acta' ") === false) {

		$bandera_transaccional = 1;
		echo "Transaccional ventas";
		echo mysqli_error($conn);

	} else {

		$busqueda_factura = mysqli_query($conn, "SELECT MAX(cod_factura) as maximo FROM transaccional_ventas WHERE id_usuario = $id_usuario AND id_entidad = '$id_empresa' AND id_sorteo = '$id_sorteo' AND id_entidad = $id_empresa ");
		$ob_max_factura = mysqli_fetch_object($busqueda_factura);
		$cod_factura = $ob_max_factura->maximo;

	}

//////////// FIN INSERT TRANSACCIONAL ///////////////////
	/////////////////////////////////////////////////////////

////////////////////////////////////////////////////
	/////////////////// CODIGO DE DETALLE /////////////

	if ($bandera_erp == 0 AND $bandera_transaccional == 0 AND $bandera_mayor_ventas == 0) {

		$i = 0;
		while (isset($_POST['billete'][$i])) {

			if (isset($_POST['billete'][$i])) {
				if ($_POST['billete'][$i] != '') {

					$billete = $_POST['billete'][$i];
					$precio_unitario = $_POST['total'][$i];

					if (mysqli_query($conn, "INSERT INTO fvp_detalles_ventas_mayor (billete,precio_unitario,decimos,cod_factura,id_sorteo,estado_venta, agencia, departamento, municipio)
VALUES ('$billete','$precio','10','$cod_factura','$id_sorteo','APROBADO' , '$agencia_id','$departamento_id','$municipio_id') ") === false) {
						$bandera_detalle = 1;
						$i = -2;
					}

				}
			}

			$i++;
		}

	}

///////////// FIN DE CODIGO DETALLE ////////////////
	////////////////////////////////////////////////////

	if ($bandera_mayor_ventas == 0 AND $bandera_erp == 0 AND $bandera_detalle == 0 AND $bandera_transaccional == 0) {
		echo '<div class="alert alert-success" role="alert"> Venta realizada exitosamente</div>';
	} else {
		echo '<div class="alert alert-danger" role="alert"> Error en la venta, por favor intente nuevamente.</div>';
	}

	$_SESSION['cod_impresion'] = $cod_factura;
	unset($_SESSION['token']);
	unset($_SESSION["estado_mayor"]);

}

if (isset($_POST['reversar_venta'])) {

	$id_empresa = $_SESSION['entidad_id'];
	$cod_factura = $_POST['reversar_venta'];

	if (mysqli_query($conn, "UPDATE  transaccional_ventas SET estado_venta = 'CANCELADA' WHERE cod_factura = '$cod_factura' AND cod_producto = '1' AND id_entidad = '$id_empresa' ") === TRUE) {

		if (mysqli_query($conn, "UPDATE  fvp_detalles_ventas_mayor SET estado_venta = 'CANCELADA' WHERE cod_factura = '$cod_factura' ") === TRUE) {

			echo "<div class = 'alert alert-success'>Factura reversada correctamente</div>";
			echo mysqli_error($conn);

		} else {
			echo "<div class = 'alert alert-danger'>Error inesperado, por favor notifique a la unidad de informatica del error</div>";
			echo mysqli_error($conn);
		}

	} else {

		echo "<div class = 'alert alert-danger'>Error inesperado, por favor notifique a la unidad de informatica del error</div>";
		echo mysqli_error($conn);

	}

}

?>