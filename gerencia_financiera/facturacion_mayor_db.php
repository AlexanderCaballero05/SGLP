<?php
$sorteos = mysqli_query($conn, "SELECT * FROM sorteos_mayores  ORDER BY no_sorteo_may DESC ");

if (isset($_POST['guardar_factura'])) {

	$sorteo = $_POST['id_sorteo'];
	$receptor = $_POST['receptor'];
	$id_empresa = $_POST['id_empresa'];
	$fecha_expedicion = $_POST['fecha_expedicion'];
	$no_factura = $_POST['no_factura'];
	$fecha_sorteo = $_POST['fecha_sorteo'];
	$cantidad_asignada = $_POST['cantidad_asignada'];
	$valor_nominal = $_POST['valor_nominal'];
	$descuento = $_POST['descuento'];
	$rebaja = $_POST['rebaja'];
	$valor_neto = $_POST['valor_neto'];

	$nombre_empresa = mysqli_query($conn, "SELECT * FROM empresas WHERE id = '$receptor' LIMIT 1 ");
	$ob_empresa = mysqli_fetch_object($nombre_empresa);
	$nombre = $ob_empresa->nombre_empresa;
	$usuario_ftp = $ob_empresa->usuario_ftp;
	$clave_ftp = $ob_empresa->clave_ftp;

	if (mysqli_query($conn, "INSERT INTO facturacion_mayor (`id_empresa`,`receptor`, `fecha_expedicion`, `no_factura`, `id_sorteo`, `fecha_sorteo`, `cantidad`, `valor_nominal`, `descuento`, `rebaja_depositario`, `valor_neto`) VALUES ('$id_empresa','$nombre','$fecha_expedicion', '$no_factura','$sorteo','$fecha_sorteo','$cantidad_asignada','$valor_nominal','$descuento','$rebaja','$valor_neto' ) ") === TRUE) {

		$consulta_id = mysqli_query($conn, "SELECT * FROM facturacion_mayor WHERE no_factura = '$no_factura' ");
		$ob_factura = mysqli_fetch_object($consulta_id);
		$id = $ob_factura->id;

		$_SESSION['factura_mayor'] = $id;

		if (mysqli_query($conn, "UPDATE sorteos_mezclas as a  SET a.cod_factura = '$no_factura' WHERE   a.id_sorteo = '$sorteo' AND  a.id_empresa = '$id_empresa' AND a.cod_factura IS NULL  ") === false) {
			echo mysqli_error();
		}
///////////////////////////////////////////////////////////////
		/////////////////// CREACION DE ARCHIVO PLANO /////////////////
		if ($usuario_ftp != "") {

			$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo' ");
			$ob_sorteo = mysqli_fetch_object($info_sorteo);
			$fecha_sorteo = $ob_sorteo->fecha_sorteo;
			$mezcla = $ob_sorteo->mezcla;
			$precio_unitario = $ob_sorteo->precio_unitario;
			$precio_unitario *= 10;

			$content = $id_sorteo . "$" . $precio_unitario . "$" . $fecha_sorteo . "\n";

			$inventario = mysqli_query($conn, "SELECT b.num_mezcla,b.rango FROM sorteos_mezclas as a INNER JOIN sorteos_mezclas_rangos as b ON a.id_sorteo = b.id_sorteo AND a.num_mezcla = b.num_mezcla WHERE a.id_sorteo = '$id_sorteo' AND a.id_empresa = '$id_empresa' AND cod_factura = '$no_factura' ");

			while ($reg_inventario = mysqli_fetch_array($inventario)) {
				$rango_final = $reg_inventario['rango'] + $mezcla - 1;
				$content .= $reg_inventario['num_mezcla'] . "$" . $reg_inventario['rango'] . "$" . $rango_final . "\n";
			}

			$fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/PANEL-SGLP-F/gerencia_financiera/deposito_billetes/asignacion.txt", "wb");
			fwrite($fp, $content);
			fclose($fp);

/* File and path to send to remote FTP server */
			$local_file = "asignacion.txt";

/* Remote File Name and Path */
			$remote_file = "ASIGNACIONES/" . $id_sorteo . ".txt";

/* FTP Account (Remote Server) */
			$ftp_host = '192.168.15.17'; /* host */
			$ftp_user_name = $usuario_ftp; /* username */
			$ftp_user_pass = $clave_ftp; /* password */

/* Connect using basic FTP */
			$connect_it = ftp_connect($ftp_host);

/* Login to FTP */
			$login_result = ftp_login($connect_it, $ftp_user_name, $ftp_user_pass);

/* Send $local_file to FTP */

			if (ftp_put($connect_it, $remote_file, $local_file, FTP_BINARY)) {

			} else {

			}

/* Close the connection */
			ftp_close($connect_it);

		}
////////////////// CREACION DE ARCHIVO PLANO //////////////////
		///////////////////////////////////////////////////////////////

		?>

<script type="text/javascript">
swal({
title: "",
text: "Registro guardado correctamente",
type: "success"
})
.then(() => {
window.open("./print_acta_entrega_mayor.php");
});
</script>

<?php

	} else {
		echo mysqli_error();
	}

}

