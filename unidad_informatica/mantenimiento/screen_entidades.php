<?php

require '../../template/header.php';

$empresas = mysqli_query($conn, "SELECT * FROM empresas ");

if (isset($_POST['guardar_nuevo'])) {

	$n_entidad = $_POST['n_entidad'];
	$n_descripcion_acta = $_POST['n_descripcion_acta'];
	$n_descuento_mayor = $_POST['n_descuento_mayor'];
	$n_tipo_descuento_mayor = $_POST['n_tipo_descuento_mayor'];
	$n_comision_mayor = $_POST['n_comision_mayor'];
	$n_tipo_comision_mayor = $_POST['n_tipo_comision_mayor'];

	$n_descuento_menor = $_POST['n_descuento_menor'];
	$n_tipo_descuento_menor = $_POST['n_tipo_descuento_menor'];
	$n_comision_menor = $_POST['n_comision_menor'];
	$n_tipo_comision_menor = $_POST['n_tipo_comision_menor'];

	$n_estado = "ACTIVO";
	$n_distribuidor = $_POST['n_distribuidor'];

	$insert = mysqli_query($conn, "INSERT INTO empresas (nombre_empresa, descripcion_acta, descuento_mayor, tipo_descuento_mayor, rebaja_mayor, tipo_rebaja_mayor,descuento_menor, tipo_descuento_menor, rebaja_menor, tipo_rebaja_menor, estado, distribuidor) VALUES ('$n_entidad','$n_descripcion_acta','$n_descuento_mayor','$n_tipo_descuento_mayor','$n_comision_mayor','$n_tipo_comision_mayor','$n_descuento_menor','$n_tipo_descuento_menor','$n_comision_menor','$n_tipo_comision_menor','$n_estado', '$n_distribuidor') ");

	if ($insert === TRUE) {
		echo "<div class = 'alert alert-info'><i class = 'fa fa-check-circle'></i> Entidad creada correctamente.</div>";
	} else {
		echo "<div class = 'alert alert-danger'><i class = 'fa fa-exclamation-circle'></i> Error inesperado, por favor vuelva a intentarlo. " . mysqli_error($conn) . "</div>";
	}

	$empresas = mysqli_query($conn, "SELECT * FROM empresas ");

}

if (isset($_POST['editar_entidad'])) {

	$e_id = $_POST['e_id'];
	$e_entidad = $_POST['e_entidad'];
	$e_descripcion_acta = $_POST['e_descripcion_acta'];
	$e_descuento_mayor = $_POST['e_descuento_mayor'];
	$e_tipo_descuento_mayor = $_POST['e_tipo_descuento_mayor'];
	$e_comision_mayor = $_POST['e_comision_mayor'];
	$e_tipo_comision_mayor = $_POST['e_tipo_comision_mayor'];

	$e_descuento_menor = $_POST['e_descuento_menor'];
	$e_tipo_descuento_menor = $_POST['e_tipo_descuento_menor'];
	$e_comision_menor = $_POST['e_comision_menor'];
	$e_tipo_comision_menor = $_POST['e_tipo_comision_menor'];

	$e_estado = $_POST['e_estado'];
	$e_distribuidor = $_POST['e_distribuidor'];

	$usuario_ftp = $_POST['e_usuario_ftp'];
	$clave_ftp = $_POST['e_clave_ftp'];

	$update = mysqli_query($conn, "UPDATE empresas SET nombre_empresa = '$e_entidad', descripcion_acta = '$e_descripcion_acta', descuento_mayor = '$e_descuento_mayor', tipo_descuento_mayor = '$e_tipo_descuento_mayor', rebaja_mayor = '$e_comision_mayor', tipo_rebaja_mayor = '$e_tipo_comision_mayor', descuento_menor = '$e_descuento_menor', tipo_descuento_menor = '$e_tipo_descuento_menor', rebaja_menor = '$e_comision_menor', tipo_rebaja_menor = '$e_tipo_comision_menor', estado = '$e_estado', distribuidor = '$e_distribuidor', usuario_ftp = '$usuario_ftp' , clave_ftp = '$clave_ftp' WHERE id = '$e_id' ");

	if ($update === TRUE) {
		echo "<div class = 'alert alert-info'><i class = 'fa fa-check-circle'></i> Entidad actualizada correctamente.</div>";
	} else {
		echo "<div class = 'alert alert-danger'><i class = 'fa fa-exclamation-circle'></i> Error inesperado, por favor vuelva a intentarlo. " . mysqli_error($conn) . "</div>";
	}

	$empresas = mysqli_query($conn, "SELECT * FROM empresas ");

}

