<?php
require('../../template/header.php'); 
$usuario_id=$_SESSION['id_usuario'];
$nombre_usuario=$_SESSION['nombre'];
$user_name=$_SESSION['nombre_usuario'];

if (isset($_GET['cod_impresion_faltante'])) {
$cod_factura = $_GET['cod_impresion_faltante'];
}else{
$cod_factura =  $_SESSION['cod_impresion_faltante'];  
}
 

 
$factura =  mysqli_query($conn, "SELECT remesa, sorteo, numero, decimos serie, registro, totalpayment, imptopayment, netopayment, transactionagencyname, transactionusername, creationdate, creationuser, registertype, coment
from rp_faltantes_sobrantes_mayor 
where id=$cod_factura; ");

$ob_factura = mysqli_fetch_object($factura);
$remesa = $ob_factura->remesa;
$sorteo = $ob_factura->sorteo;
$numero = $ob_factura->numero;
$serie = $ob_factura->serie;
$registro = $ob_factura->registro;
$totalpayment = $ob_factura->totalpayment;
$imptopayment = $ob_factura->imptopayment;
$netopayment = $ob_factura->netopayment;
$transactionagencyname=$ob_factura->transactionagencyname;
$transactionusername = $ob_factura->transactionusername;
$creationdate = $ob_factura->creationdate;
$creationuser = $ob_factura->creationuser;
$registertype=$ob_factura->registertype;
$coment=$ob_factura->coment
 ?>

<form method="post"  id="_revision_premios" name="_revision_premios">
<div class="Section1">
 <table align="center">
 <tr>
 <td  width="20%">  
 </td>
 <td width="60%" style="font-family: Arial; font-size: 18pt;">  
  <!-- img src="imagenes/PANI_1.jpg" align="center"  border="0"  width="100%" / -->
  </td>
  <td width="20%">      
  </td>
</tr>
<tr>
<tr>
  <td  colspan="3"> . </td>
</tr>
<td width="20%"></td>
   <td  width="60%"  style="font-family: Arial; font-size: 14pt;">
  <div align="center">
  <label>
          Patronato Nacional de la Infancia PANI <br> Departamento de Revisión de Premios<br>Reporte de  <?php echo $registertype ?>, de Lotería Mayor <br> Del  <?php echo $creationdate; ?> <br> Perteneciente al Sorteo No. <?php echo $sorteo ?> <br> De la Remesa No. <?php echo $remesa ?>
  </label>
  </div>
  </td>
  <td width="20%"></td>
</tr>      
 </table><br>
<?php      
  
 echo "<div style='font-family: Arial; font-size: 12pt;'>Estimados Sres.  Cordialmente nos remitimos a Uds., por lo siguiente :.<br><br>Comentario del Revisor:<br> ".$coment."<br><br> Descripcion:<br><br>";

?>

   <table style='font-family: Arial; font-size: 12pt;' id="table_" align="center" class="table table-hover table-bordered table-sm">  
          <thead>
            <tr>
            <td style='font-family: Arial; font-size: 12pt;'>Tipo</td>
             <td style='font-family: Arial; font-size: 12pt;'>Fecha</td>
             <td>Remesa</td>
             <td>Agencia</td> 
             <td>Cajero</td>  
             <td>Sorteo</td> 
             <td>Numero</td>
             <td>Decimos</td>
             <td>Registro</td>
             <td>Total</td> 
            </tr> 
         <thead>
         <tbody>
         <?php

            $result = mysqli_query($conn, " SELECT  `remesa`, `sorteo`, `numero`, decimos serie, `registro`, `totalpayment`, `imptopayment`, `netopayment`, `transactionusername`, `transactionagencyname`, date(`transactiondate`) fecha, date(`creationdate`) fecha_creacion, `creationuser`, `registertype`, `coment` FROM `rp_faltantes_sobrantes_mayor` where id=$cod_factura ");
                       $num_productos = mysqli_num_rows($result);
                       if (mysqli_num_rows($result)>0)
                       {
                   while ($row = mysqli_fetch_array($result)) 
                   {  
                   echo "<tr> 
                         <td>".$row['registertype']."</td>
                                 <td>".$row['fecha']."</td>
                                 <td>".$row['remesa']."</td>
                                 <td>".$row['transactionagencyname']."</td>
                                 <td style='font-family: Arial; font-size: 11pt;'>".$row['transactionusername']."</td>
                                 <td>".$row['sorteo']."</td>
                                 <td>".$row['numero']."</td>
                                 <td>".$row['serie']."</td>
                                 <td>".$row['registro']."</td>
                                 <td>".number_format($row['totalpayment'],2,'.',',')."</td> 
                               
                                 </tr>";
                               }
                           }
                           else
                           {  
                            echo mysqli_error();   
                           }
             
            ?>           
             </tbody> 
        </table> 

<?php 

echo "<br><br><br>Deseándole Éxitos en sus labores 

<br><br><br><br>
Atte. 
<br><br><br> <p align='center'>_______________________________</p><p align='center'>".$user_name."</p><p align='center'>Revisión de Premios</p><div>";

          

 
?>
                 
<script type="text/javascript">
    window.print(); 
    setTimeout(window.close, 1000) ;
</script>   
