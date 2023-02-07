<?php
require '../../template/header.php';
require './deposito_billetes_historico_detalle_db.php';
?>

<script type="text/javascript">
function imprimir(){

document.getElementById('boton_print').style.display = "none";
if (alert = document.getElementById('alert')) {
document.getElementById('alert').style.display = "none";
}

window.print();

document.getElementById('boton_print').style.display = "block";
if (alert = document.getElementById('alert')) {
document.getElementById('alert').style.display = "block";
}

}
</script>

<section style="background-color:#ededed;">
<br>
<h2  align="center" style="color:black; " ><b> DETALLE DE MEZCLA DE LOTERIA MAYOR</b></h2>
<br>
</section>

<br>

<form method="POST">

<div align="center" style=" width:100%">

<div class="col ">
<div class="card">
  <div class="card-header bg-secondary text-white">
    <h3 style="text-align: center">SORTEO <?php echo $num_sorteo ?></h3>
  </div>

<div class="card-body">

<input type="hidden" name="id_oculto" value="<?php echo $num_sorteo; ?>">

<div class="input-group" style="margin-bottom: 10px; ">
<div  class="input-group-prepend"><div  class="input-group-text">Sorteo: </div></div>
<input class="form-control" style="margin-right: 10px"  type="text" id="sorteo" value ="<?php echo $num_sorteo; ?>" name="sorteo" disabled="true">

<div class="input-group-prepend"><div  class="input-group-text">Fecha de Sorteo: </div></div>
 <input class="form-control" id="fecha_sorteo" value ="<?php echo $fecha; ?>" name="fecha_sorteo" type="date"  disabled="true">
</div>



<div class="input-group" style="margin-bottom: 10px">
<div class="input-group-prepend"><div  class="input-group-text">Cantidad de billetes: </div></div>
<input class="form-control" style="margin-right: 10px"  name="c_billetes" value ="<?php echo number_format($cantidad); ?>" id="c_billetes" type="text"  disabled="true">

<div class="input-group-prepend"><div  class="input-group-text">Rango de Mezcla: </div></div>
<input type="text" name="" value="<?php echo $mezcla; ?>" class = 'form-control'  readonly>
</div>

</div>

</div>
</div>

</div>

</form>



<br><br>
<table width="100%" class="table table-hover table-bordered">
<?php
$i = 0;
$j = 0;
$k = 0;

if ($mezcla == 200) {

	while (isset($v_mezclas[$i])) {
		echo "<tr>";

		while ($j < 5) {
			echo "<td>";
			echo "<b>Paquete: " . $v_mezclas[$i] . "</b>";
			echo "<br>";

			$billete_i = $v_rangos[$k];

			$billete_f = $billete_i + 199;

//$masc = strlen($cantidad);
			$billete_i = str_pad($billete_i, $masc, '0', STR_PAD_LEFT);
			$billete_f = str_pad($billete_f, $masc, '0', STR_PAD_LEFT);

			echo $billete_i . " - " . $billete_f;
			echo "<br>";
			$k++;
			$billete_i = $v_rangos[$k];
			$billete_f = $billete_i + 199;

			$billete_i = str_pad($billete_i, $masc, '0', STR_PAD_LEFT);
			$billete_f = str_pad($billete_f, $masc, '0', STR_PAD_LEFT);

			echo $billete_i . " - " . $billete_f;
			echo "<br>";
			$k++;
			$billete_i = $v_rangos[$k];
			$billete_f = $billete_i + 199;

			$billete_i = str_pad($billete_i, $masc, '0', STR_PAD_LEFT);
			$billete_f = str_pad($billete_f, $masc, '0', STR_PAD_LEFT);

			echo $billete_i . " - " . $billete_f;
			echo "<br>";
			$k++;
			$billete_i = $v_rangos[$k];
			$billete_f = $billete_i + 199;

			$billete_i = str_pad($billete_i, $masc, '0', STR_PAD_LEFT);
			$billete_f = str_pad($billete_f, $masc, '0', STR_PAD_LEFT);

			echo $billete_i . " - " . $billete_f;
			echo "<br>";
			$k++;
			$billete_i = $v_rangos[$k];
			$billete_f = $billete_i + 199;

			$billete_i = str_pad($billete_i, $masc, '0', STR_PAD_LEFT);
			$billete_f = str_pad($billete_f, $masc, '0', STR_PAD_LEFT);

			echo $billete_i . " - " . $billete_f;
			echo "<br>";
			$k++;

			echo "</td>";
			$j++;
			$i++;
		}
		echo "</tr>";

		$j = 0;
	}

}

