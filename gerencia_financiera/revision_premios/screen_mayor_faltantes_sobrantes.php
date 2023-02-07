<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['id_usuario'];
$nombre_usuario=$_SESSION['nombre'];
$user_name=$_SESSION['nombre_usuario']
?>
<script type="text/javascript">
          
      function justNumbers(e)
      {
            var keynum = window.event ? window.event.keyCode : e.which;
            if ((keynum == 8) || (keynum ==47 ))
            return true;            
            return /\d/.test(String.fromCharCode(keynum));
     }

</script>
<form method="post">
<input type='hidden' id ='usuario_txt'  name='usuario_txt' class='form-control' value="<?php echo $usuario_id; ?>" />
<div id="div_wait" class="div_wait">  </div> <div id="divcarga"></div> <div id="msg_reversion"></div>
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Faltantes y Sobrantes de Loteria Mayor</h3> <br></section>
<section>
<a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS  <i class="far fa-hand-point-down fa-lg"></i></a>
    <div class="collapse " id="collapse1" align="center">
      <div class="card">
        <div class="card card-body" id="non-printable" >         
          <table class="table table-sm table-hover">
            <tr>
                <td>
                    <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
                        <div class = "input-group-prepend"><span  class="input-group-text"> &nbsp;  Tipo: </span></div>
                        <select name="slcttipo" id="slcttipo" required="true"  class="form-control">
                          <option>Seleccione Uno</option>
                          <option value="Faltante">Faltante</option>
                          <option value="Sobrante">Sobrante</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
                        <div class = "input-group-prepend"><span  class="input-group-text"></i> &nbsp;  Fecha Revision: </span></div>
                        <input type='date' id ="fecha_i"   name = "fecha_inicial" class="form-control">
                    </div>
                </td>
                <td>
                    <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
                        <div class = "input-group-prepend" style="margin-left: 10px;"><span  class="input-group-text"> &nbsp;   Agencia: </span></div>
                        <select name="slctagencia" id="slctagencia" required="true" class="form-control">
                        <option> Seleccione Uno </option>
                        <?php 
                        $query_agencia=mysqli_query($conn, "SELECT nombre from seccionales where id_empresa=3 order by nombre asc;");
                        while ($row_agencia=mysqli_fetch_array($query_agencia)) {
                        echo "<option value='".$row_agencia['nombre']."'> ".$row_agencia['nombre']." </option>";
                        }
                        ?> 
                        </select>
                    </div> 
                </td>
                <td>                  
                     <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
                        <div class = "input-group-prepend"><span  class="input-group-text"> &nbsp;  Remesa: </span></div>
                        <select name="txtremesa" id="txtremesa" required="true" class="form-control">
                            <option> Seleccione Uno </option>
                          <?php 
                          $query_agencia=mysqli_query($conn, "SELECT remesa from mayor_pagos_detalle where ano_remesa='2019' group by remesa order by remesa DESC;");
                          while ($row_agencia=mysqli_fetch_array($query_agencia)) 
                          {
                          echo "<option value=".$row_agencia['remesa']."> ".$row_agencia['remesa']." </option>";
                          }
                          ?>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
                        <div class = "input-group-prepend"><span  class="input-group-text"> &nbsp;  Sorteo: </span></div>
                        <select name="slctsorteo" id="slctsorteo" required="true" class="form-control">
                            <option> Seleccione Uno </option>
                          <?php 
                          $query_agencia=mysqli_query($conn, "SELECT sorteo from mayor_pagos_detalle group by sorteo order by sorteo DESC;");
                          while ($row_agencia=mysqli_fetch_array($query_agencia)) 
                          {
                          echo "<option value=".$row_agencia['sorteo']."> ".$row_agencia['sorteo']." </option>";
                          }
                          ?>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
                        <div class = "input-group-prepend"><span  class="input-group-text"> &nbsp;  Número: </span></div>
                        <input type='number' id ='txtnumero' required="true" onkeypress="return justNumbers(event)"  name='txtnumero' class='form-control'/>
                    </div>
                </td>
                <td>
                    <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
                      <div class = "input-group-prepend"><span  class="input-group-text"> &nbsp; Decimos: </span></div>
                      <input type='number' id ='txtdecimos' required="true"  onkeypress="return justNumbers(event)" name='txtserie' class='form-control'/>
                    </div>	
                </td>
                <td>
                    <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
                    <div class = "input-group-prepend"><span  class="input-group-text"> &nbsp; Registro: </span></div>
                    <input type='number' id ='txtregistro' required="true" onkeypress="return justNumbers(event)" name='txtregistro' class='form-control'/>
                  </div>
                </td> 
            </tr>
            <tr>
              <td>
                  <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
                        <div class = "input-group-prepend"><span  class="input-group-text"> &nbsp;  Total: </span></div>
                        <input type='number' id ='txttotal' required="true" onkeypress="return justNumbers(event)"  name='txttotal' class='form-control'/>
                  </div>
              </td>
              <td>
                  <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
                        <div class = "input-group-prepend"><span  class="input-group-text"> &nbsp;  Impto: </span></div>
                        <input type='number' id ='txtimpto' required="true" onkeypress="return justNumbers(event)"  name='txtimpto' class='form-control'/>
                  </div>
              </td>
              <td>
                  <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
                        <div class = "input-group-prepend"><span  class="input-group-text"> &nbsp;  Neto: </span></div>
                        <input type='number' id ='txtneto' required="true" onkeypress="return justNumbers(event)"  name='txtneto' class='form-control'/>
                  </div>
              </td>              
            </tr>
            <tr>
                <td colspan="4">
                  <textarea  id="txtcomment" name="txtcomment" style="width:100%" rows="6" name=""></textarea>
                </td>
            </tr>
            <tr>
              <td colspan="4" align="center">
                  <button id="btnguardar" name="btnguardar" type="submit" class="Consulta btn btn-success">Agregar Registro de Faltantes | Sobrantes</button>
              </td>
            </tr>
          </table> 
          </div>
        </div>
      </div>
    </div> 
 </section>
 <hr><hr>
 <section>
