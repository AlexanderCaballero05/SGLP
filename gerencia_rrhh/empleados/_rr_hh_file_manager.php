<?php 
require('../../template/header.php'); 
$usuario_id   = $_SESSION['usuario'] ;
$usuario_name = $_SESSION['nombre_usuario'];
define ('FTP_LOCALDIR', 'c:/temp');

if (isset($_GET['id'])) 
{
  $siid=true;
  $identidad_persona = $_GET['id'];     
  $identidad_txt     = str_replace( '-', '', $identidad_persona);
}      
?> 
  <style type="text/css" media="print"> 
  @page {    size:  portrait;  } 
   th, td { padding-bottom: 0px;   border-spacing: 0; font-family: Arial; font-size: 09pt; } 
  </style> 

  <style type="text/css">
    @media only screen and (max-width: 700px) {
     }
          /* form starting stylings ------------------------------- */
      .group{ 
              position:relative; 
              margin-bottom:25px; 
            }
            input               {
              font-size:13px;
              padding:10px 10px 10px 5px;
              display:block;
              width:90%;
              border:none;
              border-bottom:1px solid #757575;
            }
            input:focus         { outline:none; }

         
            input:focus ~ label, input:valid ~ label        
            {
              top:-20px;
              font-size:13px;
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
          $("#celularfam").mask("9999-9999",  { placeholder: "____-____" });
  }); 

  $(".div_wait").fadeIn("fast");

 </script> 
<form method="post" class="form-control" enctype="multipart/form-data">
<div id='div_wait'></div><div id="getid"></div>
<div class="container-fluid" style="width: 100%">
<section style="text-align: center; background-color:#ededed; padding-top:20px; padding-bottom:-5%;">
<h4>ADMINISTRACION DE EXPEDIENTES DE EMPLEADOS <br> <?php echo $identidad_persona ?> </h4> <br>
</section>
<section id="no_print"> 
    
      <div class="card">
        <div class="card-header bg-info text-white">Listado de Documentos!</div>
       <div class="card-body">
        <div class="row">
           <div class="col-sm-8"></div>
           <div class="col-sm-4">
              <div align="right"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"> Nuevo</button></div>
           </div>
        </div>
        <div class="row">
          <div class="col-sm-12"><br>   
              <div class="table-responsive">
                      <!-- table id="table_id1" class="table table-bordered table-sm table-hove w-auto" style="width:100%;   font-size:13px;" -->
                      <table id="table_id" class="table table-bordered table-sm table-hover" style="width:100%;   font-size:13px;">
                           <thead><tr><th>#</th>
                                      <th>Fecha de Creacion</th>                                  
                                      <th>Nombe del Documento</th>
                                      <th>Tamaño en MB</th>
                                      <th>Descripción</th><th></th></tr>
                           </thead>
                           <tbody>                              
                                    <?php 
                                    $query_select_files= mysqli_query($conn, "SELECT * FROM rr_hh_empleados_files WHERE identidad_empleado= '$identidad_txt' ");                                
                                    if (mysqli_num_rows($query_select_files)>0) 
                                    {       
                                        $contador=1;                             
                                        while ( $row_files = mysqli_fetch_array($query_select_files))  
                                        {
                                            $id                           = $row_files['id'];                                        
                                            $fecha_creacion               = $row_files['datecreate'];
                                            $nombre_archivo               = $row_files['nombre_archivo'];
                                            $descripcion_archivo_select   = $row_files['descripcion_archivo'];
                                            $tamaño_archivo               = $row_files['size'];

                                             echo "<tr> <td>".$contador."</td>
                                                        <td>".$fecha_creacion."</td>
                                                        <td>".$nombre_archivo."</td>
                                                        <td>".$tamaño_archivo."</td>
                                                        <td>".$descripcion_archivo_select."</td>                                                    
                                                        <td>
                                                           <button class='btn btn-danger' type='submit' name='descargar' value='".$id."'> Descargar Archivo</button>
                                                        </td>                                                    
                                                  </tr>";   

                                            $contador++;                               
                                        }
                                    }
                                    else {  echo "error";  }     
                                     ?>   
                          </tbody>                       
                 </table>
              </div>   
        </div><hr>
      </div> 
    </div>
  </div> 
