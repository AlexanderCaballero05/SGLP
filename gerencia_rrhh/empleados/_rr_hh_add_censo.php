<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['usuario'] ;
if (isset($_GET['id'])) {
  $siid=true;
  $identidad_persona= $_GET['id'];
}

  
?>
	<style type="text/css" media="print"> 
	@page {    size:  portrait;  } 
	 th, td { padding-bottom: 0px;   border-spacing: 0; font-family: Arial; font-size: 09pt; } 
	</style> 

	<style type="text/css">
          /* form starting stylings ------------------------------- */
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
              font-size:20px;
              font-weight:normal;
              position:absolute;
              pointer-events:none;
              left:25px;
              top:-30px;
              transition:0.2s ease all; 
              -moz-transition:0.2s ease all; 
              -webkit-transition:0.2s ease all;
            }

            /* active state */
            input:focus ~ label, input:valid ~ label        
            {
              top:-20px;
              font-size:18px;
              color:#5264AE;
            }

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
	@media print    
	{
	    #no_print { display: none; }          
	}  
	</style>


	<script type="text/javascript">
	$(document).ready(function ()
	{
		$("#txtid").mask("9999-9999-99999", { placeholder: "____-____-____ " });
	}); 

    $(document).ready(function ()
  {
    $("#txttelefono").mask("9999-9999", { placeholder: "____-____" });
  }); 

       $(document).ready(function ()
  {
    $("#txtcelular").mask("9999-9999", { placeholder: "____-____" });
  }); 


		 $(".div_wait").fadeIn("fast");  
	</script> 


<form method="post" class="form-control" >
<div id='div_wait'></div>
 
<section style="text-align: center; background-color:#ededed; padding-top: 10px;">
  <p><h3>ALTA DE PERSONAS EN EL CENSO DEL 2017 | PANI </h3> </p><br></section>


<section id="no_print"> 
    <div class="container-fluid" style="width: 80%">
        <div class="row">
          <div class="col-sm-12"><br>
         <?php if ($siid==true): ?>

             <div class="group input-group" style="margin-top:10%;">    
                                    <input type="text" id="txtid" name="txtid"  required="true" style="width: 95%" value="<?php echo $identidad_persona?>"  readonly>
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label>Identidad </label>
                </div> 
        <?php else: ?>

            <div class="group input-group" style="margin-top:10%;">    
                                    <input type="text" id="txtid" name="txtid"  required="true" style="width: 95%">
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label>Identidad </label>
            </div> 

         <?php endif ?>                     

            <div class="group input-group" style="margin-top:10%;">      
                <input type="text" id="txtprimernombre" name="txtprimernombre" style="width: 20%;" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' required="true" value="" >
                <input type="text" id="txtsgdonombre" name="txtsgdonombre" style="width:20%; margin-left:5%" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()'   value="">
                <input type="text" id="txtprimerapellido" name="txtprimerapellido" style="width: 20%;  margin-left:5%;" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' required="true" value="" >
               <input type="text" id="txtsegundoapellido" name="txtsegundoapellido" style="width: 20%;  margin-left:5%;" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' required="true"  value="">
               <span class="highlight"></span>
               <span class="bar"></span>
               <label>Nombre Completo </label>
            </div>

           <div class="group input-group" style="margin-top:10%;">      
                                    <input type="date" id="fechanacimiento" name="fechanacimiento" style="width:95%" required="true"  >
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label>Fecha de Nacimiento </label>
          </div>
           </div>      
            </div> 
            <br><br>
        <div class="row">
          <div class="col-sm-3"></div>
          <div class="col-sm-6">
              <button type="submit"class="btn btn-success btn-lg  btn-block"> Guardar Registro</button>
          </div>
          <div class="col-sm-3"></div>  
        </div>
    </div>
</section>
 <?php 
 if ($_SERVER["REQUEST_METHOD"] == "POST") 
 {  

     $_identidad            =  $_POST['txtid'];
     $_identidad_add        = str_replace( '-', '', $_identidad);    
     $fecha_nacimiento_info =  $_POST['fechanacimiento'];
     $_fecha_nacimiento     = date('Y/m/d', strtotime($fecha_nacimiento_info));   
     $_primer_nombre        = $_POST['txtprimernombre'];
     $_segundo_nombre       = $_POST['txtsgdonombre'];
     $_primer_apellido      = $_POST['txtprimerapellido'];
     $_segundo_apellido     = $_POST['txtsegundoapellido'];
     $_nombre_completo      = $_primer_nombre ." ". $_segundo_nombre  ." ". $_primer_apellido ." ".$_segundo_apellido;   

 
    $query_insert_empleado= "INSERT INTO censo_2017(identidad, nombre_completo, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido,      fecha_nacimiento_txt) 
                                  VALUES ('$_identidad_add', '$_nombre_completo', '$_primer_nombre' , '$_segundo_nombre' , '$_primer_apellido' ,  '$_segundo_apellido',  '$_fecha_nacimiento');";
      //echo $query_insert_empleado;    
    
         if (mysqli_query($conn,$query_insert_empleado)) {
           //echo "<div class='alert alert-success'> Se ha registrado el empleado correctamente </div>";
            ?>
            <script type="text/javascript">
             
                    swal({
                      title: "",
                        text: "Persona agregada Exitosamente!.",
                        type: "success" 
                      })  
                      .then(function(result){
                          window.close();
                        });
              </script>  
          <?php 

         }
         else
         {

            ?>
            <script type="text/javascript">
             
                    swal({
                      title: "",
                        text: "Ha ocurrido un error!.",
                        type: "error" 
                      });
              </script>  
          <?php 

          echo "<div class='alert alert-danger'> Error : ".mysqli_error($conn)." </div>";
         }




 }
 ?>

 </form>

 <script type="text/javascript">
  $(".div_wait").fadeOut("fast");  
 </script>

 