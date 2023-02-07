<?php


date_default_timezone_set("America/Tegucigalpa");

//$conn = mysqli_connect('localhost', 'root', '', 'pani_new') or die('No se pudo conectar: ' . mysqli_error());
$conn = mysqli_connect('192.168.15.248:3306', 'SVR_APP', 'softlotpani**', 'pani') or die('No se pudo conectar: ' . mysqli_error($conn));

$id_sorteo = 3320;

$c_vendedores = mysqli_query($conn, " SELECT identidad, nombre ,telefono , parametro , departamento, municipio FROM vendedores_incentivos WHERE sorteo = '$id_sorteo'   ");

echo "REGISTRANDO....";


while ($reg_vendedores = mysqli_fetch_array($c_vendedores)) {

    $identidad = $reg_vendedores['identidad'];
    $nombre = $reg_vendedores['nombre'];
    $telefono = $reg_vendedores['telefono'];
    $parametro = $reg_vendedores['parametro'];
    $departamento = $reg_vendedores['departamento'];
    $municipio = $reg_vendedores['municipio'];

    mysqli_query($conn, "INSERT INTO sorteos_menores_otros_incentivos_tickets (identidad, nombre_completo, telefono, sorteos_activos, municipio, departamento, id_sorteo) VALUES ('$identidad', '$nombre', '$telefono', '$parametro', '$municipio', '$departamento', '$id_sorteo') ");

    $divicion = round($parametro / 4);
    $divicion --;
    while ($divicion > 0) {    
        mysqli_query($conn, "INSERT INTO sorteos_menores_otros_incentivos_tickets (identidad, nombre_completo, telefono, sorteos_activos, municipio, departamento, id_sorteo) VALUES ('$identidad', '$nombre', '$telefono', '$parametro', '$municipio', '$departamento', '$id_sorteo') ");
        $divicion--;
    }

}



echo "REGISTRADO";

?>