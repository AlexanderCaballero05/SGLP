<?php 
  require('../../template/header.php'); 
  $usuario_id=$_SESSION['id_usuario'];
  $sorteo=$_GET['sorteo'];
  $user_name=$_SESSION['nombre'];
  $user_text=$_SESSION['id_usuario'];
  $user_id=$_SESSION['usuario'];

    $dias = array("DOMINGO","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
                $dia=$dias[date("w")];
                $meses= array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio", "Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                $meso=$meses[date("m")-1];
                $ano=date("Y");
                $diadate=date("d");

       echo $dia." ".$diadate." de ".$meso." del ".$ano;
 
?>

<form method="post"  id="_revision_premios" name="_revision_premios">
<div class="Section1">
 <table align="center">      
      <tr><td  colspan="3"> . </td></tr>
      <tr>
          <td width="20%"></td>
          <td  width="60%"  style="font-family: Arial; font-size: 12pt;"><div align="center"><label >Patronato Nacional de la Infancia PANI <br> Departamento de Revisión de Premios<br>Nota de Crédito de Lotería Mayor <br> Correspondiente al Sorteo <?php echo $sorteo; ?></label></div></td>
          <td width="20%"></td>
      </tr>      
 </table><br><br>


<?php      

  $cantidad_acumulado_final=0;  $neto_acumulado_final=0;
  
    $query_sorteos=mysqli_query($conn, "SELECT  sorteo FROM mayor_pagos_detalle   where estado_revision=1 and sorteo=$sorteo  group by  sorteo;");     if ($query_sorteos===false) {echo mysqli_error($conn); }  

    while ($row_sorteo=mysqli_fetch_array($query_sorteos)) 
    {
          $sorteo=$row_sorteo['sorteo'];

          echo "<!-- table width='95%'  id='tableinfo' align='center' class='table table-hover table-bordered'>         
                 <thead><th>No.</th>
                        <th>Descripción de Pliegos Pagados</th>  
                        <th>Cantidad de Decimos</th>  
                        <th>Total</th></thead><tbody -->";
                
              $query_numeros=mysqli_query($conn, "(SELECT a.totalpayment valor_premio , a.numero numero, a.tipo_premio tipo_premio , count(decimos) cantidad, sum(a.totalpayment) total, sum(a.imptopayment) total_impto, sum(a.netopayment) total_neto, sum(decimos) num_dec, decimos,  (sum(a.totalpayment)/sum(decimos))*10 pago_por_terminacion
              FROM mayor_pagos_detalle a, mayor_pagos_recibos b
              WHERE
              a.transactioncode=b.transactioncode and
              a.tipo_premio='U' and 
              a.transactionstate in (1,3)  and
              a.estado_revision in (1,3) and
              a.sorteo=$sorteo 
              group by a.numero order by  a.totalpayment desc
              )UNION
              (SELECT a.totalpayment valor_premio , a.numero numero, a.tipo_premio  tipo_premio, count(decimos) cantidad, sum(a.totalpayment) total, sum(a.imptopayment) total_impto, sum(a.netopayment) total_neto , a.decimos, sum(decimos) num_dec , (a.totalpayment/a.decimos )*10 pago_por_terminacion
              FROM mayor_pagos_detalle a, mayor_pagos_recibos b
              WHERE
              a.transactioncode=b.transactioncode and
              a.tipo_premio='T' and
              a.transactionstate in (1,3)  and
              a.estado_revision in (1,3) and
              a.sorteo=$sorteo
              group by pago_por_terminacion order by pago_por_terminacion desc ) order by valor_premio desc ;");

         if ($query_numeros==false){ echo "error en la pcpal".mysqli_error($conn); }
         $total_acumulado=0;  $impto_acumulado=0;   $neto_acumulado=0;    $cantidad_acumulado=0;   $contador=1; $numero_termi=0; $pago_por_decimo=0; $total_premio=0;
        while ($row_numeros=mysqli_fetch_array($query_numeros)) 
        {       
                 $cantidad_acumulado=$cantidad_acumulado+$row_numeros['decimos'];
                 $neto_acumulado=$neto_acumulado+$row_numeros['total_neto'];
                 $pago_por_decimo=  number_format($row_numeros['pago_por_terminacion'],2,'.',',');//($row_numeros['total']/$row_numeros['decimos']);
                 $numero_termi=0;  $total_premio=0;

                if ( $row_numeros['tipo_premio']=='U' ) 
                {

                  $cantidad_billetes=$row_numeros['num_dec'];
                  $numero=$row_numeros['numero'];  $palabra='Premio de Urna ';  
                  $query_pago_urna=mysqli_query($conn, "SELECT a.numero_premiado_mayor, b.total monto FROM `sorteos_mayores_premios` a, archivo_pagos_mayor b WHERE b.sorteo=a.sorteos_mayores_id and a.numero_premiado_mayor=b.numero and  a.numero_premiado_mayor=$numero and a.sorteos_mayores_id=$sorteo  ");
                  while ($_row_urna=mysqli_fetch_array($query_pago_urna))  {     $numero_termi=$_row_urna['numero_premiado_mayor'];     $total_premio= number_format(($_row_urna['monto']/10),2,'.',',');   
                  }
                }
                else
                {
                  $palabra='Premio por Terminación'; 
                  $cantidad_billetes=$row_numeros['decimos'];
                  $query_pago_termi=mysqli_query($conn, "SELECT numero_premiado_mayor, monto FROM `sorteos_mayores_premios` WHERE respaldo='terminacion' and monto=$pago_por_decimo and sorteos_mayores_id=$sorteo ;");
                  while ($_row_termi=mysqli_fetch_array($query_pago_termi))
                  {
                    $numero_termi=$_row_termi['numero_premiado_mayor'];   $total_premio= number_format(($_row_termi['monto']/10),2,'.',',');  
                  }
                }
                echo "<!-- tr><td align='center'>".$contador."</td>  
                          <td align='left'>  ".$palabra."   ".$numero_termi." por L. ".$pago_por_decimo." el billete y L.  ".$total_premio." el decimo</td>  
                          <td align='center'>".$cantidad_billetes."</td>     
                          <td align='right'>".number_format($row_numeros['total_neto'],2,'.',',')."</td></tr -->";
                $contador++;      
       }

        echo "<!-- /tbody><tr><td colspan='2' align='center'><label>Total</label></td>
                          <td align='center'><label>".number_format($cantidad_acumulado)."</label></td>
                          <td align='right'><label>".number_format($neto_acumulado,2,'.',',')."</label></td></tr --> ";

          if ($sorteo==1190) {
                                echo  "<!--- tr><td colspan='2' align='center'><label>Valor por pago incompleto</label></td>
                                           <td align='center'><label>".number_format(5)."</label></td>
                                           <td align='right'><label>".number_format(50,2,'.',',')."</label></td></tr>
                                       <tr><td colspan='2' align='center'><label>Total</label></td -->";

                                $cantidad_acumulado=$cantidad_acumulado-5; $neto_acumulado=$neto_acumulado-50;

                                echo "<!-- td align='center'><label>".number_format($cantidad_acumulado)."</label></td>
                                      <td align='right'><label>".number_format($neto_acumulado,2,'.',',')."</label></td></tr></table></div></div -->"; 
          }

                             $cantidad_acumulado_final=$cantidad_acumulado_final+$cantidad_acumulado;    $neto_acumulado_final=$neto_acumulado_final+$neto_acumulado;
    }
        
                    if ($sorteo==1190) {  
                        echo "<!-- div class='alert alert-danger'>Nota Explicativa: Para la remesa número 6 del sorteo 1190, se reporto un pago en el cual  el reporte impreso por el banco y en el sistema reflejaba un pago de L. 100.00 (cien)  con 10 (diez) decimos, pero al abrir el paquete y revisar físicamente los billetes solo aparecían 5 (cinco) , al momento del posteo en el sistema no permitía el cambio de los valores registrados por los pagos, debido a que no había sido un caso previsto durante la implementación y para mantener la atomocidad de la información en la base de datos.</div -->"; } # code...

                      $query_info_revisado_notas=mysqli_query($conn, "SELECT mpd.fecha_revision fecha, notas.decimos_nota decimos, 
                      sum(mpd.netopayment) neto_detalle, sum(mpd.netopayment)-notas.neto   neto
                      FROM mayor_pagos_detalle mpd, mayor_pagos_recibos mpr, seccionales sec, banrural_usuarios users, pani_usuarios paniusers, rp_notas_credito_debito_mayor notas
                      where 
                      mpd.transactioncode=mpr.transactioncode and
                      mpr.transactionagency=sec.cod_seccional and
                      notas.sorteo=mpd.sorteo and
                      notas.numero=mpd.numero and
                      notas.remesa=mpd.remesa and
                      sec.id_empresa=3 and
                      mpd.transactionstate in(1,3) and 
                      mpd.estado_revision in (1,2) and 
                      mpr.transactionuser=users.id and
                      mpd.usuario_revision=paniusers.id and
                      mpd.sorteo=$sorteo 
                      group by sec.nombre, users.codigo_empleado, date(mpd.transactiondate)  order by fecha asc;");

                      if (mysqli_num_rows($query_info_revisado_notas)>0 ) {
                         while ($row_notas_aa=mysqli_fetch_array($query_info_revisado_notas))  
                         {
                            $cantidad_notas_cc=$row_notas_aa['decimos'];  $monto_notas_cc=$row_notas_aa['neto'];

                            echo "<!-- table width='95%'  id='tableinfo_2' align='center' class='table table-hover table-bordered'  >
                                  <tr><td colspan='1' align='center'><label>Notas Debito</label></td>
                                      <td align='center'><label>".$cantidad_notas_cc."</label></td>
                                      <td align='right'><label>".number_format($monto_notas_cc,2,'.',',')."</label></td></tr></table -->";       
                         }
                       }  else  { 
                        
                        // PARCHE APLICADO EL 06/DICIEMBRE DEL 2022 /* NO RESTABA EL VALOR DE NOTAS DE CREDITO PREVIO A ACTUALIZACION DE CODIGO */
                        $c_notas_credito = mysqli_query($conn, "SELECT SUM(neto_nota) as neto_nota_credito FROM rp_notas_credito_debito_mayor WHERE sorteo = '$sorteo' AND incidencia IN ('1','2','3','4') AND tipo_documento = '4' AND state = '1' ");

                        if (mysqli_num_rows($c_notas_credito) > 0) {

                          $ob_notas_credito = mysqli_fetch_object($c_notas_credito);

                          $cantidad_notas_cc=0;
                          $monto_notas_cc= $ob_notas_credito->neto_nota_credito;


                        }else{

                          $cantidad_notas_cc=0;    $monto_notas_cc=0;    

                        }


                      }

                  //     if ($sorteo==1193) {  $monto_notas_cc=50; }

                //       if ($sorteo==1207) {  $monto_notas_cc=10; }

              //         if ($sorteo==1213) {  $monto_notas_cc=45; }

            //           if ($sorteo==1214) {  $monto_notas_cc=80; }

          //             if ($sorteo==1215) {  $monto_notas_cc=125; }

        //               if ($sorteo==1220) {  $monto_notas_cc=50; }

      //                 if ($sorteo==1223) {  $monto_notas_cc=60; }

    //                   if ($sorteo==1225) {  $monto_notas_cc=75; }
		
		//                   if ($sorteo==1227) {  $monto_notas_cc=10; }
     
    //                   if ($sorteo==1236) {  $monto_notas_cc=110; }
                       
		//                   if ($sorteo==1245) {  $monto_notas_cc=150; }
		
		//	                 if ($sorteo==1248) { $monto_notas_cc=68; }
		
		//	                 if ($sorteo==1249) {  $monto_notas_cc=120; }

      
echo "<!-- table width='95%' id='tableinfo2' align='center' class='table table-hover table-bordered'>
       <tr><td colspan='4' align='center'><label> -- </label></td></tr> 
       <tr><td colspan='4' align='center'><label> Liquidación Total de la Remesa</label></td></tr> 
       <tr><td colspan='1' align='center'><label>Gran Total</label></td>
           <td align='center'><label>".number_format($cantidad_acumulado_final-$cantidad_notas_cc)."</label></td>
           <td align='right'><label>".number_format($neto_acumulado_final-$monto_notas_cc,2,'.',',')."</label></td></tr></table -->";
         
 ?>

<table class='table table-bordered'>
      <tr>
        <td><label>A: BANRURAL</label></td>

        <td><label>FECHA: <?php echo $dia." ".$diadate." de ".$meso." del ".$ano ?> </label></td>
      </tr>  
      <tr>
        <td><label>CONCEPTO: Valor Recibido en Premios de Loteria Mayor</label></td>
        <td align="right"><label>  L.  <?php echo number_format($neto_acumulado_final-$monto_notas_cc,2,'.',',') ?> </label></td>    
      </tr>
</table>
 
<?php  
 echo "<br><br><br> <p align='center'>_______________________________________</p><p align='center'>".$user_name."</p><p align='center'>Supervision de Revisión de Premios</p><div>";
?>                 
          
<script type="text/javascript">
   window.print(); 
   setTimeout(window.close, 1000) ;
</script>   
    </form> 
    
</body>
