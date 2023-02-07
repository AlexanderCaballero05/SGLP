<?php
require('../../template/header.php');

$identidad = $_GET['id'];

$datos_empleado = mysqli_query($conn, "SELECT a.cod_empleado, a.nombre_completo FROM rr_hh_empleados as a WHERE a.identidad = '$identidad'  ");


$ob_empleado = mysqli_fetch_object($datos_empleado);
$nombre = $ob_empleado->nombre_completo;
$cod_empleado = $ob_empleado->cod_empleado;

$datos_empleado_cc = mysqli_query($conn, "SELECT a.id, a.centro, a.descripcion FROM pre_centros_costos as a LEFT JOIN rr_hh_empleados_centro_costos as b ON a.id = b.centro_costo_id WHERE b.usuarioid = '$identidad' AND b.estado = 'A'  ");

$ob_empleado_cc = mysqli_fetch_object($datos_empleado_cc);
$descripcion_cc = $ob_empleado_cc->descripcion;
$cc = $ob_empleado_cc->centro;
$id_cc = $ob_empleado_cc->id;
$datos_empleado_forma_pago = mysqli_query($conn, "SELECT * FROM rr_hh_empleados_forma_pago WHERE usuarioid = '$identidad' AND estado = 'A'  ");

$ob_empleado_forma_pago = mysqli_fetch_object($datos_empleado_forma_pago);
$forma_pago = $ob_empleado_forma_pago->forma_pago;
$banco = $ob_empleado_forma_pago->banco;
$tipo_cuenta = $ob_empleado_forma_pago->tipo_cuenta;




if (isset($_POST['guardar_cambios'])) {

    $id_empleado = $_POST['identidad'];

    $id_cc_actual = $_POST['id_cc_o'];
    $id_banco_actual = $_POST['id_banco_o'];
    $id_forma_pago_actual = $_POST['id_forma_pago_o'];
    $id_tipo_cuenta_actual = $_POST['id_tipo_cuenta_o'];

    $id_cc_nuevo = $_POST['select_centro_costo'];
    $id_banco_nuevo = $_POST['select_banco'];
    $id_forma_pago_nuevo = $_POST['select_forma_pago'];
    $id_tipo_cuenta_nuevo = $_POST['select_tipo_cuenta'];


    if ($id_cc_actual != $id_cc_nuevo) {

        mysqli_query($conn, "UPDATE rr_hh_empleados_centro_costos SET estado = 'I' WHERE usuarioid = '$id_empleado' ");

        if (mysqli_query($conn, " INSERT INTO rr_hh_empleados_centro_costos (usuarioid, centro_costo_id) VALUES ('$id_empleado', '$id_cc_nuevo') ") == true) {

?>
            <script type="text/javascript">
                swal({
                        title: "",
                        text: "Cambios realizados exitosamente.",
                        type: "success"
                    })
                    .then(function(result) {
                        window.location.href = window.location.href;
                    });
            </script>
        <?php

        } else {
            echo mysqli_error($conn);
            echo "<div class = 'alert alert-danger'>Error inesperado, por favor intente nuevamente.</div>";
        }
    }


    if ($id_banco_actual != $id_banco_nuevo or $id_forma_pago_actual != $id_forma_pago_nuevo or $id_tipo_cuenta_actual != $id_tipo_cuenta_nuevo) {

        mysqli_query($conn, "UPDATE rr_hh_empleados_forma_pago SET estado = 'I' WHERE usuarioid = '$id_empleado' ");

        if (mysqli_query($conn, " INSERT INTO rr_hh_empleados_forma_pago (usuarioid, forma_pago, banco, tipo_cuenta) VALUES ('$id_empleado', '$id_forma_pago_nuevo', '$id_banco_nuevo', '$id_tipo_cuenta_nuevo' ) ") == true) {

        ?>
            <script type="text/javascript">
                swal({
                        title: "",
                        text: "Cambios realizados exitosamente.",
                        type: "success"
                    })
                    .then(function(result) {
                        window.location.href = window.location.href;
                    });
            </script>
<?php

        } else {
            echo "<div class = 'alert alert-danger'>Error inesperado, por favor intente nuevamente.</div>";
        }
    }
}

?>

