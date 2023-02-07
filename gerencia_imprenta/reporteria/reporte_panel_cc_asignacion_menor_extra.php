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
Asignacion de Loteria Menor para Revision
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

$revisores_asignados = mysql_query("SELECT a.numero,a.grupo,a.serie_inicial,a.serie_final,b.nombre_completo  FROM cc_revisores_sorteos_menores_extras as a INNER JOIN pani_usuarios as b ON a.id_revisor = b.id WHERE a.id_sorteo = '$id_sorteo'  ");


$info_sorteo = mysql_query("SELECT * FROM sorteos_menores WHERE id = '$id_sorteo' ");

$i_sorteo = mysql_fetch_object($info_sorteo);
$numero_sorteo = $i_sorteo->no_sorteo_men;
$fecha_sorteo = $i_sorteo->fecha_sorteo;

?>

<input type="hidden" name="id_sorteo_oculto"  value="<?php echo $id_sorteo;?>"></input>
<hr>

<style type="text/css">
  #print {
  font-size: 9pt;
  font-family:'Times New Roman',Times,serif;
}
</style>

<div id="print" style="width: 100%" align="center">
<div id="print" class="alert alert-info" style="align:center">
<p style="font-size: 12pt;">  
Sorteo Numero: <?php echo $numero_sorteo;?>
 | Fecha del Sorteo:   <?php echo $fecha_sorteo;?>
</p>
</div>


  <table id="print" width="100%" class="table table-condensed" border = '1' id="detalle_revisor">
    <tr>
      <th width="5%">No.</th>    
      <th width="40%">Nombre</th>
      <th width="5%">grupo</th>      
      <th width="10%">Desde</th>      
      <th width="10%">Hasta</th>
      <th width="30%">Firma</th>            
    </tr>
<?php

$i = 0;
$j = 1;
while ($revisor = mysql_fetch_array($revisores_asignados)) {
echo "<tr>";
echo "<td>".$revisor['numero']."</td>";
echo "<td>".$revisor['nombre_completo']."</td>";
echo "<td>".$revisor['grupo']."</td>";
echo "<td>".$revisor['serie_inicial']."</td>";
echo "<td>".$revisor['serie_final']."</td>";
echo "<td></td>";
echo "</tr>";
$i++;
$j++;
}

?>
  </table>


</div>

<?php
}
?>

</form>

</div>
</div>
