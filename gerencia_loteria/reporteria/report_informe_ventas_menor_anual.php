<?php
require '../../template/header.php';
$years = mysqli_query($conn, "SELECT YEAR(fecha_registro) as year FROM utilidades_perdidas_sorteos GROUP BY YEAR(fecha_registro) ORDER BY YEAR(fecha_registro) DESC ");

?>

<style type="text/css">
@media print
{
#non-printable { display: none; }
#printable { display: block; }
}
</style>

<script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/Chart.bundle.js" ></script>
<script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/Chart.bundle.min.js" ></script>
<script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/Chart.js" ></script>
<script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/Chart.min.js" ></script>
<script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/moment.min.js" ></script>


<script type="text/javascript">

//////////////////////////////////
//// FUNCION CONSULTA FACTURA ////

function consultar_ventas(){

year = document.getElementById('select_year').value;
texto_s   = document.getElementById('select_year').options[document.getElementById('select_year').selectedIndex].text;
texto_e   = document.getElementById('select_entidad').options[document.getElementById('select_entidad').selectedIndex].text;
filtro 	  = document.getElementById('select_entidad').value;

div = document.getElementById('titulo');

$(".div_wait").fadeIn("fast");

if (filtro == 1) {
div.innerHTML = 'INFORME ACUMULADO DE VENTA DE LOTERIA MENOR '+texto_s+' <br> GENERAL';
}else{
div.innerHTML = 'INFORME ACUMULADO DE VENTA DE LOTERIA MENOR '+texto_s+' <br> '+texto_e;
};

token = Math.random();
consulta = 'informe_ventas_menor_anual_db.php?year='+year+"&filtro="+filtro+"&token="+token;
$("#div_respuesta").load(consulta);


}

////////////////////////////////////
////////////////////////////////////
</script>




<section style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  id="titulo" >INFORME ACUMULADO DE VENTA LOTERIA MENOR</h2>

<button class="btn btn-info" style="width: 100%" id="non-printable" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
  Seleccion de Parametros
</button>
</section>

<div class="collapse" id="collapseOne" style="width: 100%" align="center"  style="background-color: grey">
<div class="card card-body" id="non-printable" style="width: 100%">
<div class="input-group " style="margin:0px 0px 0px 0px; width: 50%">
<div class="input-group-prepend"><span class="input-group-text">Año: </span></div>
<select class="form-control" name="select_year" id = 'select_year' style="margin-right: 5px">
<?php
while ($reg_year = mysqli_fetch_array($years)) {
	echo "<option value = '" . $reg_year['year'] . "' >" . $reg_year['year'] . "</option>";
}
?>
</select>
<div class="input-group-prepend"><span class="input-group-text">Entidad: </span></div>
<select class="form-control" name="select_entidad" id = 'select_entidad' style="margin-right: 5px">
<option value="1">TODAS</option>
<option value="2">FVP Y ASOCIADOS</option>
<option value="3">BANCO DISTRIBUIDOR</option>
</select>

<div class="input-group-append">
<button class="btn btn-success" onclick="consultar_ventas()" > SELECCIONAR</button>
</div>
</div>
</div>
</div>



<div id="div_respuesta" class="card-body"></div>




<script>

function getRandomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}




