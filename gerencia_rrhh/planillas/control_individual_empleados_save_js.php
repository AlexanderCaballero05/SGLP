<?php

ob_start();
session_start();

$accion = $_GET['accion'];

if ($accion == "GUARDAR") {

    $fecha = $_GET['fecha'];
    $devengado = $_GET['devengado'];
    $seguro = $_GET['seguro'];
    $impuesto = $_GET['impuesto'];
    $observaciones = $_GET['observaciones'];
    $observaciones = str_replace('!',' ',$observaciones);
    $identidad = $_GET['identidad'];
    $cod_empleado = $_GET['cod_empleado'];
    $usuario = $_SESSION['usuario'];

    $identidad = str_replace('-', '', $identidad);
    $fecha = date("Y-m-d", strtotime($fecha));

    require('../../conexion.php');

    $registro =  mysqli_query($conn, "INSERT INTO rr_hh_planilla_otros_ingresos (identidad, cod_empleado ,fecha ,devengado ,seg_social ,impuesto ,observaciones , usuario) VALUES ('$identidad', '$cod_empleado','$fecha','$devengado','$seguro','$impuesto','$observaciones', '$usuario') ");

    if ($registro === TRUE) {
 
    ?>

        <script text="javascript">
            swal({
            title: "",
            text: "Registro guardado correctamente.",
                type: "success"
            });

        </script>
    
    <?php

} else {
        echo mysqli_error($conn);

        ?>

        <script text="javascript">
            swal({
            title: "",
            text: "Error inesperado, por favor vuelva a intentarlo.",
                type: "error"
            });

        </script>
    
    <?php


    }


}else if($accion == "ELIMINAR"){

    $id = $_GET['id'];
    $usuario = $_SESSION['usuario'];

    require('../../conexion.php');

    $registro =  mysqli_query($conn, " UPDATE rr_hh_planilla_otros_ingresos SET estado = 'I', usuario_edit = '$usuario' WHERE id = '$id' ");

    if ($registro === TRUE) {
        echo "<div class = 'alert alert-success'>Registro inactivado correctamente.</div>";
    } else {
        echo mysqli_error($conn);
        echo "<div class = 'alert alert-danger'>Error, por favor vuelva a intentarlo.</div>";
    
    }

}


?>

<script type="text/javascript">
    consultar_empleado();
</script>