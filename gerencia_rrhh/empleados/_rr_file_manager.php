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
    $("#celularfam").mask("9999-9999", { placeholder: "____-____" });
	}); 

  $(".div_wait").fadeIn("fast"); 


    function GetIdRNP()
    {
                   var id=document.getElementById("txtid").value;
                   $(".div_wait").fadeIn("fast");
                   var urr = './_rr_hh_GetIdRNP.php?id='+id+'&valida='+Math.random();
                   $("#getid").load(urr);
                   document.getElementById('updfam').style.display = "none";
    }


    function cargar_datos(idn, idenfam, nom, parent, fech, sex,  celtel, dom, trab, )    
    {      
          //alert(idn + "|" + idenfam   + "|" + nom + "|" +  parent  + "|" +  fech  + "|" + sex  + "|" + celtel + "|" + dom  + "|" +  trab);     
          document.getElementById('txtid').value =idenfam;
          document.getElementById('namefam').value =nom;
          document.getElementById('fechanac').value =fech;
          document.getElementById('sexofam').value =sex;
          document.getElementById('parentescofami').value =parent;
          document.getElementById('domiciliofam').value =dom;
          document.getElementById('trabajofam').value =trab;
          document.getElementById('celularfam').value =celtel;
          document.getElementById('addfam').style.display = "none";
          document.getElementById('updfam').style.display = "block";
    }


 
	</script> 

<form method="post" class="form-control" >
<div id='div_wait'></div><div id="getid"></div> 
<section style="text-align: center; background-color:#ededed; padding-top:2px; padding-bottom:-2%;">
  <h4>FICHA FAMILIAR DE EMPLEADOS DEL PANI </h4> <br></section>
<section id="no_print">
    <a style = "width:100%; padding-top:-10px;" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse2" href="" aria-expanded="false" aria-controls="collapse"> AGREGAR NUEVO FAMILIAR <i class="far fa-hand-point-down fa-lg"></i></a>
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
                                   <div class="col-sm-3">
                                       <div class="group input-group" style="margin-top:6%; width:95%">
                                           <select class="form-control" name='sexofam' id='sexofam' required="true" style="margin-top:1%;">
                                              <option value=""> Seleccione ....</option>
                                              <option value="H" > HOMBRE </option>
                                              <option value="F"> MUJER </option>
                                           </select>
                                           <span class="highlight"></span>
                                           <span class="bar"></span>
                                           <label>SEXO </label>
                                       </div>
                                    </div>
                                    <div class="col-sm-3">                        
                                                <div class="group input-group" style="margin-top:6%; width:95%">      
                                                                   <select class="form-control" name='parentescofami' id="parentescofami"  required="true" style="margin-top:1%;">
                                                                          <option value='' > Seleccione ....</option>
                                                                          <?php 
                                                                          $query_parent=mysqli_query($conn, 'SELECT * FROM `rr_hh_parentescos`');

                                                                          while ($row_parents= mysqli_fetch_array($query_parent)) {
                                                                            $idparent= $row_parents['id'];
                                                                            $descparent= $row_parents['descripcion'];
                                                                            echo "<option value='".$idparent."'> ".$descparent."</option>";
                                                                          }
                                                                           ?>
                                                                   </select>
                                                                    <span class="highlight"></span>
                                                                    <span class="bar"></span>
                                                                    <label>Paréntesco </label>
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
                                      <div class="group input-group" style="margin-top:6%;">   
                                              <textarea class="form-control" rows="2" name="trabajofam" id="trabajofam" required="true" ></textarea>
                                              <span class="highlight"></span>
                                              <span class="bar"></span>
                                              <label>Trabajo | Estudio </label>
                                      </div>  

                      </div>
                      <div class="col-sm-3">
                          <div class="group input-group" style="margin-top:2%; width:95%">            
                                              <input type="text" id="celularfam" name="celularfam"  style="width:95%" required="true"  maxlength="8" >                                   
                                              <span class="highlight"></span>
                                              <span class="bar"></span>
                                              <label>TEL | CEL </label>
                          </div>
                      </div>
                      <div class="col-sm-3">  
                                  <div class="group input-group" style="margin-top:10%; width:95%">
                                  <button class="btn btn-success btn-sm" type="submit" name='addfam' id="addfam" > Agregar Ficha Familiar</button>
                                  <button class="btn btn-primary btn-sm" type="submit" name='updfam' id="updfam" style="display:none;"> Actualizar Ficha Familiar</button>
                                </div>
                      </div>
                  </div>                          
              </div>
            </div>
 </section><hr>

