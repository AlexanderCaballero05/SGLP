<?php
 	 require("../../conexion.php");   	 

	 $_id=$_GET["id"];	$_user=$_GET["usu"];  $_estado=$_GET["estado"];  $_cod_seccional=$_GET["agencia_code"];  $_fecha_inicial=$_GET["fecha_inicial"];
	 $query_usuario=mysqli_query($conn, "SELECT nombre_completo FROM pani_usuarios where id=$_user");
	 while ($row_usu=mysqli_fetch_array($query_usuario) )
	 {
	 	 $nombre_completo=$row_usu["nombre_completo"];
	 }


	 if ($_estado==2)  
	 { 
	   	$requete = mysqli_query($conn, "UPDATE menor_pagos_detalle SET estado_revision=2 , usuario_revision=$_user, usuario_revision_name='$nombre_completo',  fecha_revision=current_timestamp() WHERE id=$_id "); 
	   if ($requete) 
	   {
	   			echo "<span class='badge badge-danger'> <i class='far fa-thumbs-down fa-2x'></i> Observaciones</span> ";
	  		// 	echo "<div class='alert alert-danger'><i class='far far fa-file-times fa-2x'></i>  &nbsp; Observaciones</div> ";
	   }
	   else
	   {
	  			 echo "Error ".mysqli_error();
	   }  	
	 }
	 else if ($_estado==1)  
	 { 
	 	$requete = mysqli_query($conn, "UPDATE menor_pagos_detalle SET estado_revision=1 , usuario_revision=$_user, usuario_revision_name='$nombre_completo', fecha_revision=current_timestamp() WHERE id=$_id "); 
	  if ($requete) 
	   {
	 	echo "<span class='badge badge-success'><i class='far fa-check-square  fa-2x'></i>  &nbsp; Revisado</span> ";
	 	//echo "<div class='alert alert-success'><i class='far fa-check-square  fa-2x'></i>  &nbsp; Revisado</div> ";
	 	 }
	   else
	   {
	  			 echo "Error ".mysqli_error();
	   } 
	 } 
	
  

	 ?>
	 	<script type="text/javascript">
            $(".div_wait").fadeOut("fast");   
        </script>
        <?php
 
?>
 

      