?>


<script type="text/javascript">


function isNumberKey(evt){
var charCode = (evt.which) ? evt.which : event.keyCode
if (charCode > 31 && (charCode < 46 || charCode > 57))
return false;
return true;
}


/////////////////////////////////////////
//// FUNCIONES CARGADO DE EDICION ///////

function cargar_edicion(id,empresa,valor_comision_mayor,tipo_comision_mayor, valor_comision_menor, tipo_comision_menor,valor_descuento_mayor,tipo_descuento_mayor, valor_descuento_menor, tipo_descuento_menor, estado_venta, distribuidor, acta, u_ftp, c_ftp){

document.getElementById("e_id").value	      = id;
document.getElementById("e_entidad").value  = empresa;

document.getElementById("e_comision_mayor").value 		  = valor_comision_mayor;
document.getElementById("e_tipo_comision_mayor").value  = tipo_comision_mayor;

document.getElementById("e_descuento_mayor").value 		  = valor_descuento_mayor;
document.getElementById("e_tipo_descuento_mayor").value = tipo_descuento_mayor;

document.getElementById("e_comision_menor").value 			= valor_comision_menor;
document.getElementById("e_tipo_comision_menor").value 	= tipo_comision_menor;

document.getElementById("e_descuento_menor").value 			= valor_descuento_menor;
document.getElementById("e_tipo_descuento_menor").value = tipo_descuento_menor;

document.getElementById("e_descripcion_acta").value = acta;
document.getElementById("e_distribuidor").value 		= distribuidor;

document.getElementById("e_usuario_ftp").value = u_ftp;
document.getElementById("e_clave_ftp").value     = c_ftp;


document.getElementById("e_estado").value = estado_venta;

}

//// FUNCIONES CARGADO DE EDICION ///////
/////////////////////////////////////////




</script>


<section style="color:rgb(63,138,214);background-color:#ededed;">
<br>
<h1  align="center" style="color:black; "  >GESTION DE ENTIDADES</h1>
<br>
</section>


<br>
<br>


<div class="card" style="margin-left: 10px; margin-right: 10px">
<div class="card card-header bg-secondary text-white">
<h4 align="center">ENTIDADES</h4>


</div>
<div class="card card-body">


<table  class="table table-bordered">
	<thead>
    <tr>
      <th rowspan="2">ENTIDAD</th>
      <th colspan="2">LOTERIA MAYOR</th>
			<th colspan="2">LOTERIA MENOR</th>
      <th rowspan="2">ESTADO</th>
      <th rowspan="2">EDITAR</th>
		</tr>

		<tr>
			<th>DESCUENTO DE VENTA</th>
      <th>COMISION DE VENTA </th>
      <th>DESCUENTO DE VENTA</th>
      <th>COMISION DE VENTA </th>
		</tr>
	</thead>
	<tbody>
<?php

