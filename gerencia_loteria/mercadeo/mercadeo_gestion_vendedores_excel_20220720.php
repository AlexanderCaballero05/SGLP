<?php 

    require_once '../../assets/phpexcel/Classes/PHPExcel/IOFactory.php';
    require("../../conexion.php");

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);


	$objPHPExcel->getActiveSheet()->SetCellValue('A1', "IDENTIDAD");
	$objPHPExcel->getActiveSheet()->SetCellValue('B1', "NOMBRE ");
	$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'ASOCIACION');
	$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'CODIGO');
	$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'BOLSAS');
	$objPHPExcel->getActiveSheet()->SetCellValue('F1', "EDAD");
	$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'ESTADO CIVIL');
	$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'GENERO');
	$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'TELEFONO');
	$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'DEPARTAMENTO');
	$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'MUNICIPIO');
	$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'DIRECCION');
	$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'ZONA DE VENTA');
	$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'ESTADO');
	$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'FECHA ULTIMA COMPRA');



$ConUltimaVenta = mysqli_query($conn, "SELECT identidad_comprador, MAX(fecha_venta) as fecha_venta, cantidad FROM transaccional_ventas_general WHERE cod_producto = '3' AND estado_venta = 'APROBADO' GROUP BY identidad_comprador ");

while ($RegUltimaVenta = mysqli_fetch_array($ConUltimaVenta)) {
  $ArrayVentas[$RegUltimaVenta['identidad_comprador']] = ["fecha" => $RegUltimaVenta['fecha_venta'], "cantidad" => $RegUltimaVenta['cantidad']];
}

$CurrentYear =  date("Y");
$row = 2;
$edad = "";
$ConVendedores = mysqli_query($conn, "SELECT a.identidad, a.nombre, a.asociacion, a.seccional,  a.codigo, a.numero_bolsas, a.estado_civil, a.sexo, a.telefono, b.dpto, b.municipio, a.direccion, a.zona_venta, a.estado FROM vendedores as a INNER JOIN geocodigos as b ON a.geocodigo = b.cod_muni  ");


	while ($reg_vendedores = mysqli_fetch_array($ConVendedores)) {

        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $reg_vendedores['identidad']);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $row)->getNumberFormat()->setFormatCode('0000000000000');

		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $reg_vendedores['nombre']);
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $reg_vendedores['asociacion']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, $reg_vendedores['asociacion'] . "-" . $reg_vendedores['seccional'] . "-" . $reg_vendedores['codigo']);
		$objPHPExcel->getActiveSheet()->SetCellValue('E' . $row, $reg_vendedores['numero_bolsas']);
    
    
    if (strlen($reg_vendedores['identidad']) == 13){
      $edad = substr($reg_vendedores['identidad'], 4, 4);
      $edad = $CurrentYear - $edad; 
      }else{
      $edad = "";
      }

	    $objPHPExcel->getActiveSheet()->SetCellValue('F' . $row, $edad);
		$objPHPExcel->getActiveSheet()->SetCellValue('G' . $row, $reg_vendedores['estado_civil']);
		$objPHPExcel->getActiveSheet()->SetCellValue('H' . $row, $reg_vendedores['sexo']);
		$objPHPExcel->getActiveSheet()->SetCellValue('I' . $row, $reg_vendedores['telefono']);
		$objPHPExcel->getActiveSheet()->SetCellValue('J' . $row, $reg_vendedores['dpto']);

//	  	echo $reg_vendedores['municipio']."<br>";
		$objPHPExcel->getActiveSheet()->SetCellValue('K' . $row, utf8_encode($reg_vendedores['municipio']));
		$objPHPExcel->getActiveSheet()->SetCellValue('L' . $row, $reg_vendedores['direccion']);
		$objPHPExcel->getActiveSheet()->SetCellValue('M' . $row, $reg_vendedores['zona_venta']);

    if ($reg_vendedores['estado'] == 1) {
      $objPHPExcel->getActiveSheet()->SetCellValue('N' . $row, "ACTIVO");
    }else{
      $objPHPExcel->getActiveSheet()->SetCellValue('N' . $row, "INACTIVO");
    }


if (isset($ArrayVentas[$reg_vendedores['identidad']])) {

  $objPHPExcel->getActiveSheet()->SetCellValue('O' . $row, $ArrayVentas[$reg_vendedores['identidad']]['fecha']);
}else{
  $objPHPExcel->getActiveSheet()->SetCellValue('O' . $row, '');
}

  $row++;
	}

	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=\"LISTADO_GENERAL_VENDEDORES.xlsx\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	ob_clean();
	$objWriter->save("php://output");

?>