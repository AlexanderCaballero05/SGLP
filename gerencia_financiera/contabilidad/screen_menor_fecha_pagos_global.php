<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['id_usuario'];
 function diferenciaDias($inicio, $fin)
    {
        $inicio = strtotime($inicio);
        $fin = strtotime($fin);
        $dif = $fin - $inicio;
        $diasFalt = (( ( $dif / 60 ) / 60 ) / 24);
        return ceil($diasFalt);
    }

$conn2 = oci_connect('cide', 'pani2017', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.15.102)(PORT=1521)))(CONNECT_DATA=(SID=dbpani)(SERVER = DEDICATED)(SERVICE_NAME = DBPANITG)))');

if ($conn2==FALSE) 
{
            $e = oci_error();
            echo $e['message']."<br>";
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            exit;
}


?>
<script type="text/javascript">
  $(".div_wait").fadeIn("fast"); 
 </script>
 
<style type="text/css" media="print">

@page {    size:  landscape;  } 
th, td { padding-bottom: 0px;   border-spacing: 0; font-family: Arial; font-size: 09pt; } 
  
</style> 


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

.modal-lg {
    max-width: 80%;
}

@media print {
        #no_print { display: none; }  
        #printOnly {
       display : block;
    }       
 }
@media screen {
        #printOnly { display: none; } 
    }       
 }

</style>


<form method="post" id="_revision_premios" name="_revision_premios">
<div class="container-fluid">
<ul class="nav nav-tabs" id="no_print">
<li class="nav-item">    
    <a class="nav-link" href="./screen_mayor_fecha_pagos_global.php">Pagos de Premios de Lotería Mayor</a>
  </li>
  <li class="nav-item">
    <a style="background-color:#ededed;" class="nav-link active" href="#">Pagos de Premios de Lotería Menor</a>
  </li>
</ul> 
<div id="no_print_fr">
<div id="div_wait" class="div_wait">  </div>
<section id="no_print" style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Reporte General de Pagos de Premios de Loteria Menor</h3><br></section>
<section>
<a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS  <i class="far fa-hand-point-down fa-lg"></i></a>
    <div class="collapse " id="collapse1" align="center">
      <div class="card">
        <div class="card card-body" id="non-printable" >
          <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
            <div class = "input-group-prepend"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i> &nbsp;  Fecha Inicial: </span></div>
            <input type='date' id ="fecha_i"   name = "fecha_inicial" class="form-control" id ="dt1">
            <div class = "input-group-prepend" style="margin-left: 10px;"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i>  &nbsp;   Fecha Final: </span></div>
            <input type='date' id ="fecha_f"   name = "fecha_final" class="form-control" id ="dt2">           
            <button type="submit" name="seleccionar" style="margin-left: 10px;" class="btn btn-primary" value = "Seleccionar">  Seleccionar &nbsp;<i class="fas fa-search fa-lg"></i></button>
          </div>
        </div>
      </div>
    </div> 
 </section> 
