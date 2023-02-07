<?php

require("../../template/header.php");
$recaudadores = mysqli_query($conn, " SELECT * FROM empresas WHERE estado = 'ACTIVO' ");
date_default_timezone_set('America/Tegucigalpa');

?>

<script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/Chart.bundle.js"></script>
<script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/Chart.bundle.min.js"></script>
<script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/Chart.js"></script>
<script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/Chart.min.js"></script>
<script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/moment.min.js"></script>



<body>
  <form method="POST">



    <section style="background-color:#ededed;">
      <br>
      <h2 align="center" style="color:black;">
        <b>INGRESOS CON CONTRAPRESTACION NICSP 9

          <?php

          if (isset($_POST['seleccionar'])) {

            $fecha_i = $_POST['fecha_inicial'];
            $fecha_i = date("Y-m-d", strtotime($fecha_i));
            $fecha_f = $_POST['fecha_final'];
            $fecha_f = date("Y-m-d", strtotime($fecha_f));

            echo "<p align = 'center'>Del  " . $fecha_i . " Al " . $fecha_f . "</p>";
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
            <input type="text" placeholder="Fecha Inicial" name="fecha_inicial" id="fecha_inicial" class="form-control">
          </div>


          <div class="input-group" style="margin:10px 0px 10px 0px;">
            <input type="text" placeholder="Fecha Inicial" name="fecha_final" id="fecha_final" class="form-control">
          </div>


          <input type="submit" name="seleccionar" class="btn btn-primary" value="Seleccionar" style="width: 100%">


          <script>
            $('#fecha_inicial').datepicker({
              locale: 'es-es',
              format: 'yyyy-mm-dd',
              uiLibrary: 'bootstrap4'
            });

            $('#fecha_final').datepicker({
              locale: 'es-es',
              format: 'yyyy-mm-dd',
              uiLibrary: 'bootstrap4'
            });
          </script>


        </div>
      </div>
    </div>






    <?php

    function funcion_nombre_mes($numero_mes)
    {

      if ($numero_mes == 1) {
        $nombre_mes = "Enero";
      } elseif ($numero_mes == 2) {
        $nombre_mes = "Febrero";
      } elseif ($numero_mes == 3) {
        $nombre_mes = "Marzo";
      } elseif ($numero_mes == 4) {
        $nombre_mes = "Abril";
      } elseif ($numero_mes == 5) {
        $nombre_mes = "Mayo";
      } elseif ($numero_mes == 6) {
        $nombre_mes = "Junio";
      } elseif ($numero_mes == 7) {
        $nombre_mes = "Julio";
      } elseif ($numero_mes == 8) {
        $nombre_mes = "Agosto";
      } elseif ($numero_mes == 9) {
        $nombre_mes = "Septiembre";
      } elseif ($numero_mes == 10) {
        $nombre_mes = "Octubre";
      } elseif ($numero_mes == 11) {
        $nombre_mes = "Noviembre";
      } elseif ($numero_mes == 12) {
        $nombre_mes = "Diciembre";
      }

      return $nombre_mes;
    }



    if (isset($_POST['seleccionar'])) {


      $concatenado_porcentaje_venta = '';
      $concatenado_asociaciones = '';


      $fecha = date('Y-m-d');
      echo "<p align = 'right'>" . $fecha . "</p>";

      $fecha_i = $_POST['fecha_inicial'];
      $fecha_i = date("Y-m-d", strtotime($fecha_i));
      $fecha_f = $_POST['fecha_final'];
      $fecha_f = date("Y-m-d", strtotime($fecha_f));


      echo "<input type = 'hidden' name = 'fecha_inicial_o' value = '" . $fecha_i . "' >";
      echo "<input type = 'hidden' name = 'fecha_final_o' value = '" . $fecha_f . "' >";


      $current_date = date("Y-m-d");
      if ($fecha_f >= $current_date or $fecha_i >= $current_date) {

        echo "<div class = 'alert alert-danger'>La fecha inicial y final por consultar deben ser anterior al dia de hoy.</div>";
      } else {


        $first_sorteo = 0;
        $bandera_sorteo = 0;

        //////////////////////////////////
        ////// VENTAS BANCO BOLSA/////////


        $consulta_banco = mysqli_query($conn, "SELECT year, mes, SUM(cantidad) as venta, SUM(bruto) as monto_venta FROM (  
SELECT fecha_venta, YEAR(fecha_venta) as year , MONTH(fecha_venta) as mes , precio_unitario ,SUM(cantidad) as cantidad,  SUM(total_bruto) as bruto FROM transaccional_ventas_general WHERE estado_venta = 'APROBADO' AND cod_producto = 1 AND id_entidad = 3 AND date(fecha_venta) BETWEEN '" . $fecha_i . "' AND '" . $fecha_f . "' GROUP BY YEAR(fecha_venta), MONTH(fecha_venta) 
UNION 
SELECT fecha_venta ,YEAR(fecha_venta) as year , MONTH(fecha_venta) as mes , precio_unitario ,SUM(cantidad) as cantidad,  SUM(total_bruto) as bruto FROM transaccional_ventas WHERE estado_venta = 'APROBADO' AND cod_producto = 1  AND date(fecha_venta) BETWEEN '" . $fecha_i . "' AND '" . $fecha_f . "' GROUP BY YEAR(fecha_venta), MONTH(fecha_venta)
) as t GROUP BY YEAR(fecha_venta), MONTH(fecha_venta)   ");



        echo "<div class = 'card' style = 'margin-right: 5px; margin-left: 5px; '>";

        echo "<div class = 'card-header bg-primary text-white'><h4 align = 'center'>VENTAS DE LOTERIA MAYOR DEL " . $fecha_i . " AL " . $fecha_f . " </h4></div>";
        echo "<div class = 'panel-body'>";

        echo "<table class = 'table table-bordered'>";
        echo "<tr>";
        echo "<th>AÑO</th>";
        echo "<th>MES</th>";
        echo "<th>EMISION</th>";
        echo "<th>DEVOLUCION</th>";
        echo "<th>% DEV.</th>";
        echo "<th>MONTO</th>";
        echo "<th>VENTA</th>";
        echo "<th>% VENTA</th>";
        echo "<th>MONTO</th>";
        echo "</tr>";


        $total_cantidad = 0;
        $total_bruto = 0;
        $total_descuento = 0;
        $total_neto = 0;
        $total_comision = 0;
        $total_credito = 0;
        $total_producido = 0;
        $total_dev = 0;
        $total_monto_dev = 0;
        $total_monto_venta = 0;

        $i = 0;
        while ($reg_conbsulta_banco = mysqli_fetch_array($consulta_banco)) {


          $y = $reg_conbsulta_banco['year'];
          $m = $reg_conbsulta_banco['mes'];

          $consulta_producccion = mysqli_query($conn, "SELECT SUM(cantidad_numeros) as producido, precio_unitario FROM sorteos_mayores WHERE  YEAR(fecha_sorteo) = '$y' AND MONTH(fecha_sorteo) = '$m' ");
          $ob_produccion = mysqli_fetch_object($consulta_producccion);

          $producido = $ob_produccion->producido;
          $precio_unitario = $ob_produccion->precio_unitario;

          $total_producido += $producido;





          if ($producido == 0) {

            $total_cantidad  = $total_cantidad + $reg_conbsulta_banco['venta'];

            $dev = 0;
            $por_dev = 0;
            $monto_dev = $dev * $precio_unitario;


            $por_venta = 0;

            $total_dev  += $dev;
            $total_monto_dev  += 0;

            $total_bruto     = $total_bruto + $reg_conbsulta_banco['monto_venta'];
            $total_descuento = 0;
            $total_neto      = 0;
            $total_comision  = 0;
            $total_credito   = 0;
          } else {

            $total_cantidad  = $total_cantidad + $reg_conbsulta_banco['venta'];


            $dev = $producido - $reg_conbsulta_banco['venta'];
            $por_dev = ($dev / $producido) * 100;
            $monto_dev = $dev * $precio_unitario;


            $por_venta = ($reg_conbsulta_banco['venta'] / $producido) * 100;


            $total_dev  += $dev;
            $total_monto_dev  += $monto_dev;

            $total_bruto     = $total_bruto + $reg_conbsulta_banco['monto_venta'];
          }


          $nombre_mes = funcion_nombre_mes($reg_conbsulta_banco['mes']);

          $concatenado_porcentaje_venta = $concatenado_porcentaje_venta . "," . $reg_conbsulta_banco['venta'];
          $concatenado_asociaciones = $concatenado_asociaciones . "," . $nombre_mes;


          echo "<tr>";
          echo "<td>" . $reg_conbsulta_banco['year'] . "</td>";
          echo "<td>" . $nombre_mes . "</td>";
          echo "<td>" . number_format($producido) . "</td>";
          echo "<td>" . number_format($dev) . "</td>";
          echo "<td>" . number_format($por_dev) . "%</td>";
          echo "<td>" . number_format($monto_dev, 2) . "</td>";
          echo "<td>" . number_format($reg_conbsulta_banco['venta']) . "</td>";
          echo "<td>" . number_format($por_venta) . "%</td>";
          echo "<td>" . number_format($reg_conbsulta_banco['monto_venta'], 2) . "</td>";
          echo "</tr>";

          $i++;
        }


        echo "<tr class = 'alert alert-success'>";
        echo "<th colspan = '2' >TOTALES</th>";
        echo "<th>" . number_format($total_producido) . "</th>";
        echo "<th>" . number_format($total_dev) . "</th>";
        $tt_porc_dev = ($total_dev / $total_producido) * 100;
        echo "<th>" . number_format($tt_porc_dev) . "%</th>";
        echo "<th>" . number_format($total_monto_dev, 2) . "</th>";
        echo "<th>" . number_format($total_cantidad) . "</th>";
        $tt_porc_venta = ($total_cantidad / $total_producido) * 100;
        echo "<th>" . number_format($tt_porc_venta) . "%</th>";
        echo "<th>" . number_format($total_bruto, 2) . "</th>";


        echo "</tr>";

        echo "</table>";

        echo "</div>";
        echo "<div class = 'card-footer' style = 'text-align:center'>";
        echo "<button id = 'non-printable' type = 'submit' name = 'generar_excel' class = 'btn btn-success'>GENERAR EXCEL</button>";

    ?>
        <span class='btn btn-info' onclick="generar('<?php echo $concatenado_asociaciones; ?>','<?php echo $concatenado_porcentaje_venta; ?>')" id='non-printable'>
          GENERAR GRAFICO
        </span>

        <?php

        echo "</div>";
        echo "</div>";


        ?>


        <br><br>
        <div class="" style=" width:100%;height:40px">
          <canvas style='display:none' id="myChart" width="600" height="250"></canvas>

          <br><br><br><br><br><br>

          <div class="col" style="text-align: center;">
            <img src="../../assets/firmas_digitales/firma_digital_jefatura_ventas.png?fgdgcg22sfsfsfs2fs2fs" width="350" height="130">
            <br>
            ____________________________________
            <br>
            YOLANI NAJERA
            <br>
            JEFE DE VENTAS PANI

          </div>

        </div>



    <?php


      }
    }

    ?>


  </form>
</body>




<script>
  function getRandomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
  }

  function generar(empresas, porcentajes_venta) {

    document.getElementById("myChart").style.display = "block";

    var array_c = [];

    array_e = empresas.split(",");
    emp = array_e.splice(1);
    array_p = porcentajes_venta.split(",");
    porcentaje = array_p.splice(1);

    for (x = 0; x < emp.length; x++) {
      random = getRandomColor();

      array_c.push(random.toString());

    }


    new Chart(document.getElementById("myChart"), {
      type: 'bar',
      data: {
        labels: emp,
        datasets: [{
          label: "Ventas",
          backgroundColor: array_c,
          data: porcentaje
        }]
      },
      options: {
        legend: {
          display: false
        },
        title: {
          display: true,
          text: ''
        },
      scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    stepSize: 5000
                }
            }]
        }
      }
    });

  }
