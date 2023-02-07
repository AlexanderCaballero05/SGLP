<?php

$sorteos = mysqli_query($conn,"SELECT * FROM sorteos_menores WHERE estado_sorteo = 'PENDIENTE DISTRIBUCION' ORDER BY no_sorteo_men DESC ");

$empresas = mysqli_query($conn,"SELECT * FROM empresas WHERE estado = 'ACTIVO' AND id = 3 ");




///////////////////////// Si se selecciono un sorteo ////////////////////////////////////////////////////

if (isset($_POST['seleccionar'])) {

$id_sorteo = $_POST['sorteo'];

$_SESSION['id_sorteo'] =  $id_sorteo;


$info_sorteo = mysqli_query($conn,"SELECT *  FROM sorteos_menores WHERE id = '$id_sorteo' limit 1");
$value = mysqli_fetch_object($info_sorteo);
$sorteo = $value->no_sorteo_men;
$fecha_sorteo = $value->fecha_sorteo;
$num_series = $value->series;

////////////////////////////
// TOTAL ASIGNADO COMO BOLSA 
$bolsas_no_disponibles = mysqli_query($conn,"SELECT * FROM menor_seccionales_bolsas WHERE id_sorteo = '$id_sorteo' ");

$i = 0;
$bolsas_seccionales = 0;
while ($no_disponibles = mysqli_fetch_array($bolsas_no_disponibles ) ) {
$bolsas_seccionales = $bolsas_seccionales + $no_disponibles['cantidad'];
}
// FIN TOTAL ASIGNADO BOLSA
////////////////////////////

////////////////////////////
// TOTAL ASIGNADO NUMEROS 
$bolsas_numeros = 0;
$bolsas_no_disponibles_numeros = mysqli_query($conn,"SELECT DISTINCT(serie_inicial), cantidad FROM menor_seccionales_numeros WHERE id_sorteo = '$id_sorteo' AND origen = 'bolsas'  ");

while ($numeros_no_disponibles = mysqli_fetch_array($bolsas_no_disponibles_numeros)) {
$bolsas_numeros = $bolsas_numeros + $numeros_no_disponibles['cantidad'];
}
// FIN TOTAL ASIGNADO NUMEROS 
////////////////////////////


$bolsas_disponibles= $num_series - $bolsas_seccionales - $bolsas_numeros;

}




////////////////////////////////////////
//////// CODIGO DE GUARDADO ////////////
////////////////////////////////////////

if (isset($_POST['guardar_distribucion'])) {

$id_sorteo = $_SESSION['id_sorteo'];
$cantidad_total = 0;
$i = 0;

$info_sorteo = mysqli_query($conn,"SELECT *  FROM sorteos_menores WHERE id = '$id_sorteo' limit 1");
$value = mysqli_fetch_object($info_sorteo);
$sorteo = $value->no_sorteo_men;
$fecha_sorteo = $value->fecha_sorteo;
$num_series = $value->series;
$bandera = 0;

$i = 0;
while (isset($_POST['id_empresa'.$i])) {

if ($_POST['id_empresa'.$i] != '' ) {

$id_empresa = $_POST['id_empresa'.$i];
$cantidad = $_POST['cantidad_seccional'.$i];

if ($cantidad != ''){

$cantidad_total = $cantidad_total + $_POST['cantidad_seccional'.$i];
$serie_inicial = $_POST['serie_inicial'.$i];
$serie_final = $_POST['serie_final'.$i];
$tipo_venta = $_POST['tipo_venta'.$i];

if ($tipo_venta == 'bolsas') {

if (mysqli_query($conn," INSERT INTO  menor_seccionales_bolsas (id_sorteo,cantidad,serie_inicial,serie_final,id_empresa) VALUES ('$id_sorteo',$cantidad,$serie_inicial,$serie_final,$id_empresa)  ") === TRUE) {
}else{
	echo mysqli_error();
$bandera = 1;
}

}else{

$n = 0;
while ($n < 100) {

if (mysqli_query($conn," INSERT INTO  menor_seccionales_numeros (id_sorteo, numero, serie_inicial, serie_final, cantidad, id_empresa,origen) VALUES ('$id_sorteo', $n, $serie_inicial, $serie_final, $cantidad ,$id_empresa, 'bolsas')  ") === TRUE) {
}else{
	echo mysqli_error();
$bandera = 1;
}

$n++;
}

}



}

}

$i++;
}


if ($bandera == 0) {

?>
<script type="text/javascript">
  swal({ 
  title: "",
   text: "Distribucion Realizada Correctamente",
    type: "success" 
})
.then(() => {
window.location.href = './fvp_distribucion_pedidos_menor_bolsas.php';
});

</script>
<?php

}else{

?>
<script type="text/javascript">
  swal({ 
  title: "",
   text: "Error inesperado, por favor vuelva a intentarlo.",
    type: "error" 
})
.then(() => {
window.location.href = './fvp_distribucion_pedidos_menor_bolsas.php';
});
</script>
<?php

}

}
////////////////////// FIN DE GUARDADO /////////////////////
////////////////////////////////////////////////////////////



////////////////////////////////////////////////////////////
///////// INICIO DE ELIMINADO DISTRIBUCION BOLSAS //////////
if (isset($_POST['eliminar_distribucion'])) {
$id = $_POST['eliminar_distribucion'];
if (mysqli_query($conn,"DELETE FROM menor_seccionales_bolsas WHERE id = '$id' ") === TRUE) {

?>
<script type="text/javascript">
  swal({ 
  title: "",
   text: "Registro Eliminado Correctamente",
    type: "success" 
})
.then(() => {
window.location.href = './fvp_distribucion_pedidos_menor_bolsas.php';
});
</script>
<?php

}

}
///////////  FIN ELIMINADO DISTRIBUCION BOLSAS ///////////// 
////////////////////////////////////////////////////////////





////////////////////////////////////////////////////////////
///////// INICIO DE ELIMINADO DISTRIBUCION NUMEROS //////////
if (isset($_POST['eliminar_distribucion_numeros'])) {
$parametros = $_POST['eliminar_distribucion_numeros'];

$v_parametros = explode('/', $parametros);
$serie_inicial = $v_parametros[0];
$serie_final = $v_parametros[1];

/*
if (mysqli_query($conn,"DELETE FROM menor_seccionales_numeros WHERE serie_inicial = '$serie_inicial' AND serie_final = '$serie_final' ") === TRUE) {

?>
<script type="text/javascript">
  swal({ 
  title: "",
   text: "Registro Eliminado Correctamente",
    type: "success" 
  },
  function(){
    window.location.href = './fvp_distribucion_pedidos_menor_bolsas.php';
});
</script>
<?php

}
*/

}
///////////  FIN ELIMINADO DISTRIBUCION NUMEROS ///////////// 
////////////////////////////////////////////////////////////






?>
