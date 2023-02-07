
<?php 


//$conn = mysqli_connect('localhost', 'root', '', 'pani_new') or die('No se pudo conectar: ' . mysqli_error());
$conn = mysqli_connect('192.168.15.248:3306', 'SVR_APP', 'softlotpani**', 'pani') or die('No se pudo conectar: ' . mysqli_error($conn));

$id_sorteo = $_GET['s'];
$index = $_GET['i'];


$c_asignados = mysqli_query($conn, "SELECT * FROM sorteos_menores_incentivos WHERE id_sorteo = '$id_sorteo' AND id_vendedor != '' ");


if (mysqli_num_rows($c_asignados) > 0) {

$concat_asignados = "";
while ($reg_asignado = mysqli_fetch_array($c_asignados)) {
$concat_asignados .=  "'".$reg_asignado['id_vendedor']."',";

}


$concat_asignados = substr($concat_asignados, 0,-1);


$c_vendedores = mysqli_query($conn, "SELECT * FROM sorteos_menores_otros_incentivos_tickets WHERE id_sorteo = '$id_sorteo' AND identidad NOT IN ($concat_asignados)  ");

echo mysqli_error($conn);

}else{

$c_vendedores = mysqli_query($conn, "SELECT * FROM sorteos_menores_otros_incentivos_tickets WHERE id_sorteo = '$id_sorteo' ");

}



$i = 0;
while ($r_vendedores = mysqli_fetch_array($c_vendedores)) {

$info_vendedor = array("identidad" => $r_vendedores['identidad'],"nombre" => $r_vendedores['nombre_completo'],"ticket" => $r_vendedores['id'],"sorteos_activos" => $r_vendedores['sorteos_activos']);

$v_tickets[$i] = $info_vendedor;


$i++;
}

$i--;
$ganador = rand(0, $i);

$identidad_ganador = $v_tickets[$ganador]["identidad"];
$nombre_ganador = $v_tickets[$ganador]["nombre"];
$nombre_ganador = utf8_decode($nombre_ganador);
$ticket_ganador = $v_tickets[$ganador]["ticket"];
$media_ganador = $v_tickets[$ganador]["sorteos_activos"];

mysqli_query($conn, "UPDATE sorteos_menores_incentivos SET id_vendedor = '$identidad_ganador', ticket_electronico = '$ticket_ganador', nombre_vendedor = '$nombre_ganador' WHERE id = '$index' ");


$path_foto = "./imagenes/vendedores/".$identidad_ganador.".jpg";

if (file_exists($path_foto)) {
$foto = $path_foto;
}else{
$foto = "./imagenes/default_foto.png";
}


$query_desc= mysqli_query($conn, "SELECT descripcion_incentivo FROM sorteos_menores_incentivos WHERE id=$index");
				 $obj_desc=mysqli_fetch_object($query_desc);
				 $descrip_incentivo=$obj_desc->descripcion_incentivo;


?>

<script type="text/javascript">
	document.getElementById("identidad-<?php echo $index; ?>").value = "<?php echo $identidad_ganador; ?>";
	document.getElementById("nombre-<?php echo $index; ?>").value = "<?php echo $nombre_ganador; ?>";
	document.getElementById("ticket-<?php echo $index; ?>").value = "<?php echo $ticket_ganador; ?>";
	document.getElementById("premio-<?php echo $index; ?>").value = "<?php echo $descrip_incentivo; ?>";

	document.getElementById("boton_random-<?php echo $index; ?>").remove();

	document.getElementById("icon-<?php echo $index; ?>").classList.remove('fa-exclamation-circle');

	document.getElementById("icon-<?php echo $index; ?>").classList.add('fa-check-circle');

	document.getElementById("vista_previa-<?php echo $index; ?>").src = "<?php echo $foto; ?>";

$(".div_wait").fadeOut("fast");

</script>
<?php 


 ?>