while ($reg_empresas = mysqli_fetch_array($empresas)) {

	$id = $reg_empresas['id'];
	$empresa = $reg_empresas['nombre_empresa'];
	$acta = $reg_empresas['descripcion_acta'];

	$valor_descuento_mayor = $reg_empresas['descuento_mayor'];
	$tipo_descuento_mayor = $reg_empresas['tipo_descuento_mayor'];
	$valor_comision_mayor = $reg_empresas['rebaja_mayor'];
	$tipo_comision_mayor = $reg_empresas['tipo_rebaja_mayor'];
	$valor_descuento_menor = $reg_empresas['descuento_menor'];
	$tipo_descuento_menor = $reg_empresas['tipo_descuento_menor'];

	$valor_comision_menor = $reg_empresas['rebaja_menor'];
	$tipo_comision_menor = $reg_empresas['tipo_rebaja_menor'];

	$estado_empresa = $reg_empresas['estado'];
	$distribuidor = $reg_empresas['distribuidor'];

	$u_ftp = $reg_empresas['usuario_ftp'];
	$c_ftp = $reg_empresas['clave_ftp'];

	echo "<tr>";
	echo "<td>";
	echo $reg_empresas['nombre_empresa'];
	echo "</td>";
	echo "<td align = 'center'>";
	echo $valor_descuento_mayor;
	if ($tipo_descuento_mayor == 1) {
		echo " Lps";
	} else {
		echo "%";
	}
	echo "</td>";
	echo "<td align = 'center'>";
	echo $valor_comision_mayor;
	if ($tipo_comision_mayor == 1) {
		echo " Lps";
	} else {
		echo "%";
	}
	echo "</td>";
	echo "<td align = 'center'>";
	echo $valor_descuento_menor;
	if ($tipo_descuento_menor == 1) {
		echo " Lps";
	} else {
		echo "%";
	}
	echo "</td>";
	echo "<td align = 'center'>";
	echo $valor_comision_menor;
	if ($tipo_comision_menor == 1) {
		echo " Lps";
	} else {
		echo "%";
	}
	echo "</td>";
	echo "<td align = 'center'>";
	echo $estado_empresa;
	echo "</td>";
	echo "<td align = 'center'>";
	?>
<a onclick = "cargar_edicion('<?php echo $id; ?>','<?php echo $empresa; ?>','<?php echo $valor_comision_mayor; ?>','<?php echo $tipo_comision_mayor; ?>','<?php echo $valor_comision_menor; ?>','<?php echo $tipo_comision_menor; ?>','<?php echo $valor_descuento_mayor; ?>','<?php echo $tipo_descuento_mayor; ?>','<?php echo $valor_descuento_menor; ?>','<?php echo $tipo_descuento_menor; ?>','<?php echo $estado_empresa; ?>','<?php echo $distribuidor; ?>','<?php echo $acta; ?>','<?php echo $u_ftp; ?>','<?php echo $c_ftp; ?>')" data-toggle='modal' href='#modal-edit'  class='btn btn-primary fa fa-edit'></a>
<?php
echo "</td>";

	echo "</tr>";

}

?>

	</tbody>
</table>

</div>

<div class="card-footer" align="center">
	<a  data-toggle="modal" href="#modal-login" class="btn btn-primary">Agregar Nueva Entidad</a>
</div>

</div>

<br><br>



<form method="POST">

<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->
<!-- $$$$$$$$$$$$$$$$$$$$ MODAL DE NUEVO REG $$$$$$$$$$$$$$$$$$$$$$ -->



<div class="modal fade" role="dialog" tabindex="-1" id="modal-login">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">

<div class="modal-header" id="modal-header" >
<h4 class="text-center modal-title" id="modal-heading" style="width:100%;">CREACION DE ENTIDAD</h4>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>

<div class="modal-body" >

<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">ENTIDAD: </span></div>
<input type="text" class="form-control" id="n_entidad" name="n_entidad">
</div>

<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">DESCRIPCION ACTA: </span></div>
<input type="text" class="form-control" id="n_descripcion_acta" name="n_descripcion_acta">
</div>


<div class="row">

<div class="col">
  <div class="input-group" style="margin:5px 0px 5px 0px;">
  <div class="input-group-prepend"><span class="input-group-text">Descuento Mayor</span></div>
  <input type="text" class="form-control" id="n_descuento_mayor" name="n_descuento_mayor" onkeypress = 'return isNumberKey(event)'  >
  <div class="input-group-append" >
    <select name = 'n_tipo_descuento_mayor' id = 'n_tipo_descuento_mayor' class = 'form-control'>
			<option value="1">L</option>
      <option value="2">%</option>
    </select>
  </div>
  </div>
