<?php
require "../../template/header.php";
date_default_timezone_set('America/Tegucigalpa');
?>


<form method="POST">


    <section style="background-color:#ededed;">
        <br>
        <h3 align="center"><b>REPORTE DE CONSULTA DE HISTORICO DE COMPRA POR PERSONA</b></h3>
        <br>
    </section>



    <a id='non-printable' style="width:100%" class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">Seleccion de Parametros</a>

    <div class="card collapse" id="collapse1">
        <div class="card-body">


<div class="alert alert-info">Para desplegar los compradores de loteria que no esten registrados como vendedores autorizados deberá dejar en blanco tanto el campo de identidad como nombre.</div>


            <div class="row">


                <div class=" col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Identidad:</div>
                        </div>
                        <input type="text" name="identidad" id="identidad" class="form-control">
                    </div>


                </div>


                <div class=" col">

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Del Mes:</div>
                        </div>

                        <select class="form-control" name="del_mes" id='del_mes' ">
		<option value="">Seleccione uno</option>
<?php

$meses = mysqli_query($conn, "SELECT CONCAT( MONTH(fecha_venta), '-', YEAR(fecha_venta) ) as fecha  FROM transaccional_ventas_general  GROUP BY YEAR(fecha_venta) DESC , MONTH(fecha_venta) DESC ");

while ($r_meses = mysqli_fetch_array($meses)) {
    echo '<option value = "' . $r_meses['fecha'] . '">  ' . $r_meses['fecha'] . '</option>';
}

?>
</select>
</div>


			</div>



                    </div>






                    <div class=" row" style="margin-top: 15px;">



                            <div class="col">

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Nombre:</div>
                                    </div>
                                    <input type="text" name="nombre" id="nombre" class="form-control">
                                </div>


                            </div>

                            <div class=" col" class="col">

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Al Mes:</div>
                                    </div>

                                    <select class="form-control" name="al_mes" id='al_mes' ">
							<option value="">Seleccione uno</option>
<?php

$meses = mysqli_query($conn, "SELECT CONCAT( MONTH(fecha_venta), '-', YEAR(fecha_venta) ) as fecha  FROM transaccional_ventas_general  GROUP BY YEAR(fecha_venta) DESC , MONTH(fecha_venta) DESC ");

while ($r_meses = mysqli_fetch_array($meses)) {
    echo '<option value = "' . $r_meses['fecha'] . '">  ' . $r_meses['fecha'] . '</option>';
}

