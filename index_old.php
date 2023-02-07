<?php 
require("./template/header.php");
?>

<script language="Javascript">

var capsLockEnabled = null;

function getChar(e) {

  if (e.which == null) {
    return String.fromCharCode(e.keyCode); // IE
  }
  if (e.which != 0 && e.charCode != 0) {
    return String.fromCharCode(e.which); // rest
  }

  return null;
}

document.onkeydown = function(e) {
  e = e || event;

  if (e.keyCode == 20 && capsLockEnabled !== null) {
    capsLockEnabled = !capsLockEnabled;
  }
}

document.onkeypress = function(e) {
  e = e || event;

  var chr = getChar(e);
  if (!chr) return; // special key

  if (chr.toLowerCase() == chr.toUpperCase()) {
    // caseless symbol, like whitespace 
    // can't use it to detect Caps Lock
    return;
  }

  capsLockEnabled = (chr.toLowerCase() == chr && e.shiftKey) || (chr.toUpperCase() == chr && !e.shiftKey);
}

/**
 * Check caps lock 
 */
function checkCapsWarning() {
  document.getElementById('caps').style.display = capsLockEnabled ? 'block' : 'none';
}

function removeCapsWarning() {
  document.getElementById('caps').style.display = 'none';
}

</script>



<section id="section-titulo">
<h2 class="text-center" style="padding-top:50px;padding-bottom:50px;background-color:#ffffff;"><b>SISTEMA DE GESTION DE LOTERIA PANI<b></h2>
</section>








<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& SECCION GERENCIAS &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& SECCION GERENCIAS &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->

<?php 


