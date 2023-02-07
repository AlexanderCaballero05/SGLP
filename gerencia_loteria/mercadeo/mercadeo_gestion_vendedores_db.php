<?php





if (isset($_POST['guardar_nuevo'])) {

$nuevo_id   = $_POST['nuevo_id'];
$tipo_id    = 1;
$id_usuario = $_SESSION['id_usuario'];

$v_id = explode("-", $nuevo_id);
$nuevo_id = $v_id[0].$v_id[1].$v_id[2];


$consulta_vendedor = mysqli_query($conn, "SELECT id FROM vendedores WHERE identidad = '$nuevo_id' ");

if (mysqli_num_rows($consulta_vendedor) > 0) {

echo "<div class = 'alert alert-danger'>El numero de identidad que intenta ingresar ya existe, si no esta realizando ingreso de nuevo vendedor por favor evite actualizar la pantalla mediante F5. </div>";

}else{



$nuevo_codigo	     = $_POST['nuevo_codigo'];
$nuevo_nombre 	     = strtoupper($_POST['nuevo_nombre']);
$nueva_asociacion    = $_POST['nueva_asociacion'];
$nuevo_telefono      = $_POST['nuevo_telefono'];
$nueva_direccion     = $_POST['nueva_direccion'];
$nuevo_sexo 	     = $_POST['nuevo_sexo'];
$nuevo_estado_civil  = $_POST['nuevo_estado_civil'];
$nueva_zona_venta    = $_POST['nueva_zona_venta'];
$nuevo_cod_municipio = $_POST['nuevo_municipio'];
$nuevo_productos = $_POST['nuevo_productos'];



$v_codigo 			 = explode("-", $nuevo_codigo);
$nuevo_codigo 		 = $v_codigo[2];
$nueva_bolsa 		 = $_POST['nuevo_bolsa'];
$nueva_seccional 	 = $_POST['nueva_seccional'];

$fileTempName = $_FILES['foto']['tmp_name']; 

if ($fileTempName != '') {
$temp = explode(".", $_FILES["foto"]["name"]);
$newfilename = $nuevo_id . '.' . end($temp);

if (move_uploaded_file($_FILES["foto"]["tmp_name"], "./imagenes/vendedores/" . $newfilename) === FALSE) {

echo "<div class = 'alert alert-danger'>Error al cargar la imagen</div>";
$newfilename = "";

}

}else{
$newfilename = "";	
}



$nuevo_correo  = $_POST['nuevo_correo'];
$nuevo_discapacidad    = $_POST['nuevo_discapacidad'];
$nuevo_desc_discapacidad = $_POST['nuevo_desc_discapacidad'];
$nuevo_num_hijos = $_POST['nuevo_num_hijos'];
$nuevo_municipio_venta = $_POST['nuevo_municipio_venta'];
$nuevo_tipo_sangre = $_POST['nuevo_tipo_sangre'];
$nuevo_fecha_nacimiento = $_POST['nuevo_fecha_nacimiento'];


if (mysqli_query($conn,"INSERT INTO vendedores (identidad, codigo, asociacion, nombre, telefono, direccion, sexo, estado_civil, zona_venta, foto, estado, geocodigo, tipo_identificacion, id_usuario_creacion, numero_bolsas, seccional, correo, discapacidad, desc_discapacidad, num_hijos, geocodigo_venta, tipo_sangre, fecha_nacimiento, productos) VALUES ('$nuevo_id','$nuevo_codigo','$nueva_asociacion','$nuevo_nombre','$nuevo_telefono','$nueva_direccion','$nuevo_sexo', '$nuevo_estado_civil' , '$nueva_zona_venta' , '$newfilename','1','$nuevo_cod_municipio','$tipo_id','$id_usuario', '$nueva_bolsa' , '$nueva_seccional', '$nuevo_correo', '$nuevo_discapacidad', '$nuevo_desc_discapacidad', '$nuevo_num_hijos', '$nuevo_municipio_venta', '$nuevo_tipo_sangre', '$nuevo_fecha_nacimiento', '$nuevo_productos') ") === TRUE) {

/*
$conn = mysqli_connect('172.16.21.14:3306', 'SVR_BD', 'softlotbanrural')
    or die('No se pudo conectar: ' . mysqli_error());
mysqli_select_db('cidesoft_banco', $conn) or die('No se pudo seleccionar la base de datos');

$insert_banco = mysqli_query($conn,"INSERT INTO vendedores (identidad, codigo, asociacion, nombre, telefono, estado) VALUES ('$nuevo_id','$nuevo_codigo','$nueva_asociacion','$nuevo_nombre','$nuevo_telefono','1') ");

mysqli_close($conn);

*/

echo "<div class = 'alert alert-info'>Registro ingresado correctamente</div>";
}else{
echo "<div class = 'alert alert-danger'>Error inesperado, por favor intentelo de nuevo <br> ".mysqli_error($conn)."</div>";
}


/*
$conn = mysqli_connect('localhost', 'SVR_APP', 'softlotpani**')
    or die('No se pudo conectar: ' . mysqli_error());
mysqli_select_db('pani', $conn) or die('No se pudo seleccionar la base de datos');
*/






}

$asociaciones = mysqli_query($conn,"SELECT a.correo, a.discapacidad, a.desc_discapacidad, a.num_hijos, a.geocodigo_venta, a.tipo_sangre, a.fecha_nacimiento, a.id, a.identidad ,a.tipo_identificacion, a.codigo, a.nombre, a.direccion, a.foto, a.asociacion, a.telefono, a.estado, a.estado_civil , a.sexo , a.zona_venta , b.nombre_asociacion, a.geocodigo, a.seccional, a.numero_bolsas FROM vendedores as a INNER JOIN asociaciones_vendedores as b ON a.asociacion = b.codigo_asociacion ");

}







