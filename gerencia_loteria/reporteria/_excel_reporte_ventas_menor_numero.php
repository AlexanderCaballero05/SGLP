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
		          $total_cantidad = 0;
		          $total_vendido = 0;

		$objPHPExcel = new PHPExcel(); 
		$objPHPExcel->setActiveSheetIndex(0); 

		$objPHPExcel->getActiveSheet()->SetCellValue('A1','REPORTE DE REVERSIONES LOTERIA MENOR (POR USUARIOS) ');

					  $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Numero');
                      $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Cantidad');
                      $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Total');

			$i = 3;  
		   $seccionales=mysql_query(" SELECT * FROM fvp_detalles_ventas_menor where estado_venta='APROBADO' GROUP BY numero order by numero asc; ");
			  while ( $seccional=mysql_fetch_array($seccionales))
			  {
				$id_seccional = $seccional["numero"];
				$consulta_ventas = mysql_query(" SELECT count(numero) numero FROM fvp_detalles_ventas_menor where estado_venta='APROBADO'  and numero=$id_seccional  and date(fecha_transaccion) BETWEEN  '$fecha_inicial' and '$fecha_final'; ");

				while ($reg_venta = mysql_fetch_array($consulta_ventas)) 
				{
					$total  = $reg_venta['numero']* $precio_unitario;
					$cantidad = $reg_venta['numero'];

		                    $total_vendido = $total_vendido + $billetes_vendidos;

							$objPHPExcel->getActiveSheet()->SetCellValue('B'.$i, $id_seccional);
							$objPHPExcel->getActiveSheet()->SetCellValue('C'.$i, $cantidad);
							$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i, $total);

		                 
			    }
			 	$i ++;
			}


		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment; filename=\"Reporte_ventas_menor_numero.xlsx\"");
		header("Cache-Control: max-age=0");

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_clean();
		$objWriter->save("php://output");

}
?>