</div>

<div class="col">
  <div class="input-group" style="margin:5px 0px 5px 0px;">
  <div class="input-group-prepend"><span class="input-group-text">Comisión Mayor</span></div>
  <input type="text" class="form-control" id="n_comision_mayor" name="n_comision_mayor"  onkeypress = 'return isNumberKey(event)' >
  <div class="input-group-append" >
    <select name = 'n_tipo_comision_mayor' id = 'n_tipo_comision_mayor' class = 'form-control'>
			<option value="1">L</option>
      <option value="2">%</option>
    </select>
  </div>
  </div>
</div>

</div>


<div class="row">

<div class="col">
  <div class="input-group" style="margin:5px 0px 5px 0px;">
  <div class="input-group-prepend"><span class="input-group-text">Descuento Menor</span></div>
  <input type="text" class="form-control" id="n_descuento_menor" name="n_descuento_menor" onkeypress = 'return isNumberKey(event)' >
  <div class="input-group-append" >
    <select name = 'n_tipo_descuento_menor' id = 'n_tipo_descuento_menor' class = 'form-control'>
			<option value="1">L</option>
      <option value="2">%</option>
    </select>
  </div>
  </div>
</div>

<div class="col">
  <div class="input-group" style="margin:5px 0px 5px 0px;">
  <div class="input-group-prepend"><span class="input-group-text">Valor Comisión Menor</span></div>
  <input type="text" class="form-control" id="n_comision_menor" name="n_comision_menor" onkeypress = 'return isNumberKey(event)' >
  <div class="input-group-append" >
    <select name = 'n_tipo_comision_menor' id = 'n_tipo_comision_menor' class = 'form-control'>
			<option value="1">L</option>
      <option value="2">%</option>
    </select>
  </div>
  </div>
</div>

</div>


<div class="row">

<div class="col">
  <div class="input-group" style="margin:5px 0px 5px 0px;">
  <div class="input-group-prepend"><span class="input-group-text">Estado</span></div>
    <select name = 'n_estado' id = 'n_estado' class = 'form-control'>
      <option value="ACTIVO">ACTIVO</option>
    </select>
  </div>
</div>

<div class="col">
  <div class="input-group" style="margin:5px 0px 5px 0px;">
  <div class="input-group-prepend"><span class="input-group-text">Distribuidor</span></div>
    <select name = 'n_distribuidor' id = 'n_distribuidor' class = 'form-control'>
      <option value="NO">NO</option>
      <option value="SI">SI</option>
    </select>
  </div>
</div>

</div>


</div>

<div class="modal-footer" id="modal-footer" >
<button name="guardar_nuevo" style="margin-top: 10px" class="btn btn-info" type="submit">Guardar</button>
</div>


</div>
</div>
</div>


<!-- $$$$$$$$$$$$$$$$$$$$ MODAL DE NUEVO REG $$$$$$$$$$$$$$$$$$$$$$ -->
<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->






<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->
<!-- $$$$$$$$$$$$$$$$$$$$ MODAL DE EDIT REG $$$$$$$$$$$$$$$$$$$$$$ -->



<div class="modal fade" role="dialog" tabindex="-1" id="modal-edit">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">

<div class="modal-header" id="modal-header" >
<h4 class="text-center modal-title" id="modal-heading" style="width:100%;">EDICION DE ENTIDAD</h4>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>

<div class="modal-body" >

<input type="hidden" name = 'e_id'  id = 'e_id' >

<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">ENTIDAD: </span></div>
<input type="text" class="form-control" id="e_entidad" name="e_entidad">
</div>

<div class="input-group" style="margin:5px 0px 5px 0px;">
<div class="input-group-prepend"><span class="input-group-text">DESCRIPCION ACTA: </span></div>
<input type="text" class="form-control" id="e_descripcion_acta" name="e_descripcion_acta">
</div>


<div class="row">

