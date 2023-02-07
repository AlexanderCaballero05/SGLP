<?php 
require("../../conexion.php");

$fecha=$_GET['fecha']; $remesa=$_GET['remesa'];    $est=$_GET['est'];

if (mysqli_query($conn, "UPDATE rp_asignacion_agencias_revisor_menor SET usuario_revision=null  WHERE  date(transactiondate)='$fecha' AND remesa=$remesa ")==true) 
{
    $_remesa=$remesa; 

    if ($est==1) 
    {
          $query_agencias_fecha=mysqli_query($conn, "SELECT date(transactiondate) fecha, count(transactionagency) agencia, sum(cant_numeros) sum_numeros, sum(totalpayment) total, sum(imptopayment) impto, sum(netopayment)  neto
                                                     FROM rp_asignacion_agencias_revisor_menor a
                                                     WHERE remesa=$_remesa GROUP BY fecha ORDER BY fecha_recepcion ASC");
    }
    else
    {
          $query_agencias_fecha=mysqli_query($conn, "SELECT date(transactiondate) fecha, count(transactionagency) agencia, sum(cant_numeros) sum_numeros, sum(totalpayment) total, sum(imptopayment) impto, sum(netopayment)  neto
                                                     FROM rp_asignacion_agencias_revisor_menor a 
                                                     WHERE remesa=$_remesa AND date(transactiondate)='$fecha' GROUP BY fecha ORDER BY fecha_recepcion ASC");
    }  

    echo "<div class='alert alert-success' role='alert'><h3>Asignación de Loteria Menor Remesa ".$_remesa."</h3></div><hr>
                 <div class='table-responsive'>
                    <table class='table table-hover table-sm'>
                       <thead><tr><th>Fecha | pago</th>
                                  <th>Agencias</th>
                                  <th>Billetes</th>
                                  <th>Neto pagado</th>
                                  <th></th>
                              </tr></thead><tbody>";


    $ontador=0;
      while ( $row_agencias_dia=mysqli_fetch_array($query_agencias_fecha)  )
    {
       $fecha=$row_agencias_dia['fecha'];  $agencias=$row_agencias_dia['agencia'];  $totalnumeros=$row_agencias_dia['sum_numeros'];  $total_fecha=$row_agencias_dia['total'];  $impto_fecha=$row_agencias_dia['impto'];  $neto_fecha=$row_agencias_dia['neto'];  
        echo "<tr><td>".$fecha."</td>
                  <td>".$agencias."</td>
                  <td>".$totalnumeros."</td>
                  <td>".number_format($neto_fecha,2,'.',',')."</td>
                  <td align='left'><input type='hidden' id='txt".$ontador."' value='".$fecha."'>";                     
                    $query_user_asignado=mysqli_query($conn, "SELECT substring(b.nombre_completo, 1, 10) nombre_completo FROM `rp_asignacion_agencias_revisor_menor` a, pani_usuarios b WHERE a.usuario_revision=b.id and remesa=$_remesa and date(transactiondate)='$fecha' group by usuario_revision");                      
                    if ( mysqli_num_rows( $query_user_asignado )===0 ) 
                      { 
                         echo"<a role='button' class='btn btn-success btn-sm'  onclick='add_revisor(".$ontador.", 1)'  data-toggle='modal' href='#myModal'><i class='far fa-thumbs-down'></i> Asignar Revisor</a>
                              <a role='button' class='btn btn-primary btn-sm' target='blank' href='_rp_asignacion_agencias_menor_fechas_agencia.php?remesa=".$_remesa."&fecha_pago=".$fecha."'><i class='far fa-thumbs-up'></i> Distribuir Detalle</a>";
                      }
                      else if (mysqli_num_rows( $query_user_asignado )===1 ) 
                      {       
                         while ($row_user_asignado=mysqli_fetch_array($query_user_asignado) ) {   $revisor='<span style="color:green;">'.$row_user_asignado['nombre_completo'].'</span>';   }
                         echo   $revisor.'</span>&nbsp;&nbsp;&nbsp;'."<a role='button' class='btn btn-danger btn-sm' onclick='quitar_revisor(".$ontador.", ".$_remesa.")'><i class='far fa-trash-alt'></i>  </a>";
                      }
                      else if (mysqli_num_rows( $query_user_asignado )>1 ) 
                      {
                            $pleca=''; $revisor='';
                            while ($row_user_asignado=mysqli_fetch_array($query_user_asignado) )  {  $revisor=$revisor.$pleca.$row_user_asignado['nombre_completo'];   $pleca=' / ';   }
                            echo '<span class="label label-success" >'.$revisor.'</span>&nbsp;&nbsp;&nbsp;';
                            echo "<a role='button' class='btn btn-primary btn-sm' target='blank' href='_rp_asignacion_agencias_menor_fechas_agencia.php?remesa=".$_remesa."&fecha_pago=".$fecha."'><i class='far fa-thumbs-up'></i> Distribuir Detalle</a>";
                      }
                      else
                      {
                        echo $revisor;
                      }
                     
              echo "</td></tr>";
          $ontador++;
    }
    
    echo "</tbody></table></div></div></div>";
}
else
{
 echo  mysql_error();
}

   ?>
  <script type="text/javascript">
   $(".div_wait").fadeOut("fast");
 </script>
<?php



 ?>