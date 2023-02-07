<?php 

require("../../conexion.php");

$parametros      = $_GET['p'];
$billete_inicial = $_GET['bi'];
$billete_final   = $_GET['bf'];
$status_re       = $_GET['re'];

$v_parametros = explode("_", $parametros);
$id_sorteo    = $v_parametros[0];
$id_revisor   = $v_parametros[1];
$num_asignado = $v_parametros[2];
$num_revision = $v_parametros[3];

$info_mayor 	   = mysqli_query($conn, "SELECT * FROM sorteos_mayores where id = '$id_sorteo' ");
$value_mayor 	   = mysqli_fetch_object($info_mayor);
$cantidad_billetes = $value_mayor->cantidad_numeros;
$registro_inicial  = $value_mayor->desde_registro;
$patron_salto 	   = $value_mayor->patron_salto;


if ($num_revision == 1) {

$inventario_revisor = mysqli_query($conn,"SELECT * FROM cc_revisores_sorteos_mayores WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$id_revisor' AND numero = '$num_asignado' ");

$a = 0;
while ($reg_inventario_revisor = mysqli_fetch_array($inventario_revisor)) {

$v_asignado_i[$a] = $reg_inventario_revisor['billete_inicial'];
$v_asignado_f[$a] = $reg_inventario_revisor['billete_final'];

$a++;
}

}



$verificar_reprobado = $num_revision + 1;
$loteria_reporbada   = mysqli_query($conn ," SELECT distinct billete,estado,id FROM cc_revisores_sorteos_mayores_control WHERE id_sorteo = '$id_sorteo'  AND id_revisor = '$id_revisor' AND numero_revision = '$verificar_reprobado' ");

$i = 0;
while ($reg_loteria_reporbada = mysqli_fetch_array($loteria_reporbada)) {
$v_reprobado[$i] = $reg_loteria_reporbada['billete'];
$i++;
}



$parametros_mayor = mysqli_query($conn, "SELECT * FROM sorteos_mayores_produccion where id_sorteo = '$id_sorteo' ");

$i = 1;
while ($reg = mysqli_fetch_array($parametros_mayor)) {
$v_salto[$i] = $reg['salto'];
$i++;
}


echo "<table class = 'table table-bordered' >";
echo "<tr><th>Billete</th><th>Registro</th><th>R. E.</th></tr>";

$h = 0;
$j = 1;
$cantidad = $billete_final - $billete_inicial + 1;
$asignado = true;

while ($billete_inicial <= $billete_final) {


$num_saltos = $billete_inicial/$patron_salto;
$num_saltos = floor($num_saltos);

$k = 1;
$acumulador = 0;
while ($k <= $num_saltos) {
if (isset($v_salto[$k])) {
$acumulador = $acumulador + $v_salto[$k] - 1;
}
$k++;
}

$registro = $registro_inicial - $acumulador ;
$registro = $registro - $billete_inicial;

$a = 0;
while (isset($v_asignado_i[$a])) {

if ($billete_inicial < $v_asignado_i[$a] OR $billete_inicial > $v_asignado_f[$a] ) {
$asignado = false;
}

$a++;
}

echo "<tr>";

if (isset($v_reprobado[$h])) {

if (in_array($billete_inicial, $v_reprobado)) {

echo "<td colspan = '3'><div class = 'alert alert-danger'>El billete ".$billete_inicial." ya fue reprobado.</div></td>";

$cantidad --;

}else{

echo "<td><input type = 'text' class = 'form-control' name = 'billete_reprobado[]'  value  = '".$billete_inicial."' readonly></td>";
echo "<td><input type = 'text' class = 'form-control' name = 'registro_reprobado[]' value  = '".$registro."' readonly></td>";

if ($status_re === "true") {
echo "<td><input type = 'checkbox' name = 're_reprobado".$j."' class = 'form-control' checked></input> </td>";
}else{
echo "<td><input type = 'checkbox' name = 're_reprobado".$j."' class = 'form-control' > </input></td>";
}

}

}else{

echo "<td><input type = 'text' class = 'form-control' name = 'billete_reprobado[]'  value  = '".$billete_inicial."' readonly></td>";
echo "<td><input type = 'text' class = 'form-control' name = 'registro_reprobado[]' value  = '".$registro."' readonly></td>";

if ($status_re === "true") {
echo "<td><input type = 'checkbox' name = 're_reprobado".$j."' class = 'form-control' checked></input> </td>";
}else{
echo "<td><input type = 'checkbox' name = 're_reprobado".$j."' class = 'form-control' > </input></td>";
}

}



echo "</tr>";

$j++;
$h++;
$billete_inicial++;
}


echo "<tr><th >TOTAL BILLETES A REPROBAR</th><th colspan = '2' style = 'text-align :center'>".$cantidad."</th></tr>";
echo "</table>";

if ($asignado === true) {

?>

<script type="text/javascript">
	document.getElementById('reprobar_rango').disabled = false;
</script>

<?php

}else{

?>

<script type="text/javascript">
document.getElementById('reprobar_rango').disabled = true;

  swal({ 
  title: "Al menor uno de los billetes a reprobar no le fue asignado.",
   text: "",
    type: "error" 
  });

</script>

<?php

}

?>
