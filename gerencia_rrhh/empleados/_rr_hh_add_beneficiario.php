<?php
 	 require("../../conexion.php");   	 

	 $_id=$_GET["id"];	 $_porcentaje= $_GET["porc"];
	 //echo "este es el id : ".$_id;
 	 
	 $requete = mysqli_query($conn, "UPDATE rr_hh_empleados_familias  SET porcentaje_seguro='$_porcentaje' WHERE identidad_familiar='$_id' ");

	   if ($requete) {
	   		$query_new_porcentaje =  mysqli_query($conn, " SELECT porcentaje_seguro FROM rr_hh_empleados_familias WHERE identidad_familiar='$_id' ");
	   		$obj_porcen		 = mysqli_fetch_object($query_new_porcentaje);
	   		$new_porcentaje  = $obj_porcen->porcentaje_seguro;
	       echo "<input type='number' name='arraybenef[]' class='' value='".$new_porcentaje."'  min='0' max='100' required>";	 

	   } else {
	  			 echo "Error ".mysqli_error($conn);
 	   }

	 ?>
	 	<script type="text/javascript">
            $(".div_wait").fadeOut("fast");   
        </script>
        <?php
 
?>
 

