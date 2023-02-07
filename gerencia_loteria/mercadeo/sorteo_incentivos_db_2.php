
<?php 


//$conn = mysqli_connect('localhost', 'root', '', 'pani_new') or die('No se pudo conectar: ' . mysqli_error());
$conn = mysqli_connect('192.168.15.248:3306', 'SVR_APP', 'softlotpani**', 'pani') or die('No se pudo conectar: ' . mysqli_error());

$id_sorteo = $_GET['s'];
$index = $_GET['i'];


$c_asignados = mysqli_query($conn, "SELECT * FROM sorteos_menores_incentivos WHERE id_sorteo = '$id_sorteo' AND id_vendedor != '' ");


if (mysqli_num_rows($c_asignados) > 0) {

$concat_asignados = "";
while ($reg_asignado = mysqli_fetch_array($c_asignados)) {
$concat_asignados .=  "'".$reg_asignado['id_vendedor']."',";

}


$concat_asignados = substr($concat_asignados, 0,-1);


$c_vendedores = mysqli_query($conn, "SELECT * FROM sorteos_menores_incentivos_tickets WHERE id_sorteo = '$id_sorteo' AND identidad_comprador NOT IN ($concat_asignados)  ");

echo mysqli_error($conn);

}else{

$c_vendedores = mysqli_query($conn, "SELECT * FROM sorteos_menores_incentivos_tickets WHERE id_sorteo = '$id_sorteo' ");

}



$i = 0;
while ($r_vendedores = mysqli_fetch_array($c_vendedores)) {

$info_vendedor = array("identidad" => $r_vendedores['identidad_comprador'],"nombre" => $r_vendedores['nombre_comprador'],"ticket" => $r_vendedores['id'],"media_compra" => $r_vendedores['media_compra'],"ultima_compra" => $r_vendedores['ultima_compra']);

$v_tickets[$i] = $info_vendedor;


$i++;
}

$i--;
$ganador = rand(0, $i);

$identidad_ganador = $v_tickets[$ganador]["identidad"];
$nombre_ganador = $v_tickets[$ganador]["nombre"];
$nombre_ganador = utf8_decode($nombre_ganador);
$ticket_ganador = $v_tickets[$ganador]["ticket"];
$media_ganador = $v_tickets[$ganador]["media_compra"];
$ultima_compra_ganador = $v_tickets[$ganador]["ultima_compra"];

mysqli_query($conn, "UPDATE sorteos_menores_incentivos SET id_vendedor = '$identidad_ganador', ticket_electronico = '$ticket_ganador', nombre_vendedor = '$nombre_ganador' WHERE id = '$index' ");


$path_foto = "./imagenes/vendedores/".$identidad_ganador.".jpg";

if (file_exists($path_foto)) {
$foto = $path_foto;
}else{
$foto = "./imagenes/default_foto.png";
}



$query_factura= mysqli_query($conn, "SELECT cod_factura_recaudador FROM transaccional_ventas_general WHERE cod_producto in (2,3) and  identidad_comprador='$identidad_ganador' and id_sorteo=$id_sorteo");
				 $obj_factura=mysqli_fetch_object($query_factura);
				 $factura_asociada=$obj_factura->cod_factura_recaudador;

$query_desc= mysqli_query($conn, "SELECT descripcion_incentivo FROM sorteos_menores_incentivos WHERE id=$index");
				 $obj_desc=mysqli_fetch_object($query_desc);
				 $descrip_incentivo=$obj_desc->descripcion_incentivo;


?>

<script type="text/javascript">
	document.getElementById("identidad-<?php echo $index; ?>").value = "<?php echo $identidad_ganador; ?>";
	document.getElementById("nombre-<?php echo $index; ?>").value = "<?php echo $nombre_ganador; ?>";
	document.getElementById("ticket-<?php echo $index; ?>").value = "<?php echo $ticket_ganador; ?>";
	document.getElementById("factura-<?php echo $index; ?>").value = "<?php echo $factura_asociada; ?>";
	document.getElementById("premio-<?php echo $index; ?>").value = "<?php echo $descrip_incentivo; ?>";

	document.getElementById("boton_random-<?php echo $index; ?>").remove();

	document.getElementById("icon-<?php echo $index; ?>").classList.remove('fa-exclamation-circle');

	document.getElementById("icon-<?php echo $index; ?>").classList.add('fa-check-circle');

	document.getElementById("vista_previa-<?php echo $index; ?>").src = "<?php echo $foto; ?>";

$(".div_wait").fadeOut("fast");

</script>
<?php 


 ?>