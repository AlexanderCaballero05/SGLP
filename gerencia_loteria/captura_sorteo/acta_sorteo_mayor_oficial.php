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
<h4   style=" text-align: center;">REPORTE OFICIAL DE PREMIOS DEL SORTEO DE LOTERIA MAYOR NO. <?php echo $sorteo; ?></h4>

<?php

if ($lugar_captura == "") {
	echo "TEGUCIGALPA, M.D.C.";
} else {
	echo strtoupper($lugar_captura) . ", ";
}

?>

 <?php echo $dia . " " . $diadate . " " . $mes . " DE " . $ano; ?>
</td>
<td width="30%" style="text-align: right;" align="right"><img src="../../template/images/logo-pani.png" align="center"  border="0"  width="80%" ></td>
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
        <th width="10%" >Billete</th>
        <th width="20%" >Valor del Premio</th>
        <th width="15%" >Descripcion Premio</th>
        <th width="10%" >Decimo</th>
        <th width="15%" >Estado</th>
        </tr>

      </thead>
      <tbody>
         <?php
                $query_ganadores=mysqli_query($conn,"SELECT desc_premio, numero_premiado_mayor premiado,  monto, decimos, descripcion_respaldo, respaldo
                FROM sorteos_mayores_premios
                where
                sorteos_mayores_id=$sorteo and
                respaldo !='terminacion' and monto>900
                order by monto desc, numero_premiado_mayor desc;");


      $cont=1;

      $par = 0;
       while ($filas_ganadores=mysqli_fetch_array($query_ganadores))
      {

         $numero_premiado=$filas_ganadores['premiado'];
         $descripcion_premio=$filas_ganadores['descripcion_respaldo'];
         $respal=$filas_ganadores['respaldo'];
         $dec=$filas_ganadores['decimos'];
         $desc_premio=$filas_ganadores['desc_premio'];

         $monto=$filas_ganadores['monto'];
         if ($monto>30000)  {  $impto=$monto*0.10;  } else  { $impto=0; }


        if ($filas_ganadores['decimos']==10 and $filas_ganadores['premiado'] !=47300 )  { $dec=10;  }  else  { $de=$filas_ganadores['decimos']; }

        $query_vendido=mysqli_query($conn,"SELECT * from ventas_distribuidor_mayor where sorteo=$sorteo and billete=$numero_premiado");
        if (mysqli_num_rows($query_vendido)>0)  {   $estado='Vendido';   }  else  {     $estado='No Vendido';     }

         $neto=$monto-$impto;
         if ($par == 0) {
         echo "<tr style='page-break-inside: avoid'>";
         $par++;
         }else{
         echo "<tr style='page-break-inside: avoid; background-color: #cccccc;' id = 'grey_row'>";
         $par = 0;
         }

         echo "<td align='center' style='page-break-inside: avoid' >".$cont."</td>
                <td align='center' ><label>".$filas_ganadores['premiado']."</label></td>
                <td align='center' >".number_format($monto, 2)."</td>
                <td align='center' >".$desc_premio."</td>
                <td align='center' >".$dec."</td>
                <td align='center' >".$estado."</td>
              </tr>";
      $cont++;
      }
                     ?>
  </tbody>
  </table>



<br><br><br>


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
<br><br>

<p align="left">
  Caducidad del sorteo: <?php echo $fecha_vencimiento; ?>. 
</p>


</div>
</body>
