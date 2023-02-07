<?php
require('../../template/header.php');

?>

<section style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >HISTORICO DE PREMIOS LOTERIA MAYOR</h2> 
<br>
</section>

<br>

<ul class="nav nav-tabs">
 <li class="nav-item">
    <a class="nav-link" href="screen_mto_premios_mayores_pendientes.php">Sorteos Pendientes</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" href="#">Historico de Premios</a>
  </li>
</ul>




<div class="tab-content">

<br>

<div class="card" style="margin-left: 15px; margin-right: 15px">
<div class="card-body">

<table class="table table-hover table-bordered" id="table_id1" >

<thead>        
<tr>            
<th width="18%">Sorteo</th>
<th width="18%">Fecha Sorteo</th>
<th width="18%">Fecha Vencimiento</th>
<th width="18%">Cantidad</th>
<th width="18%">Descripcion</th>
<th  width="10%">Accion</th>
</tr>   
</thead> 
<tbody>  

<?php

$result = mysqli_query($conn, " SELECT * FROM sorteos_mayores WHERE  premios_asignados = 'SI' ORDER BY no_sorteo_may DESC ");

if ($result != null){
while ($row = mysqli_fetch_array($result)) {
$id = $row['id'];
echo "<tr> 
<td>".$row['no_sorteo_may']."</td>
<td>".$row['fecha_sorteo']."</td>
<td>".$row['fecha_vencimiento']."</td>
<td>".$row['cantidad_numeros']."</td>
<td>".$row['descripcion_sorteo_may']."</td>
<td align = 'center'><a class = 'btn btn-primary' href = './mto_premios_mayores_detalle.php?sort=".$id."'><i class = 'fa fa-edit'></i> Ver</a></td>   
</tr>
";
}
}

?>
</tbody>
</table>

  
</div>
</div>

<br>

</div>