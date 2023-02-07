<br>
<div class="card card-primary" style="margin-left: 10px;margin-right: 10px">
<div class="card-header">
<h4 align="center">Previsualizacion de Produccion</h4>
</div>

<div class="card-body" >



<table width="100%">

<tr>
<td width="39%" valign="top">
<div class="alert alert-info" >
<h4 align="center"><b>Produccion Normal</b></h4>
<hr>

 <table  class="table table-hover table-bordered">   
        <thead>        
            <tr>
            <th width="33%">Numeros</th>
            <th width="33%">Series</th>
            <th width="34%">Registros </th>
            </tr>   
        </thead> 
        <tbody>

<?php 

require("../../conexion.php");

$k = 0;

$id_sorteo = $_GET['s'];
$series = $_GET['ser']-1;
$i = 0;
$n_inicial = 0;
$n_final = 0;
$registro = $_GET['r_i'];
$registro_inicial = $_GET['r_i'];
$registro_final = $registro_inicial + $series;
$registro_adicional = 0; 


$salto[1] = $_GET['s1'];
$salto[2] = $_GET['s2'];
$salto[3] = $_GET['s3'];
$salto[4] = $_GET['s4'];
$salto[5] = $_GET['s5'];
$salto[6] = $_GET['s6'];
$salto[7] = $_GET['s7'];
$salto[8] = $_GET['s8'];
$salto[9] = $_GET['s9'];


while ($i < 10) {

if (isset($salto[$i])) {
$registro_adicional = $salto[$i];
}else{
$registro_adicional = 0; 
}

$n_inicial = $i * 10;
$n_final = $n_inicial + 9;


$registro_inicial = $registro_inicial + $registro_adicional;
$registro_final = $registro_inicial + $series;

if ($registro_inicial  > 99999) {
$sobrante = $registro_inicial - 100000;
$registro_inicial = $sobrante;
}


if ($registro_final  > 99999) {
$sobrante = $registro_final - 100000;
$registro_final = $sobrante;
}


$n_inicial = str_pad($n_inicial, 2, '0', STR_PAD_LEFT);
$n_final = str_pad($n_final, 2, '0', STR_PAD_LEFT);

$registro_inicial = str_pad($registro_inicial, 5, '0', STR_PAD_LEFT);
$registro_final = str_pad($registro_final, 5, '0', STR_PAD_LEFT);

echo "<tr>

<td>".$n_inicial." - ".$n_final."</td>
<td>0000 - ".$series."</td>
<td>".$registro_inicial." - ".$registro_final."</td>
</tr>"; 


$i = $i + 1; 


$v_registros_numeros[$k] = $registro_final + 1;
$k ++;


if ($salto[1] == 0) {
$registro_inicial = $registro_final + 1;
}else{
$registro_inicial = $registro_final + 1;
}
  
$registro_final = $registro_inicial + $series;


}


?>

</tbody>        
</table>
</div>
	
</td>	
<td width="1%"></td>	
<td width="60%" valign="top">
	

<?php 


$result2 = mysqli_query($conn,"SELECT * FROM sorteos_menores_num_extras WHERE estado_sorteo = 'PENDIENTE DISTRIBUCION' AND id_sorteo = '$id_sorteo' order by grupo ASC, numero ASC ");
 
if ($result2 != null){

$i = 0;
while ($row2 = mysqli_fetch_array($result2)) {
$numeros_extras[$i] = $row2['numero'];
$cantidades_extras[$i] = $row2['cantidad'];
$estados_extras[$i] = $row2['estado_sorteo'];
$grupos[$i] = $row2['grupo'];
$id_extras[$i] = $row2['id'];
$i ++;
}

}


$result3 = mysqli_query($conn,"SELECT * FROM sorteos_menores_num_extras WHERE estado_sorteo != 'PENDIENTE DISTRIBUCION' AND id_sorteo = '$id_sorteo' order by  grupo ASC, numero ASC ");

if ($result3 != null){

while ($row3 = mysqli_fetch_array($result3)) {
$numeros_extras[$i] = $row3['numero'];
$cantidades_extras[$i] = $row3['cantidad'];
$estados_extras[$i] = $row3['estado_sorteo'];
$grupos[$i] = $row3['grupo'];
$id_extras[$i] = $row3['id'];
$i ++;
}

}

?>

<div class="alert alert-success">
<h4 align="center"><b>Produccion Extra</b></h4>
<hr>
<div style="overflow-y: scroll; height:555px;width: 100%">
<table width="100%" class="table table-hover table-bordered" >
<tr>
  <th>Numero</th>
  <th>Cantidad</th>
  <th>Series</th>
  <th>Registros</th>
  <th>Grupo</th>
  <th>Estado</th>
</tr>
<?php

$serie_extra = $series + 1;
$i = 0;
$num_produccion_extra[] = '';
$num_grupo = 0;
$grupo = 1;

