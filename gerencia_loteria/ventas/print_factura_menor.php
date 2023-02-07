<?php
require('../../template/header.php');


if (isset($_GET['c'])) {
$cod_factura = $_GET['c'];
}else{
$cod_factura = $_SESSION['cod_impresion'];	
}

$factura =  mysqli_query($conn,"SELECT fecha_venta, identidad_comprador, total_neto, id_seccional, id_sorteo, identidad_comprador, nombre_comprador, cantidad, id_entidad, b.nombre_empresa, c.nombre , id_usuario FROM transaccional_ventas as a INNER JOIN empresas as b INNER JOIN fvp_seccionales as c ON a.id_entidad = b.id AND a.id_seccional = c.id WHERE cod_factura = $cod_factura ");

if ($factura === FALSE) {
echo mysqli_error();
}

$ob_factura = mysqli_fetch_object($factura);
$fecha_venta = $ob_factura->fecha_venta;
$identidad = $ob_factura->identidad_comprador;
$total = $ob_factura->total_neto;
$id_seccional = $ob_factura->id_seccional;
$id_sorteo = $ob_factura->id_sorteo;
$identidad_comprador = $ob_factura->identidad_comprador;
$nombre_comprador = $ob_factura->nombre_comprador;
$cantidad_factura = $ob_factura->cantidad;
$id_entidad = $ob_factura->id_entidad;
$entidad = $ob_factura->nombre_empresa;
$seccional = $ob_factura->nombre;
$id_usuario = $ob_factura->id_usuario;

$info_sorteo = mysqli_query($conn,"SELECT * FROM sorteos_menores WHERE id = '$id_sorteo' ");
$ob_sorteo = mysqli_fetch_object($info_sorteo);
$fecha_captura = $ob_sorteo->fecha_sorteo;




$usuario_factura =  mysqli_query($conn,"SELECT nombre_usuario FROM fvp_usuarios WHERE id = $id_usuario ");

 

$ob_usuario_factura = mysqli_fetch_object($usuario_factura);
$nombre_usuario = $ob_usuario_factura->nombre_usuario;
 

$info_sorteo = mysqli_query($conn,"SELECT * FROM sorteos_menores WHERE id = '$id_sorteo' ");
$ob_sorteo = mysqli_fetch_object($info_sorteo);
$fecha_captura = $ob_sorteo->fecha_sorteo;


?>







<br>

<div class="card"  style="margin-right: 10px; margin-left: 10px">

<div class="card-header" align="center">


PANI HONDURAS<br>
FACTURA DE VENTA DE LOTERIA MENOR<br><br>
ENTIDAD: 
<?php 
echo $entidad;
?>
<br>

Sorteo: <?php echo $id_sorteo;?> 
a jugarse el <?php echo $fecha_captura;?><br><br>

</div>

<div  class = card-body>


<table class="table table-bordered" style="font-size:10px">
    <tr>
        <th>FACTURA</th>
        <th>USUARIO</th>
        <th>NOMBRE USUARIO</th>
        <th>ID COMPRADOR</th>
        <th>NOMBRE COMPRADOR</th>
        <th>CANTIDAD</th>
        <th>TOTAL LPS.</th>
        <th>FECHA DE VENTA</th>
    </tr>
    <tr>
        <td><?php echo $cod_factura; ?></td>
        <td><?php echo $id_usuario; ?></td>
        <td><?php echo $nombre_usuario; ?></td>
        <td><?php echo $identidad_comprador; ?></td>
        <td><?php echo $nombre_comprador; ?></td>
        <td><?php echo number_format($cantidad_factura); ?></td>
        <td><?php echo number_format($total,2); ?></td>
        <td><?php echo $fecha_venta; ?></td>
    </tr>    
</table>

<table  class="table table-bordered">

<tr>
    <th colspan="4" style="align:center">DETALLE DE LOTERIA MENOR VENDIDA</th>
</tr>

	<tr>
		<th>Numero</th>
		<th>Serie Inicial</th>
		<th>Serie Final</th>
		<th>Cantidad</th>
	</tr>
	<?php 


$total_numeros = 0;

$numeros_vendidos = mysqli_query($conn,"SELECT a.numero FROM `fvp_detalles_ventas_menor` as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE a.cod_factura = '$cod_factura' GROUP BY a.numero ORDER BY COUNT(numero)  DESC ");



while ($reg_numeros = mysqli_fetch_array($numeros_vendidos)) {
$num = $reg_numeros['numero'];

$venta_por_serie = mysqli_query($conn,"SELECT a.serie FROM `fvp_detalles_ventas_menor` as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE a.cod_factura = '$cod_factura' AND numero = '$num'  ORDER BY serie ASC");

$v = 0;
while ($registro = mysqli_fetch_array($venta_por_serie)) {
$v_numeros[$v] = $num;
$v_series[$v] = $registro['serie'];
$v++;
};


$v = 0;
$s = 0;
$n = 0;

$v_numero_i[$s] = $num;
$v_serie_i[$s] = $v_series[$v];
$v_serie_f[$s] = $v_series[$v];


while (isset($v_series[$v])) {
    
    if (isset($v_series[$v + 1])) {

        if ($v_series[$v] + 1 == $v_series[$v + 1]) {
        $v_serie_f[$s] = $v_series[$v + 1];
        
        }else{

        $v_serie_f[$s] = $v_series[$v];

        $s++;

        $v_serie_i[$s] = $v_series[$v + 1];  
        $v_serie_f[$s] = $v_series[$v + 1];
		$v_numero_i[$s] = $v_numeros[$v + 1];

         }

    }else{
    $v_serie_f[$s] = $v_series[$v];  
    $v_numero_i[$s] = $v_numeros[$v];  
    $s++;
    }


$v++;
}

$s = 0;
while (isset($v_serie_i[$s])) {
$cant = $v_serie_f[$s] - $v_serie_i[$s] + 1;
echo "<tr>";
echo "<td>".$v_numeros[$s]."</td>";
echo "<td>".$v_serie_i[$s]."</td>";
echo "<td>".$v_serie_f[$s]."</td>";
echo "<td>".$cant."</td>";
echo "</tr>";

$total_numeros = $total_numeros + $cant;
$s++;
}


unset($v_series);
unset($v_numeros);
unset($v_serie_i);
unset($v_serie_f);

}


	?>
</table>
</div>
</div>

<br>