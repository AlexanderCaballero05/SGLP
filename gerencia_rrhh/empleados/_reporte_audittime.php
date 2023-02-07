<?php 
require('../../template/header.php');  

$conn_ingress = mysqli_connect('192.168.15.4:3306', 'ingress' , 'ingress', 'ingress') or die('No se pudo conectar: ' . mysqli_error());  

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
            input  {
              font-size:18px;
              padding:10px 10px 10px 5px;
              display:block;
              width:100%;
              border:none;
              border-bottom:1px solid #757575;
            }
            input:focus         { outline:none; }

            /* LABEL ======================================= */
      /*      label                
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
    <a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS <i class="far fa-hand-point-down fa-lg"></i></a> 
</section>
</div>
 


<section id="no_print"> 
    <div class="collapse " id="collapse1" align="center">
      <div class="card">
        <div class="card card-body" id="non-printable" >
          <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
            <div class = "input-group-prepend"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i> &nbsp;  SELECCIONE EL MES : </span></div>
            
              <input type="month"  class="form-control" id="mes" name="mes"  min="2019-01" value="">     
          
            </select>
            <button type="submit" name="seleccionar" style="margin-left: 10px;" class="btn btn-primary" value = "Seleccionar">  Seleccionar &nbsp;<i class="fas fa-search fa-lg"></i></button>
          </div>            
        </div>
      </div>
    </div> 
</section> <br>
<section>
  <?php 
   if ($_SERVER["REQUEST_METHOD"] == "POST") 
   {
          setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
          setlocale(LC_ALL, "es_ES");

          $mes=$_POST['mes'];

          echo "este es el mes : ".$mes;
          $mes_txt = date('d-m-Y', strtotime($_POST['mes']));
          $año_txt = date('Y', strtotime($_POST['mes']));   
          $monthName = date("F", strtotime( $mes_txt )); 
          $monthNum  = date('m', strtotime($_POST['mes'])); 
          $dateObj   = DateTime::createFromFormat('!m', $monthNum);
          $monthName = strftime('%B', $dateObj->getTimestamp());
                
                     echo  "<br> Reporte de Impuesto de Lotería Menor del Mes de ".$monthName." de ".$año_txt." </strong></div>";

                            echo " <div class='table-responsive'>                                      
                                      <table id='table_id' class='table table-striped table-bordered dt-responsive nowrap table-sm'   cellspacing='0'style0='width:100%''>
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>UserId</th>
                                                    <th>nombre</th>                                                    
                                                    <th>Area</th>
                                                    <th>Fecha</th>
                                                    <th class='table-success'>reloj In</th>
                                                    <th class='table-success'>audit In</th>
                                                    <th class='table-success'>atte In</th>
                                                    <th class='table-success'>Mod In</th>
                                                    <th class='table-danger'>Dif In</th>
                                                    <th class='table-info'>reloj Out</th>
                                                    <th class='table-info'>audit Out</th>
                                                    <th class='table-info'>atte Out</th>
                                                    <th class='table-info'>Mod Out</th>
                                                    <th class='table-danger'>Dif Out</th>
                                                </tr>
                                            </thead>
                                            <tbody>";

                                             $fecha_inicial       = date('d-m-Y', strtotime($_POST['mes']));
                                             $fecha_final         = date("d-m-Y",strtotime($mes)); 
                                             $fecha_final         = date("d-m-Y",strtotime($fecha_final."+ 1 month - 1 day"));
                                             //$fecha_inicial_ciclo = strtotime($fecha_inicial);
                                             //$fecha_final_ciclo   = strtotime($fecha_final);

                                             $fecha_inicial_ciclo = strtotime('16-12-2019');
                                             $fecha_final_ciclo   = strtotime('16-12-2019');
                                           //  $fecha_inicial       = strtotime('2019-12-16');

                                             $dia = 86400;
/*
                                            $conta=1;   $contador=0;
                                            while ( $fecha_inicial_ciclo <= $fecha_final_ciclo) 
                                            {                                            
                                              $fecha_view          =  date("d-m-Y",strtotime($fecha_inicial."+ ".$contador." day")); 
                                              $fecha_query_audit   =  date("Y-m-d",strtotime($fecha_view)); 
                                            //  $fecha_query_audit   =  '2020-02-01'; 
                                              $query_info_personal = mysqli_query($conn_ingress, "SELECT a.userid, concat(name, ' ',  lastname) nombre_completo , User_Group,  gName  
                                                       FROM user a, user_group b 
                                                       WHERE a.User_Group =b.id ");
                                           
                                                    while ($row_info_personal = mysqli_fetch_array($query_info_personal)) 
                                                    {
                                                      $userid           = $row_info_personal['userid'] ;                       
                                                      $nombre_completo  = $row_info_personal['nombre_completo'] ;
                                                      $area             = $row_info_personal['gName'] ;

                                                      $query_audit=mysqli_query($conn_ingress, "SELECT time(min(checktime)) auditin, time(max(checktime)) auditout  FROM auditdata WHERE userid=$userid AND date(checktime)='2019-12-16' ORDER BY checktime ASC;");

                                                        $obj_audit  = mysqli_fetch_object($query_audit);
                                                        $auditin    = $obj_audit->auditin;
                                                        $auditout   = $obj_audit->auditout;

                                                        if ($auditin==$auditout) {
                                                           $auditin="";
                                                        }

                                                        echo "<tr><td>".$conta."</td>
                                                                  <td>".$userid."</td>
                                                                  <td>".$nombre_completo."</td>
                                                                  <td>".$area."</td>
                                                                  <td>".$fecha_view."</td>
                                                                  <td class='table-success'>".$auditin."</td>
                                                                  <td class='table-success'></td>
                                                                  <td class='table-success'></td>
                                                                  <td class='table-success'></td>
                                                                  <td class='table-danger'></td>
                                                                  <td class='table-info'>".$auditout."</td>
                                                                  <td class='table-info'></td>
                                                                  <td class='table-info'></td>
                                                                  <td class='table-info'></td>
                                                                  <td class='table-danger'></td></tr>";
                                                           $conta++;
                                                    }                                         
                                            $fecha_inicial_ciclo += $dia;
                                            $contador++;
                                          }
                                         echo "</tbody>
                                        </table></div>";
                                        */
            
       

 
   }
   ?>
</section>



 </form>
 <script type="text/javascript">
 	$(".div_wait").fadeOut("fast");  
 </script>
