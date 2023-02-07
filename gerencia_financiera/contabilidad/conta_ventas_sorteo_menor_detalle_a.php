<?php

require("../../template/header.php");



date_default_timezone_set('America/Tegucigalpa');

?>

<link href="./dates/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css">
<script src="./dates/moment.min.js"></script>
<script src="./dates/bootstrap-datetimepicker.min.js"></script>

<style type="text/css">
@media print
{
    #non-printable { display: none; }
    #printable { display: block; }
}
</style>

<script type="text/javascript">
            $(function () {
                $('#datetimepicker1').datetimepicker();
            });
</script>
<script type="text/javascript">
            $(function () {
                $('#datetimepicker2').datetimepicker();
            });
</script>


<body>
<form method="POST">


<br>


<ul class="nav nav-tabs">

  <li class="nav-item">
    <a class="nav-link" href="./screen_conta_ventas_sorteo_mayor_detalle.php" >Lotería Mayor</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="./conta_ventas_sorteo_menor_detalle.php" >Lotería Menor</a>
  </li>
  <li class="nav-item">
    <a style="background-color:#ededed;" class="nav-link active" href="#" >Aportaciones Lotería Menor</a>
  </li>

</ul>



<section style="background-color:#ededed;">
<br>
<h2 align="center" style="color:black;" >
  <b>APORTACION A ASOCIACIONES POR SORTEO 

<?php

if (isset($_POST['seleccionar'])) {

$sorteo1 = $_POST['sorteo1'];
$sorteo2 = $_POST['sorteo2'];

echo "<p align = 'center'>DEL  ".$sorteo1." AL ".$sorteo2."</p>";

}


?>

</b></h2> 
<br>
</section>


<a class="btn btn-secondary" id="non-printable" style="width:100%" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
Selección de parametros 
</a>

<div  class="collapse" style = "width:100%"  id="collapse1" align="center">
<div class="card" style="width: 50%">
<div class="card-body">
  
<?php 

$current_date = date("Y-m-d");

$c_sorteos = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE fecha_sorteo <= '$current_date' ORDER BY id DESC");
$c_sorteos2 = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE fecha_sorteo <= '$current_date' ORDER BY id DESC");


?>

<div class="input-group" style="margin:10px 0px 10px 0px;">

<select placeholder="Sorteo Inicial" name="sorteo1" id="sorteo1" class="form-control" >

<?php

foreach ($c_sorteos as $s_sorteos) {

    echo "<option value = ".$s_sorteos['id'].">". $s_sorteos['id'] ." - ". $s_sorteos['fecha_sorteo'] ."</option>";

}

?>

</select>

</div>


<div class="input-group" style="margin:10px 0px 10px 0px;">

<select placeholder="Sorteo Final" name="sorteo2" id="sorteo2" class="form-control" >

<?php

foreach ($c_sorteos2 as $s_sorteos2) {

    echo "<option value = ".$s_sorteos2['id'].">". $s_sorteos2['id'] ." - ". $s_sorteos2['fecha_sorteo'] ."</option>";

}

?>

</select>

</div>


<input type="submit" name="seleccionar" class="btn btn-primary" value="Seleccionar" style="width: 100%">


</div>
</div>
</div>






<?php

function funcion_nombre_mes($numero_mes){

if ($numero_mes == 1) {
$nombre_mes = "Enero";
}elseif ($numero_mes == 2) {
$nombre_mes = "Febrero";
}elseif ($numero_mes == 3) {
$nombre_mes = "Marzo";
}elseif ($numero_mes == 4) {
$nombre_mes = "Abril";
}elseif ($numero_mes == 5) {
$nombre_mes = "Mayo";
}elseif ($numero_mes == 6) {
$nombre_mes = "Junio";
}elseif ($numero_mes == 7) {
$nombre_mes = "Julio";
}elseif ($numero_mes == 8) {
$nombre_mes = "Agosto";
}elseif ($numero_mes == 9) {
$nombre_mes = "Septiembre";
}elseif ($numero_mes == 10) {
$nombre_mes = "Octubre";
}elseif ($numero_mes == 11) {
$nombre_mes = "Noviembre";
}elseif ($numero_mes == 12) {
$nombre_mes = "Diciembre";
}

return $nombre_mes;

}


