<?php
require('../../template/header.php');
$select_sorteos = mysqli_query($conn,"SELECT * FROM sorteos_mayores WHERE control_calidad = 'SI' ORDER BY no_sorteo_may DESC ");




if (isset($_POST['registrar_re'])) {

$id_sorteo  = $_POST['id_sorteo_o'];
$id_usuario = $_POST['id_usuario_o'];

$i = 0;
while (isset($_POST['billete_o'][$i])) {

$id_revisor =  $_POST['id_revisor_o'][$i];
$billete    =  $_POST['billete_o'][$i];
$registro   =  $_POST['registro_o'][$i];



if (mysqli_query($conn, "INSERT INTO reposiciones_especiales_mayor (id_sorteo, billete, registro, id_revisor, id_usuario) VALUES ('$id_sorteo','$billete','$registro','$id_revisor','$id_usuario') ")=== FALSE) {
echo mysqli_error($conn);
}

$i++;
}

echo "<div class = 'alert alert-info'>Reposiciones especiales registradas correctamente.</div>";

}


?>



<form method="POST">


<section style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >
<b>DEPARTAMENTO DE CONTROL DE CALIDAD PANI</b>
<p>REPOSICIONES ESPECIALES LOTERIA MAYOR</p>
</h2> 
</section>
<br>



<div class="card" style="margin-left: 5px;margin-right: 5px">
  <div class="card-header" id="non-printable" align="center">

<div class="input-group" style="margin:10px 0px 10px 0px; width: 50%" >
<div class="input-group-prepend"><span  class="input-group-text">Seleccione un sorteo: </span></div>
 <select class="form-control" name="sorteo">
   <?php
   while ($sorteo = mysqli_fetch_array($select_sorteos)) {
     echo "<option value = '".$sorteo['id']."'>".$sorteo['no_sorteo_may']."</option>";
   }
   ?>
 </select> 

<input type="submit" name="seleccionar" class="btn btn-primary" value = "Seleccionar">
</div>
    
  </div>
  <div class="card-body">
    

<?php

