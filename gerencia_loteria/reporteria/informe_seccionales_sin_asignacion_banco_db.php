<?php
require('../assets/nusoap/lib/nusoap.php');

header('Content-Type: text/html; charset=ISO-8859-1');

date_default_timezone_set("America/tegucigalpa");
$date = date("d-m-Y H:i:s a");


$sorteo = $_GET['ss'];
$ruta = "http://localhost/PANEL-SGLP/gerencia_loteria/";
//$ruta = "http://192.168.15.248/wbs_consulta_ventas/";

$wsdl= $ruta."consulta_ventas_bolsas_wsdl.php?wsdl";


$cliente = new nusoap_client($wsdl,true);
$cliente->soap_defencoding = 'utf-8';//default is 
$cliente->response_timeout = 200;//seconds
$cliente->useHTTPPersistentConnection();
$result_wbs = $cliente-> call("ws_no_asignado", array("sorteo_envio" => $sorteo));

$v_filas = explode("#", $result_wbs);

?>

<script type="text/javascript" src="../assets/datatable/dataTables.min.js"></script>
<script type="text/javascript" src="../assets/datatable/bootstrap4.min.js"></script>
<script type="text/javascript" src="../assets/datatable/loadtable.js"></script>
<link rel="stylesheet" type="text/css" href="../assets/datatable/bootstrap4.min.css">

<script type="text/javascript">

$(document).ready(function() {

$('#table_format').dataTable( {
"lengthMenu": [[-1, 100, 50, 25,10 ], ["Todos", 100, 50, 25, 10 ]],
"language": {
"sProcessing":    "Procesando...",
"sLengthMenu":    "Mostrar _MENU_ registros",
"sZeroRecords":   "No se encontraron resultados",
"sEmptyTable":    "Ningún dato disponible en esta tabla",
"sInfo":          "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
"sInfoEmpty":     "Mostrando registros del 0 al 0 de un total de 0 registros",
"sInfoFiltered":  "(filtrado de un total de _MAX_ registros)",
"sInfoPostFix":   "",
"sSearch":        "Buscar:",
"sUrl":           "",
"sInfoThousands":  ",",
"sLoadingRecords": "Cargando...",
"oPaginate": {
"sFirst":    "Primero",
"sLast":    "Último",
"sNext":    "Siguiente",
"sPrevious": "Anterior"
},
"oAria": {
"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
"sSortDescending": ": Activar para ordenar la columna de manera descendente"
}
}

} );
} );

</script>


<p>Fecha de consulta: <?php echo $date; ?></p>

<div class="table-responsive">
<table id="table_format" class="table table-bordered" width="100%">
	<thead>
		<tr>
<th align = 'center' >Departamento</th>
<th align = 'center' >Municipio</th>
<th align = 'center' >Cod. Seccional</th>
<th align = 'center' >Seccional</th>
		</tr>
	</thead>

	<tbody>

<?php 
$i = 0;
$tt_asignado    = 0;
$tt_vendido     = 0;
$tt_no_vendido  = 0;

while (isset($v_filas[$i])) {

$v_fields = explode("$", $v_filas[$i]);

echo "<tr>";
echo "<td>".$v_fields[0]."</td>";
echo "<td>".$v_fields[1]."</td>";
echo "<td>".$v_fields[2]."</td>";
echo "<td>".$v_fields[3]."</td>";
echo "</tr>";

$i++;
}
?>
		
	</tbody>	
</table>
</div>


<script type="text/javascript">
	$(".div_wait").fadeOut("fast");
</script>