<?php

header ("Pragma-directive: no-cache");
header ("Cache-directive: no-cache");
header ("Cache-control: no-cache");
header ("Pragma: no-cache");
header ("Expires: 0");

require '../../template/header.php';



$foto 		= $_GET['f'];
$identidad  = $_GET['i'];
$nombre		= strtoupper($_GET['n1']);
$nombre2	= strtoupper($_GET['n2']);

$c_puesto_e = mysqli_query($conn, "SELECT * FROM rr_hh_empleados_desc_puestos_provicionales WHERE identidad = '$identidad' ");

if (mysqli_num_rows($c_puesto_e) > 0) {
$ob_puesto_e = mysqli_fetch_object($c_puesto_e);
$puest_e = $ob_puesto_e->descripcion_puesto;
}else{
$puest_e = '';
}

if ($puest_e == '') {
$puesto		= strtoupper($_GET['p']);
}else{
$puesto		= strtoupper($puest_e);
}


$departamento = strtoupper($_GET['d']);
$nombre = str_replace("??", "Ñ", $nombre);
$nombre2 = str_replace("??", "Ñ", $nombre2);

$nombre = str_replace("?", "Ñ", $nombre);
$nombre2 = str_replace("?", "Ñ", $nombre2);

$rand = rand();

?>


<style type="text/css">



</style>






<div style="display: inline" >
<img style=" margin-top: 55px; margin-left: 70px; float: left;" width="170px"; height ="170px" src="../imagenes/empleados/<?php echo $foto; ?>?rand=<?php echo $rand; ?>" >
<img  id="i_anavelh" src="../imagenes/carnet_front_f.png" width="318px" height="495px" style="position: relative; margin-top: -72px; margin-left: -240px">

<label id = 'span_datos' style="-webkit-print-color-adjust:exact; width: 305px; font-size: 18px; text-align: center; position: absolute; margin-top: 240px; margin-left: -312px; color: white; font-weight: bold;" ><p class="text-white"><?php echo $nombre; ?><br><?php echo $nombre2; ?></p></label>
<label id = 'span_datos' style="-webkit-print-color-adjust:exact; width: 305px; font-size: 16px; text-align: center; position: absolute; margin-top: 297px; margin-left: -312px; color: white; font-weight: bold;" ><p class="text-white"><?php echo $identidad; ?></p></label>
<label id = 'span_datos' style="-webkit-print-color-adjust:exact; width: 305px; font-size: 16px; text-align: center; position: absolute; margin-top: 330px; margin-left: -312px; color: white; font-weight: bold;" ><p class="text-white"> <?php echo $puesto; ?></p></label>
<label id = 'span_datos' style="-webkit-print-color-adjust:exact; width: 305px; font-size: 16px; text-align: center; position: absolute; margin-top: 350px; margin-left: -312px; color: white; font-weight: bold;" ><p class="text-white"> <?php echo $departamento; ?></p></label>

</div>








<style>

		-webkit-print-color-adjust:exact;

	@media print{

		-webkit-print-color-adjust:exact;

	}
</style>