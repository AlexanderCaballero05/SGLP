<?php
setlocale(LC_MONETARY, 'es_HN');

require('../../template/header.php');
$sorteo=$_GET['sorteo'];


$c_info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE id = '$sorteo' ");
$ob_sorteo     = mysqli_fetch_object($c_info_sorteo);
$fecha_sorteo  = $ob_sorteo->fecha_sorteo;
$lugar_captura = $ob_sorteo->lugar_captura;
$vencimiento_sorteo = $ob_sorteo->vencimiento_sorteo;


$v_fecha = explode("-", $fecha_sorteo);
$year    = $v_fecha[0];
$month   = $v_fecha[1];
$day     = $v_fecha[2];
$diadate = $day;

$date = $fecha_sorteo;
$nameOfDay = date('D', strtotime($date));

if ($nameOfDay == "Sun") {
$dia = "DOMINGO";
}elseif ($nameOfDay == "Mon") {
$dia = "LUNES";
}elseif ($nameOfDay == "Tue") {
$dia = "MARTES";
}elseif ($nameOfDay == "Wed") {
$dia = "MIERCOLES";
}elseif ($nameOfDay == "Thu") {
$dia = "JUEVES";
}elseif ($nameOfDay == "Fri") {
$dia = "VIERNES";
}elseif ($nameOfDay == "Sat") {
$dia = "SÁBADO";
}

$meses= array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO", "AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
$mes = $meses[$month - 1];
$ano = $year;




  $num_ganador=mysqli_query($conn," SELECT a.id, a.descripcion_premios , b.numero_premiado_menor premiado, a.pago_premio monto FROM premios_menores a, sorteos_menores_premios b WHERE b.sorteos_menores_id = $sorteo and a.id=b.premios_menores_id  and b.premios_menores_id=1 ");
  if (mysqli_num_rows( $num_ganador)>0)
  {
   while ($row3 = mysqli_fetch_array($num_ganador))  {   $numero_premiado=$row3['premiado'];     $premio_premiado=$row3['monto'];  } }  else  {    echo mysqli_error();  }

    $num_reves=mysqli_query($conn," SELECT a.id, b.numero_premiado_menor numero, a.pago_premio pago FROM premios_menores a, sorteos_menores_premios b  WHERE b.sorteos_menores_id = $sorteo and  tipo_serie='REVES' and clasificacion = 'NUMERO' AND a.id=b.premios_menores_id   ");
  if (mysqli_num_rows( $num_reves)>0) { while ($row2 = mysqli_fetch_array($num_reves))  {   $numero_reves=$row2['numero'];    $premio_reves=$row2['pago'];  } } else {  echo mysqli_error();  }

  $premiado_par=$premio_premiado+$premio_reves;
?>
<head>
<meta charset="UTF-8">
<title>PANI: Acta del Sorteo de lotería Menor No. <?php echo $sorteo; ?> </title>
<meta name="viewport" content="width=device-width, initial-scale=1">


<style type="text/css">
  @media print{

    p{
        font-size:12pt;
    }

    td{
       font-size:12pt;
    }

    th{
       font-size:12pt;
    }



table.table-bordered{
      border-color: black;
      border-width: 3pt;
}


#captura_menor{
margin-top: -50px;
}


#fila_delegados{
background-color: #cccccc !important;
-webkit-print-color-adjust: exact;
}

}
</style>

</head>



<body>
<form  id="captura_menor"  style="width:100%;" class="form-inline" form="role" method="post">



<div class="card" style="width: 100%">
<div class="card-header" align="center">



<table width="100%" >
<tr>
<td width="30%" align="left"><img src="../../template/images/Logo_LoteriaNacional.png"  align="center"  border="0"  width="80%" /></td>
<td width="40%" align="center">
<h2   style=" text-align: center;font-family: Arial;">INFORME OFICIAL DEL SORTEO DE LOTERIA MENOR NO. <?php echo $sorteo; ?></h2>
</td>
<td width="30%" style="text-align: right;" align="right"><img src="../../template/images/logo-pani.png" align="center"  border="0"  width="80%" /></td>
</tr>
</table>

