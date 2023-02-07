<?php
require('../../template/header.php');

//require('../conexion.php');
header('Content-Type: text/html; charset=ISO-8859-1');


$id_vendedor = $_GET['v'];

$consulta_vendedor = mysqli_query($conn,"SELECT * FROM vendedores WHERE id = '$id_vendedor' ");
$ob_vendedor = mysqli_fetch_object($consulta_vendedor);

$identidad 			  = $ob_vendedor->identidad;

$identidad1 = substr($identidad, 0,4);
$identidad2 = substr($identidad, 4,4);
$identidad3 = substr($identidad, 8,5);

$identidad = $identidad1."-".$identidad2."-".$identidad3;

$nombre_vendedor 	  = $ob_vendedor->nombre;
$nombre_vendedor 	  = substr($nombre_vendedor, 0,35);


$tipo_identificacion  = $ob_vendedor->tipo_identificacion;
$codigo 			  = $ob_vendedor->codigo;
$asociacion 		  = $ob_vendedor->asociacion;
$sexo  				  = $ob_vendedor->sexo;
$estado_civil 		  = $ob_vendedor->estado_civil;
$zona_venta 		  = $ob_vendedor->zona_venta;

$zona_venta 		  = substr($zona_venta, 0,25);

$discapacidad 		  = $ob_vendedor->discapacidad;
$tipo_sangre 		  = $ob_vendedor->tipo_sangre;

$telefono 			  = $ob_vendedor->telefono;
$direccion 			  = $ob_vendedor->direccion;
$estado 			  = $ob_vendedor->estado;
$fecha_creacion 	  = $ob_vendedor->fecha_creacion;
$geocodigo 			  = $ob_vendedor->geocodigo;
$usuario_creacion     = $ob_vendedor->id_usuario_creacion;
$seccional     		  = $ob_vendedor->seccional;
$bolsas     		  = $ob_vendedor->numero_bolsas;
$foto     		      = $ob_vendedor->foto;
$rand               = rand(0, 99999);
$foto               = $foto."?rand".$rand;

$ruta_foto 			  = "./imagenes/vendedores/".$foto;

if ($estado == 1) {
$desc_estado = "ACTIVO";
}else{
$desc_estado = "INACTIVO";
}

if ($asociacion == "A") {
$desc_asociacion = "ANAVELH";
}elseif ($asociacion == "B") {
$desc_asociacion = "ANVLUH";
}else{
$desc_asociacion = "SIN ASOCIACION";	
}


if ($sexo == "M") {
$desc_sexo = "MASCULINO";
}else{
$desc_sexo = "FEMENINO";	
}


if ($estado_civil == "S") {
$desc_civil = "SOLTERO";
}elseif ($estado_civil == "C") {
$desc_civil = "CASADO";
}elseif ($estado_civil == "V") {
$desc_civil = "VIUDO";
}elseif ($estado_civil == "D") {
$desc_civil = "DIVORCIADO";
}

$consulta_geocodigo = mysqli_query($conn,"SELECT * FROM geocodigos WHERE cod_muni = '$geocodigo'  ");

if (mysqli_num_rows($consulta_geocodigo) > 0) {
$ob_geocodigo = mysqli_fetch_object($consulta_geocodigo);
$municipio    = $ob_geocodigo->municipio;
$depto 		  = $ob_geocodigo->dpto;
}else{
$municipio    = "";
$depto 		  = "";	
}

if ($usuario_creacion != '') {
$consulta_usuario_creacion = mysqli_query($conn,"SELECT * FROM pani_usuarios WHERE id = '$usuario_creacion' ");
$ob_usuario_creacion = mysqli_fetch_object($consulta_usuario_creacion);
$usuario = $ob_usuario_creacion->usuario;
$nombre  = $ob_usuario_creacion->nombre_completo;
}else{
$usuario = "";
$nombre  = "";	
}

if ($tipo_identificacion == 1) {
$identificacion = "Tarjeta Identidad";
}elseif ($tipo_identificacion == 2) {
$identificacion = "No. Residencia";
}elseif ($tipo_identificacion == 3) {
$identificacion = "Pasaporte";
}elseif ($tipo_identificacion == 4) {
$identificacion = "No especificado";	
}

?>



<style type="text/css">
	@media print 
{
   @page
   {
    size: landscape;
  }
}
</style>


<!--
<div style="width: 336px; height: 192px; background-color: grey">
-->



<?php 

