Carregando = (opc = 'flex') => {
    $(".Carregando").css("display",opc);
    // alert(opc);

    $.ajax({
        url:'lib/sessoes.php',
        success:function(dados){
            // console.log(dados);
            $("body").attr("session", dados);

            $.ajax({
                url:'lib/sessoes.php',
                type:"POST",
                data:{
                    dados
                },
                success:function(dados){
                    console.log(dados);
					if(dados == 'error'){
						// window.location.href='./?s=1';
						return false;
					}
                    $("body").attr("session", dados);
                }
            });

        }
    });
}


historico = (opc)=>{
	Carregando();
	console.log(`Local: ${opc.local}`)
	console.log(`destino: ${opc.destino}`)
}


function validarCPF(cpf_v) {
	cpf_v = cpf_v.replace(/[^\d]+/g,'');	
	if(cpf_v == '') return false;	
	// Elimina cpf_vs invalidos conhecidos	
	if (cpf_v.length != 11 || 
		cpf_v == "00000000000" || 
		cpf_v == "11111111111" || 
		cpf_v == "22222222222" || 
		cpf_v == "33333333333" || 
		cpf_v == "44444444444" || 
		cpf_v == "55555555555" || 
		cpf_v == "66666666666" || 
		cpf_v == "77777777777" || 
		cpf_v == "88888888888" || 
		cpf_v == "99999999999")
			return false;		
	// Valida 1o digito	
	add = 0;	
	for (i=0; i < 9; i ++)		
		add += parseInt(cpf_v.charAt(i)) * (10 - i);	
		rev = 11 - (add % 11);	
		if (rev == 10 || rev == 11)		
			rev = 0;	
		if (rev != parseInt(cpf_v.charAt(9)))		
			return false;		
	// Valida 2o digito	
	add = 0;	
	for (i = 0; i < 10; i ++)		
		add += parseInt(cpf_v.charAt(i)) * (11 - i);	
	rev = 11 - (add % 11);	
	if (rev == 10 || rev == 11)	
		rev = 0;	
	if (rev != parseInt(cpf_v.charAt(10)))
		return false;		
	return true;   
}


(function(window) {
    'use strict';

  var noback = {

      //globals
      version: '0.0.1',
      history_api : typeof history.pushState !== 'undefined',

      init:function(){
          window.location.hash = '#no-back';
          noback.configure();
      },

      hasChanged:function(){
          if (window.location.hash == '#no-back' ){
              window.location.hash = '#back';
			//   alert('acao1')
              $.ajax({
                url:"lib/voltar.php",
                dataType:"JSON",
                success:function(dados){
                    var obj = $.parseJSON(dados.dt);
                    console.log(obj.categoria);

                    // let formatar = {
                    //     data: dados.dados.map( (i, v) => {
                    //         return {i:v};
                    //     })
                    // };

                    $.ajax({
                        url:dados.pg,
                        type:"POST",
                        data:JSON.stringify(dados.dt),
                        success:function(retorno){
                            $(`${dados.tg}`).html(retorno);
                        }

                    })
                }
              })
              //mostra mensagem que não pode usar o btn volta do browser
              //MensagemBack();
            //   PageClose();
          }
      },

      checkCompat: function(){
          if(window.addEventListener) {
              window.addEventListener("hashchange", noback.hasChanged, false);
          }else if (window.attachEvent) {
              window.attachEvent("onhashchange", noback.hasChanged);
          }else{
              window.onhashchange = noback.hasChanged;
          }
      },

      configure: function(){
          if ( window.location.hash == '#no-back' ) {
              if ( this.history_api ){
				// alert('acao3')
			  history.pushState(null, '', '#back');
              }else{
			//   alert('acao2')
			  window.location.hash = '#back';
                  //mostra mensagem que não pode usar o btn volta do browser
                  //MensagemBack();
                  //PageClose();
              }
          }
          noback.checkCompat();
          noback.hasChanged();
      }

      };

      // AMD support
      if (typeof define === 'function' && define.amd) {
          define( function() { return noback; } );
      }
      // For CommonJS and CommonJS-like
      else if (typeof module === 'object' && module.exports) {
          module.exports = noback;
      }
      else {
          window.noback = noback;
      }
      noback.init();
  }(window));


  var CopyMemory = function (text) {
    var $txt = $('<textarea />');
    $txt.val(text).css({ width: "1px", height: "1px", position:'fixed', left:-999}).appendTo('body');
    $txt.select();
    if (document.execCommand('copy')) {
        $txt.remove();
    }
};