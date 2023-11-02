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
						window.location.href='./?s=1';
						return false;
					}
                    $("body").attr("session", dados);
                }
            });

        }
    });



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