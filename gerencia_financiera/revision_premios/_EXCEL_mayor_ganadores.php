<?php 
require("../../conexion.php");
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=facturas_mayor.xls");
$sorteo=$_GET['sorteo'];

                  						$query_ganador=mysqli_query($conn, "SELECT b.transactionwinnername, b.transactionwinnerid, a.transactioncode, b.transactionagencyname, b.transactionusername, a.sorteo, a.numero, a.decimos, a.totalpayment total, a.imptopayment impto, a.netopayment neto FROM mayor_pagos_detalle a, mayor_pagos_recibos b WHERE a.transactioncode=b.transactioncode and a.sorteo=$sorteo and a.transactionstate = 1 order by a.netopayment desc");
                  					?>
                  					<div class="table-responsive">
                  					<table id="table_id1" class="table table-sm table-hover  table-bordered" width="100%" >
                  						<thead>                  							
                  						<tr ><th>ID</th>
                  							 <th>Nombre</th>
                  							 <th>Factura</th>
                  							 <th>Agencia</th>
                  							 <th>Cajero</th>
                  							 <th>Sorteo</th>
                  							 <th>Número</th>
                  							 <th>Décimos</th>
                  							 <th>Total</th>
                  							 <th>Impto</th>
                  							 <th>Neto</th> 
                  						</tr>
                  						</thead>
                  						<tbody>
                  							<?php  
                  							if ($query_ganador) 
                  							{
                  								$total_decimos=0;	$total_total=0;  $total_impto=0; $total_neto=0;
                  								while ($row_info=mysqli_fetch_array($query_ganador)) 
                  								{
                  								   echo "<tr ><td>".$row_info['transactionwinnerid']."</td>                  								   			  
                  								   			  <td>".$row_info['transactionwinnername']."</td>
                  								   			  <td>".$row_info['transactioncode']."</td>
                  								   			  <td>".$row_info['transactionagencyname']."</td>
                  								   			  <td>".$row_info['transactionusername']."</td>	
                  								   			  <td>".$row_info['sorteo']."</td>	
                  								   			  <td>".$row_info['numero']."</td>
                  								   			  <td align='center'>".$row_info['decimos']."</td>
                  								   			  <td align='right'>".$row_info['total']."</td>
                  								   			  <td align='right'>".$row_info['impto']."</td>
                  								   			  <td align='right'>".$row_info['neto']."</td>
                  								   		</tr>";
                  								   		$total_decimos=$total_decimos+$row_info['decimos'];
                  								   		$total_total=$total_total+$row_info['total'];
                  								   		$total_impto=$total_impto+$row_info['impto'];
                  								   		$total_neto=$total_neto+$row_info['neto'] ;               								   		
                  								}
                  								echo "</tbody>
                  								<tfoot>
                  									 <tr class='table-success' ><td colspan='7'> Totales </td>
                  										   <td align='center'>".$total_decimos."</td>
                  										   <td align='right'>".number_format($total_total,2)."</td>
                  										   <td align='right'>".number_format($total_impto,2)."</td>
                  										   <td align='right'>".number_format($total_neto,2)."</td>                  										   
                  									  </tr> 
                  								</tfoot>";
                  							
                  							}
                  							else
                  							{
                  								echo "No hay registros de esta persona";
                  							}
                  							?>                  						
                  						</tbody>
                  					</table>
                  					</div>

?>