<?php

if (isset($_POST['guardar'])) {

$id_sorteo = $_POST['id_sorteo_oculto'];
$num_filas = $_POST['filas_guardadas'];

$consulta_maxima_lista = mysqli_query($conn,"SELECT MAX(numero) as numero FROM cc_revisores_sorteos_menores WHERE id_sorteo = '$id_sorteo' ");

$ob_maxima_lista = mysqli_fetch_object($consulta_maxima_lista);
$maxima_lista = $ob_maxima_lista->numero;


$i = 0;
$j = $maxima_lista + 1;
$bandera = 1;
while ($i <= $num_filas) {

if (isset($_POST['id_o'.$i])) {

$id_revisor = $_POST['id_o'.$i];
$numero = $j;
$grupo = $_POST['grupo'.$i];
$billete_inicial = $_POST['desde'.$i];
$billete_final = $_POST['hasta'.$i];

if (mysqli_query($conn,"INSERT INTO cc_revisores_sorteos_menores_extras(id_sorteo,id_revisor,numero,serie_inicial,serie_final,grupo) VALUES ('$id_sorteo','$id_revisor','$numero','$billete_inicial','$billete_final','$grupo') ") === false) {
echo mysqli_error($conn);
$bandera = 0;
}

$j++;
}
$i++;
}

if ($bandera == 1 ) {
if (mysqli_query($conn,"UPDATE sorteos_menores SET control_calidad_extra = 'SI' WHERE id =  '$id_sorteo' ") === TRUE) {
?>
<script type="text/javascript">
  swal({ 
  title: "",
   text: "Distribucion para revision guardada correctamente",
    type: "success" 
  },
  function(){
    window.location.href = './cc_sorteos_pendientes.php';
});
</script>
<?php
}
}

}

?>
