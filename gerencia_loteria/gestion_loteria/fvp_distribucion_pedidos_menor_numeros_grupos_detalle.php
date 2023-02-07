<script type="text/javascript">

function calcular_cantidad(id){
i = id;
serie_inicial = parseInt(document.getElementById('serie_inicial'+i).value);
serie_final = parseInt(document.getElementById('serie_final'+i).value);
cantidad = serie_final - serie_inicial + 1;
document.getElementById('cantidad_seccional'+i).value = cantidad;
}

</script>

<?php 
require('./template/header.php');


$id_sorteo = $_GET['v1'];
$grupo = $_GET['v2'];

$info_sorteo = mysql_query("SELECT *  FROM sorteos_menores WHERE id = '$id_sorteo' limit 1");
$value = mysql_fetch_object($info_sorteo);
$sorteo = $value->no_sorteo_men;
$fecha_sorteo = $value->fecha_sorteo;


$numeros_extras = mysql_query("SELECT * FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo' AND estado_sorteo = 'PENDIENTE DISTRIBUCION' AND grupo = '$grupo' ");

$concatenado_detalle = '';
$n = 0;
while ($detalle_grupo = mysql_fetch_array($numeros_extras)) {

$cantidad_consulta = $detalle_grupo['cantidad'];
$serie_inicial_consulta = $detalle_grupo['serie_inicial'];
$serie_final_consulta = $serie_inicial_consulta + $cantidad_consulta - 1;
$concatenado_detalle =  $concatenado_detalle.",".$detalle_grupo['numero'];
$v_numeros[$n] = $detalle_grupo['numero'];
$n++;
}

$_SESSION['detalle_extra'] = $v_numeros;
$seccionales = mysql_query("SELECT * FROM seccionales WHERE id_empresa = 5 ORDER BY ruta ASC, cod_seccional ASC ");


?>

<form method="POST">

<div align="center" style="width:100%; ">
 <input type="hidden" name="id_sorteo_oculto" value="<?php $id_sorteo; ?>">
 <div class = "alert alert-info">
 <h3 align="center">
  Sorteo Numero: <?php if (isset($sorteo)) {echo $sorteo;} ?>  
 Fecha de Sorteo: <?php if (isset($sorteo)) {echo $fecha_sorteo;} ?>  
 </h3> 
</div>
</div>
<br>

<div class = 'alert alert-info'>
Detalle de Numeros: <?php echo $concatenado_detalle; ?>
<br>
Serie Inicial: <?php echo $serie_inicial_consulta; ?>
<br>
Serie Final: <?php echo $serie_final_consulta; ?>
<br>
Cantidad:<?php echo $cantidad_consulta; ?>
</div>


<?php 
echo "<table  width = '100%'  class= 'table table-hover table-bordered'>";
echo "<tr>
<th width = '5%'>Ruta</th>
<th width = '5%'>Cod. Seccional</th>
<th width = '60%'>Nombre</th>
<th width = '10%'>Serie Inicial</th>
<th width = '10%'>Serie Final</th>
<th width = '10%'>Cantidad a Asignar</th>
</tr>";

$i = 0; 
while ($row = mysql_fetch_array($seccionales)) {
echo "<tr>
<td align= 'center'>".$row['ruta']."</td>
<td align= 'center'>".$row['cod_seccional']."</td>
<td align= 'center'>".$row['nombre']."</td>
<input type = 'hidden' value = '".$row['id']."' name = 'id_seccional".$i."'>

<td align= 'center'><input class = 'form form-control' max = '".$serie_final_consulta."' min = '".$serie_inicial_consulta."' type = 'number' id = 'serie_inicial".$i."' name = 'serie_inicial".$i."'></td>

<td align= 'center'><input class = 'form form-control' max = '".$serie_final_consulta."' min = '".$serie_inicial_consulta."' type = 'number' id = 'serie_final".$i."' name = 'serie_final".$i."' onchange ='calcular_cantidad(".$i.")'></td>

<td align= 'center'><input class = 'form form-control' type = 'number' id = 'cantidad_seccional".$i."' name = 'cantidad_seccional".$i."' readonly ></td>
</tr>";
$i++;
}
echo "</table>";

?>

<p align="center"><button type="submit" name="guardar_distribucion" class="btn btn-primary" >Guardar Distribucion</button></p>

</form>



<?php 

if (isset($_POST['guardar_distribucion'])) {
$v_numeros =  $_SESSION['detalle_extra'];
$cantidad_total = 0;
$i = 0;
while (isset($_POST['id_seccional'.$i])) {

if ($_POST['id_seccional'.$i] != 0 && $_POST['id_seccional'.$i] != '' ) {

$id_seccional = $_POST['id_seccional'.$i];
$cantidad = $_POST['cantidad_seccional'.$i];

if ($cantidad != ''){

$cantidad_total = $cantidad_total + $_POST['cantidad_seccional'.$i];

$serie_inicial = $_POST['serie_inicial'.$i];
$serie_final = $_POST['serie_final'.$i];

$n = 0;
while (isset($v_numeros[$n])) {
$numero = $v_numeros[$n];
if (mysql_query(" INSERT INTO  menor_seccionales_numeros (id_sorteo,numero, serie_inicial, serie_final, cantidad, id_seccional, origen) VALUES ('$id_sorteo' ,'$numero',$serie_inicial,$serie_final,$cantidad,'$id_seccional','Numeros' )  ") === TRUE) {



if ($id_seccional == 9) {

$cod_consignacion = $id_sorteo;

if (mysql_query(" INSERT INTO  fvp_menor_reservas_numeros (sorteos_menores_id,numero,serie_inicial,serie_final,cod_consignacion) VALUES ('$id_sorteo','$numero',$serie_inicial,$serie_final,$cod_consignacion)  ") === FALSE) {
echo mysql_error();
}

}

}else{
	echo mysql_error();
}

$n++;
}

}
}
$i++;
}


?>
<script type="text/javascript">
  swal({ 
  title: "",
   text: "Registros Guardados Correctamente",
    type: "success" 
  },
  function(){
    window.location.href = './fvp_distribucion_pedidos_menor_numeros_grupos.php';
});
</script>

<?php


}

?>