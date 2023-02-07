<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['usuario'] ;
$fecha=$_GET['fecha']; 
?>

<script type="text/javascript">
       $(".div_wait").fadeIn("fast");  
 </script>
 <style type="text/css" media="print"> 
 @page {    size: a4; landscape;  }
 
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

@media print    {
        #no_print { display: none; }          

    }
</style>
<form method="post" id="_revision_premios"  class="" name="_revision_premios">
<div class="container-fluid">
<div id='div_wait'></div>
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Ajustes de Lotería Mayor Fecha # <?php echo $fecha; ?>  </h3><br></section>

<table class="table table-sm table-bordered table-hover">
  <thead>
  <tr ><th>No.</th>
       <th>Fecha de Pago</th>
       <th>Remesa</th>
       <th>Agencia</th>
       <th>Cajero</th>   
       <th>Tipo</th>    
       <th>Incicencia</th>
       <th>Fecha Creación</th>
       <th>Comentario</th> 
       <th>Sorteo</th>
       <th>Número</th>
       <th>Decimos</th>
       <th>Registro</th>
       <th>Total</th>
       <th>Impto</th>
       <th>Neto</th>
  </tr>
</thead>
<tbody>
<?php 
 $query= mysqli_query($conn, "SELECT id, transactiondate, remesa, transactionagencyname, transactionusername, comentario_revisor comentario, tipo_documento, incidencia, fecha_creacion, sorteo, numero, decimos_nota decimos, registro, neto_nota total, impto, neto_nota neto FROM `rp_notas_credito_debito_mayor` where date(transactiondate)='$fecha'");
 $tota_notas=0;            $impto_notas=0;            $neto_notas=0;
 if ($query) 
 {    
    while ($row_query= mysqli_fetch_array($query)) 
    {
       echo "<tr ><td>".$row_query['id']."</td>
                  <td>".$row_query['transactiondate']."</td>
                  <td>".$row_query['remesa']."</td>
                  <td>".$row_query['transactionagencyname']."</td>
                  <td>".$row_query['transactionusername']."</td>
                  <td>".$row_query['tipo_documento']."</td>
                  <td>".$row_query['incidencia']."</td>
                  <td>".$row_query['fecha_creacion']."</td>
                  <td>".$row_query['comentario']."</td>
                  <td>".$row_query['sorteo']."</td>
                  <td>".$row_query['numero']."</td>
                  <td align='center'>".$row_query['decimos']."</td>
                  <td>".$row_query['registro']."</td>
                  <td>".$row_query['total']."</td>
                  <td>".$row_query['impto']."</td>
                  <td>".$row_query['neto']."</td>
            </tr>";
            $tota_notas=$tota_notas+$row_query['total'];
            $impto_notas=$impto_notas+$row_query['impto'];
            $neto_notas=$neto_notas+$row_query['neto'];
    }

    echo "<tr class='table-success'><td colspan='13' align='center'> Total </td>
                                    <td align='right'>".number_format($tota_notas,2)."</td> 
                                    <td align='right'>".number_format($impto_notas,2)."</td>
                                    <td align='right'>".number_format($neto_notas,2)."</td></tr>
  </tbody>
</table>";

echo  '<div align="center">
            <button class="btn btn-danger btn-lg"  onclick="window.print();" type="button" id="no_print"> <i class="fas fa-print"></i> Imprimir </button>
       </div>';
    
 }
 else
{
   echo mysqli_error();
}



 ?>  


</form> 
<script type="text/javascript">
  $(".div_wait").fadeOut("fast");  
</script>