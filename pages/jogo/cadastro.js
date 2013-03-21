var t;
$(document).ready(function(){
	//abilita o calendario
	$(".conteudo #data_lancamento").datepicker();
	//abilita as opcoes
	selectMod($('.conteudo table tr'));
	//quando uma opcao da plataforma for marcada ou desmarcada, executa
	$('#plataforma .multiselect label').change(function(){
		mostraOpcoesPc(this);
	});

});

function mostraOpcoesPc(tag){//recebe como parametro <input type="checkbox" id="plataforma1" value="1"> NOME DA PLATAFORMA MARCADA
	var tmp = $(tag).html().split('Computador');
	//se o campo 'Computador' for marcado ou desmarcado
	if(tmp.length == 2){
		//abilita os campos para a opcao 'Computador' que sao:
		//requisitos minimos, requisitos recomendados e sistema operacional
		if($('input', tag).attr('checked') == 'checked'){
			$('.conteudo table .pc').each(function(){
				$(this).fadeIn();
			});
		}
		//desabilita os campos para a opcao 'Computador'
		else{
			$('.conteudo table .pc').each(function(){
				$(this).fadeOut();
			});
		}
	}
}

function opcoesPc(){
	//para cada opcao de plataforma, chama mostraOpcoesPc
	$('#plataforma .multiselect label').each(function(){
		mostraOpcoesPc(this);
	});
}