</div>
 <hr id="no_print">

 <section>
   
 <?php     
    if (isset($_POST['seleccionar']))
    { 
    ?>
    <script type="text/javascript">
       $(".div_wait").fadeIn("fast");  
    </script>
    <?php 
       $_fecha_inicial=$_POST['fecha_inicial'];  $_fecha_inicial = date("Y-m-d", strtotime($_fecha_inicial)); 
       $_fecha_final=$_POST['fecha_final'];      $_fecha_final   = date("Y-m-d", strtotime($_fecha_final)); 
      
       ?>
          <div align="center" id="printOnly" style="margin-top: -20px"><h6><strong>Información General de Pagos de premios <br> Loteria Menor <br> Del <?php echo $_fecha_inicial; ?> al <?php echo $_fecha_final; ?> </strong></h6>     </div>
              <div class="table-responsive">
                <table  class="table table-hover table-sm table-bordered">
                  <thead><tr align="center">
                        <th colspan="2"></th> 
                        <th colspan="2">Pagado</th>  
                        <th> Debitado Cuenta </th>  
                        <th>Contabilizado (ERP)</th>                           
                        <th class="table-secondary">Conciliación</th>  
                        <th colspan="2">Recepcionado</th>
                        <th colspan="2">Revisado</th>
                        <th colspan="2">Debitos </th> 
                        <th>Ajustes (ERP) </th>                     
                        <th colspan="2">Pendiente</th>                        
                      </tr> 
                      <tr tr align="center"><th>Fecha</th>
                        <th>Dias</th>
                        <th>Billetes</th>
                        <th>Neto</th>
                        <th>Neto</th>                                               
                        <th>Neto</th>
                        <th class="table-secondary">Neto</th>
                        <th>Billetes</th>
                        <th>Neto</th>
                        <th>Billetes</th>
                        <th>Neto</th>
                        <th>Billetes</th>
                        <th>Neto</th>
                        <th>Neto</th>
                        <th>Billetes</th>
                        <th>Neto</th>                         
                      </tr>                 
                  </thead>
                  <tbody> 
                    <?php 
                    $query_pagos_fecha=mysqli_query($conn, "SELECT date(a.transactiondate)  fecha_pago, count(numero) cantidad_pagada, sum(a.neto) neto_pagado ,
                                        (count( case when (estado_revision =1 ) then numero end) )revisado,
                                        (sum( case when (estado_revision =1 ) then a.neto else 0 end) ) 'neto_revisado'
                                       FROM menor_pagos_detalle a INNER JOIN menor_pagos_recibos b ON a.transactioncode=b.transactioncode 
                                       WHERE date(a.transactiondate) between '$_fecha_inicial' AND '$_fecha_final' AND a.transactionstate in (1) group by date(a.transactiondate) order by date(a.transactiondate) asc ");

                    if ($query_pagos_fecha) 
                    {
                      $decimos_total_debitos           = 0;
                      $diferencia_decimos              = 0;
                      $diferencia_neto                 = 0;
                      $total_decimos_pagados           = 0;
                      $total_neto_pagado               = 0;
                      $total_neto_debitado             = 0;                     
                      $total_neto_contabilzado         = 0;
                      $total_decimos_recepcionados     = 0;
                      $total_neto_recepcionado         = 0;
                      $total_decimos_revisado          = 0;
                      $total_neto_revisado             = 0;
                      $total_decimos_debito_acum       = 0;
                      $total_neto_debito_acum          = 0;
                      $total_neto_ajustes              = 0;
                      $total_decimos_pendiente         = 0;
                      $total_neto_pendiente            = 0; 
                      $conciliado                      = 0; 
                      $conciliado_acumulado            = 0; 

                      while ($row_pagado_fechas=mysqli_fetch_array($query_pagos_fecha)) 
                      {
                          $fecha_pago=$row_pagado_fechas['fecha_pago'];                        
                          $fecha_pago_screen= date("d-m-Y", strtotime($fecha_pago));
                          $fecha_actual=date("Y-m-d");
                          $fecha1 = strtotime($fecha_actual);  $fecha2 = strtotime($fecha_pago);
                          $res = $fecha1 - $fecha2;
                          $dias_vencimiento = date('d', $res); 
                          $dias_vencidos=diferenciaDias($fecha_pago, $fecha_actual);

                         $fecha_pago=$row_pagado_fechas['fecha_pago'];
                         $decimos_pagado=$row_pagado_fechas['cantidad_pagada'];
                         $neto_pagado=$row_pagado_fechas['neto_pagado'];
                         $decimos_revisados=$row_pagado_fechas['revisado'];
                         $neto_revisado=$row_pagado_fechas['neto_revisado'];

                         ////// debitado
                         $query_debitado_banco=mysqli_query($conn, "SELECT monto FROM debitos_banco WHERE DATE(fecha_movimiento)='$fecha_pago' and producto=2 ");
                         if ($query_debitado_banco) 
                         {
                            if (mysqli_num_rows($query_debitado_banco)>0) 
                            {
                              while ($row_debitado_banco=mysqli_fetch_array($query_debitado_banco)) 
                              {
                                $neto_debitado_banco=$row_debitado_banco['monto'];  
                              }                              
                            }
                            else
                            {
                              $neto_debitado_banco=0;
                            }
                         }
                         else
                         {
                            echo mysqli_error($conn);
                         }

                         ////// PAGOS DIARIOS ERP CONTABILIZADOS
                             $fecha_oracle=date("d/m/Y" , strtotime($fecha_pago)); 
                           //  echo $fecha_oracle;                       
                             $query_oracle="SELECT  FECHA_PAGO, SUM(NETO_PAGADO) NETO_PAGADO FROM LOT_PAGOS_DIARIOS WHERE PRODUCTO=2 AND TO_DATE(FECHA_PAGO, 'DD/MM/YY') = TO_DATE('$fecha_oracle', 'DD/MM/YY')  GROUP BY FECHA_PAGO";
                             $neto_contabilizado=0;
                              $stid = oci_parse($conn2, $query_oracle );                       
                              $rc=oci_execute($stid); 

                              if(!$rc)
                              {
                                $bandera_erp = 1;
                                $e=oci_error($stid);
                                //var_dump($e);
                              }
                              else
                              {                                 
                                while ($row_oracle = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) 
                                {
                                  $neto_contabilizado= $row_oracle['NETO_PAGADO'];                                                
                                }
                              }
 

                         ////// RECEPCIONADO EN PREMIOS
                         $decimos_recibido = 0; 
                         $neto_recibido    = 0;  
                         $query_recepcionado= mysqli_query($conn, "SELECT sum(cant_numeros) decimos_recibidos, sum(netopayment) neto_recibido FROM rp_asignacion_agencias_revisor_menor WHERE date(transactiondate)='$fecha_pago' GROUP BY date(transactiondate)  ");
                         if ($query_recepcionado) 
                         {
                            if (mysqli_num_rows($query_recepcionado)>0) 
                            {
                              while ($row_recibido=mysqli_fetch_array($query_recepcionado)) 
                              {
                                $decimos_recibido=$row_recibido['decimos_recibidos']; 
                                $neto_recibido=$row_recibido['neto_recibido'];  
                              }                              
                            }                           
                         }
                         else { echo mysqli_error($conn); }

                         ////// DEBITOS EN PREMIOS
                         $query_debitos_total= mysqli_query($conn, "SELECT count(c.numero) decimos_debito_pagos, sum(c.neto) neto_debito_pagos FROM menor_pagos_detalle a, menor_pagos_recibos b, rp_notas_credito_debito_menor c WHERE a.transactioncode=b.transactioncode and a.id=c.id_detalle and date(a.transactiondate)='$fecha_pago' and a.estado_revision=2 GROUP BY date(a.transactiondate) ");
                         if ($query_debitos_total) 
                         {
                            if (mysqli_num_rows($query_debitos_total)>0) 
                            {
                              while ($row_debitos_completo=mysqli_fetch_array($query_debitos_total)) 
                              {
                                $decimos_debito_pagos=$row_debitos_completo['decimos_debito_pagos'];  $neto_debito_pagos=$row_debitos_completo['neto_debito_pagos'];
                              }                              
                            } 
                            else
                            {
                              $decimos_debito_pagos=0;  $neto_debito_pagos=0;  
                            }                           
                         } else {  echo mysqli_error($conn);  }


                         $query_debitos_parcial= mysqli_query($conn, "SELECT count(c.numero) decimos_debito_pagos_parcial, sum(c.neto) neto_debito_pagos_parcial FROM rp_notas_credito_debito_menor c WHERE c.id_detalle = 0 and date(c.transactiondate)='$fecha_pago' GROUP BY date(c.transactiondate)");
                         if ($query_debitos_parcial) 
                         {
                            if (mysqli_num_rows($query_debitos_parcial)>0) 
                            {
                              while ($row_debitos_parcial=mysqli_fetch_array($query_debitos_parcial)) 
                              {
                                $decimos_debito_pagos_parcial=$row_debitos_parcial['decimos_debito_pagos_parcial'];  $neto_debito_pagos_parcial=$row_debitos_parcial['neto_debito_pagos_parcial'];
                              }                              
                            } 
                            else
                            {
                              $decimos_debito_pagos_parcial=0;  $neto_debito_pagos_parcial=0;  
                            }                           
                         } else {  echo mysqli_error($conn);  }


                         $query_faltante= mysqli_query($conn, "SELECT count(c.numero) decimos_debito_pagos_parcial, sum(c.netopayment) neto_debito_pagos_parcial FROM rp_faltantes_sobrantes_menor c WHERE date(c.transactiondate)='$fecha_pago' GROUP BY date(c.transactiondate)");
                         if ($query_faltante) 
                         {
                            if (mysqli_num_rows($query_faltante)>0) 
                            {
                              while ($row_faltante=mysqli_fetch_array($query_faltante)) 
                              {
                                $decimos_faltante=$row_faltante['decimos_debito_pagos_parcial'];  $neto_faltante=$row_faltante['neto_debito_pagos_parcial'];
                              }                              
                            } 
                            else
                            {
                              $decimos_faltante=0;  $neto_faltante=0;  
                            }                           
                         } else {  echo mysqli_error($conn);  }

                         $decimos_total_debitos = $decimos_debito_pagos + $decimos_debito_pagos_parcial + $decimos_faltante;
                         $neto_total_debitos    = $neto_debito_pagos    + $neto_debito_pagos_parcial    + $neto_faltante;

                         ////// DIFERENCIAS ENTRE LO PAGADO Y LO REVISADO , DESPUES DE 60 DIAS SALE EN ROJO 

                          $diferencia_decimos = $decimos_pagado - ($decimos_revisados + $decimos_total_debitos);
                          $diferencia_neto    = $neto_pagado    - ($neto_revisado     + $neto_total_debitos);

                          $total_decimos_pagados           = $total_decimos_pagados         + $decimos_pagado; 
                        $total_neto_pagado               = $total_neto_pagado             + $neto_pagado;
                        $total_neto_debitado             = $total_neto_debitado;          + $neto_debitado_banco     ;          
                        //$total_neto_contabilzado         = $total_neto_contabilzado;      +
                        $total_decimos_recepcionados     = $total_decimos_recepcionados   + $decimos_recibido ;
                        $total_neto_recepcionado         = $total_neto_recepcionado       + $neto_recibido;
                        $total_decimos_revisado          = $total_decimos_revisado        + $decimos_revisados;
                        $total_neto_revisado             = $total_neto_revisado           + $neto_revisado;
                        $total_decimos_debito_acum       = $total_decimos_debito_acum     + $decimos_total_debitos;
                        $total_neto_debito_acum          = $total_neto_debito_acum        + $neto_total_debitos;
                        //$total_neto_ajustes        = $total_neto_ajustes            +
                        $total_decimos_pendiente         = $total_decimos_pendiente       + $diferencia_decimos;
                        $total_neto_pendiente            = $total_neto_pendiente          + $diferencia_neto;
                        $conciliado                      = $neto_pagado-$neto_debitado_banco-$neto_contabilizado;
                        $conciliado_acumulado= $conciliado_acumulado+$conciliado;


                         echo "<tr><td>".$fecha_pago."</td>
                               <td>".number_format($dias_vencidos)."</td>
                               <td align='center'>".number_format($decimos_pagado)."</td>
                               <td align='right'>".number_format($neto_pagado,2)."</td>
                               <td align='right'>".number_format($neto_debitado_banco,2)."</td> 
                               <td align='right'>".number_format($neto_contabilizado,2) ."</td>                               
                               <td align='right' class='table-secondary font-weight-bold'>".number_format($conciliado,2)."</td>   
                               <td align='center'>".number_format($decimos_recibido)."</td>
                               <td align='right'>".number_format($neto_recibido,2)."</td>                              
                               <td align='center'>".number_format($decimos_revisados)."</td>
                               <td align='right'>".number_format($neto_revisado,2)."</td> 
                               <td align='center'>".number_format($decimos_total_debitos)."</td>
                               <td align='right'>".number_format($neto_total_debitos)."</td>
                               <td align='right'></td>                           
                               <td align='center'>".number_format($diferencia_decimos)."</td>
                               <td align='right'>".number_format($diferencia_neto,2)."</td>
                            <tr>";

                      }

                      echo "<tr class='table-success font-weight-bold' >
                            <td colspan='2'>Totales</td>                          
                             <td align='center'>".number_format($total_decimos_pagados)."</td>
                               <td align='right'>".number_format($total_neto_pagado,2)."</td>
                               <td align='center'>".number_format($total_neto_debitado,2)."</td> 
                               <td align='right'></td>   
                               <td align='right' class='font-weight-bold'>".number_format($conciliado_acumulado,2)."</td>              
                               <td align='center'>".number_format($total_decimos_recepcionados)."</td>
                               <td align='right'>".number_format($total_neto_recepcionado,2)."</td>                              
                               <td align='center'>".number_format($total_decimos_revisado)."</td>
                               <td align='right'>".number_format($total_neto_revisado,2)."</td> 
                               <td align='center'>".number_format($total_decimos_debito_acum)."</td>
                               <td align='right'>".number_format($total_neto_debito_acum,2)."</td>
                               <td align='right'></td>                               
                               <td align='center'>".number_format($total_decimos_pendiente)."</td>
                               <td align='right'>".number_format($total_neto_pendiente,2)."</td>
                        </tr> 
                    </tbody>
                </table>";

                echo "<div align='center'> 
                        <div align='center'>
                    <button class='btn btn-danger btn-lg'  onclick='window.print();' type='button' id='no_print'> <i class='fas fa-print'></i> Imprimir </button>
                  </div>
  
                     </div>";
                    }
                    else
                    {
                      echo mysqli_error($conn);
                    }

                  ?>


                    
                   

  <?php
    } 
  ?>
  </section>
</form>
<script type="text/javascript">
  $(".div_wait").fadeOut("fast");  
</script>
