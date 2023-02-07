<?php
require '../../template/header.php';

$sorteos = mysqli_query($conn, "SELECT * FROM sorteos_mayores ORDER BY no_sorteo_may DESC");

?>


<form method="POST">

    <section style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
        <br>
        <h2 align="center" style="color:black; ">REPORTE DE COMBINACION DE PREMIOS DE LOTERIA MAYOR</h2>
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
                        echo "<option value = '" . $sorteo['id'] . "'>" . $sorteo['no_sorteo_may'] . " -- Fecha " . $sorteo['fecha_sorteo'] . " -- " . "</option>";
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

        $result = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo'");

        if ($result != null) {
            while ($row = mysqli_fetch_array($result)) {
                $sorteo = $row['no_sorteo_may'];
                $fecha_sorteo = $row['fecha_sorteo'];
                $fecha_sorteo_v = $row['fecha_vencimiento'];
            }
        }


        $c_combinacion = mysqli_query($conn, "SELECT  b.id, a.tipo_premio, a.monto, a.desc_premio, b.descripcion_premios, a.tipo_premio FROM sorteos_mayores_premios as a INNER JOIN premios_mayores as b ON a.premios_mayores_id = b.id  WHERE sorteos_mayores_id = '$sorteo' ORDER BY a.monto DESC  ");


        echo "<div style = 'page-break-inside:avoid'>";
        echo "<div class = 'alert alert-info' >";
        echo '<h3 align="center">Patronato Nacional de la Infancia</h3>
        <p align="center">Reporte de Auditoria En la Emisión de Loteria Mayor<br>
        Sorteo # ' . $sorteo . ' del ' . $fecha_sorteo . ' <br> Con fecha de caducidad ' . $fecha_sorteo_v . '</p>
        </div>';

    ?>


        <div style='page-break-after: always;'>
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>PREMIO</th>
                        <th>DESCRIPCION</th>
                        <th>MONTO</th>
                        <th>TIPO DE PREMIO</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $total = 0;
                    while ($reg_combinacion = mysqli_fetch_array($c_combinacion)) {
                        echo "<tr>";
                        if ($reg_combinacion['id'] == 1) {
                            echo "<td>".$reg_combinacion['descripcion_premios']." (PREMIO MAYOR)</td>";                            
                        }else{
                            echo "<td>".$reg_combinacion['descripcion_premios']."</td>";                            
                        }
                        echo "<td>".$reg_combinacion['desc_premio']."</td>";
                        echo "<td>".$reg_combinacion['monto']."</td>";
                        echo "<td>".$reg_combinacion['tipo_premio']."</td>";
                        echo "</tr>";

                        $total +=  $reg_combinacion['monto'];
                    }


                    ?>

                    <tr>
                    <th colspan="2">TOTAL</th>
                    <th><?php echo number_format($total); ?></th>
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