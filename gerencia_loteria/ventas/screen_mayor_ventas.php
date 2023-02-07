<?php
require '../../template/header.php';
require './venta_mayor_t_db.php';

$id_usuario = $_SESSION['id_usuario'];

?>

<script type="text/javascript" src="./js/funciones_venta_mayor_t.js"></script>

<script type="text/javascript">


jQuery(function($){
$("#identidad").mask("9999-9999-99999", { placeholder: "____-____-_____" });
});

function consultar_id(){

identidad = document.getElementById("identidad").value;
tipo_id   = 1;

if (identidad === '') {
swal("","Debe ingresar el numero de identidad del comprador","error");
$("#div_respuesta_id").html('');

}else{

token = Math.random();
consulta = 'venta_consulta_id.php?id='+identidad+"&filtro="+"1"+"&token="+token+"&tipo_id="+tipo_id;
$("#div_respuesta_id").load(consulta);

}

}


  $('html').bind('keypress', function(e)
{
   if(e.keyCode == 13)
   {
      return false;
   }
});



</script>

<body>

<form method="POST" autocomplete="off">


  <section style="background-color:#ededed;">
  <br>
  <h3 align="center"><b>VENTA DE LOTERIA MAYOR </b></h3>
  <br>
  </section>



  <a style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
  Selección de Parametros
  </a>



  <div class="card collapse" id="collapse1" style="margin-left: 100px; margin-right: 100px;" >
  <div class="card-body">



  <div class="input-group">

  <div class="input-group-prepend">
  <div class="input-group-text">SORTEO: </div>
  </div>


  <select class="form-control"  name = "sorteo" id = 'sorteo'   style="margin-right: 5px;">
  <?php
while ($row2 = mysqli_fetch_array($sorteos)) {
	echo '<option value = "' . $row2['id'] . '">No.' . $row2['no_sorteo_may'] . ' -- Fecha ' . $row2['fecha_sorteo'] . ' -- ' . $row2['descripcion_sorteo_may'] . '</option>';
}
?>
  </select>

  <div class="input-group-append" >
  <input style="width: 100%"  type="submit" name="seleccionar" class="btn btn-primary" value="Seleccionar">
  </div>

  </div>

</div>
</div>

<br>


