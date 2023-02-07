<?php

$code = $_GET['code'];
$year = $_GET['year'];



$conn2 = oci_connect('cide', 'pani2017', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.15.102)(PORT=1521)))(CONNECT_DATA=(SID=dbpani)(SERVER = DEDICATED)(SERVICE_NAME = DBPANITG)))');


///////////////////
// INFO EMPLEADO //

if ($conn2 == FALSE) {
    $e = oci_error();
    $msg_error = "ERROR DE CONEXION ORACLE: " . $e['message'] . "<br>";
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    exit;
}


$consulta_empleados = oci_parse($conn2, "SELECT * FROM PL_EMPLEADOS WHERE NO_EMPLE = '$code'  ");

oci_execute($consulta_empleados);

$nombre = '';
while ($reg_empleado = oci_fetch_array($consulta_empleados, OCI_ASSOC + OCI_RETURN_NULLS)) {
    $nombre = $reg_empleado['NOMBRE_PILA'] . ' ' . $reg_empleado['APE_PAT'] . ' ' . $reg_empleado['APE_MAT'];
    $cod_puesto = $reg_empleado['PUESTO'];
    $fecha_ingreso = $reg_empleado['F_INGRESO'];
    $fecha_nacimiento = $reg_empleado['F_NACIMI'];
    $estado_civil = $reg_empleado['E_CIVIL'];
    $sexo = $reg_empleado['SEXO'];
    $nacionalidad = $reg_empleado['NACION'];
    $cedula = $reg_empleado['CEDULA'];
}

if ($sexo == "M") {
    $sexo = "MASCULINO";
} else {
    $sexo = "FEMENINO";
}

if ($nacionalidad == "N") {
    $nacionalidad = "HONDURAS";
}


if ($estado_civil == "S") {
    $estado_civil = "SOLTERO/A";
} elseif ($estado_civil == "C") {
    $estado_civil = "CASADO/A";
} elseif ($estado_civil == "V") {
    $estado_civil = "VIUDIO/A";
} elseif ($estado_civil == "D") {
    $estado_civil = "DIVORCIADO/A";
}



$consulta_puestos = oci_parse($conn2, "SELECT DESCRI FROM PL_PUESTOS  WHERE PUESTO = '$cod_puesto'  ");
oci_execute($consulta_puestos);

while ($reg_puesto = oci_fetch_array($consulta_puestos, OCI_ASSOC + OCI_RETURN_NULLS)) {
    $puesto = $reg_puesto['DESCRI'];
}

// INFO EMPLEADO //
///////////////////





/////////////////////
// OTROS REGISTROS //

require('../../conexion.php');

$consulta_otros = mysqli_query($conn, "SELECT id , MONTH(fecha) mes , devengado, seg_social, impuesto, observaciones FROM rr_hh_planilla_otros_ingresos WHERE cod_empleado = '$code' AND estado = 'A' AND YEAR(fecha) = '$year' ORDER BY fecha ASC ");

echo mysqli_error($conn);

$i = 0;
while ($reg_consulta_otros = mysqli_fetch_array($consulta_otros)) {
    $mes = $reg_consulta_otros['mes'];
    $devengado = $reg_consulta_otros['devengado'];
    $seguro = $reg_consulta_otros['seg_social'];
    $impuesto = $reg_consulta_otros['impuesto'];
    $observaciones = $reg_consulta_otros['observaciones'];
    $id_otro = $reg_consulta_otros['id'];

    if (!isset($v_otros_ingresos[$mes])) {

        $i = 0;
        $v_otros_ingresos[$mes][$i] = ['id' => $id_otro, 'mes' => $mes, 'devengado' => $devengado, 'seguro' => $seguro, 'impuesto' => $impuesto, 'observaciones' => $observaciones];
    } else {

        $v_otros_ingresos[$mes][$i] = ['id' => $id_otro, 'mes' => $mes, 'devengado' => $devengado, 'seguro' => $seguro, 'impuesto' => $impuesto, 'observaciones' => $observaciones];
    }

    $i++;
}

// OTROS REGISTROS //
///////////////////


//print_r($v_otros_ingresos);



///////////////////
// INFO PAGOS //

$consulta_pagos = oci_parse($conn2, "SELECT * FROM PL_HISTORICO_SALARIOS WHERE NO_EMPLE = '$code' AND ANO = '$year' AND TIPO_M = 'I'  ORDER BY MES ASC, TIPO_M DESC ");
oci_execute($consulta_pagos);

echo '

