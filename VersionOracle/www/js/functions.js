/*
 *
 * MUSHOQ JS AND JQUERY FUNCTIONS
 * 
 */


//VARIABLES GLOBALES

var divDestino;
var urlDestino;
var formDestino;

function sendPageAjax(form,destination,div){

   
      var param = $("#"+ form).serialize();
      $("#"+ div).html('<div  id="loading" style="margin:0 auto;"><img src="images/ajax-loader.gif"></div>');
      $.ajax({
      type: "POST",
      url: destination,
      data: param,
      success: function(newPage) {
       //alert(newPage);
       $("#"+ div).load(newPage,{data: 0},function(){
            //alert('se supone que ya hice');
            });
      }
      });
}

function loadPageAjax(form,destination,div){
      var param = $("#"+ form).serialize();
      $("#"+ div).html('<div  id="loading"><img src="images/ajax-loader.gif"></div>');
      $.ajax({
      type: "POST",
      url: destination,
      data: param,
      success: function(newPage) {
       //alert(newPage);
       $("#"+ div).html(newPage)
      }
      });
}

function sendPage(form,destination,div){

 //alert(form);
 if(form != 'null'){
     var param = $("#"+ form).serialize();

     //serializar a objeto
     var campos = param.split("&");
     var data = "{";
     for (var i=0;i<campos.length;i++){
     temp = campos[i].split("=");
     data= data + temp[0]+': "'+temp[1]+'"';
     if(i<campos.length-1){
     data = data + ",";
     }
     }
     data = data + "}";
     //
     var parametros = eval("("+data+")");
 }else{
     var parametros = eval("({data: 0})");
 }
 $("#"+ div).addClass('msgInfo');
 $("#"+ div).html('<div  id="loading" style="margin:0 auto;"><img src="images/ajax-loader.gif"></div>');
 $("#"+ div).removeClass('msgInfo');
 
 $("#"+ div).load(destination,parametros,function(){
 //alert('se supone que ya hice');
 });
}

function validaInputs(tipo){
    var llenado = true;

    $("input[required='"+tipo+"']").each(function(i){
        
        
		if($(this).val()== '')
                    llenado = false;
    });

    return llenado;
}


function validarPonderacion(){
    var total = 0;

    $("input[type='text']").each(function(i){
		t = parseFloat($(this).val());
                total = total + t;
                  
    });

    return total;
}


function setValueToAllInputs(valor,tipo){
  
    $("input[type='"+tipo+"']").each(function(i){
		$(this).val(valor);
    });

   
}

function validateDecimalDigits(valor){
    var length = valor.length;
    var decimales = 0;
    
    for(i=0;i<length;i++){
        car = valor.substr(i,1);
        
        if(car == '.'){
            //alert('punto');
            decimales = decimales + 1;
            //alert(decimales);
        }
        
        if(decimales > 2){
            alert('Solo se permiten dos decimales.');
        }
    }

}


function validaEnter(e,form){
  
}
