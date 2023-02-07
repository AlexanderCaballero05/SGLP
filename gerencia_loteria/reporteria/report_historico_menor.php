<?php 
require("../../template/header.php"); 
date_default_timezone_set('America/Tegucigalpa');
?> 




<section style="background-color:#ededed;">
<br>
<h3 align="center"><b>HISTORICO DE NUMEROS PREMIADOS DE LOTERIA MENOR</b></h3>
<br>
</section>



<form method="POST">
	

<br>
<a class="btn btn-info" style="width:100%" role="button" data-toggle="collapse" href="#collapse3" aria-expanded="false" aria-controls="collapse3" id="non-printable">
<h3> Parametros de Seleccion </h3>
</a>

<div  class="collapse" style = "width:100%"  id="collapse3">
<div class="well" align="center">

<table style = "width:75%" class="table table-bordered">
  <tr>
    <th>Fecha Inicial</th>
    <th>Fecha Final</th>
    <th>Accion</th>
  </tr>
  <tr>
    <td align="center">

        <input type="date" class="form-control" name="fecha_inicial" id="fecha_incial">
    
    </td>

    <td align="center">

        <input type="date" class="form-control" name="fecha_final" id="fecha_final">

    </td>

    <td align="center">
        <input  type="submit" name="seleccionar" class="btn btn-primary" value="Seleccionar"> 
    </td>
  </tr>
</table>
</div>
</div>

</form>

<?php 


if (isset($_POST['seleccionar'])) {

    $fecha_inicial = $_POST['fecha_inicial'];
    $fecha_final = $_POST['fecha_final'];


    if ($fecha_inicial == "" OR $fecha_final == "") {

        $sorteos = mysqli_query($conn, "SELECT a.sorteos_menores_id, a.numero_premiado_menor, b.fecha_sorteo FROM  view_sorteos_menores_premios_historico as a INNER JOIN view_sorteos_menores_historico as b ON a.sorteos_menores_id = b.no_sorteo_men WHERE premios_menores_id = '1'   ORDER BY a.sorteos_menores_id DESC ");
        
    }else{

        $sorteos = mysqli_query($conn, "SELECT a.sorteos_menores_id, a.numero_premiado_menor, b.fecha_sorteo FROM  view_sorteos_menores_premios_historico as a INNER JOIN view_sorteos_menores_historico as b ON a.sorteos_menores_id = b.no_sorteo_men WHERE premios_menores_id = '1' AND b.fecha_sorteo >= '$fecha_inicial' AND b.fecha_sorteo <= '$fecha_final'  ORDER BY a.sorteos_menores_id DESC ");
        
    }

    ?>




<br>

<div class="row">

    <div class="col ">

        <div class="card" style="margin-left: 10px; margin-right: 10px">
        <div class="card-header bg-secondary text-white">
            <h3 >
                Periodo  <?php echo $fecha_inicial; ?>  -  <?php echo $fecha_final; ?>
            </h3>
        </div>	

        <div class="card-body">

        <table id="table_id1" class = 'table table-bordered table-hover'>
            <thead>
                <tr>
                    <th>Sorteo</th>
                    <th>Num. Ganador</th>
                    <th>Fecha de Captura</th>
                </tr>
            </thead>

            <tbody>

        <?php 

        $tt = 0;

        $v_numeros = [];

        while ($reg_sorteo = mysqli_fetch_array($sorteos)) {

            if (!isset($v_numeros[$reg_sorteo['numero_premiado_menor']])) {
                
                $v_numeros[$reg_sorteo['numero_premiado_menor']] = 1;
            
            } else {

                $v_numeros[$reg_sorteo['numero_premiado_menor']]++;

            }

            echo "<tr>";
            echo "<td>".$reg_sorteo['sorteos_menores_id']."</td>";
            echo "<td>".$reg_sorteo['numero_premiado_menor']."</td>";
            echo "<td>".$reg_sorteo['fecha_sorteo']."</td>";
            echo "</tr>";

            $tt++;

        }


        ?>

                
            </tbody>

        </table>

        </div>
        </div>


    </div>



    <div class="col">


    <div class="card" style="margin-left: 10px; margin-right: 10px">
        <div class="card-header bg-secondary text-white">
            <h3 >
                Resumen
            </h3>
        </div>	

        <div class="card-body">


        <table id="table_id2" class="table table-bordered">

            <thead>
                <tr>
                    <th>Numero</th>
                    <th># Jugado</th>
                    <th>% Jugado</th>
                </tr>
            </thead>

            <?php 
                $i = 0;
                $tt_p = 0;
                while ($i <= 99) {

                    if ($i < 10) {
                        $i = "0". $i;
                    }

                    $porcentaje = 0;
                    $veces = 0;
                    if ( isset($v_numeros[$i]) ) {
                        $veces = $v_numeros[$i];
                        $porcentaje = $v_numeros[$i] / $tt;
                        $porcentaje *= 100;
                        $porcentaje = round($porcentaje, 2);

                        echo "<tr>";
                        echo "<td>".$i."</td>";
                        echo "<td>".$veces."</td>";
                        echo "<td>". $porcentaje."%</td>";
                        echo "</tr>";
    
                    }
    
    


                    $tt_p += $porcentaje;
                    $i++;

                }
                
            ?>
            

            <tfoot>
                <tr>
                    <th>TOTALES</th>
                    <th><?php echo $tt; ?></th>
                    <th><?php echo round($tt_p); ?>%</th>
                </tr>
            </tfoot>

        </table>

        </div>
        </div>

    </div>
    

</div>


    <?php


}






?>
