<?php
require('../../template/header.php');

$parametros = $_GET['par'];
$vector = explode("_", $parametros);

$id_sorteo = $vector[0];
$rev =  $vector[1] - 1;


echo "<div class = 'alert alert-info' align = 'center'>";
echo "<b>DEPARTAMENTO DE CONTROL DE CALIDAD (PANI)</b>";
echo "<p>REPOSICIONES LOTERIA MAYOR</p>";
echo "</div>";
echo "<br>";

echo "<div>";
echo "<table border = '1' class = 'table table-bordered' style= 'width:100%'>";

?>
  <thead>
    <tr>
      <th style="width:33.33%">Numero</th>
      <th style="width:33.34%">Registro</th>
      <th style="width:33.33%">Cantidad</th>
    </tr>
  </thead>
  <tbody>

<?php
$rev = $rev + 1;

$inventario_rechazado = mysqli_query($conn, "SELECT * FROM cc_revisores_sorteos_mayores_control WHERE id_sorteo = '$id_sorteo'   AND numero_revision = '$rev' ORDER BY billete ASC ");

if ($inventario_rechazado===false) {
echo mysqli_error($conn);
}

while ($inventario_r = mysqli_fetch_array($inventario_rechazado)) {
echo "<tr>";
echo "<td>".$inventario_r['billete']."</td>";
echo "<td>";
echo "</td>";
echo "<td>";
echo "</td>";
echo "</tr>";
}
?>

  </tbody>

<?php

echo "</table>";
echo "</div>";
	

?>