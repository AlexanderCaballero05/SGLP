<?php
require '../../template/header.php';
require './fvp_distribucion_pedidos_mayor_db.php';
?>

<script type="text/javascript">

function agregar_rango(r_inicial,r_final,cantidad){

document.getElementById('paquete_inicial').value = r_inicial;
document.getElementById('paquete_final').value = r_final;
document.getElementById('cantidad').value = cantidad;

document.getElementById('cantidad').setAttribute('min',0);
document.getElementById('cantidad').setAttribute('max',cantidad);

document.getElementById('boton_guardar').disabled = false;

}


function calcular_final(cantidad){


r_inicial = document.getElementById('paquete_inicial').value;
r_final = parseInt(r_inicial) + parseInt(cantidad) - 1;


if (isNaN(parseInt(r_final))) {

document.getElementById('boton_guardar').disabled = true;

  swal({
  title: "",
   text: "Paquete inicial invalido",
    type: "warning"
  });

}else{

r_final = parseInt(r_inicial) + parseInt(cantidad) - 1;

document.getElementById('paquete_final').value = r_final;
document.getElementById('boton_guardar').disabled = false;

}

}


function isNumber(evt) {
var iKeyCode = (evt.which) ? evt.which : evt.keyCode
if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
return false;

return true;
}

</script>

<form method="POST">




<br>

<ul class="nav nav-tabs">
 <li class="nav-item">
    <a style="background-color:#ededed;" class="nav-link active" href="#">Distribución Mayor</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="./fvp_distribucion_pedidos_menor_bolsas.php" >Distribución Menor Bolsas</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="./fvp_distribucion_pedidos_menor_numeros_grupos.php" >Distribución Menor Extra</a>
  </li>
</ul>


<section style="background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >DISTRIBUCION DE LOTERIA MAYOR</h2>
<br>
</section>

<a class="btn btn-info" style="width:100%" role="button" data-toggle="collapse" href="#collapse3" aria-expanded="false" aria-controls="collapse3">
 Selección de Parametros
</a>

<div  class="collapse" style = "width:100%"  id="collapse3" align="center">
<div class="card" align="center" style="width: 50%">
<div class="card-body">

<div class="input-group">
<div class="input-group-prepend"><div class="input-group-text">Sorteo: </div></div>

<select class="form-control" name="sorteo" >
<?php
while ($row2 = mysqli_fetch_array($sorteos_seleccion)) {
	echo '<option value = "' . $row2['id'] . '">' . $row2['no_sorteo_may'] . '</option>';
}
?>
</select>


<div class="input-group-prepend"><div class="input-group-text">Entidad: </div></div>

<select class="form-control" name="s_empresa" >
<?php
while ($row2 = mysqli_fetch_array($empresas)) {
	echo '<option value = "' . $row2['id'] . '">' . $row2['nombre_empresa'] . '</option>';
}
?>
</select>

<div class="input-group-append">
<input  type="submit" name="seleccionar" class="btn btn-primary" value="Seleccionar">
</div>

</div>

</div>
</div>
</div>



<?php

