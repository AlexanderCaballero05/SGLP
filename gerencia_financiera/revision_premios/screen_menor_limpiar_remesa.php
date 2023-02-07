<?php 
require('../../template/header.php'); 
$usuario_id= $_SESSION['id_usuario'];
$nombre_usuario=$_SESSION['nombre'];
?>  
  <style type="text/css" media="screen">
  #reporte 
     {
       border-radius: 42px 43px 43px 43px;
       -moz-border-radius: 42px 43px 43px 43px;
       -webkit-border-radius: 42px 43px 43px 43px;
        border: 3px solid #139949;
     }
</style>        
<script type="text/javascript">


function seek_agencia()
{  
            $("#agencia").attr("disabled",false);            
            var urr_agencia = "./_select_agencia_rp_menor.php?inicio=" + $( '#fecha_i' ).val()+'&valida='+Math.random(); 
          //  alert(urr_agencia);
            $("#agencia").load(urr_agencia);
}

function act_esta(valor, accion, contador, id)
{
         var result=valor.split('--');
         var id=result[0];
         var agencianame=result[1];  
         var agencia_code=result[3];
         var fecha_inicial=result[2];
         var user=   "<?php echo $usuario_id; ?>"
         var revisor="<?php echo $nombre_usuario; ?>" ;
         $(".div_wait").fadeIn("fast");
      
       
              var urr_revi_true = "./_rp_limpiar_revision_menor.php?id=" + id+"&usu="+user+"&estado=1&agencia_code="+agencia_code+"&fecha_inicial="+fecha_inicial+"&valida="+Math.random();   
              $('#lbletiqueta'+id).load(urr_revi_true); 
              //alert(urr_revi_true);
      
}

   
</script>


<form method="post" id="_revision_premios" name="_revision_premios">
<div id="div_wait" class="div_wait"></div><br> 

<div id="div_wait" class="div_wait">  </div> 
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Limpiar Billetes posteados de Lotería Menor</h3> <br></section>
<section>
<a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS  <i class="far fa-hand-point-down fa-lg"></i></a>
    <div class="collapse " id="collapse1" align="center">
      <div class="card">
        <div class="card card-body" id="non-printable" >
          <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
            <div class = "input-group-prepend"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i> &nbsp;  Fecha Revision: </span></div>
            <input type='date' id ="fecha_i"   name = "fecha_inicial" class="form-control" onchange="seek_agencia()">
            <div class = "input-group-prepend" style="margin-left: 10px;"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i>  &nbsp;   Agencia: </span></div>
            <select required name="agencia" id="agencia" disabled="true"  style="width:50%;" class="form-control"></select>
            <button id="buttonConsulta" name="buttonConsulta" type="submit" class="Consulta btn btn-primary">BUSQUEDA DE BILLETES PAGADOS</button>
          </div>
        </div>
      </div>
    </div> 
 </section>
 <hr>
<hr>
 <section >   
      <legend style='width:90%'>Parametros de Revisión de Billetes</legend> 
          <div class="table-responsive">
              <table class="table table-hover table-bordered table-sm" id="table" style="font-size: 14pt" >
                  <thead align="center">
                    <tr><td>Número</td>
                        <td>Serie</td>
                        <td>Registro</td>
                        <td>Neto</td>
                        <td>Estado Revisión</td>
                    </tr></thead><tbody>
                    <?php  
                    if (isset($_POST['buttonConsulta']))
                      {  
                            if ( $_POST['agencia']>0 )  
                            {                       
                              $_fecha_inicial=$_POST['fecha_inicial'];  $_fecha_inicial = date("Y-m-d", strtotime($_fecha_inicial)); $_agencia=$_POST['agencia'];
                              $_result_sorteo=mysqli_query($conn, "SELECT sorteo FROM menor_pagos_detalle a, menor_pagos_recibos b  WHERE a.transactioncode=b.transactioncode and date(a.transactiondate) ='$_fecha_inicial' and b.transactionagency=$_agencia  group by sorteo order by sorteo asc")  ;                                                                                          
                             
                             while ( $row_sorteo=mysqli_fetch_array($_result_sorteo))
                             {                             
                                $_sorteo=$row_sorteo['sorteo'];   
                                echo "<tr class='table-success'><td colspan='5' align='center'> Sorteo ".$_sorteo."</td></tr>";                                          
                      
                                $result = mysqli_query($conn, "SELECT a.id, a.transactiondate, a.sorteo, a.numero, a.serie, a.registro, a.principal totalpayment, a.impto imptopayment, a.neto, b.transactionagencyname seccional, a.transactionstate ,  estado_revision
                                                    FROM menor_pagos_detalle a, menor_pagos_recibos b
                                                    WHERE  a.transactioncode=b.transactioncode and b.transactionagency=$_agencia and date(a.transactiondate) = '$_fecha_inicial' and a.sorteo=$_sorteo and a.transactionstate in (1 ,3) 
                                                    order by transactionuser, numero , serie asc;");
 
                                if (mysqli_num_rows($result)>0)
                                { 
                                    $acumulado_neto=0;  $contador=0;  $neto_sorteo=0;                          
                                    while ($row = mysqli_fetch_array($result))                                 
                                    { 
                                      $transactionstate=$row['transactionstate'];                                     
                                      $estado_revision=$row['estado_revision']; 
                                      $_seccional=   $row['seccional'];                           
                                      $neto_sorteo=$neto_sorteo+$row['neto']; 
                                      $id=$row['id']; $acumulado_neto=$row['neto']+$acumulado_neto;
                                                                                                     
                                      echo "<tr><td align='center'>".$row['numero']."</td>
                                                                            <td align='center'>".$row['serie']."</td>
                                                                            <td align='center'>".$row['registro']."</td>
                                                                            <!-- td>".number_format($row['totalpayment'],2,'.',',')."</td>
                                                                            <td>".number_format($row['imptopayment'],2,'.',',')."</td -->
                                                                            <td align='right'>".number_format($row['neto'],2,'.',',')."</td>
                                                                            <td align= 'center'> 
                                                                            <div id='lbletiqueta".$id."'> " ;                                                                             
                                                                            if ($estado_revision>0) {
                                                                                 echo "<button type='button' class='btn btn-warning btn-sm' value='".$id."--".$_seccional."--".$_fecha_inicial."--".$_agencia."' onclick='act_esta(this.value, 1, ".$row['sorteo'].$contador.")' id='option2".$row['sorteo'].$contador."' ><i class='fas fa-eraser'></i> Limpiar Billete </button>";            
                                                                            } 
                                                                            else
                                                                            {
                                                                              echo "Billete pendiente de revisar";
                                                                            }                
                                                                      
                                                                            echo "</div>
                                                                            </td></tr>";
                                                                            $contador ++;                                            
                                    }                                                                                         
                                       echo "<tr><td colspan='3'> Total Neto pagado  </td><td align='right'>".number_format($neto_sorteo,2,'.',',')."</td><td></td></tr><tr><td colspan='5'> --- </td></tr>";
                                } 
                                         else   {   echo "<div class='alert alert-danger'> Esta remesa ya ha sido posteada en su totalidad </div>";  }
                             }                                                               
                         }
                      }  
                         
                          ?>
                  </tbody></table>

                  <div id="msg_reversion"></div>             
   </section>


</form>
