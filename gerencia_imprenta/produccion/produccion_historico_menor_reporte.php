<?php
require('./template/header.php');
require('./produccion_historico_menor_db.php');

$id_sorteo = $_SESSION['historico_menor'];
$_SESSION['id_sorteo'] = $id_sorteo;


$result = mysql_query("SELECT * FROM sorteos_menores WHERE id = '$id_sorteo'");
 
if ($result != null){

while ($row = mysql_fetch_array($result)) {
$sorteo = $row['no_sorteo_men'] ;
$fecha_sorteo = $row['fecha_sorteo'] ;
$series = $row['series'] -1;
$desde_registro = $row['desde_registro'];
$descripcion = $row['descripcion_sorteo_men'];
}
$masc = strlen($series);
}


$max_extra  = mysql_query("SELECT MAX(cantidad) as maximo FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo'");
if (mysql_num_rows($max_extra) == 0) {
$cantidad_extra_mayor =  0; 
}else{
$ob_extra = mysql_fetch_object($max_extra); 
$cantidad_extra_mayor =  $ob_extra->maximo; 
}


$result2 = mysql_query("SELECT * FROM sorteos_menores_num_extras WHERE id_sorteo = '$id_sorteo' ORDER BY numero  ");




?>

<script type="text/javascript">

function imprimir(){
document.getElementById('boton_print').style.display = "none";  
document.getElementById('alert').style.display = "none";  
window.print();
document.getElementById('boton_print').style.display = "block";  
document.getElementById('alert').style.display = "block"; 
}


function calcular_serie_final(cantidad,numero,inicial){
c = parseInt(cantidad);
n = parseInt(numero);
i = parseInt(inicial);

document.getElementById('serie_inicial'+numero).value =  i + c;

}

function calcular_registro_final(cantidad,numero,inicial){
c = parseInt(cantidad);
n = parseInt(numero);
i = parseInt(inicial);

document.getElementById('registro_inicial'+numero).value =  i + c;

}

</script>
<form method="POST">  

<h2 align="center">Patronato Nacional de la Infancia</h2>
<h3 align="center">Departamento de Produccion<br>
Centro de Registro para Impresion de Loteria Menor<br>
Sorteo # <?php echo $sorteo;?> de Fecha <?php echo $fecha_sorteo ;?> </h3>


<br>

<p id="boton_print" align="center">
<span id="boton_print"  class="btn btn-primary" onclick='imprimir()' value='Imprimir'>
<span class = 'glyphicon glyphicon-print'></span>
</span>
</p>

<div class="tab-content">
  <div id="home" class="tab-pane fade in active" align="center">


 <br>

<div class="">
 <table class="table table-hover table-bordered">   
        <thead>        
            <tr>
            <th width="15%">Numeros</th>
            <th width="15%">Series</th>
            <th width="15%">Registro Inicial</th>
            <th width="15%">Registro Final</th>
            </tr>   
        </thead> 
        <tbody>

<?php 

if (isset($desde_registro)) {

$i = 0;
$n_inicial = 0;
$n_final = 0;
$registro = $desde_registro;
$registro_inicial = $desde_registro;
$registro_final = $registro_inicial + $series;


while ($i < 10) {

$n_inicial = $i * 10;
$n_final = $n_inicial + 9;

if ($registro_final  > 99999) {
$sobrante = $registro_final - 100000;
$registro_final = $sobrante;
}


$n_inicial = str_pad($n_inicial, 2, '0', STR_PAD_LEFT);
$n_final = str_pad($n_final, 2, '0', STR_PAD_LEFT);


$registro_inicial = str_pad($registro_inicial, 5, '0', STR_PAD_LEFT);
$registro_final = str_pad($registro_final, 5, '0', STR_PAD_LEFT);

echo "<tr>
<td>".$n_inicial." - ".$n_final."</td>
<td>0000 - ".$series."</td>
<td>".$registro_inicial."</td>
<td>".$registro_final."</td>
</tr>"; 

$i = $i + 1; 

$registro_inicial = $registro_final + 1 + $cantidad_extra_mayor;  
$registro_final = $registro_inicial + $series;


}

}

?>

</tbody>        
</table>
</div>


<br>
</form>
</div>
</div>

<br><br><br><br>