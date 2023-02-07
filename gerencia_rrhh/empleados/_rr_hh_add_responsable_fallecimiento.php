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
    @media only screen and (max-width: 700px) {

}
          /* form starting stylings ------------------------------- */
  .group    { 
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

            /* LABEL ======================================= */
            label                
            {
              color:#999; 
              font-size:15px;
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
    $("#celularfam").mask("9999-9999-9999", { placeholder: "____-____-_____" });
  }); 

    $(".div_wait").fadeIn("fast"); 

    function GetIdRNP()
    {
                   var id=document.getElementById("txtid").value;
                   $(".div_wait").fadeIn("fast");
                   var urr = './_rr_hh_GetIdRNP.php?id='+id+'&valida='+Math.random();
                   $("#getid").load(urr);
                   document.getElementById('updfam').style.display = "none";

                  document.getElementById('sexofam').value="";
                  document.getElementById('celularfam').value="";
                  document.getElementById('domiciliofam').value="";
                  document.getElementById('trabajofam').value="";
                  document.getElementById('ocupaid').value="";
                  document.getElementById('parentescofami').value="";
    }

    function GetOcupacion(valor)
    {      
      document.getElementById('txtocupacion').value=valor;
    
    }

    function cargar_datos(idn, idenfam, nom, fech,  celtel, dom, idocupa, trab, observaciones)    
    {    
          //alert(idn + "--" + idenfam + "--" + nom + "--" + fech + "--" +   celtel + "--" + dom + "--" + idocupa + "--" + trab );
          var idocupacion=idocupa.trim();
          document.getElementById('txtid').value                = idenfam;
          document.getElementById('namefam').value              = nom; 
          document.getElementById('fechanac').value             = fech; 
          document.getElementById('celularfam').value           = celtel;
          document.getElementById('domiciliofam').value         = dom;         
          document.getElementById('ocupaid').value              = idocupacion;
          document.getElementById('trabajofam').value           = trab; 
          document.getElementById('observaciones').value        = observaciones;                      
          document.getElementById('addfam').style.display       = "none";
          document.getElementById('updfam').style.display       = "block";

          var txtocupation = document.getElementById('ocupaid').options[document.getElementById('ocupaid').selectedIndex].text;        
          document.getElementById('txtocupacion').value = txtocupation; 
    }

   
 
  </script> 
<body onload="valida_beneficiario()">
<form method="post" class="form-control"   >
<div id='div_wait'></div><div id="getid"></div> 
<section style="text-align: center; background-color:#ededed; padding-top:2px; padding-bottom:-2%;">
  <h4>FICHA FAMILIAR DE EMPLEADOS </h4> <br></section>
