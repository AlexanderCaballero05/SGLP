          //alert();

                 function soloLetras(e)
                {
                       key = e.keyCode || e.which;
                       tecla = String.fromCharCode(key).toLowerCase();
                       letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
                       especiales = "8-37-39-46";

                       tecla_especial = false
                       for(var i in especiales)
                       {
                            if(key == especiales[i])
                            {
                                tecla_especial = true;
                                break;
                            }
                        }

                        if(letras.indexOf(tecla)==-1 && !tecla_especial)
                        {
                            return false;
                        }
                }

                function justNumbers(e)
                  {
                        var keynum = window.event ? window.event.keyCode : e.which;
                        if ((keynum == 8) || (keynum ==47 ))
                        return true;            
                        return /\d/.test(String.fromCharCode(keynum));
                 }

                 function GetIdRNP()
                 {

                    var id=document.getElementById("txtid").value;
                   $(".div_wait").fadeIn("fast");
                   var urr = '_GetIdRNP.php?id='+id+'&valida='+Math.random();
                
                   
                   $("#getid").load(urr);
                 }
				 
				  function slct_document(sel)
                 {  
                      if (sel=='id')
                         {
                           document.getElementById('txtid').value="";
                            document.getElementById('txtid').readOnly=false;
                            $("#txtid").mask("9999-9999-99999", { placeholder: "____-____-____ " });
                         }
                         else
                         {      
                                  document.getElementById('txtid').value="";
                                  document.getElementById('txtnombre').value="";
                                  $("#txtid").unmask("mask");
                                  document.getElementById('txtid').readOnly=false;
                                  document.getElementById('txtid').maxLength=25; 
                         }
                 }

               

                function eliminar_fila(elemento, fila, id)
                {    


                    f =  elemento.parentNode.parentNode.rowIndex;
                    var valor_acumulado_neto=parseFloat(document.getElementById ('textacumulado_neto').value);      
                    var valor_restar=document.getElementsByName('rbo_valor_pagar[]')[f-1].value;
                    document.getElementById ('textacumulado_neto').value=parseFloat(valor_acumulado_neto)-parseFloat(valor_restar);

                    var valor_acumulado_total=parseFloat(document.getElementById ('textacumulado_total').value);
                    var valor_restar_total=document.getElementsByName('rbo_monto_total[]')[f-1].value;
                    document.getElementById('textacumulado_total').value=parseFloat(valor_acumulado_total)-parseFloat(valor_restar_total);
                     
                    var valor_acumulado_impto=parseFloat(document.getElementById ('textacumulado_impto').value);        
                    var valor_restar_impto=document.getElementsByName('rbo_impto[]')[f-1].value;
                    document.getElementById ('textacumulado_impto').value=parseFloat(valor_acumulado_impto)-parseFloat(valor_restar_impto); 
                     
                     tabla = document.getElementById('tbl_recibos');
                     filas = tabla.rows.length;
                     tabla.deleteRow(f);
                     filas_final = tabla.rows.length;
                     i = 1;

                      
                     
                    document.getElementById('lbl_tota_total').innerText  =  "L. "+document.getElementById('textacumulado_total').value+".00"; 
                     document.getElementById('lbl_tota_impto').innerText  =  document.getElementById('textacumulado_impto').value+".00"; 
                    document.getElementById('lbl_tota_pagar').innerText  =  document.getElementById('textacumulado_neto').value+".00"; 

                    document.getElementById('textacumulado_total').value
                }
 


                
