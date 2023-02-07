<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<?php
require('../../template/header.php');
require('./mercadeo_gestion_vendedores_db.php');
?>

<script type="text/javascript" src="./js/jquery.maskedinput.js" ></script>

<script type="text/javascript">

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




/////////////////////////////////////
// FUNCIONES ENMASCARADO DE INPUTS //

jQuery(function($){
$("#nuevo_id").mask("9999-9999-99999", { placeholder: "____-____-_____" });
$("#nuevo_telefono").mask("9999-99-99", { placeholder: "____-__-__" });
});




// FUNCIONES ENMASCARADO DE INPUTS //
/////////////////////////////////////


/////////////////////////////////////
//// FUNCIONES CARGADO DE EDICION ///

function cargar_edicion(id,identidad,edicion_codigo,edicion_nombre,nombre_asociacion,telefono,direccion,foto,estado_civil,sexo,zona_venta,tipo_id,geocodigo, asociacion, seccional, bolsas){

if (tipo_id == 1) {
identidad1 = identidad.substring(0, 4);
identidad2 = identidad.substring(4, 8);
identidad3 = identidad.substring(8, 13);
identidad  = identidad1+'-'+identidad2+'-'+identidad3;   
}


longitud = geocodigo.length;

if (longitud == 3) {
dpto = geocodigo.substr(0, 1);
}else{
dpto = geocodigo.substr(0, 2);  
}

document.getElementById("id_edicion").value           = id;
document.getElementById("edicion_id").value           = identidad;
document.getElementById("edicion_codigo").value       = edicion_codigo;
document.getElementById("edicion_nombre").value       = edicion_nombre;
document.getElementById("edicion_telefono").value     = telefono;
document.getElementById("edicion_direccion").value    = direccion;
document.getElementById("edicion_sexo").value         = sexo;
document.getElementById("edicion_estado_civil").value = estado_civil;
document.getElementById("edicion_zona_venta").value   = zona_venta;

document.getElementById("edicion_asociacion").value   = asociacion;
document.getElementById("edicion_seccional").value    = seccional;
document.getElementById("edicion_bolsa").value    = bolsas;


if (foto != ''){
document.getElementById("vista_previa_edicion").src="./imagenes/vendedores/"+foto;
}else{
document.getElementById("vista_previa_edicion").src="./imagenes/default_foto.png";
}


if (geocodigo != '') {

document.getElementById("edicion_departamento").options[dpto].selected=true;
conteo_options = document.getElementById("edicion_municipio").length;

for (i = 0; i <= conteo_options; i++) {

if (document.getElementById("edicion_municipio").options[i].value == geocodigo ) {
document.getElementById("edicion_municipio").options[i].selected=true;
}

}

}

}

//// FUNCIONES CARGADO DE EDICION ///
/////////////////////////////////////


/////////////////////////////////////////
/// FUNCION PARA CARGADO DE MUNICIPIOS //

function funcion_seleccion_nuevo(id_depto){
var obj_select = document.getElementById("nuevo_municipio");
conteo_opciones = obj_select.length;
obj_select.options[0].selected = true;

for (var i = 1; i <= conteo_opciones; i++) {

if (obj_select.options[i].id == id_depto ) {
obj_select.options[i].style.display = "block";
}else{
obj_select.options[i].style.display = "none";  
}
}

}


function funcion_seleccion_edicion(id_depto){

var obj_select = document.getElementById("edicion_municipio");
conteo_opciones = obj_select.length;
obj_select.options[0].selected = true;

for (var i = 1; i <= conteo_opciones; i++) {

if (obj_select.options[i].id == id_depto ) {
obj_select.options[i].style.display = "block";
}else{
obj_select.options[i].style.display = "none";  
}
}

}


/// FUNCION PARA CARGADO DE MUNICIPIOS //
/////////////////////////////////////////



//////////////////////////////////////////
// FUNCION PARA CARGADO DE SECCIONALES ///

function funcion_seleccion_nuevo_asociacion(id_asociacion){

document.getElementById("nuevo_codigo").value = '';

var obj_select = document.getElementById("nueva_seccional");
conteo_opciones = obj_select.length;
obj_select.options[0].selected = true;

for (var i = 1; i <= conteo_opciones; i++) {

if (obj_select.options[i].id == id_asociacion ) {
obj_select.options[i].style.display = "block";
}else{
obj_select.options[i].style.display = "none";  
}
}

}
// FUNCION PARA CARGADO DE SECCIONALES ///
//////////////////////////////////////////



