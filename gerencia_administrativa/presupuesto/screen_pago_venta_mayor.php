<?php
require('../../template/header.php');

date_default_timezone_set('America/Tegucigalpa');
$date = date('Y-m-d', time()); 

?>


<script type="text/javascript">


function consultar_pago_venta(){


fecha_inicial = document.getElementById('fecha_inicial').value;
fecha_final   = document.getElementById('fecha_final').value;
fecha_actual   = document.getElementById('fecha_actual').value;


if (fecha_inicial === '') {
  swal({ 
  title: "",
   text: "Debe seleccional una Fecha Inicial",
    type: "error" 
  });  
}else{

if (fecha_final === '') {
  swal({ 
  title: "",
   text: "Debe seleccional una Fecha Final",
    type: "error" 
  });  
}else{


if (fecha_inicial == fecha_actual) {

  swal({ 
  title: "",
   text: "No se puede seleccionar el dia de hoy como fecha inicial.",
    type: "error" 
  });  

}else{


if (fecha_final == fecha_actual) {

  swal({ 
  title: "",
   text: "No se puede seleccionar el dia de hoy como fecha final.",
    type: "error" 
  });  

}else{


$(".div_wait").fadeIn("fast");

token = Math.random();
consulta = 'consulta_pago_venta.php?f_i='+fecha_inicial+"&f_f="+fecha_final+"&filtro=1&token="+token;     
$("#div_respuesta").load(consulta);



  
}

}


}

}

}
</script>

<style type="text/css">

.div_wait {
  display: none;
  position: fixed;
  left: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  z-index: 9999;
  background-color: black;
  opacity:0.5;
  background: url(./imagenes/wait.gif) center no-repeat #fff;
}

</style>

<div id="div_wait" class="div_wait"></div>


<br>


<input type="hidden" value = '<?php echo $date; ?>' id="fecha_actual">




<div class="card text-center border-info" style="margin-right: 15px; margin-left: 15px"> 
  <div class="card-header text-white bg-info">
    <h3 align="center">REPORTE DE PAGO Y VENTA DE LOTERIA</h3>
    <hr style="color: white">
    <ul class="nav nav-tabs card-header-tabs">
      <li class="nav-item">
        <a class="nav-link active" href="#">Loteria Mayor</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" style="color: white" href="pago_venta_menor.php">Loteria Menor</a>
      </li>
    </ul>
  </div>
  <div class="card-body" >

  

<div class="card-group">

<div class="card" >
<div class="card-header ">
Parametros de Busqueda
</div>
<div class="card-body">



<div class="input-group" style="margin:10px 0px 10px 0px;">
<input type="text" placeholder="Fecha Inicial" name="fecha_inicial" id="fecha_inicial" class="form-control" >
</div>


<div class="input-group" style="margin:10px 0px 10px 0px;">
<input type="text"  placeholder="Fecha Inicial" name="fecha_final" id="fecha_final" class="form-control" >
</div>


<script>
$('#fecha_inicial').datepicker({
locale: 'es-es',
format: 'yyyy-mm-dd',
uiLibrary: 'bootstrap4'
});

$('#fecha_final').datepicker({
locale: 'es-es',
format: 'yyyy-mm-dd',
uiLibrary: 'bootstrap4'
});

</script>


<br>
<span class="btn btn-info" onclick="consultar_pago_venta()">Consultar</span>

</div>
</div>





<div class="card">
<div class="card-header ">
Resultado Loteria Mayor
</div>


<div class="card-body" id="div_respuesta">


</div>
</div>

</div>  



</div>

</div>