if(!isset($_SESSION['logged'])){
//echo md5("123");

?>

<section id="section-modulos" style="background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
<div class="row" style="margin:0px;">
<div class="col align-self-center align-middle"><button class="btn btn-primary btn-lg center-block" type="button" id="boton-portal-1" style="width:100%;background-color:rgb(47,47,47);font-size:20px;height:120px;margin:40px 0px  20px 0px;" data-toggle="modal"
data-target="#modal-login" disabled="true">GERENCIA &nbsp;LOTERIA</button></div>
<div class="col align-self-center align-middle"><button class="btn btn-primary btn-lg center-block" type="button" id="boton-portal-2" style="width:100%;background-color:rgb(47,47,47);font-size:20px;height:120px;margin:40px 0px  20px 0px;" data-toggle="modal"
data-target="#modal-login" disabled="true">GERENCIA &nbsp;IMPRENTA</button></div>
<div class="col align-self-center align-middle"><button class="btn btn-primary btn-lg center-block" type="button" id="boton-portal-3" style="width:100%;background-color:rgb(47,47,47);font-size:20px;height:120px;margin:40px 0px  20px 0px;" data-toggle="modal"
data-target="#modal-login" disabled="true">UNIDAD INFORMATICA</button></div>
<div class="col align-self-center align-middle"><button class="btn btn-primary btn-lg center-block" type="button" id="boton-portal-4" style="width:100%;background-color:rgb(47,47,47);font-size:20px;height:120px;margin:40px 0px  20px 0px;" data-toggle="modal"
data-target="#modal-login" disabled="true">UNIDAD PLANIFICACION</button></div>
</div>
<div class="row" style="margin:0px;">
<div class="col align-self-center align-middle"><button class="btn btn-primary btn-lg center-block" type="button" id="boton-portal-5" style="width:100%;background-color:rgb(47,47,47);font-size:20px;height:120px;margin:20px 0px  40px 0px;" data-toggle="modal"
data-target="#modal-login" disabled="true">AUDITORIA INTERNA</button></div>
<div class="col align-self-center align-middle"><button class="btn btn-primary btn-lg center-block" type="button" id="boton-portal-6" style="width:100%;background-color:rgb(47,47,47);font-size:20px;height:120px;margin:20px 0px  40px 0px;" data-toggle="modal"
data-target="#modal-login" disabled="true">GERENCIA FINANCIERA</button></div>
<div class="col align-self-center align-middle"><button class="btn btn-primary btn-lg center-block" type="button" id="boton-portal-7" style="width:100%;background-color:rgb(47,47,47);font-size:20px;height:120px;margin:20px 0px  40px 0px;" data-toggle="modal"
data-target="#modal-login" disabled="true">DASHBOARD</button></div>
<div class="col align-self-center align-middle"><button class="btn btn-primary btn-lg center-block" type="button" id="boton-portal-8" style="width:100%;background-color:rgb(47,47,47);font-size:20px;height:120px;margin:20px 0px  40px 0px;" disabled="true">OTROS</button></div>
</div>
</section>


<?php
    
}else{


?>

<section id="section-modulos" style="background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
<div class="row" style="margin:0px;">
<div class="col align-self-center align-middle">

<?php
if (isset($_SESSION['gerencia_loteria'])) {
?>
<button class="btn btn-primary btn-lg bounce animated center-block" type="button" data-bs-hover-animate="bounce"  style="width:100%; background-color:rgb(18,73,77); font-size:20px; height:120px; margin:40px 0px  20px 0px;" onclick = "window.location='./gerencia_loteria';" >GERENCIA &nbsp;LOTERIA</button>
<?php
}else{
?>
<button class="btn btn-primary btn-lg center-block" type="button" style="width:100%;background-color:rgb(47,47,47);font-size:20px;height:120px;margin:40px 0px  20px 0px;" >GERENCIA &nbsp;LOTERIA</button>
<?php
}
?>

</div>
<div class="col align-self-center align-middle">

<?php 

if (isset($_SESSION['gerencia_imprenta'])) {

?>
<button class="btn btn-primary btn-lg bounce animated center-block" type="button" data-bs-hover-animate="bounce"  style="width:100%; background-color:rgb(18,73,77); font-size:20px; height:120px; margin:40px 0px  20px 0px;" onclick = "window.location='./gerencia_imprenta';" >GERENCIA &nbsp;IMPRENTA</button>
<?php

}else{
echo '<button class="btn btn-primary btn-lg center-block" type="button" style="width:100%;background-color:rgb(47,47,47);font-size:20px;height:120px;margin:40px 0px  20px 0px;" >GERENCIA &nbsp;IMPRENTA</button>';    
}

?>

</div>
<div class="col align-self-center align-middle">

<?php 

if (isset($_SESSION['unidad_informatica'])) {
?>

<button class="btn btn-primary btn-lg bounce animated center-block" type="button" data-bs-hover-animate="bounce"  style="width:100%; background-color:rgb(18,73,77); font-size:20px; height:120px; margin:40px 0px  20px 0px;" onclick = "window.location='./unidad_informatica';" >UNIDAD INFORMATICA</button>

<?php
}else{
echo '<button class="btn btn-primary btn-lg center-block" type="button" style="width:100%;background-color:rgb(47,47,47);font-size:20px;height:120px;margin:40px 0px  20px 0px;" >UNIDAD INFORMATICA</button>';    
}

?>

</div>
<div class="col align-self-center align-middle">

<?php 

if (isset($_SESSION['unidad_planificacion'])) {

?>

<button class="btn btn-primary btn-lg bounce animated center-block" type="button" data-bs-hover-animate="bounce"  style="width:100%; background-color:rgb(18,73,77); font-size:20px; height:120px; margin:40px 0px  20px 0px;" onclick = "window.location='./unidad_planificacion';" >UNIDAD PLANIFICACION</button>

<?php


}else{
echo '<button class="btn btn-primary btn-lg center-block" type="button" style="width:100%;background-color:rgb(47,47,47);font-size:20px;height:120px;margin:40px 0px  20px 0px;" >UNIDAD PLANIFICACION</button>';    
}

?>

</div>
</div>

<div class="row" style="margin:0px;">
<div class="col align-self-center align-middle">

<?php 
if (isset($_SESSION['auditoria_interna'])) {

?>

<button class="btn btn-primary btn-lg bounce animated center-block" type="button" data-bs-hover-animate="bounce"  style="width:100%; background-color:rgb(18,73,77); font-size:20px; height:120px; margin:20px 0px  40px 0px;" onclick = "window.location='./auditoria_interna';" >AUDITORIA INTERNA</button>

<?php


}else{
echo '<button class="btn btn-primary btn-lg center-block" type="button" style="width:100%;background-color:rgb(47,47,47);font-size:20px;height:120px;margin:20px 0px  40px 0px;" >AUDITORIA INTERNA</button>';    
}
?>

</div>
<div class="col align-self-center align-middle">

<?php 
if (isset($_SESSION['gerencia_financiera'])) {
?>

<button class="btn btn-primary btn-lg bounce animated center-block" type="button" data-bs-hover-animate="bounce"  style="width:100%; background-color:rgb(18,73,77); font-size:20px; height:120px; margin:20px 0px  40px 0px;" onclick = "window.location='./gerencia_financiera';" >GERENCIA FINANCIERA</button>

<?php

}else{
echo '<button class="btn btn-primary btn-lg center-block" type="button" style="width:100%;background-color:rgb(47,47,47);font-size:20px;height:120px;margin:20px 0px  40px 0px;" >GERENCIA FINANCIERA</button>';    
}
?>

</div>

<div class="col align-self-center align-middle">

<?php 
if (isset($_SESSION['gerencia_rrhh'])) {
?>

<button class="btn btn-primary btn-lg bounce animated center-block" type="button" data-bs-hover-animate="bounce"  style="width:100%; background-color:rgb(18,73,77); font-size:20px; height:120px; margin:20px 0px  40px 0px;" onclick = "window.location='./gerencia_rrhh';" >GERENCIA RRHH</button>

<?php

}else{
echo '<button class="btn btn-primary btn-lg center-block" type="button" style="width:100%;background-color:rgb(47,47,47);font-size:20px;height:120px;margin:20px 0px  40px 0px;" >DASHBOARD</button>';    
}
?>

</div>

<div class="col align-self-center align-middle">

<?php 
if (isset($_SESSION['dashboard'])) {
?>

<button class="btn btn-primary btn-lg bounce animated center-block" type="button" data-bs-hover-animate="bounce"  style="width:100%; background-color:rgb(18,73,77); font-size:20px; height:120px; margin:20px 0px  40px 0px;" onclick = "window.location='./dashboard';" >DASHBOARD</button>

<?php

}else{
echo '<button class="btn btn-primary btn-lg center-block" type="button" style="width:100%;background-color:rgb(47,47,47);font-size:20px;height:120px;margin:20px 0px  40px 0px;" >DASHBOARD</button>';    
}
?>

</div>

</div>
</section>


<?php
    
}

