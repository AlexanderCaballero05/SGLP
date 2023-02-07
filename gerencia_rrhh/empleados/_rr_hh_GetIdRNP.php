<?php 
require("../../conexion.php");
$identidad=$_GET['id'];
 
if (strlen($identidad)>=15)
 {
	$identidad = preg_replace('([^A-Za-z0-9])', '', $identidad);	    				 
    $query_identidad=mysqli_query($conn, "SELECT * FROM censo_2017 WHERE identidad='$identidad'");

		if (mysqli_num_rows($query_identidad)>0) 
		{			 
		    while ($row_identidad=mysqli_fetch_array($query_identidad)) 
			{
		   		$nombre_completo			=  $row_identidad['nombre_completo'];
		   		$fecha_nacimiento_array     =  $row_identidad['fecha_nacimiento_txt'];
		   		$v_fecha = explode("/", $fecha_nacimiento_array);
		   		$year    = $v_fecha[0];
				$month   = $v_fecha[1];
				$day     = $v_fecha[2];
		   		$true_date= $day."-".$month."-".$year;	
		   		$true_date= trim($true_date);
			}
			?>
			<input type="hidden" id="consulta_nombre" value=" <?php echo $nombre_completo; ?> ">
			<input type="hidden" id="fecha_nac_fam" value=" <?php echo $true_date; ?> ">
			<script type="text/javascript">
				var consulta_nombre=document.getElementById('consulta_nombre').value;
				document.getElementById('namefam').value=consulta_nombre;
				$("#fechanac").attr('type', 'text');				
				consulta_fecha_nac=document.getElementById('fecha_nac_fam').value;					
				$('#fechanac').val(consulta_fecha_nac);			
			</script>
			<?php 
		}
		else
		{
			?>
			<script type="text/javascript">
					
					document.getElementById('namefam').value="";					
					document.getElementById('fechanac').value="";
					var identidad_nueva=   "<?php echo $identidad; ?>"

				    swal({
					      title: 'No Registrado!',
					      text: 'Registrar en la Base local del RNP',
					      icon: 'warning',
					      buttons: {
					          cancel: true,
					          confirm: true
					      }
					  }).then(function(value) {
					      if (value) {
					          //  Either true or null
					          window.open("_rr_hh_add_censo.php?id="+identidad_nueva, '_blank');
					      }
					  });

   
			</script>
			<?php 
		}
 }
 else
 {
 	?>
			<script type="text/javascript">
				
								 

			</script>
			<?php 
 }


 

 ?>
<script type="text/javascript">
 	$(".div_wait").fadeOut("fast");
 </script>