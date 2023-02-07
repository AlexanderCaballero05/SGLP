<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['id_usuario'];
$usuario_name=$_SESSION['nombre_usuario'];


?>
<script type="text/javascript">
  $(".div_wait").fadeIn("fast");  



 </script>
 <style type="text/css" media="print"> 

@page {    size:  portrait;  }
 
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
 
<div id="no_print_fr">
<div id="div_wait" class="div_wait">  </div>
<section id="no_print" style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Reporte de Impuestos por pagos de Loteria Mayor</h3><br></section>
<section>
<a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS  <i class="far fa-hand-point-down fa-lg"></i></a>
    <div class="collapse " id="collapse1" align="center">
      <div class="card">
        <div class="card card-body" id="non-printable" >
          <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
            <div class = "input-group-prepend"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i> &nbsp;   Seleccionar Mes de Impuesto pagar: </span></div>
                <input type="month"  class="form-control" id="mes" name="mes"  min="2019-01" value="">     
            <button id="buttonConsulta" name="seleccionar" type="submit" class="Consulta btn btn-primary">BUSQUEDA DE BILLETES PAGADOS</button>
          </div>
        </div>
      </div>
    </div> 
    <input type="hidden" name="slctrevisor" value="<?php echo $usuario_id  ?> ">
 </section>
</div>
 <hr>

<?php 
if (isset($_POST['seleccionar'])) 
{ 
  setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
  setlocale(LC_ALL, "es_ES");

  $mes=$_POST['mes'];
  //echo "Este es el mes ".$mes;
  $mes_txt = date('d-m-Y', strtotime($_POST['mes']));
  $año_txt = date('Y', strtotime($_POST['mes']));   
  $monthName = date("F", strtotime( $mes_txt )); 
  $monthNum  = date('m', strtotime($_POST['mes'])); 
  $dateObj   = DateTime::createFromFormat('!m', $monthNum);
  $monthName = strftime('%B', $dateObj->getTimestamp());

  $total_pagar_final=0;
  echo "<div align='center' class='alert alert-success' style='font-size:16pt;' ><strong> Patronato Nacional de la Infancia PANI <br> Departamento de Revisión de Premios <br> Reporte de Impuesto por pagos de Lotería Mayor <br>  ".$monthName." de ".$año_txt." </strong></div>";

   $query_impto_mayor = mysqli_query($conn, "SELECT 
'Loteria Mayor' as loteria,
c.id sorteo,
c.fecha_sorteo fecha_sorteo,
date(a.transactiondate) fecha_pago,
a.numero,
a.decimos,
a.totalpayment,
a.imptopayment,
a.netopayment
FROM mayor_pagos_detalle a
INNER JOIN mayor_pagos_recibos b  ON a.transactioncode  = b.transactioncode
INNER JOIN sorteos_mayores c      ON a.sorteo = c.id 
WHERE 
date_format(a.transactiondate , '%Y-%m')='$mes' AND
a.imptopayment > 0                AND
a.transactionstate = 1");

 

 
 ?>
           <table width="100%"  id="table" align="center" class="table table-hover table-bordered table-sm">
                  <thead align='center'> 
                    <tr><td>Lotería</td>
                        <td>Sorteo</td> 
                        <td>Fecha de Sorteo</td> 
                        <td>Fecha de Pago</td> 
                        <td>Número</td> 
                        <td>Décimos </td>
                        <td>Total</td>
                        <td>Impto</td>
                        <td>Neto</td>
                        <td class="bg-light">Neto a Pagar</td></tr>
                 </thead>
                 <tbody>
          <?php 

    $sumatoria_impto_mayor =0;
     if (mysqli_num_rows($query_impto_mayor)>0) {
      
          
         while ( $row_sorteo_mayor=mysqli_fetch_array($query_impto_mayor)) 
        {
           $loteria               = $row_sorteo_mayor['loteria'];
           $sorteo                = $row_sorteo_mayor['sorteo'];
           $fecha_sorteo          = $row_sorteo_mayor['fecha_sorteo'];
           $fecha_pago            = $row_sorteo_mayor['fecha_pago'];
           $numero                = $row_sorteo_mayor['numero'];
           $decimos               = $row_sorteo_mayor['decimos'];
           $total                 = $row_sorteo_mayor['totalpayment'];
           $impto                 = $row_sorteo_mayor['imptopayment'];
           $neto                  = $row_sorteo_mayor['netopayment'];

       
                    echo "<tr class=''> <td align='center'> ".$loteria."       </td>
                               <td align='center'> ".$sorteo."        </td>
                               <td align='center'> ".$fecha_sorteo."  </td>  
                               <td align='center'> ".$fecha_pago."    </td>
                               <td align='center'> ".$numero. "       </td>
                               <td align='center'> ".$decimos."       </td>
                               <td align='right '> ".number_format($total,2,'.',',')."</td>
                               <td align='right '> ".number_format($impto,2,'.',',')."</td>
                               <td align='right '> ".number_format($neto,2,'.',','). "</td> 
                               <td align='right' class='bg-light'><strong>".number_format($impto,2,'.',',')."</strong></td></tr>";

                           $sumatoria_impto_mayor =  $sumatoria_impto_mayor+$impto;
        }
     }
     else{
        echo "<tr>
                <td colspan='9' align='center'><strong>ESTE MES NO SE PAGARON PREMIOS DE LOTERÍA MAYOR SUJETOS A RETENCIÓN DE IMPUESTOS</strong></td>
             </tr>";
     }
     
 
    $total_pagar_final = $sumatoria_impto_mayor  ;
    echo "<tr><td align='center' colspan='10'> -- </td></tr><tr><td align='center' colspan='9'> -- </td></tr>
          <tr><td align='center' colspan='9'><strong> Total a Pagar de impuestos generados en el perido de ".$monthName." de ".$año_txt."</strong> </td>
              <td align='right'><strong>".number_format($total_pagar_final,2,'.',',')."</strong></td></tr>"; 

	?>
		   </tbody>
	   </table>
	</div>
          


</div>
   <section id="no_print">
	<div align="center">
		<button class="btn btn-danger btn-lg"  onclick='window.print();' type="button" id="no_print"> <i class="fas fa-print"></i> Imprimir </button>
	</div>
  </section>
  <div align="center" id="printOnly">
    <br><br><br><br> <p id="printOnly"> __________________________________ <br> Firma y Sello  <br> <?php echo $usuario_name; ?> </p>
 </div>
    <?php 
}
  ?>
      
</form>
      


<script type="text/javascript">
	$(".div_wait").fadeOut("fast");  
</script>
