<?php

require '../conexion.php';
require 'get_default_info_dash.php';

$parametros = $_GET['param'];

$v_parametros = explode("|", $parametros);

//////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////  CARD DE MONITOREO  ////////////////////////////////////////////
/////////////////////////////////////////  CARD DE MONITOREO  ////////////////////////////////////////////

/////////////////////////////////////////  CARD DE MONITOREO  ////////////////////////////////////////////
/////////////////////////////////////////  CARD DE MONITOREO  ////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// CARD DE VENTAS DE LOTER //////////////////////////////////////////
/////////////////////////////////////// CARD DE VENTAS DE LOTER //////////////////////////////////////////

if ($v_parametros[0] == 'card_ventas') {

	if ($v_parametros[2] == '') {

		if ($v_parametros[1] == '') {
			$year_actual = date('Y');
		} else {
			$year_actual = $v_parametros[1];
		}

		$c_ventas_b = mysqli_query($conn, "SELECT SUM(credito_pani) as credito_pani FROM transaccional_ventas_general WHERE YEAR(fecha_venta) = '$year_actual' AND estado_venta = 'APROBADO' AND cod_producto = 1 ");
		$c_ventas_o = mysqli_query($conn, "SELECT SUM(credito_pani) as credito_pani FROM transaccional_ventas WHERE YEAR(fecha_venta)  = '$year_actual' AND estado_venta = 'APROBADO' AND cod_producto = 1 ");

		$ob_ventas_b = mysqli_fetch_object($c_ventas_b);
		$ob_ventas_o = mysqli_fetch_object($c_ventas_o);

		$total_venta = $ob_ventas_b->credito_pani + $ob_ventas_o->credito_pani;

		$descripcion_titulo = '<b>VENTA Y PAGO EN LEMPIRAS (' . $year_actual . ')</b>';

	} else {

		$sorteo = $v_parametros[2];

		$c_premios_mayor = mysqli_query($conn, "SELECT * FROM sorteos_mayores_premios WHERE sorteos_mayores_id = '$sorteo'  AND premios_mayores_id NOT IN (9,10,11,12)  ORDER BY monto DESC LIMIT 1 ");
		$ob_premios_mayor = mysqli_fetch_object($c_premios_mayor);
		$monto = $ob_premios_mayor->monto;
		$numero_premiado_mayor = $ob_premios_mayor->numero_premiado_mayor;

		$c_ventas_b = mysqli_query($conn, "SELECT SUM(credito_pani) as credito_pani FROM transaccional_ventas_general WHERE id_sorteo = '$sorteo' AND estado_venta = 'APROBADO' AND cod_producto = 1 ");
		$c_ventas_o = mysqli_query($conn, "SELECT SUM(credito_pani) as credito_pani FROM transaccional_ventas WHERE id_sorteo = '$sorteo' AND estado_venta = 'APROBADO' AND cod_producto = 1 ");



		$ob_ventas_b = mysqli_fetch_object($c_ventas_b);
		$ob_ventas_o = mysqli_fetch_object($c_ventas_o);

		$total_venta = $ob_ventas_b->credito_pani + $ob_ventas_o->credito_pani;

		$descripcion_titulo = '<b>VENTA Y PAGO EN LEMPIRAS SORTEO ' . $sorteo .'</b>';

	}

	?>






<div class="card-header bg-dark text-white">

<div class="btn-group" style="width: 100%">
  <span class="bg-dark text-white" style="width: 100%" style="font-size: 18px" >
	<?php echo $descripcion_titulo; ?>
  </span>

<?php
$concat = 'card_ventas|' . $v_parametros[1] . '|' . $v_parametros[2];
	?>

<div  class="dropdown" >
<button class="btn btn-dark text-light fa fa-sync-alt" onclick="load_info_dash('<?php echo $concat; ?>')" ></button>
</div>


<div  class="dropdown" >
<button class="btn btn-dark text-light fa fa-ellipsis-v" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
</button>

<div class="dropdown-menu dropdown-menu-right p-4"  >
<div class="input-group" style="margin-bottom: 5px"  >
<div class="input-group-prepend"><div class="input-group-text"  >A??O</div></div>
<select class="form-control" id="card_venta_year_select">
<option value="">Seleccione uno</option>
option
<?php
while ($r_years = mysqli_fetch_array($c_years)) {
		echo "<option value = '" . $r_years['year'] . "'>" . $r_years['year'] . "</option>";
	}
	?>
</select>
</div>


<div class="input-group">
<div class="input-group-prepend"><div class="input-group-text">SORTEO</div></div>
<select class="form-control" id="card_venta_sorteo_select">
<option value="">Seleccione uno</option>
<?php
while ($r_sorteos = mysqli_fetch_array($c_sorteos)) {
		echo "<option value = '" . $r_sorteos['sorteo'] . "'>" . $r_sorteos['sorteo'] . " - " . $r_sorteos['fecha_sorteo'] . "</option>";
	}
	?>
</select>
</div>

<div class="dropdown-divider"></div>
<button style="width: 350px" onclick="load_info_dash('card_ventas|'+document.getElementById('card_venta_year_select').value+'|'+ document.getElementById('card_venta_sorteo_select').value)" class="btn btn-dark btn-block">Aceptar</button>

</div>

</div>


</div>
</div>




<?php

	if ($v_parametros[2] == '') {

		if ($v_parametros[1] == '') {
			$year_actual = date('Y');
		} else {
			$year_actual = $v_parametros[1];
		}

		$c_pagos = mysqli_query($conn, "SELECT SUM(totalpayment) as pago_neto FROM mayor_pagos_detalle WHERE YEAR(transactiondate) = '$year_actual' AND transactionstate = '1' ");
		$ob_pagos = mysqli_fetch_object($c_pagos);
		$total_pagos = $ob_pagos->pago_neto;

	} else {

		$sorteo = $v_parametros[2];

		$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE id = '$sorteo'  ");
		$ob_sorteo = mysqli_fetch_object($info_sorteo);
		$fecha_sorteo = $ob_sorteo->fecha_sorteo;
		$fecha_vencimiento = $ob_sorteo->fecha_vencimiento;

		$c_pagos = mysqli_query($conn, "SELECT SUM(totalpayment) as pago_neto FROM mayor_pagos_detalle WHERE sorteo = '$sorteo' AND transactionstate = '1' ");
		$ob_pagos = mysqli_fetch_object($c_pagos);
		$total_pagos = $ob_pagos->pago_neto;

		$c_provision = mysqli_query($conn, "SELECT SUM(total) as pago_neto FROM archivo_pagos_mayor WHERE sorteo = '$sorteo'  ");
		$ob_provision = mysqli_fetch_object($c_provision);
		$total_provision = $ob_provision->pago_neto;

		$total_caduco = $total_provision - $total_pagos;

	}

	$total_utilidad = $total_venta - $total_pagos;

	?>


<div class="card-body" style="font-size: 30px ; margin: 10px 0px 10px 10px " >

<?php
if (isset($sorteo)) {



		echo "<div class = 'row' >
		<div class = 'col'>
			<p style = 'font-size:16px' align= 'center'><b>#1 PREMIO: ".$numero_premiado_mayor." L ".number_format($monto, 2)."</b></p>
		</div>		
		<div class = 'col'> 
		<p style = 'font-size:16px' align= 'center'><b>
		FECHA DE SORTEO: " . $fecha_sorteo . "</b></p>
		</div>		
		<div class = 'col'> 
		<p style = 'font-size:16px' align= 'center'><b>
		FECHA DE VENCIMIENTO: " . $fecha_vencimiento . "</b></p>
		</div>		
		</div>";

	}
	?>


<table class="table table-bordered" >
	<tr>
		<th style = 'border-left: 10px solid;border-left-color: #006600' >VENDIDO</th>
<?php
if (isset($sorteo)) {
		echo "		<th style = 'border-left: 10px solid;border-left-color: #cc0000;' >PROVISIONADO</th>";
	}
	?>

		<th style = 'border-left: 10px solid;border-left-color: #cc0000;' >PAGADO</th>
		<th style = 'border-left: 10px solid;border-left-color: #1b827f;' >UTILIDAD</th>
	</tr>

	<tr>
		<td style = 'border-left: 10px solid;border-left-color: #006600' >L. <?php echo number_format($total_venta, 2); ?></td>
<?php
if (isset($sorteo)) {
		echo "		<td style = 'border-left: 10px solid;border-left-color: #cc0000;' >" . number_format($total_provision, 2) . "</td>";
	}
	?>

		<td style = 'border-left: 10px solid;border-left-color: #cc0000;'>L. <?php echo number_format($total_pagos, 2); ?></td>
		<td style = 'border-left: 10px solid;border-left-color: #1b827f;'>L. <?php echo number_format($total_utilidad, 2); ?></td>
	</tr>

</table>

<?php
if (isset($sorteo)) {

		$fecha_actual = date("Y-m-d");
		if ($fecha_vencimiento < $fecha_actual) {
			echo "<p style = 'font-size:16px'><b> TOTAL CADUCO: L. " . number_format($total_caduco, 2) . "</b></p>";
		}

	}
	?>

</div>

<?php

}

