<?php
require('../../template/header.php');
$usuario_id = $_SESSION['usuario'];
if (isset($_GET['id'])) {
  $siid = true;
  $identidad_persona = $_GET['id'];
}


?>
<style type="text/css" media="print">
  @page {
    size: portrait;
  }

  th,
  td {
    padding-bottom: 0px;
    border-spacing: 0;
    font-family: Arial;
    font-size: 09pt;
  }
</style>

<style type="text/css">
  /* form starting stylings ------------------------------- */
  .group {
    position: relative;
    margin-bottom: 25px;
  }

  input {
    font-size: 18px;
    padding: 10px 10px 10px 5px;
    display: block;
    width: 100%;
    border: none;
    border-bottom: 1px solid #757575;
  }

  input:focus {
    outline: none;
  }

  /* LABEL ======================================= */
  label {
    color: #999;
    font-size: 20px;
    font-weight: normal;
    position: absolute;
    pointer-events: none;
    left: 25px;
    top: -30px;
    transition: 0.2s ease all;
    -moz-transition: 0.2s ease all;
    -webkit-transition: 0.2s ease all;
  }

  /* active state */
  input:focus~label,
  input:valid~label {
    top: -20px;
    font-size: 18px;
    color: #5264AE;
  }

  .borderless td,
  .borderless th {
    border: none;
  }

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


  $(".div_wait").fadeIn("fast");
</script>


