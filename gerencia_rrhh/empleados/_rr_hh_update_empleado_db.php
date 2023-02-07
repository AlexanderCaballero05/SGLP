<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['usuario'] ;
$usuario_name= $_SESSION['nombre_usuario'];
 
if (  isset($_SESSION['identity']) )
{
    $old_rtn              =  trim($_POST['rtn_info']);
    $old_domicilio        =  trim($_POST['domicilio_info']);
    $old_telefono         =  trim( $_POST['telefono_info']);
    $old_celular          =  trim($_POST['celular_info']);
    $old_estado_civil     =  trim($_POST['estado_civil_info']);
    $old_escolaridad      =  trim($_POST['escolaridad_info']);
 
    $_rtn                = trim($_POST['txtrtn']);
    $_domicilio          = trim($_POST['domicilio']);
    $_telefono           = trim($_POST['txttelefono']);
    $_celular            = trim($_POST['txtcelular']);
    $_estado_civil       = trim($_POST['txtetcivil']);
    $_escolaridad        = trim($_POST['escolaridad']);

$query_update_empleado = "UPDATE rr_hh_empleados SET rtn='$_rtn' , domicilio='$_domicilio' , telefono = '$_telefono' , celular='$_celular' , estado_civil= '$_estado_civil' , escolaridad ='$_escolaridad';";

$query_insert_audit    = "INSERT INTO rr_hh_empleados_audit (user_mod, old_rtn, old_domicilio, old_telefono, old_celular, old_estado_civil, old_escolaridad) VALUES('$usuario_name', '$old_rtn' ,  '$old_domicilio' ,  '$old_telefono' , '$old_celular' , '$old_estado_civil' , '$old_escolaridad');";

        if (mysqli_query($conn,$query_update_empleado)) 
        {
            if (mysqli_query($conn,$query_insert_audit)) 
            {
                      echo "<div class='alert alert-success'> Se ha registrado el empleado correctamente </div>";
                       ?> 
                            <script type="text/javascript">                             
                                    swal({
                                      title: "",
                                        text: "Empleado agregada Exitosamente!.",
                                        type: "success" 
                                      })  
                                      .then(function(result){
                                          window.close();
                                        });
                            </script>  
                        <?php 
            }
            else
            {
                echo "<div class='alert alert-danger'> Error en audit : ".mysqli_error($conn)." </div>";                
            }

        }
        else
        {        
          echo "<div class='alert alert-danger'> Error en empleados: ".mysqli_error($conn)." </div>";
        }
        unset( $_SESSION['identity']);
}
else
{
	echo "<div class='alert alert-danger'> Debes volver a realizar la busqueda del empleado</div>";
}


 
  
?>