<section id="no_print">
    <a style = "width:100%;" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse2" href="" aria-expanded="false" aria-controls="collapse"> AGREGAR NUEVO RESPONSABLE EN CASO DE FALLECIMIENTO <i class="far fa-hand-point-down fa-lg"></i></a>
    <div class="collapse2" id="collapse1" align="center">
               <div class="card">                      
                <div class="card-body"  align="center">
                  <div class="row" style="padding-top: 8px; ">
                      <div class="col-sm-4">
                            <div>                    
                               <input type="text" id="txtid" name="txtid"  required="true">
                                          <span class="highlight"></span>
                                          <span class="bar"></span>
                                          <label>Identidad </label>
                            </div>';
                            
                      </div>
                      <div class="col-sm-2" align="left">
                           <!-- button class="btn btn-info active btn-md" type="submit" name="consulta_id" value="consulta"   > <i class="fas fa-search" style="font-size:15px; margin-left: 0px;"></i></button --> 
                            <button class="btn btn-outline-secondary btn-sm"  name="consulta_id" onclick="GetIdRNP()" type="button">Consultar  <i class="fas fa-search" style="font-size:15px;"></i></button>
                      </div>
                      <div class="col-sm-6">                        
                      </div>
                  </div>
                  <div class="row" >                             
                                <div class="col-sm-3">
                                   <div class="group input-group" style="margin-top:6%;">   
                                         <input type="text" id="namefam" name="namefam"  onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' required="true" >
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label>Nombre Completo </label>
                                    </div>  
                                  </div>
                                   <div class="col-sm-3">
                                     <div class="group input-group" style="margin-top:6%;">      
                                               <input type="date" id="fechanac" name="fechanac" style="width:95%" required="true">
                                               <span class="highlight"></span>
                                               <span class="bar"></span>
                                               <label>Fecha de Nacimiento </label>
                                      </div>
                                   </div> 

                                   <div class="col-sm-6">
                                     <div class="group input-group" style="margin-top:6%;">      
                                               
                                               <label>Observacionesy detalles: </label>
                                               <textarea class="form-control" rows="5" id="observaciones" name="observaciones"></textarea>
                                      </div>
                                   </div> 
                                   
                                                                  
                  </div>  
                  <div class="row" >
                       <div class="col-sm-3">
                                       <div class="group input-group" style="margin-top:6%;">   
                                              <textarea class="form-control"  id="domiciliofam" name="domiciliofam" rows="2" required="true" ></textarea>
                                              <span class="highlight"></span>
                                              <span class="bar"></span>
                                              <label>Domicilio </label>
                                      </div>  
                      </div>
                      <div class="col-sm-3">
                          <div class="group input-group" style="margin-top:2%; width:95%">            
                                              <input type="text" id="celularfam" name="celularfam"  style="width:95%" required="true"  maxlength="20" >
                                              <span class="highlight"></span>
                                              <span class="bar"></span>
                                              <label>TEL | CEL </label>
                          </div>
                      </div>
                      <div class="col-sm-3">
                        <div class="group input-group" style="margin-top:6%; width:95%">      
                          <select class="form-control"  onchange="GetOcupacion(this.options[this.selectedIndex].text)"  id="ocupaid"  name='ocupaid' required="true" style="margin-top:1%; width: 100%">     
                            <option value='' > Seleccione ....</option>
                               <?php 
                               $query_ocup=mysqli_query($conn, 'SELECT * FROM rr_hh_ocupacion_general');
                               while ($row_ocups= mysqli_fetch_array($query_ocup)) {
                               $idocup= $row_ocups['id'];
                               $descocup= $row_ocups['descripcion'];
                               echo "<option value=".$idocup.">".$descocup."</option>";
                               }
                               ?>                                 
                          </select>
                          <span class="highlight"></span>
                          <span class="bar"></span>
                          <label>Ocupación </label>
                          <input type="hidden" name="txtocupacion" id="txtocupacion">
                        </div>
                      </div>  
                      <div class="col-sm-3">
                                <div class="group input-group" style="margin-top:6%;">   
                                    <textarea class="form-control" rows="2" name="trabajofam" id="trabajofam" required="true" ></textarea>
                                      <span class="highlight"></span>
                                      <span class="bar"></span>
                                      <label>Trabajo | Estudio </label>
                                </div>  
                      </div> 
                  </div> 
                  <div class="row">
                       <div class="col-sm-3"></div>
                       <div class="col-sm-6" align="center">                                 
                                  <button class="btn btn-success btn-lg" type="submit" name='addfam' id="addfam"> Agregar Responsable en Caso de Fallecimiento</button>
                                  <button class="btn btn-primary btn-lg" type="submit" name='updfam' id="updfam" style="display:none;"> Actualizar Responsable en Caso de Fallecimiento</button>
                      </div>
                      <div class="col-sm-3"></div>
                  </div>                         
              </div>
            </div>
 </section><hr>

