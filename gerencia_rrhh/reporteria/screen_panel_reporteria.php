<?php
require('../../template/header.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$gerencia = $v_ruta[2];
$i = 0;
$ma = 0; 
if ($_SESSION["r_".$gerencia][$i]) {
    $v_mayor[$ma] = $_SESSION["r_".$gerencia][$i];
    $ma++;
    } 
    

 
 
 
 
?>

<form method="POST">
        <section style="background-color:#ededed;"><br><h2 align="center" style="color:black;">PANEL DE REPORTERIA, GERENCIA DE RECURSOS HUMANOS</h2><br></section><br> 
        <div class="row"> 
            <div class="col mx-4" > 
                <div  class="alert alert-info" >  
                        <?php 
                                   $i = 0;
                                   while (isset($v_mayor[$i])) 
                                   {
                                        
                                            $v_opcion = explode("%", $v_mayor[$i]);
                                            $pantalla = $v_opcion[0];
                                            $name_p   = $v_opcion[1];                                    
                                            echo '<a href="./'.$pantalla.'" style="width:100%" class="btn btn-success" target="blank">  '.$name_p.'</a><br><br> '; 
                                            $i++;
                                   } 
                        ?> 
                </div> 
            </div> 
        </div> 
</form>

