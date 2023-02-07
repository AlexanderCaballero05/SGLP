<?php 
require('../../template/header.php'); 

$usuario_id=$_SESSION['id_usuario'];
$nombre_usuario=$_SESSION['nombre'];

?>
 
<script type="text/javascript">
       $(".div_wait").fadeIn("fast");  
</script>

<style type="text/css">

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

</style>


<script type="text/javascript"> 
$(document).ready(function ()
{ 
     $(".btn").click(function() 
      {
        var btn = $(this).data("btn");
        $(this).addClass(btn).removeClass("btn-default");
        $(this).siblings().each(function() {
        $(this).removeClass($(this).data("btn")).addClass("btn-default");
         // $(this).removeClass($(this).data('btn')).addClass('btn-default');
        });
      });
});

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
      
         if (accion==2) 
          {       
                 var urr_revi_false = "./_rp_actualiza_revision_menor.php?id=" + id+"&usu="+user+"&estado=2&agencia_code="+agencia_code+"&fecha_inicial="+fecha_inicial+"&valida="+Math.random(); 
                 $('#lbletiqueta'+id).load(urr_revi_false); 
                // alert(urr_revi_false);
                  document.getElementById('id_nota').value=id;
                  document.getElementById('comentario_dco').value=" ";
                  document.getElementById('tipo_doc').value=" ";
                  document.getElementById('slctincidencia').value=" ";
                  $('#myModal').modal('show');
                  $('#comentario_dco').val("");  
          }  
          else if (accion==1)
          { 
              var urr_revi_true = "./_rp_actualiza_revision_menor.php?id=" + id+"&usu="+user+"&estado=1&agencia_code="+agencia_code+"&fecha_inicial="+fecha_inicial+"&valida="+Math.random();   
              $('#lbletiqueta'+id).load(urr_revi_true); 
              //alert(urr_revi_true);
          }
}

 


