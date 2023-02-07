<?php 

$fileTempName = $_FILES['importacion']['tmp_name']; 
$nombre_archivo = $_FILES["importacion"]["name"];


if ($fileTempName != '') {

$sorteo_seleccion = $_POST['sorteo'];
$id_empresa_seleccionada = $_POST['id_nueva_empresa'];

$info_sorteo = mysqli_query($conn,"SELECT * FROM sorteos_menores WHERE id = '$sorteo_seleccion' ");
$ob_sorteo = mysqli_fetch_object($info_sorteo);
$precio_unitario = $ob_sorteo->precio_unitario;

$info_empresa = mysqli_query($conn,"SELECT * FROM empresas WHERE id = '$id_empresa_seleccionada' ");
$ob_empresa = mysqli_fetch_object($info_empresa);
$nombre_empresa = $ob_empresa->nombre_empresa;



echo "<input type = 'hidden' name = 'id_sorteo_o' value ='".$sorteo_seleccion."' >";
echo "<input type = 'hidden' name = 'id_empresa_o' value ='".$id_empresa_seleccionada."' >";


require_once $ruta.'assets/phpexcel/Classes/PHPExcel/IOFactory.php';
$objPHPExcel = PHPExcel_IOFactory::load($fileTempName);

$worksheet = $objPHPExcel->setActiveSheetIndex(0);

$worksheetTitle     = $worksheet->getTitle();
$highestRow         = $worksheet->getHighestRow(); // e.g. 10
$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
$nrColumns = ord($highestColumn) - 64;

$cell1 = $worksheet->getCellByColumnAndRow(1, 1);
$sorteo = $cell1->getValue();


if ($sorteo_seleccion == $sorteo) {


$numeros_asignados = mysqli_query($conn,"SELECT numero,serie_inicial,serie_final,cantidad FROM menor_seccionales_numeros WHERE id_empresa = '$id_empresa_seleccionada' AND id_sorteo = '$sorteo' ");

$n = 0;
$irregularidades = 0;
while ($reg_numeros_asignados = mysqli_fetch_array($numeros_asignados)) {
$numero_asignado = $reg_numeros_asignados['numero'];
$billete_inicial_asignado = $reg_numeros_asignados['serie_inicial'];
$billete_final_asignado = $reg_numeros_asignados['serie_final'];

while ($billete_inicial_asignado <= $billete_final_asignado) {
$v_b[$n] = $numero_asignado.$billete_inicial_asignado;
$billete_inicial_asignado++;
$n++;
}

}


$billetes_vendidos = mysqli_query($conn," SELECT CONCAT(a.numero,a.serie) as numero_serie FROM fvp_detalles_ventas_menor as a INNER JOIN transaccional_ventas as b ON a.cod_factura = b.cod_factura WHERE a.id_sorteo = '$sorteo_seleccion' AND b.id_entidad = '$id_empresa_seleccionada' AND a.estado_venta = 'APROBADO' ");
if ($billetes_vendidos == false) {
echo mysqli_error();
}

$i = 0;

while ($detalle_vendidos = mysqli_fetch_array($billetes_vendidos)) {
$v_v[$i] = $detalle_vendidos['numero_serie'];
$i++;
}


if (isset($v_b)) {

echo "<br>";
echo "<br>";

echo "<div  class = 'card' style = 'margin-left: 10px; margin-right: 10px;' >";
echo "<div  class = 'card-header bg-secondary text-white' align = 'center'>";

echo "<h3 style = 'align:center'>";
echo $nombre_empresa;
echo "<hr>";
echo "SORTEO ".$sorteo_seleccion;
echo "</h3>";

echo "</div>";


echo "<div class = 'card-body' >";
echo "<table  class = ' table table-bordered'>";
echo "<tr>
	  <th>#</th>
	  <th>Numero</th>
	  <th>Serie Inicial</th>
	  <th>Serie Final</th>
	  <th>Cantidad</th>
	  <th>Estado</th>
	  </tr>";


$i = 0;   
$j = 0;
$cantidad_total = 0;
for ($row = 4; $row <= $highestRow; ++ $row) {
$cell1 = $worksheet->getCellByColumnAndRow(0, $row);
$cell2 = $worksheet->getCellByColumnAndRow(1, $row);
$cell3 = $worksheet->getCellByColumnAndRow(2, $row);

$numero 		 = $cell1->getValue();
$billete_inicial = $cell2->getValue();
$billete_final   = $cell3->getValue();

$numero_validacion         	= $numero;
$billete_inicial_validacion = $billete_inicial;
$billete_final_validacion 	= $billete_final;
$cantidad  = $billete_final - $billete_inicial + 1;
$bandera = 0;

if ($cantidad > 0 ) {

while ($billete_inicial_validacion <= $billete_final_validacion) {

$concatenado_validacion = $numero_validacion.$billete_inicial_validacion; 

if (!in_array($concatenado_validacion, $v_b)) {
$bandera = 1;
$msg = 'Numero y Serie '.$billete_inicial_validacion." No asignado";
$billete_inicial_validacion = $billete_final_validacion;
}elseif (isset($v_v)) {

if (in_array($concatenado_validacion, $v_v)) {
$bandera = 1;
$msg = 'Numero y Serie '.$billete_inicial_validacion." Ya vendido";
$billete_inicial_validacion = $billete_final_validacion;
}


}elseif (isset($v_por_importar)) {
if (in_array($concatenado_validacion, $v_por_importar)) {
$bandera = 1;
$msg = 'Numero y Serie '.$billete_inicial_validacion." Repetido";
$billete_inicial_validacion = $billete_final_validacion;
}	
}	

$v_por_importar[$j] = $concatenado_validacion;
$j++;

$billete_inicial_validacion++;	
}

}else{

$bandera = 1;
$msg = "Rango Incorrecto";
$billete_inicial_validacion = $billete_final_validacion;

}


if ($bandera == 0) {

$matriz_import[$i][0] = $numero;
$matriz_import[$i][1] = $billete_inicial;
$matriz_import[$i][2] = $billete_final;
$matriz_import[$i][3] = $cantidad;
$cantidad_total = $cantidad_total + $cantidad;
$i++;

echo "<tr style = 'background-color:#ccffcc'>";
echo "<td>".$row."</td>";
echo "<td>".$numero."</td>";
echo "<td>".$billete_inicial."</td>";
echo "<td>".$billete_final."</td><td>".$cantidad."</td>";
echo "<td><font color = 'green'>OK</font></td>";
echo "</tr>";

}else{

echo "<tr style = 'background-color:#ffcccc'>";
echo "<td>".$row."</td>";
echo "<td>".$numero."</td>";
echo "<td>".$billete_inicial."</td>";
echo "<td>".$billete_final."</td><td>".$cantidad."</td>";
echo "<td><font color = 'red'>".$msg."</font></td>";
echo "</tr>";
$irregularidades++;
}

}

echo "</table>";
echo "</div>";

if ($irregularidades == 0) {



/////////////////////////////////////////////////////////////////
///////////// BUSQUEDA DE PARAMETROS DE VENTA ///////////////////
$parametros_venta = mysqli_query($conn,"SELECT * FROM empresas WHERE id = '$id_empresa_seleccionada' ");
$ob_paramatros_venta = mysqli_fetch_object($parametros_venta);
$descuento = $ob_paramatros_venta->descuento_menor;
$tipo_descuento = $ob_paramatros_venta->tipo_descuento_menor;
$comision = $ob_paramatros_venta->rebaja_menor;
$tipo_comision = $ob_paramatros_venta->tipo_rebaja_menor;


if ($tipo_descuento == 1) {
$monto_descuento = $descuento;
}else{
$desc = $descuento/100;
$monto_descuento = $precio_unitario * $desc;
}


if ($tipo_comision == 1) {
$monto_comision = $comision;
}else{
$com = $comision/100;
$monto_comision = $precio_unitario * $com;
}

$total_bruto 	 = $cantidad_total * $precio_unitario;
$total_descuento = $cantidad_total * $monto_descuento; 
$total_descuento = $total_descuento;

$total_neto      = $total_bruto - $total_descuento; 
$total_comision  = $cantidad_total * $monto_comision;
$aportacion = 0.03 * $cantidad_total;
$total_credito_pani = $total_neto - $total_comision - $aportacion;

echo "<div class = 'card-footer'>";

echo "<table class = 'table table-bordered' >";
echo "<tr>";
echo "<th>Total Billetes</th>";
echo "<th>Precio Unitario</th>";
echo "<th>Total Bruto</th>";
echo "<th>Total Descuento</th>";
echo "<th>Total Neto</th>";
echo "</tr>";
echo "<tr>";
echo "<td><input type = 'text' name = 'total_cantidad'  class = 'form-control' value = '".$cantidad_total."'  readonly></td>";
echo "<td><input type = 'text' name = 'precio_unitario' class = 'form-control' value = '".$precio_unitario."' readonly></td>";
echo "<td><input type = 'text' name = 'total_bruto'     class = 'form-control' value = '".$total_bruto."'     readonly></td>";
echo "<td><input type = 'text' name = 'total_descuento' class = 'form-control' value = '".$total_descuento."' readonly></td>";
echo "<td><input type = 'text' name = 'total_neto' class = 'form-control' value = '".$total_neto."'      readonly></td>";
echo "</tr>";
echo "</table>";

echo "<input type = 'hidden' class = 'form-control' name = 'total_comision' value = '".$total_comision."' >";
echo "<input type = 'hidden' class = 'form-control' name = 'total_credito_pani' value = '".$total_credito_pani."' >";

///////////// FIN BUSQUEDA DE PARAMETROS DE VENTA ///////////////////
/////////////////////////////////////////////////////////////////////




$dataString = serialize($matriz_import);

echo "
<div class = 'alert alert-info' align = 'center'>
No. de irregularidades: ".$irregularidades."<br>
Por favor proceda a realizar la importacion de estas ventas. <br><br>";

echo "<button type = 'submit' value = '".$dataString."' class = 'btn btn-primary' name = 'guardar_importacion'>
GUARDAR
</button> 

</div>";


}else{

echo "<div class = 'alert alert-danger'>
No. de irregularidades: ".$irregularidades."<br>
El archivo que intenta importar contiene irregularidades, por favor realice las correcciones correspondientes e intentelo nuevamente.
</div>";

echo "</div>";
echo "<br><br><br>";

}


}else{
echo '<div class = "alert alert-danger">La entidad seleccionada no tiene asignacion de loteria.</div>';		
}	


}else{
echo '<div class = "alert alert-danger">El sorteo seleccionado y el sorteo a importar no coinciden.</div>';		
}	



}else{
echo '<div class = "alert alert-danger">Debe seleccionar un archivo a validar.</div>';	
}


?>