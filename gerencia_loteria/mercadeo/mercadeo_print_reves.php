<?php
require('../../template/header.php');


//require('../conexion.php');
header('Content-Type: text/html; charset=ISO-8859-1');

?>



<style type="text/css">
	@media print 
{
   @page
   {
    size: landscape;
  }
}
</style>


<div style="width: 486px ; height: 305px;">
	<img src="./imagenes/carnet_anavelh_back.jpg" width="100%" height="100%" style="position: relative;">
</div>


<div style="width: 486px ; height: 305px;">
	<img src="./imagenes/carnet_anvluh_back.jpg" width="100%" height="100%" style="position: relative;">
</div>


<div style="width: 486px ; height: 305px;">
	<img src="./imagenes/carnet_sin_back.jpg" width="100%" height="100%" style="position: relative;">
</div>


<?php 
echo md5("123");
?>

<script type="text/javascript">
window.print();
</script>