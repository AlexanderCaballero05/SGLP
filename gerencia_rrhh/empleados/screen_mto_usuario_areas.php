<?php
require('../../template/header.php');
$usuario_id = $_SESSION['usuario'];
if (isset($_GET['id'])) {
  $siid = true;
  $identidad_persona = $_GET['id'];
}

?>
<style type="text/css" media="print">
  #no_print {
    display: none;
  }
</style>

<style type="text/css">
  .div_wait {
    display: none;
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background-color: black;
    opacity: 0.5;
    background: url(../../template/images/wait.gif) center no-repeat #fff;
  }
</style>


<script type="text/javascript">
  $(document).ready(function() {
    $("#txtid").mask("9999-9999-99999", {
      placeholder: "____-____-____ "
    });
    $("#celularfam").mask("9999-9999", {
      placeholder: "____-____"
    });
  });

  $(".div_wait").fadeIn("fast");

  function cargar_datos(idn, nom, fecha_ingreso, area_antigua, unidad_antigua) {

    document.getElementById('updfam').style.display = "block";
    

    document.getElementById('txtid').value = idn;
    document.getElementById('namefam').value = nom;
    document.getElementById('areatrabaja').value = area_antigua;
    document.getElementById('departamentotrabaja').value = unidad_antigua;
    /*  var txtocupation = document.getElementById('areatrabaja').options[document.getElementById('areatrabaja').selectedIndex].text;        
      document.getElementById('areatrabaja').value = txtocupation; */
  }
</script>

<body>
  <form method="post">
    <div id='div_wait'></div>
    <div id="getid"></div>


    <br>

    <div id="no_print">
      <div class="collapse2" id="collapse1" align="center">
        <div class="card" style="margin-left: 10px; margin-right:10px">
        <div class="card-header bg-secondary text-white">
        <h4>GESTION DE GERENCIA Y DEPARTAMENTO DE EMPLEADOS </h4>
        </div>
          <div class="card-body" align="center">
            <div class="row" style="padding-top: 8px;">


              <div class="col-sm-3">



                <div class=" input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Identidad</span>
                  </div>
                  <input class="form-control" type="text" id="txtid" name="txtid" required="true" readonly="true">
                </div>

              </div>

              <div class="col">

                <div class=" input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Nombre</span>
                  </div>
                  <input type="text" class="form-control" id="namefam" name="namefam" required="true" readonly="true">
                </div>

              </div>

            </div>
            <div class="row">


              <div class="col">

                <div class=" input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Gerencia en que labora</span>
                  </div>
                  <select class="form-control areatrabaja" name='areatrabaja' id="areatrabaja" required="true">
                    <option value='0'> No asignado, seleccione ....</option>
                    <?php
                    $query_parent = mysqli_query($conn, 'SELECT id, descripcion FROM organizacional_gerencias WHERE categoria = 1 ORDER BY id ASC;');
                    while ($row_parents = mysqli_fetch_array($query_parent)) {
                      $idparent = $row_parents['id'];
                      $descparent = $row_parents['descripcion'];
                      echo "<option value='" . $idparent . "'> " . $descparent . "</option>";
                    }
                    ?>
                  </select>
                </div>

              </div>

              <div class="col">

                <div class=" input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Departamento en que labora</span>
                  </div>
                  <select class="form-control" name='departamentotrabaja' id="departamentotrabaja" required="true">
                    <option value='0'> No asignado, seleccione ....</option>
                    <?php
                    $query_unit = mysqli_query($conn, 'SELECT id, descripcion_unidad FROM organizacional_unidades ORDER BY id ASC;');
                    while ($row_units = mysqli_fetch_array($query_unit)) {
                      $idunit = $row_units['id'];
                      $descunit = $row_units['descripcion_unidad'];
                      echo "<option value='" . $idunit . "'> " . $descunit . "</option>";
                    }
                    ?>
                  </select>
                </div>


              </div>

            </div>
            <!--
            <div class="row" style="margin-top:2%;">
              <div class="col-sm-3"></div>
              <div class="col-sm-6" align="center">
                 <button class="btn btn-success btn-lg" type="submit" name='addfam' id="addfam" > Asignar Area de Labores</button> 
              </div>
              <div class="col-sm-3"></div>
            </div>
