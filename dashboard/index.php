<?php
require '../template/header.php';
?>

<script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/Chart.bundle.js" ></script>
<script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/Chart.bundle.min.js" ></script>
<script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/Chart.js" ></script>
<script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/Chart.min.js" ></script>
<script type="text/javascript" src="<?php echo $ruta; ?>assets/charts/moment.min.js" ></script>


<script type="text/javascript">



$(window).bind("load", function() {



parametros = 'card_ventas||';
load_info_dash(parametros,null,null);


parametros = 'card_donut_ventas||';
load_info_dash(parametros,null,null);

parametros = 'card_bar_ventas||';
load_info_dash(parametros,null,null);

parametros = 'card_line_ventas||';
load_info_dash(parametros,null,null);


});



function load_info_dash(parametros){
split =  parametros.split("|");
elemento = split[0];


content_wait = '<br><br><div class="d-flex align-items-center"><strong style="margin-left: 15px" >Cargando...</strong><div class="spinner-grow ml-auto text-success" role="status" aria-hidden="true"></div></div><br><br>';
$("#"+elemento).html(content_wait);

token = Math.random();
consulta = 'get_info_dash.php?param='+parametros+"&token="+token;

$("#"+elemento).fadeOut('slow');
$("#"+elemento).load(consulta);
$("#"+elemento).fadeIn('slow');

}



</script>




<br>


<ul class="nav nav-tabs">
 <li class="nav-item">
    <a style="background-color:#ededed;" class="nav-link" >Lotería Mayor</a>
  </li>
  <li class="nav-item">
    <a  class="nav-link"  href="dash_menor.php" >Lotería Menor</a>
  </li>
</ul>


<section style="background-color:#ededed;">
<br>



<div class="row" style="margin:0px;">
<div class="col">
<div class="card" id="card_monitoreo"  >


</div>
</div>
</div>







<br>








<div class="row" style="margin:0px;" >
<div class="col">
<div class="card" id="card_ventas"  align="left" style="overflow-y: auto;">


</div>
</div>
</div>















<br>



















<div class="row" style="margin:0px;">

<div class="col col-md-4">
<div class="card"  id="card_donut_ventas">



</div>
</div>





<div class="col col-md-8">
<div class="card" id="card_bar_ventas" >


</div>
</div>

</div>

















<br>








<div class="row" style="margin:0px;">
<div class="col">


<div class="card" id="card_line_ventas" >
</div>

</div>
</div>












<br>

</section>










<script>

function cargar_line_ventas(){

year_a = document.getElementById("h_year_a").value;
year_b = document.getElementById("h_year_b").value;
concat_year_a = document.getElementById("concat_year_a").value;
concat_year_a = concat_year_a.substring(0, concat_year_a.length-1);
concat_year_b = document.getElementById("concat_year_b").value;
concat_year_b = concat_year_b.substring(0, concat_year_b.length-1);
concat_meses = document.getElementById("concat_meses").value;
concat_meses = concat_meses.substring(0, concat_meses.length-1);

v_meses = concat_meses.split("%");

v_year_a = concat_year_a.split("%");
v_year_b = concat_year_b.split("%");


var ctx = document.getElementById("graf_line");

new Chart(ctx, {
  type: 'line',
  data: {
    labels: v_meses,
    datasets: [{
        data: v_year_a,
        label: year_a,
        borderColor: "#3e95cd",
        pointStyle: "circle",
        fill: false
      },{
        data: v_year_b,
        label: year_b,
        borderColor: "#cc3300",
        pointStyle: "circle",
        fill: false
      }
    ]
  },
  options: {
    title: {
      display: true,
      text: ''
    }
  }
});

}


function cargar_bar_ventas(){

sorteo_a = document.getElementById("h_sorteo_a").value;
sorteo_b = document.getElementById("h_sorteo_b").value;
concat_sorteo_a = document.getElementById("concat_sorteo_a").value;
concat_sorteo_a = concat_sorteo_a.substring(0, concat_sorteo_a.length-1);
concat_sorteo_b = document.getElementById("concat_sorteo_b").value;
concat_sorteo_b = concat_sorteo_b.substring(0, concat_sorteo_b.length-1);
concat_entidades = document.getElementById("concat_entidades").value;
concat_entidades = concat_entidades.substring(0, concat_entidades.length-1);

v_entidades = concat_entidades.split("%");

v_sorteo_a = concat_sorteo_a.split("%");
v_sorteo_b = concat_sorteo_b.split("%");

conteo_array = v_sorteo_a.length;

v_colores_a = [];
v_colores_b = [];

for (var i = 0; i < conteo_array; i++) {
v_colores_a[i] = "#006600";
v_colores_b[i] = "#00ff00";
}


max_scale = v_sorteo_a[0];
max_scale = parseInt(max_scale);

if (max_scale < v_sorteo_b[0]) {
max_scale = v_sorteo_b[0];
}

max_scale = parseInt(max_scale);


var ctx2 = document.getElementById("graf_bar");

new Chart(ctx2, {
    type: 'bar',

    data: {
      labels: v_entidades,
      datasets: [
        {
          label: sorteo_a,
          backgroundColor: v_colores_a,
          data: v_sorteo_a
        },{
          label: sorteo_b,
          backgroundColor: v_colores_b,
          data: v_sorteo_b
        }
      ]
    },
    options: {
    	 scales: {
            yAxes: [{
                ticks: {
                    max:max_scale,
                    min:0,
                }
            }]
        },
      legend: { display: true },
      title: {
        display: true,
        text: ''
      }
    }
});

}


function cargar_donut_ventas(){

venta = document.getElementById('h_donut_venta').value;

no_venta = document.getElementById('h_donut_no_venta').value;

var ctx2 = document.getElementById("graf_don");

new Chart(ctx2, {
    type: 'doughnut',
    data: {
      labels: ["VENDIDO", "NO VENDIDO"],
      datasets: [
        {
          label: "",
          backgroundColor: ["#006600", "#cc0000"],
          data: [venta, no_venta]
        }
      ]
    },
    options: {
  	  rotation: 1 * Math.PI,
      circumference: 1 * Math.PI,
      title: {
        display: true,
        text: ''
      }
    }
});


}



</script>