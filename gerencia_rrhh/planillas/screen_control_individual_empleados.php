<?php
require '../../template/header.php';
?>

<script type="text/javascript">
    function consultar_empleado() {

        code = document.getElementById("cod_empleado").value;
        year = document.getElementById("year").value;

        if (code == "") {

            alert("Debe ingresar un codigo de empleado o seleccionar un nombre.");

        } else {

            if (year == "") {

                alert("Debe ingresar un año a consultar.");

            } else {

                $(".div_wait").fadeIn("fast");
                token = Math.random();
                consulta = 'control_individual_empleados_js.php?code=' + code + "&year=" + year + "&token=" + token;
                $("#respuesta").load(consulta);

                $(".div_wait").fadeOut("fast");

            }

        }

    }

    function digitar_empleado() {
        code = document.getElementById("cod_empleado").value;
        document.getElementById("nombre").value = code;
    }

    function seleccion_nombre() {
        code = document.getElementById("nombre").value;
        document.getElementById("cod_empleado").value = code;
    }
</script>

<section style="background-color:#ededed;">
    <br>
    <h3 align="center"><b>CONTROL INDIVIDUAL DEL EMPLEADO</b></h3>
    <br>
</section>

<div id="respuesta2"></div>

<div class="card">
    <div class="card-body">

        <div class="row">
            <div class="col">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text" style="min-width:210px">Nombre</div>
                    </div>

                    <select name="nombre" id="nombre" onchange="seleccion_nombre()" class="form-control">
                        <option value="ninguno">Seleccione uno</option>
                        <?php

                        $conn2 = oci_connect('cide', 'pani2017', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.15.102)(PORT=1521)))(CONNECT_DATA=(SID=dbpani)(SERVER = DEDICATED)(SERVICE_NAME = DBPANITG)))');

                        //////////////////////
                        // SELECT EMPLEADOS //

                        $consulta_empleados = oci_parse($conn2, "SELECT * FROM PL_EMPLEADOS ORDER BY NOMBRE_PILA ASC ");

                        oci_execute($consulta_empleados);

                        $nombre = '';
                        while ($reg_empleado = oci_fetch_array($consulta_empleados, OCI_ASSOC + OCI_RETURN_NULLS)) {
                            $nombre = $reg_empleado['NOMBRE_PILA'] . ' ' . $reg_empleado['APE_PAT'] . ' ' . $reg_empleado['APE_MAT'];
                            $cod = $reg_empleado['NO_EMPLE'];
                            echo "<option value = " . $cod . " >" . $nombre . "</option>";
                        }

                        // SELECT EMPLEADOS //
                        //////////////////////

                        ?>

                    </select>

                </div>

            </div>
        </div>

        <div class="row">
            <div class="col">

                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text" style="min-width:210px">COD. EMPLEADO: </div>
                    </div>
                    <input type="number" name="cod_empleado" id="cod_empleado" onkeyup="digitar_empleado()" class="form-control">

                    <div class="input-group-prepend">
                        <div class="input-group-text">AÑO: </div>
                    </div>
                    <input type="text" name="year" id="year" class="form-control">

                    <div class="input-group-append">
                        <button id="non-printable" class="btn btn-outline-primary" onclick="consultar_empleado()" id="buscar" name="buscar" type="button"><i class="fa fa-search"></i></button>
                    </div>
                </div>


                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text" style="min-width:210px" readonly>IDENTIDAD: </div>
                    </div>
                    <input type="text" name="identidad" id="identidad" class="form-control">
                </div>


                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text" style="min-width:210px" readonly>FECHA DE INGRESO: </div>
                    </div>
                    <input type="text" name="fecha_ingreso" id="fecha_ingreso" class="form-control">
                </div>


            </div>
            <div class="col">

                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text" style="min-width:220px" readonly>OCUPACION: </div>
                    </div>
                    <input type="text" name="ocupacion" id="ocupacion" class="form-control">
                </div>


                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text" readonly>EDAD: </div>
                    </div>
                    <input type="text" name="edad" id="edad" class="form-control">

                    <div class="input-group-prepend">
                        <div class="input-group-text" readonly>SEXO: </div>
                    </div>
                    <input type="text" name="sexo" id="sexo" class="form-control">

                </div>


                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text" style="min-width:220px" readonly>FECHA DE NACIMIENTO: </div>
                    </div>
                    <input type="text" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control">
                </div>

            </div>
        </div>
    </div>
</div>

<br>

<div class="card">
    <div class="card-body">

        <div id="respuesta"></div>

    </div>
</div>

<div id="div_wait"></div>