<script>
    function validar_cambios() {
        cc_actual = document.getElementById("id_cc_o").value;
        banco_actual = document.getElementById("id_banco_o").value;
        forma_pago_actual = document.getElementById("id_forma_pago_o").value;
        tipo_cuenta_actual = document.getElementById("id_tipo_cuenta_o").value;

        cc_select = document.getElementById("select_centro_costo").value;
        banco_select = document.getElementById("select_banco").value;
        forma_pago_select = document.getElementById("select_forma_pago").value;
        tipo_cuenta_select = document.getElementById("select_tipo_cuenta").value;


        guardar_cambios = 0;

        if (cc_actual != cc_select) {
            guardar_cambios = 1;
        }

        if (banco_actual != banco_select) {
            guardar_cambios = 1;
        }

        if (forma_pago_actual != forma_pago_select) {
            guardar_cambios = 1;
        }

        if (tipo_cuenta_actual != tipo_cuenta_select) {
            guardar_cambios = 1;
        }


        if (guardar_cambios == 1) {
            document.getElementById("guardar_cambios").disabled = false;
        } else {
            document.getElementById("guardar_cambios").disabled = true;
        }


    }
</script>


<br>

<form method="POST">

    <input type="hidden" name='id_cc_o' id='id_cc_o' value="<?php echo $id_cc ?>">
    <input type="hidden" name='id_forma_pago_o' id='id_forma_pago_o' value="<?php echo $forma_pago ?>">
    <input type="hidden" name='id_banco_o' id='id_banco_o' value="<?php echo $banco ?>">
    <input type="hidden" name='id_tipo_cuenta_o' id='id_tipo_cuenta_o' value="<?php echo $tipo_cuenta ?>">

    <div class="row">
        <div class="col">

            <div class="card" style='margin-left: 5px; margin-right: 5px'>
                <div class="card-header bg-success text-white">
                    <h4 align="center">GESTION DE OTROS DATOS DE EMPLEADO</h4>
                </div>


                <div class="card-body">

                    <div class="row">
                        <div class="col">
                            <input type="hidden" id="id_registro" name="id_registro" class="form-control" value='<?php echo $id_registro ?>'>

                            <div class="input-group">
                                <div class="input-group-prepend ">
                                    <span style="width: 300px;" class="input-group-text" id="basic-addon2">COD. EMPLEADO</span>
                                </div>
                                <input type="text" name="cod_empleado" class="form-control" value='<?php echo $cod_empleado ?>' readonly>
                            </div>

                        </div>
                        <div class="col">

                            <div class="input-group" style="margin-top: 5px;">
                                <div class="input-group-prepend ">
                                    <span style="width: 300px;" class="input-group-text" id="basic-addon2">IDENTIDAD</span>
                                </div>
                                <input type="text" id="id_empleado_seleccionado" name="identidad" class="form-control" value='<?php echo $identidad ?>' readonly>
                            </div>

                        </div>
                        <div class="col col-md-6">

                            <div class="input-group" style="margin-top: 5px;">
                                <div class="input-group-prepend">
                                    <span style="width: 300px;" class="input-group-text" id="basic-addon2">NOMBRE COMPLETO</span>
                                </div>
                                <input type="text" class="form-control" value='<?php echo $nombre ?>' readonly>
                            </div>

                        </div>
                    </div>



                    <div class="input-group" style="margin-top: 5px;">
                        <div class="input-group-prepend">
                            <span style="width: 300px;" class="input-group-text" id="basic-addon2">CENTRO DE COSTO</span>
                        </div>

                        <select name="select_centro_costo" onchange="validar_cambios()" id="select_centro_costo" class="form-control">
                            <?php

                            $c_centros_costos = mysqli_query($conn, "SELECT * FROM pre_centros_costos ");


                            while ($reg_centros_costos = mysqli_fetch_array($c_centros_costos)) {

                                if ($reg_centros_costos['id'] == $id_cc) {
                                    echo "<option value = '" . $reg_centros_costos['id'] . "'  selected>" . $reg_centros_costos['centro'] . " | " . $reg_centros_costos['descripcion'] . "</option>";
                                } else {
                                    echo "<option value = '" . $reg_centros_costos['id'] . "' >" . $reg_centros_costos['centro'] . " | " . $reg_centros_costos['descripcion'] . "</option>";
                                }
                            }

                            ?>
                        </select>
                    </div>

                    <div class="input-group" style="margin-top: 5px;">
                        <div class="input-group-prepend">
                            <span style="width: 300px;" class="input-group-text" id="basic-addon2">FORMA DE PAGO</span>
                        </div>

                        <select name="select_forma_pago" onchange="validar_cambios()" id="select_forma_pago" class="form-control">
                            <?php

                            $c_formas_pago = mysqli_query($conn, "SELECT * FROM organizacional_formas_pago_salario ");

                            while ($reg_formas_pago = mysqli_fetch_array($c_formas_pago)) {

                                if ($reg_formas_pago['id'] == $forma_pago) {
                                    echo "<option value = '" . $reg_formas_pago['id'] . "'  selected>" . $reg_formas_pago['descripcion'] . "</option>";
                                } else {
                                    echo "<option value = '" . $reg_formas_pago['id'] . "' >" . $reg_formas_pago['descripcion'] . "</option>";
                                }
                            }

                            ?>
                        </select>

                    </div>

                    <div class="input-group" style="margin-top: 5px;">
                        <div class="input-group-prepend">
                            <span style="width: 300px;" class="input-group-text" id="basic-addon2">BANCO</span>
                        </div>

                        <select name="select_banco" onchange="validar_cambios()" id="select_banco" class="form-control">
                            <?php

                            $c_bancos = mysqli_query($conn, "SELECT * FROM organizacional_bancos_salarios ");

                            while ($reg_bancos = mysqli_fetch_array($c_bancos)) {

                                if ($reg_bancos['id'] == $banco) {
                                    echo "<option value = '" . $reg_bancos['id'] . "'  selected>" . $reg_bancos['descripcion'] . "</option>";
                                } else {
                                    echo "<option value = '" . $reg_bancos['id'] . "' >" . $reg_bancos['descripcion'] . "</option>";
                                }
                            }

                            ?>
                        </select>


                    </div>

                    <div class="input-group" style="margin-top: 5px;">
                        <div class="input-group-prepend">
                            <span style="width: 300px;" class="input-group-text" id="basic-addon2">TIPO DE CUENTA</span>
                        </div>

                        <select name="select_tipo_cuenta" onchange="validar_cambios()" id="select_tipo_cuenta" class="form-control">
                            <?php

                            $c_tipo_cuentas = mysqli_query($conn, "SELECT * FROM organizacional_tipos_cuentas_salario ");

                            while ($reg_tipo_cuentas = mysqli_fetch_array($c_tipo_cuentas)) {

                                if ($reg_tipo_cuentas['id'] == $tipo_cuenta) {
                                    echo "<option value = '" . $reg_tipo_cuentas['id'] . "'  selected>" . $reg_tipo_cuentas['descripcion'] . "</option>";
                                } else {
                                    echo "<option value = '" . $reg_tipo_cuentas['id'] . "' >" . $reg_tipo_cuentas['descripcion'] . "</option>";
                                }
                            }

                            ?>
                        </select>

                    </div>

                    <?php

                    ?>

                </div>

                <div class="card-footer" style="text-align:center">
                    <button type="submit" id="guardar_cambios" name="guardar_cambios" class="btn btn-success" disabled>GUARDAR CAMBIOS</button>
                </div>
            </div>


        </div>


    </div>

    <br>

    <div class="row">


        <div class="col">
            <div class="card " style='margin-left: 5px; margin-right: 5px'>
                <div class="card-header bg-success text-white">
                    <h4 style="text-align: center;">HISTORICO DE CAMBIOS </h4>
                </div>
                <div class="card-body">

                    <?php
                    $cambios_historico = mysqli_query($conn, "SELECT * FROM rr_hh_tipo_contrato_salarios WHERE identidad = '$identidad' AND status = 'C' ");
                    ?>

                    <table class="table table-bordered" id="table_id1">
                        <thead>
                            <tr>
                                <th>INICIO</th>
                                <th>FIN</th>
                                <th>SALARIO</th>
                                <th>TIPO DE CONTRATO</th>
                                <th>ESTADO</th>
                            </tr>

                            <?php
                            while ($reg_cambios_historico = mysqli_fetch_array($cambios_historico)) {
                                echo "<tr>";
                                echo "<td>" . $reg_cambios_historico['fecha_inicio'] . "</td>";
                                echo "<td>" . $reg_cambios_historico['fecha_fin'] . "</td>";
                                echo "<td>" . $reg_cambios_historico['salario_base'] . "</td>";
                                echo "<td>" . $reg_cambios_historico['tipo_contratacion'] . "</td>";
                                echo "<td>" . $reg_cambios_historico['status'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </thead>
                    </table>

                </div>
                <div class="card-footer">
                    <b>LEYENDA </b><br>
                    <?php
                    $i = 0;
                    while (isset($v_contratos[$i])) {

                        echo  $v_contratos[$i]["id"] . " = " . $v_contratos[$i]["descripcion"] . " <br> ";

                        $i++;
                    }
                    ?>
                </div>
            </div>

        </div>


    </div>


</form>