<?php
//REPORTE VENTAS

require('./template/header.php');
require('./reporte_boveda_mayor_excel.php');

$id_sorteo = $_GET['v1'];

?>


<body>

<form name="distribucion" method="POST">


<?php 
if (isset($id_sorteo)) {

if (!isset($_SESSION['id_empresa'])) {
$id_empresa = 5;
}else{
$id_empresa = $_SESSION['id_empresa'];	
}


$sorteos = mysql_query("SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo'  ORDER BY no_sorteo_may DESC ");

$info_sorteo = mysql_query("SELECT *  FROM sorteos_mayores WHERE id = '$id_sorteo' limit 1");
$value = mysql_fetch_object($info_sorteo);
$sorteo = $value->no_sorteo_may;
$fecha_sorteo = $value->fecha_sorteo;
$precio_unitario = $value->precio_unitario;
$mezcla = $value->mezcla;

?>

<br>
<br>
<div class="alert alert-info"> 
<h3 align="center">
Inventario de Loteria en Boveda <br>
Sorteo: <?php echo $id_sorteo;?>
</h3>
</div>
<hr>



<div class="well" style = 'width:100%' >
<table id="table_id1" width="100%"  class="table table-hover table-bordered">
<thead>
  <tr>
  <th>Billete Inicial</th>
  <th>Billete Final</th>
  <th>Cantidad en Boveda</th>
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

  ?>
</tbody>
<tr>
<td>
TOTAL EN BOVEDA  
</td>  
<td >  
</td>
<td>
<?php
echo $total_disponible;
?>  
</td>
</tr>
</table>
</div>

<br>
<p align="center">
<button class = "btn btn-success" type="submit" value="<?php echo $id_sorteo;?>" name = "generar_excel" >Generar Excel</button>

<a class ='btn btn-primary'  target = '_blank' href = './reporte_boveda_mayor_print.php?v1=<?php echo $id_sorteo; ?>' >Imprimir Reporte</a>

</p>
<br>

<?php 

}

?>

<br>

</form>
</body>


