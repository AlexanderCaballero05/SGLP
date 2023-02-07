<?php

require "../../template/header.php";
date_default_timezone_set('America/Tegucigalpa');

?>



<script type="text/javascript">

$(document).ready(function() {

       $('#table_format').dataTable( {
        "lengthMenu": [[-1, 100, 50, 25,10 ], ["Todos", 100, 50, 25, 10 ]],
        "language": {
       "sProcessing":    "Procesando...",
        "sLengthMenu":    "Mostrar _MENU_ registros",
        "sZeroRecords":   "No se encontraron resultados",
        "sEmptyTable":    "Ningún dato disponible en esta tabla",
        "sInfo":          "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":     "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":  "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":   "",
        "sSearch":        "Buscar:",
        "sUrl":           "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":    "Último",
            "sNext":    "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
        }

    } );
} );


</script>


<body>


<form method="post">


<section style="background-color:#ededed;">
<br>
<h3 align="center"><b>INFORME DE DISTRIBUCION Y VENTA DE LOTERIA MAYOR POR AGENCIAS BANCO DISTRIBUIDOR</b></h3>
<br>
</section>



<a  id = 'non-printable' style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
Seleccion de Parametros
</a>


<div class="card collapse" id="collapse1" style="margin-left: 250px; margin-right: 250px;" >
<div class="card-body">

<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">Sorteo:</div>
</div>

<select class="form-control"  name = "s_sorteo" id = 's_sorteo' ">
<?php
$sorteos = mysqli_query($conn, "SELECT a.id, a.no_sorteo_may, a.fecha_sorteo, a.descripcion_sorteo_may  FROM sorteos_mayores as a ORDER BY id DESC ");

while ($row2 = mysqli_fetch_array($sorteos)) {
	echo '<option value = "' . $row2['id'] . '">No.' . $row2['no_sorteo_may'] . ' -- Fecha ' . $row2['fecha_sorteo'] . ' -- ' . $row2['descripcion_sorteo_may'] . '</option>';
}
?>
</select>


<div class="input-group-append">
    <button type="submit" name="seleccionar" id="seleccionar" class="btn btn-primary">Seleccionar</button>
</div>

</div>

</div>
</div>