</div>



<div class="card-body">
<br>

<?php

if ($numero_premiado==11 || $numero_premiado==00 || $numero_premiado==22  || $numero_premiado==33 || $numero_premiado==44  || $numero_premiado==55  ||  $numero_premiado==66  || $numero_premiado==77 || $numero_premiado==88 || $numero_premiado==99)
{
//////////////////////// condicion para numero par 11, 22, 33, 44, 55, 66, 77, 88, 99

    echo '<p >En el sorteo No. '.$sorteo.' de esta fecha '.$dia.'  '.$diadate.'  '.$mes.' DE '.$ano.'  resultó favorecido el número <b>'.$numero_premiado.'</b> con premio de L. '.number_format($premiado_par).' (Un Mil cien lempiras) por billete con la siguiente serie ganadora:  </p>';

    $cont=0; 
   
    $query_ganadores=mysqli_query($conn,"SELECT a.numero_premiado_menor, a.monto FROM sorteos_menores_premios a, premios_menores b WHERE sorteos_menores_id=$sorteo and a.premios_menores_id=b.id and b.clasificacion='SERIE'");
    while ($filas_ganadores=mysqli_fetch_array($query_ganadores))
    {
    $numero_ganador[$cont]=$filas_ganadores['numero_premiado_menor'] ;
    $monto[$cont]=$filas_ganadores['monto'] ;
    $cont++;
    }
?>
<div style="width:100%">
<table class="table table-bordered" width="100%">
<tr>

<th width="15%" >Seríe Número</th>
<th width="45%" >Distribuido por agencia</th>
<th width="15%" >Valor del premio</th>
<th width="15%" >Valor a pagar</th>

</tr>

<?php
$cont2=0; 
$monto_premio=0; $monto_pagar=0;
while (isset($numero_ganador[$cont2]))
{
      $query_num_Serie=mysqli_query($conn,"SELECT  a.agencia_banrural FROM ventas_distribuidor_menor a WHERE a.numero=$numero_premiado  and a.serie=$numero_ganador[$cont2] and a.sorteo=$sorteo ");
      if (mysqli_num_rows($query_num_Serie)>0)
      {
            while ($fila_suc = mysqli_fetch_array($query_num_Serie)) {
            $agencia_banrural=$fila_suc['agencia_banrural'];
            $monto_premio=$monto[$cont2];
            $monto_pagar=$monto[$cont2];
            }
      }else{



////////////////////////////////////////////////////////////////////
///////////////////// BUSQUEDA DE DISTRIBUCION /////////////////////

                  $c_distribucion = mysqli_query($conn,"SELECT nombre_seccional FROM distribucion_menor_bolsas_banco WHERE id_sorteo = '$sorteo' AND serie_inicial <= '$numero_ganador[$cont2]' AND serie_final >= '$numero_ganador[$cont2]' ");

                  if (mysqli_num_rows($c_distribucion) > 0) {
                  $ob_distribucion  = mysqli_fetch_object($c_distribucion);
                  $agencia_banrural = $ob_distribucion->nombre_seccional;
                  $monto_premio     = $monto[$cont2];
                  $monto_pagar      =   0;
                  if ( $agencia_banrural=='MATRIZ') {
                     $agencia_banrural='DISTRIBUIDO POR AGENCIA PANI';
                    
                  }

                  }else{

                  $c_distribucion = mysqli_query($conn,"SELECT nombre_seccional FROM distribucion_menor_numeros_banco WHERE id_sorteo = '$sorteo' AND numero = '$numero_premiado' AND serie_inicial <= '$numero_ganador[$cont2]' AND serie_final >= '$numero_ganador[$cont2]' ");

                  if (mysqli_num_rows($c_distribucion) > 0) {
                  $ob_distribucion  = mysqli_fetch_object($c_distribucion);
                  $agencia_banrural = $ob_distribucion->nombre_seccional;
                  }else{
                  $agencia_banrural='DISTRIBUIDO POR AGENCIA PANI';
                    
                  }

                  }

///////////////////// BUSQUEDA DE DISTRIBUCION /////////////////////
////////////////////////////////////////////////////////////////////

                    
             


                }

echo "<tr><td align='center' ><label>".$numero_ganador[$cont2]."</label></td>
<td align='center' >".$agencia_banrural."</td>
<td align='center' >".number_format($monto_premio, 2)."</td>
<td align='center' >".number_format($monto_pagar, 2)."</td></tr>";
$cont2++;
}
?>
</table>
</div>


<?php
$query_autoridades=mysqli_query($conn,"SELECT identidad, nombre_completo, puesto_labora, empresa, originario  FROM cs_autoridades_sorteo WHERE sorteo=$sorteo order by puesto_labora asc");
if (mysqli_num_rows($query_autoridades)>0)
{
$cont4=0;
while ($row_autori=mysqli_fetch_array($query_autoridades))
{
$identidad[$cont4]=$row_autori['identidad'];
$nombre_completo[$cont4]=$row_autori['nombre_completo'];
$puesto_labora[$cont4]=$row_autori['puesto_labora'];
$empresa[$cont4]=$row_autori['empresa'];
$originario[$cont4]=$row_autori['originario'];
$cont4++;
}

}

}
else
{


// echo '<p style="font-family: Arial; font-size: 11pt;">En el sorteo No. '.$sorteo.' de esta fecha '.$dia.'  '.$diadate.'  '.$mes.' DE '.$ano.'  resultó favorecido el número <label style="font-size:11pt;">'.$numero_premiado.'</label> con premio de L. '.number_format($premio_premiado).' (Un Mil lempiras) por billete con la siguiente serie ganadora:  </p><br>';
echo '<p >En el sorteo No. '.$sorteo.' de esta fecha '.$dia.'  '.$diadate.'  '.$mes.' DE '.$ano.'  resultó favorecido el número <b>'.$numero_premiado.'</b> con premio de L. '.number_format($premio_premiado).' (Un Mil lempiras) por billete con la siguiente serie ganadora:  </p>';
$cont=0;
$query_ganadores=mysqli_query($conn,"SELECT a.numero_premiado_menor, a.monto FROM sorteos_menores_premios a, premios_menores b WHERE sorteos_menores_id=$sorteo and a.premios_menores_id=b.id and b.tipo_serie='GANADOR';");
while ($filas_ganadores=mysqli_fetch_array($query_ganadores))
{
$numero_ganador[$cont]=$filas_ganadores['numero_premiado_menor'] ;
$monto[$cont]=$filas_ganadores['monto'] ;
$cont++;
}
?>
<div style="width:100%">
<table class="table table-bordered" width="100%">
<thead align="center">
<tr>
<th width="15%" >Seríe Número</th>
<th width="45%" >Distribuido por agencia</th>
<th width="15%" >Valor del premio</th>
<th width="15%" >Valor a pagar</th>

</tr>
</thead>
</tr>
<?php
$cont2=1;
while (isset($numero_ganador[$cont2]))
{
///echo  $numero_ganador[0].' -- '.$numero_ganador[$cont2];
$query_num_Serie=mysqli_query($conn," SELECT  a.agencia_banrural FROM ventas_distribuidor_menor a WHERE a.numero='$numero_ganador[0]' and a.serie=$numero_ganador[$cont2] and a.sorteo=$sorteo ");
if (mysqli_num_rows($query_num_Serie)>0)
{
while ($fila_suc=mysqli_fetch_array($query_num_Serie)) {
$agencia_banrural=$fila_suc['agencia_banrural'];
$monto_premio=$monto[$cont2];
$monto_pagar=$monto[$cont2];
}
$monto_premio= $monto[0] = 50000;


}else{



////////////////////////////////////////////////////////////////////
///////////////////// BUSQUEDA DE DISTRIBUCION /////////////////////

$c_distribucion = mysqli_query($conn,"SELECT nombre_seccional FROM distribucion_menor_bolsas_banco WHERE id_sorteo = '$sorteo' AND serie_inicial <= '$numero_ganador[$cont2]' AND serie_final >= '$numero_ganador[$cont2]' ");

if (mysqli_num_rows($c_distribucion) > 0) {
$ob_distribucion  = mysqli_fetch_object($c_distribucion);
$agencia_banrural = $ob_distribucion->nombre_seccional;
}else{

$c_distribucion = mysqli_query($conn,"SELECT nombre_seccional FROM distribucion_menor_numeros_banco WHERE id_sorteo = '$sorteo' AND numero = '$numero_premiado' AND serie_inicial <= '$numero_ganador[$cont2]' AND serie_final >= '$numero_ganador[$cont2]' ");

if (mysqli_num_rows($c_distribucion) > 0) {
$ob_distribucion  = mysqli_fetch_object($c_distribucion);
$agencia_banrural = $ob_distribucion->nombre_seccional;
}else{
$agencia_banrural='DISTRIBUIDO POR AGENCIA MATRIZ';
}

}

///////////////////// BUSQUEDA DE DISTRIBUCION /////////////////////
////////////////////////////////////////////////////////////////////


$monto_premio= $monto[$cont2];
$monto_pagar= 0;


}

echo "<tr><td align='center' ><label>".$numero_ganador[$cont2]."</label></td>
<td align='center' >".$agencia_banrural."</td>
<td align='center' >".number_format($monto_premio, 2)."</td>
<td align='center' >".number_format($monto_pagar, 2)."</td></tr>";
$cont2++;
}
?>
</table>
</div>

<br>

<div style="width:100%; margin-left:0%"><p align="left">El reverso del número favorecido, No. <b ><?php echo $numero_reves; ?></b>  con premio de L. <?php echo $premio_reves; ?> (cien Lempiras) por billete con las siguientes series ganadoras:</p></div>

<div style="width:100%;">
<table class="table table-bordered" width="100%">

<thead align="center">
<th width="15%" >Seríe Número</th>
<th width="45%" >Distribuido por agencia</th>
<th width="15%" >Valor del premio</th>
<th width="15%" >Valor a pagar</th>
</thead>

<tbody>
<?php
$cont_reves=0;
$query_reves=mysqli_query($conn,"SELECT a.numero_premiado_menor, a.monto FROM sorteos_menores_premios a, premios_menores b WHERE sorteos_menores_id=$sorteo and a.premios_menores_id=b.id and b.tipo_serie='REVES'");
while ($filas_reves=mysqli_fetch_array($query_reves))  { $numero_reves_tab[$cont_reves]=$filas_reves['numero_premiado_menor']; $monto_reves_tab[$cont_reves]=$filas_reves['monto'];  $cont_reves++;  }

$cont3=1;
while (isset($numero_reves_tab[$cont3]))
{
$query_num_Reves=mysqli_query($conn," SELECT a.agencia_banrural FROM ventas_distribuidor_menor a WHERE a.numero='$numero_reves_tab[0]' and a.serie=$numero_reves_tab[$cont3] and a.sorteo=$sorteo ");
if (mysqli_num_rows($query_num_Reves)>0) {
while ($fila_suc_reves=mysqli_fetch_array($query_num_Reves))  {
$agencia_banrural_ser=$fila_suc_reves['agencia_banrural'];
$monto_premio_ser=$monto_reves_tab[$cont3];
$monto_pagar_ser=$monto_reves_tab[$cont3];
}

}else{


////////////////////////////////////////////////////////////////////
///////////////////// BUSQUEDA DE DISTRIBUCION /////////////////////

$c_distribucion = mysqli_query($conn,"SELECT nombre_seccional FROM distribucion_menor_bolsas_banco WHERE id_sorteo = '$sorteo' AND serie_inicial <= '$numero_reves_tab[$cont3]' AND serie_final >= '$numero_reves_tab[$cont3]' ");

if (mysqli_num_rows($c_distribucion) > 0) {
$ob_distribucion  = mysqli_fetch_object($c_distribucion);
$agencia_banrural_ser = $ob_distribucion->nombre_seccional;
}else{

$c_distribucion = mysqli_query($conn,"SELECT nombre_seccional FROM distribucion_menor_numeros_banco WHERE id_sorteo = '$sorteo' AND numero = '$numero_reves' AND serie_inicial <= '$numero_reves_tab[$cont3]' AND serie_final >= '$numero_reves_tab[$cont3]' ");

if (mysqli_num_rows($c_distribucion) > 0) {
$ob_distribucion  = mysqli_fetch_object($c_distribucion);
$agencia_banrural_ser = $ob_distribucion->nombre_seccional;
}else{
$agencia_banrural_ser='DISTRIBUIDO POR AGENCIA MATRIZ';
}

}

///////////////////// BUSQUEDA DE DISTRIBUCION /////////////////////
////////////////////////////////////////////////////////////////////


$monto_premio_ser=$monto_reves_tab[$cont3];
$monto_pagar_ser=0;

//$monto_reves_tab[1] = 50000;
}

setlocale(LC_MONETARY, 'es_HN');
echo "<tr> <td align='center' ><label>".$numero_reves_tab[$cont3]."</label></td>
<td align='center' >".$agencia_banrural_ser."</td>
<td align='center' >".number_format($monto_premio_ser, 2)."</td>
<td align='center' >".number_format($monto_pagar_ser, 2)."</td></tr>";
$cont3++;
}


?>
</tbody>
</table>
</div>
<?php
}
?>

