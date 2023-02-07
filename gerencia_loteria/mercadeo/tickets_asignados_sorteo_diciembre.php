<?php
require '../../template/header.php';

$id_sorteo = $_GET["s"];
$c_ticket_participantes = mysqli_query($conn, "SELECT * FROM sorteos_menores_incentivos_tickets WHERE id_sorteo = '$id_sorteo'  ");
echo mysqli_error($conn);
?>

<section style="background-color:#ededed;">
<br>
<h3  align="center" style="color:black; "  >LISTADO DE TICKETS ASIGNADOS EN SORTEO <?php echo $id_sorteo; ?></h3>
<br>
</section>
<br>
<br>
<form method="POST">
<?php
if (mysqli_num_rows($c_ticket_participantes) > 0) {
?>
<table class="table table-bordered" >
	<thead>
		<tr>
			<th>IDENTIDAD COMPRADOR</th>
			<th>NOMBRE COMPRADOR</th>
			<th>NUMERO DE TICKET</th>
<!-- 			<th>COMPRA MEDIA</th>-->
<!--			<th>COMPRA EN SORTEO</th>-->
		</tr>
	</thead>

<?php
	while ($reg_tickets = mysqli_fetch_array($c_ticket_participantes)) {
		echo "<tr>";
		echo "<td>" . $reg_tickets["identidad_comprador"] . "</td>";
		echo "<td>" . $reg_tickets["nombre_comprador"] . "</td>";
		echo "<td>" . $reg_tickets["id"] . "</td>";
//		echo "<td>" . $reg_tickets["media_compra"] . "</td>";
		//		echo "<td>" . $reg_tickets["ultima_compra"] . "</td>";
		echo "</tr>";
	}

} else {

	echo "<div class = 'row'>";
	echo "<div  class = 'col'>
                <div class = 'alert alert-info'>Aun no se han generado tickets para rifa de incentivos, por favor de clic al siguiente boton:
                    <button class = 'btn btn-primary'  type = 'submit' name = 'generar' value = '" . $id_sorteo . "'>GENERAR TICKETS</button>
                </div>
          </div>";
	echo "</div>";

}

?>

</table>
</form>
<?php

if (isset($_POST['generar'])) {

	$id_sorteo = $_POST['generar'];
	//$id_sorteo = 3340;
	//$inicial = $id_sorteo - 10;
	//$final = $id_sorteo - 1;
	//$c_vendedores = mysqli_query($conn, "SELECT identidad_comprador as identidad, nombre_comprador, SUM(cantidad)/10 as numero_bolsas, COUNT(identidad_comprador) as sorteos_activos FROM  ( SELECT identidad_comprador, nombre_comprador,SUM(cantidad) as cantidad, id_sorteo FROM transaccional_ventas_general WHERE cod_producto = 3 AND id_sorteo BETWEEN '$inicial' AND '$final' AND estado_venta = 'APROBADO' GROUP BY identidad_comprador, id_sorteo ORDER BY  identidad_comprador, id_sorteo ASC) as tbl_a  GROUP BY identidad_comprador HAVING COUNT(identidad_comprador) > 9");
    $c_vendedores = mysqli_query($conn, "SELECT identidad_comprador, nombre_comprador, sum(total_comprado) as gran_total_comprado, sum(tickest) as total_tickets
                                            FROM (
                                                (SELECT identidad_comprador, nombre_comprador, sum(cantidad) total_comprado, floor((sum(cantidad)/2)) tickest
                                                    FROM `transaccional_ventas_general` 
                                                    WHERE id_sorteo= $id_sorteo and cod_producto=3 
                                                    GROUP BY identidad_comprador HAVING(total_comprado)>1) 
                                                UNION ALL 
                                                (SELECT identidad_comprador, nombre_comprador, sum(cantidad) total_comprado, floor((sum(cantidad)/10)) tickest 
                                                    FROM `transaccional_ventas_general` 
                                                    WHERE id_sorteo= 1241 and cod_producto=1 
                                                    GROUP BY identidad_comprador HAVING(total_comprado)>9 ) 
                                                )tba 
                                            GROUP BY identidad_comprador 
                                            ORDER BY identidad_comprador asc");
 
    echo mysqli_error($conn);

	while ($reg_vendedores = mysqli_fetch_array($c_vendedores)) {
		$identidad           =      $reg_vendedores['identidad_comprador'];
		$nombre              =      $reg_vendedores['nombre_comprador'];
        $ventas              =      $reg_vendedores['gran_total_comprado']; 
        $total_tickets       =      $reg_vendedores['total_tickets'];
         	 
            $i=0;
            while ($i < $total_tickets) 
            {
               //mysqli_query($conn, "INSERT INTO sorteos_menores_incentivos_tickets (identidad_comprador, nombre_comprador, id_sorteo, media_compra, ultima_compra) VALUES ('$identidad_comprador', '$nombre_comprador', '$id_sorteo', '$media_compra', '$ultima_compra') ");
              //  echo "</br> ".$i." --  "."INSERT INTO sorteos_menores_incentivos_tickets (identidad_comprador, nombre_comprador, id_sorteo, media_compra, ultima_compra) VALUES ('$identidad', '$nombre', '$id_sorteo', '$ventas', '$ventas') ";
              mysqli_query($conn,  "INSERT INTO sorteos_menores_incentivos_tickets (identidad_comprador, nombre_comprador, id_sorteo, media_compra, ultima_compra) VALUES ('$identidad', '$nombre', '$id_sorteo', '$ventas', '$ventas')");
                $i++;
            }    	 
	}
header("Refresh:0");
}

?>