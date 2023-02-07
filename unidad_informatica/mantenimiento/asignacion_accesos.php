<?php 

require('../../conexion.php');

$user    = $_GET['u'];
$id_user = $_GET['i'];

$gerencia = $_GET['g'];
$depto    = $_GET['d'];


?>



	
<?php

$accesos = mysqli_query($conn, "SELECT * , if( t1.id IN (select id_acceso from pani_usuarios_accesos t where t.usuario = '$user' ), 'yes', 'no') as asignado 
  FROM accesos t1 WHERE gerencia = '$gerencia' AND depto = '$depto' ORDER BY descripcion_menu ");

$i = 0;
while ($reg_acceso = mysqli_fetch_array($accesos)) {
echo "<tr>";
echo "<td><input type = 'hidden' name = 'id_acceso".$i."' value = '".$reg_acceso['id']."' >  ".$reg_acceso['gerencia']."</td>";
echo "<td>".$reg_acceso['depto']."</td>";
echo "<td>".$reg_acceso['pantalla']."</td>";
echo "<td>".$reg_acceso['descripcion_menu']."</td>";
if ($reg_acceso['asignado'] == "no") {
echo "<td align = 'center'><input name = 'check".$i."' class = 'form-control' type ='checkbox' style = 'width: 30px; height: 30px;' ></td>";
}else{
echo "<td align = 'center'><input name = 'check".$i."' class = 'form-control' type ='checkbox' style = 'width: 30px; height: 30px;' checked></td>";	
}
echo "</tr>";

$i++;
}

?>