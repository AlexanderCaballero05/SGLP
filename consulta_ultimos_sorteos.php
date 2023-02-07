<?php 

require("conexion.php");

$current_date = date("Y-m-d");

//////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////// MENOR ////////////////////////////////////////////////////////

$c_ultimo_sorteo_menor = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE fecha_sorteo <= '$current_date' AND estado_sorteo = 'CAPTURADO' ORDER BY fecha_sorteo DESC LIMIT 1 ");

$ob_sorteo_menor = mysqli_fetch_object( $c_ultimo_sorteo_menor);

$sorteo_menor = $ob_sorteo_menor->id;
$fecha_sorteo_menor = $ob_sorteo_menor->fecha_sorteo;
$fecha_vencimiento_menor = $ob_sorteo_menor->vencimiento_sorteo;


$c_premios_menor = mysqli_query($conn, "SELECT * FROM sorteos_menores_premios WHERE sorteos_menores_id = '$sorteo_menor'  ");

while ($r_premios_menor = mysqli_fetch_array($c_premios_menor)) {

if ($r_premios_menor['premios_menores_id'] == 1) {
$derecho = "Numero ganador: <span style = 'color:red;'> ".$r_premios_menor['numero_premiado_menor'].' </span> <span style = "color:rgb(0,143,5); "> L '.number_format($r_premios_menor['monto'], 2).' </span>';
}elseif ($r_premios_menor['premios_menores_id'] == 3) {
$reves =  "Numero de revés: <span style = 'color:red;'> ".$r_premios_menor['numero_premiado_menor'].' </span> <span style = "color:rgb(0,143,5); "> L '.number_format($r_premios_menor['monto'], 2).' </span>';
}elseif ($r_premios_menor['premios_menores_id'] == 2) {
$primera_serie = "Serie  derecho: <span style = 'color:red;'> ".$r_premios_menor['numero_premiado_menor'].' </span> <span style = "color:rgb(0,143,5); "> L '.number_format($r_premios_menor['monto'], 2).'  </span>';
}elseif ($r_premios_menor['premios_menores_id'] == 4) {
$primera_serie_reves = "Serie de revés: <span style = 'color:red;'> ".$r_premios_menor['numero_premiado_menor'].' </span> <span style = "color:rgb(0,143,5); "> L '.number_format($r_premios_menor['monto'], 2).' </span>';
}elseif ($r_premios_menor['premios_menores_id'] == 5) {
$segunda_serie_reves = "Serie de revés: <span style = 'color:red;'> ".$r_premios_menor['numero_premiado_menor'].' </span> <span style = "color:rgb(0,143,5); "> L '.number_format($r_premios_menor['monto'], 2).' </span>';
}elseif ($r_premios_menor['premios_menores_id'] == 6) {
$tercera_serie_reves = "Serie de revés: <span style = 'color:red;'> ".$r_premios_menor['numero_premiado_menor'].' </span> <span style = "color:rgb(0,143,5); "> L '.number_format($r_premios_menor['monto'], 2).'  </span>';
}


}

/////////////////////////////////////////// MENOR ////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////




//////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////// MAYOR ////////////////////////////////////////////////////////

$c_ultimo_sorteo_mayor = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE fecha_sorteo <= '$current_date' AND estado_sorteo = 'CAPTURADO' ORDER BY fecha_sorteo DESC LIMIT 1 ");

$ob_sorteo_mayor = mysqli_fetch_object($c_ultimo_sorteo_mayor);

$sorteo = $ob_sorteo_mayor->id;
$fecha_sorteo = $ob_sorteo_mayor->fecha_sorteo;
$fecha_vencimiento_mayor = $ob_sorteo_mayor->fecha_vencimiento;


$c_premios_mayor = mysqli_query($conn, "SELECT * FROM sorteos_mayores_premios WHERE sorteos_mayores_id = '$sorteo' AND numero_premiado_mayor IS NOT NULL AND premios_mayores_id NOT IN (9,10,11,12)  ORDER BY monto DESC ");


/////////////////////////////////////////// MAYOR ////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////






?>

<div class="card-group">
    <div class="card">
        <div class="card-body" style="background-color:#d6e4dc;">
            <h4 class="text-center card-title">MENOR</h4>
            <ul class="list-group">
                <li class="list-group-item" style="background-color:rgb(254,254,254);"><span><strong>Sorteo: <?php echo $sorteo_menor; ?></strong></span></li>
                <li class="list-group-item" style="background-color:rgb(254,254,254);"><span><strong>Fecha del sorteo: <?php echo $fecha_sorteo_menor; ?></strong></span></li>
                <li class="list-group-item" style="background-color:rgb(254,254,254);"><span><strong>Fecha vencimiento: <?php echo $fecha_vencimiento_menor; ?></strong></span></li>
                <li class="list-group-item"><span><strong><?php echo $derecho; ?></strong></span></li>
                <li class="list-group-item"><span><strong><?php echo $reves; ?></strong></span></li>
                <li class="list-group-item"><span><strong><?php echo $primera_serie; ?></strong></span></li>
                <li class="list-group-item"><span><strong><?php echo $primera_serie_reves; ?></strong></span></li>
                <li class="list-group-item"><span><strong><?php echo $segunda_serie_reves; ?></strong></span></li>
                <li class="list-group-item"><span><strong><?php echo $tercera_serie_reves; ?></strong></span></li>
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body" style="background-color:#fff7e2;">
            <h4 class="text-center card-title" style="margin:0px 0px 12px;">MAYOR</h4>
            <div style="height: 400px; overflow-y: scroll;">
            <ul class="list-group">
                <li class="list-group-item"><span><strong>Sorteo: <?php echo $sorteo; ?></strong><br></span></li>
                <li class="list-group-item"><span><strong>Fecha del sorteo: <?php echo $fecha_sorteo; ?></strong><br></span></li>
                <li class="list-group-item" style="background-color:rgb(254,254,254);"><span><strong>Fecha vencimiento: <?php echo $fecha_vencimiento_mayor; ?></strong></span></li>

<?php 

$i = 1;
while ($r_premios_mayor = mysqli_fetch_array($c_premios_mayor)) {

echo '<li class="list-group-item"><span><strong> #'.$i.' Premio: <span style = "color:red"> '.$r_premios_mayor['numero_premiado_mayor'].' </span> </strong></span>  <span style = "color:rgb(0,143,5); "> L '.number_format($r_premios_mayor['monto'], 2).'  </span></li>';

$i++;
}

?>
                
            </ul>
            </div>
        </div>
    </div>
</div>
