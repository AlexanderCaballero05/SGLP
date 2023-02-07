<?php 

if (isset($_POST['envio_excel'])) 
{
		require_once './phpexcel/Classes/PHPExcel/IOFactory.php';
		$parametros = explode("/", $_POST['envio_excel']);
		$id_sorteo = $parametros[0];
		$fecha_inicial = $parametros[1];
		$fecha_final = $parametros[2];


		
		          $info_sorteo = mysql_query("SELECT *  FROM sorteos_menores WHERE id = '$id_sorteo' limit 1");
		          $value = mysql_fetch_object($info_sorteo);
		          $precio_unitario = $value->precio_unitario;

$id_seccional = $_SESSION['id_seccional'];
$info_comision = mysql_query("SELECT *  FROM fvp_seccionales WHERE id = '$id_seccional' limit 1");
$value_c = mysql_fetch_object($info_comision);
$comision = $value_c->comision;

				  $porcentaje_comision=$comision/100;
		          $total_cantidad = 0;
		          $total_vendido = 0;

		$objPHPExcel = new PHPExcel(); 
		$objPHPExcel->setActiveSheetIndex(0); 

		$objPHPExcel->getActiveSheet()->SetCellValue('A1','REPORTE DE VENTAS LOTERIA MENOR (POR USUARIOS) ');



						$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Usuarios');
						$objPHPExcel->getActiveSheet()->SetCellValue('B3', 'Vendidos');
						$objPHPExcel->getActiveSheet()->SetCellValue('C3', 'Total Vendidos');
						$objPHPExcel->getActiveSheet()->SetCellValue('D3', 'Comision');


				$i = 4;  
				 $seccionales=mysql_query("SELECT * FROM  fvp_usuarios ");
				  while ( $seccional=mysql_fetch_array($seccionales))
				  {
					$id_seccional = $seccional["id"];
					$seccional = $seccional["usuario"];
					
					
					$consulta_ventas = mysql_query("SELECT SUM(precio_total) as total FROM fvp_menor_ventas WHERE estado_venta = 'APROBADO' AND id_sorteo =  '$id_sorteo' AND pani_usuarios_id = '$id_seccional'  and date(fecha_venta) BETWEEN  '$fecha_inicial' and '$fecha_final' ");

				  while ($reg_venta = mysql_fetch_array($consulta_ventas)) 
					{
						$billetes_vendidos  = ($reg_venta['total']/ $precio_unitario);
						$total_vendido = $billetes_vendidos*$precio_unitario;
						$monto_comision=number_format($billetes_vendidos*$precio_unitario*$porcentaje_comision,2);
						

						$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i, $seccional);
						$objPHPExcel->getActiveSheet()->SetCellValue('B'.$i, $billetes_vendidos);
						$objPHPExcel->getActiveSheet()->SetCellValue('C'.$i, $total_vendido);
						$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i, $monto_comision);

		            }
		          	$i ++;
		          }


		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment; filename=\"Reporte_ventas_menor_usuario.xlsx\"");
		header("Cache-Control: max-age=0");

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_clean();
		$objWriter->save("php://output");

}
?>