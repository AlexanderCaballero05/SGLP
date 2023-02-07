<?php
require '../../template/header.php';
require './distribucion_pedidos_mayor_seccionales_db.php';
?>

<script type="text/javascript">

function agregar_rango(r_inicial,r_final,cantidad){

document.getElementById('paquete_inicial').value = r_inicial;
document.getElementById('paquete_final').value = r_final;
document.getElementById('cantidad').value = cantidad;

document.getElementById('cantidad').setAttribute('min',0);
document.getElementById('cantidad').setAttribute('max',cantidad);

document.getElementById('boton_guardar').disabled = false;

}


function calcular_final(cantidad){


r_inicial = document.getElementById('paquete_inicial').value;
r_final = parseInt(r_inicial) + parseInt(cantidad) - 1;


if (isNaN(parseInt(r_final))) {

document.getElementById('boton_guardar').disabled = true;

  swal({
  title: "",
   text: "Paquete inicial invalido",
    type: "warning"
  });

}else{

r_final = parseInt(r_inicial) + parseInt(cantidad) - 1;

document.getElementById('paquete_final').value = r_final;
document.getElementById('boton_guardar').disabled = false;

}

}


function isNumber(evt) {
var iKeyCode = (evt.which) ? evt.which : evt.keyCode
if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
return false;

return true;
}

</script>

<form method="POST">






<section style="background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >DISTRIBUCION DE LOTERIA MAYOR - SECCIONALES</h2>
<br>
</section>

<a class="btn btn-info" style="width:100%" role="button" data-toggle="collapse" href="#collapse3" aria-expanded="false" aria-controls="collapse3">
 Selección de Parametros
</a>

<div  class="collapse" style = "width:100%"  id="collapse3" align="center">
<div class="card" align="center" style="width: 50%">
<div class="card-body">

<div class="input-group">
<div class="input-group-prepend"><div class="input-group-text">Sorteo: </div></div>

<select class="form-control" name="sorteo" >
<?php
while ($row2 = mysqli_fetch_array($sorteos_seleccion)) {
	echo '<option value = "' . $row2['id'] . '">' . $row2['no_sorteo_may'] . '</option>';
}
?>
</select>


<div class="input-group-prepend"><div class="input-group-text">Entidad: </div></div>

<select class="form-control" name="s_empresa" >
<?php
while ($row2 = mysqli_fetch_array($empresas)) {
	echo '<option value = "' . $row2['id'] . '">' . $row2['nombre_empresa'] . '</option>';
}
?>
</select>

<div class="input-group-append">
<input  type="submit" name="seleccionar" class="btn btn-primary" value="Seleccionar">
</div>

</div>

</div>
</div>
</div>



<?php

