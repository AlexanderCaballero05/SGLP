<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['id_usuario'];

  
$remesa=1;
$remesa_titulo=str_pad($remesa, 3, "0", STR_PAD_LEFT);
$remesa_titulo= "No. ".$remesa_titulo." - ".date("Y");

?>
<script type="text/javascript">
  $(".div_wait").fadeIn("fast");  

function check(t){
    var id = t.id;  
    var select = document.getElementById(id); //El <select>
        value = select.value; //El valor seleccionado
        text = select.options[select.selectedIndex].innerText;
 		document.getElementById('agencia_cod').value=text;
}

function check2(t)
{
	var id = t.id;      
    var select = document.getElementById(id); //El <select>
        value = select.value; //El valor seleccionado
        text = select.options[select.selectedIndex].innerText;
 		document.getElementById('cajero_cod').value=text;
}


 </script>
<style type="text/css">
.div_wait 
{
  display: none;
  position: fixed;
  left: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  z-index: 9999;
  background-color: black;
  opacity:0.5;
  background: url(../../template/images/wait.gif) center no-repeat #fff;
}

.modal-lg {
    max-width: 80%;
}
</style>
<form method="post" id="_revision_premios"  class="" name="_revision_premios">
<div id='div_wait'></div>
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><br><h3> Solicitudes de Debitos de Lotería Menor </h3> <br></section>

 <section><hr>
 <div align="left"> 

<?php if ($usuario_id<>65): ?>
	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#basicExampleModal" style="margin-left: 10px;">
  Ingresar un Nuevo Débito
</button>
<?php endif ?>

