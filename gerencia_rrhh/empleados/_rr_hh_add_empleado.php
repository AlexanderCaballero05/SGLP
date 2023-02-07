<?php
require('../../template/header.php');
$usuario_id = $_SESSION['usuario'];
if (isset($_GET['id'])) {
  $siid = true;
  $identidad_persona = $_GET['id'];
}


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
</style>


<script type="text/javascript">
  $(document).ready(function() {
    $("#txtid").mask("9999-9999-99999", {
      placeholder: "____-____-____ "
    });
  });

  $(document).ready(function() {
    $("#txttelefono").mask("9999-9999", {
      placeholder: "____-____"
    });
  });

  $(document).ready(function() {
    $("#txtcelular").mask("9999-9999", {
      placeholder: "____-____"
    });
  });


  function validar_campos() {


    txtid = document.getElementById('txtid').value;
    txtrtn = document.getElementById('txtrtn').value;
    txtsexo = document.getElementById('txtsexo').value;
    txtprimernombre = document.getElementById('txtprimernombre').value;
    txtprimerapellido = document.getElementById('txtprimerapellido').value;
    fechanacimiento = document.getElementById('fechanacimiento').value;

    lugarnacimiento = document.getElementById('lugarnacimiento').value;
    txtsangre = document.getElementById('txtsangre').value;
    domicilio = document.getElementById('domicilio').value;
    txttelefono = document.getElementById('txttelefono').value;
    txtcelular = document.getElementById('txtcelular').value;

    txtetcivil = document.getElementById('txtetcivil').value;
    escolaridad = document.getElementById('escolaridad').value;
    txtcodmarcacion = document.getElementById('txtcodmarcacion').value;
    txtcodempleado = document.getElementById('txtcodempleado').value;

    areatrabaja = document.getElementById('areatrabaja').value;
    categoriatrabaja = document.getElementById('categoriatrabaja').value;
    departamentotrabaja = document.getElementById('departamentotrabaja').value;
    cargotrabaja = document.getElementById('cargotrabaja').value;
    centrocosto = document.getElementById('centrocosto').value;

    fecha_ingreso = document.getElementById('fecha_ingreso').value;
    tipo_contratacion = document.getElementById('tipo_contratacion').value;
    txtsalario = document.getElementById('txtsalario').value;
    banco = document.getElementById('banco').value;
    tipo_cuenta = document.getElementById('tipo_cuenta').value;


    guardar = 1;

    if (txtid == "") {
      guardar = 0;
    } else

    if (txtsexo == "") {
      guardar = 0;
      alert("Debe seleccionar el sexo de la persona a ingresar");
      document.getElementById("general-tab").click();
      document.getElementById("txtsexo").focus();
    } else

    if (txtprimernombre == "") {
      guardar = 0;
      alert("Debe ingresar el nonbre de la persona a ingresar");
      document.getElementById("general-tab").click();
      document.getElementById("txtprimernombre").focus();
    } else

    if (txtprimerapellido == "") {
      guardar = 0;
      alert("Debe ingresar el nonbre de la persona a ingresar");
      document.getElementById("general-tab").click();
      document.getElementById("txtprimerapellido").focus();
    } else

      /*
        if (fechanacimiento == "") {
          guardar = 0;    
          alert("Debe ingresar la fecha de nacimiento de la persona a ingresar");
          document.getElementById("general-tab").click();
          document.getElementById("fechanacimiento").focus();
        }

        if (lugarnacimiento == "") {
          guardar = 0;    
          alert("Debe ingresar el lugar de nacimiento de la persona a ingresar");
          document.getElementById("general-tab").click();
          document.getElementById("lugarnacimiento").focus();
        }

        if (domicilio == "") {
          guardar = 0;    
          alert("Debe ingresar el domicilio de la persona a ingresar");
          document.getElementById("general-tab").click();
          document.getElementById("domicilio").focus();
        }

        if (txttelefono == "") {
          guardar = 0;    
          alert("Debe ingresar el domicilio de la persona a ingresar");
          document.getElementById("general-tab").click();
          document.getElementById("txttelefono").focus();
        }

        if (txtcodmarcacion == "") {
          guardar = 0;    
          alert("Debe ingresar el codigo de marcacion de la persona a ingresar");
          document.getElementById("general-tab").click();
          document.getElementById("txttelefono").focus();
        }
        */

      if (txtcodempleado == "") {
        guardar = 0;
        alert("Debe ingresar el codigo de empleado de la persona a ingresar");
        document.getElementById("general-tab").click();
        document.getElementById("txtcodempleado").focus();
      } else



    if (areatrabaja == "0") {
      guardar = 0;
      alert("Debe seleccionar gerencia de la persona a ingresar");
      document.getElementById("puesto-tab").click();
      document.getElementById("areatrabaja").focus();
    } else

    if (categoriatrabaja == "0") {
      guardar = 0;
      alert("Debe seleccionar la categoria de la persona a ingresar");
      document.getElementById("puesto-tab").click();
      document.getElementById("categoriatrabaja").focus();
    } else

    if (departamentotrabaja == "") {
      guardar = 0;
      alert("Debe seleccionar el departamento de la persona a ingresar");
      document.getElementById("puesto-tab").click();
      document.getElementById("departamentotrabaja").focus();
    } else

    if (cargotrabaja == "0") {
      guardar = 0;
      alert("Debe seleccionar el cargo de la persona a ingresar");
      document.getElementById("puesto-tab").click();
      document.getElementById("cargotrabaja").focus();
    } else

    if (centrocosto == "0") {
      guardar = 0;
      alert("Debe seleccionar el centro de costo de la persona a ingresar");
      document.getElementById("puesto-tab").click();
      document.getElementById("centrocosto").focus();
    } else






    if (fecha_ingreso == "") {
      guardar = 0;
      alert("Debe ingresar una fecha de ingreso");
      document.getElementById("contratación-salario-tab").click();
      document.getElementById("fecha_ingreso").focus();
    } else

    if (tipo_contratacion == "") {
      guardar = 0;
      alert("Debe seleccionar el tipo de contratación de la persona a ingresar");
      document.getElementById("contratación-salario-tab").click();
      document.getElementById("tipo_contratacion").focus();
    } else

    if (txtsalario == "") {
      guardar = 0;
      alert("Debe ingresar el salario de la persona a ingresar");
      document.getElementById("contratación-salario-tab").click();
      document.getElementById("txtsalario").focus();
    } else

    if (forma_pago == "") {
      guardar = 0;
      alert("Debe seleccionar una forma de pago");
      document.getElementById("contratación-salario-tab").click();
      document.getElementById("forma_pago").focus();
    } else

    if (banco == "") {
      guardar = 0;
      alert("Debe seleccionar un banco");
      document.getElementById("contratación-salario-tab").click();
      document.getElementById("banco").focus();
    } else

    if (tipo_cuenta == "") {
      guardar = 0;
      alert("Debe seleccionar un tipo de cuenta");
      document.getElementById("contratación-salario-tab").click();
      document.getElementById("tipo_cuenta").focus();
    }


    if (guardar == 1) {
      document.getElementById("guardar_nuevo").click();
    }

  }


  $(".div_wait").fadeIn("fast");