//////////////////////////////////////////
// FUNCION PARA CARGADO DE SECCIONALES ///

function funcion_seleccion_edicion_asociacion(id_asociacion){

document.getElementById("edicion_codigo").value = '';

var obj_select = document.getElementById("edicion_seccional");
conteo_opciones = obj_select.length;
obj_select.options[0].selected = true;

for (var i = 1; i <= conteo_opciones; i++) {

if (obj_select.options[i].id == id_asociacion ) {
obj_select.options[i].style.display = "block";
}else{
obj_select.options[i].style.display = "none";  
}
}

}
// FUNCION PARA CARGADO DE SECCIONALES ///
//////////////////////////////////////////



//////////////////////////////////////////
// FUNCION PARA CARGADO DE CODIGO ///

function funcion_seleccion_nuevo_seccional(id_seccional){

asociacion  = document.getElementById('nueva_asociacion').value;
seccional   = document.getElementById('nueva_seccional').value;

token = Math.random();
consulta = 'mercadeo_gestion_vendedores_correlativo.php?a='+asociacion+"&s="+seccional+"&tipo=n&token="+token;     

$("#respuesta_consulta").load(consulta);


}
// FUNCION PARA CARGADO DE SECCIONALES ///
//////////////////////////////////////////




//////////////////////////////////////////
// FUNCION PARA CARGADO DE CODIGO ///

function funcion_seleccion_edicion_seccional(id_seccional){

asociacion  = document.getElementById('edicion_asociacion').value;
seccional   = document.getElementById('edicion_seccional').value;

token = Math.random();
consulta = 'mercadeo_gestion_vendedores_correlativo.php?a='+asociacion+"&s="+seccional+"&tipo=e&token="+token;     

$("#respuesta_consulta").load(consulta);


}
// FUNCION PARA CARGADO DE SECCIONALES ///
//////////////////////////////////////////



///////////////////////////////////////////
// FUNCION VALIDAR IDENTIDAD NO REPETIDA //

function validar_identidad(id){
identidad  = id;
token = Math.random();
consulta = 'mercadeo_gestion_vendedores_validar_id.php?id='+identidad+"&tipo_id=1&token="+token;     
$("#respuesta_consulta").load(consulta);
}

// FUNCION VALIDAR IDENTIDAD NO REPETIDA //
///////////////////////////////////////////





function validar_nuevo_vendedor(){
nuevo_id           = document.getElementById('nuevo_id').value;
nuevo_nombre       = document.getElementById('nuevo_nombre').value;
nueva_asociacion   = document.getElementById('nueva_asociacion').value;
nuevo_departamento = document.getElementById('nuevo_departamento').value;
nuevo_municipio    = document.getElementById('nuevo_municipio').value;
nueva_seccional    = document.getElementById('nueva_seccional').value;
zona_venta         = document.getElementById('nueva_zona_venta').value;
bolsas             = document.getElementById('nuevo_bolsa').value;
foto               = document.getElementById('foto').value;

if (nuevo_id != '') {

if (nuevo_nombre != '') {

if (nueva_asociacion != 'no') {

if (nueva_seccional != 'no') {

if ( nuevo_departamento != 'ninguno' && nuevo_municipio != 'ninguno' ) {

if (zona_venta != '') {

if (bolsas != '') {

if (foto != '') {

document.getElementById("guardar_nuevo").click();  

}else{
swal("ERROR", "Debe seleccionar la fotografia del vendedor.", "error");    
}

}else{
swal("ERROR", "Debe ingresar el numero de bolsas del vendedor.", "error");    
}

}else{
swal("ERROR", "Debe ingresar la zona de venta.", "error");  
}

}else{
swal("ERROR", "Debe seleccionar un departamento y municipio.", "error");  
}

}else{
swal("ERROR", "Debe seleccionar una seccional.", "error");    
}

}else{
swal("ERROR", "Debe seleccionar una asociacion.", "error");  
}

}else{
swal("ERROR", "Debe ingresar el nombre del nuevo vendedor.", "error");  
}


}else{
swal("ERROR", "Debe ingresar el numero de identidad del nuevo vendedor.", "error");  
}

}



////////////////////////////////////////////////
////////////// VALIDAR EDICION /////////////////

