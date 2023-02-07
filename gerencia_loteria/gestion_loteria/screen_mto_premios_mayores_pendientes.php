<?php
require('../../template/header.php');

?>


<br>

<ul class="nav nav-tabs">
<li class="nav-item">
<a style="background-color:#ededed;" class="nav-link active">Lotería Mayor</a>
</li>
<li class="nav-item">
<a class="nav-link" href="mto_premios_menores_pendientes.php">Lotería Menor</a>
</li>
</ul>

<section style="background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  ><b>ASIGNACION DE PREMIOS LOTERIA MAYOR</b></h2> 
<br>
</section>



<div class="tab-content">

<br>

<div class="row">

<div class="col">


<div class="card" style="margin-left: 15px;">
<div class="card-header alert alert-info">
<h4 align="center">SORTEOS PENDIENTES DE ASIGNACION DE PREMIOS</h4>	
</div>
<div class="card-body">

<table class="table table-hover table-bordered" id="table_id1" >

<thead>        
<tr>            
<th width="18%">Sorteo</th>
<th width="18%">Fecha Sorteo</th>
<th width="18%">Fecha Vencimiento</th>
<th  width="10%">Accion</th>
</tr>   
</thead> 
<tbody>  
<?php
$result = mysqli_query($conn, " SELECT * FROM sorteos_mayores WHERE  premios_asignados IS NULL ORDER BY no_sorteo_may DESC ");

if ($result != null){
while ($row = mysqli_fetch_array($result)) {
$id = $row['id'];
echo "<tr> 
<td>".$row['no_sorteo_may']."</td>
<td>".$row['fecha_sorteo']."</td>
<td>".$row['fecha_vencimiento']."</td>
<td align='center' ><a class = 'btn btn-info' href = './mto_premios_mayores.php?sort=".$id."'>Asignar Premios</a></td>   
</tr>
";
}
}

?>
</tbody>
</table>

  
</div>
</div>
	
</div>	

<div class="col">


<div class="card" style=" margin-right: 15px">

<div class="card-header alert alert-success">
<h4 align="center">HISTORICO DE ASIGNACION DE PREMIOS</h4>	
</div>

<div class="card-body">

<table class="table table-hover table-bordered" id="table_id2" >

<thead>        
<tr>            
<th width="18%">Sorteo</th>
<th width="18%">Fecha Sorteo</th>
<th width="18%">Fecha Vencimiento</th>
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
<td align = 'center'><a class = 'btn btn-success' href = './mto_premios_mayores_detalle.php?sort=".$id."'> Ver</a></td>   
</tr>
";
}
}

?>
</tbody>
</table>

  
</div>
</div>

	
</div>

</div>


</div>