</script>





<?php

if (isset($_POST['generar_excel'])) {

  require_once '../../assets/phpexcel/Classes/PHPExcel/IOFactory.php';

  $objPHPExcel = new PHPExcel();
  $objPHPExcel->setActiveSheetIndex(0);



  $fecha_i = $_POST['fecha_inicial_o'];
  $fecha_f = $_POST['fecha_final_o'];

  $fecha_i = date("Y-m-d", strtotime($fecha_i));
  $fecha_f = date("Y-m-d", strtotime($fecha_f));


  $current_date = date("Y-m-d");
  if ($fecha_f >= $current_date or $fecha_i >= $current_date) {
  } else {


    $first_sorteo = 0;
    $bandera_sorteo = 0;

    //////////////////////////////////
    ////// VENTAS BANCO BOLSA/////////


    $consulta_banco = mysqli_query($conn, "SELECT year, mes, SUM(cantidad) as venta, SUM(bruto) as monto_venta FROM (  
SELECT fecha_venta, YEAR(fecha_venta) as year , MONTH(fecha_venta) as mes , precio_unitario ,SUM(cantidad) as cantidad,  SUM(total_bruto) as bruto FROM transaccional_ventas_general WHERE estado_venta = 'APROBADO' AND cod_producto = 1 AND id_entidad = 3 AND date(fecha_venta) BETWEEN '" . $fecha_i . "' AND '" . $fecha_f . "' GROUP BY YEAR(fecha_venta), MONTH(fecha_venta) 
UNION 
SELECT fecha_venta ,YEAR(fecha_venta) as year , MONTH(fecha_venta) as mes , precio_unitario ,SUM(cantidad) as cantidad,  SUM(total_bruto) as bruto FROM transaccional_ventas WHERE estado_venta = 'APROBADO' AND cod_producto = 1  AND date(fecha_venta) BETWEEN '" . $fecha_i . "' AND '" . $fecha_f . "' GROUP BY YEAR(fecha_venta), MONTH(fecha_venta)
) as t GROUP BY YEAR(fecha_venta), MONTH(fecha_venta)   ");




    $total_cantidad = 0;
    $total_bruto = 0;
    $total_descuento = 0;
    $total_neto = 0;
    $total_comision = 0;
    $total_credito = 0;
    $total_producido = 0;
    $total_dev = 0;
    $total_monto_dev = 0;
    $total_monto_venta = 0;




    $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'AÑO');
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'MES');
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'EMISION');
    $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'DEVOLUCION');
    $objPHPExcel->getActiveSheet()->SetCellValue('E1', '% DEV.');
    $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'MONTO');
    $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'VENTA');
    $objPHPExcel->getActiveSheet()->SetCellValue('H1', '% VENTA');
    $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'MONTO');


    $i = 2;
    while ($reg_conbsulta_banco = mysqli_fetch_array($consulta_banco)) {


      $y = $reg_conbsulta_banco['year'];
      $m = $reg_conbsulta_banco['mes'];

      $consulta_producccion = mysqli_query($conn, "SELECT SUM(cantidad_numeros) as producido, precio_unitario FROM sorteos_mayores WHERE  YEAR(fecha_sorteo) = '$y' AND MONTH(fecha_sorteo) = '$m' ");
      $ob_produccion = mysqli_fetch_object($consulta_producccion);

      $producido = $ob_produccion->producido;
      $precio_unitario = $ob_produccion->precio_unitario;

      $total_producido += $producido;





      if ($producido == 0) {

        $total_cantidad  = $total_cantidad + $reg_conbsulta_banco['venta'];

        $dev = 0;
        $por_dev = 0;
        $monto_dev = $dev * $precio_unitario;


        $por_venta = 0;

        $total_dev  += $dev;
        $total_monto_dev  += 0;

        $total_bruto     = $total_bruto + $reg_conbsulta_banco['monto_venta'];
        $total_descuento = 0;
        $total_neto      = 0;
        $total_comision  = 0;
        $total_credito   = 0;
      } else {

        $total_cantidad  = $total_cantidad + $reg_conbsulta_banco['venta'];


        $dev = $producido - $reg_conbsulta_banco['venta'];
        $por_dev = ($dev / $producido) * 100;
        $monto_dev = $dev * $precio_unitario;


        $por_venta = ($reg_conbsulta_banco['venta'] / $producido) * 100;


        $total_dev  += $dev;
        $total_monto_dev  += $monto_dev;

        $total_bruto     = $total_bruto + $reg_conbsulta_banco['monto_venta'];
      }


      $nombre_mes = funcion_nombre_mes($reg_conbsulta_banco['mes']);


      $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $reg_conbsulta_banco['year']);
      $objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $nombre_mes);
      $objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, number_format($producido));
      $objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, number_format($dev));
      $objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, number_format($por_dev));
      $objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, number_format($monto_dev, 2));
      $objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, number_format($reg_conbsulta_banco['venta'], 2));
      $objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, number_format($por_venta));
      $objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, number_format($reg_conbsulta_banco['monto_venta'], 2));


      $i++;
    }


    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, "TOTAL");
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':B' . $i);

    $objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, number_format($total_producido));
    $objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, number_format($total_dev));
    $tt_porc_dev = ($total_dev / $total_producido) * 100;
    $objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, number_format($tt_porc_dev));
    $objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, number_format($total_monto_dev, 2));

    $objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, number_format($total_cantidad));
    $tt_porc_venta = ($total_cantidad / $total_producido) * 100;
    $objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, number_format($tt_porc_venta));
    $objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, number_format($total_bruto, 2));




    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=\"INGRESOS CON CONTRAPRESTACION NICSP 9.xlsx\"");
    header("Cache-Control: max-age=0");

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    ob_clean();
    $objWriter->save("php://output");
  }
}

?>
