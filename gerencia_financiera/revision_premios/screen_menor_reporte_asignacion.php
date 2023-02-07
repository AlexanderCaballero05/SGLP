<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['usuario'] ; 
?>

  <script type="text/javascript">
$(document).ready(function ()
 {
      $("#slctremesa").change(function()
      {         
        var urr_agencia = "./_select_revisor_remesa.php?remesa=" + $( '#slctremesa' ).val() ;
        $("#slctrevisor").load(urr_agencia); 
      });
  });
     
</script>

<form method="post" id="_revision_premios" name="_revision_premios">
<div id='div_wait'></div>
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Reporte de Asignación de Lotería Menor</h3> <br></section>
 
<section>
<a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS  <i class="far fa-hand-point-down fa-lg"></i></a>
    <div class="collapse " id="collapse1" align="center">
      <div class="card">
        <div class="card card-body" id="non-printable" >
          <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
          <div class="input-group-prepend" style="margin-left: 5px;" ><span  class="input-group-text">Año de Remesa: </span></div>
                            <select name="slctanoremesa" id="slctanoremesa" class="form-control">
                             <option value='0'>Seleccione Uno</option>
                             <?php 
                               $result_ano_remesa=mysqli_query($conn, "SELECT ano_remesa FROM rp_asignacion_agencias_revisor_menor group by ano_remesa order by ano_remesa desc; ");
                               if (mysqli_num_rows($result_ano_remesa)>0)  {  while ($row_remesa = mysqli_fetch_array($result_ano_remesa))  { echo "<option value = '".$row_remesa['ano_remesa']."'>".$row_remesa['ano_remesa']."</option>";   }   
                             } 
                            ?>
                            </select>
            <div class="input-group-prepend" style="margin-left: 5px;" ><span  class="input-group-text">Remesa: </span></div>
                            <select name="slctremesa" id="slctremesa" class="form-control">
                             <option value='0'>Seleccione Uno</option>
                             <?php 
                               $result_remesa=mysqli_query($conn, " SELECT remesa FROM rp_asignacion_agencias_revisor_menor group by remesa order by remesa desc; ");
                               if (mysqli_num_rows($result_remesa)>0)  {  while ($row_remesa = mysqli_fetch_array($result_remesa))  { echo "<option value = '".$row_remesa['remesa']."'>".$row_remesa['remesa']."</option>";   }   
                             } 
                            ?>
                            </select>
            <div class="input-group-prepend" style="margin-left: 5px;" ><span  class="input-group-text">Revisor: </span></div>
                            <select name="slctrevisor" id="slctrevisor" class="form-control">
                             <option value='0'>Seleccione Uno</option>
                             <?php 
                               $result_remesa=mysqli_query($conn, "SELECT id, nombre_completo FROM pani_usuarios where areas_id=9 and roles_usuarios_id in (2,3 ) and id not in(66, 79, 100, 98, 37,99)order by id asc; ");
                               if (mysqli_num_rows($result_remesa)>0)  {  while ($row_remesa = mysqli_fetch_array($result_remesa))  { echo "<option value = '".$row_remesa['id']."'>".$row_remesa['nombre_completo']."</option>";   }   
                             } 
                            ?>
                            </select>

            <button type="submit" name="seleccionar" style="margin-left: 10px;" class="btn btn-primary" value = "Seleccionar">  Seleccionar &nbsp;<i class="fas fa-search fa-lg"></i></button>
          </div>
        </div>
      </div>
    </div> 
 </section>

