<?php
require '../../template/header.php';
?>

<script type="text/javascript">


jQuery(function($){
$("#identidad").mask("9999-9999-99999", { placeholder: "____-____-_____" });
});



///////////////////////////////////////
////////// CONSULTAR ID ///////////////
function consultar_empleado(){
identidad  = document.getElementById('identidad').value;
token = Math.random();
consulta = 'emision_carnet_empleados_db.php?id='+identidad+"&token="+token;
$("#respuesta_consulta").load(consulta);
}
////////// CONSULTAR ID ///////////////
///////////////////////////////////////



////////////////////////////////////
// FUNCIONES DE CARGADO DE IMAGEN //

function readURL(input) {
  if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
          $('#vista_previa').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
  }
}

function readURL2(input) {
  if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
          $('#vista_previa_edicion').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
  }
}

// FUNCIONES DE CARGADO DE IMAGEN //
////////////////////////////////////


</script>

<form method="POST" enctype="multipart/form-data">



<section style="color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >CARNETIZACIÓN DE EMPLEADOS</h2>
<br>
</section>
<br>


<div style="width: 100%" align="center">
<div class="card" style="width: 50%">
<div class="card-header bg-primary" >

<div class="input-group" >
<div class="input-group-prepend"><span style="width: 100%" class = "input-group-text">IDENTIDAD DE EMPLEADO: </span></div>
<input type="text" name="identidad" id="identidad"  class="form-control" >
<div  class="input-group-append">
<button class="btn btn-success" type="submit" name="seleccionar" onclick="consultar_empleado()" > BUSCAR</button>
</div>
</div>

</div>
<div class="card-body" id="respuesta_consulta">






<?php

if (isset($_POST['seleccionar'])) {

	$identidad = $_POST['identidad'];

//////////////////////////////////////////////////////////
	///////////////// CONSULTA INFO EMPLEADO /////////////////

	$conn2 = oci_connect('cide', 'pani2017', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.15.102)(PORT=1521)))(CONNECT_DATA=(SID=dbpani)(SERVER = DEDICATED)(SERVICE_NAME = DBPANITG)))');

	if ($conn2 == FALSE) {
		$e = oci_error();
		$msg_error = "ERROR DE CONEXION ORACLE: " . $e['message'] . "<br>";
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		exit;
	}

	$consulta_empleados = oci_parse($conn2, "SELECT NOMBRE_PILA , APE_PAT, APE_MAT, PUESTO, DEPTO  FROM PL_EMPLEADOS WHERE CEDULA = '$identidad'  ");

	oci_execute($consulta_empleados);

	$nombre = '';
	while ($reg_empleado = oci_fetch_array($consulta_empleados, OCI_ASSOC + OCI_RETURN_NULLS)) {
		$nombre = $reg_empleado['NOMBRE_PILA'] . ' ' . $reg_empleado['APE_PAT'] . ' ' . $reg_empleado['APE_MAT'];
		$nombre_1 = $reg_empleado['NOMBRE_PILA'];
		$nombre_2 = $reg_empleado['APE_PAT'] . ' ' . $reg_empleado['APE_MAT'];
		$cod_puesto = $reg_empleado['PUESTO'];
		$cod_departamento = $reg_empleado['DEPTO'];
	}

	$consulta_puestos = oci_parse($conn2, "SELECT DESCRI FROM PL_PUESTOS  WHERE PUESTO = '$cod_puesto'  ");
	oci_execute($consulta_puestos);

	while ($reg_puesto = oci_fetch_array($consulta_puestos, OCI_ASSOC + OCI_RETURN_NULLS)) {
		$puesto = $reg_puesto['DESCRI'];
	}


	$consulta_departamentos = oci_parse($conn2, "SELECT DESCRI FROM PL_DEPARTAMENTOS  WHERE DEPA = '$cod_departamento'  ");
	oci_execute($consulta_departamentos);

	while ($reg_depto = oci_fetch_array($consulta_departamentos, OCI_ASSOC + OCI_RETURN_NULLS)) {
		$departamento = $reg_depto['DESCRI'];
	}

///////////////// CONSULTA INFO EMPLEADO /////////////////
	//////////////////////////////////////////////////////////

