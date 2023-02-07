<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['usuario'] ;
$usuario_name=$_SESSION['nombre_usuario'];
    function diferenciaDias($inicio, $fin)
    {
        $inicio = strtotime($inicio);
        $fin = strtotime($fin);
        $dif = $fin - $inicio;
        $diasFalt = (( ( $dif / 60 ) / 60 ) / 24);
        return ceil($diasFalt);
    }

$conn_ingress = mysqli_connect('192.168.15.82:3306', 'ingress' , 'ingress', 'ingress') or die('No se pudo conectar: ' . mysqli_error());

$logaccion= "Ingreso al reporte de Compensatorios";
$txt_logquery = "INSERT INTO rr_hh_bitacora (usuario_creacion, descripcion_accion) values ('$usuario_name', '$logaccion')";
$loquery=mysqli_query($conn, $txt_logquery );



 

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
       /*     label                
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


<form method="post">
<div id='div_wait'></div>
<div id="no_print_fr" class="page">
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"id="no_print" ><h3>CONSULTA DE COMPENSATORIOS | PANI </h3> <br></section>
 
<section id="no_print">
<a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS DE BUSQUEDA  <i class="far fa-hand-point-down fa-lg"></i></a>
 
 </section>


</div>
 


<section id="no_print"> 
    <div class="collapse " id="collapse1" align="center">
      <div class="card">
        <div class="card card-body" id="non-printable" >
          <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
            <div class = "input-group-prepend"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i> &nbsp;  Fecha Inicial: </span></div>
            <input type='date' id ="fecha_i"   name = "fecha_inicial" class="form-control" id ="dt1">
            <div class = "input-group-prepend" style="margin-left: 10px;"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i>  &nbsp;   Fecha Final: </span></div>
            <input type='date' id ="fecha_f"   name = "fecha_final" class="form-control" id ="dt2">           
            <button type="submit" name="seleccionar" style="margin-left: 10px;" class="btn btn-primary" value = "Seleccionar">  Seleccionar &nbsp;<i class="fas fa-search fa-lg"></i></button>
          </div>
        </div>
      </div>
    </div> 
</section> <br>


<section id="">

 

 <?php if ($_SERVER["REQUEST_METHOD"] == "POST") : 

     $_fecha_inicial=$_POST['fecha_inicial'];  $_fecha_inicial = date("Y-m-d", strtotime($_fecha_inicial)); 
     $_fecha_final=$_POST['fecha_final'];      $_fecha_final   = date("Y-m-d", strtotime($_fecha_final)); 
      

     $query_asistencia_txt ="SELECT  a.userid, c.gName area, concat( b.name ,' ', b.lastname) nombre_completo,  time(min(a.checktime)) hora_entrada, time(max(a.checktime)) hora_salida  , TIMEDIFF( time(max(a.checktime)), time(min(a.checktime)) ) as tiempo_trabajado , date(checktime) fecha_marcacion
                             FROM auditdata a INNER JOIN user b ON a.userid=b.userid INNER JOIN  user_group c ON b.user_group=c.id 
                             WHERE date(a.checktime) between '$_fecha_inicial' and '$_fecha_final' GROUP BY a.userid , date(a.checktime) order by userid asc;";

     $query_asistencia=mysqli_query($conn_ingress , $query_asistencia_txt);

  ?>
  <section>
    <h3>Gerencia de Recursos Humanos</h3>
    <h4> Inspectoria del Tiempo </h4>
    <p>Listado General de marcacion Biometrica | Consulta de Tiempo Compensatorio</p>
  </section>
  <div class="container-fluid"><br>
    <table  class="table table-hover table-sm table-bordered" id="table_id1">
                  <thead>  
                      <tr align="center">
                        <th></th>
                        <th>Id</th>
                        <th>Area</th>
                        <th>Nombre Completo</th>
                        <th>Fecha Marcacion</th>
                        <th>Hora Entrada</th>
                        <th>Hora Salida</th>                                               
                        <th>Horas Trabajado</th>   
                        <th id="no_print"></th>                                               
                      </tr>                 
                  </thead>
                  <tbody> 
                    <?php   
                    if ($query_asistencia_txt) 
                    { 
                      $contador=1;
                      while ($row_asistencia= mysqli_fetch_array($query_asistencia)) 
                      {
                        $id               = $row_asistencia['userid'];
                        $area             = $row_asistencia['area'];
                        $nombre_completo  = utf8_encode($row_asistencia['nombre_completo']);
                        $hora_entrada     = $row_asistencia['hora_entrada']; 
                        $hora_salida      = $row_asistencia['hora_salida']; 
                        $tiempo_trabajado = $row_asistencia['tiempo_trabajado']; 
                        $fecha_marcacion  = $row_asistencia['fecha_marcacion']; 

                        echo "<tr><td>".$contador."</td>
                                  <td>".$id."</td>
                                  <td>".$area."</td>
                                  <td>".$nombre_completo."</td>
                                  <td>".$fecha_marcacion."</td>
                                  <td>".$hora_entrada."</td>
                                  <td>".$hora_salida."</td>
                                  <td>".$tiempo_trabajado."</td>
                                  <td id='no_print'>
                                  <a type='button' class='btn btn-danger' role='button' target='_blank' href='_rr_hh_solicitud_compensatorio.php?fechai=".$_fecha_inicial."&fechaf=".$_fecha_inicial."&usu=".$id."'  >  Jornada Única</a>";

                                  if ($_fecha_inicial<>$_fecha_final) 
                                  {
                                    echo "<a type='button' class='btn btn-info' role='button' target='_blank' href='_rr_hh_solicitud_compensatorio_extendido.php?fechai=".$_fecha_inicial."&fechaf=".$_fecha_final."&usu=".$id."'>  Jornada Múltiple</a>";
                                  }                                 
                                  
                                  echo "</td>                                       
                              </tr>";
                        $contador++;
                      }              
                    }

                     ?>
                  </tbody> 
     </table><br>
     <div align="center" id="no_print">
      <button type="button" class="btn btn-success"  onclick="window.print()" > Imprimir Reporte General</button>   
     </div>
    
 
   </div>
 <?php endif ?>
 
 </section>


 </form>
 <script type="text/javascript">
 	$(".div_wait").fadeOut("fast");  

/*
  $(document).ready(function() {
    var table = $('#table_id1').DataTable( { 
         "pageLength" : "5",
         "language": {
        "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
        }
    }
    } );
*/k
     
 </script>
