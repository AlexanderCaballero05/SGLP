<?php 
require('../../template/header.php'); 
require ("funcs.php");
$usuario_id=$_SESSION['id_usuario'];
$nombre_usuario=$_SESSION['nombre'];
 
$_SESSION['postdata']='true';
$_SESSION['flag_valida']=1;
$dias = array("DOMINGO","LUNES","MARTES","MIERCOLES","JUEVES","VIERNES","SÁBADO");
$dia=$dias[date("w")];
$meses= array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO", "AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
$mes=$meses[date("m")-1];
$ano=date("Y");
$diadate=date("d");
 
?>

 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">        
    <script type="text/javascript" src="_pago_premios_mayor.js"></script> 


    <style type="text/css">
            /* form starting stylings ------------------------------- */
            .group            { 
              position:relative; 
              margin-bottom:25px; 
            }
            input               {
              font-size:18px;
              padding:10px 10px 10px 5px;
              display:block;
              width:100%;
              border:none;
              border-bottom:1px solid #757575;
            }
            input:focus         { outline:none; }

            /* LABEL ======================================= */
            label                
            {
              color:#999; 
              font-size:18px;
              font-weight:normal;
              position:absolute;
              pointer-events:none;
              left:5px;
              top:10px;
              transition:0.2s ease all; 
              -moz-transition:0.2s ease all; 
              -webkit-transition:0.2s ease all;
            }

            /* active state */
            input:focus ~ label, input:valid ~ label        
            {
              top:-20px;
              font-size:14px;
              color:#5264AE;
            }

            .borderless td, .borderless th {
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
              opacity:0.5;
              background: url(../template/images/wait.gif) center no-repeat #fff;
            }
    </style>

<body>
<form class="form" method="POST" action="_savepayment_mayor.php">
  <div id="div_wait"></div>
    <div class="container-fluid" style="padding-top:20px; padding-bottom: 1px; width:90%">
        <h3>Pago de Premios de Lotería Mayor</h3><hr>
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Consulta de Premios</h5>
                    </div>
                    <div class="card-body"  align="center"><div class="group">  
     
    <select class="form-control" id="selectsorteo" name="selectsorteo">
    <option value=''>Seleccione Sorteo</option> 
    <?php 
         echo sorteos_mayores();                                     
    ?>   
    </select>
    <span class="highlight"></span>
    <span class="bar"></span>      
</div>

<div class="group">      
    <input type="text" maxlength="5" id="txtnumero" name="txtnumero" onkeypress="return justNumbers(event)">
    <span class="highlight"></span>
    <span class="bar"></span>
        <label>NÚMERO</label>
</div>

<div class="group">      
    <input type="text" maxlength="2" id="txtdecimo" name="txtdecimo" onkeypress="return justNumbers(event)">
    <span class="highlight"></span>
    <span class="bar"></span>
        <label>DÉCIMO</label>
</div>


