<?php
require('../../template/header.php');
require('./fvp_distribucion_pedidos_menor_numeros_grupos_db.php');

?>


<form method="POST">


<br>

<ul class="nav nav-tabs">
 <li class="nav-item">
    <a  class="nav-link" href="./screen_distribucion_pedidos_mayor.php" >Distribución Mayor</a>
  </li>
  <li class="nav-item">
    <a  class="nav-link" href="./fvp_distribucion_pedidos_menor_bolsas.php" >Distribución Menor Bolsas</a>
  </li>
  <li class="nav-item">
    <a style="background-color:#ededed;" class="nav-link  active"  >Distribución Menor Extra</a>
  </li>
</ul>


<section style="background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >DISTRIBUCION DE LOTERIA MENOR EXTRA</h2> 
<br>
</section>



<a class="btn btn-info" style="width:100%" role="button" data-toggle="collapse" href="#collapse3" aria-expanded="false" aria-controls="collapse3">
 Selección de Parametros 
</a>

<div  class="collapse" style = "width:100%"  id="collapse3" align="center">
<div class="card" align="center" style="width: 50%">
<div class="card-body">

<div class="input-group">
<div class="input-group-prepend"><div class="input-group-text">Sorteo: </div></div>  

<select class="form-control" name="sorteo" >
<?php
while ($row2 = mysqli_fetch_array($sorteos)) {
echo '<option value = "'.$row2['id'].'">'.$row2['no_sorteo_men'].'</option>' ;
}
?>
</select>       


<div class="input-group-prepend"><div class="input-group-text">Entidad: </div></div>  

<select class="form-control" name="empresa" >
<?php
while ($row = mysqli_fetch_array($empresas)) {
echo '<option value = "'.$row['id'].'">'.$row['nombre_empresa'].'</option>' ;
}
?>
</select>    


<div class="input-group-append">
<input  type="submit" name="seleccionar" class="btn btn-primary" value="Seleccionar"> 
</div>

</div>

</div>
</div>
</div>


<br>





