<?php
require('../../template/header.php');

$select_sorteos = mysql_query("SELECT * FROM sorteos_menores WHERE control_calidad_extra = 'SI' ORDER BY no_sorteo_men DESC ");

?>

<script type="text/javascript">
function imprimir(){
document.getElementById('boton_print').style.display = "none";  
document.getElementById('alert').style.display = "none";  
window.print();
document.getElementById('boton_print').style.display = "visible"; 
document.getElementById('alert').style.display = "visible";  
}
</script>


<div  class="tab-content">
  <div id="home" class="tab-pane fade in active" align="center">


<form method="POST">
<div class="alert alert-info"  align="CENTER"> 
Reporte Reposiciones de Loteria Menor


</div>


<p id="boton_print" align="center">
 Seleccione un sorteo 
 <select name="sorteo" style="width:30%">
   <?php
   while ($sorteo = mysql_fetch_array($select_sorteos)) {
     echo "<option value = '".$sorteo['id']."'>".$sorteo['no_sorteo_men']."</option>";
   }
   ?>
 </select> 
<input type="submit" name="seleccionar" class="btn btn-primary" value = "Seleccionar"></input>
<br>

<button id="boton_print"  class="btn btn-primary" onclick='imprimir()' value='Imprimir'>
<span class = 'glyphicon glyphicon-print'></span> Imprimir  
</button>
</p>


<?php

if (isset($_POST['seleccionar'])) {
$id_sorteo = $_POST['sorteo'];


$revisores = mysql_query("SELECT a.id_revisor, b.nombre_completo FROM cc_revisores_sorteos_menores_extras_control as a INNER JOIN pani_usuarios as b ON a.id_revisor = b.id WHERE a.id_sorteo = '$id_sorteo'  GROUP BY a.id_revisor  ");

if ($revisores === false) {
echo mysql_error();
}

while ($revisor = mysql_fetch_array($revisores)) {

$reporte_revision = $revisor['id_revisor'];
$num_rev = $revisor['id_revisor'];

$parametros = $id_sorteo."_".$num_rev;

echo '<br>
<a style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse'.$revisor['id_revisor'].'" aria-expanded="false" aria-controls="collapse'.$revisor['id_revisor'].'">
Revisor '.$revisor['nombre_completo'].'
</a>';

echo "<br>";

echo '<div class="collapse" id="collapse'.$reporte_revision.'">';
echo "<div class = 'well'>";

$numero_revisiones = mysql_query("SELECT * FROM cc_revisores_sorteos_menores_extras_control as a  WHERE a.id_sorteo = '$id_sorteo'  GROUP BY a.numero_revision  ");


while ($revision = mysql_fetch_array($numero_revisiones)) {

$num_rev =  $revision['numero_revision']; 
$parametros = $id_sorteo.'_'.$reporte_revision.'_'.$num_rev;
$num_rev = $num_rev - 1;

echo '<br>
<a style = "width:100%"  class="btn btn-success" target = "blanck" href = "./reporte_panel_cc_reposicion_menor_revisor_detalle_extra.php?par=' .$parametros. '" >
Generar Reporte de Revision '.$num_rev.'
</a>';

}


echo "</div>";
echo "</div>";

}


}
?>

</form>

</div>
</div>