<h3 align="center"><div class="alert alert-success"> <strong> Listado de Sobrantes y Faltantes de Lotería Mayor </strong></div></h3>
  <hr>
		<div id="reporte" class="table-responive" align="center">	<br><br>
				 <table id="table_id1" align="center" class="table table-hover table-bordered table-responsive">	
    				  <thead>
                <tr>
          				   <td>Tipo</td>
          				   <td>Fecha</td>
          				   <td>Remesa</td>
          				   <td>Agencia</td> 
          				   <td>Cajero</td>  
          					 <td>Sorteo</td> 
          					 <td>Numero</td>
          					 <td>Decimos</td>
          					 <td>Registro</td>
          					 <td>Total</td>
          					 <td>Impto</td>
          					 <td>Neto</td>
          					 <td>Creacion</td>
          					 <td>Comentario</td>
          					 <td>Accion</td>
                </tr>
    				 </thead>
    				 <tbody>
    				 <?php
    					$result = mysqli_query($conn, "SELECT id, remesa, sorteo, numero, decimos, `registro`, `totalpayment`, `imptopayment`, `netopayment`, `transactionusername`, `transactionagencyname`, date(`transactiondate`) fecha, date(`creationdate`) fecha_creacion, `creationuser`, `registertype`, `coment` FROM `rp_faltantes_sobrantes_mayor`  ");
    				  $num_productos = mysqli_num_rows($result);
    				  if (mysqli_num_rows($result)>0)
    				  {
    					  while ($row = mysqli_fetch_array($result)) 
    					  {  
    						  echo "<tr><td>".$row['registertype']."</td>
    		                             <td>".$row['fecha']."</td>
    		                             <td>".$row['remesa']."</td>
    		                             <td>".$row['transactionagencyname']."</td>
    		                             <td>".$row['transactionusername']."</td>
    		                             <td>".$row['sorteo']."</td>
    		                             <td>".$row['numero']."</td>
    		                             <td>".$row['decimos']."</td>
    		                             <td>".$row['registro']."</td>
    		                             <td>".$row['totalpayment']."</td>
    		                             <td>".$row['imptopayment']."</td>
    		                             <td>".$row['netopayment']."</td>
    		                             <td>".$row['creationuser']." el ".$row['fecha_creacion']."</td>
    		                             <td>".$row['coment']."</td>
    		                             <td><a class='btn btn-primary' target='_blank' href= './_PDF_rp_add_faltantes_sobrantes_mayor.php?cod_impresion_faltante=".$row['id']."'>
    									     <span class ='glyphicon glyphicon-eye-open'>Imprimir</span>  
    									     </a></td>
    		                </tr>";
    		          }
    		        }
    		        else
    		        {  
    		          echo mysqli_error();   
    		        }
    						 
    						?>				 	 
    		     </tbody> 
		    </table>
 </section>

 <?php 
if (isset($_POST['btnguardar'])) 
{
        $_fecha_inicial=$_POST['fecha_inicial'];  
        $_fecha_inicial = date("Y-m-d", strtotime($_fecha_inicial));
        $_remesa=$_POST['txtremesa'];
        $_agencia=$_POST['slctagencia'];    
        $_sorteo=$_POST['slctsorteo'];
        $_numero=$_POST['txtnumero'];
        $_serie=$_POST['txtserie'];
        $_registro=$_POST['txtregistro'];
        $_total=$_POST['txttotal'];
        $_impto=$_POST['txtimpto'];
        $_neto=$_POST['txttotal']-$_POST['txtimpto'];       
        $_tipo=$_POST['slcttipo'];
        $_comment=$_POST['txtcomment'];     

        if (mysqli_query($conn, "INSERT INTO rp_faltantes_sobrantes_mayor (remesa, sorteo, numero, decimos, registro, totalpayment, imptopayment, netopayment,  transactionagencyname, transactiondate, registertype, creationuser,  coment) 
        VALUES ('$_remesa',  '$_sorteo', '$_numero', '$_serie', '$_registro', '$_total', '$_impto', '$_neto',  '$_agencia', '$_fecha_inicial', '$_tipo', '$user_name', '$_comment' ) ")==true)
        {
            $query_id=mysqli_query($conn, "SELECT LAST_INSERT_ID(id) id from rp_faltantes_sobrantes_mayor order by id desc limit 1;");
            while ($row_id=mysqli_fetch_array($query_id))
            { 
                $lastid=$row_id['id'];
            }

          $_SESSION['cod_impresion_faltante'] = $lastid; 
     
  ?>
   

             <script type="text/javascript">
             swal({
              title: "Excelente",
              text: "Has generado un Sobrante | Faltante ",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                 window.open('./_PDF_rp_add_faltantes_sobrantes_mayor.php', '_blank');                 
              }  
            });
            </script>
        <?php 
        } else {
          echo '<div class="alert alert-danger" role="alert"> Error inesperado  '.mysql_error().', favor vuelva a intentarlo</div>';
        }
        
        

}
   


  ?> 	   
</form>