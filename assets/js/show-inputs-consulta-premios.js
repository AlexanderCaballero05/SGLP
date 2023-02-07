
$('#select-tipo-sorteo').on('change', function(){

document.getElementById("sorteo-premio").value = "";

var div = document.getElementById('show-cambio-inputs');
if(this.value == 1){

div.innerHTML = '<div class="input-group"  style = "margin-top:8px"><div class="input-group-prepend"><span style="width: 80px;" class="input-group-text" >Billete</span></div><input type = "number" class = "form-control" id="billete-premio" min = "0" max = "99999" ></div>';    

}else{

div.innerHTML = '<div class="input-group"  style = "margin-top:8px"><div class="input-group-prepend"><span style="width: 80px;" class="input-group-text" >Numero</span></div><input type = "number" class = "form-control" id = "numero-premio" min = "00" max = "99" ></div><div class="input-group"  style = "margin-top:8px"><div class="input-group-prepend"><span style="width: 80px;" class="input-group-text" >Serie</span></div><input type = "number" class = "form-control" id = "serie-premio" min = "00000" max = "99999" ></div>';
    
}
    
$('#respuesta-consulta-premio').html('');
    
});


