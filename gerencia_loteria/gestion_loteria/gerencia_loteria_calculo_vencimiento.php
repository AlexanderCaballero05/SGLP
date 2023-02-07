<?php

$fecha = $_GET['fecha'];

$fecha_vencimiento = date('Y-m-d', strtotime($fecha. ' + 45 days'));

?>

<script type="text/javascript">
document.getElementById('fecha_vencimiento').value = '<?php echo $fecha_vencimiento;?>';
</script>