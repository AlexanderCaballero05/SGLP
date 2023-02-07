<?php
require('../../template/header.php');

$identidad = $_GET['id'];

$datos_empleado = mysqli_query($conn, "SELECT a.nombre_completo, b.tipo_contratacion, b.salario_base , b.cod_empleado , b.fecha_inicio, b.id FROM rr_hh_empleados as a LEFT JOIN rr_hh_tipo_contrato_salarios as b ON a.identidad = b.identidad  WHERE a.identidad = '$identidad' AND b.status = 'A' ");
echo mysqli_error($conn);

if (mysqli_num_rows($datos_empleado) > 0) {


    $ob_empleado = mysqli_fetch_object($datos_empleado);


    $nombre = $ob_empleado->nombre_completo;
    $tipo_contratacion = $ob_empleado->tipo_contratacion;
    $salario_base_actual = $ob_empleado->salario_base;
    $cod_empleado = $ob_empleado->cod_empleado;
    $fecha_inicio_actual = $ob_empleado->fecha_inicio;
    $fecha_inicio_actual      = date('d/m/Y', strtotime($fecha_inicio_actual));

    $id_registro = $ob_empleado->id;

    $c_desc_contratacion = mysqli_query($conn, "SELECT * FROM rr_hh_mto_contrataciones WHERE id = '$tipo_contratacion' ");
    $ob_desc_contratacion = mysqli_fetch_object($c_desc_contratacion);
    $desc_contratacion_actual = $ob_desc_contratacion->descripcion;
} else {

    $datos_empleados = mysqli_query($conn, "SELECT * FROM rr_hh_empleados WHERE identidad = '$identidad' ");
    $ob_empleado = mysqli_fetch_object($datos_empleados);

    $nombre = $ob_empleado->nombre_completo;
    $cod_empleado = $ob_empleado->cod_empleado;
    $tipo_contratacion = "";
    $salario_base_actual = "";
    $fecha_inicio_actual = "";
    $desc_contratacion_actual = "";
}




$c_tipos_contrataciones = mysqli_query($conn, "SELECT * FROM rr_hh_mto_contrataciones WHERE estado = 'A' ");





if (isset($_POST['guardar_cambios'])) {

    $id_empleado = $_POST['identidad'];
    $id_registro = $_POST['id_registro'];
    $cod_empleado = $_POST['cod_empleado'];

    if (isset($_POST['fecha_final'])) {

        $fin_contratacion_actual = $_POST['fecha_final'];
        $fin      = date('Y-m-d', strtotime($fin_contratacion_actual));
    
        $update_contratacion = mysqli_query($conn, "UPDATE rr_hh_tipo_contrato_salarios SET status = 'C', fecha_fin = '$fin' WHERE identidad = '$identidad' AND status = 'A' AND id = '$id_registro' ");
    
    }else{

        $update_contratacion = true;
        
    }


    if ($update_contratacion === true) {

        if (isset($_POST['finalizacion_definitiva'])) {
            $fin_definitivo = $_POST['finalizacion_definitiva'];             
        }else{
            $fin_definitivo = "NO";
        }

        if ($fin_definitivo == "NO") {

            $nuevo_tipo_contratacion = $_POST['nuevo_tipo_contratacion'];
            $nuevo_salario = $_POST['nuevo_salario_base'];
            $inicio_nueva_contratacion = $_POST['fecha_inicio'];
            $inicio      = date('Y-m-d', strtotime($inicio_nueva_contratacion));


            if (mysqli_query($conn, "INSERT INTO rr_hh_tipo_contrato_salarios (cod_empleado, identidad, tipo_contratacion, salario_base, fecha_inicio, status) VALUES  ('$cod_empleado', '$id_empleado', '$nuevo_tipo_contratacion', '$nuevo_salario', '$inicio', 'A') ") === true) {
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
            }
        }
    } else {
        echo mysqli_error($conn);
    }
}

?>