?>
</select>
</div>


                        </div>
                    </div>




                    <div class=" row" style="margin-top:15px">
                                        <div class="col" style="text-align:center">
                                            <button type="submit" name="seleccionar" id="seleccionar" class="btn btn-primary">Seleccionar</button>
                                        </div>
                                </div>



                            </div>
                    </div>



                    <?php

                    if (isset($_POST['seleccionar'])) {

                        $mes = $_POST['del_mes'];
                        $mes2 = $_POST['al_mes'];
                        $identidad = $_POST['identidad'];
                        $nombre = $_POST['nombre'];


                        $v_mes = explode("-", $mes);

                        $num_mes = $v_mes[0];
                        $year = $v_mes[1];


                        $v_mes2 = explode("-", $mes2);

                        $num_mes2 = $v_mes2[0];
                        $year2 = $v_mes2[1];
                        
                        $fecha_1 = $year."-".$num_mes."-01";
                        $fecha_2 = $year2."-".$num_mes2."-31";


                        echo "<input type = 'hidden' name = 'mes_o' value = '$mes' >";
                        echo "<input type = 'hidden' name = 'mes_o_2' value = '$mes2' >";
                        echo "<input type = 'hidden' name = 'identidad_o' value = '$identidad' >";
                        echo "<input type = 'hidden' name = 'nombre_o' value = '$nombre' >";

                        echo "<br>";



                        if ($identidad != '') {
                            $consulta = mysqli_query($conn, "SELECT a.id_sorteo, a.cantidad, a.fecha_venta, a.identidad_comprador, a.nombre_comprador, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a INNER JOIN distribucion_menor_bolsas_banco as b ON a.id_seccional = b.id_seccional WHERE estado_venta = 'APROBADO' AND cod_producto = '3' AND DATE(fecha_venta) BETWEEN '$fecha_1' AND  '$fecha_2'  AND  identidad_comprador = '$identidad' GROUP BY  a.cod_factura_recaudador ORDER BY fecha_venta ASC ");


                        }elseif($nombre != ''){
                            $consulta = mysqli_query($conn, "SELECT a.id_sorteo, a.cantidad, a.fecha_venta, a.identidad_comprador, a.nombre_comprador, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a INNER JOIN distribucion_menor_bolsas_banco as b ON a.id_seccional = b.id_seccional WHERE estado_venta = 'APROBADO' AND cod_producto = '3' AND DATE(fecha_venta) BETWEEN '$fecha_1' AND  '$fecha_2'  AND  nombre_comprador LIKE '$nombre' GROUP BY  a.cod_factura_recaudador ORDER BY fecha_venta ASC ");

                        }else{

                            $consulta = mysqli_query($conn, "SELECT a.id_sorteo, a.cantidad, a.fecha_venta, a.identidad_comprador, a.nombre_comprador, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a INNER JOIN distribucion_menor_bolsas_banco as b ON a.id_seccional = b.id_seccional WHERE estado_venta = 'APROBADO' AND cod_producto = '3' AND DATE(fecha_venta) BETWEEN '$fecha_1' AND  '$fecha_2'  AND  identidad_comprador NOT IN (SELECT identidad FROM vendedores ) GROUP BY  a.cod_factura_recaudador ORDER BY fecha_venta ASC ");

                        }

                        echo mysqli_error($conn);
                        $tt = 0;
                        if (mysqli_num_rows($consulta) > 0) {
                            
                            ?>

                            <table class="table table-bordered">
                            <tr>
                            <th>Sorteo</th>
                            <th>Identidad</th>
                            <th>Nombre</th>
                            <th>Cantidad en compra</th>
                            <th>Fecha de la compra</th>
                            <th>Departamento</th>
                            <th>Municipio</th>
                            <th>Seccional</th>
                            </tr>

                            <?php 
                            while ($reg_consulta = mysqli_fetch_array($consulta)) {
                                echo "<tr>";
                                echo "<td>".$reg_consulta['id_sorteo']."</td>";
                                echo "<td>".$reg_consulta['identidad_comprador']."</td>";
                                echo "<td>".$reg_consulta['nombre_comprador']."</td>";
                                echo "<td>".$reg_consulta['cantidad']."</td>";
                                echo "<td>".$reg_consulta['fecha_venta']."</td>";
                                echo "<td>".$reg_consulta['departamento']."</td>";
                                echo "<td>".$reg_consulta['municipio']."</td>";
                                echo "<td>".$reg_consulta['nombre_seccional']."</td>";
                                echo "</tr>";

                                $tt += $reg_consulta['cantidad'];
                            }
                            ?>
                            </table>

                            <?php

                            if ($identidad != "") {
                                $c_tabla_vendedores = mysqli_query($conn, "SELECT * FROM vendedores WHERE identidad = '$identidad'  ");                                

                                if (mysqli_num_rows($c_tabla_vendedores) > 0) {
                                    $ob_vendedor = mysqli_fetch_object($c_tabla_vendedores);
                                    echo "<div class = 'alert alert-info'>La persona consultada esta registrada como vendedor autorizado con la siguiente información: <br>
                                        Codigo de vendedor: ".$ob_vendedor->asociacion."-".$ob_vendedor->seccional."-".$ob_vendedor->codigo."<br>
                                        Asociacion: ".$ob_vendedor->asociacion."<br>
                                        Fecha de registro o edición: ".$ob_vendedor->fecha_edicion."<br>
                                        </div>";
                                }else{
                                    echo "<div class = 'alert alert-info'> La persona consultada no esta registrada como vendedor de loteria </div>";
                                }
    
                            }else if ($nombre != ""){

                                $c_tabla_vendedores = mysqli_query($conn, "SELECT * FROM vendedores WHERE nombre = '$nombre'  ");                                                                

                                if (mysqli_num_rows($c_tabla_vendedores) > 0) {
                                    $ob_vendedor = mysqli_fetch_object($c_tabla_vendedores);
                                    echo "<div class = 'alert alert-info'>La persona consultada esta registrada como vendedor autorizado con la siguiente información: <br>
                                        Codigo de vendedor: ".$ob_vendedor->asociacion."-".$ob_vendedor->seccional."-".$ob_vendedor->codigo."<br>
                                        Asociacion: ".$ob_vendedor->asociacion."<br>
                                        Fecha de registro o edición: ".$ob_vendedor->fecha_edicion."<br>
                                        </div>";
                                }else{
                                    echo "<div class = 'alert alert-info'> La persona consultada no esta registrada como vendedor de loteria </div>";
                                }
    
                            }



                            echo '<div class = "row">
                            <div class = "col"></div>
                            <div class = "col"><button type = "submit" class = "btn btn-success" name = "generar_excel">Exportar a Excel</button></div>
                            <div class = "col"></div>
                          </div>';


                        }


                    }


                    ?>

</form>



















<?php

