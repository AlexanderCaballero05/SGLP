<?php 

require('../../conexion.php');

$gerencia = $_GET['g'];
$depto = $_GET['d'];

$usuario_accesos = mysqli_query($conn, "SELECT * FROM accesos WHERE gerencia = '$gerencia' AND depto = '$depto' ORDER BY depto , posicion ");

while ($user_access = mysqli_fetch_array($usuario_accesos)) {
echo "<tr>";
echo "<td>".$user_access['gerencia']."</td>";
echo "<td>".$user_access['depto']."</td>";
echo "<td>".$user_access['pantalla']."</td>";
echo "<td> <input type = 'text' name = 'editar_nombre_".$user_access['id']."' class = 'form-control' value = '".$user_access['descripcion_menu']."' ></td>";
echo "<td> <input type = 'text' name = 'editar_posicion_".$user_access['id']."' class = 'form-control' value = '".$user_access['posicion']."' ></td>";
echo "<td align = 'center'>

<button class = 'btn btn-info fa fa-edit' type = 'submit' name = 'editar_acceso' value = '".$user_access['id']."' ></button>
<button class = 'btn btn-danger fa fa-times-circle' type = 'submit' name = 'eliminar_acceso' value = '".$user_access['id']."' >

</button></td>";
echo "</tr>";
}

?>
