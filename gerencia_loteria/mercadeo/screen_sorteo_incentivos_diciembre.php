<?php
mysqli_query($conn,"SET CHARACTER SET 'utf8'");
mysqli_query($conn,"SET SESSION collation_connec-unicode_ci'");

require '../../template/header.php';

$current_date = date("Y-m-d");

$reg_ultimo_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE incentivo_vendedores = 1 ORDER BY date(fecha_sorteo) DESC LIMIT 1 ");

//$reg_ultimo_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_menores WHERE id = 3340 ORDER BY date(fecha_sorteo) DESC LIMIT 1 ");

$ob_ultimo_sorteo = mysqli_fetch_object($reg_ultimo_sorteo);
$ultimo_sorteo = $ob_ultimo_sorteo->id;
$fec_sorteo = $ob_ultimo_sorteo->fecha_sorteo;

$c_incentivos = mysqli_query($conn, "SELECT * FROM sorteos_menores_incentivos WHERE id_sorteo = '$ultimo_sorteo' ");
$c_incentivos2 = mysqli_query($conn, "SELECT * FROM sorteos_menores_incentivos WHERE id_sorteo = '$ultimo_sorteo' ");

?>

<style type="text/css">

@media print    {
        #no_print { display: none; }
        #respuesta { display: none; }

    }

    @media print {
			#print {
			 margin-left:10%;
			}
    }
</style>

<script type="text/javascript">

function sortear_ganador(index) {
$(".div_wait").fadeIn("fast");

setTimeout(function(){

s = document.getElementById("id_sorteo").value;
token = Math.random();
consulta = 'sorteo_incentivos_db_diciembre.php?s='+s+"&i="+index+"&token="+token;
$("#respuesta_"+index).load(consulta);


validacion = '_incentivos_validacion.php?s='+s+"&token="+token;
$("#validacion").load(validacion);


}, 2000);

}




</script>

<br>


<div class="card" style="margin-right: 15px;margin-left: 15px; ">
<input type="hidden" name="id_sorteo" id="id_sorteo" value = "<?php echo $ultimo_sorteo; ?>" >

<div class="card-header bg-dark text-white">
<h3 align="center">PREMIACION A COMPRADORES DESTACADOS EN SORTEO <?php echo $ultimo_sorteo; ?></h3>
<br>
<?php echo "Fecha : " . $fec_sorteo; ?>
</div>

<div id="print"  class="card-body table-responsive">


<div class="row">
  <div id="no_print"  class="col-4" style="margin-right: 0px">
    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">

<?php
$contador = 1;
while ($reg_incentivos = mysqli_fetch_array($c_incentivos)) {

	if ($reg_incentivos["id_vendedor"] == NULL) {
		$tipo_i = "exclamation-circle";
	} else {
		$tipo_i = "check-circle";
	}

	if ($contador == 1) {

		echo '<a class="nav-link active" id="v-pills-' . $reg_incentivos['id'] . '-tab" data-toggle="pill" href="#v-pills-' . $reg_incentivos['id'] . '" role="tab" aria-controls="v-pills-' . $reg_incentivos['id'] . '" aria-selected="true">' . $contador . ' - ' . $reg_incentivos['descripcion_incentivo'] . '<span class = "float-right"><i id = "icon-' . $reg_incentivos['id'] . '" class = "fa fa-' . $tipo_i . '" ></i></span></a>';
	} else {
		echo '<a class="nav-link" id="v-pills-' . $reg_incentivos['id'] . '-tab" data-toggle="pill" href="#v-pills-' . $reg_incentivos['id'] . '" role="tab" aria-controls="v-pills-' . $reg_incentivos['id'] . '" aria-selected="false">' . $contador . ' - ' . $reg_incentivos['descripcion_incentivo'] . '<span class = "float-right"><i id = "icon-' . $reg_incentivos['id'] . '" class = "fa fa-' . $tipo_i . '" ></i></span></a>';

	}

	$contador++;
}
?>


          </div>
  </div>
  <div  class="col-8" style="margin-left: -32px">
    <div id="print" class="tab-content" id="v-pills-tabContent">


