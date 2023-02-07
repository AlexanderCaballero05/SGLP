<?php 
require("template/header.php");

$c_sorteos = mysqli_query($conn,"SELECT * FROM sorteos_menores  ORDER BY id DESC ");

?>


<form method="POST">




<section  style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >REPORTE DE PRODUCCION DE LOTERIA MENOR</h2> 
<br>
</section>

<br>

<div class="card" >
<div class="card-header" align="center" id="non-printable">
<div class="input-group" style="margin:10px 0px 10px 0px; width: 50%" >
<div class="input-group-prepend"><span  class="input-group-text">Seleccione un sorteo: </span></div>
 <select class="form-control" name="sorteo">
   <?php
   while ($sorteo = mysqli_fetch_array($c_sorteos)) {
     echo "<option value = '".$sorteo['id']."'>".$sorteo['no_sorteo_men']." -- Fecha ".$sorteo['fecha_sorteo']." -- "."</option>";
   }
   ?>
 </select> 
<input  type="submit" name="seleccionar" class="btn btn-primary" value= "Seleccionar"> 

</div>    
</div>

<div class="card-body">
  
<?php

if (isset($_POST['seleccionar'])) {

$id_sorteo = $_POST['sorteo'];
$info_sorteo = mysqli_query($conn,"SELECT * FROM sorteos_menores WHERE id = '$id_sorteo'  ");
$ob_sorteo = mysqli_fetch_object($info_sorteo);
$fecha_sorteo =  $ob_sorteo->fecha_sorteo;
$series =  $ob_sorteo->series;


$c_produccion = mysqli_query($conn,"SELECT numero ,MIN(serie_inicial) as serie_inicial , MAX(serie_inicial) + cantidad - 1 as serie_final , SUM(cantidad) as cantidad FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo' GROUP BY numero ORDER BY numero ASC ");


?>

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 align="center">PRODUCCION POR NUMERO DEL SORTEO <?php echo $id_sorteo; ?></h3>
  </div>
  <div class="panel-body">

<table class = 'table table-bordered'>
  <tr>
    <th>Numero</th>
    <th>Serie Inicial</th>
    <th>Serie Final</th>
    <th>Cantidad</th>
  </tr>

<?php 

$total_cantidad = 0;

while ($reg_produccion = mysqli_fetch_array($c_produccion)) {

echo "<tr>";  
echo "<td>".$reg_produccion['numero']."</td>";
echo "<td>".$reg_produccion['serie_inicial']."</td>";
echo "<td>".$reg_produccion['serie_final']."</td>";
echo "<td>".number_format($reg_produccion['cantidad'])."</td>";
echo "</tr>"; 

$total_cantidad += $reg_produccion['cantidad'];

}


echo "<tr>";
echo "<th colspan = '3'>TOTAL</th>";
echo "<th>".number_format($total_cantidad)."</th>";
echo "</tr>";

?>


</table>    
  </div>

</div>

<?php

}

?>

</div>
</div>

</form>

