<?php
require('../../template/header.php');

$select_sorteos = mysql_query("SELECT * FROM sorteos_mayores WHERE control_calidad = 'SI' ORDER BY no_sorteo_may DESC ");

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
Reporte Reposiciones de Loteria Mayor
</div>


<p id="boton_print" align="center">
 Seleccione un sorteo 
 <select name="sorteo" style="width:30%">
   <?php
   while ($sorteo = mysql_fetch_array($select_sorteos)) {
     echo "<option value = '".$sorteo['id']."'>".$sorteo['no_sorteo_may']."</option>";
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


$numero_revisiones = mysql_query("SELECT * FROM cc_revisores_sorteos_mayores_control WHERE id_sorteo = '$id_sorteo'  GROUP BY numero_revision  ");

if ($numero_revisiones === false) {
echo mysql_error();
}

while ($numero = mysql_fetch_array($numero_revisiones)) {

$reporte_revision = $numero['numero_revision'] - 1;
$num_rev = $numero['numero_revision'];

$parametros = $id_sorteo."_".$num_rev;



echo '<br>
<a style = "width:100%"  class="btn btn-info" target = "blanck" href = "./reporte_panel_cc_reposicion_mayor_detalle.php?par=' .$parametros. '" >
Generar Reporte de Revision '.$reporte_revision.'
</a>';




}


}
?>

</form>

</div>
</div>
