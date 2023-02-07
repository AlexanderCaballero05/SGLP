<?php

require('../../template/header.php');
require('./fvp_distribucion_pedidos_mayor_detalle_db.php');

?>

<form method="POST">


<section style="background-color:#ededed;">
<br>
<h3 align="center">
	SORTEO: <?php echo $id_sorteo;?>
</h3>
<h4 align="center">
	EMPRESA: <?php echo $nombre_empresa;?>
</h4>
<br>
</section>



<div class="alert alert-danger" id="non-printable">
Unicamente podra realizar eliminacion de asignacion de loteria si aun no se han emitido actas de entrega de loteria que contengan dicha distribucion, en caso contrario debera eliminar en primera instancia el acta correspondiente a la loteria que desea reasignar. 	
</div>

<div class="card"  style="margin-left: 10px; margin-right: 10px">
<div class="card-header">
<h4 align="center">Detalle de Asignacion</h4>	
</div>
<div class="card-body">	
<table class="table table-bordered">
<tr>
<?php 

$m = 0;
$c = 0;
while ($mezclas = mysqli_fetch_array($inventario)) {

$num_mezcla  = $mezclas['num_mezcla'];
$cod_factura = $mezclas['cod_factura'];

$detalle_mezcla = mysqli_query($conn,"SELECT * FROM sorteos_mezclas_rangos WHERE id_sorteo = $id_sorteo AND num_mezcla = $num_mezcla ");

if ($c < 5) {
echo "<td>";

echo "<table class = 'table table-bordered'>";
echo "<tr>";
echo "<th> Mezcla No. ".$num_mezcla."</th>";
echo "</tr>";
while ($detalle = mysqli_fetch_array($detalle_mezcla)) {
$rango_final_mezcla = $detalle['rango'] + $mezcla - 1;
echo "<tr>";
echo "<td>".$detalle['rango']." - ".$rango_final_mezcla."</td>";
echo "</tr>";
}

if ($cod_factura == null) {
echo "<tr><td><button name = 'formatear_asignacion' type = 'submit' value = '".$mezclas['id']."' style = 'width:100%' class = 'btn btn-danger'>X</button></td></tr>";
}

echo "</table>";

echo "</td>";
}else{

echo "</tr>";		
echo "<tr>";	

echo "<td>";

echo "<table class = 'table table-bordered'>";
echo "<tr>";
echo "<th> Mezcla No. ".$num_mezcla."</th>";
echo "</tr>";

while ($detalle = mysqli_fetch_array($detalle_mezcla)) {

$rango_final_mezcla = $detalle['rango'] + $mezcla - 1;
echo "<tr>";
echo "<td>".$detalle['rango']." - ".$rango_final_mezcla."</td>";
echo "</tr>";
}


if ($cod_factura == null) {
echo "<tr><td><button name = 'formatear_asignacion' type = 'submit' value = '".$mezclas['id']."' style = 'width:100%' class = 'btn btn-danger'>X</button></td></tr>";
}

echo "</table>";

echo "</td>";

$c = 0;	
}

$c++;
$m++;
}

$b = $m * 100;
?>
</tr>	
</table>
</div>
<div class="card-footer">
Total Paquetes: <?php echo $m;?>
<br>
Total Billetes: <?php echo $b;?>	
</div>
</div>

<br><br><br>

</form>