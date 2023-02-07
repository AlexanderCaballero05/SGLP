<?php
 	require("../../conexion.php"); 
 ob_start();
 session_start(); 

	 $_id=$_GET["id"];	
     $_user=$_GET["usu"];  
     $_tipo=$_GET["tipo"]; 
     $_incidencia=$_GET["incidencia"]; 
     $_decimos_nota=$_GET["decimos_nota"]; 
     $_neto_nota=$_GET["neto_nota"]; 
     $comment=$_GET["comment"]; 
     $texto_cambiado = str_replace("_", " ", $comment);
     $nombre_completo= $_SESSION['nombre_usuario']; 
	 
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

   $update_estado_revision = mysqli_query($conn, "UPDATE mayor_pagos_detalle SET estado_revision=1 , usuario_revision=$_user, usuario_revision_name='$nombre_completo',  fecha_revision=current_timestamp() WHERE id=$_id "); 
   if ($update_estado_revision) {
    $query_insert_nota="INSERT INTO rp_notas_credito_debito_mayor( id_detalle ,  transactiondate, transactioncode, agencia, transactionagencyname, transactionusername, `remesa`, `sorteo`, `numero`, decimos, `registro`, `neto`, `incidencia`, `tipo_documento`, `comentario_revisor`, usuario , ano_remesa, decimos_nota, neto_nota) 
                        VALUES ($_id, '$transactiondate', $transactioncode, $agency, '$agencyname',  '$cajero', $remesa, $sorteo, $numero, $serie, $registro, $neto, $_incidencia, $_tipo, '$texto_cambiado', $_user, YEAR(CURDATE()), $_decimos_nota, $_neto_nota  )";
   
  //  echo  $query_insert_nota;
    $insert_nota=mysqli_query($conn, $query_insert_nota);

   if ($insert_nota==false) {echo "<div class='alert alert-danger'><label>".mysqli_error($conn)."</label></div>"; } 
   }
   else
   {
    echo "error";
   }


 
 echo "<span class='badge badge-danger'> <i class='far fa-thumbs-down fa-2x'></i> Observaciones</span> ";
 
?>
 