function generar(fechas,u_fvp,u_b,filtro,tt_acum){

if (filtro == 1) {

document.getElementById("myChart").style.display = "block";

v_fechas  = fechas.split("%");
v_fvp 	  = u_fvp.split("%");
v_b 	  = u_b.split("%");
v_tt_acum = tt_acum.split("%");

new Chart(document.getElementById("myChart"), {
  type: 'line',
  data: {
    labels: v_fechas,
    datasets: [{
        data: v_fvp,
        label: "FVP Y ASOCIADOS",
        borderColor: "#3e95cd",
        pointRadius: 5,
		pointHoverRadius: 5,
        fill: false
      }, {
        data: v_b,
        label: "BANRURAL",
        borderColor: "#8e5ea2",
        pointRadius: 5,
		pointHoverRadius: 5,
        fill: false
      }, {
        data: v_tt_acum,
        label: "UTILIDAD TOTAL",
        borderColor: "#d84b13",
        pointRadius: 5,
		pointHoverRadius: 5,

        fill: false
      }
    ]
  },
  options: {
    title: {
      display: true,
      text: 'UTLIDADES Y PERDIDAS ANUALES ACUMULADAS'
    }
  }
});

}



if (filtro == 2) {

document.getElementById("myChart").style.display = "block";

v_fechas = fechas.split("%");
v_fvp = u_fvp.split("%");


new Chart(document.getElementById("myChart"), {
  type: 'line',
  data: {
    labels: v_fechas,
    datasets: [{
        data: v_fvp,
        label: "FVP Y ASOCIADOS",
        borderColor: "#3e95cd",
        pointRadius: 5,
		pointHoverRadius: 5,

        fill: false
      }
    ]
  },
  options: {
    title: {
      display: true,
      text: 'UTLIDADES Y PERDIDAS ANUALES'
    }
  }
});

}



if (filtro == 3) {

document.getElementById("myChart").style.display = "block";

v_fechas = fechas.split("%");
v_b = u_b.split("%");


new Chart(document.getElementById("myChart"), {
  type: 'line',
  data: {
    labels: v_fechas,
    datasets: [{
        data: v_b,
        label: "BANRURAL",
        borderColor: "#8e5ea2",
        pointRadius: 5,
		pointHoverRadius: 5,

        fill: false
      }
    ]
  },
  options: {
    title: {
      display: true,
      text: 'UTLIDADES Y PERDIDAS ANUALES'
    }
  }
});

}



}

</script>



<?php

