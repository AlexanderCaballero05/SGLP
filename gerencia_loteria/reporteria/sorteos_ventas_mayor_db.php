<?php
require("../conexion.php");

$id_sorteo  = $_GET['id_s'];
$estado 	= $_GET['n_e'];


$actualizacion_estado = mysql_query("UPDATE empresas_estado_venta SET  estado_venta  = '$estado' WHERE id_sorteo = '$id_sorteo'  AND cod_producto = '1' ");

if ($actualizacion_estado === TRUE) {

echo "<div class = 'alert alert-info'>Cambio de estado realizado correctamente.</div>";

}else{

echo "<div class = 'alert alert-info'>Error inesperado, por favor intente nuevamente.</div>";

}

?>