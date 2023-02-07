<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['usuario'] ;
?>
<style type="text/css"> 
.div_wait {
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

@media print  
{      
        #non-printable { display: none; }
        #printable { display: block; }
        #table_1 { display: block; }        
        #btn-rev { display: none; }
          td, tr { padding-bottom: 0px;   border-spacing: 0; font-family: Arial; font-size: 07pt;    } 
         body  { font-family: Arial; font-size: 07pt; }
}   

    .Custom_Cancel > .sa-button-container > .cancel {
   background-color: #DD6B55;
   border-color: #DD6B55;
}
.Custom_Cancel > .sa-button-container > .cancel:hover {
   background-color: #DD6B55;
   border-color: #DD6B55;
}
</style>
<link href="https://cdn.jsdelivr.net/sweetalert2/6.4.1/sweetalert2.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/sweetalert2/6.4.1/sweetalert2.js"></script> 
<script type="text/javascript">
 $(document).ready(function ()
 {
      $('#datetimepicker1').datetimepicker();
      $('#datetimepicker2').datetimepicker();           
  });



function llenar_tabla() {   document.getElementById('seleccionar').disabled=true;  document.getElementById('slctremesa').disabled = true;  document.getElementById('agencia').disabled = true;  }

function add_remesa(valor, acc )
{

  var result=valor.split('--');
  var remesa= parseInt(result[0]);
  var agencia=         result[1];
  var agencia_txt =    result[2]; 
  var fecha_up =       result[3]; 
  var ano_remesa =     result[4]; 
  var user=   <?php echo json_encode($usuario_id);?>;

 
    if (acc==1) 
      {
            swal({
              title: "¿Está Seguro?",
              text:  "¡Esta a punto de AGREGAR a la remesa "+remesa +" del año "+ ano_remesa+ ", los pagos de la agencia " + agencia_txt + " !",
              showCancelButton: true,
              cancelButtonColor: '#d33',  
              reverseButtons: true,
              confirmButtonColor: '#28a745', 
              type: 'question',              
            })
            .then(() => {  
             $(".div_wait").fadeIn("fast");  
             var urr_update_serie = '_pp_add_remesa_mayor.php?remesa='+remesa+ '&ano_remesa='+ano_remesa+'&agencia='+agencia+'&usuario='+user+'&acc='+ acc + '&fecha_pago='+ fecha_up +'&al='+Math.random();  
          //   alert(urr_update_serie);            
             $("#table_1").load(urr_update_serie);                       
             swal("Bien!", "Se actualizó correctamente.", "success");
            });                   
      } 
      else
      {


            swal({
              title: "Esta Seguro?",
              text: "Ud. Esta a punto de QUITAR a la remesa "+ remesa +" del "+ano_remesa+" los pagos de la agencia " + agencia_txt + " !",
              showCancelButton: true,
              cancelButtonColor: '#d33',  
              reverseButtons: true,
              confirmButtonColor: '#28a745', 
              type: 'question',              
            })
            .then(() => {
            //  $(".div_wait").fadeIn("fast");  
              var urr_update_serie = '_pp_add_remesa_mayor.php?remesa='+remesa+'&ano_remesa='+ano_remesa+'&agencia='+agencia+'&usuario='+user+'&acc='+ acc + '&fecha_pago='+ fecha_up +'&al='+Math.random(); 
            //  alert(urr_update_serie);
              $("#table_1").load(urr_update_serie);
              swal("Bien!", "Se actualizó correctamente.", "success");                      
             
            });   

 
      }
}

     function justNumbers(e)
     {
            var keynum = window.event ? window.event.keyCode : e.which;
            if ((keynum == 8) || (keynum ==47 ))
            return true;            
            return /\d/.test(String.fromCharCode(keynum));
     }
</script>

<div id="div_wait" class="div_wait"></div>
<form method="post" id="_revision_premios"  class="" name="_revision_premios">
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Recepción de Remesas de Lotería Mayor</h3> <br></section>
<section>
<a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS  <i class="far fa-hand-point-down fa-lg"></i></a>
    <div class="collapse " id="collapse1" align="center">
      <div class="card">
        <div class="card card-body" id="non-printable">
          <div class="input-group" style="margin:10px 0px 10px 0px; width: 70%">
            <div class = "input-group-prepend"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i>   &nbsp; Fecha de Pago: </span></div> 
             <?php 
              if (isset($_GET['fecha_pago'])) 
              {
            ?>
                <input type='date' id ="fecha_i" name = "fecha_inicial" class="form-control" id ="dt1" value="<?php echo $_GET['fecha_pago']; ?>" readonly='true'>            
            <?php 
              }
              else
              {
            ?>
                <input type='date' id ="fecha_i" name = "fecha_inicial" class="form-control" id ="dt1">            
            <?php 
              }
             ?>         
            <div class="input-group-prepend" style="margin-left: 5px;" ><span  class="input-group-text">Remesa: </span></div>
            <input type='text' min='35' name="remesa" id="remesa" class="form-control input-lg" required onkeypress="return justNumbers(event)" maxlength="4">
            <div class="input-group-prepend" style="margin-left: 5px;" ><span  class="input-group-text">Año Remesa: </span></div>
            <input type='text' min='35' name="ano_remesa" id="ano_remesa" class="form-control input-lg" required onkeypress="return justNumbers(event)" maxlength="4">
            <button type="submit" name="seleccionar" style="margin-left: 10px;" class="btn btn-primary" value = "Seleccionar">  Seleccionar &nbsp;<i class="fas fa-search fa-lg"></i></button>
          </div>
        </div>
      </div>
    </div>
 <hr>
<div class='form-group'  id='table_1' >
  <?php  
  if (isset($_POST['seleccionar']))
  {     
      $_fecha_pago=$_POST['fecha_inicial'];  $_remesa=$_POST['remesa'];   $_ano_remesa=$_POST['ano_remesa'];
            echo  "<div class='row' >                             
                        <div class='col-md-1' align='center'></div>
                          <div class='col-md-10' align='center'><legend><div class='alert alert-info'>Información de Billetes Pagados en fecha ".$_fecha_pago." con número de remesa No. ".$_remesa." del año ".$_ano_remesa.".</div></legend>
                                  <div class='table-responsive' style='padding-bottom: 0px; page-break-after: always;'>
                                      <table class='table table-hover table-sm table-bordered'   id='table'>
                                            <thead><tr><th align='center'>Agencia</th> 
                                                       <th align='center'>Cantidad</th>
                                                       <th align='right'>Principal</th>
                                                       <th align='right'>Impto</th>                
                                                       <th align='right'>Neto</th>                    
                                                       <th id='btn-rev'></th></tr></thead><tbody>";
                                                  
                         $result = mysqli_query($conn, "SELECT  b.transactionagency,  b.transactionagencyname seccional,  sum(a.decimos) conteo, sum(a.totalpayment) total, sum(a.imptopayment) impto,  sum(a.netopayment) neto
                                                FROM mayor_pagos_detalle a, mayor_pagos_recibos b
                                                WHERE  a.transactioncode=b.transactioncode AND 
                                                       a.transactionstate IN (1,3) AND 
                                                       (a.remesa=0 OR a.remesa is NULL)  AND
                                                       date(a.transactiondate) ='$_fecha_pago'
                                                GROUP BY  b.transactionagency  ORDER BY b.transactionagency ASC");
                          $contador=0;
                      if (mysqli_num_rows($result)>0)
                        {
                          $acumulado_neto=0; $contador=0; $acumulado_total=0; $acumulado_impto=0; $contador_billetes=0;
                          while ($row = mysqli_fetch_array($result)) 
                          {  
                             $acumulado_total=$row['total']+$acumulado_total;  $acumulado_impto=$row['impto']+$acumulado_impto;  $acumulado_neto=$row['neto']+$acumulado_neto; $contador_billetes= $contador_billetes+ $row['conteo'];
                            echo "<tr><td align='left'>".$row['transactionagency']."--".$row['seccional']."</td>
                                      <td align='center'>".$row['conteo']."</td>
                                      <td align='right'>".number_format($row['total'],2,'.',',')."</td>
                                      <td align='right'>".number_format($row['impto'],2,'.',',')."</td>
                                      <td align='right'>".number_format($row['neto'],2,'.',',')."</td>
                                      <td align= 'center' id='btn-rev'>
                                        <button type='button' name='option1".$contador."' id='option1".$contador."' onclick='add_remesa(this.value, 1)'  value='".$_remesa."--".$row['transactionagency']."--".$row['seccional']."--".$_fecha_pago."--".$_ano_remesa."' class='btn btn-sm btn-success'><i class='far fa-thumbs-up fa-lg'></i>  Agregar a la Remesa</button>                                   
                                      </td></tr></tbody>";
                                      $contador ++;     
                          }
                          echo "<tr class='table-info'>
                                        <td align='center'>Total de Billetes</td>
                                        <td align='center'>".$contador_billetes."</td>
                                        <td align='right'> L. ".number_format($acumulado_total,2,'.',',')."</td>
                                        <td align='right'> L. ".number_format($acumulado_impto,2,'.',',')."</td> 
                                        <td align='right'> L. ".number_format($acumulado_neto,2,'.',',')."</td>
                                        <td id='btn-rev'></td></tr>";                       
                        }  
                        else
                        {
                            // echo mysqli_error();  
                             echo  "<tr class='table-danger'><td colspan='8' align='center'> No existen pagos pendientes de remesa para esta fecha!<td></tr></tbody>";    
                        }  

              echo "</table></div></div><div class='col-md-1' align='center'></div></div>";
             
                    echo  "<div class='row'>                             
                        <div class='col-md-1' align='center'></div>
                          <div class='col-md-10' align='center'><legend><div class='alert alert-success'>Información Seleccionada para la Remesa No. ".$_remesa." </div></legend> 
                                  <div class='table-responsive' style='padding-bottom: 0px; page-break-after: always;'>
                                      <table class='table table-hover table-sm table-bordered'   id='table'>
                                            <thead><tr><th align='center'>Agencia</th> 
                                                       <th align='center'>Cantidad</th>
                                                       <th align='right'>Principal</th>
                                                       <th align='right'>Impto</th>                
                                                       <th align='right'>Neto</th>                    
                                                       <th id='btn-rev'></th></tr></thead><tbody>";

                            $result_remesados = mysqli_query($conn,"SELECT  b.transactionagency,  b.transactionagencyname seccional,  sum(a.decimos) conteo, sum(a.totalpayment) total, sum(a.imptopayment) impto,  sum(a.netopayment) neto
                                                FROM mayor_pagos_detalle a, mayor_pagos_recibos b 
                                                WHERE  a.transactioncode=b.transactioncode AND 
                                                       a.transactionstate IN (1,3) AND 
                                                      date(a.transactiondate) ='$_fecha_pago' AND
                                                      a.remesa=$_remesa and 
                                                      a.ano_remesa = '$_ano_remesa' 
                                                GROUP BY  b.transactionagency  ORDER BY  b.transactionagency ASC");

                                if (mysqli_num_rows($result_remesados)>0)
                                  {
                                    $acumulado_neto_remesados=0; $contador_remesados=0; $acumulado_total_remesados=0; $acumulado_impto_remesados=0; $contador_billetes_remesados=0;  
                                    while ($row_remesados = mysqli_fetch_array($result_remesados)) 
                                    {  
                                       $acumulado_total_remesados=$row_remesados['total']+$acumulado_total_remesados;  $acumulado_impto_remesados=$row_remesados['impto']+$acumulado_impto_remesados;  $acumulado_neto_remesados=$row_remesados['neto']+$acumulado_neto_remesados;  $contador_billetes_remesados= $contador_billetes_remesados+ $row_remesados['conteo'];

                                      echo "<tr><td  align='left'>".$row_remesados['transactionagency']." -- ".$row_remesados['seccional']."</td>
                                                <td  align='center'>".$row_remesados['conteo']."</td>
                                                <td  align='right'>".number_format($row_remesados['total'],2,'.',',')."</td>
                                                <td  align='right'>".number_format($row_remesados['impto'],2,'.',',')."</td>
                                                <td  align='right'>".number_format($row_remesados['neto'],2,'.',',')."</td>
                                                <td  align='center'  id='btn-rev'>  
                                                <button type='button' name='option1".$contador."' id='option1".$contador."' onclick='add_remesa(this.value, 2)'  value='".$_remesa."--".$row_remesados['transactionagency']."--".$row_remesados['seccional']."--".$_fecha_pago."--".$_ano_remesa."' class='btn btn-sm btn-danger'><i class='far fa-thumbs-down fa-lg'></i>  Quitar a la Remesa</button>       
                                                </td></tr></tbody>";
                                                $contador_remesados ++;    
                                     }                                      
                                      echo "<tr class='table-success' ><td  align='center'>Total de Billetes Agregados a la Remesa</td>
                                                <td align='center'>".$contador_billetes_remesados."</td>
                                                <td align='right'> L. ".number_format($acumulado_total_remesados,2,'.',',')."</td><td align='right'> L. ".number_format($acumulado_impto_remesados,2,'.',',')."</td> 
                                                <td align='right'> L. ".number_format($acumulado_neto_remesados,2,'.',',')."</td><td id='btn-rev'></td></tr></table>";   
                                  }  
                                  else
                                  {     
                                    
                                      echo  "<tr class='table-success'><td colspan='8' align='center'>No se han ingresado valores a la remesa<td></tr></tbody>";    
                                   } 

                        

}


?>
</section>
</div>
</form>

 