if (isset($_POST['generar_excel'])) {

	$year = $_POST['generar_excel'];

	require_once '../../assets/phpexcel/Classes/PHPExcel/IOFactory.php';

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);

	$filtro = 1;
	$acumulado_banco = 0;
	$acumulado_fvp = 0;
	$acumulado_tt = 0;
	$utilidad_perdida_f = 0;
	$utilidad_perdida_b = 0;
	$concat_fecha = "";
	$concat_utlididades_fvp = "";
	$concat_utlididades_banco = "";
	$concat_utlididades_acumuladas = "";
	$a = 0;

	$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
	$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'INFORME ACUMULADO DE VENTA DE LOTERIA MENOR ' . $year);

	$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'SORTEO');
	$objPHPExcel->getActiveSheet()->SetCellValue('B3', 'FECHA');
	$objPHPExcel->getActiveSheet()->SetCellValue('C3', 'NUMERO');
	$objPHPExcel->getActiveSheet()->SetCellValue('D3', 'ENTIDAD');

	$objPHPExcel->getActiveSheet()->SetCellValue('E3', 'VENTA NETA');
	$objPHPExcel->getActiveSheet()->SetCellValue('F3', 'PROVISION DE PAGO');
	$objPHPExcel->getActiveSheet()->SetCellValue('G3', 'UTILIDAD');
	$objPHPExcel->getActiveSheet()->SetCellValue('H3', 'ACUMULADO');

	$consulta_sorteos = mysqli_query($conn, "SELECT DISTINCT(a.id_sorteo) as id_sorteo, b.fecha_sorteo  FROM utilidades_perdidas_sorteos as a INNER JOIN sorteos_menores as b ON a.id_sorteo = b.id WHERE YEAR(b.fecha_sorteo) = '$year' AND a.tipo_loteria = 2 GROUP BY a.id_sorteo ORDER BY a.id_sorteo ASC ");

	$bandera = 0;
	$f = 4;
	while ($reg_sorteos = mysqli_fetch_array($consulta_sorteos)) {

		$id_sorteo = $reg_sorteos['id_sorteo'];
		$fecha_sorteo = $reg_sorteos['fecha_sorteo'];
		$utilidad_perdida_sorteo = 0;

		$consulta_num = mysqli_query($conn, "SELECT numero_premiado_menor FROM sorteos_menores_premios WHERE sorteos_menores_id = '$id_sorteo' AND premios_menores_id = '1' ");
		$ob_numero = mysqli_fetch_object($consulta_num);
		$numero_premiado = $ob_numero->numero_premiado_menor;

		if ($bandera == 0) {
			$style = "#f2f2f2";
			$bandera = 1;
		} else {
			$style = "#ffffff";
			$bandera = 0;
		}

		$date_f = strtotime($fecha_sorteo);
		$datef = date('d-M', $date_f);
		$datef = strftime("%d %B", strtotime($datef));

		$v_date = explode(" ", $datef);
		$mes = strtoupper(substr($v_date['1'], 0, 3));

		if ($mes == 'JAN') {
			$mes = 'ENE';
		} elseif ($mes == 'AUG') {
			$mes = 'AGO';
		} elseif ($mes == 'DEC') {
			$mes = 'DIC';
		}

		$datef = $v_date[0] . " " . $mes;

		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $f, $id_sorteo);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $f, $datef);
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $f, $numero_premiado);

		if ($concat_fecha == '') {
			$concat_fecha = $fecha_sorteo;
		} else {
			$concat_fecha .= "%" . $fecha_sorteo;
		}

		if ($filtro == 1 OR $filtro == 2) {

///////////////////////////////////////////////////////////////////////////////
			////////////////////////// CONSULTA UTILIDADES FVP //////////////////////////
			$consulta_utilidades_banco = mysqli_query($conn, "SELECT a.id_sorteo, a.id_entidad, SUM(a.credito_pani) as credito_pani, SUM(a.provision_pago) as provision_pago, SUM(a.utilidad_perdida) as utilidad_perdida, a.tipo_loteria, a.fecha_registro, c.nombre_empresa, b.fecha_sorteo FROM utilidades_perdidas_sorteos as a INNER JOIN sorteos_menores as b INNER JOIN empresas as c ON a.id_sorteo = b.id AND a.id_entidad = c.id WHERE c.distribuidor = 'NO' AND a.id_sorteo = '$id_sorteo'  AND YEAR(b.fecha_sorteo) = '$year' AND a.tipo_loteria = 2 ");

			if ($consulta_utilidades_banco == FALSE) {
				echo mysqli_error($conn);
			}

			if (mysqli_num_rows($consulta_utilidades_banco) > 0) {

				$ob_consulta_utilidades_banco = mysqli_fetch_object($consulta_utilidades_banco);
				$id_sorteo_p = $ob_consulta_utilidades_banco->id_sorteo;
				$fecha_sorteo_p = $ob_consulta_utilidades_banco->fecha_sorteo;
				$credito_pani_p = $ob_consulta_utilidades_banco->credito_pani;
				$provision_pago_p = $ob_consulta_utilidades_banco->provision_pago;
				$utilidad_perdida_f = $ob_consulta_utilidades_banco->utilidad_perdida;
				$nombre_empresa_p = $ob_consulta_utilidades_banco->nombre_empresa;

				$acumulado_fvp += $utilidad_perdida_f;

				if ($filtro == 1) {
					$objPHPExcel->getActiveSheet()->SetCellValue('D' . $f, 'ASOCIADOS');
				}

				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $f, number_format($credito_pani_p, "2"));
				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $f, number_format($provision_pago_p, "2"));

				$v_utilidad_fvp[$a] = $utilidad_perdida_f;

				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $f, number_format($utilidad_perdida_f, "2"));

				if ($concat_utlididades_fvp == '') {
					$concat_utlididades_fvp = $acumulado_fvp;
				} else {
					$concat_utlididades_fvp .= "%" . $acumulado_fvp;
				}

				$objPHPExcel->getActiveSheet()->SetCellValue('H' . $f, number_format($acumulado_fvp, "2"));

			}

