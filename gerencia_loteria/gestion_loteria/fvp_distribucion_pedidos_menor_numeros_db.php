<?php

$sorteos = mysql_query("SELECT * FROM sorteos_menores WHERE estado_sorteo = 'PENDIENTE DISTRIBUCION' ");

$seccionales = mysql_query("SELECT * FROM seccionales ");


///////////////////////// Si se selecciono un sorteo ////////////////////////////////////////////////////

if (isset($_POST['seleccionar']) ) {

$id_sorteo = $_POST['sorteo'];
$_SESSION['id_sorteo'] = $id_sorteo; 

$info_sorteo = mysql_query("SELECT *  FROM sorteos_menores WHERE id = '$id_sorteo' limit 1");
$value = mysql_fetch_object($info_sorteo);
$sorteo = $value->no_sorteo_men;
$fecha_sorteo = $value->fecha_sorteo;



///////////////// busqueda de produccion extra  ////////////////////////////////////// 


$extras = mysql_query("SELECT * FROM sorteos_menores_num_extras WHERE id_sorteo = $id_sorteo order by numero ");
$i = 0;

$cantidad_distribuida = 0;
while ($row_extra = mysql_fetch_array($extras)) {
$v_numero[$i] = $row_extra['numero'];
$v_serie_inicial[$i] = $row_extra['serie_inicial'];
$v_serie_final[$i] = $row_extra['serie_inicial'] + $row_extra['cantidad'] - 1;
$v_cantidad[$i] = $row_extra['cantidad'];

$reservados = mysql_query("SELECT * FROM menor_reservas_numeros WHERE sorteos_menores_id = '$id_sorteo' AND numero_inicial <= '$v_numero[$i]' AND numero_final >= '$v_numero[$i]' AND serie_inicial <= '$v_serie_inicial[$i]' AND  serie_final <= '$v_serie_final[$i]' AND origen IS NULL ");

$distribuidos = mysql_query("SELECT MAX(serie_final) as max_s, SUM(cantidad) as suma_cantidad FROM menor_seccionales_numeros WHERE id_sorteo = $id_sorteo AND numero = '$v_numero[$i]'  AND origen = 'Numeros' ");

if ($distribuidos=== false) {
 echo mysql_error();
}else{
$ob_distribuidos = mysql_fetch_object($distribuidos);
$serie_final_distribuida = $ob_distribuidos->max_s;
$cantidad_distribuida = $ob_distribuidos->suma_cantidad;
}

if ($reservados===false) {
echo mysql_error();
}

$cantidad_reservada = 0;
while ($row_reserva = mysql_fetch_array($reservados)) {
$serie_final = 	$row_reserva['serie_final'] + 1;
$cantidad_reservada = $serie_final - $row_reserva['serie_inicial']  + $cantidad_reservada;  
}




$v_cantidad[$i] = $v_cantidad[$i] - $cantidad_reservada - $cantidad_distribuida;
$cantidad_distribuida = 0;
$i ++;
}
//////////////////////////////////////////////////////////////////////////////////////

}



if (isset($_POST['distribuir'])) {
$_SESSION['numero_distribucion'] = $_POST['distribuir'];

?>
<script type="text/javascript">
window.location = "./fvp_distribucion_pedidos_menor_numeros_detalle.php";
</script>
<?php
}



if (isset($_POST['eliminar_distribucion'])) {
$id = $_POST['eliminar_distribucion'];
if (mysql_query("DELETE FROM menor_seccionales_numeros WHERE id = '$id' ") === TRUE) {

?>
<script type="text/javascript">
  swal({ 
  title: "",
   text: "Registro Eliminado Correctamente",
    type: "success" 
  },
  function(){
    window.location.href = './fvp_distribucion_pedidos_menor_numeros.php';
});
</script>
<?php


}
}



?>