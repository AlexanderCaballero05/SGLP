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
<div id="div_wait" class="div_wait">  </div><br> 
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Remision de Remesa a Boveda de Loteria Mayor</h3> <br></section>
<section>
<a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS  <i class="far fa-hand-point-down fa-lg"></i></a>
    <div class="collapse " id="collapse1" align="center">
      <div class="card">
        <div class="card card-body" id="non-printable" >
          <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
            <div class = "input-group-prepend"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i> &nbsp;  Remesa: </span></div>
                <select name="slctremesa" id="slctremesa"  class="form-control">
                     <option value='0'>Seleccione Uno</option>
                     <?php 
                       $result_remesa=mysqli_query($conn, "SELECT remesa, ano_remesa  FROM mayor_pagos_detalle  GROUP BY CONCAT(remesa, ano_remesa)  order by ano_remesa DESC, remesa desc; ");
                       if (mysqli_num_rows($result_remesa)>0)
                       {
                          while ($row_remesa = mysqli_fetch_array($result_remesa))
                         {
                          $rem=$row_remesa['remesa'];
                           echo "<option value='".$rem."/".$row_remesa['ano_remesa']."'> Remesa: ".$row_remesa['remesa']." | Año: ".$row_remesa['ano_remesa']." </option>";
                         }
                       } 
                    ?>
                </select>           
            <button id="buttonConsulta" name="seleccionar" type="submit" class="Consulta btn btn-primary">BUSQUEDA DE BILLETES PAGADOS</button>
          </div>
        </div>
      </div>
    </div> 
    <input type="hidden" name="slctrevisor" value="<?php echo $usuario_id  ?> ">
 </section>
<hr>


<?php 
if (isset($_POST['seleccionar'])) 
{
   $cantidad_acumulado_final=0;    $neto_acumulado_final=0;
    if ($_POST['slctremesa']>0 )
    {


      $parametros = explode('/',$_POST['slctremesa']);

			$_remesa = $parametros[0];
			$s_year = $parametros[1];


      $querys_revisores=mysqli_query($conn, "SELECT a.usuario_revision, b.nombre_completo , sum(a.decimos) conteo, sum(a.totalpayment) total , sum(a.imptopayment) impto , sum(a.netopayment) neto FROM mayor_pagos_detalle a, pani_usuarios b WHERE remesa=$_remesa and estado_revision in (1) and transactionstate in (1,3)  and a.usuario_revision = b.id and ano_remesa = '$s_year'  group by usuario_revision order by usuario_revision asc");
      $contador_sorteo=0; 

      echo "<table class='table table-sm table-bordered table-hover'>
            <thead><tr><td align='center'><label>Revisor</label></td>
                       <td align='center'><label>Paquetes</label></td>
                       <td align='center'><label>Decimos</label></td>
                       <td align='center'><label>Total</label></td>
                       <td align='center'><label>Impto</label></td>
                       <td align='center'><label>Neto</label></td>
                       </tr></thead><tbody>";

            $total_cantidad_revisor=0; $total_total_revisor=0;  $total_impto_revisor=0;  $total_neto_revisor=0; $cantida_paquetes=1; $total_paquetes=0;
            while ( $row_revisores=mysqli_fetch_array($querys_revisores)) 
            {       
                     $usuario_revision= $row_revisores['usuario_revision'];
             
                     $query_debitos= mysqli_query($conn, "SELECT sum(decimos_nota) decimos , neto_nota neto FROM rp_notas_credito_debito_mayor WHERE remesa= $_remesa and ano_remesa = '$s_year' and usuario=$usuario_revision");
                      if ($query_debitos) {
                        while ($row_nota= mysqli_fetch_array($query_debitos)) {
                           $decimos_nota =$row_nota['decimos'];
                           $neto_nota =$row_nota['neto'];

                        }
                      }
                      else
                      {
                        echo mysqli_error($conn);
                      }

                 echo "<tr><td>".$row_revisores['nombre_completo']."</td>
                           <td align='center'>".$cantida_paquetes."</td>
                           <td align='center'>    ".number_format(($row_revisores['conteo']- $decimos_nota))."</td>                                  
                           <td align='right' > L. ".number_format(($row_revisores['total'] - $neto_nota),2,'.',',')."</td>
                           <td align='right' > L. ".number_format(($row_revisores['impto']),2,'.',',')."</td>
                           <td align='right' > L. <label>".number_format(($row_revisores['neto'] - $neto_nota),2,'.',',')."</label></td></tr>";

               $total_paquetes=$cantida_paquetes+$total_paquetes;
               $total_cantidad_revisor=$total_cantidad_revisor+$row_revisores['conteo']-$decimos_nota;
               $total_total_revisor=$total_total_revisor+$row_revisores['total']-$neto_nota;
               $total_impto_revisor=$total_impto_revisor+$row_revisores['impto'];
               $total_neto_revisor=$total_neto_revisor+$row_revisores['neto']-$neto_nota;
            }

            


            echo "</tbody>
            <tr><td colspan='6' align='center'> -- </td></tr>
            <tr><td align='center'> Total Entregado </td>
                <td align='center'>".number_format($total_paquetes)."</td>
                <td align='center'>".number_format($total_cantidad_revisor)."</td>
                <td align='right'> L. ".number_format($total_total_revisor,2,'.',',')."</td>
                <td align='right'> L. ".number_format($total_impto_revisor,2,'.',',')."</td>
                <td align='right'> L. <label>".number_format($total_neto_revisor,2,'.',',')."</label></td></tr>
            </table>";  

             echo "<div align='center'><a class='btn btn-success'  href='_PDF_rp_remision_boveda_mayor.php?remesa=".$_remesa."&year=".$s_year."'  target='_blank' role='button'>
         <span class='glyphicon glyphicon-save' aria-hidden='true'></span> Imprimir Remision a Boveda</a></div>";
   
    

    }   
 }
  ?>
                



</form>
