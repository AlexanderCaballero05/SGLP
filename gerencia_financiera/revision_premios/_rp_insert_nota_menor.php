<?php
 	require("../../conexion.php"); 

	 $_id=$_GET["id"];	
     $_user=$_GET["usu"];  
     $_tipo=$_GET["tipo"]; 
     $_incidencia=$_GET["incidencia"]; 
     $comment=$_GET["comment"]; 
     $texto_cambiado = str_replace("_", " ", $comment); 
	 
    $query_info_billete= mysqlI_query($conn, "SELECT a.token, b.transactionagency, b.transactionagencyname, b.transactionusername,  a.remesa, a.sorteo, a.numero, a.serie, a.registro, a.neto   , a.transactioncode, a.transactiondate
                                      FROM menor_pagos_detalle a, menor_pagos_recibos b     
                                      WHERE a.transactioncode=b.transactioncode and a.id=$_id");

    while ($row_info = mysqli_fetch_array($query_info_billete)) 
    { 
        $transactiondate= $row_info['transactiondate'];
        $transactioncode= $row_info['transactioncode'];
    	$token= $row_info['token'];
    	$agency= $row_info['transactionagency'];
        $agencyname= $row_info['transactionagencyname'];
    	$cajero= $row_info['transactionusername'];
    	$remesa= $row_info['remesa'];
    	$sorteo= $row_info['sorteo'];
    	$numero= $row_info['numero'];
    	$serie= $row_info['serie'];
    	$registro= $row_info['registro'];
    	$neto= $row_info['neto'];
        $token= $sorteo.$numero.$serie;
    }

    
   $insert_nota=mysqli_query($conn, "INSERT INTO rp_notas_credito_debito_menor( id_detalle , `transactioncode`, transactiondate, `agencia`, transactionagencyname, transactionusername, `remesa`, `sorteo`, `numero`, `serie`, `registro`, `neto`, `incidencia`, `tipo_documento`, `comentario_revisor`, `usuario`) 
                                     VALUES ($_id, $transactioncode, '$transactiondate', $agency, '$agencyname', '$cajero', $remesa, $sorteo, $numero, $serie, $registro, $neto, $_incidencia, $_tipo, '$texto_cambiado', $_user )");

   if ($insert_nota==false) {echo "<div class='alert alert-danger'><label>".mysqli_error()."</label></div>"; } 
  
 
?>
 
