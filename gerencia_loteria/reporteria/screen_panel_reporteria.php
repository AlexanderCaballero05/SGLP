<?php
require('../../template/header.php');

$gerencia = $v_ruta[2];

$i = 0;
$ma = 0;
$me = 0;
while (isset($_SESSION["r_".$gerencia][$i])) {


if (strpos($_SESSION["r_".$gerencia][$i], 'mayor') == true) {
$v_mayor[$ma] = $_SESSION["r_".$gerencia][$i];
$ma++;
}elseif(strpos($_SESSION["r_".$gerencia][$i], 'menor') == true){
$v_menor[$me] = $_SESSION["r_".$gerencia][$i];
$me++;

}

$i++;
}

?>

<form method="POST">




<section style="background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  >PANEL DE REPORTERIA GERENCIA DE LOTERIA</h2> 
<br>
</section>

<br>


<div class="row">

<div class="col" >

<div class="alert alert-success">

<h3 align="center">LOTERIA MAYOR</h3>
<br>
<br>

<?php

$i = 0;
while (isset($v_mayor[$i])) {

$v_opcion = explode("%", $v_mayor[$i]);
$pantalla = $v_opcion[0];
$name_p   = $v_opcion[1];

echo '
<a href="./'.$pantalla.'" style="width:100%" class="btn btn-success">
'.$name_p.'
</a>
<br>
<br>

';

$i++;
}

?>


</div>

</div>
	





<div class="col" >
	
<div  class="alert alert-info" >

<h3 align="center">LOTERIA MENOR</h3>

<br>
<br>



<?php

$i = 0;
while (isset($v_menor[$i])) {

$v_opcion = explode("%", $v_menor[$i]);
$pantalla = $v_opcion[0];
$name_p   = $v_opcion[1];

echo '
<a href="./'.$pantalla.'" style="width:100%" class="btn btn-primary">
'.$name_p.'
</a>
<br>
<br>

';

$i++;
}

?>


</div>

</div>


</div>

</form>