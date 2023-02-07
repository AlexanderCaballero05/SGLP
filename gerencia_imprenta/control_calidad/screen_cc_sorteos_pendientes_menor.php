<?php
require('../../template/header.php');

if (isset($_POST['procesar_menor'])) {
$_SESSION['cc_menor'] = $_POST['procesar_menor'];
?>
<script type="text/javascript">
window.location = "./cc_asignacion_revisores_menor.php";
</script>
<?php
}

if (isset($_POST['procesar_menor_extra'])) {
$_SESSION['cc_menor'] = $_POST['procesar_menor_extra'];
?>
<script type="text/javascript">
window.location = "./cc_asignacion_revisores_menor_extra.php";
</script>
<?php
}

?>


<br>

<form method="POST">

<ul class="nav nav-tabs">
 <li class="nav-item">
    <a style="background-color:#ededed;" class="nav-link active" href="#">Sorteos Pendientes</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="cc_sorteos_historico_menor.php">Historico de sorteos</a>
  </li>
</ul>


<section style="background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >SORTEOS PENDIENTES DE ASIGNACION A REVISORES LOTERIA MENOR</h2> 
<br>
</section>

<br>

<div class="row" style="margin-right: 10px; margin-left: 10px">
<div class="col" >



<div class="card" style="width:100%">

<br>

<div class="">
<table class="table table-hover table-bordered" id="table_id1">   
<thead>        
<tr>            
<th width="20%">Sorteo</th>
<th width="20%">Fecha de Sorteo</th>
<th width="20%">Cantidad</th>
<th align="center" width="10%">Accion</th>
</tr>   
</thead> 
<tbody>  

<?php

$result3 = mysqli_query($conn,"SELECT * FROM sorteos_menores WHERE control_calidad = 'NO' ORDER BY no_sorteo_men DESC   ");

if ($result3 != null){

while ($row3 = mysqli_fetch_array($result3)) {

echo '   <tr>
<td>'.$row3['no_sorteo_men'].'</td>
<td>'.$row3['fecha_sorteo'].'</td>
<td>'.$row3['series'].' series</td>
<td align = "center" >
<button class="btn btn-info"  name="procesar_menor" value="'.$row3['id'].'" type="submit">Procesar</button>
</td>
</tr>
';

}
}



?>


</tbody>
</table>
</div>
</div>

</div>

</div>

</form>