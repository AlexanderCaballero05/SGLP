<?php
require '../../template/header.php';

$sorteos = mysqli_query($conn, "SELECT * FROM sorteos_menores ORDER BY no_sorteo_men DESC");
$sorteos2 = mysqli_query($conn, "SELECT * FROM sorteos_menores ORDER BY no_sorteo_men DESC");

?>

<form method="POST">

<section style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
<br>
<h5  align="center" style="color:black; "  >PATRONATO NACIONAL DE LA INFANCIA <br>REPORTE DE BILLETES TRITURADOS EN REPOSICION POR SORTEO LOTERIA MENOR </h5>
<br>

<?php 

$fecha_actual = date("d-m-Y h:m:i a");
echo "Fecha de emisión: <u>" . $fecha_actual . "</u>";


?>

</section>

<br>

<div class="card" style="margin-left: 10px; margin-right: 10px">
<div class="card-header" align="center" id="non-printable">
<div class="input-group" style="margin:10px 0px 10px 0px; width: 50%" >
<div class="input-group-prepend"><span  class="input-group-text">Del Sorteo: </span></div>
 <select class="form-control" name="sorteo">
   <?php
while ($sorteo = mysqli_fetch_array($sorteos)) {
	echo "<option value = '" . $sorteo['id'] . "'>" . $sorteo['id'] . " -- Fecha " . $sorteo['fecha_sorteo'] . " -- " . "</option>";
}
?>
 </select>
 <div class="input-group-prepend"><span  class="input-group-text">Al Sorteo: </span></div>
 <select class="form-control" name="sorteo2">
   <?php
while ($sorteo2 = mysqli_fetch_array($sorteos2)) {
	echo "<option value = '" . $sorteo2['id'] . "'>" . $sorteo2['id'] . " -- Fecha " . $sorteo2['fecha_sorteo'] . " -- " . "</option>";
}
?>
 </select>

<input type="submit" name="seleccionar" class="btn btn-primary" value = "Seleccionar">
</div>
</div>

<div class="card-body">


</form>


<?php 


if (isset($_POST['seleccionar'])) {
    
    $sorteo1 = $_POST['sorteo'];
    $sorteo2 = $_POST['sorteo2'];

    $c_data_sorteos = mysqli_query($conn, "SELECT a.id, a.fecha_sorteo, count(b.id_sorteo) as cantidad FROM sorteos_menores as a INNER JOIN cc_revisores_sorteos_menores_control as b ON a.id = b.id_sorteo WHERE a.id BETWEEN '$sorteo1' AND '$sorteo2' AND b.numero_revision = 2 GROUP BY a.id ");

    ?>


<table class="table table-bordered">
    <thead>
        <tr>
            <th>Sorteo</th>
            <th>Fecha del Sorteo</th>
            <th>Cantidad</th>
        </tr>
    </thead>

    <?php

    $total = 0;

    while ($reg_data_sorteos = mysqli_fetch_array($c_data_sorteos)) {
        
        echo "<tr>";
        echo "<td>".$reg_data_sorteos['id']."</td>";
        echo "<td>".$reg_data_sorteos['fecha_sorteo']."</td>";
        echo "<td>".number_format($reg_data_sorteos['cantidad'])."</td>";
        echo "</tr>";

        $total += $reg_data_sorteos['cantidad'];

    }


    ?>



<tfoot>
    <tr>
        <th colspan="2">TOTAL</th>
        <th> <?php echo number_format($total)?></th>
    </tr>
</tfoot>

</table>

    <?php 



}

?>

</div>
</div>
