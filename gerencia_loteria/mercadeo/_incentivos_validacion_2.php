<?php 
$conn = mysqli_connect('192.168.15.248:3306', 'SVR_APP', 'softlotpani**', 'pani') or die('No se pudo conectar: ' . mysqli_error());

$sorteo=$_GET['s'];

//echo "SELECT count(*) conteo_total FROM sorteos_menores_incentivos where id_sorteo=$sorteo";
$query_conteo_total=mysqli_query($conn,"SELECT count(*) conteo_total FROM sorteos_menores_incentivos where id_sorteo=$sorteo");
$obj_conteo_total=mysqli_fetch_object($query_conteo_total);
$conteo_total=$obj_conteo_total->conteo_total;

//echo "SELECT count(*) conteo_premiados FROM sorteos_menores_incentivos where id_sorteo=$sorteo and id_vendedor is not null";
$query_conteo_premiados=mysqli_query($conn,"SELECT count(*) conteo_premiados FROM sorteos_menores_incentivos where id_sorteo=$sorteo and id_vendedor is not null");
$obj_conteo_premiados=mysqli_fetch_object($query_conteo_premiados);
$conteo_premiados=$obj_conteo_premiados->conteo_premiados;

 
//echo "<br>".$conteo_total."---".$conteo_premiados;
if ($conteo_premiados == $conteo_total) {
echo '<a href="./menor_acta_incentivos.php?s=<?php echo $ultimo_sorteo;?>" class="btn btn-danger" id="no_print" target="_blank">IMPRIMIR ACTA</a>';
}




 ?>
 <script type="text/javascript">

 $(".div_wait").fadeOut("fast");

</script>