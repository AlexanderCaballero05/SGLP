<style type="text/css">
  #print {
}
</style>

<script type="text/javascript">
  function imprimir(){
document.getElementById('boton_print').style.display = "none";  
document.getElementById('alert').style.display = "none";  
window.print();
document.getElementById('boton_print').style.display = "block"; 
document.getElementById('alert').style.display = "block";  
}
</script>

<?php
require('../../template/header.php');

$parametros = $_GET['par'];
$vector = explode("_", $parametros);

$id_sorteo = $vector[0];
$rev =  $vector[1];
$num_rev =  $vector[2];

$max_extra  = mysql_query("SELECT MAX(cantidad) as maximo FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo'");
if (mysql_num_rows($max_extra) == 0) {
$cantidad_extra_mayor =  0; 
}else{
$ob_extra = mysql_fetch_object($max_extra); 
$cantidad_extra_mayor =  $ob_extra->maximo; 
}


$info_sorteo = mysql_query("SELECT * FROM sorteos_menores WHERE id = '$id_sorteo' ");
$ob_sorteo = mysql_fetch_object($info_sorteo);
$no_sorteo = $ob_sorteo->no_sorteo_men;
$fecha_sorteo = $ob_sorteo->fecha_sorteo;
$series = $ob_sorteo->series;
$desde_registro = $ob_sorteo->desde_registro;

$masc = strlen($series);
$masc_rec = 5;



$info_revisor = mysql_query("SELECT a.nombre_completo, b.numero FROM pani_usuarios as a INNER JOIN cc_revisores_sorteos_menores_extras as b ON a.id = b.id_revisor  WHERE a.id = '$rev' ");

$ob_revisor = mysql_fetch_object($info_revisor);
$num_revisor = $ob_revisor->numero;
$nombre_revisor = $ob_revisor->nombre_completo;

echo "<div class = 'alert alert-info' align = 'center'>";
echo "<b>DEPARTAMENTO DE CONTROL DE CALIDAD (PANI)</b>";
echo "<p>REPOSICIONES LOTERIA MENOR</p>";
echo '
<div id="boton_print">
<span id="boton_print"  class="btn btn-primary" onclick="imprimir()" >
<span class = "glyphicon glyphicon-print"></span>
</span>
</div>
';
echo "</div>";

echo "<p style = 'font-size:12px'>Reporte de errores detectados en loteria mayor </p>";
echo "<p style = 'font-size:12px'>Sorteo No. <u>".$no_sorteo."</u> De fecha: <u>".$fecha_sorteo."</u></p>";
echo "<p style = 'font-size:12px'>Nombre de Revisor. <u>".$nombre_revisor."</u> Lista No: <u>".$num_revisor."</u></p>";

echo "<div id='print'>";

?>
<table border = '1' class='table table-condensed'  border = '1' style= 'width:100%'>

  <thead>
    <tr>
      <th style="width:25%">Numero</th>
      <th style="width:25%">Serie</th>
      <th style="width:25%">Registro</th>
      <th style="width:10%">R. E.</th>      
      <th style="width:15%">Cantidad</th>
    </tr>
  </thead>
  <tbody>

<?php

$inventario_rechazado = mysql_query("SELECT * FROM cc_revisores_sorteos_menores_extras_control WHERE id_sorteo = '$id_sorteo' AND  id_revisor = '$rev'  AND numero_revision = '$num_rev' ORDER BY serie ASC ");

if ($inventario_rechazado === false) {
echo mysql_error();
}

while ($inventario_r = mysql_fetch_array($inventario_rechazado)) {


echo "<tr>";
echo "<td><p style = 'font-size:12px'>".$inventario_r['detalle_numeros']."</p></td>";
echo "<td><p style = 'font-size:12px'>".str_pad($inventario_r['serie'], $masc, '0', STR_PAD_LEFT)."</p></td>";
echo "<td><p style = 'font-size:12px'>".$inventario_r['detalle_registros']."</p></td>";
if ($inventario_r['especial'] == 'E') {
echo "<td><p style = 'font-size:12px'>R. E.</p></td>";
}else{
echo "<td><p style = 'font-size:12px'></p></td>";  
}
echo "<td><p style = 'font-size:12px'>1</p></td>";
echo "</tr>";
}


$consulta_suma = mysql_query("SELECT COUNT(*) as suma FROM cc_revisores_sorteos_menores_extras_control WHERE id_sorteo = '$id_sorteo' AND  id_revisor = '$rev'  AND numero_revision = '$num_rev' ");

$ob_suma = mysql_fetch_object($consulta_suma);
$suma = $ob_suma->suma;

?>

  </tbody>

<?php
echo "<tr>";
echo "<td colspan = '4'>TOTAL</td>";
echo "<td>".$suma."</td>";
echo "<tr>";
echo "</table>";
echo "</div>";


date_default_timezone_set('America/El_Salvador');
$fecha = date("Y-m-d");


?>

<p style = 'font-size:12px'>Para reponer se entregan <u><?php echo $suma; ?></u> pliegos Fecha <u><?php echo $fecha; ?></u></p>

<p style = 'font-size:12px'>Revisor <u><?php echo $nombre_revisor; ?></u></p>