/////////////////////////////////////// CARD DE VENTAS DE LOTER //////////////////////////////////////////
/////////////////////////////////////// CARD DE VENTAS DE LOTER //////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// CARD DE DONUT DE VENTAS //////////////////////////////////////////
/////////////////////////////////////// CARD DE DONUT DE VENTAS //////////////////////////////////////////

if ($v_parametros[0] == 'card_donut_ventas') {
	$total_venta = 0;

	if ($v_parametros[2] == '') {
		$sorteo_actual = $max_sorteo;
		$c_ventas_b = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad FROM transaccional_ventas_general WHERE id_sorteo = '$sorteo_actual' AND estado_venta = 'APROBADO' AND cod_producto = 1 ");
		$c_ventas_o = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad FROM transaccional_ventas WHERE id_sorteo = '$sorteo_actual' AND estado_venta = 'APROBADO' AND cod_producto = 1 ");

		$ob_ventas_b = mysqli_fetch_object($c_ventas_b);
		$ob_ventas_o = mysqli_fetch_object($c_ventas_o);

		$total_venta = $ob_ventas_b->cantidad + $ob_ventas_o->cantidad;

		$descripcion_titulo = '<b>VENTA EN SORTEO ' . $sorteo_actual . '</b>';

	} else {

		$sorteo_actual = $v_parametros[2];

		$c_ventas_b = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad FROM transaccional_ventas_general WHERE id_sorteo = '$sorteo_actual' AND estado_venta = 'APROBADO' AND cod_producto = 1 ");
		$c_ventas_o = mysqli_query($conn, "SELECT SUM(cantidad) as cantidad FROM transaccional_ventas WHERE id_sorteo = '$sorteo_actual' AND estado_venta = 'APROBADO' AND cod_producto = 1 ");

		$ob_ventas_b = mysqli_fetch_object($c_ventas_b);
		$ob_ventas_o = mysqli_fetch_object($c_ventas_o);

		$total_venta = $ob_ventas_b->cantidad + $ob_ventas_o->cantidad;

		$descripcion_titulo = '<b>VENTA EN SORTEO ' . $sorteo_actual . '</b>';

	}

	$c_produccion = mysqli_query($conn, "SELECT cantidad_numeros FROM sorteos_mayores WHERE id = '$sorteo_actual' ");
	$ob_produccion = mysqli_fetch_object($c_produccion);
	$cantidad_producida = $ob_produccion->cantidad_numeros;

	$total_no_venta = $cantidad_producida - $total_venta;

	?>

<div class="card-header bg-dark text-white">

<input type="hidden" id="h_donut_venta" name="h_donut_venta" value="<?php echo $total_venta; ?>">
<input type="hidden" id="h_donut_no_venta" name="h_donut_no_venta" value="<?php echo $total_no_venta; ?>">


<div class="btn-group" style="width: 100%">
  <span class="bg-dark text-white" style="width: 100%" style="font-size: 18px" >
	<?php echo $descripcion_titulo; ?>
  </span>


<!--
<div  class="dropdown" >
<a class="btn btn-dark text-light fa fa-file-download" href="./dash_mayor_excel.php?info=1&s=<?php //echo $sorteo_actual; ?>" target="_blank"></a>
</div>
-->

<div  class="dropdown" >
<button class="btn btn-dark text-light fa fa-info-circle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
</button>

<div class = "dropdown-menu dropdown-menu-right" >

<ul  class = "list-group" style="display:inline-block;">
  <li style=" white-space:nowrap" class = "list-group-item">Vendido: <?php echo number_format($total_venta); ?></li>
  <li style=" white-space:nowrap" class = "list-group-item">No Vendido: <?php echo number_format($total_no_venta); ?></li>
</ul>

</div>
</div>


<div  class="dropdown" >
<button class="btn btn-dark text-light fa fa-ellipsis-v" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
</button>

<div class="dropdown-menu dropdown-menu-right p-4"  >

<div class="input-group">
<div class="input-group-prepend"><div class="input-group-text">SORTEO</div></div>
<select class="form-control" id="card_donut_sorteo_select">
<option value="">Seleccione uno</option>
<?php
while ($r_sorteos = mysqli_fetch_array($c_sorteos)) {
		echo "<option value = '" . $r_sorteos['sorteo'] . "'>" . $r_sorteos['sorteo'] . " - " . $r_sorteos['fecha_sorteo'] . "</option>";
	}
	?>
</select>
</div>

<div class="dropdown-divider"></div>
<button style="width: 350px" onclick="load_info_dash('card_donut_ventas||'+ document.getElementById('card_donut_sorteo_select').value)" class="btn btn-dark btn-block">Aceptar</button>

</div>

</div>

</div>

</div>

<div class="card-body">
	<canvas  height="260px" id="graf_don"></canvas>
</div>


<script type="text/javascript">
		cargar_donut_ventas();
</script>

<?php

}

