<?php 
require('../../template/header.php'); 
$usuario_id= $_SESSION['id_usuario']; 
date_default_timezone_set("America/Tegucigalpa");
  
  $_remesa=$_GET['remesa'];
  $year=$_GET['year'];
  $user_name=$_SESSION['nombre'];
  $user_text=$_SESSION['id_usuario'];
  $user_id=$_SESSION['usuario'];

   $dias = array("DOMINGO","LUNES","MARTES","MIERCOLES","JUEVES","VIERNES","SÁBADO");
                $dia=$dias[date("w")];
                $meses= array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio", "Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                $meso=$meses[date("m")-1];
                $ano=date("Y");
                $diadate=date("d");

     
?>
   
 
<form method="post"  id="_revision_premios" name="_revision_premios">
<div class="Section1">
<?php echo date('d-m-Y'); ?>
 <table align="center">
 <tr>
 <td  width="20%">  
 </td>
 <td width="60%" style="font-family: Arial; font-size: 18pt;">  
 
  </td>
  <td width="20%">      
  </td>
</tr>
<tr>
<tr>
  <td  colspan="3"> . </td>
</tr>
<td width="20%"></td>
   <td  width="60%"  style="font-family: Arial; font-size: 12pt;">
  <div align="center">
  <label >Patronato Nacional de la Infancia PANI <br> Departamento de Revisión de Premios<br>Remision a Boveda de Remesa No. <?php echo $_remesa; ?> <br> Lotería Menor  </label>
  </div>
  </td>
  <td width="20%"></td>
</tr>      
 </table><br>
<?php      

 $cantidad_acumulado_final=0;  $neto_acumulado_final=0;            
      
      $querys_revisores=mysqli_query($conn, "SELECT a.usuario_revision, b.nombre_completo , count(*) conteo, sum(a.principal) total , sum(a.impto) impto , sum(a.neto) neto FROM menor_pagos_detalle a, pani_usuarios b WHERE remesa=$_remesa and estado_revision in (1) and transactionstate in (1,3)  and a.usuario_revision = b.id and   ano_remesa = $year  group by usuario_revision order by usuario_revision asc ");
      $contador_sorteo=0; 

      echo "<table class='table table-sm table-bordered'>
            <thead><tr><td align='center'><label>Revisor</label></td>
                      <td align='center'><label>Paquetes</label></td>
                      <td align='center'><label>Billetes</label></td>
                      <td align='center'><label>Total</label></td>
                      <td align='center'><label>Impto</label></td>
                      <td align='center'><label>Neto</label></td>
                  </tr></thead><tbody>";

            $total_cantidad_revisor=0; $total_total_revisor=0;  $total_impto_revisor=0;  $total_neto_revisor=0; $cantida_paquetes=1; $total_paquetes=0;
            while ( $row_revisores=mysqli_fetch_array($querys_revisores)) 
            {      
                 echo "<tr><td>".$row_revisores['nombre_completo']."</td>
                           <td align='center'>".$cantida_paquetes."</td>
                           <td align='center'>    ".number_format($row_revisores['conteo'])."</td>                                  
                           <td align='right' > L. ".number_format($row_revisores['total'],2,'.',',')."</td>
                           <td align='right' > L. ".number_format($row_revisores['impto'],2,'.',',')."</td>
                           <td align='right' > L. <label>".number_format($row_revisores['neto'],2,'.',',')."</label></td></tr>";

               $total_paquetes=$cantida_paquetes+$total_paquetes;
               $total_cantidad_revisor=$total_cantidad_revisor+$row_revisores['conteo'];
               $total_total_revisor=$total_total_revisor+$row_revisores['total'];
               $total_impto_revisor=$total_impto_revisor+$row_revisores['impto'];
               $total_neto_revisor=$total_neto_revisor+$row_revisores['neto'];
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
 

 echo "<br><br><br> <p align='center'>_______________________________________</p><p align='center'>".$user_name."</p><p align='center'>Jefatura de Revisión de Premios</p><div>";


?>
                 
          
<script type="text/javascript">
     window.print(); 
     setTimeout(window.close, 1000) ;
</script>   
</form> 
    
 