<table class="table table-bordered table-sm">
<tr>
<th rowspan="2" style="text-align:center; vertical-align:middle"  > FECHA </th>
<th colspan="3" style="text-align:center"> PARCIALES </th>
<th rowspan="2" style="text-align:center; vertical-align:middle"> OBSERVACIONES </th>
</tr>

<tr>
<th> DEVENGADO </th>
<th> SEG. SOCIAL </th>
<th> IMP. S/RENTA </th>
</tr>

';


$monto_devengado = 0;

$tt_devengado = 0;
$tt_seguro = 0;
$tt_isr = 0;
$tt_vacaciones = 0;


while ($reg_pagos = oci_fetch_array($consulta_pagos, OCI_ASSOC + OCI_RETURN_NULLS)) {

    $year_pago = $reg_pagos['ANO'];
    $mes_pago = $reg_pagos['MES'];
    $tipo_movimiento = $reg_pagos['TIPO_M'];
    $codigo_movimiento = $reg_pagos['CODIGO'];
    $monto = $reg_pagos['MONTO'];
    $monto_isr = "";
    $monto_ss = "";
    $cod_pla = $reg_pagos['COD_PLA'];



    ///////////////////////////////////////////////////////////////////
    /////////// CONSULTA DE DESCRIPCION DEL MOVIMIENTO ////////////////
    $consulta_desc_mov = oci_parse($conn2, "SELECT DESCRI FROM PL_CODIGOS_PLANILLA WHERE CODPLA = '$cod_pla' ");

    oci_execute($consulta_desc_mov);

    while ($reg_desc_mov = oci_fetch_array($consulta_desc_mov, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $desc_movimiento = $reg_desc_mov['DESCRI'];
    }
    /////////// CONSULTA DE DESCRIPCION DEL MOVIMIENTO ////////////////
    ///////////////////////////////////////////////////////////////////


    $consulta_deduccion_seguro = oci_parse($conn2, "SELECT * FROM PL_HISTORICO_SALARIOS WHERE NO_EMPLE = '$code' AND ANO = '$year' AND TIPO_M = 'D' AND COD_PLA = '$cod_pla' AND CODIGO = '100' AND MES = '$mes_pago'  ");
    oci_execute($consulta_deduccion_seguro);

    while ($reg_deduccion_seguro = oci_fetch_array($consulta_deduccion_seguro, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $monto_ss = $reg_deduccion_seguro['MONTO'];
    }

    $consulta_deduccion_isr = oci_parse($conn2, "SELECT * FROM PL_HISTORICO_SALARIOS WHERE NO_EMPLE = '$code' AND ANO = '$year' AND TIPO_M = 'D' AND COD_PLA = '$cod_pla' AND CODIGO = '200' AND MES = '$mes_pago' ");
    oci_execute($consulta_deduccion_isr);

    while ($reg_deduccion_isr = oci_fetch_array($consulta_deduccion_isr, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $monto_isr = $reg_deduccion_isr['MONTO'];
    }




    
    $i = 0;
    while (isset($v_otros_ingresos[$mes_pago][$i])) {

        echo "<tr>";
        echo "<td>" . $mes_pago . "/" . $year_pago . "</td>"; // FECHA    
        echo "<td>" . number_format($v_otros_ingresos[$mes_pago][$i]['devengado'], 2) . "</td>"; // MONTO
        echo "<td>" . number_format($v_otros_ingresos[$mes_pago][$i]['seguro'], 2) . "</td>"; // SEG SOCIAL
        echo "<td>" . number_format($v_otros_ingresos[$mes_pago][$i]['impuesto'], 2) . "</td>"; // IMP S/RENTA
        echo "<td></td>"; // HORAS EXTRAS
        echo "<td></td>"; // VACACIONES
        echo "<td>" . $v_otros_ingresos[$mes_pago][$i]['observaciones'] . "<button onclick = 'eliminar_registro(this.value)' name = 'eliminar_registro' id = 'eliminar_registro' value = '" . $v_otros_ingresos[$mes_pago][$i]['id'] . "' style = 'margin-left:10px' class = 'btn btn-outline-danger'>X</button></td>"; // OBSERVACIONES
        echo "</tr>";

        unset($v_otros_ingresos[$mes_pago][$i]);
        $i++;

        $tt_devengado += $v_otros_ingresos[$mes_pago][$i]['devengado'];
        $tt_seguro += $v_otros_ingresos[$mes_pago][$i]['seguro'];
        $tt_isr += $v_otros_ingresos[$mes_pago][$i]['impuesto'];
    }



    $consulta_otras_deducciones = oci_parse($conn2, "SELECT * FROM PL_HISTORICO_SALARIOS WHERE NO_EMPLE = '$code' AND ANO = '$year' AND TIPO_M = 'D' AND COD_PLA = '$cod_pla' AND CODIGO =  '110' AND MES = '$mes_pago'  ");
    oci_execute($consulta_otras_deducciones);

    $monto_otras_deducciones = 0;
    $monto_tt = $monto;
    $concat_otras_deducciones = ""; 
    while ($reg_otras_deducciones = oci_fetch_array($consulta_otras_deducciones, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $monto_otras_deducciones = $reg_otras_deducciones['MONTO'];
        $concat_otras_deducciones .= "-".number_format($monto_otras_deducciones,2) . " <br>";
    }

    $monto_tt = $monto - $monto_otras_deducciones;


    if ($cod_pla == "02") {

        echo "<tr>";
        echo "<td>" . $mes_pago . "/" . $year_pago . "</td>"; // FECHA    
        echo "<td>" . number_format($monto_tt, 2) . "</td>"; // VACACIONES
        echo "<td>" . number_format($monto_ss, 2) . "</td>"; // SEG SOCIAL
        echo "<td>" . number_format($monto_isr, 2) . "</td>"; // IMP S/RENTA
        echo "<td>";
        echo $desc_movimiento; // OBSERVACIONES
        if ($concat_otras_deducciones != "") {

            echo "<table  style = 'width:100%; font-size:14px' >";

            echo "<tr>";
            echo "<td>Sueldo Base:</td>";
            echo "<td style = 'color:green; text-align:right' >".number_format($monto, 2)." </td>";
            echo "</tr>";

            echo "<tr>";
            echo "<td>Autorizaciones:</td>";
            echo "<td style = 'color:red; text-align:right' >".$concat_otras_deducciones." </td>";
            echo "</tr>";

            echo "</table>";

        }
        echo "</td>";
        echo "</tr>";

        $tt_seguro += $monto_ss;
        $tt_isr += $monto_isr;
        $tt_vacaciones += $monto;
        $tt_devengado += $monto_tt;



    }elseif($cod_pla == "14"){

        echo "<tr>";
        echo "<td>" . $mes_pago . "/" . $year_pago . "</td>"; // FECHA    
        echo "<td>" . number_format($monto_tt, 2) . "</td>"; // VACACIONES
        echo "<td>" . number_format($monto_ss, 2) . "</td>"; // SEG SOCIAL
        echo "<td>" . number_format($monto_isr, 2) . "</td>"; // IMP S/RENTA
        echo "<td>";
        echo $desc_movimiento; // OBSERVACIONES
        if ($concat_otras_deducciones != "") {

            echo "<table  style = 'width:100%; font-size:14px' >";

            echo "<tr>";
            echo "<td>Sueldo Base:</td>";
            echo "<td style = 'color:green; text-align:right' >".number_format($monto, 2)." </td>";
            echo "</tr>";

            echo "<tr>";
            echo "<td>Autorizaciones:</td>";
            echo "<td style = 'color:red; text-align:right' >".$concat_otras_deducciones." </td>";
            echo "</tr>";

            echo "</table>";

        }
        echo "</td>";
        echo "</tr>";

        $tt_seguro += $monto_ss;
        $tt_isr += $monto_isr;
        $tt_vacaciones += $monto;
        $tt_devengado += $monto_tt;




    } else {

        echo "<tr>";
        echo "<td>" . $mes_pago . "/" . $year_pago . "</td>"; // FECHA    
        echo "<td>" . number_format($monto_tt, 2) . "</td>"; // MONTO
        echo "<td>" . number_format($monto_ss, 2) . "</td>"; // SEG SOCIAL
        echo "<td>" . number_format($monto_isr, 2) . "</td>"; // IMP S/RENTA
        echo "<td>";
        echo $desc_movimiento; // OBSERVACIONES
        if ($concat_otras_deducciones != "") {

            echo "<table  style = 'width:100%; font-size:14px' >";

            echo "<tr>";
            echo "<td>Sueldo Base:</td>";
            echo "<td style = 'color:green; text-align:right' >".number_format($monto, 2)." </td>";
            echo "</tr>";

            echo "<tr>";
            echo "<td>Autorizaciones:</td>";
            echo "<td style = 'color:red; text-align:right' >".$concat_otras_deducciones." </td>";
            echo "</tr>";

            echo "</table>";

        }
        echo "</td>";
        echo "</tr>";

        $tt_devengado += $monto_tt;
        $tt_seguro += $monto_ss;
        $tt_isr += $monto_isr;


    }
}

if (count($v_otros_ingresos) > 0) {
    foreach ($v_otros_ingresos as $key => $value) {
        $i = 0;

        while (isset($value[$i])) {
            echo "<tr>";
            echo "<td>" . $value[$i]['mes'] . "/" . $year_pago . "</td>"; // FECHA    
            echo "<td>" . number_format($value[$i]['devengado'], 2) . "</td>"; // MONTO
            echo "<td>" . number_format($value[$i]['seguro'], 2) . "</td>"; // SEG SOCIAL
            echo "<td>" . number_format($value[$i]['impuesto'], 2) . "</td>"; // IMP S/RENTA
            echo "<td>" . $value[$i]['observaciones'] . "<button onclick = 'eliminar_registro(this.value)' name = 'eliminar_registro' id = 'non-printable' value = '" . $value[$i]['id'] . "' style = 'margin-left:10px' class = 'btn btn-outline-danger'>X</button></td>"; // OBSERVACIONES
            echo "</tr>";    
            $i++;


            $tt_devengado += $value[$i]['devengado'];
            $tt_seguro += $value[$i]['seguro'];
            $tt_isr += $value[$i]['impuesto'];    

        }

    }
}

echo "<tr>";

echo "<th>TOTALES</th>"; // FECHA    
echo "<th>" . number_format($tt_devengado, 2) . "</th>"; // MONTO
echo "<th>" . number_format($tt_seguro, 2) . "</th>"; // SEG SOCIAL
echo "<th>" . number_format($tt_isr, 2) . "</th>"; // IMP S/RENTA
echo "<th></th>"; // OBSERVACIONES

echo "</tr>";
echo '</table>';


// INFO EMPLEADO //
///////////////////



/////////////////////////////////////////
///////// TABLA OTROS INGRESOS //////////

$c_no_proveedor =  oci_parse($conn2, "SELECT * FROM CXP_PROVEEDORES WHERE CEDULA = '$cedula'  ");
oci_execute($c_no_proveedor);


$num_proveedor = "";
while ($reg_no_proveedor = oci_fetch_array($c_no_proveedor, OCI_ASSOC + OCI_RETURN_NULLS)) {
    $num_proveedor = $reg_no_proveedor['NO_PROVE'];    
}    


if ($num_proveedor != "") {
    


    $c_otros_otros_ingresos_erp = oci_parse($conn2, "SELECT  to_char(FECHA, 'dd/mm/yyyy') as FECHA, EXTRACT(month FROM FECHA) as MES, MONTO_NOMINAL, BENEFICIARIO, COM FROM CK_TRANSACCIONES WHERE NO_PROVE = '$num_proveedor' AND EXTRACT(year FROM FECHA) = '$year' AND ANULADO IS NULL AND EMITIDO = 'S' ORDER BY FECHA ASC ");
    oci_execute($c_otros_otros_ingresos_erp);

    $v_otros_ingresos_erp = [];
    $i = 0;
    while ($r_otros_otros_ingresos_erp = oci_fetch_array($c_otros_otros_ingresos_erp, OCI_ASSOC + OCI_RETURN_NULLS)) {

        $fecha = $r_otros_otros_ingresos_erp['FECHA'];
        $monto = $r_otros_otros_ingresos_erp['MONTO_NOMINAL'];
        $comentario = $r_otros_otros_ingresos_erp['COM'];
        $v_otros_ingresos_erp[$i] = ['fecha' => $fecha, 'monto' => $monto , 'comentario' => $comentario];
        $i++;
    }    
    

}

///////// TABLA OTROS INGRESOS //////////
/////////////////////////////////////////






?>


<br>
<div style="width: 100%; text-align:center">
    <button class="btn btn-success" data-toggle="modal" data-target="#modal_otros_ingresos">VER ADELANTOS </button>
    <button class="btn btn-success" data-toggle="modal" data-target="#modal_nuevo_registro">AGREGAR NUEVO REGISTRO</button>
</div>
<br>


<div class="modal fade" id="modal_nuevo_registro" tabindex="-1" role="dialog" aria-labelledby="modal_nuevo_registro" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">NUEVO REGISTRO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text" style="min-width:200px" readonly>FECHA: </div>
                    </div>
                    <input type="date" name="fecha_n" id="fecha_n" class="form-control">
                </div>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text" style="min-width:200px" readonly>DEVENGADO: </div>
                    </div>
                    <input type="number" name="devengado_n" id="devengado_n" class="form-control">
                </div>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text" style="min-width:200px" readonly>SEG. SOCIAL: </div>
                    </div>
                    <input type="number" name="seguro_n" id="seguro_n" class="form-control">
                </div>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text" style="min-width:200px" readonly>IMP S/RENTA: </div>
                    </div>
                    <input type="number" name="impuesto_n" id="impuesto_n" class="form-control">
                </div>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text" style="min-width:200px" readonly>OBSERVACIONES: </div>
                    </div>
                    <textarea rows="5" name="observaciones_n" id="observaciones_n" class="form-control"></textarea>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="guardar_registro()">Guardar</button>
            </div>
        </div>
    </div>
</div>






<div class="modal fade" id="modal_otros_ingresos" tabindex="-1" role="dialog" aria-labelledby="modal_otros_ingresos" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ADELANTOS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            <table class="table table-bordered">
            <thead>
                <th>FECHA</th>
                <th>VALOR LPS.</th>
                <th>COMENTARIO</th>
            </thead>
            
            <tbody>

            <?php
            $i = 0;
            while (isset($v_otros_ingresos_erp[$i])) {

                $fecha_otros_r = $v_otros_ingresos_erp[$i]["fecha"]; 
                $monto_otros_r = $v_otros_ingresos_erp[$i]["monto"];
                $desc_otros_r = $v_otros_ingresos_erp[$i]["comentario"]; 
            
                echo "<tr>";
                echo "<td width ='15%' >".$fecha_otros_r."</td>";
                echo "<td width ='15%' >".$monto_otros_r."</td>";
                echo "<td width ='60%' > <textarea id='' name='' style = 'width:100%' readonly> ". $desc_otros_r ." </textarea></td>";
                ?>

                <td width ='10%' ><button class = 'btn btn-primary' onclick = "registrar_otro_ingreso_erp('<?php echo $fecha_otros_r ?>', '<?php echo $monto_otros_r ?>', '<?php echo $desc_otros_r ?>')" >Agregar</button></td>
                <?php
                echo "</tr>";

                $i++;
            }
            
            ?>

            </tbody>
            </table>

            </div>
        </div>
    </div>
</div>







<script type="text/javascript">
    document.getElementById("identidad").value = "<?php echo $cedula; ?>";
    document.getElementById("ocupacion").value = "<?php echo $puesto; ?>";
    document.getElementById("fecha_ingreso").value = "<?php echo $fecha_ingreso; ?>";
    document.getElementById("fecha_nacimiento").value = "<?php echo $fecha_nacimiento; ?>";
    $(".div_wait").fadeOut("fast");



    function guardar_registro() {

        fecha = document.getElementById('fecha_n').value;
        devengado = document.getElementById('devengado_n').value;
        seguro = document.getElementById('seguro_n').value;
        impuesto = document.getElementById('impuesto_n').value;
        observaciones = document.getElementById('observaciones_n').value;
        observaciones = observaciones.replace(/ /g,'!');

        identidad = document.getElementById('identidad').value;
        cod_empleado = document.getElementById('cod_empleado').value;

        token = Math.random();

        $(".div_wait").fadeIn("fast");
        token = Math.random();
        consulta = 'control_individual_empleados_save_js.php?accion=GUARDAR&fecha=' + fecha + "&devengado=" + devengado + "&seguro=" + seguro + "&impuesto=" + impuesto + "&observaciones=" + observaciones + "&identidad=" + identidad + "&cod_empleado=" + cod_empleado + "&token=" + token;
        $("#respuesta2").load(consulta);


    }



    function registrar_otro_ingreso_erp(fecha, devengado, observaciones) {

        fecha = fecha;
        devengado = devengado;
        seguro = 0;
        impuesto = 0;
        observaciones = observaciones;
        observaciones = observaciones.replace(/ /g,'!');

        identidad = document.getElementById('identidad').value;
        cod_empleado = document.getElementById('cod_empleado').value;

        token = Math.random();

        $(".div_wait").fadeIn("fast");
        token = Math.random();
        consulta = 'control_individual_empleados_save_js.php?accion=GUARDAR&fecha=' + fecha + "&devengado=" + devengado + "&seguro=" + seguro + "&impuesto=" + impuesto + "&observaciones=" + observaciones + "&identidad=" + identidad + "&cod_empleado=" + cod_empleado + "&token=" + token;
        $("#respuesta2").load(consulta);
        $(".div_wait").fadeOut("fast");

    }




    function eliminar_registro(id) {

        token = Math.random();

        $(".div_wait").fadeIn("fast");
        token = Math.random();
        consulta = 'control_individual_empleados_save_js.php?id=' + id + '&accion=ELIMINAR&token=' + token;
        $("#respuesta2").load(consulta);

    }


</script>