if ($asociacion == "A") {

?>

<div style="width: 486px ; height: 305px; margin-top: -61px;" id="container_2">

<img id="i_anavelh" src="./imagenes/anavelh.png" width="100%" height="100%" style="position: relative;">

<div style=" position:absolute; width:270px; height:30px;">
<span style=" font-size: 16px; position: absolute; margin-top: -220px; margin-left: 60px; color: #1B3F88; font-weight: bold" ><?php echo $nombre_vendedor; ?> </span>
</div>

<div style="background-color: #001F89; position:absolute; width:250px; height:30px;">
<span style=" font-size: 18px; position: absolute; margin-top: -170px; margin-left: 60px; color: #1B3F88; font-weight: bold" ><?php echo $identidad; ?> </span>
</div>

<span style=" font-size: 12px; position: absolute; margin-top: -135px; margin-left: 60px;  color: black; font-weight: bold;" >AFILIADO: <?php echo $asociacion."-".$seccional."-".$codigo; ?> </span>
<span style=" font-size: 12px; position: absolute; margin-top: -120px; margin-left: 60px;  color: black; font-weight: bold;" >BOLSAS: <?php echo $bolsas; ?> </span>
<span style=" font-size: 12px; position: absolute; margin-top: -105px; margin-left: 60px;  color: black; font-weight: bold;" >Z. VENTA: </span>
<span style=" font-size: 12px; position: absolute; margin-top: -90px; margin-left: 60px;  color: black; font-weight: bold;" > <?php echo strtoupper($zona_venta); ?> </span>

<span style=" font-size: 11px; position: absolute; margin-top: -88px; margin-left: 275px;  color: black; font-weight: bold;" >SECTOR DISCAPACIDAD: <?php echo $discapacidad; ?> </span>
<span style=" font-size: 11px; position: absolute; margin-top: -73px; margin-left: 275px;  color: black; font-weight: bold;" >SANGRE: <?php echo $tipo_sangre; ?> </span>


<img style="margin-top: -345px; margin-left: 300px;" width="130px"; height ="135px" src="<?php echo $ruta_foto; ?>" >

</div>

<div style="width: 486px ; height: 305px;">
	<img src="./imagenes/back_anavelh.png" width="100%" height="100%" style="position: relative;">
</div>


<?php

}elseif ($asociacion == "B") {


?>

<div style="width: 486px ; height: 305px; margin-top: -61px;" id="container_2">

<img id="i_anavelh" src="./imagenes/anvluh.png" width="100%" height="100%" style="position: relative;">

<div style=" position:absolute; width:270px; height:30px;">
<span style=" font-size: 16px; position: absolute; margin-top: -220px; margin-left: 60px; color: #1B3F88; font-weight: bold" ><?php echo $nombre_vendedor; ?> </span>
</div>

<div style="background-color: #001F89; position:absolute; width:250px; height:30px;">
<span style=" font-size: 18px; position: absolute; margin-top: -170px; margin-left: 60px; color: #1B3F88; font-weight: bold" ><?php echo $identidad; ?> </span>
</div>

<span style=" font-size: 12px; position: absolute; margin-top: -135px; margin-left: 60px;  color: black; font-weight: bold;" >AFILIADO: <?php echo $asociacion."-".$seccional."-".$codigo; ?> </span>
<span style=" font-size: 12px; position: absolute; margin-top: -120px; margin-left: 60px;  color: black; font-weight: bold;" >BOLSAS: <?php echo $bolsas; ?> </span>
<span style=" font-size: 12px; position: absolute; margin-top: -105px; margin-left: 60px;  color: black; font-weight: bold;" >Z. VENTA: </span>
<span style=" font-size: 12px; position: absolute; margin-top: -90px; margin-left: 60px;  color: black; font-weight: bold;" > <?php echo strtoupper($zona_venta); ?> </span>

<span style=" font-size: 11px; position: absolute; margin-top: -88px; margin-left: 275px;  color: black; font-weight: bold;" >SECTOR DISCAPACIDAD: <?php echo $discapacidad; ?> </span>
<span style=" font-size: 11px; position: absolute; margin-top: -73px; margin-left: 275px;  color: black; font-weight: bold;" >SANGRE: <?php echo $tipo_sangre; ?> </span>



<img style="margin-top: -345px; margin-left: 300px;" width="130px"; height ="135px" src="<?php echo $ruta_foto; ?>" >

</div>

<div style="width: 486px ; height: 305px;">
	<img src="./imagenes/back_anvluh.png" width="100%" height="100%" style="position: relative;">
</div>

<?php

}elseif ($asociacion == "C") {

?>
<div style="width: 486px ; height: 305px; margin-top: -61px;" id="container_2">

<img id="i_anavelh" src="./imagenes/independiente.png" width="100%" height="100%" style="position: relative;">

<div style=" position:absolute; width:270px; height:30px;">
<span style=" font-size: 16px; position: absolute; margin-top: -220px; margin-left: 60px; color: #1B3F88; font-weight: bold" ><?php echo $nombre_vendedor; ?> </span>
</div>

<div style="background-color: #001F89; position:absolute; width:250px; height:30px;">
<span style=" font-size: 18px; position: absolute; margin-top: -170px; margin-left: 60px; color: #1B3F88; font-weight: bold" ><?php echo $identidad; ?> </span>
</div>

<span style=" font-size: 12px; position: absolute; margin-top: -135px; margin-left: 60px;  color: black; font-weight: bold;" >AFILIADO: <?php echo $asociacion."-".$seccional."-".$codigo; ?> </span>
<span style=" font-size: 12px; position: absolute; margin-top: -120px; margin-left: 60px;  color: black; font-weight: bold;" >BOLSAS: <?php echo $bolsas; ?> </span>
<span style=" font-size: 12px; position: absolute; margin-top: -105px; margin-left: 60px;  color: black; font-weight: bold;" >Z. VENTA: </span>
<span style=" font-size: 12px; position: absolute; margin-top: -90px; margin-left: 60px;  color: black; font-weight: bold;" > <?php echo strtoupper($zona_venta); ?> </span>

<span style=" font-size: 11px; position: absolute; margin-top: -88px; margin-left: 275px;  color: black; font-weight: bold;" >SECTOR DISCAPACIDAD: <?php echo $discapacidad; ?> </span>
<span style=" font-size: 11px; position: absolute; margin-top: -73px; margin-left: 275px;  color: black; font-weight: bold;" >SANGRE: <?php echo $tipo_sangre; ?> </span>

<img style="margin-top: -345px; margin-left: 300px;" width="130px"; height ="135px" src="<?php echo $ruta_foto; ?>" >

</div>

<div style="width: 486px ; height: 305px;">
	<img src="./imagenes/back.png" width="100%" height="100%" style="position: relative;">
</div>

<?php

}

?>

<script type="text/javascript">
window.print();
</script>
