<?php
require('../../template/header.php');
?>

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
        opacity: 0.5;
        background: url(../../template/images/wait.gif) center no-repeat #fff;
    }
</style>


<script>
    $(document).ready(function() {
        $("#txtid").mask("9999-9999-99999", {
            placeholder: "____-____-____ "
        });

    });
</script>



<section style="color:rgb(63,138,214);background-color:#ededed;">
<br>
<h3  align="center" style="color:black;" ><b>GESTION DE EMPLEADOS</b></h3>
<br>
</section>

<form method="post">
    <div id='div_wait'></div>



<a style = "width:100%"  class="btn btn-info" id = "non-printable" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
  Seleccion de Parametros de Busqueda 
</a>

<div class="collapse " id="collapse1" align="center">
		<div class="row">
		<div class="col col-md-4"></div>
		<div class="col col-md-4 card">
<br>
		<div class="input-group" >
			<div class="input-group-prepend" ><span  class="input-group-text">Identidad: </span></div>
			<input type="text" class="form-control" id="identidad" name="identidad"  >
			<div  class="input-group-append">
			<button class="btn btn-primary" type="submit" name="seleccionar"  > <span class="fa fa-search"></span></button>
			</div>
		</div>
<br>
		</div>
		<div class="col col-md-4"></div>
		</div>
</div>



</form>