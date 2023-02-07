<?php
require('./template/header.php');
?>


<script type="text/javascript">

function confirmar_cambio(nuevo_estado, sorteo){

swal({   title: "",   text: "¿Esta seguro de realizar esta accion?",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "SI",   cancelButtonText: "NO",   closeOnConfirm: true,   closeOnCancel: true }, function(isConfirm){   
if (isConfirm) {
consulta = 'sorteos_ventas_menor_db.php?id_s='+sorteo+"&n_e="+nuevo_estado;			
$("#div_respuesta").load(consulta);
}else{ 
swal("", "Cambio de estado cancelado", "error");   
} 
});

}

</script>


<body>


<form method="POST">


<div class="panel panel-primary">
<div class="panel-heading">
<h3 align="center"><b> ADMINISTRACION DE ESTADO DE VENTA DE SORTEOS DE LOTERIA MENOR </b></h3>
<hr>

<a style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
  Seleccion de Parametros
</a>

<div class="collapse" id="collapse1">
<br>
<table width="100%">
<tr>
<td width="25%"></td>	
<td width="50%">

	
<div style="" class="input-group">

<span class="input-group-addon">SORTEO</span>
<select class="form-control" name="sorteo" id = 'select_sorteo'>
<?php
$sorteos = mysql_query("SELECT * FROM sorteos_menores ORDER BY no_sorteo_men DESC ");
while ($row2 = mysql_fetch_array($sorteos)) {
echo '<option value = "'.$row2['id'].'">No.'.$row2['no_sorteo_men'].' -- Fecha '.$row2['fecha_sorteo'].' -- '.$row2['descripcion_sorteo_men'].'</option>' ;
}
?>
</select>
</div>
</td>	
<td width="10%">
	<input type="submit" id="seleccionar" name="seleccionar" class="btn btn-default" value="Seleccionar">
</td>
<td width="25%"></td>	
</tr>
</table>

</div>


</div>

<div class="panel-body">


<?php

if (isset($_POST['seleccionar'])) {

$sorteo = $_POST['sorteo'];


$info_sorteo_menor = mysql_query("SELECT a.id, a.no_sorteo_men, a.fecha_sorteo, a.estado_venta, b.estado_venta as estado  FROM sorteos_menores as a INNER JOIN empresas_estado_venta as b ON a.id = b.id_sorteo WHERE a.id = '$sorteo' AND b.cod_producto = '2' limit 1");

if (mysql_num_rows($info_sorteo_menor) > 0 ) {
$value2 = mysql_fetch_object($info_sorteo_menor);
$id_sorteo_menor = $value2->id;
$num_sorteo = $value2->no_sorteo_men;
$fecha_sorteo = $value2->fecha_sorteo;
$estado_sorteo_n = $value2->estado;
}else{
$id_sorteo_menor = null;
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

}
?>
	
</div>

</div>


<div id="div_respuesta"></div>


</form>
</body>