?>

<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& SECCION GERENCIAS &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& SECCION GERENCIAS &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->


    






<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& SECCION LOGIN &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& SECCION LOGIN &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->



<section id="section-modal-login" class="clean-block slider dark" style="background-color:rgb(255,255,255);color:rgb(0,0,0);">
<div class="modal fade" role="dialog" tabindex="-1" id="modal-login">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header" id="modal-header" style="background-color:#e7e7e7;">
<h4 class="text-center modal-title" id="modal-heading" style="width:100%;">AUTENTIFICACION DE USUARIO</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
<div class="modal-body" style="background-color:#f8f8f8;">
<form method="POST">
<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-user" style="font-size:27px;color:rgb(58,58,58);"></i></span></div><input class="form-control" type="text" name="user" placeholder="Usuario">
<div class="input-group-append"></div>
</div>
<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-eye-slash" style="font-size:19px;color:rgb(47,47,47);"></i></span></div><input class="form-control" type="password" name="pass" placeholder="Contraseña"   onkeyup="checkCapsWarning(event)" onfocus="checkCapsWarning(event)" onblur="removeCapsWarning()"  >
<div class="input-group-append"></div>
</div>

<div style="display:none;" id="caps" class="alert alert-danger">Mayusculas activadas.</div>

<div class="container" align="right" style="padding:0px; "><button name="login" style="margin-top: 10px" class="btn btn-info" type="submit">Iniciar Sesion</button></div>
</form>
</div>
</div>
</div>
</div>
</section>