//$identidad = "balls.jpg";
	//$puesto = "ANALISTA PROGRAMADOR";
	$random = mt_rand();
	$foto = '../imagenes/empleados/' . $identidad . ".jpg";
	$name_foto = $identidad . ".jpg";
	if (file_exists($foto)) {
		$existe = 1;
	} else {
		$existe = 0;
	}

	if ($nombre != "") {

		?>


<table class="table table-bordered">
<tr>

<td valign="top" width="20%">

<?php
if ($existe == 0) {
			?>
<img width="150px" height="150px" onclick="document.getElementById('foto').click()"  id="vista_previa" src="./imagenes/default_foto.png" alt="" >
<?php
} else {
			?>
<img width="150px" height="150px" onclick="document.getElementById('foto').click()"  id="vista_previa" src="<?php echo $foto; ?>?d=<?php echo $random ?>" alt="" >
<?php
}
		?>
</td>

<td >

<div style="width: 100%" class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Identidad</span></div>
<input type="text" onblur="validar_identidad(this.value)" name="id" id="id" class="form-control" value="<?php echo $identidad; ?>" readonly="true">
</div>

<input style="text-transform:uppercase" class="form-control" type="hidden" name="nombre" id="nombre" value="<?php echo $nombre; ?>" readonly="true">

<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Nombres</span></div>
<input style="text-transform:uppercase" class="form-control" type="text" name="nombre1" id="nombre1" value="<?php echo $nombre_1; ?>" readonly="true">
</div>


<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Apellidos</span></div>
<input style="text-transform:uppercase" class="form-control" type="text" name="nombre2" id="nombre2" value="<?php echo $nombre_2; ?>" readonly="true">
</div>


<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Puesto</span></div>
<input style="text-transform:uppercase" class="form-control" type="text" name="puesto" id="puesto" value="<?php echo $puesto; ?>" readonly="true">
</div>
<?php
$c_puesto_e = mysqli_query($conn, "SELECT * FROM  rr_hh_empleados_desc_puestos_provicionales WHERE identidad = '$identidad' ");
if (mysqli_num_rows($c_puesto_e) > 0) {
$ob_puesto_e = mysqli_fetch_object($c_puesto_e);
$puest_e = $ob_puesto_e->descripcion_puesto;
}else{
$puest_e = '';
}

?>
<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Puesto Editable</span></div>
<input style="text-transform:uppercase" class="form-control" type="text" name="puesto_e" id="puesto_e" value="<?php echo $puest_e; ?>" >
</div>

<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Departamento</span></div>
<input style="text-transform:uppercase" class="form-control" type="text" name="puesto2" id="puesto2" value="<?php echo $departamento; ?>" readonly="true">
</div>

</td>

</tr>

<tr>
	<td colspan="2">
<input class="form-control" type='file' id="foto" name="foto"  onchange="readURL(this);" >
	</td>
</tr>

</table>


<?php

		if ($existe == 1) {

			?>


<p align="center">
<button class="btn btn-success" name="guardar_foto" type="submit" style="margin-right: 5px">Actualizar Foto</button>

<a class="btn btn-success" target="_blank" href="./print_carnet.php?f=<?php echo $name_foto ?>&i=<?php echo $identidad ?>&n1=<?php echo $nombre_1 ?>&n2=<?php echo $nombre_2 ?>&p=<?php echo $puesto ?>&d=<?php echo $departamento ?>&r=<?php echo $random ?>">Imprimir carnet</a>

<a class="btn btn-success" target="_blank" href="./print_back_carnet.php">Imprimir carnet (Back)</a>

</p>

<?php

		} else {

			?>

<p align="center">
<button class="btn btn-success" name="guardar_foto" type="submit">guardar e Imprimir carnet</button>
</p>

<?php

		}

	} else {

		echo "<div class = 'alert alert-danger'>El número de identidad consultado no existe.</div>";

	}

}

?>


</form>





</div>
</div>
</div>


<?php

if (isset($_POST['guardar_foto'])) {

ini_set('display_errors',1);
error_reporting(E_ALL);

	$identidad = $_POST['id'];
	$nombre = $_POST['nombre'];
	$nombre_1 = $_POST['nombre1'];
	$nombre_2 = $_POST['nombre2'];
	$puesto = $_POST['puesto'];
	$puesto_e = $_POST['puesto_e'];
	$departamento = $_POST['puesto2'];

	$fileTempName = $_FILES['foto']['tmp_name'];
	$temp = explode(".", $_FILES["foto"]["name"]);
	$newfilename = $identidad . '.' . end($temp);

	$name_foto = $newfilename;
	$random = rand();


		mysqli_query($conn, "DELETE  FROM rr_hh_empleados_desc_puestos_provicionales WHERE identidad = '$identidad' LIMIT 1 ");
		mysqli_query($conn, "INSERT INTO rr_hh_empleados_desc_puestos_provicionales  (identidad, descripcion_puesto) VALUES ('$identidad', '$puesto_e') ");

	echo mysqli_error($conn);

	if (move_uploaded_file($_FILES["foto"]["tmp_name"], "../imagenes/empleados/" . $newfilename) === FALSE) {

		echo "<br><br><div class = 'alert alert-danger'>Error al cargar la imagen</div>";
	} else {
		echo "<br><div align = 'center' class = 'alert alert-info'>Fotografia de empleado <b>".$nombre."</b> guardada correctamente, por favor proceda a imprimir su respectivo carnet. <br> <br>";

?>

<a class="btn btn-success" target="_blank" href="./print_carnet.php?f=<?php echo $name_foto ?>&i=<?php echo $identidad ?>&n1=<?php echo $nombre_1 ?>&n2=<?php echo $nombre_2 ?>&p=<?php echo $puesto ?>&d=<?php echo $departamento ?>&r=<?php echo $random ?>">Imprimir carnet</a>
<a class="btn btn-success" target="_blank" href="./print_back_carnet.php">Imprimir carnet (Back)</a>

<?php

echo "</div>";


	}

}

?>