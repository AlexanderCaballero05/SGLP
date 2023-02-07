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
 
function seek_agencia()
{  
            $("#agencia").attr("disabled",false);            
            var urr_agencia = "./_select_agencia_rp_mayor.php?inicio=" + $( '#fecha_i' ).val()+'&valida='+Math.random(); 
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

         
      
         if (accion==2) 
          {       
                  document.getElementById('id_nota').value=id;
                  document.getElementById('comentario_dco').value=" ";
                  document.getElementById('tipo_doc').value=" ";
                  document.getElementById('slctincidencia').value=" ";
                  document.getElementById('decimos_nota').value=" ";
                  document.getElementById('neto_nota').value=" ";
                  $('#myModal').modal('show');
                  $('#comentario_dco').val("");  
          }  
          else if (accion==1)
          { 
             $(".div_wait").fadeIn("fast");
              var urr_revi_true = "./_rp_actualiza_revision_mayor.php?id=" + id+"&usu="+user+"&estado=1&agencia_code="+agencia_code+"&fecha_inicial="+fecha_inicial+"&valida="+Math.random();   
              $('#lbletiqueta'+id).load(urr_revi_true); 
              //alert(urr_revi_true);
          }
}

function add_nota()
{  
  var id             =$("#id_nota").val(); 
  var documento      =$("#tipo_doc").val();   
  var incidencia     =$("#slctincidencia").val();
  var comentario_rev =$("#comentario_dco").val();
  var decimos_nota   =$("#decimos_nota").val();
  var neto_nota      =$("#neto_nota").val();
  var user           ="<?php echo $usuario_id; ?>" ; 

  comentario_rev= comentario_rev.replace(/\s/g,"_");  
  var urr_dco = "_rp_insert_nota_mayor.php?id="+id+"&tipo="+documento+"&incidencia="+ incidencia +"&usu="+user+"&comment="+comentario_rev+"&decimos_nota="+decimos_nota+"&neto_nota="+neto_nota+"&valida="+Math.random(); 
  if (documento=='1') { pa='NOTA DE DEBITO'; } else { pa='NOTA DE CREDITO'; };
  if (incidencia=='1') { incidencia_text='Mal Pago'; } else if (incidencia=='2') { incidencia_text='Adulteración'; } else if (incidencia=='3') { incidencia_text='Faltante'; } else if (incidencia=='4'){ incidencia_text='Sobrante'; } ;            
  swal("Bien!", "Has creado una : " + pa +" por " + incidencia_text , "success"); 

  //$("#msg_reversion").load(urr_dco); 
  $('#lbletiqueta'+id).load(urr_dco); 
  //alert(urr_dco); 
  $("[data-dismiss=modal]").trigger({ type: "click" });
  $('#myModal').trigger("reset");  
}
</script>
<form method="post">
<div id="div_wait" class="div_wait">  </div> 
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Posteo de Billetes Premiados de Lotería Mayor</h3> <br></section>
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
           
                    <?php  
                    if (isset($_POST['buttonConsulta']))
                      {  

                    

                            if ( $_POST['agencia']>0 )  
                            {                       
                                $_fecha_inicial=$_POST['fecha_inicial'];  $_fecha_inicial = date("Y-m-d", strtotime($_fecha_inicial)); $_agencia=$_POST['agencia'];                               
                                //$var_t="SELECT * FROM rp_asignacion_agencias_revisor_mayor WHERE date(transactiondate)='$_fecha_inicial' and transactionagency= $_agencia and usuario_revision=  $usuario_id ";
                               // echo  $var_t;

                                $query_agency_name=mysqli_query($conn, "SELECT nombre FROM seccionales WHERE id_empresa=3 and cod_seccional=$_agencia");
                                while ($row_seccional=mysqli_fetch_array($query_agency_name)) 
                                {
                                   $nombre_seccional= $row_seccional['nombre'];
                                }
                                    echo "<div class='alert alert-info'><h5><strong>Pagos realizados el ".$_fecha_inicial." en la agencia : ".$_agencia." -- ".$nombre_seccional.".</strong></h5></div>";

                                    echo  '<table class="table table-hover table-bordered table-sm" id="table" style="font-size: 14pt">
                                              <thead align="center">
                                                <tr><td>Número</td>
                                                    <td>Decimos</td>
                                                    <td>Registro</td>
                                                    <td>Neto</td>
                                                    <td>Revisión</td>
                                                </tr></thead><tbody>';

                                $validacion_asignacion= mysqli_query($conn, "SELECT * FROM rp_asignacion_agencias_revisor_mayor WHERE date(transactiondate)='$_fecha_inicial' and transactionagency= $_agencia and usuario_revision=  $usuario_id or usuario_revision=$usuario_id ");

                                if (mysqli_num_rows($validacion_asignacion)>0) 
                                {
                                    $_result_sorteo=mysqli_query($conn, "SELECT sorteo FROM mayor_pagos_detalle a, mayor_pagos_recibos b  WHERE a.transactioncode=b.transactioncode and date(a.transactiondate) ='$_fecha_inicial' and b.transactionagency=$_agencia and a.estado_revision is null group by sorteo order by sorteo asc")  ;                                                                
                          
                                    if (!mysqli_num_rows($_result_sorteo)>0)  {  echo "<div class='alert alert-danger'> Esta remesa ya ha sido posteada en su totalidad </div>";  }

                                    while ( $row_sorteo=mysqli_fetch_array($_result_sorteo))
                                    {                             
                                       $_sorteo=$row_sorteo['sorteo'];   
                                       echo "<tr class='table-success'><td colspan='5' align='center'> Sorteo ".$_sorteo."</td></tr>";                                       
                      
                                       $result = mysqli_query($conn, "SELECT a.id, a.transactiondate, a.sorteo, a.numero, a.decimos, a.registro, a.totalpayment, a.imptopayment, a.netopayment neto, b.transactionagencyname seccional, a.transactionstate
                                                    FROM mayor_pagos_detalle a, mayor_pagos_recibos b  WHERE  a.transactioncode=b.transactioncode and b.transactionagency=$_agencia and a.estado_revision is null and date(a.transactiondate) = '$_fecha_inicial' and a.sorteo=$_sorteo and a.transactionstate in (1 ,3) order by transactionuser, numero asc;");
 
                                          if (mysqli_num_rows($result)>0)
                                          { 
                                                  $acumulado_neto=0;  $contador=0;  $neto_sorteo=0;                          
                                                  while ($row = mysqli_fetch_array($result))                                 
                                                  { 
                                                      $transactionstate=$row['transactionstate'];
                                                      if ($transactionstate==3)    {  $rowcolor='ff0000';    $pal="Adulterado";   }   else   {  $rowcolor='000000';    $pal="";   }
                                                      $_seccional=   $row['seccional'];                           
                                                      $neto_sorteo=$neto_sorteo+$row['neto']; 
                                                      $id=$row['id']; $acumulado_neto=$row['neto']+$acumulado_neto;
                                                                                                      
                                                      echo "<tr style='color:".$rowcolor."'><td align='center'>".$row['numero']."</td>
                                                                                        <td align='center'>".$row['decimos']."</td>
                                                                                        <td align='center'>".$row['registro']."</td>
                                                                                        <!-- td>".number_format($row['totalpayment'],2,'.',',')."</td>
                                                                                        <td>".number_format($row['imptopayment'],2,'.',',')."</td -->
                                                                                        <td align='right'>".number_format($row['neto'],2,'.',',')."</td>
                                                                                        <td align= 'center'> 
                                                                                           <div id='lbletiqueta".$id."'>
                                                                                           <button type='button' class='btn btn-danger btn-sm' value='".$id."--".$_seccional."--".$_fecha_inicial."--".$_agencia."' onclick='act_esta(this.value, 2, ".$row['sorteo'].$contador.")' id='option1".$row['sorteo'].$contador."'  data-toggle='modal' data-target='#myModal'><i class='far fa-times-circle'></i>   Observaciones </button> 
                                                                                           <button type='button' class='btn btn-success btn-sm' value='".$id."--".$_seccional."--".$_fecha_inicial."--".$_agencia."' onclick='act_esta(this.value, 1, ".$row['sorteo'].$contador.")' id='option2".$row['sorteo'].$contador."' ><i class='far fa-thumbs-up'></i> Revisado ".$pal."</button>  
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
                  </tbody></table>

                  <div id="msg_reversion"></div>             
   </section>
  <!-- Modal -->
                          <div class="modal fade" id="myModal" role="dialog">
                            <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                    <div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Solicitud de documento para las incidencias registradas en Billtes de Loteria Mayor</h4></div>
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
                                                        } else { echo "<option value='99'>Valores no encontrados</option>";   }
                                                    ?>
                                            </select>
                                       </p>   
                                       <p>Decimos de la nota: <input type="number" name="decimos_nota" id="decimos_nota" class="form-control"></p>       
                                       <p>Monto de la nota: <input type="number" name="neto_nota" id="neto_nota"  class="form-control"></p>       
                                         <p>Comentario del revisor:</p>
                                         <p><textarea id="comentario_dco"  style='width:100%;' rows="10"  name="comentario_dco"></textarea></p>
                                    </div>
                                    <div class="modal-footer">
                                      <button class="notas_docs btn btn-success" type="button" onclick="add_nota()" >Generar y guardar</button>
                                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                              </div>
                            </div>
                          </div>

</form>
<script type="text/javascript">
       $(".div_wait").fadeOut("fast");  
</script>
