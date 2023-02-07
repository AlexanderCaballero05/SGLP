<?php
require('../../template/header.php');
$years = mysqli_query($conn, "SELECT YEAR(fecha_registro) as year FROM utilidades_perdidas_sorteos GROUP BY YEAR(fecha_registro) ORDER BY YEAR(fecha_registro) DESC ");

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

year = document.getElementById('select_year').value;
texto_s   = document.getElementById('select_year').options[document.getElementById('select_year').selectedIndex].text;
texto_e   = document.getElementById('select_entidad').options[document.getElementById('select_entidad').selectedIndex].text;
filtro    = document.getElementById('select_entidad').value;

div = document.getElementById('titulo');

$(".div_wait").fadeIn("fast");

if (filtro == 1) {
div.innerHTML = 'INFORME ACUMULADO DE VENTA DE LOTERIA MAYOR '+texto_s+' <br> GENERAL';
}else{
div.innerHTML = 'INFORME ACUMULADO DE VENTA DE LOTERIA MAYOR '+texto_s+' <br> '+texto_e;
};

token = Math.random();
consulta = 'informe_ventas_mayor_anual_db.php?year='+year+"&filtro="+filtro+"&token="+token;      
$("#div_respuesta").load(consulta);


}

////////////////////////////////////
////////////////////////////////////
</script>




<section style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  id="titulo" >INFORME ACUMULADO DE VENTA LOTERIA MAYOR</h2> 

<button class="btn btn-info" style="width: 100%" id="non-printable" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
  Seleccion de Parametros
</button>
</section>


<div class="collapse" id="collapseOne" style="width: 100%" align="center"  style="background-color: grey">
<div class="card card-body" id="non-printable" style="width: 100%">
<div class="input-group " style="margin:0px 0px 0px 0px; width: 50%">
<div class="input-group-prepend"><span class="input-group-text">Año: </span></div>
<select class="form-control" name="select_year" id = 'select_year' style="margin-right: 5px">
<?php
while ($reg_year = mysqli_fetch_array($years)) {
echo "<option value = '".$reg_year['year']."' >".$reg_year['year']."</option>";
}
?>
</select>
<div class="input-group-prepend"><span class="input-group-text">Entidad: </span></div>
<select class="form-control" name="select_entidad" id = 'select_entidad' style="margin-right: 5px">
<option value="1">TODAS</option>
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




function generar(fechas,u_fvp,u_b,filtro,tt_acum){

if (filtro == 1) {

document.getElementById("myChart").style.display = "block";

v_fechas  = fechas.split("%");
v_fvp     = u_fvp.split("%");
v_b     = u_b.split("%");
v_tt_acum = tt_acum.split("%");

new Chart(document.getElementById("myChart"), {
  type: 'line',
  data: {
    labels: v_fechas,
    datasets: [{ 
        data: v_fvp,
        label: "FVP Y ASOCIADOS",
        borderColor: "#3e95cd",
        pointRadius: 5,
    pointHoverRadius: 5,
        fill: false
      }, { 
        data: v_b,
        label: "BANRURAL",
        borderColor: "#8e5ea2",
        pointRadius: 5,
    pointHoverRadius: 5,
        fill: false
      }, { 
        data: v_tt_acum,
        label: "UTILIDAD TOTAL",
        borderColor: "#d84b13",
        pointRadius: 5,
    pointHoverRadius: 5,

        fill: false
      }
    ]
  },
  options: {
    title: {
      display: true,
      text: 'UTLIDADES Y PERDIDAS ANUALES ACUMULADAS'
    }
  }
});

}



if (filtro == 2) {

document.getElementById("myChart").style.display = "block";

v_fechas = fechas.split("%");
v_fvp = u_fvp.split("%");


new Chart(document.getElementById("myChart"), {
  type: 'line',
  data: {
    labels: v_fechas,
    datasets: [{ 
        data: v_fvp,
        label: "FVP Y ASOCIADOS",
        borderColor: "#3e95cd",
        pointRadius: 5,
    pointHoverRadius: 5,

        fill: false
      }
    ]
  },
  options: {
    title: {
      display: true,
      text: 'UTLIDADES Y PERDIDAS ANUALES'
    }
  }
});

}



if (filtro == 3) {

document.getElementById("myChart").style.display = "block";

v_fechas = fechas.split("%");
v_b = u_b.split("%");


new Chart(document.getElementById("myChart"), {
  type: 'line',
  data: {
    labels: v_fechas,
    datasets: [{ 
        data: v_b,
        label: "BANRURAL",
        borderColor: "#8e5ea2",
        pointRadius: 5,
    pointHoverRadius: 5,

        fill: false
      }
    ]
  },
  options: {
    title: {
      display: true,
      text: 'UTLIDADES Y PERDIDAS ANUALES'
    }
  }
});

}



}

</script>