if (isset($_POST['generar_excel'])) {

    require_once '../../assets/phpexcel/Classes/PHPExcel/IOFactory.php';


    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);



    $mes = $_POST['mes_o'];
    $mes2 = $_POST['mes_o_2'];
    $identidad = $_POST['identidad_o'];
    $nombre = $_POST['nombre_o'];


    $v_mes = explode("-", $mes);

    $num_mes = $v_mes[0];
    $year = $v_mes[1];


    $v_mes2 = explode("-", $mes2);

    $num_mes2 = $v_mes2[0];
    $year2 = $v_mes2[1];
    
    $fecha_1 = $year."-".$num_mes."-01";
    $fecha_2 = $year2."-".$num_mes2."-31";



    if ($identidad != '') {
        $consulta = mysqli_query($conn, "SELECT a.id_sorteo, a.cantidad, a.fecha_venta, a.identidad_comprador, a.nombre_comprador, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a INNER JOIN distribucion_menor_bolsas_banco as b ON a.id_seccional = b.id_seccional WHERE estado_venta = 'APROBADO' AND cod_producto = '3' AND DATE(fecha_venta) BETWEEN '$fecha_1' AND  '$fecha_2'  AND  identidad_comprador = '$identidad' GROUP BY  a.cod_factura_recaudador ORDER BY fecha_venta ASC ");


    }elseif($nombre != ''){
        $consulta = mysqli_query($conn, "SELECT a.id_sorteo, a.cantidad, a.fecha_venta, a.identidad_comprador, a.nombre_comprador, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a INNER JOIN distribucion_menor_bolsas_banco as b ON a.id_seccional = b.id_seccional WHERE estado_venta = 'APROBADO' AND cod_producto = '3' AND DATE(fecha_venta) BETWEEN '$fecha_1' AND  '$fecha_2'  AND  nombre_comprador LIKE '$nombre' GROUP BY  a.cod_factura_recaudador ORDER BY fecha_venta ASC ");

    }else{

        $consulta = mysqli_query($conn, "SELECT a.id_sorteo, a.cantidad, a.fecha_venta, a.identidad_comprador, a.nombre_comprador, b.nombre_seccional, b.departamento, b.municipio FROM transaccional_ventas_general as a INNER JOIN distribucion_menor_bolsas_banco as b ON a.id_seccional = b.id_seccional WHERE estado_venta = 'APROBADO' AND cod_producto = '3' AND DATE(fecha_venta) BETWEEN '$fecha_1' AND  '$fecha_2'  AND  identidad_comprador NOT IN (SELECT identidad FROM vendedores ) GROUP BY  a.cod_factura_recaudador ORDER BY fecha_venta ASC ");

    }

    $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Sorteo');
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Identidad');
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Nombre');
    $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Cantidad en compra');
    $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Fecha de la compra');
    $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Departamento');
    $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Municipio');
    $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Seccional');


    echo mysqli_error($conn);
    $tt = 0;
    if (mysqli_num_rows($consulta) > 0) {
        
        $row = 2;
        $i = 1;
        $tt_bolsas = 0;
    
        while ($reg_ventas = mysqli_fetch_array($consulta)) {
    
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $reg_ventas['id_sorteo']);

            $objPHPExcel->getActiveSheet()->getStyle('B' . $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $reg_ventas['identidad_comprador']);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $row)->getNumberFormat()->setFormatCode("0000000000000");


            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $reg_ventas['nombre_comprador']);
    
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, $reg_ventas['cantidad']);
    
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $row, $reg_ventas['fecha_venta']);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $row, $reg_ventas['departamento']);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $row, $reg_ventas['municipio']);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $row, $reg_ventas['nombre_seccional']);
    
            $row++;
            $i++;
        }

        $row++;

        if ($identidad != "") {
            $c_tabla_vendedores = mysqli_query($conn, "SELECT * FROM vendedores WHERE identidad = '$identidad'  ");                                

            if (mysqli_num_rows($c_tabla_vendedores) > 0) {
                $ob_vendedor = mysqli_fetch_object($c_tabla_vendedores);

                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, 'La persona consultada esta registrada como vendedor autorizado con la siguiente información:');
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':K' . $row);
            
                $row++;
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, "Codigo de vendedor: ".$ob_vendedor->asociacion."-".$ob_vendedor->seccional."-".$ob_vendedor->codigo."");
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':G' . $row);
                $row++;
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, "Asociacion: ".$ob_vendedor->asociacion."");
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':G' . $row);
                $row++;
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, "Fecha de registro o edición: ".$ob_vendedor->fecha_edicion."");
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':G' . $row);

            }else{
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, 'La persona consultada no esta registrada como vendedor de loteria');
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':W' . $row);
            }

        }else if ($nombre != ""){

            $c_tabla_vendedores = mysqli_query($conn, "SELECT * FROM vendedores WHERE nombre = '$nombre'  ");                                                                

            if (mysqli_num_rows($c_tabla_vendedores) > 0) {
                $ob_vendedor = mysqli_fetch_object($c_tabla_vendedores);


                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, 'La persona consultada esta registrada como vendedor autorizado con la siguiente información:');
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':W' . $row);
            
                $row++;
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, "Codigo de vendedor: ".$ob_vendedor->asociacion."-".$ob_vendedor->seccional."-".$ob_vendedor->codigo."");
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':G' . $row);
                $row++;
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, "Asociacion: ".$ob_vendedor->asociacion."");
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':G' . $row);
                $row++;
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, "Fecha de registro o edición: ".$ob_vendedor->fecha_edicion."");
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':G' . $row);

            }else{
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, 'La persona consultada no esta registrada como vendedor de loteria');
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':W' . $row);
            }

        }



    }





    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=\"Lista_compradores.xlsx\"");
    header("Cache-Control: max-age=0");

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    ob_clean();
    $objWriter->save("php://output");



}

?>