<script>
    function finalizacion(seleccion) {


        if (seleccion == "SI") {
            document.getElementById("fecha_inicio").disabled = true;
            document.getElementById("nuevo_tipo_contratacion").disabled = true;
            document.getElementById("nuevo_salario_base").disabled = true;
        } else {
            document.getElementById("fecha_inicio").disabled = false;
            document.getElementById("nuevo_tipo_contratacion").disabled = false;
            document.getElementById("nuevo_salario_base").disabled = false;
        }

    }


    function cambio_fecha_final_actual(seleccion) {
        fecha_inicial_actual = document.getElementById("fecha_inicio_actual").value;
        $v_fecha = fecha_inicial_actual.split("/");
        new_date = $v_fecha[2]+"-"+$v_fecha[1]+"-"+$v_fecha[0]; 
        
        var d1 = new Date(new_date);
        var d2 = new Date(seleccion);


        if (d2 < d1) {

            document.getElementById("fecha_final").value = "";

            swal({
                title: "",
                text: "Debe ingresar una fecha de finalización mayor a la fecha de inicio.",
                type: "error"
            });
        }

    }

    function cambio_fecha_inicial_nueva(seleccion) {

        fecha_final_anterior = document.getElementById("fecha_final").value;

        if (fecha_final_anterior == "") {

            document.getElementById("fecha_inicio").value = "";

            swal({
                title: "",
                text: "Debe seleccionar una fecha de finalización de parametros actuales.",
                type: "error"
            });

        } else {

            var d1 = new Date(fecha_final_anterior);
            var d2 = new Date(seleccion);


            if (d2 < d1) {

                document.getElementById("fecha_inicio").value = "";

                swal({
                    title: "",
                    text: "Debe ingresar una fecha de inicio mayor a la fecha de finalización de la contratación anterior.",
                    type: "error"
                });
            }

        }


    }
</script>


<br>

