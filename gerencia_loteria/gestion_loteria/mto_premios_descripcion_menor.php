<?php
require('../../template/header.php');

$premios = mysql_query("SELECT * FROM premios_menores WHERE id != 1 AND id != 3 ");
$ultimo_premio = mysql_query("SELECT MAX(id) as maximo FROM premios_menores");
$ob_ultimo = mysql_fetch_object($ultimo_premio);
$ultimo = $ob_ultimo->maximo;

if ($premios===false) {
echo mysql_error();
}

?>

<form method="POST">


<div class="alert alert-info"  align="CENTER"> 
Mantenimiento de Descripciones de Premios de Loteria Menor
</div>

<div style="width: 100%" align="center">
<div class="well" style="width:70%">


  <table class="table table-bordered">
    <tr>
      <th width="100%">Descripcion </th>
    </tr>

<tr>
<td><input type="text" name="nueva_descripcion" style="width: 100%"> </input></td>
</tr>

  </table>


<p align="center">
  <button type="submit" name="guardar" class="btn btn-info">Guardar</button>
</p>

</div>
</div>


<div style="width: 100%" align="center">
<div class="well" style="width:70%">
<input type="hidden" name="ultimo_id" value="<?php echo $ultimo; ?>">

  <table class="table table-bordered">
    <tr>
      <th width="90%">Descripcion </th>
      <th width="10%">Accion </th>      
    </tr>
<?php
$i = 0;
while ($premio = mysql_fetch_array($premios)) {
  echo "<input type = 'hidden' name = 'id_o".$i."' value = '".$premio['id']."' >";
echo "<tr><td><input style = 'width: 100%' type = 'text' name = 'descripcion".$i."' value = '".$premio['descripcion_premios']."' ></td>";
echo "<td align = 'center'><button class = 'btn btn-danger' name = 'eliminar_premio' value = '".$premio['id']."' >X</button></td></tr>";
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

if (isset($_POST['eliminar_premio'])) {
$id = $_POST['eliminar_premio'];

if (mysql_query(" DELETE FROM premios_menores WHERE id = '$id' ") === TRUE ) {

?>
<script type="text/javascript">
  swal({ 
  title: "",
   text: "Premio Eliminado Correctamente",
    type: "success" 
  },
  function(){
    window.location.href = './mto_premios_descripcion.php';
});
</script>
<?php

}else{

?>
<script type="text/javascript">
  swal({ 
  title: "Premio No Eliminado",
   text: " La accion no puede realizarse ya que este premio ha sido asignado previamente",
    type: "error" 
  },
  function(){
    window.location.href = './mto_premios_descripcion_menor.php';
});
</script>
<?php

}

}



if (isset($_POST['actualizar'])) {
$i = 0;
$ultimo_id = $_POST['ultimo_id'];
while ($i <= $ultimo_id) {

if (isset($_POST['id_o'.$i])) {

$id = $_POST['id_o'.$i];
$desc = $_POST['descripcion'.$i];

if (mysql_query("UPDATE premios_menores SET descripcion_premios = '$desc'  WHERE id = '$id' ") ===false) {
echo mysql_error();
}

}

$i++;
}

?>
<script type="text/javascript">
  swal({ 
  title: "",
   text: "Premios Actualizados correctamente",
    type: "success" 
  },
  function(){
    window.location.href = './mto_premios_descripcion_menor.php';
});
</script>
<?php

}


if (isset($_POST['guardar'])) {
$nueva_desc = $_POST['nueva_descripcion'];
if ($nueva_desc == '') {

}else{

if (mysql_query("INSERT INTO premios_menores (descripcion_premios) VALUES ('$nueva_desc') ") === true) {

?>
<script type="text/javascript">
  swal({ 
  title: "",
   text: "Premio ingresado correctamente",
    type: "success" 
  },
  function(){
    window.location.href = './mto_premios_descripcion_menor.php';
});
</script>
<?php

}else{
  echo mysql_error();
}

}

}

?>
