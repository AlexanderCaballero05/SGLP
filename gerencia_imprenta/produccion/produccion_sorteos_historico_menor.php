<?php
require('../../template/header.php');


if (isset($_POST['historico_menor'])) {
$_SESSION['produccion_menor'] = $_POST['historico_menor'];
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
    <a class="nav-link" href="./screen_produccion_sorteos_menor.php" >Sorteos Pendientes de Produccion</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active"  >Historico de Produccion Menor</a>
  </li>
</ul>


<br>


<div class="card card-primary" style=" margin-left: 10px; margin-right: 10px" >
<div class="card-header">
<h2 align="center"> Historico de Produccion  Loteria Mayor</h2>
</div>

<div class="card-body">
 <table id="table_id1" class="table table-hover table-bordered">   
        <thead>        
            <tr>            
                <th width="25%">Sorteo</th>
                <th width="30%">Fecha de Sorteo</th>
                <th width="22%">Cantidad</th>
                <th align="center" width="23%">Accion</th>
                </tr>   
        </thead> 
        <tbody>  

<?php


$result2 = mysqli_query($conn,"SELECT * FROM sorteos_menores WHERE estado_sorteo != 'PENDIENTE PRODUCCION' ORDER BY no_sorteo_men DESC ");

if ($result2 != null){

while ($row2 = mysqli_fetch_array($result2)) {

echo '   <tr>
   <td>'.$row2['no_sorteo_men'].'</td>
   <td>'.$row2['fecha_sorteo'].'</td>
   <td>'.$row2['series'].' Series</td>
   <td align = "center"><!-- Single button -->
   <button class="btn btn-info fa fa-eye"  name="historico_menor" value="'.$row2['id'].'" type="submit"></button>
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