////////////////////////// CONSULTA UTILIDADES FVP //////////////////////////
			///////////////////////////////////////////////////////////////////////////////

		}

		if ($filtro == 1 OR $filtro == 3) {

			$f++;

///////////////////////////////////////////////////////////////////////////////
			////////////////////////// CONSULTA UTILIDADES BANCO //////////////////////////
			$consulta_utilidades_banco = mysqli_query($conn, "SELECT a.id_sorteo, a.id_entidad, SUM(a.credito_pani) as credito_pani, SUM(a.provision_pago) as provision_pago, SUM(a.utilidad_perdida) as utilidad_perdida, a.tipo_loteria, a.fecha_registro, c.nombre_empresa, b.fecha_sorteo FROM utilidades_perdidas_sorteos as a INNER JOIN sorteos_menores as b INNER JOIN empresas as c ON a.id_sorteo = b.id AND a.id_entidad = c.id WHERE c.distribuidor = 'SI' AND a.id_sorteo = '$id_sorteo'  AND YEAR(b.fecha_sorteo) = '$year' AND a.tipo_loteria = 2 ");

			if (mysqli_num_rows($consulta_utilidades_banco) > 0) {

				$ob_consulta_utilidades_banco = mysqli_fetch_object($consulta_utilidades_banco);
				$id_sorteo_p = $ob_consulta_utilidades_banco->id_sorteo;
				$fecha_sorteo_p = $ob_consulta_utilidades_banco->fecha_sorteo;
				$credito_pani_p = $ob_consulta_utilidades_banco->credito_pani;
				$provision_pago_p = $ob_consulta_utilidades_banco->provision_pago;
				$utilidad_perdida_b = $ob_consulta_utilidades_banco->utilidad_perdida;
				$nombre_empresa_p = $ob_consulta_utilidades_banco->nombre_empresa;

				$acumulado_banco += $utilidad_perdida_b;

				$v_utilidad_b[$a] = $utilidad_perdida_b;

				$objPHPExcel->getActiveSheet()->SetCellValue('D' . $f, $nombre_empresa_p);

				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $f, number_format($credito_pani_p, "2"));
				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $f, number_format($provision_pago_p, "2"));
				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $f, number_format($utilidad_perdida_b, "2"));

				if ($concat_utlididades_banco == '') {
					$concat_utlididades_banco = $acumulado_banco;
				} else {
					$concat_utlididades_banco .= "%" . $acumulado_banco;
				}

				$objPHPExcel->getActiveSheet()->SetCellValue('H' . $f, number_format($acumulado_banco, "2"));

			}

////////////////////////// CONSULTA UTILIDADES BANCO //////////////////////////
			///////////////////////////////////////////////////////////////////////////////

		}

		$utilidad_perdida_sorteo = $utilidad_perdida_f + $utilidad_perdida_b;
		$acumulado_tt = $acumulado_fvp + $acumulado_banco;

		$f++;

		if ($filtro == 1) {

			$objPHPExcel->getActiveSheet()->mergeCells('D' . $f . ':F' . $f);
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $f, "UTILIDAD O PERDIDA DEL SORTEO");
			$objPHPExcel->getActiveSheet()->SetCellValue('G' . $f, number_format($utilidad_perdida_sorteo, "2"));
			$objPHPExcel->getActiveSheet()->SetCellValue('H' . $f, number_format($acumulado_tt, "2"));

			if ($concat_utlididades_acumuladas == '') {
				$concat_utlididades_acumuladas = $acumulado_tt;
			} else {
				$concat_utlididades_acumuladas .= "%" . $acumulado_tt;
			}

		}

		$conteo_sorteos = $a;
		$v_sorteos[$a] = $id_sorteo;
		$a++;
		$f++;
	}

	$conteo_sorteos++;
	$a = 0;

	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=\"INFORME DE COMPRADORES DE LOTERIA MAYOR SORTEO " . $id_sorteo . ".xlsx\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	ob_clean();
	$objWriter->save("php://output");

}

?>