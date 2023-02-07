<?php
$id_sorteo = $_SESSION['historico_mayor']; 

$info_mayor = mysql_query("SELECT * FROM sorteos_mayores where id = '$id_sorteo' ");
$value_mayor = mysql_fetch_object($info_mayor);
$cantidad_billetes = $value_mayor->cantidad_numeros;
$registro_inicial = $value_mayor->desde_registro;
$patron_salto = $value_mayor->patron_salto;
$sorteo = $value_mayor->no_sorteo_may;
$fecha = $value_mayor->fecha_sorteo;


$masc = strlen($cantidad_billetes);
$masc_rec = strlen($registro_inicial);

$parametros_mayor = mysql_query("SELECT * FROM sorteos_mayores_produccion where id_sorteo = '$id_sorteo' ");


if (isset($_POST['guardar_cambios'])) {

$id_sorteo = $_SESSION['historico_mayor']; 
$reg_inicial = $_POST['registro_inicial'];

if (mysql_query(" UPDATE sorteos_mayores SET desde_registro = '$reg_inicial' WHERE id = '$id_sorteo' ")=== TRUE) {
$bandera = true;
$i = 1;
while (isset($_POST['salto_oculto'.$i])) {
$salto = $_POST['salto'.$i];
$salto_o = $_POST['salto_oculto'.$i];
if (mysql_query("UPDATE sorteos_mayores_produccion SET salto = '$salto' WHERE id = '$salto_o' ") === false) {
echo mysql_error();
$bandera = false;
}
$i++;
}

}else{
echo mysql_error();	
}

if ($bandera == true) {


?>
<script type="text/javascript">
  swal({ 
  title: "",
   text: "Cambios Realizados Correctamente",
    type: "success" 
  },
  function(){
    window.location.href = './produccion_historico_mayor.php';
});
</script>
<?php

}

}

if (isset($_POST['eliminar'])) {
$id_sorteo = $_SESSION['historico_mayor']; 

mysql_query("DELETE FROM sorteos_mayores_produccion WHERE id_sorteo = '$id_sorteo' ");
mysql_query("DELETE FROM sorteos_mayores_registros WHERE id_sorteo = '$id_sorteo' ");

mysql_query("UPDATE `sorteos_mayores` SET patron_salto = NULL , desde_registro = NULL , `estado_sorteo`= 'PENDIENTE PRODUCCION' WHERE id = '$id_sorteo' ");

?>
<script type="text/javascript">
  swal({ 
  title: "",
   text: "Registro Eliminado correctamente",
    type: "success" 
  },
  function(){
    window.location.href = './produccion_sorteos.php';
});
</script>
<?php

}


?>