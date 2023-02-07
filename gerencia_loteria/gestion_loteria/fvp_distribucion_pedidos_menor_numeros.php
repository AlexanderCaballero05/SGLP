<?php
require('./template/header.php');
require('./fvp_distribucion_pedidos_menor_numeros_db.php');
?>

<form method="POST">

<ul class="nav nav-tabs">
  <li ><a  href="./fvp_distribucion_pedidos_mayor.php">Distribución Loteria Mayor</a></li>
  <li ><a  href="./fvp_distribucion_pedidos_menor_bolsas.php">Distribución Loteria Menor Bolsas</a></li>
   <li class="active"><a  data-toggle="tab">Distribución Loteria Menor Extra por Numero</a></li>
  <li ><a  href="./fvp_distribucion_pedidos_menor_numeros_grupos.php">Distribución de Loteria Menor Extra por Grupos</a></li>
</ul>

<div class="tab-content">
  <div id="home" class="tab-pane fade in active">

<h3 align="center">DISTRIBUCIÓN DE LOTERIA MENOR</h3>
<br>
<br>
<p align="center">
  Seleccione un Sorteo: <select name="sorteo" style="width: 30%">
    <?php
while ($row2 = mysql_fetch_array($sorteos)) {
echo '<option value = "'.$row2['id'].'">'.$row2['no_sorteo_men'].'</option>' ;
}
    ?>
  </select> 
<input type="submit" name="seleccionar" class="btn btn-primary" value="Seleccionar"></p>
<hr>

<?php 
if (isset($_POST['seleccionar']) ) {

?>

<div align="center" style="width:100%; ">
 <input type="hidden" name="id_sorteo_oculto" value="<?php $id_sorteo; ?>">
 <h3 align="center">
  Sorteo Numero: <?php if (isset($sorteo)) {echo $sorteo;} ?>  
 Fecha de Sorteo: <?php if (isset($sorteo)) {echo $fecha_sorteo;} ?>  
 </h3> 
</div>

<br>

<div style="overflow: scroll;height:300px ;width:100%" class="well">
<h4 align="center">Numeros Extras Producidos sin Distribucion</h4>

<table id="numeros_extras" width="80%" class="table table-hover table-bordered">
<tr>
<th>Numero</th>
<th>Cantidad</th>
<th>Accion</th>
</tr>
 <?php
$i = 0;
while (isset($v_numero[$i]) ) {
echo "<tr>
<td>
".$v_numero[$i]."
<input type = 'hidden' id = 'o_numero".$i."'   value = '".$v_numero[$i]."' disabled>
</td>
<td>
<input type = 'hidden' id = 'o_cantidad".$i."' value = '".$v_cantidad[$i]."' disabled>
<input type = 'text'   id = 'cantidad".$i."'   value = '".$v_cantidad[$i]."' disabled>
</td>";
if ($v_cantidad[$i] == 0) {
echo "<td></td>";
}else{
echo "<td><button type = 'submit' class = 'btn btn-primary' name = 'distribuir' value= '".$v_numero[$i]."'>Asignar</button></td>";
}
echo "</tr>";
$i ++;
}
 ?> 
 </table>
</div>

<br>


<a class="btn btn-info" style="width:100%" role="button" data-toggle="collapse" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
<h3>  Historico de Distribucion </h3>
</a>

<div  class="collapse" style = "width:100%"  id="collapse2">
<div class="well">
<?php 

echo "<table  width = '100%'  class= 'table table-hover table-bordered'>";
echo "<tr>
<th width = '5%'>Ruta</th>
<th width = '5%'>Seccional</th>
<th width = '45%'>Nombre</th>
<th width = '5%'>Numero</th>
<th width = '10%'>Serie Inicial</th>
<th width = '10%'>Serie Final</th>
<th width = '10%'>Cantidad</th>
<th width = '10%'>Accion</th>
</tr>";

$cantidad_total = 0;
$bolsas_asignadas = mysql_query("SELECT a.id,a.numero,a.cantidad, a.serie_inicial, a.serie_final,b.ruta,b.cod_seccional,b.nombre FROM menor_seccionales_numeros as a INNER JOIN seccionales as b ON a.id_seccional = b.id  WHERE a.id_sorteo = '$id_sorteo' ORDER BY b.ruta ASC, b.cod_seccional ASC, a.numero ASC ");
while ($bolsa_asignada = mysql_fetch_array($bolsas_asignadas)) {
$cantidad_total = $cantidad_total + $bolsa_asignada['cantidad'];
echo "<tr>";
echo "<td>". $bolsa_asignada['ruta']."</td>";
echo "<td>". $bolsa_asignada['cod_seccional']."</td>";
echo "<td>". $bolsa_asignada['nombre']."</td>";
echo "<td>". $bolsa_asignada['numero']."</td>";
echo "<td>". $bolsa_asignada['serie_inicial']."</td>";
echo "<td>". $bolsa_asignada['serie_final']."</td>";
echo "<td>". $bolsa_asignada['cantidad']."</td>";
echo "<td align = 'center'><button class = 'btn btn-danger' value = '".$bolsa_asignada['id']."' name = 'eliminar_distribucion'>x</button></td>";
echo "</tr>";
}

echo "<tr>";
echo "<td colspan = '6'></td>";
echo "<td>".$cantidad_total."</td>";
echo "<td></td>";
echo "</tr>";
echo "</table>";

?>
</div>
</div>



<?php
  } 
?> 


</div>
</div>

<br>


<br>
</form>