<?php
require('../../template/header.php');

$id_sorteo =  $_SESSION['cc_mayor'];

$revisores_asignados = mysqli_query($conn,"SELECT a.numero,a.billete_inicial,a.billete_final,b.nombre_completo, a.billete_final - a.billete_inicial + 1 as cantidad  FROM cc_revisores_sorteos_mayores as a INNER JOIN pani_usuarios as b ON a.id_revisor = b.id WHERE a.id_sorteo = '$id_sorteo' ");

$info_sorteo = mysqli_query($conn,"SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo' ");

$i_sorteo = mysqli_fetch_object($info_sorteo);
$numero_sorteo = $i_sorteo->no_sorteo_may;
$fecha_sorteo = $i_sorteo->fecha_sorteo;
$cantidad_billetes = $i_sorteo->cantidad_numeros;


?>



<form method="POST">


<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >LOTERIA MAYOR ASIGNADA PARA REVISION</h2> 

<h4 style="color:black; " align="center">
Sorteo Numero: <?php echo $numero_sorteo;?>
 | Fecha del Sorteo: <?php echo $fecha_sorteo;?>
<br>
Cantidad de Billetes: <?php echo number_format($cantidad_billetes);?> 
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
echo "<td>".$revisor['billete_inicial']."</td>";
echo "<td>".$revisor['billete_final']."</td>";
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
$id_sorteo = $_SESSION['cc_mayor'];

mysqli_query($conn,"UPDATE sorteos_mayores SET control_calidad = 'NO'  WHERE id = '$id_sorteo' ");

if (mysqli_query($conn,"DELETE FROM `cc_revisores_sorteos_mayores` WHERE id_sorteo = '$id_sorteo' ")=== false) {
echo mysqli_error($conn);
}else{
 ?>
<script type="text/javascript">

swal({
title: "",
  text: "Cambios realizados exitosamente",
  type: "success" 
})
.then(() => {
    window.location.href = './screen_cc_sorteos_pendientes_mayor.php';
});

</script>
<?php
 
}

}
?>
