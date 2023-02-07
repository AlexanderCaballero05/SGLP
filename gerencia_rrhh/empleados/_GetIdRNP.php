<?php 
require("../../conexion.php");
$identidad=$_GET['id'];
if (strlen($identidad)>=15)
 {

$identidad = preg_replace('([^A-Za-z0-9])', '', $identidad);	     				 
 
   
   $query_identidad=mysqli_query($conn,"SELECT nombre_completo FROM censo_2017 WHERE identidad='$identidad'");

		if (mysqli_num_rows($query_identidad)>0) 
		{
		    while ($row_identidad=mysqli_fetch_array($query_identidad)) 
			{
		   		$nombre_completo=$row_identidad['nombre_completo'];
			}
		//	echo $nombre_completo;

			?>
			<input type="hidden" id="consulta_nombre" value=" <?php echo $nombre_completo; ?> ">
			<script type="text/javascript">
				var consulta_nombre=document.getElementById('consulta_nombre').value;
				document.getElementById('txtnombre').value=consulta_nombre;
			</script>
			<?php 

		}
		else
		{
			?>
			<script type="text/javascript">
				swal("Esta persona actualmente no está en nuestra base de clientes");
			</script>
			<?php 
		}


 }
 else
 {
 	?>
			<script type="text/javascript">
				swal("Ingresa un numero de identidad valido");
			</script>
			<?php 

 	
 }


 

 ?>
<script type="text/javascript">
 	$(".div_wait").fadeOut("fast");
 </script>