if (isset($_POST['seleccionar'])) {

$sorteo1 = $_POST['sorteo1'];
$sorteo2 = $_POST['sorteo2'];
    
/////////////////////////////////////////
////////////// APORTACIONES ///////////////

$consulta_banco_a = mysqli_query($conn,"SELECT id_sorteo , YEAR(fecha_venta) as year , MONTH(fecha_venta) as mes ,SUM(cantidad) as cantidad, SUM(aportacion) as aportacion, asociacion_comprador FROM transaccional_ventas_general WHERE estado_venta = 'APROBADO' AND cod_producto = 3 AND id_entidad = 3 AND id_sorteo BETWEEN '$sorteo1' AND '$sorteo2' GROUP BY  id_sorteo , asociacion_comprador ");
if ($consulta_banco_a === FALSE) {
echo mysqli_error($conn);
}

echo "<br><br>";

echo "<div class = 'card' style = 'margin-left:5px;margin-right:5px;'>";
echo "<div class = 'card-header bg-primary text-white'><h4 align = 'center'>DETALLE DE APORTACIONES BANRURAL (BOLSAS)</h4></div>";
echo "<div class = 'card-body'>";

$acumulado_cantidad_a   = 0;
$acumulado_aportacion_a = 0;

$i = 0;
$indice_anterior = ""; 
while ($reg_consulta_banco_a = mysqli_fetch_array($consulta_banco_a)) {

  $year_a = $reg_consulta_banco_a['year'];
  $nombre_mes_a = funcion_nombre_mes($reg_consulta_banco_a['mes']);

  $id_sorteo_a = $reg_consulta_banco_a['id_sorteo'];
  $asociacion_a = $reg_consulta_banco_a['asociacion_comprador'];
  $cantidad_a = $reg_consulta_banco_a['cantidad'];
  $aportacion_a = $reg_consulta_banco_a['aportacion'];

  $v_meses[$id_sorteo_a][$asociacion_a] = ["cantidad" => $cantidad_a, "aportacion" => $aportacion_a]; 

$i++;
}


echo "<table class = 'table table-bordered'>";
echo "<tr>";
echo "<th>Asociacion</th>";

  $columnas = count($v_meses) + 4;
  $ttt = 0;


  foreach ($v_meses as $key2 => $a_sorteo_a) {
    echo "<th>".$key2."</th>";
  }

    echo "<th>Total Bolsas Vendidas</th>";
    echo "<th>Aportación</th>";
    echo "<th>Monto Aportación</th>";
    echo "</tr>";

    echo "<tr>";
    echo "<td>ANAVELH</td>";
  
    $tt = 0;
    foreach ($v_meses as $key2 => $a_sorteo_a) {

      if (isset($a_sorteo_a['A'])) {
        $tt += $a_sorteo_a['A']['cantidad'];
        echo "<td>".number_format($a_sorteo_a['A']['cantidad']) ."</td>";      
      }else{
        echo "<td>0</td>";
      }
  
    }




    echo "<td>".number_format($tt)."</td>";
    echo "<td>2.00</td>";
    echo "<td>".number_format($tt * 2, 2)."</td>";
    $ttt += $tt * 2;
    echo "</tr>";
    


    echo "<tr>";
    echo "<td>ANVLUH</td>";

    $tt = 0;
    foreach ($v_meses as $key2 => $a_sorteo_a) {

      if (isset($a_sorteo_a['B'])) {
        $tt += $a_sorteo_a['B']['cantidad'];
        echo "<td>".number_format($a_sorteo_a['B']['cantidad']) ."</td>";      
      }else{
        echo "<td>0</td>";
      }
  
    }
  
    echo "<td>".number_format($tt)."</td>";
    echo "<td>2.00</td>";
    echo "<td>".number_format($tt * 2, 2)."</td>";
    $ttt += $tt * 2;
    echo "</tr>";

    


    echo "<tr>";
    echo "<td>(ANAVELH + ANVLUH)</td>";

    $tt = 0;
    foreach ($v_meses as $key2 => $a_sorteo_a) {

      if (isset($a_sorteo_a['B'])) {
        $compartido = $a_sorteo_a['A']['cantidad'] + $a_sorteo_a['B']['cantidad'];
        $tt += $compartido;
        echo "<td>".number_format($compartido) ."</td>";      
      }else{
        echo "<td>0</td>";
      }
  
    }
  
    echo "<td>".number_format($tt)."</td>";
    echo "<td>1.00</td>";
    echo "<td>".number_format($tt * 1, 2)."</td>";
    $ttt += $tt;
    echo "</tr>";






    $columnas = count($v_meses) + 3;

    echo "<tr>";
    echo "<th colspan = '".$columnas."' >TOTAL</th>";
    echo "<th>".number_format($ttt , 2)."</th>";
    echo "</tr>";


  echo "</table>";












    echo "<br>";

    echo "<table class = 'table table-bordered'>";
    echo "<tr>";
    echo "<th>Asociacion</th>";

  $columnas = count($v_meses) + 4;


  foreach ($v_meses as $key2 => $a_sorteo_a) {
    echo "<th>".$key2."</th>";
  }

    echo "<th>Total Bolsas Vendidas</th>";
    echo "<th>Aportación</th>";
    echo "<th>Monto Aportación</th>";
    echo "</tr>";




echo "<tr>";
echo "<td>SIN ASOCIACION</td>";

$tt = 0;  
foreach ($v_meses as $key2 => $a_sorteo_a) {

  if (isset($a_sorteo_a['C'])) {
    $tt += $a_sorteo_a['C']['cantidad'];
    echo "<td>".number_format($a_sorteo_a['C']['cantidad']) ."</td>";      
  }else{
    echo "<td>0</td>";
  }

}

echo "<td>".number_format($tt)."</td>";
echo "<td>3.00</td>";
echo "<td>".number_format($tt * 3, 2)."</td>";
echo "</tr>";

echo "</table>";


echo "</div>";
echo "</div>";












////////////// APORTACIONES ///////////////
/////////////////////////////////////////



}


?>