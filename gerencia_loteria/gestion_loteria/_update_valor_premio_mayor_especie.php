<?php 
require('../../template/header.php');

$id=     $_GET['id'];
$usuario=$_GET['usuario'];
$total=  $_GET['total'];
$impto=  $_GET['impto'];
$neto=   $_GET['neto'];


$query_update =  mysql_query("UPDATE archivo_pagos_mayor SET total=$total, impto=$impto, neto=$neto where id=$id");

if (!$query_update) 
{
		echo "<div class='alert alert-danger'> Ha habido un error  ".mysql_error(). "</div>";
}
else 
{
		 mysql_query("INSERT INTO bitacora_update_premio_mayor (usuario, id_premio, total, impto, neto) values ($usuario, $id, $total, $impto, $neto)  ");

		 $query_actualiza_main=mysql_query("SELECT sorteo, numero, total FROM archivo_pagos_mayor WHERE id=$id");
		 while ($row_info_premio=mysql_fetch_array($query_actualiza_main)) 
		 {
		    $sorteo=$row_info_premio['sorteo'];
		    $numero=$row_info_premio['numero']; 
		    $total=$row_info_premio['total']; 
		 }

		 if (mysql_query("UPDATE sorteos_mayores_premios set monto=$total where sorteos_mayores_id=$sorteo and numero_premiado_mayor=$numero")) 
		 {
		 	echo "<div class='alert alert-success'> Se han actualizado los valores exitosamente </div>";
		    
		 }
		 else
		 {
		 	echo "<div class='alert alert-danger'> Ha habido un error  ".mysql_error(). "</div>";
		 }

}
 





?>