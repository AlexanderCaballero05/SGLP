<?php
require('./template/header.php');
require('./reporte_boveda_excel.php');

//REPORTE VENTAS

$id_sorteo = $_GET['v1'];

if (!isset($_SESSION['id_empresa'])) {
$id_empresa = 5;
}else{
$id_empresa = $_SESSION['id_empresa'];	
}

?>


<body>

<form name="distribucion" method="POST">


<?php 
if (isset($id_sorteo)) {


$sorteos = mysql_query("SELECT * FROM sorteos_menores   ORDER BY no_sorteo_men DESC ");

$info_sorteo = mysql_query("SELECT *  FROM sorteos_menores WHERE id = '$id_sorteo' limit 1");
$value = mysql_fetch_object($info_sorteo);
$sorteo = $value->no_sorteo_men;
$fecha_sorteo = $value->fecha_sorteo;
$precio_unitario = $value->precio_venta;

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
  <th>Numero</th>
  <th>Serie Inicial</th>
  <th>Serie Final</th>
  <th>Cantidad Disponible</th>
</tr>
</thead>
<tbody>
<?php 
$i = 0;
$k = 0;
$contador = 0;
$w = 0;
$total_disponible = 0;

if ($id_sorteo >= 3144) {

$numeros_disponibles = mysql_query("SELECT * FROM menor_seccionales_numeros WHERE id_sorteo = '$id_sorteo' AND id_empresa = '$id_empresa' ORDER BY numero ASC, serie_inicial ASC ");

}else{

$numeros_disponibles = mysql_query(" SELECT * FROM fvp_menor_reservas_numeros WHERE sorteos_menores_id = '$id_sorteo' ORDER BY numero ASC , serie_inicial ASC  ");

}

while ($row_disponible  = mysql_fetch_array($numeros_disponibles)) {

$v_numero[$i] = $row_disponible['numero'];
$v_cantidad[$i] = 0;

$s_i = $row_disponible['serie_inicial'];
$s_f = $row_disponible['serie_final'];
$cantidad_extra = $s_f - $s_i + 1;

$validar_distribuciones = mysql_query(" SELECT count(*) as conteo FROM fvp_menor_reservas_seccionales_numeros WHERE sorteos_menores_id = '$id_sorteo' AND numero = '$v_numero[$i]' AND serie_final  >= $s_i AND serie_final <= $s_f  ");

$ob_distribuciones = mysql_fetch_object($validar_distribuciones);
$conteo = $ob_distribuciones->conteo;

if ($conteo > 0) {
$max_serie_distribuida = mysql_query("SELECT  MAX(serie_final) as serie_maxima FROM fvp_menor_reservas_seccionales_numeros WHERE sorteos_menores_id = '$id_sorteo' AND numero = '$v_numero[$i]' AND serie_final  >= $s_i AND serie_final <= $s_f  ");

if ($max_serie_distribuida === false) {
echo mysql_error();
}

$ob_maximo = mysql_fetch_object($max_serie_distribuida);
$ultima_serie_dist = $ob_maximo->serie_maxima;
$serie_inicial_disp = $ultima_serie_dist + 1; 

}else{
$serie_inicial_disp = $s_i;     
}


$serie_final_disp = $s_f;

$cantidad = $serie_final_disp - $serie_inicial_disp + 1;
if ($cantidad > 0 ) {
echo  "<tr> <td>".$v_numero[$i]." </td>
<td>".$serie_inicial_disp."</td>
<td>".$serie_final_disp."</td>
<td>".$cantidad."</td>
</tr>";

$total_disponible = $total_disponible + $cantidad;
}

$k++;
$w++;
$i++;
}
  ?>
</tbody>
<tr>
<td>
TOTAL EN BOVEDA  
</td>  
<td colspan="2">  
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

<a class ='btn btn-primary'  target = '_blank' href = './reporte_boveda_print.php?v1=<?php echo $id_sorteo; ?>' >Imprimir Reporte</a>

</p>
<br>

<?php 

}

?>

<br>

</form>
</body>