<form method="POST">

    <div class="row">
        <div class="col">

            <div class="card" style='margin-left: 5px; margin-right: 5px'>
                <div class="card-header bg-success text-white">
                    <h4 align="center">GESTION DE CONTRATACION DE EMPLEADO</h4>
                </div>

                <div class="card-body">

                    <input type="hidden" id="id_registro" name="id_registro" class="form-control" value='<?php echo $id_registro ?>'>

                    <div class="input-group">
                        <div class="input-group-prepend ">
                            <span style="width: 300px;" class="input-group-text" id="basic-addon2">COD. EMPLEADO</span>
                        </div>
                        <input type="text" name="cod_empleado" class="form-control" value='<?php echo $cod_empleado ?>' readonly>
                    </div>


                    <div class="input-group" style="margin-top: 5px;">
                        <div class="input-group-prepend ">
                            <span style="width: 300px;" class="input-group-text" id="basic-addon2">IDENTIDAD</span>
                        </div>
                        <input type="text" id="id_empleado_seleccionado" name="identidad" class="form-control" value='<?php echo $identidad ?>' readonly>
                    </div>


                    <div class="input-group" style="margin-top: 5px;">
                        <div class="input-group-prepend">
                            <span style="width: 300px;" class="input-group-text" id="basic-addon2">NOMBRE COMPLETO</span>
                        </div>
                        <input type="text" class="form-control" value='<?php echo $nombre ?>' readonly>
                    </div>


                    <?php

                    if ($desc_contratacion_actual == "") {
                        echo "<br><div class = 'alert alert-danger'>Este empleado esta actualmente inactivo, para activarlo nuevamente por favor ingrese los siguientes datos: </div>";

                    ?>

                        <div class="input-group" style="margin-top: 5px;">
                            <div class="input-group-prepend">
                                <span style="width: 300px;" class="input-group-text" id="basic-addon2">INICIO DE NUEVA CONTRATACION</span>
                            </div>
                            <input type="date" class="form-control"  id="fecha_inicio" name="fecha_inicio" required />
                        </div>

                        <div class="input-group" style="margin-top: 5px;">
                            <div class="input-group-prepend">
                                <span style="width: 300px;" class="input-group-text" id="basic-addon2">NUEVO TIPO DE CONTRATACION</span>
                            </div>
                            <select class="form-control" id="nuevo_tipo_contratacion" name="nuevo_tipo_contratacion" required>
                                <?php

                                while ($reg_contrataciones = mysqli_fetch_array($c_tipos_contrataciones)) {
                                    echo "<option value = " . $reg_contrataciones['id'] . ">" . $reg_contrataciones['descripcion'] . "</option>";
                                }

                                ?>
                            </select>
                        </div>

                        <div class="input-group" style="margin-top: 5px;">
                            <div class="input-group-prepend">
                                <span style="width: 300px;" class="input-group-text" id="basic-addon2">NUEVO SALARIO BASE</span>
                            </div>
                            <input type="text" class="form-control" id="nuevo_salario_base" name="nuevo_salario_base" required>
                        </div>

                    <?php


                    } else {

                    ?>



                        <div class="input-group" style="margin-top: 5px;">
                            <div class="input-group-prepend">
                                <span style="width: 300px;" class="input-group-text" id="basic-addon2">TIPO DE CONTRATACION ACTUAL</span>
                            </div>
                            <input type="text" class="form-control" value='<?php echo $desc_contratacion_actual ?>' readonly>
                        </div>

                        <div class="input-group" style="margin-top: 5px;">
                            <div class="input-group-prepend">
                                <span style="width: 300px;" class="input-group-text" id="basic-addon2">SALARIO BASE ACTUAL</span>
                            </div>
                            <input type="text" class="form-control" value='<?php echo $salario_base_actual ?>' readonly>
                        </div>


                        <div class="input-group" style="margin-top: 5px;">
                            <div class="input-group-prepend">
                                <span style="width: 300px;" class="input-group-text" id="basic-addon2">INICIO DE CONT. ACTUAL</span>
                            </div>
                            <input type="text" readonly value='<?php echo $fecha_inicio_actual; ?>' class="form-control" id="fecha_inicio_actual" name="fecha_inicio_actual" required />
                        </div>



                        <div class="input-group" style="margin-top: 5px;">
                            <div class="input-group-prepend">
                                <span style="width: 300px;" class="input-group-text" id="basic-addon2">FINAL DE CONT. ACTUAL</span>
                            </div>
                            <input type="date" class="form-control" id="fecha_final" name="fecha_final" onchange="cambio_fecha_final_actual(this.value)" required />
                        </div>

                        <hr>

                        <div class="input-group" style="margin-top: 5px;">
                            <div class="input-group-prepend">
                                <span style="width: 300px;" class="input-group-text" id="basic-addon2">FINALIZACION DEFINITIVA</span>
                            </div>
                            <select class="form-control" id="finalizacion_definitiva" name="finalizacion_definitiva" onchange="finalizacion(this.value)">
                                <option value="NO">NO</option>
                                <option value="SI">SI</option>
                            </select>
                        </div>


                        <div class="input-group" style="margin-top: 5px;">
                            <div class="input-group-prepend">
                                <span style="width: 300px;" class="input-group-text" id="basic-addon2">INICIO DE NUEVA CONTRATACION</span>
                            </div>
                            <input type="date" class="form-control" onchange="cambio_fecha_inicial_nueva(this.value)" id="fecha_inicio" name="fecha_inicio" required />
                        </div>



                        <div class="input-group" style="margin-top: 5px;">
                            <div class="input-group-prepend">
                                <span style="width: 300px;" class="input-group-text" id="basic-addon2">NUEVO TIPO DE CONTRATACION</span>
                            </div>
                            <select class="form-control" id="nuevo_tipo_contratacion" name="nuevo_tipo_contratacion" required>
                                <?php

                                $i = 0;
                                while ($reg_contrataciones = mysqli_fetch_array($c_tipos_contrataciones)) {
                                    $v_contratos[$i] = array("id"=>$reg_contrataciones['id'], "descripcion"=>$reg_contrataciones['descripcion'] );
                                    $i++;
                                    echo "<option value = " . $reg_contrataciones['id'] . ">" . $reg_contrataciones['descripcion'] . "</option>";
                                }

                                ?>
                            </select>
                        </div>



                        <div class="input-group" style="margin-top: 5px;">
                            <div class="input-group-prepend">
                                <span style="width: 300px;" class="input-group-text" id="basic-addon2">NUEVO SALARIO BASE</span>
                            </div>
                            <input type="text" class="form-control" id="nuevo_salario_base" name="nuevo_salario_base" required>
                        </div>




                    <?php

                    }

                    ?>

                </div>

                <div class="card-footer" style="text-align:center">
                    <button type="submit" name="guardar_cambios" class="btn btn-success">GUARDAR CAMBIOS</button>
                </div>
            </div>


        </div>

        <div class="col">
            <div class="card " style='margin-left: 5px; margin-right: 5px'>
                <div class="card-header bg-success text-white">
                    <h4 style="text-align: center;">HISTORICO DE CONTRATACION Y SALARIOS </h4>
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
                            
                            echo  $v_contratos[$i]["id"]." = ".$v_contratos[$i]["descripcion"]." <br> ";
                            
                            $i++;
                        }
                    ?>
                </div>
            </div>

        </div>


    </div>


</form>