</section>
</div>  
 <section><hr>
    <div id="no_print" align="center"><button type='submit' class="btn btn-primary btn-sm"> Descargar Todo (Zip) </button></div>
    <div id='msgfile'></div>    
 </section>
  <?php 
 if ($_SERVER["REQUEST_METHOD"] == "POST") 
 {        
   if (isset($_POST['agregar_expediente'])) 
   {
       $servidor_ftp = "192.168.15.10";
       $conexion_id  = ftp_connect($servidor_ftp);    
       $ftp_usuario  = "SGRHP";
       $ftp_clave    = "sgrhp2020**";     

       $ftp_carpeta_remota   =   $identidad_txt."/";          
       $nombre_archivo       =   $_FILES["fileperson"]["name"];
       $archivo_destino      =   $_FILES["fileperson"]["tmp_name"]; 
       $descript_file        =   $_POST['descripcion_expediente'];
       $tamaño_file          =   ($_FILES["fileperson"]["size"] / 1048576) ;        
       $resultado_login      =   ftp_login($conexion_id, $ftp_usuario, $ftp_clave);  
       $msg_exito_conexion   =   "";
      
                if ((!$conexion_id) || (!$resultado_login)) 
                {
                      $msg_exito_conexion =  "<br>La conexion ha fallado! al conectar con  $servidor_ftp para usuario $ftp_usuario";
                      exit;
                } 
                else 
                {
                     $msg_exito_conexion = "Conectado a $servidor_ftp";
                }   
         $query_validacion_archivo = mysqli_query($conn, "SELECT * FROM rr_hh_empleados_files WHERE identidad_empleado = '$identidad_txt' and nombre_archivo = '$nombre_archivo' ");          
         if ( mysqli_num_rows($query_validacion_archivo)>0 )  
         {               
          ?>          
                  <script type="text/javascript">
                    $(".div_wait").fadeOut("fast");  
                               swal("Error!", "Este archivo ya existe en el expediente, favor validar!", "error")
                                   .then(function(result){
                                    window.location.replace('./_rr_hh_file_manager.php?id='+'<?php echo $identidad_persona ?>');
                                });
                  </script>
                  <?php 
                  exit();               
         }
         else 
         {
              ftp_pasv ($conexion_id, true);
                 $upload = ftp_put($conexion_id, $ftp_carpeta_remota.$nombre_archivo  , $archivo_destino,   FTP_BINARY);
                 if (!$upload) 
                 {
                    echo  "<div class='alert alert-danger'>" .$msg_exito_conexion."Ha ocurrido un error al subir el archivo</div>";
                 } 
                 else 
                 {
                      $query_insert_file = "INSERT INTO rr_hh_empleados_files ( identidad_empleado, direccion_archivo    ,  nombre_archivo  , descripcion_archivo, usuario_creacion, size)
                                                                      VALUES ('$identidad_txt'   , '$ftp_carpeta_remota', '$nombre_archivo', '$descript_file' , '$usuario_name' , $tamaño_file)";

                      if (!mysqli_query($conn, $query_insert_file)) 
                      {
                         echo "Error en el insert ".mysqli_error($conn);
                      } 
                      else 
                      {
                         $mensaje_insert = "Agregado";
                      } 
                         ?>
                              <script type="text/javascript">
                                  swal("Exito!", "Has subido el archivo al expediente!", "success")
                                  .then(function(result){
                                    window.location.replace('./_rr_hh_file_manager.php?id='+'<?php echo $identidad_persona ?>');
                                });
                              </script>
                        <?php 
                 }
         }        
        ftp_close($conexion_id);
   } else if (isset($_POST['descargar'])) {
         echo "Descargar archivo individul" .$_POST['descargar'];
         // FTP server details
             $_id=$_POST['descargar'];
             $query_download_file    =   mysqli_query($conn, "SELECT * FROM rr_hh_empleados_files WHERE id=$_id");
             $bj_download_file       =   mysqli_fetch_object($query_download_file);
             $_direc_file            =   $bj_download_file->direccion_archivo;
             $_name_file             =   $bj_download_file->nombre_archivo;


            $servidor_ftp = "192.168.15.10";
            $conexion_id  = ftp_connect($servidor_ftp);    
            $ftp_usuario  = "SGRHP";
            $ftp_clave    = "sgrhp2020**";   

                    $resultado_login      =   ftp_login($conexion_id, $ftp_usuario, $ftp_clave);    
                      if ((!$conexion_id) || (!$resultado_login)) 
                      {
                            $msg_exito_conexion =  "<br>La conexion ha fallado! al conectar con  $servidor_ftp para usuario $ftp_usuario";
                            exit;
                      } 
                      else 
                      {
                           $msg_exito_conexion = "Conectado a $servidor_ftp";
                      }   

         
                     ftp_pasv($conexion_id, true);
                     ftp_chdir($conexion_id, $_direc_file);                    
                     echo "<br>Esta es la ubicacion ".ftp_pwd($conexion_id)."<br>";
                 //    $file_list = ftp_nlist($conexion_id, ".");
                   //   var_dump($file_list);

                     $upload = ftp_get($conexion_id, "123.pdf"  ,   "123.pdf",  FTP_ASCII);
                     if (!$upload) 
                     {
                        echo  "<div class='alert alert-danger'>" .$msg_exito_conexion."Ha ocurrido un error al subir el archivo</div>";
                     } 
                     else 
                     {                        
                             ?>
                                  <script type="text/javascript">
                                      swal("Exito!", "Has subido el archivo al expediente!", "success")
                                      .then(function(result){
                                        window.location.replace('./_rr_hh_file_manager.php?id='+'<?php echo $identidad_persona ?>');
                                    });
                                  </script>
                            <?php 
                     }
   }      
} 
 ?>
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Agregar Expedientes</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>        
        <!-- Modal body -->
        <div class="modal-body">
          <div class="container">
            <div class="row">
                <div class="col-sm-12">                  
                    <input type="hidden" name="identidad" class="form-control" id="identity" value=" <?php echo $identidad_txt ?> ">                  
                     <div class="input-group" style="margin-bottom: 10px">
                        <div class="input-group-prepend">
                        <div class="input-group-text" style="min-width: 180px; ">Descripción: </div>
                        </div>
                        <textarea id="descripcion_expediente" name="descripcion_expediente" class="form-control" rows="2" ></textarea>
                    </div>
                    <div class="input-group" style="margin-bottom: 10px">
                        <div class="input-group-prepend">
                        <div class="input-group-text" style="min-width: 180px; ">Archivo: </div>
                        </div>
                          <input type="file" name="fileperson" id="fileperson" class="form-control"  >
                    </div>
                </div>
            </div>
          </div>
        </div>        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button  type="submit" name="agregar_expediente" value="agregar_expediente" class="btn btn-secondary"> Agregar Archivo</button>
        </div>        
      </div>
    </div>
  </div>
 </form>

 <script type="text/javascript">
        $(".div_wait").fadeOut("fast");  
 </script>