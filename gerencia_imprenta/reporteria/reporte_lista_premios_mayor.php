<?php 
require('../../template/header.php'); 
?>


<div id="div_wait" class="div_wait">  </div><br> 
<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Generacion de Lista de Premios</h3> <br></section>
<section>
<a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS  <i class="far fa-hand-point-down fa-lg"></i></a>
    <div class="collapse " id="collapse1" align="center">
      <div class="card">
        <div class="card card-body" id="non-printable" >
          <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
            <div class = "input-group-prepend"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i> &nbsp;  Sorteo: </span></div>
                <select name="sorteo" id="sorteo"  class="form-control">
                     <option value='0'>Seleccione Uno</option>
                     <?php 
                       $c_sorteos =mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE estado_sorteo = 'CAPTURADO' ORDER BY id DESC ");
                       if (mysqli_num_rows($c_sorteos)>0)
                       {
                          while ($r_sorteos = mysqli_fetch_array($c_sorteos))
                         {
                           echo "<option value='".$r_sorteos['id']."'> ".$r_sorteos['id']." | Año: ".$r_sorteos['fecha_sorteo']." </option>";
                         }
                       } 
                    ?>
                </select>           
            <button id="buttonConsulta" name="seleccionar" onclick = 'generar()' class="Consulta btn btn-primary">GENERAR ARCHIVO PLANO</button>
          </div>
        </div>
      </div>
    </div> 
 </section>




<script type="text/javascript">

function generar(){
  
  sorteo = document.getElementById('sorteo').value;
  window.open('excel_reporte_lista_premios_mayor.php?sorteo=' + sorteo, '_blank');

}

</script>