/////////////////////////////////////// CARD DE DONUT DE VENTAS //////////////////////////////////////////
/////////////////////////////////////// CARD DE DONUT DE VENTAS //////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// CARD DE BARRA DE VENTAS //////////////////////////////////////////
/////////////////////////////////////// CARD DE BARRA DE VENTAS //////////////////////////////////////////

if ($v_parametros[0] == 'card_bar_ventas') {
	$total_venta = 0;

	if ($v_parametros[2] == '') {
		$sorteo_actual = $max_sorteo;
		$sorteo_anterior = $sorteo_actual - 1;

	} else {

		$sorteo_actual = $v_parametros[2];
		$sorteo_anterior = $v_parametros[1];

	}

	$concatenado = $sorteo_actual;

	$c_ventas_b = mysqli_query($conn, "SELECT   a.id_sorteo, SUM(a.cantidad) as cantidad, b.nombre_empresa FROM transaccional_ventas_general as a INNER JOIN empresas as b ON a.id_entidad = b.id WHERE a.id_sorteo = '$sorteo_anterior'  AND a.estado_venta = 'APROBADO' AND a.cod_producto = 1 GROUP BY a.id_sorteo, a.id_entidad  ORDER BY a.id_sorteo, a.id_entidad ASC ");

	$i = 0;
	$v_sorteo_a = array();
	$v_sorteo_b = array();
	$v_entidades = array();

	$concatenado_sorteo_a = "";
	$concatenado_sorteo_b = "";

	foreach ($c_ventas_b as $ventas) {
		$empresa_venta = $ventas['nombre_empresa'];
		$cantidad_venta = $ventas['cantidad'];
		$v_sorteo_a[$empresa_venta] = $cantidad_venta;
		$v_sorteo_b[$empresa_venta] = 0;
		$v_entidades[$empresa_venta] = $empresa_venta;
	}

	$c_ventas_o = mysqli_query($conn, "SELECT   a.id_sorteo, SUM(a.cantidad) as cantidad, b.nombre_empresa FROM transaccional_ventas as a INNER JOIN empresas as b ON a.id_entidad = b.id WHERE a.id_sorteo = '$sorteo_anterior' AND a.estado_venta = 'APROBADO' AND a.cod_producto = 1 GROUP BY a.id_sorteo, a.id_entidad ORDER BY a.id_sorteo, a.id_entidad ASC ");

	foreach ($c_ventas_o as $ventas) {
		$empresa_venta = $ventas['nombre_empresa'];
		$cantidad_venta = $ventas['cantidad'];
		$v_sorteo_a[$empresa_venta] = $cantidad_venta;
		$v_sorteo_b[$empresa_venta] = 0;
		$v_entidades[$empresa_venta] = $empresa_venta;
	}

	$c_ventas_b = mysqli_query($conn, "SELECT   a.id_sorteo, SUM(a.cantidad) as cantidad, b.nombre_empresa FROM transaccional_ventas_general as a INNER JOIN empresas as b ON a.id_entidad = b.id WHERE a.id_sorteo = '$sorteo_actual'  AND a.estado_venta = 'APROBADO' AND a.cod_producto = 1 GROUP BY a.id_sorteo, a.id_entidad  ORDER BY a.id_sorteo, a.id_entidad ASC ");

	foreach ($c_ventas_b as $ventas) {
		$empresa_venta = $ventas['nombre_empresa'];
		$cantidad_venta = $ventas['cantidad'];
		if (!isset($v_sorteo_a[$empresa_venta])) {
			$v_sorteo_a[$empresa_venta] = 0;
		}
		$v_sorteo_b[$empresa_venta] = $cantidad_venta;
		$v_entidades[$empresa_venta] = $empresa_venta;
		$i++;
	}

	$c_ventas_o = mysqli_query($conn, "SELECT   a.id_sorteo, SUM(a.cantidad) as cantidad, b.nombre_empresa FROM transaccional_ventas as a INNER JOIN empresas as b ON a.id_entidad = b.id WHERE a.id_sorteo = '$sorteo_actual' AND a.estado_venta = 'APROBADO' AND a.cod_producto = 1 GROUP BY a.id_sorteo, a.id_entidad ORDER BY a.id_sorteo, a.id_entidad ASC ");

	foreach ($c_ventas_o as $ventas) {
		$empresa_venta = $ventas['nombre_empresa'];
		$cantidad_venta = $ventas['cantidad'];
		if (!isset($v_sorteo_a[$empresa_venta])) {
			$v_sorteo_a[$empresa_venta] = 0;
		}
		$v_sorteo_b[$empresa_venta] = $cantidad_venta;
		$v_entidades[$empresa_venta] = $empresa_venta;
	}

	$concatenado_entidades = "";
	foreach ($v_entidades as $entidad) {
		$concatenado_sorteo_a .= $v_sorteo_a[$entidad] . "%";
		$concatenado_sorteo_b .= $v_sorteo_b[$entidad] . "%";
		$concatenado_entidades .= $entidad . "%";
	}

	$descripcion_titulo = '<b>COMPARATIVO DE VENTAS POR ASOCIADO (SORTEO ' . $sorteo_anterior . ' Y ' . $sorteo_actual . ') </b>';

	?>





<div class="card-header bg-dark text-white">

<input type="hidden" id="h_sorteo_a" value = '<?php echo $sorteo_anterior; ?>' name="">
<input type="hidden" id="h_sorteo_b" value = '<?php echo $sorteo_actual; ?>' name="">
<input type="hidden" id="concat_entidades" value = '<?php echo $concatenado_entidades; ?>' >
<input type="hidden" id="concat_sorteo_a" value = '<?php echo $concatenado_sorteo_a; ?>' >
<input type="hidden" id="concat_sorteo_b" value = '<?php echo $concatenado_sorteo_b; ?>' >

<div class="btn-group" style="width: 100%">
  <span class="bg-dark text-white" style="width: 100%" style="font-size: 18px" >
	<?php echo $descripcion_titulo; ?>
  </span>

<!--
<div  class="dropdown" >
<a class="btn btn-dark text-light fa fa-file-download" href="./dash_mayor_excel.php?info=2&s1=<?php //echo $sorteo_anterior; ?>&s2=<?php //echo $sorteo_actual; ?>" target="_blank"></a>
</div>
-->

<div  class="dropdown" >
<button class="btn btn-dark text-light fa fa-info-circle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

<div class = "dropdown-menu dropdown-menu-right" >

<div style="height: 300px; overflow-y: scroll;">

<table class="table table-bordered table-striped" >
	<tr class = 'bg-dark text-white' >
	<th>ENTIDAD</th>
	<th><?php echo $sorteo_anterior; ?></th>
	<th><?php echo $sorteo_actual; ?></th>
	</tr>

<?php
$v_entidades = explode("%", $concatenado_entidades);
	array_pop($v_entidades);
	foreach ($v_entidades as $v_entidad) {

		echo "<tr>";
		echo "<td>" . $v_entidad . "</td>";

		if (isset($v_sorteo_a[$v_entidad])) {
			echo "<td>" . number_format($v_sorteo_a[$v_entidad]) . "</td>";
		} else {
			echo "<td>0</td>";
		}

		if (isset($v_sorteo_b[$v_entidad])) {
			echo "<td>" . number_format($v_sorteo_b[$v_entidad]) . "</td>";
		} else {
			echo "<td>0</td>";
		}

		echo "</tr>";
	}

	?>

</table>
</div>

</div>

</div>

<div  class="dropdown" >
<button class="btn btn-dark text-light fa fa-ellipsis-v" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
</button>

<div class="dropdown-menu dropdown-menu-right p-4"  >

<div class="input-group" style="margin-bottom: 5px">
<div class="input-group-prepend"><div class="input-group-text">SORTEO 1</div></div>
<select class="form-control" id="card_donut_sorteo_select_1">
<option value="">Seleccione uno</option>
<?php
while ($r_sorteos = mysqli_fetch_array($c_sorteos)) {
		echo "<option value = '" . $r_sorteos['sorteo'] . "'>" . $r_sorteos['sorteo'] . " - " . $r_sorteos['fecha_sorteo'] . "</option>";
	}
	?>
</select>
</div>


<div class="input-group">
<div class="input-group-prepend"><div class="input-group-text">SORTEO 2</div></div>
<select class="form-control" id="card_donut_sorteo_select_2">
<option value="">Seleccione uno</option>
<?php
while ($r_sorteos2 = mysqli_fetch_array($c_sorteos2)) {
		echo "<option value = '" . $r_sorteos2['sorteo'] . "'>" . $r_sorteos2['sorteo'] . " - " . $r_sorteos2['fecha_sorteo'] . "</option>";
	}
	?>
</select>
</div>

<div class="dropdown-divider"></div>
<button style="width: 350px" onclick="load_info_dash('card_bar_ventas|'+ document.getElementById('card_donut_sorteo_select_1').value+'|'+ document.getElementById('card_donut_sorteo_select_2').value)" class="btn btn-dark btn-block">Aceptar</button>

</div>

</div>


</div>

</div>

<div class="card-body">
<canvas height="120px" id="graf_bar"></canvas>
</div>


<script type="text/javascript">
		cargar_bar_ventas();
</script>

<?php

}

