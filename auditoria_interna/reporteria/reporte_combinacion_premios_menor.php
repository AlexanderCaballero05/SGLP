<?php
require '../../template/header.php';

$sorteos = mysqli_query($conn, "SELECT * FROM sorteos_menores ORDER BY no_sorteo_men DESC");

?>


<form method="POST">

    <section style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
        <br>
        <h2 align="center" style="color:black; ">REPORTE DE COMBINACION DE PREMIOS DE LOTERIA MENOR</h2>
        <br>
    </section>

    <br>

    <div class="card" id="non-printable">
        <div class="card-header" align="center">
            <div class="input-group" style="margin:10px 0px 10px 0px; width: 50%">
                <div class="input-group-prepend"><span class="input-group-text">Seleccione un sorteo: </span></div>
                <select class="form-control" name="sorteo">
                    <?php
                    while ($sorteo = mysqli_fetch_array($sorteos)) {
                        echo "<option value = '" . $sorteo['id'] . "'>" . $sorteo['no_sorteo_men'] . " -- Fecha " . $sorteo['fecha_sorteo'] . " -- " . "</option>";
                    }
                    ?>
                </select>
                <input type="submit" name="seleccionar" class="btn btn-primary" value="Seleccionar">
            </div>
        </div>
        <br>
    </div>


    <?php

    if (isset($_POST['seleccionar'])) {

        $id_sorteo = $_POST['sorteo'];

        $result = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE id = '$id_sorteo'");

        if ($result != null) {
            while ($row = mysqli_fetch_array($result)) {
                $sorteo = $row['no_sorteo_men'];
                $fecha_sorteo = $row['fecha_sorteo'];
                $fecha_sorteo_v = $row['vencimiento_sorteo'];
                $series = $row['series'] - 1;
                $desde_registro = $row['desde_registro'];
                $descripcion = $row['descripcion_sorteo_men'];
            }
        }


        $c_combinacion = mysqli_query($conn, "SELECT a.tipo_premio, a.monto, b.descripcion_premios, b.tipo_serie, b.clasificacion FROM sorteos_menores_premios as a INNER JOIN premios_menores as b ON a.premios_menores_id = b.id  WHERE sorteos_menores_id = '$sorteo' ORDER BY  a.premios_menores_id, a.monto DESC  ");


        echo "<div style = 'page-break-inside:avoid'>";
        echo "<div class = 'alert alert-info' >";
        echo '<h3 align="center">Patronato Nacional de la Infancia</h3>
        <p align="center">Reporte de Auditoria En la Emisión de Loteria Menor<br>
        Sorteo # ' . $sorteo . ' del ' . $fecha_sorteo . ' <br> Con fecha de caducidad ' . $fecha_sorteo_v . '</p>
        </div>';

    ?>


        <div style='page-break-after: always;'>
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>DESCRIPCION PREMIO</th>
                        <th>MONTO</th>
                        <th>TIPO DE PREMIO</th>
                        <th>CLASIFICACION</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $total = 0;
                    while ($reg_combinacion = mysqli_fetch_array($c_combinacion)) {
                        echo "<tr>";
                        if ($reg_combinacion['descripcion_premios'] == 'SERIE GANADORA') {                            
                            echo "<td>".$reg_combinacion['descripcion_premios']." (SERIE DE DERECHO)</td>";
                        }else{
                            echo "<td>".$reg_combinacion['descripcion_premios']."</td>";
                        }
                        echo "<td>". number_format($reg_combinacion['monto']) ."</td>";
                        echo "<td>". $reg_combinacion['tipo_serie'] ."</td>";
                        echo "<td>". $reg_combinacion['clasificacion'] ."</td>";
                        echo "</tr>";

                        $total +=  $reg_combinacion['monto'];
                    }

                
                    ?>

                    <tr>
                    <th>TOTAL</th>
                    <th> <?php echo number_format($total) ?></th>
                    <th></th>
                    <th></th>
                    </tr>

                </tbody>
            </table>
        </div>


    <?php

        echo "</div>";
    }

    ?>

</form>