function validar_edicion_vendedor(){

edicion_id           = document.getElementById('edicion_id').value;
edicion_nombre       = document.getElementById('edicion_nombre').value;
edicion_asociacion   = document.getElementById('edicion_asociacion').value;
edicion_municipio    = document.getElementById('edicion_municipio').value;
edicion_departamento = document.getElementById('edicion_departamento').value;

if (edicion_id != '') {

if (edicion_nombre != '') {

if (edicion_asociacion != '') {

if ( edicion_departamento != 'ninguno' && edicion_municipio != 'ninguno' ) {

document.getElementById("guardar_edicion").click();  

}else{
swal("ERROR", "Debe seleccionar un departamento y municipio.", "error");
}

}else{
swal("ERROR", "Debe seleccionar una asociacion.", "error");  
}

}else{
swal("ERROR", "Debe ingresar el nombre del vendedor.", "error");  
}


}else{
swal("ERROR", "Debe ingresar el numero de identidad del vendedor.", "error");  
}

}
///////////////
/////////////////////////////////////////////////////////////


</script>

<form method="POST" enctype="multipart/form-data">

<div id="respuesta_consulta"></div>

<section style="color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >GESTION DE VENDEDORES AUTORIZADOS</h2> 
<br>
</section>
<br>


<div align="center">
<a style="width: 99%; margin-left: 10px; margin-right: 10px;" data-toggle="modal" href="#nuevo" class="btn btn-primary">Agregar Vendedor</a>  
</div>

<br>
<br>
<div class="card" style="margin-left: 10px;margin-right: 10px;">
<div class="card-header">
  <h3 align="center">Historico de Vendedores Registrados</h3>
</div>

<div class="card-body">

<table class="table table-bordered table-responsive-sm" id="table_id1">
  <thead>
    <th>Identidad</th>
    <th>Nombre</th>
    <th>Asociacion</th>
    <th>Telefono</th>
    <th>Estado</th>
    <th>Accion</th>
  </thead>
  <tbody>
<?php

while ($reg_asociacion = mysqli_fetch_array($asociaciones)) {

$id                 = $reg_asociacion['id'];
$identidad          = $reg_asociacion['identidad'];
$nom                = $reg_asociacion['nombre'];
$nombre_asociacion  = $reg_asociacion['nombre_asociacion'];
$cod                = $reg_asociacion['asociacion']."-".$reg_asociacion['seccional']."-".$reg_asociacion['codigo'];
$seccional          = $reg_asociacion['seccional'];
$asociacion         = $reg_asociacion['asociacion'];
$concat_codigo      = $cod;
$telefono           = $reg_asociacion['telefono'];
$direccion          = $reg_asociacion['direccion'];
$rand               = rand(0, 99999);
$foto               = $reg_asociacion['foto']."?rand".$rand;
$sexo               = $reg_asociacion['sexo'];
$estado_civil       = $reg_asociacion['estado_civil'];
$zona_venta         = $reg_asociacion['zona_venta'];
$tipo_id            = $reg_asociacion['tipo_identificacion'];
$geocodigo          = $reg_asociacion['geocodigo'];
$bolsas             = $reg_asociacion['numero_bolsas'];

echo "<tr>";
echo "<td>";
echo $reg_asociacion['identidad'];
echo "</td>";
echo "<td>";
echo $reg_asociacion['nombre'];
echo "</td>";
echo "<td>";
echo $concat_codigo;
echo "</td>";
echo "<td>";
echo $reg_asociacion['telefono'];
echo "</td>";
echo "<td>";
if ($reg_asociacion['estado'] == 1) {
echo "ACTIVO";
}else{
echo "INACTIVO";  
}
echo "</td>";
echo "<td align = 'center'>";

?>

<a onclick = "cargar_edicion('<?php echo $id;?>','<?php echo $identidad;?>','<?php echo $cod;?>','<?php echo $nom;?>','<?php echo $nombre_asociacion;?>','<?php echo $telefono;?>','<?php echo $direccion;?>','<?php echo $foto;?>','<?php echo $estado_civil;?>','<?php echo $sexo;?>','<?php echo $zona_venta;?>','<?php echo $tipo_id;?>','<?php echo $geocodigo;?>','<?php echo $asociacion;?>','<?php echo $seccional;?>','<?php echo $bolsas;?>')" data-toggle='modal' href='#editar' class='btn btn-primary  fa fa-edit' ></a>

<a href="./mercadeo_gestion_vendedores_info.php?v=<?php echo $id; ?>" target = '_blanck' class="btn btn-info fa fa-eye"  ></a>

<a href="./mercadeo_print_carnet.php?v=<?php echo $id; ?>" target = '_blanck' class="btn btn-success fa fa-print"  ></a>

<?php

echo "</td>";
echo "</tr>";
}
?>

  </tbody>
