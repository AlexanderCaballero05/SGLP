<?php
require('../../template/header.php');

if (isset($_POST['procesar_mayor'])) {
$_SESSION['cc_mayor'] = $_POST['procesar_mayor'];
?>
<script type="text/javascript">
window.location = "./cc_asignacion_revisores_mayor.php";
</script>
<?php
}


?>


<br>


<form method="POST">



<ul class="nav nav-tabs">
 <li class="nav-item">
    <a style=" background-color:#ededed;" class="nav-link active" href="#">Sorteos Pendientes</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="cc_sorteos_historico_mayor.php">Historico de sorteos</a>
  </li>
</ul>

<section style=" background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >SORTEOS PENDIENTES DE ASIGNACION A REVISORES LOTERIA MAYOR</h2> 
<br>
</section>



<br>


<div class="row">
  <div class="col">

<div class="card" style="margin-left: 10px; margin-right: 10px">

<br>

<div class="">
 <table  class="table table-hover table-bordered" id="table_id1">   
        <thead>        
            <tr>            
                <th width="20%">Sorteo</th>
                <th width="20%">Fecha de Sorteo</th>
                <th width="20%">Cantidad Billetes</th>
                <th align="center" width="10%">Accion</th>
                </tr>   
        </thead> 
        <tbody>  

<?php

$result2 = mysqli_query($conn,"SELECT * FROM sorteos_mayores WHERE control_calidad = 'NO' ORDER BY no_sorteo_may DESC ");

if ($result2 != null){

while ($row2 = mysqli_fetch_array($result2)) {

echo '   <tr>
   <td>'.$row2['no_sorteo_may'].'</td>
   <td>'.$row2['fecha_sorteo'].'</td>
   <td>'.number_format($row2['cantidad_numeros']).'</td>
   <td align = "center">
<button class="btn btn-info"  name="procesar_mayor" value="'.$row2['id'].'" type="submit">Procesar</button>
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

 

<br>




