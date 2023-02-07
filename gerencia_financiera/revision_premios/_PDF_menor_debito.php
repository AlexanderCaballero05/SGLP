<?php 
require('../../template/header.php'); 
$id=$_GET['id_nota'];
$name_revisor=$_SESSION['nombre_usuario'];
 
 $hoy = date("Y-m-d H:i:s");  
 echo $hoy;
$contenido ="";
 $contenido .= '<table  class="table table-hover table-sm table-bordered" id="">
    	       		 	 <thead><tr><th>Fecha de pago</th>
		    	       				<th>Agencia</th> 
		    	       				<th>Cajero</th>
		    	       				<th>Descripcion Nota</th>		    	       				 		    	       				
		    	       				<th>sorteo</th> 
		    	       				<th>numero</th>
		    	       				<th>Serie</th> 		    	       				
		    	       				<th>Neto</th>
		    	       			</tr>   	       			
    	       			 </thead>
    	       			<tbody> ';   	       		 
			    	  	
			    	    $query_info_notas = mysqli_query($conn, "SELECT a.id, id_detalle, transactioncode, a.agencia, transactionagency, transactionagencyname, transactiondate, cajero, transactionusername , remesa, sorteo, numero, serie, neto,  incidencia,  tipo_documento, comentario_revisor, fecha_creacion, state estado, b.nombre_completo  FROM rp_notas_credito_debito_menor a,  pani_usuarios b WHERE a.usuario=b.id and ano_remesa>'2018' and a.id=$id order by transactiondate asc;");

			    	    if (mysqli_num_rows($query_info_notas)>0) 
			    	    {
			    	    	 $estado_txt='';
			    	    	 $tipo_documento_txt='';
			    	    	 while( $row_info_notas=mysqli_fetch_array($query_info_notas))
			    	    	 {
			    	    	 	$fecha_pago             = $row_info_notas['transactiondate'];
			    	    	 	$transactioncode        = $row_info_notas['transactioncode'];
			    	    	 	$transactionagency      = $row_info_notas['transactionagency'];
			    	    	 	$transactionagencyname  = $row_info_notas['transactionagencyname'];
			    	    	 	$transactionusername    = $row_info_notas['transactionusername'];
			    	    	 	$remesa  				= $row_info_notas['remesa'];
			    	    	 	$sorteo  				= $row_info_notas['sorteo'];
			    	    	 	$numero  				= $row_info_notas['numero'];
			    	    	 	$decimos 				= $row_info_notas['serie'];
			    	    	 	$neto    				= $row_info_notas['neto'];
			    	    	 	$incidencia         	= $row_info_notas['incidencia'];
			    	    	 	$tipo_documento         = $row_info_notas['tipo_documento'];
			    	    	 	$comentario_revisor     = $row_info_notas['comentario_revisor'];
			    	    	 	$fecha_creacion         = $row_info_notas['fecha_creacion'];
			    	    	 	$estado    				= $row_info_notas['estado'];
			    	    	 	$usuario_revision_name  = $row_info_notas['nombre_completo'];
			    	    	 	$fecha_revision			= $row_info_notas['fecha_creacion'];
			    	    	 	$id_nota 	 			= $row_info_notas['id'];
 

			    	    	 	if ($tipo_documento==1) 
			    	    	 	{
			    	    	 		$tipo_documento_txt="Nota de Credito Interna";

			    	    	 	}
			    	    	 	else if ($tipo_documento==2) 
			    	    	 	{
			    	    	 		$tipo_documento_txt="Nota de Debito Interna";
			    	    	 	}
			    	    	 	else if ($tipo_documento==3) 
			    	    	 	{
			    	    	 		$tipo_documento_txt="Reporte de Sobrante";
			    	    	 	}
			    	    	 	else if ($tipo_documento==4) 
			    	    	 	{
			    	    	 		$tipo_documento_txt="Nota de Debito Externa";
			    	    	 	}

			    	    	 	$contenido .=  "<tr><td>".$fecha_pago."</td>
			    	    	 			    <td>".$transactionagencyname."</td>
			    	    	 			    <td>".$transactionusername."</td>	
			    	    	 			    <td>".$tipo_documento_txt."</td>  
			    	    	 			    <td>".$sorteo."</td>
			    	    	 			    <td>".$numero."</td>
			    	    	 			    <td>".$decimos."</td>
			    	    	 			    <td>".$neto."</td>
			    	    	 			</tr>";
			    	    	 }
			    	    }


			    	 	
    	       		$contenido .= "</tbody>
    	       		</table>  "; 

 
 

echo '<table align="center">
         <tr><td  width="20%"></td>
               <td width="60%" style="font-family: Arial; font-size: 18pt;"></td>
               <td width="20%"></td>
        </tr> 
        <tr>
            <td  colspan="3"> . </td>
        </tr>
         <tr><td width="20%"></td>
           <td  width="60%"> <div align="center">
           <label style=" font-family: Arial; font-size:14pt;" >Patronato Nacional de la Infancia PANI 
           <br> Departamento de Revision de Premios
           <br> '.$tipo_documento_txt.' de Loteria Menor No. '.$id.'  a favor del PANI
           <br> Emitida el '.$fecha_creacion.'
           <br> Correspondiente al pago realizado el '.$fecha_pago.'  
           <td width="20%"></td>
        </tr>      
     </table><br>';

     echo $contenido;
 ?> 



    	       		<p><h5>Comentario del Revisor: </h5>
    	       			<?php echo $comentario_revisor; ?>
    	       		</p>

    	       		<?php    
    	       				echo"<br><br><br><div align='center'>____________________________<br> Firma y Sello Obligatorio <br> ".$usuario_revision_name." </div>";

    	       		 ?>

<script type="text/javascript">
document.title="Revision de Premios";
window.print(); 
setTimeout(window.close, 1000);
</script>
 