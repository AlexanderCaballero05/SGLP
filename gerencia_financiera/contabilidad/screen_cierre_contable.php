<?php


require("../../template/header.php");



?>


<form method="POST">



    <section style="background-color:#ededed;">
        <br>
        <h2 align="center" style="color:black;">
            <b>NORMALIZACION DE CIERRE CONTABLE

                <?php

                if (isset($_POST['seleccionar'])) {

                    $year = $_POST['year'];
                    $mes = $_POST['mes'];

                    echo "<p align = 'center'>  " . $year . " - " . $mes . "</p>";
                }


                ?>

            </b>
        </h2>
        <br>
    </section>


    <a class="btn btn-secondary" id="non-printable" style="width:100%" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
        Selección de parametros
    </a>

    <div class="collapse" style="width:100%" id="collapse1" align="center">
        <div class="card" style="width: 50%">
            <div class="card-body">


                <div class="input-group" style="margin:10px 0px 10px 0px;">
                    <input type="text" placeholder="Año" name="year" id="year" class="form-control">
                </div>


                <div class="input-group" style="margin:10px 0px 10px 0px;">
                    <input type="text" placeholder="Mes" name="mes" id="mes" class="form-control">
                </div>


                <input type="submit" name="seleccionar" class="btn btn-primary" value="Seleccionar" style="width: 100%">


            </div>
        </div>
    </div>




    <?php

    if (isset($_POST['seleccionar'])) {

        $year = $_POST['year'];
        $mes_actual = $_POST['mes'];
        $mes_anterior = $mes_actual - 1;


        //////////////////////////////////////////////////////////
        ///////////////// CONSULTA INFO SALDOS   /////////////////

        $conn2 = oci_connect('cide', 'pani2017', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.15.102)(PORT=1521)))(CONNECT_DATA=(SID=dbpani)(SERVER = DEDICATED)(SERVICE_NAME = DBPANITG)))');

        if ($conn2 == FALSE) {
            $e = oci_error();
            $msg_error = "ERROR DE CONEXION ORACLE: " . $e['message'] . "<br>";
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            exit;
        }

        $consulta_saldos = oci_parse($conn2, "SELECT *  FROM CG_HISTORICO_CUENTAS WHERE ANO = '$year' AND MES IN ('$mes_actual','$mes_anterior')  ORDER BY CUENTA, MES ASC ");

        oci_execute($consulta_saldos);

        $v_cuentas = [];
        while ($reg_saldos = oci_fetch_array($consulta_saldos, OCI_ASSOC + OCI_RETURN_NULLS)) {

            $cuenta_erp =  $reg_saldos['CUENTA'];
            $moviemiento_erp =  $reg_saldos['MOVIMIENTO'];            
            $saldo_erp =  $reg_saldos['SALDO'];
            $saldo_anterior_erp =  $reg_saldos['SALDO_ANTERIOR'];
            $year_erp =  $reg_saldos['ANO'];
            $mes_erp =  $reg_saldos['MES'];

            $detalle = ["movimiento" => $moviemiento_erp, "saldo" => $saldo_erp, "saldo_anterior" => $saldo_anterior_erp ];            

            $v_cuentas[$cuenta_erp][$mes_erp] = $detalle;

        }


        echo "<br>";
        echo "<br>";
        echo "<table class = 'table table-bordered' >";

        echo "<thead>";
        echo "<tr>";
        echo "<th colspan = '4' style = 'background-color:#f7efe4' >ERP</th>";
        echo "<th colspan = '3' style = 'background-color:#eef7eb'  >POSTERIOR A CORRECCION</th>";
        echo "</tr>";

        echo "<tr>";
        echo "<th style = 'background-color:#f7efe4'>CUENTA</th>";
        echo "<th style = 'background-color:#f7efe4'>SALDO INICIAL</th>";
        echo "<th style = 'background-color:#f7efe4'>TOTAL MOVIMIENTOS</th>";
        echo "<th style = 'background-color:#f7efe4'>SALDO FINAL</th>";

        echo "<th style = 'background-color:#eef7eb'>SALDO INICIAL</th>";
        echo "<th style = 'background-color:#eef7eb'>TOTAL MOVIMIENTOS</th>";
        echo "<th style = 'background-color:#eef7eb'>SALDO FINAL</th>";
        echo "</tr>";

        echo "</thead>";


        $tt_erp_saldo_inicial = 0;
        $tt_erp_movimientos = 0;
        $tt_erp_saldo_final = 0;

        $tt_normalizacion_saldo_inicial = 0;
        $tt_normalizacion_movimientos = 0;
        $tt_normalizacion_saldo_final = 0;
        $irregularidades = 0;

        foreach ($v_cuentas as  $c =>  $reg_cuenta) {

            $saldo_final = $reg_cuenta[$mes_anterior]['saldo'] + $reg_cuenta[$mes_actual]['movimiento'];  
            $comparativo = $reg_cuenta[$mes_actual]['saldo']; 

            $result = number_format($saldo_final) - number_format($comparativo);


            echo "<tr>";

            if ($reg_cuenta[$mes_anterior]['saldo'] == 0 AND $reg_cuenta[$mes_actual]['saldo'] == 0 AND $reg_cuenta[$mes_actual]['movimiento'] == 0 AND $reg_cuenta[$mes_anterior]['movimiento'] == 0 ) {
                
            }else{

                echo "<td style = 'background-color:#f7efe4'>".$c." DIF ".$result."</td>";            
                echo "<td style = 'background-color:#f7efe4'>".number_format($reg_cuenta[$mes_anterior]['saldo'], 2 ) ."</td>";  
                $tt_erp_saldo_inicial += $reg_cuenta[$mes_anterior]['saldo'];
                echo "<td style = 'background-color:#f7efe4'>".number_format($reg_cuenta[$mes_actual]['movimiento'], 2 )."</td>";            
                $tt_erp_movimientos += $reg_cuenta[$mes_actual]['movimiento'];

                if ($result != 0) {
                    $irregularidades ++;
                    echo "<td style = 'background-color:#f7efe4; color:red'><b>".number_format($reg_cuenta[$mes_actual]['saldo'], 2 )."</b></td>";            
                }else{
                    echo "<td style = 'background-color:#f7efe4'>".number_format($reg_cuenta[$mes_actual]['saldo'], 2 )."</td>";            
                }
                $tt_erp_saldo_final += $reg_cuenta[$mes_actual]['saldo'];

    
                echo "<td style = 'background-color:#eef7eb'>".number_format($reg_cuenta[$mes_anterior]['saldo'], 2 )."</td>";            
                $tt_normalizacion_saldo_inicial += $reg_cuenta[$mes_anterior]['saldo'];

                echo "<td style = 'background-color:#eef7eb' >".number_format($reg_cuenta[$mes_actual]['movimiento'], 2 )."</td>";            
                $tt_normalizacion_movimientos += $reg_cuenta[$mes_actual]['movimiento'];

                $update = oci_parse($conn2, "UPDATE CG_HISTORICO_CUENTAS SET SALDO = '$saldo_final' WHERE ANO = '$year' AND MES = '$mes_actual' AND CUENTA = '$c' ");
                oci_execute($update);

                if ($result != 0) {
                    echo "<td style = 'background-color:#eef7eb; color:#0c2e00'><b>".number_format($saldo_final, 2 )."</b></td>";  
                    
                    $update = oci_parse($conn2, "UPDATE CG_HISTORICO_CUENTAS SET SALDO = '$saldo_final' WHERE ANO = '$year' AND MES = '$mes_actual' AND CUENTA = '$c' ");
                   oci_execute($update);

                }else{
                    echo "<td style = 'background-color:#eef7eb'>".number_format($saldo_final, 2 )."</td>";            
                }
                $tt_normalizacion_saldo_final += $saldo_final;

    
            }
            
            echo "</tr>";            
        }

        echo "<tr>";
        echo "<td>TOTALES</td>";
        echo "<td>".number_format( $tt_erp_saldo_inicial, 2)."</td>";
        echo "<td>".number_format( $tt_erp_movimientos, 2)."</td>";
        echo "<td>".number_format( $tt_erp_saldo_final, 2)."</td>";
        echo "<td>".number_format( $tt_normalizacion_saldo_inicial, 2)."</td>";
        echo "<td>".number_format( $tt_normalizacion_movimientos, 2)."</td>";
        echo "<td>".number_format( $tt_normalizacion_saldo_final, 2)."</td>";
        echo "</tr>";
        echo "<td colspan = '7'>IRREGULARIDADES: ".$irregularidades."</td>";
        echo "</tr>";

        echo "</table>";
        ///////////////// CONSULTA INFO SALDOS   /////////////////
        //////////////////////////////////////////////////////////

    }

    ?>


</form>
