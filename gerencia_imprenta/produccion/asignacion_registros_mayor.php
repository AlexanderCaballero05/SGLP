<?php

require('../../conexion.php');

$id_s = $_GET['id_s'];

$info_mayor = mysqli_query($conn,"SELECT * FROM sorteos_mayores where id = '$id_s' ");
$value_mayor = mysqli_fetch_object($info_mayor);
$cantidad_billetes = $value_mayor->cantidad_numeros;
$registro_inicial = $value_mayor->desde_registro;
$patron_salto = $value_mayor->patron_salto;
$sorteo = $value_mayor->no_sorteo_may;
$fecha = $value_mayor->fecha_sorteo;


$masc = strlen($cantidad_billetes);
$masc_rec = strlen($registro_inicial);


$parametros_mayor = mysqli_query($conn,"SELECT * FROM sorteos_mayores_produccion where id_sorteo = '$id_s' ");


$i = 1;
while ($row = mysqli_fetch_array($parametros_mayor)) {
$v_salto[$i] = $row['salto'];
$i++;
}
$num_saltos = $cantidad_billetes/$patron_salto;


$i = 0;
$j = 1;
$acumulador_salto = 0;
$indicador = false;
$billete_i = 0;
$b_i = 0;
$billete_f =  999;
$b_f = 999;
$registro = $registro_inicial;
$registro_i = $registro_inicial;
$r_i = $registro_i;
$registro_f = $registro_i - 999;
$r_f = $registro_f;


$busqueda = mysqli_query($conn,"SELECT * FROM sorteos_mayores_registros WHERE id_sorteo = $id_s LIMIT 1 ");

$conteo = mysqli_num_rows($busqueda);
if ($conteo == 1) {

mysqli_query($conn,"DELETE FROM sorteos_mayores_registros WHERE id_sorteo = '$id_s' ");

}


$input = 0;

$acum = 0;
while ($i  < $cantidad_billetes) {


if ($acumulador_salto == $patron_salto) {
$indicador = true;


$b_f = $billete_f - 1000;
$r_f = $registro_f + 1000;


$dif = $b_f - $b_i; 
$acum = $acum  + $dif + 1;



$b_i = $b_i;
$b_f = $b_f;

$r_i = $r_i;
$r_f = $r_f;



mysqli_query($conn,"INSERT INTO sorteos_mayores_registros (id_sorteo,billete,billete_final,registro) VALUES ($sorteo, '$b_i','$b_f', '$r_i') ");


$input++;

$registro = $registro - $v_salto[$j] + 1;
$registro_i = $registro;
$registro_f = $registro_i - 999;


$b_i = $b_f + 1;
$r_i = $registro_i;


$j ++;
$acumulador_salto = 0;
}


if ($indicador == true) {

$indicador = false;
}else{
}   

$i = $i + 1000;
$acumulador_salto = $acumulador_salto + 1000;

$billete_i = $billete_i + 1000;
$billete_f = $billete_i + 999 ;
$registro = $registro - 1000;
$registro_i = $registro;
$registro_f = $registro_i - 999;


}

$b_f = $billete_f - 1000;
$r_f = $registro_f + 1000;

$b_i = str_pad($b_i, $masc, '0', STR_PAD_LEFT);
$b_f = str_pad($b_f, $masc, '0', STR_PAD_LEFT);

$r_i = str_pad($r_i, $masc_rec, '0', STR_PAD_LEFT);
$r_f = str_pad($r_f, $masc_rec, '0', STR_PAD_LEFT);



if ($acum != $cantidad_billetes) {

mysqli_query($conn,"INSERT INTO sorteos_mayores_registros (id_sorteo,billete, billete_final,registro) VALUES ($sorteo, '$b_i', '$b_f', '$r_i') ");


$input++;

}


?>

<script type="text/javascript">
    window.location.href = './screen_produccion_sorteos.php';
</script>