<div align="left" style="width:100%;">
<p >A los noventa y nueve (99) billetes restantes de cada una de las series premiadas en el número favorecido y el reverso del mismo, les
corresponde un premio de cien lempiras por billete. </p>
</div>

<div style="width:100%; margin-left:%">
<p align="center" > 

<?php

if ($lugar_captura == "") {
echo "TEGUCIGALPA, M.D.C. ";
}else{
echo $lugar_captura . ", ";
}

?>

<?php echo $dia." ".$diadate." ".$mes." DE ".$ano ;?></p>
</div>


<br>


<table width = '100%' border="1"  >
<tr>

<th style="text-align: center" colspan=" 2">
  DELEGADOS DEL SORTEO
</th>

</tr>

<?php

$query_autoridades = mysqli_query($conn,"SELECT a.identidad, a.nombre_completo, a.puesto_labora, a.originario, b.descripcion  FROM cs_autoridades_sorteo a, cs_tipo_representacion b WHERE a.puesto_labora = b.id and  sorteo = $sorteo order by puesto_labora DESC");


if (mysqli_num_rows($query_autoridades) > 0) {

$col = 0;
while ($reg_autoridades = mysqli_fetch_array($query_autoridades)) {

if ($col == 0) {
echo "<tr>";
echo "<td>";
echo "<br><br><br><br>";
echo "</td>";
echo "<td>";
echo "<br><br><br><br>";
echo "</td>";
echo "</tr>";

echo "<tr id = 'fila_delegados'  style = 'background-color: #cccccc;'>";
echo "<td width = '49%'  align = 'center' >";
echo  strtoupper($reg_autoridades['nombre_completo'])."<br>";
echo $reg_autoridades['identidad']."<br>";
echo $reg_autoridades['descripcion'];
echo "</td>";
$col++;

}elseif ($col == 1) {

echo "<td width = '49%'  align = 'center' >";
echo strtoupper($reg_autoridades['nombre_completo'])."<br>";
echo $reg_autoridades['identidad']."<br>";
echo $reg_autoridades['descripcion'];
echo "</td>";
echo "</tr>";
$col = 0;

}
}


}else{

echo "<div class = 'alert alert-danger' align = 'center' >Aun no ha ingresado las autoridades del sorteo, por favor de clic en el siguiente link para ingresarlos.
<br><br>
<a class = 'btn btn-danger' href = '_mto_autoridades_sorteo_menor2.php' target = '_blanck' >Ingresar autoridades del sorteo.</a>
</div>";

}


?>

</table>

</div>


</div>





<br><br>
<br><br>

<p align="left">
  Caducidad del sorteo: <?php echo $vencimiento_sorteo; ?>. 
</p>

</form>
  <script type="text/javascript">



  document.title="SISTEMA DE LOTERIA";
  //window.print();
 // setTimeout(window.close, 1000);
</script>

</body>
