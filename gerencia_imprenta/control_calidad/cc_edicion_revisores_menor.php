<?php
require('../../template/header.php');

$id_sorteo =  $_SESSION['cc_menor'];

$revisores_asignados = mysqli_query($conn,"SELECT a.numero,a.serie_inicial,a.serie_final,b.nombre_completo , a.serie_final - a.serie_inicial + 1 as cantidad FROM cc_revisores_sorteos_menores as a INNER JOIN pani_usuarios as b ON a.id_revisor = b.id WHERE a.id_sorteo = '$id_sorteo' ");


$info_sorteo = mysqli_query($conn,"SELECT * FROM sorteos_menores WHERE id = '$id_sorteo' ");

$i_sorteo = mysqli_fetch_object($info_sorteo);
$numero_sorteo = $i_sorteo->no_sorteo_men;
$fecha_sorteo = $i_sorteo->fecha_sorteo;
$cantidad_billetes = $i_sorteo->series;

?>


<form method="POST">


<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >LOTERIA MENOR ASIGNADA PARA REVISION</h2> 

<h4 style="color:black; " align="center">
Sorteo Numero: <?php echo $numero_sorteo;?>
 | Fecha del Sorteo: <?php echo $fecha_sorteo;?>
<br>
Cantidad de Series: <?php echo number_format($cantidad_billetes);?> 
</h4>

</section>




<input type="hidden" name="id_sorteo_oculto"  value="<?php echo $id_sorteo;?>"></input>


<div align="center">
<div class="card" style="width: 80%">
<div class="card-body">


  <table class="table table-bordered" id="detalle_revisor">
    <tr>
      <th >No.</th>    
      <th width="50%">revisor</th>
      <th >Desde Billete</th>      
      <th >Hasta Billete</th>            
      <th >Cantidad de Billetes</th>            
    </tr>
<?php

$i = 0;
$j = 1;
while ($revisor = mysqli_fetch_array($revisores_asignados)) {
echo "<tr>";
echo "<td>".$revisor['numero']."</td>";
echo "<td>".$revisor['nombre_completo']."</td>";
echo "<td>".$revisor['serie_inicial']."</td>";
echo "<td>".$revisor['serie_final']."</td>";
echo "<td>".number_format($revisor['cantidad'])."</td>";
echo "</tr>";
$i++;
$j++;
}

?>
  </table>


</div>
<div class="card-footer" align="center">
  <button type="submit" name="reasignar" class="btn btn-danger">Reasignar</button>  
</div>
</div>
</div>


</form>


<?php
if (isset($_POST['reasignar'])) {
$id_sorteo = $_SESSION['cc_menor'];

mysqli_query($conn,"UPDATE sorteos_menores SET control_calidad = 'NO'  WHERE id = '$id_sorteo' ");

if (mysqli_query($conn,"DELETE FROM `cc_revisores_sorteos_menores` WHERE id_sorteo = '$id_sorteo' ")=== false) {
echo mysqli_error();
}else{
 ?>
<script type="text/javascript">
swal({
title: "",
  text: "Cambios realizados exitosamente",
  type: "success" 
})
.then(() => {
    window.location.href = './screen_cc_sorteos_pendientes_menor.php';
});
</script>
<?php
 
}

}
?>
