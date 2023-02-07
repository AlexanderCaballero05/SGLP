<?php

if (isset($_POST['guardar'])) {

$id_sorteo = $_POST['id_sorteo_oculto'];

$i = 0;
$j = 1;
$bandera = 1;

while (isset($_POST['id_o'][$i])) {

if (isset($_POST['id_o'][$i])) {

$id_revisor = $_POST['id_o'][$i];
$numero = $j;
$billete_inicial = $_POST['desde'][$i];
$billete_final = $_POST['hasta'][$i];
//echo $id_sorteo." - ".$id_revisor." - ".$numero." - ".$billete_inicial." - ".$billete_final."<br>";

if (mysqli_query($conn,"INSERT INTO cc_revisores_sorteos_mayores (id_sorteo,id_revisor,numero,billete_inicial,billete_final) VALUES ('$id_sorteo','$id_revisor','$numero','$billete_inicial','$billete_final') ") === false) {
echo mysqli_error($conn);
$bandera = 0;
}

$j++;
}
$i++;
}


if ($bandera == 1 ) {
if (mysqli_query($conn,"UPDATE sorteos_mayores SET control_calidad = 'SI' WHERE id =  '$id_sorteo' ") === TRUE) {
?>
<script type="text/javascript">
 swal({
title: "",
  text: "Distribucion para revision guardada correctamente",
  type: "success" 
})
.then(() => {
    window.location.href = './screen_cc_sorteos_pendientes_mayor.php';
});
</script>
<?php
}
}

}

?>