if (isset($_POST['aceptar_anulacion'])) {

	$cod_factura = $_POST['id_factura_oculto'];
	$user_autorizado = $_POST['username'];
	$pass_autorizado = md5($_POST['password']);
	$p = $_POST['password'];
	$u = $user_autorizado;
//
	$wsdl = "http://192.168.15.17/PANIAD_LOGIN/_GetPaniLogin.php?wsdl";
	$cliente = new nusoap_client($wsdl, true);
	$cliente->soap_defencoding = 'utf-8'; //default is
	$cliente->response_timeout = 200; //seconds
	$cliente->useHTTPPersistentConnection();

	$result = $cliente->call("PaniGetlogin", array("usuario" => $u, "password" => $p));

	if ($result == 1) {

		$c_user = mysqli_query($conn, "SELECT * FROM pani_usuarios WHERE  areas_id = 10 AND roles_usuarios_id = 1 AND estados_id = 1 AND usuario = '$user_autorizado'  LIMIT 1  ");
		$count = 1;

	} else {

		$count = 0;

	}

	if ($count == 1) {

		$update_inventario = mysqli_query($conn, "UPDATE sorteos_mezclas SET  cod_factura = NULL  WHERE  cod_factura = '$cod_factura' ");

		if ($update_inventario === FALSE) {
			echo mysqli_error();
		} else {

			$update_inventario = mysqli_query($conn, "UPDATE facturacion_mayor SET estado = 'C'  WHERE no_factura = '$cod_factura' ");

			if ($update_inventario === FALSE) {
				echo mysqli_error();
			} else {

				echo "<div class = 'alert alert-info'>Factura " . $cod_factura . " anulada correctamente.</div>";

			}

		}

	} else {

		echo "<div class = 'alert alert-danger'>Usuario o contraseña de autorizacion incorrectos.</div>";

	}

}

if (isset($_POST['generar_ftp'])) {

	$info = $_POST['generar_ftp'];
	$v_info = explode("$", $info);
	$id_sorteo = $v_info[0];
	$id_empresa = $v_info[1];
	$usuario_ftp = $v_info[2];
	$clave_ftp = $v_info[3];
	$cod_factura = $v_info[4];

	$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo' ");
	$ob_sorteo = mysqli_fetch_object($info_sorteo);
	$fecha_sorteo = $ob_sorteo->fecha_sorteo;
	$mezcla = $ob_sorteo->mezcla;
	$precio_unitario = $ob_sorteo->precio_unitario;
	$precio_unitario *= 10;

	$content = $id_sorteo . "$" . $precio_unitario . "$" . $fecha_sorteo . "\n";

	$inventario = mysqli_query($conn, "SELECT b.num_mezcla,b.rango FROM sorteos_mezclas as a INNER JOIN sorteos_mezclas_rangos as b ON a.id_sorteo = b.id_sorteo AND a.num_mezcla = b.num_mezcla WHERE a.id_sorteo = '$id_sorteo' AND a.id_empresa = '$id_empresa' AND cod_factura = '$cod_factura' ");

	while ($reg_inventario = mysqli_fetch_array($inventario)) {
		$rango_final = $reg_inventario['rango'] + $mezcla - 1;
		$content .= $reg_inventario['num_mezcla'] . "$" . $reg_inventario['rango'] . "$" . $rango_final . "\n";
	}

	$fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/PANEL-SGLP-F/gerencia_financiera/deposito_billetes/asignacion.txt", "wb");
	fwrite($fp, $content);
	fclose($fp);

/* File and path to send to remote FTP server */
	$local_file = "asignacion.txt";

/* Remote File Name and Path */
	$remote_file = "ASIGNACIONES/" . $id_sorteo . ".txt";

/* FTP Account (Remote Server) */
	$ftp_host = '192.168.15.17'; /* host */
	$ftp_user_name = $usuario_ftp; /* username */
	$ftp_user_pass = $clave_ftp; /* password */

/* Connect using basic FTP */
	$connect_it = ftp_connect($ftp_host);

/* Login to FTP */
	$login_result = ftp_login($connect_it, $ftp_user_name, $ftp_user_pass);

/* Send $local_file to FTP */

	if (ftp_put($connect_it, $remote_file, $local_file, FTP_BINARY)) {
		echo "<div class = 'alert alert-info'>TXT generado correctamente, la entidad ya puede hacer lectura del archivo de asignacion de loteria.</div>";
	} else {
		echo "<div class = 'alert alert-danger'>Error inesperado, por favor intente nuevamente.</div>";
	}

/* Close the connection */
	ftp_close($connect_it);

}

?>