<section>
<?php 
if (isset($_POST['seleccionar'])) 
{
   $cantidad_acumulado_final=0;   $neto_acumulado_final=0;

  if ($_POST['slctremesa']>0 and $_POST['slctrevisor']==0   )
     {
        $_remesa=$_POST['slctremesa']; 
        $ano_remesa=$_POST['slctanoremesa'];  
        $query_fechas=mysqli_query($conn, "SELECT date(transactiondate) fecha FROM rp_asignacion_agencias_revisor_menor where remesa=$_remesa and ano_remesa='$ano_remesa'group by date(transactiondate) order by fecha asc");

              echo "<div class='col-md-12'><hr><h3>Reporte de Lotería Menor Entregada Remesa No. ".$_remesa." - ".$ano_remesa."</h3>
                         <table id='tableinfo' align='center' class='table table-hover table-bordered table-sm'> 
                                 <thead><tr><td align='center'><label>No. </label></td>
                                            <td align='center'><label>Agencia</label></td>
                                            <td align='center'><label>Billetes</label></td>
                                            <td  align='center'><label>Neto</label></td>
                                            <td  align='center'><label>Revisor Asignado </label></td>
                                            <td  align='center'><label>Fecha Recepcion</label></td></tr></thead><tbody>";                       
                                            
                $cantidad_acumulado_final=0;  $neto_acumulado_final=0; 
                while ($row_fechas=  mysqli_fetch_array($query_fechas))
                {   
                    $_fecha_pago= $row_fechas['fecha'];  
                     echo "<tr class='table-secondary'><td colspan='6' align='center'><label> <i class='far fa-calendar-alt'></i> &nbsp;  ".$_fecha_pago."</label></td></tr>";
                    $query_agencias_fecha=mysqli_query($conn, "SELECT transactionagency, b.nombre seccional, c.nombre_completo usuario,  cant_numeros, netopayment, usuario_revision, fecha_recepcion 
                                                               FROM rp_asignacion_agencias_revisor_menor a, seccionales b, pani_usuarios c 
                                                               WHERE date(transactiondate)='$_fecha_pago' and remesa=$_remesa and a.usuario_revision=c.id and a.transactionagency=b.cod_seccional and b.id_empresa=3 order by fecha_recepcion asc");

                     $contador=1;    $cantidad_acumualdo_fecha=0;  $neto_acumulado_fecha=0;
                     while ($row_info = mysqli_fetch_array($query_agencias_fecha))                 
                     {
                         $revisor=$row_info['usuario']; $agencia=$row_info['seccional'];  $cantidad=$row_info['cant_numeros'];  $neto=$row_info['netopayment'];  $recepcion=$row_info['fecha_recepcion']; 
                       
                         echo "<tr><td align='center'><label>".$contador."</label></td>
                                   <td align='center'><label>".$agencia."</label></td>                         
                                   <td align='center'><label>".number_format($cantidad)."</label></td>
                                   <td align='right'><label>".number_format($neto,2,'.',',')."</label></td>
                                   <td align='center'><label>".$revisor."</label></td>
                                   <td align='center'><label>".$recepcion."</label></td></tr>";

                                   $cantidad_acumualdo_fecha=$cantidad_acumualdo_fecha+$cantidad;  $neto_acumulado_fecha= $neto_acumulado_fecha+$neto;
                                   $contador++;
                     }

                     echo     "<tr><td></td></tr><tr class='table-info'><td align='center' colspan='2'><label>Total por Fecha</label></td>                                                   
                                   <td align='center'><label>".number_format($cantidad_acumualdo_fecha)."</label></td>
                                   <td align='right'><label>".number_format($neto_acumulado_fecha,2,'.',',')."</label></td>
                                   <td align='center' colspan='2'></td></tr><tr><td></td></tr>"  ;

                                  $cantidad_acumulado_final=$cantidad_acumulado_final+$cantidad_acumualdo_fecha;  $neto_acumulado_final=$neto_acumulado_final+$neto_acumulado_fecha;
                }

                 echo        "<tr><td></td></tr>
                              <tr class='table-success'><td align='center' colspan='2'><label>Total de la Remesa</label></td>                                                   
                                   <td align='center'><label>".number_format($cantidad_acumulado_final)."</label></td>
                                   <td align='right'><label>".number_format($neto_acumulado_final,2,'.',',')."</label></td>
                                   <td align='center' colspan='2'></td></tr>"  ;
          echo "</tbody></table></div>                              
                <div class='row'>
                <div class='col-md-4'></div>
                <div class='col-md-4'>
                  <a class='btn btn-success btn-lg' role='button' style='width:100%;' href='./_PDF_asignacion_revisores_menor.php?remesa=".$_remesa."'  target='_blank' ><i class='fas fa-print'></i>  Imprimir Reporte</a>
                </div>
                <div class='col-md-4'></div>
                </div>";     
  }
  else if ( $_POST['slctremesa']>0 and $_POST['slctrevisor']>0  ) 
  {      
        $_revisor=$_POST['slctrevisor'];  $_remesa=$_POST['slctremesa'];  $ano_remesa=$_POST['slctanoremesa']; 
        $query_revisor_name=mysqli_query($conn, "SELECT nombre_completo from pani_usuarios where id=$_revisor");
        while ( $row_user_name =mysqli_fetch_array($query_revisor_name)) { $name_revisor=  $row_user_name['nombre_completo'];   }


       echo "<div class='col-md-12'><hr><h3>Reporte de Lotería Menor Entregada Remesa ".$_remesa." , Revisor ".$name_revisor." </h3>
                         <table id='tableinfo' align='center' class='table table-hover table-bordered table-sm'> 
                                 <thead><tr><td align='center'><label>No. </label></td>
                                            <td align='center'><label>Agencia</label></td>
                                            <td align='center'><label>Billetes</label></td>
                                            <td  align='center'><label>Total</label></td>
                                            <td  align='center'><label>Impto</label></td>
                                            <td  align='center'><label>Neto</label></td></tr></thead><tbody>"; 

        
       $query_fechas=mysqli_query($conn, "SELECT date(transactiondate) fecha FROM rp_asignacion_agencias_revisor_menor where remesa=$_remesa and usuario_revision=$_revisor and ano_remesa= $ano_remesa group by date(transactiondate) order by fecha asc");
       $cantidad_acumualdo_final=0;  $total_acumulado_final=0; $impto_acumulado_final=0;  $neto_acumulado_final=0;
        
       while ( $row_fecha= mysqli_fetch_array($query_fechas)) 
       {
         $_fecha_pp=$row_fecha['fecha'];          
          echo "<tr class='table-secondary'><td colspan='6' align='center'><i class='far fa-calendar-alt'></i> &nbsp;  ".$_fecha_pp."</td></tr>";

                       $query_info_revisor=mysqli_query($conn, "SELECT transactiondate, transactionagency, b.nombre, cant_numeros, totalpayment, imptopayment, netopayment 
                                                                FROM  rp_asignacion_agencias_revisor_menor a, seccionales b 
                                                                WHERE a.transactionagency=b.cod_seccional and remesa=$_remesa and usuario_revision=$_revisor and transactiondate='$_fecha_pp' and b.id_empresa=3 order by fecha_recepcion asc");

                      
                        $contador=0;  $cantidad_acumualdo=0;  $total_acumulado= 0; $impto_acumulado= 0; $neto_acumulado= 0;                                           
                        while ($row_info_revisor=mysqli_fetch_array($query_info_revisor)) 
                        {
                            $fecha_recepcion=$row_info_revisor['transactiondate'];
                            $agencia=$row_info_revisor['transactionagency'];
                            $agencia_name=$row_info_revisor['nombre'];
                            $cantidad_numeros=$row_info_revisor['cant_numeros'];
                            $totalpayment=$row_info_revisor['totalpayment'];
                            $imptopayment=$row_info_revisor['imptopayment'];
                            $netopayment=$row_info_revisor['netopayment'];

                             echo "<tr><td align='center'><label>".$contador."</label></td>
                                                   <td align='left'><label>".$agencia." -- ".$agencia_name."</label></td>                         
                                                   <td align='center'><label>".number_format($cantidad_numeros)."</label></td>
                                                   <td align='right'><label>".number_format($totalpayment,2,'.',',')."</label></td>
                                                   <td align='right'><label>".number_format($imptopayment,2,'.',',')."</label></td>
                                                   <td align='right'><label>".number_format($netopayment,2,'.',',')."</label></td></tr>"  ;

                                                   $cantidad_acumualdo=$cantidad_acumualdo+$cantidad_numeros;  $total_acumulado= $total_acumulado+$totalpayment; $impto_acumulado= $impto_acumulado+$imptopayment; $neto_acumulado= $neto_acumulado+$netopayment;
                                                   $contador++;
                        }

                        echo        "<tr><td></td></tr>
                                              <tr class='table-info'><td align='center' colspan='2'><label>Total de la Remesa</label></td>                                                   
                                                   <td align='center'><label>".number_format($cantidad_acumualdo)."</label></td>
                                                   <td align='right'><label>".number_format($total_acumulado,2,'.',',')."</label></td>
                                                   <td align='right'><label>".number_format($impto_acumulado,2,'.',',')."</label></td>
                                                   <td align='right'><label>".number_format($neto_acumulado,2,'.',',')."</label></td></tr>"  ;


                          $cantidad_acumualdo_final=$cantidad_acumualdo_final+$cantidad_acumualdo;  $total_acumulado_final= $total_acumulado_final+ $total_acumulado; $impto_acumulado_final= $impto_acumulado_final+$impto_acumulado; $neto_acumulado_final= $neto_acumulado_final+$neto_acumulado;
     }

      echo        "<tr><td></td></tr><tr><td colspan='6' align='center'><label> Total General</label></td></tr>
                                              <tr class='table-success'><td align='center' colspan='2'><label></label></td>                                                   
                                                   <td align='center'><label>".number_format($cantidad_acumualdo_final)."</label></td>
                                                   <td align='right'><label>".number_format($total_acumulado_final,2,'.',',')."</label></td>
                                                   <td align='right'><label>".number_format($impto_acumulado_final,2,'.',',')."</label></td>
                                                   <td align='right'><label>".number_format($neto_acumulado_final,2,'.',',')."</label></td></tr>"  ;

 echo "</tbody></table>
              </div><div class='row'>
                    <div class='col-md-4'></div>
                    <div class='col-md-4'>
                        <a class='btn btn-success btn-lg' style='width:100%;' href='./_PDF_asignacion_revisores_menor.php?remesa=".$_remesa."&revisor=".$_revisor."&year=".$ano_remesa."'  target='blank' role='button'><i class='fas fa-print'></i> Imprimir Reporte </a>                       
                    </div>
                    <div class='col-md-4'></div>
                  </div>";     

  }
  else  if (  $_POST['slctrevisor']>0  and $_POST['slctremesa']==0 ) 
  {
        $_revisor=$_POST['slctrevisor'];
        echo $_revisor;
  }
  else
  {
    echo "<div class='alert alert-danger'> Debe Ingresar Parametros</div>";
  }

}
       
  ?>
                

</section>


</form>


