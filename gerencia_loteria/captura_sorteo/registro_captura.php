<?php

require('../../conexion.php');


$tipo_sorteo = $_GET['t'];
$id_sorteo   = $_GET['s'];

if ($tipo_sorteo == 1) {

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////// LOTERIA MAYOR ////////////////////////////////////////////////////////



$premio  = $_GET['p'];
$billete = $_GET['b'];
$decimo  = $_GET['d'];

$captura_premio=mysqli_query($conn,"UPDATE sorteos_mayores_premios SET numero_premiado_mayor = $billete, decimos = $decimo  WHERE sorteos_mayores_id = $id_sorteo and id = $premio ");



$consulta_id_tipo_premio = mysqli_query($conn, " SELECT premios_mayores_id FROM sorteos_mayores_premios  WHERE sorteos_mayores_id = $id_sorteo and id = $premio ");
$ob_id_tipo_premio =  mysqli_fetch_object($consulta_id_tipo_premio);
$id_tipo_premio = $ob_id_tipo_premio->premios_mayores_id;


$consulta =  mysqli_query($conn,"SELECT * FROM  sorteos_mayores_premios WHERE premios_mayores_id  = '$id_tipo_premio' AND sorteos_mayores_id = '$id_sorteo' AND respaldo = 'SI' ");

if (mysqli_num_rows($consulta) > 0) {

?>
<script type="text/javascript">
window.location.href = "screen_captura_sorteo_mayor.php";
</script>
<?php

}else{


$consulta_mensaje = mysqli_query($conn, " SELECT a.billete, a.detalle_venta  FROM `ventas_distribuidor_mayor` as a  WHERE  a.billete='$billete' AND a.sorteo = '$id_sorteo' ");
$numero_ganador = $billete;

if (mysqli_num_rows($consulta_mensaje)>0 ){

$ob_geo        = mysqli_fetch_object($consulta_mensaje);
$detalle_venta = $ob_geo->detalle_venta;

$municipio = '';
$dpto      = '';
$v_muni    = '';

$_mensaje = "El Numero <b>". $numero_ganador."</b> ha sido Vendido en <b>".$detalle_venta."</b> ";
echo "<td colspan = '6' class = 'alert alert-success'><strong>¡Vendido! </strong>". $_mensaje."</td>";

}else{

$_mensaje = " El Numero <b>".$numero_ganador."</b>  No Fue Vendido";
echo "<td colspan = '6' class = 'alert alert-danger'> <div class='' style='width:100%;'> <strong>¡No Vendido!</strong>".$_mensaje."</div></td>"; 


}

}



///////////////////////////////////////////////////// LOTERIA MAYOR ////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


}else{


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////// LOTERIA MENOR ////////////////////////////////////////////////////////

$no_sorteo = $_GET['s'];
$premio    = $_GET['p'];
$numero_d  = $_GET['d'];
$numero_r  = $_GET['r'];
$serie     = $_GET['ser'];

$id_d     = $_GET['idd'];
$id_r     = $_GET['idr'];


$derecho_reves    = $_GET['dr'];

$numero_ganador_0 = $numero_d;
$numero_reves_0 = $numero_r;

mysqli_query($conn," UPDATE sorteos_menores_premios SET numero_premiado_menor = '$numero_ganador_0'  WHERE sorteos_menores_id = '$no_sorteo' and premios_menores_id = '$id_d' ");
mysqli_query($conn," UPDATE sorteos_menores_premios SET numero_premiado_menor= '$numero_reves_0'  WHERE sorteos_menores_id = '$no_sorteo' and premios_menores_id = '$id_r' ");


$captura_premio=mysqli_query($conn,"UPDATE sorteos_menores_premios SET numero_premiado_menor = $serie  WHERE sorteos_menores_id = $no_sorteo and premios_menores_id = $premio ");


if ($derecho_reves == 1) {

$consulta_mensaje=mysqli_query($conn, " SELECT agencia_banrural FROM `ventas_distribuidor_menor` WHERE  `numero`= '$numero_d'  and  `serie` = '$serie' AND sorteo = '$no_sorteo' " );
$cargar_ganador = $numero_d;

}else{

$consulta_mensaje=mysqli_query($conn, " SELECT agencia_banrural FROM `ventas_distribuidor_menor` WHERE  `numero`= '$numero_r'  and  `serie` = '$serie' AND sorteo = '$no_sorteo' " );
$cargar_ganador = $numero_r;

}



if (mysqli_num_rows($consulta_mensaje)>0 ) {

$row = mysqli_fetch_object($consulta_mensaje);
$lugar_venta = $row->agencia_banrural;

$_mensaje= "El Numero <b>".$cargar_ganador."</b>  con Serie <b>".$serie."</b> ha sido Vendido  en <b>".$lugar_venta."</b> ";
echo "<td colspan = '3' class='alert  alert-success' > <strong>¡Vendido! </strong>". $_mensaje."</td>";

}else{

$_mensaje_reves= "El Numero <b>".$cargar_ganador."</b>  con Serie <b>".$serie."</b> No ha sido Vendido";
echo "<td colspan = '3' class='alert alert-danger' > <strong>¡No Vendido! </strong>".$_mensaje_reves."</td>";

}



///////////////////////////////////////////////////// LOTERIA MENOR ////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

}


?>


<script>
$(".div_wait").fadeOut("fast");
</script>
