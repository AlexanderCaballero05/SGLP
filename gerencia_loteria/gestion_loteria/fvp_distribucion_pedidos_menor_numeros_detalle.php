<?php
require('./template/header.php');

$id_sorteo = $_SESSION['id_sorteo'];
$num = $_SESSION['numero_distribucion'];

$info_sorteo = mysql_query("SELECT *  FROM sorteos_menores WHERE id = '$id_sorteo' limit 1");
$value = mysql_fetch_object($info_sorteo);
$sorteo = $value->no_sorteo_men;
$fecha_sorteo = $value->fecha_sorteo;

$seccionales = mysql_query("SELECT * FROM seccionales ORDER BY ruta ASC, cod_seccional ASC ");
///////////////// busqueda de produccion extra  ////////////////////////////////////// 

$extras = mysql_query("SELECT * FROM sorteos_menores_num_extras WHERE id_sorteo = $id_sorteo AND numero = '$num'");
$i = 0;

while ($row_extra = mysql_fetch_array($extras)) {
$v_numero = $row_extra['numero'];
$v_serie_inicial = $row_extra['serie_inicial'];
$v_serie_final = $row_extra['serie_inicial'] + $row_extra['cantidad'] - 1;
$v_cantidad = $row_extra['cantidad'];

$reservados = mysql_query("SELECT * FROM menor_reservas_numeros WHERE sorteos_menores_id = '$id_sorteo' AND numero_inicial <= '$v_numero' AND numero_final >= '$v_numero' AND serie_inicial <= '$v_serie_inicial' AND  serie_final <= '$v_serie_final' AND origen IS NULL ");


$distribuidos = mysql_query("SELECT SUM(cantidad) as suma_cantidad FROM menor_seccionales_numeros WHERE id_sorteo = $id_sorteo AND numero = '$v_numero'  AND origen = 'Numeros' ");

if ($distribuidos=== false) {
 echo mysql_error();
}else{
$ob_distribuidos = mysql_fetch_object($distribuidos);
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

$v_cantidad = $v_cantidad - $cantidad_reservada - $cantidad_distribuida;
$i ++;
}



if (isset($_POST['guardar_distribucion'])) {

$num = $_SESSION['numero_distribucion'];
$id_sorteo = $_SESSION['id_sorteo'];
$id_banco = 4;
$cantidad_total = 0;
$i = 0;


$extras = mysql_query("SELECT * FROM sorteos_menores_num_extras WHERE id_sorteo = $id_sorteo AND numero = '$num' order by numero ");

while ($row_extra = mysql_fetch_array($extras)) {
$numero_extra = $row_extra['numero'];
$serie_inicial_extra = $row_extra['serie_inicial'];
$serie_final_extra = $row_extra['serie_inicial'] + $row_extra['cantidad'] - 1;
$cantidad_extra = $row_extra['cantidad'];
}


$reservados = mysql_query("SELECT * FROM menor_reservas_numeros WHERE sorteos_menores_id = '$id_sorteo' AND numero_inicial <= '$numero_extra' AND numero_final >= '$numero_extra' AND serie_inicial >= '$serie_inicial_extra' AND  serie_final <= '$serie_final_extra' AND origen IS NULL ORDER BY serie_final ASC ");

$distribuidos = mysql_query("SELECT * FROM menor_seccionales_numeros WHERE id_sorteo = '$id_sorteo' AND numero = '$numero_extra' AND serie_inicial >= '$serie_inicial_extra' AND  serie_final <= '$serie_final_extra' AND origen IS NULL ORDER BY serie_final ASC ");


if ($reservados === false) {
echo mysql_error();
}

if ($distribuidos === false) {
echo mysql_error();
}


if (mysql_num_rows($reservados) > 0) {
while ($reservado =  mysql_fetch_array($reservados)) {
$serie_inicial_reservada = $reservado['serie_inicial'];
$serie_final_reservada = $reservado['serie_final'];

$i = 0;
$contador_serie = $serie_inicial_extra;
while ($contador_serie <= $serie_final_reservada) {
$v_series_reservadas[$i] = $contador_serie;
$i++;
$contador_serie++;
}

}
}

if (mysql_num_rows($distribuidos) > 0) {
while ($distribuido =  mysql_fetch_array($distribuidos)) {
$serie_inicial_distribuida = $distribuido['serie_inicial'];
$serie_final_distribuida = $distribuido['serie_final'];
$i = 0;
$contador_serie = $serie_inicial_extra;
while ($contador_serie <= $serie_final_distribuida) {
$v_series_distribuidas[$i] = $contador_serie;
$i++;
$contador_serie++;
}

}
}


$disponible = 0;
$j = 0;
while ($serie_inicial_extra <= $serie_final_extra) {

if (isset($v_series_reservadas[0])) {
if (in_array($serie_inicial_extra,$v_series_reservadas)) {
$disponible = 1;
}
}

if (isset($v_series_distribuidas[0])) {
if (in_array($serie_inicial_extra,$v_series_distribuidas)) {
$disponible = 1;
}
}

if ($disponible == 0) {
$v_series_disponibles[$j] = $serie_inicial_extra;
$j++;
}

$disponible = 0;
$serie_inicial_extra ++;
}


$j = 0;
$i = 0;
while (isset($_POST['id_seccional'.$i])) {

$id_seccional = $_POST['id_seccional'.$i];
$cantidad = $_POST['cantidad_seccional'.$i];
$cantidad_total = $cantidad_total + $_POST['cantidad_seccional'.$i];

if ($cantidad != '') {

$serie_inicial_d = $_POST['serie_inicial'.$i];
$serie_final_d = $_POST['serie_final'.$i];
	
//$serie_inicial_d = $v_series_disponibles[$j];
$j = $j + $cantidad - 1;
//$serie_final_d = $v_series_disponibles[$j];


if (mysql_query(" INSERT INTO  menor_seccionales_numeros (id_sorteo,numero,serie_inicial,serie_final,cantidad,id_seccional) VALUES ('$id_sorteo','$num','$serie_inicial_d','$serie_final_d' ,'$cantidad' ,'$id_seccional')  ") === TRUE) {
}else{
	echo mysql_error();
}

$serie_inicial_d = $serie_final_d + 1;
$j++;

}
$i++;
}

?>
<script type="text/javascript">
  swal({ 
  title: "",
   text: "Distribucion Realizada correctamente",
    type: "success" 
  },
  function(){
    window.location.href = './fvp_distribucion_pedidos_menor_numeros.php';
});
</script>
<?php


}

?>

<script type="text/javascript">
function calcular_cantidad(id){
i = id;
serie_inicial = parseInt(document.getElementById('serie_inicial'+i).value);
serie_final = parseInt(document.getElementById('serie_final'+i).value);
cantidad = serie_final - serie_inicial + 1;
document.getElementById('cantidad_seccional'+i).value = cantidad;
}


function calcular_disponibles(valor){
i = 0;
total_restar = 0;
total = document.getElementById('cantidad_oculto').value;
while (cantidad = document.getElementById('cantidad_seccional'+i)) {
if (cantidad.value != '') {
c = parseInt(cantidad.value);  
}else{
c = 0;  
}
c = c;
total_restar = total_restar + c;     
i++;
}
document.getElementById('cantidad').value = total - total_restar ;
}
</script>

<form method="POST">
<br>
<h3 align="center">
  Sorteo Numero: <?php if (isset($sorteo)) {echo $sorteo;} ?>  
 Fecha de Sorteo: <?php if (isset($sorteo)) {echo $fecha_sorteo;} ?>  
 </h3> 
<br>
<br>
<div class="alert alert-success" role="alert">
<p align="center">
Numero: <?php echo $v_numero;?>
<br> 
<input id="cantidad_oculto" type="hidden" value="<?php echo $v_cantidad;?>"> 
Cantidad sin distribucion: <input id="cantidad" type="text" value="<?php echo $v_cantidad;?>"> 
</p>
</div>
<br>

<div class = 'well' style="width:100%">

<h3 align="center">Puntos de Venta</h3>
<br>

<?php

echo "<table  width = '100%'  class= 'table table-hover table-bordered'>";
echo "<tr>
<th width = '5%'>Ruta</th>
<th width = '5%'>Seccional</th>
<th width = '50%'>Nombre</th>
<th width = '10%'>Serie Inicial</th>
<th width = '10%'>Serie Final</th>
<th width = '10%'>Cantidad</th>
</tr>";

$i = 0; 
while ($row = mysql_fetch_array($seccionales)) {
echo "<tr>
<td align= 'center'>".$row['ruta']."</td>
<td align= 'center'>".$row['cod_seccional']."</td>
<td align= 'center'>".$row['nombre']."</td>
<input type = 'hidden' value = '".$row['id']."' name = 'id_seccional".$i."'>

<td align= 'center'><input class = 'form form-control' type = 'text' id = 'serie_inicial".$i."' name = 'serie_inicial".$i."'></td>

<td align= 'center'><input class = 'form form-control' type = 'text' id = 'serie_final".$i."' name = 'serie_final".$i."' onchange ='calcular_cantidad(".$i.")'></td>

<td align= 'center'><input class = 'form form-control' type = 'text' id = 'cantidad_seccional".$i."' name = 'cantidad_seccional".$i."' onblur ='calcular_disponibles(this.value,".$i.")' readonly ></td>
</tr>";
$i++;
}

echo "</table>";

?>
<br>
<p align="center"><input type="submit" id="guardar_distribucion" name="guardar_distribucion" class="btn btn-primary" value="Guardar Distribucion"></p>

</div>

</form>