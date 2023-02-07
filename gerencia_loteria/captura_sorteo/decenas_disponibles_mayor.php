<?php 

require '../../conexion.php';

$sorteo = $_GET["sorteo"];

$c_decenas = mysqli_query($conn, " SELECT * FROM (SELECT COUNT(monto) as conteo_monto, monto FROM sorteos_mayores_premios WHERE sorteos_mayores_id = '$sorteo' AND numero_premiado_mayor IS NULL GROUP BY (monto) HAVING COUNT(monto) >=10 ) as t ");



?>



<?php 
if (mysqli_num_rows($c_decenas) > 0) {
    
?>

<select class="form-control" id = 'select_decena' style="width: 290px;" >

<?php 

    while ($reg_decenas = mysqli_fetch_array($c_decenas)) {
        echo "<option value = '".$reg_decenas['monto']."'>".$reg_decenas['monto']."</option>";
    }

?>

</select>

<?php
    
}else{

    echo "<div class = 'alert alert-info'>No existen premios aplicables a premiarse en decena</div>";
    
}
?>



<?php


?>