<?php 
if (isset($_POST['seleccionar']) ) {

$id_sorteo = $_POST['sorteo'];
$id_empresa = $_POST['empresa'];

$info_sorteo = mysqli_query($conn,"SELECT *  FROM sorteos_menores WHERE id = '$id_sorteo' limit 1");
$value = mysqli_fetch_object($info_sorteo);
$sorteo = $value->no_sorteo_men;
$fecha_sorteo = $value->fecha_sorteo;

$info_empresa = mysqli_query($conn,"SELECT * FROM empresas WHERE id = '$id_empresa' limit 1");
$value2 = mysqli_fetch_object($info_empresa);
$nombre_empresa = $value2->nombre_empresa;

$numeros_extras = mysqli_query($conn,"SELECT * FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo' AND estado_sorteo = 'PENDIENTE DISTRIBUCION' GROUP BY  grupo ASC   ");

?>

<br>

<input type="hidden" name="id_sorteo_oculto" name="id_sorteo_oculto" value="<?php echo $id_sorteo; ?>">
<input type="hidden" name="id_empresa_oculto" name="id_empresa_oculto" value="<?php echo $id_empresa; ?>">
 
<div class="card" style="margin-left: 10px; margin-right: 10px;">
<div class="card-header bg-secondary text-white">

 <h3 align="center">
  Sorteo Numero: <?php if (isset($sorteo)) {echo $sorteo;} ?>  
 Fecha de Sorteo: <?php if (isset($sorteo)) {echo $fecha_sorteo;} ?>  
<br><br>
 Empresa: <?php echo $nombre_empresa;?>
 </h3> 
  
</div>  
<div class="card-body">
  

<table class = 'table table-bordered'>
  <tr>
    <th>GRUPO</th>
    <th>DETALLE</th>
    <th>CANT. POR NUMERO</th>
    <th>CANT. TOTAL</th>
    <th>ASIGNADO</th>
    <th colspan="2" style="text-align: center">ACCIONES</th>
  </tr>

  

<?php 

$total_extra_producido = 0;
$j = 0;

while ($num_extra = mysqli_fetch_array($numeros_extras)) {
$cantidad_redistribuida_grupo = 0;
$cantidad_total_grupo = 0;
$grupo = $num_extra['grupo'];
$bandera_distribucion = 0;
$bandera_distribucion_total = 0;

$detalles_grupos_extras = mysqli_query($conn,"SELECT * FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo' AND estado_sorteo = 'PENDIENTE DISTRIBUCION' AND grupo = '$grupo'   ");

$concatenado_detalle = '';

while ($detalle_grupo = mysqli_fetch_array($detalles_grupos_extras)) {

$cantidad_consulta = $detalle_grupo['cantidad'];
$cantidad_total_grupo = $cantidad_total_grupo + $cantidad_consulta;
$serie_inicial_consulta = $detalle_grupo['serie_inicial'];
$serie_final_consulta = $serie_inicial_consulta + $cantidad_consulta - 1;

$concatenado_detalle =  $concatenado_detalle.",".$detalle_grupo['numero'];
$numero = $detalle_grupo['numero'];

$busqueda_distribuidos = mysqli_query($conn,"SELECT SUM(cantidad) as suma_cantidad FROM menor_seccionales_numeros WHERE id_sorteo = $id_sorteo AND numero = '$numero' AND  serie_inicial >= $serie_inicial_consulta AND serie_final <=  $serie_final_consulta ");

while ($r_destribuidos = mysqli_fetch_array($busqueda_distribuidos)) {
if ($r_destribuidos['suma_cantidad']  !=  '') {
$cantidad_redistribuida_grupo = $cantidad_redistribuida_grupo + $r_destribuidos['suma_cantidad'];
}
}

}


echo "<tr>";

echo "<td>";
echo $grupo;
echo "</td>";

echo "<td>";
echo $concatenado_detalle;
echo "</td>";

echo "<td>";
echo $cantidad_consulta;
echo "</td>";

echo "<td>";
echo $cantidad_total_grupo;
echo "</td>";

$total_extra_producido += $cantidad_total_grupo;

echo "<td>";
echo $cantidad_redistribuida_grupo;
echo "</td>";

echo "<td>";
if ($cantidad_redistribuida_grupo == 0) {
$parametros_guardado = $id_sorteo."-".$grupo;
echo "<button name = 'asignar_grupo' value = '".$parametros_guardado."' type = 'submmit' class = 'btn btn-primary'  style = 'width:100%' >Asignar Grupo Completo</button> ";
}else{
echo "<button type = 'submmit' class = 'btn btn-primary'  style = 'width:100%' disabled>Asignar Grupo Completo</button> ";  
}
echo "</td>";

echo "<td>";
if ($cantidad_total_grupo == $cantidad_redistribuida_grupo) {
echo '<a class="btn btn-success" target = "_blank" style="width:100%" role="button"  href="./fvp_distribucion_pedidos_menor_numeros_grupos_detalle_2.php?v1='.$id_sorteo.'&v2='.$grupo.'&v3='.$id_empresa.'">
   Grupo No. '.$grupo.' Asignado Totalmente  
</a>';

}else{
echo '<a class="btn btn-primary" target = "_blank" style="width:100%" role="button"  href="./fvp_distribucion_pedidos_menor_numeros_grupos_detalle_2.php?v1='.$id_sorteo.'&v2='.$grupo.'&v3='.$id_empresa.'">
  Realizar asignacion Detallada del Grupo '.$grupo.' 
</a>';  
}

echo "</td>";

echo "</tr>";

$j ++;
}


?>

<tr>
  <th colspan="3">TOTAL EXTRA PRODUCIDO</th>
  <th ><?php echo $total_extra_producido;?></th>
  <th colspan="3"></th>
</tr>

</table>

</div>
</div>




<br>



<a class="btn btn-info" style="width:100%" role="button" data-toggle="collapse" href="#collapseh" aria-expanded="false" aria-controls="collapseh">
<h3>  Historico de Distribucion </h3>
</a>



<div  class="collapse" style = "width:100%"  id="collapseh">

<br>
<div class="card">
<?php 

echo "<table id = 'table_id1'  width = '100%'  class= 'table table-hover table-bordered'>";
echo "<thead>";
echo "<tr>
<th width = '50%'>Empresa</th>
<th width = '10%'>Numero</th>
<th width = '10%'>Serie Inicial</th>
<th width = '10%'>Serie Final</th>
<th width = '10%'>Cantidad</th>
<th width = '10%'>Accion</th>
</tr>";
echo "</thead>";
echo "<tbody>";

$cantidad_total = 0;
$bolsas_asignadas = mysqli_query($conn,"SELECT a.id,a.numero,a.cantidad, a.serie_inicial, a.serie_final, a.cod_factura, b.nombre_empresa FROM menor_seccionales_numeros as a INNER JOIN empresas as b ON a.id_empresa = b.id  WHERE a.id_sorteo = '$id_sorteo' AND a.id_empresa = '$id_empresa' AND a.origen = 'numeros'  ORDER BY a.numero ASC, a.serie_inicial ASC  ");

if ($bolsas_asignadas === false) {
echo mysqli_error();
}


$contador_asignado = 0;

while ($bolsa_asignada = mysqli_fetch_array($bolsas_asignadas)) {
$cantidad_total = $cantidad_total + $bolsa_asignada['cantidad'];
echo "<tr>";
echo "<td>". $bolsa_asignada['nombre_empresa']."</td>";
echo "<td>". $bolsa_asignada['numero']."</td>";
echo "<td>". $bolsa_asignada['serie_inicial']."</td>";
echo "<td>". $bolsa_asignada['serie_final']."</td>";
echo "<td>". $bolsa_asignada['cantidad']."</td>";

if ($bolsa_asignada['cod_factura'] == null) {
echo "<td align = 'center'><button class = 'btn btn-danger' value = '".$bolsa_asignada['id']."' name = 'eliminar_distribucion'>x</button></td>";
}else{
echo "<td align = 'center'><button class = 'btn btn-danger' value = '".$bolsa_asignada['id']."' name = 'eliminar_distribucion' disabled>x</button></td>";

}

$contador_asignado += $bolsa_asignada['cantidad'];

echo "</tr>";
}

echo "</tbody>";

echo "<tr>
<th colspan = '4'>TOTAL ASIGNADO</th>
<th >".$contador_asignado."</th>
<th ></th>
</tr>";

echo "</table>";

?>
</div>
</div>


<?php

}
?>


<br><br>

</form>