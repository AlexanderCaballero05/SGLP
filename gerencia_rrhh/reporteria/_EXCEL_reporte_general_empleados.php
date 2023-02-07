<?php
require("../../conexion.php");
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=empleados.xls");
?>
<table>
    <tr><td></td> 
        <td>Identidad</td>
        <td>Nombre</td>
        <td>Celular</td>
		<td>Correo</td>
        <td>Tipo Contratación</td>
        <td>Gerencia</td>
        <td>Deptartamento</td> 
        <td>Puesto</td> 
        <td>Fecha de Ingreso</td>
        <td>Fecha de Nacimiento</td>
    </tr>
            
                    <?php
                    $query_empleados = mysqli_query($conn, "SELECT identidad, nombre_completo, celular, fecha_nacimiento, fecha_ingreso, mail  from rr_hh_empleados ORDER BY nombre_completo ASC");
                    if (mysqli_num_rows($query_empleados)>0) 
                       {
                            $no=1;
                            while ($row_empleados = mysqli_fetch_array($query_empleados)) 
                            { 
                                $_identidad                =  $row_empleados['identidad'];
                                $_nombre_completo          =  $row_empleados['nombre_completo'];
                                $_celular                  =  $row_empleados['celular'];
                                $_fecha_nacimiento         =  $row_empleados['fecha_nacimiento']; 
                                $_fecha_ingreso            =  $row_empleados['fecha_ingreso'];
                                $_mail                     =  $row_empleados['mail'];

                                $query_contratacion = mysqli_query($conn, "SELECT descripcion FROM rr_hh_tipo_contrato_salarios a, rr_hh_mto_contrataciones b WHERE a.tipo_contratacion=b.id and identidad LIKE '".$_identidad."' order by fecha_inicio desc limit 1 ");
                                if (mysqli_num_rows($query_contratacion)>0) {
                                    $info_contratacion  = mysqli_fetch_object($query_contratacion);
                                    $contratacion       = $info_contratacion->descripcion; 
                                } else {
                                    $contratacion       = 'No informado';
                                }
                                

                                $query_gerencia = mysqli_query($conn, "SELECT a.gerenciaid, b.descripcion, a.unidadid, c.descripcion_unidad FROM organizacional_usuarios_gerencias a, organizacional_gerencias b, organizacional_unidades c WHERE a.gerenciaid=b.id and a.unidadid = c.id and a.usuarioid ='".$_identidad."' and a.status=1 order by a.fecha_creacion desc limit 1 ");
                                $info_gerencia  = mysqli_fetch_object($query_gerencia);
                                $gerencia       = $info_gerencia->descripcion; 
                                $unidad       = $info_gerencia->descripcion_unidad; 

                                $query_puesto   = mysqli_query($conn, "SELECT descripcion FROM `organizacional_usuarios_puestos` a, organizacional_puestos b WHERE a.puestoid=b.id and `usuarioid` LIKE '".$_identidad."' order by fecha_creacion desc limit 1 ");
                                $info_puesto    = mysqli_fetch_object($query_puesto);
                                $puesto         = $info_puesto->descripcion; 

                                echo '<tr> <td>'.$no .'</td>   
                                            <td>'.$_identidad .'</td>
                                            <td>'.$_nombre_completo .'</td>
                                            <td>'.$_celular .'</td>
                                            <td>'.$_mail.'</td>  
                                            <td>'.$contratacion.'</td>
                                            <td>'.$gerencia.'</td>
                                            <td>'.$unidad.'</td>
                                            <td>'.$puesto.'</td>
                                            <td>'.date("d-m-Y", strtotime($_fecha_nacimiento)) .'</td>
                                            <td>'.date("d-m-Y", strtotime($_fecha_ingreso)) .'</td></tr>';  
                                $no++;
                            }
                       } 
                    ?>
          
           
</table>  
      
 