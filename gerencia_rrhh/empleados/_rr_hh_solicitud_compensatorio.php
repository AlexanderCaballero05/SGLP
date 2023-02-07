<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['usuario'] ;
$fecha_asistenciai=$_GET['fechai'];
$fecha_asistenciaf=$_GET['fechaf'];
$usuario_asistencia=$_GET['usu'];

    $conn_ingress = mysqli_connect('192.168.15.4:3306', 'ingress' , 'ingress', 'ingress') or die('No se pudo conectar: ' . mysqli_error());

$query_datos_asistencia= mysqli_query($conn_ingress, "SELECT  a.userid, c.gName area, concat( b.name ,' ', b.lastname) nombre_completo,  time(min(a.checktime)) hora_entrada, time(max(a.checktime)) hora_salida, TIMEDIFF( time(max(a.checktime)), time(min(a.checktime)) ) as tiempo_trabajado 
FROM auditdata a INNER JOIN user b ON a.userid=b.userid INNER JOIN  user_group c ON b.user_group=c.id 
WHERE date(a.checktime) between '$fecha_asistenciai' and '$fecha_asistenciaf' AND a.userid='$usuario_asistencia';");

$obj_datos_asistencia= mysqli_fetch_object($query_datos_asistencia);
$area                 = $obj_datos_asistencia->area;
$name                 = $obj_datos_asistencia->nombre_completo;
$hora_entrada         = $obj_datos_asistencia->hora_entrada;
$hora_salida          = $obj_datos_asistencia->hora_salida;
$tiempo_trabajado     = $obj_datos_asistencia->tiempo_trabajado;




$fecha_sorteo= date('Y-m-d');

$v_fecha = explode("-", $fecha_sorteo);
$year    = $v_fecha[0];
$month   = $v_fecha[1];
$day     = $v_fecha[2];
$diadate = $day;

$date = $fecha_sorteo;
$nameOfDay = date('D', strtotime($date));

if ($nameOfDay == "Sun") {
$dia = "DOMINGO";
}elseif ($nameOfDay == "Mon") {
$dia = "LUNES";
}elseif ($nameOfDay == "Tue") {
$dia = "MARTES";
}elseif ($nameOfDay == "Wed") {
$dia = "MIERCOLES";
}elseif ($nameOfDay == "Thu") {
$dia = "JUEVES";
}elseif ($nameOfDay == "Fri") {
$dia = "VIERNES";
}elseif ($nameOfDay == "Sat") {
$dia = "SÁBADO";
}

$meses= array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO", "AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
$mes = $meses[$month - 1];
$ano = $year; 

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
<br>

<section id="no_print">
<a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS ADICIONALES PARA SOLICITUD  <i class="far fa-hand-point-down fa-lg"></i></a>
 
 </section>


</div>
 


<section id="no_print"> 
    <div class="collapse " id="collapse1" align="center">
      <div class="card">
        <div class="card card-body" id="non-printable" >
          <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
            <div class = "input-group-prepend"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i> &nbsp;  Día a compensatorio a solicitar :  </span></div>
            <input type='date' id ="fecha_i"   name = "fecha_inicial" class="form-control" id ="dt1" required="true">
           
             <div class = "input-group-prepend"  style="margin-left:20px;" ><span  class="input-group-text"> <i class="far fa-calendar-alt"></i> &nbsp;  Motivo de Asistencia  </span></div>
            <select class="form-control" id="motivo" name='motivo' required="true">
              <option value=""> Seleccione Uno</option>
             <?php 
              $query_concepto_comopensatorio= mysqli_query($conn, "SELECT * FROM rr_hh_compensatorios_reasons ORDER BY id asc");

              while ($row_concept=mysqli_fetch_array($query_concepto_comopensatorio))
              {
                 $idconcept=$row_concept['id'];
                 $txtconcept=$row_concept['concept'];
                 echo "<option id='".$idconcept."'> ".$txtconcept."</option>";
              }

              ?>

            </select>
            <button type="submit" name="seleccionar" style="margin-left: 10px;" class="btn btn-primary" value = "Seleccionar">  Seleccionar &nbsp;<i class="fas fa-search fa-lg"></i></button>
          </div>
             <div class = "input-group-prepend"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i> &nbsp;  NOTA :  </span></div>
             <textarea class="form-control" id='nota' name='nota' rows="3" required="true"></textarea>
        </div>
      </div>
    </div> 
</section> <br>
<section>
<?php if ($_SERVER["REQUEST_METHOD"] == "POST") : 
$_fecha_inicial=$_POST['fecha_inicial'];  
$_fecha_inicial = date("d-m-Y", strtotime($_fecha_inicial));
$reason=$_POST['motivo'];  
$_nota=$_POST['nota'];  


$fecha_asistenciai = date("d-m-Y", strtotime($fecha_asistenciai));
?>


  <div class="container">
<section align="center" >
  <table align="center">
    <tr>
      <td width="30%" style="text-align: center;" align="center"><img src="../../template/images/PANI_1.jpg" align="center"  border="0"  width="60%" ></td>
  </tr>
  </table>
</section><br><br>
<section>
  <h3>Gerencia de Recursos Humanos</h3>
  <h4> Inspectoría del Tiempo </h4>
  <br>
  <p>Solicitud de diá Compensatorio <br> 
     Empleado Solicitante : <?php echo $usuario_asistencia; ?> - <?php echo $name; ?> <br> 
     Fecha : <?php echo $dia." ".$diadate." ".$mes." DE ".$ano ?>
  </p><br>
  <p>

    Por medio del presente solicito se me permita gozar de un día Compensatorio , el día  &nbsp;<strong><?php echo $_fecha_inicial; ?> </strong> &nbsp;, por haber asistido a trabajar el día  &nbsp;<strong><?php echo $fecha_asistenciai; ?></strong>&nbsp;  EN ACTIVIDADES DE &nbsp;  <strong><?php echo $reason ?>   </strong>&nbsp;,  en una jornada de  &nbsp;<strong><?php echo $hora_entrada ; ?></strong>&nbsp; a   &nbsp; <strong><?php echo $hora_salida ; ?></strong>&nbsp; ACUMULANDO  &nbsp;<strong><?php echo $tiempo_trabajado; ?> </strong>HORAS LABORADAS.
  </p><br>
  <p>Nota :
    <?php  echo $_nota; ?>
  </p><br>
  <p>
   Agradeciendo de antemano
  </p> 
  <p>
    Saludos.
  </p><br><br><br>

 <table width="100%">
   <tr>
    <td align="center">______________________________</td>
    <td></td>
    <td align="center">_______________________________</td>
  </tr>
  <tr>
    <td align="center">EMPLEADO</td>
    <td align="center">_______________________________</td>       
    <td align="center">INSPECTORIA DEL TIEMPO</td>
  </tr> 
  <tr>
    <td></td>
    <td align="center">GERENTE/JEFE INFORMATICA</td>    
    <td></td>
  </tr>  
 </table>
</section>   
</div><br><br>
  <div align="center" id="no_print">
      <button type="button" class="btn btn-success"  onclick="window.print()" > Imprimir Reporte General</button>   
  </div><br><br>

<?php endif ?>



 



 </form>
 <script type="text/javascript">
 	$(".div_wait").fadeOut("fast");  
 </script>