<?php
if (isset($_POST["seleccionar"])) {

	$id_sorteo = $_POST["s_sorteo"];

	$info_sorteo = mysqli_query($conn, "SELECT *  FROM sorteos_mayores WHERE id = '$id_sorteo' limit 1");
	$value = mysqli_fetch_object($info_sorteo);
	$precio_unitario = $value->precio_unitario;
	$precio_unitario = $precio_unitario * 100;
	$total_cantidad = 0;
	$total_vendido = 0;
	$total_no_vendido = 0;
	$sorteo = $value->no_sorteo_may;
	$descripcion = $value->descripcion_sorteo_may;
	$fecha_sorteo = $value->fecha_sorteo;
	$mezcla = $value->mezcla;

	date_default_timezone_set("America/Tegucigalpa");

	$date = date("Y-m-d h:i:s a");

	?>

<br>
<br>

<div class="card "style = 'margin-right: 15px; margin-left: 15px'>
<div class="card-header bg-dark text-white">
<h3 align="center">
<br>
SORTEO <?php echo $id_sorteo; ?>  CON FECHA DE CAPTURA <?php echo $fecha_sorteo; ?>
</h3>
<p align="left">Fecha de consulta: <?php echo $date; ?></p>
</div>
<div class="card-body">

<table width="100%"  class="table table-bordered" id="table_format" >
<thead>
<tr>
<th align = 'center' >Cod. Seccional</th>
<th align = 'center' >Seccional</th>
<th align = 'center' >Municipio</th>
<th align = 'center' >Asignados</th>
<th align = 'center' >Vendidos</th>
<th align = 'center' >No Vendido</th>
<th align = 'center' >Ultima venta</th>
</tr>
</thead>
<tbody>
<?php

	$seccionales = mysqli_query($conn, "SELECT COUNT(id) * $mezcla as cantidad_asignada, nombre_seccional as nombre, id_seccional as cod_seccional , municipio  FROM distribucion_mayor_banco WHERE id_sorteo = '$id_sorteo'  GROUP BY id_seccional ORDER BY id_seccional ");

	echo mysqli_error($conn);

	if ($seccionales === false) {
		echo mysqli_error();
	}

	$i = 0;

	while ($r_seccional = mysqli_fetch_array($seccionales)) {

		$v_seccional_cantidad[$i] = $r_seccional['cantidad_asignada'];
		$v_seccional_cod[$i] = $r_seccional['cod_seccional'];
		$v_seccional_nombre[$i] = $r_seccional['nombre'];
		$v_seccional_geo[$i] = $r_seccional['municipio'];

		$v_seccional_venta[$i] = 0;
		$v_seccional_fecha[$i] = "";
		$v_seccional_no_vendido[$i] = 0;

		$i++;
	}

	if (mysqli_num_rows($seccionales) > 0) {

		$venta_seccionales = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad_vendida , id_seccional as cod_seccional , MAX(fecha_venta) as f_v  FROM transaccional_ventas_general  WHERE estado_venta = 'APROBADO' AND id_sorteo = '$id_sorteo' AND cod_producto = 1 GROUP BY id_seccional ");
		$i = 0;

		echo mysqli_error($conn);

		while ($rv_seccional = mysqli_fetch_array($venta_seccionales)) {

			$vv_seccional_cantidad[$i] = $rv_seccional['cantidad_vendida'];
			$vv_seccional_cod[$i] = $rv_seccional['cod_seccional'];

			$index = array_search($vv_seccional_cod[$i], $v_seccional_cod);

			$v_seccional_venta[$index] = $rv_seccional['cantidad_vendida'];
			$v_seccional_fecha[$index] = $rv_seccional['f_v'];

			$i++;
		}

		$i = 0;
		$tt_asignado = 0;
		$tt_vendido = 0;
		$tt_no_vendido = 0;

		while (isset($v_seccional_cod[$i])) {

			$v_seccional_no_vendido[$i] = $v_seccional_cantidad[$i] - $v_seccional_venta[$i];

			echo "<tr>";
			echo "<td align = 'center'>" . $v_seccional_cod[$i] . "</td>";
			echo "<td align = 'center'>" . $v_seccional_nombre[$i] . "</td>";
			echo "<td align = 'center'>" . $v_seccional_geo[$i] . "</td>";
			echo "<td align = 'center'>" . $v_seccional_cantidad[$i] . "</td>";
			echo "<td align = 'center'>" . $v_seccional_venta[$i] . "</td>";
			echo "<td align = 'center'>" . $v_seccional_no_vendido[$i] . "</td>";
			if ($v_seccional_fecha[$i] != '') {
				echo "<td align = 'center'>" . date("d-m-Y H:i:s a", strtotime($v_seccional_fecha[$i])) . "</td>";
			} else {
				echo "<td align = 'center'></td>";
			}
			echo "</tr>";

			$tt_asignado += $v_seccional_cantidad[$i];
			$tt_vendido += $v_seccional_venta[$i];
			$tt_no_vendido += $v_seccional_no_vendido[$i];

			$i++;
		}

		echo "</tbody>";

		echo "<tr>";
		echo "<th align = 'center' colspan = '3'>TOTALES</th>";
		echo "<th align = 'center'>" . number_format($tt_asignado) . "</th>";
		echo "<th align = 'center'>" . number_format($tt_vendido) . "</th>";
		echo "<th align = 'center'>" . number_format($tt_no_vendido) . "</th>";
		echo "<th align = 'center'></th>";
		echo "</tr>";

	}

	?>



</table>

	</div>
    <div class="card-footer" align="center">
        <button type="submit" id = 'non-printable' name="generar_excel" class="btn btn-success fa fa-file-excel" value="<?php echo $id_sorteo; ?>"> GENERAR EXCEL </button>
    </div>
</div>

</form>

<br><br>

<?php

}

