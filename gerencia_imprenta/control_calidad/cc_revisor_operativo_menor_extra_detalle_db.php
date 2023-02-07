<?php


if (isset($_POST['finalizar_revision'])) {

$v_parametros = explode("_",$_POST['finalizar_revision']);

$id_sorteo = $v_parametros[0];
$id_revisor = $v_parametros[1];
$num_lista = $v_parametros[2];
$revision  = $v_parametros[3];

date_default_timezone_set('America/El_Salvador');
$fecha = date("Y-m-d H:i:s");

if (mysql_query("INSERT INTO cc_produccion_menor_extra(id_sorteo,id_revisor,numero_revisor,numero_revision,estado_revisor,fecha_cierre_revisor) VALUES ('$id_sorteo','$id_revisor','$num_lista','$revision','FINALIZADA','$fecha') ") === true) {

mysql_query("UPDATE cc_revisores_sorteos_menores_extras_control SET estado = 'APROBADO' WHERE id_sorteo = $id_sorteo AND estado != 'REPROBADO' AND id_revisor = $id_revisor  AND num_lista = $num_lista AND numero_revision = $revision  ");	

echo "<div class = 'alert alert-info'>
Se ha finalizado la revision ".$num_lista."
</div>";

}else{

echo "<div class = 'alert alert-danger'>
Error inesperado, por favor vuelva a intentarlo
</div>";

}

}






if (isset($_POST['reprobar_rango'])) {

$v_parametros = explode("_",$_POST['reprobar_rango']);


$id_sorteo = $v_parametros[0];
$id_revisor = $v_parametros[1];
$num_lista = $v_parametros[2];
$revision  = $v_parametros[3];
$grupo  = $v_parametros[4];
$revision  = $revision + 1;


$contador_r = 0;
$registros_grupos  = mysql_query("SELECT * FROM sorteos_menores_num_extras WHERE grupo = '$grupo' AND id_sorteo = '$id_sorteo' ");

while ($registro_grupo = mysql_fetch_array($registros_grupos)) {
$numeros = $numeros.",".$registro_grupo['numero'];
$cantidad = $registro_grupo['cantidad'];
$serie_i_i = $registro_grupo['serie_inicial'];


$v_registros[$contador_r] =  $registro_grupo['registro_inicial'];
$contador_r++;
}

$numero_rango  = $_POST['numero_rango'];
$billete_inicial = $_POST['desde'];
$billete_final = $_POST['hasta'];


while ($billete_inicial <= $billete_final) {

if (mysql_query("INSERT INTO cc_revisores_sorteos_menores_extras_control (id_sorteo,id_revisor,num_lista,numero_revision,numero,serie) VALUES ('$id_sorteo','$id_revisor','$num_lista','$revision','$numero_rango','$billete_inicial') ") === true) {

}else{

echo mysql_error();
}

$billete_inicial++;
}

}




if (isset($_POST['reprobar'])) {

$v_parametros = explode("_",  $_POST['reprobar']);

$id_sorteo = $v_parametros[0];
$id_revisor = $v_parametros[1];
$num_lista = $v_parametros[2];
$revision  = $v_parametros[3];
$revision  = $revision + 1;

$grupo = $v_parametros[4];
$serie = $v_parametros[5];
$check = $v_parametros[6];
$c_numeros = $v_parametros[7];
$c_registros = $v_parametros[8];


if (isset($_POST['e'.$check])) {
$especial = 'E';
}else{
$especial = '';	
}



if (mysql_query("INSERT INTO cc_revisores_sorteos_menores_extras_control (id_sorteo,id_revisor,num_lista,numero_revision,grupo,detalle_numeros,serie,detalle_registros,especial) VALUES ('$id_sorteo','$id_revisor','$num_lista','$revision','$grupo','$c_numeros','$serie','$c_registros','$especial') ") === true) {

echo "<div class = 'alert alert-info'>
El numero ha sido reprobado correctamente
</div>";

}else{

echo "<div class = 'alert alert-danger'>
Error inesperado, por favor vuelva a intentarlo
</div>";

}

}


if (isset($_POST['reprobar_nuevamente'])) {

$v_parametros = explode("_",  $_POST['reprobar_nuevamente']);

$id_reprobacion = $v_parametros[0];
$check = $v_parametros[1];



$id_reprobacion = $_POST['reprobar_nuevamente'];
$info_reprobacion = mysql_query("SELECT * FROM cc_revisores_sorteos_menores_extras_control WHERE id = '$id_reprobacion' ");
$ob_reprovado = mysql_fetch_object($info_reprobacion);
$num_lista = $ob_reprovado->num_lista;
$grupo = $ob_reprovado->grupo;
$detalle_numeros = $ob_reprovado->detalle_numeros;
$serie = $ob_reprovado->serie;
$detalle_registros = $ob_reprovado->detalle_registros;
$id_sorteo = $ob_reprovado->id_sorteo;
$id_revisor = $ob_reprovado->id_revisor;
$especial = $ob_reprovado->especial;
$num = $ob_reprovado->numero_revision;
$num = $num + 1;

if (mysql_query("INSERT INTO cc_revisores_sorteos_menores_extras_control (id_sorteo,id_revisor,num_lista,numero_revision,grupo,detalle_numeros,serie,detalle_registros,especial) VALUES ('$id_sorteo','$id_revisor','$num_lista','$num','$grupo','$detalle_numeros','$serie','$detalle_registros','$especial') ") === true) {

if (mysql_query("UPDATE cc_revisores_sorteos_menores_extras_control SET estado = 'REPROBADO' WHERE id = '$id_reprobacion' ") === TRUE) {

echo "<div class = 'alert alert-info'>
El numero ha sido reprobado correctamente
</div>";

}else{
echo mysql_error();
echo "<div class = 'alert alert-danger'>
Error inesperado, por favor vuelva a intentarlo
</div>";

}

}else{
echo mysql_error();
echo "<div class = 'alert alert-danger'>
Error inesperado, por favor vuelva a intentarlo
</div>";

}


}

?>