<section id="no_print"> 
    <div class="container-fluid" style="width: 100%">
      <div class="row">
        <div class="col-sm-12" align="left"> </div>
      </div>
        <div class="row">
          <div class="col-sm-12"><br>   
                  <div class="table-responsive">
                    <table id="table_id" class="table table-bordered table-sm table-hove w-auto"   cellspacing="0" style="width:100%;   font-size:13px;">
                       <thead><tr><th></th>     
                                  <th>Id</th>
                                  <th>Name</th>
                                  <th>Edad</th> 
                                  <th>Tel.</th>                                  
                                  <th>Domicilio</th> 
                                  <th>Trabaja | Estudia</th>
                                  <th>Observaciones</th>
                                  <th >Fecha de creación</th></tr></thead><tbody>
                            <?php 
                            $denti_query     = str_replace( '-', '', $identidad_persona);                           
                            $query_familiares= mysqli_query($conn, "SELECT a.id, a.identidad_responsable,  a.nombre_completo, a.fecha_nacimiento, a.celular, a.domicilio, a.ocupaciondesc , ocupacionid, lugar_ocupacion , datecreate , a.observaciones_detalles FROM rr_hh_responsable_muerte a WHERE identidad_empleado= '$denti_query' ORDER BY a.id ASC;");

                              if (mysqli_num_rows($query_familiares)>0)
                              {
                                  $cont=1; $fila=0;
                                  while ($_row_familiares= mysqli_fetch_array($query_familiares)) 
                                  {
                                    $id                     =   $_row_familiares['id'];
                                    $identifam              =   $_row_familiares['identidad_responsable'];                                    
                                    $nombre_completo_tbl    =   $_row_familiares['nombre_completo'];
                                    $fecha_nacimiento_tbl   =   $_row_familiares['fecha_nacimiento'];
                                    $cel_tbl                =   $_row_familiares['celular'];
                                    $domicilio_tbl          =   $_row_familiares['domicilio'];
                                    $trabajo_tbl            =   $_row_familiares['lugar_ocupacion'];
                                    $idocupacion            =   $_row_familiares['ocupacionid'];
                                    $ocupacion              =   $_row_familiares['ocupacion'];
                                    $fecha_creacion         =   $_row_familiares['datecreate'];
                                    $observaciones_detalles =   $_row_familiares['observaciones_detalles'];

                                    $fecha_calculo_edad = new Datetime($fecha_nacimiento_tbl);
                                    $hoy =  new Datetime();
                                    $edad_to_print   = $hoy->diff($fecha_calculo_edad);
                                   
                                    $identifamparametro= strval($identifam);
                                    $idocupacion= strval($idocupacion);
                            ?>
                                    <tr onclick = "cargar_datos('<?php echo $denti_query; ?>','<?php echo $identifam; ?>','<?php echo $nombre_completo_tbl ?>','<?php echo $fecha_nacimiento_tbl; ?>', '<?php echo $cel_tbl; ?> ' ,'<?php echo $domicilio_tbl; ?> ' ,'<?php echo $idocupacion; ?> ','<?php echo $trabajo_tbl; ?>' , '<?php echo $observaciones_detalles; ?> ' )">
                            <?php                                  
                                          echo "<td>".$cont."</td> 
                                                 <td>".$identifam."</td>                                                 
                                                 <td>".$nombre_completo_tbl."</td>
                                                 <td>".$edad_to_print->y ."</td>          
                                                 <td>".$cel_tbl."</td>
                                                 <td>".$domicilio_tbl."</td> 
                                                 <td>".$trabajo_tbl."</td> 
                                                 <td>".$observaciones_detalles."</td>     
                                                 <td>".$fecha_creacion."</td></tr> ";
                                    $cont++;   $fila++; 
                                }
                              } else {   echo mysqli_error($conn);  }     
                            ?>
                      </tbody>                       
                 </table>
                 </div>   
        </div><hr>
      </div>       
    </div>    