/////////////////////////////////////// CARD DE BARRA DE VENTAS //////////////////////////////////////////
/////////////////////////////////////// CARD DE BARRA DE VENTAS //////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// CARD  LINE ANUAL VENTAS //////////////////////////////////////////
/////////////////////////////////////// CARD  LINE ANUAL VENTAS //////////////////////////////////////////

if ($v_parametros[0] == 'card_line_ventas') {
	$total_venta = 0;

	if ($v_parametros[2] == '') {

		$year_actual = $year_actual;
		$year_anterior = $year_actual - 1;

	} else {

		$year_actual = $v_parametros[2];
		$year_anterior = $v_parametros[1];

	}

	$concatenado = $year_actual;

	$c_ventas_b = mysqli_query($conn, "SELECT  SUM(a.cantidad) as cantidad,  MONTH(b.fecha_sorteo) as mes FROM transaccional_ventas_general as a INNER JOIN sorteos_mayores as b ON a.id_sorteo = b.id WHERE YEAR(b.fecha_sorteo) = '$year_anterior'  AND a.estado_venta = 'APROBADO' AND a.cod_producto = 1 GROUP BY MONTH(b.fecha_sorteo)  ORDER BY MONTH(b.fecha_sorteo) ASC ");

	$i = 0;
	$v_sorteo_a = array();
	$v_sorteo_b = array();
	$v_meses = array();

	$concatenado_year_a = "";
	$concatenado_year_b = "";

	$v_meses[1] = 1;
	$v_meses[2] = 2;
	$v_meses[3] = 3;
	$v_meses[4] = 4;
	$v_meses[5] = 5;
	$v_meses[6] = 6;
	$v_meses[7] = 7;
	$v_meses[8] = 8;
	$v_meses[9] = 9;
	$v_meses[10] = 10;
	$v_meses[11] = 11;
	$v_meses[12] = 12;

	$v_sorteo_a[0] = 0;
	$v_sorteo_a[1] = 0;
	$v_sorteo_a[2] = 0;
	$v_sorteo_a[3] = 0;
	$v_sorteo_a[4] = 0;
	$v_sorteo_a[5] = 0;
	$v_sorteo_a[6] = 0;
	$v_sorteo_a[7] = 0;
	$v_sorteo_a[8] = 0;
	$v_sorteo_a[9] = 0;
	$v_sorteo_a[10] = 0;
	$v_sorteo_a[11] = 0;
	$v_sorteo_a[12] = 0;

	$v_sorteo_b[0] = 0;
	$v_sorteo_b[1] = 0;
	$v_sorteo_b[2] = 0;
	$v_sorteo_b[3] = 0;
	$v_sorteo_b[4] = 0;
	$v_sorteo_b[5] = 0;
	$v_sorteo_b[6] = 0;
	$v_sorteo_b[7] = 0;
	$v_sorteo_b[8] = 0;
	$v_sorteo_b[9] = 0;
	$v_sorteo_b[10] = 0;
	$v_sorteo_b[11] = 0;
	$v_sorteo_b[12] = 0;

	foreach ($c_ventas_b as $ventas) {
		$mes_venta = $ventas['mes'];
		$cantidad_venta = $ventas['cantidad'];
		$v_sorteo_a[$mes_venta] = $cantidad_venta;
		$v_sorteo_b[$mes_venta] = 0;
		$v_meses[$mes_venta] = $mes_venta;
	}

	$c_ventas_o = mysqli_query($conn, "SELECT   SUM(a.cantidad) as cantidad,  MONTH(b.fecha_sorteo) as mes FROM transaccional_ventas as a INNER JOIN sorteos_mayores as b ON a.id_sorteo = b.id WHERE YEAR(b.fecha_sorteo) = '$year_anterior'  AND a.estado_venta = 'APROBADO' AND a.cod_producto = 1 GROUP BY MONTH(b.fecha_sorteo)  ORDER BY MONTH(b.fecha_sorteo) ASC ");

	foreach ($c_ventas_o as $ventas) {
		$mes_venta = $ventas['mes'];
		$cantidad_venta = $ventas['cantidad'];

		if (!isset($v_sorteo_a[$mes_venta])) {
			$v_sorteo_a[$mes_venta] = $cantidad_venta;
		} else {
			$v_sorteo_a[$mes_venta] += $cantidad_venta;
		}

		if (!isset($v_sorteo_b[$mes_venta])) {
			$v_sorteo_b[$mes_venta] = 0;
		}

		$v_meses[$mes_venta] = $mes_venta;
	}

	$c_ventas_b = mysqli_query($conn, "SELECT SUM(a.cantidad) as cantidad,  MONTH(b.fecha_sorteo) as mes FROM transaccional_ventas_general as a INNER JOIN sorteos_mayores as b ON a.id_sorteo = b.id WHERE YEAR(b.fecha_sorteo) = '$year_actual'  AND a.estado_venta = 'APROBADO' AND a.cod_producto = 1 GROUP BY MONTH(b.fecha_sorteo)  ORDER BY MONTH(b.fecha_sorteo) ASC ");

	foreach ($c_ventas_b as $ventas) {
		$mes_venta = $ventas['mes'];
		$cantidad_venta = $ventas['cantidad'];

		if (!isset($v_sorteo_b[$mes_venta])) {
			$v_sorteo_b[$mes_venta] = $cantidad_venta;
		} else {
			$v_sorteo_b[$mes_venta] += $cantidad_venta;
		}

		if (!isset($v_sorteo_a[$mes_venta])) {
			$v_sorteo_a[$mes_venta] = 0;
		}

		$v_meses[$mes_venta] = $mes_venta;
		$i++;
	}

	$c_ventas_b = mysqli_query($conn, "SELECT SUM(a.cantidad) as cantidad,  MONTH(b.fecha_sorteo) as mes FROM transaccional_ventas as a INNER JOIN sorteos_mayores as b ON a.id_sorteo = b.id WHERE YEAR(b.fecha_sorteo) = '$year_actual'  AND a.estado_venta = 'APROBADO' AND a.cod_producto = 1 GROUP BY MONTH(b.fecha_sorteo)  ORDER BY MONTH(b.fecha_sorteo) ASC ");

	foreach ($c_ventas_b as $ventas) {
		$mes_venta = $ventas['mes'];
		$cantidad_venta = $ventas['cantidad'];
		if (!isset($v_sorteo_a[$mes_venta])) {
			$v_sorteo_a[$mes_venta] = 0;
		}

		if (!isset($v_sorteo_b[$mes_venta])) {
			$v_sorteo_b[$mes_venta] = $cantidad_venta;
		} else {
			$v_sorteo_b[$mes_venta] += $cantidad_venta;
		}

		$v_meses[$mes_venta] = $mes_venta;
	}

	$concatenado_meses = "";
	foreach ($v_meses as $mes) {
		$concatenado_year_a .= $v_sorteo_a[$mes] . "%";
		$concatenado_year_b .= $v_sorteo_b[$mes] . "%";
		$concatenado_meses .= $mes . "%";
	}

	$descripcion_titulo = '<b>COMPARATIVO DE VENTAS POR A??O (' . $year_anterior . ' Y ' . $year_actual . ') </b>';

	?>





<div class="card-header bg-dark text-white">

<input type="hidden" id="h_year_a" value = '<?php echo $year_anterior; ?>' name="">
<input type="hidden" id="h_year_b" value = '<?php echo $year_actual; ?>' name="">
<input type="hidden" id="concat_meses" value = '<?php echo $concatenado_meses; ?>' >
<input type="hidden" id="concat_year_a" value = '<?php echo $concatenado_year_a; ?>' >
<input type="hidden" id="concat_year_b" value = '<?php echo $concatenado_year_b; ?>' >


<div class="btn-group" style="width: 100%">
  <span class="bg-dark text-white" style="width: 100%" style="font-size: 18px" >
	<?php echo $descripcion_titulo; ?>
  </span>


<!--
<div  class="dropdown" >
<a class="btn btn-dark text-light fa fa-file-download" href="./dash_mayor_excel.php?info=3&y1=<?php //echo $year_anterior; ?>&y2=<?php //echo $year_actual; ?>" target="_blank"></a>
</div>
-->

<!--
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'excel', 'pdf', 'print'
        ],
            	"paging":   false,
    	"processing":   false,
    	"searching":   false,
        "ordering": false,
        "info":     false,


    } );
} );
</script>
-->


