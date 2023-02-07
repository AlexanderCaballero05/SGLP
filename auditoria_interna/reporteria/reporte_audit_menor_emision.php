<?php
require '../../template/header.php';

$sorteos = mysqli_query($conn, "SELECT * FROM sorteos_menores ORDER BY no_sorteo_men DESC");

?>


<form method="POST">

    <section style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
        <br>
        <h2 align="center" style="color:black; ">REPORTE DE EMISION DE LOTERIA MENOR</h2>
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
            $masc = strlen($series);
        }




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
                        <th>Numeros</th>
                        <th>Serie Inicial</th>
                        <th>Serie Final</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>

                    <?php


                    $i = 0;
                    $n_inicial = 0;
                    $n_final = 0;
                    $registro = $desde_registro;
                    $registro_inicial = $desde_registro;
                    $registro_final = $registro_inicial + $series;

        $tt_cantidad = 0;

                    while ($i < 10) {

                        $n_inicial = $i * 10;
                        $n_final = $n_inicial + 9;


                        $n_inicial = str_pad($n_inicial, 2, '0', STR_PAD_LEFT);
                        $n_final = str_pad($n_final, 2, '0', STR_PAD_LEFT);

                        $cantidad = ($series + 1) * 10;
                        echo "<tr>
<td>" . $n_inicial . " - " . $n_final . "</td>
<td>0000</td>
<td>" . $series . "</td>
<td>" . number_format($cantidad) . "</td>
</tr>";

                        $i = $i + 1;

                        $tt_cantidad += $cantidad;
                    }


            
                    ?>
                <tr>
                <th colspan="3">TOTAL</th>
                <th><?php echo number_format($tt_cantidad) ; ?></th>
                </tr>
                </tbody>
            </table>
        </div>


    <?php

        echo "</div>";
    }

    ?>

</form>