<section id="no_print"> 
    <div class="container-fluid" style="width: 100%">
        <div class="row">
          <div class="col-sm-12"><br>   
                  <div class="table-responsive">
                    <table id="table_id" class="table table-bordered table-sm table-hove w-auto"   cellspacing="0" style="width:100%;   font-size:13px;">
                       <thead><tr><th></th>                                  
                                  <th>Parentesco</th>
                                  <th>Id</th>
                                  <th>Name</th>
                                  <th>Fecha de Nacimiento</th>
                                  <th>Sexo</th>
                                  <th>Tel.</th>                                  
                                  <th>Domicilio</th>
                                  <th>Trabaja | Estudia</th>
                                  <th >Beneficiario</th>
                                  <th>Ṕorcentaje</th></tr>
                       </thead>
                       <tbody>
                            <?php 
                            $denti_query     = str_replace( '-', '', $identidad_persona);                           
                            $query_familiares= mysqli_query($conn, "SELECT a.id, a.identidad_familiar,  a.parentesco, b.descripcion parentescotxt, a.nombre_completo, a.fecha_nacimiento, a.celular, a.domicilio, a.ocupaciondesc , sexo, a.porcentaje_seguro FROM rr_hh_empleados_familias a, rr_hh_parentescos b WHERE a.parentesco=b.id and identidad_empleado= '$denti_query' ORDER BY a.id ASC;");

                              if (mysqli_num_rows($query_familiares)>0)
                              {
                                  $cont=1; $fila=0;
                                  while ($_row_familiares= mysqli_fetch_array($query_familiares)) 
                                  {
                                    $id                     =   $_row_familiares['id'];
                                    $identifam              =   $_row_familiares['identidad_familiar'];
                                    $parenteco_tbl_cod      =   $_row_familiares['parentesco'];
                                    $parenteco_tbl          =   $_row_familiares['parentescotxt'];
                                    $nombre_completo_tbl    =   $_row_familiares['nombre_completo'];
                                    $fecha_nacimiento_tbl   =   $_row_familiares['fecha_nacimiento'];
                                    $cel_tbl                =   $_row_familiares['celular'];
                                    $domicilio_tbl          =   $_row_familiares['domicilio'];
                                    $trabajo_tbl            =   $_row_familiares['ocupaciondesc'];
                                    $sexo_tbl               =   $_row_familiares['sexo'];
                                    $porcentaje_seguro      =   $_row_familiares['porcentaje_seguro'];

                                    $identifamparametro= strval($identifam) ;


                                    ?>
                                    <tr onclick = "cargar_datos('<?php echo $denti_query; ?>','<?php echo $identifam; ?>','<?php echo $nombre_completo_tbl ?>','<?php echo $parenteco_tbl_cod; ?>','<?php echo $fecha_nacimiento_tbl; ?>','<?php echo $sexo_tbl; ?>' ,'<?php echo $cel_tbl; ?> ' ,'<?php echo $domicilio_tbl; ?> ' ,'<?php echo $trabajo_tbl; ?> '  )">
                                    <?php                                  
                                          echo "<td>".$cont."</td>
                                                <td>".$parenteco_tbl."</td>
                                                 <td>".$identifam."</td>                                                 
                                                 <td>".$nombre_completo_tbl."</td>
                                                 <td>".$fecha_nacimiento_tbl."</td>                                              
                                                 <td>".$sexo_tbl."</td>                                              
                                                 <td>".$cel_tbl."</td>
                                                 <td>".$domicilio_tbl."</td>
                                                 <td>".$trabajo_tbl."</td> 
                                                 <td>
                                                    <div id='sendbenef".$fila."'>
                                                    <input type='number'  name='arraybenef[]' style='width:80%' min='0' max='100' required value='".$porcentaje_seguro."' >
                                                    <div>
                                                 </td>
                                                 <td>";
                                                 ?>
                                                    <button type='button' class='btn btn-primary' onclick="actualiza_beneficiario( '<?php echo $identifamparametro; ?>' , '<?php echo $fila ?>' )" > Actualizar</button>
                                                  <?php 
                                                 echo "</td></tr> ";
                                    $cont++;   $fila++; 
                                }
                              }
                              else{   echo mysqli_error($conn);  }     
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
        $_ins_sexo                =   $_POST['sexofam'];
        $_ins_parentesco          =   $_POST['parentescofami'];        
        $_ins_domicilio           =   $_POST['domiciliofam'];
        $_ins_celular             =   $_POST['celularfam'];
        $_ins_ocupaciondesc       =   $_POST['trabajofam'];

        $query_txt_insert_new="INSERT INTO rr_hh_empleados_familias (identidad_empleado, identidad_familiar, fecha_nacimiento, nombre_completo, parentesco,  domicilio, celular,  lugar_ocupacion) 
                                                              VALUES('$_ins_identidad_persona', '$_ins_identidad_familiar', '$_ins_fecha_nacimiento', '$_ins_nombre_completo',  '$_ins_parentesco',  '$_ins_domicilio', '$_ins_celular' ,  '$_ins_ocupaciondesc');";
     
        $query_insert_fam=mysqli_query($conn, $query_txt_insert_new);

        if ($query_insert_fam) {          
          echo "<div class='alert alert-success'> <strong> Registrado con Correctamente </strong></div>";          
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
        $_ins_sexo                =   $_POST['sexofam'];
        $_ins_parentesco          =   $_POST['parentescofami'];        
        $_ins_domicilio           =   $_POST['domiciliofam'];
        $_ins_celular             =   $_POST['celularfam'];
        $_ins_ocupaciondesc       =   $_POST['trabajofam'];

        $query_txt_update= "UPDATE rr_hh_empleados_familias 
                            SET parentesco = '$_ins_parentesco', sexo = '$_ins_sexo', domicilio = '$_ins_domicilio' ,  ocupaciondesc = '$_ins_ocupaciondesc', celular = '$_ins_celular' 
                            WHERE identidad_familiar='$_ins_identidad_familiar'";

        $query_update_fam=mysqli_query($conn, $query_txt_update);

        if ($query_update_fam) {          
          echo "<div class='alert alert-success'> <strong> Actualizado con Correctamente </strong></div>";          
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
    <div id="no_print" align="center"><button class="btn btn-danger btn-md"> Imprimir Ficha Familiar</button></div>
 </section>
 </form>

 <script type="text/javascript">
  $(".div_wait").fadeOut("fast");  

  function actualiza_beneficiario(idet, fila)
  {
         var vpercent    =  document.getElementsByName("arraybenef[]")[fila].value;      
         var count_array =  document.getElementsByName("arraybenef[]").length;  
         var j=0; 
         var sum_benef =0; 

         while (j < count_array) 
         {  
             var valor_benef=document.getElementsByName("arraybenef[]")[j].value;
             sum_benef= parseInt(sum_benef)+parseInt(valor_benef);
             j++;
         }

         if (sum_benef>=100) {
            swal("El Valor "+vpercent +" que pretende ingresar no es valido, sobrepasa lo permitido! ");
         }
         else{
                var getbenef = "./_rr_hh_add_beneficiario.php?id=" + idet+ "&porc="+ vpercent +"&valida="+Math.random(); 
                 $('#sendbenef'+fila).load(getbenef); 
                 alert(getbenef);
         }
          
  }
 </script>

 