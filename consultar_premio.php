<?php

require("conexion.php");

$tipo_loteria = $_GET['ts'];
$sorteo           = $_GET['sort'];
$billete          = $_GET['b'];
$numero           = $_GET['n'];
$serie            = $_GET['s'];


if ($tipo_loteria  == 1) {

$consulta_premio = mysqli_query($conn, "SELECT total as totalpayment FROM archivo_pagos_mayor WHERE sorteo = '$sorteo' AND numero = '$billete' ");

}else{

$consulta_premio = mysqli_query($conn, "SELECT * FROM archivo_pagos_menor WHERE sorteo = '$sorteo' AND numero = '$numero' AND serie = '$serie' ");

}


if (mysqli_num_rows($consulta_premio) > 0) {

$ob_premio = mysqli_fetch_object($consulta_premio);
$premio    = $ob_premio->totalpayment;

if ($tipo_loteria  == 1) {
echo "<div class = 'alert alert-success' ><p align = 'center'> El billete No. ".$billete." tiene un premio de:<br> L ".number_format($premio, 2 )." </p></div>";
}else{
echo "<div class = 'alert alert-success' ><p align = 'center'> El billete No. ".$numero." con serie ".$serie." tiene un premio de:<br> L ".number_format($premio, 2 )." </p></div>";
}


}else{

if ($tipo_loteria  == 1) {
echo "<div class = 'alert alert-danger'>El billete No. ".$billete." no tiene premio</div>";
}else{
echo "<div class = 'alert alert-danger'>El billete No. ".$numero." con serie ".$serie." no tiene premio</div>";
}


}


?>