if (isset($_POST['seleccionar'])) {
	$id_sorteo = $_POST['sorteo'];
	$id_empresa = $_POST['s_empresa'];

	$info_sorteo = mysqli_query($conn, "SELECT *  FROM sorteos_mayores WHERE id = '$id_sorteo' limit 1");
	$value = mysqli_fetch_object($info_sorteo);
	$sorteo = $value->no_sorteo_may;
	$fecha_sorteo = $value->fecha_sorteo;
	$cantidad_billetes = $value->cantidad_numeros;
	$mezcla = $value->mezcla;
	$estado_sorteo = $value->estado_sorteo;
	$estado_venta = $value->estado_venta;

	$info_empresa = mysqli_query($conn, "SELECT * FROM empresas WHERE id = '$id_empresa' ");
	$ob_empresa = mysqli_fetch_object($info_empresa);
	$nombre_empresa = $ob_empresa->nombre_empresa;

	$paquetes_disponibles = mysqli_query($conn, "SELECT COUNT(*) as conteo FROM sorteos_mezclas WHERE id_sorteo = '$id_sorteo' AND id_empresa = '$id_empresa' AND id_seccional IS NULL ");

	$op_paquetes = mysqli_fetch_object($paquetes_disponibles);
	$num_paquetes = $op_paquetes->conteo;

	$c_rangos_disponibles = mysqli_query($conn, " SELECT MIN(num_mezcla) as minimo, MAX(num_mezcla) as maximo, MAX(num_mezcla) -MIN(num_mezcla) +1 as cantidad , indicador FROM ( SELECT num_mezcla, @curRow := @curRow + 1 AS row_number, num_mezcla - @curRow AS indicador from sorteos_mezclas p join (SELECT @curRow := 0) r WHERE id_sorteo = '$id_sorteo' AND id_empresa = '$id_empresa' AND id_seccional IS NULL ORDER BY num_mezcla ASC ) t GROUP BY indicador ");

	?>
<div align="center" style="width:100%; ">
<input type="hidden" name="id_sorteo_oculto" value="<?php echo $id_sorteo; ?>">
<input type="hidden" name="id_empresa_oculto" value="<?php echo $id_empresa; ?>">

<input type="hidden" id="billetes_disponibles_oculto" value="<?php echo $num_paquetes; ?>">

<br>


<div class="card" style="margin-right: 10px; margin-left: 10px;">
<div class="card-header bg-secondary text-white">
 <h3 align="center">
Inventario Disponible  Sorteo Numero: <?php if (isset($sorteo)) {echo $sorteo;}?>
 Fecha de Sorteo: <?php if (isset($sorteo)) {echo $fecha_sorteo;}?>
</h3>

<hr>
<b>NOTA:</b>
La distribución de loteria mayor se realiza por paquete y cada paquete contiene 100 billetes.


</div>
<div class="card-body">

<table class="table table-bordered">
  <tr>
    <th>Paquete Inicial</th>
    <th>Paquete Final</th>
    <th>Cantidad</th>
    <th align="center">Accion</th>
  </tr>
<?php

	while ($r_rangos_disponibles = mysqli_fetch_array($c_rangos_disponibles)) {
		echo "<tr><td>" . $r_rangos_disponibles['minimo'] . "</td><td>" . $r_rangos_disponibles['maximo'] . "</td><td>" . $r_rangos_disponibles['cantidad'] . "</td>";
		echo "<td align = 'center'><span class = 'btn btn-primary' onclick = agregar_rango('" . $r_rangos_disponibles['minimo'] . "','" . $r_rangos_disponibles['maximo'] . "','" . $r_rangos_disponibles['cantidad'] . "') >Agregar Rango</span></td>";
		echo "</tr>";
	}

	?>

<tr><th colspan="2">TOTAL PAQUETES DISPONIBLES</th><th><?php echo $num_paquetes; ?></th><th></th></tr>
</table>

</div>
</div>



<input type="hidden" id="billetes_disponibles" value="<?php echo $num_paquetes; ?>" readonly>


<br>



<table  style="margin-right: 10px; margin-left: 10px;">
  <tr>
    <td width="59%" valign="top">

<div class="card">
  <div class="card-header bg-secondary text-white">
    <h3 align="center"><?php echo $nombre_empresa; ?></h3>
  </div>

  <div class="card-body">
    <table class="table table-bordered">
      <tr>
        <th width="40%">Seccional</th>
        <th width="20%">Cantidad</th>
        <th width="20%">Paquete Inicial</th>
        <th width="20%">Paquete Final</th>
      </tr>

      <tr>

<?php

	$consulta_seccionales = mysqli_query($conn, "SELECT * FROM fvp_seccionales WHERE id_empresa = '$id_empresa' ");

	?>

        <td>

        	<select name="id_seccional" id="id_seccional" class="form-control" >
        		<?php
while ($reg_seccionales = mysqli_fetch_array($consulta_seccionales)) {
		echo "<option value = '" . $reg_seccionales['id'] . "'>" . $reg_seccionales['nombre'] . "</option>";
	}
	?>
        	</select>

        </td>
        <td>
          <input type="number" id="cantidad" name="cantidad" class="form-control" onchange="calcular_final(this.value)" onkeypress="javascript:return isNumber(event)">
        </td>
        <td>
          <input type="text" id="paquete_inicial" name="paquete_inicial" onkeyup="validar_inicial(this.value)" class="form-control" readonly="true">
        </td>
        <td>
          <input type="text" id="paquete_final" name="paquete_final" onkeyup="validar_final(this.value)" class="form-control" readonly="true">
        </td>

      </tr>
    </table>
  </div>

<div align="center" class="card-footer">
  <button name="guardar_distribucion" id = "boton_guardar" type="submit" class="btn btn-primary" disabled="true">GUARDAR DISTRIBUCION</button>
</div>
</div>


    </td>

    <td width="2%"></td>

    <td width="39%" valign="top">


<div class="card">
  <div class="card-header bg-secondary text-white">
    <h3 align="center">ASIGNADO A SECCIONALES</h3>
  </div>
  <div class="card-body">

<table class="table table-bordered">
<tr>
  <th>Seccional</th>
  <th>Cantidad Paquetes</th>
  <th>Eliminar</th>
</tr>

<?php

	$distribuciones_realizadas = mysqli_query($conn, "SELECT COUNT(id_seccional) as conteo, b.id , b.nombre FROM sorteos_mezclas AS a INNER JOIN fvp_seccionales as b  ON a.id_seccional = b.id WHERE a.id_sorteo = '$id_sorteo' AND a.estado = 'DISTRIBUIDO' AND a.id_empresa = '$id_empresa' GROUP BY id_seccional ");

	if ($distribuciones_realizadas === false) {
		echo mysqli_error();
	}

	while ($reg_distribucion = mysqli_fetch_array($distribuciones_realizadas)) {

		$parametros = $reg_distribucion['id'] . "-" . $id_sorteo;

		if ($reg_distribucion['conteo'] != 0) {
			echo "<tr>";
			echo "<td>" . $reg_distribucion['nombre'] . "</td>";
			echo "<td>" . $reg_distribucion['conteo'] . "</td>";
			echo "<td align = 'center'><button class = 'btn btn-danger' name = 'borrar_distribucion' value = '" . $reg_distribucion['id'] . "' type = 'submit'>X</button></td>";

			echo "</tr>";
		}

	}

	?>

</table>


</div>

</div>

    </td>
  </tr>
</table>

</div>


<?php
}
?>



</form>