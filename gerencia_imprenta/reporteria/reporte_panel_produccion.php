<?php
require('./template/header.php');

$sorteos = mysqli_query($conn,"SELECT * FROM sorteos_mayores ORDER BY no_sorteo_may");

$sorteos_menores = mysqli_query($conn,"SELECT * FROM sorteos_menores ORDER BY no_sorteo_men");

?>

<form method="POST">

<div class="row">

<div  class="col alert alert-success" style="margin-left: 10px; margin-right: 10px">

<h3 align="center">PANEL DE REPORTERIA DE PRODUCCION MAYOR</h3>
<br>
<br>
<br>


<a href="reporte_produccion_saltos.php" style="width:100%" class="btn btn-primary">
REPORTE DE PRODUCCION POR SALTOS
</a>
<br><br>
<a href="reporte_registros.php" style="width:100%" class="btn btn-primary">
REPORTE DE REGISTROS POR MILLAR
</a>

<hr>

<a href="reporte_panel_produccion_control_mayor.php" style="width:100%" class="btn btn-primary">
REPORTE DE CONTROL DE PRODUCCION
</a>

<br>
<br>
<a href="reporte_panel_produccion_control_mayor_informe.php" style="width:100%" class="btn btn-primary">
INFORME DE PRODUCCION DE LOTERIA 
</a>

<br>
<br>
<a href="reporte_panel_produccion_control_mayor_anexo.php" style="width:100%" class="btn btn-primary">
CONTROL DE PRODUCCION DE LOTERIA NACIONAL
</a>

<br>
<br>
<a href="reporte_panel_produccion_control_mayor_liquidacion.php" style="width:100%" class="btn btn-primary">
CONTROL DE LIQUIDACION DE PRODUCCION POR ETAPAS
</a>


<hr>

<a href="reporte_panel_cc_reposicion_mayor_revisor.php" style="width:100%" class="btn btn-primary">
REPORTE DE REPOSICIONES DE LOTERIA POR REVISOR
</a>

<br>
<br>
<a href="reporte_panel_produccion_trituracion.php" style="width:100%" class="btn btn-primary">
GENERAR ACTA DE TRITURACION
</a>

<br>
<br>
<a href="reporte_panel_produccion_liquidacion_diaria.php" style="width:100%" class="btn btn-primary">
GENERAR ACTA DE TRITURACION DIARIA
</a>
<!--
<br><br>
<a href="reporte_general_mayor_registros.php" style="width:100%" class="btn btn-primary">
REPORTE DETALLADO DE REGISTROS
</a>
<br><br>
<a href="reporte_produccion_mayor_numeradoras.php" style="width:100%" class="btn btn-primary">
REPORTE REGISTROS SEGUN NUMERADORAS
</a>
-->

</div>

<div  class="col alert alert-info" style="margin-left: 10px; margin-right: 10px">

<h3 align="center">PANEL DE REPORTERIA DE PRODUCCION MENOR</h3>
<br>

<br><br>
<a href="reporte_panel_produccion_normal.php" style="width:100%" class="btn btn-primary">
REPORTE DE PRODUCCION NORMAL Y EXTRA
</a>

<br><br>
<a href="reporte_produccion_menor_numeros.php" style="width:100%" class="btn btn-primary">
REPORTE DE PRODUCCION EXTRA POR NUMERO
</a>

<hr>

<a href="reporte_panel_produccion_control_menor.php" style="width:100%" class="btn btn-primary">
REPORTE DE CONTROL DE PRODUCCION
</a>

<br>
<br>
<a href="reporte_panel_produccion_control_menor_informe.php" style="width:100%" class="btn btn-primary">
INFORME DE PRODUCCION DE LOTERIA 
</a>

<br>
<br>
<a href="reporte_panel_produccion_control_menor_anexo.php" style="width:100%" class="btn btn-primary">
CONTROL DE PRODUCCION DE LOTERIA NACIONAL
</a>

<br>
<br>
<a href="reporte_panel_produccion_control_menor_liquidacion.php" style="width:100%" class="btn btn-primary">
CONTROL DE LIQUIDACION DE PRODUCCION 
</a>

<br>
<br>
<a href="reporte_panel_produccion_control_menor_irregularidades.php" style="width:100%" class="btn btn-primary">
IREGULARIDADES EN IMPRESION 
</a>


<hr>


<a href="reporte_panel_cc_reposicion_menor_revisor.php" style="width:100%" class="btn btn-primary">
REPORTE DE REPOSICIONES DE LOTERIA POR REVISOR
</a>


<br>
<br>
<a href="reporte_panel_produccion_trituracion_menor.php" style="width:100%" class="btn btn-primary">
GENERAR ACTA DE TRITURACION
</a>


<!--
<br><br>
<a href="reporte_general_menor_registros.php" style="width:100%" class="btn btn-primary">
REPORTE DETALLADO DE REGISTROS
</a>

<br><br>
<a href="reporte_produccion_menor_numeradoras.php" style="width:100%" class="btn btn-primary">
REPORTE REGISTROS SEGUN NUMERADORAS
</a>
-->

</div>


</div>
</form>
