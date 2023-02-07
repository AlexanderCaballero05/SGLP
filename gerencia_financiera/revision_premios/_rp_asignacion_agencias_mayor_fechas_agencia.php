<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['usuario'];   $_remesa=$_GET['remesa'];  $_fecha_pago=$_GET['fecha_pago'];  $fecha=$_fecha_pago;
?> 
 
<style type="text/css"> 
   @media print  {      
        #non-printable { display: none; }
        #printable { display: block; }
        #table_1 { display: block; }        
        #btn-rev { display: none; }
          td, tr { padding-bottom: 0px;   border-spacing: 0; font-family: Arial; font-size: 07pt;    } 
         body  { font-family: Arial; font-size: 07pt; }
    } 

    body  { font-family: Arial;  }

    th, td , tr{ padding-bottom: 0px;   border-spacing: 0; font-size: 12pt; font-family: Arial;  } ; 
</style>

<script type="text/javascript">
      function add_revisor(valor, estado)
      {
        document.getElementById('fecha').value=document.getElementById('txt'+valor).value;
        document.getElementById('age').value=document.getElementById('txtag'+valor).value;
      }

      function quitar_revisor(valor, remesa, agencia)
      {
          var fecha= document.getElementById('txt'+valor).value;
           var urr_dco = '_rp_quitar_asignacion_menor_agencia.php?fecha='+fecha+'&remesa='+remesa+'&ag='+agencia+'&est=2&al='+Math.random(); 
           $("#tabl").load(urr_dco);
      }

      function update_revisor()
      {
         fecha=document.getElementById('fecha').value;
         agencia=document.getElementById('age').value;
         remesa=document.getElementById('remesa_modal').value;
         revisor=document.getElementById('slctincidencia').value;

         var urr_dco = '_rp_add_asignacion_menor_agencia.php?fecha='+fecha+'&remesa='+remesa+'&revisor='+revisor+'&ag='+agencia+'&est=2&al='+Math.random(); 
         
         $("#tabl").load(urr_dco);
       //  alert(urr_dco) ;
         $("[data-dismiss=modal]").trigger({ type: "click" });
         $('#myModal').trigger("reset");
      }
</script>

<form method="post" id="_revision_premios"  class="" name="_revision_premios"> 
<div class='form-group'  id='table_1' >
<?php  	
    echo "<div class='row'>
          <div class='col-md-1'></div>
          <div class='col-md-10' id='tabl'><br><h3><label>Asignación de Loteria Menor Remesa ".$_remesa."  fecha ".$_fecha_pago."</label></h3><hr>
            <table class='table table-hover'>
              <thead><tr><th>Agencias</th>
                         <th>Billetes</th>
                         <th>Neto pagado</th>
                         <th></th></tr></thead><tbody>";                      

    $query_agencias_fecha=mysqli_query($conn, "SELECT a.id, transactionagencyname nombre,  transactionagency, cant_numeros, totalpayment total, imptopayment impto, netopayment neto, usuario_revision 
                                      FROM `rp_asignacion_agencias_revisor_menor` a 
                                      WHERE   remesa=$_remesa   and date(transactiondate)='$_fecha_pago' order by fecha_recepcion asc ");
    if (!$query_agencias_fecha) {     echo mysqli_error();  }
    $ontador=0;
    while ( $row_agencias_dia=mysqli_fetch_array($query_agencias_fecha)  )
    {
       $agencia_code=$row_agencias_dia['transactionagency']; $agencias=$row_agencias_dia['nombre'];  $totalnumeros=$row_agencias_dia['cant_numeros'];  $total_fecha=$row_agencias_dia['total'];  $impto_fecha=$row_agencias_dia['impto'];  $neto_fecha=$row_agencias_dia['neto'];  $revisor_code=$row_agencias_dia['usuario_revision'];

        echo "<tr><td>".$agencia_code."--".$agencias."</td>
                  <td>".$totalnumeros."</td>
                  <td>".number_format($neto_fecha,2,'.',',')."</td>
                  <td align= 'left'>           
                     <input type='hidden' id='txt".$ontador."' value='".$fecha."'>
                     <input type='hidden' id='txtag".$ontador."' value='".$agencia_code."'>  ";                     
                   
                    if ( $revisor_code>1 ) 
                      {
                        $query_revisores=mysqli_query($conn, "SELECT nombre_completo from pani_usuarios where id=$revisor_code");
                        if (!$query_revisores) {  echo mysqli_error();  }
                        while ($row_revisor=mysqli_fetch_array($query_revisores)) {  $revisor_name=$row_revisor['nombre_completo'];  }

                        $revisor_txt='<label style="color:green;">'.$revisor_name.'</label>'; 
                        echo $revisor_txt."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a role='button' class='btn btn-danger btn-sm' onclick='quitar_revisor(".$ontador.", ".$_remesa.", ".$agencia_code." )'><i class='far fa-trash-alt'></i></a>";
                      }
                      else
                      {
                        echo"<a role='button' class='btn btn-success btn-sm' onclick='add_revisor(".$ontador.", 1)' data-toggle='modal' href='#myModal'><i class='far fa-thumbs-up'></i> Asignar Revisor</a>";                       
                      }
                     
              echo "</td></tr>";
          $ontador++;
    }
    echo "</body></table></div>";
    echo "<div class='col-md-1'></div></div>";

 ?>
  <!-- Modal -->
                  <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog modal-md">
                      <div class="modal-content">
                            <div class="modal-header success"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Seleccione el Revisor a Asignar</h4></div>
                            <div class="modal-body">
                             <div class="row">
                               <div class="col col-sm-2"><label>Revisor</label></div>
                               <div class="col col-sm-10">
                               <input type='hidden' id='fecha'   >
                               <input type='hidden' id='age'   >
                               <input type='hidden' id='remesa_modal' value='<?php echo $_remesa; ?>'  >
                                 <select class="form-control" id="slctincidencia"  required name='slctincidencia'><option>Seleccione uno</option>
                                            <?php 
                                                $query_incidencias=mysqli_query($conn, "SELECT id, nombre_completo from pani_usuarios where areas_id=9 and roles_usuarios_id>1 and id<>99 order by nombre_completo asc");
                                                if ($query_incidencias==true)  {  while ($row_incidencia=mysqli_fetch_array($query_incidencias))   {  echo"<option value=".$row_incidencia['id'].">".$row_incidencia['nombre_completo']."</option>";   } }
                                                else { echo "<option value='99'>Valores no encontrados</option>";   }
                                             ?>
                                    </select>
                               </div>
                             </div>            
                             
                            </div>
                            <div class="modal-footer"><button class="notas_docs btn btn-success" type="button" onclick="update_revisor()" >Generar y guardar</button><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button></div>
                      </div>
                    </div>
                  </div> 
</form>
 