</table>
</div>

<div class="card-footer" align="center">
<a href="screen_mto_vendedores_print.php" target="_blanck"  class="btn btn-success">IMPRIMIR REPORTE</a>
</div>

</div>

<br>
<br>














<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->
<!-- $$$$$$$$$$$$$$$$$$$$ MODAL DE NUEVO REG $$$$$$$$$$$$$$$$$$$$$$ -->

<input class="form-control" type='file' id="foto" name="foto" style="visibility: hidden" onchange="readURL(this);" >

<div class="modal" id="nuevo" >
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header alert-info">
        <h4 align="center">Nuevo Vendedor</h4>
        </div>
      <div class="container"></div>
        <div class="modal-body">





<div class="row">

<div class="col col-sm-3" align="center" style="vertical-align: center">
<img width="150px" height="150px" onclick="document.getElementById('foto').click()"  id="vista_previa" src="./imagenes/default_foto.png" alt="" >
</div>

<div class = "col" >
    
<div style="width: 100%" class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Identidad</span></div>
  <input type="text" onblur="validar_identidad(this.value)" name="nuevo_id" id="nuevo_id" class="form-control">
</div>

<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Nombre</span></div>
<input style="text-transform:uppercase" class="form-control" type="text" name="nuevo_nombre" id="nuevo_nombre" readonly="true">    
</div>


<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Asociacion</span></div>
<select class="form-control" name="nueva_asociacion" id="nueva_asociacion" onchange=" funcion_seleccion_nuevo_asociacion(this.value);" >
<option value="no">Seleccione una opcion</option>
  <?php
  $select_a = mysqli_query($conn,"SELECT * from asociaciones_vendedores");
  while ($reg_select_a = mysqli_fetch_array($select_a)) {
  echo "<option value = '".$reg_select_a['codigo_asociacion']."'>".$reg_select_a['nombre_asociacion']."</option>";
  }
  ?>
</select>
</div>


<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Seccional</span></div>
<select class="form-control" name="nueva_seccional" id="nueva_seccional" onchange=" funcion_seleccion_nuevo_seccional();" >
<option value="no">Seleccione una opcion</option>
  <?php
  $select_a = mysqli_query($conn,"SELECT * from asociaciones_seccionales");
  while ($reg_select_a = mysqli_fetch_array($select_a)) {
  echo "<option style = 'display:none' id = '".$reg_select_a['codigo_asociacion']."' name = '".$reg_select_a['codigo_asociacion']."'  value = '".$reg_select_a['codigo_seccional']."'>".$reg_select_a['codigo_seccional']." - ".$reg_select_a['zona']."</option>";
  }
  ?>
</select>
</div>


  </div>
</div>


<br>


<div class="row">
<div class="col">

<div style="width: 100%; " class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Codigo</span></div>
<input class="form-control" type="text" name="nuevo_codigo" id="nuevo_codigo" readonly="true">    
</div>

</div>
<div class="col">

<div style="width: 100%; " class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Bolsas</span></div>
<input class="form-control" type="text" name="nuevo_bolsa" id="nuevo_bolsa" >    
</div>

</div>
</div>

<div class = "row">
<div class = "col">

<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Genero</span></div>
<select class="form-control" id="nuevo_sexo" name="nuevo_sexo" >
<option value="M"> MASCULINO </option>
<option value="F"> FEMENINO </option>
</select>
</div>

</div>

<div class="col">

<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Estado Civil</span></div>
<select class="form-control" id="nuevo_estado_civil" name="nuevo_estado_civil" >
  <option value="S"> SOLTERO </option>
  <option value="C"> CASADO </option>
  <option value="V"> VIUDO </option>
  <option value="D"> DIVORCIADO </option>
</select>
</div>

  </div>
</div>


<div class="row">
<div class="col">

<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Telefono</span></div>
<input class="form-control" type="text" name="nuevo_telefono" id="nuevo_telefono">      
</div>

</div>
<div class="col">

<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Estado</span></div>
<select class="form-control" name="nuevo_estado" disabled>
<option value="1" selected="true">ACTIVO</option>
<option value="2">INACTIVO</option>
</select>    
</div>
</div>
</div>


<div class="row">
<div class="col">

