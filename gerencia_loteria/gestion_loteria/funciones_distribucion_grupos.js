function validar_maximo(cantidad,fila){
c = parseInt(cantidad);
f = fila;
cantidad_maxima = parseInt(document.getElementById('oculto'+f).value);
if (c > cantidad_maxima) {
document.getElementById('cantidad'+f).value = 0;  
document.getElementById('serie_final'+f).value = 0;  
};
}


function agregar_numero(boton,num,s_i,c){
b = boton;
numero = num;
serie_inicial = s_i;
cantidad = c;


span = document.getElementById('boton'+b);
span.style.background = "grey";
span.onclick = '';


tabla = document.getElementById('detalle_venta');


if (document.getElementById('filas').value == '') {
document.getElementById('filas').value = 1;	
}else{
document.getElementById('filas').value = parseInt(document.getElementById('filas').value) + 1;	
}

// Create an empty <tr> element and add it to the 1st position of the table:
var row = tabla.insertRow(1);
// Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
var cell1 = row.insertCell(0);
var cell2 = row.insertCell(1);
var cell3 = row.insertCell(2);
var cell4 = row.insertCell(3);
var cell5 = row.insertCell(4);

filas = document.getElementById('filas').value;

// Add some text to the new cells:
cell1.innerHTML = "<input type = 'hidden' value = '"+cantidad+"' id = 'oculto"+filas+"' ><input type= 'number' class = 'form-control' style='width:90%' id = 'numero"+filas+"' name = 'numero[]'  value = '"+numero+"' required readonly>";
cell2.innerHTML = "<input type = 'number' class = 'form-control' style='width:90%' onkeyup = 'validar_maximo(this.value,"+filas+")' onblur = 'calculo_serie_final("+filas+")'  id = 'cantidad"+filas+"' name = 'cantidad[]' max = '"+cantidad+"' required> ";
cell3.innerHTML = "<input type = 'number' class = 'form-control' style='width:90%' id = 'serie_inicial"+filas+"' name = 'serie_inicial[]' value = '"+serie_inicial+"' required readonly>";
cell4.innerHTML = "<input type = 'number' class = 'form-control' style='width:90%' id = 'serie_final"+filas+"' name = 'serie_final[]' required readonly>";
cell5.innerHTML = '<SPAN style = "width:100%" onclick="eliminar_numero(this,'+ b +','+ numero +','+ serie_inicial +','+ cantidad +')" class="btn btn-danger">-</SPAN>';

}

function eliminar_numero(fila,b,num,serie,cantidad){

document.getElementById("boton"+b).style.background = "#337ab7"; 
document.getElementById("boton"+b).setAttribute('onclick','agregar_numero('+b+','+num+','+serie+','+cantidad+')');

i =  fila.parentNode.parentNode.rowIndex;
document.getElementById("detalle_venta").deleteRow(i);  
calculo_total();

}


function calculo_serie_final(fila){
f = fila;
cantidad = document.getElementById('cantidad'+f).value;

filas = document.getElementById('filas').value;
 
if (cantidad != 0 && cantidad != '') {
serie_inicial = document.getElementById('serie_inicial'+f).value;
document.getElementById('serie_final'+f).value = parseInt(serie_inicial) + parseInt(cantidad) - 1;	
};

calculo_total();

}



function calculo_total(){
i = 1;
cantidad_total = 0;

filas = document.getElementById('filas').value;
while(i <= filas){
if (serie_final = document.getElementById('serie_final'+i)) {

if (serie_final.value != '') {
c = parseInt(serie_final.value);  
}else{
c = 0;  
}

serie_inicial = parseInt(document.getElementById('serie_inicial'+i).value);
num_series = c - serie_inicial  + 1;

if (isNaN(num_series)) {
num_series = 0;  
};

validacion = parseInt(cantidad_total) + parseInt(document.getElementById('cantidad'+i).value );
if (isNaN(validacion)) {
}else{
cantidad_total = parseInt(cantidad_total) + parseInt(document.getElementById('cantidad'+i).value );
}

};

i++;	
}

document.getElementById('total_cantidad').value = cantidad_total;
}