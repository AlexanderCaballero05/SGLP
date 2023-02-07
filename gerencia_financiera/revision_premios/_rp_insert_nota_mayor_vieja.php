<?php
 	require("../../conexion.php"); 

	 $_id=$_GET["id"];	
     $_user=$_GET["usu"];  
     $_tipo=$_GET["tipo"]; 
     $_incidencia=$_GET["incidencia"]; 
     $comment=$_GET["comment"]; 
     $texto_cambiado = str_replace("_", " ", $comment); 
	 
    $query_info_billete= mysqli_query($conn, "SELECT a.token, b.transactionagency, b.transactionagencyname, b.transactionusername,  a.remesa, a.sorteo, a.numero, a.decimos, a.registro, a.netopayment neto  , a.transactioncode, a.transactiondate
                                              FROM mayor_pagos_detalle a, mayor_pagos_recibos b     
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
    	$serie= $row_info['decimos'];
    	$registro= $row_info['registro'];
    	$neto= $row_info['neto'];
        $token= $sorteo.$numero.$serie;
    }

    $eeee=  "INSERT INTO rp_notas_credito_debito_mayor( id_detalle ,  transactiondate, transactioncode, agencia, transactionagencyname, transactionusername, `remesa`, `sorteo`, `numero`, decimos, `registro`, `neto`, `incidencia`, `tipo_documento`, `comentario_revisor`, usuario , ano_remesa) 
                                     VALUES ($_id, '$transactiondate', $transactioncode, $agency, '$agencyname',  '$cajero', $remesa, $sorteo, $numero, $serie, $registro, $neto, $_incidencia, $_tipo, '$texto_cambiado', $_user, YEAR(CURDATE())  )";

                                echo $eeee;  
   $insert_nota=mysqli_query($conn, "INSERT INTO rp_notas_credito_debito_mayor( id_detalle ,  transactiondate, transactioncode, agencia, transactionagencyname, transactionusername, `remesa`, `sorteo`, `numero`, decimos, `registro`, `neto`, `incidencia`, `tipo_documento`, `comentario_revisor`, usuario , ano_remesa) 
                                     VALUES ($_id, '$transactiondate', $transactioncode, $agency, '$agencyname',  '$cajero', $remesa, $sorteo, $numero, $serie, $registro, $neto, $_incidencia, $_tipo, '$texto_cambiado', $_user, YEAR(CURDATE())  )");

   if ($insert_nota==false) {echo "<div class='alert alert-danger'><label>".mysqli_error()."</label></div>"; } 
  
 
?>
 
