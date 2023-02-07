<?php
require('./template/header.php');

$sorteos = mysqli_query($conn,"SELECT a.id_sorteo as id , b.fecha_sorteo FROM empresas_estado_venta as a INNER JOIN sorteos_menores as b ON a.id_sorteo = b.id WHERE a.estado_venta != 'D' AND a.cod_producto = 2  GROUP BY a.id_sorteo ORDER BY a.id_sorteo DESC");

?>

<script type="text/javascript">
//////////////////////////////////
//// FUNCION CONSULTA FACTURA ////

function consultar_ventas(){

$(".div_wait").fadeIn("fast");

id_sorteo = document.getElementById('select_sorteo').value;
texto_s   = document.getElementById('select_sorteo').options[document.getElementById('select_sorteo').selectedIndex].text;
var res   = texto_s.split("|");


$("#titulo").text('INFORME DE VENTAS LOTERIA MENOR POR BOLSA  BANCO DISTRIBUIDOR SORTEO # '+id_sorteo+' | '+res[1]);

token = Math.random();
consulta = 'informe_ventas_banco_bolsas_db.php?ss='+id_sorteo+"&token="+token;			
$("#div_respuesta").load(consulta);
}

////////////////////////////////////
////////////////////////////////////
</script>

<style type="text/css">
@media print
{
#non-printable { display: none; }
#printable { display: block; }
}
</style>

<section style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  id="titulo" >INFORME DE VENTAS LOTERIA MENOR POR BOLSA <br> BANCO DISTRIBUIDOR</h2> 

<button class="btn btn-info" style="width: 100%" id="non-printable" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
  Seleccion de Parametros
</button>
</section>

<div class="collapse" id="collapseOne" style="width: 100%" align="center"  style="background-color: grey">
<div class="card card-body" id="non-printable" style="width: 100%">
<div class="input-group " style="margin:0px 0px 0px 0px; width: 50%">
<div class="input-group-prepend"><span class="input-group-text">Sorteo: </span></div>
<select class="form-control" name="select_sorteo" id = 'select_sorteo' style="margin-right: 5px">
<?php
while ($reg_sorteos = mysqli_fetch_array($sorteos)) {
echo "<option value = '".$reg_sorteos['id']."' >".$reg_sorteos['id']." | ".$reg_sorteos['fecha_sorteo']."</option>";
}
?>
</select>

<div class="input-group-append">
<button class="btn btn-success" onclick="consultar_ventas()" > SELECCIONAR</button>     
</div>
</div>
</div>
</div>


<div id="div_respuesta" class="card-body"></div>


<?php
require('./template/footer.php');
?>