<?php
if (isset($_POST['seleccionar'])) {

	$_SESSION['estado_mayor'] = 1;

	?>

<hr>


<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->
<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->
<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%  INVENTARIO DISPONIBLE  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->


<div class = 'card' align = 'center' style="margin-left: 15px; margin-right: 15px">
<div class="card-header bg-secondary text-white">
  <h4>INVENTARIO DISPONIBLE PARA LA VENTA SORTEO <b><u> <?php echo $sorteo; ?></u></b></h4>
</div>

<div class="card-body">


<?php

	if (isset($v_billetes_disponibles)) {

		echo "
    <div class = 'col col-md-4'>
    <div class = 'input-group'>
    <div class = 'input-group-prepend'>
    <div class = 'input-group-text'>Pliegos Disponibles para venta: </div>
    </div>
    <input class ='form-control' type='text' value = '" . count($v_billetes_disponibles) . "' disabled>
    </div>
    </div>
    ";

	} else {

		echo "
    <div class = 'col col-md-4'>
    <div class = 'input-group'>
    <div class = 'input-group-prepend'>
    <div class = 'input-group-text'>Pliegos Disponibles para venta: </div>
    </div>
    <input class ='form-control' type='text' value = '0' disabled>
    </div>
    </div>
    ";
	}

	?>

<br>

<div style="border-width: 5px; overflow: scroll;height:300px ;width:100%" >

<table width="100%" class="table table-hover table-bordered">

<?php
$j = 0;
	if (isset($v_billetes_disponibles[$j])) {
		sort($v_billetes_disponibles);
	}
	while (isset($v_billetes_disponibles[$j])) {
		echo "<tr>";
		if (isset($v_billetes_disponibles[$j])) {
			?>
  <td><span id = '<?php echo $v_billetes_disponibles[$j]; ?>' class = 'btn btn-primary' onclick="agregar_billete(<?php echo $v_billetes_disponibles[$j]; ?>)"><?php echo $v_billetes_disponibles[$j]; ?></span></td>
  <?php
} else {
			echo "<td></td>";
		}
		$j++;
		if (isset($v_billetes_disponibles[$j])) {
			?>
  <td><span id = '<?php echo $v_billetes_disponibles[$j]; ?>' class = 'btn btn-primary' onclick="agregar_billete(<?php echo $v_billetes_disponibles[$j]; ?>)"><?php echo $v_billetes_disponibles[$j]; ?></span></td>
  <?php
} else {
			echo "<td></td>";
		}
		$j++;
		if (isset($v_billetes_disponibles[$j])) {
			?>
  <td><span id = '<?php echo $v_billetes_disponibles[$j]; ?>' class = 'btn btn-primary' onclick="agregar_billete(<?php echo $v_billetes_disponibles[$j]; ?>)"><?php echo $v_billetes_disponibles[$j]; ?></span></td>
  <?php
} else {
			echo "<td></td>";
		}
		$j++;
		if (isset($v_billetes_disponibles[$j])) {
			?>
  <td><span id = '<?php echo $v_billetes_disponibles[$j]; ?>' class = 'btn btn-primary' onclick="agregar_billete(<?php echo $v_billetes_disponibles[$j]; ?>)"><?php echo $v_billetes_disponibles[$j]; ?></span></td>
  <?php
} else {
			echo "<td></td>";
		}
		$j++;
		if (isset($v_billetes_disponibles[$j])) {
			?>
  <td><span id = '<?php echo $v_billetes_disponibles[$j]; ?>' class = 'btn btn-primary' onclick="agregar_billete(<?php echo $v_billetes_disponibles[$j]; ?>)"><?php echo $v_billetes_disponibles[$j]; ?></span></td>
  <?php
} else {
			echo "<td></td>";
		}
		$j++;
		if (isset($v_billetes_disponibles[$j])) {
			?>
  <td><span id = '<?php echo $v_billetes_disponibles[$j]; ?>' class = 'btn btn-primary' onclick="agregar_billete(<?php echo $v_billetes_disponibles[$j]; ?>)"><?php echo $v_billetes_disponibles[$j]; ?></span></td>
  <?php
} else {
			echo "<td></td>";
		}
		$j++;
		if (isset($v_billetes_disponibles[$j])) {
			?>
  <td><span id = '<?php echo $v_billetes_disponibles[$j]; ?>' class = 'btn btn-primary' onclick="agregar_billete(<?php echo $v_billetes_disponibles[$j]; ?>)"><?php echo $v_billetes_disponibles[$j]; ?></span></td>
  <?php
} else {
			echo "<td></td>";
		}
		$j++;
		if (isset($v_billetes_disponibles[$j])) {
			?>
  <td><span id = '<?php echo $v_billetes_disponibles[$j]; ?>' class = 'btn btn-primary' onclick="agregar_billete(<?php echo $v_billetes_disponibles[$j]; ?>)"><?php echo $v_billetes_disponibles[$j]; ?></span></td>
  <?php
} else {
			echo "<td></td>";
		}
		$j++;
		if (isset($v_billetes_disponibles[$j])) {
			?>
  <td><span id = '<?php echo $v_billetes_disponibles[$j]; ?>' class = 'btn btn-primary' onclick="agregar_billete(<?php echo $v_billetes_disponibles[$j]; ?>)"><?php echo $v_billetes_disponibles[$j]; ?></span></td>
  <?php
} else {
			echo "<td></td>";
		}
		$j++;
		if (isset($v_billetes_disponibles[$j])) {
			?>
  <td><span id = '<?php echo $v_billetes_disponibles[$j]; ?>' class = 'btn btn-primary' onclick="agregar_billete(<?php echo $v_billetes_disponibles[$j]; ?>)"><?php echo $v_billetes_disponibles[$j]; ?></span></td>
  <?php
} else {
			echo "<td></td>";
		}
		$j++;

		echo "</tr>";
	}
	?>

</table>
</div>
</div>
</div>


<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%  INVENTARIO DISPONIBLE  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->
<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->
<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->




<input type="hidden" name="factura" value="<?php echo $cod_factura; ?>">
<input type="hidden" name="id_sorteo" value="<?php echo $id_sorteo; ?>">
<input type="hidden" name="precio" id="precio" value="<?php echo $precio_pliego; ?>">
<input type="hidden" name="mezcla" id="mezcla" value="<?php echo $mezcla; ?>">
<input type="hidden" name="filas" id="filas">

<input type="hidden" name="descuento" id="descuento" value="<?php echo $monto_descuento; ?>">
<input type="hidden" name="comision" id="comision" value="<?php echo $monto_comision; ?>">



<br>



<div class="row">

  <div class="col col-md-4">



<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->
<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->
<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%   INFORMACION COMPRADOR %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->

<div class="card" style="margin-left: 15px">
  <div class="card-header  bg-secondary text-white">
    <h4 align="center">Información del Comprador</h4>
  </div>
  <div class="card-body">


<div  class="input-group ">
<div class="input-group-prepend">
<span class="input-group-text">Identidad: </span>
</div>

<input  maxlength="13" onkeydown ="validar_identidad()" type="text" type="text" class="form-control" id = 'identidad' name="identidad"  >

<div class="input-group-prepend">
<span style="" class="btn btn-success " onclick="consultar_id()" >
<i class="fa fa-search"></i>
</span>
</div>
</div>


<div id="div_respuesta_id">
</div>

  </div>
</div>

<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%  INFORMACION COMPRADOR  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->
<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->
<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->



  </div>


  <div class="col">




<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->
<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->
<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%  DETALLLE DE LA VENTA  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->


<div class="card" style="margin-right: 15px;">
  <div class="card-header  bg-secondary text-white">
    <h4 align="center">Detalle De Venta Sorteo <b><?php echo $id_sorteo; ?></b></h4>
  </div>

  <div class="card-body">

<div align="felt">
<p align="left"> Precio por pliego Lps: <?php echo $precio_pliego; ?>
</p>
</div>


<table class="table table-hover table-bordered" id="detalle_venta" style="width:100%" >
<thead>
  <th width="50%">Pliego</th>
  <th  width="40%">Total Lps.</th>
  <th  width="10%">Eliminar</th>
</thead>
</table>

<table class="table table-bordered" width="100%">
<tr>
  <th width="25%">Total Cantidad</th>
  <th width="25%">Total Bruto</th>
  <th width="25%">Descuento</th>
  <th width="25%">Total Neto</th>
</tr>
  <tr>
    <td>
    <input class="form-control"  type="text" id="total_cantidad" name="total_cantidad" value="0" readonly>
    </td>
    <td>
    <input class="form-control"  type="text" id="total_pagar" name="total_pagar" value="0" readonly>
    </td>
    <td >
    <input class="form-control" type="text" name="descuento_total" id="descuento_total" value="0" readonly>
    </td>
    <td>
    <input class="form-control" type="text" name="neto" id = "neto" value="0" readonly>
    </td>
  </tr>
</table>


<table class="table table-bordered" width="100%">
<tr>
  <th>Contado</th>
  <th>Credito</th>
  <th>Deduccion</th>
</tr>
</table>


<hr>
<p align="center">
  <input type="button" class="btn btn-primary" onclick="confirmar()" value="GUARDAR">
  <input style="visibility:hidden;" type="submit" id="guardar" name = 'guardar' value="GUARDAR">
</p>


  </div>
</div>


<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%  DETALLLE DE LA VENTA  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->
<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->
<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->



  </div>


</div>



















<br><br>



<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->
<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->
<!--%%%%%%%%%%%%%%%%%%%%%%%%%  HISTORICO DE TRANSACCIONES  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->





<div class="card " style="margin-right: 15px; margin-left: 15px">
  <div class="card-header  bg-secondary text-white">
    <h4 align="center">HISTORICO DIARIO DE TRANSACCIONES - SORTEO <b><?php echo $id_sorteo; ?></b></h4>
  </div>
<div class="card-body">

<table id="table_id2" style="width:100%" class="table table-hover table-bordered">
<thead>
  <tr>
    <th>Factura</th>
    <th>Fecha Venta</th>
    <th>Pliegos</th>
    <th>Total Pagado</th>
    <th>Estado Venta</th>
    <th>Detalle</th>
    <th>Reversar</th>
<!--     <th>Factura</th> -->
  </tr>
</thead>
<tbody>
<?php
while ($venta = mysqli_fetch_array($ventas_sorteo)) {
		$cod = $venta['cod_factura'];

		if ($venta['estado_venta'] != 'APROBADO') {
			echo "<tr class = 'alert alert-danger'>";
		} else {
			echo "<tr class = 'alert alert-success'>";
		}

		echo "<td >" . $venta['cod_factura'] . "</td>
<td >" . $venta['fecha_venta'] . "</td>
<td >" . $venta['cantidad'] . "</td>
<td >" . $venta['total_neto'] . "</td>";

		echo "<td >" . $venta['estado_venta'] . "</td>";
		echo "<td  align= 'center'>
<a class='btn btn-primary' target='_blank' href= './print_factura_mayor.php?c=" . $cod . "'>
<span class = 'fa fa-eye'></span>
</a>
</td>

<td  align= 'center'>
<button type ='submit' class='btn btn-danger' name= 'reversar_venta' id = 'reversar_venta' value = '" . $cod . "' >
<span class = 'fa fa-times-circle'></span>
</a>
</td>
</tr>
";
	}
	?>


    </tbody>
  </table>
</div>
</div>

<!--%%%%%%%%%%%%%%%%%%%%%%%%%  HISTORICO DE TRANSACCIONES  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->
<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->
<!--%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->



<?php
}
?>

</form>
</body>
