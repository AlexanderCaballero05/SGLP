<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['usuario'] ;
 
  $conn2 = mysqli_connect('192.168.15.248:3306', 'SVR_APP', 'softlotpani**', 'pani') or die('No se pudo conectar: ' . mysqli_error());

 
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
 

		 $(".div_wait").fadeIn("fast");  
	</script> 


<form method="post" class="form-control" >
<div id='div_wait'></div>
 
<section style="text-align: center; background-color:#ededed; padding-top: 10px;">
  <p><h3>CONSULTA DE PERSONAS  POR NOMBRE EN EL CENSO DEL 2017 | PANI </h3> </p><br></section>


<section id="no_print"> 
    <div class="container-fluid" style="width: 80%">
        <div class="row">
          <div class="col-sm-12"><br>
                         

            <div class="group input-group" style="margin-top:10%;">      
                <input type="text" id="txtprimernombre" name="txtprimernombre" style="width: 20%;" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' required="true" >
                <input type="text" id="txtsgdonombre" name="txtsgdonombre" style="width:20%; margin-left:5%" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()'>
                <input type="text" id="txtprimerapellido" name="txtprimerapellido" style="width: 20%;  margin-left:5%;" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' required="true">
               <input type="text" id="txtsegundoapellido" name="txtsegundoapellido" style="width: 20%;  margin-left:5%;" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()'>
               <span class="highlight"></span>
               <span class="bar"></span>
               <label>Nombre Completo </label>
            </div>
           
           </div>      
            </div> 
            <br><br>
        <div class="row">
          <div class="col-sm-4">
            <button type="submit"class="btn btn-success btn-lg  btn-block"> Consultar Registro</button>
          </div>
          <div class="col-sm-6">
              
          </div>
          <div class="col-sm-3"></div>  
        </div>
    </div>
</section>
 <?php 
 if ($_SERVER["REQUEST_METHOD"] == "POST") 
 {  

      
     if (  !empty($_POST['txtprimernombre']) && !empty($_POST['txtsgdonombre']) && !empty($_POST['txtprimerapellido'])  &&  !empty($_POST['txtsegundoapellido'] )) {
     
        $_primer_nombre        = $_POST['txtprimernombre'];
        $_segundo_nombre       = $_POST['txtsgdonombre'];
        $_primer_apellido      = $_POST['txtprimerapellido'];
        $_segundo_apellido     = $_POST['txtsegundoapellido'];
        $_nombre_completo      = $_primer_nombre ." ". $_segundo_nombre  ." ". $_primer_apellido ." ".$_segundo_apellido; 

         $query_insert_empleado= mysqli_query($conn2, "SELECT *  FROM censo_2017 WHERE primer_nombre = '$_primer_nombre' and segundo_nombre = '$_segundo_nombre' and primer_apellido = '$_primer_apellido' and segundo_apellido = '$_segundo_apellido'  ") ;

      } else if (!empty($_POST['txtprimernombre']) && !empty($_POST['txtsgdonombre']) && !empty($_POST['txtprimerapellido'])  ) {
     
        $_primer_nombre        = $_POST['txtprimernombre'];
        $_segundo_nombre       = $_POST['txtsgdonombre'];
        $_primer_apellido      = $_POST['txtprimerapellido']; 
      

         $query_insert_empleado= mysqli_query($conn2, "SELECT * FROM censo_2017 WHERE primer_nombre = '$_primer_nombre' and  segundo_nombre = '$_segundo_nombre' and  primer_apellido = '$_primer_apellido'   ") ;

      }else if (!empty($_POST['txtprimernombre'])  && !empty($_POST['txtprimerapellido'])  ) {
      
        $_primer_nombre        = $_POST['txtprimernombre'];        
        $_primer_apellido      = $_POST['txtprimerapellido']; 

        $query_insert_empleado= mysqli_query($conn2, "SELECT * FROM censo_2017 WHERE primer_nombre = '$_primer_nombre' and primer_apellido = '$_primer_apellido';") ;

      }

      // error_reporting(E_ALL);
      // ini_set('display_errors', 1);

       // echo $query_insert_empleado;    
    
         
             if ($query_insert_empleado) 
             {

               echo "<br><hr><table class='table table-sm table-bordered table-hover table-striped'>
                        <thead><th>Identidad</th><th>Nombre Completo</th><th>Fecha de Nacimiento</th><thead><tbody>";
                      
                       // echo mysqli_num_rows($query_insert_empleado);
                  
                
                          
               while ($row_empleado = mysqli_fetch_array($query_insert_empleado)  )
               {
                
                $id                = $row_empleado['identidad'];
                $nombre_completo   = $row_empleado['nombre_completo'];
                $fecha_nacimiento  = $row_empleado['fecha_nacimiento_txt'];

                 echo "<tr>
                          <td>".$id."</td>
                          <td>".utf8_encode($nombre_completo)."</td>
                          <td>".$fecha_nacimiento."</td>
                       </tr>";
               }
             
         }
         else
         {
 

          echo "<div class='alert alert-danger'> Error : ".mysqli_error($conn2)." </div>";
         }




 }
 ?>

 </form>

 <script type="text/javascript">
  $(".div_wait").fadeOut("fast");  
 </script>

 