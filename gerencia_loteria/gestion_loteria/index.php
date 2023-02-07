<?php
require('./template/header.php');
?>
<style> 
.div_inicio {
    border: 2px solid #a1a1a1;
    padding: 10px 40px; 
    border-radius: 25px;
    width:80% ; 
    background-color: white;  
    margin: 0 auto; 
}

h5 {text-align: justify}
</style>
<form method="POST">
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" align="center">INICIO DE SESION</h4>
      </div>
      <div class="modal-body">
     

<div class="panel-body">
<div class="input-group input-group-lg">
<span class="input-group-addon" id="sizing-addon1"> 
<span class="glyphicon glyphicon-user" aria-hidden="true" style="height: 25px;">
</span>
</span>
<div class="input text">
<input type="text" name="username" id="uss" placeholder="Usuario" style="width:100% ; height:50px; font-size:17px;" aria-describedby="sizing-addon1" required>
</div>
</div>
<br/>
<div class="input-group input-group-lg">
<span class="input-group-addon" id="sizing-addon1">
<span class="glyphicon glyphicon-eye-close" aria-hidden="true" style="height: 25px;">
</span>
</span>
<div class="input password">
<input type="password" name="password" id="psw" placeholder="Contraseña" style="width:100% ; height:50px; font-size:17px;" aria-describedby="sizing-addon1" required>
</div>
</div><br/>
<a href="_recuperar_clave.php">Restablecer la contraseña</a>
</div>



      </div>
      <div class="modal-footer" align="center">
        <button type="submit" name="aceptar" class="btn btn-primary">Aceptar</button>
        <button type="submit" class="btn btn-default" data-dismiss="modal">Cancelar</button>
     
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</form>


<div style="width:100%" align="center">
<div style="width:60% ;" >
<h1 style="color:#942571;" align="center">PATRONATO NACIONAL DE LA INFANCIA</h1>

<?php 
echo '<br>
<a style = "width:100%"  class="btn btn-info" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse">
  Registro de Usuarios sistema de VENTAS-PANI
</a>';
echo '<div class="collapse" id="collapse1">';
echo "<div class = 'well' style='background-color:white;'>";
echo "<h4 align='center'><u>Instrucciones y Recomendaciones</u></h4>";
echo "

<h5 align='justified'>
<ul>
<li><b>
Todo Nuevo usuario deberá solicitar con un documento oficial sus accesos al sistema al área de informática, debera llenar el <a href='pani_usuarios.docx' > <u> formato adjunto </u> </a> y guardarlo como PDF. Luego enviarlo por medio de su correo oficial, o en su defecto puede imprimirlo y enviarlo al área de Informática.
</b></li>
</ul>
<ul>
<li><b>
Luego de la recepción por parte del área informática del documento de solicitud de Usuario. Ud Recibirá a su correo oficial su usuario y contraseña con la cual podra ingresar al sistema.
</b></li>
</ul>
<ul>
<li><b>
 Inmediatamente luego de haber ingresado al sistema este le llevara a la pantalla de perfil de usuario en donde debera cambiar la contraseña aleatoria generada por el sistema por una contraseña personal.
</b></li>
</ul>
<ul>
<li><b>
En el caso de que haya olvidado, extraviado o sospeche que su usuario es manipulado por alguien más, deberá recuperar la contraseña inmediatamente en la opción disponible para esta acción en la pantalla de inicio de sesión, la recuperación de contraseña será enviada a su correo y hará el cambio inmediatamente después de ingresar.
</b></li>
</ul>
</h5>
";
echo "</div>";
echo "</div>";
 ?>
<div style="width:100%" >
  <img src="./imagenes/loteria.jpg" style="opacity:0.2;" width="60%">
</div>

</div>
<?php 
 
 ?>
</div>

<br>
<br>

<?php
if (isset($_POST['aceptar'])) {

$user = $_POST['username'];
$pass = md5($_POST['password']);

$result = mysql_query("SELECT * FROM pani_usuarios WHERE usuario = '$user' AND  password = '$pass' ");

if (mysql_num_rows($result) == 0) {
?>
<script type="text/javascript">
  swal("Error inesperado, por favor vuelva a intentarlo", "", "error");
</script>
<?php

}else{

while ($row = mysql_fetch_array($result)) {
$_SESSION['sesion'] = true;
$_SESSION['nombre'] = $row['nombre_completo'];
$_SESSION['rol'] = $row['roles_usuarios_id'];
$_SESSION['id_usuario'] = $row['id'];
$_SESSION['usuario'] = $row['usuario'];
$_SESSION['estado'] = $row['estados_id'];
$_SESSION['area_id'] = $row['areas_id'];
}


 if ($_SESSION['estado'] == 3){
    header('Location: ./_mi_perfil.php'); 
    }else{
    header('Location: ./index.php'); 
    }

}


}

?>
