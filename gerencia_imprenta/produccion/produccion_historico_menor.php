<?php
require('./template/header.php');
require('./produccion_historico_menor_db.php');

?>
<script type="text/javascript">

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


<div class="tab-content">
  <div id="home" class="tab-pane fade in active" align="center">
  <br>
  <h1 align="center">PRODUCCION DE SORTEO DE LOTERIA MENOR</h1>
  <hr>

  <br>

  <div align="center" style=" width:100%">

  <div class="div_inicio" align="center" style=" width:60%">
  <table  width="100%" >
    <tr>
      <td width="100%" align="left" >
      
      <input type="hidden" id = 'id_oculto' name="id_oculto" value="<?php echo $id_sorteo; ?>">
      

<div class="row">
<div class="col-md-4">
    Numero Sorteo: <input class="form-control" type="text" id="sorteo" name="sorteo" value="<?php echo $sorteo; ?>"  disabled>
</div>
<div class="col-md-4">
    Fecha Sorteo: <input class="form-control" id="fecha_sorteo" name="fecha_sorteo" type="text" value="<?php echo $fecha_sorteo; ?>" disabled> 
</div>
<div class="col-md-4">
    Series: <input name="series" id="series" type="text" value="<?php echo $series; ?>" class="form-control" readonly>      
</div>
</div>

    <br><br>
     Descripcion:<textarea class="form-control" name="descripcion" id="descripcion" disabled><?php echo $descripcion;?></textarea>

    </td>
    </tr>

    <tr ><td ><hr><h3 align="center">Parametros de Produccion Normal</h3></td></tr>
    <tr>
    <td >
    <br>
    <p align="center">
    Numero de registro inicial:    <input class="form-control" type="text" name="registro_inicial" value="<?php echo $desde_registro; ?>" style="width:25%" disabled>   
     </p>
    </td>
    </tr>
  </table>

  </div>

  </div>

    <hr>  
  <p align="center"> <input type="submit" class="btn btn-danger" name="eliminar" value="Eliminar"></p>
   <br>
   <br>


  <p align="center">
  <a style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapsee" aria-expanded="false" aria-controls="collapsee">
    Detalle de Saltos Asignados
  </a></p>
  <div class="collapse" id="collapsee">
  <div style="width:100%" class="well">
 
<table class="table table-bordered">
<?php
$i = 1;

if (isset($v_saltos[$i])) {

while (isset($v_saltos[$i])) {
echo "<tr>";
echo "<td>Salto ".$i."</td>";
echo "<td><input type = 'number' name = 'salto".$i."' value = '".$v_saltos[$i]."' class = 'form-control'></td>";
echo "<tr>";
$i++;
}

}

?>
</table>


  </div>
  </div>

<hr>

  <p align="center">
  <a style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse" aria-expanded="false" aria-controls="collapse">
    Detalle de Produccion Normal
  </a></p>

  <div class="collapse" id="collapse">
  <div style="width:100%" class="well">
   <table  class="table table-hover table-bordered">   
          <thead>        
              <tr>
              <th width="25%">Numeros</th>
              <th width="25%">Series</th>
              <th width="25%">Registro Inicial</th>
              <th width="25%">Registro Final</th>
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


if (isset($v_saltos[$i])) {
$registro_adicional = $v_saltos[$i];
}else{
$registro_adicional = 0; 
}

$registro_inicial = $registro_inicial + $registro_adicional;
$registro_final = $registro_inicial + $series;

if ($registro_inicial > 99999) {
$registro_inicial = $registro_inicial - 100000;
}



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

if (isset($v_saltos[1])) {
if ($v_saltos[1] == 0) {
$registro_inicial = $registro_final + 1 + $cantidad_extra_mayor;
}else{
$registro_inicial = $registro_final + 1;
}
}else{
$registro_inicial = $registro_final + 1 + $cantidad_extra_mayor;  
}

$registro_final = $registro_inicial + $series;


  }

  }

  ?>

  </tbody>        
  </table>
  </div>
  </div>
  <hr>

  <p align="center">
  <a style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapseextra" aria-expanded="false" aria-controls="collapseextra">
    Detalle de Produccion Extra
  </a></p>

  <div class="collapse" id="collapseextra">
  <div class="well" style="width:100%">

    <h3 align="center">Parametros de Produccion Extra</h3>
    <table width="100%" class="table table-hover table-bordered">
    <tr>
      <th>Numero</th>
      <th>Cantidad</th>
      <th>Serie Inicial</th>
      <th>Serie Final</th>  
      <th>Registro Inicial</th>
      <th>Registro Final</th>
      <th>Grupo</th>
    </tr>
    <?php
    $i = 0;
    while ($row = mysql_fetch_array($result2)) {
    $serie_final =   $row['serie_inicial'] + $row['cantidad'] -1;
    $registro_final =   $row['registro_inicial'] + $row['cantidad'] -1;

if ($registro_final  > 99999) {
$sobrante = $registro_final - 100000;
$registro_final = $sobrante;
}


$row['numero'] = str_pad($row['numero'], 2, '0', STR_PAD_LEFT);

$row['registro_inicial'] = str_pad($row['registro_inicial'], 5, '0', STR_PAD_LEFT);
$registro_final = str_pad($registro_final, 5, '0', STR_PAD_LEFT);

    echo '<tr>
    <td>'.$row['numero'].'</td>
    <td>'.$row['cantidad'].'</td>
    <td><input type = "text" value = "'.$row['serie_inicial'].'" name = "serie_inicial'.$i.'" disabled></td>
    <td><input type = "text" value = "'.$serie_final.'" name = "" id = "serie_final'.$i.'" disabled></td>
    <td><input type = "text" value = "'.$row['registro_inicial'].'" name = "registro_inicial'.$i.'" disabled></td>
    <td><input type = "text" value = "'.$registro_final.'" name = "" id = "registro_final'.$i.'" disabled></td>
    <td><input type = "text" value = "'.$row['grupo'].'" name = "" id = "grupo'.$i.'" disabled></td>    
    </tr>';
    $i ++;
    }

    ?>
    </table>
  </div>
</div>

  
<br>
</form>
</div>
</div>
<br><br><br><br>