<!-- Modal -->
<div class="modal fademodal fade bd-example-modal-lg" id="basicExampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header text-center" style="background-color:#e7e7e7;">
        <h5 class="modal-title" id="exampleModalLabel">Formulario para ingresar hojas de Débito de loteria Menor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">     
       <form>
       	<div class="row">
       		<div class="col-sm-4">
		          <div class="form-group">
		            <label for="recipient-name" class="col-form-label">Fecha de pago:</label>
		            <input type="date" class="form-control" id="recipient-name" name="fecha_pago_debito">
		          </div>
		    </div>
		    <div class="col-sm-4">
		          <div class="form-group">
		            <label for="message-text" class="col-form-label">Agencia:</label>
		            <select class="form-control" required="true" onchange="check(this)" id="agencia_debito" name="agencia_debito">
		            	<option>Seleccione uno ..</option>
		            	 <?php 
                                                        $query_agencias=mysqli_query($conn, "SELECT transactionagency , transactionagencyname from menor_pagos_recibos group by transactionagencyname order by transactionagency asc");
                                                        if ($query_agencias==true)  
                                                          {  
                                                            while ($row_agencias=mysqli_fetch_array($query_agencias))   
                                                              { 
                                                                  echo"<option value='".$row_agencias['transactionagency']."'>".$row_agencias['transactionagency']." - ".$row_agencias['transactionagencyname']."</option>";   
                                                              } 
                                                          }
                                                        else { echo "<option value='99'>Valores no encontrados</option>";   }
                                                     ?>
		            </select>
		          </div>
		    </div>
		    <div class="col-sm-4">
		          <div class="form-group">
		            <label for="message-text" class="col-form-label">Cajero:</label>
		            <select class="form-control" required="true" onchange="check2(this)" name="cajero_debito" id="cajero_debito">
		            	<option>Seleccione uno ...</option>

		            	 <?php 
                                                        $query_cajeros=mysqli_query($conn, "SELECT transactionuser , transactionusername from menor_pagos_recibos group by transactionusername order by transactionuser asc");
                                                        if ($query_agencias==true)  
                                                          {  
                                                            while ($row_cajeros=mysqli_fetch_array($query_cajeros))   
                                                              { 
                                                                  echo"<option value='".$row_cajeros['transactionuser']."'>".$row_cajeros['transactionusername']."</option>";   
                                                              } 
                                                          }
                                                        else { echo "<option value='99'>Valores no encontrados</option>";   }
                                                     ?>
		            </select>
		          </div>
		    </div>
         </div>

         <div class="row">
       		<div class="col-sm-4">
		          <div class="form-group">
		            <label for="recipient-name" class="col-form-label">Sorteo:</label>
		              <input type="text" class="form-control" name="sorteo_debito">
		          </div>
		    </div>
		    <div class="col-sm-4">
		          <div class="form-group">
		            <label for="message-text" class="col-form-label">Numero:</label>
		            <input type="text" class="form-control" name="numero_debito">
		          </div>
		    </div>

		     <div class="col-sm-4">
		          <div class="form-group">
		            <label for="message-text" class="col-form-label">Serie:</label>
		            <input type="text" class="form-control" name="serie_debito">
		          </div>
		    </div>
         </div>
         <div class="row">
       		<div class="col-sm-4">
		          <div class="form-group">
		            <label for="recipient-name" class="col-form-label">Remesa:</label>
		              <input type="text" class="form-control" name="remesa_debito">
		          </div>
		    </div>
		    <div class="col-sm-4">
		           <div class="form-group">
		            <label for="recipient-name" class="col-form-label">Registro:</label>
		              <input type="text" class="form-control" name="registro_debito">
		          </div>
		    </div>
		     <div class="col-sm-4">
		          <div class="form-group">
		           
		            <input type="hidden" class="form-control" name="cajero_cod" id="cajero_cod">
		            <input type="hidden" class="form-control" name="agencia_cod" id="agencia_cod">
		          </div>
		    </div>
		    
         </div>
          	<div class="row">
       		<div class="col-sm-4">
		         <div class="form-group">
		            <label for="recipient-name" class="col-form-label">Tipo documento:</label>
		             <select class="form-control" required="true" name="tipo_documento_debito">
		            	<option>Seleccione uno</option>
		            	<option value='1'>Nota de Credito Interna</option>
		            	<option value='2'>Nota de Debito Interna</option>
		            	<option value='3'>Nota de Credito Externa</option>
		            	<option value='4'>Nota de Debito Externa</option>
		            </select>
		          </div>
		    </div>
		      <div class="col-sm-4">
		          <div class="form-group">
		            <label for="recipient-name" class="col-form-label">Incidencia:</label>
		             <select class="form-control" required="true" name="incidencia_debito">
		            	<option>Seleccione uno...</option>
		            	  <?php 
                                                        $query_incidencias=mysqli_query($conn, "SELECT * from rp_incidencias order by id asc");
                                                        if ($query_incidencias==true)  
                                                          {  
                                                            while ($row_incidencia=mysqli_fetch_array($query_incidencias))   
                                                              { 
                                                                  echo"<option value=".$row_incidencia['id'].">".$row_incidencia['incidencia']."</option>";   
                                                              } 
                                                          }
                                                        else { echo "<option value='99'>Valores no encontrados</option>";   }
                                                     ?>
		            </select>
		          </div>
		    </div>
		      <div class="col-sm-4">
		         <div class="form-group">
		            <label for="message-text" class="col-form-label"> Valor Neto :</label>
		              <div class="input-group mb-2">
						  <div class="input-group-prepend">
						    <span class="input-group-text">L.</span>
						  </div>
						  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" name="neto_debito">
						  <div class="input-group-append">
						    <span class="input-group-text">.00</span>
						  </div>
						</div>
				</div>
		    </div>		   
          </div>

          

          <div class="row">
          		<div class="col-sm-12">          			  
				          <div class="form-group">
				            <label for="message-text" class="col-form-label">Comentario Revisor :</label>
				            <textarea class="form-control" rows="5" id="message-text" name="comentario_debito"></textarea>
				          </div>
          		</div>
          </div>
       </form>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" name="add_nota" value='nota' class="btn btn-primary">Agregar</button>
      </div>
    </div>
  </div>
