<?php


require("../../template/header.php");



?>


<form method="POST">



    <section style="background-color:#ededed;">
        <br>
        <h2 align="center" style="color:black;">
            <b>ACTUALIZACION DE SALDOS PRESUPUESTARIOS 

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
                    <input type="text" placeholder="Año" name="year" id="year" class="form-control" value = '2022' readonly>
                </div>


                <div class="input-group" style="margin:10px 0px 10px 0px;">
                    <input type="text" placeholder="Mes" name="mes" id="mes" value = '9' class="form-control" readonly>
                </div>


                <input type="submit" name="seleccionar" class="btn btn-primary" value="Seleccionar" style="width: 100%">


            </div>
        </div>
    </div>




    <?php

    if (isset($_POST['seleccionar'])) {

        $year = $_POST['year'];
        $mes = $_POST['mes'];

        $contador_pagado = 0;
        $contador_error_pagado = 0;
        $contador_provision = 0;
        $contador_error_provision = 0;


        //////////////////////////////////////////////////////////
        ///////////////// CONSULTA INFO SALDOS   /////////////////

        $conn2 = oci_connect('cide', 'pani2017', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.15.102)(PORT=1521)))(CONNECT_DATA=(SID=dbpani)(SERVER = DEDICATED)(SERVICE_NAME = DBPANITG)))');

        if ($conn2 == FALSE) {
            $e = oci_error();
            $msg_error = "ERROR DE CONEXION ORACLE: " . $e['message'] . "<br>";
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            exit;
        }

        $consulta_pagado   = oci_parse($conn2, " SELECT  ESTRUCTURA, OBJETO_GASTO, SUM(MONTO) as MONTO FROM (SELECT PRE_ORDEN_OBJETO_GASTO.MONTO, PRE_ORDEN_OBJETO_GASTO.ESTRUCTURA, PRE_ORDEN_OBJETO_GASTO.OBJETO_GASTO FROM PRE_ORDEN_OBJETO_GASTO INNER JOIN PRE_ORDENES_PAGO ON  PRE_ORDEN_OBJETO_GASTO.NO_ORDEN = PRE_ORDENES_PAGO.NO_ORDEN WHERE EXTRACT(year FROM FECHA)  = '$year' AND EXTRACT(month FROM FECHA)  = '$mes' AND PRE_ORDENES_PAGO.PRES_ANO_ANT = 'N' AND AUTORIZADO = 'T' AND NVL(rechazado_presu, 'N')='N') GROUP BY  ESTRUCTURA, OBJETO_GASTO ORDER BY  ESTRUCTURA, OBJETO_GASTO  ");

        oci_execute($consulta_pagado);

        $v_pagado = [];
        while ($reg_pagado = oci_fetch_array($consulta_pagado, OCI_ASSOC + OCI_RETURN_NULLS)) {

            $p_monto =  $reg_pagado['MONTO'];
            $p_estructura =  $reg_pagado['ESTRUCTURA'];            
            $p_objeto =  $reg_pagado['OBJETO_GASTO'];

            $detalle = ["monto" => $p_monto];            


            $update_pagado = oci_parse($conn2, " UPDATE CG_HISTORICO_CUENTAS_P SET PAGADO = '$p_monto' WHERE CODIGO_ESTRUCTURA = '$p_estructura' AND CODIGO_OBJETO = '$p_objeto' AND ANO = '$year'  AND MES = '$mes' ");

            try {

                oci_execute($update_pagado);

                $contador_pagado ++; 

            } catch (Exception $e) {

                $contador_error_pagado ++; 

            }



            $v_pagado[$p_estructura][$p_objeto] = $detalle;

        }


        

        $consulta_comprometido = oci_parse($conn2, " SELECT * FROM (SELECT SUM(MONTO) as MONTO, ESTRUCTURA, OBJETO_GASTO FROM PRE_COMPROMISOS_V WHERE  EXTRACT(year FROM FECHA)  = '$year' AND EXTRACT(month FROM FECHA)  = '$mes' GROUP BY ESTRUCTURA, OBJETO_GASTO  ) ");

        oci_execute($consulta_comprometido);

        $v_comprometido = [];
        while ($reg_comprometido = oci_fetch_array($consulta_comprometido, OCI_ASSOC + OCI_RETURN_NULLS)) {

            $c_monto =  $reg_comprometido['MONTO'];
            $c_estructura =  $reg_comprometido['ESTRUCTURA'];            
            $c_objeto =  $reg_comprometido['OBJETO_GASTO'];

            $detalle = ["monto" => $c_monto];            


            $update_comprometido = oci_parse($conn2, " UPDATE CG_HISTORICO_CUENTAS_P SET COMPROMETIDO = '$c_monto', DISPONIBLE = (PRES_AC - '$c_monto') WHERE CODIGO_ESTRUCTURA = '$c_estructura' AND CODIGO_OBJETO = '$c_objeto' AND ANO = '$year'  AND MES = '$mes' ");

            try {

                oci_execute($update_comprometido);

                $contador_provision ++; 

            } catch (Exception $e) {

                $contador_error_provision ++; 

            }



            $v_comprometido[$c_estructura][$c_objeto] = $detalle;

        }



        echo "<div class = 'alert alert-success'>";
        echo "Objetos de gasto actualizados: ". $contador_provision;
        echo "Errores en actualizacion: ". $contador_error_provision;
        echo "</div>";
      

    }

    ?>


</form>
