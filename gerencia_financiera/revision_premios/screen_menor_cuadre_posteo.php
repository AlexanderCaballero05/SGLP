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
<div id="div_wait" class="div_wait">  </div> 
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Reporte de Remesas de Loteria Menor</h3> <br></section>
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
             	        $query_remesas=mysqli_query($conn, "SELECT remesa, ano_remesa FROM menor_pagos_detalle GROUP BY CONCAT(remesa, ano_remesa)  order by ano_remesa DESC, remesa desc ");
             	        while ($row_remesa=mysqli_fetch_array($query_remesas)) 
             	        {
             	        $rem=$row_remesa['remesa'];
					            echo "<option value='".$rem."/".$row_remesa['ano_remesa']."'> Remesa: ".$row_remesa['remesa']." | Año: ".$row_remesa['ano_remesa']." </option>";
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
    if ($_POST['slctremesa']>0 && $_POST['slctrevisor']>0 )
        {

          $parametros = explode('/',$_POST['slctremesa']);

          $_remesa = $parametros[0];
          $s_year = $parametros[1];
    
          $_revisor=$_POST['slctrevisor'];   
           $ano_remesa = $s_year; 
           //$_revisor = 38;   
   
           echo "<div  class='alert alert-success' align='center'><h4> Pago de Premios correspondientes a la Remesa No. ".$_remesa." del año ".$ano_remesa."</h4></div>";

           $query_sorteos=mysqli_query($conn, "SELECT  sorteo FROM menor_pagos_detalle where estado_revision=1 and remesa=$_remesa  and usuario_revision=$_revisor and ano_remesa = '$s_year' group by  sorteo;");

           if ($query_sorteos===false) { echo mysqli_error($conn); } 

            echo "<table class='table table-hover table-bordered table-sm'>         
                        <thead><tr><th>No.</th>
                                  <th>Descripción de Billetes Pagados</th>  
                                  <th>Cantidad</th>  
                                  <th>Total</th>                 
                              </tr></thead><tbody>"; 

              while ($row_sorteo=mysqli_fetch_array($query_sorteos)) 
              {   
                            $sorteo=$row_sorteo['sorteo'];  unset($array_numeros);  unset($array_series);
                            $query = mysqli_query($conn, "SELECT numero_premiado_menor FROM sorteos_menores_premios where sorteos_menores_id=$sorteo and premios_menores_id in(1,3);");

                            if ($query==false) { echo mysqli_error($conn); }

                            while($row=mysqli_fetch_array($query)) {   $array_numeros[] = $row['numero_premiado_menor'];}
                      
                            $query_series = mysqli_query($conn, "SELECT numero_premiado_menor FROM sorteos_menores_premios where sorteos_menores_id=$sorteo and (premios_menores_id =2 or premios_menores_id >3);");

                            if ($query_series==false) { echo mysqli_error($conn); }
                                
                            while($row_series=mysqli_fetch_array($query_series)) { $array_series[] = $row_series['numero_premiado_menor']; }
         
                                    echo "<tr class='table-info'><td colspan='4'>Sorteo: ".$sorteo."</td></tr>";   
                                  $query_numeros=mysqli_query($conn, "(SELECT a.numero numero, a.neto valor, COUNT(*) cantidad, SUM(a.neto) total_neto, 1 as vale, 'a' as 'orden'
                                    FROM menor_pagos_detalle a, menor_pagos_recibos b 
                                    WHERE 
                                    a.transactioncode=b.transactioncode and 
                                    a.remesa=$_remesa and
                                    a.usuario_revision=$_revisor and
                                    a.transactionstate in (1,3) and
                                    a.estado_revision in (1,3)  and
                                    a.sorteo=$sorteo and
                                    a.ano_remesa = '$s_year' and
                                    a.serie not in( ".implode(',',$array_series)." )  and
                                    a.numero in( ".implode(',',$array_numeros)." )
                                    GROUP BY a.numero 
                                    )UNION
                                    (SELECT a.serie serie,  a.neto valor, COUNT(a.serie) cantidad, SUM(a.neto) total_neto, 2 as vale, 'b' as 'orden' 
                                    FROM menor_pagos_detalle a, menor_pagos_recibos b 
                                    WHERE 
                                    a.transactioncode=b.transactioncode and
                                    a.transactionstate in (1,3) and    
                                    a.estado_revision in (1,3)  and
                                    a.remesa=$_remesa and
                                    a.ano_remesa = '$s_year' and
                                    a.usuario_revision=$_revisor and
                                    a.sorteo=$sorteo and
                                    a.serie in( ".implode(',',$array_series)." )  
                                    GROUP BY a.serie , valor  ) order by orden , valor desc  ;");

                                    if ($query_numeros==false){ echo mysqli_error($conn); }
                                              $total_acumulado=0;
                                              $impto_acumulado=0;
                                              $neto_acumulado=0;
                                              $cantidad_acumulado=0;
                                              $contador=1;

                                              while ($row_numeros=mysqli_fetch_array($query_numeros)) 
                                                {                                                  
                                                  //  $total_acumulado=$total_acumulado+$row_numeros['principal'];
                                                   // $impto_acumulado=$impto_acumulado+$row_numeros['impto'];
                                                  $cantidad_acumulado=$cantidad_acumulado+$row_numeros['cantidad'];
                                                  $neto_acumulado=$neto_acumulado+$row_numeros['total_neto'];
                                                  if (  in_array( $row_numeros['numero'],  $array_numeros)  ) 
                                                     {
                                                        $palabra='Numero';
                                                     }
                                                     else
                                                     {
                                                        $palabra='Serie';
                                                     }

                                                     if ($row_numeros['vale']=='1') { $palabra='Numero'; } else {  $palabra='Serie'; }                                  

                                                       echo "<tr><td align='center'>".$contador."</td>  
                                                                    <td align='center'>  ".$palabra."   ".$row_numeros['numero']." por L. ".$row_numeros['valor']."</td>  
                                                                    <td align='center'>".$row_numeros['cantidad']."</td>    
                                                                    <td align='right'>".number_format($row_numeros['total_neto'],2,'.',',')."</td></tr>";
                                                          $contador++;  
                                               }
                                                  echo "<tr><td colspan='2' align='center'><label>Total del sorteo  ".$sorteo."</label></td>
                                                            <td align='center'><label>".number_format($cantidad_acumulado)."</label></td>
                                                            <td align='right'><label>".number_format($neto_acumulado,2,'.',',')."</label></td></tr>"; 
                                              $cantidad_acumulado_final=$cantidad_acumulado_final+$cantidad_acumulado;
                                              $neto_acumulado_final=$neto_acumulado_final+$neto_acumulado;  
               }
        }

        
        echo "<tr><td colspan='4' align='center'><label> -- </label></td></tr>";
        echo "<tr><td colspan='4' align='center'><label> Liquidación Total de la Remesa</label></td></tr>";
        echo "<tr><td colspan='2' align='center'><label>Gran Total</label></td>
                  <td align='center'><label>".number_format($cantidad_acumulado_final)."</label></td>
                  <td align='right'><label>".number_format($neto_acumulado_final,2,'.',',')."</label></td>
              </tr></tbody></table><br><br>";
         echo "<div align='center'> <a class='btn btn-success'  href='_PDF_cuadre_remesa_revisor_menor.php?remesa=".$_remesa."&revisor=".$_revisor."&ano_remesa=".$ano_remesa."'  target='_blank' role='button'>
         <i class='far fa-save'></i> Imprimir Liquidación</a></div></div><br><br>";
        
 }
    $contador_sorteo2=0;
    while (isset($vector_sorteos[$contador_sorteo2])) 
    {
      //echo $vector_suma_sorteos[$contador_sorteo2]."--".$vector_sorteos[$contador_sorteo2]."<br>";
      
    echo "<input type='hidden' value='".$vector_suma_sorteos[$contador_sorteo2]."' name='valores_sorteo[]' >";
    echo "<input type='hidden' value='".$vector_sorteos[$contador_sorteo2]."' name='erp_sorteos[]' >";
    echo "<input type='hidden' value='".$vector_suma_sorteos_cantidad[$contador_sorteo2]."' name='erp_cantidad_sorteos[]' >";
$contador_sorteo2++;
    }

  ?>
                



</form>