</div>
</div><hr>		 
    	       	<div class="table-responsive">
    	       		<table  class="table table-hover table-sm table-bordered" id="table_id1">
    	       		 	 <thead><tr class='table-success'><th>Fecha de pago</th>
		    	       				<th>Agencia</th> 
		    	       				<th>Cajero.</th>
		    	       				<th>Comentario</th>
		    	       				<th>Descripcion Nota</th>
		    	       				<!-- th>Estado</th --> 
		    	       				<th>Usuario Revision</th> 
		    	       				<th>Fecha Revision</th>
		    	       				<th>sorteo</th> 
		    	       				<th>numero</th>
		    	       				<th>Serie</th> 		    	       				
		    	       				<th>Neto</th>
		    	       				<th></th>
		    	       			</tr>   	       			
    	       			 </thead>
    	       			<tbody>    	       		 
			    	    <?php	
			    	    $query_info_notas = mysqli_query($conn, "SELECT a.id, id_detalle, transactioncode, a.agencia, transactionagency, transactionagencyname, transactiondate, cajero, transactionusername , remesa, sorteo, numero, serie, neto,  incidencia,  tipo_documento, comentario_revisor, fecha_creacion, state estado, b.nombre_completo  FROM rp_notas_credito_debito_menor a,  pani_usuarios b WHERE a.usuario=b.id and ano_remesa>'2018' order by transactiondate desc;");

			    	    if (mysqli_num_rows($query_info_notas)>0) 
			    	    {
			    	    	 $estado_txt='';	 $tipo_documento_txt='';
			    	    	 
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

			    	    	 	if ($estado==1) 
			    	    	 	{
			    	    	 	  $estado_txt="NO APROBADO";
			    	    	 	}
			    	    	 	else
			    	    	 	{
			    	    	 	  $estado_txt="APROBADO";
			    	    	 	}

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
			    	    	 		$tipo_documento_txt="Sobrante";
			    	    	 	}
			    	    	 	else if ($tipo_documento==4) 
			    	    	 	{
			    	    	 		$tipo_documento_txt="Nota de Debito Externa";
			    	    	 	}




			    	    	 	echo   "<tr><td>".$fecha_pago."</td>
			    	    	 			    <td>".$transactionagencyname."</td>
			    	    	 			    <td>".$transactionusername."</td>	
			    	    	 			    <td>".$comentario_revisor."</td>
			    	    	 			    <td>".$tipo_documento_txt."</td>
			    	    	 			    <!-- td>".$estado_txt."</td -->
			    	    	 			    <td>".$usuario_revision_name."</td>
			    	    	 			    <td>".$fecha_revision."</td>
			    	    	 			    <td>".$sorteo."</td>
			    	    	 			    <td>".$numero."</td>
			    	    	 			    <td>".$decimos."</td>
			    	    	 			    <td>".$neto."</td>
			    	    	 			    <td><a role='button' class='btn btn-primary text-white' target='_blank' href='_PDF_menor_debito.php?id_nota=".$id_nota."'> Imprimir </a></td>
			    	    	 		    </tr>";
			    	    	 }
			    	    }

			    	
			    	    ?>	
    	       			</tbody>
    	       		</table>    	       			
    	       		</div> 
   
   	<?php if (isset($_POST['add_nota']))
   	{ 		 
 		$fecha_pago_debito			=	$_POST['fecha_pago_debito'];
 		$agencia_cod 				=	$_POST['agencia_cod'];
 		$agencia_debito				=	$_POST['agencia_debito'];
 		$cajero_cod  				=	$_POST['cajero_cod'];
 		$cajero_debito				=	$_POST['cajero_debito'];
 		$sorteo_debito				=	$_POST['sorteo_debito'];
 		$numero_debito				=	$_POST['numero_debito'];
 		$serie_debito				=	$_POST['serie_debito'];
 		$registro_debito			=	$_POST['registro_debito'];
 		$remesa_debito			    =	$_POST['remesa_debito'];
 		$neto_debito 				=	$_POST['neto_debito']; 		
 		$incidencia_debito			=	$_POST['incidencia_debito']; 		
 		$tipo_documento_debito		=	$_POST['tipo_documento_debito'];
 		$comentario_debito			=	$_POST['comentario_debito'];

 		if (mysqli_query($conn, "INSERT INTO rp_notas_credito_debito_menor (agencia, transactionagency, transactionagencyname, cajero, transactionusername, transactiondate, remesa, ano_remesa,  sorteo, numero, serie, registro, neto, incidencia, tipo_documento, comentario_revisor, usuario, state ) VALUES ( '$agencia_debito',  '$agencia_debito', '$agencia_cod', '$cajero_debito', '$cajero_cod', '$fecha_pago_debito',  $remesa_debito, '2019', $sorteo_debito, $numero_debito, $serie_debito, $registro_debito, $neto_debito, $incidencia_debito, $tipo_documento_debito,  '$comentario_debito', $usuario_id, 1   ) " )) 
 		{
 		   echo "<div class='alert alert-success'> Registro Guardado Con exito</div>";
 		} 
 		else
 		{
 			echo "<div class='alert alert-danger'>Ha habido un error detalle : ".mysqli_error($conn)."</div>";
 		}
 	}

 	?>
 		
 	 

 	
 </section>
<script type="text/javascript">


	$(".div_wait").fadeOut("fast");  
</script>