-->
          </div>
          <div class="card-footer">
            <button class="btn btn-primary btn-lg" type="submit" name='updfam' id="updfam" style="display:none;"> Actualizar Area de Labores</button>
          </div>
        </div>
      </div>

      <br>

      <div class="card" style="margin-left: 10px; margin-right:10px;">

      <div class="card-body">




          <table id="table_id1" class="table table-bordered table-sm table-hover " style="width:100%;   font-size:13px;">

            <thead>
              <tr>
                <th></th>
                <th>Id</th>
                <th>Cod. Empleado</th>
                <th>Nombre Completo</th>
                <th>Gerencia</th>
                <th>Departamento</th>
              </tr>
            </thead>
            <tbody>
              <?php

              $query_familiares = mysqli_query($conn, "SELECT identidad , cod_empleado, nombre_completo, fecha_ingreso  FROM rr_hh_empleados WHERE status_empleado=1 ORDER BY nombre_completo ASC;");

              if (mysqli_num_rows($query_familiares) > 0) {
                $cont = 1;
                $fila = 0;
                $area_labora = 0;
                $areal_labora_txt = '';
                $unidad_labora = 0;
                $unidad_labora_txt  = '';
                while ($_row_familiares = mysqli_fetch_array($query_familiares)) {
                  $id                        =   $_row_familiares['identidad'];
                  $_cons_identidad_persona   =   str_replace('-', '',  $id);

                  $cod_empleado           =   $_row_familiares['cod_empleado'];
                  $nombre_completo        =   $_row_familiares['nombre_completo'];
                  $fecha_ingreso          =   $_row_familiares['fecha_ingreso'];

                  $query_cons_area_labora = "SELECT  a.gerenciaid, b.descripcion, a.unidadid, c.descripcion_unidad  FROM organizacional_usuarios_gerencias a, organizacional_gerencias b, organizacional_unidades c 
                                                              WHERE a.gerenciaid=b.id and  a.unidadid = c.id and a.usuarioid ='$_cons_identidad_persona' and a.status=1  order by a.fecha_creacion desc limit 1  ;";
                  //echo $query_cons_area_labora;
                  $query_area_labora  = mysqli_query($conn, $query_cons_area_labora);
                  if (mysqli_num_rows($query_area_labora) > 0) {
                    while ($row_area_labora = mysqli_fetch_array($query_area_labora)) {
                      $area_labora     =  $row_area_labora['gerenciaid'];
                      $area_labora_txt =  $row_area_labora['descripcion'];
                      $unidad_labora     =  $row_area_labora['unidadid'];
                      $unidad_labora_txt =  $row_area_labora['descripcion_unidad'];
                    }
                  } else {
                    $area_labora     =  0;
                    $area_labora_txt =  "No ha sido asignada";
                    $unidad_labora     =  0;
                    $unidad_labora_txt =  "No ha sido asignada";
                  }
              ?>
                  <tr onclick="cargar_datos('<?php echo $id; ?>','<?php echo $nombre_completo; ?>','<?php echo $fecha_ingreso ?>','<?php echo $area_labora ?>' ,'<?php echo $unidad_labora ?>' )">

                <?php
                  echo "<td>" . $cont . "</td>
                                                <td>" . $id . "</td>
                                                 <td>" . $cod_empleado . "</td>                                                 
                                                 <td>" . $nombre_completo . "</td>
                                                 <td>" . $area_labora_txt . "</td>
                                                 <td>" . $unidad_labora_txt . "</td></tr> ";
                  $cont++;
                  $fila++;
                }
              } else {
                echo mysqli_error($conn);
              }
                ?>
            </tbody>
          </table>

        </div>
      </div>







      <?php
      if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST['addfam']) or isset($_POST['updfam'])) {

          $_ins_identidad_persona     =   str_replace('-', '',  $_POST['txtid']);
          $_ins_area_trabajo          =   $_POST['areatrabaja'];
          $_ins_unidad_trabajo        =   $_POST['departamentotrabaja'];

          $query_txt_insert_new = "INSERT INTO organizacional_usuarios_gerencias(usuarioid, gerenciaid, unidadid ) VALUES ('$_ins_identidad_persona', '$_ins_area_trabajo', '$_ins_unidad_trabajo'  );";

          //  echo $query_txt_insert_new;

          $query_insert_fam = mysqli_query($conn, $query_txt_insert_new);

          if ($query_insert_fam) {

      ?>
            <script type="text/javascript">
              swal({
                  title: "",
                  text: "agregado | Actualizado Exitosamente!.",
                  type: "success"
                })
                .then(function(result) {
                  window.location.href = window.location.href
                });
            </script>
      <?php
          } else {
            echo "<div class='alert alert-danger'> <strong> Ha Ocurrido un error " . mysqli_error($conn) . " </strong></div>";
          }

          unset($query_insert_fam);
          $_SESSION['posting'] = true;
        }
      }
      ?>
      <section>

        <!--    <div id="no_print" align="center"><button class="btn btn-danger btn-md"> Imprimir Ficha Familiar</button></div> -->
      </section>
  </form>

  <script type="text/javascript">
    $(".div_wait").fadeOut("fast");

    $("#areatrabaja").change(function(e) {
      //alert();
      var selectgerencia = document.getElementById('areatrabaja').value;
      var selectdepartamento = "_select_departamento_gerencia.php?gerencia=" + selectgerencia + "&al=" + Math.random();
      $("#departamentotrabaja").load(selectdepartamento);

    })
  </script>
</body>