<div  class="dropdown" >
<button class="btn btn-dark text-light fa fa-info-circle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
</button>

<div class = "dropdown-menu dropdown-menu-right" >

<div style="height: 350px; overflow-y: scroll;">

<table class="table table-bordered table-striped" id="example" >
	<thead>
	<tr class = 'bg-dark text-white' >
	<th>SORTEO</th>
	<th>A??O <?php echo $year_anterior; ?></th>
	<th>A??O <?php echo $year_actual; ?></th>
	</tr>
	</thead>
	<tbody>
<?php

$tt1 = 0;
$tt2 = 0;

$v_meses = explode("%", $concatenado_meses);
	array_pop($v_meses);
	foreach ($v_meses as $v_mes) {

		echo "<tr>";
		echo "<td>" . $v_mes . "</td>";

		if (isset($v_sorteo_a[$v_mes])) {
			echo "<td>" . number_format($v_sorteo_a[$v_mes]) . "</td>";
			$tt1+=$v_sorteo_a[$v_mes];

		} else {
			echo "<td>0</td>";
		}

		if (isset($v_sorteo_b[$v_mes])) {
			echo "<td>" . number_format($v_sorteo_b[$v_mes]) . "</td>";
			$tt2+=$v_sorteo_b[$v_mes];

		} else {
			echo "<td>0</td>";
		}

		echo "</tr>";
	}

	?>
	</tbody>
	<tfoot>
		<tr>
			<th>TOTAL</th>
			<th><?php echo number_format($tt1)?></th>
			<th><?php echo number_format($tt2)?></th>
		</tr>
	</tfoot>