<?php
$contador = 1;
unset($reg_incentivos);
while ($reg_incentivos = mysqli_fetch_array($c_incentivos2)) {

	if ($reg_incentivos["id_vendedor"] == NULL) {
		$id_vendedor = "";
		$nombre_vendedor = "";
		$ticket_electronico = "";
		$incentivo = $reg_incentivos["descripcion_incentivo"];
	} else {
		$id_vendedor = $reg_incentivos["id_vendedor"];
		$nombre_vendedor = $reg_incentivos["nombre_vendedor"];
		$nombre_vendedor = utf8_decode($nombre_vendedor);
		$ticket_electronico = $reg_incentivos["ticket_electronico"];
		$incentivo = $reg_incentivos["descripcion_incentivo"];
	}

	if ($contador == 1) {
		?>

<div class="tab-pane fade show active" id="v-pills-<?php echo $reg_incentivos['id'] ?>" style="border-style: solid; border-color: #007bff" role="tabpanel" aria-labelledby="v-pills-<?php echo $reg_incentivos['id'] ?>-tab">

<div  class="row" style="margin-top: 10px; margin-left: 10px; margin-bottom: 10px; margin-right: 10px;" >
	<div class="col-9">
		<div class="input-group" >
			<div  class="input-group-prepend" ><span style="min-width: 120px"  class="input-group-text">IDENTIDAD</span></div>
			<input type="text" id="identidad-<?php echo $reg_incentivos['id'] ?>" name="identidad-<?php echo $reg_incentivos['id'] ?>" class="form-control" value = "<?php echo $id_vendedor; ?>" readonly="true">
		</div>

		<div style="margin-top: 10px;" class="input-group" >
			<div  class="input-group-prepend"><span style="min-width: 120px" class="input-group-text">NOMBRE</span></div>
			<input type="text" id="nombre-<?php echo $reg_incentivos['id'] ?>" name="nombre-<?php echo $reg_incentivos['id'] ?>" class="form-control" value = "<?php echo $nombre_vendedor; ?>"  readonly="true">
		</div>

		<div style="margin-top: 10px;" class="input-group" >
			<div  class="input-group-prepend"><span class="input-group-text">TICKET ELECTRONICO</span></div>
			<input type="text" id="ticket-<?php echo $reg_incentivos['id'] ?>" name="ticket-<?php echo $reg_incentivos['id'] ?>" class="form-control" value = "<?php echo $ticket_electronico; ?>" readonly="true">
		</div>

			<!-- div style="margin-top: 10px;" class="input-group" >
			<div  class="input-group-prepend"><span class="input-group-text">NÚMERO DE FACTURA</span></div>
			<?php
$query_factura = mysqli_query($conn, "SELECT cod_factura_recaudador FROM transaccional_ventas_general WHERE cod_producto  = 3 and  identidad_comprador='$id_vendedor' and id_sorteo=$ultimo_sorteo");
		$obj_factura = mysqli_fetch_object($query_factura);
		$factura_asociada = $obj_factura->cod_factura_recaudador;
		?>
			<input type="text" id="factura-<?php echo $reg_incentivos['id'] ?>" name="factura-<?php echo $reg_incentivos['id'] ?>" class="form-control" value = "<?php echo $factura_asociada; ?>" readonly="true">
		</div -->

		<div style="margin-top: 10px;" class="input-group" >
			<div  class="input-group-prepend"><span class="input-group-text">PREMIO</span></div>
			<input type="text" id="premio-<?php echo $reg_incentivos['id'] ?>" name="premio-<?php echo $reg_incentivos['id'] ?>" class="form-control" value = "<?php echo $incentivo; ?>" readonly="true">
		</div>

<?php
if ($reg_incentivos["id_vendedor"] == NULL) {
			?>
		<span style="margin-top: 10px; width: 100%" onclick="sortear_ganador('<?php echo $reg_incentivos['id']; ?>')"  class="btn btn-primary" id = "boton_random-<?php echo $reg_incentivos['id'] ?>">SORTEAR GANADOR</span>

<?php
}
		?>

	</div>
	<div class="col"  >

<?php
$path_foto = "./imagenes/vendedores/" . $id_vendedor . ".jpg";
		if (file_exists($path_foto)) {
			?>
<img width="150px" height="150px"   id="vista_previa-<?php echo $reg_incentivos['id'] ?>" src="<?php echo $path_foto; ?>" alt="" >

<?php

		} else {
			?>
<img width="150px" height="150px"   id="vista_previa-<?php echo $reg_incentivos['id'] ?>" src="./imagenes/default_foto.png" alt="" >

<?php
}

		?>

	</div>
</div>

<div style=" margin-left: 10px;  margin-right: 10px;" class="row" id="respuesta_<?php echo $reg_incentivos['id']; ?>">  </div>

</div>

<?php
} else {
		?>
      <div style="border-style: solid; border-color: #007bff" class="tab-pane fade" id="v-pills-<?php echo $reg_incentivos['id'] ?>" role="tabpanel" aria-labelledby="v-pills-<?php echo $reg_incentivos['id'] ?>-tab">


<div class="row" style="margin-top: 10px; margin-left: 10px; margin-bottom: 10px; margin-right: 10px;" >
	<div class="col-9">
		<div class="input-group" >
			<div  class="input-group-prepend"><span style="min-width: 120px" class="input-group-text">IDENTIDAD</span></div>
			<input type="text" id="identidad-<?php echo $reg_incentivos['id'] ?>" name="identidad-<?php echo $reg_incentivos['id'] ?>" class="form-control" value = "<?php echo $id_vendedor; ?>" readonly="true">

		</div>

		<div style="margin-top: 10px;" class="input-group" >
			<div  class="input-group-prepend"><span style="min-width: 120px" class="input-group-text">NOMBRE</span></div>9
			<input type="text" id="nombre-<?php echo $reg_incentivos['id'] ?>" name="nombre-<?php echo $reg_incentivos['id'] ?>" class="form-control" value = "<?php echo  utf8_encode($nombre_vendedor); ?>" readonly="true">
		</div>

		<div style="margin-top: 10px;" class="input-group" >
			<div  class="input-group-prepend"><span class="input-group-text">TICKET ELECTRONICO</span></div>
			<input type="text" id="ticket-<?php echo $reg_incentivos['id'] ?>" name="ticket-<?php echo $reg_incentivos['id'] ?>" class="form-control" value = "<?php echo $ticket_electronico; ?>" readonly="true">
		</div>
		<!-- div style="margin-top: 10px;" class="input-group" >
			<div  class="input-group-prepend"><span class="input-group-text">NÚMERO DE FACTURA</span></div>
			<?php
$query_factura = mysqli_query($conn, "SELECT cod_factura_recaudador FROM transaccional_ventas_general WHERE cod_producto in (2,3) and  identidad_comprador='$id_vendedor' and id_sorteo=$ultimo_sorteo");
		$obj_factura = mysqli_fetch_object($query_factura);
		$factura_asociada = $obj_factura->cod_factura_recaudador;
		?>
			<input type="text" id="factura-<?php echo $reg_incentivos['id'] ?>" name="factura-<?php echo $reg_incentivos['id'] ?>" class="form-control" value = "<?php echo $factura_asociada; ?>" readonly="true" >
		</div -->

		<div style="margin-top: 10px;" class="input-group" >
			<div  class="input-group-prepend"><span class="input-group-text">PREMIO</span></div>
			<input type="text" id="premio-<?php echo $reg_incentivos['id'] ?>" name="premio-<?php echo $reg_incentivos['id'] ?>" class="form-control" value = "<?php echo $incentivo; ?>" readonly="true">
		</div>

<?php
if ($reg_incentivos["id_vendedor"] == NULL) {
			?>
		<span style="margin-top: 10px; width: 100%" onclick="sortear_ganador('<?php echo $reg_incentivos['id']; ?>')"  class="btn btn-primary" id = "boton_random-<?php echo $reg_incentivos['id'] ?>">SORTEAR GANADOR</span>

<?php
}
		?>

	</div>
	<div class="col"  >

<?php
$path_foto = "./imagenes/vendedores/" . $id_vendedor . ".jpg";
		if (file_exists($path_foto)) {
			?>
<img width="150px" height="150px"   id="vista_previa-<?php echo $reg_incentivos['id'] ?>" src="<?php echo $path_foto; ?>" alt="" >

<?php

		} else {
			?>
<img width="150px" height="150px"   id="vista_previa-<?php echo $reg_incentivos['id'] ?>" src="./imagenes/default_foto.png" alt="" >

<?php
}

		?>

	</div>
</div>

<div  class="row" style=" margin-left: 10px;  margin-right: 10px;" id="respuesta_<?php echo $reg_incentivos['id']; ?>"></div>

      </div>

<?php
}

	$contador++;
}

?>


    </div>
  </div>
</div>




</div>
<div class="card-footer" align="center">
	<div class="row">
		<div class="col-sm-2"></div>
		<div class="col-sm-4" align="right">
			<a href="./tickets_asignados_sorteo_diciembre.php?s=<?php echo $ultimo_sorteo; ?>" class="btn btn-success" id="no_print" target="_blank">GENERAR REPORTE DE TICKETS ASIGNADOS</a>
		</div>
		<div  class="col-sm-4" align="left" >
		<div id="validacion"></div>
		<?php

echo '<a href="./menor_acta_incentivos_diciembre.php?s=' . $ultimo_sorteo . '" class="btn btn-danger" id="no_print" target="_blank">IMPRIMIR ACTA </a>';
?>

		</div>
		<div class="col-sm-2"></div>
	</div>
</div>
</div>