<button class="Consulta btn btn-success active btn-sm justify-content-center" type="button">CONSULTAR <i class="fas fa-search" style="padding-bottom:3px; font-size:15px;"></i></button></div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card" id="info_sorteo">
                    <div class="card-header" style="background-color:rgba(0,0,0,0.03);">
                        <h5 class="mb-0">Información del Billete</h5>
                    </div>
                    <div class="card-body" style="padding-top:184px;">
                      
                    </div>
                </div>
            </div>
        </div>
        <h3 style="padding-top:20px;">Recibo de Pago</h3>
        <hr>
        <div class="row">
            <div class="col">
                <div class="row">
                <div class="col-sm-2">  </div>
                    <div class="col-sm-2" style="padding-top: 12px">                     
                        
                          <select class="custom-select" id="inputGroupSelect04" onchange="slct_document(this.value)" >
                            <option value="" selected>Seleccione</option>
                            <option value="id">Identidad</option>
                            <option value="ot">Otro</option> 
                          </select>
                        
                    </div>
                    <div class="col-sm-2" style="padding-right:4px;padding-bottom:3px;padding-left:3px;padding-top:3px;">
                                        <input type="text" id="txtid" name="txtid" readonly="false"  required="true" style="width: 98%">
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label>Identidad </label>
                    </div> 
                    <div class="col-sm-3" style="padding-right:4px;padding-bottom:3px;padding-left:3px;padding-top:3px;">
                        <div class="group input-group">      
                            <input type="text" id="txtnombre" name="txtnombre" style="width: 98%" onkeypress="return soloLetras(event)" onkeyup='this.value=this.value.toUpperCase()' required="true" >
                            <span class="highlight"></span>
                            <span class="bar"></span>
                                <label>Nombre </label>
                        </div>
                    </div>
                    <div class="col-sm-1">  <button class="btn btn-outline-secondary btn-lg" onclick="GetIdRNP()" type="button">Consultar  <i class="fas fa-search" style="font-size:15px;"></i></button> </div>
                    <div class="col-sm-2">  </div>
                </div>
                <div class="row">
                <div class="col-sm-2"></div>
                    <div class="col-sm-8">
                        <div class="table-responsive">
                            <table  id="tbl_recibos" name="tbl_recibos" class="table table-hover table-sm table-bordered"> 
                                <thead>
                                    <tr class="table-active" align="center">
                                        <th width="10%">Sorteo</th>
                                        <th>Registro</th>
                                        <th>Número</th>
                                        <th>Décimos</th>
                                        <th>Total</th>
                                        <th>Impto</th>
                                        <th>Neto</th>
                                        <th style="padding-top:2px;padding-right:2px;padding-bottom:2px;padding-left:2px;width:55px;"><i class="fa fa-remove" style="padding-top:5px;padding-right:5px;padding-bottom:5px;padding-left:5px;"></i></th>
                                    </tr>
                                </thead>
                                <tbody>                                    
                                   <tr>
                                      <td colspan="5"></td>
                                      <td>Total</td>
                                      <td align="right"><span id="lbl_tota_total">0</span><input id="textacumulado_total" name="textacumulado_total"  required='true' class="form-control" type="hidden" value='0'></td>
                                      <td></td>            
                                    </tr>
                                    <tr class="danger">
                                      <td colspan="5"></td>
                                      <td><span>Impto.</span></td>
                                      <td align="right"><span id="lbl_tota_impto">0</span><input id="textacumulado_impto" name="textacumulado_impto" required='true' class="form-control" type="hidden" value='0'> </td>
                                      <td></td>                       
                                    </tr>
                                    <tr class="success">
                                      <td colspan="5"></td>
                                      <td><span>Neto</span></td>
                                      <td align="right"><p  id="lbl_tota_pagar">0</p><input id="textacumulado_neto" name="textacumulado_neto" required='true' class="form-control" type="hidden" value='0' ></td>
                                      <td></td>                         
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-2"></div>
                </div>
                <div class="row" align="center">
                    <div class="col">
                      <button class="btn btn-primary active justify-content-center align-items-center align-content-center" type="submit" align="center" name='savedata' id='savedata' style='display:none'>REALIZAR EL PAGO &nbsp;<i class="far fa-save"></i></button -->
                     <button class="btn btn-primary active justify-content-center align-items-center align-content-center" type="button" align="center" onclick='valida()'>REALIZAR EL PAGO &nbsp;<i class="far fa-save"></i></button>
                   </div>
              </div>
            </div>
        </div>
   </div>
   <div id="getid"></div>
</form>
</body>     
<script type="text/javascript"> 
 $(document).ready(function ()
 {
    $('.Consulta').click(function()     
    {   
      var sorteo=$("#selectsorteo").val(); 
      var numero=$("#txtnumero").val();    
      var decimo=$("#txtdecimo").val();  
      
      if (sorteo == '' || numero== '' || decimo== '')
      {
        swal("Error...!", "Seleccione todos los campos necesarios para la consulta!", "error");
      }
      else
      {
        $(".div_wait").fadeIn("fast");
        var urr = '_pp_consulta_mayor_especies.php?sorteo='+sorteo+'&numero='+numero+'&decimo='+decimo+'&valida='+Math.random();
        $("#info_sorteo").load(urr); 
      }
    });        
  });
</script>
</html>