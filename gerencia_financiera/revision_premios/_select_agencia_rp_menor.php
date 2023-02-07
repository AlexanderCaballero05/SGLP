<?php
 	require("../../conexion.php");

 	 $_fecha_inicial=$_GET['inicio']; 	 
	 $requete = mysqli_query($conn, "SELECT distinct(b.transactionagency) agencia , transactionagencyname nombre 
	 								 FROM   menor_pagos_recibos b
	 								 WHERE  date(b.transactiondate) = '$_fecha_inicial' ORDER BY nombre ASC;");	 
	 if ($requete==false)
	 {
	   $valor= mysqli_error();   echo $valor;
	 }
	 else
	 {
	 	echo "<option> Selecione Uno</option>";
	 	while ($_row_agencia=mysqli_fetch_array($requete)) 
	 	{
	 	  $idagencia=$_row_agencia['agencia'];
	 	  $nombre=$_row_agencia['nombre'];
	 	  echo "<option value='".$idagencia."'> ".$idagencia."--".$nombre."</option>";
	 	}
	 }
 


?>
 
