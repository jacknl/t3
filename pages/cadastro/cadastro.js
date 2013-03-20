$(document).ready(function(){
	$(".conteudo #data_nascimento").datepicker();
	
	selectMod($('.conteudo table #estado'));
	var cidade = selectMod($('.conteudo table #cidade'));
	
	$('.conteudo table #estado .multiselect_one p').click(function(){
		$.ajax({
	        type: "POST",
	        url: 'getCidade.php',
	        dataType: 'json',
	        data: {'estado': $('input', $(this).parent()).val()},
	    }).done(function(data){
			cidade(data, false, true);
		}).error(function(){ alert('N�o foi poss�vel realiza a opera��o. Verifique a sua conec��o com a internet ou voc� n�o tem permiss�o.'); });
	});
	
});
