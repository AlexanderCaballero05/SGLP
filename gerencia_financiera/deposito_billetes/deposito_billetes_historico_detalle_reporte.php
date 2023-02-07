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


<form method="POST">

<br><br>
<?php
$i = 0;
$j = 0;
$k = 0;

if ($mezcla == 200) {

	while (isset($v_mezclas[$i])) {

		echo '<table width="100%" style = "page-break-after: always;" class="table table-hover table-bordered">';

		echo '  <tr>
    <td width="100%" align="center" >
    <br>
    <input  type="hidden" value ="' . $id_sorteo . '" id="id_oculto" name="id_oculto" >

<div class="row">
<div class="col-md-3">
<div class = "input-group">
<div class = "input-group-preprend"><div class = "input-group-text">SORTEO: </div></div>
 <input class="form-control" style="width:80%;" type="text" id="sorteo" value ="' . $num_sorteo . '" name="sorteo" disabled="true">
</div>
</div>

<div class="col-md-3">

<div class = "input-group">
<div class = "input-group-preprend"><div class = "input-group-text">FECHA SORTEO: </div></div>
 <input class="form-control" style=" width:80%;" id="fecha_sorteo" value ="' . $fecha . '" name="fecha_sorteo" type="date"  disabled="true">
</div>


</div>

<div class="col-md-3">

<div class = "input-group">
<div class = "input-group-preprend"><div class = "input-group-text">RANGOS: </div></div>
  <input class="form-control" style=" width:80%;"  value ="' . $mezcla . '" type="text"  disabled="true">
</div>

</div>
</div>

</tr>';

		echo "<tr>";
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
		echo "</tr>";
		echo "</table>";

		$j = 0;
	}

}

if ($mezcla == 20) {

	while (isset($v_mezclas[$i])) {

		echo '<div class = "card" style = "page-break-after: always;">
<div class = "card-header">
<h3 style = "text-align:center;" >PATRONATO NACIONAL DE LA INFANCIA</h3>
<h3 style = "text-align:center;" >PANI</h3>
</div>
<div class = "card-body">

<div class="row">
<div class="col">
<div class = "input-group" style = "margin-bottom:5px">
<div class = "input-group-preprend"><div style = "width:170px" class = "input-group-text">SORTEO: </div></div>
 <input class="form-control" type="text" id="sorteo" value ="' . $num_sorteo . '" name="sorteo" disabled="true">
</div>
</div>

</div>
<div class="row">
<div class="col">

<div class = "input-group" style = "margin-bottom:5px">
<div class = "input-group-preprend"><div style = "width:170px" class = "input-group-text">FECHA SORTEO: </div></div>
 <input class="form-control" id="fecha_sorteo" value ="' . $fecha . '" name="fecha_sorteo" type="date"  disabled="true">
</div>


</div>

</div>

<div class="row">
<div class="col">

<div class = "input-group" style = "margin-bottom:5px">
<div class = "input-group-preprend"><div style = "width:170px" class = "input-group-text">RANGOS DE: </div></div>
  <input class="form-control"  value ="' . $mezcla . '" type="text"  disabled="true">
</div>

</div></div>
</div><br>';

		echo "<table class = 'table table-bordered'>";
		echo "<tr>";
		echo "<td align = 'center' style = 'font-size:20px'>";
		echo "<b>Paquete: " . $v_mezclas[$i] . "</b>";
		echo "<hr>";

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

		echo "</tr>";
		echo "</table>";

		echo '</div>
</div><br>';

		$j = 0;
	}

} elseif ($mezcla == 50) {

	while (isset($v_mezclas[$i])) {

		echo '<div class = "card" style = "page-break-after: always;">
<div class = "card-header">
<h3 style = "text-align:center;" >PATRONATO NACIONAL DE LA INFANCIA</h3>
<h3 style = "text-align:center;" >PANI</h3>
</div>
<div class = "card-body">

<div class="row">
<div class="col">
<div class = "input-group" style = "margin-bottom:5px">
<div class = "input-group-preprend"><div style = "width:170px" class = "input-group-text">SORTEO: </div></div>
 <input class="form-control" type="text" id="sorteo" value ="' . $num_sorteo . '" name="sorteo" disabled="true">
</div>
</div>

</div>
<div class="row">
<div class="col">

<div class = "input-group" style = "margin-bottom:5px">
<div class = "input-group-preprend"><div style = "width:170px" class = "input-group-text">FECHA SORTEO: </div></div>
 <input class="form-control" id="fecha_sorteo" value ="' . $fecha . '" name="fecha_sorteo" type="date"  disabled="true">
</div>


</div>

</div>
<div class="row">
<div class="col">

<div class = "input-group" style = "margin-bottom:5px">
<div class = "input-group-preprend"><div style = "width:170px" class = "input-group-text">CANTIDAD: </div></div>
 <input class="form-control" name="c_billetes" value ="' . number_format($cantidad) . '" id="c_billetes" type="text"  disabled="true">
</div>


</div>

</div>
<div class="row">
<div class="col">

<div class = "input-group" style = "margin-bottom:5px">
<div class = "input-group-preprend"><div style = "width:170px" class = "input-group-text">RANGOS DE: </div></div>
  <input class="form-control"  value ="' . $mezcla . '" type="text"  disabled="true">
</div>

</div></div>
</div><br>';

		echo "<table class = 'table table-bordered'>";
		echo "<tr>";
		echo "<td align = 'center' style = 'font-size:20px'>";
		echo "<b>Paquete: " . $v_mezclas[$i] . "</b>";
		echo "<hr>";

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
		echo "</tr>";
		echo "</table>";

		echo '</div>
</div><br>';

		$j = 0;
	}

}

?>
</td>
</tr>
</table>
</form>

<br>




