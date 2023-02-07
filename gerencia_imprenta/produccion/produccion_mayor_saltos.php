<?php

require("../../conexion.php");

$id_sorteo        = $_GET['s'];
$registro_inicial = $_GET['r_i'];
$patron_salto     = $_GET['p_s'];

$info_mayor = mysqli_query($conn,"SELECT * FROM sorteos_mayores where id = '$id_sorteo' ");
$value_mayor = mysqli_fetch_object($info_mayor);
$cantidad_billetes = $value_mayor->cantidad_numeros;
$sorteo = $value_mayor->no_sorteo_may;
$fecha = $value_mayor->fecha_sorteo;

$consulta_saltos = mysqli_query($conn,"SELECT * FROM sorteos_mayores_produccion WHERE id_sorteo = '$id_sorteo' ");

$masc = strlen($cantidad_billetes);
$masc_rec = strlen($registro_inicial);

$num_saltos = ($cantidad_billetes - 1)/$patron_salto;

$i = 1;
if (mysqli_num_rows($consulta_saltos) > 0) {



while ($reg_saltos = mysqli_fetch_array($consulta_saltos)) {
echo "

<div class='input-group' style='margin:10px 0px 10px 0px;'>
<div class='input-group-prepend'><span style='width: 140px' class='input-group-text'>Salto ".$i."</span></div>
<input class = 'form-control' onblur = 'validar_terminacion(this.value,".$i.")' name = 'salto".$i."' id = 'salto".$i."' type = 'text' value = '".$reg_saltos['salto']."' required>
</div>

";
$i++;
}

}else{

$valor = 1;
while ($i <= $num_saltos) {
echo "
<div class='input-group' style='margin:10px 0px 10px 0px;'>
<div class='input-group-prepend'><span style='width: 140px' class='input-group-text'>Salto ".$i."</span></div>
<input class = 'form-control' onblur = 'validar_terminacion(this.value,".$i.")' name = 'salto".$i."' id = 'salto".$i."' type = 'text' value = '".$valor."' required>
</div>
";
$i++;
}

}




?>	

<p align="center">
<input name="guardar" type="submit" class="btn btn-primary" value="Guardar">
</p>