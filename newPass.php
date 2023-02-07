<?php
require("./template/header.php");

$u = $_GET['u'];


?>


<br>
<br>
<br>

<form method="post">

<div class="row" style="height: 700px;" >

<div class="col"></div>

<div class="col">
<div class="card">
    <div class="card-header bg-dark text-white">
        <h4 style="text-align: center;">
            Establecimiento de Contraseña
        </h4>
    </div>
    <div class="card-body">

        
            <div class="input-group">
                <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user" ></i></span>
                </div>
                <input type="text" value = '<?php echo $u; ?>' class="form-control" name="username" id="username" readonly>
            </div>

            <hr>

            <div class="input-group">
                <div class="input-group-prepend">
                <span class="input-group-text"><i id="icon1" class="fa fa-key" ></i></span>
                </div>
                <input type="password" class="form-control" name="pass" id="pass" onkeyup="checkPass()" minlength="6" required>
            </div>


            <div class="input-group" style="margin-top: 10px;">
                <div class="input-group-prepend">
                <span class="input-group-text"><i id="icon2" class="fa fa-key" ></i></span>
                </div>
                <input type="password" class="form-control" name="passC" id="passC" onkeyup="checkPass()" minlength="6" required>
            </div>


    </div>

    <div class = 'card-footer' style="align-items: center; text-align: center;" >

        <div class = 'alert alert-info'><i class="fa fa-exclamation-circle"></i>
            Su contraseña debe contener al menor seis (6) caracteres.
        </div>

        <button type="submit" name = 'guardar' id = 'guardar' class="btn btn-primary" disabled>Guardar Nueva Contraseña</button>

    </div>


</div>



</div>

<div class="col"></div>

</div>


</form>



<div style="height:30px;
    margin: 0;
    width:100%;
    position: relative;">

<?php 

require("./template/footer.php");

?>

</div>


<script text="javascript">

function checkPass(){

p1 =  document.getElementById('pass').value;
p2 =  document.getElementById('passC').value;

if (p1.length > 5 && p2.length > 5) {

if (p1 == p2) {

document.getElementById('guardar').disabled = false;
document.getElementById('icon1').style.color  = "darkgreen";
document.getElementById('icon2').style.color  = "darkgreen";


}else{

document.getElementById('guardar').disabled = true;
document.getElementById('icon1').style.color  = "darkred";
document.getElementById('icon2').style.color  = "darkred";

}

}else{

document.getElementById('guardar').disabled = true;
document.getElementById('icon1').style.color  = "darkred";
document.getElementById('icon2').style.color  = "darkred";


}


} 


</script>


<?php 

if (isset($_POST['guardar'])) {
    
    $u = $_POST['username'];
    $p = md5($_POST['pass']);

    if (mysqli_query($conn, "UPDATE pani_usuarios SET password = '$p', estados_id = '1' WHERE usuario = '$u' ") === TRUE ) {
        
        ?>
        <script type="text/javascript">

swal({
title: "",
  text: "contraseña establecida correctamente.",
  type: "success"
})
.then(() => {
    window.location.href = './index.php';
});
                    
        </script>
        <?php
  

    }


}

?>