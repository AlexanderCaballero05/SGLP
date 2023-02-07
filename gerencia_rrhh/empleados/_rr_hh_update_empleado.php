<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['usuario'] ;
$usuario_name= $_SESSION['nombre_usuario'];


if (isset($_GET['id'])) {
  $siid=true;
  $identidad_persona= $_GET['id']; 
  $_SESSION['identity']= $_GET['id'];
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
		 $(".div_wait").fadeIn("fast");  



	</script> 


<form method="post" class="form-control" action="_rr_hh_update_empleado_db.php" >
<div id='div_wait'></div>
 
<section style="text-align: center; background-color:#ededed; padding-top: 10px;">
  <p><h3>ACTUALIZACION DE INFORMACION DE EMPLEADOS DEL PANI </h3> </p><br></section>


<section id="no_print"> 
    <div class="container-fluid" style="width: 80%">
        <div class="row">
          <div class="col-sm-6"><br>  
          <?php 
            $query_info_empleados = "SELECT * FROM rr_hh_empleados WHERE identidad= '$identidad_persona';"; 

         //   echo $query_info_empleados;

            $info_empleados= mysqli_query($conn, $query_info_empleados);
            
            while ( $row_infor_empleados = mysqli_fetch_array($info_empleados) ) 
            { 
               $rtn_info               = $row_infor_empleados['rtn'];
               $sexo_info              = $row_infor_empleados['sexo'];
               $nombre_completo_info   = $row_infor_empleados['nombre_completo'];
               $fecha_nacimiento_info  = $row_infor_empleados['fecha_nacimiento'];
               $lugar_nacimiento_cod   = $row_infor_empleados['lugar_nacimiento'];
               $tipo_sangre_info       = $row_infor_empleados['tipo_sangre'];
               $domicilio_info         = $row_infor_empleados['domicilio'];
               $telefono_info          = $row_infor_empleados['telefono'];
               $celular_info           = $row_infor_empleados['celular'];
               $estado_civil_info      = $row_infor_empleados['estado_civil'];
               $escolaridad_info       = $row_infor_empleados['escolaridad'];
               $fecha_ingreso_info     = $row_infor_empleados['fecha_ingreso'];
               $cod_empleado_info      = $row_infor_empleados['cod_empleado'];
               $cod_marca_info         = $row_infor_empleados['cod_marcacion'];


                $old_rtn              =   $rtn_info;
                $old_domicilio        =   $domicilio_info ;
                $old_telefono         =   $telefono_info ;
                $old_celular          =   $celular_info  ;
                $old_estado_civil     =   $estado_civil_info ;
                $old_escolaridad      =   $escolaridad_info ;

                echo "
                 <input type='hidden' name='rtn_info' value='".$rtn_info."''>
                 <input type='hidden' name='domicilio_info' value='".$domicilio_info."''>
                 <input type='hidden' name='telefono_info' value='".$telefono_info."''>
                 <input type='hidden' name='celular_info' value='".$celular_info."''>
                 <input type='hidden' name='estado_civil_info' value='".$estado_civil_info."''>
                 <input type='hidden' name='escolaridad_info' value='".$escolaridad_info."''>
                ";
            } 

           ?>   

            <div class="group input-group" style="margin-top:10%;">    
                                    <input type="text" id="" name="txtid"  required="true" style="width: 95%" value=" <?php echo $identidad_persona ?> " readonly >
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label>Identidad </label>
            </div>  

           <div class="group input-group" style="margin-top:10%;">      
                                    <input type="text" id="txtrtn" name="txtrtn" style="width: 95%" onkeypress="return soloLetras(event)" required="true" maxlength="14" value="  <?php echo $rtn_info  ?> " >
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label> RTN </label>
           </div>


          <div class="group input-group" style="margin-top:10%; width:95%"> 
            <script type='text/javascript'>
                  $(function() {
                                var temp3="<?php echo  $sexo_info ?>"; 
                                $("#txtsexo").val(temp3);
                            });                                             
                </script>    
      
                                   <select class="form-control" name='txtsexo' id='txtsexo' disabled="true">
                                    <option value=""> Seleccione ....</option>
                                    <option value="M"  > HOMBRE </option>
                                    <option value="F"  > MUJER </option>
                                   </select>
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label> SEXO </label>
          </div>

          <div class="group input-group" style="margin-top:10%;">      
                                    <input type="text" id="txtprimernombre" name="txtprimernombre" style="width: 95%;" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' required="true" value="<?php echo $nombre_completo_info?>" readonly >
                                    
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label>Nombre Completo </label>
           </div>                 

          <div class="group input-group" style="margin-top:10%;">      
                                    <input type="text" id="fechanacimiento" name="fechanacimiento" style="width:95%" required="true" value="<?php echo $fecha_nacimiento_info?>" readonly>
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label>Fecha de Nacimiento </label>
          </div>                                    
            
          <div class="group input-group" style="margin-top:10%; width:95%"> 
                                   <select class="form-control" name='lugarnacimiento' id='lugarnacimiento' style="" required="true" disabled="true">
                                      <option value=''> Seleccione ....</option>
                                     <?php 
                                         $query_lugar_nac=mysqli_query($conn, "SELECT cod_muni, municipio FROM geocodigos order by cod_muni asc");
                                         $statusac='';
                                         while ($row_muni=mysqli_fetch_array($query_lugar_nac)) {
                                           $codigo_muni = $row_muni['cod_muni'];
                                           $municipio   = $row_muni['municipio'];

                                           if ($codigo_muni==$lugar_nacimiento_cod) {
                                             $statusac='selected';
                                           }
                                           else
                                          {
                                             $statusac='';
                                          }

                                           echo "<option value='".$codigo_muni."' ".$statusac." > ".$codigo_muni." --  ".$municipio."</option>";
                                         }
                                      ?>
                                   </select>
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label>Lugar de Nacimiento </label>
           </div>   

                <div class="group input-group" style="margin-top:10%; width:95%">  
                                    <input type="text" id="" name="sangre" style="width: 95%"readonly value=" <?php echo $tipo_sangre_info  ?> ">
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label>Tipo de Sangre </label>
                </div>
                
                 <div class="group input-group" style="margin-top:10%; width:95%">      
                                    <textarea type='form-control' rows="5" id='domicilio' name='domicilio' style="width: 100%" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' required="true"  onblur=" $(this).val($(this).val().trim() );">
                                      <?php echo trim($domicilio_info); ?>
                                    </textarea>

                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label>Domicilio </label>
                </div>
            
          </div>
          <div class="col-sm-6"><br>

              <div class="group input-group" style="margin-top:10%;">      
                                    <input type="text" id="" name="txttelefono" style="width: 95%" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' required="true" value=" <?php echo $telefono_info ?> " maxlength="9" >
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label>Telefono Domicilio</label>
              </div>

               <div class="group input-group" style="margin-top:10%;">      
                                    <input type="text" id="" name="txtcelular" style="width: 95%" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' required="true" value=" <?php echo $celular_info ?> " maxlength="9">
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label>Telefono Celular </label>
              </div>

              <div class="group input-group" style="margin-top:10%; width:95%">  
              <script type='text/javascript'>
                  $(function() {
                                var temp="<?php echo  $estado_civil_info ?>"; 
                                $("#txtetcivil").val(temp);
                            });                                             
                </script>    
                              <select class="form-control" name='txtetcivil' id='txtetcivil' required="true">
                                    <option value=""> Seleccione ....</option>
                                    <option value="1"> CASAD@ </option>
                                    <option value="2"> VIUD@ </option>
                                    <option value="3"> DIVORCIAD@ </option>
                                    <option value="4"> SEPARAD@ </option>
                                    <option value="5"> SOLTER@ </option>
                                    <option value="6"> UNION LIBRE </option>
                              </select>
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label>Estado Civil </label>
              </div>
           

            <div class="group input-group" style="margin-top:10%; width:95%">    
              <script type='text/javascript'>
                  $(function() {
                                var temp2="<?php echo  $escolaridad_info ?>"; 
                                $("#escolaridad").val(temp2);
                            });
                                              
                </script>    
                              
                              <select class="form-control" name='escolaridad' id='escolaridad' required="true">
                                    <option value=""> Seleccione ....</option>
                                    <option value="1"> Ninguno </option>
                                    <option value="2"> Programa de Alfabetizacion </option>
                                    <option value="3"> Pre-Básica (1-3) </option>
                                    <option value="4"> Básica (1-9) </option>
                                    <option value="5"> Ciclo Común </option>
                                    <option value="6"> Diversificado </option>
                                    <option value="7"> Técnico Superior </option>
                                    <option value="8"> Superior No Universitaria </option>
                                    <option value="9"> Superior Universitario </option>
                                    <option value="10"> Post Grado </option>
                                    <option value="99"> No Sabe/Responde  </option>
                              </select>
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label>Escolaridad </label>
            </div>


            <div class="group input-group" style="margin-top:10%; width:95%">      
                                   <input type="text" name="fecha_ingreso" required="true" value=" <?php echo $fecha_ingreso_info ?> " readonly="true" >
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label>Fecha de Ingreso </label>
            </div>


            <div class="group input-group" style="margin-top:10%;">      
                                    <input type="text" id="txtcodempleado" name="txtcodempleado" style="width: 95%" onkeypress="return justNumbers(event)" required="true" readonly="true"  value=" <?php echo $cod_empleado_info ?> ">
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label>Codigo de Empleado </label>
            </div>

            <div class="group input-group" style="margin-top:10%;">      
                                    <input type="text"  name="txtcodmarcacion" style="width: 95%" required="true"  readonly="true" value=" <?php echo $cod_marca_info ?> ">
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label>Codigo de Marcacion </label>
            </div>

          </div>
        </div> <hr>
        <div class="row">
          <div class="col-sm-3"></div>
          <div class="col-sm-6">
              <button type="submit"class="btn btn-success btn-lg  btn-block"> Actualizar Registro</button>
          </div>
          <div class="col-sm-3"></div>  
        </div>
    </div>
</section>
 </form>

 <script type="text/javascript">
  $(".div_wait").fadeOut("fast");  
    $(document).ready(function ()
  {
    $("#txtid").mask("9999-9999-99999", { placeholder: "____-____-____ " });
    $("#txttelefono").mask("9999-9999", { placeholder: "____-____" });
    $("#txtcelular").mask("9999-9999", { placeholder: "____-____" });
  }); 

 </script>

 
