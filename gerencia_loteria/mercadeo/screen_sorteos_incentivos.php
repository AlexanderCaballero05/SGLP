<?php
require '../../template/header.php';

$today_date = date('Y-m-d');

?>



<section style=" background-repeat:no-repeat;background-image:url(&quot;none&quot;);color:rgb(63,138,214);background-color:#ededed;">
<br>
<h2  align="center" style="color:black; "  ><b>ASIGNACION DE INCENTIVOS LOTERIA MENOR</b></h2>
<br>
</section>

<br>



<div class="tab-content">

<div class="row">


<div class="col">

<div class="card" style="margin-left: 15px;">

<div class="card-header alert alert-info">
<h4 align="center"><b>SORTEOS PENDIENTES DE ASIGNACION DE INCENTIVOS</b></h4>
</div>

<div class="card-body">

<table class="table table-hover table-bordered" id="table_id1" >

        <thead>
            <tr>
                <th width="18%">Sorteo</th>
                <th width="18%">Fecha Sorteo</th>
                <th width="18%">Fecha Vencimiento</th>
                <th width="10%">Accion</th>
            </tr>
        </thead>
        <tbody>
<?php
$result = mysqli_query($conn, " SELECT * FROM sorteos_menores WHERE  incentivo_vendedores = '0' AND date(fecha_sorteo) >= '$today_date' ");

if ($result != null) {
	while ($row = mysqli_fetch_array($result)) {
		$id = $row['id'];
		echo "<tr>
   <td>" . $row['no_sorteo_men'] . "</td>
   <td>" . $row['fecha_sorteo'] . "</td>
   <td>" . $row['vencimiento_sorteo'] . "</td>
   <td align = 'center'><a class = 'btn btn-info' href = './registro_incentivos_loteros.php?sort=" . $id . "'>Asignar</a></td>
   </tr>
   ";
	}
}

?>
</tbody>
</table>


</div>
</div>


</div>


<div class="col">

<div class="card " style="margin-right: 15px;">
<div class="card-header alert alert-success">
<h4 align="center"><b>HISTORICO DE ASIGNACION DE INCENTIVOS</b></h4>
</div>

<div class="card-body">

<table class="table table-hover table-bordered" id="table_id2" >

        <thead>
            <tr>
                <th width="18%">Sorteo</th>
                <th width="18%">Fecha Sorteo</th>
                <th width="18%">Fecha Vencimiento</th>
                <th width="10%">Accion</th>
            </tr>
        </thead>
        <tbody>
<?php
$result = mysqli_query($conn, " SELECT * FROM sorteos_menores WHERE   incentivo_vendedores != '0'  ");

if ($result != null) {
	while ($row = mysqli_fetch_array($result)) {
		$id = $row['id'];
		echo "<tr>
   <td>" . $row['no_sorteo_men'] . "</td>
   <td>" . $row['fecha_sorteo'] . "</td>
   <td>" . $row['vencimiento_sorteo'] . "</td>
   <td align = 'center'><a class = 'btn btn-success' href = './registro_incentivos_loteros.php?sort=" . $id . "'>Ver</a></td>
   </tr>
   ";
	}
}

?>
</tbody>
</table>


</div>
</div>

</div>


</div>








</div>