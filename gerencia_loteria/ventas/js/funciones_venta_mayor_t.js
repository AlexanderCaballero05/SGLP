function cargar_info_vendedor(vendedor){


if (vendedor == 0) {
jQuery(function($){
$("#identidad").mask("9999-9999-99999", { placeholder: "____-____-_____" });
});
}else{
$("#identidad").unmask();
}


if (vendedor == 0) {
document.getElementById('nombre').value = '';
document.getElementById('asociacion').value = 'C';
document.getElementById('codigo').value = '';
document.getElementById('identidad').value = '';

document.getElementById('identidad').readOnly = false;
document.getElementById('nombre').readOnly = false;
document.getElementById('asociacion').disabled = false;
document.getElementById('codigo').readOnly = false;


document.getElementById("respuesta_credito").innerHTML = "";
document.getElementById('radio_credito').disabled = true;
document.getElementById('radio_contado').checked = true;

}else{

document.getElementById('identidad').readOnly = true;
document.getElementById('nombre').readOnly = true;
document.getElementById('asociacion').disabled = true;
document.getElementById('codigo').readOnly = true;

var str = vendedor;
var parametros = str.split("/");
asociacion = parametros[0];
identidad = parametros[1];
nombre = parametros[2];
codigo = parametros[3];

token = Math.random();

consulta = 'venta_validar_credito.php?id='+identidad+"&product=1&token="+token;
$("#respuesta_credito").load(consulta);

document.getElementById('identidad').value  = identidad;
document.getElementById('nombre').value   = nombre;
document.getElementById('asociacion').value = asociacion;
document.getElementById('codigo').value   = codigo;

}

}





function cargar_factura(factura) {
document.getElementById('cod_fac').value = factura;
}




function confirmar(){

select_comprador  = parseInt(document.getElementById('s_identidad').value);
identidad_comprador = document.getElementById('identidad').value;
nombre_comprador  = document.getElementById('nombre').value;
radio_credito     = document.getElementById("radio_credito");

total_neto = parseFloat(document.getElementById('neto').value);

if (document.getElementById("total_cantidad").value == 0  ) {

swal("", "Aun no ha seleccionado loteria para la venta.", "error");

}else{

if (identidad_comprador.trim() == '') {
swal("", "Debe ingresar los datos del comprador.", "error");
}else{


if (radio_credito.checked === true) {
credito_disponible  = parseFloat(document.getElementById("input_credito_disponible").value);

if (credito_disponible < total_neto) {

swal("", "El comprador no cuenta con suficiente credito para realizar la compra.", "error");

}else{

swal({   title: "",   text: "¿Esta seguro de realizar esta venta?",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "SI",   cancelButtonText: "NO",   closeOnConfirm: false,   closeOnCancel: false }, function(isConfirm){   if (isConfirm) {
  document.getElementById('guardar').click();
} else {
  swal("", "Venta no aprobada", "error");
} });

}

}else{


swal({   title: "",   text: "¿Esta seguro de realizar esta venta?",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "SI",   cancelButtonText: "NO",   closeOnConfirm: false,   closeOnCancel: false }, function(isConfirm){   if (isConfirm) {
  document.getElementById('guardar').click();
} else {
  swal("", "Venta no aprobada", "error");
} });


}

}

}

}



function agregar_billete(billete){

b = billete;
span = document.getElementById(b);
span.style.background = "grey";
span.onclick = '';

precio = document.getElementById('precio').value;

tabla = document.getElementById('detalle_venta');

if (document.getElementById('filas').value == '') {
document.getElementById('filas').value = 1;
}else{
document.getElementById('filas').value = parseInt(document.getElementById('filas').value) + 1;
}
filas = document.getElementById('filas').value;


   // Create an empty <tr> element and add it to the 1st position of the table:
var row = tabla.insertRow(1);
   // Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
var cell1 = row.insertCell(0);
var cell2 = row.insertCell(1);
var cell3 = row.insertCell(2);

// Add some text to the new cells:
cell1.innerHTML = "<input type= 'text' style='width:90%' class = 'form-control' id = 'billete"+filas+"' value = '"+b+"' name = 'billete[]' readonly> ";
cell2.innerHTML = "<input type= 'text' style='width:90%' class = 'form-control' id = 'total"+filas+"' value = '"+precio+".00' name = 'total[]' readonly>";
cell3.innerHTML = '<SPAN onclick="eliminar_billete(this, '+ b +' )" class="btn btn-danger">-</SPAN>';

descuento = document.getElementById('descuento').value;

document.getElementById('total_cantidad').value = parseFloat(document.getElementById('total_cantidad').value) + 1;
document.getElementById('total_pagar').value = parseFloat(document.getElementById('total_pagar').value) + parseFloat(precio);
document.getElementById('descuento_total').value = parseFloat(document.getElementById('descuento_total').value) + parseFloat(descuento);
document.getElementById('neto').value = parseFloat(document.getElementById('total_pagar').value) - parseFloat(document.getElementById('descuento_total').value);

}



function eliminar_billete(elemento, b){

tabla = document.getElementById('detalle_venta');
filas = tabla.rows.length;
f = filas - 1;

f =  elemento.parentNode.parentNode.rowIndex;

span = document.getElementById(b);
span.style.background = "#337ab7";

span.setAttribute('onclick','agregar_billete('+ b +')');
document.getElementById("detalle_venta").deleteRow(f);
precio = document.getElementById('precio').value;

document.getElementById('total_cantidad').value = parseFloat(document.getElementById('total_cantidad').value) - 1;
document.getElementById('total_pagar').value = parseFloat(document.getElementById('total_pagar').value) - parseFloat(precio);

descuento = document.getElementById('descuento').value;

document.getElementById('descuento_total').value = parseFloat(document.getElementById('descuento_total').value) - parseFloat(descuento);
document.getElementById('neto').value = parseFloat(document.getElementById('total_pagar').value) - parseFloat(document.getElementById('descuento_total').value);

}
