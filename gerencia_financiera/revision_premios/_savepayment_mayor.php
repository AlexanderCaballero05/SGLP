<?php 
require('../../template/header.php'); 

if (!isset($_SESSION['sesion'])) 
{ 
  ?>
    <script type="text/javascript">
    alert("La sesion se ha cerrado");
    </script>
  <?php 
  header('Location: ../index.php'); 
}
else
{
    if ($_SESSION['flag_valida']===1) 
    { 
    if (isset($_POST['savedata'])) 
    {      
        $ip              =    '1';
        $fisicalname     =    'PANI-PAGO-ESPECIES';
        $terminal        =    'PANI-PAGO-ESPECIES';
        $cod_seccional   =    '999';
        $agencia         =    'PANI-PAGO-ESPECIES';
        $usuario         =    $_SESSION['usuario'];              
        $nombre_usuario  =    $_SESSION['nombre_usuario'];
        $_idganador      =    $_POST['txtid'];
        $_nombreganador  =    $_POST['txtnombre'];  
        $_monto_total    =    $_POST['textacumulado_total'];
        $_monto_impto    =    $_POST['textacumulado_impto'];      
        $_monto_neto     =    $_POST['textacumulado_neto'];

          $_uniquekey=array();          $_uniquekey=$_POST['rbo_key'];
          $conteo_registros=count($_uniquekey);
          $_detalle_venta=array();      $_detalle_venta=$_POST['rbo_detalle_venta'];
          $_sorteo=array();             $_sorteo=$_POST['rbo_sorteo'];
          $_register=array();           $_register=$_POST['rbo_registro']; 
          $_num=array();                $_num=$_POST['rbo_numero'];
          $_dec=array();                $_dec=$_POST['rbo_decimos'];
          $_mto_total=array();          $_mto_total=$_POST['rbo_monto_total'];
          $_mto_impto=array();          $_mto_impto=$_POST['rbo_impto'];
          $_mto_pagar=array();          $_mto_pagar=$_POST['rbo_valor_pagar']; 
          $_tipo_premio=array();        $_tipo_premio=$_POST['rbo_tipo_premio']; 

           $query_id=mysqli_query($conn,"SELECT LAST_INSERT_ID(id+1) id FROM mayor_pagos_recibos order by id desc limit 1;");

           if (!$query_id) { echo mysqli_error(); }
           while ($row_id=mysqli_fetch_array($query_id)) {  $lastid=$row_id['id'];  }             
           $_transactionsoftlot= $cod_seccional.$lastid;
           $_corecode=$_transactionsoftlot;   
        

        $query_recibo="INSERT INTO mayor_pagos_recibos( transactioncode, transactioncore, transactionip, transactionphisicalname, transactionagency, transactionagencyname,  transactioncajero, transactionuser, transactionusername, transactionwinnerid, transactionwinnername, totalpayment, imptopayment, netopayment, transactionstate)VALUES ($_transactionsoftlot, '$_corecode', '$ip', '$fisicalname', $cod_seccional, '$agencia',  '$terminal', '$usuario',  '$nombre_usuario','$_idganador', '$_nombreganador', $_monto_total, $_monto_impto, $_monto_neto, 1 )";

        $query_recibo_banco=mysqli_query($conn,$query_recibo);
        $ano_actual=date("Y");
        $fecha_actual_recepcion=date("Y-m-d");
        if ($query_recibo_banco===true)
          {             
              $contadorbanrural=0;
              while ($contadorbanrural<$conteo_registros) 
              {
                $query_detalle = "INSERT INTO mayor_pagos_detalle(transactioncode, transactioncore,  sorteo, registro, numero, decimos, totalpayment, imptopayment, netopayment, vendedor, tipo_premio, ano_remesa, fecha_recepcion_banco) VALUES ($_transactionsoftlot, '$_corecode',  $_sorteo[$contadorbanrural], $_register[$contadorbanrural], $_num[$contadorbanrural], $_dec[$contadorbanrural], $_mto_total[$contadorbanrural], $_mto_impto[$contadorbanrural], $_mto_pagar[$contadorbanrural], '$_detalle_venta[$contadorbanrural]', '$_tipo_premio[$contadorbanrural]', '$ano_actual', '$fecha_actual_recepcion' );";

              //  echo $query_detalle;
                $query_detalle_banco=mysqli_query($conn,$query_detalle);
                if (!$query_detalle_banco) 
                {
                  echo "Error en el detalle del banco".mysqli_error();
                }
                else
                {
                  
                  $text_recibo= '<div class="alert alert-success" role="alert"> Pago realizada exitosamente factura : '.$_transactionsoftlot.'</div>';
                  $_SESSION['cod_impresion'] = $_transactionsoftlot;
                    ?>
                  <script type="text/javascript">
                    swal({
                      title: "",
                        text: "Pago Realizado Exitosamente!.",
                        type: "success" 
                      })  
                      .then(function(result){
                          window.open('_print_recibo_pago_mayor_especies.php', '_blank');
                        });
                  </script>
                  <?php 
                }
                
                $contadorbanrural++;
              }
            echo $text_recibo;
          


          }
          else
          {
             echo "HAY ERROR AL GUARDAR EL RECIBO DEL BANCO" . mysqli_error($query_recibo_banco);
          } 
                
    }
    $_SESSION['flag_valida']=0;;
  }
  else
  {
     echo "<div class='alert alert-warning'><strong>Atencion!</strong> Debe Ingresar a la Pantalla de pagos nuevamente si desea guardar un nuevo registro</div>"; 
  }

}




 ?>