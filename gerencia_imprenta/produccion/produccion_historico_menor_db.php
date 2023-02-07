<?php

$id_sorteo = $_SESSION['historico_menor'];
$_SESSION['id_sorteo'] = $id_sorteo;


$result = mysql_query("SELECT * FROM sorteos_menores WHERE id = '$id_sorteo'");
 
if ($result != null){

while ($row = mysql_fetch_array($result)) {
$sorteo = $row['no_sorteo_men'] ;
$fecha_sorteo = $row['fecha_sorteo'] ;
$series = $row['series'] -1;
$desde_registro = $row['desde_registro'];
$descripcion = $row['descripcion_sorteo_men'];
}
$masc = strlen($series);
}


$saltos = mysql_query("SELECT * FROM sorteos_menores_produccion WHERE id_sorteo = '$sorteo' ");
if ($saltos === false) {
echo mysql_error();
}
$i = 1;
while ($reg_saltos = mysql_fetch_array($saltos)) {
$v_saltos[$i] = $reg_saltos['salto']; 
$v_decena[$i] = $reg_saltos['decena']; 
$i++;
}


$max_extra  = mysql_query("SELECT MAX(cantidad) as maximo FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo'");
if (mysql_num_rows($max_extra) == 0) {
$cantidad_extra_mayor =  0;	
}else{
$ob_extra = mysql_fetch_object($max_extra); 
$cantidad_extra_mayor =  $ob_extra->maximo;	
}


$result2 = mysql_query("SELECT * FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo' ORDER BY cantidad DESC, numero ASC  ");


 
if (isset($_POST['eliminar'])) {

$id_sorteo =  $_SESSION['id_sorteo']; 

mysql_query("UPDATE `sorteos_menores` SET`estado_sorteo`= 'PENDIENTE PRODUCCION' WHERE id = '$id_sorteo' ");
mysql_query("DELETE FROM sorteos_menores_produccion WHERE id_sorteo = '$id_sorteo' ");

?>
<script type="text/javascript">
  swal({ 
  title: "",
   text: "Registro Eliminado correctamente",
    type: "success" 
  },
  function(){
    window.location.href = './produccion_sorteos_menor.php';
});
</script>
<?php

}

?>