if (isset($_POST['seleccionar'])) {
	$id_sorteo = $_POST['sorteo'];
	$id_empresa = $_POST['s_empresa'];

	$info_sorteo = mysqli_query($conn, "SELECT *  FROM sorteos_mayores WHERE id = '$id_sorteo' limit 1");
	$value = mysqli_fetch_object($info_sorteo);
	$sorteo = $value->no_sorteo_may;
	$fecha_sorteo = $value->fecha_sorteo;
	$cantidad_billetes = $value->cantidad_numeros;
	$mezcla = $value->mezcla;
	$estado_sorteo = $value->estado_sorteo;
	$estado_venta = $value->estado_venta;

	$info_empresa = mysqli_query($conn, "SELECT * FROM empresas WHERE id = '$id_empresa' ");
	$ob_empresa = mysqli_fetch_object($info_empresa);
	$nombre_empresa = $ob_empresa->nombre_empresa;

	$paquetes_disponibles = mysqli_query($conn, "SELECT COUNT(*) as conteo FROM sorteos_mezclas WHERE id_sorteo = '$id_sorteo' AND estado IN 'PENDIENTE DISTRIBUCION' ");

	$op_paquetes = mysqli_fetch_object($paquetes_disponibles);
	$num_paquetes = $op_paquetes->conteo;

	$c_rangos_disponibles = mysqli_query($conn, " SELECT MIN(num_mezcla) as minimo, MAX(num_mezcla) as maximo, MAX(num_mezcla) -MIN(num_mezcla) +1 as cantidad , indicador FROM ( SELECT num_mezcla, @curRow := @curRow + 1 AS row_number, num_mezcla - @curRow AS indicador from sorteos_mezclas p join (SELECT @curRow := 0) r WHERE id_sorteo = '$id_sorteo' AND id_empresa IS NULL ORDER BY num_mezcla ASC ) t GROUP BY indicador ");

	?>
<div align="center" style="width:100%; ">
<input type="hidden" name="id_sorteo_oculto" value="<?php echo $id_sorteo; ?>">
<input type="hidden" name="id_empresa_oculto" value="<?php echo $id_empresa; ?>">

<input type="hidden" id="billetes_disponibles_oculto" value="<?php echo $num_paquetes; ?>">

<br>


<div class="card" style="margin-right: 10px; margin-left: 10px;">
<div class="card-header bg-secondary text-white">
 <h3 align="center">
Inventario Disponible  Sorteo Numero: <?php if (isset($sorteo)) {echo $sorteo;}?>
 Fecha de Sorteo: <?php if (isset($sorteo)) {echo $fecha_sorteo;}?>
</h3>

<hr>
<b>NOTA:</b>
La distribución de loteria mayor se realiza por paquete y cada paquete contiene 100 billetes.


</div>
<div class="card-body">

<table class="table table-bordered">
  <tr>
    <th>Paquete Inicial</th>
    <th>Paquete Final</th>
    <th>Cantidad</th>
    <th align="center">Accion</th>
  </tr>
<?php

	while ($r_rangos_disponibles = mysqli_fetch_array($c_rangos_disponibles)) {
		echo "<tr><td>" . $r_rangos_disponibles['minimo'] . "</td><td>" . $r_rangos_disponibles['maximo'] . "</td><td>" . $r_rangos_disponibles['cantidad'] . "</td>";
		echo "<td align = 'center'><span class = 'btn btn-primary' onclick = agregar_rango('" . $r_rangos_disponibles['minimo'] . "','" . $r_rangos_disponibles['maximo'] . "','" . $r_rangos_disponibles['cantidad'] . "') >Agregar Rango</span></td>";
		echo "</tr>";
	}

	?>

<tr><th colspan="2">TOTAL PAQUETES DISPONIBLES</th><th><?php echo $num_paquetes; ?></th><th></th></tr>
</table>

</div>
</div>



<input type="hidden" id="billetes_disponibles" value="<?php echo $num_paquetes; ?>" readonly>


<br>



<table  style="margin-right: 10px; margin-left: 10px;">
  <tr>
    <td width="49%" valign="top">

<div class="card">
  <div class="card-header bg-secondary text-white">
    <h3 align="center"><?php echo $nombre_empresa; ?></h3>
  </div>

  <div class="card-body">
    <table class="table table-bordered">
      <tr>
        <th width="33.33%">Cantidad</th>
        <th width="33.33%">Paquete Inicial</th>
        <th width="33.34%">Paquete Final</th>
      </tr>

      <tr>
        <td>
          <input type="number" id="cantidad" name="cantidad" class="form-control" onchange="calcular_final(this.value)" onkeypress="javascript:return isNumber(event)">
        </td>
        <td>
          <input type="text" id="paquete_inicial" name="paquete_inicial" onkeyup="validar_inicial(this.value)" class="form-control" readonly="true">
        </td>
        <td>
          <input type="text" id="paquete_final" name="paquete_final" onkeyup="validar_final(this.value)" class="form-control" readonly="true">
        </td>

      </tr>
    </table>
  </div>

<div align="center" class="card-footer">
  <button name="guardar_distribucion" id = "boton_guardar" type="submit" class="btn btn-primary" disabled="true">GUARDAR DISTRIBUCION</button>
</div>
</div>


    </td>

    <td width="2%"></td>

    <td width="49%" valign="top">


<div class="card">
  <div class="card-header bg-secondary text-white">
    <h3 align="center">ASIGNADO EN SORTEO</h3>
  </div>
  <div class="card-body">

<table class="table table-bordered">
<tr>
  <th>Entidad</th>
  <th>Cantidad Paquetes</th>
  <th>Ver</th>
  <th>Excel</th>
</tr>

<?php

	$distribuciones_realizadas = mysqli_query($conn, "SELECT COUNT(id_empresa) as conteo, b.id , b.nombre_empresa FROM sorteos_mezclas AS a INNER JOIN empresas as b  ON a.id_empresa = b.id WHERE a.id_sorteo = '$id_sorteo' AND a.estado = 'DISTRIBUIDO' GROUP BY id_empresa ");

	if ($distribuciones_realizadas === false) {
		echo mysqli_error();
	}

	while ($reg_distribucion = mysqli_fetch_array($distribuciones_realizadas)) {

		$parametros = $reg_distribucion['id'] . "-" . $id_sorteo;

		if ($reg_distribucion['conteo'] != 0) {
			echo "<tr>";
			echo "<td>" . $reg_distribucion['nombre_empresa'] . "</td>";
			echo "<td>" . $reg_distribucion['conteo'] . "</td>";
			echo "<td align = 'center'>
<a target = 'blank'  href = './fvp_distribucion_pedidos_mayor_detalle.php?par=" . $parametros . "' class = 'btn btn-info'>
<span class = 'fa fa-eye'></span>
</a>
</td>";

			echo "<td align = 'center'>
<button  class = 'btn btn-success' name = 'generar_excel' value = '" . $reg_distribucion['id'] . "'  >
<span class = 'fa fa-file'></span>
</button>
</td>";

			echo "</tr>";
		}

	}

	?>

</table>


</div>
<div class="card-footer" align="center">
  <button type="submit" class="btn btn-danger" name="borrar_distribucion" >Eliminar Distribuciones sin Factura</button>
</div>
</div>

    </td>
  </tr>
</table>

</div>


<?php
}
?>