<div class="col">
  <div class="input-group" style="margin:5px 0px 5px 0px;">
  <div class="input-group-prepend"><span class="input-group-text">Descuento Mayor</span></div>
  <input type="text" class="form-control" id="e_descuento_mayor" name="e_descuento_mayor" onkeypress = 'return isNumberKey(event)'  >
  <div class="input-group-append" >
    <select name = 'e_tipo_descuento_mayor' id = 'e_tipo_descuento_mayor' class = 'form-control'>
      <option value="1">L</option>
      <option value="2">%</option>
    </select>
  </div>
  </div>
</div>

<div class="col">
  <div class="input-group" style="margin:5px 0px 5px 0px;">
  <div class="input-group-prepend"><span class="input-group-text">Comisión Mayor</span></div>
  <input type="text" class="form-control" id="e_comision_mayor" name="e_comision_mayor"  onkeypress = 'return isNumberKey(event)' >
  <div class="input-group-append" >
    <select name = 'e_tipo_comision_mayor' id = 'e_tipo_comision_mayor' class = 'form-control'>
			<option value="1">L</option>
      <option value="2">%</option>
    </select>
  </div>
  </div>
</div>

</div>


<div class="row">

<div class="col">
  <div class="input-group" style="margin:5px 0px 5px 0px;">
  <div class="input-group-prepend"><span class="input-group-text">Descuento Menor</span></div>
  <input type="text" class="form-control" id="e_descuento_menor" name="e_descuento_menor" onkeypress = 'return isNumberKey(event)' >
  <div class="input-group-append" >
    <select name = 'e_tipo_descuento_menor' id = 'e_tipo_descuento_menor' class = 'form-control'>
			<option value="1">L</option>
      <option value="2">%</option>
    </select>
  </div>
  </div>
</div>

<div class="col">
  <div class="input-group" style="margin:5px 0px 5px 0px;">
  <div class="input-group-prepend"><span class="input-group-text">Comisión Menor</span></div>
  <input type="text" class="form-control" id="e_comision_menor" name="e_comision_menor" onkeypress = 'return isNumberKey(event)' >
  <div class="input-group-append" >
    <select name = 'e_tipo_comision_menor' id = 'e_tipo_comision_menor' class = 'form-control'>
			<option value="1">L</option>
      <option value="2">%</option>
    </select>
  </div>
  </div>
</div>

</div>


<div class="row">

<div class="col">
  <div class="input-group" style="margin:5px 0px 5px 0px;">
  <div class="input-group-prepend"><span class="input-group-text">Estado</span></div>
    <select name = 'e_estado' id = 'e_estado' class = 'form-control'>
      <option value="ACTIVO">ACTIVO</option>
			<option value="INACTIVO">INACTIVO</option>
    </select>
  </div>
</div>

<div class="col">
  <div class="input-group" style="margin:5px 0px 5px 0px;">
  <div class="input-group-prepend"><span class="input-group-text">Distribuidor</span></div>
    <select name = 'e_distribuidor' id = 'e_distribuidor' class = 'form-control'>
      <option value="NO">NO</option>
      <option value="SI">SI</option>
    </select>
  </div>
</div>

</div>


<hr>

<div class="row">

<div class="col">
  <div class="input-group" style="margin:5px 0px 5px 0px;">
  <div class="input-group-prepend"><span class="input-group-text">Usuario FTP</span></div>
  <input type="text" class="form-control" id="e_usuario_ftp" name="e_usuario_ftp"   >
  </div>
</div>

<div class="col">
  <div class="input-group" style="margin:5px 0px 5px 0px;">
  <div class="input-group-prepend"><span class="input-group-text">Clave FTP</span></div>
  <input type="text" class="form-control" id="e_clave_ftp" name="e_clave_ftp"   >
  </div>
</div>

</div>


</div>

<div class="modal-footer" id="modal-footer" style="background-color:rgb(255,255,255);">
<button name="editar_entidad" style="margin-top: 10px" class="btn btn-info" type="submit">Guardar Cambios</button>
</div>


</div>
</div>
</div>


<!-- $$$$$$$$$$$$$$$$$$$$ MODAL DE EDIT REG $$$$$$$$$$$$$$$$$$$$$$ -->
<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->







</form>