<form method="post" class="form-control">
  <div id='div_wait'></div>

  <section style="text-align: center; background-color:#ededed; padding-top: 10px;">
    <p>
      <h3>ALTA DE EMPLEADOS DEL PANI </h3>
    </p><br>
  </section>


  <section id="no_print">
    <div class="container-fluid" style="width: 80%">
      <div class="row">
        <div class="col-sm-6"><br>
          <?php if ($siid == true) : ?>

            <div class="group input-group" style="margin-top:10%;">
              <input type="text" id="txtid" name="txtid" required="true" style="width: 95%" value="<?php echo $identidad_persona ?>" readonly>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Identidad </label>
            </div>
          <?php else : ?>

            <div class="group input-group" style="margin-top:10%;">
              <input type="text" id="txtid" name="txtid" required="true" style="width: 95%">
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Identidad </label>
            </div>

          <?php endif ?>


          <div class="group input-group" style="margin-top:10%;">
            <input type="text" id="txtrtn" name="txtrtn" style="width: 95%" onkeypress="return soloLetras(event)" maxlength="14">
            <span class="highlight"></span>
            <span class="bar"></span>
            <label>RTN </label>
          </div>


          <div class="group input-group" style="margin-top:10%; width:95%">
            <select class="form-control" name='txtsexo' id='txtsexo'>
              <option value=""> Seleccione ....</option>
              <option value="M"> MASCULINO </option>
              <option value="F"> MUJER </option>
            </select>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label>SEXO </label>
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

            <div class="group input-group" style="margin-top:10%;">
              <input type="text" id="txtprimernombre" name="txtprimernombre" style="width: 20%;" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' required="true" value="<?php echo $primer_nombre_info ?>" readonly>
              <input type="text" id="txtsgdonombre" name="txtsgdonombre" style="width:20%; margin-left:5%" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' required="true" value="<?php echo $segundo_nombre_info ?>" readonly>
              <input type="text" id="txtprimerapellido" name="txtprimerapellido" style="width: 20%;  margin-left:5%;" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' required="true" value="<?php echo $primer_apellido_info ?>" readonly>
              <input type="text" id="txtsegundoapellido" name="txtsegundoapellido" style="width: 20%;  margin-left:5%;" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' required="true" value="<?php echo $segundo_apellido_info ?>" readonly>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Nombre Completo </label>
            </div>

            <div class="group input-group" style="margin-top:10%;">
              <input type="text" id="fechanacimiento" name="fechanacimiento" style="width:95%" value="<?php echo $fecha_nacimiento_info ?>" readonly>
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Fecha de Nacimiento </label>
            </div>

          <?php else : ?>

            <div class="group input-group" style="margin-top:10%;">
              <input type="text" id="txtprimernombre" name="txtprimernombre" style="width: 20%;" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' required="true" value="<?php echo $primer_nombre ?>" >
              <input type="text" id="txtsgdonombre" name="txtsgdonombre" style="width:20%; margin-left:5%" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' required="true" value="<?php echo $segundo_nombre ?>">
              <input type="text" id="txtprimerapellido" name="txtprimerapellido" style="width: 20%;  margin-left:5%;" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' required="true" value="<?php echo $primer_apellido ?>">
              <input type="text" id="txtsegundoapellido" name="txtsegundoapellido" style="width: 20%;  margin-left:5%;" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' required="true" value="<?php echo $segundo_apellido ?>">
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Nombre Completo </label>
            </div>

            <div class="group input-group" style="margin-top:10%;">
              <input type="date" id="fechanacimiento" name="fechanacimiento" style="width:95%" readonly="true">
              <span class="highlight"></span>
              <span class="bar"></span>
              <label>Fecha de Nacimiento </label>
            </div>

          <?php endif ?>
          <div class="group input-group" style="margin-top:10%; width:95%">
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
            <span class="highlight"></span>
            <span class="bar"></span>
            <label>Lugar de Nacimiento </label>
          </div>

          <div class="group input-group" style="margin-top:10%; width:95%">
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
            <span class="highlight"></span>
            <span class="bar"></span>
            <label>Tipo de Sangre </label>
          </div>

          <div class="group input-group" style="margin-top:10%; width:95%">
            <textarea type='form-control' rows="5" id='domicilio' name='domicilio' style="width: 100%" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()'></textarea>

            <span class="highlight"></span>
            <span class="bar"></span>
            <label>Domicilio </label>
          </div>

        </div>
        <div class="col-sm-6"><br>

          <div class="group input-group" style="margin-top:10%;">
            <input type="text" id="txttelefono" name="txttelefono" style="width: 95%" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()'>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label>Telefono Domicilio</label>
          </div>

          <div class="group input-group" style="margin-top:10%;">
            <input type="text" id="txtcelular" name="txtcelular" style="width: 95%" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()'>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label>Telefono Celular </label>
          </div>

          <div class="group input-group" style="margin-top:10%; width:95%">
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
            <span class="highlight"></span>
            <span class="bar"></span>
            <label>Estado Civil </label>
          </div>


          <div class="group input-group" style="margin-top:10%; width:95%">
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
            <span class="highlight"></span>
            <span class="bar"></span>
            <label>Escolaridad </label>
          </div>


          <div class="group input-group" style="margin-top:10%; width:95%">
            <input type="date" name="fecha_ingreso" required>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label>Fecha de Ingreso </label>
          </div>

          <?php

          $c_tipo_contrataciones = mysqli_query($conn, "SELECT * FROM rr_hh_mto_contrataciones WHERE estado = 'A' ");

          ?>

          <div class="group input-group" style="margin-top:10%; width:95%">
            <select class="form-control" id="tipo_contratacion" name="tipo_contratacion" required>
              <?php
              while ($reg_tipo_cont = mysqli_fetch_array($c_tipo_contrataciones)) {
                echo "<option value = '" . $reg_tipo_cont['id'] . "'>" . $reg_tipo_cont['descripcion'] . "</option>";
              }
              ?>
            </select>
            <label>Tipo de contratación </label>
          </div>

          <div class="group input-group" style="margin-top:10%;">
            <input type="number" id="txtsalario" name="txtsalario" style="width: 95%" onkeypress="return justNumbers(event)" required>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label>Salario Base </label>
          </div>


          <div class="group input-group" style="margin-top:10%;">
            <input type="number" id="txtcodempleado" name="txtcodempleado" style="width: 95%" onkeypress="return justNumbers(event)" required>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label>Codigo de Empleado </label>
          </div>

          <div class="group input-group" style="margin-top:10%;">
            <input type="number" id="txtcodmarcacion" name="txtcodmarcacion" style="width: 95%" onkeypress="return justNumbers(event)" required>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label>Codigo de Marcacion </label>
          </div>

        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
          <button type="submit" class="btn btn-success btn-lg  btn-block"> Guardar Registro</button>
        </div>
        <div class="col-sm-3"></div>
      </div>
    </div>
  </section>
  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
    $_celular            = $_POST['txtcelular'];
    $_estado_civil       = $_POST['txtetcivil'];
    $_escolaridad        = $_POST['escolaridad'];

    $_tipo_contratacion       = $_POST['tipo_contratacion'];
    $_salario_base        = $_POST['txtsalario'];


    $query_insert_empleado = "INSERT INTO rr_hh_empleados(cod_empleado,     cod_marcacion,      identidad,    rtn,      sexo,      fecha_nacimiento,      lugar_nacimiento,       tipo_sangre,     nombre_completo,      primer_nombre,   segundo_nombre,        primer_apellido,       segundo_apellido,      fecha_ingreso,      domicilio,      telefono,     celular ,  estado_civil,       escolaridad) 
                                                  VALUES ($_cod_empleados, $_codmarcacion,  '$_ins_identidad', '$_rtn' , '$_sexo' , '$tfecha' , '$_lugar_nacimiento' , '$_tipo_sangre',  '$_nombre_completo', '$_primer_nombre' , '$_segundo_nombre' , '$_primer_apellido' ,  '$_segundo_apellido',  '$_fecha_ingreso', '$_domicilio' , '$_telefono', '$_celular' , '$_estado_civil', '$_escolaridad');";


    mysqli_query($conn, "INSERT INTO rr_hh_tipo_contrato_salarios (cod_empleado, identidad, tipo_contratacion, fecha_inicio, salario_base, status) VALUES ('$_cod_empleados', '$_ins_identidad', '$_tipo_contratacion', '$_fecha_ingreso', '$_salario_base', 'A') ");

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

</form>

<script type="text/javascript">
  $(".div_wait").fadeOut("fast");
</script>