<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Departamento</span></div>
<select required class="form-control" id="nuevo_departamento"  name="nuevo_departamento" onchange=" funcion_seleccion_nuevo(this.value);" >
<option value="ninguno">Seleccione una opcion</option>
<?php
$departamentos = mysqli_query($conn,"SELECT * FROM fvp_dptos ORDER BY id ASC ");

while ($dpto = mysqli_fetch_array($departamentos)) {
echo '<option value="'.$dpto['id'].'">'. $dpto['descripcion'].'</option>';
}

?>
</select>
</div>

</div> 


<div class="col">

<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Municipio</span></div>
<select required name="nuevo_municipio" id="nuevo_municipio"  class="form-control">
<option value="ninguno">Seleccione una opcion</option>
<?php 
$municipios = mysqli_query($conn,"SELECT * FROM fvp_geocodigos ORDER BY cod_muni ASC");

while ($municipio = mysqli_fetch_array($municipios)) {
echo "<option style = 'display:none' id = '".$municipio['dpto_id']."' name = '".$municipio['cod_muni']."' value = '".$municipio['cod_muni']."'>".$municipio['municipio']."</option>";
}
?>
</select>       
</div>

</div>
</div>


<div class="row">
<div class="col">

<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >direccion</span></div>
<textarea  maxlength="100" class="form form-control" name="nueva_direccion" id="nueva_direccion"></textarea>  
</div>

</div>
</div>

<div class="row">
<div class="col">

<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Zona de Venta</span></div>
<textarea  maxlength="100" class="form form-control" name="nueva_zona_venta" id="nueva_zona_venta"></textarea>  
</div>

</div>
</div>





        </div>
        <div class="modal-footer">
        <button style="display: none" type="submit" name="guardar_nuevo" id="guardar_nuevo" ></button>
        <span  onclick="validar_nuevo_vendedor()" class="btn btn-primary">Guardar</span>
        <span  class="btn btn-danger" data-dismiss="modal" >Cancelar</span>
        </div>
      </div>
    </div>
</div>

<!-- $$$$$$$$$$$$$$$$$$$$ MODAL DE NUEVO REG $$$$$$$$$$$$$$$$$$$$$$ -->
<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->














<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->
<!-- $$$$$$$$$$$$$$$$$$$$ MODAL DE EDICION $$$$$$$$$$$$$$$$$$$$$$ -->




<input class="form-control" style="visibility: hidden" type='file' id="foto_edicion" name="foto_edicion" onchange="readURL2(this);" >

<input class="form-control" type="hidden" name="id_edicion" id="id_edicion">

<div class="modal" id="editar" >
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header alert-info">
        <h4 align="center">Edicion Vendedor</h4>
        </div>
      <div class="container"></div>
        <div class="modal-body">





<div class="row">


<div class="col col-sm-3" align="center" style="vertical-align: center">
<img  width="150px" height="150px" onclick="document.getElementById('foto_edicion').click()"  id="vista_previa_edicion" name="vista_previa_edicion"  alt="" >
</div>


<div class="col">
    

<div style="width: 100%" class="input-group">
<div class="input-group-prepend">  <span class="input-group-text" style="min-width:150px" >Identidad</span></div>
<input type="text"  name="edicion_id" id="edicion_id" class="form-control" readonly>
</div>


<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend">  <span class="input-group-text" style="min-width:150px" >Nombre</span></div>
<input style="text-transform:uppercase" class="form-control" type="text" name="edicion_nombre" id="edicion_nombre" readonly="true">
</div>


<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Asociacion</span></div>
<select class="form-control" name="edicion_asociacion" id="edicion_asociacion" onchange=" funcion_seleccion_edicion_asociacion(this.value);" >
<option value="no">Seleccione una opcion</option>
  <?php
  $select_a = mysqli_query($conn,"SELECT * from asociaciones_vendedores");
  while ($reg_select_a = mysqli_fetch_array($select_a)) {
  echo "<option value = '".$reg_select_a['codigo_asociacion']."'>".$reg_select_a['nombre_asociacion']."</option>";
  }
  ?>
</select>
</div>


<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Seccional</span></div>
<select class="form-control" name="edicion_seccional" id="edicion_seccional" onchange=" funcion_seleccion_edicion_seccional();" >
<option value="no">Seleccione una opcion</option>
  <?php
  $select_a = mysqli_query($conn,"SELECT * from asociaciones_seccionales");
  while ($reg_select_a = mysqli_fetch_array($select_a)) {
  echo "<option style = 'display:none' id = '".$reg_select_a['codigo_asociacion']."' name = '".$reg_select_a['codigo_asociacion']."'  value = '".$reg_select_a['codigo_seccional']."'>".$reg_select_a['codigo_seccional']." - ".$reg_select_a['zona']."</option>";
  }
  ?>
