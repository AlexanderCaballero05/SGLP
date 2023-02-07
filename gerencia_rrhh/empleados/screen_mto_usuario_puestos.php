<?php
require('../../template/header.php');
$usuario_id = $_SESSION['usuario'];
?>

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

  @media print {
    #no_print {
      display: none;
    }
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

  function cargar_datos(idn, nom, fecha_ingreso, area_antigua) {
 
    document.getElementById('updfam').style.display = "block";
    

    document.getElementById('txtid').value = idn;
    document.getElementById('namefam').value = nom;
    document.getElementById('areatrabaja').value = area_antigua;



    /*  var txtocupation = document.getElementById('areatrabaja').options[document.getElementById('areatrabaja').selectedIndex].text;        
      document.getElementById('areatrabaja').value = txtocupation; */
  }
</script>

<body onload="valida_beneficiario()">
  <form method="post">
    <div id='div_wait'></div>
    <div id="getid"></div>

    <br>

    <section id="no_print">
      <div class="collapse2" id="collapse1" align="center">
        <div class="card">

          <div class="card-header bg-secondary text-white">
            <h4>GESTION DE PUESTO DE EMPLEADOS </h4>
          </div>


          <div class="card-body" align="center">
            <div class="row" style="padding-top: 8px; ">

              <div class="col-sm-3">


                <div class=" input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Identidad</span>
                  </div>
                  <input class="form-control" type="text" id="txtid" name="txtid" required="true" readonly="true">
                </div>


              </div>
              <div class="col-sm-4">

                <div class=" input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Nombre</span>
                  </div>
                  <input type="text" class="form-control" id="namefam" name="namefam" required="true" readonly="true">
                </div>

              </div>

              <div class="col">

                <div class=" input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Puesto</span>
                  </div>
                  <select class="form-control" name='areatrabaja' id="areatrabaja" required="true">
                    <option value='0'> No asignado, seleccione ....</option>
                    <?php
                    $query_parent = mysqli_query($conn, 'SELECT id, descripcion FROM organizacional_puestos ORDER BY id ASC;');
                    while ($row_parents = mysqli_fetch_array($query_parent)) {
                      $idparent = $row_parents['id'];
                      $descparent = $row_parents['descripcion'];
                      echo "<option value='" . $idparent . "'> " . $descparent . "</option>";
                    }
                    ?>
                  </select>
                </div>

              </div>

            </div>
          </div>
          <div class="card-footer">
          <button class="btn btn-primary btn-lg" type="submit" name='updfam' id="updfam" style="display:none;"> Actualizar Puesto</button>
          </div>

        </div>
    </section>
    <hr>

    <section id="no_print">
      <div class="container-fluid" style="width: 100%">
        <div class="row">
          <div class="col-sm-12" align="left">
            <div id="msg_valid_benef"></div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12"><br>
            <div class="table-responsive">
              <table id="table_id2" class="table table-bordered table-sm table-hover" cellspacing="0" style="width:100%;   font-size:13px;">
                <thead>
                  <tr>
                    <th></th>
                    <th>Id</th>
                    <th>Cod. Empleado</th>
                    <th>Nombre Completo</th>
                    <th>Fecha de Ingreso</th>
                    <th>Puesto</th>
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
                    while ($_row_familiares = mysqli_fetch_array($query_familiares)) {
                      $id                        =   $_row_familiares['identidad'];
                      $_cons_identidad_persona   =   str_replace('-', '',  $id);

                      $cod_empleado           =   $_row_familiares['cod_empleado'];
                      $nombre_completo        =   $_row_familiares['nombre_completo'];
                      $fecha_ingreso          =   $_row_familiares['fecha_ingreso'];

                      $query_cons_area_labora = "SELECT  a.puestoid, b.descripcion  FROM organizacional_usuarios_puestos a, organizacional_puestos b WHERE a.puestoid=b.id and a.usuarioid ='$_cons_identidad_persona' and a.status=1  order by a.fecha_creacion desc limit 1  ;";
                      //  echo $query_cons_area_labora;
                      $query_area_labora  = mysqli_query($conn, $query_cons_area_labora);
                      if (mysqli_num_rows($query_area_labora) > 0) {
                        while ($row_area_labora = mysqli_fetch_array($query_area_labora)) {
                          $area_labora     =  $row_area_labora['puestoid'];
                          $area_labora_txt =  $row_area_labora['descripcion'];
                        }
                      } else {
                        $area_labora     =  0;
                        $area_labora_txt =  "No ha sido asignada";
                      }
                  ?>
                      <tr onclick="cargar_datos('<?php echo $id; ?>','<?php echo $nombre_completo; ?>','<?php echo $fecha_ingreso ?>','<?php echo $area_labora ?>')">

                    <?php
                      echo "<td>" . $cont . "</td>
                                                <td>" . $id . "</td>
                                                 <td>" . $cod_empleado . "</td>                                                 
                                                 <td>" . $nombre_completo . "</td>
                                                 <td>" . $fecha_ingreso . "</td> 
                                                 <td>" . $area_labora_txt . "</td></tr> ";
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
          <hr>
        </div>
      </div>
    </section>
    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {



      if (isset($_POST['addfam']) or isset($_POST['updfam'])) {

        $_ins_identidad_persona   =   str_replace('-', '',  $_POST['txtid']);
        $_ins_area_trabajo        =   $_POST['areatrabaja'];

        $query_txt_insert_new = "INSERT INTO organizacional_usuarios_puestos(usuarioid, puestoid ) VALUES('$_ins_identidad_persona', '$_ins_area_trabajo');";

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
      }
    }
    ?>
    <!--section><hr>
    <div id="no_print" align="center"><button class="btn btn-danger btn-md"> Imprimir Ficha Familiar</button></div>
 </section -->
  </form>

  <script type="text/javascript">
    $(".div_wait").fadeOut("fast");
  </script>
</body>