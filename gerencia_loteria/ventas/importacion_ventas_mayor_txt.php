<?php
require "../../template/header.php";
date_default_timezone_set('America/Tegucigalpa');

?>

<form  enctype="multipart/form-data" method="post" action="" accept-charset="UTF-8">



<br>

<ul class="nav nav-tabs">
<li class="nav-item">
<a   class="nav-link" href="./screen_importacion_ventas_mayor.php"  >Lotería Mayor (EXCEL)</a>
</li>
<li class="nav-item">
<a style="background-color:#ededed;" class="nav-link active"  >Lotería Mayor (FTP)</a>
</li>
<li class="nav-item">
<a  class="nav-link" href="./importacion_ventas_menor.php">Lotería Menor</a>
</li>
</ul>

<section style="background-color:#ededed;">
<br>
<h3 align="center"><b>IMPORTACION DE VENTAS LOTERIA MAYOR  (FTP)</b></h3>
<br>
</section>



<a style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
Seleccion de Parametros
</a>





<div class="card collapse" id="collapse1" style="margin-left: 15px; margin-right: 15px;" >
<div class="card-body">

<table class="table table-bordered">
<tr>
<th width="20%">SORTEO</th>
<th width="20%">ENTIDAD</th>
<th width="10%">ACCION</th>
</tr>

<tr>
<td>
<select class="form-control"  name = "sorteo" id = 'sorteo'   style="margin-right: 5px;">
<?php
$sorteos = mysqli_query($conn, "SELECT a.id, a.no_sorteo_may, a.fecha_sorteo, a.descripcion_sorteo_may  FROM sorteos_mayores as a inner join empresas_estado_venta as b ON a.id = b.id_sorteo WHERE  b.estado_venta != 'F'  AND b.cod_producto = 1 GROUP BY b.id_sorteo ORDER BY a.id DESC ");

while ($row2 = mysqli_fetch_array($sorteos)) {
	echo '<option value = "' . $row2['id'] . '">No.' . $row2['no_sorteo_may'] . ' -- Fecha ' . $row2['fecha_sorteo'] . ' -- ' . $row2['descripcion_sorteo_may'] . '</option>';
}
?>
</select>
</td>

<td>
<select  onchange="" class="form-control" name="select_empresa" id = 'select_empresa'  style="margin-right: 5px;">
<option>Seleccione una opcion</option>
<?php
$empresas = mysqli_query($conn, "SELECT * FROM empresas WHERE estado = 'activo' AND usuario_ftp IS NOT NULL ");
while ($empresa = mysqli_fetch_array($empresas)) {
	echo "<option value = '" . $empresa['id'] . "$" . $empresa['usuario_ftp'] . "$" . $empresa['clave_ftp'] . "'>" . $empresa['nombre_empresa'] . "</option>";
}
?>
</select>
</td>


<td align="center">
<input type="submit" class="btn btn-success" name ="importar" value="Importar">
</td>
</tr>

</table>

</div>
</div>


<?php

