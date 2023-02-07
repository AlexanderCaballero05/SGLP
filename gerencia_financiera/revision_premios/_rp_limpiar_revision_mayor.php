<?php
 	 require("../../conexion.php");   	 

	 $_id=$_GET["id"];	$_user=$_GET["usu"];  $_estado=$_GET["estado"];  $_cod_seccional=$_GET["agencia_code"];  $_fecha_inicial=$_GET["fecha_inicial"];
	 $query_usuario=mysqli_query($conn, "SELECT nombre_completo FROM pani_usuarios where id=$_user");
	 while ($row_usu=mysqli_fetch_array($query_usuario) )
	 {
	 	 $nombre_completo=$row_usu["nombre_completo"];
	 }


	 
	   	$requete = mysqli_query($conn, "UPDATE mayor_pagos_detalle SET estado_revision=null , usuario_revision=$_user, usuario_revision_name='$nombre_completo',  fecha_revision=current_timestamp() WHERE id=$_id "); 
	   if ($requete) 
	   {
	   			echo "Billete pendiente de Revisar"; 
	   }
	   else
	   {
	  			 echo "Error ".mysqli_error($conn);
	   }  	
	  
  

	 ?>
	 	<script type="text/javascript">
            $(".div_wait").fadeOut("fast");   
        </script>
        <?php
 
?>
 

      