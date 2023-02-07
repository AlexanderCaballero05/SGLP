<?php
setlocale(LC_MONETARY, 'es_HN');
//date_default_timezone_set("America/Tegucigalpa");
require('../../template/header.php');
$sorteo = $_GET['s'];
// echo $sorteo;

$consul_fecha_vence=mysqli_query($conn," SELECT  fecha_sorteo, fecha_vencimiento, lugar_captura FROM sorteos_mayores WHERE id = $sorteo ");
  while ($ro = mysqli_fetch_array($consul_fecha_vence))   {     $fecha_sorteo=$ro['fecha_sorteo'];  $fecha_vencimiento=$ro['fecha_vencimiento']; $lugar_captura=$ro['lugar_captura'];     }


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

<div class="card" style="width: 100%;" >

<div class="card-header" >

<table width="100%" >
<tr>
<td width="30%" align="left" style="vertical-align: top"><img src="../../template/images/Logo_LoteriaNacional.png"  align="center"  border="0"  width="80%" ></td>
<td width="40%" align="center">
<h4   style=" text-align: center;">REPORTE OFICIAL DE GANADORES DE INCENTIVOS DEL SORTEO DE LOTERIA MENOR NO. <?php echo $sorteo; ?></h4>

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

<br>
  <div align="center" style="width: 100%;"  >
  <table border="1" class="table table-bordered table-sm"  >

      <thead align="center">
        <tr>
        <th width="5%" >No.</th>
        <th width="10%" >Identidad</th>
        <th width="20%" >Nombre</th>
        <th width="10%" >Ticket</th>
        <th width="15%" >Descripcion Premio</th>
        </tr>
      </thead>
      <tbody>
         <?php
                $query_ganadores=mysqli_query($conn,"SELECT id_vendedor, nombre_vendedor, ticket_electronico, descripcion_incentivo
                FROM sorteos_menores_incentivos
                WHERE
                id_sorteo=$sorteo and
                id_vendedor is not null
                order by fecha_creacion asc;");


      $cont=1;

      $par = 0;
       while ($filas_ganadores=mysqli_fetch_array($query_ganadores))
      {

         $id_vendedor=$filas_ganadores['id_vendedor'];
         $nombre_vendedor=$filas_ganadores['nombre_vendedor'];
         $ticket_electronico=$filas_ganadores['ticket_electronico']; 
         $descripcion_incentivo=$filas_ganadores['descripcion_incentivo'];
 

         echo "<td align='center' style='page-break-inside: avoid' >".$cont."</td>
                <td align='center' ><label>".$id_vendedor."</label></td>
                <td align='center' >".$nombre_vendedor."</td>
                <td align='center' >".$ticket_electronico."</td>
                <td align='center' >".$descripcion_incentivo."</td>                
              </tr>";
      $cont++;
      }
                     ?>
  </tbody>
  </table>



<!--- br><br><br>


<table border="1" width="100%" style='page-break-inside: avoid;' >
<tr style='page-break-inside: avoid;'>
  <th style="text-align: center" colspan=" 2"><h2><b>DELEGADOS DEL SORTEO</b></h2></th>
</tr>

<tr style='page-break-inside: avoid;'>
  <td width="50%" align="center" valign="bottom"><br><br><br><br><br><br></td>
  <td width="50%" align="center" valign="bottom"><br><br><br><br><br><br></td>
</tr>
<tr id = 'fila_delegados' style='page-break-inside: avoid;'>
  <td align="center" style="background-color: #cccccc;"><b> GERENCIA FINANCIERA </b></td>
  <td align="center" style="background-color: #cccccc;"><b>  BANRURAL</b>  </td>
</tr>


<tr style='page-break-inside: avoid;'>
  <td width="50%" align="center"valign="bottom"><br><br><br><br><br><br></td>
  <td width="50%" align="center"> <br><br><br><br><br></td>
</tr>

<tr id = 'fila_delegados' style='page-break-inside: avoid;'>
  <td align="center" style="background-color: #cccccc;"><b> GOBERNACION </b></td>
  <td align="center" style="background-color: #cccccc;"><b>  GERENCIA DE LOTERIA</b>  </td>
</tr>

<tr style='page-break-inside: avoid;'>
  <td width="50%" align="center"valign="bottom"><br><br><br><br><br><br></td>
  <td width="50%" align="center"> <br><br><br><br><br></td>
</tr>

<tr id = 'fila_delegados' style='page-break-inside: avoid;'>
  <td align="center" style="background-color: #cccccc;"><b> ABOGADO Y NOTARIO </b></td>
  <td align="center" style="background-color: #cccccc;"><b></b>  </td>
</tr>

</table>
<br><br  -->

<!-- p align="left">
 Caducidad del sorteo: <?php echo $fecha_vencimiento; ?>. 
</p  -->


</div>
</body>
