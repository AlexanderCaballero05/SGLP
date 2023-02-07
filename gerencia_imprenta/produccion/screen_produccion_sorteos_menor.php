<?php
require('../../template/header.php');


if (isset($_POST['procesar_menor'])) {
$_SESSION['produccion_menor'] = $_POST['procesar_menor'];
?>
<script type="text/javascript">
window.location = "./produccion_menor.php";
</script>
<?php
}


?>


<form method="POST">

<br>

<ul class="nav nav-tabs" style="margin-left: 10px; margin-right: 10px">
  <li class="nav-item">
    <a class="nav-link active" >Sorteos Pendientes de Produccion</a>
  </li>
  <li class="nav-item">
    <a class="nav-link"  href="./produccion_sorteos_historico_menor.php" >Historico de Produccion</a>
  </li>
</ul>


<br>


<div class="card card-primary" style=" margin-left: 10px; margin-right: 10px" >
<div class="card-header">
<h2 align="center">Sorteos Pendientes de Produccion de Loteria Menor</h2>
</div>

<div class="card-body">
 <table id="table_id1" class="table table-hover table-bordered">   
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

$result2 = mysqli_query($conn,"SELECT * FROM sorteos_menores WHERE estado_sorteo = 'PENDIENTE PRODUCCION' ORDER BY no_sorteo_men DESC ");

if ($result2 != null){

while ($row2 = mysqli_fetch_array($result2)) {

echo '   <tr>
   <td>'.$row2['no_sorteo_men'].'</td>
   <td>'.$row2['fecha_sorteo'].'</td>
   <td>'.$row2['series'].' Series</td>
   <td align = "center">
<button class="btn btn-primary"  name="procesar_menor" value="'.$row2['id'].'" type="submit">Procesar</button>
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