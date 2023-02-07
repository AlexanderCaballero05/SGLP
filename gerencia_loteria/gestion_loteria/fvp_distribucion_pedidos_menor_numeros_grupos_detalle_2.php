<?php
require('../../template/header.php');
require('./fvp_distribucion_pedidos_menor_numeros_grupos_detalle_2_db.php');
?>


<script type="text/javascript" src="./funciones_distribucion_grupos.js"></script>
<script type="text/javascript">
  $('html').bind('keypress', function(e)
{
   if(e.keyCode == 13)
   {
      return false;
   }
});
</script>


<body>

<form name="distribucion" method="POST">


<section style="background-color:#ededed;">
<br>
<h3 align="center">
Distribucion Loteria Menor <br>
</h3>
<p align="left">
Sorteo: <?php echo $id_sorteo;?><br>
Institucion Receptora: <?php echo $nombre_empresa;?>
</p>
</section>



<div class="card" style = 'overflow: scroll;height:300px ;width:100%' >
<table width="100%"  class="table table-hover table-bordered">
<thead>
  <th>Numero</th>
  <th>Cantidad Disponible</th>
  <th>Serie Inicial</th>
  <th>Serie Final</th>
  <th>Accion</th>
</thead>

<?php 
$i = 0;
$k = 0;
$contador = 0;
$w = 0;


$numeros_disponibles = mysqli_query($conn," SELECT * FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo' AND grupo = '$grupo' group by numero ASC  ");

while ($row_disponible  = mysqli_fetch_array($numeros_disponibles)) {

$v_numero[$i] = $row_disponible['numero'];
$v_cantidad[$i] = 0;

$s_i = $row_disponible['serie_inicial'];
$cantidad_extra = $row_disponible['cantidad'];
$s_f = $s_i + $cantidad_extra - 1;

$validar_distribuciones = mysqli_query($conn," SELECT count(*) as conteo FROM menor_seccionales_numeros WHERE id_sorteo = '$id_sorteo' AND numero = '$v_numero[$i]' AND serie_final  >= $s_i AND serie_final <= $s_f  ");

$ob_distribuciones = mysqli_fetch_object($validar_distribuciones);
$conteo = $ob_distribuciones->conteo;

if ($conteo > 0) {
$max_serie_distribuida = mysqli_query($conn,"SELECT  MAX(serie_final) as serie_maxima FROM menor_seccionales_numeros WHERE id_sorteo = '$id_sorteo' AND numero = '$v_numero[$i]' AND serie_final  >= $s_i AND serie_final <= $s_f  ");
$ob_maximo = mysqli_fetch_object($max_serie_distribuida);
$ultima_serie_dist = $ob_maximo->serie_maxima;
$serie_inicial_disp = $ultima_serie_dist + 1; 

}else{
$serie_inicial_disp = $s_i;     
}


$serie_final_disp = $s_f;

$cantidad = $serie_final_disp - $serie_inicial_disp + 1;
if ($cantidad > 0 ) {
echo  "<tr> <td>".$v_numero[$i]." </td>
<td>".$cantidad."</td>
<td>".$serie_inicial_disp."</td>
<td>".$serie_final_disp."</td><td align = 'center'>";

echo "<span id= 'boton".$k."' onclick='agregar_numero(".$k.",".$v_numero[$i].",".$serie_inicial_disp.",".$cantidad.")' class = 'btn btn-primary' >Agregar No. ".$v_numero[$i]."</span>";

echo "</td></tr>";
}

$k++;
$w++;
$i++;
}

  ?>
</table>
</div>

<br>

<div>
<div style="float:right;width: 100%;" class="well">


<input type="hidden" name="filas" id="filas">

<table class="table table-hover table-bordered" id="detalle_venta" style="width:100%" >
<thead>
  <th  width="20%">Numero</th>
  <th  width="20%">Cantidad</th>
  <th  width="20%">Serie Inicial</th>
  <th  width="20%">Serie Final</th>
  <th  width="20%">Accion</th>
</thead>
</table>


<table class = "table table-bordered">
<tr>
<td width="20%">TOTAL POR ASIGNAR</td>
  <td width="20%">
  <input class = 'form form-control' type="text" style="width:100%" id="total_cantidad" name="total_cantidad" value="0" readonly>
  </td>

<td width="20%"></td>
<td width="20%"></td>

  <td width="20%">
<button style="width: 100%" type="submit" name="guardar" class="btn btn-primary">GUARDAR</button> 
  </td>

</tr>
</table>

<input type="hidden" name='id_sorteo' value = '<?php echo $id_sorteo;?>' ">
<input type="hidden" name='id_empresa' value = '<?php echo $id_empresa;?>' ">

</div>
</div>

<br><br>


<a class="btn btn-info" style="width:100%" role="button" data-toggle="collapse" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
<h3>  Historico de Distribucion </h3>
</a>

<div  class="collapse" style = "width:100%"  id="collapse2">
<div class="well">
<?php 

echo "<table  width = '100%'  class= 'table table-hover table-bordered'>";
echo "<tr>
<th width = '55%'>Nombre</th>
<th width = '5%'>Numero</th>
<th width = '10%'>Serie Inicial</th>
<th width = '10%'>Serie Final</th>
<th width = '10%'>Cantidad</th>
<th width = '10%'>Accion</th>
</tr>";

$cantidad_total = 0;

$bolsas_asignadas = mysqli_query($conn,"SELECT a.id,a.numero,a.cantidad, a.serie_inicial, a.serie_final , a.cod_factura ,b.nombre_empresa FROM menor_seccionales_numeros as a INNER JOIN empresas as b ON a.id_empresa = b.id  WHERE a.id_sorteo = '$id_sorteo' AND a.id_empresa = '$id_empresa' AND a.origen = 'numeros' ORDER BY a.numero ASC, a.serie_inicial ASC ");


$i =0;
while ($asignado = mysqli_fetch_array($bolsas_asignadas) ) {
$cantidad_total = $cantidad_total + $asignado['cantidad'];

echo "<tr>";
echo "<td>". $asignado['nombre_empresa']."</td>";
echo "<td>". $asignado['numero']."</td>";
echo "<td>". $asignado['serie_inicial']."</td>";
echo "<td>". $asignado['serie_final']."</td>";
echo "<td>". $asignado['cantidad']."</td>";

if ($asignado['cod_factura'] == null) {
echo "<td align = 'center'><button class = 'btn btn-danger' value = '".$asignado['id']."' name = 'eliminar_distribucion'>x</button></td>";
}else{
echo "<td align = 'center'><button class = 'btn btn-danger' name = 'eliminar_distribucion' disabled>x</button></td>";

}

echo "</tr>";

$i++;
}

echo "<tr>";
echo "<td colspan = '4'>TOTAL</td>";
echo "<td>".$cantidad_total."</td>";
echo "<td></td>";
echo "</tr>";
echo "</table>";

?>
</div>
</div>


<br>
<br>
<br>
<br>

</form>
</body>


