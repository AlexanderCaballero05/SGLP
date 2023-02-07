<?php
require('../../template/header.php');


if (isset($_POST['historico_mayor'])) {
$_SESSION['produccion_mayor'] = $_POST['historico_mayor'];
?>
<script type="text/javascript">
window.location = "./produccion_mayor.php";
</script>
<?php
}

if (isset($_POST['historico_mayor_reporte'])) {
$_SESSION['historico_mayor'] = $_POST['historico_mayor_reporte'];
?>
<script type="text/javascript">
window.location = "./produccion_historico_mayor_reporte.php";
</script>
<?php
}


?>


<form method="POST">

<br>

<ul class="nav nav-tabs" style="margin-left: 10px; margin-right: 10px">
  <li class="nav-item">
    <a class="nav-link" href="./screen_produccion_sorteos.php" >Sorteos Pendientes de Produccion</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active"  >Historico de Produccion</a>
  </li>
</ul>


<br>


<div class="card card-primary" style=" margin-left: 10px; margin-right: 10px" >
<div class="card-header">
<h2 align="center"> Historico de Produccion de Loteria Mayor</h2>
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

$result1 = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE estado_sorteo != 'PENDIENTE PRODUCCION' ORDER BY id DESC ");

if ($result1 != null){

while ($row1 = mysqli_fetch_array($result1)) {

echo '   <tr>
   <td>'.$row1['no_sorteo_may'].'</td>
   <td>'.$row1['fecha_sorteo'].'</td>
   <td>'.$row1['cantidad_numeros'].' billetes</td>
   <td align ="center"><!-- Single button -->
<button class="btn btn-primary fa fa-eye"  name="historico_mayor" value="'.$row1['id'].'" type="submit"></button>
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