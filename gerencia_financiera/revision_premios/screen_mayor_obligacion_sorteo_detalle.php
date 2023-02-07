<?php
require '../../template/header.php';
$usuario_id = $_SESSION['usuario'];
$sorteo = $_GET['sorteo'];
//$sorteo=1213;

function diferenciaDias($inicio, $fin) {
	$inicio = strtotime($inicio);
	$fin = strtotime($fin);
	$dif = $fin - $inicio;
	$diasFalt = ((($dif / 60) / 60) / 24);
	return ceil($diasFalt);
}

?>
<script type="text/javascript">
 			 $(".div_wait").fadeIn("fast");
</script>
 <style type="text/css" media="print">
 @page
 {
    size: A4;
    landscape;
 }

 th, td { padding-bottom: 0px;   border-spacing: 0; font-family: Arial; font-size: 09pt; }

</style>
<style type="text/css">
.div_wait
{
  display: none;
  position: fixed;
  left: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  z-index: 9999;
  background-color: black;
  opacity:0.5;
  background: url(../../template/images/wait.gif) center no-repeat #fff;
}

@media print {
  #no_print
  {
    display: none;
  }
}

</style>
<form method="post" id="_revision_premios"  class="" name="_revision_premios">
<div class="container-fluid">
<div id='div_wait'></div>
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Detalle de Obligacion de Pagos de Lotería Mayor del Sorteo # <?php echo $sorteo; ?>  </h3>   <br></section>
 <?php
$cantidad_acumulado_final = 0;
$neto_acumulado_final = 0;

echo "<table width='96%'  id='tableinfo' align='center' class='table table-hover table-sm table-bordered'>
                <thead><th>No.</th>
                      <th>Descripción de Pliegos</th>
                      <th>Cantidad de Decimos</th>
                      <th>Total a Pagar</th>
                </thead><tbody>";

$query_numeros = mysqli_query($conn, "(SELECT total valor_premio , numero, tipo_pago tipo_premio , (count(*) *10) cantidad, sum(total) total, sum(impto) total_impto, sum(neto) total_neto, (count(*) *10) num_dec, decimo, (sum(total)/10) pago_por_terminacion FROM archivo_pagos_mayor WHERE tipo_pago in ('U', 'E') and sorteo=$sorteo GROUP BY numero order by total desc
          )UNION
          ( select total as valor_premio, numero, tipo_pago as tipo_premio, 10 as cantidad, sum(total) total, sum(impto) total_impto, sum(neto) total_neto, count(numero)*10 num_dec, (count(*) *10)  decimos, total pago_por_terminacion from archivo_pagos_mayor where sorteo=$sorteo and tipo_pago='T' GROUP BY pago_terminacion) order by valor_premio desc ;");

