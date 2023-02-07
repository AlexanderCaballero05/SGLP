$wsdl="http://192.168.15.17/PANIAD_LOGIN/_GetPaniLogin.php?wsdl";
$cliente = new nusoap_client($wsdl,true);
$cliente->soap_defencoding = 'utf-8';//default is
$cliente->response_timeout = 200;//seconds
$cliente->useHTTPPersistentConnection();


$result = $cliente-> call("PaniGetlogin", array("usuario" => $u , "password" => $p));

if ($result == 1) {

$c_user = mysqli_query($conn, "SELECT * FROM pani_usuarios WHERE usuario = '$u'  LIMIT 1  ");
$count = 1;

}else{

$count = 0;

}