if (isset($_POST['seleccionar'])) {
$id_sorteo = $_POST['sorteo'];
$id_usuario = $_SESSION['id_usuario'];


$info_sorteo = mysqli_query($conn,"SELECT * FROM sorteos_mayores WHERE id = '$id_sorteo' ");
$ob_sorteo = mysqli_fetch_object($info_sorteo);
$no_sorteo = $ob_sorteo->no_sorteo_may;
$fecha_sorteo = $ob_sorteo->fecha_sorteo;
$cantidad_billetes = $ob_sorteo->cantidad_numeros;
$registro_inicial = $ob_sorteo->desde_registro;
$patron_salto = $ob_sorteo->patron_salto;
$fecha_vencimiento = $ob_sorteo->fecha_vencimiento;

$fecha_sorteo = date("d-m-Y", strtotime($fecha_sorteo));
$fecha_vencimiento = date("d-m-Y", strtotime($fecha_vencimiento));

$inventario_rechazado = mysqli_query($conn,"SELECT a.billete, a.registro, a.especial, b.id as id_revisor ,  b.nombre_completo FROM cc_revisores_sorteos_mayores_control as a  INNER JOIN pani_usuarios as b ON a.id_revisor = b.id WHERE a.id_sorteo = '$id_sorteo' AND a.especial = 'SI'  AND numero_revision = 2  GROUP BY a.billete ASC  ");



$c_pendientes = mysqli_query($conn, "SELECT * FROM cc_revisores_sorteos_mayores_control WHERE id_sorteo = '$id_sorteo' AND estado = 'PENDIENTE' ");

$finalizado = "SI";
if (mysqli_num_rows($c_pendientes) > 0) {
$finalizado = "NO";
}



$total_re = mysqli_num_rows($inventario_rechazado);

if ($finalizado == "SI") {
if ($total_re > 0) {

$c_re_existentes = mysqli_query($conn, "SELECT * FROM reposiciones_especiales_mayor WHERE id_sorteo = '$id_sorteo' ");

if (mysqli_num_rows($c_re_existentes) > 0) {

echo "<div class = 'alert alert-danger' id = 'non-printable'>Ya se registraron previamente las reposiciones especiales de este sorteo</div>";

}else{

echo "<div class = 'alert alert-danger' align = 'center' ><b>Nota:</b> Aun no se ha confirmado el registro de reposiciones especiales, para hacerlo verifique que cada billete mostrado en pantalla es correcto y de clic con el mouse en el siguiente boton
<br>
<button type = 'submit' name = 'registrar_re' class = 'btn btn-danger'>Registrar listado de R.E. </buttom>

</div>";

}

}
}else{

echo "<div class = 'alert alert-danger'><a href = './reporte_cc_envio_produccion_mayor.php' >Aun existen reposiciones sin finalizar, por favor de clic en este texto para ir a pantalla de finalización de reposiciones.</a></div>";

}




date_default_timezone_set('America/Tegucigalpa');
$fecha = date("d-m-Y");


echo "<div class = 'alert alert-info' align = 'center'>";
echo "<b>MEMORANDUM</b>";
echo "</div>";


echo "<br>";

echo "<table style = 'font-size:18px' width = '100%'>

<tr>
<td width = '5%'  valign = 'center'>Fecha:</td>
<td width = '80%' valign = 'center' ><u>".$fecha."</u></td>
<td width = '15%'><br><br> </td>
</tr>


<tr>
<td width = '5%'  valign = ''>De:</td>
<td width = '80%'  align = '' ><u>ABOG. JUAN BAUTISTA IZAGUIRRE </u><p > Jeje Depto. Control de Calidad</p></td>
<td width = '15%'></td>
</tr>

<tr>
<td width = '5%'  valign = 'center'>Para:</td>
<td width = '80%'  align = '' ><u> LIC. OLGA SUYAPA AVILA GALO  </u><p > Coordinador Proyecto Loteria Nacional BANRURAL</p></td>
<td width = '15%'></td>
</tr>


</table><br>";


echo "<p style = 'font-size:18px'>Sorteo Loteria Mayor No. ".$no_sorteo."</p>";
echo "<p style = 'font-size:18px'>A realizarse el  ".$fecha_sorteo." con fecha de caducidad ".$fecha_vencimiento."</p>";

echo "<p style = 'font-size:18px' align = 'justify'> Por medio del presente informo a usted, que se solicitó al Departamento de Producción las Reposiciones (RE), recibidas por los siguientes revisores:
</p>";

?>

<input type="hidden" name="id_sorteo_o"  value = '<?php echo $id_sorteo; ?>'>
<input type="hidden" name="id_usuario_o"  value = '<?php echo $id_usuario; ?>'>

<div id='print'>
<table  class='table table-bordered table-sm' style="font-size: 18px;" id='detalle_revisor'  style= 'width:100%'>


  <thead>
    <tr>
      <th style="width:50%">Revisor</th>
      <th style="width:25%">Billete</th>
      <th style="width:25%">Registro</th>
    </tr>
  </thead>
  <tbody>

<?php

$total_re = 0;
while ($rechazado = mysqli_fetch_array($inventario_rechazado)) {

$billete_inicial = $rechazado['billete'];

echo "<tr  >";
echo "<td><input type = 'hidden' name = 'id_revisor_o[]' value = '".$rechazado['id_revisor']."'>".$rechazado['nombre_completo']."</td>";
echo "<td><input type = 'hidden' name = 'billete_o[]' value = '".$billete_inicial."'>".$billete_inicial."</td>";
echo "<td>";
echo "<input type = 'hidden' name = 'registro_o[]' value = '".$rechazado['registro']."'> ".$rechazado['registro']."";
echo "</td>";
echo "</tr>";


$total_re ++;
}



?>

</tbody>
</table>


<br>
<br>

CC. Dirección Ejecutiva <br>
CC. Sub. Dirección Ejecutiva <br>
CC. Gerencia Financiera <br>
CC. Gerencia Comercialización <br>
CC. Jefe de Ventas <br>
CC. Auditoria Interna <br>
CC. Informatica <br>
CC. Revisión de Premios <br>
CC. Producción <br>
CC. Archivo <br>



<?php 

}

?>

  </div>
</div>


</form>
