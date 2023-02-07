<?php

$id_sorteo = $_SESSION['procesar_mayor'];

$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE id =  '$id_sorteo' ");

while ($row2 = mysqli_fetch_array($info_sorteo)) {
	$num_sorteo = $row2['no_sorteo_may'];
	$cantidad = $row2['cantidad_numeros'];
	$fecha = $row2['fecha_sorteo'];
	$descripcion = $row2['descripcion_sorteo_may'];
	$mezcla = $row2['mezcla'];

}

$masc = strlen($cantidad);

$detalle_mezclas = mysqli_query($conn, "SELECT * FROM sorteos_mezclas WHERE id_sorteo=  '$id_sorteo' ");

$i = 0;
while ($row1 = mysqli_fetch_array($detalle_mezclas)) {
	$v_mezclas[$i] = $row1['num_mezcla'];
	$i++;
}

$detalle_rangos = mysqli_query($conn, "SELECT * FROM sorteos_mezclas_rangos WHERE id_sorteo =  '$id_sorteo' ");

$i = 0;
while ($row3 = mysqli_fetch_array($detalle_rangos)) {
	$v_rangos[$i] = $row3['rango'];
	$i++;
}

?>