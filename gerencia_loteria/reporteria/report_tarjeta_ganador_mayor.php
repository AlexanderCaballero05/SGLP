<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['id_usuario'];
?>
 
   <style type="text/css" media="print">

@media print {   
 @page {    
  size:  landscape;  
} 
} 

  
</style> 

  <style type="text/css" media="screen">
 
  #reporte 
     {
       border-radius: 42px 43px 43px 43px;
       -moz-border-radius: 42px 43px 43px 43px;
       -webkit-border-radius: 42px 43px 43px 43px;
        border: 3px solid #139949;
     }
    

   #slctsorteo
    {
       -webkit-appearance: none;
       background-image:url();
       background-repeat:no-repeat;
        -moz-appearance:none
    } 
 

   .tarjeta 
   {
     background-color: white; border-left-color: transparent; border-top-color: transparent; height:100px; border-spacing: 20px 30px; border-collapse: separate
   }

   hr {
    border: none;
    height: 1px;
    /* Set the hr color */
    color: #333; /* old IE */
    background-color: #333; /* Modern Browsers */
}

 
    #salto {page-break-after: always;}
 

</style>        
 
<script type="text/javascript">
    function  imprimir()
            {
                 document.getElementById("div0").style.display = "none";                            
                 document.getElementById("div1").style.display = "none";
                 document.getElementById("r").style.display = "none";          
                 window.print();                 
           }
