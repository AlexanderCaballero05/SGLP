<?php
require('../../template/header.php');
?>


<script type="text/javascript">

function confirmar_cambio(nuevo_estado, sorteo){

swal({
  title: "¿Esta seguro?",
  text: "Esta accion es irreversible",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {

consulta = 'sorteos_ventas_menor_db.php?id_s='+sorteo+"&n_e="+nuevo_estado;			
$("#div_respuesta").load(consulta);

  } else {
    swal("Accion cancelada.");
  }
});

}

</script>


<body>


<form method="POST">

<br>

<ul class="nav nav-tabs">
 <li class="nav-item">
    <a  class="nav-link" href="./screen_sorteos_ventas_mayor.php">Lotería Mayor</a>
  </li>
  <li class="nav-item">
    <a style="background-color:#ededed;" class="nav-link"  >Lotería Menor</a>
  </li>
</ul>

<section style="background-color:#ededed;">
<br>
<h3 align="center"><b> ADMINISTRACION DE ESTADO DE SORTEOS DE LOTERIA MENOR </b></h3>
<br>
</section>


<a style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
  Seleccion de Parametros
</a>

<div class="collapse card" id="collapse1" align="center">
<br>
<div  class="input-group" style="width: 50%">
<div class="input-group-prepend"><div class="input-group-text"> SORTEO </div></div>
<select class="form-control" name="sorteo" id = 'select_sorteo'>
<?php
$sorteos = mysqli_query($conn,"SELECT * FROM sorteos_menores ORDER BY no_sorteo_men DESC ");
while ($row2 = mysqli_fetch_array($sorteos)) {
echo '<option value = "'.$row2['id'].'">No.'.$row2['no_sorteo_men'].' -- Fecha '.$row2['fecha_sorteo'].' -- '.$row2['descripcion_sorteo_men'].'</option>' ;
}
?>
</select>

<div class="input-group-apend">
<input type="submit" id="seleccionar" name="seleccionar" class="btn btn-default" value="Seleccionar">
</div>

</div>
<br>
</div>


<br><br><br>




<?php

if (isset($_POST['seleccionar'])) {

?>

<div class="card" style="margin-left: 10px; margin-right: 10px; ">

<div class="panel-body">


<?php

$sorteo = $_POST['sorteo'];

$info_sorteo_mayor = mysqli_query($conn,"SELECT a.id, a.no_sorteo_men, a.fecha_sorteo, a.estado_venta, b.estado_venta as estado  FROM sorteos_menores as a INNER JOIN empresas_estado_venta as b ON a.id = b.id_sorteo WHERE a.id = '$sorteo'  AND b.cod_producto = '2' limit 1");

if (mysqli_num_rows($info_sorteo_mayor) > 0 ) {
$value2 = mysqli_fetch_object($info_sorteo_mayor);
$id_sorteo_mayor = $value2->id;
$num_sorteo = $value2->no_sorteo_men;
$fecha_sorteo = $value2->fecha_sorteo;
$estado_sorteo_n = $value2->estado;
}else{
$id_sorteo_may = null;
}


if ($estado_sorteo_n == "D") {
$estado_sorteo_d = "DESHABILITADO";
}elseif ($estado_sorteo_n == "H") {
$estado_sorteo_d = "HABILITADO";
}elseif ($estado_sorteo_n == "F") {
$estado_sorteo_d = "FINALIZADO";
}

echo "<div class = 'alert alert-info' align = 'center'><h3> Sorteo No. ".$num_sorteo." Fecha de Sorteo ".$fecha_sorteo." </h3> Estado: ".$estado_sorteo_d."</div>";

if ($estado_sorteo_n == 'D') {
?>
<p align = "center">
<span name="habilitar" class="btn btn-info" onclick = "confirmar_cambio('H','<?php echo $sorteo?>')" >HABILITAR PARA VENTA</span>
</p>
<?php	

}elseif ($estado_sorteo_n == 'H') {
?>
<p align = "center">
<span name="deshabilitar" class="btn btn-info" onclick = "confirmar_cambio('D','<?php echo $sorteo?>')" >DESHABILITAR VENTA</span>
<span name="finalizar" class="btn btn-danger"  onclick = "confirmar_cambio('F','<?php echo $sorteo?>')" >FINALIZAR VENTA</span>
</p>
<?php
}elseif ($estado_sorteo_n == 'F') {
echo "<div class = 'alert alert-info'>La venta del sorteo seleccionado esta finalizada</div>";
}

?>
</div>

</div>
<?php

}
?>


<div id="div_respuesta"></div>


</form>
</body>