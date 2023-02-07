<?php 
require('./template/header.php');
require('./_excel_reporte_ventas_menor_numero.php');

$sorteos = mysql_query("SELECT * FROM sorteos_menores   ORDER BY no_sorteo_men DESC ");
?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BANRURAL</title>     
  <style type="text/css" media="screen">
  #pani 
  {
    max-width:1300px;
    -webkit-box-shadow: 0px 0px 15px 0px rgba(48,50,50,0.48);
    moz-box-shadow: 0px 0px 18px 0px rgba(48,50,50,0.48);
    box-shadow: 0px 0px 18px 0px rgba(48,50,50,0.48);
    background-color: #ffffff;
    
  }
</style>


<link href="./dates/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css">
<script src="./dates/moment.min.js"></script>
<script src="./dates/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript">
            $(function () {
                $('#datetimepicker1').datetimepicker();
            });
</script>
<script type="text/javascript">
            $(function () {
                $('#datetimepicker2').datetimepicker();
            });
</script>


  </head>
<body>
 <div class="container-fluid" style="width:100%; margin-top:-20px;" id="pani"> <br>
 <form method="post">
<h1 align = "center">Reporte de Ventas de Lotería menor</h1><hr><br>
<p align="center">
  Seleccione un Sorteo: 
  <select name="sorteo" style="width: 30%">
    <?php
		while ($row2 = mysql_fetch_array($sorteos))
		{
			echo '<option value = "'.$row2['id'].'">No.'.$row2['no_sorteo_men'].' -- Fecha '.$row2['fecha_sorteo'].' -- '.$row2['descripcion_sorteo_men'].'</option>' ;
		}
    ?>
  </select> 


<div style="width:100%;" align="center" >


<div style="width:25%">
Fecha Inicial
                <div class='input-group date' id='datetimepicker1'>
                    <input type='text' id ="fecha_i" name = "fecha_inicial" class="form-control" />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
</div>

<div style="width:25%">
Fecha Final
                <div class='input-group date' id='datetimepicker2'>
                    <input type='text' id ="fecha_f" name="fecha_final" class="form-control" />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
</div>
<br>
<input type="submit" name="seleccionar" class="btn btn-primary" style="background-color: #005c7a;" value="Seleccionar">

</div>


<?php
if (isset($_POST["sorteo"])) 
{
		echo '<table width="100%" id="table_id1" class="table table-hover table-bordered">';
		echo "<th>Numero</th>";
		echo "<th>Cantidad Vendidos</th>";
		echo "<th>Total Vendidos</th>";

			$id_sorteo = $_POST["sorteo"];
			$fecha_inicial=$_POST['fecha_inicial'];                                                                   
       	   $fecha_final=$_POST['fecha_final'];
	
      			$fecha_inicial = date("Y-m-d", strtotime($fecha_inicial));
				$fecha_final= date("Y-m-d", strtotime($fecha_final));

		    $info_sorteo = mysql_query("SELECT *  FROM sorteos_menores WHERE id = '$id_sorteo' limit 1");
		    $value = mysql_fetch_object($info_sorteo);
		    $precio_unitario = $value->precio_unitario;
		   	$total_cantidad = 0;
			$total_vendido = 0;
			$total_no_vendido = 0;

			     $sorteo = $value->no_sorteo_men;
			     $descripcion = $value->descripcion_sorteo_men;

				echo "<div class = 'alert alert-info' align = 'center'>
						<h3> Sorteo No. ".$sorteo." ".$descripcion." </h3>
						
						Reporte de venta por agencia desde  ".$fecha_inicial."  Hasta  ".$fecha_final."
					  </div>";


	 		$seccionales=mysql_query(" SELECT * FROM fvp_detalles_ventas_menor where estado_venta='APROBADO'  AND id_sorteo = '$id_sorteo' GROUP BY numero order by numero asc; ");
	  while ( $seccional=mysql_fetch_array($seccionales))
	  {
		$id_seccional = $seccional["numero"];
		$consulta_ventas = mysql_query(" SELECT count(numero) numero FROM fvp_detalles_ventas_menor where estado_venta='APROBADO'  and numero = $id_seccional AND id_sorteo = '$id_sorteo'  and date(fecha_transaccion) BETWEEN  '$fecha_inicial' and '$fecha_final'; ");
		while ($reg_venta = mysql_fetch_array($consulta_ventas)) 
		{
			$total  = $reg_venta['numero']* $precio_unitario;
			$cantidad = $reg_venta['numero'];
			
			echo "<tr>";
			echo "<td>".$id_seccional."</td>";
			echo "<td>".$cantidad."</td>";
			echo "<td>L. ".number_format($total,2)."</td>";
			echo "</tr>";
			$total_cantidad=$total_cantidad+$cantidad ;
			$total_vendido=$total_vendido+$total;
		}
     }


	echo "<tr>
			<td>Total</td>
			<td>".$total_cantidad."</td>
			<td>".number_format($total_vendido,2)."</td>
			</tr>";
			echo "</table><br><br>";

$parametros  = $id_sorteo."/".$fecha_inicial."/".$fecha_final; 
echo ' <button type="submit"  class="btn btn-warning" style=" width:20%; margin-left:40%; background-color: #005c7a;" name="envio_excel" class="button button-default"  value='.$parametros.' >Exportar a Excel</button>';
}
?>
	</form><br>

		</div>
	

</body>