</select>
</div>


  </div>
</div>


<br>


<div class="row">
<div class="col">

<div style="width: 100%; " class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Codigo</span></div>
<input class="form-control" type="text" name="edicion_codigo" id="edicion_codigo" readonly="true">    
</div>

</div>
<div class="col">

<div style="width: 100%; " class="input-group">
<div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px" >Bolsas</span></div>
<input class="form-control" type="text" name="edicion_bolsa" id="edicion_bolsa" >    
</div>

</div>
</div>

<div class="row">
<div class="col">

<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend">  <span class="input-group-text" style="min-width:150px" >Genero</span></div>
<select class="form-control" id="edicion_sexo" name="edicion_sexo" >
  <option value="M"> MASCULINO </option>
  <option value="F"> FEMENINO </option>
</select>
</div>

</div>
<div class="col">

<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend">  <span class="input-group-text" style="min-width:150px" >Estado Civil</span></div>
<select class="form-control" id="edicion_estado_civil" name="edicion_estado_civil" >
  <option value="S"> SOLTERO </option>
  <option value="C"> CASADO </option>
  <option value="V"> VIUDO </option>
  <option value="D"> DIVORCIADO </option>
</select>    
</div>

  </div>
</div>


<div class="row">
<div class="col">

<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend">  <span class="input-group-text" style="min-width:150px" >Telefono</span></div>
<input class="form-control" type="text" name="edicion_telefono" id="edicion_telefono">      
</div>

</div>

<div class="col">

<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend">  <span class="input-group-text" style="min-width:150px" >Estado</span></div>
<select class="form-control" name="edicion_estado" >
<option value="1">ACTIVO</option>
<option value="2">INACTIVO</option>
</select>    
</div>

</div>
</div>



<div class="row">
<div class="col">

<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend">  <span class="input-group-text" style="min-width:150px" >Departamento</span></div>
<select required class="form-control" id="edicion_departamento"  name="edicion_departamento" onchange=" funcion_seleccion_edicion(this.value);" >
<option value="ninguno" >Seleccione una opcion</option>
<?php
$departamentos = mysqli_query($conn,"SELECT * FROM fvp_dptos ORDER BY id ASC ");

while ($dpto = mysqli_fetch_array($departamentos)) {
echo '<option value="'.$dpto['id'].'">'.$dpto['descripcion'].'</option>';
}

?>
</select>
</div>

</div> 


<div class="col">

<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend">  <span class="input-group-text" style="min-width:150px" >Municipio</span></div>
<select required name="edicion_municipio" id="edicion_municipio"  class="form-control" >
<option value="ninguno" >Seleccione una opcion</option>
<?php 
$municipios = mysqli_query($conn,"SELECT * FROM fvp_geocodigos ORDER BY cod_muni ASC");

while ($municipio = mysqli_fetch_array($municipios)) {
echo "<option style = 'display:none' id = '".$municipio['dpto_id']."' value = '".$municipio['cod_muni']."'>".$municipio['municipio']."</option>";
}
?>
</select>       
</div>
</div>
</div>


<div class="row">
<div class="col">

<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend">  <span class="input-group-text" style="min-width:150px" >Direccion</span></div>
<textarea  maxlength="50" class="form form-control" name="edicion_direccion" id="edicion_direccion"></textarea>  
</div>

</div>
</div>

<div class="row">
<div class="col">

<div style="width: 100%; margin-top: 5px" class="input-group">
<div class="input-group-prepend">  <span class="input-group-text" style="min-width:150px" >Zona De Venta</span></div>
<textarea  maxlength="50" class="form form-control" name="edicion_zona_venta" id="edicion_zona_venta"></textarea>  
</div>

</div>
</div>




        </div>
         <div class="modal-footer">

        <button style="display: none" type="submit" name="guardar_edicion" id="guardar_edicion" ></button>
        <span  onclick="validar_edicion_vendedor()" class="btn btn-primary">Actualizar</span>
         <span  class="btn btn-danger" data-dismiss="modal" >Cancelar</span>
         </div>
      </div>
    </div>
</div>

<!-- $$$$$$$$$$$$$$$$$$$$ MODAL DE EDICION $$$$$$$$$$$$$$$$$$$$$$ -->
<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->



</form>