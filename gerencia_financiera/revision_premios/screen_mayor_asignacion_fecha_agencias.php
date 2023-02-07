<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['usuario'] ; 
?>
 
 
<style type="text/css"> 
.div_wait {
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

function add_revisor(valor, estado)
{
  document.getElementById('fecha').value=document.getElementById('txt'+valor).value;
}

function quitar_revisor(valor, remesa)
{
     var fecha= document.getElementById('txt'+valor).value;
     $(".div_wait").fadeIn("fast");  
     var urr_dco = './rp_quitar_asignacion_mayor.php?fecha='+fecha+'&remesa='+remesa+'&est=1&al='+Math.random(); 
     $("#tabl").load(urr_dco); 
}


function update_revisor()
{
   fecha=document.getElementById('fecha').value;
   remesa=document.getElementById('remesa_modal').value;
   revisor=document.getElementById('slctincidencia').value;
   $(".div_wait").fadeIn("fast");  
   var urr_dco = './rp_add_asignacion_mayor.php?fecha='+fecha+'&remesa='+remesa+'&revisor='+revisor+'&est=1&al='+Math.random();    
   $("#tabl").load(urr_dco);
   //alert(urr_dco) ;
   $("[data-dismiss=modal]").trigger({ type: "click" });
   $('#myModal').trigger("reset");
}
</script>

<form method="post">

<div id='div_wait'></div>
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Asignación de Lotería Mayor por Fecha</h3> <br></section>
 
<section>
<a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS  <i class="far fa-hand-point-down fa-lg"></i></a>
    <div class="collapse " id="collapse1" align="center">
      <div class="card">
        <div class="card card-body" id="non-printable" >
          <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
            <div class="input-group-prepend" style="margin-left: 5px;" ><span  class="input-group-text">Remesa: </span></div>
            <input type='text' min='35' name="remesa" id="remesa" class="form-control input-lg" required onkeypress="return justNumbers(event)" maxlength="3">
            <button type="submit" name="seleccionar" style="margin-left: 10px;" class="btn btn-primary" value = "Seleccionar">  Seleccionar &nbsp;<i class="fas fa-search fa-lg"></i></button>
          </div>
        </div>
      </div>
    </div> 
 </section>
 <hr>
<section>
<?php
if (isset($_POST['seleccionar'])) 
{   


  $_remesa=$_POST['remesa'];
  echo "<div class='row'>
          <div class='col-sm-12' id='tabl'>
            <div class='alert alert-success' role='alert'><h3>Asignación de Loteria Mayor Remesa ".$_remesa."</h3></div><hr>
                 <div class='table-responsive'>
                    <table class='table table-hover table-sm'>
                       <thead><tr><th>Fecha | pago</th>
                                  <th>Agencias</th>
                                  <th>Billetes</th>
                                  <th>Neto pagado</th>
                                  <th></th>
                              </tr></thead><tbody>";

    $ano_actual_consulta= date("Y");
    echo "este es ell año ".$ano_actual_consulta;
    $query_agencias_fecha=mysqli_query($conn, "SELECT date(transactiondate) fecha,  count(transactionagency) agencia, sum(cant_decimos) sum_numeros, sum(totalpayment) total, sum(imptopayment) impto, sum(netopayment)  neto
                                       FROM rp_asignacion_agencias_revisor_mayor
                                       WHERE remesa=$_remesa and ano_remesa='$ano_actual_consulta' group by fecha order by fecha_recepcion asc");
    $ontador=0;
    while ( $row_agencias_dia=mysqli_fetch_array($query_agencias_fecha)  )
    {
       $fecha=$row_agencias_dia['fecha'];  $agencias=$row_agencias_dia['agencia'];  $totalnumeros=$row_agencias_dia['sum_numeros'];  $total_fecha=$row_agencias_dia['total'];  $impto_fecha=$row_agencias_dia['impto'];  $neto_fecha=$row_agencias_dia['neto'];  
        echo "<tr><td>".$fecha."</td>
                  <td>".$agencias."</td>
                  <td>".$totalnumeros."</td>
                  <td>".number_format($neto_fecha,2,'.',',')."</td>
                  <td align='left'><input type='hidden' id='txt".$ontador."' value='".$fecha."'>";                     
                   $query_user_asignado=mysqli_query($conn, "SELECT substring(b.nombre_completo, 1, 10) nombre_completo FROM `rp_asignacion_agencias_revisor_mayor` a, pani_usuarios b WHERE a.usuario_revision=b.id and remesa=$_remesa and date(transactiondate)='$fecha' group by usuario_revision");                      
                    if ( mysqli_num_rows( $query_user_asignado )===0 ) 
                      { 
                         echo"<a role='button' class='btn btn-success btn-sm'  onclick='add_revisor(".$ontador.", 1)'  data-toggle='modal' href='#myModal'><i class='far fa-thumbs-down'></i> Asignar Revisor</a>
                              <a role='button' class='btn btn-primary btn-sm' target='blank' href='_rp_asignacion_agencias_menor_fechas_agencia.php?remesa=".$_remesa."&fecha_pago=".$fecha."'><i class='far fa-thumbs-up'></i> Distribuir Detalle</a>";
                      }
                      else if (mysqli_num_rows( $query_user_asignado )===1 ) 
                      {       
                         while ($row_user_asignado=mysqli_fetch_array($query_user_asignado) ) {   $revisor='<span style="color:green;">'.$row_user_asignado['nombre_completo'].'</span>';   }
                         echo   $revisor.'</span>&nbsp;&nbsp;&nbsp;'."<a role='button' class='btn btn-danger btn-sm' onclick='quitar_revisor(".$ontador.", ".$_remesa.")'><i class='far fa-trash-alt'></i>  </a>";
                      }
                      else if (mysqli_num_rows( $query_user_asignado )>1 ) 
                      {
                            $pleca=''; $revisor='';
                            while ($row_user_asignado=mysqli_fetch_array($query_user_asignado) )  {  $revisor=$revisor.$pleca.$row_user_asignado['nombre_completo'];   $pleca=' / ';   }
                            echo '<span class="label label-success" >'.$revisor.'</span>&nbsp;&nbsp;&nbsp;';
                            echo "<a role='button' class='btn btn-primary btn-sm' target='blank' href='_rp_asignacion_agencias_menor_fechas_agencia.php?remesa=".$_remesa."&fecha_pago=".$fecha."'><i class='far fa-thumbs-up'></i> Distribuir Detalle</a>";
                      }
                      else
                      {
                        echo $revisor;
                      }
                     
              echo "</td></tr>";
          $ontador++;
    }
    
    echo "</tbody></table></div></div></div>";

      
} 
?>
</section>


<!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
          <div class="modal-dialog modal-md">
            <div class="modal-content">
                  <div class="modal-header success"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Seleccione el Revisor a Asignar</h4></div>
                  <div class="modal-body">
                   <div class="row">
                     <div class="col col-sm-2"><span>Revisor</span></div>
                     <div class="col col-sm-10">
                     <input type='hidden' id='fecha'   >
                     <input type='hidden' id='remesa_modal' value='<?php echo $_remesa; ?>'  >
                       <select class="form-control" id="slctincidencia"  required name='slctincidencia'><option>Seleccione uno</option>
                                  <?php 
                                      $query_incidencias=mysqli_query($conn, "SELECT id, nombre_completo from pani_usuarios where areas_id=9 and roles_usuarios_id>1 and id not in (99,37,79,66) order by nombre_completo asc");
                                      if ($query_incidencias==true)  {  while ($row_incidencia=mysqli_fetch_array($query_incidencias))   {  echo"<option value=".$row_incidencia['id'].">".$row_incidencia['nombre_completo']."</option>";   } }
                                      else { echo "<option value='99'>Valores no encontrados</option>";   }
                                   ?>
                          </select>
                     </div>
                   </div>            
                   
                  </div>
                  <div class="modal-footer"><button class="notas_docs btn btn-success" type="button" onclick="update_revisor()" >Generar y guardar</button><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button></div>
            </div>
          </div>
        </div>

</form>