if ($mezcla == 20) {

	while (isset($v_mezclas[$i])) {
		echo "<tr>";

		while ($j < 5) {
			echo "<td>";
			echo "<b>Paquete: " . $v_mezclas[$i] . "</b>";
			echo "<br>";

			$billete_i = $v_rangos[$k];
			$billete_f = $billete_i + 19;

			$billete_i = str_pad($billete_i, $masc, '0', STR_PAD_LEFT);
			$billete_f = str_pad($billete_f, $masc, '0', STR_PAD_LEFT);

			echo $billete_i . " - " . $billete_f;
			echo "<br>";
			$k++;
			$billete_i = $v_rangos[$k];
			$billete_f = $billete_i + 19;

			$billete_i = str_pad($billete_i, $masc, '0', STR_PAD_LEFT);
			$billete_f = str_pad($billete_f, $masc, '0', STR_PAD_LEFT);

			echo $billete_i . " - " . $billete_f;
			echo "<br>";
			$k++;
			$billete_i = $v_rangos[$k];
			$billete_f = $billete_i + 19;

			$billete_i = str_pad($billete_i, $masc, '0', STR_PAD_LEFT);
			$billete_f = str_pad($billete_f, $masc, '0', STR_PAD_LEFT);

			echo $billete_i . " - " . $billete_f;
			echo "<br>";
			$k++;
			$billete_i = $v_rangos[$k];
			$billete_f = $billete_i + 19;

			$billete_i = str_pad($billete_i, $masc, '0', STR_PAD_LEFT);
			$billete_f = str_pad($billete_f, $masc, '0', STR_PAD_LEFT);

			echo $billete_i . " - " . $billete_f;
			echo "<br>";
			$k++;
			$billete_i = $v_rangos[$k];
			$billete_f = $billete_i + 19;

			$billete_i = str_pad($billete_i, $masc, '0', STR_PAD_LEFT);
			$billete_f = str_pad($billete_f, $masc, '0', STR_PAD_LEFT);

			echo $billete_i . " - " . $billete_f;
			echo "<br>";
			$k++;

			echo "</td>";
			$j++;
			$i++;
		}
		echo "</tr>";

		$j = 0;
	}

} elseif ($mezcla == 50) {

	while (isset($v_mezclas[$i])) {
		echo "<tr>";

		while ($j < 5) {
			echo "<td>";
			echo "<b>Paquete: " . $v_mezclas[$i] . "</b>";
			echo "<br>";

			$billete_i = $v_rangos[$k];
			$billete_f = $billete_i + 49;

			$billete_i = str_pad($billete_i, $masc, '0', STR_PAD_LEFT);
			$billete_f = str_pad($billete_f, $masc, '0', STR_PAD_LEFT);

			echo $billete_i . " - " . $billete_f;
			echo "<br>";
			$k++;

			$billete_i = $v_rangos[$k];
			$billete_f = $billete_i + 49;

			$billete_i = str_pad($billete_i, $masc, '0', STR_PAD_LEFT);
			$billete_f = str_pad($billete_f, $masc, '0', STR_PAD_LEFT);

			echo $billete_i . " - " . $billete_f;
			echo "<br>";
			$k++;

			echo "</td>";
			$j++;
			$i++;
		}
		echo "</tr>";

		$j = 0;
	}

}

?>
</td>
</tr>
</table>
</form>

<br>




