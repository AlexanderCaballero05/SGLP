<?php
require('../../template/header.php');
$sorteos = mysqli_query($conn,"SELECT a.id_sorteo as id , b.fecha_sorteo FROM empresas_estado_venta as a INNER JOIN sorteos_mayores as b ON a.id_sorteo = b.id WHERE a.estado_venta != 'D' AND a.cod_producto = 1 AND a.id_sorteo >= '1194'  GROUP BY a.id_sorteo ORDER BY a.id_sorteo DESC");
?>

<style type="text/css">
@media print
{
#non-printable { display: none; }
#printable { display: block; }
}
</style>

<script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/Chart.bundle.js" ></script>
<script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/Chart.bundle.min.js" ></script>
<script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/Chart.js" ></script>
<script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/Chart.min.js" ></script>
<script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/moment.min.js" ></script>


<script type="text/javascript">
//////////////////////////////////
//// FUNCION CONSULTA FACTURA ////

function consultar_ventas(){

$(".div_wait").fadeIn("fast");

id_sorteo = document.getElementById('select_sorteo').value;
texto_s   = document.getElementById('select_sorteo').options[document.getElementById('select_sorteo').selectedIndex].text;
var res   = texto_s.split("|");

filtro 	  = document.getElementById('select_entidad').value;

$("#titulo").text('INFORME DE VENTAS MAYOR SORTEO # '+id_sorteo+' | '+res[1]);

token = Math.random();
consulta = 'informe_ventas_mayor_db.php?id_s='+id_sorteo+"&filtro="+filtro+"&token="+token;			
$("#div_respuesta").load(consulta);

}

////////////////////////////////////
////////////////////////////////////
</script>



<section style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  id="titulo" >INFORME DE VENTAS LOTERIA MAYOR</h2> 

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
<div class="input-group-prepend"><span class="input-group-text">Entidad: </span></div>
<select class="form-control" name="select_entidad" id = 'select_entidad' style="margin-right: 5px">
<option value="1">TODOS</option>
<option value="2">FVP Y ASOCIADOS</option>
<option value="3">BANCO DISTRIBUIDOR</option>
</select>

<div class="input-group-append">
<button class="btn btn-success" onclick="consultar_ventas()" > SELECCIONAR</button>     
</div>
</div>
</div>
</div>



<div id="div_respuesta" class="card-body"></div>





<script>

function getRandomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}




function generar(empresas,porcentajes_venta){

document.getElementById("myChart").style.display = "block";

var array_c = []; 

array_e = empresas.split(",");
emp = array_e.splice(1);
array_p = porcentajes_venta.split(",");
porcentaje = array_p.splice(1);

for (x=0;x< emp.length;x++){
random = getRandomColor();

array_c.push(random.toString());

}


new Chart(document.getElementById("myChart"), {
    type: 'bar',
    data: {
      labels: emp,
      datasets: [
        {
          label: "% de ventas",
          backgroundColor: array_c,
          data: porcentaje
        }
      ]
    },
    options: {
      legend: { display: false },
      title: {
        display: true,
        text: 'PORCENTAJES DE VENTA DE LOTERIA MAYOR EN BASE A ASIGNACION'
      }
    }
});

}




function generar_u(venta,pago){

document.getElementById("myChart_u").style.display = "block";

new Chart(document.getElementById("myChart_u"), {
    type: 'pie',
    data: {
      labels: ["INGRESOS POR VENTA", "OBLIGACION POR PAGO"],
      datasets: [{
        label: "",
        backgroundColor: ["#007acc", "#ff4d4d"],
        data: [venta,pago]
      }]
    },
    options: {
      title: {
        display: true,
        text: 'UTILIDAD O PORDIDA DEL SORTEO'
      }
    }
});

}

</script>