function agregar_info_recibo()
{ 

 var array_tabla = document.getElementsByName("rbo_key[]").length;   
 var _key=document.getElementById ('_key').value;
 var _detalle_venta=document.getElementById ('_detalle_venta').value; 
 var _sorteo=document.getElementById ('_sorteo').value;
 var _registro=document.getElementById ('_registro').value;
 var _numero=document.getElementById ('_numero').value;
 var _monto_total=document.getElementById ('_monto_total').value;
 var _impto=document.getElementById ('_impto').value;   
 var _neto=document.getElementById ('_neto').value; 
 var _decimos=document.getElementById ('_decimos').value; 
 var _dec_disp=document.getElementById ('_decimos_disponibles').value; 
 var _tipo_premio=document.getElementById ('_tipo_premio').value; 

 

     if (parseInt(_decimos)>parseInt(_dec_disp)) 
     {
             swal("Error...!", "No puede ingresar mas decimos de los disponibles!", "error");
     }  
     else if (_decimos == '')
     {
     swal("Error...!", "Debe llenar los decimos !", "error");
      } 
     else
    {
     //var _monto_total= (parseInt(_monto_total)/1)*parseInt(_decimos);
     var _monto_total= (parseFloat(_monto_total)/1);
     var _impto= (parseFloat(_impto)/1);
     var _neto= (parseFloat(_neto)/1); 
     
    var j=0;
    var bloqueo=0;
    while (j < array_tabla  ) 
     {      
        var valor_input=document.getElementsByName("rbo_key[]")[j].value;
        //alert(valor_input);
        if (_key === valor_input) 
        {   
          swal("Error...!", "Has intentado ingresar un numero ya existente!", "error"); 
          document.getElementById("add_recibo").style.display='none';
          document.getElementById("add_alert").style.display='none';
          var bloqueo=1;             
        }
        j++;
     }
        if (bloqueo==0)
          {  
             tabla=document.getElementById ('tbl_recibos');
             var new_fila=parseInt(array_tabla)+parseInt(1);
             var row = tabla.insertRow(new_fila);
             var cell0 =row.insertCell(0);
             var cell1 =row.insertCell(1);
             var cell2 =row.insertCell(2);
             var cell3 =row.insertCell(3);
             var cell4 =row.insertCell(4);
             var cell5 =row.insertCell(5);
             var cell6 =row.insertCell(6);
             var cell7 =row.insertCell(7);
            
                     
             cell0.innerHTML=_sorteo+"<input type='hidden' id='rbo_key' class='form-control'  name='rbo_key[]' onclick='alert(this.name)'  value='"+_key+"'><input type='hidden' id='rbo_sorteo'  name='rbo_sorteo[]' value='"+_sorteo+"'  ><input type='hidden' id='rbo_detalle_venta'  name='rbo_detalle_venta[]' value='"+_detalle_venta+"'  ><input type='hidden' id='rbo_tipo_premio'  name='rbo_tipo_premio[]' value='"+_tipo_premio+"'  >";
             cell1.innerHTML=_registro+"<input type='hidden' class='form-control' id='rbo_registro' name='rbo_registro[]' value='"+_registro+"' >";
             cell2.innerHTML=_numero+"<input type='hidden' class='form-control' id='rbo_numero' name='rbo_numero[]' value='"+_numero+"'>";
             cell3.innerHTML=_decimos+"<input type='hidden' class='form-control' id='rbo_decimos' name='rbo_decimos[]' value='"+_decimos+"'>";
             cell4.innerHTML="L. "+_monto_total+"<input type='hidden' class='form-control' id='rbo_monto_total' name='rbo_monto_total[]' value='"+_monto_total+"' >";
             cell5.innerHTML="L. "+"-"+_impto+"<input type='hidden' class='form-control' id='rbo_impto' name='rbo_impto[]' value='"+_impto+"'>";
             cell6.innerHTML="L. "+_neto+"<input type='hidden' class='form-control' id='rbo_valor_pagar' name='rbo_valor_pagar[]' value='"+_neto+"'>";
             cell7.innerHTML="<span type='button' class='btn btn-danger' id='btn_eliminar' onclick='eliminar_fila(this, this.id)'> X  </span>";

            var y=0;
            while (y <= array_tabla  ) 
            {   
             var array_valor_total=document.getElementsByName("rbo_monto_total[]")[y].value;
             var valor_acumulado_total=parseFloat(document.getElementById ('textacumulado_total').value)+parseFloat(array_valor_total);

             var array_valor_impto=document.getElementsByName("rbo_impto[]")[y].value;
             var valor_acumulado_impto=parseFloat(document.getElementById ('textacumulado_impto').value)+parseFloat(array_valor_impto);

             var array_valor_pagar=document.getElementsByName("rbo_valor_pagar[]")[y].value;
             var valor_acumulado_neto=parseFloat(document.getElementById ('textacumulado_neto').value)+parseFloat(array_valor_pagar);
             y++;
            }

            document.getElementById('textacumulado_total').value=valor_acumulado_total; 
            document.getElementById('textacumulado_impto').value=valor_acumulado_impto;     
            document.getElementById('textacumulado_neto').value=valor_acumulado_neto;
            document.getElementById("add_recibo").style.display='none';
           // document.getElementById("add_alert").style.display='none';           
         }
     
    document.getElementById('lbl_tota_total').innerHTML =  document.getElementById('textacumulado_total').value+""; 
    document.getElementById('lbl_tota_impto').innerHTML  =  document.getElementById('textacumulado_impto').value+""; 
    document.getElementById('lbl_tota_pagar').innerHTML  =  document.getElementById('textacumulado_neto').value+""; 

    }  
}

function valida()
{	
  if (document.getElementById ('textacumulado_total').value==="0") 
    {
      swal('Debe adjuntar pagos al recibo');  
    }
    else
    {
	  document.getElementById('savedata').click();
    } 
}


 
   



 