</script>
 
 
    <form method="post"  id="_revision_premios" name="_revision_premios">  
    <div id='r' class=" alert alert-secondary">
    <h4 align="center">Selección de Sorteo para Emisión de Tarjeta de Premios Ganadores</h4>   
    <div id="div0"><p align="center">SORTEO No. <select id="slctsorteo" name="slctsorteo" style="width:30%; font-family: Arial; font-size: 10pt;"  class="form-control">
       <option>Seleccione Uno</option>
          <?php 
                    $result=mysqli_query($conn, " SELECT * FROM `sorteos_mayores` order by id DESC ");
                                            if (mysqli_num_rows($result)>0)
                                            {
                                             while ($row = mysqli_fetch_array($result))
                                              {
                                                echo "<option value = '".$row['id']."'>".$row['no_sorteo_may']."</option>";
                                              }
                                            } 
           ?>
          </select> <br><button type="submit" name="seleccionar" class="btn btn-info btn-lg"> Consultar</button></div></div>
          <?php 
 if (isset($_POST['seleccionar'])) 
 {

   $_sorteo=$_POST['slctsorteo'];
      echo '<div id="div1">
     <p align="center"> <button type="button" name="seleccionar1" onclick="imprimir()"  style="background-color: #139949;" class="btn btn-info btn-lg"><span class="glyphicon glyphicon-print"></span>  IMPRIMIR</button></p>
      </div>';
  ?> 
<table align="center">
 <tr>
 <td  width="20%">  
 </td>
 <td width="60%" style="font-family: Arial; font-size: 18pt;">  
  <img src="../../template/images/PANI_1.jpg" align="center"  border="0"  width="100%" />
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
  <label >Patronato Nacional de la Infancia PANI <br> Departamento de Revisión de Premios<br>Tarjeta de Premios Mayores, Sorteo No. <?php echo $_sorteo?></label>
  </div>
  </td>
  <td width="20%"></td>
</tr>      
 </table><br>
<?php 
             

                $query_info_sorteo=mysqli_query($conn, "SELECT id,  fecha_sorteo, cantidad_numeros cantidad, fecha_vencimiento caducidad FROM sorteos_mayores where id=$_sorteo; ");
                while ($row_sorteo= mysqli_fetch_array($query_info_sorteo)) 
                {
                   $caduca=$row_sorteo['caducidad'];
                   $cantidad=$row_sorteo['cantidad'];
                   $efectuado=$row_sorteo['fecha_sorteo'];
                }

                $requete = mysqli_query($conn, "SELECT sorteo, numero, registro, total FROM archivo_pagos_mayor WHERE sorteo=$_sorteo and (tipo_pago = 'U' or (tipo_pago='T' and total>600) or (tipo_pago='E')) order by total desc ;");    
                 if (mysqli_num_rows($requete)>0)
                 {

                  while ($row=mysqli_fetch_array($requete))
                  {
                     $billete=$row['numero'];
                     $registro=$row['registro'];
                     $monto=$row['total'];                  
                     $sorte=$row['sorteo'];

$consulta_tipo_premio =  mysqli_query($conn, "SELECT * FROM sorteos_mayores_premios WHERE sorteos_mayores_id = '$sorte' AND numero_premiado_mayor = '$billete' ");
$ob_tipo_premio = mysqli_fetch_object($consulta_tipo_premio);
$tipo_premio = $ob_tipo_premio->tipo_premio;
$desc_premio = $ob_tipo_premio->desc_premio;



      echo '<div id="reporte"><br><br><br><br>
    <input type="hidden" id ="usuario_txt"  name="usuario_txt" class="form-control" value="'.$usuario_id.'" /> 
    <table width="96%" style="margin-left:02%">
        <tr>
          <td width="25%" style="font-family: Arial; font-size: 10pt;">SORTEO No.: '.$sorte.'</td>
          <td width="20%" style="font-family: Arial; font-size: 10pt;">BILLETE No.: '.$billete.'</td>';

if ($tipo_premio != 'EFECTIVO') {
echo '    <td width="30%" style="font-family: Arial; font-size: 10pt;">PREMIO DE ESPECIE '.$desc_premio.' L.:'.money_format('%n', $monto).' </td>';
}else{
echo '    <td width="30%" style="font-family: Arial; font-size: 10pt;">PREMIADO CON L.:'.money_format('%n', $monto).'</td>';  
}

echo '    <td width="25%" style="font-family: Arial; font-size: 10pt;">REGISTRO No.:'.$registro.'</td>
        </tr>
        <tr>
          <td width="25%" style="font-family: Arial; font-size: 10pt;">EFECTUADO:'.$efectuado.'</td>
          <td width="20%" style="font-family: Arial; font-size: 10pt;">CADUCA: '.$caduca.'</td>
          <td width="30%" style="font-family: Arial; font-size: 10pt;"> &nbsp;&nbsp;&nbsp;  EMISION: '.$cantidad.' BILLETES</td>          
          <td width="25%"></td>
        </tr>
    </table><br><br>

   <table  width="96%" style="margin-left:02%" id="table" name="table" class="tarjeta" >
     <tr  >
     <td width="20%" > <p align="center">1</p><button type="button" class="btn btn-default btn-block" style="height:160px; border: 0.5px solid;"></button></td>
     <td width="20%" > <p align="center">2</p><button type="button" class="btn btn-default btn-block" style="height:160px; border: 0.5px solid;"></button></td>
     <td width="20%" > <p align="center">3</p><button type="button" class="btn btn-default btn-block" style="height:160px; border: 0.5px solid;"></button></td>
     <td width="20%" > <p align="center">4</p><button type="button" class="btn btn-default btn-block" style="height:160px; border: 0.5px solid;"></button></td>
     <td width="20%" > <p align="center">5</p><button type="button" class="btn btn-default btn-block" style="height:160px; border: 0.5px solid;"></button></td>
     </tr>
    <tr>
    <td></td>
    </tr>
     <tr>
     <td width="15%" > <p align="center">6</p> <button type="button" class="btn btn-default btn-block" style="height:160px; border: 0.5px solid;"></button></td>
     <td width="15%" > <p align="center">7</p> <button type="button" class="btn btn-default btn-block" style="height:160px; border: 0.5px solid;"></button></td>
     <td width="15%" > <p align="center">8</p> <button type="button" class="btn btn-default btn-block" style="height:160px; border: 0.5px solid;"></button></td>
     <td width="15%" > <p align="center">9</p> <button type="button" class="btn btn-default btn-block" style="height:160px; border: 0.5px solid;"></button></td>
     <td width="15%" > <p align="center">10</p><button type="button" class="btn btn-default btn-block" style="height:160px; border: 0.5px solid;"></button></td>
     </tr>
   </table><br><br>

   <table style="width:96%" >
    <tr >
       <td width="20%" align="right">Vo.Bo.</td> <td width="25%"> <hr>  </td> <td width="10%" align="right">Vo.Bo.</td> <td width="25%"><hr></td><td width="20%"></td> 
     </tr>
     <tr>
       <td width="20%"></td> <td width="25%" align="center">GERENCIA DE LOTERIA NACIONAL</td> <td width="10%"></td> <td width="25%" align="center">GERENCIA FINANCIERA</td><td width="20%"></td> 
     </tr><br>
  </table>
      </div>
<div id="salto" style="page-break-after: always;"></div>
      <br><br>';
      }
    }
                 else
                 {
                  mysql_error();
                 } 


  }

           ?></p>
    <br><br>
    
    </div>
    </form><br>
 