while (isset($numeros_extras[$i])) {


if (in_array($numeros_extras[$i],$num_produccion_extra)) {

$j = 0;
while (isset($num_produccion_extra[$j])) {

if ($num_produccion_extra[$j] == $numeros_extras[$i]) {
$num_serie_inicial_extra[$i] = $num_serie_final_extra[$j] + 1;
$num_serie_final_extra[$i] = $num_serie_inicial_extra[$i] + $cantidades_extras[$i] -1;



$num_registro_inicial_extra[$i] = $num_registro_final_extra[$j]+1;
$num_registro_final_extra[$i] = $num_registro_inicial_extra[$i]  +  $cantidades_extras[$i] -1;


if ($num_registro_final_extra[$i]  > 99999) {
$sobrante = $num_registro_final_extra[$i] - 100000;
$num_registro_final_extra[$i] = $sobrante;
}


}

$j++;
}
$num_produccion_extra[$i] =  $numeros_extras[$i];

}else{
$numero = $numeros_extras[$i];
$decena = $numero/10 ; 

$num_produccion_extra[$i] =  $numeros_extras[$i];
$num_serie_inicial_extra[$i] = $serie_extra;
$num_serie_final_extra[$i] = $series + $cantidades_extras[$i];
$num_registro_inicial_extra[$i] = $v_registros_numeros[$decena];
$num_registro_final_extra[$i] = $num_registro_inicial_extra[$i]  +  $cantidades_extras[$i] -1;
}


if ($num_registro_inicial_extra[$i]  > 99999) {
$sobrante = $num_registro_inicial_extra[$i] - 100000;
$num_registro_inicial_extra[$i] = $sobrante;
}


if ($num_registro_final_extra[$i]  > 99999) {
$sobrante = $num_registro_final_extra[$i] - 100000;
$num_registro_final_extra[$i] = $sobrante;
}


$numeros_extras[$i] = str_pad($numeros_extras[$i], 2, '0', STR_PAD_LEFT);
$n_final = str_pad($n_final, 2, '0', STR_PAD_LEFT);

$num_registro_inicial_extra[$i] = str_pad($num_registro_inicial_extra[$i], 5, '0', STR_PAD_LEFT);
$num_registro_final_extra[$i] = str_pad($num_registro_final_extra[$i], 5, '0', STR_PAD_LEFT);

echo '
<input value = "'.$id_extras[$i].'"                  type = "hidden" name = "id'.$i.'"  >
<input value = "'.$num_serie_inicial_extra[$i].'"    type = "hidden" name = "serie_inicial'.$i.'">
<input value = "'.$num_serie_final_extra[$i].'"      type = "hidden" name = "" id = "serie_inicial'.$i.'">
<input value = "'.$num_registro_inicial_extra[$i].'" type = "hidden" name = "registro_inicial'.$i.'">
<input value = "'.$num_registro_final_extra[$i].'"   type = "hidden" name = "" id = "registro_inicial'.$i.'">';
echo "<input  value = '".$grupos[$i]."' 	     	 type = 'hidden' name = 'grupo_extra".$i."' >";

echo "<tr>";  

echo '<td>'.$numeros_extras[$i].'</td>
<td>'.$cantidades_extras[$i].'</td>
<td>'.$num_serie_inicial_extra[$i].' - '.$num_serie_final_extra[$i].'</td>
<td>'.$num_registro_inicial_extra[$i].' - '.$num_registro_final_extra[$i].'</td>';
echo "<td>".$grupos[$i]."</td>";
echo "<td align='center'>";
if ($estados_extras[$i] == 'PENDIENTE DISTRIBUCION') {
echo "<b style ='color:green;'>PRODUCIDO<b>";
}else{
echo "<b style ='color:red;'>PENDIENTE<b>";
}
echo "</td>";
echo '</tr>';
$i ++;
$num_grupo++;

if ($num_grupo == 10) {
$num_grupo = 0;
$grupo ++;
}

}

?>
</table>
</div>
</div>


</td>	
</tr>	

</table>


</div>

<div class="card-footer" align="center">

<?php 

$c_estado_sorteo = mysqli_query($conn,"SELECT * FROM sorteos_menores WHERE id = '$id_sorteo' ");
$ob_estado_sorteo = mysqli_fetch_object($c_estado_sorteo);
$estado_sorteo = $ob_estado_sorteo->estado_sorteo;

if ($estado_sorteo != 'PENDIENTE DISTRIBUCION') {

?>
  <button class="btn btn-success" name="guardar_produccion" type="submit">GUARDAR PRODUCCION</button>
<?php 

}else{

?>
  <button class="btn btn-danger" name="eliminar_produccion" type="submit">RESETEAR SORTEO </button>
<?php


}

?>

</div>

</div>
