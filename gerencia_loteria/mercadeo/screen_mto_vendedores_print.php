<?php

require '../../template/header.php';

$asociaciones = mysqli_query($conn, "SELECT a.id, a.identidad ,a.tipo_identificacion, a.codigo, a.nombre, a.direccion, a.foto, a.asociacion, a.telefono, a.estado, a.estado_civil , a.sexo , a.zona_venta , b.nombre_asociacion, a.geocodigo FROM vendedores as a INNER JOIN asociaciones_vendedores as b ON a.asociacion = b.codigo_asociacion ");

$total = mysqli_num_rows($asociaciones);

$c_resumen = mysqli_query($conn, "SELECT COUNT(*) as conteo, asociacion FROM vendedores WHERE asociacion != '' GROUP BY asociacion ORDER BY asociacion ASC ");

?>


<table class="table table-bordered" >
  <tr>
    <th colspan="3">
      RESUMEN DE VENDEDORES AUTORIZADOS POR ASOCIACION
    </th>
  </tr>

<?php

$conteo_sin = 0;
$acum = 0;

$CurrentYear =  date("Y");

while ($reg_resumen = mysqli_fetch_array($c_resumen)) {

	$acum += $reg_resumen['conteo'];

	if ($reg_resumen['asociacion'] == '' OR $reg_resumen['asociacion'] == 'C') {
		$conteo_sin = $reg_resumen['conteo'];
	}

	if ($reg_resumen['asociacion'] != '' AND $reg_resumen['asociacion'] != 'C') {

		echo "<tr>";
		echo "<td>";
		if ($reg_resumen['asociacion'] == 'A') {
			echo "ANAVELH";
		} else {
			echo "ANVLUH";
		}
		echo "</td>";
		echo "<td>";
		echo $reg_resumen['conteo'];
		echo "</td>";

		$porcentual = ($reg_resumen['conteo'] / $total) * 100;

		echo "<td>";
		echo number_format($porcentual, '2') . " %";
		echo "</td>";

		echo "</tr>";

	}

}

echo "<tr>";
echo "<td>";
echo "SIN ASOCIACION";
echo "</td>";
echo "<td>";
echo $conteo_sin;
echo "</td>";

$porcentual = ($conteo_sin / $total) * 100;
$porcentual = round($porcentual, 2);

echo "<td>";
echo number_format($porcentual, '2') . " %";
echo "</td>";

echo "</tr>";

echo "<tr>";
echo "<th>";
echo "TOTAL";
echo "</th>";
echo "<th>";
echo number_format($acum);
echo "</th>";

$porcentual = ($acum / $total) * 100;
$porcentual = round($porcentual);

echo "<th>";
echo $porcentual . " %";
echo "</th>";

echo "</tr>";

?>

<tr></tr>

</table>


<table class="table table-bordered" style="font-size: 10px" >

  <thead>

  	<tr>
    <th colspan="8" align="center"> <p align="center"> LISTADO DE VENDEDORES</p></th>
</tr>

  	<tr>
    <th>Identidad</th>
    <th>Nombre</th>
    <th>Edad</th>
    <th>Asociacion</th>
    <th>Genero</th>
    <th>Zona de venta</th>
    <th>Telefono</th>
    <th>Estado</th>
</tr>
  </thead>
  <tbody>
<?php
while ($reg_asociacion = mysqli_fetch_array($asociaciones)) {

	$id = $reg_asociacion['id'];
	$identidad = $reg_asociacion['identidad'];
	$nom = $reg_asociacion['nombre'];
	$nombre_asociacion = $reg_asociacion['nombre_asociacion'];
	$cod = $reg_asociacion['codigo'];
	$telefono = $reg_asociacion['telefono'];
	$direccion = $reg_asociacion['direccion'];
	$foto = $reg_asociacion['foto'];
	$sexo = $reg_asociacion['sexo'];
	$estado_civil = $reg_asociacion['estado_civil'];
	$zona_venta = $reg_asociacion['zona_venta'];
	$tipo_id = $reg_asociacion['tipo_identificacion'];
	$geocodigo = $reg_asociacion['geocodigo'];

	echo "<tr>";
	echo "<td>";
	echo $reg_asociacion['identidad'];
	echo "</td>";
	echo "<td>";
	echo $reg_asociacion['nombre'];
	echo "</td>";

	if (strlen($reg_asociacion['identidad']) == 13){
		$edad = substr($reg_asociacion['identidad'], 4, 4);
		$edad = $CurrentYear - $edad; 
	  }else{
		$edad = "";
	  }
	  echo "<td>";
	  echo $edad;
	  echo "</td>";
	  
	echo "<td>";
	echo $reg_asociacion['nombre_asociacion'];
	echo "</td>";
	echo "<td>";
	echo $reg_asociacion['sexo'];
	echo "</td>";
	echo "<td>";
	echo $reg_asociacion['zona_venta'];
	echo "</td>";
	echo "<td>";
	echo $reg_asociacion['telefono'];
	echo "</td>";
	echo "<td>";
	if ($reg_asociacion['estado'] == 1) {
		echo "ACTIVO";
		}else{
		echo "INACTIVO";
		}
	echo "</td>";
	echo "</tr>";
}
?>
  </tbody>
</table>