if (isset($_POST['generar_excel'])) {

	require_once '../../assets/phpexcel/Classes/PHPExcel/IOFactory.php';

	$id_sorteo = $_POST['generar_excel'];
	$fecha_actual = date('Y-m-d h:i:s a');

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);

	$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'INFORME DE DISTRIBUCION Y VENTA DE LOTERIA MAYOR POR AGENCIAS BANCO DISTRIBUIDOR');
	$objPHPExcel->getActiveSheet()->mergeCells('A1:K1');

	$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'COD. DESCCIONAL');
	$objPHPExcel->getActiveSheet()->SetCellValue('B3', 'SECCIONAL');
	$objPHPExcel->getActiveSheet()->SetCellValue('C3', 'MUNICIPIO');
	$objPHPExcel->getActiveSheet()->SetCellValue('D3', 'ASIGNADOS');
	$objPHPExcel->getActiveSheet()->SetCellValue('E3', 'VENDIDOS');
	$objPHPExcel->getActiveSheet()->SetCellValue('F3', 'NO VENDIDOS');
	$objPHPExcel->getActiveSheet()->SetCellValue('G3', 'ULTIMA VENTA');

	$info_sorteo = mysqli_query($conn, "SELECT *  FROM sorteos_mayores WHERE id = '$id_sorteo' limit 1");
	$value = mysqli_fetch_object($info_sorteo);
	$precio_unitario = $value->precio_unitario;
	$precio_unitario = $precio_unitario * 100;
	$total_cantidad = 0;
	$total_vendido = 0;
	$total_no_vendido = 0;
	$sorteo = $value->no_sorteo_may;
	$descripcion = $value->descripcion_sorteo_may;
	$fecha_sorteo = $value->fecha_sorteo;
	$mezcla = $value->mezcla;

	$seccionales = mysqli_query($conn, "SELECT COUNT(id) * $mezcla as cantidad_asignada, nombre_seccional as nombre, id_seccional as cod_seccional , municipio  FROM distribucion_mayor_banco WHERE id_sorteo = '$id_sorteo'  GROUP BY id_seccional ORDER BY id_seccional ");

	echo mysqli_error($conn);

	if ($seccionales === false) {
		echo mysqli_error();
	}

	$i = 0;

	while ($r_seccional = mysqli_fetch_array($seccionales)) {

		$v_seccional_cantidad[$i] = $r_seccional['cantidad_asignada'];
		$v_seccional_cod[$i] = $r_seccional['cod_seccional'];
		$v_seccional_nombre[$i] = $r_seccional['nombre'];
		$v_seccional_geo[$i] = $r_seccional['municipio'];

		$v_seccional_venta[$i] = 0;
		$v_seccional_fecha[$i] = "";
		$v_seccional_no_vendido[$i] = 0;

		$i++;
	}

	$venta_seccionales = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad_vendida , id_seccional as cod_seccional , MAX(fecha_venta) as f_v  FROM transaccional_ventas_general  WHERE estado_venta = 'APROBADO' AND id_sorteo = '$id_sorteo' AND cod_producto = 1 GROUP BY id_seccional ");
	$i = 0;

	echo mysqli_error($conn);

	while ($rv_seccional = mysqli_fetch_array($venta_seccionales)) {

		$vv_seccional_cantidad[$i] = $rv_seccional['cantidad_vendida'];
		$vv_seccional_cod[$i] = $rv_seccional['cod_seccional'];

		$index = array_search($vv_seccional_cod[$i], $v_seccional_cod);

		$v_seccional_venta[$index] = $rv_seccional['cantidad_vendida'];
		$v_seccional_fecha[$index] = $rv_seccional['f_v'];

		$i++;
	}

	$i = 0;
	$tt_asignado = 0;
	$tt_vendido = 0;
	$tt_no_vendido = 0;

	$row = 4; // 1-based index
	$conteo_vendido = 0;

	while (isset($v_seccional_cod[$i])) {

		$v_seccional_no_vendido[$i] = $v_seccional_cantidad[$i] - $v_seccional_venta[$i];

		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $v_seccional_cod[$i]);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $v_seccional_nombre[$i]);
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $v_seccional_geo[$i]);
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, $v_seccional_cantidad[$i]);
		$objPHPExcel->getActiveSheet()->SetCellValue('E' . $row, $v_seccional_venta[$i]);
		$objPHPExcel->getActiveSheet()->SetCellValue('F' . $row, $v_seccional_no_vendido[$i]);
		if ($v_seccional_fecha[$i] != '') {
			$objPHPExcel->getActiveSheet()->SetCellValue('G' . $row, date("d-m-Y H:i:s a", strtotime($v_seccional_fecha[$i])));
		} else {
			$objPHPExcel->getActiveSheet()->SetCellValue('G' . $row, date("d-m-Y H:i:s a", strtotime($v_seccional_fecha[$i])));
		}

		$tt_asignado += $v_seccional_cantidad[$i];
		$tt_vendido += $v_seccional_venta[$i];
		$tt_no_vendido += $v_seccional_no_vendido[$i];

		$i++;
		$row++;
	}

	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=\"Reporte_ventas_mayor_agencia.xlsx\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	ob_clean();
	$objWriter->save("php://output");

}

?>