<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& SECCION LOGIN &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& SECCION LOGIN &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->










<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& SECCION INFO SORTEOS &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& SECCION INFO SORTEOS &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->

<section id="section-info-sorteos">
<div class="modal fade" role="dialog" tabindex="-1" id="modal-sorteos">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header" style="background-color:#eeeeee;">
<h4 class="text-center modal-title" style="width:100%;">ULTIMOS SORTEOS JUGADOS</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
<div class="modal-body" style="background-color:#f5f5f5;">
<div id="respuesta_sorteos"></div>
</div>
</div>
</div>
</div>
</section>
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& SECCION INFO SORTEOS &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& SECCION INFO SORTEOS &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->











<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& SECCION PREMIOS &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& SECCION PREMIOS &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->


<section id="section-consulta-premios">
<div class="modal fade" role="dialog" tabindex="-1" id="modal-consulta-premios">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h4 class="text-center modal-title" style="width:100%;">CONSULTA DE PREMIOS</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
<div class="modal-body" style="padding-bottom:0px;"><div class="form-group">

<div class="input-group">
<div class="input-group-prepend">
<span style="width: 80px;" class="input-group-text">
Loteria
</span>
</div>
<select class="form-control" id = 'select-tipo-sorteo' >
<option value = '1'>Mayor</option>
<option value = '2'>Menor</option>
</select>
</div>


<div class="input-group"  style = "margin-top:8px">
<div class="input-group-prepend">
<span style="width: 80px;" class="input-group-text" >
Sorteo
</span>
</div>
<input type = "numer" class = "form-control" min = "1" max = "9999" id="sorteo-premio" >      
</div>


<div id= 'show-cambio-inputs'>

<div class="input-group"  style = "margin-top:8px"><div class="input-group-prepend"><span style="width: 80px;" class="input-group-text" >Billete</span></div><input type = "numer" class = "form-control" min = "0" max = "99999" id="billete-premio" ></div>    

</div>

<span id = 'btn-consulta-premio' class = 'btn btn-success' style = 'margin-top:10px; width:100%' onclick="consultar_premio()">Consultar Premio <i class="" id="icon_consultar"></i> </span>


</div></div>
<div class="modal-footer">
<div class="container" id="respuesta-consulta-premio"></div>
</div>
</div>
</div>
</div>
</section>


<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& SECCION PREMIOS &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& SECCION PREMIOS &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->












<?php 

require("./template/footer.php");

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////// LOGIN ///////////////////////////////////////////////////
///////////////////////////////////////////////////// LOGIN ///////////////////////////////////////////////////