function add_nota()
{  
  var id= $("#id_nota").val(); 
  var documento=$("#tipo_doc").val();   
  var incidencia=$("#slctincidencia").val();
  var comentario_rev=$("#comentario_dco").val();
  var user="<?php echo $usuario_id; ?>" ; 

  comentario_rev= comentario_rev.replace(/\s/g,"_");   
  var urr_dco = "_rp_insert_nota_menor.php?id="+id+"&tipo="+documento+"&incidencia="+ incidencia +"&usu="+user+"&comment="+comentario_rev+"&valida="+Math.random(); 
  //alert(urr_dco);
   
  if (documento=='1') { pa='NOTA DE DEBITO'; } else { pa='NOTA DE CREDITO'; };
  if (incidencia=='1') { incidencia_text='Mal Pago'; } else if (incidencia=='2') { incidencia_text='Adulteración'; } else if (incidencia=='5') { incidencia_text='Error del Revisor'; } else if (incidencia=='3') { incidencia_text='Faltante'; } else if (incidencia=='4'){ incidencia_text='Sobrante'; } ;            
  swal("Bien!", "Has creado una : " + pa +" por " + incidencia_text , "success"); 

  $("#msg_reversion").load(urr_dco);  
  $("[data-dismiss=modal]").trigger({ type: "click" });
  $('#myModal').trigger("reset");  
}
</script>
<form method="post">
<div id="div_wait" class="div_wait">  </div> 
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Posteo de Billetes premiados de Lotería Menor</h3> <br></section>
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
 <section >   
      <legend style='width:90%'>Parametros de Revisión de Billetes</legend> 
          <div class="table-responsive">
              <table class="table table-hover table-bordered table-sm" id="table" style="font-size: 14pt" >
                  <thead align="center">
                    <tr><td>Número</td>
                        <td>Serie</td>
                        <td>Registro</td>
                        <td>Neto</td>
                        <td>Revisión</td>
                    </tr></thead><tbody>
                    <?php  
                    if (isset($_POST['buttonConsulta']))
                      {
                            if ( $_POST['agencia']>0 )  
                            {

                                $_fecha_inicial=$_POST['fecha_inicial'];  $_fecha_inicial = date("Y-m-d", strtotime($_fecha_inicial)); $_agencia=$_POST['agencia'];                               
                                $validacion_asignacion= mysqli_query($conn, "SELECT * FROM rp_asignacion_agencias_revisor_menor WHERE date(transactiondate)='$_fecha_inicial' and transactionagency= $_agencia and usuario_revision=$usuario_id ");

                                if (mysqli_num_rows($validacion_asignacion)>0) 
                                {



                                  $ob_asignacion =  mysqli_fetch_object($validacion_asignacion);
                                  $year_remesa = $ob_asignacion->ano_remesa; 


                                    $_result_sorteo=mysqli_query($conn, "SELECT sorteo FROM menor_pagos_detalle a, menor_pagos_recibos b  WHERE a.transactioncode=b.transactioncode and date(a.transactiondate) ='$_fecha_inicial' and b.transactionagency=$_agencia and a.estado_revision is null AND a.ano_remesa = '$year_remesa'  group by sorteo order by sorteo asc");
                                    
                                    

                                    if (!mysqli_num_rows($_result_sorteo)>0)  {  echo "<div class='alert alert-danger'> Esta remesa ya ha sido posteada en su totalidad </div>";  }

                                    while ( $row_sorteo=mysqli_fetch_array($_result_sorteo))
                                    {                             
                                       $_sorteo=$row_sorteo['sorteo'];   
                                       echo "<tr class='table-success'><td colspan='5' align='center'> Sorteo ".$_sorteo."</td></tr>";   
                                      $result = mysqli_query($conn, "SELECT concat(a.sorteo,a.numero, a.serie,a.registro)  llave, a.id, a.transactiondate, a.sorteo, a.numero, a.serie, a.registro, a.principal, a.impto, a.neto , b.transactionagencyname seccional, a.transactionstate 
                                                                     FROM   menor_pagos_detalle a, menor_pagos_recibos b WHERE  a.transactioncode=b.transactioncode AND b.transactionagency=$_agencia AND a.estado_revision is NULL AND date(a.transactiondate) = '$_fecha_inicial' AND a.sorteo=$_sorteo AND a.transactionstate IN (1,3)  ORDER BY transactionuser, numero, serie ASC;");

                                          $num_productos = mysqli_num_rows($result); 
                                          if (mysqli_num_rows($result)>0)
                                          {
                                                  $acumulado_neto=0;  $contador=0;  $neto_sorteo=0;                          
                                                  while ($row = mysqli_fetch_array($result))                                 
                                                  { 
                                                      $transactionstate=$row['transactionstate'];
                                                      if ($transactionstate==3)    {  $rowcolor='ff0000';    $pal="Adulterado";   }   else   {  $rowcolor='000000';    $pal="";     }
                                                      $_seccional=   $row['seccional'];                           
                                                      $neto_sorteo=$neto_sorteo+$row['neto']; 
                                                      $id=$row['id']; $acumulado_neto=$row['neto']+$acumulado_neto;
                                                                                                      
                                                      echo "<tr style='color:".$rowcolor."'>
                                                                <td align='center'>".$row['numero']."</td>
                                                                <td align='center'>".$row['serie']."</td>
                                                                <td align='center'>".$row['registro']."</td>
                                                                <!-- td>".number_format($row['principal'],2,'.',',')."</td>
                                                                <td>".number_format($row['impto'],2,'.',',')."</td -->
                                                                <td align='right'>".number_format($row['neto'],2,'.',',')."</td>
                                                                <td align= 'center'> 
                                                                <div id='lbletiqueta".$id."'> <strong class='text-danger'>".$pal."</strong> 
                                                                <button type='button' class='btn btn-danger btn-sm'  value='".$id."--".$_seccional."--".$_fecha_inicial."--".$_agencia."' onclick='act_esta(this.value, 2, ".$row['sorteo'].$contador.")' id='option1".$row['sorteo'].$contador."'  data-toggle='modal' data-target='#myModal'><i class='far fa-times-circle'></i>   Observaciones </button> 
                                                                <button type='button' class='btn btn-success btn-sm' value='".$id."--".$_seccional."--".$_fecha_inicial."--".$_agencia."' onclick='act_esta(this.value, 1, ".$row['sorteo'].$contador.")' id='option2".$row['sorteo'].$contador."' ><i class='far fa-thumbs-up'></i> Revisado </button>  
                                                                </div>
                                                              </td></tr>";
                                                                                                        $contador ++;                                           
                                                  }                                                                                         
                                             echo "<tr><td colspan='3'> Total Neto pagado  </td><td align='right'>".number_format($neto_sorteo,2,'.',',')."</td><td></td></tr><tr><td colspan='5'> --- </td></tr>";
                                         } 
                                         else   {   echo "<div class='alert alert-danger'> Esta remesa ya ha sido posteada en su totalidad </div>";  }
                                    }
                                }
                                else   {   echo "<div class='alert alert-danger'> <strong> Atención ! </strong> Los Parametros seleccionados no corresponden a su asignacion, favor confirmar con receptoria de Lotería</div>";  }                                  
                         }
                      }  
                         
                          ?>
                  </tbody>
                </table>

                  <div id="msg_reversion"></div>             
   </section>
 
  <!-- Modal -->
                          <div class="modal fade" id="myModal" role="dialog">
                            <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                    <div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Solicitud de documento para las incidencias registradas en Billtes de Loteria Menor</h4></div>
                                    <div class="modal-body">
                                      <p>Tipo de Documento: <input type="hidden" id="id_nota" name="id_nota"><select class="form-control"  id="tipo_doc" name="tipo_doc"><option>Seleccione uno</option><option value='1'>Nota de Credito Interna</option><option value='2'>Nota de Debito Interna</option><option value='3'>Nota de Credito Externa</option><option value='4'>Nota de Debito Externa</option></select></p>
                                      <p>Tipo de Incidencia: 
                                            <select class="form-control" id="slctincidencia"  name='slctincidencia'><option>Seleccione uno</option>
                                                    <?php 
                                                        $query_incidencias=mysqli_query($conn, "SELECT * from rp_incidencias order by id asc");
                                                        if ($query_incidencias==true)  
                                                          {  
                                                            while ($row_incidencia=mysqli_fetch_array($query_incidencias))   
                                                              { 
                                                                  echo"<option value=".$row_incidencia['id'].">".$row_incidencia['incidencia']."</option>";   
                                                              } 
                                                          }
                                                        else { echo "<option value='99'>Valores no encontrados</option>";   }
                                                     ?>
                                            </select>
                                       </p>          
                                         <p>Comentario del revisor:</p>
                                         <p><textarea id="comentario_dco"  style='width:100%;' rows="10"  name="comentario_dco"></textarea></p>
                                    </div>
                                    <div class="modal-footer"><button class="notas_docs btn btn-success" type="button" onclick="add_nota()" >Generar y guardar</button><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button></div>
                              </div>
                            </div>
                          </div>

</form>
<script type="text/javascript">
       $(".div_wait").fadeOut("fast");  
</script>