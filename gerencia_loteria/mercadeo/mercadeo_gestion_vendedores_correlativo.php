<?php 

require("../../conexion.php");


$tipo  		= $_GET['tipo'];

if ($tipo == "n") {



    $asociacion = $_GET['a'];
    $seccional  = $_GET['s'];
    $consulta_max_contador = mysqli_query($conn, "SELECT MAX(codigo) + 1 as correlativo  FROM vendedores WHERE asociacion = '$asociacion' AND seccional = '$seccional' ");
    $ob_correlativo = mysqli_fetch_object($consulta_max_contador);
    $correlativo = $ob_correlativo->correlativo;
    
    if ($correlativo == '') {
    $correlativo = 1;	
    }
    
    $concat = $asociacion."-".$seccional."-".$correlativo;
    
    

?>

<script type="text/javascript">
document.getElementById('nuevo_codigo').value = '<?php echo $concat; ?>';
</script>

<?php	


}else{



$seccional  = $_GET['s'];

$consulta_max_contador = mysqli_query($conn, "SELECT MAX(codigo) + 1 as correlativo  FROM vendedores WHERE  CONCAT(asociacion, '-', seccional) =  '$seccional' ");
$ob_correlativo = mysqli_fetch_object($consulta_max_contador);
$correlativo = $ob_correlativo->correlativo;

if ($correlativo == '') {
$correlativo = 1;	
}
    
$concat = $seccional."-".$correlativo;

?>

<script type="text/javascript">
document.getElementById('edicion_codigo').value = '<?php echo $concat; ?>';
</script>

<?php	

}