</script>


<form method="post">
  <div id='div_wait'></div>

  <section style="text-align: center; background-color:#ededed; padding-top: 10px;">
    <p>
      <h3>ALTA DE EMPLEADOS DEL PANI </h3>
    </p><br>
  </section>

  <br>

  <ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-left: 5px;margin-right: 5px;">
    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="general-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Información General</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="puesto-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Puesto</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="contratación-salario-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contratación y Salario</a>
    </li>
  </ul>



  <div class="tab-content" id="myTabContent" style="border: solid; margin-left:5px; margin-right:5px; border-width:0.2px; border-color:#dbdbdb">

    <!-- INICIO DE INFORMACION GENERAL -->
    <!-- INICIO DE INFORMACION GENERAL -->
    <!-- INICIO DE INFORMACION GENERAL -->

    <div class="tab-pane fade show active" style="margin-left: 5px; margin-right:5px;" id="home" role="tabpanel" aria-labelledby="home-tab">

      <br>

      <div class="row">
        <div class="col">

          <?php if ($siid == true) : ?>


            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" style="width:180px">Identidad</span>
              </div>
              <input type="text" id="txtid" name="txtid" class="form-control" value="<?php echo $identidad_persona ?>" readonly>
            </div>

          <?php else : ?>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" style="width:180px">Identidad</span>
              </div>
              <input type="text" id="txtid" name="txtid" class="form-control" style="width: 95%">
            </div>


          <?php endif ?>


          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" style="width:180px">RTN</span>
            </div>
            <input type="text" id="txtrtn" name="txtrtn" class="form-control" onkeypress="return soloLetras(event)" maxlength="14">
          </div>

          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" style="width:180px">Sexo</span>
            </div>
            <select class="form-control" name='txtsexo' id='txtsexo'>
              <option value=""> Seleccione ....</option>
              <option value="M"> MASCULINO </option>
              <option value="F"> MUJER </option>
            </select>
          </div>


          <?php if ($siid == true) :
            $query_info_persona = mysqli_query($conn, "SELECT primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, fecha_nacimiento_txt FROM censo_2017 where identidad='$identidad_persona'");

            while ($row_info_persona = mysqli_fetch_array($query_info_persona)) {
              $primer_nombre_info    = $row_info_persona['primer_nombre'];
              $segundo_nombre_info   = $row_info_persona['segundo_nombre'];
              $primer_apellido_info  = $row_info_persona['primer_apellido'];
              $segundo_apellido_info = $row_info_persona['segundo_apellido'];
              $fecha_nacimiento_info = $row_info_persona['fecha_nacimiento_txt'];
              $fecha_nacimiento_info = date("d/m/Y", strtotime($fecha_nacimiento_info));
              $fecha_nacimiento_save = $fecha_nacimiento_info;
              $fch = explode("/", $fecha_nacimiento_save);
              $tfecha = $fch[2] . "-" . $fch[1] . "-" . $fch[0];
            }


          ?>



            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" style="width:180px">Nombre Completo</span>
              </div>

              <input type="text" class="form-control" id="txtprimernombre" name="txtprimernombre" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' value="<?php echo $primer_nombre_info ?>" readonly>
              <input type="text" class="form-control" id="txtsgdonombre" name="txtsgdonombre" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' value="<?php echo $segundo_nombre_info ?>" readonly>
              <input type="text" class="form-control" id="txtprimerapellido" name="txtprimerapellido" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' value="<?php echo $primer_apellido_info ?>" readonly>
              <input type="text" class="form-control" id="txtsegundoapellido" name="txtsegundoapellido" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' value="<?php echo $segundo_apellido_info ?>" readonly>

            </div>



            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" style="width:180px">Fecha de Nacimiento</span>
              </div>
              <input type="text" id="fechanacimiento" class="form-control" name="fechanacimiento" value="<?php echo $fecha_nacimiento_info ?>" readonly>
            </div>



          <?php else : ?>


            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" style="width:180px">Nombre Completo</span>
              </div>

              <input type="text" class="form-control" id="txtprimernombre" name="txtprimernombre" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' value="<?php echo $primer_nombre ?>">
              <input type="text" class="form-control" id="txtsgdonombre" name="txtsgdonombre" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' value="<?php echo $segundo_nombre ?>">
              <input type="text" class="form-control" id="txtprimerapellido" name="txtprimerapellido" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' value="<?php echo $primer_apellido ?>">
              <input type="text" class="form-control" id="txtsegundoapellido" name="txtsegundoapellido" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' value="<?php echo $segundo_apellido ?>">

            </div>


            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" style="width:180px">Fecha de Nacimiento</span>
              </div>
              <input class="form-control" type="date" id="fechanacimiento" name="fechanacimiento" style="width:95%" readonly="true">
            </div>


          <?php endif ?>



          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" style="width:180px">Lugar de nacimiento</span>
            </div>

            <select class="form-control" name='lugarnacimiento' id='lugarnacimiento'>
              <option value=''> Seleccione ....</option>
              <?php
              $query_lugar_nac = mysqli_query($conn, "SELECT cod_muni, municipio FROM geocodigos");

              while ($row_muni = mysqli_fetch_array($query_lugar_nac)) {
                $codigo_muni = $row_muni['cod_muni'];
                $municipio   = $row_muni['municipio'];

                echo "<option value='" . $codigo_muni . "'> " . $codigo_muni . " --  " . utf8_decode($municipio) . "</option>";
              }
              unset($query_lugar_nac);
              ?>
            </select>

          </div>



          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" style="width:180px">Tipo de Sangre</span>
            </div>
            <select class="form-control" name='txtsangre' id="txtsangre">
              <option value=''> Seleccione ....</option>
              <option value='A+'> A (+) </option>
              <option value='A-'> A (-) </option>
              <option value='AB+'> AB (+) </option>
              <option value='AB-'> AB (-) </option>
              <option value='B+'> B (+) </option>
              <option value='B-'> B (-) </option>
              <option value='O+'> O (+) </option>
              <option value='O-'> O (-) </option>
            </select>
          </div>


        </div> <!--  END of COL -->

        <div class='col'>




          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text">Domicilio</span>
            </div>
            <textarea type='form-control' rows="2" id='domicilio' name='domicilio' style="width: 88%" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()'></textarea>
          </div>


          <div class="input-group mb-3">
            <div class="input-group-prepend">
               <span class="input-group-text" style="width:180px">Email</span>
            </div>
             <input type="text" id="txtemail" class="form-control" name="txtemail">

          </div>


          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" style="width:180px">Telefono Domicilio</span>
            </div>
            <input type="text" id="txttelefono" class="form-control" name="txttelefono" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()'>
          </div>



          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" style="width:180px">Telefono Celular</span>
            </div>
            <input type="text" id="txtcelular" class="form-control" name="txtcelular" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()'>
          </div>


          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" style="width:180px">Estado Civil</span>
            </div>
            <select class="form-control" name='txtetcivil' id='txtetcivil'>
              <option value=""> Seleccione ....</option>
              <option value="1"> CASAD@ </option>
              <option value="2"> VIUD@ </option>
              <option value="3"> DIVORCIAD@ </option>
              <option value="4"> SEPARAD@ </option>
              <option value="5"> SOLTER@ </option>
              <option value="6"> UNION LIBRE </option>
              <option value="99"> No Sabe/Responde </option>
            </select>
          </div>


          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" style="width:180px">Escolaridad</span>
            </div>
            <select class="form-control" name='escolaridad' id='escolaridad'>
              <option value=""> Seleccione ....</option>
              <option value="1"> Ninguno </option>
              <option value="2"> Programa de Alfabetizacion </option>
              <option value="3"> Pre-Básica (1-3) </option>
              <option value="4"> Básica (1-9) </option>
              <option value="5"> Ciclo Común </option>
              <option value="6"> Diversificado </option>
              <option value="7"> Técnico Superior </option>
              <option value="8"> Superior No Universitaria </option>
              <option value="9"> Superior Universitario </option>
              <option value="10"> Post Grado </option>
              <option value="99"> No Sabe/Responde </option>
            </select>
          </div>


          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" style="width:180px">Codigo de Marcacion</span>
            </div>
            <input type="number" id="txtcodmarcacion" name="txtcodmarcacion" class='form-control' onkeypress="return justNumbers(event)">
          </div>

          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" style="width:180px">Codigo de Empleado</span>
            </div>
            <input type="number" id="txtcodempleado" name="txtcodempleado" class='form-control' onkeypress="return justNumbers(event)">
          </div>



        </div>

      </div>
    </div>

    <!-- FIN DE INFORMACION GENERAL -->
    <!-- FIN DE INFORMACION GENERAL -->
    <!-- FIN DE INFORMACION GENERAL -->

    <!-- INICIO DE INFORMACION PUESTO -->
    <!-- INICIO DE INFORMACION PUESTO -->
    <!-- INICIO DE INFORMACION PUESTO -->

    <div class="tab-pane fade" id="profile" style="margin-left: 5px; margin-right:5px;" role="tabpanel" aria-labelledby="profile-tab">


      <br>

      <div class="row">
        <div class="col">


          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" style="width:220px">Gerencia</span>
            </div>
            <select class="form-control areatrabaja" name='areatrabaja' id="areatrabaja" >
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



          <div class=" input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" style="width:220px">Categoria</span>
              </div>
              <select class="form-control" name='categoriatrabaja' id="categoriatrabaja">
                <option value='0'> No asignado, seleccione ....</option>
                <?php
                $query_parent = mysqli_query($conn, 'SELECT id, descripcion FROM organizacional_categorias ORDER BY id ASC;');
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

          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" style="width:180px">Departamento</span>
            </div>
            <select class="form-control" name='departamentotrabaja' id="departamentotrabaja" >
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


          <div class=" input-group mb-3">
              <div class=" input-group-prepend">
                <span class="input-group-text" style="width:180px">Cargo</span>
              </div>
              <select class="form-control" name='cargotrabaja' id="cargotrabaja">
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

      <div class="row">
        <div class="col">
          <div class=" input-group mb-3">
            <div class=" input-group-prepend">
              <span class="input-group-text" style="width:220px">Centro de Costo Asignado</span>
            </div>
            <select class="form-control" name='centrocosto' id="centrocosto">
              <option value='0'> No asignado, seleccione ....</option>
              <?php
              $query_parent = mysqli_query($conn, 'SELECT id, descripcion, centro FROM pre_centros_costos ORDER BY descripcion ASC;');
              while ($row_parents = mysqli_fetch_array($query_parent)) {
                $idparent = $row_parents['id'];
                $descparent = $row_parents['descripcion'];
                $centro = $row_parents['centro'];
                echo "<option value='" . $idparent . "'> " . $centro . " - " . $descparent . "</option>";
              }
              ?>
            </select>
          </div>
        </div>
      </div>

    </div>


    <!-- FIN DE INFORMACION PUESTO -->
    <!-- FIN DE INFORMACION PUESTO -->
    <!-- FIN DE INFORMACION PUESTO -->


    <!-- INICIO DE INFORMACION CONTRATACION -->
    <!-- INICIO DE INFORMACION CONTRATACION -->
    <!-- INICIO DE INFORMACION CONTRATACION -->


    <div class=" tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

      <br>

      <div class="row" style="margin-left: 2px; margin-right:2px">
        <div class="col">

          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text">Fecha De Ingreso</span>
            </div>
            <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso">
          </div>


        </div>
        <div class="col col-md-6">


          <?php
          $c_tipo_contrataciones = mysqli_query($conn, "SELECT * FROM rr_hh_mto_contrataciones WHERE estado = 'A' ");
          ?>

          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text">Tipo De Contrato</span>
            </div>
            <select class="form-control" id="tipo_contratacion" name="tipo_contratacion">
              <?php
              while ($reg_tipo_cont = mysqli_fetch_array($c_tipo_contrataciones)) {
                echo "<option value = '" . $reg_tipo_cont['id'] . "'>" . $reg_tipo_cont['descripcion'] . "</option>";
              }
              ?>
            </select>
          </div>


        </div>
        <div class="col">

          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text">Salario Base</span>
            </div>
            <input type="text" class="form-control" id="txtsalario" name="txtsalario" >
          </div>

        </div>
      </div>



      <div class="row" style="margin-left: 2px; margin-right:2px">

        <div class="col">
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text">Forma de Pago</span>
            </div>
            <select class="form-control" id="forma_pago" name="forma_pago">
              <option value="1">Transferencia</option>
              <option value="2">Cheque</option>
            </select>
          </div>
        </div>

        <div class="col col-md-6">
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text">Banco</span>
            </div>
            <select class="form-control" id="banco" name="banco">
              <option value="1">BANCO CENTRAL DE HONDURAS</option>
              <option value="2">BAC CREDOMATIC</option>
            </select>
          </div>
        </div>

        <div class="col">
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text">Tipo de Cuenta</span>
            </div>
            <select class="form-control" id="tipo_cuenta" name="tipo_cuenta">
              <option value="1">AHORRO</option>
              <option value="2">CHEQUE</option>
            </select>
          </div>
        </div>

      </div>





    </div>

    <!-- FIN DE INFORMACION CONTRATACION -->
    <!-- FIN DE INFORMACION CONTRATACION -->


  </div>

  <br>

  <div style="text-align: center; ">

    <span class="btn btn-success" onclick="validar_campos()"> Guardar Registro</span>

    <button type="submit" style="visibility:hidden" id="guardar_nuevo" name="guardar_nuevo"> Guardar Registro</button>
  </div>


