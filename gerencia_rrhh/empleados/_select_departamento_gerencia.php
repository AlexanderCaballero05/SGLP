<?php 
require("../../conexion.php");
$gerencia=$_GET['gerencia'];
   
$query_identidad = mysqli_query($conn,"SELECT id, descripcion_unidad descripcion FROM organizacional_unidades WHERE gerenciaid='$gerencia'");

if (mysqli_num_rows($query_identidad)>0) 
{
	echo "<option value=''> Seleccione Uno ...</option>";
    while ($row_identidad=mysqli_fetch_array($query_identidad)) 
	{
		$id_departamento = $row_identidad['id'];
		$departamento    = $row_identidad['descripcion'];
		echo "<option value='".$id_departamento."'>  ".$departamento." </option>";
	}
		

}
?>
<script type="text/javascript">
 	$(".div_wait").fadeOut("fast");
</script>