<?php 
require("../../conexion.php");

$fecha=$_GET['fecha']; $remesa=$_GET['remesa'];  $usuario_revision=$_GET['revisor']; $est=$_GET['est'];  if (isset($_GET['ag'])) { $agencia_cod=$_GET['ag']; }

$query_update=mysqli_query($conn, "UPDATE rp_asignacion_agencias_revisor_menor set usuario_revision=$usuario_revision  WHERE  date(transactiondate)='$fecha' and remesa=$remesa and transactionagency=$agencia_cod "); 

if ($query_update==true) 
{
  echo "<br><h3><label>Asignación de Loteria Menor Remesa ".$remesa."</label></h3><hr>
        <table class='table table-hover'>
            <thead><tr><th>Agencias</th>
                       <th>Billetes</th>
                       <th>Neto pagado</th>
                       <th></th>
                     </tr></thead><tbody>";

        $_remesa=$remesa;  
        $query_agencias_fecha=mysqli_query($conn,  "SELECT id, transactionagencyname nombre,  transactionagency, cant_numeros, totalpayment total, imptopayment impto, netopayment neto, usuario_revision 
                                                    FROM  rp_asignacion_agencias_revisor_menor
                                                    WHERE  remesa=$remesa and date(transactiondate)='$fecha' order by fecha_recepcion asc");  

    if (!$query_agencias_fecha) { echo mysqli_error(); }

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
                        echo $revisor_txt."&nbsp;&nbsp;&nbsp;&nbsp;<a role='button' class='btn btn-danger btn-md'  onclick='quitar_revisor(".$ontador.", ".$_remesa.", ".$agencia_code." )'><i class='far fa-trash-alt'></i></a>";
                      }
                      else
                      {
                        echo "<a role='button' class='btn btn-success btn-sm' onclick='add_revisor(".$ontador.", 1)' data-toggle='modal' href='#myModal'><i class='far fa-thumbs-down'></i>Asignar Revisor</a>";
                      
                      }
                     
              echo "</td></tr>";
          $ontador++;
    }
    echo "</body></table>";
}
else
{
 echo  mysqli_error();
}




 ?>