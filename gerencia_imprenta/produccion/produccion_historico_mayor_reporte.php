<?php
require('./template/header.php');

$id_sorteo = $_SESSION['historico_mayor']; 

$info_mayor = mysqli_query($conn,"SELECT * FROM sorteos_mayores where id = '$id_sorteo' ");
$value_mayor = mysqli_fetch_object($info_mayor);
$cantidad_billetes = $value_mayor->cantidad_numeros;
$registro_inicial = $value_mayor->desde_registro;
$patron_salto = $value_mayor->patron_salto;
$sorteo = $value_mayor->no_sorteo_may;
$fecha = $value_mayor->fecha_sorteo;

$masc = strlen($cantidad_billetes);
$masc_rec = strlen($registro_inicial);


$parametros_mayor = mysqli_query($conn,"SELECT * FROM sorteos_mayores_produccion where id_sorteo = '$id_sorteo' ");

$i = 1;
while ($row = mysqli_fetch_array($parametros_mayor)) {
$v_salto[$i] = $row['salto'];
$i++;
}

?>


<section style=" color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2 align="center" style="color:black;">Patronato Nacional de la Infancia</h2>
<h3 align="center" style="color:black;">Departamento de Produccion<br>
Centro de Registro para Impresion de Loteria Mayor<br>
Sorteo # <?php echo $sorteo;?> de Fecha <?php echo $fecha;?> </h3>
<br>
</section>



<form method="POST">
<br>


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