$total_acumulado = 0;
$impto_acumulado = 0;
$neto_acumulado = 0;
$cantidad_acumulado = 0;
$contador = 1;
$numero_termi = 0;
$pago_por_decimo = 0;
$total_premio = 0;
$conteo_termi = 0;
$detalle = '';
$cantidad_tt = 0;
while ($row_numeros = mysqli_fetch_array($query_numeros)) {
	$neto_acumulado = $neto_acumulado + $row_numeros['total_neto'];
	$pago_por_decimo = number_format($row_numeros['pago_por_terminacion'], 2, '.', ','); //($row_numeros['total']/$row_numeros['decimos']);
	$numero_termi = "";
	$total_premio = 0;

	if ($row_numeros['tipo_premio'] == 'E') {
		$cantidad_acumulado = 1;
		$cantidad_billetes = 1;
		$numero = $row_numeros['valor_premio'];
		$palabra = 'Valor Monetario de Premio de especies ';
		$numero_termi = $row_numeros['numero'];
		$total_premio = number_format(($row_numeros['total_neto']), 2, '.', ',');
		$decimos_especie = $row_numeros['decimo'];
		$detalle = $palabra . "   " . $numero_termi . " por L. " . $total_premio . " pagados al decimo numero " . $decimos_especie;
	} else if ($row_numeros['tipo_premio'] == 'U') {
		$cantidad_acumulado = $cantidad_acumulado + $row_numeros['num_dec'];
		$cantidad_billetes = $row_numeros['num_dec'];
		$numero = $row_numeros['numero'];
		$palabra = 'Premio de Urna ';
		$query_pago_urna = mysqli_query($conn, "SELECT CEIL(numero_premiado_mayor) numero_premiado_mayor,  b.total monto FROM sorteos_mayores_premios a, archivo_pagos_mayor b WHERE b.sorteo=a.sorteos_mayores_id and a.numero_premiado_mayor=b.numero and  a.numero_premiado_mayor=$numero and a.sorteos_mayores_id=$sorteo  ");
		while ($_row_urna = mysqli_fetch_array($query_pago_urna)) {
			$numero_termi = $_row_urna['numero_premiado_mayor'];
			$pago_por_decimo = number_format($_row_urna['monto'], 2);
			$total_premio = number_format(($_row_urna['monto'] / 10), 2, '.', ',');
		}
		$detalle = $palabra . "   " . $numero_termi . " por L. " . $pago_por_decimo . " el billete y L.  " . $total_premio . " el decimo";
	} else {
		$palabra = 'Premio por Terminación';
		$cantidad_acumulado = $cantidad_acumulado + $row_numeros['decimo'];
		$cantidad_billetes = $row_numeros['decimo'];
		$query_pago_termi = mysqli_query($conn, "SELECT b.numero_premiado_mayor_desc  , pago_terminacion, (pago_terminacion/10) pago_por_decimo, (count(numero)*10) decimos ,  sum(pago_terminacion) valor_pagar
                                                           FROM archivo_pagos_mayor a, sorteos_mayores_premios b
                                                           WHERE a.sorteo=b.sorteos_mayores_id and pago_terminacion=$pago_por_decimo and a.sorteo = $sorteo and a.tipo_pago='T' and b.respaldo='terminacion' and a.pago_terminacion=b.monto group by pago_terminacion ;");

		while ($_row_termi = mysqli_fetch_array($query_pago_termi)) {
			$numero_termi = $_row_termi['numero_premiado_mayor_desc'];
			$pago_por_decimo = $_row_termi['pago_terminacion'];
			$total_premio = number_format(($_row_termi['pago_por_decimo']), 2, '.', ',');
		}

		$detalle = $palabra . "   " . $numero_termi . " por L. " . $pago_por_decimo . " el billete y L.  " . $total_premio . " el decimo";
	}

	echo "<tr><td align='center'>" . $contador . "</td>
                            <td align='left'>  " . $detalle . "</td>
                            <td align='center'>" . $cantidad_billetes . "</td>
                            <td align='right'>" . number_format($row_numeros['total_neto'], 2, '.', ',') . "</td></tr>";
	$contador++;

	$cantidad_tt += $cantidad_billetes;

}

echo "<tr><td colspan='2' align='center'><label>Subtotal</label></td><td align='center'><label>" . number_format($cantidad_tt) . "</label></td><td align='right'><label>" . number_format($neto_acumulado, 2, '.', ',') . "</label></td></tr>";

$cantidad_acumulado_final = $cantidad_acumulado_final + $cantidad_acumulado;
$neto_acumulado_final = $neto_acumulado_final + $neto_acumulado;

echo "<tr><td></td>
                  <td colspan='4' align='center'><label> -- </label></td></tr>
              <tr class='table-success'><td></td>
                  <td colspan='1' align='center'><label> Total</label></td>
                  <td align='center'><label>" . number_format($cantidad_tt) . "</label></td>
                  <td align='right'><label>" . number_format($neto_acumulado_final, 2, '.', ',') . "</label></td></tr></table>";

?>
 </section>
 <section id="no_print">
    <div align="center">
      <button class="btn btn-danger btn-lg"  onclick='window.print();' type="button" id="no_print"> <i class="fas fa-print"></i> Imprimir </button>
      <a class="btn btn-success btn-lg text-white"  role="button" href="_EXCEL_mayor_detalle_pagos.php?sorteo=<?php echo $sorteo; ?>" target="blank" id="no_print"> <i class="far fa-file-excel"></i> Exportar detalle a Excel </a>
    </div>
 </section>
</div>
</form>
<script type="text/javascript">
  $(".div_wait").fadeOut("fast");
</script>

