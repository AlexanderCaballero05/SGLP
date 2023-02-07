<?php
require('../../template/header.php');

$revisores = mysql_query("SELECT * FROM cc_revisores  ");

if ($revisores===false) {
echo mysql_error();
}

?>

<form method="POST">
<div class="alert alert-info"  align="CENTER"> 
Mantenimiento de Revisores
</div>

<div style="width: 100%" align="center">
<div class="well" style="width:70%">
  <table class="table table-bordered">
    <tr>
      <th width="15%">Cod. Empleado </th>
      <th width="85%">Nombre Completo </th>
    </tr>

<tr>
<td><input type="text" name="nuevo_codigo" style="width: 100%"> </input></td>
<td><input type="text" name="nuevo_nombre" style="width: 100%"> </input></td>
</tr>

  </table>


<p align="center">
  <button type="submit" name="guardar" class="btn btn-info">Guardar</button>
</p>

</div>
</div>


<div style="width: 100%" align="center">
<div class="well" style="width:70%">
  <table class="table table-bordered">
    <tr>
      <th width="10%">Cod. Empleado </th>
      <th width="70%">Nombre</th>
      <th width="10%">Estado </th>      
      <th width="10%">Accion </th>      
    </tr>
<?php
$i = 0;
while ($revisor = mysql_fetch_array($revisores)) {
  echo "<input type = 'hidden' name = 'id_o".$i."' value = '".$revisor['id']."' >";
echo "<tr><td><input style = 'width: 100%' type = 'text' name = 'descripcion".$i."' value = '".$revisor['cod_empleado']."' ></td>";
echo "<td><input style = 'width: 100%' type = 'text' name = 'terminacion".$i."' value = '".$revisor['nombre_revisor']."' ></td>";
echo "<td>".$revisor['estado']."</td>";


if ($revisor['estado'] == 'ACTIVO') {

echo "<td align = 'center'><button class = 'btn btn-danger' name = 'eliminar_premio' value = '".$revisor['id']."' >!</button></td></tr>";

}else{

echo "<td align = 'center'><button class = 'btn btn-primary' name = 'activar_revisor' value = '".$revisor['id']."' >!</button></td></tr>";

}

$i++;
}
?>
  </table>


<p align="center">
  <button type="submit" name="actualizar" class="btn btn-info">Actualizar</button>
</p>

</div>
</div>

</form>

<?php


if (isset($_POST['activar_revisor'])) {
$id = $_POST['activar_revisor'];

if (mysql_query(" UPDATE  cc_revisores SET estado = 'ACTIVO' WHERE id = '$id' ") === TRUE ) {

?>
<script type="text/javascript">
  swal({ 
  title: "",
   text: "Revisor Activado Correctamente",
    type: "success" 
  },
  function(){
    window.location.href = './cc_mto_revisores.php';
});
</script>
<?php

}else{

?>
<script type="text/javascript">
  swal({ 
  title: "Revisor No Activado",
   text: " Por favor vuelva a intentarlo",
    type: "error" 
  },
  function(){
    window.location.href = './cc_mto_revisores.php';
});
</script>
<?php

}

}


if (isset($_POST['eliminar_premio'])) {
$id = $_POST['eliminar_premio'];

if (mysql_query(" UPDATE  cc_revisores SET estado = 'INACTIVO' WHERE id = '$id' ") === TRUE ) {

?>
<script type="text/javascript">
  swal({ 
  title: "",
   text: "Revisor Inactivado Correctamente",
    type: "success" 
  },
  function(){
    window.location.href = './cc_mto_revisores.php';
});
</script>
<?php

}else{

?>
<script type="text/javascript">
  swal({ 
  title: "Revisor No Inactivado",
   text: " Por favor vuelva a intentarlo",
    type: "error" 
  },
  function(){
    window.location.href = './cc_mto_revisores.php';
});
</script>
<?php

}

}



if (isset($_POST['actualizar'])) {
$i = 0;

while (isset($_POST['id_o'.$i])) {
$id = $_POST['id_o'.$i];
$cod = $_POST['descripcion'.$i];
$nombre = $_POST['terminacion'.$i];

mysql_query("UPDATE cc_revisores SET cod_empleado = '$cod', nombre_revisor = '$nombre'  WHERE id = '$id' ");

$i++;
}

?>
<script type="text/javascript">
  swal({ 
  title: "",
   text: "Revisores Actualizados correctamente",
    type: "success" 
  },
  function(){
    window.location.href = './cc_mto_revisores.php';
});
</script>
<?php

}


if (isset($_POST['guardar'])) {
$nuevo_cod = $_POST['nuevo_codigo'];
$nuevo_nom = $_POST['nuevo_nombre'];

if ($nuevo_nom == '') {

}else{

if (mysql_query("INSERT INTO cc_revisores (cod_empleado, nombre_revisor,estado) VALUES ('$nuevo_cod','$nuevo_nom','ACTIVO' ) ") === true) {

?>
<script type="text/javascript">
  swal({ 
  title: "",
   text: "Revisor ingresado correctamente",
    type: "success" 
  },
  function(){
    window.location.href = './cc_mto_revisores.php';
});
</script>
<?php

}else{
  echo mysql_error();
}

}

}

?>
