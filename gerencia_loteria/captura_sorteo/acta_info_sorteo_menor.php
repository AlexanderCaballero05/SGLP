<?php
setlocale(LC_MONETARY, 'es_HN');

require '../../template/header.php';
//con_pani();
$sorteo = $_GET['sorteo'];

$consul_fecha_vence = mysqli_query($conn, " SELECT a.monto, b.descripcion_premios, b.tipo_serie, b.clasificacion FROM `sorteos_menores_premios` a, premios_menores b WHERE sorteos_menores_id=$sorteo and a.premios_menores_id=b.id order by b.clasificacion, b.tipo_serie, b.id asc");

$query_delegados = mysqli_query($conn, "SELECT a.nombre_completo, b.descripcion, a.originario, a.identidad  FROM `cs_autoridades_sorteo` a , cs_tipo_representacion b   WHERE sorteo=$sorteo and a.puesto_labora=b.id ");

$dias = array("DOMINGO", "LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SÁBADO");
$dia = $dias[date("w")];
$meses = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE");
$mes = $meses[date("m") - 1];
$ano = date("Y");
$diadate = date("d");

$query_info_sorteo = mysqli_query($conn, "SELECT fecha_sorteo, vencimiento_sorteo from sorteos_menores where id=$sorteo");
while ($row_info_sorteo = mysqli_fetch_array($query_info_sorteo)) {
	$fecha_sorteo = $row_info_sorteo['fecha_sorteo'];
	$fecha_sorteo = date("d-m-Y", strtotime($fecha_sorteo));
	$fecha_vencimiento_sorteo = $row_info_sorteo['vencimiento_sorteo'];
	$fecha_vencimiento_sorteo = date("d-m-Y", strtotime($fecha_vencimiento_sorteo));}

?>


<style type="text/css">
@media print{

    p{
        font-size:20pt;
    }

    table{
       font-size:20pt;
    }


}
</style>


<head>
              <meta charset="UTF-8">
              <title>PANI: Informacion del Sorteo de lotería Menor No. <?php echo $sorteo; ?> </title>
              <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body>

<form  id="captura_menor" method="post">




<div class="card" style="margin-left: 50px; margin-right: 50px">
<div class="card-header" align="center">



<table width="100%" >
<tr>
<td width="30%" align="left"><img src="../../template/images/Logo_LoteriaNacional.png"  align="center"  border="0"  width="80%" /></td>
<td width="40%" align="center">
<h2 align="center">INFORMACION DEL SORTEO DE LOTERIA MENOR NO. <?php echo $sorteo; ?><br>fecha del sorteo: <?php echo $fecha_sorteo; ?>   <br>Fecha Vencimiento: <?php echo $fecha_vencimiento_sorteo; ?></h2>
</td>
<td width="30%" style="text-align: right;" align="right"><img src="../../template/images/PANI_1.jpg" align="center"  border="0"  width="80%" /></td>
</tr>
</table>



</div>

<div class="card-body">

<table class="table table-bordered" >
<tr>
  <th colspan="3">
     Delegados del sorteo
  </th>
</tr>

<tr>
  <td align="center" ><b> Nombre </b> </td>
  <td align="center" ><b> Representación </b> </td>
  <td align="center" ><b> Identidad </b> </td>
</tr>


<?php
$contador = 0;
$monto_premio_serie = 0;
while ($ro = mysqli_fetch_array($query_delegados)) {
	$nombre = $ro['nombre_completo'];
	$representación = $ro['descripcion'];
	$originario = $ro['identidad'];
	echo "<tr><td align='left' ><label>" . $nombre . "</label></td>
<td align='left' >" . $representación . "</td>
<td align='left' >" . $originario . "</td></tr>";
}
?>

</table>


<br>



<table class="table table-bordered">
<tr>
  <th colspan="3">Tabla de Premios</th>
</tr>
<tr>
  <th >No.</th>
  <th >Descripción del Premio</th>
  <th >Valor</th>
</tr>

<?php
$contador = 0;
$monto_premio_serie = 0;
while ($ro = mysqli_fetch_array($consul_fecha_vence)) {
	$monto_premio = $ro['monto'];
	$descripcion_premio = $ro['descripcion_premios'];
	$tipo_serie_premio = $ro['tipo_serie'];
	$clasificacion_premio = $ro['clasificacion'];

	if ($clasificacion_premio == 'SERIE') {
		$monto_premio_serie = $monto_premio_serie + $monto_premio;
		$contador++;}

	echo "<tr><td align='center' ><label>" . $contador . "</label></td>
<td align='center' >" . $descripcion_premio . "</td>
<td align='right' > L.  " . number_format($monto_premio, 2, '.', ',') . "</td></tr>";
}
echo "</tbody><tr><td colspan='3' align='center'> -- </td></tr><tr><td colspan='2' align='center'> Total de Premios por Seríe</td><td align='right'> L.  " . number_format($monto_premio_serie, 2, '.', ',') . "</td></tr>";
?>


</table>

</div>

</div>

</form>


<script type="text/javascript">
document.title="SISTEMA DE LOTERIA";
</script>

</body>