</table>

</div>

</div>
</div>


<div  class="dropdown" >
<button class="btn btn-dark text-light fa fa-ellipsis-v" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
</button>

<div class="dropdown-menu dropdown-menu-right p-4"  >


<div class="input-group" style="margin-bottom: 5px">
<div class="input-group-prepend"><div class="input-group-text">A??O 1</div></div>
<select class="form-control" id="card_line_year_select_1">
<option value="">Seleccione uno</option>
<?php
while ($r_years = mysqli_fetch_array($c_years)) {
		echo "<option value = '" . $r_years['year'] . "'>" . $r_years['year'] . "</option>";
	}
	?>
</select>
</div>


<div class="input-group">
<div class="input-group-prepend"><div class="input-group-text">A??O 2</div></div>
<select class="form-control" id="card_line_year_select_2">
<option value="">Seleccione uno</option>
<?php
while ($r_years2 = mysqli_fetch_array($c_years2)) {
		echo "<option value = '" . $r_years2['year'] . "'>" . $r_years2['year'] . "</option>";
	}
	?>
</select>
</div>

<div class="dropdown-divider"></div>
<button style="width: 350px" onclick="load_info_dash('card_line_ventas|'+ document.getElementById('card_line_year_select_1').value+'|'+ document.getElementById('card_line_year_select_2').value)" class="btn btn-dark btn-block">Aceptar</button>

</div>

</div>


</div>

</div>

<div class="card-body">
<canvas height="80px"  id="graf_line"></canvas>
</div>


<script type="text/javascript">
		cargar_line_ventas();
</script>

<?php

}

/////////////////////////////////////// CARD  LINE ANUAL VENTAS //////////////////////////////////////////
/////////////////////////////////////// CARD  LINE ANUAL VENTAS //////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////

?>

<script type="text/javascript">
	$('.dropdown-menu').click(function(e) {
    e.stopPropagation();
});
</script>
