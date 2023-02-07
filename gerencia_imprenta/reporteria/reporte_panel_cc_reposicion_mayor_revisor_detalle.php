<?php
require('../../template/header.php');

$parametros = $_GET['par'];
$vector = explode("_", $parametros);

$id_sorteo = $vector[0];
$rev =  $vector[1];
$num_rev =  $vector[2];
$num_lista =  $vector[3];


$info_sorteo = mysqli_query($conn,"SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo' ");
$ob_sorteo = mysqli_fetch_object($info_sorteo);
$no_sorteo = $ob_sorteo->no_sorteo_may;
$fecha_sorteo = $ob_sorteo->fecha_sorteo;
$cantidad_billetes = $ob_sorteo->cantidad_numeros;
$registro_inicial = $ob_sorteo->desde_registro;
$patron_salto = $ob_sorteo->patron_salto;
$fecha_vencimiento = $ob_sorteo->fecha_vencimiento;

$masc = strlen($cantidad_billetes);
$masc_rec = strlen($registro_inicial);


$parametros_mayor = mysqli_query($conn,"SELECT * FROM sorteos_mayores_produccion where id_sorteo = '$id_sorteo' ");

$i = 1;
while ($reg = mysqli_fetch_array($parametros_mayor)) {
$v_salto[$i] = $reg['salto'];
$i++;
}




$info_revisor = mysqli_query($conn,"SELECT a.nombre_completo, b.numero FROM pani_usuarios as a INNER JOIN cc_revisores_sorteos_mayores as b ON a.id = b.id_revisor  WHERE a.id = '$rev' ");


$ob_revisor = mysqli_fetch_object($info_revisor);
$num_revisor = $ob_revisor->numero;
$nombre_revisor = $ob_revisor->nombre_completo;

?>
<section style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >
<b>DEPARTAMENTO DE CONTROL DE CALIDAD PANI</b>
<p>REPOSICIONES LOTERIA MAYOR</p>
</h2> 
</section>
<br>
<?php


echo "<p style = ''>Reporte de errores detectados en loteria mayor </p>";
echo "<p style = ''>Sorteo No. <u>".$no_sorteo."</u> De fecha: <u>".$fecha_sorteo."</u> y Vencimiento <u>".$fecha_vencimiento."</u></p>";
echo "<p style = ''>Nombre de Revisor. <u>".$nombre_revisor."</u> Lista No: <u>".$num_revisor."</u></p>";


?>
<table  class='table table-bordered' id='detalle_revisor' border = '1' style= 'width:100%'>
  <thead>
    <tr>
      <th style="width:25%">Numero de Billete</th>
      <th style="width:25%">Registro</th>
      <th style="width:25%">R. E.</th>
      <th style="width:25%">Cantidad</th>
    </tr>
  </thead>
  <tbody>

<?php

$inventario_rechazado = mysqli_query($conn," SELECT billete , registro, especial FROM cc_revisores_sorteos_mayores_control   WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$rev' AND numero_revision = '$num_rev'  ORDER BY billete ASC "); 


$i = 0;
while ($reg_inventerio_rechazado = mysqli_fetch_array($inventario_rechazado)) {

$v_billete[$i]  = $reg_inventerio_rechazado['billete'];
$v_registro[$i] = $reg_inventerio_rechazado['registro'];
$v_re[$i]       = $reg_inventerio_rechazado['especial'];

$i++;
}

$i = 0;
$j = 0;

while (isset($v_billete[$i])) {

if (isset($v_billete[$i + 1])) {

if ($v_billete[$i] + 1 == $v_billete[$i + 1]) {

if ($v_re[$i] == $v_re[$i + 1]) {

}else{

$cantidad = $v_billete[$i] - $v_billete[$j] + 1;
echo "<tr>";
echo "<td>".str_pad((string) $v_billete[$j], 5, "0", STR_PAD_LEFT)." - ".str_pad((string) $v_billete[$i], 5, "0", STR_PAD_LEFT)."</td>";
echo "<td>".$v_registro[$j]." - ".$v_registro[$i]."</td>";
echo "<td>".$v_re[$i]."</td>";
echo "<td>".$cantidad."</td>";
echo "</tr>";

$j = $i + 1;

}

}else{

$cantidad = $v_billete[$i] - $v_billete[$j] + 1;
echo "<tr>";
echo "<td>".str_pad((string) $v_billete[$j], 5, "0", STR_PAD_LEFT)." - ".str_pad((string) $v_billete[$i], 5, "0", STR_PAD_LEFT)."</td>";
echo "<td>".$v_registro[$j]." - ".$v_registro[$i]."</td>";
echo "<td>".$v_re[$i]."</td>";
echo "<td>".$cantidad."</td>";
echo "</tr>";

$j = $i + 1;

}


}else{

$cantidad = $v_billete[$i] - $v_billete[$j] + 1;
echo "<tr>";
echo "<td>".str_pad((string) $v_billete[$j], 5, "0", STR_PAD_LEFT)." - ".str_pad((string) $v_billete[$i], 5, "0", STR_PAD_LEFT)."</td>";
echo "<td>".$v_registro[$j]." - ".$v_registro[$i]."</td>";
echo "<td>".$v_re[$i]."</td>";
echo "<td>".$cantidad."</td>";
echo "</tr>";

$j = $i + 1;

}

$i++;
}


?>

</tbody>
</table>


<p >Para reponer se entregan <u><?php echo count($v_billete); ?></u> pliegos Fecha <u><?php echo $fecha_actual; ?></u></p>

<p >Revisor <u><?php echo $nombre_revisor; ?></u></p>