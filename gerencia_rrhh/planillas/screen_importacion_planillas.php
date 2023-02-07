<?php
require '../../template/header.php';


if (isset($_POST['guardar'])) {
    $cod_planilla_origen = $_POST['o_cod_planilla'];
    $year_planilla_origen = $_POST['o_year_planilla'];
    $mes_planilla_origen = $_POST['o_mes_planilla'];

    $cod_planilla_destino = $_POST['new_cod_planilla'];
    $year_planilla_destino = $_POST['new_year_planilla'];
    $mes_planilla_destino = $_POST['new_mes_planilla'];

    echo "PLANILLA ORIGEN: ".$cod_planilla_origen."<br>";
    echo "AÑO ORIGEN: ".$year_planilla_origen."<br>";
    echo "MES ORIGEN: ".$mes_planilla_origen."<br>";

    echo "PLANILLA DESTINO: ".$cod_planilla_destino."<br>";
    echo "AÑO DESTINO: ".$year_planilla_destino."<br>";
    echo "MES DESTINO: ".$mes_planilla_destino."<br>";

}


?>

<section style="background-color:#ededed;">
    <br>
    <h3 align="center"><b>CARGADO DE PLANILLAS PERIODO ANTERIOR</b></h3>
    <br>
</section>


<form method="POST">


    <a class="btn btn-secondary" id="non-printable" style="width:100%" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
        Selección de parametros
    </a>

    <div class="collapse" style="width:100%" id="collapse1" align="center">
        <div class="card">
            <div class="card-body">


                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Codigo de Planilla</div>
                        <input type="text" class="form-control" name="cod_planilla" id="cod_planilla">
                    </div>

                    <div class="input-group-prepend">
                        <div class="input-group-text">Año</div>
                        <input type="text" class="form-control" name="year_planilla" id="year_planilla">
                    </div>


                    <div class="input-group-prepend">
                        <div class="input-group-text">Mes</div>
                        <input type="text" class="form-control" name="mes_planilla" id="mes_planilla">
                    </div>

                    <button class="btn btn-primary" name="seleccionar">VISUALIZAR DATOS A CARGAR</button>

                </div>




            </div>
        </div>
    </div>

    <br>




    <?php

    if (isset($_POST['seleccionar'])) {

        $cod_planilla = $_POST['cod_planilla'];
        $year_planilla = $_POST['year_planilla'];
        $mes_planilla = $_POST['mes_planilla'];


        echo '      <br><br>
                    <div class="card">
                    <div class="card-body">
                    ';


        $conn2 = oci_connect('cide', 'pani2017', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.15.102)(PORT=1521)))(CONNECT_DATA=(SID=dbpani)(SERVER = DEDICATED)(SERVICE_NAME = DBPANITG)))');

        $c_ingresos = oci_parse($conn2, "SELECT CODIGO, TIPO_M, '01' as NO_CIA, '01' as COD_PLA, NO_EMPLE, '001' as NO_INGRE, MONTO, 'S' as MODIFICADO, NULL as TASA, NULL as MESES, NULL as DIAS, 0 as MONTO_AUX, NULL as DEVOL_NO_DEDU, NULL as NO_OPERA, NULL as ANO_ORIG, NULL as MES_ORIG, NULL as COD_PLA_ORIG, NULL as PERIODO_ORIG, 'M' as IND_GEN_AUTO, NULL as SALDO  FROM PL_HISTORICO_SALARIOS WHERE COD_PLA = '$cod_planilla' AND ANO = '$year_planilla' AND MES = '$mes_planilla'  ");

        oci_execute($c_ingresos);

        $v_empleados = [];

        while ($r_ingresos = oci_fetch_array($c_ingresos, OCI_ASSOC + OCI_RETURN_NULLS)) {

            $cod_empleado = $r_ingresos['NO_EMPLE'];
            $tipo_movimiento = $r_ingresos['TIPO_M'];
            $codigo_movimiento = $r_ingresos['CODIGO'];
            $monto = $r_ingresos['MONTO'];

            $detalle = ["codigo_movimiento" => $codigo_movimiento, "monto" => $monto];

            $v_empleados[$cod_empleado][$tipo_movimiento][$codigo_movimiento] = $monto;
        }

        //print_r($v_empleados);

        echo "<table class = 'table table-bordered'>";
        echo "<thead>";

        echo "<tr>";
        echo "<th colspan = '4'> PLANILLA: " . $cod_planilla . " || FECHA: " . $year_planilla . "-" . $mes_planilla . "</th>";
        echo "</tr>";

        echo "<tr>";
        echo "<th>COD. EMPLEADO</th>";
        echo "<th>INGRESO</th>";
        echo "<th>DEDUCCIONES</th>";
        echo "<th>TOTAL</th>";
        echo "</tr>";
        echo "</thead>";

        $ttt = 0;
        foreach ($v_empleados as $e => $ingresos_deducciones) {

            $td = 0;
            $ti = 0;
            $tt = 0;

            echo "<tr>";
            echo "<td>" . $e . "</td>";
            echo "<td style = 'color:green' >";
            foreach ($ingresos_deducciones['I'] as  $ingresos) {
                $ti += $ingresos;

                echo number_format($ingresos, 2) . "<br>";
            }
            echo "</td>";
            echo "<td style = 'color:red'>";
            foreach ($ingresos_deducciones['D'] as  $deducciones) {
                $td += $deducciones;

                echo number_format($deducciones, 2) . "<br>";
            }

            echo "</td>";

            $tt = $ti - $td;
            $ttt += $tt;

            echo "<td style = 'color:green' >" . number_format($tt, 2) . "</td>";

            echo "</tr>";
        }


        echo "<tr>";
        echo "<th colspan = '3'>TOTAL</th>";
        echo "<th>" . number_format($ttt, 2) . "</th>";
        echo "</tr>";


        echo "</table>";


        echo "</div>
            <div class = 'card-footer'>
            <input type='text' class='form-control' name='o_cod_planilla' id='o_cod_planilla' value = '" . $cod_planilla . "' >
            <input type='text' class='form-control' name='o_year_planilla' id='o_year_planilla' value = '" . $year_planilla . "' >
            <input type='text' class='form-control' name='o_mes_planilla' id='o_mes_planilla' value = '" . $mes_planilla . "' >";


        echo '
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">Codigo de Planilla</div>
                <input type="text" class="form-control" name="new_cod_planilla" id="new_cod_planilla" value = "' . $cod_planilla . '" readonly> 
            </div>

            <div class="input-group-prepend">
                <div class="input-group-text">Año</div>
                <input type="text" class="form-control" name="new_year_planilla" id="new_year_planilla" value = "' . $year_planilla . '" >
            </div>

            <div class="input-group-prepend">
                <div class="input-group-text">Mes</div>
                <input type="text" class="form-control" name="new_mes_planilla" id="new_mes_planilla"  required>
            </div>

            <button class="btn btn-primary" name="guardar" id="guardar" >GUARDAR</button>
        </div>';

        echo "</div>
            </div>
             ";
    }

    ?>



</form>