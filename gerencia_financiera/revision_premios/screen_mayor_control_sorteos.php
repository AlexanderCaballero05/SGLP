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
    <a style="background-color:#ededed;" class="nav-link active" href="#" >Lotería Mayor</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="./screen_menor_control_sorteos.php">Lotería Menor</a>
  </li>
</ul> 
<div id="no_print_fr">
<div id="div_wait" class="div_wait">  </div>
<section id="no_print" style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Reporte de Control de Pagos de Premios de Loteria Mayor</h3><br></section>
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
    <script type="text/javascript">      $(".div_wait").fadeIn("fast");       </script>
    <?php 
       $_fecha_inicial           =  $_POST['fecha_inicial'];                                    $_fecha_inicial = date("Y-m-d", strtotime($_fecha_inicial)); 
       $_fecha_final             =  $_POST['fecha_final'];                                      $_fecha_final   = date("Y-m-d", strtotime($_fecha_final)); 
       $fecha_inicial_vigente    =  date("Y-m-d",strtotime($_fecha_inicial."- 45 days")); 
      // echo "La fecha Actual es : ".$fecha_inicial_vigente." , la fecha inicial vigente es : ".$_fecha_inicial;      
       ?>
          <div align="center" id="" style="margin-top:"><h6><strong>Información de Pagos de premios <br> Loteria Mayor <br> Del <?php echo  date("d-m-Y", strtotime($_fecha_inicial)) ?> al <?php echo date("d-m-Y", strtotime($_fecha_final)); ?> </strong></h6>     </div>
              <div class="table-responsive mt-3">
                <table  class=" table table-bordered table-sm table-hove table-striped  dt-responsive" cellspacing="0" style="width:100%; font-size:15px;">
                  <thead> 
                      <tr align="center"> 
                            <th>Sorteo</th>
                            <th>Fecha Sorteo</th>
                            <th>Vencimiento</th>
                            <th>Estado</th>
                            <th>Fecha Pago</th>
                            <th>Número</th>                                               
                            <th>Décimos</th>                      
                            <th>Total</th>
                            <th>Impto</th>
                            <th>Neto a Pagar</th>
                            <th>Pagado</th>
                            <th>Neto a Pagar Impto</th>                                               
                      </tr></thead><tbody> 
                    <?php  
                          $query_sorteos_vigentes = mysqli_query($conn, "SELECT id, fecha_sorteo, fecha_vencimiento  FROM sorteos_mayores WHERE date(fecha_sorteo) BETWEEN '$fecha_inicial_vigente' AND '$_fecha_final' ");
                          if (mysqli_num_rows($query_sorteos_vigentes)>0) {                                     
                              
                            $total_acumulado_impto_final =0;
                            while ($row_sorteos_vigentes = mysqli_fetch_array($query_sorteos_vigentes))  
                            {
                              $_sorteo            =  $row_sorteos_vigentes['id'];
                              $_fecha_sorteo      =  $row_sorteos_vigentes['fecha_sorteo'];
                              $_fecha_sorteo      =  date("d-m-Y", strtotime($_fecha_sorteo)); 
                              $_fecha_vencimiento =  $row_sorteos_vigentes['fecha_vencimiento'];  
                              $_fecha_vencimiento =  date("d-m-Y", strtotime($_fecha_vencimiento)); 

                              $query_numeros_ganadores=mysqli_query($conn, "SELECT a.numero_premiado_mayor, a.monto pago_premio, 10 as decimos FROM sorteos_mayores_premios a  WHERE  a.sorteos_mayores_id= '$_sorteo' AND   monto > 1000  ;");
                                  
                                  $estado_derecho='';  $impto_serie_pagar_derecho=0;  $impto_acumulado_sorteo  = 0;  $pagado=0;

                                      while ($_numeros_ganadores=mysqli_fetch_array($query_numeros_ganadores))
                                      {
                                             $_numero_ganador_derecho=$_numeros_ganadores['numero_premiado_mayor'];
                                             $decimos =  $_numeros_ganadores['decimos'];
                                             $_monto_ganador_derecho=$_numeros_ganadores['pago_premio']; 

                                              $query_series_ganadores=mysqli_query($conn, "SELECT numero_premiado_menor, monto pago_premio FROM sorteos_menores_premios  WHERE  sorteos_menores_id=$_sorteo AND premios_menores_id = 2; ");

                                                      if ($_monto_ganador_derecho>30000) {  $impto=$_monto_ganador_derecho*0.10;  } else  { $impto=0; }

                                                      $neto=$_monto_ganador_derecho-$impto;   

                                                       $query_pagado= mysqli_query($conn, "SELECT a.transactiondate, a.numero, sum(decimos) decimos, sum(a.netopayment) pagado, sum(a.imptopayment) impto_pagado FROM mayor_pagos_detalle a, mayor_pagos_recibos b WHERE a.transactioncode= b.transactioncode and sorteo='$_sorteo' and numero='$_numero_ganador_derecho'   and a.transactionstate=1 GROUP BY numero order by a.transactiondate desc ");

                                                       $query_vendido= mysqli_query($conn, "SELECT *FROM archivo_pagos_mayor WHERE  sorteo='$_sorteo' and numero='$_numero_ganador_derecho' ");
                                                        $fecha_pago="";
                                                       if (mysqli_num_rows($query_pagado)>0) 
                                                       {
                                                            $ob_pagado_derecho          = mysqli_fetch_object($query_pagado);
                                                            $fecha_pago                 = $ob_pagado_derecho->transactiondate;
                                                            $fecha_pago                 =  date("d-m-Y", strtotime($fecha_pago));
                                                            $decimos                    = $ob_pagado_derecho->decimos;
                                                            $pagado                     = $ob_pagado_derecho->pagado;
                                                            $impto                      = $ob_pagado_derecho->impto_pagado;
                                                            $estado_derecho             = 'Pagada por el Banco';
                                                            $impto_serie_pagar_derecho  = $impto;
                                                        } 
                                                        else if (mysqli_num_rows($query_vendido)>0) 
                                                        {
                                                             $estado_derecho='No Cobrada por el Cliente';
                                                        } 
                                                        else
                                                        {
                                                           $estado_derecho='No vendida';
                                                        }

                                                        echo " <tr><td align='center'>".$_sorteo."</td> 
                                                                   <td align='center'>".$_fecha_sorteo."</td> 
                                                                   <td align='center'>".$_fecha_vencimiento."</td> 
                                                                   <td align='center'>".$estado_derecho."</td>
                                                                   <td align='center'>".$fecha_pago."</td> 
                                                                   <td align='center'>".$_numero_ganador_derecho."</td>
                                                                   <td align='center'>".$decimos."</td>
                                                                   <td align='right'>".number_format($_monto_ganador_derecho,2,'.',',')."</td>
                                                                   <td align='right'>".number_format($impto,2,'.',',')."</td>
                                                                   <td align='right'>".number_format($neto,2,'.',',')."</td>
                                                                   <td align='right'>".number_format($pagado,2,'.',',')."</td>
                                                                   <td align='center'></td></tr>"; 
                                       }

                                                       $impto_acumulado_sorteo=  $impto_acumulado_sorteo + $impto_serie_pagar_derecho;                                                        
                                                        echo "<tr> <td align='center'></td>
                                                                   <td align='center'></td>  
                                                                   <td align='center'></td> 
                                                                   <td align='center'></td>
                                                                   <td align='center'></td>
                                                                   <td align='center'></td>
                                                                   <td align='right' ></td>
                                                                   <td align='right' ></td>
                                                                   <td align='right' ></td>
                                                                   <td align='right' ></td>
                                                                   <td align='right' ></td>
                                                                   <td align='right'><strong>".number_format($impto_acumulado_sorteo,2,'.',',')."</strong></td></tr>";
                                                                $total_acumulado_impto_final  =  $total_acumulado_impto_final + $impto_acumulado_sorteo ;

                            }
                                                             echo "<tr class='table-success'>  <td align='center'></td>
                                                                                               <td align='center'></td>  
                                                                                               <td align='center'></td> 
                                                                                               <td align='center'></td>
                                                                                               <td align='center'></td>
                                                                                               <td align='center'></td>
                                                                                               <td align='right' ></td>
                                                                                               <td align='right' ></td>
                                                                                               <td align='right' ></td>
                                                                                               <td align='right' ></td>
                                                                                               <td align='right' ></td>
                                                                                               <td align='right'><strong>".number_format($total_acumulado_impto_final,2,'.',',')."</strong></td></tr>";
                          }
                    ?>


                    
          </tbody>
        </table>       

  <?php
    } 
  ?>

  </section>
</form>
<script type="text/javascript">
  $(".div_wait").fadeOut("fast");  
</script>
