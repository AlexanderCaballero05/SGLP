<?php


$tipo = $_GET['tipo'];

if ($tipo == 'FAMILIA') {

    $fecha_familiar = $_GET['fecha_familiar'];
    $current_date = date("Y-m-d");
    $d1 = new DateTime($fecha_familiar);
    $d2 = new DateTime($current_date);

    $diff = $d2->diff($d1);
    $edad = $diff->y;

?>
    <script>
        document.getElementById('nuevo_edad_familiar').value = <?php echo $edad; ?>;
    </script>
<?php

} elseif ($tipo == 'BENEFICIARIO') {

    $fecha_familiar = $_GET['fecha_beneficiario'];
    $current_date = date("Y-m-d");
    $d1 = new DateTime($fecha_familiar);
    $d2 = new DateTime($current_date);

    $diff = $d2->diff($d1);
    $edad = $diff->y;

?>
    <script>
        document.getElementById('nuevo_edad_beneficiario').value = <?php echo $edad; ?>;
    </script>
<?php

} elseif ($tipo == 'NUEVO') {

    $fecha_familiar = $_GET['fecha_nuevo'];
    $current_date = date("Y-m-d");
    $d1 = new DateTime($fecha_familiar);
    $d2 = new DateTime($current_date);

    $diff = $d2->diff($d1);
    $edad = $diff->y;

?>
    <script>
        document.getElementById('nuevo_edad').value = <?php echo $edad; ?>;
    </script>
<?php

} elseif ($tipo == 'EDICION') {

    $fecha_familiar = $_GET['fecha_nuevo'];
    $current_date = date("Y-m-d");
    $d1 = new DateTime($fecha_familiar);
    $d2 = new DateTime($current_date);

    $diff = $d2->diff($d1);
    $edad = $diff->y;

?>
    <script>
        document.getElementById('edicion_edad').value = <?php echo $edad; ?>;
    </script>
<?php

}

