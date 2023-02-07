<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['usuario'] ; 
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
  opacity:0.5;
  background: url(../../template/images/wait.gif) center no-repeat #fff;
}
</style>

<script type="text/javascript">

function add_revisor(valor, estado)
{
  document.getElementById('fecha').value=document.getElementById('txt'+valor).value;
}

function quitar_revisor(valor, remesa)
{
     var fecha= document.getElementById('txt'+valor).value;
     $(".div_wait").fadeIn("fast");  
     var urr_dco = './rp_quitar_asignacion_menor.php?fecha='+fecha+'&remesa='+remesa+'&est=1&al='+Math.random(); 
     $("#tabl").load(urr_dco); 
}


function update_revisor()
{
   fecha=document.getElementById('fecha').value;
   remesa=document.getElementById('remesa_modal').value;
   revisor=document.getElementById('slctincidencia').value;
   $(".div_wait").fadeIn("fast");  
   var urr_dco = './rp_add_asignacion_menor.php?fecha='+fecha+'&remesa='+remesa+'&revisor='+revisor+'&est=1&al='+Math.random();    
   $("#tabl").load(urr_dco);
   //alert(urr_dco) ;
   $("[data-dismiss=modal]").trigger({ type: "click" });
   $('#myModal').trigger("reset");
}
</script>

<form method="post">

<div id='div_wait'></div>
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>ARCHIVOS y EXPEDIENTES DE EMPLEADOS</h3> <br></section>
 
<section>
<a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS  <i class="far fa-hand-point-down fa-lg"></i></a>
    <div class="collapse " id="collapse1" align="center">
      <div class="card">
        <div class="card card-body" id="non-printable" >
          <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
            <div class="input-group-prepend" style="margin-left: 5px;" ><span  class="input-group-text">Expediente: </span></div>
            <input type='text' min='35' name="expediente" id="expediente" class="form-control input-lg" required onkeypress="return justNumbers(event)" maxlength="13">
            <button type="submit" name="seleccionar" style="margin-left: 10px;" class="btn btn-primary" value = "Seleccionar">  Seleccionar &nbsp;<i class="fas fa-search fa-lg"></i></button>
          </div>
        </div>
      </div>
    </div> 
 </section>
 <hr>
<section>
<?php
if (isset($_POST['seleccionar'])) 
{  	

 echo "<div class='alert alert-warning'> <h3> No habilitado, En construccion </h3></div>";
} 
?>
</section>


<!-- Modal -->
 

</form>