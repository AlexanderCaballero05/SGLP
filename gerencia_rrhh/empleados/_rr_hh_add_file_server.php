<?php
 require("../../conexion.php");   	 
 
	  $_id              =   $_GET["ide"];	 
	  $_direccin_local  =   $_GET['ruta'];
	  $_descriṕcion     =   $_GET['descript'];	
	  $_namefile        =   $_GET['namefile'];	
	  $_texto_desc      =   str_replace("_", " ", $_descriṕcion); 

	  echo $_id." | ".$_direccin_local." | ".$_texto_desc; 
 
	  $servidor_ftp = "192.168.15.7";
	  $conexion_id = ftp_connect($servidor_ftp);
	  $ftp_usuario = "SGRHP";
	  $ftp_clave = "sgrhp2020**";


	  $ftp_carpeta_local    =   $_namefile;
	  $ftp_carpeta_remota   =  "/volumen1/EMPLOY_DOCS/";
      $mi_nombredearchivo   =   $_namefile;
      $nombre_archivo       =   $ftp_carpeta_local;
      $archivo_destino      =   $ftp_carpeta_remota;

 
      $resultado_login = ftp_login($conexion_id, $ftp_usuario, $ftp_clave);
      if ((!$conexion_id) || (!$resultado_login)) {
            echo  "La conexion ha fallado! al conectar con  $servidor_ftp para usuario $ftp_usuario";
            exit;
        } else {
            echo "Conectado con $servidor_ftp, para usuario $ftp_usuario";
   		} 

		$upload = ftp_put($conexion_id, $archivo_destino, $nombre_archivo, FTP_BINARY);
     		if (!$upload) {
       		echo "Ha ocurrido un error al subir el archivo";
   			} else {
       			echo "Subido $nombre_archivo a $servidor_ftp as $archivo_destino";
   			}


        ftp_close($conexion_id);
?>

<script type="text/javascript">
        $(".div_wait").fadeOut("fast");   
</script>