if (isset($_POST['guardar_edicion'])) {
$id = $_POST['id_edicion'];

$identidad  = $_POST['edicion_id']; 

$v_id = explode("-", $identidad);
$identidad = $v_id[0].$v_id[1].$v_id[2];


$nombre 	  = strtoupper($_POST['edicion_nombre']);
$asociacion   = $_POST['edicion_asociacion'];
$telefono 	  = $_POST['edicion_telefono'];
$direccion 	  = $_POST['edicion_direccion'];
$sexo         = $_POST['edicion_sexo'];
$estado_civil = $_POST['edicion_estado_civil'];
$zona_venta   = $_POST['edicion_zona_venta'];
$geocodigo    = $_POST['edicion_municipio'];
$productos    = $_POST['edicion_productos'];

$v_seccional = explode("-", $_POST['edicion_seccional']);
$seccional 	 = $v_seccional[1];



$codigo  	  = $_POST['edicion_codigo'];
$cantidad  	  = $_POST['edicion_bolsa'];
$estado       = $_POST['edicion_estado'];

$v_codigo 	  = explode("-", $codigo);
$codigo 	  = $v_codigo[2];




    //    correo, discapacidad, desc_discapacidad, num_hijos, municipio_venta, tipo_sangre, fecha_nacimiento 

$correo = $_POST["edicion_correo"];
$discapacidad = $_POST["edicion_discapacidad"];
$desc_discapacidad = $_POST["edicion_desc_discapacidad"];

$num_hijos = $_POST["edicion_num_hijos"];
$tipo_sangre = $_POST["edicion_tipo_sangre"];
$fecha_nacimiento = $_POST["edicion_fecha_nacimiento"];
$municipio_venta = $_POST["edicion_municipio_venta"];



if ($_FILES["foto_edicion"]["name"] != "") {

$temp = explode(".", $_FILES["foto_edicion"]["name"]);
$newfilename = $identidad . '.' . end($temp);

if (move_uploaded_file($_FILES["foto_edicion"]["tmp_name"], "./imagenes/vendedores/" . $newfilename) === FALSE) {
echo "<div class = 'alert alert-danger'>Error al cargar la imagen</div>";
$newfilename = "";
}


if (mysqli_query($conn," UPDATE vendedores SET  seccional = '$seccional' ,codigo = '$codigo' , asociacion = '$asociacion' , nombre = '$nombre' , telefono = '$telefono', sexo = '$sexo', estado_civil = '$estado_civil' , zona_venta = '$zona_venta' , direccion =  '$direccion', estado = '$estado'  , geocodigo = '$geocodigo' , numero_bolsas = '$cantidad' , correo = '$correo', discapacidad = '$discapacidad', desc_discapacidad = '$desc_discapacidad' , num_hijos = '$num_hijos', tipo_sangre = '$tipo_sangre' , fecha_nacimiento = '$fecha_nacimiento', geocodigo_venta = '$municipio_venta', foto = '$newfilename' , productos = '$productos' WHERE id = '$id' ") === TRUE) {

echo "<div class = 'alert alert-info'>Registro actualizado correctamente</div>";
}else{
echo "<div class = 'alert alert-danger'>Error inesperado, por favor intentelo de nuevo <br> ".mysqli_error($conn)."</div>";	
}


}else{

if (mysqli_query($conn," UPDATE vendedores SET  seccional = '$seccional' ,codigo = '$codigo' , asociacion = '$asociacion' , nombre = '$nombre' , telefono = '$telefono', sexo = '$sexo', estado_civil = '$estado_civil' , zona_venta = '$zona_venta' , direccion =  '$direccion', estado = '$estado' , geocodigo = '$geocodigo', numero_bolsas = '$cantidad' , correo = '$correo', discapacidad = '$discapacidad', desc_discapacidad = '$desc_discapacidad' , num_hijos = '$num_hijos', tipo_sangre = '$tipo_sangre' , fecha_nacimiento = '$fecha_nacimiento', geocodigo_venta = '$municipio_venta', productos = '$productos' WHERE id = '$id' ") === TRUE) {

echo "<div class = 'alert alert-info'>Registro actualizado correctamente</div>";
}else{
echo "<div class = 'alert alert-danger'>Error inesperado, por favor intentelo de nuevo <br> ".mysqli_error($conn)."</div>";	
}


}



$asociaciones = mysqli_query($conn,"SELECT a.correo, a.discapacidad, a.desc_discapacidad, a.num_hijos, a.geocodigo_venta, a.tipo_sangre, a.fecha_nacimiento, a.id, a.identidad ,a.tipo_identificacion, a.codigo, a.nombre, a.direccion, a.foto, a.asociacion, a.telefono, a.estado, a.estado_civil , a.sexo , a.zona_venta , b.nombre_asociacion, a.geocodigo, a.seccional, a.numero_bolsas, a.productos FROM vendedores as a INNER JOIN asociaciones_vendedores as b ON a.asociacion = b.codigo_asociacion ");

}
