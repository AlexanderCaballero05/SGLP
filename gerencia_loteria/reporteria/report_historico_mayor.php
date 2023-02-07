<?php 
require("../../template/header.php"); 
date_default_timezone_set('America/Tegucigalpa');
?> 




<section style="background-color:#ededed;">
<br>
<h3 align="center"><b>HISTORICO DE NUMEROS PREMIADOS DE LOTERIA MAYOR</b></h3>
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


    $conn = new mysqli('192.168.15.248', 'domingo.figueroa', 'Dfigueroa2019**', 'pani');


    if ($fecha_inicial == "" OR $fecha_final == "") {

        $sorteos = mysqli_query($conn, "SELECT a.sorteos_mayores_id ,a.numero_premiado_mayor, b.fecha_sorteo, a.monto FROM  sorteos_mayores_premios as a INNER JOIN sorteos_mayores as b ON a.sorteos_mayores_id = b.no_sorteo_may WHERE a.premios_mayores_id = '1' AND a.respaldo != 'SI'  AND a.numero_premiado_mayor != '' AND monto > '90000' ORDER BY a.sorteos_mayores_id DESC  ");
        
    }else{

        $sorteos = mysqli_query($conn, "SELECT a.sorteos_mayores_id ,a.numero_premiado_mayor, b.fecha_sorteo, a.monto FROM  sorteos_mayores_premios as a INNER JOIN sorteos_mayores as b ON a.sorteos_mayores_id = b.no_sorteo_may WHERE a.premios_mayores_id = '1' AND  a.respaldo != 'SI' AND a.numero_premiado_mayor != '' AND monto > '90000' AND b.fecha_sorteo >= '$fecha_inicial' AND b.fecha_sorteo <= '$fecha_final'  ORDER BY a.sorteos_mayores_id DESC ");
        
    }

    echo mysqli_error($conn);

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
                    <th>Valor Lps.</th>
                    <th>Fecha de Captura</th>
                </tr>
            </thead>

            <tbody>

        <?php 

        $tt = 0;

        $v_numeros = [];

        while ($reg_sorteo = mysqli_fetch_array($sorteos)) {


            echo "<tr>";
            echo "<td>".$reg_sorteo['sorteos_mayores_id']."</td>";
            echo "<td>".$reg_sorteo['numero_premiado_mayor']."</td>";
            echo "<td>".number_format($reg_sorteo['monto'], 2) ."</td>";
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
    

</div>


    <?php


}


?>