if (isset($_POST["importar"])) {

	$ftp_info = $_POST['select_empresa'];

	$v_info = explode("$", $ftp_info);

	$id_entidad = $v_info[0]; /* ID */
	$ftp_user_name = $v_info[1]; /* username */
	$ftp_user_pass = $v_info[2]; /* password */


	$c_seccional_unica = mysqli_query($conn, "SELECT * FROM fvp_seccionales WHERE id_empresa = '$id_entidad' LIMIT 1 ");
	$ob_seccional = mysqli_fetch_object($c_seccional_unica);
	$id_seccional = $ob_seccional->id; 


// connect and login to FTP server
	$ftp_server = "192.168.15.17";
	$ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
	$login = ftp_login($ftp_conn, $ftp_user_name, $ftp_user_pass);

	$s = $_POST['sorteo'];

	$local_file = "importacion.txt";
	$server_file = "VENTAS/ventas_" . $s . ".txt";

// Consulta de importaciones previas

	$c_ventas = mysqli_query($conn, "SELECT * FROM transaccional_ventas WHERE id_sorteo = '$s' AND id_entidad = '$id_entidad' AND estado_venta = 'APROBADO'    ");

	if (mysqli_num_rows($c_ventas) == 0) {

// download server file
		if (ftp_get($ftp_conn, $local_file, $server_file, FTP_ASCII)) {

////////////////////////////////////////////////////////////////////////////////////////////////////
			/////////////////////////////////////// PARAMETROS DE VENTA ////////////////////////////////////////

			//$conn = mysqli_connect('localhost', 'root', '', 'pani') or die('No se pudo conectar: ' . mysqli_error());
			//$conn = mysqli_connect('192.168.15.248', 'SVR_APP', 'softlotpani**', 'pani') or die('No se pudo conectar: ' . mysqli_error());

			$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE id = '$s' ");
			$ob_sorteo = mysqli_fetch_object($info_sorteo);
			$precio_unitario = $ob_sorteo->precio_unitario;
			$precio_unitario = $precio_unitario * 10;

//$precio_unitario = 10 * 10;

			$parametros_venta = mysqli_query($conn, "SELECT * FROM empresas WHERE id = '$id_entidad' ");
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

/////////////////////////////////////// PARAMETROS DE VENTA //////////////////////////////////////////
			//////////////////////////////////////////////////////////////////////////////////////////////////////

			echo "<br>";
			echo "<br>";
			echo "<div class = 'card' style = 'margin-left: 10px;margin-right: 10px'>";
			echo "<div class = 'card-header bg-secondary text-white'>";
			echo "<h3 style = 'text-align:center '>IMPORTACION </h3>";
			echo "</div>";

			echo "<table class = 'table table-bordered' >";
			echo "<tr><th>No.</th><th>Sorteo</th><th>Billete</th><th>Identidad Comprador</th><th>Nombre Comprador</th><th>Precio de Venta</th><th>Cajero</th><th>Terminal</th><th>Fecha</th><th>Hora</th><th>Agencia</th><th>Descripcion A.</th></tr>";

			$texto = "";

			chmod("importacion.txt", 0777);

			$file = "importacion.txt";
			$f = fopen($file, "r");

			$i = 0;
			while ($line = fgets($f)) {

				$longitud = strlen($line);
				$registros = $longitud / 165;

				$i = 0;
				while ($i < $registros) {

					$linea_registro = substr($line, 0, 165);

					$sorteo = substr($line, 0, 6);
					$billete = substr($line, 6, 6);
					$identidad = substr($line, 12, 20);
					$identidad_formato = str_replace( " ", "", $identidad);
					$nombre = substr($line, 32, 40);
					$monto = substr($line, 72, 9);
					$cajero = substr($line, 81, 10);
					$terminal = substr($line, 91, 10);
					$fecha = substr($line, 101, 8);
					$hora = substr($line, 109, 8);
					$agencia = substr($line, 116, 3);
					$d_agencia = substr($line, 120, 45);

					echo "<tr>";
					echo "<td>" . $i . "</td>";
					echo "<td>" . $sorteo . "</td>";
					echo "<td>" . $billete . "</td>";
					echo "<td>" . $identidad . "</td>";
					echo "<td>" . $nombre . "</td>";
					echo "<td>" . $monto . "</td>";
					echo "<td>" . $cajero . "</td>";
					echo "<td>" . $terminal . "</td>";
					echo "<td>" . $fecha . "</td>";
					echo "<td>" . $hora . "</td>";
					echo "<td>" . $agencia . "</td>";
					echo "<td>" . $d_agencia . "</td>";
					echo "</tr>";

					$bandera_transaccional = 0;

////////////////////////////////////////////////////////////
					///////////// INSERT TRANSACCIONAL /////////////////////////

					$neto_total = $precio_unitario - $monto_descuento;
					$credito_pani = $neto_total - $monto_comision;
					$concat_descripcion = $agencia . " - " . $d_agencia;

if (mysqli_query($conn, " INSERT INTO `transaccional_ventas`(`cod_factura`, `id_sorteo`, `id_entidad`, `cantidad`, `precio_unitario`, `total_bruto`, `descuento`, `total_neto`, `comision_bancaria`, `credito_pani`, `id_usuario`, `id_seccional`, `identidad_comprador`, `nombre_comprador`, `estado_venta`, `forma_pago`, `cod_producto`,importacion, descripcion_agencia)
SELECT(SELECT MAX(cod_factura)+1 from transaccional_ventas), '$sorteo', '$id_entidad', '1', '$precio_unitario', '$precio_unitario', '$monto_descuento', '$neto_total', '$monto_comision', '$credito_pani', '161', '$id_seccional', '$identidad_formato', '$nombre' , 'APROBADO' , '2' , '1','d' , '$d_agencia' ") === false) {

$bandera_transaccional = 1;
echo mysqli_error($conn);

} else {

$busqueda_factura = mysqli_query($conn, "SELECT MAX(cod_factura) as maximo FROM transaccional_ventas WHERE id_usuario = '161' AND id_seccional = '$id_seccional' AND id_sorteo = '$s' AND id_entidad = '$id_entidad' ");

$ob_max_factura = mysqli_fetch_object($busqueda_factura);
$cod_factura = $ob_max_factura->maximo;

mysqli_query($conn, "INSERT INTO fvp_detalles_ventas_mayor (billete,precio_unitario,decimos,cod_factura,id_sorteo,estado_venta) VALUES ('$billete','$monto','10','$cod_factura','$sorteo','APROBADO' ) ");

}
 
//////////// FIN INSERT TRANSACCIONAL ///////////////////
					/////////////////////////////////////////////////////////

					$line = substr($line, 165);

					$i++;
				}

				echo "<tr><th colspan = '4'>TOTAL REGISTROS</th><th colspan = '8'>" . $i . "</th></tr>";

			}

			echo "</table>";
			echo "</div>";

		} else {

			echo "Error downloading $server_file.";

		}

	} else {

		echo "<div class = 'alert alert-danger'>Ya existen ventas de la entidad y sorteo seleccionados, para evitar duplicidades por favor verifique el historico de ventas de los mismos.</div>";

	}

// close connection
	ftp_close($ftp_conn);

}

?>

</form>
