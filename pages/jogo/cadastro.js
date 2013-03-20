var t;
$(document).ready(function(){
	$(".conteudo #data_lancamento").datepicker();
	t = selectMod($('.conteudo table tr'));
	
	$('#plataforma .multiselect label').change(function(){
		mostraOpcoesPc(this);
	});

});

function mostraOpcoesPc(tag){
	var tmp = $(tag).html().split('Computador');

	if(tmp.length == 2){
		if($('input', tag).attr('checked') == 'checked'){
			$('.conteudo table .pc').each(function(){
				$(this).fadeIn();
			});
		}
		else{
			$('.conteudo table .pc').each(function(){
				$(this).fadeOut();
			});
		}
		return true;
	}
	return false;
}

function opcoesPc(){
	$('#plataforma .multiselect label').each(function(){
		mostraOpcoesPc(this);
	});
}
