<?php
require('../../template/header.php');


$id_vendedor = $_GET['v'];

$consulta_vendedor = mysqli_query($conn,"SELECT * FROM vendedores WHERE id = '$id_vendedor' ");
$ob_vendedor = mysqli_fetch_object($consulta_vendedor);

$identidad 			  = $ob_vendedor->identidad;
$nombre_vendedor 	  = $ob_vendedor->nombre;
$tipo_identificacion  = $ob_vendedor->tipo_identificacion;
$codigo 			  = $ob_vendedor->codigo;
$asociacion 		  = $ob_vendedor->asociacion;
$sexo  				  = $ob_vendedor->sexo;
$estado_civil 		  = $ob_vendedor->estado_civil;
$zona_venta 		  = $ob_vendedor->zona_venta;
$telefono 			  = $ob_vendedor->telefono;
$direccion 			  = $ob_vendedor->direccion;
$estado 			  = $ob_vendedor->estado;
$fecha_creacion 	  = $ob_vendedor->fecha_creacion;
$geocodigo 			  = $ob_vendedor->geocodigo;
$usuario_creacion     = $ob_vendedor->id_usuario_creacion;


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

<br>
<div  class="card" style="margin-right: 10px; margin-left: 10px" >
<div align="center" class="card-header bg-info text-white">
<h3 > FICHA DE VENDEDOR </h3>	
</div>

<div class="card-body">


<table width="100%">
<tr>
<td width="24%" valign="top" >
	

<div style="width: 100%" class="input-group">
<div class="input-group-prepend"><span class="input-group-text" style="width:170px " ><?php echo $identificacion; ?></span></div>
<input class="form-control"  readonly="true" value="<?php echo $identidad;?>">    
</div>

<br>

<div style="width: 100%" class="input-group">
<div class="input-group-prepend"><span class="input-group-text" style="width:170px " >Nombre Completo</span></div>
<input class="form-control"  readonly="true" value="<?php echo $nombre_vendedor;?>">    
</div>

<br>

<div style="width: 100%" class="input-group">
<div class="input-group-prepend"><span class="input-group-text" style="width:170px " >Codigo Vendedor</span></div>
<input class="form-control"  readonly="true" value="<?php echo $codigo;?>">    
</div>

<br>

<div style="width: 100%" class="input-group">
<span class="input-group-addon" style="min-width:35% " >Asociacion</span>
<input class="form-control"  readonly="true" value="<?php echo $desc_asociacion;?>">    
</div>

<br>

<div style="width: 100%" class="input-group">
<span class="input-group-addon" style="min-width:35% " >Genero</span>
<input class="form-control"  readonly="true" value="<?php echo $desc_sexo;?>">    
</div>

<br>

<div style="width: 100%" class="input-group">
<span class="input-group-addon" style="min-width:35% " >Estado Civil</span>
<input class="form-control"  readonly="true" value="<?php echo $desc_civil;?>">    
</div>

<br>

<div style="width: 100%" class="input-group">
<span class="input-group-addon" style="min-width:35% " >Departamento</span>
<input class="form-control"  readonly="true" value="<?php echo $depto;?>">    
</div>

<br>

<div style="width: 100%" class="input-group">
<span class="input-group-addon" style="min-width:35% " >Municipio</span>
<input class="form-control"  readonly="true" value="<?php echo $municipio;?>">    
</div>




</td>	
<td width="2%"></td>
<td width="24%" valign="top" >
	



<div style="width: 100%" class="input-group">
<span class="input-group-addon" style="min-width:35% " >Direccion</span>
<textarea class="form-control"  readonly="true" ><?php echo $direccion;?> </textarea>    
</div>

<br>

<div style="width: 100%" class="input-group">
<span class="input-group-addon" style="min-width:35% " >Zona Venta</span>
<textarea class="form-control"  readonly="true" ><?php echo $zona_venta;?> </textarea>    
</div>


<br>

<div style="width: 100%" class="input-group">
<span class="input-group-addon" style="min-width:35% " >Telefono</span>
<input class="form-control"  readonly="true" value="<?php echo $telefono;?>">    
</div>

<br>

<div style="width: 100%" class="input-group">
<span class="input-group-addon" style="min-width:35% " >Estado Vendedor</span>
<input class="form-control"  readonly="true" value="<?php echo $desc_estado;?>">    
</div>

<hr>
<br>

<div style="width: 100%" class="input-group">
<span class="input-group-addon" style="min-width:35% " >Fecha Creacion</span>
<input class="form-control"  readonly="true" value="<?php echo $fecha_creacion;?>">    
</div>

<br>

<div style="width: 100%" class="input-group">
<span class="input-group-addon" style="min-width:35% " >Creado Por</span>
<input class="form-control"  readonly="true" value="<?php echo $nombre;?>">    
</div>



</td>	
</tr>
</table>

	

</div>
</div>
