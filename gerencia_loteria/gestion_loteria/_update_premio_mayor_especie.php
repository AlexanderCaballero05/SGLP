<?php 

require('../../template/header.php');

  $usuario_id=$_SESSION['id_usuario'];
  $query_user=mysql_query("SELECT  codigo_empleado nombre_completo FROM banrural_usuarios WHERE id=$usuario_id;");  
  while ($row_user=mysql_fetch_array($query_user))  {  $nombre_usuario=$row_user['nombre_completo']; }
  mysql_free_result($query_user);


  $time=mysql_query("SELECT CURRENT_TIMESTAMP() fecha_hora;");
  $time_object=mysql_fetch_object($time);
  $fecha_hora = $time_object ->fecha_hora;

 
 
?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="widtd=device-width, initial-scale=1">
  <title>PANI | PAGO DE PREMIOS LOTERIA MENOR</title>     
       
<link href="./dates/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css">
<script src="./dates/moment.min.js"></script>
<script src="./dates/bootstrap-datetimepicker.min.js"></script>
 
<style type="text/css"> 
   @media print    {
        #non-printable { display: none; }
        #printable { display: block; }
        #save_data { display: none;  }
        #btn-rev { display: none; }
    }  
    th, td { padding-bottom: 0px;  border-spacing: 0; } ; 
</style>
</head>
<body >
<form method="post"   >
 

<?php echo "G_LOT ".$fecha_hora ?>


             
<div  style="padding-bottom: 0px;  border-spacing: 0;" ><h2 align = "center" >Mantenimiento de precios de premios en especies</h2><hr width="80%"></div>
<div id="non-printable" > 
 
    
     <div class="table table-bordered"><br>
            <div class="row" class="table table-bordered ">
                      <div class="col-md-1" align="center"></div>
                      <div class="col-md-3" align="center">
                          <label class="f" for="dt1" >Sorteo : &nbsp;</label>
                            <select class="form-control" required="true" id='slct_sorteo' name='slct_sorteo'>
                               <option></option>
                               <?php 
                                   $query_sorteos= mysql_query("SELECT a.sorteo, b.fecha_sorteo FROM archivo_pagos_mayor a INNER JOIN sorteos_mayores b on a.sorteo=b.id and  CURRENT_TIMESTAMP() <= b.fecha_vencimiento group by a.sorteo order by a.sorteo desc limit 2 ");
                                   while ($row_sorteo = mysql_fetch_array($query_sorteos)) 
                                   {
                                       $sorteo=$row_sorteo['sorteo'];
                                       $fecha_sorteo=$row_sorteo['fecha_sorteo'];
                                       echo "<option value='".$sorteo."'> ".$sorteo." -- ".$fecha_sorteo."</option>";
                                   }
                               ?>
                            </select>
                      </div>
                      <div class="col-md-2" align="center"><br><input type="submit" name="seleccionar"   id="seleccionar" class="btn btn-primary" style="background-color: #22543F; width:100%;"  value="Seleccionar"></div>           
                      <div class="col-md-6" align="center"></div>
            </div><br><hr>
           
           <div class="row">
              <div class="col-md-1"></div>
              <div class="col-md-10">
                  <div class="table-responsive">
                     <table class="table table-bordered table-hover table-sm">
                       <thead>
                            <tr>
                              <th>Detalle de Venta</th>
                              <th>Sorteo</th>
                              <th>Numero</th>
                              <th>Decimo</th>
                              <th>Registro</th>
                              <th>Total</th>
                              <th>Impto</th>
                              <th>Neto</th>
                              <th></th>
                            </tr>
                       </thead>
                       <tbody>
                        <?php 
                        if (isset($_POST['seleccionar'])) 
                        {
                           $_sorteo=$_POST['slct_sorteo'];

                           $query_info_premio= mysql_query("SELECT id, detalle_venta, sorteo, numero, decimo, registro, total, impto , neto FROM archivo_pagos_mayor WHERE sorteo=$_sorteo and tipo_pago='E' and estado=9 ");
                           while ($row_info_sorteo= mysql_fetch_array($query_info_premio)) 
                           {
                              echo "<input class='form-class' type='hidden' id='txtid' name='txtid' value='".$row_info_sorteo['id']."'>";
                              echo "<input class='form-class' type='hidden' id='txtuser' name='txtuser' value='".$usuario_id."'>";
                              echo "<tr>
                                        <td>".$row_info_sorteo['detalle_venta']."</td>
                                        <td>".$row_info_sorteo['sorteo']."</td>
                                        <td>".$row_info_sorteo['numero']."</td>
                                        <td>".$row_info_sorteo['decimo']."</td>
                                        <td>".$row_info_sorteo['registro']."</td>
                                        <td><input class='form-class' type='text' id='txttotal' name='txttotal' value='".$row_info_sorteo['total']."'></td>
                                        <td><input class='form-class' type='text' id='txtimpto' name='txtimpto' value='".$row_info_sorteo['impto']."'></td>
                                        <td><input class='form-class' type='text' id='txtneto' name='txtneto' value='".$row_info_sorteo['neto']."'></td>
                                        <td><button class='btn btn-primary' id='update_valor' onclick=' update_premio()' name='update_valor' type='button'> Actualizar valor</button></td>
                                    </tr>"  ;
                           }

                        }

                         ?>
                         
                       </tbody>
                     </table>
                  </div>
               </div>
               <div class="col-md-1"></div>
           </div>

           <div class="row">
              <div class="col-md-12" id="msj">
                  
              </div>               
           </div>
   </div><br>         
    
</div><br>
 
  

   
</form><br>
</body>
<script type="text/javascript">

    
      

      function update_premio()
      {
           var urr_cajero = "./_update_valor_premio_mayor_especie.php?id=" + $( '#txtid' ).val() + "&usuario=" + $( '#txtuser' ).val()+ "&total=" + $( '#txttotal' ).val()  + "&impto=" + $( '#txtimpto' ).val() + "&neto=" + $( '#txtneto' ).val();
          $("#msj").load(urr_cajero); 

          $('#txttotal').attr('readonly', true);
          $('#txtimpto').attr('readonly', true);
          $('#txtneto').attr('readonly', true);
          
      }

</script>