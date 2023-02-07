<?php
require('./template/header.php');

//REPORTE VENTAS

$id_sorteo = $_GET['v1'];

if (!isset($_SESSION['id_empresa'])) {
$id_empresa = 5;
}else{
$id_empresa = $_SESSION['id_empresa'];  
}

$nombre_seccional = "BOVEDA";

$info_sorteo = mysql_query("SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo'  ");
$ob_sorteo = mysql_fetch_object($info_sorteo);
$no_sorteo = $ob_sorteo->no_sorteo_may;
$fecha_sorteo = $ob_sorteo->fecha_sorteo;


?>

<script type="text/javascript">
window.print();
window.onfocus=function(){ window.close();}
</script>


<table  width="100%">
    <tr>
        <td width="20%" style="vertical-align: top">
            <img src="./imagenes/logo-republica.png" width="100%">
        </td>
        <td width="60%" align="center">
<h3>
REPORTE DE LOTERIA NO VENDIDA Y SIN DISTRIBUCION
</h3>
        </td>
        <td width="20%" style="vertical-align: top">
            <img src="./imagenes/logo-pani.png" width="100%">           
        </td>
    </tr>
</table>


<br>
<br>
SORTEO: <?php echo $id_sorteo;?><br>
FECHA DE SORTEO: <?php echo $fecha_sorteo;?><br>

<br>
<br>

<?php

$sorteos = mysql_query("SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo'  ORDER BY no_sorteo_may DESC ");

$info_sorteo = mysql_query("SELECT *  FROM sorteos_mayores WHERE id = '$id_sorteo' limit 1");
$value = mysql_fetch_object($info_sorteo);
$sorteo = $value->no_sorteo_may;
$fecha_sorteo = $value->fecha_sorteo;
$precio_unitario = $value->precio_unitario;
$mezcla = $value->mezcla;

?>

<body >

<div class="" style = 'width:100%' >
<table style="font-size: 9pt" width="100%"  class="table table-bordered">
<thead>
  <tr>
  <th width="33.33%">Billete Inicial</th>
  <th width="33.33%">Billete Final</th>
  <th width="33.33%">Cantidad en Boveda</th>
  </tr>
</thead>
<tbody>

<?php 
$i = 0;
$k = 0;
$contador = 0;
$w = 0;
$total_disponible = 0;

$numeros_disponibles = mysql_query(" SELECT a.rango FROM sorteos_mezclas_rangos as a INNER JOIN sorteos_mezclas as b ON a.num_mezcla = b.num_mezcla WHERE a.id_sorteo = '$id_sorteo' AND b.id_sorteo = '$id_sorteo' AND b.id_empresa = '$id_empresa' AND a.id_seccional IS NULL");

while ($reg_boveda = mysql_fetch_array($numeros_disponibles)) {
$b_i = $reg_boveda['rango'];
$b_f = $b_i + $mezcla - 1;
$cantidad = $b_f - $b_i + 1;
$total_disponible = $total_disponible + $cantidad;

echo "<tr>";
echo "<td>".$b_i."</td>";
echo "<td>".$b_f."</td>";
echo "<td>".$cantidad."</td>";
echo "</tr>";
}



echo "</tbody>";
echo "</table>";

echo "<br>";
echo "<br>";
echo "<p align = 'center'> Por este medio se hace entrega del reporte de loteria mayor sin distribucion a ser triturada, con un total de <b><u>".$total_disponible."</u></b> billetes de loteria mayor</p>";

$hoy = date("d-m-Y");

echo "<br>";
echo "Fecha de emision ".$hoy;

echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";

echo"<table width = '100%'>";
echo "<tr>";
echo "<td width = '30%'></td>";
echo "<td width = '40%'>
<b><hr></b>
<p align = 'center'>FIRMA DEL RESPONSABLE</p>
</td>";
echo "<td width = '30%'></td>";
echo "</tr>";
echo"</table>";


?>

</body>