<?php 
  require('../../template/header.php'); 
  $usuario_id=$_SESSION['id_usuario'];
  $remesa=$_GET['remesa'];
  $year=$_GET['year'];
  $user_name=$_SESSION['nombre'];
  $user_text=$_SESSION['id_usuario'];
  $user_id=$_SESSION['usuario'];

    $dias = array("DOMINGO","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
                $dia=$dias[date("w")];
                $meses= array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio", "Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                $meso=$meses[date("m")-1];
                $ano=date("Y");
                $diadate=date("d");

    //   echo $dia." ".$diadate." de ".$meso." del ".$ano;
 
?>

<form method="post"  id="_revision_premios" name="_revision_premios">
<div class="Section1">
 <table align="center"> 
      <tr><td class="text-center" colspan="3"><img src="../../template/images/PANI_1.jpg" class="img-fluid"> </td></tr>
      <tr><td width="20%"></td>
          <td  width="60%"  style="font-family: Arial; font-size:21pt;">
            <div align="center">
              <label> 
                <strong>Patronato Nacional de la Infancia PANI <br> Departamento de Revisión de Premios<br>Nota de Crédito de Lotería Menor <br> Correspondiente a la Remesa <?php echo $remesa; ?></strong>
              </label>
            </div>
          </td>
          <td width="20%"></td>
      </tr>      
 </table><br><br>
<?php  
$_remesa= $remesa;  $cantidad_acumulado_final=0;   $neto_acumulado_final=0;   $contador_sorteo=0; 
$query_sorteos= mysqli_query($conn, "SELECT  a.sorteo, b.fecha_sorteo FROM menor_pagos_detalle a , sorteos_menores b  WHERE a.sorteo=b.id AND  a.estado_revision=1 AND a.remesa=$_remesa AND  ano_remesa = $year GROUP BY  sorteo;");

$query_sorteos2=mysqli_query($conn, "SELECT  a.sorteo, b.fecha_sorteo FROM menor_pagos_detalle a , sorteos_menores b  WHERE a.sorteo=b.id AND  a.estado_revision=1 AND a.remesa=$_remesa AND  ano_remesa = $year GROUP BY  sorteo;");

$query_sorteos3=mysqli_query($conn, "SELECT  a.sorteo, b.fecha_sorteo FROM menor_pagos_detalle a , sorteos_menores b  WHERE a.sorteo=b.id AND  a.estado_revision=1 AND a.remesa=$_remesa AND  ano_remesa = $year GROUP BY  sorteo;");    


$query_entregado=mysqli_query($conn, "SELECT  COUNT(*) cantidad, SUM(a.neto) total_neto
                                      FROM menor_pagos_detalle a, menor_pagos_recibos b 
                                      WHERE  a.transactioncode=b.transactioncode and  a.remesa=$_remesa and a.transactionstate in (1,3) and  ano_remesa = $year and date(a.transactiondate)>='2021-11-30' and ( a.estado_revision in (0,1,2) or a.estado_revision is null ) GROUP BY remesa=$_remesa");


                  if (mysqli_num_rows($query_entregado)>0) 
                  {
                      while ( $_rowentregado=mysqli_fetch_array($query_entregado) ) 
                      { 
                         $_cantidad_entregada_total=$_rowentregado['cantidad']; $_monto_entregado_total=$_rowentregado['total_neto']; 

                        
                      }
                  }


                   while ($row_sorteo=mysqli_fetch_array($query_sorteos)) 
                    {     
                            $sorteo=$row_sorteo['sorteo']; $fecha_sorteo=$row_sorteo['fecha_sorteo'];
                            $query = mysqli_query($conn, "SELECT numero_premiado_menor FROM sorteos_menores_premios where sorteos_menores_id=$sorteo and premios_menores_id in(1,3);");
                            if ($query==false) { echo mysqli_error($conn); }

                            while($row=mysqli_fetch_array($query))  {   $array_numeros[] = $row['numero_premiado_menor']; }
                         
                            $query_series = mysqli_query($conn, "SELECT numero_premiado_menor FROM sorteos_menores_premios where sorteos_menores_id=$sorteo and (premios_menores_id =2 or premios_menores_id >3);");

                                 if ($query_series==false) { echo mysqli_error($conn); }
                                 while($row_series=mysqli_fetch_array($query_series))  { $array_series[] = $row_series['numero_premiado_menor']; }
                           
                                     $query_numeros=mysqli_query($conn, "(SELECT a.numero numero, a.neto valor, COUNT(*) cantidad, SUM(a.neto) total_neto, 'a' as 'orden'
                                      FROM menor_pagos_detalle a, menor_pagos_recibos b 
                                      WHERE 
                                      a.transactioncode=b.transactioncode and  a.remesa=$_remesa and a.transactionstate in (1,3) and  a.estado_revision in (1,1)   and  ano_remesa = $year and
                                      a.sorteo=$sorteo and a.serie not in( ".implode(',',$array_series)." )  and  a.numero in( ".implode(',',$array_numeros)." )
                                      GROUP BY a.numero  
                                      )UNION
                                      (SELECT a.serie serie,  a.neto valor, COUNT(a.serie) cantidad, SUM(a.neto) total_neto, 'b' as 'orden'
                                      FROM menor_pagos_detalle a, menor_pagos_recibos b 
                                      WHERE 
                                      a.transactioncode=b.transactioncode and a.transactionstate in (1,3) and  a.remesa=$_remesa and  a.estado_revision in (1,1)  and  ano_remesa = $year and
                                      a.sorteo=$sorteo  and  a.serie in( ".implode(',',$array_series)." )  
                                      GROUP BY a.serie , valor   ) order by orden , valor desc ;");

                                  if ($query_numeros==false){ echo mysqli_error($conn); }

                                      $total_acumulado=0; $impto_acumulado=0;  $neto_acumulado=0;  $cantidad_acumulado=0;  $contador=1;                            
                                      while ($row_numeros=mysqli_fetch_array($query_numeros)) 
                                      {                                               

                                            if (  in_array( $row_numeros['numero'],  $array_numeros)  )  { $palabra='Numero';  } else {  $palabra='Serie'; }     
                                              $cantidad_acumulado=$cantidad_acumulado+$row_numeros['cantidad'];    $neto_acumulado=$neto_acumulado+$row_numeros['total_neto'];
                                              $contador++;      
                                      }
                                            
                                              $cantidad_acumulado_final=$cantidad_acumulado_final+$cantidad_acumulado;   $neto_acumulado_final=$neto_acumulado_final+$neto_acumulado;
                                              unset($array_series);  unset($array_numeros);
                     }


                     unset($sorteo); unset($fecha_sorteo);
                      if (mysqli_num_rows($query_sorteos2)>0) 
                      {
                           
                            $contador_notas=0; $neto_acumulado_notas=0;
                            while ($row_sorteo2=mysqli_fetch_array($query_sorteos2) )
                            {
                              $sorteo=$row_sorteo2['sorteo']; $fecha_sorteo=$row_sorteo2['fecha_sorteo'];
                              $query_notas= mysqli_query($conn, "SELECT numero, serie, neto, incidencia, tipo_documento FROM rp_notas_credito_debito_menor where remesa=$_remesa and sorteo=$sorteo"); 
                              if (mysqli_num_rows($query_notas)>0) 
                               { 
                                  
                                  while ($_row_notas=mysqli_fetch_array($query_notas)) 
                                  {
                                      $numero=$_row_notas['numero'];   $serie=$_row_notas['serie'];   $neto=$_row_notas['neto']; $incidencia=$_row_notas['incidencia']; $tipo_documento=$_row_notas['tipo_documento'];
                                      

                                      if ($incidencia<>4) 
                                      {
                                         if ($tipo_documento<>2) 
                                        {
                                        $neto_acumulado_notas= $neto_acumulado_notas+$neto;
                                        $contador_notas ++;
                        }

                                      }
 
                                      
                                  }                                                
                               } 
                            }
                            
                      }  

               unset($sorteo); unset($fecha_sorteo);
              if (mysqli_num_rows($query_sorteos3)>0) 
              {
                   
                    while ($row_sorteo3=mysqli_fetch_array($query_sorteos3) )
                    {
                              $sorteo=$row_sorteo3['sorteo']; $fecha_sorteo=$row_sorteo3['fecha_sorteo']; $neto_acumulado_faltante=0;   $contador_faltante=0;
                              $query_faltantes= mysqli_query($conn, "SELECT numero, serie, netopayment neto, registertype FROM rp_faltantes_sobrantes_menor where remesa=$_remesa and sorteo=$sorteo"); 
                              if (mysqli_num_rows($query_faltantes)>0) 
                               { 
                                    
                                      $contador_faltante=0;
                                      while ($_row_notas3=mysqli_fetch_array($query_faltantes)) 
                                      {
                                                       $numero=$_row_notas3['numero'];  $serie=$_row_notas3['serie'];  $neto=$_row_notas3['neto']; $tipo=$_row_notas3['registertype'];
                                                     ; 
                                                        $neto_acumulado_faltante=$neto_acumulado_faltante+$neto;   $contador_faltante ++;  
                                      }
                                        
                                                      
                                      
                                   //   if ($tipo=='Sobrante') {  $contador_faltante=0;    $neto_acumulado_faltante=0;         }
                                       

                                       $contador_faltante=0;    $neto_acumulado_faltante=0;                 
                               } 
                              else {   echo mysqli_error($conn);  }
                    }
              }

               $cantidad_acumulado_final   = $_cantidad_entregada_total - $contador_faltante-$contador_notas;
               $neto_acumulado_final_total = $_monto_entregado_total    - $neto_acumulado_faltante-$neto_acumulado_notas;


  $formatterES  = new NumberFormatter("es", NumberFormatter::SPELLOUT); 
  $monto_letras = $formatterES->format($neto_acumulado_final_total); 
  $monto_letras = ltrim(rtrim($monto_letras)); 
?>       
<div class="row">
  <div class="col-sm-1"></div>

  <div class="col-sm-5 text-left" style="font-size:18pt;">
    <strong>A: BANRURAL</strong>
  </div>

  <div class="col-sm-5 text-right" style="font-size:18pt;">
    <strong>Fecha :  <?php echo $dia." ".$diadate." de ".$meso." del ".$ano; ?> </strong>
  </div>

  <div class="col-sm-1"></div>
</div>
<div class="row mt-5">
  <div class="col-sm-1"></div>
  <div class="col-sm-10 text-left">
    <h3>Sirvase tomar nota que hemos abonado a su apreciable cuenta :</h3>
  </div>
  <div class="col-sm-1"></div>
</div>

<div class="row mt-3">
  <div class="col-sm-1"></div>
  <div class="col-sm-10 text-center">
    <table class="table table-bordered" style=" font-size:18pt;">
       <tbody>
         <tr style=" font-size:18pt;">

           <td style="width:75%;"> <p class="text-justify" style=" font-size:18pt;"> La cantidad de L. <?php echo number_format($neto_acumulado_final_total,2,'.',',') ."  (".$monto_letras.") Valor recibido en concepto de la remesa no. ".$remesa."-".$ano." quedando sujeta a revisión" ; ?></p></td>
           <td class="text-right" style="width:25%; font-size:18pt;"> L.  <?php echo number_format($neto_acumulado_final_total,2,'.',','); ?> </td>
         </tr>
       </tbody>
    </table>
  </div>
  <div class="col-sm-1"></div>
</div>

<br><br><br> <p align='center'  style=" font-size:18pt;">_______________________________________</br> <?php echo $user_name  ?></br>Jefatura de Revisión de Premios</p><div>          
          
        <script type="text/javascript">
           window.print(); 
          setTimeout(window.close, 1000) ;
        </script>   
</form> 
    
</body>