</form>



<?php

if (isset($_POST['generar_excel'])) {

	$id_entidad = $_POST['generar_excel'];
	$id_sorteo = $_POST['id_sorteo_oculto'];

	$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo' ");
	$ob_sorteo = mysqli_fetch_object($info_sorteo);
	$mezcla = $ob_sorteo->mezcla;

	$info_entidad = mysqli_query($conn, "SELECT * FROM empresas WHERE id = '$id_entidad' ");
	$ob_entidad = mysqli_fetch_object($info_entidad);
	$nombre_entidad = $ob_entidad->nombre_empresa;

	$inventario = mysqli_query($conn, "SELECT * FROM sorteos_mezclas WHERE id_empresa = '$id_entidad' AND id_sorteo = '$id_sorteo' ORDER BY num_mezcla ASC ");

	$fecha_actual = date('Y-m-d h:i:s a');

	require_once '../../assets/phpexcel/Classes/PHPExcel/IOFactory.php';

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);

//	$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ASIGNACION LOTERIA MAYOR - ' . $nombre_entidad);
//	$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'SORTEO ');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', $id_sorteo);

	$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'BILLETE INICIAL');
	$objPHPExcel->getActiveSheet()->SetCellValue('B3', 'BILLETE FINAL');
	$objPHPExcel->getActiveSheet()->SetCellValue('C3', 'PAQUETE');
	$objPHPExcel->getActiveSheet()->SetCellValue('D3', 'CANTIDAD');

	$row = 4; // 1-based index

	$m = 0;
	$c = 0;
	while ($mezclas = mysqli_fetch_array($inventario)) {

		$num_mezcla = $mezclas['num_mezcla'];
		$cod_factura = $mezclas['cod_factura'];

		$detalle_mezcla = mysqli_query($conn, "SELECT * FROM sorteos_mezclas_rangos WHERE id_sorteo = $id_sorteo AND num_mezcla = $num_mezcla ");

		while ($detalle = mysqli_fetch_array($detalle_mezcla)) {
			$rango_inicial_mezcla = $detalle['rango'];
			$rango_final_mezcla = $detalle['rango'] + $mezcla - 1;

			while ($rango_inicial_mezcla <= $rango_final_mezcla) {

				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $rango_inicial_mezcla);
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $rango_inicial_mezcla);
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $num_mezcla);
				$objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, "1");

				$row++;
				$rango_inicial_mezcla++;
				$c++;
			}

		}

	}

	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, "TOTAL");
	$objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':C' . $row);
	$objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, $c);

	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=\"Asignacion " . $id_sorteo . " - " . $nombre_entidad . ".xlsx\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	ob_clean();
	$objWriter->save("php://output");

}
?>