<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<?php
require('../../template/header.php');
require('./mercadeo_gestion_vendedores_db.php');
mysqli_query($conn,"SET CHARACTER SET 'utf8'");
mysqli_query($conn,"SET SESSION collation_connection ='utf8_unicode_ci'");

?>
          <!-- 0801198514372 -->


<script type="text/javascript" src="./js/jquery.maskedinput.js"></script>

<script type="text/javascript">
  //////////////////////////////////// 
  // FUNCIONES DE CARGADO DE IMAGEN //  

  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function(e) {
        $('#vista_previa').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }

  function readURL2(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function(e) {
        $('#vista_previa_edicion').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }

  // FUNCIONES DE CARGADO DE IMAGEN //  
  //////////////////////////////////// 




  /////////////////////////////////////
  // FUNCIONES ENMASCARADO DE INPUTS //

  jQuery(function($) {
    $("#nuevo_id").mask("9999-9999-99999", {
      placeholder: "____-____-_____"
    });

    $("#nuevo_identidad_familiar").mask("9999-9999-99999", {
      placeholder: "____-____-_____"
    });

    $("#nuevo_identidad_beneficiario").mask("9999-9999-99999", {
      placeholder: "____-____-_____"
    });

    $("#nuevo_telefono").mask("9999-99-99", {
      placeholder: "____-__-__"
    });
  });




  // FUNCIONES ENMASCARADO DE INPUTS //
  /////////////////////////////////////


  /////////////////////////////////////
  //// FUNCIONES CARGADO DE EDICION ///

  function cargar_edicion(id, identidad, edicion_codigo, edicion_nombre, nombre_asociacion, telefono, direccion, foto, estado_civil, sexo, zona_venta, tipo_id, geocodigo, asociacion, seccional, bolsas, correo, discapacidad, desc_discapacidad, num_hijos, municipio_venta, tipo_sangre, fecha_nacimiento, estado_vendedor, productos) {


    if (tipo_id == 1) {
      identidad1 = identidad.substring(0, 4);
      identidad2 = identidad.substring(4, 8);
      identidad3 = identidad.substring(8, 13);
      identidad = identidad1 + '-' + identidad2 + '-' + identidad3;
    }

//    alert(productos);
document.getElementById("edicion_productos").value = productos;
//    ​document.getElementById('edicion_productos').selected = productos;​​​​​​​​​​

    document.getElementById("id_edicion").value = id;
    document.getElementById("edicion_id").value = identidad;
    document.getElementById("edicion_codigo").value = edicion_codigo;
    document.getElementById("edicion_nombre").value = edicion_nombre;
    document.getElementById("edicion_telefono").value = telefono;
    document.getElementById("edicion_direccion").value = direccion;
    document.getElementById("edicion_sexo").value = sexo;
    document.getElementById("edicion_estado_civil").value = estado_civil;
    document.getElementById("edicion_zona_venta").value = zona_venta;

    document.getElementById("edicion_asociacion").value = asociacion;
    document.getElementById("edicion_seccional").value = asociacion + "-" + seccional;
    document.getElementById("edicion_bolsa").value = bolsas;
    document.getElementById("edicion_estado").value = estado_vendedor;

    //    correo, discapacidad, desc_discapacidad, num_hijos, municipio_venta, tipo_sangre, fecha_nacimiento 

    document.getElementById("edicion_correo").value = correo;
    document.getElementById("edicion_discapacidad").value = discapacidad;
    document.getElementById("edicion_desc_discapacidad").value = desc_discapacidad;
    document.getElementById("edicion_num_hijos").value = num_hijos;
    document.getElementById("edicion_tipo_sangre").value = tipo_sangre;
    document.getElementById("edicion_fecha_nacimiento").value = fecha_nacimiento;

    calcular_edad_edicion();





    longitudDom = geocodigo.length;

    if (longitudDom == 3) {
      dptoDom = geocodigo.substr(0, 1);
    } else {
      dptoDom = geocodigo.substr(0, 2);
    }





    if (geocodigo != '') {

      document.getElementById("edicion_departamento").options[dptoDom].selected = true;

      conteo_optionsDom = document.getElementById("edicion_municipio").length;
      for (i = 0; i <= conteo_optionsDom; i++) {

        if (document.getElementById("edicion_municipio").options[i].value == geocodigo) {
          document.getElementById("edicion_municipio").options[i].selected = true;
          i = conteo_optionsDom + 10000;
        }

      }

    }


    edicion_depto_venta(municipio_venta);


    if (foto != '') {
      document.getElementById("vista_previa_edicion").src = "./imagenes/vendedores/" + foto;
    } else {
      document.getElementById("vista_previa_edicion").src = "./imagenes/default_foto.png";
    }




  }

  //// FUNCIONES CARGADO DE EDICION ///
  /////////////////////////////////////

  /////////////////////////////////////
  //// FUNCIONES CARGADO DE EDICION ///

  function edicion_depto_venta(municipio_venta) {

    longitud = municipio_venta.length;

    if (longitud == 3) {
      dpto = municipio_venta.substr(0, 1);
    } else {
      dpto = municipio_venta.substr(0, 2);
    }

    if (municipio_venta != '') {

      document.getElementById("edicion_departamento_venta").options[dpto].selected = true;
      conteo_options = document.getElementById("edicion_municipio_venta").length;

      for (i = 0; i <= conteo_options; i++) {

        if (document.getElementById("edicion_municipio_venta").options[i].value == municipio_venta) {
          document.getElementById("edicion_municipio_venta").options[i].selected = true;
          i = conteo_optionsDom + 10000;

        }

      }

    }


  }

  //// FUNCIONES CARGADO DE EDICION ///
  /////////////////////////////////////




  /////////////////////////////////////////
  /// FUNCION PARA CARGADO DE MUNICIPIOS //

  function funcion_seleccion_nuevo(id_depto) {
    var obj_select = document.getElementById("nuevo_municipio");
    conteo_opciones = obj_select.length;
    obj_select.options[0].selected = true;

    for (var i = 1; i <= conteo_opciones; i++) {

      if (obj_select.options[i].id == id_depto) {
        obj_select.options[i].style.display = "block";
      } else {
        obj_select.options[i].style.display = "none";
      }
    }

  }




  function funcion_seleccion_nuevo_venta(id_depto) {
    var obj_select = document.getElementById("nuevo_municipio_venta");
    conteo_opciones = obj_select.length;
    obj_select.options[0].selected = true;

    for (var i = 1; i <= conteo_opciones; i++) {

      if (obj_select.options[i].id == id_depto) {
        obj_select.options[i].style.display = "block";
      } else {
        obj_select.options[i].style.display = "none";
      }
    }

  }

  function funcion_seleccion_edicion_venta(id_depto) {
    var obj_select = document.getElementById("edicion_municipio_venta");
    conteo_opciones = obj_select.length;
    obj_select.options[0].selected = true;

    for (var i = 1; i <= conteo_opciones; i++) {

      if (obj_select.options[i].id == id_depto) {
        obj_select.options[i].style.display = "block";
      } else {
        obj_select.options[i].style.display = "none";
      }
    }

  }


  function funcion_seleccion_edicion(id_depto) {

    var obj_select = document.getElementById("edicion_municipio");
    conteo_opciones = obj_select.length;
    obj_select.options[0].selected = true;

    for (var i = 1; i <= conteo_opciones; i++) {

      if (obj_select.options[i].id == id_depto) {
        obj_select.options[i].style.display = "block";
      } else {
        obj_select.options[i].style.display = "none";
      }
    }

  }


  /// FUNCION PARA CARGADO DE MUNICIPIOS //
  /////////////////////////////////////////



  //////////////////////////////////////////
  // FUNCION PARA CARGADO DE SECCIONALES ///

  function funcion_seleccion_nuevo_asociacion(id_asociacion) {

    document.getElementById("nuevo_codigo").value = '';

    var obj_select = document.getElementById("nueva_seccional");
    conteo_opciones = obj_select.length;
    obj_select.options[0].selected = true;

    for (var i = 1; i <= conteo_opciones; i++) {

      if (obj_select.options[i].id == id_asociacion) {
        obj_select.options[i].style.display = "block";
      } else {
        obj_select.options[i].style.display = "none";
      }
    }

  }
  // FUNCION PARA CARGADO DE SECCIONALES ///
  //////////////////////////////////////////



  //////////////////////////////////////////
  // FUNCION PARA CARGADO DE SECCIONALES ///

  function funcion_seleccion_edicion_asociacion(id_asociacion) {

    document.getElementById("edicion_codigo").value = '';

    var obj_select = document.getElementById("edicion_seccional");
    conteo_opciones = obj_select.length;
    obj_select.options[0].selected = true;

    for (var i = 1; i <= conteo_opciones; i++) {

      if (obj_select.options[i].id == id_asociacion) {
        obj_select.options[i].style.display = "block";
      } else {
        obj_select.options[i].style.display = "none";
      }
    }

  }
  // FUNCION PARA CARGADO DE SECCIONALES ///
  //////////////////////////////////////////



  //////////////////////////////////////////
  // FUNCION PARA CARGADO DE CODIGO ///

  function funcion_seleccion_nuevo_seccional(id_seccional) {

    asociacion = document.getElementById('nueva_asociacion').value;
    seccional = document.getElementById('nueva_seccional').value;

    token = Math.random();
    consulta = 'mercadeo_gestion_vendedores_correlativo.php?a=' + asociacion + "&s=" + seccional + "&tipo=n&token=" + token;

    $("#respuesta_consulta").load(consulta);


  }
  // FUNCION PARA CARGADO DE SECCIONALES ///
  //////////////////////////////////////////




  //////////////////////////////////////////
  // FUNCION PARA CARGADO DE CODIGO ///

  function funcion_seleccion_edicion_seccional(id_seccional) {

    asociacion = document.getElementById('edicion_asociacion').value;
    seccional = document.getElementById('edicion_seccional').value;

    token = Math.random();
    consulta = 'mercadeo_gestion_vendedores_correlativo.php?a=' + asociacion + "&s=" + seccional + "&tipo=e&token=" + token;

    $("#respuesta_consulta").load(consulta);


  }
  // FUNCION PARA CARGADO DE SECCIONALES ///
  //////////////////////////////////////////



  ///////////////////////////////////////////
  // FUNCION VALIDAR IDENTIDAD NO REPETIDA //

  function validar_identidad(id) {
    identidad = id;
    token = Math.random();
    consulta = 'mercadeo_gestion_vendedores_validar_id.php?id=' + identidad + "&tipo_id=1&token=" + token;
    $("#respuesta_consulta").load(consulta);
  }

  // FUNCION VALIDAR IDENTIDAD NO REPETIDA //
  ///////////////////////////////////////////





  function validar_nuevo_vendedor() {
    nuevo_id = document.getElementById('nuevo_id').value;
    nuevo_nombre = document.getElementById('nuevo_nombre').value;
    nueva_asociacion = document.getElementById('nueva_asociacion').value;
    nuevo_departamento = document.getElementById('nuevo_departamento').value;
    nuevo_municipio = document.getElementById('nuevo_municipio').value;
    nueva_seccional = document.getElementById('nueva_seccional').value;
    zona_venta = document.getElementById('nueva_zona_venta').value;
    bolsas = document.getElementById('nuevo_bolsa').value;
    foto = document.getElementById('foto').value;
    fecha_nacimiento = document.getElementById('nuevo_fecha_nacimiento').value;

    if (nuevo_id != '') {

      if (nuevo_nombre != '') {

        if (nueva_asociacion != 'no') {

          if (nueva_seccional != 'no') {

            if (nuevo_departamento != 'ninguno' && nuevo_municipio != 'ninguno') {

              if (zona_venta != '') {

                if (bolsas != '') {

                  if (foto != '') {

                    if (fecha_nacimiento != '') {

                      document.getElementById("guardar_nuevo").click();

                    } else {
                      swal("ERROR", "Debe ingresar una fecha de nacimiento.", "error");
                    }


                  } else {
                    swal("ERROR", "Debe seleccionar la fotografia del vendedor.", "error");
                  }

                } else {
                  swal("ERROR", "Debe ingresar el numero de bolsas del vendedor.", "error");
                }

              } else {
                swal("ERROR", "Debe ingresar la zona de venta.", "error");
              }

            } else {
              swal("ERROR", "Debe seleccionar un departamento y municipio.", "error");
            }

          } else {
            swal("ERROR", "Debe seleccionar una seccional.", "error");
          }

        } else {
          swal("ERROR", "Debe seleccionar una asociacion.", "error");
        }

      } else {
        swal("ERROR", "Debe ingresar el nombre del nuevo vendedor.", "error");
      }


    } else {
      swal("ERROR", "Debe ingresar el numero de identidad del nuevo vendedor.", "error");
    }

  }



  ////////////////////////////////////////////////
  ////////////// VALIDAR EDICION /////////////////

  function validar_edicion_vendedor() {

    edicion_id = document.getElementById('edicion_id').value;
    edicion_nombre = document.getElementById('edicion_nombre').value;
    edicion_asociacion = document.getElementById('edicion_asociacion').value;
    edicion_municipio = document.getElementById('edicion_municipio').value;
    edicion_departamento = document.getElementById('edicion_departamento').value;

    if (edicion_id != '') {

      if (edicion_nombre != '') {

        if (edicion_asociacion != '') {

          if (edicion_departamento != 'ninguno' && edicion_municipio != 'ninguno') {

            document.getElementById("guardar_edicion").click();

          } else {
            swal("ERROR", "Debe seleccionar un departamento y municipio.", "error");
          }

        } else {
          swal("ERROR", "Debe seleccionar una asociacion.", "error");
        }

      } else {
        swal("ERROR", "Debe ingresar el nombre del vendedor.", "error");
      }


    } else {
      swal("ERROR", "Debe ingresar el numero de identidad del vendedor.", "error");
    }

  }
  ///////////////
  /////////////////////////////////////////////////////////////

  function consultar_empleado() {

    code = document.getElementById("cod_empleado").value;
    year = document.getElementById("year").value;

    if (code == "") {

      alert("Debe ingresar un codigo de empleado o seleccionar un nombre.");

    } else {

      if (year == "") {

        alert("Debe ingresar un año a consultar.");

      } else {

        $(".div_wait").fadeIn("fast");
        token = Math.random();
        consulta = 'control_individual_empleados_js.php?code=' + code + "&year=" + year + "&token=" + token;
        $("#respuesta").load(consulta);

      }

    }

  }




  /////////////////////////////////////////// 
  ////////////// FAMILIARES ///////////////// 

  function cargar_familiares(id_vendedor, nombre_vendedor, accion) {


    document.getElementById('nombre_vendedor_familiar').value = nombre_vendedor;
    document.getElementById('identidad_vendedor_familiar_o').value = id_vendedor;
    nombre_vendedor2 = nombre_vendedor.replace(/ /g, '!');
    //    $(".div_wait").fadeIn("fast");
    token = Math.random();
    consulta = 'mercadeo_gestion_vendedores_familiares_js.php?id_vendedor=' + id_vendedor + "&nombre_vendedor=" + nombre_vendedor2 + "&accion=" + accion + "&token=" + token;
    $("#respuesta_familiares").load(consulta);



  }







  function guardar_familiares(accion) {

    id_vendedor = document.getElementById('identidad_vendedor_familiar_o').value;
    nombre_vendedor = document.getElementById('nombre_vendedor_familiar').value;
    nombre_vendedor = nombre_vendedor.replace(/ /g, '!');
    nombre_familiar = document.getElementById('nuevo_nombre_familiar').value;
    nombre_familiar = nombre_familiar.replace(/ /g, '!');
    identidad_familiar = document.getElementById('nuevo_identidad_familiar').value;
    telefono_familiar = document.getElementById('nuevo_telefono_familiar').value;
    relacion_familiar = document.getElementById('nuevo_relacion_familiar').value;
    bandera = 1;

    if (nombre_familiar == '') {
      bandera = 0;
      swal("ERROR", "Debe ingresar el nombre del familiar.", "error");
    }

    //    $(".div_wait").fadeIn("fast");
    token = Math.random();
    if (bandera == 1) {
      consulta = 'mercadeo_gestion_vendedores_familiares_js.php?id_vendedor=' + id_vendedor + "&nombre_vendedor=" + nombre_vendedor + "&nombre_familiar=" + nombre_familiar + "&identidad_familiar=" + identidad_familiar + "&telefono_familiar=" + telefono_familiar  + "&relacion_familiar=" + relacion_familiar + "&accion=" + accion + "&token=" + token;
      $("#respuesta_familiares").load(consulta);
    }

  }


  function eliminar_familiar(id, id_vendedor) {

    id_familiar = id;

    //    $(".div_wait").fadeIn("fast");
    token = Math.random();
    consulta = 'mercadeo_gestion_vendedores_familiares_js.php?id_vendedor=' + id_vendedor + '&id_familiar=' + id_familiar + "&accion=ELIMINAR&token=" + token;
    $("#respuesta_familiares").load(consulta);

  }



  function calcular_edad_familiar() {
    fecha_familiar = document.getElementById('nuevo_fecha_familiar').value;
    token = Math.random();
    consulta = 'mercadeo_gestion_vendedores_calculo_edad_js.php?fecha_familiar=' + fecha_familiar + "&token=" + token;
    $("#respuesta_calculo_edad").load(consulta);


  }

  ////////////// FAMILIARES ///////////////// 
  /////////////////////////////////////////// 






  /////////////////////////////////////////// 
  ////////////// FAMILIARES ///////////////// 

  function cargar_familiares(id_vendedor, nombre_vendedor, accion) {


    document.getElementById('nombre_vendedor_familiar').value = nombre_vendedor;
    document.getElementById('identidad_vendedor_familiar_o').value = id_vendedor;
    nombre_vendedor2 = nombre_vendedor.replace(/ /g, '!');
    //    $(".div_wait").fadeIn("fast");
    token = Math.random();
    consulta = 'mercadeo_gestion_vendedores_familiares_js.php?id_vendedor=' + id_vendedor + "&nombre_vendedor=" + nombre_vendedor2 + "&accion=" + accion + "&token=" + token;
    $("#respuesta_familiares").load(consulta);



  }







  function guardar_familiares(accion) {

    id_vendedor = document.getElementById('identidad_vendedor_familiar_o').value;
    nombre_vendedor = document.getElementById('nombre_vendedor_familiar').value;
    nombre_vendedor = nombre_vendedor.replace(/ /g, '!');
    nombre_familiar = document.getElementById('nuevo_nombre_familiar').value;
    nombre_familiar = nombre_familiar.replace(/ /g, '!');
    identidad_familiar = document.getElementById('nuevo_identidad_familiar').value;
    telefono_familiar = document.getElementById('nuevo_telefono_familiar').value;
    relacion_familiar = document.getElementById('nuevo_relacion_familiar').value;
    bandera = 1;

    if (nombre_familiar == '') {
      bandera = 0;
      swal("ERROR", "Debe ingresar el nombre del familiar.", "error");
    }

    //    $(".div_wait").fadeIn("fast");
    token = Math.random();
    if (bandera == 1) {
      consulta = 'mercadeo_gestion_vendedores_familiares_js.php?id_vendedor=' + id_vendedor + "&nombre_vendedor=" + nombre_vendedor + "&nombre_familiar=" + nombre_familiar + "&identidad_familiar=" + identidad_familiar + "&telefono_familiar=" + telefono_familiar + "&relacion_familiar=" + relacion_familiar + "&accion=" + accion + "&token=" + token;
      $("#respuesta_familiares").load(consulta);
    }

  }


  function eliminar_familiar(id, id_vendedor) {

    id_familiar = id;

    //    $(".div_wait").fadeIn("fast");
    token = Math.random();
    consulta = 'mercadeo_gestion_vendedores_familiares_js.php?id_vendedor=' + id_vendedor + '&id_familiar=' + id_familiar + "&accion=ELIMINAR&token=" + token;
    $("#respuesta_familiares").load(consulta);

  }



  function calcular_edad_familiar() {
    fecha_familiar = document.getElementById('nuevo_fecha_familiar').value;
    token = Math.random();
    consulta = 'mercadeo_gestion_vendedores_calculo_edad_js.php?fecha_familiar=' + fecha_familiar + "&tipo=FAMILIA&token=" + token;
    $("#respuesta_calculo_edad").load(consulta);
  }


  ////////////// FAMILIARES ///////////////// 
  /////////////////////////////////////////// 






  // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


  function calcular_edad_nuevo() {
    fecha_familiar = document.getElementById('nuevo_fecha_nacimiento').value;
    token = Math.random();
    consulta = 'mercadeo_gestion_vendedores_calculo_edad_js.php?fecha_nuevo=' + fecha_familiar + "&tipo=NUEVO&token=" + token;
    $("#respuesta_calculo_edad").load(consulta);


  }


  function calcular_edad_edicion() {
    fecha_familiar = document.getElementById('edicion_fecha_nacimiento').value;
    token = Math.random();
    consulta = 'mercadeo_gestion_vendedores_calculo_edad_js.php?fecha_nuevo=' + fecha_familiar + "&tipo=EDICION&token=" + token;
    $("#respuesta_calculo_edad").load(consulta);


  }



  ////////////////////////////////////////////// 
  ////////////// BENEFICIARIOS ///////////////// 

  function cargar_beneficiarios(id_vendedor, nombre_vendedor, accion) {

    document.getElementById('nombre_vendedor_beneficiario').value = nombre_vendedor;
    document.getElementById('identidad_vendedor_beneficiario_o').value = id_vendedor;
    nombre_vendedor2 = nombre_vendedor.replace(/ /g, '!');
    //    $(".div_wait").fadeIn("fast");
    token = Math.random();
    consulta = 'mercadeo_gestion_vendedores_beneficiarios_js.php?id_vendedor=' + id_vendedor + "&nombre_vendedor=" + nombre_vendedor2 + "&accion=" + accion + "&token=" + token;
    $("#respuesta_beneficiarios").load(consulta);

  }




  function guardar_beneficiarios(accion) {

    id_vendedor = document.getElementById('identidad_vendedor_beneficiario_o').value;
    nombre_vendedor = document.getElementById('nombre_vendedor_beneficiario').value;
    nombre_vendedor = nombre_vendedor.replace(/ /g, '!');
    nombre_familiar = document.getElementById('nuevo_nombre_beneficiario').value;
    nombre_familiar = nombre_familiar.replace(/ /g, '!');
    identidad_familiar = document.getElementById('nuevo_identidad_beneficiario').value;
    telefono_familiar = document.getElementById('nuevo_telefono_beneficiario').value;
    relacion_familiar = document.getElementById('nuevo_relacion_beneficiario').value;
    bandera = 1;

    if (nombre_familiar == '') {
      bandera = 0;
      swal("ERROR", "Debe ingresar el nombre del beneficiario.", "error");
    }

    //    $(".div_wait").fadeIn("fast");
    token = Math.random();
    if (bandera == 1) {
      consulta = 'mercadeo_gestion_vendedores_beneficiarios_js.php?id_vendedor=' + id_vendedor + "&nombre_vendedor=" + nombre_vendedor + "&nombre_beneficiario=" + nombre_familiar + "&identidad_beneficiario=" + identidad_familiar + "&telefono_beneficiario=" + telefono_familiar  + "&relacion_beneficiario=" + relacion_familiar + "&accion=" + accion + "&token=" + token;
      $("#respuesta_beneficiarios").load(consulta);
    }

  }


  function eliminar_beneficiario(id, id_vendedor) {

    id_familiar = id;

    //    $(".div_wait").fadeIn("fast");
    token = Math.random();
    consulta = 'mercadeo_gestion_vendedores_beneficiarios_js.php?id_vendedor=' + id_vendedor + '&id_beneficiario=' + id_familiar + "&accion=ELIMINAR&token=" + token;
    $("#respuesta_beneficiarios").load(consulta);

  }



  function calcular_edad_beneficiario() {
    fecha_familiar = document.getElementById('nuevo_fecha_beneficiario').value;
    consulta = 'mercadeo_gestion_vendedores_calculo_edad_js.php?fecha_beneficiario=' + fecha_familiar + "&tipo=BENEFICIARIO&token=" + token;
    $("#respuesta_calculo_edad").load(consulta);


  }
</script>

<form method="POST" enctype="multipart/form-data">

  <div id="respuesta_calculo_edad"></div>
  <div id="respuesta_consulta"></div>

  <section style="color:rgb(63,138,214);background-color:#ededed;">
    <br>
    <h2 align="center" style="color:black; ">GESTION DE VENDEDORES AUTORIZADOS</h2>
    <br>
  </section>
  <br>


  <div align="center">
    <a style="width: 99%; margin-left: 10px; margin-right: 10px;" data-toggle="modal" href="#nuevo" class="btn btn-primary">Agregar Vendedor</a>
  </div>

  <br>
  <br>
  <div class="card" style="margin-left: 10px;margin-right: 10px;">
    <div class="card-header">
      <h3 align="center">Historico de Vendedores Registrados</h3>
    </div>

    <div class="card-body">

      <table class="table table-bordered table-responsive-sm" id="table_id1">
        <thead>
          <th>Identidad</th>
          <th>Nombre</th>
          <th>Edad</th>
          <th>Asociacion</th>
          <th>Telefono</th>
          <th>Estado</th>
          <th>Familiares</th>
          <th>Beneficiarios</th>
          <th>Accion</th>
        </thead>
        <tbody>
          <?php


          $CurrentYear =  date("Y");
          $asociaciones = mysqli_query($conn, "SELECT a.correo, a.discapacidad, a.desc_discapacidad, a.num_hijos, a.geocodigo_venta, a.tipo_sangre, a.fecha_nacimiento, a.id, a.identidad ,a.tipo_identificacion, a.codigo, a.nombre, a.direccion, a.foto, a.asociacion, a.telefono, a.estado, a.estado_civil , a.sexo , a.zona_venta , b.nombre_asociacion, a.geocodigo, a.seccional, a.numero_bolsas, a.productos FROM vendedores as a INNER JOIN asociaciones_vendedores as b ON a.asociacion = b.codigo_asociacion ");

          echo mysqli_error($conn);


          while ($reg_asociacion = mysqli_fetch_array($asociaciones)) {

            $id                 = $reg_asociacion['id'];
            $identidad          = $reg_asociacion['identidad'];
            $nom                = $reg_asociacion['nombre'];
            $nombre_asociacion  = $reg_asociacion['nombre_asociacion'];
            $cod                = $reg_asociacion['asociacion'] . "-" . $reg_asociacion['seccional'] . "-" . $reg_asociacion['codigo'];
            $seccional          = $reg_asociacion['seccional'];
            $asociacion         = $reg_asociacion['asociacion'];
            $concat_codigo      = $cod;
            $telefono           = $reg_asociacion['telefono'];
            $direccion          = $reg_asociacion['direccion'];
            $rand               = rand(0, 99999);
            $foto               = $reg_asociacion['foto'] . "?rand" . $rand;
            $sexo               = $reg_asociacion['sexo'];
            $estado_civil       = $reg_asociacion['estado_civil'];
            $zona_venta         = $reg_asociacion['zona_venta'];
            $tipo_id            = $reg_asociacion['tipo_identificacion'];
            $geocodigo          = $reg_asociacion['geocodigo'];
            $bolsas             = $reg_asociacion['numero_bolsas'];
            $estado_vendedor    = $reg_asociacion['estado'];
            $productos          = $reg_asociacion['productos'];

            $correo  = $reg_asociacion['correo'];
            $discapacidad    = $reg_asociacion['discapacidad'];
            $desc_discapacidad = $reg_asociacion['desc_discapacidad'];
            $num_hijos = $reg_asociacion['num_hijos'];
            $municipio_venta = $reg_asociacion['geocodigo_venta'];
            $tipo_sangre = $reg_asociacion['tipo_sangre'];
            $fecha_nacimiento = $reg_asociacion['fecha_nacimiento'];


            echo "<tr>";
            echo "<td>";
            echo $reg_asociacion['identidad'];
            echo "</td>";
            echo "<td>";
            echo $reg_asociacion['nombre'];
            echo "</td>";

            if (strlen($reg_asociacion['identidad']) == 13) {
              $edad = substr($reg_asociacion['identidad'], 4, 4);
              $edad = $CurrentYear - $edad;
            } else {
              $edad = "";
            }

            echo "<td>";
            echo $edad;
            echo "</td>";
            echo "<td>";
            echo $concat_codigo;
            echo "</td>";
            echo "<td>";
            echo $reg_asociacion['telefono'];
            echo "</td>";
            echo "<td>";
            if ($reg_asociacion['estado'] == 1) {
              echo "ACTIVO";
            } else {
              echo "INACTIVO";
            }
            echo "</td>";




            $rand = rand();

          ?>

            <td align="center"><button type="button" on data-toggle="modal" onclick="cargar_familiares('<?php echo $identidad; ?>', '<?php echo $nom; ?>', 'CONSULTA')" data-target="#modal_familiares" class="btn btn-secondary">Familiares</button></td>
            <td align="center"><button type="button" on data-toggle="modal" onclick="cargar_beneficiarios('<?php echo $identidad; ?>', '<?php echo $nom; ?>', 'CONSULTA')" data-target="#modal_beneficiarios" class="btn btn-secondary">Beneficiarios</button></td>


            <td align='center'>
            <a onclick="cargar_edicion('<?php echo $id; ?>','<?php echo $identidad; ?>','<?php echo $cod; ?>','<?php echo $nom; ?>','<?php echo $nombre_asociacion; ?>','<?php echo $telefono; ?>','<?php echo $direccion; ?>','<?php echo $foto; ?>','<?php echo $estado_civil; ?>','<?php echo $sexo; ?>','<?php echo $zona_venta; ?>','<?php echo $tipo_id; ?>','<?php echo $geocodigo; ?>','<?php echo $asociacion; ?>','<?php echo $seccional; ?>','<?php echo $bolsas; ?>','<?php echo $correo; ?>','<?php echo $discapacidad; ?>','<?php echo $desc_discapacidad; ?>','<?php echo $num_hijos; ?>','<?php echo $municipio_venta; ?>','<?php echo $tipo_sangre; ?>','<?php echo $fecha_nacimiento; ?>','<?php echo $estado_vendedor; ?>','<?php echo $productos; ?>')" data-toggle='modal' href='#editar' class='btn btn-primary  fa fa-edit'></a>

              <a href="./mercadeo_gestion_vendedores_info.php?v=<?php echo $id; ?>" target='_blanck' class="btn btn-info fa fa-eye"></a>

              <a href="./mercadeo_print_carnet.php?v=<?php echo $id; ?>&r=<?php echo $rand; ?>" target='_blanck' class="btn btn-success fa fa-print"></a>
            </td>

          <?php



            echo "</tr>";
          }
          ?>

        </tbody>
      </table>
    </div>

    <div class="card-footer" align="center">
      <a href="screen_mto_vendedores_print.php" target="_blanck" class="btn btn-success">IMPRIMIR REPORTE</a>
      <a href="mercadeo_gestion_vendedores_excel.php" target="_blanck" class="btn btn-success">GENERAR EXCEL</a>
    </div>

  </div>

  <br>
  <br>














  <!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->
  <!-- $$$$$$$$$$$$$$$$$$$$ MODAL DE NUEVO REG $$$$$$$$$$$$$$$$$$$$$$ -->

  <input class="form-control" type='file' id="foto" name="foto" style="visibility: hidden" onchange="readURL(this);">

  <div class="modal" id="nuevo">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header alert-info">
          <h4 align="center">Nuevo Vendedor</h4>
        </div>
        <div class="container"></div>
        <div class="modal-body">





          <div class="row">

            <div class="col col-sm-3" align="center" style="vertical-align: center">
              <img width="150px" height="150px" onclick="document.getElementById('foto').click()" id="vista_previa" src="./imagenes/default_foto.png" alt="">
            </div>

            <div class="col">

              <div style="width: 100%" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Identidad</span></div>
                <input type="text" onblur="validar_identidad(this.value)" name="nuevo_id" id="nuevo_id" class="form-control">
              </div>

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Nombre</span></div>
                <input style="text-transform:uppercase" class="form-control" type="text" name="nuevo_nombre" id="nuevo_nombre" readonly="true">
              </div>


              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Asociacion</span></div>
                <select class="form-control" name="nueva_asociacion" id="nueva_asociacion" onchange=" funcion_seleccion_nuevo_asociacion(this.value);">
                  <option value="no">Seleccione una opcion</option>
                  <?php
                  $select_a = mysqli_query($conn, "SELECT * from asociaciones_vendedores");
                  while ($reg_select_a = mysqli_fetch_array($select_a)) {
                    echo "<option value = '" . $reg_select_a['codigo_asociacion'] . "'>" . $reg_select_a['nombre_asociacion'] . "</option>";
                  }
                  ?>
                </select>
              </div>


              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Seccional</span></div>
                <select class="form-control" name="nueva_seccional" id="nueva_seccional" onchange=" funcion_seleccion_nuevo_seccional();">
                  <option value="no">Seleccione una opcion</option>
                  <?php
                  $select_a = mysqli_query($conn, "SELECT * from asociaciones_seccionales");
                  while ($reg_select_a = mysqli_fetch_array($select_a)) {
                    echo "<option style = 'display:none' id = '" . $reg_select_a['codigo_asociacion'] . "' name = '" . $reg_select_a['codigo_asociacion'] . "'  value = '" . $reg_select_a['codigo_seccional'] . "'>" . $reg_select_a['codigo_seccional'] . " - " . $reg_select_a['zona'] . "</option>";
                  }
                  ?>
                </select>
              </div>


            </div>
          </div>


          <br>



          <div class="row" style="margin-bottom: 5px">

            <div class='col'>

              <div style="width: 100%; " class="input-group">

                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Tipo de Loteria Que Vende</span></div>
                <select class="form-control" id="nuevo_productos" name="nuevo_productos">
                  <option value="LA CHICA">LA CHICA</option>
                  <option value="LA GRANDE">LA GRANDE</option>
                  <option value="AMBAS"> AMBAS </option>
                </select>

              </div>

            </div>

          </div>



          <div class="row">

            <div class="col">

              <div style="width: 100%; " class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Fecha Nacim.</span></div>
                <input class="form-control" onchange="calcular_edad_nuevo()" type="date" name="nuevo_fecha_nacimiento" id="nuevo_fecha_nacimiento">
              </div>

            </div>
            <div class="col">

              <div style="width: 100%; " class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Edad</span></div>
                <input class="form-control" type="text" name="nuevo_edad" id="nuevo_edad" readonly="true">
              </div>

            </div>
          </div>



          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px " class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Codigo</span></div>
                <input class="form-control" type="text" name="nuevo_codigo" id="nuevo_codigo" readonly="true">
              </div>

            </div>
            <div class="col">

              <div style="width: 100%; margin-top: 5px " class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Bolsas</span></div>
                <input class="form-control" type="text" name="nuevo_bolsa" id="nuevo_bolsa">
              </div>

            </div>
          </div>

          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Genero</span></div>
                <select class="form-control" id="nuevo_sexo" name="nuevo_sexo">
                  <option value="M"> MASCULINO </option>
                  <option value="F"> FEMENINO </option>
                </select>
              </div>

            </div>

            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Estado Civil</span></div>
                <select class="form-control" id="nuevo_estado_civil" name="nuevo_estado_civil">
                  <option value="S"> SOLTERO </option>
                  <option value="C"> CASADO </option>
                  <option value="V"> VIUDO </option>
                  <option value="D"> DIVORCIADO </option>
                  <option value="U"> UNION LIBRE </option>
                </select>
              </div>

            </div>
          </div>



          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Correo</span></div>
                <input class="form-control" type="text" name="nuevo_correo" id="nuevo_correo">
              </div>
            </div>
          </div>



          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Telefono</span></div>
                <input class="form-control" type="text" name="nuevo_telefono" id="nuevo_telefono">
              </div>

            </div>
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Tipo de Sangre</span></div>
                <select class="form-control" name="nuevo_tipo_sangre" id="nuevo_tipo_sangre">
                  <option value="" selected="true"></option>
                  <option value="A+">A+</option>
                  <option value="A-">A-</option>
                  <option value="B+">B+</option>
                  <option value="B-">B-</option>
                  <option value="AB+">AB+</option>
                  <option value="AB-">AB-</option>
                  <option value="O+">O+</option>
                  <option value="O-">O-</option>
                </select>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Discapacidad</span></div>
                <select class="form-control" name="nuevo_discapacidad" id="nuevo_discapacidad">
                  <option value="NO">NO</option>
                  <option value="SI">SI</option>
                </select>
              </div>

            </div>
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Desc. Discap.</span></div>
                <textarea class="form-control" name="nuevo_desc_discapacidad" id="nuevo_desc_discapacidad"></textarea>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Num. Hijos</span></div>
                <input class="form-control" type="number" name="nuevo_num_hijos" id="nuevo_num_hijos">
              </div>

            </div>
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Estado</span></div>
                <select class="form-control" name="nuevo_estado" disabled>
                  <option value="1" selected="true">ACTIVO</option>
                  <option value="2">INACTIVO</option>
                </select>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Departamento</span></div>
                <select required class="form-control" id="nuevo_departamento" name="nuevo_departamento" onchange=" funcion_seleccion_nuevo(this.value);">
                  <option value="ninguno">Seleccione una opcion</option>
                  <?php
                  $departamentos = mysqli_query($conn, "SELECT * FROM fvp_dptos ORDER BY id ASC ");

                  while ($dpto = mysqli_fetch_array($departamentos)) {
                    echo '<option value="' . $dpto['id'] . '">' . $dpto['descripcion'] . '</option>';
                  }

                  ?>
                </select>
              </div>

            </div>


            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Municipio</span></div>
                <select required name="nuevo_municipio" id="nuevo_municipio" class="form-control">
                  <option value="ninguno">Seleccione una opcion</option>
                  <?php
                  $municipios = mysqli_query($conn, "SELECT * FROM fvp_geocodigos ORDER BY cod_muni ASC");

                  while ($municipio = mysqli_fetch_array($municipios)) {
                    echo "<option style = 'display:none' id = '" . $municipio['dpto_id'] . "' name = '" . $municipio['cod_muni'] . "' value = '" . $municipio['cod_muni'] . "'>" . $municipio['municipio'] . "</option>";
                  }
                  ?>
                </select>
              </div>

            </div>
          </div>




          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Direccion</span></div>
                <textarea maxlength="100" class="form form-control" name="nueva_direccion" id="nueva_direccion"></textarea>
              </div>

            </div>
          </div>



          <hr />
          Datos lugar de venta

          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Departamento</span></div>
                <select required class="form-control" id="nuevo_departamento_venta" name="nuevo_departamento_venta" onchange=" funcion_seleccion_nuevo_venta(this.value);">
                  <option value="ninguno">Seleccione una opcion</option>
                  <?php
                  $departamentos = mysqli_query($conn, "SELECT * FROM fvp_dptos ORDER BY id ASC ");

                  while ($dpto = mysqli_fetch_array($departamentos)) {
                    echo '<option value="' . $dpto['id'] . '">' . $dpto['descripcion'] . '</option>';
                  }

                  ?>
                </select>
              </div>

            </div>


            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Municipio</span></div>
                <select required name="nuevo_municipio_venta" id="nuevo_municipio_venta" class="form-control">
                  <option value="ninguno">Seleccione una opcion</option>
                  <?php
                  $municipios = mysqli_query($conn, "SELECT * FROM fvp_geocodigos ORDER BY cod_muni ASC");

                  while ($municipio = mysqli_fetch_array($municipios)) {
                    echo "<option style = 'display:none' id = '" . $municipio['dpto_id'] . "' name = '" . $municipio['cod_muni'] . "' value = '" . $municipio['cod_muni'] . "'>" . $municipio['municipio'] . "</option>";
                  }
                  ?>
                </select>
              </div>

            </div>
          </div>





          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Zona de Venta</span></div>
                <textarea maxlength="100" class="form form-control" name="nueva_zona_venta" id="nueva_zona_venta"></textarea>
              </div>

            </div>
          </div>





        </div>
        <div class="modal-footer">
          <button style="display: none" type="submit" name="guardar_nuevo" id="guardar_nuevo"></button>
          <span onclick="validar_nuevo_vendedor()" class="btn btn-primary">Guardar</span>
          <span class="btn btn-danger" data-dismiss="modal">Cancelar</span>
        </div>
      </div>
    </div>
  </div>

  <!-- $$$$$$$$$$$$$$$$$$$$ MODAL DE NUEVO REG $$$$$$$$$$$$$$$$$$$$$$ -->
  <!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->



























  <!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->
  <!-- $$$$$$$$$$$$$$$$$$$$ MODAL DE EDICION $$$$$$$$$$$$$$$$$$$$$$ -->




  <input class="form-control" style="visibility: hidden" type='file' id="foto_edicion" name="foto_edicion" onchange="readURL2(this);">

  <input class="form-control" type="hidden" name="id_edicion" id="id_edicion">

  <div class="modal" id="editar">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header alert-info">
          <h4 align="center">Edicion Vendedor</h4>
        </div>
        <div class="container"></div>
        <div class="modal-body">









          <div class="row">


            <div class="col col-sm-3" align="center" style="vertical-align: center">
              <img width="150px" height="150px" onclick="document.getElementById('foto_edicion').click()" id="vista_previa_edicion" name="vista_previa_edicion" alt="">
            </div>


            <div class="col">


              <div style="width: 100%" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Identidad</span></div>
                <input type="text" name="edicion_id" id="edicion_id" class="form-control" readonly>
              </div>


              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Nombre</span></div>
                <input style="text-transform:uppercase" class="form-control" type="text" name="edicion_nombre" id="edicion_nombre" readonly="true">
              </div>


              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Asociacion</span></div>
                <select class="form-control" name="edicion_asociacion" id="edicion_asociacion" onchange=" funcion_seleccion_edicion_asociacion(this.value);">
                  <option value="no">Seleccione una opcion</option>
                  <?php
                  $select_a = mysqli_query($conn, "SELECT * from asociaciones_vendedores");
                  while ($reg_select_a = mysqli_fetch_array($select_a)) {
                    echo "<option value = '" . $reg_select_a['codigo_asociacion'] . "'>" . $reg_select_a['nombre_asociacion'] . "</option>";
                  }
                  ?>
                </select>
              </div>


              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Seccional</span></div>
                <select class="form-control" name="edicion_seccional" id="edicion_seccional" onchange=" funcion_seleccion_edicion_seccional();">
                  <option value="no">Seleccione una opcion</option>
                  <?php
                  $select_a = mysqli_query($conn, "SELECT * from asociaciones_seccionales");
                  while ($reg_select_a = mysqli_fetch_array($select_a)) {
                    echo "<option style = 'display:none' id = '" . $reg_select_a['codigo_asociacion'] . "' name = '" . $reg_select_a['codigo_asociacion'] . "'  value = '" . $reg_select_a['codigo_asociacion'] . "-" . $reg_select_a['codigo_seccional'] . "'   >" . $reg_select_a['codigo_seccional'] . " - " . $reg_select_a['zona'] . "</option>";
                  }
                  ?>
                </select>
              </div>


            </div>
          </div>


          <br>

          <div class="row" style="margin-bottom: 5px">

            <div class='col'>

              <div style="width: 100%; " class="input-group">

                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Tipo de Loteria Que Vende</span></div>
                <select class="form-control" id="edicion_productos" name="edicion_productos">
                  <option value="LA CHICA">LA CHICA</option>
                  <option value="LA GRANDE">LA GRANDE</option>
                  <option value="AMBAS"> AMBAS </option>
                </select>

              </div>

            </div>

          </div>

          <div class="row">
            <div class="col">

              <div style="width: 100%; " class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Fecha Nacim.</span></div>
                <input class="form-control" onchange="calcular_edad_edicion()" type="date" name="edicion_fecha_nacimiento" id="edicion_fecha_nacimiento">
              </div>

            </div>
            <div class="col">

              <div style="width: 100%; " class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Edad</span></div>
                <input class="form-control" type="text" name="edicion_edad" id="edicion_edad" readonly="true">
              </div>

            </div>
          </div>




          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px"" class=" input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Codigo</span></div>
                <input class="form-control" type="text" name="edicion_codigo" id="edicion_codigo" readonly="true">
              </div>

            </div>
            <div class="col">

              <div style="width: 100%; margin-top: 5px"" class=" input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Bolsas</span></div>
                <input class="form-control" type="text" name="edicion_bolsa" id="edicion_bolsa">
              </div>

            </div>
          </div>

          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Genero</span></div>
                <select class="form-control" id="edicion_sexo" name="edicion_sexo">
                  <option value="M"> MASCULINO </option>
                  <option value="F"> FEMENINO </option>
                </select>
              </div>

            </div>
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Estado Civil</span></div>
                <select class="form-control" id="edicion_estado_civil" name="edicion_estado_civil">
                  <option value="S"> SOLTERO </option>
                  <option value="C"> CASADO </option>
                  <option value="V"> VIUDO </option>
                  <option value="D"> DIVORCIADO </option>
                  <option value="U"> UNION LIBRE </option>
                </select>
              </div>

            </div>
          </div>

          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Correo</span></div>
                <input class="form-control" type="text" name="edicion_correo" id="edicion_correo">
              </div>
            </div>
          </div>



          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Telefono</span></div>
                <input class="form-control" type="text" name="edicion_telefono" id="edicion_telefono">
              </div>

            </div>
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Tipo de Sangre</span></div>
                <select class="form-control" name="edicion_tipo_sangre" id="edicion_tipo_sangre">
                  <option value="" selected="true"></option>
                  <option value="A+">A+</option>
                  <option value="A-">A-</option>
                  <option value="B+">B+</option>
                  <option value="B-">B-</option>
                  <option value="AB+">AB+</option>
                  <option value="AB-">AB-</option>
                  <option value="O+">O+</option>
                  <option value="O-">O-</option>
                </select>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Discapacidad</span></div>
                <select class="form-control" name="edicion_discapacidad" id="edicion_discapacidad">
                  <option value="NO">NO</option>
                  <option value="SI">SI</option>
                </select>
              </div>

            </div>
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Desc. Discap.</span></div>
                <textarea class="form-control" name="edicion_desc_discapacidad" id="edicion_desc_discapacidad"></textarea>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Num. Hijos</span></div>
                <input class="form-control" type="number" name="edicion_num_hijos" id="edicion_num_hijos">
              </div>

            </div>
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Estado</span></div>
                <select class="form-control" id="edicion_estado" name="edicion_estado">
                  <option value="1">ACTIVO</option>
                  <option value="2">INACTIVO</option>
                </select>
              </div>
            </div>
          </div>





          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Departamento</span></div>
                <select required class="form-control" id="edicion_departamento" name="edicion_departamento" onchange=" funcion_seleccion_edicion(this.value);">
                  <option value="ninguno">Seleccione una opcion</option>
                  <?php
                  $departamentos = mysqli_query($conn, "SELECT * FROM fvp_dptos ORDER BY id ASC ");

                  while ($dpto = mysqli_fetch_array($departamentos)) {
                    echo '<option value="' . $dpto['id'] . '">' . $dpto['descripcion'] . '</option>';
                  }

                  ?>
                </select>
              </div>

            </div>


            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Municipio</span></div>
                <select required name="edicion_municipio" id="edicion_municipio" class="form-control">
                  <option value="ninguno">Seleccione una opcion</option>
                  <?php
                  $municipios = mysqli_query($conn, "SELECT * FROM fvp_geocodigos ORDER BY cod_muni ASC");

                  while ($municipio = mysqli_fetch_array($municipios)) {
                    echo "<option style = 'display:none' id = '" . $municipio['dpto_id'] . "' value = '" . $municipio['cod_muni'] . "'>" . $municipio['municipio'] . "</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>



          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Direccion</span></div>
                <textarea maxlength="50" class="form form-control" name="edicion_direccion" id="edicion_direccion"></textarea>
              </div>

            </div>
          </div>




          <hr />
          Datos lugar de venta

          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Departamento</span></div>
                <select required class="form-control" id="edicion_departamento_venta" name="edicion_departamento_venta" onchange=" funcion_seleccion_edicion_venta(this.value);">
                  <option value="ninguno">Seleccione una opcion</option>
                  <?php
                  $departamentos = mysqli_query($conn, "SELECT * FROM fvp_dptos ORDER BY id ASC ");

                  while ($dpto = mysqli_fetch_array($departamentos)) {
                    echo '<option value="' . $dpto['id'] . '">' . $dpto['descripcion'] . '</option>';
                  }

                  ?>
                </select>
              </div>

            </div>


            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Municipio</span></div>
                <select required name="edicion_municipio_venta" id="edicion_municipio_venta" class="form-control">
                  <option value="ninguno">Seleccione una opcion</option>
                  <?php
                  $municipios = mysqli_query($conn, "SELECT * FROM fvp_geocodigos ORDER BY cod_muni ASC");

                  while ($municipio = mysqli_fetch_array($municipios)) {
                    echo "<option style = 'display:none' id = '" . $municipio['dpto_id'] . "' name = '" . $municipio['cod_muni'] . "' value = '" . $municipio['cod_muni'] . "'>" . $municipio['municipio'] . "</option>";
                  }
                  ?>
                </select>
              </div>

            </div>
          </div>






          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Zona De Venta</span></div>
                <textarea maxlength="50" class="form form-control" name="edicion_zona_venta" id="edicion_zona_venta"></textarea>
              </div>

            </div>
          </div>




        </div>
        <div class="modal-footer">

          <button style="display: none" type="submit" name="guardar_edicion" id="guardar_edicion"></button>
          <span onclick="validar_edicion_vendedor()" class="btn btn-primary">Actualizar</span>
          <span class="btn btn-danger" data-dismiss="modal">Cancelar</span>
        </div>
      </div>
    </div>
  </div>



  <!-- $$$$$$$$$$$$$$$$$$$$ MODAL DE EDICION $$$$$$$$$$$$$$$$$$$$$$ -->
  <!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->





  <!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->
  <!-- $$$$$$$$$$$$$$$$$$ MODAL DE FAMILIARES $$$$$$$$$$$$$$$$$$$$$ -->

  <div class="modal fade" id="modal_familiares" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Registro de Familiares</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <input type="hidden" id="identidad_vendedor_familiar_o" name="identidad_vendedor_familiar_o">

          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Vendedor</span></div>
                <input type="text" name="nombre_vendedor_familiar" id="nombre_vendedor_familiar" class="form-control" readonly='true'>
              </div>

            </div>
          </div>




          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Nombre Familiar</span></div>
                <input type="text" name="nuevo_nombre_familiar" id="nuevo_nombre_familiar" class="form-control">
              </div>

            </div>
          </div>


          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Identidad Familiar</span></div>
                <input type="text" name="nuevo_identidad_familiar" id="nuevo_identidad_familiar" class="form-control">
              </div>

            </div>
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Tefefono</span></div>
                <input type="number" name="nuevo_telefono_familiar" id="nuevo_telefono_familiar" class="form-control">
              </div>

            </div>
          </div>



          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Estado</span></div>
                <select class="form-control" name="nuevo_estado_familiar" disabled>
                  <option value="1" selected="true">ACTIVO</option>
                  <option value="2">INACTIVO</option>
                </select>
              </div>


            </div>
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Relacion</span></div>
                <select class="form-control" name="nuevo_relacion_familiar" id="nuevo_relacion_familiar">
                  <option value="MADRE">MADRE</option>
                  <option value="PADRE">PADRE</option>
                  <option value="HERMANO">HERMANO</option>
                  <option value="HERMANA">HERMANA</option>
                  <option value="HIJO">HIJO</option>
                  <option value="HIJA">HIJA</option>
                  <option value="TIO">TIO</option>
                  <option value="TIA">TIA</option>
                  <option value="OTRO">OTRO</option>
                </select>
              </div>



            </div>
          </div>

          <a style="width: 100%; margin-top: 5px" onclick="guardar_familiares('GUARDAR')" class="btn btn-primary text-white" name='guardar_familiar' id='guardar_familiar'>Guardar</a>

          <br><br>

          <div id="respuesta_familiares"></div>

        </div>
      </div>
    </div>
  </div>
  <!-- $$$$$$$$$$$$$$$$$$ MODAL DE FAMILIARES $$$$$$$$$$$$$$$$$$$$$ -->
  <!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->





  <!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->
  <!-- $$$$$$$$$$$$$$$$$$ MODAL DE BENEFICIARIOS $$$$$$$$$$$$$$$$$$$$$ -->

  <div class="modal fade" id="modal_beneficiarios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Registro de Beneficiarios</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <input type="hidden" id="identidad_vendedor_beneficiario_o" name="identidad_vendedor_beneficiario_o">

          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Vendedor</span></div>
                <input type="text" name="nombre_vendedor_beneficiario" id="nombre_vendedor_beneficiario" class="form-control" readonly='true'>
              </div>

            </div>
          </div>




          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Nombre Beneficiario</span></div>
                <input type="text" name="nuevo_nombre_beneficiario" id="nuevo_nombre_beneficiario" class="form-control">
              </div>

            </div>
          </div>


          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Identidad Beneficiario</span></div>
                <input type="text" name="nuevo_identidad_beneficiario" id="nuevo_identidad_beneficiario" class="form-control">
              </div>

            </div>
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Tefefono</span></div>
                <input type="number" name="nuevo_telefono_beneficiario" id="nuevo_telefono_beneficiario" class="form-control">
              </div>

            </div>
          </div>



          <div class="row">
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Estado</span></div>
                <select class="form-control" name="nuevo_estado_beneficiario" disabled>
                  <option value="1" selected="true">ACTIVO</option>
                  <option value="2">INACTIVO</option>
                </select>
              </div>


            </div>
            <div class="col">

              <div style="width: 100%; margin-top: 5px" class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text" style="min-width:150px">Relacion</span></div>
                <select class="form-control" name="nuevo_relacion_beneficiario" id="nuevo_relacion_beneficiario">
                  <option value="MADRE">MADRE</option>
                  <option value="PADRE">PADRE</option>
                  <option value="HERMANO">HERMANO</option>
                  <option value="HERMANA">HERMANA</option>
                  <option value="HIJO">HIJO</option>
                  <option value="HIJA">HIJA</option>
                  <option value="TIO">TIO</option>
                  <option value="TIA">TIA</option>

                  <option value="OTRO">OTRO</option>
                </select>
              </div>



            </div>
          </div>

          <a style="width: 100%; margin-top: 5px" onclick="guardar_beneficiarios('GUARDAR')" class="btn btn-primary text-white" name='guardar_familiar' id='guardar_familiar'>Guardar</a>

          <br><br>

          <div id="respuesta_beneficiarios"></div>

        </div>
      </div>
    </div>
  </div>
  <!-- $$$$$$$$$$$$$$$$$$ MODAL DE BENEFICIARIOS $$$$$$$$$$$$$$$$$$$$$ -->
  <!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->





</form>