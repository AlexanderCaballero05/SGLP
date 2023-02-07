<?php 

ob_start();
session_start(); 



$v_ruta = explode('/', $_SERVER['REQUEST_URI']);
$conteo_ruta = count($v_ruta);

$ruta = '';
if ($conteo_ruta >= 4 ){

while ($conteo_ruta >= 4) {
$ruta .= "../";
$conteo_ruta --;
}

}else{
  $ruta = "./";  
}

require($ruta."conexion.php");
require($ruta."/assets/nusoap/lib/nusoap.php");

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGLP - PORTAL</title>
    <link rel="stylesheet" href="<?php echo $ruta; ?>assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $ruta; ?>assets/font_awesome/css/all.css">
 
    <link rel="stylesheet" href="<?php echo $ruta; ?>assets/css/portal.css">
    <link rel="stylesheet" href="<?php echo $ruta; ?>assets/css/animate.min.css">

    <script src="<?php echo $ruta; ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo $ruta; ?>assets/js/bootstrap.bundle.min.js"></script>

    <script src="<?php echo $ruta; ?>assets/js/theme.js"></script>
    <script src="<?php echo $ruta; ?>assets/js/bs-animation.js"></script>
    <script src="<?php echo $ruta; ?>assets/js/login_functions.js"></script>
    <script src="<?php echo $ruta; ?>assets/js/show-inputs-consulta-premios.js"></script>
    <script src="<?php echo $ruta; ?>assets/js/sweetalert.min.js"></script>


    <script type="text/javascript" src="<?php echo $ruta; ?>assets/mask/jquery.mask.js"></script>
    <script type="text/javascript" src="<?php echo $ruta; ?>assets/datatable/dataTables.min.js"></script>
    <script type="text/javascript" src="<?php echo $ruta; ?>assets/datatable/bootstrap4.min.js"></script>
    <script type="text/javascript" src="<?php echo $ruta; ?>assets/datatable/loadtable.js"></script>
    <script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/moment.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $ruta; ?>assets/datatable/bootstrap4.min.css">

<script src="<?php echo $ruta; ?>assets/dates_4/gijgo.min.js" type="text/javascript"></script>
<link href="<?php echo $ruta; ?>assets/dates_4/gijgo.min.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $ruta; ?>assets/dates_4/messages.es-es.js" type="text/javascript"></script>



<script type="text/javascript">
$('#datepicker_1').datepicker({
locale: 'es-es',
format: 'yyyy-mm-dd',
uiLibrary: 'bootstrap4'
});

$('#datepicker_2').datepicker({
locale: 'es-es',
format: 'yyyy-mm-dd',
uiLibrary: 'bootstrap4'
});
</script>

<style type="text/css">

.div_wait {
  display: none;
  position: fixed;
  left: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  z-index: 9999;
  background-color: black;
  opacity:0.5;
  background: url(<?php echo $ruta;?>template/images/wait.gif) center no-repeat #fff;
}

@media print
{
#non-printable { display: none; }
#printable { display: block; }
}

</style>

</head>

<body>

<nav class="navbar navbar-dark navbar-expand-lg fixed-top bg-dark clean-navbar">
<a class="navbar-brand logo" style="color: white" >SGLP</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse"
id="navcol-1">
<ul class="nav navbar-nav ml-auto">


<?php 



?>


<?php 
if (isset($_SESSION['logged'])) {


if ($ruta == "./") {

echo '
<li class="nav-item" role="presentation" ><a class="nav-link" href="" data-toggle="modal" data-target="#modal-sorteos">INFO ULTIMOS SORTEOS</a></li>
<li class="nav-item" role="presentation" ><a class="nav-link" href="" data-toggle="modal" data-target="#modal-consulta-premios">CONSULTA DE PREMIOS</a></li>
';

}else{


echo '<li class="nav-item" role="presentation" ><a class="nav-link" href="'.$ruta.'">PORTAL</a></li>';






/////////////////////////////////////////////////////////////////////
/////////////////// CODIGO MENU DINAMICO ////////////////////////////

$v_ruta = explode("/",  $_SERVER['REQUEST_URI']);
$conteo_ruta = count($v_ruta);

if ($conteo_ruta >= 4) {

$i = 0;
while (isset($_SESSION[$v_ruta[2].$i])) {

$gerencia = $v_ruta[2];
$depto    = $_SESSION[$v_ruta[2].$i];

$v_depto = explode("_", $depto);

$h = 0;
$descripcion_depto = '';
while (isset($v_depto[$h])) {
$descripcion_depto .= $v_depto[$h]." ";
$h++;
}

echo ' <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$descripcion_depto.'</a>
        <div style = "max-height: 600px; overflow-y: auto;" class="dropdown-menu" aria-labelledby="navbarDropdown">';

$j = 0;


while (isset($_SESSION[$v_ruta[2].$_SESSION[$v_ruta[2].$i].$j])) {
$acceso = $_SESSION[$v_ruta[2].$_SESSION[$v_ruta[2].$i].$j];

$v_acceso = explode("%", $acceso);

$name_pantalla =  $v_acceso[0];
$desc_pantalla =  $v_acceso[1];

if ($j == 0) {
echo '<a class="dropdown-item"  href="'.$ruta.$gerencia."/".$depto."/".$name_pantalla.'">'.$desc_pantalla.'</a>';
}else{
echo '<div class="dropdown-divider"></div><a class="dropdown-item" href="'.$ruta.$gerencia."/".$depto."/".$name_pantalla.'">'.$desc_pantalla.'</a>';  
}
$j++;
}

echo '</div>
      </li>';

$i++;
}

}

/////////////////// CODIGO MENU DINAMICO ////////////////////////////
/////////////////////////////////////////////////////////////////////




}


?>


<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<i class="fa fa-user-circle"></i> <?php echo $_SESSION['nombre']; ?>
</a>
<div class="dropdown-menu" aria-labelledby="navbarDropdown">
<a class="dropdown-item" href="#">Cambiar Contraseña</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="<?php echo $ruta; ?>template/logout.php">Cerrar Sesion</a>
</div>
</li>

<?php 
}else{


$v_ruta = explode("/",  $_SERVER['REQUEST_URI']);
$conteo_ruta = count($v_ruta);

if ($conteo_ruta >= 4) {

$ruta = '';
if ($conteo_ruta >= 4 ){

while ($conteo_ruta >= 4) {
$ruta .= "../";
$conteo_ruta --;
}

}else{
  $ruta = "./";  
}


header("Location: ".$ruta."index.php ");

}else{


?>
<li class="nav-item" role="presentation" ><a class="nav-link" href="" data-toggle="modal" data-target="#modal-sorteos">INFO ULTIMOS SORTEOS</a></li>
<li class="nav-item" role="presentation" ><a class="nav-link" href="" data-toggle="modal" data-target="#modal-consulta-premios">CONSULTA DE PREMIOS</a></li>
<li class="nav-item" role="presentation" ><a class="nav-link" href="" data-toggle="modal" data-target="#modal-login"><i class="fa fa-sign-in-alt"></i> INICIAR SESION</a></li>
<?php    


}

}
?>

</ul>
</div>
</nav>
    
<main class="page landing-page">

<div id="div_wait" class="div_wait"></div>
