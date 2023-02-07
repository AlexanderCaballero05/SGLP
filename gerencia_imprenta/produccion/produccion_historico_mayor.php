<?php
require('./template/header.php');
require('./produccion_historico_mayor_db.php');

?>

<script type="text/javascript">
function validar_terminacion(valor,i){

ultimo = valor.substr(valor.length - 1);
if (ultimo != 1) {
document.getElementById('salto'+i).value = '';

  swal({ 
  title: "",
   text: "Los saltos deben terminar en 1",
    type: "info" 
  });

};

}
</script>

<h2 align="center">Detalle de Produccion</h2>
<br>

<form method="POST">
<br>
<div style="width:100%" class="well">
<table width="100%">
  <tr>
    <td width="20%"><?php echo "No. Sorteo: ".$sorteo;?></td>
    <td width="20%"><?php echo "Fecha Sorteo:".$fecha;?></td>
    <td width="20%"><?php echo "Cantidad de Billetes: ".$cantidad_billetes;?></td>
    <td width="20%"><?php echo "Patron de salto: ".$patron_salto;?></td>
    <td width="20%"><?php echo "Registro Inicial: <input style = 'width:35%' type = 'text' name = 'registro_inicial' value = '".$registro_inicial."'>"?></td>
  </tr>
</table>
</div>
<br>
<p align="center">
<input type="submit" class="btn btn-primary" name="guardar_cambios" value="Actualizar">
<input type="submit" class="btn btn-danger" name="eliminar" value="Eliminar">
<button class="btn btn-primary" onclick='window.print();' value='Imprimir'>
<span class = 'glyphicon glyphicon-print'></span> Imprimir  
</button>
</p>
<br>
<div style="width:100%" align="center"> 
<table style="width:60%" border="1" class= 'table table-hover table-bordered'>
<?php
$i = 1;
while ($row = mysql_fetch_array($parametros_mayor)) {
$v_salto[$i] = $row['salto'];
echo "<tr><td align = 'center' >
Salto ".$i.": <input onblur = 'validar_terminacion(this.value,".$i.")' id = 'salto".$i."' name = 'salto".$i."' type = 'text' value = '".$row['salto']."' required>
<input name = 'salto_oculto".$i."' type = 'hidden' value = '".$row['id']."' >
</td></tr>";
$i++;
}
?>  
</table>
<br>


</div>

</form>

<div style="width:100%" align="center">
<?php


$num_saltos = $cantidad_billetes/$patron_salto;
echo "<table style = 'width:60%' class= 'table table-hover table-bordered' >";

echo "<tr>
<th width = '33.33%'></th>
<th width = '33.34%'>Inicial</th>
<th width = '33.33%'>Final</th>
</tr>";



$i = 0;
$j = 1;
$acumulador_salto = 0;
$indicador = false;
$billete_i = 0;
$billete_f =  999;
$registro = $registro_inicial;
$registro_i = $registro_inicial;
$registro_f = $registro_i - 999;

while ($i  < $cantidad_billetes) {


if ($acumulador_salto == $patron_salto) {
$indicador = true;
$registro = $registro - $v_salto[$j] + 1;
$registro_i = $registro;
$registro_f = $registro_i - 999;
$j ++;
$acumulador_salto = 0;
}


if ($indicador == true) {
echo "<tr style = 'background-color:green;'>";
$indicador = false;
}else{
echo "<tr>";
}


$billete_i = str_pad($billete_i, $masc, '0', STR_PAD_LEFT);
$billete_f = str_pad($billete_f, $masc, '0', STR_PAD_LEFT);

$registro_i = str_pad($registro_i, $masc_rec, '0', STR_PAD_LEFT);
$registro_f = str_pad($registro_f, $masc_rec, '0', STR_PAD_LEFT);

	
echo "<td>
Numero <br>
Registro
</td>";
echo "<td>".$billete_i."<br>"; 
echo $registro_i."</td>";
echo "<td>".$billete_f."<br>"; 
echo $registro_f."</td>";
echo "</tr>";
$i = $i + 1000;
$acumulador_salto = $acumulador_salto + 1000;

$billete_i = $billete_i + 1000;
$billete_f = $billete_i + 999 ;
$registro = $registro - 1000;
$registro_i = $registro;
$registro_f = $registro_i - 999;


}


echo "</table>";  
?>
</div>