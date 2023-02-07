<?php

require("../../conexion.php");


$id_sorteo = $_GET['id_s'];

$sorteos = mysqli_query($conn,"SELECT * FROM sorteos_menores ORDER BY no_sorteo_men");


$result = mysqli_query($conn,"SELECT * FROM sorteos_menores WHERE id = '$id_sorteo'");
 
if ($result != null){
while ($row = mysqli_fetch_array($result)) {
$sorteo = $row['no_sorteo_men'] ;
$fecha_sorteo = $row['fecha_sorteo'] ;
$series = $row['series'] -1;
$desde_registro = $row['desde_registro'];
$descripcion = $row['descripcion_sorteo_men'];
}
$masc = strlen($series);
}

$saltos = mysqli_query($conn,"SELECT * FROM sorteos_menores_produccion WHERE id_sorteo = '$id_sorteo' ");
if ($saltos === false) {
echo mysqli_error($conn);
}
$i = 1;
while ($reg_saltos = mysqli_fetch_array($saltos)) {
$v_saltos[$i] = $reg_saltos['salto']; 
$v_decena[$i] = $reg_saltos['decena']; 
$i++;
}



$max_extra  = mysqli_query($conn,"SELECT MAX(cantidad) as maximo FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo'");
if (mysqli_num_rows($max_extra) == 0) {
$cantidad_extra_mayor =  0; 
}else{
$ob_extra = mysqli_fetch_object($max_extra); 
$cantidad_extra_mayor =  $ob_extra->maximo; 
}



$busqueda = mysqli_query($conn,"SELECT * FROM sorteos_menores_registros WHERE id_sorteo = $id_sorteo LIMIT 1 ");

$conteo = mysqli_num_rows($busqueda);
if ($conteo == 1) {

mysqli_query($conn," DELETE FROM sorteos_menores_registros WHERE id_sorteo = '$id_sorteo' ");

}


if (isset($desde_registro)) {

$i = 0;
$n_inicial = 0;
$n_final = 0;
$registro = $desde_registro;
$registro_inicial = $desde_registro;
$registro_final = $registro_inicial + $series;


while ($i < 10) {

$n_inicial = $i * 10;
$n_final = $n_inicial + 9;


if (isset($v_saltos[$i])) {
$registro_adicional = $v_saltos[$i];
}else{
$registro_adicional = 0; 
}

$registro_inicial = $registro_inicial + $registro_adicional;
$registro_final = $registro_inicial + $series;


if ($registro_inicial  > 99999) {
$sobrante = $registro_inicial - 100000;
$registro_inicial = $sobrante;
}

if ($registro_final  > 99999) {
$sobrante = $registro_final - 100000;
$registro_final = $sobrante;
}


$n_inicial = $n_inicial;
$n_final = $n_final;


$registro_inicial = str_pad($registro_inicial, 5, '0', STR_PAD_LEFT);
$registro_final = str_pad($registro_final, 5, '0', STR_PAD_LEFT);

while ($n_inicial <= $n_final) {

//echo $sorteo." ".$n_inicial." 0 ".$series." ".$registro_inicial." ".$registro_final."<br>";

mysqli_query($conn,"INSERT INTO sorteos_menores_registros (id_sorteo,numero,serie_inicial,serie_final,registro_inicial,registro_final) VALUES ($sorteo, $n_inicial, 0, $series, $registro_inicial, $registro_final) ");

$n_inicial++;
}

$i = $i + 1; 


if (isset($v_saltos[1])) {
if ($v_saltos[1] == 0) {
$registro_inicial = $registro_final + 1 + $cantidad_extra_mayor;
}else{
$registro_inicial = $registro_final + 1;
}
}else{
$registro_inicial = $registro_final + 1 + $cantidad_extra_mayor;  
}

$registro_final = $registro_inicial + $series;

}
}


?>

<script type="text/javascript">
    window.location.href = './screen_produccion_sorteos_menor.php';
</script>