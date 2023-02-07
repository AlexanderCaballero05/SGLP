<?php
require('./template/header.php');
require('./fvp_distribucion_pedidos_mayor_db.php');
?>
<script type="text/javascript">
  function calcular_disponibles(valor, indicador){

total_paquetes = parseInt(document.getElementById('billetes_disponibles').value);


if (valor > total_paquetes) {
document.getElementById('cantidad_seccional'+indicador).value = 0;
}

i = 0;
total_restar = 0;
total = document.getElementById('billetes_disponibles_oculto').value;


while (cantidad = document.getElementById('cantidad_seccional'+i)) {

if (cantidad.value != '') {
c = parseInt(cantidad.value);  
}else{
c = 0;  
}

c = c;

total_restar = total_restar + c;     

  i++;
}

document.getElementById('billetes_disponibles').value = total - total_restar ;



  }
</script>

<form method="POST">

<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" >Distribución Loteria Mayor</a></li>
  <li><a  href="./fvp_distribucion_pedidos_menor_bolsas.php">Distribución Loteria Menor Bolsas</a></li>
   <li ><a  href="./fvp_distribucion_pedidos_menor_numeros_grupos.php">Distribución Loteria Menor Extra Por Grupo</a></li>  
</ul>

<div class="tab-content">
  <div id="home" class="tab-pane fade in active">

<br>
<a class="btn btn-info" style="width:100%" role="button" data-toggle="collapse" href="#collapse3" aria-expanded="false" aria-controls="collapse3">
<h3> Parametros de Seleccion </h3>
</a>

<div  class="collapse" style = "width:100%"  id="collapse3">
<div class="well" align="center">

<table style = "width:50%" class="table table-bordered">
  <tr>
    <th>Seleccion de Sorteo</th>
    <th>Accion</th>
  </tr>
  <tr>
    <td align="center">

<select class="form-control" name="sorteo" >
<?php
while ($row2 = mysql_fetch_array($sorteos_seleccion)) {
echo '<option value = "'.$row2['id'].'">'.$row2['no_sorteo_may'].'</option>' ;
}
?>
</select>       
    </td>

    <td align="center">
<input  type="submit" name="seleccionar" class="btn btn-primary" value="Seleccionar"> 
    </td>
  </tr>
</table>
</div>
</div>

<hr>

<?php if (isset($sorteo)) {
?>
<div align="center" style="width:100%; ">
 <input type="hidden" name="id_sorteo_oculto" value="<?php $id_sorteo?>">

<div class="alert alert-info">
 <h2 align="center">
 Sorteo Numero: <?php if (isset($sorteo)) {echo $sorteo;} ?>  
 Fecha de Sorteo: <?php if (isset($sorteo)) {echo $fecha_sorteo;} ?>  
 </h2>

<p>
Paquetes Disponibles: <input type="text" id="billetes_disponibles" value="<?php echo $num_paquetes; ?>" readonly><br>
</p>
 </div> 

<div class="alert alert-info">
<b>NOTA:</b>
Cada paquete contiene 100 billetes y la distribucion de los mismos se realiza por paquete.
</div>

<input type="hidden" id="billetes_disponibles_oculto" value="<?php echo $num_paquetes; ?>">

<br>

<div class = 'well' style="float:right;width:100%">

<h3 align="center">Entidades</h3>
<br>

<?php

echo "<table  width = '100%'  class= 'table table-hover table-bordered'>";
echo "<tr>
<th width = '70%'>Entidad Recaudadora</th>
<th width = '30%'>Cantidad a Asignar </th>
</tr>";

$i = 0; 

while ($row = mysql_fetch_array($empresas)) {

echo "
<tr>
<input type = 'hidden' value = '".$row['id']."' name = 'id_empresa".$i."'>

<td align= 'center'>".$row['nombre_empresa']."</td>

<td align= 'center'><input style = 'width:70%' class = 'form-control' type = 'text' id = 'cantidad_seccional".$i."' name = 'cantidad_seccional".$i."' onblur ='calcular_disponibles(this.value,".$i.")' ></td>

</tr>";
$i++;

}

echo "</table>";

?>
<br>
<p align="center"><input type="submit" id="guardar_distribucion" name="guardar_distribucion" class="btn btn-primary" value="Guardar Distribucion"></p>

</div>
</div>

<br>
<table class="table table-bordered">
<tr>
  <th>Seccional</th>
  <th>Cantidad Paquetes</th>
  <th>Accion</th>
</tr>
  
<?php

$distribuciones_realizadas = mysql_query("SELECT COUNT(id_empresa) as conteo, b.id , b.nombre_empresa FROM sorteos_mezclas AS a INNER JOIN empresas as b  ON a.id_empresa = b.id WHERE a.id_sorteo = '$id_sorteo' AND a.estado = 'DISTRIBUIDO' GROUP BY id_empresa ");

if ($distribuciones_realizadas === false) {
echo mysql_error();
}

while ($reg_distribucion = mysql_fetch_array($distribuciones_realizadas)) {

$parametros = $reg_distribucion['id']."-".$id_sorteo;

if ($reg_distribucion['conteo'] != 0) {
echo "<tr>";
echo "<td>".$reg_distribucion['nombre_empresa']."</td>";
echo "<td>".$reg_distribucion['conteo']."</td>";
echo "<td>
<a target = 'blank'  href = './fvp_distribucion_pedidos_mayor_detalle.php?par=".$parametros."' class = 'btn btn-info'>
<span class = 'glyphicon glyphicon-eye-open'></span>
</a>
</td>";
echo "</tr>";
}

}

?>

</table>


<?php

if ($estado_venta != "FINALIZADO") {

?>

<p align="center">
<button type="submit" class = 'btn btn-danger' name="borrar_distribucion" value = "<?php echo $id_sorteo; ?>" >BORRAR DISTRIBUCION</button>
</p>

<?php

}

?>



<?php
 } 
?> 




  </div>
</div>
</form>