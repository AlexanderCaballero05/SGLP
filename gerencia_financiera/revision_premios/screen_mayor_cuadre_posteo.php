<?php 
require('../../template/header.php'); 
$usuario_id= $_SESSION['id_usuario'];
$nombre_usuario=$_SESSION['nombre'];

?>
<title>PANI | REVISION DE PREMIOS LOTERIA MAYOR</title>     
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
<form method="post">
<div id="div_wait" class="div_wait">  </div> 
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Reporte de Remesas de Loteria Mayor</h3> <br></section>
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
             	        $query_remesas=mysqli_query($conn, "SELECT remesa, ano_remesa FROM mayor_pagos_detalle GROUP BY CONCAT(remesa, ano_remesa)  order by ano_remesa DESC, remesa desc ");
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
 <section>

      <?php 
if (isset($_POST['seleccionar'])) 
{
    $cantidad_acumulado_final=0;   $neto_acumulado_final=0;

    if ($_POST['slctremesa']>0 && $_POST['slctrevisor']>0 )
    {

      $parametros = explode('/',$_POST['slctremesa']);

			$_remesa = $parametros[0];
			$s_year = $parametros[1];

        $_revisor=$_POST['slctrevisor'];  
        //$_revisor=39;


      $query_sorteos=mysqli_query($conn, "SELECT  sorteo FROM mayor_pagos_detalle  where estado_revision=1 and remesa=$_remesa  and usuario_revision=$_revisor and ano_remesa = '$s_year'  group by  sorteo;");
      if ($query_sorteos===false) {echo mysqli_error($conn); }  


echo "<table class='table table-hover table-bordered table-sm' >         
                <thead><tr><th>No.</th>
                           <th>Descripción de Pliegos Pagados</th>  
                           <th>Cantidad de Decimos</th>  
                           <th>Total</th>                 
                       </tr><thead><tbody>";
      while ($row_sorteo=mysqli_fetch_array($query_sorteos)) 
      {             
         $sorteo=$row_sorteo['sorteo'];      
      
                        $query_numeros=mysqli_query($conn, "(SELECT a.totalpayment valor_premio , a.numero numero, a.tipo_premio tipo_premio , count(decimos) cantidad, sum(a.totalpayment) total, sum(a.imptopayment) total_impto, sum(a.netopayment) total_neto, sum(decimos) num_dec, sum(decimos) decimos,  (sum(a.totalpayment)/decimos)*10 pago_por_terminacion
                        FROM mayor_pagos_detalle a, mayor_pagos_recibos b
                        WHERE
                        a.transactioncode=b.transactioncode and
                        a.tipo_premio='U' and 
                        a.transactionstate in (1,3)  and
                        a.estado_revision in (1,3) and
                        a.sorteo=$sorteo and
                        a.remesa=$_remesa and 
                        ano_remesa = $s_year and
                        a.usuario_revision=$_revisor
                        group by a.numero order by  a.totalpayment desc
                        )UNION
                        (SELECT a.totalpayment valor_premio , a.numero numero, a.tipo_premio  tipo_premio, count(decimos) cantidad, sum(a.totalpayment) total, sum(a.imptopayment) total_impto, sum(a.netopayment) total_neto , a.decimos, sum(decimos) num_dec , (a.totalpayment/a.decimos )*10 pago_por_terminacion
                        FROM mayor_pagos_detalle a, mayor_pagos_recibos b
                        WHERE
                        a.transactioncode=b.transactioncode and
                        a.tipo_premio='T' and
                        a.transactionstate in (1,3)  and
                        a.estado_revision in (1,3) and
                        a.sorteo=$sorteo and
                        a.remesa=$_remesa and 
                        ano_remesa = $s_year and
                        a.usuario_revision=$_revisor
                        group by pago_por_terminacion order by pago_por_terminacion desc ) order by valor_premio desc ;");

    if ($query_numeros==false){ echo mysqli_error($conn); }
       $total_acumulado=0;
       $impto_acumulado=0;
       $neto_acumulado=0;
       $cantidad_acumulado=0;
       $contador=1;
        while ($row_numeros=mysqli_fetch_array($query_numeros)) 
        {        
           $cantidad_acumulado=$cantidad_acumulado+$row_numeros['decimos'];
           $neto_acumulado=$neto_acumulado+$row_numeros['total_neto'];
           $pago_por_decimo=  number_format($row_numeros['pago_por_terminacion'],2,'.',',');//($row_numeros['total']/$row_numeros['decimos']);

          if (   $row_numeros['tipo_premio']=='U'  ) 
          {
            $numero=$row_numeros['numero'];
            $palabra='Premio de Urna ';  
            $query_pago_termi=mysqli_query($conn, "SELECT numero_premiado_mayor, monto FROM `sorteos_mayores_premios` WHERE numero_premiado_mayor=$numero and sorteos_mayores_id=$sorteo limit 1 ");
            while ($_row_termi=mysqli_fetch_array($query_pago_termi))
            {
              $numero_termi=$_row_termi['numero_premiado_mayor'];
              $total_premio= number_format(($_row_termi['monto']/10),2,'.',',');   //($row_numeros['valor_premio']/ $pago_por_decimo);
            }
          }
          else
          {
            $palabra='Premio por Terminación'; 
            $query_pago_termi=mysqli_query($conn, "SELECT numero_premiado_mayor, monto FROM `sorteos_mayores_premios` WHERE respaldo='terminacion' and monto=$pago_por_decimo and sorteos_mayores_id=$sorteo;");
            while ($_row_termi=mysqli_fetch_array($query_pago_termi))
            {
              $numero_termi=$_row_termi['numero_premiado_mayor'];
              $total_premio= number_format(($_row_termi['monto']/10),2,'.',',');   //($row_numeros['valor_premio']/ $pago_por_decimo);
            }
          }

          echo "<tr>
                <td align='center'>".$contador."</td>  
                <td align='left'>  ".$palabra."   ".$numero_termi." por L. ".$pago_por_decimo." el billete y L.  ".$total_premio." el decimo</td>  
                <td align='center'>".$row_numeros['decimos']."</td>     
                <td align='right'>".number_format($row_numeros['total_neto'],2,'.',',')."</td> 
                </tr>";
          $contador++;      
          }
        echo "<tr class='table-info'><td colspan='2' align='center'>Total Sorteo ".$sorteo."</td>
            <td align='center'><label>".number_format($cantidad_acumulado)."</label></td>
            <td align='right'><label>".number_format($neto_acumulado,2,'.',',')."</label></td></tr>"; 
        

        $cantidad_acumulado_final=$cantidad_acumulado_final+$cantidad_acumulado;
        $neto_acumulado_final=$neto_acumulado_final+$neto_acumulado;
    }
  }

 
 
$query_info_revisado_notas=mysqli_query($conn, "SELECT mpd.fecha_revision fecha, notas.decimos_nota decimos, 
  sum(mpd.netopayment) neto_detalle, notas.neto_nota   neto
  FROM mayor_pagos_detalle mpd, mayor_pagos_recibos mpr, pani_usuarios paniusers, rp_notas_credito_debito_mayor notas
  WHERE 
  mpd.transactioncode=mpr.transactioncode and  notas.sorteo=mpd.sorteo and  notas.numero=mpd.numero and notas.ano_remesa = '$s_year' and date(mpd.transactiondate)>='2020-12-15' and
  notas.remesa=mpd.remesa and  mpd.transactionstate in(1,3) and   mpd.estado_revision in (1,2) and  mpd.remesa=$_remesa and mpd.usuario_revision=$usuario_id and  mpd.usuario_revision=paniusers.id and mpd.usuario_revision=notas.usuario group by mpr.transactionagencyname, mpr.transactionusername, date(mpd.transactiondate)  order by fecha asc;");

  if (mysqli_num_rows($query_info_revisado_notas)>0) 
  {
     while ($row_notas_aa=mysqli_fetch_array($query_info_revisado_notas))  
     {
        $cantidad_notas_cc=$row_notas_aa['decimos'];     
        $monto_notas_cc=$row_notas_aa['neto'];  
       echo "<tr><td colspan='2' align='center'><label>Notas Debito</label></td>
                  <td align='center'><label>".$cantidad_notas_cc."</label></td>
                  <td align='right'><label>".number_format($monto_notas_cc,2,'.',',')."</label></td></tr>";       
     }

     $cantidad_acumulado_final=$cantidad_acumulado_final-$cantidad_notas_cc;
     $neto_acumulado_final=$neto_acumulado_final-$monto_notas_cc;

  } else  {  $cantidad_notas_cc=0; $monto_notas_cc=0;  }

      
        echo "<tr><td colspan='4' align='center'><label> -- </label></td></tr>";
        echo "<tr><td colspan='4' align='center'><label> Liquidación Total de la Remesa</label></td></tr>";
        echo "<tr class='table-success'><td colspan='2' align='center'><label>Gran Total</label></td>
                  <td align='center'><label>".number_format($cantidad_acumulado_final)."</label></td>
                  <td align='right'><label>".number_format($neto_acumulado_final,2,'.',',')."</label></td></tr></table>";
         
echo "<div align='center'> <a class='btn btn-success'  href='_PDF_cuadre_remesa_revisor_mayor.php?remesa=".$_remesa."&revisor=".$_revisor."&year=".$s_year."'  target='_blank' role='button'>
         <span class='glyphicon glyphicon-save' aria-hidden='true'></span> Imprimir Liquidación</a></div></div>";
   


     
 }
    

  ?>
</section>
</form>

