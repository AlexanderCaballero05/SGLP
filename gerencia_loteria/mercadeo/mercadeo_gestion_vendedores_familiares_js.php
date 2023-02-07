<?php
ob_start();
session_start();
$usuario = $_SESSION['usuario'];


require('../../conexion.php');

$accion = $_GET["accion"];
$identidad_vendedor = $_GET["id_vendedor"];


if ($accion == "CONSULTA") {
    
$consulta_familiares = mysqli_query($conn, "SELECT * FROM vendedores_familiares WHERE identidad_vendedor = '$identidad_vendedor' AND estado_familiar = 'ACTIVO' ");


if (mysqli_num_rows($consulta_familiares) > 0) {
    
echo "<table class = 'table table-bordered'>";
echo "<tr>";
echo "<th colspan = '7' >FAMILIARES REGISTRADOS</th>";
echo "</tr>";
echo "<tr>";
echo "<td>Identidad</td>";
echo "<td>Nombre</td>";
echo "<td>Telefono</td>";
echo "<td>Relación</td>";
echo "<td>Acción</td>";
echo "</tr>";

while ($reg_familiares = mysqli_fetch_array($consulta_familiares)) {
$edad = 0;


    $id_familiar = $reg_familiares['id']; 
    echo "<tr>";
    echo "<td>".$reg_familiares['identidad_familiar']."</td>";
    echo "<td>".$reg_familiares['nombre_familiar']."</td>";
    echo "<td>".$reg_familiares['telefono_familiar']."</td>";
    echo "<td>".$reg_familiares['relacion']."</td>";
    ?>
    <td><a class="btn btn-danger text-white" onclick="eliminar_familiar('<?php echo $id_familiar; ?>', '<?php echo $identidad_vendedor; ?>')" >x</a></td>
    <?php
    echo "</tr>";
}

echo "</table>";

}else{
echo "<div class = 'alert alert-info'>Aun no se han registrado familiares para este vendedor.</div>";
}



////////////////////////////////////////
////////////// GUARDAR /////////////////
}elseif ($accion == "GUARDAR") {

    $nombre_vendedor = $_GET['nombre_vendedor'];
    $nombre_vendedor = str_replace('!', ' ', $nombre_vendedor);

    $nombre_familiar = $_GET['nombre_familiar'];
    $nombre_familiar = str_replace('!', ' ', $nombre_familiar);


    $identidad_familiar = $_GET['identidad_familiar'];
    $identidad_familiar = str_replace('-','',$identidad_familiar);
    
    $telefono_familiar = $_GET['telefono_familiar'];
    $relacion_familiar = $_GET['relacion_familiar'];
 

    if (mysqli_query($conn, "INSERT INTO vendedores_familiares (identidad_vendedor, identidad_familiar, nombre_familiar,  telefono_familiar , estado_familiar, id_usuario , relacion  ) VALUES ('$identidad_vendedor', '$identidad_familiar','$nombre_familiar','$telefono_familiar','ACTIVO','$usuario', '$relacion_familiar') ") === TRUE) {

        ?>
        <script type="text/javascript">
        
        cargar_familiares('<?php echo $identidad_vendedor; ?>', '<?php echo $nombre_vendedor; ?>' , 'CONSULTA');        
        
        </script>
        <?php

    }else{        

        ?>
        <script type="text/javascript">
        alert("Error inesperado, por favor vuelva a intentarlo.");        
        </script>
        <?php
        
    }



////////////// GUARDAR /////////////////
////////////////////////////////////////
////////////// ELIMINAR ////////////////
}elseif ($accion == "ELIMINAR") {


    $c_info_vendedor = mysqli_query($conn, "SELECT * FROM vendedores WHERE identidad = '$identidad_vendedor' ");
    $ob_identidad_vendedor = mysqli_fetch_object($c_info_vendedor);
    $nombre_vendedor = $ob_identidad_vendedor->nombre;

    
    $id_familiar = $_GET['id_familiar'];

    if (mysqli_query($conn, "UPDATE  vendedores_familiares SET estado_familiar = 'INACTIVO' WHERE id = '$id_familiar' ") === TRUE) {

        ?>
        <script type="text/javascript">
        
        cargar_familiares('<?php echo $identidad_vendedor; ?>', '<?php echo $nombre_vendedor; ?>' , 'CONSULTA');        
        
        </script>
        <?php

    }else{        

        ?>
        <script type="text/javascript">
        alert("Error inesperado, por favor vuelva a intentarlo.");        
        </script>
        <?php
        
    }


}
////////////// GUARDAR /////////////////
////////////////////////////////////////



?>