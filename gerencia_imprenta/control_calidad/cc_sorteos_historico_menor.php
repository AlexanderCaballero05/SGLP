<?php
require('../../template/header.php');

if (isset($_POST['procesar_menor'])) {
$_SESSION['cc_menor'] = $_POST['procesar_menor'];
?>
<script type="text/javascript">
window.location = "./cc_edicion_revisores_menor.php";
</script>
<?php
}

?>



<br>


<form method="POST">

<ul class="nav nav-tabs">
 <li class="nav-item">
    <a  class="nav-link " href="screen_cc_sorteos_pendientes_menor.php">Sorteos Pendientes</a>
  </li>
  <li class="nav-item">
    <a style="background-color:#ededed;" class="nav-link active" href="#">Historico de sorteos</a>
  </li>
</ul>

<section style="background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >HISTORICO DE ASIGNACION A REVISORES LOTERIA MENOR</h2> 
<br>
</section>



<br>



<div class="row" style="margin-right: 10px; margin-left: 10px">
<div class="col">


<div class="card">

<div class="card-body" >

<table  class="table table-hover table-bordered" id="table_id1">   
<thead>        
<tr>            
<th width="20%">Sorteo</th>
<th width="20%">Fecha de Sorteo</th>
<th width="20%">Tipo Loteria</th>
<th width="20%">Cantidad</th>
<th align="center" width="10%">Accion</th>
</tr>   
</thead> 
<tbody>  

<?php

$result3 = mysqli_query($conn,"SELECT * FROM sorteos_menores WHERE control_calidad = 'SI' ORDER BY no_sorteo_men DESC ");

if ($result3 != null){

while ($row3 = mysqli_fetch_array($result3)) {

echo '   <tr>
<td>'.$row3['no_sorteo_men'].'</td>
<td>'.$row3['fecha_sorteo'].'</td>
<td>Menor</td>
<td>'.$row3['series'].' series</td>
<td align = "center" >
<button class="btn btn-primary"  name="procesar_menor" value="'.$row3['id'].'" type="submit">Procesar</button>
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