</form>


<?php
if (isset($_POST['guardar_nuevo'])) {

  $_identidad          = $_POST['txtid'];
  $_ins_identidad      = str_replace('-', '',  $_identidad);
  $_cod_empleados      = $_POST['txtcodempleado'];
  $_codmarcacion       = $_POST['txtcodmarcacion'];
  $_rtn                = $_POST['txtrtn'];
  $_sexo               = $_POST['txtsexo'];
  //   $_fecha_nacimiento   = $_POST['fechanacimiento'];
  $_fecha_nacimiento   = date('Y-m-d', strtotime($fecha_nacimiento_info));
  $_lugar_nacimiento   = $_POST['lugarnacimiento'];
  $_tipo_sangre        = $_POST['txtsangre'];
  $_primer_nombre      = $_POST['txtprimernombre'];
  $_segundo_nombre     = $_POST['txtsgdonombre'];
  $_primer_apellido    = $_POST['txtprimerapellido'];
  $_segundo_apellido   = $_POST['txtsegundoapellido'];
  $_nombre_completo    = $_primer_nombre . " " . $_segundo_nombre  . " " . $_primer_apellido . " " . $_segundo_apellido;

  $_fecha_ingreso      = $_POST['fecha_ingreso'];
  $_fecha_ingreso      = date('Y-m-d', strtotime($_fecha_ingreso));
  $_domicilio          = $_POST['domicilio'];
  $_telefono           = $_POST['txttelefono'];
  $_email              = $_POST['txtemail'];
  $_celular            = $_POST['txtcelular'];
  $_estado_civil       = $_POST['txtetcivil'];
  $_escolaridad        = $_POST['escolaridad'];



  $query_insert_empleado = "INSERT INTO rr_hh_empleados(cod_empleado,     cod_marcacion,      identidad,    rtn,      sexo,      fecha_nacimiento,      lugar_nacimiento,       tipo_sangre,     nombre_completo,      primer_nombre,   segundo_nombre,        primer_apellido,       segundo_apellido,      fecha_ingreso,      domicilio,      telefono,     celular ,  estado_civil,  escolaridad , mail) 
                                                  VALUES ($_cod_empleados, $_codmarcacion,  '$_ins_identidad', '$_rtn' , '$_sexo' , '$tfecha' , '$_lugar_nacimiento' , '$_tipo_sangre',  '$_nombre_completo', '$_primer_nombre' , '$_segundo_nombre' , '$_primer_apellido' ,  '$_segundo_apellido',  '$_fecha_ingreso', '$_domicilio' , '$_telefono', '$_celular' , '$_estado_civil', '$_escolaridad', '$_email');";





  ////////////////////////////////////////////////
  // DATOS QUE FALTABAN PARA MODULO DE PLANILLAS

  $_tipo_contratacion       = $_POST['tipo_contratacion'];
  $_salario_base        = $_POST['txtsalario'];

  $areatrabaja          = $_POST['areatrabaja'];
  $departamentotrabaja  = $_POST['departamentotrabaja'];
  
  // INSERT GERENCIA EMPLEADO Y DEPTO EMPLEADO
mysqli_query($conn, "INSERT INTO organizacional_usuarios_gerencias(usuarioid, gerenciaid, unidadid ) VALUES ('$_ins_identidad', '$areatrabaja', '$departamentotrabaja'  ) ");


  // INSERT CATEGORIA EMPLEADO
  $categoriatrabaja     = $_POST['categoriatrabaja'];
mysqli_query($conn, "INSERT INTO organizacional_usuarios_categorias(usuarioid, categoriaid ) VALUES('$_ins_identidad', '$categoriatrabaja') ");


  // INSERT PUESTO EMPLEADO
  $cargotrabaja         = $_POST['cargotrabaja'];
mysqli_query($conn, "INSERT INTO organizacional_usuarios_puestos(usuarioid, puestoid ) VALUES('$_ins_identidad', '$cargotrabaja')");


  // INSERT CentroCosto EMPLEADO
  $centrocosto       = $_POST['centrocosto'];
  mysqli_query($conn, "INSERT INTO rr_hh_empleados_centro_costos (usuarioid, centro_costo_id ) VALUES('$_ins_identidad', '$centrocosto')");
  
//  echo mysqli_error($conn);

/// INSERT PARAMETROS DE SALARIO Y CONTRATACION
mysqli_query($conn, "INSERT INTO rr_hh_tipo_contrato_salarios (cod_empleado, identidad, tipo_contratacion, fecha_inicio, salario_base, status) VALUES ('$_cod_empleados', '$_ins_identidad', '$_tipo_contratacion', '$_fecha_ingreso', '$_salario_base', 'A') ");


/// INSERT PARAMETROS DE FORMA DE PAGO

$forma_pago =  $_POST['forma_pago'];
$banco =  $_POST['banco'];
$tipo_cuenta =  $_POST['tipo_cuenta'];
 mysqli_query($conn, "INSERT INTO rr_hh_empleados_forma_pago (usuarioid, forma_pago, banco, tipo_cuenta) VALUES ('$_ins_identidad', '$forma_pago', '$banco', '$tipo_cuenta') ");

// echo mysqli_error($conn);



  // DATOS QUE FALTABAN PARA MODULO DE PLANILLAS
  ////////////////////////////////////////////////


  
  //echo $query_insert_empleado;


  if (mysqli_query($conn, $query_insert_empleado)) {
    echo "<div class='alert alert-success'> Se ha registrado el empleado correctamente </div>";


?>
    <script type="text/javascript">
      swal({
          title: "",
          text: "Agregado | Actualizado Exitosamente!.",
          type: "success"
        })
        .then(function(result) {
          window.location.href = "./screen_consulta_expediente.php";
        });
    </script>
<?php



  } else {
    echo "<div class='alert alert-danger'> Error : " . mysqli_error($conn) . " </div>";
  }
}
?>


<script type="text/javascript">
  $("#areatrabaja").change(function(e) {
    var selectgerencia = document.getElementById('areatrabaja').value;
    var selectdepartamento = "_select_departamento_gerencia.php?gerencia=" + selectgerencia + "&al=" + Math.random();
    $("#departamentotrabaja").load(selectdepartamento);

  })
  $(".div_wait").fadeOut("fast");
</script>
