<?php

require "../../conexion.php";

$identidad = $_GET["id"];
$filtro = $_GET["filtro"];
$tipo_id = $_GET["tipo_id"];

if ($tipo_id == 1) {

	$v_id = explode("-", $identidad);
	$identidad = $v_id[0] . $v_id[1] . $v_id[2];

}

$c_tbl_vendedores = mysqli_query($conn, "SELECT * FROM censo_2017 WHERE identidad = '$identidad' LIMIT 1 ");

if (mysqli_num_rows($c_tbl_vendedores) > 0) {

	$ob_consulta = mysqli_fetch_object($c_tbl_vendedores);
	$nombre = $ob_consulta->nombre_completo;

	$c_asociacion = mysqli_query($conn, "SELECT asociacion FROM vendedores WHERE identidad = '$identidad' ");

	if (mysqli_num_rows($c_asociacion) > 0) {

		$ob_asociacion = mysqli_fetch_object($c_asociacion);
		$asociacion = $ob_asociacion->asociacion;

	} else {

		$asociacion = "C";

	}

	if ($asociacion == "C" OR $asociacion == "" OR $asociacion == NULL) {
		$asociacion = "SIN ASOCIACION";
	} elseif ($asociacion == "A") {
		$asociacion = "ANAVELH";
	} elseif ($asociacion == "B") {
		$asociacion = "ANVLUH";
	}

	?>

<div class="input-group " style="margin-top: 5px">
<div class="input-group-prepend" >
<div class="input-group-text">Nombre</div>
</div>
<input style="text-transform:uppercase" type="text" class="form-control" id = 'nombre' name="nombre" value="<?php echo $nombre; ?>"  readonly>
</div>

<?php

} else {

	$c_tbl_ventas = mysqli_query($conn, " SELECT identidad_comprador, nombre_comprador, fecha_venta FROM (  SELECT * FROM  (SELECT  identidad_comprador, nombre_comprador, fecha_venta FROM transaccional_ventas WHERE identidad_comprador = '$identidad' ORDER BY id DESC LIMIT 1) as a  UNION ALL SELECT * FROM ( SELECT  identidad_comprador, nombre_comprador , fecha_venta FROM transaccional_ventas_general   WHERE identidad_comprador = '$identidad' ORDER BY id DESC LIMIT 1) as b  ) as d ORDER BY fecha_venta DESC LIMIT 1  ");

	if ($c_tbl_ventas === FALSE) {
		echo mysqli_error($conn);
	}

	if (mysqli_num_rows($c_tbl_ventas) > 0) {
		$ob_consulta = mysqli_fetch_object($c_tbl_ventas);
		$nombre = $ob_consulta->nombre_comprador;

		?>

<div class="input-group " style="margin-top: 5px">
<div class="input-group-prepend" >
<div class="input-group-text">Nombre</div>
</div>
<input style="text-transform:uppercase" type="text" class="form-control" id = 'nombre' name="nombre" value="<?php echo $nombre; ?>" >
</div>

		<?php

	} else {

		?>

<div class="input-group " style="margin-top: 5px">
<div class="input-group-prepend" >
<div class="input-group-text">Nombre</div>
</div>
<input style="text-transform:uppercase" type="text" class="form-control" id = 'nombre' name="nombre"  >
</div>



<?php
echo '<br><div class="alert alert-danger">Comprador no encontrado, por favor ingrese el nombre del comprador.</div> ';

	}
}

?>