<?php

function registro_bitacora($id_usuario,$modulo,$cod_accion,$tipo_mod,$tabla,$descripcion){
$registro = mysql_query("INSERT INTO log_historico (id_usuario, modulo, cod_accion,tipo_mod, tabla, descripcion) VALUES ('$id_usuario','$modulo','$cod_accion','$tipo_mod','$tabla','$descripcion') ");
return $registro;
}

?>