</section>
<?php 
 if ($_SERVER["REQUEST_METHOD"] === "POST") 
 {  
    if (isset($_POST['addfam'])) 
    {        
        $_ins_identidad_persona   = str_replace( '-', '',  $_GET['id']);
        $_ins_identidad_familiar  = str_replace( '-', '',  $_POST['txtid']);

        $_ins_fecha_nacimiento    =   $_POST['fechanac'];
        $_ins_fecha_nacimiento    =   date("Y-m-d", strtotime( $_ins_fecha_nacimiento)); 
        $_ins_nombre_completo     =   $_POST['namefam'];         
        $_ins_domicilio           =   $_POST['domiciliofam'];
        $_ins_celular             =   $_POST['celularfam'];
        $_ins_ocupaciondesc       =   $_POST['trabajofam'];
        $_ins_ocupacionid         =   $_POST['ocupaid'];
        $_ins_ocupaciontxt        =   $_POST['txtocupacion'];
        $_ins_observaciones       =   $_POST['observaciones'];


        $query_txt_insert_new="INSERT INTO rr_hh_responsable_muerte (identidad_empleado, identidad_responsable, fecha_nacimiento, nombre_completo,  domicilio,  lugar_ocupacion, ocupacionid, ocupacion , observaciones_detalles , celular) 
                              VALUES('$_ins_identidad_persona', '$_ins_identidad_familiar', '$_ins_fecha_nacimiento', '$_ins_nombre_completo', '$_ins_domicilio',  '$_ins_ocupaciondesc', '$_ins_ocupacionid', '$_ins_ocupaciontxt', '$_ins_observaciones' , '$_ins_celular');";
     
        $query_insert_fam=mysqli_query($conn, $query_txt_insert_new);

        if ($query_insert_fam) {          
         // echo "<div class='alert alert-success'> <strong> Registrado con Correctamente </strong></div>";          
          ?>
            <script type="text/javascript">
              var identity_empleado= "<?php echo $identidad_persona ?>";
                    swal({
                      title: "",
                        text: "Responsable agregado Exitosamente!.",
                        type: "success" 
                      })  
                      .then(function(result){
                          window.location.replace('_rr_hh_add_responsable_fallecimiento.php?id='+identity_empleado);
                        });
                  </script>  
          <?php 
        }
        else {
          echo "<div class='alert alert-danger'> <strong> Ha Ocurrido un error ".mysqli_error($conn)." </strong></div>";          
        }

        unset($query_insert_fam);
        $_SESSION['posting']=true;
    }
    else if (isset($_POST['updfam'])) 
    {            
        $_ins_identidad_persona   = str_replace( '-', '',  $_GET['id']);
        $_ins_identidad_familiar  = str_replace( '-', '',  $_POST['txtid']);

        $_ins_fecha_nacimiento    =   $_POST['fechanac'];
        $_ins_fecha_nacimiento    =   date("Y-m-d", strtotime( $_ins_fecha_nacimiento)); 
        $_ins_nombre_completo     =   $_POST['namefam'];         
        $_ins_domicilio           =   $_POST['domiciliofam'];
        $_ins_celular             =   $_POST['celularfam'];
        $_ins_ocupaciondesc       =   $_POST['trabajofam'];
        $_ins_ocupacionid         =   $_POST['ocupaid'];
        $_ins_ocupaciontxt        =   $_POST['txtocupacion'];
        $_ins_observaciones       =   $_POST['observaciones'];

        $query_txt_update= "UPDATE rr_hh_responsable_muerte 
                            SET   domicilio = '$_ins_domicilio', celular = '$_ins_celular', observaciones_detalles='$_ins_observaciones',  ocupaciondesc = '$_ins_ocupaciondesc',  lugar_ocupacion = '$_ins_ocupaciondesc', ocupacionid = $_ins_ocupacionid , ocupacion = '$_ins_ocupaciontxt'
                            WHERE identidad_responsable='$_ins_identidad_familiar'";

        $query_update_fam=mysqli_query($conn, $query_txt_update);

        if ($query_update_fam) {          
          echo "<div class='alert alert-success'> <strong> Actualizado con Correctamente </strong></div>";    

             ?>
            <script type="text/javascript">
              var identity_empleado= "<?php echo $identidad_persona ?>";
                    swal({
                      title: "",
                        text: "Responsable Actualizado Exitosamente!.",
                        type: "success" 
                      })  
                      .then(function(result){
                          window.location.replace('_rr_hh_add_responsable_fallecimiento.php?id='+identity_empleado);
                        });
                  </script> 
            <?php       
        }
        else
        {
          echo "<div class='alert alert-danger'> <strong> Ha Ocurrido un error ".mysqli_error($conn)." </strong></div>";          
        }
        unset($query_update_fam);
        $_SESSION['posting']=true;
    }
 }     
 ?>
 <section><hr>
    <!-- div id="no_print" align="center"><button class="btn btn-danger btn-md"> Imprimir Ficha Familiar</button></div --> 
 </section>
 </form>

 <script type="text/javascript">
  $(".div_wait").fadeOut("fast");  


 



 </script>
</body>
 