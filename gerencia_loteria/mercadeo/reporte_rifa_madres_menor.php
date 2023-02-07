<?php
header('Content-Type: text/html; charset=UTF-8');
setlocale(LC_MONETARY, 'es_HN');
//date_default_timezone_set("America/Tegucigalpa");
require '../../template/header.php';



?>


<style type="text/css">
@media print{

table thead tr th,table tbody tr td{
font-size:10pt;
}

body {
    margin-top: -70px;
}


#fila_delegados{
background-color: #cccccc !important;
-webkit-print-color-adjust: exact;
}

#grey_row{
background-color: #cccccc !important;
-webkit-print-color-adjust: exact;
}

}

</style>


<body>



<form method="POST" autocomplete="off">

<br>

<div id="non-printable" class="alert alert-info">
  <h3 align="center">REPORTE DE MADRES GANADORAS 2021</h3>

<a style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
  Seleccion de Parametros
</a>

<div class="collapse" id="collapse1">
<div >

<table class="table table-bordered">
    <tr>
        <th>Sorteo</th>
        <th width="20%">Accion</th>
    </tr>
    <tr>
<td>
<select name="sorteo" class = 'form-control' style="width: 100%">
<option value="3320">3320</option>
<option value="3321">3321</option>
<option value="3322">3322</option>
<option value="3323">3323</option>
</select> 
</td>


<td align="center">
<input type="submit" name="seleccionar" class="btn btn-primary" style="background-color: #005c7a;" value="Seleccionar">            
</td>       
</tr>
</table>
</div>
</div>

</div>

<hr>
<br>



<?php 


if (isset($_POST['seleccionar'])) {
    

    $sorteo = $_POST['sorteo'];
    
    $consul_fecha_vence = mysqli_query($conn, " SELECT  fecha_sorteo, vencimiento_sorteo as fecha_vencimiento, lugar_captura FROM sorteos_menores WHERE id = $sorteo ");
    while ($ro = mysqli_fetch_array($consul_fecha_vence)) {
        $fecha_sorteo = $ro['fecha_sorteo'];
        $fecha_vencimiento = $ro['fecha_vencimiento'];
        $lugar_captura = $ro['lugar_captura'];}
    
    $v_fecha = explode("-", $fecha_sorteo);
    $year = $v_fecha[0];
    $month = $v_fecha[1];
    $day = $v_fecha[2];
    $diadate = $day;
    
    $date = $fecha_sorteo;
    $nameOfDay = date('D', strtotime($date));
    
    if ($nameOfDay == "Sun") {
        $dia = "DOMINGO";
    } elseif ($nameOfDay == "Mon") {
        $dia = "LUNES";
    } elseif ($nameOfDay == "Tue") {
        $dia = "MARTES";
    } elseif ($nameOfDay == "Wed") {
        $dia = "MIERCOLES";
    } elseif ($nameOfDay == "Thu") {
        $dia = "JUEVES";
    } elseif ($nameOfDay == "Fri") {
        $dia = "VIERNES";
    } elseif ($nameOfDay == "Sat") {
        $dia = "SÁBADO";
    }
    
    $meses = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE");
    $mes = $meses[$month - 1];
    $ano = $year;
    


?>



<div class="card" style="width: 100%;" >

<div class="card-header" >

<table width="100%" >
<tr>
<td width="30%" align="left" style="vertical-align: top"><img src="../../template/images/Logo_LoteriaNacional.png"  align="center"  border="0"  width="80%" ></td>
<td width="40%" align="center">
<h4   style=" text-align: center;">REPORTE OFICIAL DE MADRES GANADORAS DE INCENTIVOS - SORTEO DE LOTERIA MENOR NO. <?php echo $sorteo; ?></h4>

<?php

if ($lugar_captura == "") {
	echo "TEGUCIGALPA, M.D.C.";
} else {
	echo strtoupper($lugar_captura) . ", ";
}

?>

 <?php echo $dia . " " . $diadate . " " . $mes . " DE " . $ano; ?>
</td>
<td width="30%" style="text-align: right;" align="right"><img src="../../template/images/PANI_1.jpg" align="center"  border="0"  width="80%" ></td>
</tr>
</table>


</div>

</div>

<br><br><br>
  <div align="center" style="width: 100%;"  >
  <table border="1" class="table table-bordered table-sm"  >

      <thead align="center">
        <tr>
        <th width="5%" >No.</th>
        <th  >Identidad</th>
        <th  >Nombre</th>
        <th  >Telefono</th>
        <th  >Departamento</th>
        <th  >Municipio</th>
        <th  >Ticket</th>
        <th  >Descripcion Premio</th>
        </tr>
      </thead>
      <tbody>
         <?php
$query_ganadores = mysqli_query($conn, "SELECT a.id_vendedor, a.nombre_vendedor, a.ticket_electronico, a.descripcion_incentivo, b.telefono,b.departamento, b.municipio
                FROM sorteos_menores_incentivos as a INNER JOIN vendedores_incentivos as b ON a.id_vendedor = b.identidad AND a.id_sorteo = b.sorteo
                WHERE
                a.id_sorteo=$sorteo and
                a.id_vendedor is not null
                order by a.id asc;");
echo mysqli_error($conn);
$cont = 1;

$par = 0;
while ($filas_ganadores = mysqli_fetch_array($query_ganadores)) {

	$id_vendedor = $filas_ganadores['id_vendedor'];
	$nombre_vendedor = $filas_ganadores['nombre_vendedor'];
	$ticket_electronico = $filas_ganadores['ticket_electronico'];
	$descripcion_incentivo = $filas_ganadores['descripcion_incentivo'];


	echo "<td align='center' style='page-break-inside: avoid' >" . $cont . "</td>
                <td align='center' ><label>" . $id_vendedor . "</label></td>
                <td align='center' >" . $nombre_vendedor . "</td>
                <td align='center' >" . $filas_ganadores['telefono'] . "</td>
                <td align='center' >" . $filas_ganadores['departamento'] . "</td>
                <td align='center' >" . utf8_decode($filas_ganadores['municipio']) . "</td>
                <td align='center' >" . $ticket_electronico . "</td>
                <td align='center' >" . $descripcion_incentivo . "</td>
              </tr>";
	$cont++;
}
?>
  </tbody>
  </table>
<br><br><br><br><br><br>

<div class="row">
  <div class="col-sm-12" align="center">
    _______________________________
  </div>
</div>
<div class="row">
  <div class="col-sm-12" align="center">
    GERENCIA DE COMERCIALIZACIÓN
  </div>
</div>
</div>
</body>

<?php 
}
?>


</form>

<script type="text/javascript">
//    window.print();
//    setTimeout(window.close, 1000) ;
</script>
