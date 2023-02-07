<?php 

require('../../template/header.php'); 
$usuario_id=$_SESSION['usuario'] ; 

$_revisor=$_GET['revisor'];  $_remesa=$_GET['remesa'];
$ano_remesa= date("Y");;

$time=mysqli_query($conn, "SELECT CURRENT_TIMESTAMP() fecha_hora;");  $time_object=mysqli_fetch_object($time);   $fecha_hora = $time_object ->fecha_hora;

$query_revisor_name=mysqli_query($conn, "SELECT nombre_completo from pani_usuarios where id=$_revisor");  while ( $row_user_name =mysqli_fetch_array($query_revisor_name)) { $name_revisor=  $row_user_name['nombre_completo'];   } 

echo "<p align='left'>RP_LMAY ".$fecha_hora."</p>";

echo '<table align="center" >
<tr>
       <td  width="20%"></td>
       <td width="60%" style="font-family: Arial; font-size: 18pt;"></td>
       <td width="20%"></td>
</tr> 
<tr>  <td  colspan="3"> . </td></tr>
<tr><td width="20%"></td>
    <td  width="60%"  style="font-family: Arial; font-size: 10pt;"> <div align="center"><label style=" font-family: Arial; font-size:10pt;" >Patronato Nacional de la Infancia PANI <br> Departamento de Revision de Premios<br>Loteria Mayor Remesa No.   '. $_remesa .' asignada a: <br> '.$name_revisor.'  </label></div></td>
    <td width="20%"></td></tr> </table><br>';

  $query_nameuser=mysqli_query($conn, "SELECT nombre_completo FROM pani_usuarios where id= $usuario_id ");
  while ($row_nameuser=mysqli_fetch_array($query_nameuser) )   {    $_erp_nameuser=$row_nameuser['nombre_completo'];  }

 
       //echo $_revisor."----".$_remesa;
        $query_revisor_name=mysqli_query($conn, "SELECT nombre_completo from pani_usuarios where id=$_revisor");

        $cantidad_acumualdo_final=0;  $total_acumulado_final= 0; $impto_acumulado_final= 0; $neto_acumulado_final= 0;
        while ( $row_user_name =mysqli_fetch_array($query_revisor_name)) { $name_revisor=  $row_user_name['nombre_completo'];   }

       echo "<div class='col-md-12'>
                         <table width='90%'  id='tableinfo' align='center' style='font-size: 10; '  class='table table-hover table-bordered table-sm'> 
                                 <thead><tr><td align='center'><label>No. </label></td>
                                            <td align='center'><label>Agencia</label></td>
                                            <td align='center'><label>Billetes</label></td>
                                            <td  align='center'><label>Total</label></td>
                                            <td  align='center'><label>Impto</label></td>
                                            <td  align='center'><label>Neto</label></td></tr></thead><tbody>"; 

       $query_fechas=mysqli_query($conn, "SELECT date(transactiondate) fecha FROM rp_asignacion_agencias_revisor_mayor where remesa=$_remesa and ano_remesa='$ano_remesa' and usuario_revision=$_revisor group by date(transactiondate) order by fecha asc");
       while ( $row_fecha= mysqli_fetch_array($query_fechas)) 
       {
         $_fecha_pp=$row_fecha['fecha'];
        echo "<tr class='info'><td colspan='6' align='center'><label> <span class='glyphicon glyphicon-calendar'> &nbsp;  ".$_fecha_pp."<span></label></td></tr>";

                       $query_info_revisor=mysqli_query($conn, "SELECT transactiondate, transactionagency, b.nombre, cant_decimos as cant_numeros, totalpayment, imptopayment, netopayment 
                                                                FROM `rp_asignacion_agencias_revisor_mayor` a, seccionales b 
                                                                WHERE a.transactionagency=b.cod_seccional and b.id_empresa=3 and remesa=$_remesa and ano_remesa='$ano_remesa' and usuario_revision=$_revisor and transactiondate='$_fecha_pp'  order by fecha_recepcion asc");

                      
                        $contador=0;  $cantidad_acumualdo=0;  $total_acumulado= 0; $impto_acumulado= 0; $neto_acumulado= 0;                                           
                        while ($row_info_revisor=mysqli_fetch_array($query_info_revisor)) 
                        {
                            $fecha_recepcion=$row_info_revisor['transactiondate'];
                            $agencia=$row_info_revisor['transactionagency'];
                            $agencia_name=$row_info_revisor['nombre'];
                            $cantidad_numeros=$row_info_revisor['cant_numeros'];
                            $totalpayment=$row_info_revisor['totalpayment'];
                            $imptopayment=$row_info_revisor['imptopayment'];
                            $netopayment=$row_info_revisor['netopayment'];

                             echo "<tr><td align='center'><label>".$contador."</label></td>
                                                   <td align='left'><label>".$agencia." -- ".$agencia_name."</label></td>                         
                                                   <td align='center'><label>".number_format($cantidad_numeros)."</label></td>
                                                   <td align='right'><label>".number_format($totalpayment,2,'.',',')."</label></td>
                                                   <td align='right'><label>".number_format($imptopayment,2,'.',',')."</label></td>
                                                   <td align='right'><label>".number_format($netopayment,2,'.',',')."</label></td></tr>"  ;

                                                   $cantidad_acumualdo=$cantidad_acumualdo+$cantidad_numeros;  $total_acumulado= $total_acumulado+$totalpayment; $impto_acumulado= $impto_acumulado+$imptopayment; $neto_acumulado= $neto_acumulado+$netopayment;
                                                   $contador++;
                        }

                        echo        "<tr><td></td></tr>
                                              <tr class='success'><td align='center' colspan='2'><label>Total</label></td>                                                   
                                                   <td align='center'><label>".number_format($cantidad_acumualdo)."</label></td>
                                                   <td align='right'><label>".number_format($total_acumulado,2,'.',',')."</label></td>
                                                   <td align='right'><label>".number_format($impto_acumulado,2,'.',',')."</label></td>
                                                   <td align='right'><label>".number_format($neto_acumulado,2,'.',',')."</label></td></tr>"  ;


                          $cantidad_acumualdo_final=$cantidad_acumualdo_final+$cantidad_acumualdo;  $total_acumulado_final= $total_acumulado_final+ $total_acumulado; $impto_acumulado_final= $impto_acumulado_final+$impto_acumulado; $neto_acumulado_final= $neto_acumulado_final+$neto_acumulado;
     }

      echo        "<tr><td></td></tr><tr><td colspan='6' align='center'><label> Total General</label></td></tr>
                                              <tr class='success'><td align='center' colspan='2'><label></label></td>                                                   
                                                   <td align='center'><label>".number_format($cantidad_acumualdo_final)."</label></td>
                                                   <td align='right'><label>".number_format($total_acumulado_final,2,'.',',')."</label></td>
                                                   <td align='right'><label>".number_format($impto_acumulado_final,2,'.',',')."</label></td>
                                                   <td align='right'><label>".number_format($neto_acumulado_final,2,'.',',')."</label></td></tr>"  ;

 echo "</table></div>";     
 echo "<br><br><br><br> <p align='center' >_______________________________________</p><p align='center' style='font-size: 10'  ><label>". $_erp_nameuser."</label></p><p  style='font-size: 10'  align='center'>Receptoría de Revisión de Premios</p><div>";      

 ?>

 <script type="text/javascript">
document.title="Revision de Premios";
window.print(); 
setTimeout(window.close, 1000);
</script>