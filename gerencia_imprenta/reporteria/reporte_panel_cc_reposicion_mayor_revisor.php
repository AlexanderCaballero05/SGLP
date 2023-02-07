<?php
require('../../template/header.php');

$select_sorteos = mysqli_query($conn,"SELECT * FROM sorteos_mayores WHERE control_calidad = 'SI' ORDER BY no_sorteo_may DESC ");

?>



<form method="POST">

<section style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >REPOSICIONES DE LOTERIA MAYOR POR REVISOR</h2> 
<br>

<?php 

if (isset($_POST['seleccionar'])) {
$id_sorteo = $_POST['sorteo'];

$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo' ");

$i_sorteo = mysqli_fetch_object($info_sorteo);
$numero_sorteo = $i_sorteo->no_sorteo_may;
$fecha_sorteo = $i_sorteo->fecha_sorteo;

echo '
<h4 align = "center" style = "color:black" >  
SORTEO: '.$numero_sorteo.' 
FECHA DEL SORTEO:   '.$fecha_sorteo.'
</h4>

';

}

?>

</section>





<br>


<div class="card" style="margin-left: 10px; margin-right: 10px;">
<div class="card-header" align="center" id="non-printable">


<div class="input-group" style="margin:10px 0px 10px 0px; width: 50%" >
<div class="input-group-prepend"><span  class="input-group-text">Seleccione un sorteo: </span></div>
 <select class="form-control" name="sorteo">
   <?php
   while ($sorteo = mysqli_fetch_array($select_sorteos)) {
     echo "<option value = '".$sorteo['id']."'>".$sorteo['no_sorteo_may']."</option>";
   }
   ?>
 </select> 

<input type="submit" name="seleccionar" class="btn btn-primary" value = "Seleccionar">
</div>

</div>  
<div class="card-body">


<?php

if (isset($_POST['seleccionar'])) {
$id_sorteo = $_POST['sorteo'];



$revisores = mysqli_query($conn,"SELECT a.id_revisor,a.num_lista, b.nombre_completo FROM cc_revisores_sorteos_mayores_control as a INNER JOIN pani_usuarios as b ON a.id_revisor = b.id WHERE a.id_sorteo = '$id_sorteo'  GROUP BY a.id_revisor, a.num_lista ORDER BY a.num_lista ");

if ($revisores === false) {
echo mysqli_error();
}

while ($revisor = mysqli_fetch_array($revisores)) {

$reporte_revision = $revisor['id_revisor'];
$num_rev = $revisor['id_revisor'];
$num_lista = $revisor['num_lista'];

$parametros = $id_sorteo."_".$num_rev;

echo '<br>
<a style = "width:100%"  class="btn btn-info" target = "_blanck" role="button" data-toggle="collapse" href="#collapse'.$num_lista.'" aria-expanded="false" aria-controls="collapse'.$num_lista.'">
Revisor '.$revisor['nombre_completo'].'<br>  Asignacion '.$num_lista.'
</a>';

echo "<br>";

echo '<div class="collapse" id="collapse'.$num_lista.'">';
echo "<div class = 'card' align = 'center'>";

$numero_revisiones = mysqli_query($conn,"SELECT * FROM cc_revisores_sorteos_mayores_control as a  WHERE a.id_sorteo = '$id_sorteo' AND num_lista = '$num_lista'  GROUP BY a.numero_revision  ");


while ($revision = mysqli_fetch_array($numero_revisiones)) {

$num_rev =  $revision['numero_revision']; 
$parametros = $id_sorteo.'_'.$reporte_revision.'_'.$num_rev.'_'.$num_lista;
$num_rev = $num_rev - 1;

echo '
<a style = "width:90%; margin-top: 2px; margin-bottom: 2px;"  class="btn btn-success" target = "_blanck" href = "./reporte_panel_cc_reposicion_mayor_revisor_detalle.php?par=' .$parametros. '" >
Generar Reporte de Reposicion '.$num_rev.'
</a>';

}


echo "</div>";
echo "</div>";

}


}
?>


</div>
</div>





</form>