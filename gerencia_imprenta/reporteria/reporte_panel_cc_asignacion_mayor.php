<?php
require('../../template/header.php');

$select_sorteos = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE control_calidad = 'SI' ORDER BY no_sorteo_may DESC ");

?>





<form method="POST">

<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >DEPARTAMENTO DE CONTROL DE CALIDAD DEL PANI</h2> 
<h2  align="center" style="color:black; "  >FORMATO PARA REVISION DE SORTEOS MAYOR O MENOR</h2> 
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

$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo' ");

$i_sorteo = mysqli_fetch_object($info_sorteo);
$numero_sorteo = $i_sorteo->no_sorteo_may;
$fecha_sorteo = $i_sorteo->fecha_sorteo;
$fecha_vencimiento = $i_sorteo->fecha_vencimiento;


$fecha_sorteo = date_create($fecha_sorteo);
$fecha_sorteo = date_format($fecha_sorteo, 'd-m-Y');

$fecha_actual = date_create($fecha_actual);
$fecha_actual = date_format($fecha_actual, 'd-m-Y');

$fecha_vencimiento = date_create($fecha_vencimiento);
$fecha_vencimiento = date_format($fecha_vencimiento, 'd-m-Y');


$revisores_asignados = mysqli_query($conn, "SELECT a.numero,a.billete_inicial,a.billete_final,b.nombre_completo  FROM cc_revisores_sorteos_mayores as a INNER JOIN pani_usuarios as b ON a.id_revisor = b.id WHERE a.id_sorteo = '$id_sorteo'  ");


echo ' Los abajo firmantes recibimos el dia '.$fecha_actual.' lo siguiente: <br><br>
<b>SORTEO DE LOTERIA MAYOR '.$numero_sorteo.'</b> a realizarse el dia '.$fecha_sorteo.', con fecha de caducidad '.$fecha_vencimiento;

?>

<br><br><br>

<input type="hidden" name="id_sorteo_oculto"  value="<?php echo $id_sorteo;?>"></input>




  <table id="print" width="100%" class="table table-bordered"  id="detalle_revisor">
    <tr>
      <th width="5%">No.</th>    
      <th width="35%">Nombre del Revisor</th>
      <th width="12.5%">Desde Billete</th>      
      <th width="12.5%">Hasta Billete</th>
      <th width="35%">Firma</th>            
    </tr>
<?php

$i = 0;
$j = 1;
while ($revisor = mysqli_fetch_array($revisores_asignados)) {
echo "<tr>";
echo "<td>".$revisor['numero']."</td>";
echo "<td>".$revisor['nombre_completo']."</td>";
echo "<td>".$revisor['billete_inicial']."</td>";
echo "<td>".$revisor['billete_final']."</td>";
echo "<td></td>";
echo "</tr>";
$i++;
$j++;
}

?>
  </table>

<br><br>
Cantidades recibidas, sujetas a revisión.
<br>
Firmamos la presente el dia <?php echo $fecha_actual; ?>
<br><br>
<br><br>
<br><br>


<p align="center">_________________________________________________________________</p>
<p align="center"><b>JEFE DEL DEPARTAMENTO DE CONTROL DE CALIDAD</b></p>


<?php
}
?>


</div>
</div>



</form>