if (isset($_POST['login'])) {

$u = $_POST['user']; 
$p = $_POST['pass']; 


require("conexion.php");


$wsdl="http://192.168.15.17/PANIAD_LOGIN/_GetPaniLogin.php?wsdl";
$cliente = new nusoap_client($wsdl,true);
$cliente->soap_defencoding = 'utf-8';//default is 
$cliente->response_timeout = 200;//seconds
$cliente->useHTTPPersistentConnection();


$result = $cliente-> call("PaniGetlogin", array("usuario" => $u , "password" => $p));

if ($result == 1) {

$c_user = mysqli_query($conn, "SELECT id, nombre_completo, roles_usuarios_id, areas_id, estados_id, usuario FROM pani_usuarios WHERE usuario = '$u'  LIMIT 1  ");
$count = 1;

}else{

$count = 0;  

}


if ($count > 0 ) {

$ob_user   = mysqli_fetch_object($c_user);
$id_u      = $ob_user->id;
$nombre_u  = $ob_user->nombre_completo;
$rol_u     = $ob_user->roles_usuarios_id;
$usuario_u = $ob_user->usuario;
$estado_u  = $ob_user->estados_id;
$area_u    = $ob_user->areas_id;


$_SESSION['logged']         = true;
$_SESSION['sesion']         = true;
$_SESSION['id_usuario']     = $id_u;
$_SESSION['nombre']         = $nombre_u;
$_SESSION['nombre_usuario'] = $nombre_u;
$_SESSION['rol']            = $rol_u;
$_SESSION['usuario']        = $usuario_u;
$_SESSION['estado']         = $estado_u;
$_SESSION['area_id']        = $area_u;





$_SESSION['id_usuario_r']   = $id_u;
//////////////////////////////////////////////////////////////
/////////////////////// ACCESOS //////////////////////////////

$c_gerencias = mysqli_query($conn, "SELECT DISTINCT(a.gerencia) as gerencia FROM accesos as a INNER JOIN pani_usuarios_accesos as b ON a.id = b.id_acceso WHERE b.usuario = '$u' ORDER BY a.gerencia ASC ");

$g = 0;
while ($reg_gerencias = mysqli_fetch_array($c_gerencias)) {

$gerencia = $reg_gerencias['gerencia'];
$_SESSION[$gerencia] = $gerencia;


$c_deptos = mysqli_query($conn, "SELECT DISTINCT(a.depto) FROM accesos as a INNER JOIN pani_usuarios_accesos as b ON a.id = b.id_acceso WHERE b.usuario = '$u' AND a.gerencia =  '$gerencia' ORDER BY  a.depto ASC ");


$d = 0;
$r = 0;

while ($reg_deptos = mysqli_fetch_array($c_deptos)) {

$depto = $reg_deptos['depto'];
$_SESSION[$gerencia.$d] = $reg_deptos['depto'];

$c_pantallas = mysqli_query($conn, "SELECT a.pantalla, a.descripcion_menu FROM accesos as a INNER JOIN pani_usuarios_accesos as b ON a.id = b.id_acceso WHERE b.usuario = '$u' AND a.gerencia =  '$gerencia' AND a.depto =  '$depto' ORDER BY  a.posicion ASC ");

$p = 0;

while ($reg_pantallas = mysqli_fetch_array($c_pantallas)) {

if (substr($reg_pantallas['pantalla'], 0,6) == "screen") {
$_SESSION[$gerencia.$depto.$p] = $reg_pantallas['pantalla']."%".$reg_pantallas['descripcion_menu'];

$p++;
}elseif (substr($reg_pantallas['pantalla'], 0,6) == "report") {
$_SESSION['r_'.$gerencia][$r] = $reg_pantallas['pantalla']."%".$reg_pantallas['descripcion_menu'];

$r++;
}

}

$d++;
}

$g++;
}

/////////////////////// ACCESOS //////////////////////////////
//////////////////////////////////////////////////////////////


header('Location: index.php');

}else{

?>
<script type="text/javascript">
swal("", "Usuario o contraseña incorrectos, por favor intente nuevamente", "error");
</script>
<?php

}


}

///////////////////////////////////////////////////// LOGIN ///////////////////////////////////////////////////
///////////////////////////////////////////////////// LOGIN ///////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////


?>

<script src="assets/js/theme.js"></script>
<script src="assets/js/bs-animation.js"></script>
<script src="assets/js/login_functions.js"></script>
<script src="assets/js/show-inputs-consulta-premios.js"></script>
<script src="assets/js/sweetalert.min.js"></script>

<script type="text/javascript">


token = Math.random();
consulta = 'consulta_ultimos_sorteos.php?token='+token;     
$("#respuesta_sorteos").load(consulta);


function consultar_premio(){

document.getElementById("icon_consultar").className = "fa fa-spinner fa-spin";

tipo_loteria = document.getElementById('select-tipo-sorteo').value;
sorteo = document.getElementById('sorteo-premio').value;



if (tipo_loteria == 1) {

billete = document.getElementById('billete-premio').value;
numero  = 0;
serie   = 0;

}else{

billete = 0;
numero  = document.getElementById('numero-premio').value;
serie   = document.getElementById('serie-premio').value;

}

token = Math.random();
consulta = 'consultar_premio.php?token='+token+'&ts='+tipo_loteria+'&sort='+sorteo+'&b='+billete+'&n='+numero+'&s='+serie;     
$("#respuesta-consulta-premio").load(consulta);


}

</script>