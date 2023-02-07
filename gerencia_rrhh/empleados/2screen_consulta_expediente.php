<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['usuario'] ;
    function diferenciaDias($inicio, $fin)
    {
        $inicio = strtotime($inicio);
        $fin = strtotime($fin);
        $dif = $fin - $inicio;
        $diasFalt = (( ( $dif / 60 ) / 60 ) / 24);
        return ceil($diasFalt);
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
         /*   label                
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
            }*/

            /* active state */
            input:focus ~ label, input:valid ~ label        
            {
              top:-20px;
              font-size:14px;
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
    td.details-control {
  background: url('https://datatables.net/examples/resources/details_open.png') no-repeat center center;
  cursor: pointer;
}
tr.shown td.details-control {
  background: url('https://datatables.net/examples/resources/details_close.png') no-repeat center center;
}

</style>



 <script type="text/javascript">
    $(".div_wait").fadeIn("fast");  
    // This function is for displaying data from HTML "data-child-value" tag in the Child Row.
function format(value) {
        var result=value.split('|');
         var comment_error=result[0];
         var comment_cierre=result[1];

      return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;"><tr><td>Dirección Domicilio : </td><td>' + comment_error + '</td></tr><tr><td> Direccion de Trabajo : </td><td class="w-30">' + comment_cierre + '</td></tr></table>';
  }

// Initialization of dataTable and settings.
  $(document).ready(function () {
    $("#txtid").mask("9999-9999-99999", { placeholder: "____-____-____ " });

      var dataTable = $('#example2').DataTable({
       bLengthChange: true,
       "pageLength": 10 
    });

// This function is for handling Child Rows.
    $('#example2').on('click', 'td.details-control', function () {
          var tr = $(this).closest('tr');
          var row = dataTable.row(tr);

          if (row.child.isShown()) {
              // This row is already open - close it
              row.child.hide();
              tr.removeClass('shown');
          } else {
              // Open this row       
             row.child(format(tr.data('child-value'))).show();
              tr.addClass('shown');
          }
    });

// Checkbox filter function below is for filtering hidden column 6 to show Free Handsets only.

    
//Alternative pagination load more.
$('#load-more').on( 'click', function () {
    var i = dataTable.page.len() + 5; // Change value for pagination.
    dataTable.page.len( i ).draw();
} );

//Alternative pagination show less - 5. (Not in use).
$('#button-less').on( 'click', function () {
        var VisibleRows = dataTable.page.len();
    var i = VisibleRows - 5; // Change value for pagination.
    if (VisibleRows > 8) {
    dataTable.page.len( i ).draw();
    }
} );

$("#example2").DataTable().rows().every( function () {
    var tr = $(this.node());
    this.child(format(tr.data('child-value'))).close();
    tr.addClass('shown');
});

});
 
</script>
 
      

<form method="post">
<div id='div_wait'></div>
<div id="no_print_fr" class="page">
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><p><h3>CONSULTA DE EXPEDIENTES Y ALTAS DE PERSONAL -> PANI </h3></p><br></section>
 
<section>
<a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS DE BUSQUEDA  <i class="far fa-hand-point-down fa-lg"></i></a>
    <div class="collapse " id="collapse1" align="center">
               <div class="card">                                        
                    <div class="row">
                      <div class="col-sm-4"  style="margin-left:5%">
                       <div class="row">
                        <div class="col-sm-8">
                              <div >
                                        <input type="text" id="txtid" name="txtid"    required="true" style="width: 98%">
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label>Busque por identidad Identidad </label>
                               </div>
                        </div>
                        <div class="col-sm-4">
                          <button class="btn btn-success active btn-md" type="submit" name="consulta_id"  style="margin-left: 10px; margin-top: 10%;">CONSULTAR <i class="fas fa-search" style="font-size:15px; margin-left: 10px;"></i></button> 
                        </div>
                       </div> 
                      </div>
                     
                    </div>                
                </div>
              </div>
    </div> 
 </section>
<section>

</div>
 
<section id="no_print">

 <?php 
 if ($_SERVER["REQUEST_METHOD"] == "POST") 
 {
    if (isset($_POST['txtid'])) {
      $identidad     = $_POST['txtid'];
      $identidad_txt = $identidad;
      $identidad     = str_replace( '-', '', $identidad);
      $area_empleado = '';

          $queryidentidad="SELECT * FROM censo_2017 WHERE identidad='$identidad'";          
          $query_identidad=mysqli_query($conn, $queryidentidad);

          if (mysqli_num_rows($query_identidad)>0) 
          {
            while ($row_identidad=mysqli_fetch_array($query_identidad)) 
            {
                $identidad_rnp         =  $row_identidad['identidad']; 
                $nombre_completo_rnp   =  $row_identidad['nombre_completo'];
                $fecha_nacimiento_rnp  =  $row_identidad['fecha_nacimiento_txt'];                
            }

            $query_identidad_validacion = "SELECT * FROM rr_hh_empleados WHERE identidad='$identidad';";             
            $query_validacion_empleado_pani= mysqli_query($conn, $query_identidad_validacion);                

                if (mysqli_num_rows($query_validacion_empleado_pani)>0) 
                {
                    
                    while ($row_info_existe=mysqli_fetch_array($query_validacion_empleado_pani)) 
                    {                        
                        $identidad_existe        = $row_info_existe['identidad'];
                        $nombre_completo_existe  = $row_info_existe['nombre_completo'];
                        $fecha_nacimiento_existe = $row_info_existe['fecha_nacimiento'];
                        $fecha_ingreso_existe    = $row_info_existe['fecha_ingreso'];
                       
                        $cumpleanos = new DateTime($fecha_nacimiento_existe);
                        $hoy        = new DateTime();
                        $annos      = $hoy->diff($cumpleanos);     
                    }

                    $query_sql_area = "SELECT b.descripcion area FROM organizacional_usuarios_gerencias a, organizacional_gerencias b WHERE a.gerenciaid=b.id and a.usuarioid='$identidad_existe'";
                  
                    $query_area = mysqli_query($conn, $query_sql_area);
                    if (mysqli_num_rows($query_area)>0) {  
                      $ob_area = mysqli_fetch_object($query_area);
                      $area_empleado = $ob_area->area;                     
                    } else {
                      $area_empleado = 'No ha sido Asignado';
                    }


                     echo '<div class="card" >
                              <div class="card-header bg-success text-white">Empleado ya Registrado!</div>
                                  <div class="card-body">
                                      <p class="mb-0">Identidad :        <strong>'.$identidad_existe.'.</strong></p>
                                      <p class="mb-0">Nombre    :        <strong>'.$nombre_completo_existe.'.</strong></p>                          
                                      <p class="mb-0">Edad :             <strong>'.$annos->y.'</strong></p>
                                      <p class="mb-0">Fecha de Ingreso : <strong>'.$fecha_ingreso_existe.'</strong></p>
                                      <p class="mb-0">Area :             <strong>'.$area_empleado.'.</strong></p>';
                                     
                      
                      $query_familiares=mysqli_query($conn, "SELECT identidad_familiar,  nombre_completo, fecha_nacimiento, sexo,   b.descripcion parentesco_txt,  celular, ocupacion,  domicilio, lugar_ocupacion  FROM rr_hh_empleados_familias a, rr_hh_parentescos b WHERE a.parentesco=b.id and a.identidad_empleado='$identidad' ;");
                       if (mysqli_num_rows($query_familiares)>0) 
                       {
                        echo '<p class="mb-0" align="center">    <strong> Familiares Registrados </strong></p><hr>';;
                        echo '  <div class="table-responsive">   
                                        <table id="example2" class="table table-hover table-bordered table-sm dt-responsive nowrap" cellspacing="0" width="100%">
                                           <thead><tr><th>+</th>
                                                     <th>Identidad</th>
                                                     <th>Nombre</th>
                                                     <th>Fecha de Nacimiento</th>
                                                     <th>Edad</th>
                                                     <th>Sexo</th>
                                                     <th>Parentesco</th>
                                                     <th>Celular</th>     
                                                     <th>Ocupacion</th></tr></thead><tbody>';

                         while ($row_familiares = mysqli_fetch_array($query_familiares)) 
                         {
                              $_identida_familiar     =  $row_familiares['identidad_familiar'];
                              $_nombre_familiar       =  $row_familiares['nombre_completo'];
                              $_fecha_nacimiento      =  $row_familiares['fecha_nacimiento'];
                              $_sexo                  =  $row_familiares['sexo'];
                              $_parentesco            =  $row_familiares['parentesco_txt'];
                              $_celular               =  $row_familiares['celular'];
                              $_ocupacion             =  $row_familiares['ocupacion'];
                              $_direccion_domicilio   =  $row_familiares['domicilio'];
                              $_direccion_trabajo     =  $row_familiares['lugar_ocupacion'];

                              
                                echo '    <tr data-child-value="'.$_direccion_domicilio .'|'.$_direccion_trabajo.'">   
                                                    <td class="details-control"></td>   
                                                    <td>'.$_identida_familiar .'</td>
                                                    <td>'.$_nombre_familiar .'</td>
                                                    <td>'.$_fecha_nacimiento .'</td>
                                                    <td>'.$_sexo .'</td>
                                                    <td>'.$_sexo .'</td>
                                                    <td>'.$_parentesco .'</td>
                                                    <td>'.$_celular .'</td>
                                                    <td>'.$_ocupacion .'</td></tr>';  
                                         
                         }
                          echo '</tbody><tfoot><tr id="load-more"><td colspan="9"><center>Cargar Mas</center></td></tr></tfoot></table></div>';                             
                       }   

                                     
//                                     <a href="http://192.168.15.10/SGRH-PANI/_rr_hh_file_manager.php?id='.$identidad_existe.'" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true" target="blank" >Expediente | Archivo</a>
                        
                              echo ' <p class="mb-0">
                                     <a href="_rr_hh_add_empleado_familia.php?id='.$identidad_existe.'" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true" target="blank" >Agregar Ficha Familiar</a>
                                     <a href="_rr_hh_update_empleado.php?id='.$identidad_existe.'" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true" target="blank" >Actualizar Información</a>                             
                                     <a href="_rr_hh_update_contratacion.php?id='.$identidad_existe.'" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true" target="blank" >Gestionar Contratación y Salario</a>                             
                                </p>                         
                          </div>
                      </div>';
              }
              else
              {
                  $fecha_nacimiento_rnp =date("d-m-Y", strtotime( $fecha_nacimiento_rnp)); 
                  $cumpleanos = new DateTime($fecha_nacimiento_rnp);
                  $hoy        = new DateTime();
                  $annos      = $hoy->diff($cumpleanos);                 

                  echo '<div class="alert alert-info" role="alert">
                          <h4 class="alert-heading">No es Empleado!</h4>
                          <p>Se encuentra en el RNP con la siguiente información :</p>
                          <hr>
                          <p class="mb-0">Identidad : <strong>'.$identidad_rnp.'.</strong></p>
                          <p class="mb-0">Nombre    : <strong>'.$nombre_completo_rnp.'.</strong></p>
                          <p class="mb-0">Fecha de Nacimiento : <strong>'.$fecha_nacimiento_rnp.'.</strong></p>
                          <p class="mb-0">Edad : <strong>'.$annos->y.'.</strong></p>
                          <hr>
                          <p class="mb-0">
                             <a href="_rr_hh_add_empleado.php?id='.$identidad_rnp.'" class="btn btn-success btn-lg active" role="button" aria-pressed="true"  >Dar Alta</a>                             
                          </p>
                          </div>';
                }
          }
          else
          {
             //echo "Esta persona no existe en el censo de 2017";
              $identidad     = $_POST['txtid'];
              $identidad     = str_replace( '-', '', $identidad);
              ?>
                <script type="text/javascript">
                    var identidad_nueva = '<?php echo $identidad ; ?>'
                    swal({
                          title: 'No Registrado!',
                          text: '¿Desea registrar en la Base local del RNP?',
                          icon: 'warning',
                          buttons: {
                              cancel: true,
                              confirm: true
                          }
                      }).then(function(value) {
                          if (value) {
                              //  Either true or null
                              window.open("_rr_hh_add_censo.php?id="+identidad_nueva, '_blank');
                          }
                      });

             
                </script>
              <?php 
          }
    }
 }
 ?>
 
 </section>

<section id="no_print"> 
</section>


 </form>
 <script type="text/javascript">
$(".div_wait").fadeOut("fast");  
 </script>