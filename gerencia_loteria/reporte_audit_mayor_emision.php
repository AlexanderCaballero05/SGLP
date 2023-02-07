<?php
require '../../template/header.php';

$sorteos = mysqli_query($conn, "SELECT * FROM sorteos_mayores ORDER BY id DESC");

?>


<form method="POST">

    <section style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
        <br>
        <h2 align="center" style="color:black; ">REPORTE DE EMISION DE LOTERIA MAYOR</h2>
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
                        echo "<option value = '" . $sorteo['id'] . "'>" . $sorteo['id'] . " -- Fecha " . $sorteo['fecha_sorteo'] . " -- " . "</option>";
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

        $billete_inicial = "00000";


        if ($result != null) {
            while ($row = mysqli_fetch_array($result)) {
                $sorteo = $row['id'];
                $fecha_sorteo = $row['fecha_sorteo'];
                $fecha_sorteo_v = $row['fecha_vencimiento'];
                $billete_final = $row['cantidad_numeros'] - 1;
            }

        }




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
                        <th>BILLETE INICIAL</th>
                        <th>BILLETE FINAL</th>
                    </tr>
                </thead>
                <tbody>

                <tr>
                <td><?php echo $billete_inicial;?></td>
                <td><?php echo $billete_final;?></td>
                </tr>

                </tbody>
            </table>
        </div>


    <?php

        echo "</div>";

    }

    ?>

</form>