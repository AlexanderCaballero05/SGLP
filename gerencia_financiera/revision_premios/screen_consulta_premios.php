<?php 
require('../../template/header.php'); 
require ("funcs.php");
$usuario_id=$_SESSION['id_usuario'];
$nombre_usuario=$_SESSION['nombre'];
?>
 
<script type="text/javascript">
 $(document).ready(function ()
 {    
            $('.Consulta').click(function()     
                {   
                  var sorteo=$("#selectsorteo").val(); 
                  var numero=$("#txtnumero").val();    
                  var serie=$("#txtserie").val();  

                     if (sorteo == '' || numero== '' || serie== '')
                     {
                       swal("Error...!", "Seleccione todos los campos necesarios para la consulta!", "error");
                     }
                      else
                     {
                       $(".div_wait").fadeIn("fast");
                       var urr = '_pp_consulta_menor.php?sorteo='+sorteo+'&numero='+numero+'&serie='+serie+'&valida='+Math.random();
                        $("#info_sorteo_menor").load(urr); 
                     }
                });

            $('.Consulta_mayor').click(function()     
                {   

                  var sorteo=$("#selectsorteo_mayor").val(); 
                  var numero=$("#txtnumero_mayor").val();  

                 // alert(sorteo +"--"+numero);   

                     if (sorteo == '' || numero== '' )
                     {
                       swal("Error...!", "Seleccione todos los campos necesarios para la consulta de loteria mayor!", "error");
                     }
                      else
                     {
                       $(".div_wait").fadeIn("fast");
                       var urr = '_pp_consulta_mayor.php?sorteo='+sorteo+'&numero='+numero+'&valida='+Math.random();
                      // alert(urr);   
                        $("#info_sorteo_mayor").load(urr); 
                     }
                });
  });
</script>
<style type="text/css">
   .group            { 
              position:relative; 
              margin-bottom:25px; 
            }
            input               {
              font-size:18px;
              padding:10px 10px 10px 5px;
              display:block;
              width:100%;
              border:none;
              border-bottom:1px solid #757575;
            }
            input:focus         { outline:none; }

            /* LABEL ======================================= */
            label                
            {
              color:#999; 
              font-size:18px;
              font-weight:normal;
              position:absolute;
              pointer-events:none;
              left:5px;
              top:10px;
              transition:0.2s ease all; 
              -moz-transition:0.2s ease all; 
              -webkit-transition:0.2s ease all;
            }

            /* active state */
            input:focus ~ label, input:valid ~ label        
            {
              top:-20px;
              font-size:14px;
              color:#5264AE;
            }yu

            .borderless td, .borderless th {
                border: none;
            }
.div_wait 
{
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
<form>
  <div id='div_wait'></div> 
  <section style="text-align: center; background-color:#ededed; padding-top: 20px;"><h3>Consulta de Premios de Lotería Menor y Mayor</h3> <br></section>
  <section>
  <a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS DE LOTERIA MENOR  <i class="far fa-hand-point-down fa-lg"></i></a>
  <div class="collapse " id="collapse1" align="center"><br>
       <div class="row">
          <div class="col-sm-4">
              <div class="card">
                  <div class="card-header"><h5 class="mb-0">Consulta de Premios</h5></div>
                  <div class="card-body"  align="center">
                    <div class="group"> 
                        <select class="form-control" id="selectsorteo" name="selectsorteo">
                        <option value=''>Seleccione sorteo</option> 
                        <?php 
                             echo sorteos_menores();
                        ?>   
                        </select>
                        <span class="highlight"></span>
                        <span class="bar"></span>                    
                    </div>
                    <div class="group">      
                        <input type="text" maxlength="2" id="txtnumero" name="txtnumero" onkeypress="return justNumbers(event)">
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label>NÚMERO</label>
                    </div>
                    <div class="group">      
                        <input type="text" maxlength="6" id="txtserie" name="txtserie" onkeypress="return justNumbers(event)">
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label>SERIE</label>
                    </div>
                  <button class="Consulta btn btn-success active btn-sm justify-content-center" type="button">CONSULTAR <i class="fas fa-search" style="font-size:15px;"></i></button>
                </div>
              </div>
          </div>
          <div class="col-sm-8">
              <div class="card" id="info_sorteo_menor">
                <div class="card-header" style="background-color:rgba(0,0,0,0.03);">  <h5 class="mb-0">Información del Billete</h5> </div>
                 <div class="card-body" style="padding-top:260px;" ></div>
              </div>
          </div>
      </div>       
  </div>
  </section>
<br><br><br>
  <section>
  <a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse2" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS DE LOTERIA MAYOR  <i class="far fa-hand-point-down fa-lg"></i></a>
  <div class="collapse secondary" id="collapse2" align="center"><br>    
    <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header"><h5 class="mb-1">Consulta de Premios</h5></div>
                    <div class="card-body" align="center">
                      <div class="group">       
                            <select class="form-control" id="selectsorteo_mayor" name="selectsorteo_mayor">
                            <option value=''>Seleccione Sorteo</option> 
                                <?php 
                                   echo sorteos_mayores();                                     
                                ?>   
                              </select>
                             <span class="highlight"></span><span class="bar"></span>
                      </div>
                      <div class="group">      
                          <input type="text" maxlength="5" id="txtnumero_mayor" name="txtnumero_mayor" onkeypress="return justNumbers(event)">
                          <span class="highlight"></span>
                          <span class="bar"></span><label>NÚMERO</label>
                      </div>
                      <button class="Consulta_mayor btn btn-success active btn-sm justify-content-center" type="button">CONSULTAR <i class="fas fa-search" style="padding-bottom:3px; font-size:15px;"></i></button></div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card" id="info_sorteo_mayor">
                    <div class="card-header" style="background-color:rgba(0,0,0,0.03);"><h5 class="mb-0">Información del Billete</h5></div>
                    <div class="card-body" style="padding-top:184px; "></div>
                </div>
            </div>
    </div>
    </div>
  </section>


  <script type="text/javascript">
  //$(".div_wait").fadeOut("fast");  
  </script>
</form>
